<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."fechas.php"); 

var_dump($_POST);
$nombreInsu = $_POST['nombre'];
$nroserie = $_POST['nroserie'];
$descrip = $_POST['descrip'];
$ptoPedido = $_POST['ptoPedido'];
$stockmin = $_POST['stockmin'];
$ptopromedio = $_POST['ptoPromedio'];
$fechamodificacion = date("Y-m-d H:i:s");
$usuariomodif = $_SESSION['usuario'];

$sqlInsertInsumos = "INSERT INTO stockinsumo VALUE (DEFAULT, '$nombreInsu','$nroserie','$descrip',$ptoPedido,$stockmin,$ptopromedio)";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	//print($sqlInsertInsumos."<br>");
	$dbh->exec($sqlInsertInsumos);
	$idInsumo = $dbh->lastInsertId('id'); 
	$sqlInsertStock = "INSERT INTO stock VALUE ($idInsumo,0,'$fechamodificacion','$usuariomodif')";
	//print($sqlInsertStock."<br>");
	$dbh->exec($sqlInsertStock);
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