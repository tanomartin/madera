<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
$id=$_GET['id'];

$sqlOrdenDoc = "SELECT * FROM ordenesconsultadoc WHERE id = $id";
$resOrdenDoc = mysql_query($sqlOrdenDoc,$db);
$rowOrdenDoc = mysql_fetch_array($resOrdenDoc);

$tipo = "application/pdf";

Header("Content-type: $tipo");
echo $rowOrdenDoc['historiaclinica'];  ?>