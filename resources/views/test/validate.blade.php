@extends('layout.structure')

@section('xmt_tit', $exam->name)
@section('xmt_des', $exam->tagline)
@section('xmt_rob', 'index, follow')

@section('content')
<div class="container">
    <div class="card shadow-lg border-0 mb-4">
        <div class="card-body p-4">

            <h4 class="mb-3 text-primary fw-bold">
                <i class="fa-solid fa-chart-line me-2"></i>Test Performance Summary
            </h4>

            <table class="table table-hover align-middle">
                <tr>
                    <th>Attended</th>
                    <td class="fw-semibold text-info">{{ $attended }} / {{ $questions->count() }}</td>
                </tr>
                <tr>
                    <th>Correct Answers</th>
                    <td class="fw-semibold text-success">{{ $correct }} / {{ $questions->count() }}</td>
                </tr>
                <tr>
                    <th>Total Marks</th>
                    <td class="fw-semibold">{{ $totalMarks }} / {{ $fullMarks }}</td>
                </tr>
                <tr>
                    <th>Percentage</th>
                    <td class="fw-bold text-primary">{{ $percentage }}%</td>
                </tr>
            </table>
            <div class="row">
                <div class="col-md-6"><div id="chart_accuracy" style="height:300px"></div></div>
                <div class="col-md-6"><div id="chart_attended" style="height:300px"></div></div>
                <div class="col-md-6"><div id="chart_marks" style="height:300px"></div></div>
                <div class="col-md-6"><div id="chart_posneg" style="height:300px"></div></div>
                {{-- <div class="col-md-6"><div id="chart_duration" style="height:300px"></div></div> --}}
            </div>
        </div>
    </div>
    <div class="card shadow-lg border-0 mb-4">
        <div class="p-3 pb-4 text-center">
            <h2 class="fs-5 text-info my-3">All Questions</h2>
            @foreach($questions as $index => $q)
                @php
                    $qNo = $index + 1;
                    $status = $q->status;
                    switch ($status) {
                        case 'Correct':
                            $qBtnClr = 'success';
                            break;
                        case 'Wrong':
                            $qBtnClr = 'danger';
                            break;
                        default:
                            $qBtnClr = 'warning';
                            break;
                    }
                @endphp
                <button type="button" class="btn btn-{{ $qBtnClr }} m-1" onclick="goToQuestion('qs_{{ $q->id }}')">
                    {{ $qNo }}
                </a>
            @endforeach
        </div>
        <div class="card-footer">
            <div class="text-center p-3">
                <button class="btn btn-sm btn-success m-1">N</button> <small class="fw-semibold">Correct</small>
                <button class="btn btn-sm btn-danger m-1">N</button> <small class="fw-semibold">Wrong</small>
                <button class="btn btn-sm btn-warning m-1">N</button> <small class="fw-semibold">Not Attended</small>
            </div>
        </div>
    </div>
    @foreach($questions as $q)
        @php
            $ua = $q->testAnswer->answer ?? null;
            $status = $q->status;
            switch ($status) {
                case 'Correct':
                    $statusClass = 'success';
                    break;
                case 'Wrong':
                    $statusClass = 'danger';
                    break;
                default:
                    $statusClass = 'warning';
                    break;
            }
            
            $opts = is_array($q->options) ? $q->options : json_decode($q->options, true);
            $opts = $opts ?: [];

            $userAnsArr = $ua ? array_map('trim', explode(',', $ua)) : [];
            $userAnsArr = array_map('intval', $userAnsArr);

            $correctArr = [];
            if ($q->question_type != 'Numerical') {
                $raw = strpos($q->answer, ',') !== false ? explode(',', $q->answer) : [$q->answer];
                $correctArr = array_map(function($v){ return (int) trim($v); }, $raw);
            }
            $i = 0;
        @endphp

        <div class="card shadow border-0 mb-4" id="qs_{{$q->id}}">
            <div class="card-header bg-dark-subtle">
                <h6 class="m-0 p-2 rounded">
                    <i class="fa-solid fa-circle-question me-1"></i>
                    Question {{ $q->priority }}
                    <span class="badge text-bg-{{ $statusClass }} ms-2">{{ $status }}</span>
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3 fs-6">
                    {!! $q->question !!}
                </div>

                @if($q->question_type != 'Numerical')
                    <div class="mt-3">
                        @foreach($opts as $opt)
                            @php
                                $i++;
                                $key = trim($opt['key']);
                                $val = $opt['value'];

                                $isUser = in_array($i, $userAnsArr, true);
                                $isCorrectOpt = in_array($i, $correctArr, true);

                                $icon = '';
                                if ($isUser && $isCorrectOpt) {
                                    $icon = '<i class="fa-solid fa-check text-success me-2"></i>';
                                }
                                if ($isUser && !$isCorrectOpt) {
                                    $icon = '<i class="fa-solid fa-xmark text-danger me-2"></i>';
                                }

                                if ($isCorrectOpt) {
                                    $optClass = 'bg-success-subtle border-success';
                                } elseif ($isUser && !$isCorrectOpt) {
                                    $optClass = 'bg-danger-subtle border-danger';
                                } else {
                                    $optClass = 'border';
                                }
                            @endphp

                            <div class="option-item my-2 p-2 border rounded {{ $optClass }}">
                                <div class="d-flex align-items-start">
                                    {!! $icon !!}
                                    <div class="me-2 fw-bold">{{ $key }}.</div>
                                    <div>{!! $val !!}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @php
                        $correctText = $q->question_type === 'Multi-choice'
                            ? implode(', ', $correctArr)
                            : ($correctArr[0] ?? '');

                        $ansColor = match($status) {
                            'Correct' => 'text-success',
                            'Wrong'   => 'text-danger',
                            default   => 'text-warning',
                        };
                    @endphp
                    <div class="mt-3">
                        <strong>Correct Answer:</strong>
                        <span class="{{ $ansColor }}">{{ $correctText }}</span>
                    </div>
                @else
                    <div class="p-3 rounded mb-2 {{ $statusClass }}">
                        <strong>Your Answer:</strong>
                        {{ $ua ?? 'Not Answered' }}
                    </div>
                    @php
                        $correctText = isset($q->answer2) && $q->answer2 !== ''
                            ? "{$q->answer} to {$q->answer2}"
                            : $q->answer;

                        $ansColor = match($status) {
                            'Correct' => 'text-success',
                            'Wrong' => 'text-danger',
                            default => 'text-warning',
                        };
                    @endphp

                    <div class="p-3 rounded bg-light border">
                        <strong>Correct Answer:</strong>
                        <span class="{{ $ansColor }}">{{ $correctText }}</span>
                    </div>
                @endif

                @if($q->solution)
                    <button class="btn btn-outline-primary btn-sm mt-3"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#solution_{{ $q->id }}">
                        <i class="fa-solid fa-lightbulb me-1"></i> View Solution
                    </button>

                    <div class="collapse mt-2" id="solution_{{ $q->id }}">
                        <div class="p-3 border rounded bg-light">
                            {!! $q->solution !!}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection
@push('endjs')
    <script>
        function goToQuestion(id) {
            $('html, body').animate({
                scrollTop: $('#' + id).offset().top
            }, 300);
        }
    </script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
google.charts.load('current', {packages:['corechart']});
google.charts.setOnLoadCallback(drawCharts);

function drawCharts() {
    // 1. Accuracy (Correct vs Wrong)
    var accuracy = google.visualization.arrayToDataTable([
        ['Type', 'Count'],
        ['Correct', {{ $correct }}],
        ['Wrong', {{ $wrong }}]
    ]);
    new google.visualization.PieChart(document.getElementById('chart_accuracy'))
        .draw(accuracy, {title: 'Accuracy'});

    // 2. Attended (Attended vs Unattended)
    var attended = google.visualization.arrayToDataTable([
        ['Type', 'Count'],
        ['Attended', {{ $attended }}],
        ['Unattended', {{ $unattended }}]
    ]);
    new google.visualization.PieChart(document.getElementById('chart_attended'))
        .draw(attended, {title: 'Attended Questions'});

    // 3. Marks (Obtained vs Remaining)
    var marks = google.visualization.arrayToDataTable([
        ['Type', 'Marks'],
        ['Obtained', {{ $totalMarks }}],
        ['Remaining', {{ $fullMarks - $totalMarks }}]
    ]);
    new google.visualization.PieChart(document.getElementById('chart_marks'))
        .draw(marks, {title: 'Total Marks'});

    // 4. Positive vs Negative Marks
    var posNeg = google.visualization.arrayToDataTable([
        ['Type', 'Marks'],
        ['Positive', {{ $positiveMarks }}],
        ['Negative', {{ $negativeMarks }}]
    ]);
    new google.visualization.PieChart(document.getElementById('chart_posneg'))
        .draw(posNeg, {title: 'Positive / Negative Marks'});

    // 5. Duration (Used vs Remaining)
    // var duration = google.visualization.arrayToDataTable([
    //     ['Type', 'Seconds'],
    //     ['Used', {{ $usedDuration }}],
    //     ['Remaining', {{ $remainingDuration }}]
    // ]);
    // new google.visualization.PieChart(document.getElementById('chart_duration'))
    //     .draw(duration, {title: 'Time Usage'});
}
</script>
@endpush