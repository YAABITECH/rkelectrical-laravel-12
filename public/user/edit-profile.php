<?php
session_start();
error_reporting(0);
define('myprivateaccess', TRUE);
require_once($_SERVER['DOCUMENT_ROOT']."/user/session.php");
$out="";
    $myuser_id=$_COOKIE['id'];
    $name=$rowsess['name'];     
    $email=$rowsess['email'];
    $phone=$rowsess['phone'];
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        if (!empty($_POST["user_email"]) && !empty($_POST["user_name"]) && !empty($_POST["user_phone"]))
        {  
            if (preg_match('/^[A-Za-z0-9_?!@#$%^&*+=~|:;.,<>\'\"\`\\()\/{}\[\]-]*$/', $_POST['user_email']) && preg_match('/([A-Za-z])/', $_POST['user_name']) && preg_match('/([0-9]{10})/', $_POST['user_phone']) )
            {
                $email = validate_data($_POST["user_email"]);
                $name=validate_data($_POST["user_name"]);
                $phone=validate_data($_POST["user_phone"]);
                $edtsql="UPDATE `users` SET `email`='$email',`name`='$name',`phone`='$phone' WHERE `id`='$myuser_id'";
                $edtres=$conn->query($edtsql);
                if($edtres===true)
                {
                    $out="Updated successfully";
                }
            }
        }
    }                
function validate_data($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }   
$metatitle="User Profile";
$metadescription="";
$metarobots="no index,follow";
$metacanonical="/";
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<?php include_once($_SERVER['DOCUMENT_ROOT']."/default/header.php"); ?>
<body>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/default/menu.php"); ?>
<br>
<div class="container py-3">
    <div class="row">
        <div class="col-12 col-lg-8 offset-lg-2 col-md-10 offset-md-1">
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
            <div class="card shadow rounded">
                <div class="card-body bg-info rounded">
                <p class="text-light pl-3 h4"><span class="fa fa-user pr-2"></span> Edit Advanced</p>
                </div>
                <div class="card-title px-3"> 
                <form action="<?php echo $_SERVER['REQUEST_URI'];?>" method="POST" enctype="multipart/form-data"> 
                <p class="text-success text-center pt-3"><?php echo $out;?></p>  
                    <table class="table">
                        <tr>
                            <th scope="row">NAME</th>
                            <td class="text-success pt-3"><input type="text" name="user_name" value="<?php echo $name;?>" class="form-control"></td>
                        </tr>
                        <tr>
                            <th scope="row">EMAIL</th>
                            <td class="text-success pt-3"><input type="text" name="user_email" value="<?php echo $email;?>" class="form-control"></td></tr>
                        <tr>
                            <th scope="row">PHONE</th>
                            <td class="text-success pt-3"><input type="text" name="user_phone"  value="<?php echo $phone;?>" class="form-control"></td>
                        </tr>
                    </table>      
                </div>
                <div class="card-action px-4">
                    <button class="btn btn-primary" type="submit">Submit Profile</button>
                </div><br>
                </form>
            </div>
        </div>    
    </div>
</div><br>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/default/footer.php"); ?>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/default/livechatbtn.php"); ?>
</body>
</html>