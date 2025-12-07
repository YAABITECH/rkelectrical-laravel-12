<?php
session_start();
error_reporting(0);
define('myprivateaccess', TRUE);
$error=$output=$allpagebtns=$checked=$option=$solution=$n_answer="";
require_once($_SERVER['DOCUMENT_ROOT']."/user/session.php");
$user_id=$_SESSION['id'];

if(!empty($_GET['id']))
{
    $test_id = $_GET['id'];        
    $selsql="SELECT * FROM `test` WHERE `id`='$test_id'";
    $selres=$conn->query($selsql);
    if($selres->num_rows==1)
    {
        while($selrow = $selres->fetch_assoc())
        {
            $fees=$selrow['fees'];
            $test_series=$selrow['test_series'];
            $paidcheck="SELECT * FROM `test_paid` WHERE `user_id`='$user_id' AND `test_series`='$test_series'"; 
            $paidcheckres=$conn->query($paidcheck);
            if($paidcheckres->num_rows>0)
            {
                $tstpaid=1;
            }
            if($fees==0 || $tstpaid==1)
            {
                $name=$selrow['name'];
                $subname=$selrow['subname'];
                $marks=$selrow['marks'];
                $count=$selrow['count'];
                $startdate=$selrow['startdate'];
                $enddate=$selrow['enddate'];
                    $ssql="SELECT * FROM `test_ques` WHERE `test_id`='$test_id'";
                    $sres=$conn->query($ssql);
                    if($sres->num_rows>0)
                    {
                        while($srow = $sres->fetch_assoc())
                        {
                            $quesimgshow="";
                            $ansimgshow="";
                            $questype=$srow["questype"];
                            $solution=$srow["solution"];
                            $num=$srow["num"];
                            if($questype=="number")
                            {
                                if(!empty($srow["answer2"]))
                                {
                                    $n_answer='<p>From : '.$srow["answer1"].' &nbsp; To : '.$srow["answer2"].'</p>';
                                }
                                else
                                {
                                    $n_answer='<p>'.$srow["answer1"].'</p>';
                                }
                                $outquestype='<div class="form-group">
                                <label for="ansshow_'.$srow["num"].'" class="text-info">Answer</label>
                                '.$n_answer.'<br><br>
                                <p class="text-info font-weight-bold">Solution:</p>
                                '.$solution.'
                            </div>';
                            } else
                            {
                                if($questype=="choice")
                                {
                                    $answer=$srow["answer"];
                                    switch($answer)
                                    {
                                        case 1;
                                            $option="a. ".$srow['ans1'];
                                            break;
                                        case 2;
                                            $option="b. ".$srow['ans2'];
                                            break;
                                        case 3;
                                            $option="c. ".$srow['ans3'];
                                            break;
                                        case 4;
                                            $option="d. ".$srow['ans4'];
                                            break;
                                    }
                                } else
                                {
                                    $option='';
                                    for($xk=0; $xk<4; $xk++)
                                    {
                                        if($srow['mans'.$xk]==1)
                                        {
                                            switch($xk)
                                            {
                                                case 1;
                                                    $option.="a. ".$srow['ans1']."<br>";
                                                    break;
                                                case 2;
                                                    $option.="b. ".$srow['ans2']."<br>";
                                                    break;
                                                case 3;
                                                    $option.="c. ".$srow['ans3']."<br>";
                                                    break;
                                                case 4;
                                                    $option.="d. ".$srow['ans4']."<br>";
                                                    break;
                                            }
                                        }
                                    }
                                }
                                $outquestype='<ol type="a" style="line-height:2rem">
                                <li>'.$srow["ans1"].'</label></li>
                                <li>'.$srow["ans2"].'</label></li>
                                <li>'.$srow["ans3"].'</label></li>
                                <li>'.$srow["ans4"].'</label></li>
                                </ol>
                                <p class="text-success font-weight-bold">Answer:</p>
                                '.$option.'<br><br>
                                <p class="text-info font-weight-bold">Solution:</p>
                                '.$solution.'
                                <br><br>';
                            }
                            if($srow["quesimage"]!="")
                            {
                                $quesimgshow='<img src="/admin/test/images/'.$srow["quesimage"].'" style="width:300px;">';
                            }
                            if($srow["ansimage"]!="")
                            {
                                $ansimgshow='<img src="/admin/test/images/'.$srow["ansimage"].'" style="width:300px;">';
                            }
                            $output.='<div id="quesid_'.$srow["num"].'" style="display:block">
                            <div class="card p-2 mb-2">
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="text-danger p-2">Question Type : '.ucfirst($srow["questype"]).'</div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="float-right p-2">Mark : 2 &nbsp;|&nbsp; Negative : <span class="text-danger">'.$srow["neg_mark"].'</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="h6">Question Number : '.$srow["num"].'</h3>
                                </div>
                                <div class="card-body">
                                    <p class="text-info font-weight-bold">'.$srow["question"].'</p>
                                    '.$quesimgshow.'
                                    <br>
                                    '.$outquestype.'
                                    '.$ansimgshow.'
                                </div>
                            </div>
                        </div>
                        <br>';
                            $allpagebtns.='<button id="qbtnid_'.$srow["num"].'" class="btn btn-sm btn-warning m-1" onclick="queschange('.$srow["num"].')">'.$srow["num"].'</button>';
                        }
                    }
            } else 
            {
                exit();
            }
        }
    }
    
} else {
    header("Location: /test-series/test");
	exit();
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
<div class="container"><br>
    <div class="row">
        <div class="col-sm-12">
            <div class="card mb-2">
                <h1 class="h6 text-light bg-info p-3 m-0"><?php echo $name;?></h1>
                <div class="bg-light p-2">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <h2 class="h6 text-secondary p-2"><?php echo $subname;?></h2>
                        </div>   
                    </div>
                </div>
            </div>
            <?php echo $output; ?>
            <br>
        </div>  
<br>
</div>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/default/footer-admin.php"); ?>
</body>
</html>