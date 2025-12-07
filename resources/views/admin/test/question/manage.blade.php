@extends('layout.admin.structure')
@section('xmt_tit', 'Manage Test Question | Admin')

@push('headcss')
<script src="{{asset('/assets/tinymce/tinymce.min.js')}}" referrerpolicy="origin"></script>
<link rel="stylesheet" href="/css/tinymce-editor-v1-0.css">
<style>
    #answer_radio, #answer_check, #answer_input {
        display:none;
    }
    .c-lh-3 {
        line-height: 3rem;
    }
</style>
@endpush
@section('content')
<form action="{{ route('admin.test.question.submit') }}" method="post" enctype="multipart/form-data" autocomplete="off">
    @csrf
    <div class="section">
        <div class="container py-3">
            <h1 class="text-center text-primary fs-5 lh-lg">Manage Test Question</h1>
            <div class="text-center m-1 mb-3">
                <a class="btn btn-primary btn-sm" href="{{ route('admin.test.series.index') }}" role="button"><i class="fa-solid fa-list"></i></a>
                <a class="btn btn-primary btn-sm" href="{{ route('admin.test.exam.index',['series_id' => $question->getExam->id ?? null]) }}" role="button"><i class="fa-solid fa-file-lines"></i></a>
                <a class="btn btn-primary btn-sm" href="{{ route('admin') }}" role="button"><i class="fa-solid fa-home"></i></a>
            </div>
            @if ($errors->any())
                {!! implode('', $errors->all('<div class="alert alert-danger" role="alert">:message</div>')) !!}
            @endif
            @if (session('success'))
                <div class="alert alert-success">
                {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                {{ session('error') }}
                </div>
            @endif
            <input type="hidden" name="exam_id" id="exam_id" value="{{ old('exam_id') ?? $question->exam_id ?? request('exam_id') }}">
            <input type="hidden" name="priority" id="priority" value="{{ old('priority',$question->priority) }}">
            <div class="d-inline-flex">
                <div class="me-4">
                    <a class="btn btn-primary btn-sm" href="#submit_div">Question {{ $question->priority }}</a>
                </div>
                <div class="align-self-center">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="question_type" id="inlineRadio1" value="Choice" onclick="questionType('Choice');"{{ old('question_type', $question->question_type) == 'Choice' || (empty(old('question_type')) && empty($question->question_type)) ? ' checked' : '' }}>
                        <label class="form-check-label" for="inlineRadio1">Choice</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="question_type" id="inlineRadio3" value="Numerical" onclick="questionType('Numerical');"{{ old('question_type',$question->question_type) === 'Numerical' ? ' checked' : '' }}>
                        <label class="form-check-label" for="inlineRadio3">Number</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="question_type" id="inlineRadio2" value="Multi-choice" onclick="questionType('Multi-choice');"{{ old('question_type',$question->question_type) === 'Multi-choice' ? ' checked' : '' }}>
                        <label class="form-check-label" for="inlineRadio2">Multi-choice</label>
                    </div>
                </div>
            </div>
            <div>
                <div class="form-group py-3">
                    <label for="question" class="form-label text-primary fw-bold">Question *</label>
                    <textarea name="question" id="question" rows="5" class="yaTinyEditor" placeholder="Enter your question" novalidate>{{ old('question',$question->question) }}</textarea>
                </div>
                <div id="optionsdiv">
                    <div class="row" id="options-container">
                        @php
                            if (!isset($question) || empty($question->options)) {
                                $options = [];
                            } elseif (is_string($question->options)) {
                                $options = json_decode($question->options, true) ?: [];
                            } elseif (is_array($question->options)) {
                                $options = $question->options;
                            } else {
                                $options = [];
                            }

                            if (empty($options)) {
                                $options = [
                                    1 => ['key' => 'A', 'value' => ''],
                                    2 => ['key' => 'B', 'value' => ''],
                                    3 => ['key' => 'C', 'value' => ''],
                                    4 => ['key' => 'D', 'value' => ''],
                                ];
                            }
                        @endphp
                        @foreach($options as $num => $opt)
                            <div class="col-12 col-md-6 option-item" data-num="{{ $num }}">
                                <div class="form-group py-3 border rounded p-3">
                                    <label class="form-label text-secondary fw-bold">
                                        Option {{ $num }}
                                    </label>

                                    <input type="text"
                                        name="option_key[{{ $num }}]"
                                        class="form-control mb-2"
                                        placeholder="Option label"
                                        value="{{ old('option_key.'.$num, $opt['key']) }}">

                                    <textarea name="option_value[{{ $num }}]"
                                        id="option_value_{{ $num }}"
                                        rows="5"
                                        class="yaTinyEditor"
                                        placeholder="Enter your option">{{ old('option_value.'.$num, $opt['value']) }}</textarea>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="py-3">
                        <button type="button" id="addOption" class="btn btn-primary"><i class="fw-bold fa-solid fa-plus"></i> Add Option</button>
                        <button type="button" id="removeOption" class="btn btn-danger"><i class="fw-bold fa-solid fa-xmark"></i> Remove Last</button>
                    </div>
                </div>
                @php
                    $opts = is_array($question->options) ? $question->options : json_decode($question->options, true);

                    if (empty($opts)) {
                        $opts = [
                            1 => ['key' => 'A', 'value' => ''],
                            2 => ['key' => 'B', 'value' => ''],
                            3 => ['key' => 'C', 'value' => ''],
                            4 => ['key' => 'D', 'value' => ''],
                        ];
                    }
                    $optionCount = count($opts);
                @endphp
                <div id="answer_radio" class="mb-3">
                    <div class="text-primary fw-bold">Answer *</div>
                    @for ($i = 1; $i <= $optionCount; $i++)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input"
                                type="radio"
                                name="choiceAnswer"
                                id="choiceAnswer{{ $i }}"
                                value="{{ $i }}"
                                {{ old('choiceAnswer', $question->answer) == $i ? 'checked' : '' }}>
                            <label class="form-check-label" for="choiceAnswer{{ $i }}">
                                {{ $i }}
                            </label>
                        </div>
                    @endfor
                </div>
                <div id="answer_check" class="mb-3">
                    <div class="text-primary fw-bold">Answer *</div>

                    @php
                        $oldMulti = old('answers', []);
                        $selectedAnswers = [];

                        if (!empty($question->answer) && $question->question_type == 'Multi-choice') {
                            $selectedAnswers = explode(',', $question->answer);
                        }

                        $selected = !empty($oldMulti) ? $oldMulti : $selectedAnswers;
                    @endphp

                    @for ($i = 1; $i <= $optionCount; $i++)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input"
                                type="checkbox"
                                name="answers[]"
                                id="answerCheck{{ $i }}"
                                value="{{ $i }}"
                                {{ in_array((string)$i, $selected) ? 'checked' : '' }}>
                            <label class="form-check-label" for="answerCheck{{ $i }}">
                                {{ $i }}
                            </label>
                        </div>
                    @endfor
                </div>
                <div id="answer_input">
                    <div class="text-primary fw-bold">Answer *</div>
                    <div class="row">
                        <div class="col form-group mb-3">
                            <label for="min_answer" class="form-label">Min *</label>
                            <input type="text" class="form-control" name="min_answer" id="min_answer" value="{{ old('min_answer',$question->answer) }}">
                        </div>
                        <div class="col form-group mb-3">
                            <label for="max_answer" class="form-label">Max</label>
                            <input type="text" class="form-control" name="max_answer" id="max_answer" value="{{ old('max_answer',$question->answer2) }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group py-3">
                        <label for="solution" class="form-label text-primary fw-bold">Solution</label>
                        <textarea name="solution" id="solution" rows="5" class="yaTinyEditor" placeholder="Enter your solution" novalidate>{{ old('solution',$question->solution) }}</textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-6 form-group mb-3">
                        <label for="mark" class="form-label">Marks *</label>
                        <input type="number" class="form-control" name="mark" id="mark" step="0.01" value="{{ old('mark') ?? $question->mark ?? $defaults['mark'] }}">
                    </div>
                    <div class="col-12 col-sm-6 form-group mb-3">
                        <label for="negative_mark" class="form-label">Negative Marks</label>
                        <input type="number" class="form-control" name="negative_mark" id="negative_mark" step="0.01" value="{{ old('negative_mark') ?? $question->negative_mark ?? $defaults['negative_mark'] }}">
                    </div>
                    <div class="col-12 col-sm-6 form-group mb-3">
                        <label for="duration" class="form-label">Duration (Secs) *</label>
                        <input type="number" class="form-control" name="duration" id="duration" value="{{ old('duration') ?? $question->duration ?? $defaults['duration'] }}">
                    </div>
                    <div class="col-12 col-sm-6 form-group mb-3">
                        <label for="difficulty" class="form-label">Difficulty</label>
                        <select class="form-select" name="difficulty" id="difficulty">
                            <option value="" disabled {{ old('difficulty', $question->difficulty ?? '') == '' ? 'selected' : '' }}>
                                Select Difficulty
                            </option>
                            @foreach(['easy','medium','hard'] as $value)
                                <option value="{{ $value }}"
                                    {{ old('difficulty', $question->difficulty ?? '') == $value ? 'selected' : '' }}>
                                    {{ ucfirst($value) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <p class="pb-3 text-center c-lh-3" id="submit_div">
                    <button type="submit" name="submit" value="savenext" class="btn btn-block btn-primary">Save &amp; Next</button>
                    <button type="submit" name="submit" value="save" class="btn btn-block btn-success">Save</button>
                    @if($question->id)
                    <input type="hidden" name="question_id" id="question_id" value="{{ $question->id }}">
                    <button type="submit" name="submit" value="delete" class="btn btn-block btn-danger">Delete</button>
                    @endif
                    <button type="button" onclick="location.reload();" class="btn btn-block btn-light">Clear</button>
                </p>
                <div class="pb-1">
                    <nav aria-label="Questions List">
                        <ul class="pagination justify-content-center">
                            <li class="page-item mx-1"><a href="{{ route('admin.test.question.manage', ['exam_id' => $question->exam_id, 'priority' => 1]) }}" class="btn btn-outline-primary border-light shadow{{ $question->priority == 1 ? ' disabled' : '' }}" aria-label="First"><i class="fas fa-angle-double-left"></i></a></li>
                            <li class="page-item mx-1"><a href="{{ route('admin.test.question.manage', ['exam_id' => $question->exam_id, 'priority' => ($question->priority-1)]) }}" class="btn btn-outline-primary border-light shadow{{ $question->priority == 1 ? ' disabled' : '' }}" aria-label="Previous"><i class="fas fa-chevron-left"></i></a></li>
                            <li class="page-item mx-1 active"><button class="btn btn-primary shadow">{{ $question->priority }}</button></li>
                            <li class="page-item mx-1"><a href="{{ route('admin.test.question.manage', ['exam_id' => $question->exam_id, 'priority' => ($question->priority+1)]) }}" class="btn btn-outline-primary border-light shadow{{ ($question->priority) > $maxQuestion ? ' disabled' : '' }}" aria-label="Next"><i class="fas fa-chevron-right"></i></a></li>
                            <li class="page-item mx-1"><a href="{{ route('admin.test.question.manage', ['exam_id' => $question->exam_id, 'priority' => ($maxQuestion+1)]) }}" class="btn btn-outline-primary border-light shadow{{ ($question->priority) > $maxQuestion ? ' disabled' : '' }}" aria-label="Last"><i class="fas fa-angle-double-right"></i></a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="section pt-3" style="background-color:#eeeeee;">
        <div class="container py-3">
            <div class="text-center c-lh-3">
                <a href="{{ route('admin.test.exam.edit', $question->getExam->id) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-title="Edit Test Exam">{{ $question->getExam->priority.'. '.$question->getExam->name }}</a>
            </div>
            <p class="text-center fw-bold mt-3">All Questions</p>
            <div class="text-center c-lh-3">
            @for ($i=1; $i<= ($maxQuestion+1); $i++)
                <a href="{{ route('admin.test.question.manage', ['exam_id' => $question->exam_id, 'priority' => $i]) }}" class="btn {{ !in_array($i, $QuestionList) ? 'btn-danger' : 'btn-dark' }} border-light shadow"{!! !in_array($i, $QuestionList) ? ' data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Not created"' : '' !!}>{{$i}}</a>
            @endfor
            </div>
        <div>
    <div>
</form>
<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
@endsection
@push('endjs')
<script src="/js/ya-tiny-editor-v1-0.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script>
    function questionType(type)
    {
        document.getElementById('answer_check').style.display = 'none';
        document.getElementById('answer_radio').style.display = 'none';
        document.getElementById('answer_input').style.display = 'none';
        document.getElementById('optionsdiv').style.display = 'block';
        if(type=='Numerical') {
            document.getElementById('answer_input').style.display = 'block';
            document.getElementById('optionsdiv').style.display = 'none';
        } else if(type=='Multi-choice') {
            document.getElementById('answer_check').style.display = 'block';
        } else {
            document.getElementById('answer_radio').style.display = 'block';
        }
    }
    document.addEventListener('DOMContentLoaded', function() {
        var radioButtons = document.getElementsByName('question_type');
        var checkedValue = '';
        for (var i = 0; i < radioButtons.length; i++) {
            if (radioButtons[i].checked) {
                checkedValue = radioButtons[i].value;
                break;
            }
        }
        questionType(checkedValue);
        refreshAnswerInputs();
    });
</script>
<script>
    let optionCount = document.querySelectorAll('.option-item').length;

    function reloadTinyMCE() {
        tinymce.remove('.yaTinyEditor');
        tinymce.init(editor_config);
    }

    document.getElementById('addOption').addEventListener('click', function () {
        optionCount++;
        let num = optionCount;

        document.getElementById('options-container').insertAdjacentHTML(
            "beforeend",
            `
            <div class="col-12 col-md-6 option-item" data-num="${num}">
                <div class="form-group py-3 border rounded p-3">
                    <label class="form-label text-secondary fw-bold">Option ${num}</label>

                    <input type="text" 
                        name="option_key[${num}]"
                        class="form-control mb-2"
                        placeholder="Option label">

                    <textarea name="option_value[${num}]"
                        id="option_value_${num}"
                        rows="5"
                        class="yaTinyEditor"
                        placeholder="Enter your option"></textarea>
                </div>
            </div>`
        );

        
        
        // Add answer radio/checkbox below
        let answerBox = document.querySelector("#answer_radio").style.display !== "none"
            ? document.querySelector("#answer_radio")
            : document.querySelector("#answer_check");

        answerBox.insertAdjacentHTML(
            "beforeend",
            `
            <div class="form-check form-check-inline answer-${num}">
                <input class="form-check-input"
                    type="${document.querySelector('input[name=question_type]:checked').value === 'Multi-choice' ? 'checkbox' : 'radio'}"
                    name="${document.querySelector('input[name=question_type]:checked').value === 'Multi-choice' ? 'answers[]' : 'choiceAnswer'}"
                    value="${num}">
                <label class="form-check-label">${num}</label>
            </div>`
        );
        reloadTinyMCE();
    });

    document.getElementById('removeOption').addEventListener('click', function () {
        if (optionCount <= 1) return;

        document.querySelector(`.option-item[data-num="${optionCount}"]`)?.remove();
        document.querySelector(`.answer-${optionCount}`)?.remove();

        optionCount--;

        reloadTinyMCE();
    });
</script>
@endpush
