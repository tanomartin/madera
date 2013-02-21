<?php 
$origen = $_GET['origen'];
if ($origen == "ospim") {
	include($_SERVER['DOCUMENT_ROOT']."/ospim/lib/controlSession.php");
	$bgcolor="#CCCCCC";
} else {
	include($_SERVER['DOCUMENT_ROOT']."/usimra/lib/controlSession.php");
	$bgcolor="#B2A274";
}
?>
