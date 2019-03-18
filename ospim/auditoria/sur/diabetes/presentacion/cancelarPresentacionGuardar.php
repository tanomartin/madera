<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");

$id = $_POST['id'];
$fechamodif = date("Y-m-d H:i:s");
$usuariomodif = $_SESSION['usuario'];
$fecha = fechaParaGuardar($_POST['fecha']);
$motivo = strtoupper(trim($_POST['motivo']));
$cancelarPresentacion = "UPDATE diabetespresentacion 
							SET fechacancelacion = '$fecha', motivocancelacion = '$motivo',
							    fechamodificacion = '$fechamodif', usuariomodificacion = '$usuariomodif'
							WHERE id = $id";
try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//echo $cancelarPresentacion."<br><br>";
	$dbh->exec($cancelarPresentacion);

	$dbh->commit();
	$redire = "moduloPresSSS.php";
	Header("Location: $redire");
	
} catch (PDOException $e) {
	$error = "Cod. Error: ".$e->getCode()." - Linea: ".$e->getLine();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	Header($redire);
	exit(0);
}

?>