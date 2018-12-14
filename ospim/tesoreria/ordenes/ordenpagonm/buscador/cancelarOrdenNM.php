<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
$nroorden = $_GET['nroorden'];
$fechacancelacion = date("Y-m-d H:i:s");
$usuariomodificacion = $_SESSION['usuario'];
$updateCancelacion = "UPDATE ordennmcabecera SET fechacancelacion = '$fechacancelacion', usuariocancelacion = '$usuariomodificacion' WHERE nroorden = $nroorden";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//print($updateCancelacion."<br>");
	$dbh->exec($updateCancelacion);

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