<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php");

print("********* TRAMITE JUICIO *****************");

$datos = array_values($_POST);

var_dump($datos);

?>