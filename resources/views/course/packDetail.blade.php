@extends('layout.structure')
@section('xmt_tit', 'RKElectrical Grid - Learning center')
@section('xmt_des', 'Team of RKELECTRICAL GRID  for GATE /BARC/TRB/TNEB/ISRO & ESE Lectures')
@section('xmt_rob', 'index, follow')
@section('xmt_can', '/')

@push('headcss')

@endpush
@push('styles')

@endpush

@section('content')
    <section class="py-5">
        <div class="container text-center">
            <h3 class="text-center text-primary mb-3">{{$coursePack->name}} Courses</h3>
            <p class="fw-bold lh-lg text-info">₹{{$coursePack->fees}} only</p>
            <a href="{{route('page.purchase',['coursepack'=>$coursePack->id])}}" class="btn btn btn-info">Buy Now</a>
        </div>
    </section>
    <section class="section bg-white pb-5 shadow-sm">
        <div class="container">
            <div class="row">
                <div class="row">
                    @foreach($courses as $course )
                    <div class="col-12 col-md-4 mt-4 ">
                        <a href="{{route('course.detail',['url'=>$course->url])}}" class="text-decoration-none">
                            <div class="card border border-info courseHover">
                                <div class="card-header py-3 bg-white">
                                    <h6 class="card-title text-center mb-0">{{$course->title}}</h6>
                                </div>
                                <div class="card-body p-0">
                                    <img src="/image/course/{{$course->photo}}" class="card-img-bottom" alt="{{$course->title}}">
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
