<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php");
$sqlDeleteRela = "DELETE FROM modulosdptos";
$sqlInsertRela = "INSERT INTO modulosdptos VALUES";
foreach ($_POST as $datos) {
 	$arrayDatos = explode("-",$datos);
 	$idDpto = intval($arrayDatos[0]);
 	$idModu = intval($arrayDatos[1]);
 	$sqlInsertRela .= "(".$idModu.",".$idDpto."),";
}
$sqlInsertRela = substr($sqlInsertRela, 0, -1);
$sqlInsertRela .= ";";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//echo ($sqlDeleteRela."<br>");
	$dbh->exec($sqlDeleteRela);
	//echo ($sqlInsertRela."<br>");
	$dbh->exec($sqlInsertRela);

	$dbh->commit();
	$pagina = "configuracion.php";
	Header("Location: $pagina");
}catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/".$_GET['origen']."/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	Header($redire);
	exit(0);
}

?>