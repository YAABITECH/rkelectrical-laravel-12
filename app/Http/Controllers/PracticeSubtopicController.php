<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PracticeSubject;
use App\Models\PracticeTopic;
use App\Models\PracticeSubtopic;
use App\Models\PracticeQuestion;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class PracticeSubtopicController extends Controller
{
    use FileUploadTrait;
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sort' => 'nullable',
            'page' => 'nullable|integer',
            'perpage' => 'nullable|integer|max:500',
            'subject' => 'nullable|exists:practice_subjects,id',
            'topic' => 'nullable|exists:practice_topics,id',
            'name' => 'nullable|string|max:255',
            'date_from' => 'nullable|date_format:d-m-Y',
            'date_to' => 'nullable|date_format:d-m-Y|after_or_equal:date_from',
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator->errors());
        }

        $perPage = $request->input('perpage', 10);
        $page = (int) $request->input('page', 1);
        $subject = $request->input('subject') ?? null;
        $topic = $request->input('topic') ?? null;
        $filterQuery = PracticeSubtopic::query();

        if ($request->filled('name')) {
            $filterQuery->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->filled('subject')) {
            $filterQuery->where('subject_id', $request->input('subject'));
        }
        if ($request->filled('topic')) {
            $filterQuery->where('topic_id', $request->input('topic'));
        }


        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        if ($dateFrom && $dateTo) {
            $filterQuery->whereBetween('launch_datetime', [
                Carbon::createFromFormat('d-m-Y', $dateFrom)->startOfDay(),
                Carbon::createFromFormat('d-m-Y', $dateTo)->endOfDay(),
            ]);
        } elseif ($dateFrom) {
            $filterQuery->where('launch_datetime', '>=', Carbon::createFromFormat('d-m-Y', $dateFrom)->startOfDay());
        } elseif ($dateTo) {
            $filterQuery->where('launch_datetime', '<=', Carbon::createFromFormat('d-m-Y', $dateTo)->endOfDay());
        }

        $sort = $request->input('sort', 'default');
        if ($sort == 'newest') {
            $filterQuery->orderBy('created_at', 'desc');
        } elseif ($sort == 'oldest') {
            $filterQuery->orderBy('created_at', 'asc');
        }
        $filterQuery->orderBy('priority', 'asc');

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


        $subtopics = $filterQuery->paginate($perPage);
        $totalCount = $subtopics->total();
        $totalPages = $subtopics->lastPage();
        $count = $subtopics->count();
        // Get the list of subjects for filtering
        $subjects = PracticeSubject::all();
        $topics = PracticeTopic::all();
        $questionPriority = PracticeQuestion::distinct()->pluck('priority');
        return view('admin.practice.subtopic.index', compact('subtopics','topics', 'totalCount', 'totalPages', 'count', 'page', 'subjects','questionPriority','subject','topic'));
    }

    public function create(Request $request)
    {
        $subjectId = $request->input('subject') ?? null;
        $topicId = $request->input('topic') ?? null;
        $subjects = PracticeSubject::orderBy('priority', 'ASC')->get();
        $topics = PracticeTopic::orderBy('priority', 'ASC')->get();
        return view('admin.practice.subtopic.create',compact('subjects','topics','subjectId','topicId'));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'ulink' => 'required|max:255|regex:/^[a-z0-9_\-]+$/|unique:'.(new PracticeSubtopic)->getTable(),
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
            'topic' => 'required|exists:'.(new PracticeTopic)->getTable(). ',id',
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
        $maxPriority = PracticeSubtopic::max('priority');
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
            $fileName = $this->handleFileUpload($request, 'image', 'image/practice/subtopic/', 'image', $metadata);
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
            $fileName = $this->handleFileUpload($request, 'ogimage', 'image/practice/subtopic/ogimage/', 'image', $metadata);
            if ($fileName) {
                $storeData['ogimage'] = $fileName;
            } else {
                return back()->with('error', 'File upload error')->withInput();
            }
        }
        $storeData['subject_id'] = $request->input('subject');
        $storeData['topic_id'] = $request->input('topic');

        $maxPriority = PracticeSubtopic::max('priority');
        $priority = $maxPriority + 1;
        $storeData['priority'] = $priority;

        $subtopic = PracticeSubtopic::create($storeData);
        if($subtopic)
        {
            return redirect(route('admin.practice.subtopic.index'))->with('success', 'Subtopic has been created!');
        } else
        {
            return back()->with('error', 'Error creating subtopic, please try again or contact our support team')->withInput();
        }
    }
    public function show($id)
    {
    }
    public function edit($id)
    {
        $subtopic=PracticeSubtopic::find($id);
        if ($subtopic === null) {
            return redirect(route('admin.practice.subtopic.index'))->with('error', 'The subtopic does not exist');
        }
        $subjects = PracticeSubject::orderBy('priority','asc')->get();
        $topics = PracticeTopic::orderBy('priority','asc')->get();

        return view('admin.practice.subtopic.edit', compact('subtopic', 'subjects', 'topics'));
    }
    public function update(Request $request, $id)
    {
        $subtopic=PracticeSubtopic::find($id);
        if ($subtopic === null) {
            return redirect(route('admin.practice.subtopic.index'))->with('error', 'The subtopic does not exist');
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'ulink' => 'required|max:255|regex:/^[a-z0-9_\-]+$/|unique:'.(new PracticeSubtopic)->getTable().',ulink,'.$id,
            'mtit' => 'required',
            'mdes' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:30720',
            'ogimage' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:30720',
            'status' => ['required', Rule::in(['hidden', 'waiting', 'public'])],
            'launch_datetime' => [
                Rule::requiredIf($request->input('status') == 'waiting'),
            ],
            'subject' => 'required|exists:'.(new PracticeSubject)->getTable(). ',id',
            'topic' => 'required|exists:'.(new PracticeTopic)->getTable(). ',id',
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
            $oldname=$subtopic->image;
            $metadata = [
                'prefix' => '',
                'sizes' => [
                    ['width' => 900, 'path' => ''],
                    ['width' => 100, 'path' => 'thumb/']
                ],
                'deletefile' => $oldname,
            ];
            $fileName = $this->handleFileUpload($request, 'image', 'image/practice/subtopic/', 'image', $metadata);
            if ($fileName) {
                $updateData['image'] = $fileName;
            } else {
                return back()->with('error', 'File upload error')->withInput();
            }
        }
        if($request->hasFile('ogimage'))
        {
            $oldname=$subtopic->ogimage;
            $metadata = [
                'prefix' => '',
                'sizes' => [
                    ['width' => 1200, 'path' => ''],
                ],
                'deletefile' => $oldname,
            ];
            $fileName = $this->handleFileUpload($request, 'ogimage', 'image/practice/subtopic/ogimage/', 'image', $metadata);
            if ($fileName) {
                $updateData['ogimage'] = $fileName;
            } else {
                return back()->with('error', 'File upload error')->withInput();
            }
        }

        $updateData['subject_id'] = $request->input('subject');
        $updateData['topic_id'] = $request->input('topic');
        $subtopic = PracticeSubtopic::whereId($id)->update($updateData);
        if($subtopic)
        {
            return redirect(route('admin.practice.subtopic.index'))->with('success', 'Subtopic has been updated!');
        } else
        {
            return back()->with('error', 'Error updating subtopic, please try again or contact our support team')->withInput();
        }
    }
    public function destroy(Request $request, $id)
    {
        $subtopic = PracticeSubtopic::find($id);
        if($subtopic) {
            $subtopicid=$subtopic->id;
            $subtopic->delete();
            $previousUrl = url()->previous();

            $oldname=$subtopic->image;
            $oldogname=$subtopic->ogimage;
            $oldimage=public_path('image/practice/subtopic/'.$oldname);
            $oldthumb=public_path('image/practice/subtopic/thumb/'.$oldname);
            $oldog=public_path('image/practice/subtopic/ogimage/'.$oldogname);
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
            if (Str::contains($previousUrl, route('admin.practice.subtopic.edit', $subtopicid))) {
                return redirect()->route('admin.practice.subtopic.index')->with('success', 'Topic has been deleted');
            } else {
                return redirect()->back()->withInput($request->all())->with('success', 'Subtopic has been deleted');
            }
        } else {
            return redirect(route('admin.practice.subtopic.index'))->with('error', 'The subtopic does not exist');
        }
    }
    public function arrange()
    {
        $subtopic = PracticeSubtopic::orderBy('priority', 'ASC')->get();
        return view('admin.practice.subtopic.arrange', compact('subtopic'));
    }
    public function updatePriority(Request $request)
    {
        if ($request->has('dataArray')) {
            $dataArray = $request->input('dataArray');
            $dataArray = explode(",", $dataArray);
            foreach ($dataArray as $i => $data_id) {
                $priority = $i + 1;
                PracticeSubtopic::where('id', $data_id)->update(['priority' => $priority]);
            }
            return response('OK', 200);
        }
    }
}
