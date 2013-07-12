<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php");

$datos = array_values($_POST);

//echo $datos[0];
//echo $datos[1];
$cuenta=$datos[1];
//echo "CUENTA: "; echo $cuenta; echo "<br>";
//echo $datos[2];
$feccar=$datos[2];
$fecemi=substr($datos[2], 6, 4).substr($datos[2], 3, 2).substr($datos[2], 0, 2);
//echo "FECHA EMISION: "; echo $fecemi; echo "<br>";
//echo $datos[3];
$orden=$datos[3];
//echo "NRO ORDEN: "; echo $orden; echo "<br>";
//echo $datos[4];
$fecimp=substr($datos[4], 6, 4).substr($datos[4], 3, 2).substr($datos[4], 0, 2);
//echo "FECHA IMPUTACION: "; echo $fecimp; echo "<br>";
//echo $datos[5];
$impimp=$datos[5];
//echo "IMPORTE IMPUTADO: "; echo $impimp; echo "<br>";
//echo $datos[6];
$tipimp=$datos[6];
//echo "TIPO IMPUTACION: "; echo $tipimp; echo "<br>";
$fecmod= date("Y-m-d H:m:s");
//echo "FECHA MODIFICACION: "; echo $fecmod; echo "<br>";
$usumod=$_SESSION['usuario'];
//echo "USUARIO MODIFICACION: "; echo $usumod; echo "<br>";

//conexion y creacion de transaccion.
try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	//echo "$hostname"; echo "<br>";
	//echo "$dbname"; echo "<br>";
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	//echo 'Connected to database<br/>';
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	$sqlActualizaResumen="UPDATE resumenusimra SET fechaimputacion = :fechaimputacion, importeimputado = :importeimputado, tipoimputacion = :tipoimputacion, fechamodificacion = :fechamodificacion, usuariomodificacion = :usuariomodificacion WHERE codigocuenta = :codigocuenta and fechaemision = :fechaemision and nroordenimputacion = :nroordenimputacion";
	//echo $sqlAddResumen; echo "<br>";
	$resultActualizaResumen = $dbh->prepare($sqlActualizaResumen);
	if($resultActualizaResumen->execute(array(':codigocuenta' => $cuenta, ':fechaemision' => $fecemi, ':nroordenimputacion' => $orden, ':fechaimputacion' => $fecimp, ':importeimputado' => $impimp, ':tipoimputacion' => $tipimp, ':fechamodificacion' => $fecmod, ':usuariomodificacion' => $usumod)))
	
	$dbh->commit();
	$pagina = "listarImputaciones.php?ctaResumen=$cuenta&fecEmision=$feccar";
	Header("Location: $pagina"); 
}
catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Banco USIMRA :.</title></head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo1 {
	font-family: Arial, Helvetica, sans-serif;
	font-style: italic;
	font-weight: bold;
}
</style>
<body bgcolor="#B2A274">
</body>
</html>