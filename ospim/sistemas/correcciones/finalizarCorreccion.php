<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 

$id = $_GET['id'];
$origen = $_GET['origen'];
$fechaFinalizacion = date ( "Y-m-d H:i:s" );
$sqlFinalizar = "UPDATE correcciones SET fechafinalizacion = '$fechaFinalizacion'  WHERE id = $id";
try {
	$hostname = $_SESSION ['host'];
	$dbname = $_SESSION ['dbname'];
	$dbh = new PDO ( "mysql:host=$hostname;dbname=$dbname", $_SESSION ['usuario'], $_SESSION ['clave'] );
	$dbh->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$dbh->beginTransaction();
	//echo $sqlFinalizar;
	$dbh->exec ($sqlFinalizar);
	$dbh->commit();
	$pagina = "listadoCorrecciones.php?origen=$origen";
	Header ( "Location: $pagina" );
} catch ( PDOException $e ) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}

?>

