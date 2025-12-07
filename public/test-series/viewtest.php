<?php
session_start();
error_reporting(0);
define('myprivateaccess', TRUE);
require_once($_SERVER['DOCUMENT_ROOT']."/default/conn.php");
if(empty($_SESSION['id']) || empty($_SESSION['email']))
{
	header("Location: /user/login.php");
	exit();
}
$exam=$error=$ans="";
$i=1;
$x="none";
$id = $_SESSION["id"];
$sqlsess = "SELECT * FROM `users` WHERE `id`='$id'";
$sessresult = $conn->query($sqlsess);
if ($sessresult->num_rows == 1)
{
	$rowsess = $sessresult->fetch_assoc();
}
$payment=$rowsess['payment'];
if($payment!="success")
{
if(!empty($_GET['testname']))
{
    $x="block";
    $testname=rawurldecode($_GET['testname']);
    $exam.='<p class="centertext">'.$testname.'</p>';
    $asql="SELECT * FROM `exams` WHERE `testname`='$testname'";
    $ares=$conn->query($asql);
          while($row = $ares->fetch_assoc())
          {
            $quesnum=$row['quesnum'];
            $ques=$row['ques'];
            $opt1=$row['opt1'];
            $opt2=$row['opt2'];
            $opt3=$row['opt3'];
            $opt4=$row['opt4'];
            $answer=$row['ans'];
            $exam.='<p>'.$quesnum.'.&emsp;'.$ques.'</p>
            <input type="radio" name="ans_'.$i.'" value="a">&emsp;'.$opt1.'<br>
            <input type="radio" name="ans_'.$i.'" value="b">&emsp;'.$opt2.'<br>
            <input type="radio" name="ans_'.$i.'" value="c">&emsp;'.$opt3.'<br>
            <input type="radio" name="ans_'.$i.'" value="d">&emsp;'.$opt4.'<br><br>';
            $i++;
          }
}
}
else
{
    $exam.='<p class="centertext">Oops..! you are not paid</p>';
}
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $mark=0;
    for($i=1;$i<=5;$i++)
    {
        $ans=$_POST["ans_".$i];
        $asql="SELECT * FROM `exams` WHERE `testname`='$testname' AND `quesnum`='$i'";
        $ares=$conn->query($asql);
              while($row = $ares->fetch_assoc())
              {
                  if($ans==$row['ans'])
                  {
                      $mark++;
                  }
              }
    }
    $error=$mark;
    $name=$rowsess['name'];
    $uid=$rowsess['id'];
    $mysql="SELECT * FROM `marklist` WHERE `user_id`='$uid' AND `testname`='$testname'";
    $myres=$conn->query($mysql);
    if ($myres->num_rows>= 1)
    {
        $row = $myres->fetch_assoc();
        $retest=$row['retest'];
        $retest=$retest+1;
        $updat="UPDATE `marklist` SET `mark`='$mark',`retest`='$retest' WHERE `user_id`='$uid' AND `name`='$name'";
        if($conn->query($updat)===true)
        {
        header("Location: /online-exam/result.php");
        }
    }
    else
    {
        $ssql="INSERT INTO `marklist` (`name`,`testname`,`mark`,`user_id`) VALUES('$name','$testname','$mark','$uid')";
        if($conn->query($ssql)===true)
        {
        header("Location: /online-exam/result.php");
        }
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
<body onload="timedCount()">
<?php include_once($_SERVER['DOCUMENT_ROOT']."/default/menu.php"); ?>
<br><br>
<div class="container">
<p class="centertext" id="timer"></p>
    <div id="card">
        <div  style="margin:10px;padding:10px;">
            <form method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>" id="myForm">
                <p id="error"><?php echo $error;?></p>

                <?php echo $exam;?>
                <p class="centertext"><button type="submit" class="btn" id="check1" style="display:<?php echo $x;?>;">Submit</button></p>
            </form><br>
        </div>
    </div>
</div>

<br><br>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/default/footer.php"); ?>

<script>
var myVar = setInterval(timedCount, 1000);
var timeup= setTimeout(myFunction,120000);
function timedCount() {
  var d = new Date();
  document.getElementById("timer").innerHTML = d.toLocaleTimeString();

}
function myFunction() {
    document.forms["myForm"].submit();
}
</script>

</body>
</html>