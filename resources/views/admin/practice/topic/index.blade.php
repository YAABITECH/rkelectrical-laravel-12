@extends('layout.admin.structure')
@section('xmt_tit', 'Practice Topic | Admin')

@push('headcss')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.1.2/css/tempusdominus-bootstrap-4.min.css" />
@endpush

@section('content')
    <div class="container mt-3">
        <h1 class="text-center text-primary fs-5 lh-lg">Practice Topics List</h1>
        <div class="text-center m-1 mb-3">
            <a class="btn btn-primary btn-sm" href="{{ route('admin.practice.topic.create',['subject' => $subject]) }}" role="button"><i class="fa-solid fa-plus"></i></a>
            <button class="btn btn-primary btn-sm" role="button" data-bs-toggle="modal" data-bs-target="#xfilter_model"><i class="fa-solid fa-filter"></i></button>
            <a class="btn btn-primary btn-sm" href="{{ route('admin.practice.topic.arrange') }}" role="button"><i class="fa-solid fa-layer-group"></i></a>
            <a class="btn btn-primary btn-sm" href="{{ route('admin.practice.subject.index') }}" role="button"><i class="fa-solid fa-list"></i></a>
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
                            <td>Topic Name</td>
                            <td>Status</td>
                            <td class="text-center" style="min-width:100px">Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $s_no=1;
                        @endphp
                        @foreach ($topics as $topic)
                            <tr>
                                <td>{{ $s_no++ }}</td>
                                <td>{{ $topic->name }}</td>
                                <td>{{ $topic->status }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.practice.topic.edit', $topic->id) }}" class="btn btn-success btn-sm"><i class="fa-solid fa-edit"></i></a>
                                    <form action="{{ route('admin.practice.topic.destroy', $topic->id) }}" method="post"
                                        style="display: inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" type="submit"><i class="fa-solid fa-xmark"></i></button>
                                    </form>
                                    <a href="/admin/practice/subtopic?subject={{$topic->subject_id }}&topic={{$topic->id}}" class="btn btn-primary btn-sm"><i class="fa-regular fa-rectangle-list"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div><small>{{$count}} of {{$totalCount}} {{$totalCount>1?'topics':'topic'}}</small></div>
            <div><small>{{$page}} of {{$totalPages}} {{$totalPages>1?'pages':'page'}}</small></div>
        </div>
        @include('layout.admin.pagination', ['page' => $page, 'totalPages' => $totalPages])
    </div>

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
                                $sortarr = array('default' => 'Default', 'newest' => 'Newest First', 'oldest' => 'Oldest First', 'ascending' => 'Name Ascending', 'descending' => 'Name Descending', 'status' => 'Status');
                            @endphp
                            @foreach($sortarr as $key => $value)
                                <option value="{{ $key }}" {{ $key ==Request::input('sort') ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="page" class="form-label">Page Number</label>
                        <input type="number" class="form-control xfilter_field" id="page" name="page" step="1" min="1" value="{{ Request::input('page') }}">
                    </div>

                    <div class="form-group mb-3">
                        <label for="subject" class="form-label">Subject</label>
                        <select name="subject" id="subject" class="form-select xfilter_field">
                            <option value="">All Subjects</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ request('subject') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="name" class="form-label">Topic Name</label>
                        <input type="text" class="form-control xfilter_field" id="name" name="name" value="{{ request('name') }}">
                    </div>
                    <div class="form-group mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select xfilter_field" data-live-search="true">
                            @php
                                $statusarr = array('' => 'All', 'hidden' => 'Hidden', 'waiting' => 'Launch Soon', 'public' => 'Live');
                            @endphp
                            @foreach($statusarr as $key => $value)
                                <option value="{{ $key }}" {{ $key ==Request::input('status') ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="perpage" class="form-label">Per Page Count</label>
                        <input type="number" class="form-control xfilter_field" id="perpage" name="perpage" value="{{ Request::input('perpage') }}">
                    </div>
                    <div class="pt-2">
                        <input type="hidden" name="queryurl" class="queryurl" value="/admin/practice/topic">
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
