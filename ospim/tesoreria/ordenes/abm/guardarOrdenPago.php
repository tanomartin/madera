<?php  $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");

$importeTotal = $_POST['total'];
$codigo = $_POST['codigo'];
$fechaorden = date("Y-m-d");
$tipoPago = $_POST['tipopago'];
$nroPago = NULL;
if ($tipoPago != 'E') {
	$nroPago = $_POST['numero'];
}
$fechaPago = fechaParaGuardar($_POST['fecha']);
$retencion = $_POST['retencion'];
$impRetencion = 0;
if ($retencion != 0) {
	$impRetencion = $_POST['rete'];
}
$impApagar = $_POST['apagar'];
$envioEmail = $_POST['enviomail'];
$email = "";
if ($envioEmail != 0) {
	$email = $_POST['email'];
}
$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];
$sqlCabeceraOrden = "INSERT INTO ordencabecera VALUE(DEFAULT, $codigo,'$fechaorden','$tipoPago', '$nroPago', '$fechaPago', $impRetencion, $impApagar, NULL, NULL, NULL, '$fecharegistro', '$usuarioregistro',NULL,NULL)";

$arrayDetalle = array();
$arrayUpdateFactura = array();
foreach ($_POST as $key => $facturas) {
	$pos = strpos($key, "id");
	if ($pos !== false) {
		$id = intval(preg_replace('/[^0-9]+/', '', $key), 10); 
		$indexTipo = "tipo".$id;
		$tipoPagoFactura = $_POST[$indexTipo];
		$indexValor = "valor".$id;
		$valorPagoFactura = $_POST[$indexValor];
		$sqlDetalleOrden = "INSERT INTO ordendetalle VALUE(nroorden, $id, '$tipoPagoFactura', $valorPagoFactura, NULL, NULL, NULL)";
		$arrayDetalle[$id] = $sqlDetalleOrden;
		$sqlUpdateFactura = "UPDATE facturas SET totalpagado = totalpagado + $valorPagoFactura, restoapagar = restoapagar - $valorPagoFactura, fechapago = '$fechaPago' WHERE id = $id";
		$arrayUpdateFactura[$id] = $sqlUpdateFactura;
	}
}

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//print($sqlCabeceraOrden."<br>");
	$dbh->exec($sqlCabeceraOrden);
	$lastId = $dbh->lastInsertId();

	foreach ($arrayDetalle as $detalleOrden) {
		$detalleOrden = str_replace("nroorden", $lastId, $detalleOrden);
		//print($detalleOrden."<br>");
		$dbh->exec($detalleOrden);
	}
	
	foreach ($arrayUpdateFactura as $updateFactura) {
		//print($updateFactura."<br>");
		$dbh->exec($updateFactura);
	}
	
	$dbh->commit();
	$pagina = "documentoOrdenPago.php?nroorden=$lastId&email=$email";
	Header("Location: $pagina");
} catch (PDOException $e) {
	$error = $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	Header($redire);
	exit(0);
}

?>