<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Practice;
use App\Models\PracticeSubject;
use App\Models\PracticeTopic;
use App\Models\PracticeSubtopic;
use App\Models\PracticeQuestion;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Intervention\Image\ImageManagerStatic as Image;

class PracticeQuestionController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.practice.question.create');
    }
    public function create(Request $request)
    {
        return view('admin.practice.question.create');
    }
    public function store(Request $request)
    {
    }
    public function show($id)
    {
    }
    public function edit($id)
    {
    }
    public function update(Request $request, $id)
    {
    }
    public function destroy(Request $request, $id)
    {
    }
    public function manage($subject, $topic = 1, $subtopic = 1, $priority=1)
    {
        $rules = [
            'subject' => 'required|integer|exists:practice_subjects,id',
            'topic' => 'required|integer|exists:practice_topics,id,subject_id,' . $subject,
            'subtopic' => 'required|integer|exists:practice_subtopics,id,subject_id,' . $subject . ',topic_id,' . $topic,
            'priority' => 'required|integer',
        ];

        // $rules = [
        //     'subject' => 'required|integer|exists:' . (new PracticeSubject())->getTable() . ',id',
        //     'topic' => 'required|integer|exists:' . (new PracticeTopic())->getTable() . ',id,subject_id,' . $subject,
        //     'subtopic' => [
        //         'required',
        //         'integer',
        //         Rule::exists((new PracticeSubtopic())->getTable(), 'id')->where(function ($query) use ($subject, $topic) {
        //             return $query->where('subject_id', $subject)
        //                          ->where('topic_id', $topic);
        //         }),
        //     ],
        //     'priority' => 'required|integer',
        // ];
        $data = [
            'subject' => $subject,
            'topic' => $topic,
            'subtopic' => $subtopic,
            'priority' => $priority,
        ];
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return redirect()->route('admin.practice.question.create',$data)->withErrors($validator->errors());
        }
        $defaults = [
            'duration' => 30,
            'mark' => 1,
        ];

        $maxQuestion = $QuestionList = null;
        $question = PracticeQuestion::where([
            'subject_id' => $subject,
            'topic_id' => $topic,
            'subtopic_id' => $subtopic,
        ])->get();
        $maxQuestion = $question->max('priority');

        if($maxQuestion && $priority<1)
        {
            $priority = 1;
            $redirect = true;
        } else if($maxQuestion && $priority>$maxQuestion+1)
        {
            $priority = $maxQuestion+1;
            $redirect = true;
        }
        if(!empty($redirect)) {
            return redirect()->route('admin.practice.question.manage', [
                'subject' => $subject,
                'topic' => $topic,
                'subtopic' => $subtopic,
                'priority' => $priority,
            ])->with('error', 'Question number changed due to invalid. ');
        }
        $QuestionList = $question->pluck('priority')->toArray();
        $question = PracticeQuestion::where([
            'subject_id' => $subject,
            'topic_id' => $topic,
            'subtopic_id' => $subtopic,
            'priority' => $priority,
        ])->first();

        if (!$question) {
            $question = new PracticeQuestion([
                'subject_id' => $subject,
                'topic_id' => $topic,
                'subtopic_id' => $subtopic,
                'priority' => $priority,
            ]);
        }

        $prevSubject=$nextSubject=$prevTopic=$nextTopic=$prevSubtopic=$nextSubtopic=null;

        // $prevSubject = $this->prevNextSubject($subject,1);
        // $nextSubject = $this->prevNextSubject($subject,2);

        // $prevTopic = $this->prevNextTopic($subject,$topic,1);
        // $nextTopic = $this->prevNextTopic($subject,$topic,2);

        // $prevSubtopic = $this->prevNextSubtopic($subject,$topic,$subtopic,1,true);
        // $nextSubtopic = $this->prevNextSubtopic($subject,$topic,$subtopic,2,true);

        return view('admin.practice.question.manage',compact('question','defaults','maxQuestion','QuestionList','prevSubject','nextSubject','prevTopic','nextTopic','prevSubtopic','nextSubtopic'));
    }
    public function prevNextSubject($subject,$type,$loop=false)
    {
        $currentSubjectPriority = PracticeSubject::where('id', $subject)->pluck('priority')->first();
        if($type==1)
        {
            $subject_id = PracticeSubject::where('id', '!=', $subject)
            ->where('priority', '<', $currentSubjectPriority)
            ->orderBy('priority')
            ->get()
            ->last()?->id;
        } else if($type==2)
        {
            $subject_id = PracticeSubject::where('id', '!=', $subject)
            ->where('priority', '>', $currentSubjectPriority)
            ->orderBy('priority')
            ->get()
            ->first()?->id;
        }
        return $subject_id;
    }
    public function prevNextTopic($subject,$topic,$type,$loop=false)
    {
        $currentTopicPriority = PracticeTopic::where('id', $topic)->pluck('priority')->first();
        // dd($currentTopicPriority);
        if($type==1)
        {

            $topic_id = PracticeTopic::where('subject_id', $subject)
            ->where('id', '!=', $topic)
            ->where('priority', '<', $currentTopicPriority)
            ->orderBy('priority')
            ->get()
            ->last()?->id;
        } else if($type==2)
        {
            $topic_id = PracticeTopic::where('subject_id', $subject)
            ->where('id', '!=', $topic)
            ->where('priority', '>', $currentTopicPriority)
            ->orderBy('priority')
            ->get()
            ->first()?->id;
        }
        return $topic_id;
    }
    public function prevNextSubtopic($subject,$topic,$subtopic,$type,$loop=false)
    {
        $currentSubtopicPriority = PracticeSubtopic::where('id', $subtopic)->pluck('priority')->first();
        if($type==1)
        {
            $subtopic = PracticeSubtopic::where([
                'subject_id' => $subject,
                'topic_id' => $topic,
            ])
            ->where('id', '!=', $subtopic)
            ->where('priority', '<', $currentSubtopicPriority)
            ->orderBy('priority')
            ->get()
            ->last();
        } else if($type==2)
        {
            $subtopic = PracticeSubtopic::where([
                'subject_id' => $subject,
                'topic_id' => $topic,
            ])
            ->where('id', '!=', $subtopic)
            ->where('priority', '>', $currentSubtopicPriority)
            ->orderBy('priority')
            ->get()
            ->first();
        }
        if(empty($subtopic) && $loop==true)
        {
            while(empty($subtopic))
            {
                $topic = $this->prevNextTopic($subject,$topic,$type,$loop);
                if($topic)
                {
                    // dd(345);
                    if($type==1)
                    {
                        $subtopic = PracticeSubtopic::where([
                            'subject_id' => $subject,
                            'topic_id' => $topic,
                        ])
                        ->orderBy('priority')
                        ->get()
                        ->last();
                    } else if($type==2)
                    {
                        $subtopic = PracticeSubtopic::where([
                            'subject_id' => $subject,
                            'topic_id' => $topic,
                        ])
                        ->orderBy('priority')
                        ->get()
                        ->first();
                    }
                }
            }
        }
        return $subtopic;

    }
    public function questionSubmit(Request $request)
    {
        if(($request->input('submit')) && $request->input('submit')=='delete' && $request->input('question_id'))
        {
            $question_id = $request->input('question_id');
            $question = PracticeQuestion::find($question_id);
            if($question) {
                $question->delete();
                return back()->with('success', 'Question deleted successfully');
            }
        }
        $validator = Validator::make($request->all(), [
            'subject_id' => 'required|integer|exists:practice_subjects,id',
            'topic_id' => 'required|integer|exists:practice_topics,id',
            'subtopic_id' => 'required|integer|exists:practice_subtopics,id',
            'priority' => 'required|integer|min:1',
            'question_type' => 'required|in:Choice,Multi-choice,Numerical',
            'question' => 'required',
            'mark' => 'required|integer',
            'duration' => 'required|integer|min:1',
            'choiceAnswer' => 'nullable|integer|between:1,4',
            'answerCheck1' => 'nullable|integer|in:1',
            'answerCheck2' => 'nullable|integer|in:2',
            'answerCheck3' => 'nullable|integer|in:3',
            'answerCheck4' => 'nullable|integer|in:4',
            'solution' => 'nullable',
            'status' => 'nullable|boolean',
            'submit' => 'nullable|in:save,savenext',
        ]);
        if ($request->input('question_type') === 'Numerical') {
            $validator->after(function ($validator) use ($request) {
                $selectedMinAnswer = $request->input('min_answer');
                $selectedMaxAnswer = $request->input('max_answer');
                if (!isset($selectedMinAnswer) || trim($selectedMinAnswer) === '') {
                    $validator->errors()->add('min_answer', 'The answer field is empty.');
                } elseif (!is_numeric($selectedMinAnswer)) {
                    $validator->errors()->add('min_answer', 'The answer field must be numeric.');
                }
                if (isset($selectedMaxAnswer)) {
                    if (!is_numeric($selectedMaxAnswer)) {
                        $validator->errors()->add('max_answer', 'The maximum answer field must be numeric.');
                    }
                }
            });
        } else
        {
            $validator->after(function ($validator) use ($request) {
                $option1 = $request->input('option1');
                $option2 = $request->input('option2');
                $option3 = $request->input('option3');
                $option4 = $request->input('option4');
                if (!isset($option1) || trim($option1) === '') {
                    $validator->errors()->add('option1', 'Option 1 is required');
                }
                if (!isset($option2) || trim($option2) === '') {
                    $validator->errors()->add('option2', 'Option 2 is required');
                }
                if (!isset($option3) || trim($option3) === '') {
                    $validator->errors()->add('option3', 'Option 3 is required');
                }
                if (!isset($option4) || trim($option4) === '') {
                    $validator->errors()->add('option4', 'Option 4 is required');
                }
            });
        }
        if ($request->input('question_type') === 'Choice') {
            $validator->after(function ($validator) use ($request) {
                $selectedChoice = $request->input('choiceAnswer');
                if (empty($selectedChoice)) {
                    $validator->errors()->add('choiceAnswer', 'The answer is not selected.');
                }
            });
        } else if ($request->input('question_type') === 'Multi-choice') {
            $validator->after(function ($validator) use ($request) {
                $selectedMultiChoice = collect([
                    $request->input('answerCheck1'),
                    $request->input('answerCheck2'),
                    $request->input('answerCheck3'),
                    $request->input('answerCheck4'),
                ])->filter();

                if ($selectedMultiChoice->isEmpty()) {
                    $validator->errors()->add('answerCheck1', 'The answer is not selected.');
                }
            });
        }
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator->errors());
        }

        $storeData = $request->only(['subject_id','topic_id','subtopic_id','priority','question','question_type','duration','mark','solution']);

        if ($request->input('question_type') === 'Numerical') {
            $storeData['answer1'] = $request->input('min_answer');
            $storeData['answer2'] = $request->input('max_answer');
        } else
        {
            $storeData['option1'] = $request->input('option1');
            $storeData['option2'] = $request->input('option2');
            $storeData['option3'] = $request->input('option3');
            $storeData['option4'] = $request->input('option4');
        }
        if ($request->input('question_type') === 'Choice') {
            $storeData['answer1'] = $request->input('choiceAnswer');
        } elseif ($request->input('question_type') === 'Multi-choice') {
            $selectedMultiChoice = [
                $request->input('answerCheck1'),
                $request->input('answerCheck2'),
                $request->input('answerCheck3'),
                $request->input('answerCheck4'),
            ];
            $selectedMultiChoice = array_filter($selectedMultiChoice);
            $storeData['answer1'] = implode(',', $selectedMultiChoice);
        }
        if($request->input('status')==1) {
            $storeData['status'] = 'public';
        } else {
            $storeData['status'] = 'hidden';
        }

        $question = PracticeQuestion::where([
            'subject_id' => $request->input('subject_id'),
            'topic_id' => $request->input('topic_id'),
            'subtopic_id' => $request->input('subtopic_id'),
            'priority' => $request->input('priority'),
        ])->first();
        if (!$question) {
            $PracticeQuestion = PracticeQuestion::create($storeData);
        } else {
            $id = $question->id;
            $PracticeQuestion = PracticeQuestion::whereId($id)->update($storeData);
        }
        if($PracticeQuestion)
        {
            $priority = $request->input('priority');
            if ($request->input('submit') === 'savenext') {
                $priority++;
            }
            return redirect()->route('admin.practice.question.manage', [
                'subject' => $request->input('subject_id'),
                'topic' => $request->input('topic_id'),
                'subtopic' => $request->input('subtopic_id'),
                'priority' => $priority,
            ])->with('success', 'Question has been saved!');
        } else
        {
            return back()->with('error', 'Error adding question, please try again or contact our support team')->withInput();
        }
    }
    public function loadSubjects()
    {
        $subjects = PracticeSubject::all(['id', 'name']);
        $subjectData = [];
        foreach ($subjects as $subject) {
            $subjectData[$subject->id] = $subject->name;
        }
        return response()->json(['subjects' => $subjectData]);
    }
    public function loadTopics(Request $request)
    {
        $subjectId = $request->input('subject');
        $topics = PracticeTopic::where('subject_id',$subjectId)->get(['id', 'name']);
        $topicData = [];
        foreach ($topics as $topic) {
            $topicData[$topic->id] = $topic->name;
        }
        return response()->json(['topics' => $topicData]);
    }
    public function loadSubtopics(Request $request)
    {
        $topicId = $request->input('topic');
        $subtopics = PracticeSubtopic::where('topic_id',$topicId)->get(['id', 'name']);
        $subtopicData = [];
        foreach ($subtopics as $subtopic) {
            $subtopicData[$subtopic->id] = $subtopic->name;
        }
        return response()->json(['subtopics' => $subtopicData]);
    }

    public function uploadImage(Request $request)
    {
        return view('admin.upload-image');
    }
    public function storeImage(Request $request)
    {
        if($request->hasFile('image'))
        {
            $uploadedFile = $request->file('image');
            $fileName = $request->input('file_name');
            $imageFolder = $request->input('folder');
            $imageWidth = $request->input('width');
            $imageFileName = $fileName.'.'.$uploadedFile->getClientOriginalExtension();
            $targetPath = public_path($imageFolder);
            if (!File::exists($targetPath)) {
                File::makeDirectory($targetPath, 0755, true);
            }
            $imageFullPath = $targetPath . '/'. $imageFileName;
            $imageManager = new ImageManager(new Driver());
            $img = $imageManager->read($uploadedFile);
            $img->scale(width: $imageWidth)->save($imageFullPath);
        }
        return redirect(route('admin.upload-image'))->with('success', 'The ' . $imageFileName.' Uploaded in '. $targetPath);

    }

    public function storeTinyImage(Request $request) {
        \Log::info('Received request for image upload'); // Log for debugging

        if ($request->hasFile('file')) {
            $image = $request->file('file');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('public/uploads', $filename);

            \Log::info('Image uploaded: ' . $filename); // Log the filename
            return response()->json(['location' => asset('storage/uploads/' . $filename)]);
        }

        \Log::error('No file found in request'); // Log error if no file
        return response()->json(['error' => 'Invalid image'], 400);
    }

}
