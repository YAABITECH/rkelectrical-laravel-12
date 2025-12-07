@extends('layout.admin.structure')
@section('xmt_tit', 'Blog | Admin')

@push('headcss')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.1.2/css/tempusdominus-bootstrap-4.min.css" />
@endpush
@section('content')
<main>
    <div class="container mt-3">
        <h1 class="text-center text-primary fs-5 lh-lg">Blog List</h1>
        <div class="text-center m-1 mb-3">
            <a class="btn btn-primary btn-sm" href="{{ route('admin.blog.create') }}" role="button"><i class="fa-solid fa-plus"></i></a>
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
        @include('layout.admin.partials.paginationtop', ['paginator' => $items])
        @foreach ($items as $item)
            <div class="card shadow border-0 rounded mb-4">
                <div class="card-header border-0">
                    <h5 class="card-title mb-0">{{ $item->title }}</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">{{ $item->author }}</p>
                </div>
                <div class="card-footer border-0">
                    <a href="{{ route('admin.blog.edit', $item->id) }}" class="btn btn-primary btn-sm"><i class="fa-solid fa-edit"></i></a>
                    <form action="{{ route('admin.blog.destroy', $item->id) }}" method="post"
                        style="display: inline-block">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" type="submit"><i class="fa-solid fa-xmark"></i></button>
                    </form>
                </div>
            </div>
        @endforeach
        @include('layout.admin.partials.paginationbottom', ['paginator' => $items])
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
                            $sortarr = array('default' => 'Default', 'newest' => 'Newest First', 'oldest' => 'Oldest First');
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
                    <label for="title" class="form-label">Blog Title</label>
                    <input type="text" class="form-control xfilter_field" id="title" name="title" value="{{ Request::input('title') }}">
                </div>
                <div class="form-group mb-3">
                    <label for="date_from" class="form-label">Registered From</label>
                    <div class="input-group date datetimepicker-input" id="datetimepicker1" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input xfilter_field" id="date_from" name="date_from" data-target="#datetimepicker1" value="{{ Request::input('date_from') }}">
                        <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                            <div class="input-group-text" style="height: 100%;"><i class="far fa-calendar-alt text-primary"></i></div>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label for="date_to" class="form-label">Registered To</label>
                    <div class="input-group date datetimepicker-input" id="datetimepicker2" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input xfilter_field" id="date_to" name="date_to" data-target="#datetimepicker2" value="{{ Request::input('date_to') }}">
                        <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                            <div class="input-group-text" style="height: 100%;"><i class="far fa-calendar-alt text-primary"></i></div>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label for="perpage" class="form-label">Per Page Count</label>
                    <input type="number" class="form-control xfilter_field" id="perpage" name="perpage" value="{{ Request::input('perpage') }}">
                </div>
                <div class="pt-2">
                    <input type="hidden" name="queryurl" class="queryurl" value="/admin/blog">
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

        $('#date_from').on('focus', function () {
            $('#datetimepicker1').datetimepicker('show');
        });
        $('#date_to').on('focus', function () {
            $('#datetimepicker2').datetimepicker('show');
        });
    });
</script> 
@endpush