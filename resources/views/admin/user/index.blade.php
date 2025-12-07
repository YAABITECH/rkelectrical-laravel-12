@extends('layout.admin.structure')
@section('xmt_tit', 'User Details | Admin')

@push('headcss')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.1.2/css/tempusdominus-bootstrap-4.min.css" />
@endpush
@section('content')
<main>
    <div class="container mt-3">
        <h1 class="text-center text-primary fs-5 lh-lg">User List</h1>
        <div class="text-center m-1 mb-3">
            {{-- <a class="btn btn-primary btn-sm" href="{{ route('admin.user.create') }}" role="button"><i class="fa-solid fa-plus"></i></a> --}}
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
        @if(session('user_credential'))
            <div class="card mb-3">
                <div class="card-header bg-primary-subtle">
                    <p class="card-title mb-0 fs-5 fw-bold">User Credential:</p>
                </div>
                <div class="card-body">
                    <textarea class="form-control" id="credential_div" rows="4" readonly style="resize: none;">Login Credential:&#13;&#10;Country Code: {{ session('user_credential')['ccode'] }}&#13;&#10;Phone: {{ session('user_credential')['phone'] }}&#13;&#10;Password: {{ session('user_credential')['password'] }}</textarea>
                    <div class="mt-2">
                        <button type="button" class="btn btn-primary btn-sm" onclick="select_credential(this)">Copy</button>
                        <button type="button" class="btn btn-success btn-sm" onclick="send_credential_whatsapp()">Send via WhatsApp</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="location.reload();">Reload Page</button>
                    </div>
                </div>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
            {{ session('error') }}
            </div>
        @endif
        @include('layout.admin.pagination', ['page' => $page, 'totalPages' => $totalPages])
        @foreach ($users as $user)
            <div class="card shadow border-0 rounded mb-4">
                <h5 class="card-title mb-0 p-3 pb-1">
                    <a class="text-decoration-none" href="tel:+{{ $user->ccode ?? '91' }}{{ $user->phone }}">
                        +{{ $user->ccode ?? '91' }} {{ $user->phone }}
                    </a>
                </h5>
                @if($user->name || $user->email)
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">{{ $user->name }}</h6>
                    <p class="card-text">{{ $user->email }}</p>
                </div>
                @endif
                <div class="card-footer border-0">
                    {{-- <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-primary btn-sm"><i class="fa-solid fa-edit"></i></a> --}}
                    <form action="{{ route('admin.user.destroy', $user->id) }}" method="post"
                        style="display: inline-block">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" type="submit"><i class="fa-solid fa-xmark"></i></button>
                    </form>
                    <a href="tel:{{ $user->ccode }}{{ $user->phone }}" class="btn btn-info btn-sm"><i class="fa-solid fa-phone"></i></a>
                    <a href="https://wa.me/{{ $user->ccode }}{{ $user->phone }}" class="btn btn-success btn-sm" target="_blank"><i class="fa-brands fa-whatsapp"></i></a>
                    {{-- <a href="{{ route('admin.course.enrol.index') }}?userid={{$user->id}}" class="btn btn-primary btn-sm"><i class="fa-solid fa-link"></i></a> --}}
                </div>
            </div>
        @endforeach
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div><small>{{$count}} of {{$totalCount}} {{$totalCount>1?'users':'user'}}</small></div>
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
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control xfilter_field" id="name" name="name" value="{{ Request::input('name') }}">
                </div>
                <div class="form-group mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="number" class="form-control xfilter_field" id="phone" name="phone" value="{{ Request::input('phone') }}">
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
                    <label for="userid" class="form-label">User ID</label>
                    <input type="number" class="form-control xfilter_field" id="userid" name="userid" value="{{ Request::input('userid') }}">
                </div>
                <div class="form-group mb-3">
                    <label for="perpage" class="form-label">Per Page Count</label>
                    <input type="number" class="form-control xfilter_field" id="perpage" name="perpage" value="{{ Request::input('perpage') }}">
                </div>
                <div class="pt-2">
                    <input type="hidden" name="queryurl" class="queryurl" value="/admin/user">
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
<script>
    function select_credential(e) {
        var div = document.getElementById("credential_div");
        div.select();
        document.execCommand("copy");
        e.innerHTML = "Copied!";
    }
    function send_credential_whatsapp() {
        var createdUserDiv = document.getElementById("credential_div");
        var text = createdUserDiv.value.trim();
        var encodedText = encodeURIComponent(text);
        var url = "https://wa.me/?text=" + encodedText;
        window.open(url,'_blank');
    }
</script>
@endpush