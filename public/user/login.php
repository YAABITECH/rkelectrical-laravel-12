<?php
//error_reporting(0);
define('myprivateaccess', TRUE);
require_once($_SERVER['DOCUMENT_ROOT']."/default/conn.php");
$error=$email=$password="";
$redirect='';
if(!empty($_GET['redirect'])){$redirect=rawurldecode($_GET['redirect']);}
if(!empty($_COOKIE['id']) || !empty($_COOKIE['salt']))
{
	header("Location: /user/profile");
	exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  if (!empty($_POST["email"]) && !empty($_POST["password"]))
	{
    $email = valid_data($_POST["email"]);
    $password=valid_data($_POST["password"]);
    $logsql="SELECT * FROM `users` WHERE `email`='$email'";
    $logres=$conn->query($logsql);
    if ($logres->num_rows == 1)
    {
      while($row = $logres->fetch_assoc())
      {
        $salt=$row['salt'];
        $savedpassword=$row['password'];
        if(password_verify($password, $savedpassword))
        {
          setcookie("id", $row['id'], time() + (365*24*3600), "/");
          setcookie("salt", $row['salt'], time() + (365*24*3600), "/");
          if(!empty($redirect))
          {
            header("Location: $redirect"); exit();
          }
          header("Location: /user/profile");
          exit();
        } else
        {
          $error.="Password incorrect";
        }
      }
    } else
    {
      $error.="Oops! you are not registered";
    }
  } else
  {
    $error.="Enter all input fileds";
  }
}

function valid_data($data)
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
      <div class="col-sm-12 col-md-3">
      </div>
      <div class="col-sm-12 col-md-6">
        <div id="card">
          <br>
       <h5 class="text-center text-primary">User Login</h5>
          <p id="error" class="text-center text-danger"><?php echo $error;?></p>
            <form method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>" class="m-3">
              <div class="form-group mb-3">
                <label class="text-primary">Email : </label>
                <input type="email" name="email" class="form-control mt-2" placeholder="Enter your email">
              </div>
              <div class="form-group mb-3">
                <label class="text-primary">Password</label>
                <input type="password" class="form-control mt-2" name="password" id="password" placeholder="Enter your password">
              </div>
              <span class="fa fa-eye text-primary" id="hideshow" onclick="showpass(this); return false;">&nbsp;&nbsp;Show password</span>
              <p class="text-center"><button type="submit" class="btn btn-primary">Submit</button></p>
            </form><br>
        </div><br><br>
        <p class="text-center"><a href="/user/register?redirect=<?php echo rawurlencode($redirect); ?>" class="btn btn-outline-info btn-block">New user? Register here</a><br><br>
        <a href="/user/forget-password"  class="btn btn-outline-info btn-block">Forget Password?</a></p><br>
        </div>
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