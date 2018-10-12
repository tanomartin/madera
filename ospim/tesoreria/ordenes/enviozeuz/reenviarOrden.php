<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."bandejaSalida.php");

$idMail = $_GET['idmail'];
$nroorden = $_GET['nroorden'];
$idReenvio = reenviarEmail($idMail);
$pagina = "buscarOrdenEnviadas.php?nroorden=$nroorden";
Header("Location: $pagina"); 

?>