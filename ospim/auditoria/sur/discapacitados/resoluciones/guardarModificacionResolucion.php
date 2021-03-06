<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php");

$id = $_GET['id'];
$nombre = $_POST['nombre'];
$emisor = $_POST['emisor'];
$fechaemision = fechaParaGuardar($_POST['fechaemision']);
$fechainicio = fechaParaGuardar($_POST['fechainicio']);
$fechafin = "NULL";
if ($_POST['fechafin'] != "") {
	$fechafin = "'".fechaParaGuardar($_POST['fechafin'])."'";
}
$obs = $_POST['obs'];
$fechamodif = date("Y-m-d H:i:s");
$usuariomodif = $_SESSION['usuario'];

$sqlUpdateCabecera = "UPDATE nomencladoresresolucion 
						SET nombre = '$nombre', 
							emisor = '$emisor', 
							fechaemision = '$fechaemision', 
							fechainicio = '$fechainicio',
							fechafin = $fechafin,
							observacion = '$obs', 
							fechamodificacion = '$fechamodif',
							usuariomodificacion = '$usuariomodif'
						WHERE id = $id";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//echo $sqlUpdateCabecera;
	$dbh->exec($sqlUpdateCabecera);
	
	$dbh->commit();
	$pagina = "detalleResolucion.php?id=$id";
	Header("Location: $pagina");
} catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}

?>

