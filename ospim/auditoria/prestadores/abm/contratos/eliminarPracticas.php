<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$codigo = $_GET['codigo'];
$idcontrato = $_GET['idcontrato'];
$datos = array_values($_POST);
$datos = array_slice ($datos, 0,sizeof($datos)-1);
try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	foreach ($datos as $codigopractica) {
		$sqlDeletePractica = "DELETE FROM detcontratoprestador WHERE idcontrato = $idcontrato and codigopractica = '$codigopractica'";
		//print($sqlDeletePeriodo."<br>");
		$dbh->exec($sqlDeletePractica);
	}
	$dbh->commit();
	$pagina = "modificarPracticasContrato.php?codigo=$codigo&idcontrato=$idcontrato";
	Header("Location: $pagina"); 
} catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}

?>