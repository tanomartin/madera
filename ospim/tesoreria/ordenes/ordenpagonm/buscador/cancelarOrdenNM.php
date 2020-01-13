<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
require_once($libPath."fpdf.php");
require_once($libPath."FPDI-1.6.1/fpdi.php");
$nroorden = $_GET['nroorden'];
$idFac = $_GET['idFac'];
$importe = $_GET['importe'];

$maquina = $_SERVER['SERVER_NAME'];
if(strcmp("localhost",$maquina)==0)
	$carpetaOrden="../OrdenesPagoPDF/";
else
	$carpetaOrden="/home/sistemas/Documentos/Repositorio/OrdenesPagoNMPDF/";

$fechacancelacion = date("Y-m-d");
$usuariomodificacion = $_SESSION['usuario'];
$updateCancelacion = "UPDATE ordencabecera SET fechacancelacion = '$fechacancelacion' WHERE nroordenpago = $nroorden";
$updateFactura = "UPDATE facturas SET totalpagado = 0, restoapagar = $importe WHERE id = $idFac";
try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//print($updateCancelacion."<br>");
	$dbh->exec($updateCancelacion);
	//print($updateFactura."<br>");
	$dbh->exec($updateFactura);

	$ordenNombreArchivo = str_pad($nroorden, 8, '0', STR_PAD_LEFT);
	$nombreArchivo = "OP".$ordenNombreArchivo."NM.pdf";
	$filename = $carpetaOrden.$nombreArchivo;
	if (file_exists($filename)) {
		$pdf = new FPDI();
		$pdf->AddPage();
		$pdf->setSourceFile($filename);
		$page = $pdf->importPage(1);
		$pdf->useTemplate($page);
		$pdf->Image('anulada.png',7,48,200,200);
		$pdf->Output($filename,'F');
	}
	
	$dbh->commit();
	$pagina = "buscarOrdenNM.php?nroorden=$nroorden";
	Header("Location: $pagina");
} catch (PDOException $e) {
	$error = $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	Header($redire);
	exit(0);
}
?>