<?php $libPath = $_SERVER ['DOCUMENT_ROOT'] . "/madera/lib/";
include($libPath."controlSessionOspim.php");

$cuit = $_GET['cuit'];
$nroacu = $_GET['nroacu'];
$fechamodificacion = date("Y-m-d H:i:s");;
$usuariomodificacion = $_SESSION['usuario'];

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	
	$sqlUpdateEstado = "UPDATE cabacuerdosospim SET estadoacuerdo = 2, fechamodificacion = '$fechamodificacion', usuariomodificacion = '$usuariomodificacion' WHERE cuit = $cuit and nroacuerdo = $nroacu";
	
	//echo $sqlUpdateEstado;
	$dbh->exec($sqlUpdateEstado);
	$dbh->commit();
	
	$pagina = "consultaAcuerdo.php?cuit=$cuit&nroacu=$nroacu";
	Header("Location: $pagina");
	
} catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}
	

?>