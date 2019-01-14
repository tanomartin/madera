<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
$nroorden = $_GET['nroorden'];
$updateCabecera = "UPDATE ordennmcabecera SET idcuenta = NULL, fechaimputacion = NULL WHERE nroorden = $nroorden";
$deleteImputacion = "DELETE FROM ordennmimputacion WHERE nroorden = $nroorden";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//print($updateCabecera."<br>");
	$dbh->exec($updateCabecera);
	//print($deleteImputacion."<br>");
	$dbh->exec($deleteImputacion);
	
	$dbh->commit();
	$pagina = "imputaOrdenPagoNM.php?nroorden=$nroorden";
	Header("Location: $pagina");
} catch (PDOException $e) {
	$error = $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	Header($redire);
	exit(0);
}