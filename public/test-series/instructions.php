<?php
if(!empty($_GET['id']))
{
    $id=$_GET['id'];
    echo "General instructions<br>";
    echo '<a href="/test-series/online-test?id='.$id.'" class="btn btn-outline-success float-right m-1" target="_blank">Start Test</a>';
}
?>