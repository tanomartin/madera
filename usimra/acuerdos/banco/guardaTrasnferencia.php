<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php"); 

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
	echo $e->getMessage();
	$dbh->rollback();
}

?>