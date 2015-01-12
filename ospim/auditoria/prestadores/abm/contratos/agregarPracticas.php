<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php"); 
$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];

var_dump($_POST);
$codigo = $_GET['codigo'];
$idcontrato = $_GET['idcontrato'];
$datos = array();
$i=0;
$arranca = 0;
while ($dato = current($_POST)) {
	$key = key($_POST);
	print($key." -> ".$dato."<br>");
	if ($arranca == 0) {
		if (strcmp($key, "arranca") !== 0) {
			next($_POST);
		} else {
			next($_POST);
			$arranca = 1;
		}
	} else {
		if (strcmp($key, "agregar") !== 0) {
			$datos[$i] = $dato;
			$i++;
			next($_POST);
		} else {
			next($_POST);
		}
	}
}
var_dump($datos);

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	for ($i = 0; $i < sizeof($datos); $i++) {
		$nomenclador = $datos[$i];
		$i++;
		if ($nomenclador == 1) {
			$valor = "0.00";
			$codigopractica = $datos[$i];
		}
		if ($nomenclador == 2) {
			$valor = number_format($datos[$i],2,'.','');
			$i++;
			$codigopractica = $datos[$i];
		}
		$sqlInsertPractica = "INSERT INTO detcontratoprestador VALUES($idcontrato,'$codigopractica',$nomenclador,$valor,'$fecharegistro','$usuarioregistro')";
		//print($sqlInsertPractica."<br>");
		$dbh->exec($sqlInsertPractica);
	}
	$dbh->commit();
	$pagina = "modificarPracticasContrato.php?codigo=$codigo&idcontrato=$idcontrato";
	Header("Location: $pagina"); 
} catch (PDOException $e) {
	$dbh->rollback();
	$error = $e->getMessage();
	if (stripos($error,'Integrity constraint violation: 1062 Entrada duplicada') !== FALSE ) {
		$pagina = "modificarContrato.php?codigo=$codigo&error=1";
		Header("Location: $pagina"); 
	} else {
		echo $error;
	}
	
}

?>