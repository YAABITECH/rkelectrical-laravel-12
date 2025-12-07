<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\PracticeSeries;
use App\Models\PracticeTest;
use App\Models\Practice;
use App\Models\PracticeQues;
use App\Models\PracticeSubject;
use App\Models\PracticeTopic;
use App\Models\PracticeSubtopic;
use App\Models\PracticeQuestion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PracticeController extends Controller
{
   public function subject()
   {
    $subjects = PracticeSubject::where('status', 'public')
    ->orderBy('priority', 'asc')
    ->with(['practiceTopics.practiceSubtopics.practiceQuestions'])
    ->get();

    foreach ($subjects as $subject) {
        $firstTopic = $subject->practiceTopics->first();
        $firstSubtopic = $firstTopic ? $subject->practiceSubtopics->first() : null;
        $firstPriority = $firstSubtopic ? $subject->practiceQuestions->first() : null;

        $subject->firstTopic = $firstTopic;
        $subject->firstSubtopic = $firstSubtopic;
        $subject->firstPriority = $firstPriority;
    }
    return view('practice.subject',compact('subjects'));
   }

   public function topic($subject)
   {
    $topics=PracticeTopic::where('subject_id',$subject)->where('status','public')->orderBy('priority','asc')->with(['practiceSubtopics.practiceQuestions'])->get();
    foreach ($topics as $topic) {
        $firstSubtopic = $topic->practiceSubtopics->first();
        $firstPriority = $firstSubtopic ? $topic->practiceQuestions->first() : null;

        $topic->firstSubtopic = $firstSubtopic;
        $topic->firstPriority = $firstPriority;
    }
    return view('practice.topic',compact('topics'));
   }

   public function subtopic($subject, $topic)
   {
    $subtopics=PracticeSubtopic::where('subject_id',$subject)->where('topic_id',$topic)->with(['practiceQuestions'])->where('status','public')->orderBy('priority','asc')->get();
    foreach ($subtopics as $subtopic) {
        $firstPriority = $subtopic->practiceQuestions->first();
        $subtopic->firstPriority = $firstPriority;
    }

    return view('practice.subtopic',compact('subtopics'));
   }

    public function start(Request $request, $subject, $topic, $subtopic, $priority= 1)
    {
        $user = Auth::user();
        if(!$user){
            return redirect()->route('user.login',['redirect' => url()->previous()]);
        }

        $rules = [
            'subject' => 'required|integer|exists:practice_subjects,id',
            'topic' => 'required|integer|exists:practice_topics,id',
            'subtopic' => 'required|integer|exists:practice_subtopics,id',
            'priority' => 'nullable|integer',
        ];

        $data = [
            'subject' => $subject,
            'topic' => $topic,
            'subtopic' => $subtopic,
            'priority' => $priority,
        ];

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return redirect()->route('practice.subject')->withErrors($validator->errors());
        }


        $question = PracticeQuestion::where([
            'subject_id' => $subject,
            'topic_id' => $topic,
            'subtopic_id' => $subtopic,
        ])->get();

        $lastPriority = $question->max('priority');

        $maxQuestion = $question->max('priority');
        $QuestionList = $question->pluck('priority')->toArray();
        $question = PracticeQuestion::where([
            'subject_id' => $subject,
            'topic_id' => $topic,
            'subtopic_id' => $subtopic,
        ])
        ->where('priority', '>=', $priority)  // Start from the given priority
        ->orderBy('priority', 'asc')  // Order by priority to get the next available question
        ->first();
        if (!$question) {
            return redirect()->route('practice.subject')->with('error', 'Question not available. Choose other Subjects ');
        }

        $startTime = $request->session()->put("subtopic_{$request->input('user_id')}_{$subtopic}_start_time", now());

        $optionTexts = [
            '1' => $question->option1,
            '2' => $question->option2,
            '3' => $question->option3,
            '4' => $question->option4,
        ];
        $prevSubtopic = $nextSubtopic = null;
        $subtopicObj = PracticeSubtopic::where('id',$subtopic)->first();
        $currentPriority = $subtopicObj->priority;
        $previousSubtopicObj = PracticeSubtopic::where([
            'subject_id' => $subject,
            'topic_id' => $topic,
        ])
        ->where('priority','<',$currentPriority)
        ->orderBy('priority', 'desc')
        ->first();
        if($previousSubtopicObj)
        {
            $prevSubtopic = $previousSubtopicObj->id;
        }
        $nextSubtopicObj = PracticeSubtopic::where([
            'subject_id' => $subject,
            'topic_id' => $topic,
        ])
        ->where('priority','>',$currentPriority)
        ->orderBy('priority', 'asc')
        ->first();
        if($nextSubtopicObj)
        {
            $nextSubtopic = $nextSubtopicObj->id;
        }
        $userData = [
            'subjectId' => $subject,
            'topicId' => $topic,
            'subtopicId' => $subtopic,
            'userId' => $user->id,
        ];
        $checkData = [
            'subject_id' => $subject,
            'topic_id' => $topic,
            'subtopic_id' => $subtopic,
            'user_id' => $user->id,
        ];
        $answered = $request->session()->get("answered_{$request->input('user_id')}_{$subtopic}", []);
        $practice = Practice::where($checkData)->first();
        return view('practice.start', compact('question', 'maxQuestion', 'QuestionList', 'prevSubtopic', 'nextSubtopic', 'answered','optionTexts','user','startTime','lastPriority','userData','practice'));
    }

    public function anssubmit(Request $request)
    {
        if  ($request->input('submit') === 'back') {
            return $this->handleBackButton($request);
        }
        $validator = Validator::make($request->all(), [
            'subject_id' => 'required|integer|exists:practice_subjects,id',
            'topic_id' => 'required|integer|exists:practice_topics,id',
            'subtopic_id' => 'required|integer|exists:practice_subtopics,id',
            'priority' => 'required|integer|min:1',
            'user_id' => 'nullable|integer|exists:users,id',
            'question_type' => 'required|in:Choice,Multi-choice,Numerical',
            'choiceAnswer' => 'nullable|integer|between:1,4',
            'answerCheck1' => 'nullable|in:1',
            'answerCheck2' => 'nullable|in:2',
            'answerCheck3' => 'nullable|in:3',
            'answerCheck4' => 'nullable|in:4',
            'num_ans' => 'nullable|numeric',
            'submit' => 'nullable|in:finish,next,back',
        ]);

        $validator->after(function ($validator) use ($request) {
            if ($request->input('question_type') === 'Choice' && !$request->filled('choiceAnswer')) {
                $validator->errors()->add('choiceAnswer', 'You must select an answer.');
            }

            if ($request->input('question_type') === 'Multi-choice' && !($request->input('answerCheck1') || $request->input('answerCheck2') || $request->input('answerCheck3') || $request->input('answerCheck4'))) {
                $validator->errors()->add('answerCheck', 'You must select at least one answer.');
            }

            if ($request->input('question_type') === 'Numerical' && !$request->filled('num_ans')) {
                $validator->errors()->add('num_ans', 'You must provide a numerical answer.');
            }
        });

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator->errors());
        }
        $user = Auth::user();
        // $user_id = Auth::user();
        $subjectId = $request->input('subject_id');
        $topicId = $request->input('topic_id');
        $subtopicId = $request->input('subtopic_id');
        $questionId = $request->input('question_id');
        $userId = $user->id;
        $priority = $request->input('priority');
        $questionType = $request->input('question_type');
        $duration = [];
        $startTime = $request->session()->get("subtopic_{$userId}_{$subtopicId}_start_time");
        $duration = now()->diffInSeconds($startTime);

        if (in_array($request->input('submit'), ['next', 'finish'])) {
            $userAnswer = [
                'question_type' => $request->input('question_type'),
                'choiceAnswer' => $request->input('choiceAnswer'),
                'answerCheck' => [
                    'answerCheck1' => $request->input('answerCheck1'),
                    'answerCheck2' => $request->input('answerCheck2'),
                    'answerCheck3' => $request->input('answerCheck3'),
                    'answerCheck4' => $request->input('answerCheck4')
                ],
                'num_ans' => $request->input('num_ans'),
                'isCorrect' => false,
            ];

            $request->session()->put("answer_{$userId}_{$priority}", $userAnswer);
            $question = PracticeQuestion::where('subject_id', $subjectId)
                                        ->where('topic_id', $topicId)
                                        ->where('subtopic_id', $subtopicId)
                                        ->where('priority', $priority)
                                        ->where('question_type', $questionType)
                                        ->first();
                $correctAnswer = $this->getCorrectAnswer($question);

            $isCorrect = $this->checkUserAnswer($request, $correctAnswer);
            $userAnswer['isCorrect'] = $isCorrect;

            $storeData = null;
            $storeData = Practice::where([
                    'subject_id' => $subjectId,
                    'topic_id'=> $topicId,
                    'subtopic_id' => $subtopicId,
                    'user_id' => $userId,
            ])->first();
            if($userAnswer['question_type'] == 'Choice'){
                $option = $userAnswer['choiceAnswer'];
            }else if($userAnswer['question_type'] == 'Multi-choice'){
                $option = $userAnswer['answerCheck'];
            }else if($userAnswer['question_type'] == 'Numerical'){
                $option = $userAnswer['num_ans'];
            }
            $existingRightIds = array_filter(explode(',', $storeData->right_answer_question_ids ?? ''));
            $existingWrongIds = array_filter(explode(',', $storeData->wrong_answer_question_ids ?? ''));
            if ($isCorrect) {
                if (in_array($priority, $existingWrongIds)) {
                    $existingWrongIds = array_diff($existingWrongIds, [$priority]);
                }
                if (!in_array($priority, $existingRightIds)) {
                    $existingRightIds[] = $priority;
                }
            } else {
                if (in_array($priority, $existingRightIds)) {
                    $existingRightIds = array_diff($existingRightIds, [$priority]);
                }
                if (!in_array($priority, $existingWrongIds)) {
                    $existingWrongIds[] = $priority;
                }
            }
            $rightAnswerQuestion = implode(',', $existingRightIds);
            $wrongAnswerQuestion = implode(',', $existingWrongIds);

            $userData = [
                    'subjectId'=> $subjectId,
                    'topicId'=> $topicId,
                    'subtopicId'=> $subtopicId,
                    'userId'=> $userId,
                    'priority' => $priority,
                    'rightAnswer' => $isCorrect ? 1 : 0,
                    'right_answer_question_ids' => $rightAnswerQuestion,
                    'wrongAnswer' => $isCorrect ? 0 : 1,
                    'wrong_answer_question_ids' => $wrongAnswerQuestion,
                    'option' => $option
            ];
            $status = $this->saveOrUpdatePracticeResult($storeData, $userData);
            if ($status['status'] == 'success') {
                $isCorrect = $userAnswer['isCorrect'];
                if($request->input('submit') === 'finish')
                {
                    $userInfo = Practice::where([
                        'subject_id' => $request->input('subject_id'),
                        'topic_id' => $request->input('topic_id'),
                        'subtopic_id' => $request->input('subtopic_id'),
                        'user_id' => $request->input('user_id'),
                    ])->first();
                    return redirect()->route('practice.start', [
                        'subject' => $request->input('subject_id'),
                        'topic' => $request->input('topic_id'),
                        'subtopic' => $request->input('subtopic_id'),
                        'priority' => $request->input('priority'),
                    ])->with([
                        'success' => 'Your answer has been saved!',
                        'show_modal' => true,
                        'userDetail' => $userInfo,
                        'isCorrect' => $isCorrect,
                    ]);
                }
                return $this->handleNextButton($request, $userData);
                // return redirect()->route('practice.start', [
                //     'subject' => $request->input('subject_id'),
                //     'topic' => $request->input('topic_id'),
                //     'subtopic' => $request->input('subtopic_id'),
                //     'priority' => $request->input('priority') + 1,
                // ])->with([
                //     'success'=> 'Your answer has been saved!',
                //     'userData' => $userData
                // ]);
            }else{
                return redirect()->route('practice.start', [
                    'subject' => $request->input('subject_id'),
                    'topic' => $request->input('topic_id'),
                    'subtopic' => $request->input('subtopic_id'),
                    'priority' => $request->input('priority'),
                ])->with(
                    'error', 'Your answer not saved!'
                );
            }

        }
    }

    protected function handleBackButton($request)
    {
        $subjectId = $request->input('subject_id');
        $topicId = $request->input('topic_id');
        $subtopicId = $request->input('subtopic_id');
        $currentPriority = $request->input('priority');

        $priorities = PracticeQuestion::where('subject_id', $subjectId)
            ->where('topic_id', $topicId)
            ->where('subtopic_id', $subtopicId)
            ->pluck('priority')
            ->sort() // Sort the priorities if they are not already sorted
            ->toArray();

        $currentIndex = array_search($currentPriority, $priorities);

        if ($currentIndex !== false && $currentIndex > 0) {
            $previousPriority = $priorities[$currentIndex - 1];
        } else {
            $previousPriority = $currentPriority;
        }

        return redirect()->route('practice.start', [
            'subject' => $subjectId,
            'topic' => $topicId,
            'subtopic' => $subtopicId,
            'priority' => $previousPriority,
        ]);
    }
    protected function handleNextButton($request, $userData)
    {
        $nextPriority = PracticeQuestion::where('subject_id', $request->input('subject_id'))
                                    ->where('topic_id', $request->input('topic_id'))
                                    ->where('subtopic_id', $request->input('subtopic_id'))
                                    ->where('priority', '>', $request->input('priority'))
                                    ->orderBy('priority', 'asc')
                                    ->value('priority');

        // If no next priority is found, we can return the current one (or handle differently)
        if (!$nextPriority) {
            return redirect()->route('practice.start', [
                'subject' => $request->input('subject_id'),
                'topic' => $request->input('topic_id'),
                'subtopic' => $request->input('subtopic_id'),
                'priority' => $request->input('priority'), // Keep on the same priority if no next is found
            ])->with('error', 'No more questions available.');
        }

        // Redirect to the next priority
        return redirect()->route('practice.start', [
            'subject' => $request->input('subject_id'),
            'topic' => $request->input('topic_id'),
            'subtopic' => $request->input('subtopic_id'),
            'priority' => $nextPriority, // Use the next valid priority
        ])->with([
            'success' => 'Your answer has been saved!',
            'userData' => $userData
        ]);
    }
    protected function getCorrectAnswer($question)
    {
        if (!$question) {
            return null;
        }
        $correctAnswer = null;
        if ($question->question_type === 'Choice' || $question->question_type === 'Multi-choice') {
            $correctAnswer = $question->answer1;
        } elseif ($question->question_type === 'Numerical') {
            $correctAnswer = [$question->answer1, $question->answer2];
        }
        return $correctAnswer;
    }

    private function checkUserAnswer($request, $correctAnswer)
    {
        $isCorrect = false;

        if ($request->input('question_type') === 'Numerical') {
            $userAnswer = floatval($request->input('num_ans'));
            $isCorrect = ($userAnswer >= floatval($correctAnswer[0]) && $userAnswer <= floatval($correctAnswer[1]));
        } elseif ($request->input('question_type') === 'Choice') {
            $userAnswer = intval($request->input('choiceAnswer'));
            $isCorrect = ($userAnswer === intval($correctAnswer));
        } elseif ($request->input('question_type') === 'Multi-choice') {
            $correctAnswer = explode(',', $correctAnswer);
            $userAnswers = [
                $request->input('answerCheck1'),
                $request->input('answerCheck2'),
                $request->input('answerCheck3'),
                $request->input('answerCheck4'),
            ];
            $userAnswers = array_filter($userAnswers);
            sort($userAnswers);
            sort($correctAnswer);
            $isCorrect = ($userAnswers === $correctAnswer);
        }

        return $isCorrect;
    }

    private function saveOrUpdatePracticeResult($storeData, $userData)
    {
        $sessionKeyPrefix = "practice_{$userData['userId']}_{$userData['subtopicId']}_{$userData['priority']}";

        $sessionKeyAttended = "{$sessionKeyPrefix}_attended";
        $sessionKeyAnswered = "{$sessionKeyPrefix}_answered";
        $sessionKeyRightAnswered = "{$sessionKeyPrefix}_rightAnswer";
        $sessionKeyWrongAnswered = "{$sessionKeyPrefix}_wrongAnswer";

        // Retrieve attended and answered questions from session, or initialize empty arrays
        $attendedQuestions = session()->get($sessionKeyAttended, []);
        $answeredQuestions = session()->get($sessionKeyAnswered, []);
        $rightAnsweredQuestions = session()->get($sessionKeyRightAnswered, []);
        $wrongAnsweredQuestions = session()->get($sessionKeyWrongAnswered, []);

        // If this is a new practice session (no previous data), create a new record
        if ($storeData == null) {
            $storeData = [
                'subject_id' => $userData['subjectId'],
                'topic_id' => $userData['topicId'],
                'subtopic_id' => $userData['subtopicId'],
                'user_id' => $userData['userId'],
                'attended' => 1,
                'answered' => 1,
                'right_answer' => $userData['rightAnswer'],
                'right_answer_question_ids' => $userData['right_answer_question_ids'],
                'wrong_answer' => $userData['wrongAnswer'],
                'wrong_answer_question_ids' => $userData['wrong_answer_question_ids'],
            ];

            $practice = Practice::create($storeData);

            // Store attended question in session
            $attendedQuestions[$userData['priority']] = $userData['priority'];
            session()->put($sessionKeyAttended, $attendedQuestions);

            // Store answered question in session
            $answeredQuestions[$userData['priority']] = $userData['option'];
            session()->put($sessionKeyAnswered, $answeredQuestions);

            $rightAnsweredQuestions[$userData['priority']] = (int)($userData['rightAnswer'] == 1 ?? 0);
            session()->put($sessionKeyRightAnswered, $rightAnsweredQuestions);

            // Store the question ID and wrong_answer in session
            $wrongAnsweredQuestions[$userData['priority']] = (int)($userData['wrongAnswer'] == 1 ?? 0);
            session()->put($sessionKeyWrongAnswered, $wrongAnsweredQuestions);

            return ['status' => 'success', 'message' => 'Practice data created successfully'];

        }
        else {
            // Convert existing question ID strings to arrays
            $existingRightIds = array_filter(explode(',', $storeData->right_answer_question_ids ?? ''));
            $existingWrongIds = array_filter(explode(',', $storeData->wrong_answer_question_ids ?? ''));

            if ($userData['rightAnswer'] == 1) {
                // Remove from wrong answers if present
                $existingWrongIds = array_diff($existingWrongIds, [$userData['priority']]);

                // Add to right answers if not already there
                if (!in_array($userData['priority'], $existingRightIds)) {
                    $existingRightIds[] = $userData['priority'];
                }
            } else {
                // Remove from right answers if previously correct
                $existingRightIds = array_diff($existingRightIds, [$userData['priority']]);

                // Add to wrong answers if not already there
                if (!in_array($userData['priority'], $existingWrongIds)) {
                    $existingWrongIds[] = $userData['priority'];
                }
            }
            $allAttemptedQuestions = array_unique(array_merge($existingRightIds, $existingWrongIds));
            $attendedCount = count($allAttemptedQuestions);
            // Convert arrays back to strings
            $updateData = [
                'attended' => $attendedCount,
                'answered' => $attendedCount,
                'right_answer' => count($existingRightIds),
                'right_answer_question_ids' => implode(',', $existingRightIds),
                'wrong_answer' => count($existingWrongIds),
                'wrong_answer_question_ids' => implode(',', $existingWrongIds),
            ];

            $storeData->update($updateData);
        }

        // Store attended question in session
        $attendedQuestions[$userData['priority']] = $userData['priority'];
        session()->put($sessionKeyAttended, $attendedQuestions);

        // Store answered question in session
        $answeredQuestions[$userData['priority']] = $userData['option'];
        session()->put($sessionKeyAnswered, $answeredQuestions);

        // Update session values for correct and incorrect answers
        $rightAnsweredQuestions[$userData['priority']] = (int)($userData['rightAnswer'] == 1);
        session()->put($sessionKeyRightAnswered, $rightAnsweredQuestions);

        $wrongAnsweredQuestions[$userData['priority']] = (int)($userData['wrongAnswer'] == 1);
        session()->put($sessionKeyWrongAnswered, $wrongAnsweredQuestions);

        // Log for debugging
        \Log::info('Attended Questions:', $attendedQuestions);
        \Log::info('Answered Questions:', $answeredQuestions);
        \Log::info('Right Answered Questions:', $rightAnsweredQuestions);
        \Log::info('Wrong Answered Questions:', $wrongAnsweredQuestions);

        return ['status' => 'success', 'message' => 'Practice data updated successfully'];
        // else {
        //     if (!in_array($userData['priority'], $attendedQuestions)) {
        //         $updateData['attended'] = $storeData['attended'] + 1;
        //         $updateData['answered'] = $storeData['answered'] + 1;
        //         $updateData['right_answer'] = $storeData['right_answer'] + $userData['rightAnswer'];
        //         $updateData['wrong_answer'] = $storeData['wrong_answer'] + $userData['wrongAnswer'];

        //         $storeData->update($updateData);

        //         $attendedQuestions[$userData['priority']] = $userData['priority'];
        //         session()->put($sessionKeyAttended, $attendedQuestions);

        //         $answeredQuestions[$userData['priority']] = $userData['option'];
        //         session()->put($sessionKeyAnswered, $answeredQuestions);

        //         $rightAnsweredQuestions[$userData['priority']] = (int)($userData['rightAnswer'] == 1 ?? 0);
        //         session()->put($sessionKeyRightAnswered, $rightAnsweredQuestions);

        //         // Store the question ID and wrong_answer in session
        //         $wrongAnsweredQuestions[$userData['priority']] = (int)($userData['wrongAnswer'] == 1 ?? 0);
        //         session()->put($sessionKeyWrongAnswered, $wrongAnsweredQuestions);
        //     } else {
        //         // Retrieve stored answers for this question from the session
        //         $previousRightAnswer = $rightAnsweredQuestions[$userData['priority']] ?? 0; // Default to 0 if not set
        //         $previousWrongAnswer = $wrongAnsweredQuestions[$userData['priority']] ?? 0; // Default to 0 if not set
        //         $attendedQuestions[$userData['priority']] = $userData['priority'];
        //         session()->put($sessionKeyAttended, $attendedQuestions);

        //         $answeredQuestions[$userData['priority']] = $userData['option'];
        //         session()->put($sessionKeyAnswered, $answeredQuestions);

        //         // Handle right answer logic
        //         if ($userData['rightAnswer'] == 1) {
        //             if ($previousRightAnswer == 0) { // If it wasn't previously answered correctly, increment
        //                 $updateData['right_answer'] = $storeData['right_answer'] + 1;
        //             }
        //         } else {
        //             if ($previousRightAnswer == 1) { // If it was previously answered correctly, decrement
        //                 $updateData['right_answer'] = max(0, $storeData['right_answer'] - 1);
        //             }
        //         }

        //         // Handle wrong answer logic
        //         if ($userData['wrongAnswer'] == 1) {
        //             if ($previousWrongAnswer == 0) { // If it wasn't previously answered incorrectly, increment
        //                 $updateData['wrong_answer'] = $storeData['wrong_answer'] + 1;
        //             }
        //         } else {
        //             if ($previousWrongAnswer == 1) { // If it was previously answered incorrectly, decrement
        //                 $updateData['wrong_answer'] = max(0, $storeData['wrong_answer'] - 1);
        //             }
        //         }

        //         if (!empty($updateData)) {
        //             $storeData->update($updateData);
        //         }

        //         $rightAnsweredQuestions[$userData['priority']] = (int)($userData['rightAnswer'] == 1 ?? 0);
        //         session()->put($sessionKeyRightAnswered, $rightAnsweredQuestions);

        //         // Store the question ID and wrong_answer in session
        //         $wrongAnsweredQuestions[$userData['priority']] = (int)($userData['wrongAnswer'] == 1 ?? 0);
        //         session()->put($sessionKeyWrongAnswered, $wrongAnsweredQuestions);
        //         // Update the practice record in the database
        //     }
        //     \Log::info('Attended Questions: ', $attendedQuestions);
        //     \Log::info('Answered Questions: ', $answeredQuestions);
        //     \Log::info('Right Answered Questions: ', $rightAnsweredQuestions);
        //     \Log::info('Wrong Answered Questions: ', $wrongAnsweredQuestions);
        //     return ['status' => 'success', 'message' => 'Practice data updated successfully'];
        // }
        // return ['status' => 'error', 'message' => 'Practice data not created or not updated'];
    }

    public function index()
    {

        $practice=Practice::with('user','practiceSubject','practiceTopic','practiceSubtopic')->get();

        return view('admin.practice.result.index',compact('practice'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

        public function show($id)
        {
            $practice = Practice::findOrFail($id);


        return view('admin.practice.result.show',compact('practice'));
        }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $practice = Practice::find($id);
        if($practice) {
            $practiceid=$practice->id;
            $practice->delete();
            $previousUrl = url()->previous();

            if (Str::contains($previousUrl, route('admin.practice.result.show', $practiceid))) {
                return redirect()->route('admin.practice.result.index')->with('success', 'Result has been deleted');
            } else {
                return redirect()->back()->withInput($request->all())->with('success', 'Result has been deleted');
            }
        } else {
            return redirect(route('admin.practice.result.index'))->with('error', 'The Result does not exist');
        }
    }


}
