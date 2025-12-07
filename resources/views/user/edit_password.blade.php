@extends('layout.structure')
@section('xmt_tit', 'Edit Password')
@section('xmt_des', '')
@section('xmt_rob', 'index,follow')
@section('xmt_can', '/')

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger">{{ $error }}</div>
                        @endforeach
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <div class="d-flex justify-content-between align-items-center text-center bg-light p-3 m-1 mb-4 shadow">
                        <div>
                            <a href="/user/profile" class=" text-decoration-none fw-bold text-info fs-5"><i class="fa-solid fa-arrow-left text-info pe-2"></i> Profile</a>
                        </div>
                        <div>
                            <a href="/user/logout" class=" text-decoration-none text-info fw-bold fs-5">Logout</a>
                        </div>
                    </div>
                    <div class="card shadow py-5">
                        <p class="text-center h5">Change Password</p>
                        <form method="POST" action="{{route('user.update_password')}}" class="m-3">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="text-primary">Old password&nbsp;:</label>
                                <input type="password" class="form-control mt-2" name="oldpassword" value="{{old('oldpassword')}}">
                            </div>
                            <div class="form-group mb-3">
                                <label class="text-primary">New Password</label>
                                <input type="password" class="form-control mt-2" id="password" name="password" value="">
                            </div>
                            <p class=" text-primary"  id="hideshow" onclick="showpass(this); return false;">
                                <i class="fa fa-eye " id="icon"></i>
                                <span id="passText"> Show password</span>
                            </p>
                            <div class="form-group mb-3">
                                <label class="text-primary">Re-enter password :</label>
                                <input type="password" class="form-control mt-2" id="re_password" name="re_password" oninput="chrepass();" value="">
                            </div>
                            <p id="repass_sp"></p>
                            <p class="text-center"><button type="submit" class="btn btn-primary" id="check1">Submit</button></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
<script>
function chrepass(val)
{
	var pass = document.getElementById('password').value;
	var re_pass = document.getElementById('re_password').value;
	if(pass==re_pass)
	{
		document.getElementById('repass_sp').innerHTML = "<span class='text-primary'>Passwords matched</span>";
	} else
	{
		document.getElementById('repass_sp').innerHTML = "<span class='text-info'>Passwords do not match</span>";
	}
}

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
