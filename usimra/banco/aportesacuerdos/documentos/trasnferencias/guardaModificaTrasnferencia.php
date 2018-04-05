<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php"); 

$nrotrans = $_GET['nrotrans'];
$datos = array_values($_POST);
$banco = $_POST['banco'];
$sucursal = $_POST['sucursal'];
$nrocuenta = $_POST['cuenta'];
$cuit = $_POST['cuit'];
$monto = $_POST['monto'];
$orden = $_POST['orden'];
$fecha = fechaParaGuardar($_POST['fecha']);
$comi = $_POST['comision'];
$iva = $_POST['ivacomi'];
$fecmod = date("Y-m-d H:i:s");
$usumod = $_SESSION['usuario'];

$sqlUpdateTrans = "UPDATE transferenciasusimra SET banco = '$banco', sucursal = $sucursal, numerocuenta = '$nrocuenta', cuit = '$cuit', monto = $monto, numeroorden = '$orden', fecha = '$fecha', importecomision = $comi, ivacomision = $iva, fechamodificacion = '$fecmod', usuariomodificacion = '$usumod' WHERE idtransferencia = $nrotrans";
print($sqlUpdateTrans."<br>");

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	$dbh->exec($sqlUpdateTrans);
	$dbh->commit();
	$pagina = "consultaTransferencia.php?nrotrans=$nrotrans";
	Header("Location: $pagina"); 
}catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/usimra/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}

?>