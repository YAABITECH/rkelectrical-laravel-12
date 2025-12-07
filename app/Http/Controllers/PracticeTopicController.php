<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PracticeSubject;
use App\Models\PracticeTopic;
use App\Models\Practice;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Arr;

class PracticeTopicController extends Controller
{
    use FileUploadTrait;
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sort' => 'nullable',
            'page' => 'nullable|integer',
            'perpage' => 'nullable|integer|max:500',
            'subject' => 'nullable|exists:practice_subjects,id',
            'name' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator->errors());
        }

        $perPage = $request->input('perpage', 5);
        $page = (int) $request->input('page', 1);
        $subject = $request->input('subject') ?? null;
        $filterQuery = PracticeTopic::query();

        if ($request->filled('name')) {
            $filterQuery->where('name', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->filled('status')) {
            $filterQuery->where('status', $request->input('status'));
        }
        if ($request->filled('subject')) {
            $filterQuery->where('subject_id', $request->input('subject'));
        }

        $sort = $request->input('sort', 'default');
        if ($sort == 'default') {
            $filterQuery->orderBy('priority', 'asc');
        } elseif ($sort == 'newest') {
            $filterQuery->orderBy('created_at', 'desc');
            $filterQuery->orderBy('id', 'desc');
        } elseif ($sort == 'oldest') {
            $filterQuery->orderBy('created_at', 'asc');
            $filterQuery->orderBy('id', 'asc');
        } elseif ($sort == 'ascending') {
            $filterQuery->orderBy('name', 'asc');
        } elseif ($sort == 'descending') {
            $filterQuery->orderBy('name', 'desc');
        } elseif ($sort == 'status') {
            $filterQuery->orderBy('status', 'asc');
        }

        if ($request->filled('perpage')) {
            $perPage = $request->input('perpage');
        }

        $totcount = $filterQuery->count();
        if($totcount>0)
        {
            $totpage = ceil($totcount / $perPage);
            if ($page > $totpage) {
                return redirect()->to(url()->current() . '?' . http_build_query(array_merge($request->all(), ['page' => $totpage])));
            } else if ($page < 1) {
                return redirect()->to(url()->current() . '?' . http_build_query(array_merge($request->all(), ['page' => 1])));
            }
        }

        $topics = $filterQuery->paginate($perPage);
        $totalCount = $topics->total();
        $totalPages = $topics->lastPage();
        $count = $topics->count();

        $subjects = PracticeSubject::all();

        return view('admin.practice.topic.index', compact('topics', 'totalCount', 'totalPages', 'count', 'page', 'subjects','subject'));
    }

    public function create(Request $request)
    {
        $subjectId = $request->input('subject') ?? null;
        $subjects = PracticeSubject::orderBy('priority', 'ASC')->get();
        return view('admin.practice.topic.create',compact('subjects','subjectId'));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'ulink' => 'required|max:255|regex:/^[a-z0-9_\-]+$/|unique:'.(new PracticeTopic)->getTable(),
            'mtit' => 'required',
            'mdes' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:30720',
            'ogimage' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:30720',
            'status' => ['required', Rule::in(['hidden', 'waiting', 'public'])],
            'launch_datetime' => [
                Rule::requiredIf($request->input('status') == 'waiting'),
                'nullable',
            ],
           'subject' => 'required|exists:'.(new PracticeSubject)->getTable(). ',id',
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator->errors());
        }
        $storeData = $request->only(['name','ulink','mtit','mdes','status']);
        $ulink=$request->input('ulink');
        $formattedLaunchDatetime = null;
        if($request->input('status') == 'waiting')
        {
            $launchDatetime = $request->input('launch_datetime');
            if ($launchDatetime) {
                $carbonInstance = Carbon::createFromFormat('m/d/Y g:i A', $launchDatetime);
                $formattedLaunchDatetime = $carbonInstance->format('Y-m-d H:i:s');
            }
        }
        $storeData['launch_datetime'] = $formattedLaunchDatetime;

        $maxPriority = PracticeTopic::max('priority');
        $priority = $maxPriority + 1;
        $storeData['priority'] = $priority;
        if($request->hasFile('image'))
        {
            $metadata = [
                'prefix' => '',
                'sizes' => [
                    ['width' => 900, 'path' => ''],
                    ['width' => 300, 'path' => 'thumb/']
                ],
            ];
            $fileName = $this->handleFileUpload($request, 'image', 'image/practice/topic/', 'image', $metadata);
            if ($fileName) {
                $storeData['image'] = $fileName;
            } else {
                return back()->with('error', 'File upload error')->withInput();
            }
        }
        if($request->hasFile('ogimage'))
        {
            $metadata = [
                'prefix' => '',
                'sizes' => [
                    ['width' => 1200, 'path' => ''],
                ],
            ];
            $fileName = $this->handleFileUpload($request, 'ogimage', 'image/practice/topic/ogimage/', 'image', $metadata);
            if ($fileName) {
                $storeData['ogimage'] = $fileName;
            } else {
                return back()->with('error', 'File upload error')->withInput();
            }
        }
        $storeData['subject_id'] = $request->input('subject');

        $maxPriority = PracticeTopic::max('priority');
        $priority = $maxPriority + 1;
        $storeData['priority'] = $priority;
        $topic = PracticeTopic::create($storeData);

        if($topic)
        {
            return redirect(route('admin.practice.topic.index'))->with('success', 'Topic has been created!');
        } else
        {
            return back()->with('error', 'Error creating topic, please try again or contact our support team')->withInput();
        }
    }
    public function show($id)
    {
    }
    public function edit($id)
    {
        $topic=PracticeTopic::find($id);
        if ($topic === null) {
            return redirect(route('admin.practice.topic.index'))->with('error', 'The topic does not exist');
        }
        $subjects = PracticeSubject::orderBy('priority','asc')->get();

        return view('admin.practice.topic.edit', compact('topic', 'subjects'));
    }
    public function update(Request $request, $id)
    {
        $topic=PracticeTopic::find($id);
        if ($topic === null) {
            return redirect(route('admin.practice.topic.index'))->with('error', 'The topic does not exist');
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'ulink' => 'required|max:255|regex:/^[a-z0-9_\-]+$/|unique:'.(new PracticeTopic)->getTable().',ulink,'.$id,
            'mtit' => 'required',
            'mdes' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:30720',
            'ogimage' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:30720',
            'status' => ['required', Rule::in(['hidden', 'waiting', 'public'])],
            'launch_datetime' => [
                Rule::requiredIf($request->input('status') == 'waiting'),
            ],
            'subject' => 'required|exists:'.(new PracticeSubject)->getTable(). ',id',
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator->errors());
        }
        $updateData = $request->only(['name','ulink','mtit','mdes','status']);
        $ulink=$request->input('ulink');
        $formattedLaunchDatetime = null;
        if($request->input('status') == 'waiting')
        {
            $launchDatetime = $request->input('launch_datetime');
            if ($launchDatetime) {
                $carbonInstance = Carbon::createFromFormat('m/d/Y g:i A', $launchDatetime);
                $formattedLaunchDatetime = $carbonInstance->format('Y-m-d H:i:s');
            }
        }
        $updateData['launch_datetime'] = $formattedLaunchDatetime;
        if($request->hasFile('image'))
        {
            $oldname=$topic->image;
            $metadata = [
                'prefix' => '',
                'sizes' => [
                    ['width' => 900, 'path' => ''],
                    ['width' => 100, 'path' => 'thumb/']
                ],
                'deletefile' => $oldname,
            ];
            $fileName = $this->handleFileUpload($request, 'image', 'image/practice/topic/', 'image', $metadata);
            if ($fileName) {
                $updateData['image'] = $fileName;
            } else {
                return back()->with('error', 'File upload error')->withInput();
            }
        }
        if($request->hasFile('ogimage'))
        {
            $oldname=$topic->ogimage;
            $metadata = [
                'prefix' => '',
                'sizes' => [
                    ['width' => 1200, 'path' => ''],
                ],
                'deletefile' => $oldname,
            ];
            $fileName = $this->handleFileUpload($request, 'ogimage', 'image/practice/topic/ogimage/', 'image', $metadata);
            if ($fileName) {
                $updateData['ogimage'] = $fileName;
            } else {
                return back()->with('error', 'File upload error')->withInput();
            }
        }
        $updateData['subject_id'] = $request->input('subject');
        $topic = PracticeTopic::whereId($id)->update($updateData);
        if($topic)
        {
            return redirect(route('admin.practice.topic.index'))->with('success', 'Topic has been updated!');
        } else
        {
            return back()->with('error', 'Error updating topic, please try again or contact our support team')->withInput();
        }
    }
    public function destroy(Request $request, $id)
    {
        $topic = PracticeTopic::find($id);
        if($topic) {
            $topicid=$topic->id;
            $topic->delete();
            $previousUrl = url()->previous();

            $oldname=$topic->image;
            $oldogname=$topic->ogimage;
            $oldimage=public_path('image/practice/topic/'.$oldname);
            $oldthumb=public_path('image/practice/topic/thumb/'.$oldname);
            $oldog=public_path('image/practice/topic/ogimage/'.$oldogname);
            if(!empty($oldname) && file_exists($oldimage))
            {
                unlink($oldimage);
            }
            if(!empty($oldname) && file_exists($oldthumb))
            {
                unlink($oldthumb);
            }
            if(!empty($oldogname) && file_exists($oldog))
            {
                unlink($oldog);
            }
            if (Str::contains($previousUrl, route('admin.practice.topic.edit', $topicid))) {
                return redirect()->route('admin.practice.topic.index')->with('success', 'Topic has been deleted');
            } else {
                return redirect()->back()->withInput($request->all())->with('success', 'Topic has been deleted');
            }
        } else {
            return redirect(route('admin.practice.topic.index'))->with('error', 'The topic does not exist');
        }
    }
    public function arrange()
    {
        $topic = PracticeTopic::orderBy('priority', 'ASC')->get();
        return view('admin.practice.topic.arrange', compact('topic'));
    }
    public function updatePriority(Request $request)
    {
        if ($request->has('dataArray')) {
            $dataArray = $request->input('dataArray');
            $dataArray = explode(",", $dataArray);
            foreach ($dataArray as $i => $data_id) {
                $priority = $i + 1;
                PracticeTopic::where('id', $data_id)->update(['priority' => $priority]);
            }
            return response('OK', 200);
        }
    }
}
