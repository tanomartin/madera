<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");

$fecha = fechaParaGuardar($_POST['fecha']);
$importeTotal = $_POST['monto'];
$codigoprestador = $_POST['codigoprestador'];
$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];
$sqlInsertCabecera = "INSERT INTO ordennmcabecera VALUES(DEFAULT,'$fecha',$importeTotal,$codigoprestador,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'$fecharegistro','$usuarioregistro')";


$lineas = $_POST['conceptoaver'];
$arrayConcepto = array();
$arrayImputacion = array();
for($i=1; $i<=$lineas; $i++) {
	$conceptoNombre = "concepto".$i;
	$concepto = $_POST[$conceptoNombre];
	$tipoNombre = "tipo".$i;
	$tipo = $_POST[$tipoNombre];
	$importeLineaNombre = "importe".$i;
	$importeLinea = $_POST[$importeLineaNombre];
	
	$sqlInsertConcepto = "INSERT INTO ordennmdetalle VALUES(nroorden, $i, '$concepto', '$tipo', $importeLinea)";
	$arrayConcepto[$i] = $sqlInsertConcepto;
}

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	print($sqlInsertCabecera."<br>");
	$dbh->exec($sqlInsertCabecera);
	$lastId = $dbh->lastInsertId();

	foreach ($arrayConcepto as $sqlconcepto) {
		$sqlconcepto = str_replace("nroorden", $lastId, $sqlconcepto);
		print($sqlconcepto."<br>");
		$dbh->exec($sqlconcepto);
	}

	$dbh->commit();
	$pagina = "imputaOrdenPagoNM.php?nroorden=$lastId";
	Header("Location: $pagina");
} catch (PDOException $e) {
	$error = $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	Header($redire);
	exit(0);
}

?>