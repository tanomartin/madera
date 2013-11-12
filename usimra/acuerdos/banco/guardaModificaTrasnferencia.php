<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php"); 

$nrotrans = $_GET['nrotrans'];
$datos = array_values($_POST);
$banco = $datos[0];
$sucursal = $datos[1];
$nrocuenta = $datos[2];
$cuit = $datos[3];
$monto = $datos[4];
$orden = $datos[5];
$fecha = fechaParaGuardar($datos[6]);
$comi = $datos[7];
$iva = $datos[8];
$fecmod = date("Y-m-d H:m:s");
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
	echo $e->getMessage();
	$dbh->rollback();
}

?>