<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
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
$fecmod= date("Y-m-d H:i:s");
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
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/usimra/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}
?>