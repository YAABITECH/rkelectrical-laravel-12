<?php
session_start();
//error_reporting(0);
define('myprivateaccess', TRUE);
$error="";
require_once($_SERVER['DOCUMENT_ROOT']."/default/conn.php");
if(!empty($_GET['redirect'])){$redirect=rawurldecode($_GET['redirect']);}
$metatitle="";
$metadescription="";
$metarobots="index,follow";
$metacanonical="/";
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<?php include_once($_SERVER['DOCUMENT_ROOT']."/default/header.php"); ?>
<body>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/default/menu.php"); ?>
<br>
<div class="container">
    <div class="row">
        <div class="col-sm-12 col-md-3">
        </div>
        <div class="col-sm-12 col-md-6">
            <div class="card shadow" style="background-color:rgb(255, 255, 255,0.8);"><br>
                <div id="regform" style="display:block;" class="p-3">
                    <p class="text-center h5 text-primary">Register</p>
                    <div id="errdiv"></div>
                    <div id="maindiv" style="display:block">
                        <div class="form-group mb-3">
                            <label for="email" class="text-primary">Email:</label>
                            <input type="email" class="form-control  mt-2" name="email" id="email" placeholder="Enter email" required>
                        </div>
                        <div class="form-group mb-2">
                            <label for="password" class="text-primary">Password:</label>
                            <input type="password" class="form-control  mt-2" id="password" name="password"  placeholder="Enter password" required>
                        </div>
                        <span class="fa fa-eye text-primary mb-3" id="hideshow" onclick="showpass(this); return false;">&nbsp;&nbsp;Show password</span>
                        <div class="form-group mb-3">
                            <label for="re_password" class="text-primary">Re-enter Password:</label>
                            <input type="password" class="form-control  mt-2" id="re_password" name="re_password" onchange="chrepass();" placeholder="Re-Enter password" required>
                        </div>
                        <p id="repass_sp" class="text-danger"></p>
                        <div id="mainform" style="display: block;">
                            <div class="form-group mb-3">
                                <label for="Name" class="text-primary">Full Name:</label>
                                <input type="name" class="form-control  mt-2" placeholder="Enter fullname" name="name" id="name" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="phone" class="text-primary">Phone:</label>
                                <input type="text" class="form-control  mt-2" placeholder="Enter phone no" name="phone" id="phone" required>
                            </div>
                        </div>
                    </div>    
                    <p class="text-center"><button type="submit" class="btn btn-primary" onclick="regsub();">Submit</button></p>
                    <br>
                </div>
                <div id="infocard" style="display:none;" class="p-2">
                    <h5 class="text-center pb-2 text-primary">Select one to continue</h5>
                    <div class="row">
                        <div class="col-sm-6">
                            <a href="/user/student-register?key=course" class="text-decoration-none"><div class="card bg-warning">
                                <div class="text-center">
                                    <h2 class="h5 text-light p-2 pt-3">Paid Course</h2>
                                    <img src="/default/images/rkelectricalgrid-logo-new.png" alt="Card image" style="width:100%; max-width:100px;">
                                    <br><br>
                                </div>
                            </div>
                        </div></a>
                        <div class="col-sm-6">
                            <a href="/user/student-register?key=test" class="text-decoration-none"><div class="card bg-info">
                                <div class="text-center">
                                    <h2 class="h5 text-light p-2 pt-3">Test Series</h2>
                                    <img src="/default/images/rkelectricalgrid-logo-new.png" alt="Card image" style="width:100%; max-width:100px;">
                                    <br><br>
                                </div>
                            </div></a>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-6">
                            <a href="/practice/" class="text-decoration-none"><div class="card bg-primary">
                                <div class="text-center">
                                    <h2 class="h5 text-light p-2 pt-3">Practice</h2>
                                    <img src="/default/images/rkelectricalgrid-logo-new.png" alt="Card image" style="width:100%; max-width:100px;">
                                    <br><br>
                                </div>
                            </div></a>
                        </div>
                        <div class="col-sm-6">
                            <a href="/test-series/" class="text-decoration-none"><div class="card bg-success">
                                <div class="text-center">
                                    <h2 class="h5 text-light p-2 pt-3">Free Test</h2>
                                    <img src="/default/images/rkelectricalgrid-logo-new.png" alt="Card image" style="width:100%; max-width:100px;">
                                    <br><br>
                                </div>
                            </div></a>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <p class="text-center m-3"><a href="/user/login?redirect=<?php if(!empty($redirect)){echo rawurlencode($redirect);} ?>" class="btn btn-outline-info btn-block">Already Registered? Login here</a></p>
            <br>
        </div>
        <div class="col-sm-12 col-md-3">
        </div>
    </div>
</div><br>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/default/footer.php"); ?>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/default/livechatbtn.php"); ?>
<script> 
function showpass()
{
	var x = document.getElementById('password');
	if(x.type=='password')
	{
		x.type='text';
    document. getElementById("hideshow"). className = "fa fa-eye-slash text-primary mb-3";
    document. getElementById("hideshow"). innerHTML = " Hide password";
	} else
	{
		x.type='password';
    document. getElementById("hideshow"). className = "fa fa-eye text-primary mb-3";
    document. getElementById("hideshow"). innerHTML = " Show password";
	}
}
function chrepass(val) 
{
	var pass = document.getElementById('password').value;
	var re_pass = document.getElementById('re_password').value;
	if(pass==re_pass) 
	{
		document.getElementById('repass_sp').innerHTML = "<span class='purple-text'>Passwords matched</span>";
	} else 
	{
		document.getElementById('repass_sp').innerHTML = "<span class='pink-text'>Passwords do not match</span>";
	}
}
function regsub()
{
    var email=document.getElementById("email").value;
    var password=document.getElementById("password").value;
    var re_password=document.getElementById("re_password").value;
    var name=document.getElementById("name").value;
    var phone=document.getElementById("phone").value;
	var formData = new FormData(); 
    formData.append("email", email);
    formData.append("password", password);
    formData.append("re_password", re_password);
    formData.append("name", name);
    formData.append("phone", phone);
	var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200)
	  {
        res = this.responseText;
		if(res=="ok")
		{
			document.getElementById("infocard").style.display="block";
            document.getElementById("regform").style.display="none";
    	}
		else
		{
			document.getElementById("errdiv").innerHTML="<p class='text-center text-danger'>"+res+"</p>";
            // alert(res);
		}
      }
    };
    xmlhttp.open("POST", "/dynamic/regsub", true);
    xmlhttp.send(formData);
}
</script>
</body>
</html>