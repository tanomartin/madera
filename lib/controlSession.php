<?php 
$origen = $_GET['origen'];
$libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
if ($origen == "ospim") {
	include($libPath."controlSessionOspim.php");
	$bgcolor="#CCCCCC";
} else {
	include($libPath."controlSessionUsimra.php");
	$bgcolor="#B2A274";
}
?>
