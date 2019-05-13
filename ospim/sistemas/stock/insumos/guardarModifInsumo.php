<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."fechas.php"); 

//var_dump($_POST);
$id = $_POST['idInsumo'];
$nombreInsu = $_POST['nombre'];
$nroserie = $_POST['nroserie'];
$descrip = $_POST['descrip'];
$ptoPedido = $_POST['ptoPedido'];
$stockmin = $_POST['stockmin'];
$ptopromedio = $_POST['ptoPromedio'];

$sqlUpdateInsumo = "UPDATE stockinsumo SET nombre = '$nombreInsu', numeroserie = '$nroserie', descripcion = '$descrip', puntopedido = $ptoPedido, stockminimo = $stockmin, puntopromedio = $ptopromedio WHERE id = $id";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//print($sqlUpdateInsumo."<br>");
	$dbh->exec($sqlUpdateInsumo);
	$dbh->commit();
	
	$pagina = "insumos.php";
	Header("Location: $pagina"); 
	
}catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}

?>