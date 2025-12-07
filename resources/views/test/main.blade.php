@extends('layout.structure-noheader')

@section('xmt_tit', $exam->name)
@section('xmt_des', $exam->tagline)
@section('xmt_rob', 'index, follow')

@push('headcss')
    <style>
        .calcbtn {
            width: 100%;
            max-width: 200px;
        }
        .text-justify {
            text-align: justify;
        }
        .qbtn-div button {
            min-width: 48px;
        }
        .exam-column-scroll * {
            max-width: 100% !important;
            box-sizing: border-box;
            overflow-x: auto;
        }
        @media (min-width: 768px) {
            .exam-column-scroll {
                height: calc(100vh - 40px);
                overflow-y: auto;
            }
        }
        .disabled-area {
            filter: blur(4px);
            pointer-events: none;
            user-select: none;
            opacity: 0.7;
        }
    </style>
    <style>
        .option-card {
            cursor: pointer;
            transition: 0.15s;
            background: #f8f9fa;
        }
        .option-card:hover {
            background: #e7f0ff;
            border-color: #0d6efd;
        }
        .option-selected {
            background: #dce9ff !important;
            border-color: #0d6efd !important;
            box-shadow: 0 0 0 2px rgba(13, 110, 253, .25);
        }
        .option-label {
            width: 32px;
            text-align: center;
            margin-top: 4px;
        }
    </style>
@endpush
@section('content')
<div class="container-fluid py-3">
    <div class="row">
        <div class="col-12 col-md-9">
            <div class="exam-column-scroll" id="left_panel">
                <div class="text-center">
                    <h2 class="bg-primary fs-4 fw-bold text-light p-2 m-0">{{ $exam->name }}</h2>
                </div>
                <div id="main_div" class="test-box">
                    @foreach($questions as $q)
                        @php
                            $testAnswer = $q->testAnswer;
                            $qtype = $q->question_type;
                            $savedAnswer = $testAnswer ? $testAnswer->answer : null;
                            $savedArray = ($savedAnswer && $qtype === 'Multi-choice')
                                            ? explode(',', $savedAnswer)
                                            : [];
                            $opts = [];
                            if (!empty($q->options)) {
                                if (is_string($q->options)) {
                                    $opts = json_decode($q->options, true) ?: [];
                                } elseif (is_array($q->options)) {
                                    $opts = $q->options;
                                }
                            }
                            if($savedAnswer) {
                                $q_status = 'answered';
                            } else if(!empty($testAnswer->review)){
                                $q_status = 'review';
                            } else if($testAnswer){
                                $q_status = 'not_answered';
                            } else {
                                $q_status = 'not_visited';
                            }
                        @endphp

                        <div class="question-block d-none" id="question_block_{{ $q->priority }}">
                            <div class="card border-0 shadow-sm mb-3 rounded-3">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <div class="text-info p-2">
                                                <span class="fw-semibold">Question Type :</span> {{ $qtype }}
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="float-right p-2">
                                                Mark : {{ $q->mark }}
                                                &nbsp;|&nbsp;
                                                Negative :
                                                <span class="text-danger">
                                                    {{ $q->negative_mark > 0 ? $q->negative_mark : '-' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body p-0">
                                    <div class="p-2 ps-3">
                                        <strong class="text-primary fs-6">
                                            <i class="fa-solid fa-hashtag me-1"></i>
                                            Question No: {{ $q->priority }}
                                        </strong>

                                        @if($q->difficulty)
                                            <span class="badge bg-info text-light">
                                                {{ ucfirst($q->difficulty) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="question-text mb-3 p-2 ps-3">
                                {!! $q->question !!}
                            </div>

                            <input type="hidden"
                                class="selected-answer"
                                id="answer_field_{{ $q->priority }}"
                                data-qid="{{ $q->id }}"
                                value="{{ $savedAnswer }}">
                            <input type="hidden"
                                class="q_status"
                                id="q_status_{{ $q->priority }}"
                                value="
                                {{ $testAnswer }}
                                 ">

                            <div class="options-list">
                                @if($qtype === 'Numerical')
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Enter Answer</label>
                                            <input type="number"
                                                class="form-control numeric-input"
                                                data-question="{{ $q->priority }}"
                                                placeholder="Type your answer"
                                                value="{{ $savedAnswer }}">
                                        </div>
                                    </div>
                                @endif

                                @if($qtype === 'Choice' || $qtype === 'Multi-choice')
                                    @foreach($opts as $idx => $opt)
                                        @php
                                            $label = $opt['key'] ?? $idx;
                                            $html  = $opt['value'];

                                            $isSelected =
                                                ($qtype === 'Choice' && $savedAnswer == $idx)
                                                || ($qtype === 'Multi-choice' && in_array($idx, $savedArray));
                                        @endphp

                                        <div class="option-card border rounded p-3 mb-2 selectable-option {{ $isSelected ? 'option-selected' : '' }}"
                                            data-question="{{ $q->priority }}"
                                            data-value="{{ $idx }}"
                                            data-type="{{ $qtype }}"
                                            style="cursor:pointer; transition:0.2s">

                                            <strong class="me-2">{{ $label }}.</strong>
                                            <span>{!! $html !!}</span>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                        </div>
                    @endforeach
                </div>
                <div id="nav_div" class="mt-4 mb-5">
                    <button class="btn btn-secondary" id="btn_back">Back</button>
                    <button class="btn btn-info" id="btn_review_next">Mark for Review & Next</button>
                    <button class="btn btn-warning" id="btn_clear">Clear</button>
                    <button class="btn btn-success" id="btn_save_next">Save & Next</button>
                    <button class="btn btn-info d-none" id="btn_review_last">Mark for Review</button>
                    <button class="btn btn-success d-none" id="btn_save_last">Save</button>
                </div>
                <div id="errorDiv"></div>
            </div>
        </div>

        <div class="col-12 col-md-3 exam-column-scroll" id="qbtn_col">
            <div id="right_panel">
                <div id="timer" class="fw-bold fs-4 text-center"></div>
                <div class="my-3">
                    <div class="text-center mb-2">
                        <strong class="text-primary"><i class="fa-solid fa-circle-user"></i> {{ $user->name }}</strong>
                    </div>
                </div>
                <p class="text-center">
                    <span class="btn btn-info calcbtn rounded-pill" onclick="calcshow();">
                        <img src="/image/calculator.png" style="width:30px;"> Calculator
                    </span>
                </p>
                <div class="card border-0 shadow py-4 px-2">
                    <div class="row">
                        <div class="col-6">
                            <p><button class="btn btn-sm btn-success m-1">N</button> <small>Answered</small></p>
                            <p><button class="btn btn-sm btn-info m-1">N</button> <small>Marked Review</small></p>
                        </div>
                        <div class="col-6">
                            <p><button class="btn btn-sm btn-danger m-1">N</button> <small>Not Answered</small></p>
                            <p><button class="btn btn-sm btn-warning m-1">N</button> <small>Not visited</small></p>
                        </div>
                    </div>
                    <h6 class="text-primary text-center mt-2">Choose a Question</h6>
                    <div id="question_palette" class="mt-2 text-center qbtn-div">
                        @foreach($questions as $index => $q)
                            @php
                                $qNo = $index + 1;
                                $testAnswer = $q->testAnswer;
                                $q_status = ($testAnswer && $testAnswer->status) ? $testAnswer->status : 'not_visited';
                            @endphp

                            <button 
                                class="btn 
                                    @if($q_status == 'answered') btn-success
                                    @elseif($q_status == 'review') btn-info
                                    @elseif($q_status == 'not_answered') btn-danger
                                    @else btn-warning 
                                    @endif 
                                    my-2 mx-1"
                                onclick="loadQuestion({{ $qNo }});"
                                id="qbtn_{{ $qNo }}">
                                {{ $qNo }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <button class="btn btn-success w-100" id="submit_test_btn">
                    Submit & Evaluate
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="timeout_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h4>Time's up!</h4>
                <p>Your test will be submitted automatically.</p>
                <button class="btn btn-danger" id="timeout_submit_btn">Submit Now</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="mismatch_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Please Review</h5>
            </div>
            <div class="modal-body">
                These questions have changed: 
                <strong id="mismatch_list"></strong>
            </div>
            <div class="modal-footer">
                <button id="confirm_submit_btn" class="btn btn-primary">Confirm & Submit</button>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="exam_id" value="{{ $exam->id }}">
<input type="hidden" id="user_id" value="{{ $user->id }}">
<input type="hidden" id="total_questions" value="{{ $totalQuestions }}">
<input type="hidden" id="current_q" value="1">
<input type="hidden" id="timer_seconds" value="{{ $timer }}">
<input type="hidden" id="confirm_submission" value="">
@endsection
@push('endjs')
<script>
    let remaining = parseInt(document.getElementById("timer_seconds").value);

    function updateTimer() {
        if (remaining <= 0) {
            document.getElementById("timer").innerText = "00:00";
            $("#left_panel").addClass("disabled-area");
            $("#right_panel").addClass("disabled-area");
            let timeoutModal = new bootstrap.Modal(document.getElementById("timeout_modal"));
            timeoutModal.show();
            setTimeout(() => submitTest(), 1500);
            return;
        }

        let min = Math.floor(remaining / 60);
        let sec = remaining % 60;

        document.getElementById("timer").innerText =
            `${min.toString().padStart(2,'0')}:${sec.toString().padStart(2,'0')}`;

        remaining--;
        setTimeout(updateTimer, 1000);
    }

    updateTimer();
</script>
<script>
        let currentQ = 1;
        let totalQ   = parseInt($("#total_questions").val());
        let examId   = parseInt($("#exam_id").val());
        let userId   = parseInt($("#user_id").val());
        loadQuestion(1);
        function loadQuestion(qNo) {
            currentQ = qNo;
            $(".question-block").addClass("d-none");
            $("#question_block_" + qNo).removeClass("d-none");
            if (qNo === totalQ) {
                $("#btn_save_next").addClass("d-none");
                $("#btn_review_next").addClass("d-none");
                $("#btn_save_last").removeClass("d-none");
                $("#btn_review_last").removeClass("d-none");
            } else {
                $("#btn_save_next").removeClass("d-none");
                $("#btn_review_next").removeClass("d-none");
                $("#btn_save_last").addClass("d-none");
                $("#btn_review_last").addClass("d-none");
            }
            scrollToTop();
        }

        function saveAnswer(answer, review, action) {
            let status = "not_answered";
            if (review) {
                status = "review";
            } else if (answer !== null && answer !== "") {
                status = "answered";
            }

            $("#q_status_" + currentQ).val(status);
            updatePaletteColor(currentQ, status);

            $.ajax({
                url: "/test/save-answer",
                type: "POST",
                data: {
                    exam_id: examId,
                    question_id: $("#answer_field_" + currentQ).data("qid"),
                    user_id: userId,
                    answer: answer,
                    status: status,
                    _token: "{{ csrf_token() }}"
                },
                success: function (res) {
                    if (res.status === "timeout") {
                        submitTest();
                        return;
                    }
                    console.log("Saved: Q" + currentQ);
                }
            });

            if (action === "next" && currentQ < totalQ) {
                loadQuestion(currentQ + 1);
            }
        }

        $("#btn_back").on("click", function(){
            if (currentQ > 1) loadQuestion(currentQ - 1);
        });

        $("#btn_clear").on("click", function(){
            $("#answer_field_" + currentQ).val("");
            $('.selectable-option[data-question="'+ currentQ +'"]').removeClass("option-selected");

            $("#q_status_" + currentQ).val("not_answered");
            updatePaletteColor(currentQ, "not_answered");

            saveAnswer("", false, "stay");
        });

        $("#btn_save_next").on("click", function(){
            let ans = $("#answer_field_" + currentQ).val();
            saveAnswer(ans, false, "next");
        });

        $("#btn_review_next").on("click", function () {
            let ans = $("#answer_field_" + currentQ).val();
            saveAnswer(ans, true, "next");
        });

        $("#btn_save_last").on("click", function () {
            let ans = $("#answer_field_" + currentQ).val();
            saveAnswer(ans, false, "save_only");
        });

        $("#btn_review_last").on("click", function () {
            let ans = $("#answer_field_" + currentQ).val();
            saveAnswer(ans, true, "review_only");
        });

        function updatePaletteColor(qNo, status) {
            let btn = $("#qbtn_" + qNo);

            btn.removeClass("btn-warning btn-success btn-danger btn-info");

            switch(status){
                case "answered": btn.addClass("btn-success"); break;
                case "review": btn.addClass("btn-info"); break;
                case "not_answered": btn.addClass("btn-danger"); break;
                default: btn.addClass("btn-warning");
            }
        }

        $(document).on("click", ".selectable-option", function () {
            let qid  = $(this).data("question");
            let type = $(this).data("type");
            let val  = $(this).data("value");
            let answerField = $("#answer_field_" + qid);

            if (type === "Choice") {
                $('.selectable-option[data-question="'+qid+'"]').removeClass("option-selected");
                $(this).addClass("option-selected");
                answerField.val(val);
            }

            else if (type === "Multi-choice") {
                $(this).toggleClass("option-selected");

                let selected = [];
                $('.selectable-option.option-selected[data-question="'+qid+'"]').each(function(){
                    selected.push($(this).data("value"));
                });

                answerField.val(selected.join(","));
            }
        });

        $(document).on("input", ".numeric-input", function () {
            let qid = $(this).data("question");
            $("#answer_field_" + qid).val($(this).val()); 
        });

function jumpTo(qNo){
    loadQuestion(qNo);
}

function scrollToTop(){
    window.scrollTo({ top: 0, behavior: "smooth" });
}

function calcshow() {
    let url="/test/calcy"; 
    window.open(url,'Calculator','height=380,width=500,right=10,bottom=10,resizable=no,scrollbars=no');
}
window.calcshow = calcshow;
</script>
<script>
    $("#timeout_submit_btn").on("click", function () {
        submitTest();
    });
    $("#submit_test_btn").on("click", function () {
        submitTest();
    });
    $("#confirm_submit_btn").on("click", function () {
        $("#confirm_submission").val("confirm");
        submitTest();
    });
    function submitTest(confirmMismatch = false) {
        let answers = [];
        $(".selected-answer").each(function () {
            let qPriority = $(this).attr("id").replace("answer_field_", "");
            answers.push({
                priority: qPriority,
                question_id: $(this).data("qid"),
                answer: $(this).val(),
                status: $("#q_status_" + qPriority).val()
            });
        });

        $.ajax({
            url: "/test/submit",
            type: "POST",
            data: {
                exam_id: examId,
                user_id: userId,
                answers: answers,
                confirm: $("#confirm_submission").val(),
                _token: "{{ csrf_token() }}"
            },
            success: function (res) {
                if (res.status === "confirm") {
                    $("#confirm_submission").val("pending");
                    $("#mismatch_list").text(res.questions.join(", "));
                    let mismatchModal = new bootstrap.Modal(document.getElementById("mismatch_modal"));
                    mismatchModal.show();
                    return;
                }
                if (res.status === "timeout") {
                    window.location.href = res.redirect;
                    return;
                }
                if (res.redirect) {
                    window.location.href = res.redirect;
                }
            }
        });
    }
</script>
@endpush
