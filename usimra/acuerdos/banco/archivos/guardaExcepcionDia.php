<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
$fechaExceptuar = explode("-",$_POST['fecha']);
$ano = $fechaExceptuar[2];
$mes = $fechaExceptuar[1];
$dia = $fechaExceptuar[0];
$motivo = $_POST['motivo'];
$fechaModif = date("Y-m-d H:i:s");
$usuarioModif = $_SESSION['usuario'];

$sqlUpdateDia = "UPDATE diasbancousimra SET exceptuado = 1, observacion = '$motivo', fechamodificacion = '$fechaModif', usuariomodificacion = '$usuarioModif' WHERE ano = $ano and mes = $mes and dia = $dia";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	//print($sqlUpdateDia);
	$dbh->exec($sqlUpdateDia);
	$dbh->commit();
	$pagina = "procesamientoArchivos.php";
	Header("Location: $pagina"); 
} catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/usimra/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}	

?>