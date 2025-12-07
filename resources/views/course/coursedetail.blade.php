@extends('layout.structure')

@section('xmt_tit', 'RKElectrical Grid - Learning center')
@section('xmt_des', 'Team of RKELECTRICAL GRID for GATE /BARC/TRB/TNEB/ISRO & ESE Lectures')
@section('xmt_rob', 'index, follow')
@section('xmt_can', '/')

@push('headcss')
<style>
   .hoverable:hover {
      box-shadow: 0 10px 20px rgba(0,0,0,.12), 0 4px 8px rgba(0,0,0,.06);
   }
</style>
@endpush

@section('content')
<div class="container">
    <div class="row">
            <div class="col-sm-7"><br>
                    <img src="/admin/course/images/<?php //echo $photo; ?>" style="height:350px;width:100%;" class="img-thumbnail">
                    <p class="text-primary" style="font-size:25px;"><?php //echo $title?>
                    <p><?php //echo $description?></p>
                    <p><blockquote style="border-left:2px solid #ff0066;border-right:1px solid #cccccc;border-top:1px solid #cccccc;border-bottom:1px solid #cccccc;padding:10px;">&nbsp;Course duration:&nbsp;<?php //echo $coursedur?></blockquote></p>
                    <p><blockquote style="border-left:2px solid #ff0066;border-right:1px solid #cccccc;border-top:1px solid #cccccc;border-bottom:1px solid #cccccc;padding:10px;">&nbsp;Class duration:&nbsp;<?php //echo $classdur?></blockquote></p>
                    <p style="text-decoration:line-through; font-size:1.6rem; color:#a7005c; font-weight:500">&#8377;<?php //echo (2*$fees);?>/-</p>
                    <button class="btn btn-outline-primary" style="margin-bottom:20px; font-weight:600">Fees:&nbsp;&#8377;<?php //echo $fees; ?>/-</button>
                    <?php //echo $viewbutton;?>
                </div>
            <div class="col-sm-5"><br>
                <?php //echo $preview; ?>
                <br>
                <p><a href="/course/video/?cid=<?php //echo $course_id;?>&n=1"class="btn btn-outline-info btn-block" >Watch  Video&emsp;<span class="fa fa-play-circle-o"></span></a></p>
                <p class="text-primary font-weight-bold">Other Courses</p>
               @foreach($course->$course)
               <a href="/course/view?uname='.$uurl.'" class="list-group-item list-group-item-action">{{$course->title}}</a>
               @endforeach
                </div>
            </div>
    </div>
</div>

@endsection
