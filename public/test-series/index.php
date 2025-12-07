<?php
session_start();
error_reporting(0);
define('myprivateaccess', TRUE);
require_once($_SERVER['DOCUMENT_ROOT']."/default/conn.php");
$packages=$user_id=$loginalrt=$packagesfree="";
$j=0;
$purch_arr=[];
$redirect=rawurlencode($_SERVER['REQUEST_URI']);
$colors=array("primary","success","danger","info","primary","success","danger","info","primary","success","danger","info");
if(!empty($_COOKIE['id']) || !empty($_COOKIE['salt']))
{
    $user_id=$_COOKIE['id'];
} else
{
    $loginalrt=' onclick="alert(\'Please login first to make payment\'); window.location.href=\'/user/login?redirect='.$redirect.'\'; return false;"';
}
$sql="SELECT * FROM `test_series`";
$res=$conn->query($sql);
while($row = $res->fetch_assoc())
{
    $testsrs_id=$row['id'];
    $name=$row['name'];
    $subname=$row['subname'];
    $fees=$row['fees'];
    $checksql="SELECT * FROM `test_paid` WHERE `user_id`='$user_id' AND `test_series`='$testsrs_id'"; 
    $checkres=$conn->query($checksql);
    if($checkres->num_rows>0)
    {
        $packagespaid.='<div class="col-12 col-md-4">
            <div class="card shadow m-md-3">
            <div class="bg-light text-center pb-3"">
                <h2 class="h5 text-'.$colors[$j].' p-2 pt-3">'.$name.'</h2>
            </div>
            <div class="card-body text-center border border-bottom-0">
            <p class="text-secondary">'.$subname.'</p>
            <div class="border rounded-pill bg-light shadow-sm" style="font-size:1.2rem; font-weight:bold">&#8377;'.$fees.'</div>
            </div>
            <div class="p-4 text-center border bg-light border-top-0">
                <p><a href="/test-series/test?id='.$testsrs_id.'" class="btn btn-'.$colors[$j].' px-5 rounded">View Tests</a></p>
                <p><a href="/test-series/test?id='.$testsrs_id.'" class="btn btn-light text-'.$colors[$j].' px-5 rounded">View Demo</a></p>
            </div>
            </div>
            </div>';
    } else if($fees==0)
    {
        $packagesfree.='<div class="col-12 col-md-4">
        <div class="card shadow m-md-3">
        <div class="bg-light text-center pb-3">
            <h2 class="h5 text-'.$colors[$j].' p-2 pt-3 lineheight-2-5">'.$name.'</h2>
        </div>
        <div class="card-body text-center border border-bottom-0">
        <p class="text-dark">'.$subname.'</p>
        <div class="border rounded-pill bg-light shadow-sm" style="font-size:1.2rem; font-weight:bold">Free</div>
        </div>
        <div class="p-4 text-center border bg-light border-top-0">
            <p><a href="/test-series/test?id='.$testsrs_id.'" class="btn btn-'.$colors[$j].' px-5 rounded">View Tests</a></p>
            <p><a href="/test-series/test?id='.$testsrs_id.'" class="btn btn-light text-'.$colors[$j].' px-5 rounded">View Demo</a></p>
        </div>
        </div>
        </div>';
    } else
    {
        $packages.='<div class="col-12 col-md-4">
        <div class="card shadow m-md-3">
        <div class="bg-light text-center p-3">
            <h2 class="h5 text-'.$colors[$j].' pt-1">'.$name.'</h2>
            
        </div>
        <div class="card-body text-center border border-bottom-0">
        <p class="text-dark">'.$subname.'</p>
        <div class="border rounded-pill bg-light shadow-sm" style="font-size:1.2rem; font-weight:bold">&#8377;'.$fees.'</div>
        </div>
        <div class="p-4 text-center border bg-light border-top-0">
            <p><a href="/purchase/?test='.$testsrs_id.'" class="btn btn-'.$colors[$j].' px-5 rounded">Buy Now</a></p>
            <p><a href="/test-series/test?id='.$testsrs_id.'" class="btn btn-light text-'.$colors[$j].' px-5 rounded">View Demo</a></p>
        </div>
        </div>
        </div>';
    }
    $j++;
    if($j>6)
    {
        $j=0;
    }
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
    <h1 class="h4 text-info mb-3 text-center">Test Series</h1>
    <h2 class="h6 text-secondary mb-3 text-center">All Packages</h2>
    <?php if(empty($user_id)) {echo '<p class="text-success font-weight-bold text-center">Please login first to view your purchased packages.</p>';} ?>
    <div class="row">
        <?php echo $packagesfree.$packagespaid.$packages;?>
</div>    
</div>    
<br><br>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/default/footer.php"); ?>
</body>
</html>