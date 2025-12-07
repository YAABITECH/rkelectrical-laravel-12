<?php
date_default_timezone_set('Asia/Kolkata');
session_start();
if (empty($_SESSION['t_starttime'])) {
    $_SESSION['t_starttime'] = time();
    $_SESSION['t_closed'] = "NO";
}
//error_reporting(0);
define('myprivateaccess', true);
$error = $output = $allpagebtns = "";
require_once $_SERVER['DOCUMENT_ROOT'] . "/user/session.php";
$user_id = $_COOKIE['id'];
$user_name = $rowsess['name'];
if (!empty($_GET['id'])) {
    $test_id = $_GET['id'];
    $tsql = "SELECT * FROM `report` WHERE `test_id`='$test_id' AND `user_id`='$user_id'";
    $tres = $conn->query($tsql);
    if ($tres->num_rows > 0) {
        echo 'You cannot enter into the page directly';
        exit();
    }
    $selsql = "SELECT * FROM `test` WHERE `id`='$test_id'";
    $selres = $conn->query($selsql);
    if ($selres->num_rows == 1) {
        while ($selrow = $selres->fetch_assoc()) {
            $fees = $selrow['fees'];
            $test_series = $selrow['test_series'];
            $paidcheck = "SELECT * FROM `test_paid` WHERE `user_id`='$user_id' AND `test_series`='$test_series'";
            $paidcheckres = $conn->query($paidcheck);
            if ($paidcheckres->num_rows > 0) {
                $tstpaid = 1;
            }
            if ($fees == 0 || $tstpaid == 1) {
                $name = $selrow['name'];
                $subname = $selrow['subname'];
                $marks = $selrow['marks'];
                $count = $selrow['count'];
                $duration = $selrow['duration'];
                $_SESSION['t_duration'] = $duration;
                $startdate = $selrow['startdate'];
                $starttime = $selrow["starttime"];
                $sdatetime = strtotime($startdate . " " . $starttime);
                $enddate = $selrow['enddate'];
                $ctdifftm = "";
                if (!empty($startdate)) {
                    $curr_date = date('Y-m-d h:i:s');
                    $sdatetime = date('Y-m-d h:i:s', $sdatetime);
                    $tdiff = date_diff(date_create($curr_date), date_create($sdatetime));
                    if ($tdiff->format("%R") == "+") {
                        $datediff = $tdiff->format("%a");
                        $hoursdiff = $tdiff->format("%h");
                        $startdate = date_create($startdate);
                        $startdate = date_format($startdate, "d-m-Y");
                        $ctdifftm = '<br><div class="row">
                        <div class="col-sm-7">
                            <p class="text-info">Activation date : ' . $startdate . '</p>
                        </div>
                        <div class="col-sm-5 text-right">
                            <span class="bg-light border border-info p-2">' . $datediff . '</span>
                            <span class="p-1">Days</span>
                            <span class="bg-light border border-info p-2">' . $hoursdiff . '</span>
                            <span class="p-1">Hours</span>
                        </div>
                        </div>';
                    }
                }
                if ($ctdifftm != "") {
                    $output = $ctdifftm;
                } else {
                    $ssql = "SELECT * FROM `test_ques` WHERE `test_id`='$test_id'";
                    $sres = $conn->query($ssql);
                    if ($sres->num_rows > 0) {
                        while ($srow = $sres->fetch_assoc()) {
                            $quesimgshow = "";
                            $questype = $srow["questype"];
                            $backnum = 1;
                            $nextnum = $count;
                            $shnext = "SUBMIT TEST";
                            $num = $srow["num"];
                            if ($srow["num"] > 1) {$backnum = $srow["num"] - 1;}
                            if ($srow["num"] < $count) {
                                $nextnum = $srow["num"] + 1;
                                $shnext = '<button onclick="queschange(' . $nextnum . ')" class="btn btn-success m-1 float-right">SAVE &amp; NEXT</button>';
                            } else {
                                $shnext = '<input form="mainform" type="submit" class="btn btn-success m-1 float-right" name="subinput" value="SUBMIT TEST">';
                            }
                            if ($questype == "number")
                            {
                                $outquestype = '<div class="form-group">
                                <label for="ansshow_' . $srow["num"] . '" class="text-info">Answer</label>
                                <input form="mainform" type="text" class="form-control" id="answer_' . $srow["num"] . '" name="answer_' . $srow["num"] . '" placeholder="Type Your Answer">
                            </div>';
                            } else
                            {
                                $outquestype = '<ol type="a" style="line-height:2rem">
                                <li>' . $srow["ans1"] . '</label></li>
                                <li>' . $srow["ans2"] . '</label></li>
                                <li>' . $srow["ans3"] . '</label></li>
                                <li>' . $srow["ans4"] . '</label></li>
                                </ol>
                                ';
                                if ($questype == "choice") {
                                    $outquestype.='<p class="text-dark font-weight-bold">Answer:</p>
                                    <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="ansopt' . $srow["num"] . '" id="ansopt' . $srow["num"] . '_1" onclick="chanswer(' . $srow["num"] . ',1)">
                                    <label class="form-check-label" for="ansopt' . $srow["num"] . '_1">a</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="ansopt' . $srow["num"] . '" id="ansopt' . $srow["num"] . '_2" onclick="chanswer(' . $srow["num"] . ',2)">
                                    <label class="form-check-label" for="ansopt' . $srow["num"] . '_2">b</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="ansopt' . $srow["num"] . '" id="ansopt' . $srow["num"] . '_3" onclick="chanswer(' . $srow["num"] . ',3)">
                                    <label class="form-check-label" for="ansopt' . $srow["num"] . '_3">c</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="ansopt' . $srow["num"] . '" id="ansopt' . $srow["num"] . '_4" onclick="chanswer(' . $srow["num"] . ',4)">
                                    <label class="form-check-label" for="ansopt' . $srow["num"] . '_4">d</label>
                                    </div>
                                    <input form="mainform" type="hidden" id="answer_' . $srow["num"] . '" name="answer_' . $srow["num"] . '" value="">';
                                } else
                                {
                                    $outquestype.='<p class="text-dark font-weight-bold">Answer:</p>
                                    <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="ansopt' . $srow["num"] . '" id="ansopt' . $srow["num"] . '_1" onclick="multians(' . $srow["num"] . ',1)">
                                    <label class="form-check-label" for="ansopt' . $srow["num"] . '_1">a</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="ansopt' . $srow["num"] . '" id="ansopt' . $srow["num"] . '_2" onclick="multians(' . $srow["num"] . ',2)">
                                    <label class="form-check-label" for="ansopt' . $srow["num"] . '_2">b</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="ansopt' . $srow["num"] . '" id="ansopt' . $srow["num"] . '_3" onclick="multians(' . $srow["num"] . ',3)">
                                    <label class="form-check-label" for="ansopt' . $srow["num"] . '_3">c</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="ansopt' . $srow["num"] . '" id="ansopt' . $srow["num"] . '_4" onclick="multians(' . $srow["num"] . ',4)">
                                    <label class="form-check-label" for="ansopt' . $srow["num"] . '_4">d</label>
                                    </div>
                                    <input form="mainform" type="hidden" id="manswer_' . $srow["num"] . '_1" name="manswer_' . $srow["num"] . '_1" value="0">
                                    <input form="mainform" type="hidden" id="manswer_' . $srow["num"] . '_2" name="manswer_' . $srow["num"] . '_2" value="0">
                                    <input form="mainform" type="hidden" id="manswer_' . $srow["num"] . '_3" name="manswer_' . $srow["num"] . '_3" value="0">
                                    <input form="mainform" type="hidden" id="manswer_' . $srow["num"] . '_4" name="manswer_' . $srow["num"] . '_4" value="0">
                                    ';
                                }
                            } 
                            if ($srow["quesimage"] != "") {
                                $quesimgshow = '<img src="/admin/test/images/' . $srow["quesimage"] . '" style="width:300px;">';
                            }
                            $output .= '<div id="quesid_' . $srow["num"] . '" style="display:block">
                            <div class="card p-2 mb-2">
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="text-danger p-2">Question Type : ' . ucfirst($srow["questype"]) . '</div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="float-right p-2">Mark : 2 &nbsp;|&nbsp; Negative : <span class="text-danger">' . $srow["neg_mark"] . '</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="h6">Question Number : ' . $srow["num"] . '</h3>
                                </div>
                                <div class="card-body">
                                    <p class="text-info font-weight-bold">' . $srow["question"] . '</p>
                                    ' . $quesimgshow . '
                                    <br>
                                    ' . $outquestype . '
                                </div>
                            </div><br>
                            <button onclick="queschange(' . $backnum . ')" class="btn btn-outline-danger m-1">Back</button>
                            <button onclick="setreview(' . $srow["num"] . ')" class="btn btn-outline-success m-1">Make for Review &amp; Next</button>
                            <button onclick="clear(' . $srow["num"] . ')" class="btn btn-outline-success m-1">Clear</button>
                            ' . $shnext . '
                            <br>
                            </div>';
                            $allpagebtns .= '<button id="qbtnid_' . $srow["num"] . '" class="btn btn-sm btn-warning m-1" onclick="queschange(' . $srow["num"] . ')">' . $srow["num"] . '</button>
                            <input type="hidden" id="qstp_'.$srow["num"].'" value="'.$questype.'">';
                        }
                    }
                }
            } else {
                echo 'You cannot enter into the page directly';
                exit();
            }
        }
    } else {
        echo 'You cannot enter into the page directly';
        exit();
    }
} else {
    echo 'You cannot enter into the page directly';
    exit();
}
$metatitle = "RKElectrical Grid - Learning center";
$metadescription = "Team of RKELECTRICAL GRID  for GATE /BARC/TRB/TNEB/ISRO & ESE Lectures";
$metarobots = "index,follow";
$metacanonical = "/";
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/default/header.php";?>
<body onload="queschange(1)" class="bg-light">
<div class="container-fluid"><br>
    <div class="row">
        <div class="col-sm-9">
            <div class="card mb-2">
                <h1 class="h6 text-light bg-info p-3 m-0"><?php echo $name; ?></h1>
                <div class="bg-light p-2">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <h2 class="h6 text-secondary p-2"><?php echo $subname; ?></h2>
                        </div>
                        <div class="col-12 col-md-6">
                            <span class="float-right p-2">Time left = <span class="text-success" id="t_count"></span>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo $output; ?>
            <br>
        </div>
        <div class="col-sm-3">
            <p class="text-center"><span class="btn btn-info" onclick="calcshow();"><img src="/test-series/images/calculator.png" style="width:40px;"> Calculator</span></p>
            <div class="card shadow p-3 border border-dark" style="font-size:12px">
                <div class="mb-3 text-center">
                    <h6 class="text-danger p-2"><?php echo $user_name; ?></h6>
                </div>
                <div class="row">
                    <div class="col-6">
                        <p><button class="btn btn-sm btn-success m-1">N</button> Answered</p>
                        <p><button class="btn btn-sm btn-info m-1">N</button> Marked Review</p>
                    </div>
                    <div class="col-6">
                        <p><button class="btn btn-sm btn-danger m-1">N</button> Not Answered</p>
                        <p><button class="btn btn-sm btn-warning m-1">N</button> Not visited</p>
                    </div>
                </div>
                <br>
                <div class="bg-light p-2">
                    <h6 class="text-primary p-1">Choose a Question</h6>
                    <p style="line-height:2rem; text-align:justify">
                        <?php echo $allpagebtns; ?>
                    </p>
                </div>
            </div>
            <br>
            <div class="text-center"><input form="mainform" type="submit" class="btn btn-success w-100" name="submit" value="Submit &amp; Evaluate"></div><br>
        </div>
    </div>
<br>
<form name="mainform" id="mainform" method="POST" action="/test-series/test-result">
    <input type="hidden" name="test_id" id="test_id" value="<?php echo $test_id; ?>">
</form>
<input type="hidden" id="curr_ques" value="0">
<input type="hidden" id="forreview" value="">
</div>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . "/default/footer-admin.php";?>
<script>
function multians(qn,ans)
{
  if(document.getElementById("manswer_"+qn+"_"+ans).value==0)
  {
    document.getElementById("manswer_"+qn+"_"+ans).value=1;
  } else
  {
    document.getElementById("manswer_"+qn+"_"+ans).value=0;
  }
}
function queschange(q)
{
    var count=<?php echo $count; ?>;
    curr_ques=document.getElementById("curr_ques").value;
    curr_ques=Number(curr_ques);
    q=Number(q);
    currans='';
    if(q!=curr_ques && q<=count)
    {
        for(i=1; i<=<?php echo $count; ?>; i++)
        {
            document.getElementById("quesid_"+i).style.display="none";
        }
        document.getElementById("quesid_"+q).style.display="block";
        if(curr_ques>0)
        {
            qstp=document.getElementById("qstp_"+curr_ques).value;
            if(qstp=='choice' || qstp=='number')
            {
                if(document.getElementById("answer_"+curr_ques))
                {
                    currans = document.getElementById("answer_"+curr_ques).value;
                }
            } else
            {
                mans1='0';
                mans2='0';
                mans3='0';
                mans4='0';
                if(document.getElementById("manswer_"+curr_ques+"_1"))
                {
                    mans1 = document.getElementById("manswer_"+curr_ques+"_1").value;
                }
                if(document.getElementById("manswer_"+curr_ques+"_2"))
                {
                    mans2 = document.getElementById("manswer_"+curr_ques+"_2").value;
                }
                if(document.getElementById("manswer_"+curr_ques+"_3"))
                {
                    mans3 = document.getElementById("manswer_"+curr_ques+"_3").value;
                }
                if(document.getElementById("manswer_"+curr_ques+"_4"))
                {
                    mans4 = document.getElementById("manswer_"+curr_ques+"_4").value;
                }
                if(mans1!='0' || mans2!='0' || mans3!='0' || mans4!='0')
                {
                    currans='number'
                }
            }
        }
        document.getElementById("curr_ques").value=q;
        forreview=document.getElementById("forreview").value;
        document.getElementById("forreview").value="";
        if(forreview=="" && curr_ques>0)
        {
            if(currans=="")
            {
                document.getElementById("qbtnid_"+curr_ques).className="btn btn-sm btn-danger m-1";
            } else
            {
                document.getElementById("qbtnid_"+curr_ques).className="btn btn-sm btn-success m-1";
            }
        }
    }
}
</script>
<script>
function chanswer(num,val)
{
    document.getElementById("answer_"+num).value=val;
}
function setreview(num)
{
    document.getElementById("qbtnid_"+num).className="btn btn-sm btn-info m-1";
    document.getElementById("forreview").value="R";
    num=Number(num+1);
    queschange(num);
}
</script>
<script type="text/javascript">
var timer = setInterval(repeatMe,1000);
function repeatMe(){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            res = this.responseText;
            if(res<0){cnt=0+":"+00; document.getElementById("mainform").submit();}
            m=Math.floor(res/60);
            s=Math.floor(res%60);
            if(s<10) {s="0"+s}
            cnt=m+":"+s;
            document.getElementById("t_count").innerHTML=cnt;
        }
    };
    xmlhttp.open("GET", "dyntimecount", true);
    xmlhttp.send();
}
 </script>
<script type="text/javascript">
    function calcshow() 
    {
        url="/test-series/calcy";
        popupWindow = window.open(url,'Calculator','height=380,width=500,right=10,bottom=10,resizable=no,scrollbars=no,toolbar=no,menubar=no,location=no,directories=no,status=no');
    }
</script>
</body>
</html>