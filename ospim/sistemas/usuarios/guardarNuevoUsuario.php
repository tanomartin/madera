<?php

$libPath = $_SERVER ['DOCUMENT_ROOT'] . "/madera/lib/";
include ($libPath . "controlSessionOspimSistemas.php");

$nombre = $_POST ['nombre'];
$depto = $_POST ['depto'];
$nombrepc = $_POST ['nombrepc'];
$usuariowin = $_POST ['usuariowin'];
$passwin = $_POST ['passwin'];
$usuariosistema = $_POST ['usuariosistema'];
$passsistema = $_POST ['passsistema'];
$puerto = $_POST ['puerto'];
$conector = $_POST ['conector'];

$fecharegistro = date ( "Y-m-d H:i:s" );
$usuarioregistro = $_SESSION ['usuario'];

$sqlInsert = "INSERT INTO usuarios VALUE (DEFAULT,$depto,'$nombre','$nombrepc','$usuariowin','$passwin','$usuariosistema','$passsistema','$puerto','$conector','$fecharegistro','$usuarioregistro')";

try {
	$hostname = $_SESSION ['host'];
	$dbname = $_SESSION ['dbname'];
	$dbh = new PDO ( "mysql:host=$hostname;dbname=$dbname", $_SESSION ['usuario'], $_SESSION ['clave'] );
	$dbh->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$dbh->beginTransaction ();
	$dbh->exec ( $sqlInsert );
	$dbh->commit ();
	$pagina = "usuarios.php";
	Header ( "Location: $pagina" );
} catch ( PDOException $e ) {
	echo $e->getMessage ();
	$dbh->rollback ();
}

?>