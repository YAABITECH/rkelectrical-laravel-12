<?php
session_start();
// error_reporting(0);
define('myprivateaccess', TRUE);
require_once($_SERVER['DOCUMENT_ROOT']."/user/session.php");
$user_id=$_COOKIE['id'];
$testsrs_name=$test="";
$tstpaid=0;
if(!empty($_GET['id']))
{
    $testsrs_id=$_GET['id'];
}
$paidcheck="SELECT * FROM `test_paid` WHERE `user_id`='$user_id' AND `test_series`='$testsrs_id'"; 
$paidcheckres=$conn->query($paidcheck);
if($paidcheckres->num_rows>0)
{
    $tstpaid=1;
}
$sql="SELECT * FROM `test_series` WHERE `id`='$testsrs_id'";
$res=$conn->query($sql);
while($row = $res->fetch_assoc())
{
    $testsrs_name=$row['name'];   
    $testsrs_subname=$row['subname']; 
}
$ssql="SELECT * FROM `test` WHERE `test_series`='$testsrs_id'";
$sres=$conn->query($ssql);
while($srow = $sres->fetch_assoc())
{
    $testdisab=' disabled';
    $startdate=$srow["startdate"];
    $starttime=$srow["starttime"];
    $sdatetime=strtotime($startdate." ".$starttime);
    $fees=$srow["fees"];
    $test_id=$srow["id"];
    $datediff=$hoursdiff=0;
    $ctdifftm="";
    if(!empty($startdate))
    {
        $curr_date=date('Y-m-d h:i:s');
        $sdatetime=date('Y-m-d h:i:s', $sdatetime);
        $tdiff=date_diff(date_create($curr_date),date_create($sdatetime));
        if($tdiff->format("%R")=="+")
        {
            $datediff = $tdiff->format("%a");
            $hoursdiff = $tdiff->format("%h");
            $startdate=date_create($startdate);
            $startdate=date_format($startdate,"d-m-Y");
            $ctdifftm='<div class="row">
            <div class="col-sm-7">
                <p class="text-info">Activation date : '.$startdate.'</p>   
            </div>
            <div class="col-sm-5 text-right">   
                <span class="bg-light border border-info p-2">'.$datediff.'</span>
                <span class="p-1">Days</span>
                <span class="bg-light border border-info p-2">'.$hoursdiff.'</span>
                <span class="p-1">Hours</span>
            </div>
            </div>';
        }
    }
    if(($fees==0 || $tstpaid==1) && $ctdifftm=="")
    {
        $testdisab='';
    }
    $demobadge='';
    if($fees==0) {$demobadge=' <span class="badge badge-danger">Demo</span>';}
    // $reportshow='<a href="/test-series/online-test?id='.$srow["id"].'" class="btn btn-outline-success float-right m-1'.$testdisab.'" target="_blank">Start Test</a>';
    $reportshow='<a href="/test-series/instructions?id='.$srow["id"].'" class="btn btn-outline-success float-right m-1'.$testdisab.'" target="_blank">Start Test</a>';
    $tsql="SELECT * FROM `report` WHERE `test_id`='$test_id' AND `user_id`='$user_id'";
    $tres=$conn->query($tsql);
    if($tres->num_rows>0)
    {
        $reportshow='<a href="/test-series/report?test_id='.$srow["id"].'" class="btn btn-outline-warning float-right m-1">Report</a>';
    }
    $test.='<div class="card mb-3">
    <div class="card-body">
        '.$ctdifftm.'
        <h3 class="h6 text-success">'.$srow["name"].'</h3>
        <small class="text-muted">'.$srow["subname"].'</small>'.$demobadge.'
    </div>
    <div class="bg-light p-2">
        <span class="fa fa-question-circle-o p-2"> Total question '.$srow["count"].'</span>
        <span class="fa fa-plus p-2"> Total mark '.$srow["marks"].'</span>
        <span class="fa fa-hourglass-half p-2"> Duration '.$srow["duration"].'</span>
        '.$reportshow.'     
    </div>
    </div>';
}
$metatitle="RKElectrical Grid - Learning center";
$metadescription="Team of RKELECTRICAL GRID  for GATE /BARC/TRB/TNEB/ISRO & ESE Lectures";
$metarobots="index,follow";
$metacanonical="/";
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<?php include_once($_SERVER['DOCUMENT_ROOT']."/default/header.php"); ?>
<body>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/default/menu.php"); ?>
<div class="container">
<br>
    <h1 class="h4">TAKE A TEST</h1>
    <p><button class="btn btn-danger"><?php echo $testsrs_name;?></button>&nbsp;&nbsp;<a href="/test-series/"><button class="btn btn-outline-info">View Packages</button></a></p>
    <?php echo $test;?>
</div>
<br><br>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/default/footer.php"); ?>
</body>
</html>