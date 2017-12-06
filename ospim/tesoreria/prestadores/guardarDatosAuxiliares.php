<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$codigo = $_POST['codigo'];
$cuit = $_POST['cuit'];
$cbu = NULL;
if ($_POST['cbu'] != "") {
	$cbu = $_POST['cbu'];
}
$banco = NULL;
if ($_POST['banco'] != "") {
	$banco = $_POST['banco'];
}
$cuenta = NULL;
if ($_POST['cuenta'] != "") {
	$cuenta = $_POST['cuenta'];
}

$updateAuxiliares = "UPDATE prestadoresauxiliar SET cbu = '$cbu', banco = '$banco', cuenta = '$cuenta' WHERE cuit = $cuit";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//echo $updateAuxiliares."<br>";
	$dbh->exec($updateAuxiliares);

	$dbh->commit();
	$pagina = "moduloPrestadores.php?codigo=$codigo";
	Header("Location: $pagina");

}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}

?>	