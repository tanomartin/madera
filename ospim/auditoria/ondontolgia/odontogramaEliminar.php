<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$nroafil = $_GET['nroafil'];
$nroorden = $_GET['nroorden'];
$id = $_GET['id'];
$delteOdonto = "DELETE FROM odontograma WHERE id = $id and nroafiliado = $nroafil and nroorden = $nroorden";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//print($delteOdonto."<br>");
	$dbh->exec($delteOdonto);

	$dbh->commit();
	$pagina = "odontograma.php?nroafil=$nroafil&nroorden=$nroorden&tipo=A";
	Header("Location: $pagina");

}catch (PDOException $e) {
	$error = "Cod. Error: ".$e->getCode()." - Linea: ".$e->getLine();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	Header($redire);
	exit(0);
}

