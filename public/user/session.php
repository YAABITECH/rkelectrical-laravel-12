<?php
require_once($_SERVER['DOCUMENT_ROOT']."/default/conn.php");
$redirect=rawurlencode($_SERVER['REQUEST_URI']);
if(empty($_COOKIE['id']) || empty($_COOKIE['salt']))
{
	header("Location: /user/logout?redirect=$redirect");
	exit();
}
$id = $conn->real_escape_string(stripslashes(trim($_COOKIE['id'])));
$salt = $conn->real_escape_string(stripslashes(trim($_COOKIE['salt'])));
$sqlsess = "SELECT * FROM `users` WHERE `id`='$id' AND `salt`='$salt'";
$sessresult = $conn->query($sqlsess);
if ($sessresult->num_rows == 1)
{
	$rowsess = $sessresult->fetch_assoc();
} else
{
	header("Location: /user/logout?redirect=$redirect");
	exit();
}
?>
