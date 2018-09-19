<?php

$libPath = $_SERVER ['DOCUMENT_ROOT'] . "/madera/lib/";
include ($libPath . "controlSessionOspimSistemas.php");

$email = $_POST ['email'];
$password = $_POST ['password'];
$idUsuario = $_POST ['usuario'];

$fecharegistro = date ( "Y-m-d H:i:s" );
$usuarioregistro = $_SESSION ['usuario'];

$sqlInsert = "INSERT INTO emails VALUE (DEFAULT,'$email','$password',$idUsuario,'$fecharegistro','$usuarioregistro')";

try {
	$hostname = $_SESSION ['host'];
	$dbname = $_SESSION ['dbname'];
	$dbh = new PDO ( "mysql:host=$hostname;dbname=$dbname", $_SESSION ['usuario'], $_SESSION ['clave'] );
	$dbh->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$dbh->beginTransaction ();
	$dbh->exec ( $sqlInsert );
	$dbh->commit ();
	$pagina = "emails.php";
	Header ( "Location: $pagina" );
} catch ( PDOException $e ) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}

?>