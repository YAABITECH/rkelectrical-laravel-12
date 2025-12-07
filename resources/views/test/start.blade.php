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
        .disabled-area {
            filter: blur(4px);
            pointer-events: none;
            user-select: none;
            opacity: 0.7;
        }
    </style>
@endpush
@section('content')
<div class="container-fluid py-3">
    <div class="row">
        <div class="col-12 col-md-9">
            <div class="left-panel">
                <div class="text-center">
                    <h2 class="bg-primary fs-4 fw-bold text-light p-2 m-0">{{ $exam->name }}</h2>
                </div>
                <div id="main_div" class="test-box">
                    @if($exam->tagline)
                        <p class="text-muted text-center my-3">{{ $exam->tagline }}</p>
                    @endif
                    <div class="card shadow-sm border-0 mt-4">
                        <div class="card-header bg-info text-white rounded">
                            <h4 class="fs-5 mb-0"><i class="fa-solid fa-book-open-reader me-2"></i>General Instructions</h4>
                        </div>
                        <div class="card-body p-4 text-secondary">
                            <ul class="">
                                <li>Read all questions carefully.</li>
                                <li>Do not refresh or close the browser.</li>
                                <li>Timer begins once you click "Start Test".</li>
                            </ul>
                        </div>
                    </div>
                    <div class="card shadow-sm border-0 mb-4 rounded">
                        <div class="card-header bg-info text-white rounded">
                            <h4 class="fs-5 mb-0"><i class="fa-solid fa-circle-info me-2"></i>Test Details</h4>
                        </div>
                        <div class="card-body p-4">
                            <table class="table table-bordered align-middle mb-0">
                                <tbody class="fs-6">
                                    <tr><th>Duration</th><td>{{ $exam->duration > 0 ? $exam->duration.' seconds' : 'Not set' }} </td></tr>
                                    <tr><th>Total Questions</th><td>{{ $totalQuestions }}</td></tr>
                                    <tr><th>Total Marks</th><td>{{ $exam->marks > 0 ? $exam->marks : 'Not given' }}</td></tr>
                                    <tr>
                                        <th>Negative Marks</th>
                                        <td>
                                            @if($exam->has_negative_marks)
                                                <span class="badge bg-danger">Yes</span>
                                            @else
                                                <span class="badge bg-success">No</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr><th>Attempt Limit</th><td>{{ $exam->attempt_limit }}</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="text-center mt-4 mb-5">
                        <a href="{{ route('test.main', $exam->id) }}" id="start_test_btn" class="btn btn-lg btn-primary px-5 rounded-pill">
                            <i class="fa-solid fa-play me-2"></i>Start Test
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-3 disabled-area" id="qbtn_col">
            <div class="my-3">
                <div class="text-center mb-2">
                    <strong class="text-primary"><i class="fa-solid fa-circle-user"></i> User</strong>
                </div>
            </div>
            <p class="text-center">
                <span class="btn btn-info calcbtn rounded-pill">
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
                    @for($i=1; $i<=$totalQuestions; $i++)
                        <button 
                            class="btn btn-warning my-2 mx-1">
                            {{ $i }}
                        </button>
                    @endfor
                </div>
                <div class="mt-3">
                    <button class="btn btn-success w-100" id="submit_test_btn">
                        Submit & Evaluate
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection