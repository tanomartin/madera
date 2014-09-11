<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php"); 
$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];

$codigo = $_GET['codigo'];
$datos = array_slice($_POST, 0,sizeof($_POST)-1);
//var_dump($datos);
$datos = array_values($datos);
//var_dump($datos);
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
		$sqlInsertPractica = "INSERT INTO practicaprestador VALUES($codigo,'$codigopractica',$nomenclador,$valor,'$fecharegistro','$usuarioregistro')";
		//print($sqlInsertPractica."<br>");
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