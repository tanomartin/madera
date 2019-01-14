<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

//var_dump($_POST);
$nroorden = $_POST['nroorden'];
$codigocuentapago = $_POST['codigocuentapago'];
$fechaimputacion = date("Y-m-d");
if (isset($_POST['nropago']) && isset($_POST['tipo'])) {
	$tipo = $_POST['tipo'];
	$nropago = $_POST['nropago'];
	$updateDatosPago = "UPDATE ordennmcabecera SET tipopago = '$tipo', nropago = '$nropago', idcuenta = $codigocuentapago, fechaimputacion = '$fechaimputacion' WHERE nroorden = $nroorden";
} else {
	$updateDatosPago = "UPDATE ordennmcabecera SET idcuenta = $codigocuentapago, fechaimputacion = '$fechaimputacion' WHERE nroorden = $nroorden";
}

$lineas = $_POST['conceptoaver'];
$arrayImputacion = array();
for($i=1; $i<=$lineas; $i++) {
	$cantImputaNombre = "imputaaver".$i;
	$cantImputa = $_POST[$cantImputaNombre];
	for($n=1; $n<=$cantImputa; $n++) {
		$imputaCuentaNombre = "idimpucuenta".$i."-".$n;
		$imputaCuenta = $_POST[$imputaCuentaNombre];

		$imputaSaldoNombre = "impusaldo".$i."-".$n;
		$imputaSaldo = $_POST[$imputaSaldoNombre];
		
		$imputaAfilNombre = "nroafil".$i."-".$n;
		if (isset($_POST[$imputaAfilNombre])) {
			$nroafiliadotipo = $_POST[$imputaAfilNombre];
			$arrayNroAfiltipo = explode("-",$nroafiliadotipo);
			$nroafil = $arrayNroAfiltipo[0];
			$nroordenfami = $arrayNroAfiltipo[1];
			$sqlInsertImputacion = "INSERT INTO ordennmimputacion VALUES($nroorden, $i, $n,  $imputaCuenta, $nroafil, $nroordenfami, $imputaSaldo)";
		} else {
			$sqlInsertImputacion = "INSERT INTO ordennmimputacion VALUES($nroorden, $i, $n,  $imputaCuenta, NULL, NULL, $imputaSaldo)";
		}	
		$arrayImputacion[$i."-".$n] = $sqlInsertImputacion;
	}
}

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//print($updateDatosPago."<br>");
	$dbh->exec($updateDatosPago);
	foreach ($arrayImputacion as $sqlimputacion) {
		//print($sqlimputacion."<br>");
		$dbh->exec($sqlimputacion);
	}

	$dbh->commit();
	$pagina = "verOrdenPagoNM.php?nroorden=$nroorden";
	Header("Location: $pagina");
} catch (PDOException $e) {
	$error = $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	Header($redire);
	exit(0);
}

?>