<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
if(isset($_POST)) {
	//var_dump($_POST);
	$codigoprestador = $_POST['codigoprestador'];
	$nrofactura = substr($_POST['nrofactura'], -8);
	$nrocomprobante = $_POST['nrofactura'];
	$error = 0;

	$sqlBuscaFactura = "SELECT id, importecomprobante FROM facturas WHERE idPrestador = $codigoprestador AND nrocomprobante = $nrofactura order by id DESC LIMIT 1";
	$resBuscaFactura = mysql_query($sqlBuscaFactura,$db);
	if(mysql_num_rows($resBuscaFactura) != 0) {
		$rowBuscaFactura = mysql_fetch_array($resBuscaFactura);
		$error = 1;
		$idcomprobante = $rowBuscaFactura['id'];
		$importecomprobante = $rowBuscaFactura['importecomprobante'];
	}

	if($error != 0) {
		header("Location: moduloFacturas.php?err=$error&id=$idcomprobante&importe=$importecomprobante");
	}
	else {
		header("Location: ingresarFactura.php?prestador=$codigoprestador&comprobante=$nrocomprobante");
	}
}
?>