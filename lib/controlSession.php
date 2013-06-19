<?php 
$origen = $_GET['origen'];
if ($origen == "ospim") {
	include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionOspim.php");
	$bgcolor="#CCCCCC";
} else {
	include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionUsimra.php");
	$bgcolor="#B2A274";
}
?>
