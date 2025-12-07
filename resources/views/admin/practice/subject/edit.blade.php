@extends('layout.admin.structure')
@section('xmt_tit', 'Edit Practice Subject | Admin')

@push('headcss')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.1.2/css/tempusdominus-bootstrap-4.min.css" />
@endpush

@section('content')
<div class="container mt-3">
    <h1 class="text-center text-primary fs-5 lh-lg">Edit Practice Subject</h1>
    <div class="text-center m-1 mb-3">
        <a class="btn btn-primary btn-sm" href="{{ route('admin.practice.subject.index') }}" role="button"><i class="fa-solid fa-list"></i></a>
        <form action="{{ route('admin.practice.subject.destroy', $subject->id) }}" method="post"
            style="display: inline-block">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger btn-sm" type="submit"><i class="fa-solid fa-xmark"></i></button>
        </form>
        <a class="btn btn-primary btn-sm" href="{{ route('admin') }}" role="button"><i class="fa-solid fa-home"></i></a>
    </div>
    @if ($errors->any())
        {!! implode('', $errors->all('<div class="alert alert-danger" role="alert">:message</div>')) !!}
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
        {{ session('error') }}
        </div>
    @endif
    <form method="post" action="{{ route('admin.practice.subject.update',$subject->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <p class="text-center">* fields are compulsory</p>
        <div class="form-group py-3">
            <label for="name" class="form-label">Subject Name *</label>
            <input type="text" class="form-control" name="name" id="name" oninput="getulinkt()" value="{{ old('name',$subject->name) }}" required>
        </div>
        <div class="form-group py-3">
            <label for="ulink" class="form-label">Ulink *</label>
            <input type="text" class="form-control" name="ulink" id="ulink" oninput="getulink(this.value)" aria-describedby="helpulink" value="{{ old('ulink',$subject->ulink) }}" required>
            <div class="form-text"><a href="{{config('app.url')}}/practice/subject/{{ old('ulink',$subject->ulink) }}" id="helpulink" class="text-decoration-none" target="_blank">{{config('app.url')}}/practice/subject/{{ old('ulink',$subject->ulink) }}</a></div>
        </div>
        <div class="py-3">
            <label class="form-label">Previous Image</label><br>
            <img src="{{URL::asset('/image/practice/subject/thumb/'.$subject->image)}}" class="img-fluid rounded" alt="" style="max-width:150px">
        </div>
        <div class="form-group py-3">
            <label for="image" class="form-label">Update Image</label>
            <input type="file" class="form-control" name="image" id="image" aria-describedby="helpimage" accept="image/*">
            <div id="helpimage" class="form-text">Recommended ratio 16:9</div>
        </div>
        <div class="form-group py-3">
            <label for="mtit" class="form-label">Meta Title (SEO) *</label>
            <input type="text" class="form-control" name="mtit" id="mtit" value="{{ old('mtit',$subject->mtit) }}" required>
        </div>
        <div class="form-group py-3">
            <label for="mdes" class="form-label">Meta Description (SEO) *</label>
            <textarea class="form-control" name="mdes" id="mdes" rows="3" required>{{ old('mdes',$subject->mdes) }}</textarea>
        </div>
        <div class="py-3">
            <label class="form-label">Previous OG Image</label><br>
            <img src="{{URL::asset('/image/practice/subject/ogimage/'.$subject->ogimage)}}" class="img-fluid rounded" alt="" style="max-width:150px">
        </div>
        <div class="form-group py-3">
            <label for="ogimage" class="form-label">OG Image</label>
            <input type="file" class="form-control" name="ogimage" id="ogimage" aria-describedby="helpogimage" accept="image/*">
            <div id="helpogimage" class="form-text">This image will be shown when you share your links to social media. Recommended ratio 1200:627</div>
        </div>
        <div class="form-group py-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select" data-live-search="true">
                @php
                    $statusarr = array('hidden' => 'Hidden', 'waiting' => 'Launch Soon', 'public' => 'Live');
                @endphp
                @foreach($statusarr as $key => $value)
                    <option value="{{ $key }}" {{ $key == old('status',$subject->status) ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div id="launch_dt_div" style="display:none">
            <div class="form-group py-3">
                <label for="launch_datetime" class="form-label">Launch On</label>
                <div class="input-group date datetimepicker-input" id="datetimepicker1" data-target-input="nearest">
                    <input type="text" class="form-control datetimepicker-input xfilter_field" id="launch_datetime" name="launch_datetime" data-target="#datetimepicker1" value="{{ old('launch_datetime') ? old('launch_datetime') : ($subject->launch_datetime ? date('m/d/Y g:i A', strtotime($subject->launch_datetime)):'') }}">
                    <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                        <div class="input-group-text" style="height: 100%;"><i class="far fa-calendar-alt text-primary"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group py-3">
            <button type="submit" class="btn btn-block btn-primary">Update Subject</button>
        </div>
    </form>
<div>
@endsection
@push('endjs')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.1.2/js/tempusdominus-bootstrap-4.min.js"></script>
<script>
    $(function () {
        $('.datetimepicker-input').datetimepicker({
            icons: {
                time: 'far fa-clock',
                date: 'far fa-calendar-alt',
                up: 'fas fa-arrow-up',
                down: 'fas fa-arrow-down',
                previous: 'fas fa-chevron-left',
                next: 'fas fa-chevron-right',
                today: 'fas fa-calendar-day',
                clear: 'far fa-trash-alt',
                close: 'far fa-times-circle'
            },
            useCurrent: false
        });

        $('#date_from').on('focus', function () {
            $('#datetimepicker1').datetimepicker('show');
        });
    });
</script> 
<script>
    function handleStatusChange() {
        var selectedStatus = $("#status").val();
        if (selectedStatus === "waiting") {
            $("#launch_dt_div").show();
        } else {
            $("#launch_dt_div").hide();
        }
    }
    $(document).ready(function() {
        handleStatusChange();
        $("#status").on("change", function() {
            handleStatusChange();
        });
    });
    function getulinkt()
    {
        title = document.getElementById("name").value;
        temp = toseourl(title);
        document.getElementById("ulink").value=temp;
        document.getElementById("helpulink").innerHTML="{{config('app.url')}}/practice/subject/"+temp;
    }
    function getulink(val)
	{
        temp = toseourl(val);
        document.getElementById("ulink").value=temp;
        document.getElementById("helpulink").innerHTML="{{config('app.url')}}/practice/subject/"+temp;
	}
    function toseourl(url)
    {
        return url.toString()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g,'')
        .replace(/\s+/g,'-')
        .toLowerCase()
        .replace(/&/g,'-and-')
        .replace(/[^a-z0-9\-]/g,'')
        .replace(/-+/g,'-')
        .replace(/^-*/,'')
        .replace(/-*$/,'');
    }
</script>
@endpush
