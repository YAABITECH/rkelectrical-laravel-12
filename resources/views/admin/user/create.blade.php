@extends('layout.admin.structure')
@section('xmt_tit', 'Create User | Admin')

@push('headcss')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.1.2/css/tempusdominus-bootstrap-4.min.css" />
@endpush
@php
  $oldccode = old('ccode', $geoData['ccode']);
@endphp
@section('content')
<div class="container mt-3">
    <h1 class="text-center text-primary fs-5 lh-lg">User Create</h1>
    <div class="text-center m-1 mb-3">
        <a class="btn btn-primary btn-sm" href="{{ route('admin.user.index') }}" role="button"><i class="fa-solid fa-list"></i></a>
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
    <form method="post" action="{{ route('admin.user.store') }}" enctype="multipart/form-data" autocomplete="off">
        @csrf
        <p class="text-center">* fields are compulsory</p>
        <div class="form-group mb-3">
            <label for="ccode" class="form-label">Country Code *</label>
            <select name="ccode" id="ccode" class="form-select" data-live-search="true" required>
                @foreach($countries as $country)
                    <option value="{{ $country->ccode }}" {{ $country->ccode == $oldccode ? 'selected' : '' }}>{{ $country->iso }} (+{{$country->ccode}})</option>
                @endforeach
            </select>
        </div>
        <div class="form-group mb-3">
            <label for="phone" class="form-label">Phone Number *</label>
            <input type="text" class="form-control" name="phone" id="phone" value="{{ old('phone') }}" required readonly onfocus="this.removeAttribute('readonly');">
        </div>
        <div class="form-group mb-3">
            <label for="password" class="form-label">Password *</label>
            <div class="input-group" id="show_hide_password">
                <input type="text" class="form-control" id="password" name="password" value="{{ old('password') }}" required readonly onfocus="this.removeAttribute('readonly');">
                <div class="input-group-append">
                    <span class="input-group-text">
                        <a href="" onclick="password_visible();return false;"><i class="fa fa-eye" aria-hidden="true"></i></a>
                    </span>
                </div>
            </div>
            <div class="mt-2">
                <button type="button" class="btn btn-secondary btn-sm" id="generate-password">Generate Password</button>
            </div>
        </div>
        <div class="form-group mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}">
        </div>
        <div class="form-group mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}" readonly onfocus="this.removeAttribute('readonly');">
        </div>
        <div class="form-group mb-3">
            <label for="dob" class="form-label">Date of Birth</label>
            <div class="input-group date datetimepicker-input" id="dobpicker" data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input" id="dob" name="dob" data-target="#dobpicker" value="{{ old('dob') }}">
                <div class="input-group-append" data-target="#dobpicker" data-toggle="datetimepicker">
                    <div class="input-group-text" style="height: 100%;"><i class="far fa-calendar-alt text-primary"></i></div>
                </div>
            </div>
        </div>
        <div class="form-group py-3">
            <button type="submit" class="btn btn-block btn-primary">Create User</button>
        </div>
    </form>
<div>
@endsection
@push('endjs')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.1.2/js/tempusdominus-bootstrap-4.min.js"></script>
<script>
    $(function () {
        $('#dobpicker').datetimepicker({
            format: 'DD-MM-YYYY',
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

        $('#dob').on('focus', function () {
            $('#dobpicker').datetimepicker('show');
        });
    });
    $(document).ready(function(){
        $("#show_hide_password a").on('click', function(event) {
            event.preventDefault();
            if($('#password').attr("type") == "text"){
                $('#password').attr('type', 'password');
                $('#show_hide_password i').addClass( "fa-eye-slash" );
                $('#show_hide_password i').removeClass( "fa-eye" );
            }else if($('#password').attr("type") == "password"){
                $('#password').attr('type', 'text');
                $('#show_hide_password i').removeClass( "fa-eye-slash" );
                $('#show_hide_password i').addClass( "fa-eye" );
            }
        });
    });
    $(document).ready(function() {
        $('#generate-password').click(function() {
            $.ajax({
                type: 'GET',
                url: '/user/generate-password',
                success: function(response) {
                    $('#password').val(response.password);
                }
            });
        });
    });
</script>
@endpush
