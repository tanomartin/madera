<?php
$libPath = $_SERVER ['DOCUMENT_ROOT'] . "/madera/lib/";
include ($libPath . "controlSessionOspimSistemas.php");

$id = $_POST['id'];
$email = $_POST ['email'];
$password = $_POST ['password'];
$idusuario = $_POST ['usuario'];

$fechamodificacion = date ( "Y-m-d H:i:s" );
$usuariomodificacion = $_SESSION ['usuario'];

$sqlModif = "UPDATE emails SET email = '$email', password = '$password', idusuario = '$idusuario', fechamodificacion = '$fechamodificacion', usuariomodificacion = '$usuariomodificacion' where id = $id";

try {
	$hostname = $_SESSION ['host'];
	$dbname = $_SESSION ['dbname'];
	$dbh = new PDO ( "mysql:host=$hostname;dbname=$dbname", $_SESSION ['usuario'], $_SESSION ['clave'] );
	$dbh->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$dbh->beginTransaction ();
	$dbh->exec ( $sqlModif );
	$dbh->commit ();
	$pagina = "emails.php";
	Header ( "Location: $pagina" );
} catch ( PDOException $e ) {
	echo $e->getMessage ();
	$dbh->rollback ();
}

?>