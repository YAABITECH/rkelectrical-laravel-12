@extends('layout.admin.structure')
@section('xmt_tit', 'Create Test Exam | Admin')

@push('headcss')
<script src="{{asset('/assets/tinymce/tinymce.min.js')}}" referrerpolicy="origin"></script>
<link rel="stylesheet" href="/css/tinymce-editor-v1-0.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.1.2/css/tempusdominus-bootstrap-4.min.css">
@endpush

@section('content')
<div class="container mt-3">
    <h1 class="text-center text-primary fs-5 lh-lg">Create Test Exam</h1>

    <div class="text-center m-1 mb-3">
        <a class="btn btn-primary btn-sm" href="{{ route('admin.test.exam.index') }}"><i class="fa-solid fa-list"></i></a>
        <a class="btn btn-primary btn-sm" href="{{ route('admin') }}"><i class="fa-solid fa-home"></i></a>
    </div>
    @if ($errors->any())
        {!! implode('', $errors->all('<div class="alert alert-danger" role="alert">:message</div>')) !!}
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
        {{ session('error') }}
        </div>
    @endif
    <form method="post" action="{{ route('admin.test.exam.store') }}" enctype="multipart/form-data">
        @csrf
        <p class="text-center">* fields are compulsory</p>
        <div class="form-group py-3" id="seriesDiv">
            <label class="form-label">Test Series</label>
            <input type="text" class="form-control" value="{{ $series->name }}" readonly>
            <input type="hidden" name="series_id" id="series_id" value="{{ $series->id }}">
        </div>
        <div class="form-group py-3">
            <label for="name" class="form-label">Name *</label>
            <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}" required>
        </div>
        <div class="form-group py-3">
            <label for="tagline" class="form-label">Tagline</label>
            <input type="text" class="form-control" name="tagline" id="tagline" value="{{ old('tagline') }}">
        </div>
        <div class="form-group py-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" class="yaTinyEditor" id="description" rows="5">{{ old('description') }}</textarea>
        </div>
        <div class="form-group py-3">
            <label for="instruction" class="form-label">General Instructions</label>
            <textarea name="instruction" class="yaTinyEditor" id="instruction" rows="5">{{ old('instruction') }}</textarea>
        </div>
        <div class="row">
            <div class="col-md-3 py-3">
                <label for="total_questions" class="form-label">Total Questions</label>
                <input type="number" class="form-control" name="total_questions" id="total_questions" value="{{ old('total_questions') }}">
            </div>
            <div class="col-md-3 py-3">
                <label for="marks" class="form-label">Marks</label>
                <input type="number" class="form-control" name="marks" id="marks" value="{{ old('marks') }}">
            </div>
            <div class="col-md-3 py-3">
                <label for="duration" class="form-label">Duration (mins)</label>
                <input type="number" class="form-control" name="duration" id="duration" value="{{ old('duration') }}">
            </div>
            <div class="col-md-3 py-3">
                <label for="attempt_limit" class="form-label">Attempt Limit</label>
                <input type="number" class="form-control" name="attempt_limit" id="attempt_limit" value="{{ old('attempt_limit',1) }}">
            </div>
        </div>
        <div class="form-group py-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="has_negative_marks" name="has_negative_marks" {{ old('has_negative_marks') ? 'checked' : '' }}>
                <label class="form-check-label" for="has_negative_marks">Has Negative Marks</label>
            </div>
        </div>
        <div class="form-group py-3">
            <label for="difficulty" class="form-label">Difficulty</label>
            <select class="form-select" name="difficulty" id="difficulty" required>
                @foreach(['easy','medium','hard','mixed'] as $value)
                    <option value="{{ $value }}" {{ $value == old('status') ? 'selected' : '' }}>
                        {{ ucfirst($value) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group py-3">
            <label for="status" class="form-label">Status *</label>
            <select class="form-select" name="status" id="status">
                @foreach(['draft','upcoming','active','archive'] as $value)
                    <option value="{{ $value }}" {{ $value == old('status') ? 'selected' : '' }}>
                        {{ ucfirst($value) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group py-3 d-none" id="start_at_div">
            <label for="start_at" class="form-label">Start Date & Time</label>
            <div class="input-group date datetimepicker-input" id="startpicker" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input"
                    id="start_at" name="start_at"
                    data-target="#startpicker"
                    value="{{ old('start_at') }}">
                <div class="input-group-append" data-target="#startpicker" data-toggle="datetimepicker">
                    <div class="input-group-text" style="height: 100%;"><i class="far fa-calendar-alt text-primary"></i></div>
                </div>
            </div>
        </div>
        <div class="form-group py-3">
            <label for="end_at" class="form-label">End Date & Time</label>
            <div class="input-group date datetimepicker-input" id="endpicker" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input"
                    id="end_at" name="end_at"
                    data-target="#endpicker"
                    value="{{ old('end_at') }}">
                <div class="input-group-append" data-target="#endpicker" data-toggle="datetimepicker">
                    <div class="input-group-text" style="height: 100%;"><i class="far fa-calendar-alt text-primary"></i></div>
                </div>
            </div>
        </div>
        <div class="form-group py-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="is_demo" name="is_demo" {{ old('is_demo') ? 'checked' : '' }}>
                <label class="form-check-label" for="is_demo">Is Demo?</label>
            </div>
        </div>
        <div class="form-group py-3">
            <button type="submit" class="btn btn-block btn-primary">Create Test & Continue</button>
        </div>
    </form>
</div>
@endsection
@push('endjs')
<script src="/js/ya-tiny-editor-v1-0.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.1.2/js/tempusdominus-bootstrap-4.min.js"></script>
<script>
    $(function () {
        $('.datetimepicker-input').datetimepicker({
            format: 'DD-MM-YYYY h:mm A',
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

        $('#start_at').on('focus', function () {
            $('#startpicker').datetimepicker('show');
        });
        $('#end_at').on('focus', function () {
            $('#endpicker').datetimepicker('show');
        });
    });
</script>
<script>
    function statusChange(){
        let status = $('#status').val();
        if(status === 'active'){
            $('.conditional-required').attr('required', true);
            $('.required-asterisk').show();
        } else {
            $('.conditional-required').removeAttr('required');
            $('.required-asterisk').hide();
        }
        if (status === "upcoming") {
            $('#start_at_div').removeClass('d-none');
        } else {
            $('#start_at_div').addClass('d-none');
        }
    }
    $(document).ready(function() {
        statusChange();
        $("#status").on("change", function() {
            statusChange();
        });
        $("#is_index").on("change", function() {
            statusChange();
        });
    });
</script>
@endpush