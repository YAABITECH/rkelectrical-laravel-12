@extends('layout.admin.structure')
@section('xmt_tit', 'Create Practice Topic | Admin')

@push('headcss')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.1.2/css/tempusdominus-bootstrap-4.min.css" />
@endpush

@section('content')
<div class="container mt-3">
    <h1 class="text-center text-primary fs-5 lh-lg">Create Practice Topic</h1>
    <div class="text-center m-1 mb-3">
        <a class="btn btn-primary btn-sm" href="{{ route('admin.practice.topic.index') }}" role="button"><i class="fa-solid fa-list"></i></a>
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
    <form method="post" action="{{ route('admin.practice.topic.store') }}" enctype="multipart/form-data">
        @csrf
        <p class="text-center">* fields are compulsory</p>
        <div class="form-group py-3">
            <label for="subject" class="form-label">Select Subject *</label>
            <select name="subject" id="subject" class="form-select" data-live-search="true" required>
                <option disabled selected>Select Subject</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}"{{ $subject->id == $subjectId ? ' selected' : '' }}>{{ $subject->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group py-3">
            <label for="name" class="form-label">Topic Name *</label>
            <input type="text" class="form-control" name="name" id="name" oninput="getulinkt()" value="{{ old('name') }}" required>
        </div>
        <div class="form-group py-3">
            <label for="ulink" class="form-label">Ulink *</label>
            <input type="text" class="form-control" name="ulink" id="ulink" oninput="getulink(this.value)" aria-describedby="helpulink" value="{{ old('ulink') }}" required>
            <div id="helpulink" class="form-text">{{config('app.url')}}/practice/topic/{{ old('ulink') }}</div>
        </div>
        <div class="form-group py-3">
            <label for="image" class="form-label">Image *</label>
            <input type="file" class="form-control" name="image" id="image" aria-describedby="helpimage" accept="image/*">
            <div id="helpimage" class="form-text">Recommended ratio 16:9</div>
        </div>
        <div class="form-group py-3">
            <label for="mtit" class="form-label">Meta Title (SEO) *</label>
            <input type="text" class="form-control" name="mtit" id="mtit" value="{{ old('mtit') }}" required>
        </div>
        <div class="form-group py-3">
            <label for="mdes" class="form-label">Meta Description (SEO) *</label>
            <textarea class="form-control" name="mdes" id="mdes" rows="3" required>{{ old('mdes') }}</textarea>
        </div>
        <div class="form-group py-3">
            <label for="ogimage" class="form-label">OG Image *</label>
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
                    <option value="{{ $key }}" {{ $key == old('status') ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div id="launch_dt_div" style="display:none">
            <div class="form-group py-3">
                <label for="launch_datetime" class="form-label">Launch On</label>
                <div class="input-group date datetimepicker-input" id="datetimepicker1" data-target-input="nearest">
                    <input type="text" class="form-control datetimepicker-input xfilter_field" id="launch_datetime" name="launch_datetime" data-target="#datetimepicker1" value="{{ old('launch_datetime') ? old('launch_datetime') : '' }}">
                    <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                        <div class="input-group-text" style="height: 100%;"><i class="far fa-calendar-alt text-primary"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group py-3">
            <button type="submit" class="btn btn-block btn-primary">Create Topic</button>
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
        document.getElementById("helpulink").innerHTML="{{config('app.url')}}/practice/topic/"+temp;
    }
    function getulink(val)
	{
        temp = toseourl(val);
        document.getElementById("ulink").value=temp;
        document.getElementById("helpulink").innerHTML="{{config('app.url')}}/practice/topic/"+temp;
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
