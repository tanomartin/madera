<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."bandejaSalida.php");

function delete_archivos_txt($ruta){
	$arrayArchivos = array();
	$i = 0;
	if (is_dir($ruta)) {
		if ($dh = opendir($ruta)) {
			while (($file = readdir($dh)) !== false) {
				$pos = strpos($file, ".txt");
				if ($pos !== false) {
					unlink($ruta.$file);
				}
			}
			closedir($dh);
		}
	}
	return true;
}

$maquina = $_SERVER['SERVER_NAME'];
if(strcmp("localhost",$maquina)==0) {
	$carpetaOrden = $nombrearchivo = $_SERVER['DOCUMENT_ROOT']."/OrdenesPagoPDF/";
	$carpetaDatos = "../OrdenesPagoPDF/datos/";
} else {
	$carpetaOrden = "/home/sistemas/Documentos/Repositorio/OrdenesPagoPDF/";
	$carpetaDatos = "/home/sistemas/Documentos/Repositorio/OrdenesPagoPDF/datos/";
}

foreach($_POST as $datos) {
	$arrayDatos = explode("|",$datos);
	$codigo = trim($arrayDatos[0]);
	$email = trim($arrayDatos[1]);
	$nroorden = trim($arrayDatos[2]);
	$nombrePDF = $carpetaOrden."OP".$nroorden."O.pdf";

	$codigo = preg_replace('/^0+/', '', $codigo);
	$nroorden = preg_replace('/^0+/', '', $nroorden);
	
	$subject = "Orden de Pago OSPIM - Nro $nroorden - Cod $codigo";
	$bodymail="<body><br><br>Este es un mensaje de Aviso de ORDEN DE PAGO Nº $nroorden Adjunto la orden en pdf<br><br>";
	$username ="tesoreria@ospim.com.ar";
	$modulo = "Ordenes de Pago";
	$arrayAttachment[0] = $nombrePDF;
	guardarEmail($username, $subject, $bodymail, $email, $modulo, $arrayAttachment);
	
	//delete de archivos de datos
	delete_archivos_txt($carpetaDatos);
	$redire = "Location: buscarOrdenEnviadas.php?fecha=".date("Y-m-d");
	header($redire);
}
?>