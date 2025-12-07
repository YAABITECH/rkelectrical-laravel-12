@extends('layout.admin.structure')
@section('xmt_tit', 'Create Admin | Admin')

@section('content')
<div class="container mt-3">
    <h1 class="text-center text-primary fs-5 lh-lg">Create New Admin</h1>
    <div class="text-center m-1 mb-3">
        <a class="btn btn-primary btn-sm" href="{{ route('admin.admin.index') }}" role="button"><i class="fa-solid fa-list"></i></a>
        <a class="btn btn-primary btn-sm" href="{{ route('admin') }}" role="button"><i class="fa-solid fa-home"></i></a>
    </div>
    @if ($errors->any())
        {!! implode('', $errors->all('<div class="alert alert-danger" role="alert">:message</div>')) !!}
    @endif
    <form method="post" action="{{ route('admin.admin.store') }}">
        @csrf
        <p class="text-center">* fields are compulsory</p>
        <div class="form-group py-3">
            <label for="name" class="form-label">Admin Name *</label>
            <input type="text" class="form-control" name="name" id="name" oninput="getusername_t()" value="{{ old('name') }}" required>
        </div>
        <div class="form-group py-3">
            <label for="username" class="form-label">Username *</label>
            <input type="text" class="form-control" name="username" id="username" oninput="getusername(this.value)" value="{{ old('username') }}" required>
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
        <div class="form-group py-3">
            <button type="submit" class="btn btn-block btn-primary">Create Admin</button>
        </div>
    </form>
<div>
@endsection
@push('endjs')
<script>
    function getusername_t()
    {
        title = document.getElementById("name").value;
        temp = tousername(title);
        document.getElementById("username").value=temp;
    }
    function getusername(val)
	{
        temp = tousername(val);
        document.getElementById("username").value=temp;
	}
    function tousername(url)
    {
        return url.toString()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g,'')
        .replace(/\s+/g,'_')
        .toLowerCase()
        .replace(/&/g,'_and_')
        .replace(/[^a-z0-9_.\-]/g,'')
        .replace(/[-_\.]+/g,'_')
        .replace(/^[-_\.]+/,'')
        .replace(/[-_\.]+$/,'');
    }
</script>
<script>
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
            var passwords = [];
            $.ajax({
                type: 'GET',
                url: '/admin/generate-password',
                success: function(response) {
                    passwords = response.passwords;
                    var currentIndex = 0;
                    var interval = setInterval(function() {
                        $('#password').val(passwords[currentIndex]);
                        currentIndex = (currentIndex + 1) % passwords.length;
                    }, 50);

                    setTimeout(function() {
                        clearInterval(interval);
                        $('#password').val(passwords[passwords.length - 1]);
                    }, 2000);
                }
            });
        });
    });
</script>
@endpush
