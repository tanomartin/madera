<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 

$poliza = $_GET['poliza'];

$sqlUpdatePoliza = "UPDATE stockproducto SET numeropoliza = '$poliza'  WHERE activo = 1";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	//print($sqlUpdatePoliza."<br>");
	$dbh->exec($sqlUpdatePoliza);
	$dbh->commit();
	
	$pagina = "moduloSeguro.php";
	Header("Location: $pagina"); 
	
}catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}
?>