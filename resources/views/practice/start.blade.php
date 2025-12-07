@extends('layout.structure-noheader')
@section('xmt_tit', 'Practice Subject')
@section('xmt_des', 'Team of RKELECTRICAL GRID  for GATE /BARC/TRB/TNEB/ISRO & ESE Lectures')
@section('xmt_rob', 'index, follow')
@section('xmt_can', '/')


@push('headcss')
    <style>
        #answer_radio, #answer_check, #answer_input {
            display:none;
        }
        .c-lh-3 {
            line-height: 3rem;
        }
        .hoverBtn:hover{
            transform:scale(1.1);
            background-color:#cc9900 !important;
            transition:transform 0.2s ease-in;
        }
        @keyframes blink {
            0% { opacity: 1; }
            50% { opacity: 0; }
            100% { opacity: 1; }
        }

        .blink {
            animation: blink 1s infinite;
        }
        .questionDiv img{
            max-width:100% !important;
        }
    </style>
@endpush
@section('content')
    <div class="container-fluid mt-3">
        @if ($errors->any())
            {!! implode('', $errors->all('<div class="alert alert-danger" role="alert">:message</div>')) !!}
        @endif
        @if (session('success'))
            <div class="alert alert-success">
            {{ session('success') }}
            </div>
        @endif
        <form method="POST" action="{{ route('practice.submit') }}" enctype="multipart/form-data" autocomplete="off">
            @csrf
            @if($question)
                <input type="hidden" name="subject_id" id="subject_id" value="{{ old('subject_id',$question->subject_id) }}">
                <input type="hidden" name="topic_id" id="topic_id" value="{{ old('topic_id',$question->topic_id) }}">
                <input type="hidden" name="subtopic_id" id="subtopic_id" value="{{ old('subtopic_id',$question->subtopic_id) }}">
                <input type="hidden" name="priority" id="priority" value="{{ old('priority',$question->priority) }}">
                <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}">
                <input type="hidden" name="question_type" id="question_type1" value="{{$question->question_type}}">
                <input type="hidden" name="duration" id="duration" value="">
                <input type="hidden" name="question_id" id="question_id" value="{{$question->id}}">

                <div class="row question">
                    <div class=" col-12 col-md-9">
                        <h1 class="text-center bg-primary text-white fs-5 lh-lg py-1 rounded">{{ $question->practiceSubtopic->name}} Practice Questions</h1>
                        <div class="text-center d-flex justify-content-between align-items-center my-3">
                            <a href="{{ $prevSubtopic ? route('practice.start', [$question->subject_id, $question->topic_id, $prevSubtopic, 1]) : 'javascript:void(0)' }}"
                            class="btn btn-sm btn-secondary text-center align-middle {{ $prevSubtopic ? '' : 'disabled' }}">
                            Prev Subtopic
                            </a>
                            <a href="{{ $nextSubtopic ? route('practice.start', [$question->subject_id, $question->topic_id, $nextSubtopic, 1]) : 'javascript:void(0)' }}"
                            class="btn btn-sm btn-secondary {{ $nextSubtopic ? '' : 'disabled' }}">
                            Next Subtopic
                            </a>
                        </div>
                        <div class="card">
                            <div class="d-flex justify-content-between align-items-center px-3 py-3 border border-1">
                                <div ><span class="text-primary fw-bold">Question Type:</span> <span id="question_type">{{ $question->question_type }}</span></div>
                                <div ><span class="text-primary fw-bold">{{$question->mark <= 1 ? 'Mark' : 'Marks'}} :</span> {{ $question->mark }}</div>
                            </div>
                            <div class="card-header" style="margin-bottom:0">
                                <div class="d-flex justify-content-between align-items-center py-2 text-info fw-bold" >
                                    <p style="margin-bottom:0">Question: {{ $question->priority }}</p>
                                    <p class="float-right" id="timerDiv" style="margin-bottom:0">Time Left: <span id="timer" >{{ $question->duration }}</span></p>
                                </div>
                            </div>

                            <div class="card-body questionDiv pt-0">
                                <div>
                                    <p class="fw-bold lh-lg">{!! $question->question !!}</p>
                                </div>
                                @php
                                    $submittedAnswer = session()->get("answer_{$question->priority}");
                                @endphp
                                <div id="answer_radio">
                                    @php
                                        $optionLetters = ['1' => 'a' , '2' => 'b', '3' => 'c' , '4' => 'd'];
                                        $submittedAnswersKey = "practice_{$userData['userId']}_{$userData['subtopicId']}_{$question->priority}_answered";
                                        $submittedAnswers = session($submittedAnswersKey);
                                        $submittedAnswer = $submittedAnswers[$question->id] ?? null;
                                    @endphp
                                    @foreach ($optionTexts as $key => $text)
                                        @php
                                            $label = $optionLetters[$key] ?? ''; // Get label or empty string if out of bounds
                                            $isChecked = ($submittedAnswer == $key) ? 'checked' : '';
                                            @endphp
                                        <div class="form-check radioDiv rounded-3 " id="radioDiv{{ $key }}">
                                            <input class="form-check-input answerContainer" type="radio" name="choiceAnswer" id="choiceAnswer{{ $key }}" value="{{ $key }}" {{$isChecked}} data-correct="{{ $key == $question->answer1 ? 'true' : 'false' }}"  onchange="submitForm()" style="opacity:1">
                                            <label class="form-check-label d-flex" for="choiceAnswer{{ $key }}">
                                               {{$label}}) <span class="ps-2">{!! $text !!}</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                <div id="answer_check">
                                    @php
                                        $correctAnswers = explode(',', $question->answer1);
                                        $optionLetters = ['1' => 'a' , '2' => 'b', '3' => 'c' , '4' => 'd'];
                                        $submittedAnswersKey = "practice_{$userData['userId']}_{$userData['subtopicId']}_{$question->priority}_answered";
                                        $submittedAnswers = session($submittedAnswersKey);
                                        $submittedAnswer = is_array($submittedAnswers[$question->id] ?? null) ? $submittedAnswers[$question->id] : [];
                                    @endphp
                                    @foreach ($optionTexts as $key => $text)
                                        @php
                                            $label = $optionLetters[$key] ?? '';
                                            // Check if the option is among submitted answers
                                            $isChecked = in_array($key, $submittedAnswer) ? 'checked' : '';
                                            // Determine if the answer is correct
                                            $isCorrect = in_array($key, $correctAnswers) ? 'true' : 'false';
                                        @endphp
                                        <div class="form-check checkboxDiv">
                                            <input class="form-check-input answerContainer"
                                                type="checkbox"
                                                name="answerCheck{{ $key }}"
                                                id="answerCheck{{ $key }}"
                                                value="{{ $key }}"
                                                {{ $isChecked }}
                                                data-correct="{{ $isCorrect }}"
                                                onchange="submitForm()"
                                                style="opacity: 1;">
                                            <label class="form-check-label d-flex" for="answerCheck{{ $key }}">
                                                {{ $label }}) <span class="ps-2"> {!! $text !!}</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                <div id="answer_input">
                                    @php
                                        $submittedAnswersKey = "practice_{$userData['userId']}_{$userData['subtopicId']}_{$question->priority}_answered";
                                        $submittedAnswers = session($submittedAnswersKey);
                                        $submittedAnswer = $submittedAnswers[$question->id] ?? null;
                                    @endphp
                                    <input type="number" class="form-control" name="num_ans" step="any" placeholder="Enter your answer" value="{{ old('num_ans',$submittedAnswer) }}" data-correct="{{ $question->correct_answer }}" oninput="submitForm()">
                                    <div class="mt-3">
                                        <p class="rightAnswer text-info fw-bold" style="display:none;">
                                            Answer: {{($question->answer1) ?? '' }}  {{ $question->answer2 ? 'to' : '' }} {{ $question->answer2 ?? '' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer py-3">
                                <button type="submit" name="submit" value="back" class="btn btn-secondary">Back</button>
                                <button id="solutionBtn" type="button" class="btn btn-success d-none" onclick="solutionShow()">Solution</button>
                                @if($question->priority == $lastPriority)
                                    <button type="submit" name="submit" value="finish" class="btn btn-primary" id="next_btn">Finish</button>

                                    <!-- <a value="next" class="btn btn-primary" role="button" data-bs-toggle="modal" data-bs-target="#finishBtn">Finish</a> -->
                                @else
                                    <!-- <button type="submit" name="submit" value="savenext" class="btn btn-success d-none" id="savenext">Save & Next</button> -->
                                    <button type="submit" name="submit" value="next" class="btn btn-primary" id="next_btn">Next</button>
                                @endif
                            </div>
                        </div>
                        <div id="solutionDiv" class="d-none">
                            <div class="card p-3">
                                <div class="text-primary fw-bold mb-3">Solution:</div>
                                {!! $question->solution !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-3" style="height:300px;">
                        <div>
                            <div class="text-center text-primary fw-bold d-flex justify-content-center align-items-center gap-3">
                                <p><i class="fa-solid fa-circle-user fs-1"></i></p>
                                <p class="fs-5">Hi {{$user->name}} !!!</p>
                            </div>
                        </div>
                        <div class="card border-0 shadow py-4 px-2">
                            <p class="text-center">
                                <span class="btn btn-info" onclick="calcshow();">
                                    <img src="/image/calculator.png" style="width:40px;"> Calculator
                                </span>
                            </p>
                            <div class="card-header text-center py-3 mt-3 overflow-auto border-0" style="max-height:300px;">
                                <p class="text-center text-info fw-bold">Choose a Question</p>

                                @php
                                    $rightAnsweredQuestions = isset($practice) && $practice->right_answer_question_ids
                                        ? explode(',', $practice->right_answer_question_ids)
                                        : [];

                                    $wrongAnsweredQuestions = isset($practice) && $practice->wrong_answer_question_ids
                                        ? explode(',', $practice->wrong_answer_question_ids)
                                        : [];
                                @endphp

                                @foreach($QuestionList as $i)
                                    @php
                                        // Default button class
                                        $buttonClass = 'btn-warning'; // Default: Not attempted

                                        // Check if the question is in the right or wrong answer list
                                        if (in_array($i, $rightAnsweredQuestions)) {
                                            $buttonClass = 'btn-success'; // Correct answer
                                        } elseif (in_array($i, $wrongAnsweredQuestions)) {
                                            $buttonClass = 'btn-danger'; // Incorrect answer
                                        }
                                    @endphp

                                    <a href="{{ route('practice.start', [$question->subject_id, $question->topic_id, $question->subtopic_id, $i]) }}"
                                    class="btn {{ $buttonClass }} border-light shadow hoverBtn">
                                        {{ $i }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </form>
    </div>
    <div class="modal fade finishBtn" id="finishBtn" tabindex="-1" aria-labelledby="xfilterModalLabel" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <!-- <h5 class="modal-title text-center" id="xfilterModalLabel">Practice Test Result</h5> -->

                    <h5 class="text-primary text-center">{{ $question->practiceSubtopic->name}} Test Result</h5>
                    @if(session('userDetail'))
                        @php
                            $userDetail = session('userDetail');
                        @endphp
                        <table class="table table-striped border border-1 mt-4">
                            <tr>
                                <td class="text-center fw-bold">Total Attended</td>
                                <td class="text-center fw-bold">{{ $userDetail->answered }}</td>
                            </tr>
                            <tr class="">
                                <td class="text-center text-success fw-bold">Right Answer</td>
                                <td class="text-center text-success  fw-bold">{{ $userDetail->right_answer }}</td>
                            </tr>
                            <tr class="">
                                <td class="text-center text-danger fw-bold">Wrong Answer</td>
                                <td class="text-center text-danger fw-bold">{{ $userDetail->wrong_answer }}</td>
                            </tr>
                        </table>
                        <!-- <div>
                            <p>Answered: {{ $userDetail->answered }}</p>
                            <p>Right Answer: {{ $userDetail->right_answer }}</p>
                            <p>Wrong Answer: {{ $userDetail->wrong_answer }}</p>
                        </div> -->
                    @endif
                </div>
                <div class="text-center pb-3">
                    <a href="{{route('practice.subject')}}" class="btn btn-success">Go Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('endjs')
@if(session('show_modal'))
<script>
    $(document).ready(function(){
        $('#finishBtn').modal('show');
    });
</script>
@endif
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var question_type = document.getElementById('question_type').textContent;
        var answer_check = document.getElementById('answer_check');
        var answer_radio = document.getElementById('answer_radio');
        var answer_input = document.getElementById('answer_input');
        // var radioDiv = document.getElementById('radioDiv');

        answer_check.style.display = 'none';
        answer_radio.style.display = 'none';
        answer_input.style.display = 'none';

        if (question_type === 'Multi-choice') {
            answer_check.style.display = 'block';
        } else if (question_type === 'Choice') {
            answer_radio.style.display = 'block';
        } else if (question_type === 'Numerical') {
            answer_input.style.display = 'block';
        }

        if (question_type === 'Choice') {
            var radioButtons = document.querySelectorAll('#answer_radio input[type="radio"]');
            radioButtons.forEach(function(radio) {
                radio.addEventListener('click', function() {
                    var selectedDiv = this.closest('.radioDiv');
                    var isCorrect = this.getAttribute('data-correct') === 'true';

                    document.querySelectorAll('.radioDiv').forEach(function(div) {
                        div.classList.remove('alert-success', 'alert-danger');
                    });
                    var correctDiv = document.querySelector('#answer_radio .radioDiv [data-correct="true"]').closest('.radioDiv');
                    correctDiv.classList.add('alert-success');

                    if (isCorrect) {
                        selectedDiv.classList.add('alert-success');
                    } else {
                        selectedDiv.classList.add('alert-danger');
                    }

                    var correctDiv = document.querySelector('#answer_radio .radioDiv [data-correct="true"]').closest('.radioDiv');
                });
            });
        }
        else if (question_type === 'Multi-choice') {
            var checkboxDivs = document.querySelectorAll('#answer_check .checkboxDiv');

            checkboxDivs.forEach(function(div) {
                var checkbox = div.querySelector('input[type="checkbox"]');
                checkbox.addEventListener('change', function() {
                    var allDivs = document.querySelectorAll('#answer_check .checkboxDiv');
                    var correctCount = 0;
                    var selectedCount = 0;

                    // Instant feedback for the selected checkbox
                    if (checkbox.checked) {
                        if (checkbox.getAttribute('data-correct') === 'true') {
                            div.classList.add('alert-success');
                        } else {
                            div.classList.add('alert-danger');
                        }
                    } else {
                        div.classList.remove('alert-success', 'alert-danger');
                    }

                    allDivs.forEach(function(div) {
                        var cb = div.querySelector('input[type="checkbox"]');
                        if (cb.checked) {
                            selectedCount++;
                            if (cb.getAttribute('data-correct') === 'true') {
                                correctCount++;
                            }
                        }
                    });

                    // Delay before showing all answers
                    setTimeout(function() {
                        allDivs.forEach(function(div) {
                            var cb = div.querySelector('input[type="checkbox"]');
                            if (cb.getAttribute('data-correct') === 'true') {
                                div.classList.add('alert-success');
                            } else if (cb.checked) {
                                div.classList.add('alert-danger');
                            }
                        });

                        if (correctCount !== selectedCount && selectedCount > 0) {
                        }
                    }, 2000); // 2-second delay before finalizing the feedback
                });
            });
        }
        else if (question_type === 'Numerical') {
            var rightAnswer = document.querySelector('.rightAnswer');
            var numInput = document.getElementById('num_ans');

                // Show correct answer when input loses focus
            numInput.addEventListener('blur', function() {
                var isCorrect = this.value == this.getAttribute('data-correct');
                rightAnswer.style.display = "block"; // Show the correct answer

                if (isCorrect) {
                    rightAnswer.textContent = 'Correct Answer: ' + rightAnswer.textContent;
                    rightAnswer.style.color = 'green'; // Or any other style to indicate correct answer
                } else {
                    rightAnswer.textContent = 'Incorrect! Correct Answer: ' + rightAnswer.textContent;
                    rightAnswer.style.color = 'red'; // Or any other style to indicate incorrect answer
                }
            });
        }
    });

</script>
<script>
    function submitForm() {
        var rightAnswer = document.querySelector('.rightAnswer');
        if(rightAnswer){
            setTimeout(function() {
                rightAnswer.style.display = "block";
            }, 2000);
        }
        $('#solutionBtn').removeClass('d-none');
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var timeLeft = {{$question->duration}};
        var timerDiv = document.getElementById('timerDiv');
        var timerElement = document.getElementById('timer');
        var subject = document.getElementById('subject_id').value;
        var topic = document.getElementById('topic_id').value;
        var subtopic = document.getElementById('subtopic_id').value;
        var priority = document.getElementById('priority').value;

        if (timerElement) {
            var timer = setInterval(function() {
                timerElement.textContent = timeLeft;
                timeLeft--;
                if (timeLeft <= 10) {
                    timerDiv.classList.remove('text-info');
                    timerDiv.classList.add('text-danger', 'blink');
                }
                if (timeLeft < 0) {
                    clearInterval(timer);
                    document.getElementById('duration').value = {{$question->duration}} - timeLeft; // Capture the remaining time
                    document.forms[0].submit(); // Submit the current form
                }
            }, 1000);
        }

    // Handle the form submission and redirect after completion

    });



    $('#savenext').click(function() {
        $('#answer_field').removeClass('d-none');
    });

    $('#next_btn').click(function() {
        $(this).removeClass('btn-primary').addClass('btn-danger');
    });
</script>
<script>
    function calcshow()
    {
        url="/calcy";
        popupWindow = window.open(url,'Calculator','height=380,width=500,right=10,bottom=10,resizable=no,scrollbars=no,toolbar=no,menubar=no,location=no,directories=no,status=no');
    }
</script>
<script>
    function solutionShow() {
        $('#solutionDiv').removeClass('d-none');
    }
    function solutionHide() {
        $('#solutionDiv').addClass('d-none');
    }
</script>
@endpush
