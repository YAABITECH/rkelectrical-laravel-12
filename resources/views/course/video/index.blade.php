@extends('layout.structure')
@section('xmt_tit', 'RKElectrical Grid - Learning center')
@section('xmt_des', 'Team of RKELECTRICAL GRID  for GATE /BARC/TRB/TNEB/ISRO & ESE Lectures')
@section('xmt_rob', 'index, follow')
@section('xmt_can', '/')

@push('headcss')
<style>
    .active {
        display: block;
        padding: 10px;
        border-radius: 5px;
    }
    .active a{
        color: #fff !important;
    }
</style>
@endpush


@section('content')
<section class="py-3" style="background-color:#f8f9fa;">
    <div class="container" style="margin-top: 50px;">
        <div class="header p-3 my-4 d-flex flex-column justify-content-center align-items-center bg-white border border-2">
            <p>Please purchase the course package to watch all the videos and join the live classes.</p>
            <a href="{{ route('page.purchase', ['coursepack' => $currentChapter->getCourse->course_pack, 'ulink' => $currentChapter->getCourse->url]) }}" class="btn btn-danger">Purchase Now</a>
        </div>
        @php
            $firstChapter = $course->getChapters->first();
            $lastChapter = $course->getChapters->last();
        @endphp
        <div class="row">
            <div class="col-md-8">
                @if (session('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif
                @if($currentChapter)
                    <div class="course-video" style="padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                        @if($currentChapter->demo == 1 || $purchased == 1)
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe class="embed-responsive-item bg-transparent" style="width:100%;height:100%;min-height:450px;" src="https://www.youtube.com/embed/{{ $currentChapter->video }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        @else
                            <div class="position-relative">
                                <div class="embed-responsive embed-responsive-16by9 " style="z-index:10">
                                    <iframe class="embed-responsive-item bg-dark" style="width:100%;height:100%;min-height:450px;" src="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                                <div class="position-absolute px-3 text-center" style="top:40%;z-index:100">
                                    <p class="text-center text-danger" >This video is not available for preview. Please purchase the course package to access all videos.</p>
                                    <a href="{{ route('page.purchase', ['coursepack' => $currentChapter->getCourse->course_pack, 'ulink' => $currentChapter->getCourse->url]) }}" class="btn btn-danger">Purchase Now</a>
                                </div>
                            </div>
                        @endif
                        <div class="py-2">
                            <h5 style="margin-top: 0; color:#8f00a7; font-weight:bold">{{ $currentChapter->topic }}</h5>
                        </div>
                        <div class="py-1">
                            <h6 style="margin-top: 0;">{{ $currentChapter->description }}</h6>
                            <p>{{$currentChapter->getCourse->title}}</p>

                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                    @if($previousChapter)
                        <a href="{{ route('course.video', ['ulink' => $course->url, 'chapter' => $previousChapter->id]) }}" class="btn btn-sm btn-info">
                            <i class="fa-solid fa-angles-left"></i> Prev
                        </a>
                    @else
                        <a href="#" class="btn btn-sm btn-info disabled">
                            <i class="fa-solid fa-angles-left"></i> Prev
                        </a>
                    @endif

                    @if($nextChapter)
                        <a href="{{ route('course.video', ['ulink' => $course->url, 'chapter' => $nextChapter->id]) }}" class="btn btn-sm btn-info">
                            <i class="fa-solid fa-angles-right"></i> Next
                        </a>
                    @else
                        <a href="#" class="btn btn-sm btn-info disabled">
                            <i class="fa-solid fa-angles-right"></i> Next
                        </a>
                    @endif

                    </div>
                @else
                    <p>No video available</p>
                @endif
            </div>

            <div class="col-md-4 mt-3 mt-md-0">
                <h4 class="text-center" style="color:#8f00a7; font-weight:bold">Course Videos</h4>
                <div class="course-list" >
                    <ul class="list-group shadow">
                        @foreach($courseChapters as $video)
                            <li class="list-group-item {{ request()->routeIs('course.video') && request('chapter') == $video->id ? 'active' : '' }}"
                            style="text-decoration: none; ">
                            <a href="{{ route('course.video', ['ulink' => $course->url, 'chapter'=>$video->id]) }}" class="text-dark" style="text-decoration: none; ">{{ $video->topic }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
