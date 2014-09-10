<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php"); 
$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];

$codigo = $_GET['codigo'];
$datos = array_values($_POST);
var_dump($datos);
$datos = array_slice ($datos, 0,sizeof($datos)-1);
var_dump($datos);
try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	foreach ($datos as $codigopractica) {
		$sqlInsertPractica = "INSERT INTO practicaprestador VALUES($codigo,'$codigopractica','$fecharegistro','$usuarioregistro')";
		//print($sqlDeletePeriodo."<br>");
		$dbh->exec($sqlInsertPractica);
	}
	$dbh->commit();
	$pagina = "modificarContrato.php?codigo=$codigo";
	Header("Location: $pagina"); 
} catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}

?>