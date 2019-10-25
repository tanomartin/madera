<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php");

$nombre = $_POST['nombre'];
$emisor = $_POST['emisor'];
$fechaemision = "'".fechaParaGuardar($_POST['fechaemision'])."'";
$fechainicio = "'".fechaParaGuardar($_POST['fechainicio'])."'";
$fechafin = "NULL";
if ($_POST['fechafin'] != "") {
	$fechafin = "'".fechaParaGuardar($_POST['fechafin'])."'";
}
$obs = $_POST['obs'];
$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];

$sqlInsertCabecera = "INSERT INTO nomencladoresresolucion 
						VALUES(DEFAULT, 7, '$nombre', '$emisor', $fechaemision, NULL,$fechainicio, $fechafin, '$obs', '$fecharegistro', '$usuarioregistro', NULL, NULL)";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//echo $sqlInsertCabecera;
	$dbh->exec($sqlInsertCabecera);
	$lastId = $dbh->lastInsertId();
	
	$dbh->commit();
	$pagina = "detalleResolucion.php?id=$lastId";
	Header("Location: $pagina");
} catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}

?>

