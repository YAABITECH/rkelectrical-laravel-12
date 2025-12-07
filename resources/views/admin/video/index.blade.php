@extends('layout.admin.structure')
@section('xmt_tit', 'Course Details | Admin')

@push('headcss')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.1.2/css/tempusdominus-bootstrap-4.min.css" />
@endpush
@section('content')
<main>
    <div class="container mt-3">
        <h1 class="text-center text-primary fs-5 lh-lg">Course List</h1>
        <div class="text-center m-1 mb-3">
            <a class="btn btn-primary btn-sm" href="{{ route('admin.video.create') }}" role="button"><i class="fa-solid fa-plus"></i></a>
            <button class="btn btn-primary btn-sm" role="button" data-bs-toggle="modal" data-bs-target="#xfilter_model"><i class="fa-solid fa-filter"></i></button>
            <a class="btn btn-primary btn-sm" href="{{ route('admin') }}" role="button"><i class="fa-solid fa-home"></i></a>
        </div>
        @if ($errors->any())
            {!! implode('', $errors->all('<div class="alert alert-danger" role="alert">:message</div>')) !!}
        @endif
        @if (session('success'))
            <div class="alert alert-success">
            {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
            {{ session('error') }}
            </div>
        @endif
        @include('layout.admin.pagination', ['page' => $page, 'totalPages' => $totalPages])
        <div class="card shadow border-white rounded">
            <div class="extnd600">
                <table class="table table-light table-striped table-borderless m-0">
                    <thead>
                        <tr class="table-primary fw-bold">
                            <td>S.No</td>
                            <td>Course</td>
                            <td>Chapter</td>
                            <td>Demo</td>
                            <td class="text-center" style="min-width:100px">Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $s_no=1;
                        @endphp
                        @foreach ($videos as $video)
                            <tr>
                                <td>{{ $s_no++ }}</td>
                                <td>{{ $video->getCourse->title }}</td>
                                <td>{{ $video->topic }}</td>
                                <td>{{ $video->demo }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.video.edit', $video->id) }}" class="btn btn-success btn-sm"><i class="fa-solid fa-edit"></i></a>
                                    <form action="{{ route('admin.video.destroy', $video->id) }}" method="post"
                                        style="display: inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" type="submit"><i class="fa-solid fa-xmark"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div><small>{{$count}} of {{$totalCount}} {{$totalCount>1?'videos':'video'}}</small></div>
            <div><small>{{$page}} of {{$totalPages}} {{$totalPages>1?'pages':'page'}}</small></div>
        </div>
        @include('layout.admin.pagination', ['page' => $page, 'totalPages' => $totalPages])
    </div>
</main>
<div class="modal fade xfilter_model" id="xfilter_model" tabindex="-1" aria-labelledby="xfilterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="xfilterModalLabel">Filter</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form action="" method="get" id="filtform">
                <div class="form-group mb-3">
                    <label for="sort" class="form-label">Sort By</label>
                    <select name="sort" id="sort" class="form-select xfilter_field" data-live-search="true">
                        @php
                            $sortarr = array('default' => 'Default', 'newest' => 'Newest First', 'oldest' => 'Oldest First', 'lowest' => 'Lowest Fees First', 'highest' => 'Highest Fees First');
                        @endphp
                        @foreach($sortarr as $key => $value)
                            <option value="{{ $key }}" {{ $key == Request::input('sort') ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="page" class="form-label">Page Number</label>
                    <input type="number" class="form-control xfilter_field" id="page" name="page" step="1" min="1" value="{{ Request::input('page') }}">
                </div>
                <div class="form-group mb-3">
                    <label for="topic" class="form-label">Chapter</label>
                    <input type="text" class="form-control xfilter_field" id="topic" name="topic" value="{{ Request::input('topic') }}">
                </div>
                <div class="form-group py-3">
                    <label for="course" class="form-label">Select Course *</label>
                    <select name="course_id" id="course" class="form-select" data-live-search="true" required>
                        <option disabled selected>Select Course</option>
                        @foreach($courses as $course)
                            <option class=" form-control xfilter_field" value="{{ $course->id }}" {{ $course->id == old('course_id') ? 'selected' : '' }}>{{ $course->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group py-3">
                    <label for="demo" class="form-label">Demo *</label>
                    <select name="demo" id="demo" class="form-select" data-live-search="true" required>
                        @php
                            $demoArray = ['No' => '0', 'Yes' => '1'];
                        @endphp
                        @foreach($demoArray as $key => $value)
                            <option class=" form-control xfilter_field" value="{{ $value }}" {{ old('demo') == $value ? 'selected' : '' }}>{{ $key }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="perpage" class="form-label">Per Page Count</label>
                    <input type="number" class="form-control xfilter_field" id="perpage" name="perpage" value="{{ Request::input('perpage') }}">
                </div>
                <div class="pt-2">
                    <input type="hidden" name="queryurl" class="queryurl" value="/admin/course-video">
                    <button onclick="xfilter_submit()" type="button" class="btn btn-primary">Apply Filter</button>
                    <input type="reset" value="Reset" class="btn btn-light" onclick="xfilter_reset();">
                </div>
            </form>
            </div>
        </div>
    </div>
</div>
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
        $('#date_to').on('focus', function () {
            $('#datetimepicker2').datetimepicker('show');
        });
    });
</script>
@endpush
