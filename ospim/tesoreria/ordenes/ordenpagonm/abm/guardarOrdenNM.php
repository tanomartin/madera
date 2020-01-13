<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");

$id = $_POST['id'];
$codigo = $_POST['codigo'];
$fechaorden = date("Y-m-d");
$tipoPago = $_POST['tipopago'];
$nroPago = 'NULL';
if ($tipoPago != 'E') {
	$nroPago = "'".$_POST['numero']."'";
}
$fechaPago = fechaParaGuardar($_POST['fecha']);
$impDebito = $_POST['debito'];
$retencion = $_POST['retencion'];
$impRetencion = 0;
if ($retencion != 0) {
	$impRetencion = $_POST['rete'];
}
$impApagar = $_POST['apagar'];
$email = "";
if (isset($_POST['enviomail'])) {
	$envioEmail = $_POST['enviomail'];
	if ($envioEmail != 0) {
		$email = $_POST['email'];
	}
}
$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];

$sqlCabeceraOrden = "INSERT INTO ordencabecera VALUE(DEFAULT, $codigo,'$fechaorden','$tipoPago', $nroPago, '$fechaPago', $impRetencion, $impDebito, $impApagar, NULL, NULL, NULL, '$fecharegistro', '$usuarioregistro',NULL,NULL)";
$sqlDetalleOrden = "INSERT INTO ordendetalle VALUE(nroorden, $id, 'T', $impApagar, NULL, NULL, NULL)";
$sqlUpdateFactura = "UPDATE facturas SET totalpagado = $impApagar, restoapagar = 0, fechapago = '$fechaPago' WHERE id = $id";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//echo $sqlCabeceraOrden."<br>";
	$dbh->exec($sqlCabeceraOrden);
	$lastId = $dbh->lastInsertId();
	
	$sqlDetalleOrden = str_replace("nroorden", $lastId, $sqlDetalleOrden);
	//echo $sqlDetalleOrden."<br>";
	$dbh->exec($sqlDetalleOrden);

	//echo $sqlUpdateFactura."<br>";
	$dbh->exec($sqlUpdateFactura);

	$dbh->commit();
	$pagina = "documentoOrdenPagoNM.php?nroorden=$lastId&id=$id";
	Header("Location: $pagina");
} catch (PDOException $e) {
	$error = $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	Header($redire);
	exit(0);
}
?>