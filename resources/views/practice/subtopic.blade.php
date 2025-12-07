@extends('layout.structure')
@section('xmt_tit', 'Practice Subtopic')
@section('xmt_des', 'Team of RKELECTRICAL GRID  for GATE /BARC/TRB/TNEB/ISRO & ESE Lectures')
@section('xmt_rob', 'index, follow')
@section('xmt_can', '/')

@section('content')
<div class="container">
    <h1 class="h4 text-info mt-3 mb-3 text-center">Practice</h1>
    <h2 class="h6 text-secondary mb-3 text-center">Subtopics</h2>
    @if($subtopics->count() > 0)
        <div class="row">
            @foreach($subtopics as $subtopic)
            <div class="col-12 col-md-4 mt-3">
                <div class="card shadow border-0">
                    <div class="text-center pb-3">
                        <h2 class="h5 text-primary p-2 pt-3">{{$subtopic->name}}</h2>
                        <img src="/image/practice/subtopic/{{ $subtopic->image }}" alt="Card image" class="rounded-2" style="width:90%;">
                    </div>
                    <div class="card-body text-center border border-bottom-0">
                        <a href="{{ route('practice.start', [
                                'subject' => $subtopic->subject_id,
                                'topic' => $subtopic->topic_id,
                                'subtopic' => $subtopic->id,
                                'priority' => $subtopic->firstPriority ? $subtopic->firstPriority->priority : 1
                            ]) }}" class="btn btn-primary btn-outline-primary text-light">Start Practice</a>
                    </div>
                    <div class="text-center border bg-light border-top-0">
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="text-center alert alert-danger">Sub topics yet to be added</div>
    @endif
</div>

@endsection
