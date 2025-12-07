<?php

namespace App\Http\Controllers;

use App\Models\TestAnswer;
use App\Models\TestExam;
use App\Models\TestQuestion;
use App\Models\TestReport;
use App\Models\TestSection;
use App\Models\TestSeries;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TestController extends Controller
{
    public function list(Request $request)
    {
        $request->validate([
            'series_id' => 'nullable|integer|exists:test_series,id'
        ]);

        $series_id = $request->series_id ?? null;

        $series = $series_id ? TestSeries::find($series_id) : null;

        $childSeries = TestSeries::where('parent_id', $series_id)
                        ->orderBy('priority')
                        ->get();

        $exams = TestExam::where('series_id', $series_id)
                    ->where('status','active')
                    ->orderBy('priority')
                    ->get();

        return view('test.list', compact(
            'series',
            'childSeries',
            'exams'
        ));
    }
    public function start($exam_id)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('user.login', ['redirect' => url()->current()]);
        }
        $exam = TestExam::findOrFail($exam_id);
        $totalQuestions = TestQuestion::where('exam_id', $exam_id)
                    ->count();

        $limit = $exam->attempt_limit ?? 1;

        $report = TestReport::where('exam_id', $exam->id)
            ->where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->first();

        if ($report) {
            if(($report->done && $report->attempt >= $limit) || ($report->attempt > $limit)) {
                return redirect()->route('test.report', $exam->id);
            }
        }

        if (!$report || $report->done) {
            $report = TestReport::create([
                'user_id'   => $user->id,
                'exam_id'   => $exam->id,
                'attempt'   => $report ? $report->attempt + 1 : 1,
                'total_questions' => $totalQuestions,
                'total_duration' => $exam->duration,
                'total_marks' => $exam->marks,
            ]);
        }

        return view('test.start', compact('exam','user','totalQuestions'));
    }
    public function main($exam_id)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('user.login', ['redirect' => url()->current()]);
        }

        $exam = TestExam::where('id', $exam_id)
                ->where('status', 'active')
                ->first();
        if (!$exam) {
            return redirect()->route('test.list')->with('error', 'Exam not available.');
        }

        $questions = TestQuestion::where('exam_id', $exam->id)->orderBy('priority')->get();

        $testAnswers = TestAnswer::where('exam_id', $exam->id)
                    ->where('user_id', $user->id)
                    ->get()
                    ->keyBy('question_id');

        $totalQuestions = $questions->count();
        $sections = TestSection::where('test_id', $exam_id)
                ->orderBy('priority', 'asc')
                ->get();

        foreach ($questions as $q) {
            $q->testAnswer = $testAnswers[$q->id] ?? null;
            $q->status = 'Unattended';
        }

        $report = TestReport::where('exam_id', $exam->id)
            ->where('user_id', $user->id)
            ->where('done', false)
            ->orderBy('id', 'desc')
            ->first();
        if (!$report) {
            return redirect()->route('test.start', $exam->id);
        }
        if (is_null($report->start_at)) {
            $report->start_at = now();
            $report->save();
        }
        $timer = 0;
        if ($exam->duration && $report->start_at) {
            $extraSeconds = config('test.maxAllowedSubmit', 10);
            $endTime = $report->start_at->copy()->addSeconds(
                $exam->duration + $extraSeconds
            );
            $timer = now()->diffInSeconds($endTime, false);
            $timer = max($timer, 0);
        }
        return view('test.main', compact('exam', 'questions', 'totalQuestions', 'sections', 'user', 'timer'));
    }
    public function loadQuestion(Request $request)
    {
        $examId = $request->exam_id;
        $qNo    = $request->question;
        $exam = TestExam::findOrFail($examId);
        $user = Auth::user();
        $userId = $user->id;
        $this->checkReport($exam, $user, $qNo);

        $timerKey = "exam_{$examId}_start";
        $durationKey = "exam_{$examId}_duration";
        $lastLoadKey = "exam_{$examId}_last_load";

        if (!session()->has($timerKey)) {
            session([$timerKey => now()]);
            session([$durationKey => $exam->duration * 60]);
        }
        session([$lastLoadKey => now()]);

        $question = TestQuestion::where('exam_id', $examId)
                    ->where('priority', $qNo)
                    ->firstOrFail();

        $answerRow = TestAnswer::where([
            'exam_id' => $examId,
            'question_id' => $question->id,
            'user_id' => $userId
        ])->first();

        $status = "not_visited";
        if ($answerRow) {
            if ($answerRow->review) {
                $status = "review";
            } elseif ($answerRow->answer !== null && $answerRow->answer !== "") {
                $status = "answered";
            } else {
                $status = "not_answered";
            }
        }
        $html = view('test.partials.question', [
            'question' => $question,
            'index' => $currentIndex ?? 1,
            'answerRow' => $answerRow,
        ])->render();

        $examStart = session($timerKey);
        $fullTime  = session($durationKey);

        $elapsed = now()->diffInSeconds($examStart);
        $remaining = max($fullTime - $elapsed, 0);

        return response()->json([
            'html' => $html,
            'status' => $status,
            'start_timer' => true,
            'remaining_seconds' => $remaining
        ]);
    }
    private function checkReport($exam, $user)
    {
        $limit = $exam->attempt_limit ?? 1;

        $report = TestReport::where('exam_id', $exam->id)
            ->where('user_id', $user->id)
            ->where('done', false)
            ->orderBy('id', 'desc')
            ->first();

        if ($report) {
            if(($report->done && $report->attempt >= $limit) || ($report->attempt > $limit)) {
                abort(403, "Attempt limit reached");
            }
        }

        if (!$report) {
            $report = TestReport::create([
                'user_id'   => $user->id,
                'exam_id'   => $exam->id,
                'attempt'   => $report ? $report->attempt + 1 : 1,
                'start_at'  => now(),
                'total_questions' => $exam->getQuestions->count(),
                'total_duration' => $exam->duration,
                'total_marks' => $exam->marks,
            ]);
        }
        $timer = null;
        $maxAllowedSubmit = config('test.maxAllowedSubmit', 10);
        $maxAllowedReturn = config('test.maxAllowedReturn', 5);
        if($report) {
            $start_at = $report->start_at;
            if ($exam->duration && $start_at) {
                $endTime = $start_at->clone()->addSeconds($exam->duration + $maxAllowedSubmit + $maxAllowedReturn);
                $timer = $endTime->diffInSeconds(now(), false) * -1;
                if ($timer < 0) {
                    $timer = 0;
                }
            }
        }
        return $timer;
    }

    public function saveAnswer(Request $request)
    {
        $examId = $request->exam_id;
        $userId = $request->user_id;
        $questionId = $request->question_id;

        $exam   = TestExam::find($examId);
        $report = TestReport::where('exam_id', $examId)
                    ->where('user_id', $userId)
                    ->where('done', false)
                    ->orderBy('id', 'desc')
                    ->first();

        $maxAllowedSubmit = config('test.maxAllowedSubmit', 10);
        $maxAllowedReturn = config('test.maxAllowedReturn', 5);
        if ($exam->duration) {
            $allowed = $report->start_at
                        ->clone()
                        ->addSeconds($exam->duration + $maxAllowedSubmit + $maxAllowedReturn);

            if (now()->gt($allowed)) {
                return response()->json([
                    'status' => 'timeout'
                ]);
            }
        }

        $answer = $request->answer;
        $status = $request->status;

        TestAnswer::updateOrCreate(
            [
                'exam_id' => $examId,
                'question_id' => $questionId,
                'user_id' => $userId,
            ],
            [
                'answer' => $answer,
                'status' => $status,
            ]
        );

        return response()->json([
            'status' => $status
        ]);
    }
    public function submitTest(Request $request)
    {
        $examId  = $request->exam_id;
        $userId  = $request->user_id;
        $answers = $request->answers;
        $exam    = TestExam::find($examId);

        $maxAllowedSubmit = config('test.maxAllowedSubmit', 10);
        $maxAllowedReturn = config('test.maxAllowedReturn', 5);

        $report = TestReport::where('exam_id', $examId)
            ->where('user_id', $userId)
            ->where('done', false)
            ->orderBy('id', 'desc')
            ->first();
            
        $mismatch = [];
        foreach ($answers as $a) {
            $qid    = $a['question_id'];
            $reqAns = trim($a['answer']);
            $existing = TestAnswer::where('exam_id', $examId)
                ->where('user_id', $userId)
                ->where('question_id', $qid)
                ->first();
            // Case A: No existing answer but user now sends a new one
            if (!$existing || !$existing->answer) {
                if ($reqAns !== '') {
                    Log::info("Mismatch: Q$qid - New answer submitted without existing record. Answer: '$reqAns'");
                }
                continue;
            }
            $old = trim($existing->answer);
            // Case B: Existing answer but user cleared it now
            if ($old !== '' && $reqAns === '') {
                $mismatch[] = $qid;
                Log::info("Mismatch: Q$qid - Existing answer removed.");
                continue;
            }
            // Case C: Existing answer differs from new answer
            if ($old !== $reqAns) {
                $mismatch[] = $qid;
                Log::info("Mismatch: Q$qid - Changed from '$old' to '$reqAns'");
            }
        }

        foreach ($answers as $a) {
            try {
                TestAnswer::updateOrCreate(
                    [
                        'exam_id'     => $examId,
                        'question_id' => $a['question_id'],
                        'user_id'     => $userId,
                    ],
                    [
                        'answer' => $a['answer'],
                        'status' => $a['status'],
                    ]
                );
            } catch (\Exception $e) {
                Log::error('Submit mismatch: ', [
                    'question_id' => $a['question_id'],
                    'error'       => $e->getMessage()
                ]);
            }
        }

        if (!empty($mismatch)) {
            $expiry = $report->start_at
                        ->clone()
                        ->addSeconds($exam->duration + $maxAllowedSubmit + $maxAllowedReturn);
            if ($exam->duration && now()->gt($expiry)) {
                Log::warning("Mismatch ignored due to timeout. User: $userId, Exam: $examId");
                return response()->json([
                    'status'   => 'timeout',
                    'redirect' => route('test.validate', ['exam_id' => $examId])
                ]);
            }
            return response()->json([
                'status'    => 'confirm',
                'questions' => $mismatch,
                'message'   => 'Please confirm changes in Questions: ' . implode(', ', $mismatch)
            ]);
        }

        if ($report && !$report->end_at) {
            $now = now();
            $maxEnd = null;

            if ($exam->duration) {
                $maxEnd = $report->start_at->clone()->addSeconds($exam->duration + $maxAllowedSubmit + $maxAllowedReturn);
            }

            if ($maxEnd && $now->gt($maxEnd)) {
                $report->update([
                    'end_at' => $now,
                    'done'   => true
                ]);
            } else {
                $report->update([
                    'end_at' => $now,
                    'done'   => true
                ]);
            }
        }

        return response()->json([
            'redirect' => route('test.validate', ['exam_id' => $examId])
        ]);
    }

    public function validateTest(Request $request)
    {
        $examId = $request->exam_id;
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('user.login', ['redirect' => url()->current()]);
        }
        $userId = $user->id;

        $correct = 0;
        $attended = 0;
        $totalMarks = 0;
        $exam = TestExam::find($examId);
        $testAnswers = TestAnswer::where('exam_id', $examId)
                    ->where('user_id', $userId)
                    ->get()
                    ->keyBy('question_id');

        $questions = TestQuestion::where('exam_id', $examId)->orderBy('priority')->get();
        foreach ($questions as $q) {
            $q->testAnswer = $testAnswers[$q->id] ?? null;
            $q->status = 'Unattended';
            if(!is_null($q->testAnswer)) {
                $userAns = $q->testAnswer->answer ?? null;
                $timeSpent = $q->testAnswer->time_spent ?? 0;
                $userAns = $userAns !== null ? trim($userAns) : null;
                $answer = trim($q->answer);
                $answer2 = isset($q->answer2) ?  : null;
                if (!is_null($userAns) && $userAns !== '') {
                    $attended++;
                    $isCorrect = false;
                    switch ($q->question_type) {
                        case 'Choice':
                            $isCorrect = ($userAns == $answer);
                            break;

                        case 'Multi-choice':
                            $correctAnsArr = collect(json_decode($answer, true))->sort()->values()->toArray();
                            $userAnsArr = collect(explode(',', $userAns))->map('trim')->sort()->values()->toArray();
                            $isCorrect = ($correctAnsArr == $userAnsArr);
                            break;

                        case 'Numerical':
                            if(isset($q->answer2) && $q->answer2 != '') {
                                $answer2 = trim($q->answer2);
                                $isCorrect = ($userAns >= $answer && $userAns <= $answer2);
                            } else {
                                $isCorrect = ($userAns == $answer);
                            }
                            break;
                    }

                    if ($isCorrect) {
                        $q->status = 'Correct';
                        $correct++;
                        $totalMarks += $q->mark;
                    } else {
                        $q->status = 'Wrong';
                        $totalMarks -= $q->negative_mark;
                    }
                }
            }
        }
        
        $fullMarks = $questions->sum('mark');
        $percentage = $fullMarks > 0
            ? round(($totalMarks / $fullMarks) * 100, 2)
            : 0;

        // NEW: Chart values
        $wrong = $attended - $correct;
        $unattended = $questions->count() - $attended;

        // Positive / Negative marks breakdown
        $positiveMarks = $questions->where('status','Correct')->sum('mark');
        $negativeMarks = $questions->where('status','Wrong')->sum('negative_mark');

        // Duration breakdown
        $totalDuration = $questions->sum('duration'); // from test_questions table
        $usedDuration = $testAnswers->sum('time_spent') ?? 0;
        $remainingDuration = max($totalDuration - $usedDuration, 0);

        // For comparison table (top rankers)
        $rankers = TestReport::where('exam_id', $examId)
                    ->where('done', true)
                    ->orderBy('marks', 'desc')
                    ->orderBy('duration', 'asc')
                    ->take(10)
                    ->get();

        return view('test.validate', compact('questions','correct','attended','totalMarks','fullMarks','percentage','exam','user','wrong','unattended','positiveMarks','negativeMarks',
    'totalDuration','usedDuration','remainingDuration','rankers'));
    }
    public function testReport($examId)
    {
        return redirect()->route('test.validate', ['exam_id' => $examId]);
    }
    public function importQuestions()
    {
        $start = 1;
        $end = 10;
        $exam_id = 2;
        $index = TestQuestion::max('priority');
        $filter = 562;
        // old database connection
        $id = 0;
        $query = DB::table('test_ques');
        if(!empty($filter)) {
            $query->where('test_id',$filter);
        }
        $old = $query->orderBy('id')->get();
        foreach ($old as $row) {
            $id++;
            if($start > $id) {
                continue;
            }
            if($end < $id) {
                continue;
            }
            $index++;
            // dd($row);
            $type = 'Choice';
            if ($row->questype == 'multiple') {
                $type = 'Multi-choice';
            } elseif ($row->questype == 'number') {
                $type = 'Numerical';
            }

            $label = ['A','B','C','D'];
            $options = [];
            if ($type !== 'Numerical') {
                for ($i=1; $i <= 4; $i++) { 
                    $x = 'ans'.$i;
                    $options[$i] = [
                        'key'   => (string) $label[$i-1],
                        'value' => (string) $row->$x,
                    ];
                }
            }
            $options = json_encode($options);

            $answer = null;
            $answer2 = null;

            if ($type == 'Choice') {
                $answer = (string)$row->answer;
            } elseif ($type == 'Multi-choice') {
                $correct = [];
                if ($row->mans1) $correct[] = 1;
                if ($row->mans2) $correct[] = 2;
                if ($row->mans3) $correct[] = 3;
                if ($row->mans4) $correct[] = 4;
                $answer = implode(',', $correct);
            } elseif ($type == 'Numerical') {
                $answer = $row->answer1;
                $answer2 = $row->answer2;
            }

            $mark = is_numeric($row->mark) ? $row->mark : 1;
            $neg  = is_numeric($row->neg_mark) ? $row->neg_mark : 0;

            DB::table('test_questions')->insert([
                'exam_id'        => $exam_id,
                'section_id'     => null,
                'question'       => $row->question,
                'options'        => $options ? json_encode($options) : null,
                'question_type'  => $type,
                'answer'         => $answer,
                'answer2'        => $answer2,
                'mark'           => $mark,
                'negative_mark'  => $neg,
                'duration'       => 0,
                'priority'       => $index,
                'solution'       => $row->solution,
                'difficulty'     => null,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }

        return 'Import Completed';
    }
}
