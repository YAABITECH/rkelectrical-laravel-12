<?php

namespace App\Http\Controllers;

use App\Models\TestExam;
use App\Models\TestQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Metadata\Test;

class TestQuestionController extends Controller
{
    public function manage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'exam_id' => 'required|integer|exists:' . (new TestExam())->getTable() . ',id',
            'priority' => 'nullable|integer',
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator->errors());
        }
        $exam_id = $request->input('exam_id');
        $priority = $request->input('priority', 1);
        $exam = TestExam::find('exam_id');
        $maxQuestion = $QuestionList = null;
        $questions = TestQuestion::where('exam_id', $exam_id)->get();
        $maxQuestion = $questions->max('priority');

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
            return redirect()->route('admin.test.question.manage', [
                'exam' => $exam_id,
                'priority' => $priority,
            ])->with('error', 'Question number changed due to invalid. ');
        }

        $QuestionList = $questions->pluck('priority')->toArray();
        $question = TestQuestion::where([
            'exam_id' => $exam_id,
            'priority' => $priority,
        ])->first();

        if (!$question) {
            $question = new TestQuestion([
                'exam_id' => $exam_id,
                'priority' => $priority,
            ]);
        }
        $defaults = [
            'duration' => 30,
            'mark' => 1,
            'negative_mark' => 0,
        ];
        return view('admin.test.question.manage',compact('question','defaults','maxQuestion','QuestionList'));
    }
    public function questionSubmit(Request $request)
    {
        if ($request->input('submit') === 'delete' && $request->input('question_id')) {
            $q = TestQuestion::find($request->input('question_id'));
            if ($q) {
                $q->delete();
                return back()->with('success', 'Question deleted successfully');
            }
            return back()->with('error', 'Question not found');
        }
        $validator = Validator::make($request->all(), [
            'exam_id'       => 'required|integer|exists:test_exams,id',
            'section_id'    => 'nullable|integer|exists:test_sections,id',
            'priority'      => 'required|integer|min:1',
            'question_type' => 'required|in:Choice,Multi-choice,Numerical',
            'question'      => 'required',
            'mark'          => 'nullable|numeric',
            'negative_mark' => 'nullable|numeric',
            'duration'      => 'nullable|integer|min:0',
            'difficulty'    => 'nullable|in:easy,medium,hard',
            'solution'      => 'nullable',
            'submit'        => 'nullable|in:save,savenext,delete',
            'min_answer'    => 'nullable',
            'max_answer'    => 'nullable',
        ]);
        // Numerical validation
        if ($request->question_type === 'Numerical') {
            $validator->after(function ($v) use ($request) {
                $min = $request->input('min_answer', null);
                $max = $request->input('max_answer', null);

                if ($min === null || trim((string)$min) === '') {
                    $v->errors()->add('min_answer', 'Minimum answer required.');
                } elseif (!is_numeric($min)) {
                    $v->errors()->add('min_answer', 'Minimum answer must be numeric.');
                }

                if ($max !== null && $max !== '' && !is_numeric($max)) {
                    $v->errors()->add('max_answer', 'Maximum answer must be numeric when provided.');
                }
            });
        }
        // Choice & Multi-choice option validation
        if ($request->question_type !== 'Numerical') {
            $validator->after(function ($v) use ($request) {
                $keys = $request->input('option_key', []);
                $vals = $request->input('option_value', []);
                if (!is_array($keys) || count($keys) < 2) {
                    $v->errors()->add('options', 'At least two options are required.');
                    return;
                }

                // ensure each value exists and not empty
                foreach ($keys as $idx => $k) {
                    $val = $vals[$idx] ?? null;
                    if (!isset($k) || trim((string)$k) === '') {
                        $v->errors()->add("option_key.{$idx}", "Option label for option {$idx} is required.");
                    }
                    if (!isset($val) || trim((string)$val) === '') {
                        $v->errors()->add("option_value.{$idx}", "Option value for option {$idx} is required.");
                    }
                }
            });
        }
        // Choice answer validation
        if ($request->question_type === 'Choice') {
            $validator->after(function ($v) use ($request) {
                $choice = $request->input('choiceAnswer', null);
                if ($choice === null || $choice === '') {
                    $v->errors()->add('choiceAnswer', 'Please select the correct answer.');
                }
            });
        }
        // Multi-choice answer validation
        if ($request->question_type === 'Multi-choice') {
            $validator->after(function ($v) use ($request) {
                $selected = $request->input('answers', []);
                if (!is_array($selected) || empty(array_filter($selected, fn($x) => trim((string)$x) !== ''))) {
                    $found = false;
                    $all = $request->all();
                    foreach ($all as $k => $val) {
                        if (preg_match('/^answerCheck(\d+)$/', $k) && trim((string)$val) !== '') {
                            $found = true;
                            break;
                        }
                    }
                    if (!$found) {
                        $v->errors()->add('answers', 'Please select at least one correct option.');
                    }
                }
            });
        }
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = [
            'exam_id'       => $request->input('exam_id'),
            'section_id'    => $request->input('section_id', null),
            'question'      => $request->input('question'),
            'question_type' => $request->input('question_type'),
            'duration'      => (int) $request->input('duration', 0),
            'priority'      => (int) $request->input('priority', 0),
            'mark'          => $request->input('mark', 1),
            'negative_mark' => $request->input('negative_mark', 0),
            'solution'      => $request->input('solution', null),
            'difficulty'    => $request->input('difficulty', null),
        ];
        if(empty($data['negative_mark'])) {
            $data['negative_mark'] = 0;
        }
        // Save options JSON
        if ($request->input('question_type') !== 'Numerical') {
            $keys = $request->input('option_key', []);
            $vals = $request->input('option_value', []);

            ksort($keys);
            ksort($vals);

            $options = [];
            foreach ($keys as $i => $label) {
                $options[$i] = [
                    'key'   => (string) ($label ?? ''),
                    'value' => (string) ($vals[$i] ?? ''),
                ];
            }
            $data['options'] = json_encode($options);
        } else {
            $data['options'] = json_encode([]);
        }
        // Save answers
        if ($request->input('question_type') === 'Numerical') {
            $data['answer']  = $request->input('min_answer', null);
            $data['answer2'] = $request->input('max_answer', null);
        } elseif ($request->input('question_type') === 'Choice') {
            $data['answer']  = (string) $request->input('choiceAnswer', '');
            $data['answer2'] = null;
        } else {
            $selected = $request->input('answers', []);
            if (!is_array($selected) || empty($selected)) {
                $selected = [];
                $all = $request->all();
                foreach ($all as $k => $val) {
                    if (preg_match('/^answerCheck(\d+)$/', $k, $m) && trim((string)$val) !== '') {
                        $selected[] = (string)$val;
                    }
                }
            }
            $selected = array_values(array_filter($selected, fn($x) => trim((string)$x) !== ''));
            $data['answer']  = empty($selected) ? '' : implode(',', $selected);
            $data['answer2'] = null;
        }

        // dd($data);
        // ---------------- Create / Update ----------------
        if ($request->input('question_id')) {
            TestQuestion::whereId($request->input('question_id'))->update($data);
        } else {
            TestQuestion::create($data);
        }
        // ---------------- Redirect ----------------
        $nextPriority = (int) $request->input('priority', 0);
        if ($request->input('submit') === 'savenext') {
            $nextPriority++;
        }

        return redirect()
            ->route('admin.test.question.manage', [
                'exam_id'    => $data['exam_id'],
                'section' => $data['section_id'],
                'priority'=> $nextPriority
            ])
            ->with('success', 'Question saved successfully!');
    }
}
