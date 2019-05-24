<?php

$libPath = $_SERVER ['DOCUMENT_ROOT'] . "/madera/lib/";
include ($libPath . "controlSessionOspimSistemas.php");
$id = $_GET ['id'];

$sqlDelete = "DELETE FROM usuarios WHERE id = ".$id;
$sqlUpdateMail = "UPDATE emails SET idusuario = 0 WHERE idusuario = ".$id;
$sqlUpdateUbicacion = "UPDATE stockubicacionproducto SET idusuario = 0 WHERE idusuario = ".$id;

try {
	$hostname = $_SESSION ['host'];
	$dbname = $_SESSION ['dbname'];
	$dbh = new PDO ( "mysql:host=$hostname;dbname=$dbname", $_SESSION ['usuario'], $_SESSION ['clave'] );
	$dbh->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$dbh->beginTransaction ();
	$dbh->exec ( $sqlUpdateUbicacion );
	$dbh->exec ( $sqlUpdateMail );
	$dbh->exec ( $sqlDelete );
	$dbh->commit ();
	$pagina = "usuarios.php";
	Header ( "Location: $pagina" );
} catch ( PDOException $e ) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}

?>