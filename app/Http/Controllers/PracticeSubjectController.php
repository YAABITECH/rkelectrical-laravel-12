<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PracticeSubject;
use App\Models\Practice;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class PracticeSubjectController extends Controller
{
    use FileUploadTrait;
    public function subject_index()
    {
        $subjects = PracticeSubject::where('status', 'public')
        ->orderBy('priority', 'asc')
        ->with(['practiceTopics.practiceSubtopics.practiceQuestions']) // Eager load topics, subtopics, and priorities
        ->get();

        foreach ($subjects as $subject) {
            $firstTopic = $subject->practiceTopics->first();
            $firstSubtopic = $firstTopic ? $firstTopic->practiceSubtopics->first() : null;
            $firstPriority = $firstSubtopic ? $firstSubtopic->priorities->first() : null;

            $subject->firstTopic = $firstTopic;
            $subject->firstSubtopic = $firstSubtopic;
            $subject->firstPriority = $firstPriority;
        }
        dd($subjects);
        return view('practice.subject',compact('subjects'));
    }
    public function index()
    {
        $subjects = PracticeSubject::orderBy('priority', 'ASC')->get();
        return view('admin.practice.subject.index', compact('subjects'));
    }
    public function create()
    {
        return view('admin.practice.subject.create');
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'ulink' => 'required|max:255|regex:/^[a-z0-9_\-]+$/|unique:'.(new PracticeSubject)->getTable(),
            'mtit' => 'required',
            'mdes' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:30720',
            'ogimage' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:30720',
            'status' => ['required', Rule::in(['hidden', 'waiting', 'public'])],
            'launch_datetime' => [
                Rule::requiredIf($request->input('status') == 'waiting'),
                'nullable',
            ],
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
        $maxPriority = PracticeSubject::max('priority');
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
            $fileName = $this->handleFileUpload($request, 'image', 'image/practice/subject/', 'image', $metadata);
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
            $fileName = $this->handleFileUpload($request, 'ogimage', 'image/practice/subject/ogimage/', 'image', $metadata);
            if ($fileName) {
                $storeData['ogimage'] = $fileName;
            } else {
                return back()->with('error', 'File upload error')->withInput();
            }
        }
        $maxPriority = PracticeSubject::max('priority');
        $priority = $maxPriority + 1;
        $storeData['priority'] = $priority;
        $subject = PracticeSubject::create($storeData);
        if($subject)
        {
            return redirect(route('admin.practice.subject.index'))->with('success', 'Subject has been created!');
        } else
        {
            return back()->with('error', 'Error creating subject, please try again or contact our support team')->withInput();
        }
    }
    public function show($id)
    {
    }
    public function edit($id)
    {
        $subject=PracticeSubject::find($id);
        if ($subject === null) {
            return redirect(route('admin.practice.subject.index'))->with('error', 'The subject does not exist');
        }
        return view('admin.practice.subject.edit', compact('subject'));
    }
    public function update(Request $request, $id)
    {
        $subject=PracticeSubject::find($id);
        if ($subject === null) {
            return redirect(route('admin.practice.subject.index'))->with('error', 'The subject does not exist');
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'ulink' => 'required|max:255|regex:/^[a-z0-9_\-]+$/|unique:'.(new PracticeSubject)->getTable().',ulink,'.$id,
            'mtit' => 'required',
            'mdes' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:30720',
            'ogimage' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:30720',
            'status' => ['required', Rule::in(['hidden', 'waiting', 'public'])],
            'launch_datetime' => [
                Rule::requiredIf($request->input('status') == 'waiting'),
            ],
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
            $oldname=$subject->image;
            $metadata = [
                'prefix' => '',
                'sizes' => [
                    ['width' => 900, 'path' => ''],
                    ['width' => 100, 'path' => 'thumb/']
                ],
                'deletefile' => $oldname,
            ];
            $fileName = $this->handleFileUpload($request, 'image', 'image/practice/subject/', 'image', $metadata);
            if ($fileName) {
                $updateData['image'] = $fileName;
            } else {
                return back()->with('error', 'File upload error')->withInput();
            }
        }
        if($request->hasFile('ogimage'))
        {
            $oldname=$subject->ogimage;
            $metadata = [
                'prefix' => '',
                'sizes' => [
                    ['width' => 1200, 'path' => ''],
                ],
                'deletefile' => $oldname,
            ];
            $fileName = $this->handleFileUpload($request, 'ogimage', 'image/practice/subject/ogimage/', 'image', $metadata);
            if ($fileName) {
                $updateData['ogimage'] = $fileName;
            } else {
                return back()->with('error', 'File upload error')->withInput();
            }
        }
        $subject = PracticeSubject::whereId($id)->update($updateData);
        if($subject)
        {
            return redirect(route('admin.practice.subject.index'))->with('success', 'Subject has been updated!');
        } else
        {
            return back()->with('error', 'Error updating subject, please try again or contact our support team')->withInput();
        }
    }
    public function destroy(Request $request, $id)
    {
        $subject = PracticeSubject::find($id);
        if($subject) {
            $subjectid=$subject->id;
            $subject->delete();
            $previousUrl = url()->previous();

            $oldname=$subject->image;
            $oldogname=$subject->ogimage;
            $oldimage=public_path('image/practice/subject/'.$oldname);
            $oldthumb=public_path('image/practice/subject/thumb/'.$oldname);
            $oldog=public_path('image/practice/subject/ogimage/'.$oldogname);
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
            if (Str::contains($previousUrl, route('admin.practice.subject.edit', $subjectid))) {
                return redirect()->route('admin.practice.subject.index')->with('success', 'Subject has been deleted');
            } else {
                return redirect()->back()->withInput($request->all())->with('success', 'Subject has been deleted');
            }
        } else {
            return redirect(route('admin.practice.subject.index'))->with('error', 'The subject does not exist');
        }
    }
    public function arrange()
    {
        $subject = PracticeSubject::orderBy('priority', 'ASC')->get();
        return view('admin.practice.subject.arrange', compact('subject'));
    }
    public function updatePriority(Request $request)
    {
        if ($request->has('dataArray')) {
            $dataArray = $request->input('dataArray');
            $dataArray = explode(",", $dataArray);
            foreach ($dataArray as $i => $data_id) {
                $priority = $i + 1;
                PracticeSubject::where('id', $data_id)->update(['priority' => $priority]);
            }
            return response('OK', 200);
        }
    }
}
