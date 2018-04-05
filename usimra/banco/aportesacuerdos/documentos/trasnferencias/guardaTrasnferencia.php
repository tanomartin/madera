<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php"); 

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
$fecreg = date("Y-m-d H:i:s");
$usureg = $_SESSION['usuario'];

$sqlInsertTrans = "INSERT INTO transferenciasusimra VALUE(DEFAULT,'$banco',$sucursal,'$nrocuenta','$cuit',$monto,'$orden','$fecha',$comi,$iva,'$fecreg','$usureg',DEFAULT,DEFAULT)";
try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	$dbh->exec($sqlInsertTrans);
	$dbh->commit();
	
	$sqlUltimpoID = "SELECT idtransferencia FROM transferenciasusimra ORDER BY idtransferencia DESC LIMIT 1";
	$result = $dbh->query($sqlUltimpoID);
	$ultimoReg = $result->fetch(PDO::FETCH_ASSOC);
	$ultimo_id = $ultimoReg['idtransferencia'];
	$pagina = "consultaTransferencia.php?nrotrans=$ultimo_id";
	Header("Location: $pagina"); 
}catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/usimra/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}

?>