<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$texto = $_GET['texto'];
$nroSolicitud = $_GET['nrosolicitud'];
$sqlUpdateHC = "INSERT INTO autorizacioneshistoria VALUES($nroSolicitud, '$texto') ON DUPLICATE KEY UPDATE detalle = '$texto'";
if ($texto == "") {
	$sqlUpdateHC = "DELETE FROM autorizacioneshistoria WHERE nrosolicitud = $nroSolicitud";
}
try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//print($sqlUpdateHC."<br>");
	$dbh->exec($sqlUpdateHC);

	$dbh->commit();
	$pagina = "consultaAutorizacion.php?nroSolicitud=$nroSolicitud";
	Header("Location: $pagina"); 
} catch (PDOException $e) {
	$error = $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	Header($redire);
	exit(0);
}


?>