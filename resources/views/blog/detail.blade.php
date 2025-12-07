@extends('layout.structure')

@section('xmt_tit', $blog->mtit)
@section('xmt_des', $blog->mdes)
@section('xmt_rob', 'index, follow')

@push('headcss')
    <style>
        .artcontdiv img
        {
            max-width: 100%;
            height:auto;
            border-radius: 8px;
        }
        .artcontdiv iframe
        {
            max-width: 100%;
            border-radius: 8px;
        }
        .ya-hl-listitem:hover {
            background-color:rgba(0,0,0,0.2);    
        }
    </style>
@endpush
@section('content')
<div class="container mt-4">
    <h1 class="text-center text-primary fs-5 lh-lg m-0 p-0 mb-3">{{$blog->title}}</h1>
    <div class="d-flex justify-content-between align-items-center mb-3 text-secondary">
        <div><small><i class="fa fa-calendar"></i> {{date('d-m-Y', strtotime($blog->post_date))}}</small></div>
        <div class=""><small><i class="fa fa-user"></i> {{$blog->author}}</small></div>
    </div>
    <div style="text-align: justify;" class="artcontdiv">{!!$blog->content!!}</div>
</div>
@endsection