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
	$nrointer = trim($arrayDatos[3]);
	$nombrePDF = $carpetaOrden."NI".$nrointer."-OP".$nroorden."-O.pdf";

	$codigo = preg_replace('/^0+/', '', $codigo);
	$nroorden = preg_replace('/^0+/', '', $nroorden);
	$nrointer = preg_replace('/^0+/', '', $nrointer);
	
	$subject = "Orden de Pago OSPIM - Nro $nroorden - Cod $codigo - Nro Int $nrointer";
	$bodymail="<body>
					Sr. Prestador de O.S.P.I.M.<br><br>
			   		Recientemente se le ha efectuado una transferencia bancaria a vuestro C.B.U.<br>
					Los datos de la transferencia figuran en la \"Orden de Pago\" adjunta.<br>
					Solicitamos imprimir el adjunto firmarlo, sellarlo y <b>enviarlo</b> junto a vuestro recibo oficial a:<br><br>
					O.S.P.I.M.<br>
					Rojas 254 - 1405 C.A.B.A.<br><br>
					La recepción de la órden de pago y de vuestro recibo son requisitos necesarios para agilizar futuros pagos.<br>
					Agradecemos vuestra atención<br><br>
					<b>TESORERIA<br>
					O.S.P.I.M.</b><br><br>
				</body>";
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