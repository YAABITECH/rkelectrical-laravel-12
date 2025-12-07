@extends('layout.structure')
@section('xmt_tit', 'RKElectrical Grid - Learning center')
@section('xmt_des', 'Team of RKELECTRICAL GRID  for GATE /BARC/TRB/TNEB/ISRO & ESE Lectures')
@section('xmt_rob', 'index, follow')
@section('xmt_can', '/')

@push('headcss')
    <style>
        .courseHover:hover{
            box-shadow: rgba(14, 30, 37, 0.12) 0px 2px 4px 0px, rgba(14, 30, 37, 0.32) 0px 2px 16px 0px;
            }
    </style>
@endpush
@section('content')
<section>
    <div class="container py-3">
        <h1 class="h4 text-primary mt-2 text-center">Course Packages</h1>
        @if($course_packs->count() > 0)
            <div class="row d-flex justify-content-center align-items-center">
                @foreach($course_packs as $course_pack)
                        <div class="col-12 col-md-3 mt-3">
                        <a href="{{route('course.packDetail',['id'=>$course_pack->id])}}" class="text-decoration-none">
                        <div class="cpackd">
                            <h3 class="text-center">{{$course_pack->name}}</h3>
                        </div></a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center alert alert-danger mt-3">Course Packages yet to be added</div>
        @endif
    </div>
</section>
    <hr>
    <section class="section bg-white py-5 pt-2 shadow-sm">
        <div class="container">
            <div class="row">
                <h4 class="text-primary h4 text-center">Courses</h4>
                @if($courses->count() > 0)
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
                @else
                    <div class="text-center alert alert-danger mt-3">Courses yet to be added</div>
                @endif
            </div>
        </div>
    </section>
@endsection
