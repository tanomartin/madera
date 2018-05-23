<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$codigo = $_POST['codigo'];
$cbu = "NULL";
if ($_POST['cbu'] != "") {
	$cbu = "'".$_POST['cbu']."'";
}
$banco = "NULL";
if ($_POST['banco'] != "") {
	$banco = "'".$_POST['banco']."'";
}
$cuenta = "NULL";
if ($_POST['cuenta'] != "") {
	$cuenta = "'".$_POST['cuenta']."'";
}
$interbanking = $_POST['inter'];
$fechamodificacion = date("Y-m-d H:i:s");
$usuariomodificacion = $_SESSION['usuario'];

$updateAuxiliares = "UPDATE prestadoresauxiliar SET cbu = $cbu, banco = $banco, cuenta = $cuenta, interbanking = $interbanking, fechamodificacion = '$fechamodificacion', usuariomodificacion = '$usuariomodificacion' WHERE codigoprestador = $codigo";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//echo $updateAuxiliares."<br>";
	$dbh->exec($updateAuxiliares);

	$dbh->commit();
	$pagina = "abmPrestadores.php?codigo=$codigo";
	Header("Location: $pagina");

}catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}

?>	