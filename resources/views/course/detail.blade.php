@extends('layout.structure')
@section('xmt_tit', 'RKElectrical Grid - Learning center')
@section('xmt_des', 'Team of RKELECTRICAL GRID  for GATE /BARC/TRB/TNEB/ISRO & ESE Lectures')
@section('xmt_rob', 'index, follow')
@section('xmt_can', '/')

@push('headcss')
<style>
   .hoverablx:hover {
      box-shadow: 0 10px 20px rgba(0,0,0,.12), 0 4px 8px rgba(0,0,0,.06);
   }
</style>
@endpush
@push('styles')

@endpush

@section('content')
   <section class="py-3">
    <div class="container">
        <div class="row">
        <h3 class="text-primary">{{$course->title}}</h3>
            <div class="col-12 col-md-7 mt-3">
                <img src="/image/course/{{$course->photo}}" class="rounded-3 w-100" alt="{{$course->title}}">
                <div class="mt-3">
                    <p class="text-justify mb-0">{{$course->description}}</p>
                </div>
                <div class="p-2 mt-3" style="border:0.05em solid #cccccc; border-left:3px solid #ff6666;">
                    <p class="mb-0">Course Duration: {{$course->coursedur}}</p>
                </div>
                <div class="p-2 mt-3" style="border:0.05em solid #cccccc; border-left:3px solid #ff6666;">
                    <p class="mb-0">Class Duration: {{$course->classdur}}</p>
                </div>
                @php
                    $strfees = $course->fees * 2;
                @endphp
                <p class="text-danger fs-4 fw-bold text-decoration-line-through lh-lg mb-0">₹{{$strfees}}/-</p>
                <div class="mt-2">
                    <button class="btn btn-outline-primary fw-bold">Fees:&nbsp;₹{{$course->fees}}/-</button>
                </div>
                <div class="mt-3">
                    <a href="" class="btn btn-primary px-3">Buy Now</a>
                </div>
            </div>
            <div class="col-12 col-md-5 mt-3">
                <div class="mb-3">
                    <a href="{{route('course.video',['ulink'=>$course->url])}}" class="btn btn-outline-info px-3">Watch Video</a>
                </div>
                <p class="text-primary">Other Courses</p>
                <ul class="list-group">
                    @foreach($courses as $course)
                        <li class="list-group-item">
                            <a href="{{route('course.detail',['url'=>$course->url])}}" class="text-decoration-none text-dark">
                                {{$course->title}}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
   </section>
@endsection
