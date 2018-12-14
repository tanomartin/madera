<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");

$fecha = fechaParaGuardar($_POST['fecha']);
$importeTotal = $_POST['monto'];
$beneficiario = $_POST['beneficiario'];
$tipoPago = $_POST['tipo'];
$nroPago = $_POST['nropago'];
$fechageneracion = date("Y-m-d");
$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];
$sqlInsertCabecera = "INSERT INTO ordennmcabecera VALUES(DEFAULT,'$fecha',$importeTotal,'$beneficiario','$tipoPago','$nroPago','$fechageneracion',NULL,NULL,'$fecharegistro','$usuarioregistro')";


$lineas = $_POST['conceptoaver'];
$arrayConcepto = array();
$arrayImputacion = array();
for($i=1; $i<=$lineas; $i++) {
	$conceptoNombre = "concepto".$i;
	$concepto = $_POST[$conceptoNombre];
	$importeLineaNombre = "importe".$i;
	$importeLinea = $_POST[$importeLineaNombre];
	
	$sqlInsertConcepto = "INSERT INTO ordennmdetalle VALUES(nroorden, $i, '$concepto', $importeLinea)";
	$arrayConcepto[$i] = $sqlInsertConcepto;
	
	$cantImputaNombre = "imputaaver".$i;
	$cantImputa = $_POST[$cantImputaNombre];

	for($n=1; $n<=$cantImputa; $n++) {
		$imputaCuentaNombre = "impucuenta".$i."-".$n;
		$imputaCuenta = $_POST[$imputaCuentaNombre];
		
		$imputaSaldoNombre = "impusaldo".$i."-".$n;
		$imputaSaldo = $_POST[$imputaSaldoNombre];
		
		$selectDBNombre = "impudc".$i."-".$n;
		$selectDB = $_POST[$selectDBNombre];
		
		$sqlInsertImputacion = "INSERT INTO ordennmimputacion VALUES(nroorden, $i, $n, '$imputaCuenta', $imputaSaldo, '$selectDB')";
		$arrayImputacion[$i."-".$n] = $sqlInsertImputacion;
	}
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

	foreach ($arrayImputacion as $sqlimputacion) {
		$sqlimputacion = str_replace("nroorden", $lastId, $sqlimputacion);
		print($sqlimputacion."<br>");
		$dbh->exec($sqlimputacion);
	}

	$dbh->commit();
	$pagina = "documentoOrdenPagoNM.php?nroorden=$lastId";
	Header("Location: $pagina");
} catch (PDOException $e) {
	$error = $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	Header($redire);
	exit(0);
}

?>