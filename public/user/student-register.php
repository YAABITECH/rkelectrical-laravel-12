<?php
session_start();
error_reporting(0);
define('myprivateaccess', TRUE);
include_once($_SERVER['DOCUMENT_ROOT']."/user/session.php");
$sel1=$sel2="";
$dob=$rowsess['dob'];
$gender=$rowsess['gender'];
if($gender=="male")
{
    $sel1=" checked";
} else if($gender=="female")
{
    $sel2=" checked";
}
$clgname=$rowsess['clgname'];
$year=$rowsess['year'];
$degree=$rowsess['degree'];
$department=$rowsess['department'];
if(!empty($_GET['key']))
{
$key=valid_data($_GET['key'],$conn);
}
function valid_data($data,$conn)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  $data = $conn->real_escape_string($data);
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
        <div class="col-md-3">
        </div>
        <div class="col-md-6">
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
            <div id="card" class="p-3" style="background-color:rgb(255, 255, 255,0.8);"><br>
                <p class="centertext h5 text-primary">Student Details</p>  
                <div class="form-group mb-3 ">
                    <label for="degree" class="text-primary">DOB:</label>
                    <input type="date" class="form-control mt-2" id="dob" placeholder="Enter dob" name="dob" required value="<?php echo $dob;?>">
                </div>
                <div class="form-group mb-3">
                    <label for="degree" class="text-primary">Gender:</label>
                    <div class="radio mt-2">
                        <label><input type="radio" name="gendclk" value="male" onclick="genderch('male')" <?php echo $sel1;?>> Male </label>
                        <label><input type="radio" name="gendclk" value="female" onclick="genderch('female')" <?php echo $sel2;?>> Female</label>
                    </div>
                    <input type="hidden" id="gender" name="gender" value="male" value="<?php echo $gender?>">
                </div>
                <div class="form-group mb-3">
                    <label for="clgname" class="text-primary">College Name:</label>
                    <input type="text" class="form-control mt-2" placeholder="Enter college Name" id="clgname" name="clgname" required  value="<?php echo $clgname;?>">
                </div>
                <div class="form-group mb-3">
                    <label for="year" class="text-primary">Year of Complete:</label>
                    <input type="text" class="form-control mt-2" id="year" placeholder="Enter year of complete" name="year" required value="<?php echo $year;?>">
                </div>
                <div class="form-group mb-3">
                    <label for="degree" class="text-primary">Degree:</label>
                    <input type="text" class="form-control mt-2" placeholder="Enter degree" id="degree" name="degree" required value="<?php echo $degree;?>">
                </div>
                <div class="form-group mb-3">
                    <label for="Department" class="text-primary">Department:</label>
                    <input type="text" class="form-control mt-2" placeholder="Enter department" id="department" name="department" required value="<?php echo $department;?>">
                </div>    
                <p class="centertext"><button type="submit" class="btn btn-primary" onclick="stdsubmit();">Submit</button></p>
            </div>
        </div>
        <div class="col-md-3">
        </div>
    </div>
    <input type="hidden" id="redirect" value="<?php echo $key; ?>">
</div><br>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/default/footer.php"); ?>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/default/livechatbtn.php"); ?>
<script>
function genderch(val)
{
    document.getElementById("gender").value=val;
}
function stdsubmit()
{
    var key=document.getElementById("redirect").value;
    var dob=document.getElementById("dob").value;
    var gender=document.getElementById("gender").value;
    var clgname=document.getElementById("clgname").value;
    var year=document.getElementById("year").value;
    var degree=document.getElementById("degree").value;
    var department=document.getElementById("department").value;
	var formData = new FormData(); 
    formData.append("dob", dob);
    formData.append("gender", gender);
    formData.append("clgname", clgname);
    formData.append("year", year);
    formData.append("degree", degree);
    formData.append("department", department);
	var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200)
	  {
        res = this.responseText;
		if(res=="ok")
		{
            alert("Submitted Successfull");
		}
		else
		{
			alert(res);
		}
      }
    };
    xmlhttp.open("POST", "/dynamic/stddetails", true);
    xmlhttp.send(formData);
}
</script>
</body>
</html>