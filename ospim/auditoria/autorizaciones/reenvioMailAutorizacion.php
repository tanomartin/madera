<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."bandejaSalida.php");

$idMail = $_GET['idmail'];
$nroSolicitud = $_GET['nrosolicitud'];
try {
	$idReenvio = reenviarEmail($idMail);
	if ($idReenvio == -1) {
		throw new PDOException('Error al intentar reenviar el correo electronico');
	}
	$pagina = "consultaAutorizacion.php?nroSolicitud=$nroSolicitud";
	Header("Location: $pagina"); 
} catch (PDOException $e) {
	echo $e->getMessage();
}

?>