@extends('layout.structure')
@section('xmt_tit', '')
@section('xmt_des', '')
@section('xmt_rob', 'noindex, follow')
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
        <form method="POST" action="/user/login-submit" class="m-3 mb-5">
          @csrf
          <div class="form-group mb-3">
            <label class="text-primary">Email : </label>
            <input type="text" name="email" class="form-control mt-2" placeholder="Enter your email" value="{{old('email')}}">
          </div>
          <div class="form-group mb-3">
            <label class="text-primary">Password</label>
            <input type="password" class="form-control mt-2" name="password" id="password" placeholder="Enter your password" value="{{old('password')}}">
          </div>
          <input type="hidden" name="previousUrl" value="{{$previousUrl}}">
          <p class=" text-primary"  id="hideshow" onclick="showpass(this); return false;">
            <i class="fa fa-eye " id="icon"></i>
            <span id="passText"> Show password</span>
          </p>
          <p class="text-center"><button type="submit" class="btn btn-primary">Submit</button></p>
        </form>
      </div>
    </div>
  </div>
  <p class="text-center"><a href="/user/register" class="btn btn-outline-info btn-block">New user? Register here</a>
  {{-- <a href="/user/forget-password"  class="btn btn-outline-info btn-block">Forget Password?</a></p> --}}
</div>

@endsection
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
