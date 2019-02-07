<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
require($libPath."fpdf.php");

$nroorden = $_GET['nroorden'];
$sqlCabecera = "SELECT ordennmcabecera.*, DATE_FORMAT(fecha,'%d/%m/%Y') as fecha 
				FROM ordennmcabecera WHERE nroorden = $nroorden";
$resCabecera = mysql_query($sqlCabecera,$db);
$rowCabecera = mysql_fetch_assoc($resCabecera);

$sqlDetalle = "SELECT * FROM ordennmdetalle WHERE nroorden = $nroorden";
$resDetalle = mysql_query($sqlDetalle,$db);

$maquina = $_SERVER['SERVER_NAME'];
if(strcmp("localhost",$maquina)==0)
	$carpetaOrden="../OrdenesPagoPDF/";
else
	$carpetaOrden="/home/sistemas/Documentos/Repositorio/OrdenesPagoNMPDF/";

function printHeader($pdf, $rowCabecera) {
	$pdf->SetFont('Courier','B',15);
	$pdf->SetXY(65, 3);
	$pdf->Cell(35,10,"DEBITOS / CREDITOS BANCARIOS",0,0);
	
	$pdf->SetFont('Courier','B',25);
	$pdf->SetXY(75, 13);
	$pdf->Cell(55,5,"ORDEN DE PAGO",0,0);
	
	$pdf->SetXY(7, 23);
	$pdf->Cell(200,25,'',1,1);

	$pdf->SetFont('Courier','B',18);
	$pdf->SetXY(150, 25);
	$nro = "Nº: ".$rowCabecera['nroorden'];
	$pdf->Cell(50,5,$nro,0,0);
	
	$pdf->SetFont('Courier','B',10);
	$pdf->SetXY(10, 25);
	$fecha = "Fecha: ".$rowCabecera['fecha'];
	$pdf->Cell(40,5,$fecha,0,0);
	
	$pdf->SetXY(10, 30);
	$fecha = "Beneficiario: ".$rowCabecera['beneficiario'];
	$pdf->Cell(185,5,$fecha,0,0);
	
	$pdf->SetXY(10, 35);
	$monto = "$: ".number_format($rowCabecera['importe'],2,",",".");
	$pdf->Cell(40,5,$monto,0,0);
	
	$pdf->SetXY(10, 40);
	if ($rowCabecera['tipopago'] == "T") {
		$labelTipoPago = "Transferencia Nro: ".$rowCabecera['nropago'];
	}
	if ($rowCabecera['tipopago'] == "C") {
		$labelTipoPago = "Cheque Nro: ".$rowCabecera['nropago']." C/Banco Nación Argentina Suc. Caballito";
	}
	$pdf->Cell(185,5,$labelTipoPago,0,0);
}

function printDetalle($pdf, $resDetalle, $db) {
	$pdf->SetXY(7, 48);
	$pdf->Cell(200,172,'',1,1);
	
	$pdf->SetXY(7, 55);
	$pdf->Cell(100,165,'',1,1);
	$pdf->SetXY(107, 55);
	$pdf->Cell(25,165,'',1,1);
	$pdf->SetXY(132, 55);
	$pdf->Cell(10,165,'',1,1);
	$pdf->SetXY(142, 55);
	$pdf->Cell(65,165,'',1,1);
	
	$pdf->SetXY(7, 48);
	$pdf->Cell(100,7,'CONCEPTO',1,1,'C');
	$pdf->SetXY(107, 48);
	$pdf->Cell(25,7,'IMPORTE',1,1,'C');
	
	$pdf->SetXY(132, 48);
	$pdf->Cell(10,7,'TIPO',1,1,'C');
	
	$pdf->SetXY(142, 48);
	$pdf->Cell(65,7,'IMPUTACION CONTABLE',1,1,'C');
	
	$pdf->SetFont('Courier','',8);
	$posY = 55;
	while($rowDetalle = mysql_fetch_assoc($resDetalle)) {
		$pdf->SetXY(7, $posY);
		$pdf->MultiCell(100,5,$rowDetalle['detalle'],0,'L');
		
		$tamañoDetalle = strlen($rowDetalle['detalle']);
		$limiteCaracterres = 58;
		$lineasDetalle = ceil($tamañoDetalle / $limiteCaracterres);
		$posSegunDetalle = $posY + ($lineasDetalle*5);
		
		$pdf->SetXY(107, $posY);
		$pdf->Cell(25,5,number_format($rowDetalle['importe'],2,",","."),0,0,'C');
		
		$pdf->SetXY(132, $posY);
		$pdf->Cell(10,5,$rowDetalle['tipo'],0,0,'C');
		
		$sqlImputacion = "SELECT o.*, c.nrocta as cuenta FROM ordennmimputacion o, cuentasospim c 
							WHERE o.nroorden = ".$rowDetalle['nroorden']." and 
								  o.concepto = ".$rowDetalle['concepto']." and 
								  o.idcuenta = c.id";
		$resImputacion = mysql_query($sqlImputacion,$db);
		while($rowImputacion = mysql_fetch_assoc($resImputacion)) {
			$pdf->SetXY(142, $posY);
			$imputacion = 'CTA: '.$rowImputacion['cuenta']." | IMP: ".number_format($rowImputacion['importe'],2,",",".");
			$pdf->Cell(65,5,$imputacion,0,0,'C');
			$posY += 5;
		}
		if ($posSegunDetalle > $posY) {
			$posY = $posSegunDetalle;
		}
		$pdf->Line(7, $posY, 207, $posY);
	}
}

function printFooter($pdf) {
	$pdf->SetFont('Courier','B',10);
	$pdf->SetXY(7, 220);
	$pdf->Cell(200,38,'',1,1);
	
	$pdf->SetXY(7, 220);
	$pdf->Cell(100,7,'Autorizado',1,1,'C');
	
	$pdf->SetXY(107, 220);
	$pdf->Cell(100,7,'Contabilizado',1,1,'C');
	
	$pdf->SetXY(107, 227);
	$pdf->Cell(50,7,'Firma',1,1,'C');
	$pdf->SetXY(107, 227);
	$pdf->Cell(50,31,'',1,1);
	
	$pdf->SetXY(157, 227);
	$pdf->Cell(50,7,'Aclaracion',1,1,'C');
	$pdf->SetXY(157, 227);
	$pdf->Cell(50,31,'',1,1);
}

/************************************************/
$ordenNombreArchivo = str_pad($nroorden, 8, '0', STR_PAD_LEFT);
$nombreArchivo = "OP-NM".$ordenNombreArchivo.".pdf";

$pdf = new FPDF('P','mm','Letter');
$pdf->AddPage();
printHeader($pdf, $rowCabecera);
printDetalle($pdf, $resDetalle, $db);
printFooter($pdf);

$nombrearchivo = $carpetaOrden.$nombreArchivo;
$pdf->Output($nombrearchivo,'F');

$fechacancelacion = date("Y-m-d");
$sqlUpdateCabecera = "UPDATE ordennmcabecera SET fechageneracion = '$fechacancelacion' WHERE nroorden = $nroorden";
try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//print($sqlUpdateCabecera."<br>");
	$dbh->exec($sqlUpdateCabecera);

	$dbh->commit();
	$nombrearchivo;
	$archivo = 	$carpetaOrden.$nombreArchivo;
	$tamanio = filesize($archivo);
	header("Content-type: application/pdf");
	header("Content-Length: $tamanio");
	header("Content-Disposition: inline; filename=$archivo");
	$respuesta=readfile($archivo);
	echo $respuesta;
	
} catch (PDOException $e) {
	$error = $e->getMessage();
	$dbh->rollback();
	unlink($nombrearchivo);
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	Header($redire);
	exit(0);
}


/************************************************/
?>