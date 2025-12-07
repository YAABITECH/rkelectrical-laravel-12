@extends('layout.structure')

@section('xmt_tit', $series ? $series->name : 'Test Series')
@section('xmt_des', $series ? $series->tagline : 'All Test Series')
@section('xmt_rob', 'index, follow')

@section('content')
<div class="container">

    {{-- PAGE HEADERS --}}
    <h1 class="h4 text-info mt-3 mb-3 text-center">
        {{ $series ? $series->name : 'Test Series' }}
    </h1>
    @if($series && $series->tagline)
        <h2 class="h6 text-secondary mb-3 text-center">{{ $series->tagline }}</h2>
    @endif

    {{-- Errors --}}
    @if ($errors->any())
        {!! implode('', $errors->all('<div class="alert alert-danger" role="alert">:message</div>')) !!}
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- 1. CHILD TEST SERIES LIST --}}
    @if($childSeries->count() > 0)
    <div class="row">

        @foreach($childSeries as $item)
            <div class="col-12 col-md-4 mt-3">
                <a href="{{ route('test.series.list', ['series_id' => $item->id]) }}" class="text-decoration-none text-secondary">

                    <div class="card shadow mb-3 border-0">
                        <div class="text-center pb-3">

                            <h2 class="h5 text-primary p-2 pt-3">{{ $item->name }}</h2>
                            <p class="text-center">{{ $item->tagline }}</p>
                            @if($item->image)
                            <img src="/image/test-series//{{ $item->image }}"
                                 alt="{{ $item->name }}"
                                 class="rounded-2"
                                 style="width:90%;">
                            @endif
                        </div>

                        <div class="card-body text-center border border-bottom-0">
                            <a href="{{ route('test.series.list', ['series_id' => $item->id]) }}"
                               class="btn btn-secondary btn-outline-primary text-light">
                               View Series
                            </a>
                        </div>
                    </div>

                    @if($item->test_count > 0)
                    <div class="text-center border-0 bg-light border-top-0 shadow">
                        <div class="card-body text-center border border-bottom-0">
                            <span class="badge bg-primary">Tests: {{ $item->test_count }}</span>
                            @if($item->is_paid)
                                <span class="badge bg-danger">Paid</span>
                            @else
                                <span class="badge bg-success">Free</span>
                            @endif
                        </div>
                    </div>
                    @endif

                </a>
            </div>
        @endforeach

    </div>
    @endif


    {{-- 2. TEST EXAMS UNDER THIS SERIES --}}
    @if($exams->count() > 0)
    <h2 class="fs-5 text-info mt-5 mb-3 text-center">Available Tests</h2>
    @foreach($exams as $exam)
        <div class="card shadow mb-3 border-0 h-100">
            <div class="card-header p-3 pb-3">
                <h2 class="fs-5 text-primary">{{ $exam->name }}
                    <small><span class="badge 
                    @if($exam->difficulty=='easy') bg-success 
                    @elseif($exam->difficulty=='medium') bg-warning text-dark
                    @elseif($exam->difficulty=='hard') bg-danger
                    @else bg-secondary
                    @endif">
                        {{ ucfirst($exam->difficulty ?? 'mixed') }}
                    </span></small>
                </h2>
                @if($exam->tagline)
                <div class="mt-2 text-secondary">{{ $exam->tagline }}</div>
                @endif
                
            </div>

            <div class="card-body text-start border">
                {{-- Test stats row --}}
                <div class="mb-2 text-secondary">
                    <span class="p-2"><span class="fw-semibold"><i class="fa-solid fa-question pe-1"></i>Total Questions:</span> {{ $exam->total_questions }}</span>
                    <span class="p-2"><span class="fw-semibold"><i class="fa-regular fa-square-check pe-1"></i>Total Marks:</span> {{ $exam->marks }}</span>
                    <span class="p-2"><span class="fw-semibold"><i class="fa-regular fa-clock pe-1"></i>Duration:</span> {{ $exam->duration }} seconds</span>
                </div>

                <div class="text-center mt-3">
                    <a href="{{ route('test.start', ['exam_id' => $exam->id]) }}"
                        class="btn btn-primary text-light">
                        Start Test
                    </a>
                </div>

            </div>
        </div>
    @endforeach
    @endif

    {{-- If no child series and no exams --}}
    @if($childSeries->count() == 0 && $exams->count() == 0)
        <div class="alert alert-danger text-center mt-4">
            No test series or exams found.
        </div>
    @endif

</div>
@endsection
