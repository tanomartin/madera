<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
require ($libPath."numeros.php");
include($libPath."bandejaSalida.php");

$nroOrden = $_GET['nroorden'];
$email = $_GET['email'];
$sqlCabecera = "SELECT *, DATE_FORMAT(o.fechaorden, '%d-%m-%Y') as fechaorden, pr.descrip as provincia
				FROM ordencabecera o, prestadores p, provincia pr
				WHERE o.nroordenpago = $nroOrden and o.codigoprestador = p.codigoprestador and p.codprovin = pr.codprovin";
$resCabecera = mysql_query($sqlCabecera,$db);
$rowCabecera = mysql_fetch_assoc($resCabecera);

require($libPath."fpdf.php");
$maquina = $_SERVER['SERVER_NAME'];
if(strcmp("localhost",$maquina)==0)
	$carpetaOrden="../OrdenesPagoPDF/";
else
	$carpetaOrden="/home/sistemas/Documentos/Repositorio/OrdenesPagoPDF/";

function printHeader($pdf) {
	$pdf->Image('../img/Logo Membrete OSPIM.jpg',7,1,25,20,'JPG');
	$pdf->SetFont('Courier','B',30);
	$pdf->SetXY(35, 1);
	$pdf->Cell(35,10,"OSPIM",0,0);
	$pdf->SetFont('Courier','B',10);
	$pdf->SetXY(35, 10);
	$pdf->Cell(55,5,"Obra Social del Personal",0,0);
	$pdf->SetXY(35, 13);
	$pdf->Cell(55,5,"de la Industria Maderera",0,0);
	$pdf->SetFont('Courier','B',7);
	$pdf->SetXY(35, 16);
	$pdf->Cell(55,5,"RNOS 11.100-1",0,0);
	$pdf->SetFont('Courier','B',11);
	$pdf->SetXY(135, 1);
	$pdf->Cell(75,5,"C.U.I.T.: 30-50289264-5",0,0,"R");
	$pdf->SetXY(135, 6);
	$pdf->Cell(75,5,"I.V.A. EXENTO",0,0,"R");
	$pdf->SetXY(135, 11);
	$pdf->Cell(75,5,"INGRESOS BRUTOS EXENTO",0,0,"R");
	$pdf->SetFont('Courier','B',7);
	$pdf->SetXY(100, 16);
	$pdf->Cell(80,5,"Rojas 254 - CP 1405 - Cap. Fed. | Tel: 4431-4089/4791 | www.ospim.com.ar",0,0);
	$pdf->Line(7, 21, 210, 21);
}

function printDetalle($pdf, $rowCabecera, $db, $tipo) {
	$nroOrden = $rowCabecera['nroordenpago'];
	$pdf->SetFont('Courier','B',8);
	$pdf->SetXY(7, 21);
	$pdf->Cell(20,5,$rowCabecera['fechaorden'],0,0,"R");
	$pdf->SetXY(130, 21);
	$pdf->Cell(80,5,"Orden de Pago: ".$rowCabecera['nroordenpago']." - $tipo",0,0,"R");
	$pdf->SetXY(7, 25);
	$pdf->Cell(203,5,"NOMBRE: ".$rowCabecera['nombre']." - CUIT: ".$rowCabecera['cuit'],0,0,"L");
	$pdf->SetXY(7, 28);
	$pdf->Cell(203,5,"DIRECCION: ".$rowCabecera['domicilio']." - CP: ".$rowCabecera['numpostal']." - ".$rowCabecera['provincia'],0,0,"L");
	$pdf->SetXY(7, 31);
	$pdf->Cell(203,5,"EMAIL: ".$rowCabecera['email1']." - ".$rowCabecera['email2']." | TEL: ".$rowCabecera['ddn1']."-".$rowCabecera['telefono1'],0,0,"L");
	
	$principioOrden = "Ha sido emitido el pago";
	if ($rowCabecera['formapago'] == "E") {
		$principioOrden .= " en efectivo correspondiente a prestaciones medicas";
	}
	if ($rowCabecera['formapago'] == "T") {
		$principioOrden .= " a traves de la transfeencia Nro ".$rowCabecera['comprobantepago']." correspondiente a prestaciones medicas";
	}
	if ($rowCabecera['formapago'] == "C") {
		$principioOrden .= " a traves del cheque Nro ".$rowCabecera['comprobantepago']." correspondiente a prestaciones medicas";
	}
	$finalOrden = "asistenciales brindadas a nuestros afiliados, segun el siguiente detalle: ";
	
	$pdf->Line(7, 36, 210, 36);
	$pdf->SetXY(7, 36);
	$pdf->Cell(203,5,$principioOrden,0,0,"L");
	$pdf->SetXY(7, 39);
	$pdf->Cell(203,5,$finalOrden,0,0,"L");
	
	$y = 44;
	$pdf->SetXY(7, $y);
	$pdf->Cell(33.83,5,"FACTURA Nº",1,1,"C");
	$pdf->SetXY(40.83, $y);
	$pdf->Cell(33.83,5,"MONTO",1,1,"C");
	$pdf->SetXY(74.66, $y);
	$pdf->Cell(33.83,5,"DEBITO",1,1,"C");
	$pdf->SetXY(108.49, $y);
	$pdf->Cell(33.83,5,"A PAGAR",1,1,"C");
	$pdf->SetXY(142.32, $y);
	$pdf->Cell(33.83,5,"PAGO",1,1,"C");
	$pdf->SetXY(176.15, $y);
	$pdf->Cell(33.83,5,"SALDO",1,1,"C");
	
	$y += 5;
	
	$sqlDetalle = "SELECT * FROM ordendetalle o, facturas f WHERE o.nroordenpago = $nroOrden and o.idfactura = f.id";
	$resDetalle = mysql_query($sqlDetalle,$db);
	while($rowDetalle = mysql_fetch_array($resDetalle)) {
		 $pdf->SetXY(7, $y);
		 $pdf->Cell(33.83,4,$rowDetalle['puntodeventa']."-".$rowDetalle['nrocomprobante'],1,1,"C");
		 $pdf->SetXY(40.83, $y);
		 $pdf->Cell(33.83,4,$rowDetalle['importecomprobante'],1,1,"C");
		 $pdf->SetXY(74.66, $y);
		 $pdf->Cell(33.83,4,$rowDetalle['totaldebito'],1,1,"C");
		 $pdf->SetXY(108.49, $y);
		 $pdf->Cell(33.83,4,$rowDetalle['importepago'],1,1,"C");
		 $pdf->SetXY(142.32, $y);
		 $pdf->Cell(33.83,4,$rowDetalle['tipocancelacion'],1,1,"C");
		 $pdf->SetXY(176.15, $y);
		 $pdf->Cell(33.83,4,$rowDetalle['restoapagar'],1,1,"C");
	 	 $y += 4;
	}
	$y += 2;
	$pdf->SetXY(74.66, $y);
	$pdf->Cell(33.83,5,"TOTAL",0,0,"C");
	$pdf->SetXY(108.49, $y);
	$total = number_format(round($rowCabecera['importe']+$rowCabecera['retencion'],2),2,'.','');
	$pdf->Cell(33.83,5,$total,0,0,"C");
	$y += 5;
	$pdf->SetXY(74.66, $y);
	$pdf->Cell(33.83,5,"RETENCION",0,0,"C");
	$pdf->SetXY(108.49, $y);
	$pdf->Cell(33.83,5,$rowCabecera['retencion'],0,0,"C");
	$y += 5;
	$pdf->SetXY(74.66, $y);
	$pdf->Cell(33.83,5,"A PAGAR",0,0,"C");
	$pdf->SetXY(108.49, $y);
	$pdf->Cell(33.83,5,$rowCabecera['importe'],0,0,"C");
	
	$y = 190;
	$pdf->Image('../img/fgornatti.png',25,$y,15,20,'PNG');
	$pdf->Image('../img/fguzman.png',170,$y,25,20,'PNG');
	$y += 12;
	$pdf->Image('../img/sgornatti.png',15,$y,40,18,'PNG');
	$pdf->Image('../img/sguzman.png',165,$y,35,20,'PNG');
}

function printRecibo($pdf, $rowCabecera) {
	$y = 218;
	$pdf->Line(7, $y, 210, $y);
	
	$pdf->SetXY(130, $y);
	$pdf->Cell(80,5,"Recibo Nro: ".$rowCabecera['nroordenpago'],0,0,"R");
	$pdf->SetXY(7, $y);
	$pdf->Cell(43,5,"Buenos Aires, ".date("d-m-Y"),0,0,"R");
	
	$y += 4;
	$textoRecibo = "Recibimos de O.S.P.I.M. la cantidad de pesos ".cfgValorEnLetras($rowCabecera['importe']);
	if ($rowCabecera['formapago'] == "E") {
		$textoRecibo .= " en efectivo";
	}
	if ($rowCabecera['formapago'] == "T") {
		$textoRecibo2 = "Por intermedio de la transferencia Nro. ".$rowCabecera['comprobantepago'];
	}
	if ($rowCabecera['formapago'] == "C") {
		$textoRecibo2 = "Por intermedio del Nro ".$rowCabecera['comprobantepago'];
	}
	$textoFin = "En concepto de pago de prestaciones medicas asistenciales detallas en la orden de pago nro ".$rowCabecera['nroordenpago'];
	$pdf->SetXY(7, $y);
	$pdf->Cell(200,5,$textoRecibo,0,0,"L");
	
	if ($rowCabecera['formapago'] != "E") {
		$y += 4;
		$pdf->SetXY(7, $y);
		$pdf->Cell(200,5,$textoRecibo2,0,0,"L");
	}
	
	$y += 4;
	$pdf->SetXY(7, $y);
	$pdf->Cell(200,5,$textoFin,0,0,"L");
	$y += 5;
	$pdf->SetFont('Courier','B',8);
	$pdf->SetXY(160, $y);
	$pdf->Cell(50,4,"SON $: ".$rowCabecera['importe'],1,1,"R");
	$y += 4;
	$pdf->SetFont('Courier','B',6);
	$pdf->SetXY(7, $y);
	$pdf->Cell(200,4,"Remitir a O.S.P.I.M. conjuntamente con vuestro recibo oficial. En caso de hornorarios el talon debe ser firmado y sellado por el profesional",0,0,"L");
	$y += 16;
	$pdf->SetXY(7, $y);
	$pdf->Cell(150,1,"---------------------------------------",0,0,"C");
	$y += 1;
	$pdf->SetXY(7, $y);
	$pdf->Cell(150,3,"FIRMA PRESTADOR",0,0,"C");
}

/************************************************/
$ordenNombreArchivo = str_pad($nroOrden, 8, '0', STR_PAD_LEFT);
$nombreArchivoO = "OP".$ordenNombreArchivo."O.pdf";

$pdf = new FPDF('P','mm','Letter');
$pdf->AddPage();
printHeader($pdf);
printDetalle($pdf, $rowCabecera, $db, "ORIGINAL");
printRecibo($pdf, $rowCabecera);

$nombrearchivoO = $carpetaOrden.$nombreArchivoO;
$pdf->Output($nombrearchivoO,'F');

$nombreArchivoC = "OP".$ordenNombreArchivo."C.pdf";
$pdf = new FPDF('P','mm','Letter');
$pdf->AddPage();
printHeader($pdf);
printDetalle($pdf, $rowCabecera, $db, "DUPLICADO");
$pdf->AddPage();
printHeader($pdf);
printDetalle($pdf, $rowCabecera, $db, "TRIPLICADO");
$nombrearchivoC = $carpetaOrden.$nombreArchivoC;
$pdf->Output($nombrearchivoC,'F');

if ($email != "") {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	
	$subject = "Orden de Pago OSPIM";
	$bodymail="<body><br><br>Este es un mensaje de Aviso de ORDEN DE PAGO<br><br>";
	$username ="tesoreria@ospim.com.ar";
	$modulo = "Ordenes de Pago";
	$arrayAttachment[] = $nombrearchivoO;
	$idMail = guardarEmail($username, $subject, $bodymail, $email, $modulo, $arrayAttachment);
	$updateIdMail = "UPDATE ordencabecera SET idemail = $idMail WHERE nroordenpago = $nroOrden";
	//print($updateIdMail."<br>");
	$dbh->exec($updateIdMail);
	
	$dbh->commit();
}

$pagina = "../buscador/ordenPagoConsulta.php?nroorden=$nroOrden";
Header("Location: $pagina");

/************************************************/
?>