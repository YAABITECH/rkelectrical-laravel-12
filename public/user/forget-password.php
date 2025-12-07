<?php
session_start();
// error_reporting(0);
define('myprivateaccess', TRUE);
require_once($_SERVER['DOCUMENT_ROOT']."/default/conn.php");
$error="";
$one="block";
$two="none";
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  if (!empty($_POST["email"]) && !empty($_POST["otp"]))
	{
    if (preg_match('/^[0-9]*$/', $_POST['otp']))
      {
        $deemail=$_POST["email"];
        if (!filter_var($deemail, FILTER_VALIDATE_EMAIL)) 
        {
          $error = "Invalid email format";
        }
        $email = validate($deemail);
        $otp=$_POST['otp'];
        $enc_email=rawurlencode($email);
        header("Location: /user/forget2password?otp=".$otp."&email=".$enc_email);
        exit();
      }
    }
}
function validate($data)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
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
      <div class="col-12 col-md-3">
      </div>
      <div class="col-12 col-md-6">
        <div id="card">
          <br>
          <h5 id="darkpink" class="text-center">Forget Password</h5>
            <p id="error" class="text-center text-danger"></p>
              <form method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>" class="m-3">
                <div class="form-group mb-3">
                  <label class="text-primary">Email : </label>
                  <input type="email" name="email" id="email" class="form-control mt-2" placeholder="Enter your email">
                </div>
                <div style="display:block;" id="first">
                  <p class="text-center"><button type="button" class="btn btn-primary" onclick="sendmail()">Send OTP</button></p>
                </div>
                <div style="display:none;" id="second">
                  <div class="form-group mb-3">
                    <label class="text-primary">otp</label>
                    <input type="number" name="otp" id="otp" class="form-control mt-2" >
                  </div>
                  <div style="display:block;" id="third">
                    <p class="text-center"><button type="button" class="btn btn-primary" onclick="verify()">Verify OTP</button></p>
                  </div>
                </div>
                <div id="fourth" style="display:none;">
                <p class="text-center"><button type="submit" class="btn btn-primary">Submit</button></p>
                </div>
            </form><br>
        </div><br><br>
        <p class="text-center"><a href="/user/register.php" class="btn btn-outline-info">Remember password? Login here</a></p><br>
        </div>
      </div>
      <div class="col-12 col-md-3">
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
	} else
	{
		x.type='password';
	}
}
function sendmail()
{
    var email=document.getElementById("email").value;
    var enemail=encodeURIComponent(email);
    var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{
				txt = this.responseText;
          if(txt=="ok")
          {
            document.getElementById("first").style.display="none";
            document.getElementById("second").style.display="block";
            document.getElementById('error').innerHTML="Enter your OTP. Usually takes upto 2 mins.";
          } else if(txt!="")
          {
            document.getElementById('error').innerHTML=txt;
          }
      }
    }
		xmlhttp.open("GET", "/dynamic/sent-mail?email=" + enemail, true);
		xmlhttp.send();
}
function verify()
{
    var otp=document.getElementById("otp").value;
    var email=document.getElementById("email").value;
    var enemail=encodeURIComponent(email);
    var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{
        txtt = this.responseText;
          if(txtt=="ok")
          {
            document.getElementById("first").style.display="none";
            document.getElementById("third").style.display="none";
            document.getElementById("fourth").style.display="block";
            document.getElementById('error').innerHTML="OTP verified. Click submit button";
          }
      }
    }
		xmlhttp.open("GET", "/dynamic/verify-mail?otp=" + otp + "&email=" +enemail , true);
		xmlhttp.send();
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
</script>
</body>
</html>