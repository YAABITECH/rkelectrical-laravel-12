<?php
// error_reporting(0);
define('myprivateaccess', TRUE);
require_once($_SERVER['DOCUMENT_ROOT']."/user/session.php");
$myuser_id=$_COOKIE['id'];
$course=$test="";
    $profoutput='<tr><th >NAME</th><td class=" pt-3" style="word-wrap: break-word;"><span class="fa fa-user-circle pr-2"></span>&nbsp;'.$rowsess['name'].'</td></tr>
    <tr><th >EMAIL</th><td class=" pt-3" style="word-wrap: break-word;"><span class="fa fa-envelope pr-2"></span>&nbsp;'.$rowsess['email'].'</td></tr>
    <tr><th >PHONE</th><td class=" pt-3" style="word-wrap: break-word;"><span class="fa fa-phone pr-2"></span>&nbsp;'.$rowsess['phone'].'</td></tr>';
$coursepaid="SELECT `course_id` FROM `course_paid` WHERE `user_id`='$myuser_id'"; 
$coursepaidres=$conn->query($coursepaid);
while($crspaidrow = $coursepaidres->fetch_assoc())
{
    $course_id=$crspaidrow['course_id'];
    $coursesql="SELECT * FROM `course` WHERE `id`='$course_id'";
    $courseres=$conn->query($coursesql);
    while($crsrow = $courseres->fetch_assoc())
    {
        $course.='<a href="/course/view?uname='.$crsrow['url'].'" class="list-group-item list-group-item-action" href="#list-item-1">'.$crsrow['title'].'</a>';
    }
}
$testpaid="SELECT `test_series` FROM `test_paid` WHERE `user_id`='$myuser_id'";
$testpaidres=$conn->query($testpaid);
while($testpaidrow = $testpaidres->fetch_assoc())
{
    $test_series_id=$testpaidrow['test_series'];
    $testsql="SELECT * FROM `test_series` WHERE `id`='$test_series_id'";
    $testres=$conn->query($testsql);
    while($testrow = $testres->fetch_assoc())
    {
        $test.='<a href="/test-series/test?id='.$test_series_id.'" class="list-group-item list-group-item-action" href="#list-item-1">'.$testrow['name'].'</a>';
    }
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
<div class="container">
    <div class="row">
        <div class="col-12 col-md-1"></div>
        <div class="col-12 col-md-10">
            <div class="row text-center bg-light p-3 m-1 mb-4 shadow">
                <div class="col-3 col-md-3">
                    <a href="/course/" class=" text-decoration-none">Paid Course</a>
                </div>
                <div class="col-3 col-md-3">
                    <a href="/test-series/" class=" text-decoration-none">Paid Test</a>
                </div>
                <div class="col-3 col-md-3">
                    <a href="/practice/" class=" text-decoration-none">Practice</a>
                </div>
                <div class="col-3 col-md-3">
                    <a href="/test-series/" class=" text-decoration-none">Free Test</a>
                </div>
            </div>
            <div class="card shadow rounded mt-5">
                <div class="card-body bg-primary rounded px-2">
                    <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <p class="text-light h5">&emsp;<span class="fa fa-user pr-2"></span> &nbsp;Profile</p>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <a href="/user/edit-profile" class="btn btn-light text-primary font-weight-bold">Edit Profile</a>
                        <a href="/user/student-register" class="btn btn-light text-primary font-weight-bold">Edit Advanced</a>
                        <a href="/user/change-password" class="btn btn-light text-primary font-weight-bold">Edit password</a>
                    </div>
                    </div>
                </div>
                <div class="card-title px-2">  
                    <table class="table">
                        <?php echo $profoutput; ?>  
                    </table> 
                    <p class="text-center"><a href="/user/logout" class="btn btn-primary">Logout</a></p>    
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-12 col-md-4">
                    <button class="btn btn-outline-info mt-3 shadow-sm" type="button" data-toggle="collapse" data-target="#buyedcourse" aria-expanded="false" aria-controls="collapseExample" style="width:100%">Purchased Course</button>
                    <div class="collapse" id="buyedcourse">
                        <div id="list-example" class="list-group">
                        <?php echo $course; ?>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <button class="btn btn-outline-info mt-3 shadow-sm" type="button" data-toggle="collapse" data-target="#buyedtest" aria-expanded="false" aria-controls="collapseExample" style="width:100%">Purchased Test</button>
                    <div class="collapse" id="buyedtest">
                        <div id="list-example" class="list-group">
                            <?php echo $test; ?>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div><a class="btn btn-outline-info mt-3 shadow-sm" href="/purchase/" style="width:100%">Buy Course &amp; Test</a></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-1"></div>
    </div>
    <br>
</div><br>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/default/footer.php"); ?>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/default/livechatbtn.php"); ?>
</body>
</html>