<?php

$libPath = $_SERVER ['DOCUMENT_ROOT'] . "/madera/lib/";
include ($libPath . "controlSessionOspimSistemas.php");
$id = $_GET ['id'];

$sqlDelete = "DELETE FROM emails WHERE id = ".$id;

try {
	$hostname = $_SESSION ['host'];
	$dbname = $_SESSION ['dbname'];
	$dbh = new PDO ( "mysql:host=$hostname;dbname=$dbname", $_SESSION ['usuario'], $_SESSION ['clave'] );
	$dbh->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$dbh->beginTransaction ();
	$dbh->exec ( $sqlDelete );
	$dbh->commit ();
	$pagina = "emails.php";
	Header ( "Location: $pagina" );
} catch ( PDOException $e ) {
	echo $e->getMessage ();
	$dbh->rollback ();
}

?>