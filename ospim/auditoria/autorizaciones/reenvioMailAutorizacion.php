<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."bandejaSalida.php");

$idMail = $_GET['idmail'];
$nroSolicitud = $_GET['nrosolicitud'];
$idReenvio = reenviarEmail($idMail);
$pagina = "consultaAutorizacion.php?nroSolicitud=$nroSolicitud";
Header("Location: $pagina"); 
?>