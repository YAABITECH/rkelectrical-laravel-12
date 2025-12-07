<?php
session_start();
$startTime = $_SESSION['t_starttime'];
$duration = $_SESSION['t_duration'];
$timediff = ($duration*60)-(time()-$startTime);
echo $timediff;
//echo (floor($timediff/60)).":".(floor($timediff%60));
?>
