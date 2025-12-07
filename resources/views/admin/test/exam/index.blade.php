@extends('layout.admin.structure')
@section('xmt_tit', 'Test Exam | Admin')

@section('content')
    <div class="container mt-3">
        <h1 class="text-center text-primary fs-5 lh-lg">Test Exams</h1>
        @if($series)
        <p class="text-center">{{ $series->name }}</p>
        @endif
        <div class="text-center m-1 mb-3">
            <a class="btn btn-primary btn-sm" href="{{ route('admin.test.exam.create',['series_id' => $series->id ?? null]) }}" role="button"><i class="fa-solid fa-plus"></i></a>
            <button class="btn btn-primary btn-sm" role="button" data-bs-toggle="modal" data-bs-target="#xfilter_model"><i class="fa-solid fa-filter"></i></button>
            <a class="btn btn-primary btn-sm" href="{{ route('admin.test.exam.arrange') }}" role="button"><i class="fa-solid fa-layer-group"></i></a>
            <a class="btn btn-primary btn-sm" href="{{ route('admin.test.exam.index') }}" role="button"><i class="fa-solid fa-list"></i></a>
            <a class="btn btn-primary btn-sm" href="{{ route('admin') }}" role="button"><i class="fa-solid fa-home"></i></a>
        </div>
        @if ($errors->any())
            {!! implode('', $errors->all('<div class="alert alert-danger text-center" role="alert">:message</div>')) !!}
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
        @include('layout.admin.partials.paginationtop', ['paginator' => $items])
        @foreach ($items as $item)
            <div class="card shadow border-0 rounded mb-4">
                <div class="card-header border-0 pt-4">
                    <h5 class="card-title mb-0">{!! $item->name !!} <span class="badge text-bg-secondary">{{$item->difficulty}}</span></h5>
                </div>
                <div class="card-body">
                    <a href="{{route('admin.test.exam.edit',$item->id)}}" class="btn btn-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Test exam"><i class="fa-solid fa-edit"></i></a>
                    <form action="{{ route('admin.test.exam.destroy', $item->id) }}" method="post"
                        style="display: inline-block" onsubmit="return confirmDelete()">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" type="submit" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Test Exam"><i class="fa-solid fa-xmark"></i></button>
                    </form>
                    <a href="{{route('admin.test.question.manage',['exam_id' => $item->id])}}" class="btn btn-success btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Manage Questions for this Test"><i class="fa-solid fa-clipboard-question"></i></a>
                </div>
            </div>
        @endforeach
        @include('layout.admin.partials.paginationbottom', ['paginator' => $items])
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
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select xfilter_field" data-live-search="true">
                            @php
                                $statusarr = array('' => 'All', 'draft' => 'Draft', 'upcoming' => 'Upcoming', 'active' => 'Active', 'archive' => 'Archive');
                            @endphp
                            @foreach($statusarr as $key => $value)
                                <option value="{{ $key }}" {{ $key ==Request::input('status') ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="page" class="form-label">Page Number</label>
                        <input type="number" class="form-control xfilter_field" id="page" name="page" step="1" min="1" value="{{ Request::input('page') }}">
                    </div>
                    <div class="form-group mb-3">
                        <label for="perpage" class="form-label">Per Page Count</label>
                        <input type="number" class="form-control xfilter_field" id="perpage" name="perpage" value="{{ Request::input('perpage') }}">
                    </div>
                    <div class="pt-2">
                        <input type="hidden" name="queryurl" class="queryurl" value="{{ route('admin.test.exam.index') }}">
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
