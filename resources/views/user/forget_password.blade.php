@extends('layout.structure')
@section('xmt_tit', 'Forget Password')
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
                    <div class="card shadow py-5">
                        <p class="text-center h5">Forget Password</p>
                        <div id="email_alert"></div>
                        <div id="email_div">
                            <div class="form-group m-3">
                                <label class="text-primary">Enter Email&nbsp;:</label>
                                <input type="email" class="form-control mt-2" id="email" value="{{old('email')}}" >
                            </div>
                            <p class="text-center" id="checkEmail"><button type="submit" class="btn btn-primary"  onclick="checkEmail()">Submit</button></p>
                        </div>
                        <div class="d-none " id="passwordDiv">
                            <form method="POST" action="{{route('user.update_forget_password')}}" class="m-3">
                                @csrf
                                <div class="form-group mb-3">
                                    <label class="text-primary">New Password</label>
                                    <input type="password" class="form-control mt-2" id="password" name="password" value="">
                                </div>
                                <p class=" text-primary"  id="hideshow" onclick="showpass(this); return false;">
                                    <i class="fa fa-eye " id="icon"></i>
                                    <span id="passText"> Show password</span>
                                </p>
                                <input type="hidden" name="email" id="updateEmail" value="">
                                <p id="repass_sp"></p>
                                <p class="text-center"><button type="submit" class="btn btn-primary" id="check1">Submit</button></p>
                            </form>
                        </div>
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

function checkEmail() {
    var emailDiv = document.getElementById('email_div');
    var email = document.getElementById('email').value.trim();
    var updateEmail = document.getElementById('updateEmail');
    var emailAlert = document.getElementById('email_alert');
    var passwordDiv = document.getElementById('passwordDiv');

    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!emailPattern.test(email)) {
        alert('Please enter a valid email address.');
        return;
    }
    if (email.length > 0) {
        $.ajax({
            url: '/user/check-user',
            type: 'GET',
            data: {
                email: email,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.status === 'success') {
                    passwordDiv.classList.remove('d-none');
                    emailDiv.classList.add('d-none');
                    updateEmail.value=email;
                } else {
                    emailAlert.innerHTML('<div class="alert alert-danger">Try with another email</div>');
                    passwordDiv.classList.add('d-none');
                    emailDiv.classList.remove('d-none');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }
}

</script>
