@extends('layout.structure')
@section('xmt_tit', '')
@section('xmt_des', '')
@section('xmt_rob', ' index,follow')
@section('xmt_can', '/')

@push('headcss')
<style>
    #hideshow{
        cursor:pointer;
    }
</style>
@endpush
@section('content')
<div class="container mt-5">
    <div class="row d-flex justify-content-center align-items-center">
        <div class="col-12 col-md-8 col-lg-6 col-xl-5">
            @if ($errors->any())
                {!! implode('', $errors->all('<div class="alert alert-danger" role="alert">:message</div>')) !!}
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                {{ session('error') }}
                </div>
            @endif
            <div id="card pt-3">
                <h5 class="text-center text-primary">User Login</h5>
                <form method="POST" action="register_submit" class="m-3 mb-5">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="email" class="text-primary">Email:</label>
                        <input type="text" class="form-control  mt-2" name="email" id="email" placeholder="Enter email" value="{{old('email')}}" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="password" class="text-primary">Password:</label>
                        <input type="password" class="form-control  mt-2" id="password" name="password"  placeholder="Enter password" required>
                    </div>
                    <!-- <span class="fa fa-eye text-primary mb-3" id="hideshow" onclick="showpass(this); return false;">&nbsp;&nbsp;Show password</span> -->
                    <p class=" text-primary"  id="hideshow" onclick="showpass(this); return false;">
                        <i class="fa fa-eye " id="icon"></i>
                        <span id="passText"> Show password</span>
                    </p>
                    <div class="form-group mb-3">
                        <label for="password_confirmation" class="text-primary">Re-enter Password:</label>
                        <input type="password" class="form-control  mt-2" id="password_confirmation" name="password_confirmation" placeholder="Re-Enter password" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="Name" class="text-primary">Full Name:</label>
                        <input type="name" class="form-control  mt-2" placeholder="Enter fullname" name="name" id="name" value="{{old('name')}}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="phone" class="text-primary">Phone:</label>
                        <input type="text" class="form-control  mt-2" placeholder="Enter phone no" name="phone" id="phone" value="{{old('phone')}}" required>
                    </div>
                    <div>
                        <p class="text-center"><button type="submit" class="btn btn-primary">Submit</button></p>
                    </div>
                </form>
            </div>
            <p class="text-center m-3"><a href="/user/login" class="btn btn-outline-info btn-block">Already Registered? Login here</a></p>
        </div>
    </div>
</div>
@endsection

@push('headcss')
<script>
function showpass()
{
	var x = document.getElementById('password');
	if(x.type=='password')
	{
		x.type='text';
    document. getElementById("icon"). className = "fa fa-eye-slash ";
    document. getElementById("passText"). innerHTML = " Hide password";
	} else
	{
		x.type='password';
    document. getElementById("icon"). className = "fa fa-eye";
    document. getElementById("passText"). innerHTML = " Show password";
	}
}
</script>
@endpush
