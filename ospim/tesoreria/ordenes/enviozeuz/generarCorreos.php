<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."bandejaSalida.php");

$maquina = $_SERVER['SERVER_NAME'];
if(strcmp("localhost",$maquina)==0) {
	$carpetaOrden = $nombrearchivo = $_SERVER['DOCUMENT_ROOT']."/OrdenesPagoPDF/";
} else {
	$carpetaOrden = "/home/sistemas/Documentos/Repositorio/OrdenesPagoPDF/";
}

foreach($_POST as $datos) {
	$arrayDatos = explode("|",$datos);
	$codigo = trim($arrayDatos[0]);
	$email = trim($arrayDatos[1]);
	$nroorden = trim($arrayDatos[2]);
	$nombrePDF = $carpetaOrden."OP".$nroorden."O.pdf";

	$subject = "Orden de Pago OSPIM - Nro $nroorden - Cod $codigo";
	$bodymail="<body><br><br>Este es un mensaje de Aviso de ORDEN DE PAGO Nº $nroorden Adjunto la orden en pdf<br><br>";
	$username ="tesoreria@ospim.com.ar";
	$modulo = "Ordenes de Pago";
	$arrayAttachment[0] = $nombrePDF;
	guardarEmail($username, $subject, $bodymail, $email, $modulo, $arrayAttachment);
	
	//delete de archivos de datos

	
	$redire = "Location: buscarOrdenEnviadas.php?fecha=".date("Y-m-d");
	header($redire);
}
?>