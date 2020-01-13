<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
require($libPath."fpdf.php");

$nroorden = $_GET['nroorden'];
$id = $_GET['id'];

$sqlFactura = "SELECT puntodeventa, nrocomprobante, importecomprobante,
				DATE_FORMAT(fechacomprobante,'%d/%m/%Y') as fechacomprobante 
				FROM facturas WHERE id = $id";
$resFactura = mysql_query($sqlFactura,$db);
$rowFactura = mysql_fetch_assoc($resFactura);

$sqlCabecera = "SELECT o.*, DATE_FORMAT(fechaorden,'%d/%m/%Y') as fechaorden, p.nombre as prestador
				FROM ordencabecera o, prestadores p 
				WHERE o.nroordenpago = $nroorden and o.codigoprestador = p.codigoprestador";
$resCabecera = mysql_query($sqlCabecera,$db);
$rowCabecera = mysql_fetch_assoc($resCabecera);

$sqlDetalle = "SELECT o.*, c.*
				FROM ordendetalle o, facturasconceptos c
				WHERE o.nroordenpago = $nroorden and o.idfactura = c.idfactura";
$resDetalle = mysql_query($sqlDetalle,$db);

$maquina = $_SERVER['SERVER_NAME'];
if(strcmp("localhost",$maquina)==0)
	$carpetaOrden="../OrdenesPagoPDF/";
else
	$carpetaOrden="/home/sistemas/Documentos/Repositorio/OrdenesPagoNMPDF/";

function printHeader($pdf, $rowCabecera, $rowFactura) {
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
	$nro = "Nº: ".$rowCabecera['nroordenpago'];
	$pdf->Cell(50,5,$nro,0,0);
	
	$pdf->SetFont('Courier','B',10);
	$pdf->SetXY(10, 25);
	$fecha = "Fecha: ".$rowCabecera['fechaorden'];
	$pdf->Cell(40,5,$fecha,0,0);
	
	$pdf->SetXY(10, 30);
	$fecha = "Prestador: ".$rowCabecera['prestador'];
	$pdf->Cell(185,5,$fecha,0,0);

	$pdf->SetXY(10, 35);
	$labelFactura ="Factura Nº: ".$rowFactura['puntodeventa']."-".$rowFactura['nrocomprobante']." | Fecha: ".$rowFactura['fechacomprobante']. " | Monto: ".number_format($rowFactura['importecomprobante'],2,",",".");
	$pdf->Cell(185,5,$labelFactura,0,0);
	
	$pdf->SetXY(10, 40);
	if ($rowCabecera['formapago'] == "E") {
		$labelTipoPago = " - Efectivo";
	}
	if ($rowCabecera['formapago'] == "T") {
		$labelTipoPago = " - Transferencia Nro: ".$rowCabecera['comprobantepago'];
	}
	if ($rowCabecera['formapago'] == "C") {
		$labelTipoPago = " - Cheque Nro: ".$rowCabecera['comprobantepago']." C/Banco Nación Argentina Suc. Caballito";
	}
	$monto = "A Pagar: ".number_format($rowCabecera['importe'],2,",",".").$labelTipoPago;
	$pdf->Cell(40,5,$monto,0,0);
}

function printDetalle($pdf, $resDetalle, $retencion) {
	$pdf->SetFont('Courier','B',10);
	$pdf->SetXY(7, 55);
	$pdf->Cell(165,165,'',1,1);
	$pdf->SetXY(172, 55);
	$pdf->Cell(25,165,'',1,1);
	$pdf->SetXY(197, 55);
	$pdf->Cell(10,165,'',1,1);
	
	$pdf->SetXY(7, 48);
	$pdf->Cell(165,7,'CONCEPTO',1,1,'C');
	$pdf->SetXY(172, 48);
	$pdf->Cell(25,7,'IMPORTE',1,1,'C');
	
	$pdf->SetXY(197, 48);
	$pdf->Cell(10,7,'TIPO',1,1,'C');
	
	$pdf->SetFont('Courier','',8);
	$posY = 55;
	while($rowDetalle = mysql_fetch_assoc($resDetalle)) {
		$pdf->SetXY(7, $posY);
		$pdf->MultiCell(165,5,$rowDetalle['detalle'],0,'L');
		
		$tamañoDetalle = strlen($rowDetalle['detalle']);
		$limiteCaracteres = 88;
		$lineasDetalle = ceil($tamañoDetalle / $limiteCaracteres);
		$posSegunDetalle = $posY + ($lineasDetalle*5);
		
		$pdf->SetXY(172, $posY);
		$pdf->Cell(25,5,number_format($rowDetalle['importe'],2,",","."),0,0,'C');
		
		$pdf->SetXY(197, $posY);
		$pdf->Cell(10,5,$rowDetalle['tipo'],0,0,'C');

		if ($posSegunDetalle > $posY) {
			$posY = $posSegunDetalle;
		}
		$pdf->Line(7, $posY, 207, $posY);
	}
	if ($retencion > 0) {
		$pdf->SetXY(7, $posY);
		$pdf->MultiCell(165,5,"Retencion",0,'L');
		$pdf->SetXY(172, $posY);
		$pdf->Cell(25,5,number_format($retencion,2,",","."),0,0,'C');
		$pdf->SetXY(197, $posY);
		$pdf->Cell(10,5,"D",0,0,'C');
		$posY = $posY + 5;
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
$nombreArchivo = "OP".$ordenNombreArchivo."NM.pdf";

$pdf = new FPDF('P','mm','Letter');
$pdf->AddPage();
printHeader($pdf, $rowCabecera, $rowFactura);
printDetalle($pdf, $resDetalle, $rowCabecera['retencion']);
printFooter($pdf);

$nombrearchivo = $carpetaOrden.$nombreArchivo;
$pdf->Output($nombrearchivo,'F');

$pagina = "consultaFacturaNM.php?id=$id";
Header("Location: $pagina");
/************************************************/
?>