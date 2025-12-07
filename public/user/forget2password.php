 <?php
session_start();
// error_reporting(0);
define('myprivateaccess', TRUE);
require_once($_SERVER['DOCUMENT_ROOT']."/default/conn.php");
$error="";
if(!empty($_GET['email']) && !empty($_GET['otp']))
{
  $deemail=rawurldecode($_GET['email']);
  if (!filter_var($deemail, FILTER_VALIDATE_EMAIL)) 
  {
    $error = "Invalid email format";
  }
  $email=validate($deemail);
  $otp=$_GET['otp'];
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
      {
        if (!empty($_POST["password"]) && !empty($_POST["re_password"]))
        {
          if (preg_match('/^[0-9]*$/', $_POST['otp']) && preg_match('/^[A-Za-z0-9_?!@#$%^&*+=~|:;.,<>\'\"\`\\()\/{}\[\]-]*$/', $_POST['password']) && preg_match('/([A-Za-z])/', $_POST['password']) &&  preg_match('/([0-9_?!@#$%^&*+=~|:;.,<>\'\"\`\\()\/{}\[\]-])/', $_POST['password']))
          {
            $password=$_POST['password'];
            $re_password=$_POST['re_password'];
            if($password==$re_password)
            {    
              $sqlforget = "SELECT * FROM `users` WHERE `email`='$email' AND `otp`='$otp'";
              $resforget=$conn->query($sqlforget);
                if($resforget->num_rows==1)
                {
                  while($rowf = $resforget->fetch_assoc())
                  {
                    $id=$rowf['id'];
                    $cryptostrong=true;
                    $salt=bin2hex(openssl_random_pseudo_bytes(126,$cryptostrong));
                    $hashpassword= password_hash($password, PASSWORD_ARGON2I);
                    $mysql="UPDATE `users` SET `password`='$hashpassword',`salt`='$salt' WHERE `email`='$email' AND `otp`='$otp'";
                    if($conn->query($mysql)===true)
                    {
    
                      setcookie("id", $rowf['id'], time() + (365*24*3600), "/");
                      setcookie("salt", $salt, time() + (365*24*3600), "/");
                      header("Location: /user/profile"); exit();
                    }
                  }  
                } else
                {
                  $error="invalid user";
                }
            } else
            {
              $error="Password does not match";
            }
          } else
          {
            $error="Password must have letters and numbers";
          }
        } else
        {
          $error="Enter all input fields";
        }
      }
}
if(!empty($_COOKIE['email']) && !empty($_COOKIE['otp']))
{
  $email=$_COOKIE['email'];
  $otp=$_COOKIE['otp'];
  if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
      if (!empty($_POST["password"]) && !empty($_POST["re_password"]))
      {
        if (preg_match('/^[0-9]*$/', $_POST['otp']) && preg_match('/^[A-Za-z0-9_?!@#$%^&*+=~|:;.,<>\'\"\`\\()\/{}\[\]-]*$/', $_POST['password']) && preg_match('/([A-Za-z])/', $_POST['password']) &&  preg_match('/([0-9_?!@#$%^&*+=~|:;.,<>\'\"\`\\()\/{}\[\]-])/', $_POST['password']))
              {
                $password=$_POST['password'];
                $re_password=$_POST['re_password'];
                if($password==$re_password)
                {     
                  $salt=bin2hex(openssl_random_pseudo_bytes(126,$cryptostrong));
                  $hashpassword=password_hash($password, PASSWORD_ARGON2I);          
                  $mysql="UPDATE `users` SET `password`='$hashpassword' WHERE `email`='$email' AND `otp`='$otp'";
                  if($conn->query($mysql)===true)
                  {
                      header("Location: /user/profile"); exit();
                  }
                }
              }
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
          <p class="text-center h5 text-primary">Password Reset</p>
            <p id="error" class='text-center text-danger'><?php echo $error;?></p>
              <form method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>" class="m-3">
              <div class="form-group mb-3">
                <label class="text-primary">Enter password :</label>
                <input type="password" class="form-control  mt-2" id="password" name="password">
                <div class="input-group-append">
              </div>
              <span class="fa fa-eye text-primary mt-2" id="hideshow" onclick="showpass(this); return false;">&nbsp;&nbsp;Show password</span>
              </div>
              <div class="form-group mb-3">
                <label class="text-primary">Re-enter password :</label>
                <input type="password" class="form-control  mt-2" id="re_password" name="re_password" oninput="chrepass();">
              </div>
              <p id="repass_sp"></p>
                <p class="text-center"><button type="submit" class="btn btn-primary">Submit</button></p>
            </form><br>
        </div>
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
    document. getElementById("hideshow"). className = "fa fa-eye-slash text-primary";
    document. getElementById("hideshow"). innerHTML = " Hide password";
	} else
	{
		x.type='password';
    document. getElementById("hideshow"). className = "fa fa-eye text-primary";
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
</script>
</body>
</html>