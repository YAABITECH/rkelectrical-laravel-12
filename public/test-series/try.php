<?php
session_start();
//error_reporting(0);
define('myprivateaccess', TRUE);
require_once($_SERVER['DOCUMENT_ROOT']."/default/conn.php");
$sql="ALTER TABLE `report` ADD `m_total` DECIMAL NULL DEFAULT NULL AFTER `m_negmk`";
if($conn->query($sql)===true)
{
  echo "success";
}else
{
  echo "failed";
}
$metatitle="";
$metadescription="";
$metarobots="noindex,follow";
?>