<?php
session_start();
error_reporting(0);
define('myprivateaccess', TRUE);
require_once($_SERVER['DOCUMENT_ROOT']."/default/conn.php");
$result="";
$uid = $_SESSION["id"];
$result.='<tr>';
$mysql="SELECT * FROM `marklist` WHERE `user_id`='$uid'";
$myres=$conn->query($mysql);
while ($row = $myres->fetch_assoc())
{
  $testname=$row['testname'];
  $mark=$row['mark'];
  $result.='
  <td>'.$row['testname'].'</td>
  <td>'.$row['mark'].'</td>
  <td>Show answer</td>';
}   
$result.='</tr>';
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
<br><br>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Testname</th>
        <th>Mark</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php echo $result;?>
    </tbody>
  </table>
  <br><br>
</div>
<?php include_once($_SERVER['DOCUMENT_ROOT']."/default/footer.php"); ?>
</body>
</html>