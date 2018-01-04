<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php");
require($libPath."fpdf.php");
$maquina = $_SERVER['SERVER_NAME'];

if(strcmp("localhost",$maquina)==0)
		$carpetaMinuta="C:/tmp/";
	else
		$carpetaMinuta="/tmp/";


//OPCION A4
//$pdf = new FPDF('P','mm', 'A4');

//OPCION Personalizada
$pdf = new FPDF('L','mm', array(200,190));

$pdf->AddPage();
$pdf->SetFont('Courier','B',10);

$fechaArray = explode("-",$_POST['fecha']);

//$pdf->SetXY(152, 31);
$pdf->SetXY(144, 28);
$pdf->Cell(17,7, $fechaArray[0],0,0,'R');
$pdf->SetXY(159, 28);
$pdf->Cell(17,7, $fechaArray[1],0,0,'R');
$pdf->SetXY(179, 28);
$pdf->Cell(17,7, $fechaArray[2],0,0,'R');

//$pdf->SetXY(152, 38);
$pdf->SetXY(144, 35);
$pdf->Cell(51,7, $_POST['asiento'],0,0,'R');

//$pdf->SetXY(35, 45);
$pdf->SetXY(25, 45);
$pdf->Cell(70,18, $_POST['cuenta'],0,0,'C');

//$pdf->SetXY(105, 45);
$pdf->SetFont('Courier','B',12);
$pdf->SetXY(101, 42);
$pdf->Cell(35,10, $_POST['cheque'],0,0,'R');

$pdf->SetFont('Courier','B',10);
if ($_POST['tipo'] == 'deposito') {
	//$pdf->SetXY(140, 45);
	$pdf->SetXY(137, 42);
	$pdf->Cell(32,5,"X",0,0,'R');
}
if ($_POST['tipo'] == 'debito') {
	//$pdf->SetXY(105, 55);
	$pdf->SetXY(101, 52);
	$pdf->Cell(35,5,"X",0,0,'R');
}
if ($_POST['tipo'] == 'credito') {
	//$pdf->SetXY(140, 45);
	$pdf->SetXY(137, 52);
	$pdf->Cell(32,5,"X",0,0,'R');
}

//$pdf->SetXY(172, 45);
$pdf->SetXY(165, 45);
$pdf->Cell(31,18, "$ ".number_format($_POST['importe'],"2",",","."),0,0,'R');

$detalle = explode("\n", $_POST['detalle']);
//$y=63;
$y=63;
foreach ($detalle as $lineaDetalle) {
	if ($y >= 118) {
		break;
	}
	//$pdf->SetXY(49, $y);
	$pdf->SetXY(41, $y);
	$pdf->Cell(154,5, $lineaDetalle,0,1,'L');
	$y += 5;
}

$debe = explode("\n", $_POST['debe']);
//$y=123;
$y=121;
foreach ($debe as $lineaDebe) {
	if ($y >= 136) {
		break;
	}
	//$pdf->SetXY(57, $y);
	$pdf->SetXY(49, $y);
	$pdf->Cell(146,5,$lineaDebe,0,1,'L');
	$y += 5;
}

$haber = explode("\n", $_POST['haber']);
//$y=143;
$y=142;
foreach ($haber as $lineaHaber) {
	if ($y >= 157) {
		break;
	}
	//$pdf->SetXY(57, $y);
	$pdf->SetXY(49, $y);
	$pdf->Cell(146,5, $lineaHaber,0,1,'L');
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