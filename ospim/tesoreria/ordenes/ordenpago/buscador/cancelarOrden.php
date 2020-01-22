<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
$nroorden = $_GET['nroorden'];
$sqlDetalle = "SELECT * FROM ordendetalle WHERE nroordenpago = $nroorden";
$resDetalle = mysql_query($sqlDetalle,$db);
$arrayUpdateFactura = array();
while($rowDetalle = mysql_fetch_assoc($resDetalle)) { 
	$importePagado = $rowDetalle['importepago'];
	$fechapago = "0000-00-00";
	if ($rowDetalle['tipocancelacion'] == 'P') {
		$sqlFechaUltimoPago = "SELECT o.fechaorden 
								FROM ordencabecera o, ordendetalle d 
								WHERE d.nroordenpago != $nroorden and 
								      d.idfactura = ".$rowDetalle['idfactura']." and 
									  d.nroordenpago = o.nroordenpago and
									  o.fechacancelacion is null
								ORDER BY o.nroordenpago DESC LIMIT 1";
		$resFechaUltimoPago = mysql_query($sqlFechaUltimoPago,$db);
		$numFechaUltimoPago = mysql_num_rows($resFechaUltimoPago);
		if ($numFechaUltimoPago == 1) {
			$rowFechaUltimoPago = mysql_fetch_assoc($resFechaUltimoPago);
			$fechapago = $rowFechaUltimoPago['fechaorden'];
		}
	}
	$sqlUpdateFactura = "UPDATE facturas SET totalpagado = totalpagado - $importePagado, restoapagar = restoapagar + $importePagado, fechapago = '$fechapago' WHERE id = ".$rowDetalle['idfactura'];
	$arrayUpdateFactura[$rowDetalle['idfactura']] = $sqlUpdateFactura;
}

$fechacancelacion = date("Y-m-d H:i:s");
$usuariomodificacion = $_SESSION['usuario'];
$updateCancelacion = "UPDATE ordencabecera SET fechacancelacion = '$fechacancelacion', fechamodificacion = '$fechacancelacion', usuariomodificacion = '$usuariomodificacion' WHERE nroordenpago = $nroorden";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//print($updateCancelacion."<br>");
	$dbh->exec($updateCancelacion);

	foreach ($arrayUpdateFactura as $updateFactura) {
		//print($updateFactura."<br>");
		$dbh->exec($updateFactura);
	}

	$dbh->commit();
	$pagina = "ordenPagoConsulta.php?nroorden=$nroorden&dato=".$_GET['dato']."&filtro=".$_GET['filtro'];
	Header("Location: $pagina");
} catch (PDOException $e) {
	$error = $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	Header($redire);
	exit(0);
}
?>