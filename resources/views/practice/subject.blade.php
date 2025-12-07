@extends('layout.structure')
@section('xmt_tit', 'Practice Subject')
@section('xmt_des', 'Team of RKELECTRICAL GRID  for GATE /BARC/TRB/TNEB/ISRO & ESE Lectures')
@section('xmt_rob', 'index, follow')
@section('xmt_can', '/')

@section('content')
<div class="container">
    <h1 class="h4 text-info mt-3 mb-3 text-center">Practice</h1>
    <h2 class="h6 text-secondary mb-3 text-center">All Subjects</h2>
    @if ($errors->any())
        {!! implode('', $errors->all('<div class="alert alert-danger" role="alert">:message</div>')) !!}
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
        {{ session('error') }}
        </div>
    @endif
    @if($subjects->count() > 0)
    <div class="row">
        @foreach($subjects as $subject)
            <div class="col-12 col-md-4 mt-3">
                <a href="{{ route('practice.topic', ['subject' => $subject->id]) }}" class="text-decoration-none">
                    <div class="card shadow mb-3 border-0">
                        <div class="text-center pb-3">
                            <h2 class="h5 text-primary p-2 pt-3">{{$subject->name}}</h2>
                            <img src="/image/practice/subject/{{ $subject->image }}" alt="Card image" class="rounded-2" style="width:90%;">
                        </div>
                        <div class="card-body text-center border border-bottom-0">
                            <a href="{{ route('practice.topic', ['subject' => $subject->id]) }}" class="btn btn-secondary btn-outline-primary text-light">View Topics</a>
                        </div>
                    </div>
                </a>
                <div class="text-center border-0 bg-light border-top-0 shadow">
                    <div class="card-body text-center border border-bottom-0">
                    <a href="{{ route('practice.start', [
                        'subject' => $subject->id,
                        'topic' => $subject->firstTopic ? $subject->firstTopic->id : 1,
                        'subtopic' => $subject->firstSubtopic ? $subject->firstSubtopic->id : 1,
                        'priority' => $subject->firstPriority ? $subject->firstPriority->priority : 1
                    ]) }}" class="btn btn-primary btn-outline-primary text-light">Start Practice</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @else
    <div class="text-center alert alert-danger">Subjects yet to be added</div>
    @endif
</div>

@endsection
