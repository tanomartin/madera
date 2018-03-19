<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php");

$nombre = $_POST['nombre'];
$emisor = $_POST['emisor'];
$fecha = fechaParaGuardar($_POST['fecha']);
$obs = $_POST['obs'];
$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];

$sqlInsertCabecera = "INSERT INTO resolucioncabecera VALUES(DEFAULT, '$nombre', '$emisor', '$fecha', '$obs', '$fecharegistro', '$usuarioregistro', NULL, NULL)";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	echo $sqlInsertCabecera;
	$dbh->exec($sqlInsertCabecera);
	$lastId = $dbh->lastInsertId();
	
	$dbh->commit();
	$pagina = "detalleResolucion.php?id=$lastId";
	Header("Location: $pagina");
} catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}

?>

