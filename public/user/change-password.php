<?php
session_start();
// error_reporting(0);
define('myprivateaccess', TRUE);
require_once($_SERVER['DOCUMENT_ROOT']."/user/session.php");
$error=$oldpassword=$password=$re_password="";
$id=$rowsess['id'];
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  if (!empty($_POST["oldpassword"]) && !empty($_POST["password"]) && !empty($_POST["re_password"]))
  {
    if (preg_match('/^[A-Za-z0-9_?!@#$%^&*+=~|:;.,<>\'\"\`\\()\/{}\[\]-]*$/', $_POST['password']) && preg_match('/([A-Za-z])/', $_POST['password']) &&  preg_match('/([0-9_?!@#$%^&*+=~|:;.,<>\'\"\`\\()\/{}\[\]-])/', $_POST['password']) && preg_match('/^[A-Za-z0-9_?!@#$%^&*+=~|:;.,<>\'\"\`\\()\/{}\[\]-]*$/', $_POST['oldpassword']) && preg_match('/([A-Za-z])/', $_POST['oldpassword']) &&  preg_match('/([0-9_?!@#$%^&*+=~|:;.,<>\'\"\`\\()\/{}\[\]-])/', $_POST['oldpassword']))
    {
      $oldpassword=valid_data($_POST["oldpassword"]);
      $password=valid_data($_POST["password"]);
      $re_password=valid_data($_POST["re_password"]);
      if($password==$re_password)
      {
        $savedpassword=$rowsess['password'];
        if(password_verify($oldpassword, $savedpassword))
        {
          $salt=bin2hex(openssl_random_pseudo_bytes(126,$cryptostrong));
          $hashpassword= password_hash($password, PASSWORD_ARGON2I);
          $mysql="UPDATE `users` SET `password`='$hashpassword',`salt`='$salt' WHERE `id`='$id'";
          if($conn->query($mysql)===true)
          {
            $error.='<p class="text-success text-center">password updated successfully</p>';
            setcookie("id", $id, time() + (365*24*3600), "/");
            setcookie("salt", $salt, time() + (365*24*3600), "/");
          }
        }
        else
        {
          $error.='<p class="text-center text-danger">Incorrect password</p>';
        }
      }else
      {
        $error.='<p class="text-center text-danger">Password are not same</p>';
      }
    }
    else
    {
      $error.='<p class="text-center text-danger">Password must contain letters with number or symbols</p>';
    }
  }
  else
  {
    $error.='<p class="text-center text-danger">Fill all the input fields</p>';
  }
}
function valid_data($data) {
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
<body  style="background-image: url('/images/.jpg');background-repeat: repeat;">
<?php include_once($_SERVER['DOCUMENT_ROOT']."/default/menu.php"); ?>
<br>
  <div class="container">
    <div class="row">
      <div class="col-md-2">
      </div>
      <div class="col-md-8">
        <div class="row text-center bg-light p-3 m-1 mb-4 shadow">
            <div class="col-3 col-md-3">
                <a href="/user/profile" class=" text-decoration-none font-weight-bold"><span class="fa fa-arrow-left text-info"> Profile</a>
            </div>
            <div class="col-3 col-md-3">
            </div>
            <div class="col-3 col-md-3">
            </div>
            <div class="col-3 col-md-3">
                <a href="/user/logout" class=" text-decoration-none text-info">Logout</a>
            </div>
        </div>
        <div class="card shadow">
          <br>
          <p class="text-center h5">Change Password</p>
          <?php echo $error;?>
            <form method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>" class="m-3">
              <div class="form-group mb-3">
                <label class="text-primary">Old password&nbsp;:</label>
                <input type="password" class="form-control mt-2" name="oldpassword" value="<?php echo $oldpassword;?>">
              </div>
              <div class="form-group mb-3">
                <label class="text-primary">New Password</label>
                <input type="password" class="form-control mt-2" id="password" name="password" value="<?php echo $password;?>">
              </div>
              <span class="fa fa-eye text-primary mb-2" id="hideshow" onclick="showpass(this); return false;">&nbsp;&nbsp;Show password</span>
              <div class="form-group mb-3">
                <label class="text-primary">Re-enter password :</label>
                <input type="password" class="form-control mt-2" id="re_password" name="re_password" oninput="chrepass();" value="<?php echo $re_password;?>">
              </div>
              <p id="repass_sp"></p>
              <p class="text-center"><button type="submit" class="btn btn-primary" id="check1">Submit</button></p>
            </form><br>
        </div><br><br>
        </div>
      </div>
      <div class="col-md-2">
      </div>
    </div>
  </div><br>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/default/footer.php"); ?>
<script>
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
</script>
</body>
</html>