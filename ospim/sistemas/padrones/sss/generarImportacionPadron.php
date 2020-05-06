<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
set_time_limit(0);
ini_set('mysql.allow_local_infile', 1);

$anio = $_POST['anio'];
$mes = $_POST['mes'];
$fechaproceso = date("Y-m-d");
$usuarioproceso = $_SESSION['usuario'];
$pathArchivo = $_FILES['archivo']['tmp_name'];

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];	
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave'],array(PDO::MYSQL_ATTR_LOCAL_INFILE => true));
	$dbh->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$sqlLoadData = "LOAD DATA LOCAL INFILE '$pathArchivo' INTO TABLE padronsss
						FIELDS TERMINATED BY '|'
						LINES TERMINATED BY '\n'
						(codigornos, cuit, cuiltitular, parentesco, cuilfamiliar,
						tipodocumento, nrodocumento, apellidoynombre, sexo, estadocivil, @var1,
						nacionalidad, calledomicilio, puertadomicilio, pisodomicilio, deptodomicilio,
						localidad, codigopostal, codprovin, tipodomicilio, telefono, situacionrevista,
						incapacidad, tipotitular, @var2, @var3, verificacioncuil, cuilinformadoos, tipotitularsijp,
						cuitsijp, ossijp, periodosijp, osopcion, periodoopcion)
						set
						fechanacimiento = STR_TO_DATE(@var1,'%d%m%Y'),
						fechaaltaos = STR_TO_DATE(@var2,'%d%m%Y'),
						fechapresentacion = STR_TO_DATE(@var3,'%d%m%Y')";
	
	$linkid = mysqli_init();
	mysqli_options($linkid, MYSQLI_OPT_LOCAL_INFILE, true);
	mysqli_real_connect($linkid, $hostname, $_SESSION['usuario'], $_SESSION['clave'], $dbname);
	$resLoadArchivo = mysqli_query($linkid, $sqlLoadData);
	if (!$resLoadArchivo) {
		$error = mysqli_error($linkid);
		throw new PDOException("Error al intentar realizar el LOAD LOCAL INFILE $pathArchivo - $error" );
	}
	
	$sqlCantidadTitulares = "SELECT count(*) as cantitu FROM padronsss WHERE parentesco = 0";
	$resCantidadTitulares = mysql_query($sqlCantidadTitulares, $db);
	$rowCantidadTitulares = mysql_fetch_array($resCantidadTitulares);
	$cantitu = $rowCantidadTitulares['cantitu'];
	
	$sqlCantidadFamiliares = "SELECT count(*) as canfami FROM padronsss WHERE parentesco != 0";
	$resCantidadFamiliares = mysql_query($sqlCantidadFamiliares, $db);
	$rowCantidadFamiliares = mysql_fetch_array($resCantidadFamiliares);
	$canfami = $rowCantidadFamiliares['canfami'];
	
	$total = $cantitu + $canfami;
	
	$sqlCantidad = "SELECT count(*) as cantidad FROM padronsss";
	$resCantidad = mysql_query($sqlCantidad, $db);
	$rowCantidad = mysql_fetch_array($resCantidad);
	$cantotal = $rowCantidad['cantidad'];
	
	if ($total != $cantotal) {
		$dbh->beginTransaction ();
		$sqlTruncate = "TRUNCATE padronsss";
		//echo $sqlTruncate."<br>";
		$dbh->exec($sqlTruncate);
		$dbh->commit();
		throw new PDOException('NO concuerda el total de afiliados con la suma de titulares y famliares al realizar el LOAD DATA');
	} 
	
	$sqlConsultaId = "SELECT id FROM padronssscabecera p ORDER BY id DESC LIMIT 1";
	$resConsultaId = mysql_query($sqlConsultaId, $db);
	$rowConsultaId = mysql_fetch_array($resConsultaId);
	
	$idPadron = $rowConsultaId['id'] + 1;
	
	$file = fopen($pathArchivo, "r");
	$archivoHistorico = "historico.txt";
	$pathGeneral="/tmp/".$archivoHistorico;
	$filew = fopen($pathGeneral, "w");
	while(!feof($file)) {
		$linea = fgets($file);
		if ($linea != "") {
			$lineaNueva = $idPadron."|".$linea;
			fwrite($filew, $lineaNueva);
		}
	}
	fclose($file);
	fclose($filew);
	
	$sqlLoadDataHistorico = "LOAD DATA LOCAL INFILE '$pathGeneral' INTO TABLE padronssshistorico
								FIELDS TERMINATED BY '|'
								LINES TERMINATED BY '\n'
								(idcabecera, codigornos, cuit, cuiltitular, parentesco, cuilfamiliar,
								tipodocumento, nrodocumento, apellidoynombre, sexo, estadocivil, @var1,
								nacionalidad, calledomicilio, puertadomicilio, pisodomicilio, deptodomicilio,
								localidad, codigopostal, codprovin, tipodomicilio, telefono, situacionrevista,
								incapacidad, tipotitular, @var2, @var3, verificacioncuil, cuilinformadoos, tipotitularsijp,
								cuitsijp, ossijp, periodosijp, osopcion, periodoopcion)
								set 
								fechanacimiento = STR_TO_DATE(@var1,'%d%m%Y'),
								fechaaltaos = STR_TO_DATE(@var2,'%d%m%Y'),
								fechapresentacion = STR_TO_DATE(@var3,'%d%m%Y')";
	
	$linkid = mysqli_init();
	mysqli_options($linkid, MYSQLI_OPT_LOCAL_INFILE, true);
	mysqli_real_connect($linkid, $hostname, $_SESSION['usuario'], $_SESSION['clave'], $dbname);
	$resLoadArchivoHistorico = mysqli_query($linkid, $sqlLoadDataHistorico);
	if (!$resLoadArchivoHistorico) {
		$error = mysqli_error($linkid);
		throw new PDOException("Error al intentar realizar el LOAD LOCAL INFILE $pathGeneral - $error" );
	}
	
	$sqlCantidadHistorico = "SELECT count(*) as totalhistorico FROM padronssshistorico WHERE idcabecera = $idPadron";
	$resCantidadHistorico = mysql_query($sqlCantidadHistorico, $db);
	$rowCantidadHistorico = mysql_fetch_array($resCantidadHistorico);
	$controlHistorico = $rowCantidadHistorico['totalhistorico'];
	
	if ($controlHistorico != $cantotal) {
		$dbh->beginTransaction();
		$sqlTruncate = "TRUNCATE padronsss";
		//echo $sqlTruncate."<br>";
		$dbh->exec($sqlTruncate);
		$sqlDeleteHistorico = "DELETE FROM padronssshistorico WHERE idcabecera = $idPadron";
		//echo $sqlDeleteHistorico."<br>";
		$dbh->exec($sqlDeleteHistorico);
		$dbh->commit();
		throw new PDOException('NO concuerda el total HISTORICO de afiliados con el total de afiliados en tabla padronsss');
	}
	
	$dbh->beginTransaction();
	$sqlInsertCabecera = "INSERT INTO padronssscabecera VALUES ($idPadron, $anio, $mes, $cantitu, $canfami, $cantotal, '$fechaproceso','$usuarioproceso', NULL,NULL,NULL,NULL)";
	//echo $sqlInsertCabecera."<br>";
	$dbh->exec($sqlInsertCabecera);
	$dbh->commit ();
	
	//cambio la hora de secion por ahora para no perder la misma
	$ahora = date("Y-n-j H:i:s");
	$_SESSION["ultimoAcceso"] = $ahora;

}  catch ( PDOException $e ) {
    unlink($archivoHistorico);
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Padron SSS :.</title>

</head>
<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = 'moduloSSS.php'" /></p>
  <h2>Importación de Padron SSS</h2>
  <h3><font color="blue">Proceso finalizado con existo</font></h3>
  </div>
</body>
</html>
