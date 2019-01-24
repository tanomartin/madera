<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$whereIn = "(";
foreach ($_POST as $ordenes) {
	$whereIn .= $ordenes.",";
}
$whereIn = substr($whereIn, 0, -1);
$whereIn .= ")";

$sqlOrdenesCabecera = "SELECT o.*, b.dirigidoa, c.nrocta
						FROM ordennmcabecera o, prestadoresnm b, cuentasospim c
						WHERE o.nroorden in $whereIn and
							  o.codigoprestador = b.codigo and
							  o.idcuenta = c.id";
$resOrdenesCabecera = mysql_query($sqlOrdenesCabecera,$db);
$arrayLineas = array();
$arrayOrdenes = array();
while ($rowOrdenesCabecera = mysql_fetch_assoc($resOrdenesCabecera)) {
	$tipolinea = "C";
	$index = $rowOrdenesCabecera['nroorden'].$tipolinea;
	$fecha = date("dmy", strtotime($rowOrdenesCabecera['fecha'])); 
	$nroorden = str_pad($rowOrdenesCabecera['nroorden'],6,"0",STR_PAD_LEFT);
	$benefici = str_pad($rowOrdenesCabecera['dirigidoa'],33," ",STR_PAD_LEFT);
	$total = $rowOrdenesCabecera['importe'] * 100;
	$total = str_pad($total,15,"0",STR_PAD_LEFT);
	$arrayOrdenes[$index] = $rowOrdenesCabecera;
	$arrayLineas[$index] = $tipolinea.$fecha.$nroorden.$benefici.$total;

	$tipolinea = "D00";
	$index = $rowOrdenesCabecera['nroorden'].$tipolinea;
	$nrocuenta = str_pad($rowOrdenesCabecera['nrocta'],9,"0",STR_PAD_LEFT);
	
	$tipo = "CH";
	if ($rowOrdenesCabecera['tipopago'] == "T") {
		$tipo = "TR";
	}
	$nropago = str_pad($rowOrdenesCabecera['nropago'],8,"0",STR_PAD_LEFT);
	$nroafil = str_pad("",8," ",STR_PAD_LEFT);
	$arrayLineas[$index] = "D".$fecha.$nroorden.$nrocuenta."-".$total.$tipo.$nropago.$nroafil;
}

$sqlOrdenesDetalle = "SELECT d.*, i.imputacion, i.importe, i.nroafiliado , i.nroordenfami, c.nrocta
						FROM ordennmdetalle d, ordennmimputacion i, cuentasospim c
						WHERE d.nroorden in $whereIn and 
							  d.nroorden = i.nroorden and 
							  d.concepto = i.concepto and 
							  i.idcuenta = c.id";
$resOrdenesDetalle = mysql_query($sqlOrdenesDetalle,$db);
while ($rowOrdenesDetalle = mysql_fetch_assoc($resOrdenesDetalle)) {
	$tipolinea = "D".$rowOrdenesDetalle['concepto'].$rowOrdenesDetalle['imputacion'];
	$index = $rowOrdenesDetalle['nroorden'].$tipolinea;
	$indexBusqueda = $rowOrdenesDetalle['nroorden']."C";
	
	$fecha = date("dmy", strtotime($arrayOrdenes[$indexBusqueda]['fecha']));
	$nroorden = str_pad($rowOrdenesDetalle['nroorden'],6,"0",STR_PAD_LEFT);
	$nrocuenta = str_pad($rowOrdenesDetalle['nrocta'],9,"0",STR_PAD_LEFT);
	
	$signo = " ";
	if ($rowOrdenesDetalle['tipo'] == "D") {
		$signo = "-";
	}
	$importe = $rowOrdenesDetalle['importe'] * 100;
	$importe = str_pad($importe,15,"0",STR_PAD_LEFT);
	
	$tipo = "CH";
	if ($arrayOrdenes[$indexBusqueda]['tipopago'] == "T") {
		$tipo = "TR";
	}
	$nropago = str_pad($arrayOrdenes[$indexBusqueda]['nropago'],8,"0",STR_PAD_LEFT);
	$nroafil = $rowOrdenesDetalle['nroafiliado'].$rowOrdenesDetalle['nroordenfami'];
	$nroafil = str_pad($nroafil,8," ",STR_PAD_LEFT);
	
	$arrayLineas[$index] = "D".$fecha.$nroorden.$nrocuenta.$signo.$importe.$tipo.$nropago.$nroafil;
}

$maquina = $_SERVER['SERVER_NAME'];
if(strcmp("localhost",$maquina)==0)
	$carpetaArchivo="archivos/";
else
	$carpetaArchivo="/home/sistemas/Documentos/Repositorio/OrdenesPagoNMMigra/";

$sqlGetProxNroMigra = "SELECT nroarchivomigra FROM ordennmcabecera ORDER BY nroarchivomigra DESC LIMIT 1";
$resGetProxNroMigra = mysql_query($sqlGetProxNroMigra,$db);
$rowGetProxNroMigra = mysql_fetch_assoc($resGetProxNroMigra);
$nroMigra = $rowGetProxNroMigra['nroarchivomigra'] + 1;	
$nroMigraArch = str_pad($nroMigra,5,"0",STR_PAD_LEFT);

$nombreArchivo = $nroMigraArch."migraorden.txt";
$archivo = $carpetaArchivo.$nombreArchivo;
ksort($arrayLineas);
if($archivo = fopen($archivo, "w")) {		
	foreach ($arrayLineas as $key => $linea) {
		//echo $linea."<br>";
		fwrite($archivo, $linea."\n");
	}
	fclose($archivo);
	
	$today = date("Y-m-d");
	$updateFechaMigra = "UPDATE ordennmcabecera SET fechamigracion = '$today', nroarchivomigra = $nroMigra WHERE nroorden in $whereIn";
	
	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();
		
		//echo $updateFechaMigra."<br>";
		$dbh->exec($updateFechaMigra);
	
		$dbh->commit();
		$pagina = "listadoOrdenesMigrar.php";
		Header("Location: $pagina");
	} catch (PDOException $e) {
		$error = $e->getMessage();
		$dbh->rollback();
		$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
		Header($redire);
		exit(0);
	}
}
?>