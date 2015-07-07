<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."fechas.php"); 

$idInsumo = $_GET['idInsumo'];
$usuario = $_GET['usuario'];
$stock = $_GET['stock'];
$fechaconsumo = date("Y-m-d H:m:s");
$usuariomodif = $_SESSION['usuario'];
$nuevoStock = $stock - 1;

$sqlInsertConsumo = "INSERT INTO consumoinsumo VALUE(DEFAULT, $idInsumo, '$usuario', '$fechaconsumo')";
$sqlUpdateStock = "UPDATE stock SET cantidad = $nuevoStock, fechamodificacion = '$fechaconsumo', usuariomodificacion = '$usuariomodif'  WHERE id = $idInsumo";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	//print($sqlInsertConsumo."<br>");
	$dbh->exec($sqlInsertConsumo);
	//print($sqlUpdateStock."<br>");
	$dbh->exec($sqlUpdateStock);
	$dbh->commit();
	
	$pagina = "stock.php";
	Header("Location: $pagina"); 
	
}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}
?>