<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php");
require($libPath."fpdf.php");
$maquina = $_SERVER['SERVER_NAME'];

if(strcmp("localhost",$maquina)==0)
		$carpetaMinuta="C:/tmp/";
	else
		$carpetaMinuta="/tmp/";

//OPCION Personalizada
$offsetX = 6;
$offsetY = 4;
$bordes = 0;
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Courier','B',10);
$fechaArray = explode("-",$_POST['fecha']);

//FECHA
$pdf->SetXY(144+$offsetX, 28+$offsetY);
$pdf->Cell(17,7, $fechaArray[0],$bordes,$bordes,'R');
$pdf->SetXY(161+$offsetX, 28+$offsetY);
$pdf->Cell(17,7, $fechaArray[1],$bordes,$bordes,'R');
$pdf->SetXY(178+$offsetX, 28+$offsetY);
$pdf->Cell(17,7, $fechaArray[2],$bordes,$bordes,'R');

//ASIENTO
$pdf->SetXY(144+$offsetX, 35+$offsetY);
$pdf->Cell(51,7, $_POST['asiento'],$bordes,$bordes,'R');

//CUENTA
$pdf->SetXY(26+$offsetX, 42+$offsetY);
$pdf->Cell(70,17, $_POST['cuenta'],$bordes,$bordes,'C');

//CHEQUE
$pdf->SetFont('Courier','B',12);
$pdf->SetXY(100+$offsetX, 42+$offsetY);
$pdf->Cell(35,10, $_POST['cheque'],$bordes,$bordes,'R');

$pdf->SetFont('Courier','B',10);
if ($_POST['tipo'] == 'deposito') {
	$pdf->SetXY(164+$offsetX, 44+$offsetY);
	$pdf->Cell(4,4,"X",$bordes,$bordes,'R');
}
if ($_POST['tipo'] == 'debito') {
	$pdf->SetXY(131+$offsetX, 52+$offsetY);
	$pdf->Cell(4,4,"X",$bordes,$bordes,'R');
}
if ($_POST['tipo'] == 'credito') {
	$pdf->SetXY(164+$offsetX, 52+$offsetY);
	$pdf->Cell(4,4,"X",$bordes,$bordes,'R');
}

//IMPORTE
$pdf->SetXY(164+$offsetX, 42+$offsetY);
$pdf->Cell(31,18, "$ ".number_format($_POST['importe'],"2",",","."),$bordes,$bordes,'R');

$detalle = explode("\n", $_POST['detalle']);
$y=65;
foreach ($detalle as $lineaDetalle) {
	if ($y >= 118) {
		break;
	}
	$pdf->SetXY(34+$offsetX, $y+$offsetY);
	$pdf->Cell(154,5, $lineaDetalle,$bordes,1,'L');
	$y += 5;
}

$debe = explode("\n", $_POST['debe']);
$y=123;
foreach ($debe as $lineaDebe) {
	if ($y >= 136) {
		break;
	}
	$pdf->SetXY(34+$offsetX, $y+$offsetY);
	$pdf->Cell(146,5,$lineaDebe,$bordes,1,'L');
	$y += 5;
}

$haber = explode("\n", $_POST['haber']);
$y=142;
foreach ($haber as $lineaHaber) {
	if ($y >= 157) {
		break;
	}
	$pdf->SetXY(34+$offsetX, $y+$offsetY);
	$pdf->Cell(146,5, $lineaHaber,$bordes,1,'L');
	$y += 5;
}

$fechaGeneracion = date("YmdHis");
$nombreMinuta = "minuta".$fechaGeneracion.".pdf";

$nombrearchivo = $carpetaMinuta.$nombreMinuta;
$pdf->Output($nombrearchivo,'F');
header('Content-type: application/pdf');
header('Content-Disposition: inline; filename="'.$nombrearchivo.'"');
readfile($nombrearchivo);
unlink($nombrearchivo);
?>