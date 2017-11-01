<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."fechas.php");
set_time_limit(0);

$tipo = $_GET['tipo'];
$pathGeneral="/tmp/";

$zip = new ZipArchive;
if ($zip->open($_FILES['archivo']['tmp_name']) === TRUE) {
	$zip->extractTo($pathGeneral);
	$zip->close();
} else {
	$error =  "Error al tratar de descomprimir el archivo";
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}

$fechafile = $_POST['fechafile'];
$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];

$deleteMedicamento = "DELETE FROM medicamentos";
$deleteExtra = "DELETE FROM mediextra";
$deleteAccion = "DELETE FROM mediaccion";

$archivoManual = $pathGeneral."manual.dat";
$fmanual = fopen($archivoManual, "r");
$pathManual = $pathGeneral."archivomanual.txt";
$filemanual = fopen($pathManual, "w");
$pathPrecio = $pathGeneral."archivoprecio.txt";
$filePrecio = fopen($pathPrecio, "w");
$cantidadMedicamento = 0;
while(!feof($fmanual)) {
	$linea = fgets($fmanual);
	if (strlen($linea) > 0) {
		$troquel = substr($linea,0,7);
		$nombre = utf8_decode(substr($linea,7,44));
		$presentacion = addslashes(substr($linea,51,24));
		
		$IOMAMonto = substr($linea,75,8);
		$IOMAMonto = (float)$IOMAMonto/100;
		
		$IOMANorma = substr($linea,83,1);
		$IOMAInterna = substr($linea,84,1);
		$laboratorio = addslashes(substr($linea,85,16));
		
		$precio = substr($linea,101,9);
		$precio = (float)$precio/100;
		
		$fecha = substr($linea,110,8);
		$codigomarca = substr($linea,118,1);
		$importado = substr($linea,119,1);
		$codigotipoventa = substr($linea,120,1);
		$iva = substr($linea,121,1);
		$codigoPAMI = substr($linea,122,1);
		$codigoLab = substr($linea,123,3);
		$codigo = substr($linea,126,5);
		$baja = substr($linea,131,1);
		$codbarra = substr($linea,132,13);
		$unidades = substr($linea,145,4);
		$codigotamano = substr($linea,149,1);
		$heladera = substr($linea,150,1);
		$SIFAR = substr($linea,151,1);
		$blanco = substr($linea,152,4);
		
		$cantidadMedicamento++;
		
		$lineain = $codigo."|".$troquel."|".$nombre."|".$presentacion."|".$IOMAMonto."|".$IOMANorma.
				"|".$IOMAInterna."|".$laboratorio."|".$precio."|".$fecha."|".$codigomarca."|".$importado.
				"|".$codigotipoventa."|".$iva."|".$codigoPAMI."|".$codigoLab."|".$baja.
				"|".$codbarra."|".$unidades."|".$codigotamano."|".$heladera."|".$SIFAR."|".$blanco.
				"|".$fecharegistro."|".$usuarioregistro;
		fwrite($filemanual, $lineain."\n");
		
		$lineaHistorico = $codigo."|".$fecha."|".$precio."|".$fecharegistro."|".$usuarioregistro;
		fwrite($filePrecio, $lineaHistorico."\n");
	}
}
fclose($filemanual);
fclose($filePrecio);

$archivoExtra = $pathGeneral."manextra.txt";
$fextra = fopen($archivoExtra,"r");
$pathExtra = $pathGeneral."archivoextra.txt";
$fileExtra = fopen($pathExtra, "w");
$cantidadExtra = 0;
while(!feof($fextra)) {
	$linea = fgets($fextra);
	if (strlen($linea) > 0) {
		$codigo = substr($linea,0,5);
		$codigotamano = substr($linea,5,2);
		$codigoaccion = substr($linea,7,5);
		$codigomonodroga = substr($linea,12,5);
		$codigoaccion = substr($linea,17,5);
		$potencia = substr($linea,22,16);
		$codigounidadpotencia = substr($linea,38,5);
		$codigotipounidad = substr($linea,43,5);
		$codigovia = substr($linea,48,5);
		
		$cantidadExtra++;
		
		$lineain = $codigo."|".$codigotamano."|".$codigoaccion."|".$codigomonodroga."|".$codigoaccion."|".
				   $potencia."|".$codigounidadpotencia."|".$codigotipounidad."|".$codigovia;
		fwrite($fileExtra, $lineain."\n");
	}
}
fclose($fileExtra);

$archivoAccion = $pathGeneral."acciofar.txt";
$faccion = fopen($archivoAccion, "r");
$pathAccion = $pathGeneral."archivoaccion.txt";
$fileAccion = fopen($pathAccion, "w");
$cantidadAccion = 0;
while(!feof($faccion)) {
	$linea = fgets($faccion);
	if (strlen($linea) > 0) {
		$codigo = substr($linea,0,5);
		$descipcion = addslashes(substr($linea,5,32));
		
		$cantidadAccion++;
		
		$lineain = $codigo."|".$descipcion;
		fwrite($fileAccion, $lineain."\n");
	}
}
fclose($fileAccion);

$archivoMono = $pathGeneral."monodro.txt";
$fmono = fopen($archivoMono, "r");
$pathMono = $pathGeneral."archivomono.txt";
$fileMono = fopen($pathMono, "w");
$cantidadMono = 0;
while(!feof($fmono)) {
	$linea = fgets($fmono);
	if (strlen($linea) > 0) {
		$codigo = substr($linea,0,5);
		$descipcion = addslashes(substr($linea,5,32));

		$cantidadMono++;

		$lineain = $codigo."|".$descipcion;
		fwrite($fileMono, $lineain."\n");
	}
}
fclose($fileMono);

$archivoTamano = $pathGeneral."tamanos.txt";
$ftamano = fopen($archivoTamano, "r");
$pathTamano = $pathGeneral."archivotamano.txt";
$fileTamano = fopen($pathTamano, "w");
$cantidadTamano = 0;
while(!feof($ftamano)) {
	$linea = fgets($ftamano);
	if (strlen($linea) > 0) {
		$codigo = substr($linea,0,2);
		$descipcion = addslashes(substr($linea,2,32));

		$cantidadTamano++;

		$lineain = $codigo."|".$descipcion;
		fwrite($fileTamano, $lineain."\n");
	}
}
fclose($fileTamano);

$archivoFormas = $pathGeneral."formas.txt";
$fformas = fopen($archivoFormas, "r");
$pathFormas = $pathGeneral."archivoformas.txt";
$fileFormas = fopen($pathFormas, "w");
$cantidadFormas = 0;
while(!feof($fformas)) {
	$linea = fgets($fformas);
	if (strlen($linea) > 0) {
		$codigo = substr($linea,0,5);
		$descipcion = addslashes(substr($linea,5,32));

		$cantidadFormas++;

		$lineain = $codigo."|".$descipcion;
		fwrite($fileFormas, $lineain."\n");
	}
}
fclose($fileFormas);

$archivoUPot = $pathGeneral."upotenci.txt";
$fupot = fopen($archivoUPot, "r");
$pathUpotenci = $pathGeneral."archivouptenci.txt";
$fileUpot = fopen($pathUpotenci, "w");
$cantidadUpot = 0;
while(!feof($fupot)) {
	$linea = fgets($fupot);
	if (strlen($linea) > 0) {
		$codigo = substr($linea,0,5);
		$descipcion = addslashes(substr($linea,5,32));

		$cantidadUpot++;

		$lineain = $codigo."|".$descipcion;
		fwrite($fileUpot, $lineain."\n");
	}
}
fclose($fileUpot);

$archivoUnidad = $pathGeneral."tipounid.txt";
$funidad = fopen($archivoUnidad, "r");
$pathUnidad = $pathGeneral."archivounidad.txt";
$fileUnidad = fopen($pathUnidad, "w");
$cantidadUnidad = 0;
while(!feof($funidad)) {
	$linea = fgets($funidad);
	if (strlen($linea) > 0) {
		$codigo = substr($linea,0,5);
		$descipcion = addslashes(substr($linea,5,32));

		$cantidadUnidad++;

		$lineain = $codigo."|".$descipcion;
		fwrite($fileUnidad, $lineain."\n");
	}
}
fclose($fileUnidad);

$archivoVias = $pathGeneral."vias.txt";
$fvias = fopen($archivoVias, "r");
$pathVias = $pathGeneral."archivovias.txt";
$fileVias = fopen($pathVias, "w");
$cantidadVias = 0;
while(!feof($fvias)) {
	$linea = fgets($fvias);
	if (strlen($linea) > 0) {
		$codigo = substr($linea,0,5);
		$descipcion = addslashes(substr($linea,5,32));

		$cantidadVias++;

		$lineain = $codigo."|".$descipcion;
		fwrite($fileVias, $lineain."\n");
	}
}
fclose($fileVias);

$sqlInsertControl = "INSERT INTO medicontrol VALUES(DEFAULT, '$tipo', $cantidadMedicamento, $cantidadExtra, $cantidadAccion,$cantidadMono,$cantidadTamano,$cantidadFormas,$cantidadUpot,$cantidadUnidad,$cantidadVias,'$fechafile','$fecharegistro', '$usuarioregistro')";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES  'UTF8'"));
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


	if ($tipo == "M") {
		$dbh->beginTransaction();
		//echo $deleteMedicamento."<br>";
		$dbh->exec($deleteMedicamento);
		//echo $deleteExtra."<br>";
		$dbh->exec($deleteExtra);
		//echo $deleteAccion."<br>";
		$dbh->exec($deleteAccion);
		$dbh->commit();
	}
	
	$linkid = mysqli_init();
	mysqli_options($linkid, MYSQLI_OPT_LOCAL_INFILE, true);
	mysqli_real_connect($linkid, $hostname, $_SESSION['usuario'], $_SESSION['clave'], $dbname);
	
	$sqlLoadArchivoMedicamento = "LOAD DATA LOCAL INFILE '$pathManual' REPLACE INTO TABLE medicamentos FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n'";
	//echo $sqlLoadArchivoMedicamento."<br>";
	$resLoadArchivoMedicamento = mysqli_query($linkid, $sqlLoadArchivoMedicamento);
	if (!$resLoadArchivoMedicamento) {
		$error = mysqli_error($linkid);
		throw new PDOException("Error al intentar realizar el LOAD LOCAL INFILE $pathManual - $error" );
	}
	
	$sqlLoadArchivoPrecio = "LOAD DATA LOCAL INFILE '$pathPrecio' REPLACE INTO TABLE medipreciohistorico FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n'";
	//echo $sqlLoadArchivoPrecio."<br>";
	$resLoadArchivoPrecio = mysqli_query($linkid, $sqlLoadArchivoPrecio);
	if (!$resLoadArchivoPrecio) {
		$error = mysqli_error($linkid);
		throw new PDOException("Error al intentar realizar el LOAD LOCAL INFILE $pathPrecio - $error" );
	}
	
	$sqlLoadArchivoExtra = "LOAD DATA LOCAL INFILE '$pathExtra' REPLACE INTO TABLE mediextra FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n'";
	//echo $sqlLoadArchivoExtra."<br>";
	$resLoadArchivoExtra = mysqli_query($linkid, $sqlLoadArchivoExtra);
	if (!$resLoadArchivoExtra) {
		$error = mysqli_error($linkid);
		throw new PDOException("Error al intentar realizar el LOAD LOCAL INFILE $pathExtra - $error" );
	}
	
	$sqlLoadArchivoAccion = "LOAD DATA LOCAL INFILE '$pathAccion' REPLACE INTO TABLE mediaccion FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n'";
	//echo $sqlLoadArchivoAccion."<br>";
	$resLoadArchivoAccion = mysqli_query($linkid, $sqlLoadArchivoAccion);
	if (!$resLoadArchivoAccion) {
		$error = mysqli_error($linkid);
		throw new PDOException("Error al intentar realizar el LOAD LOCAL INFILE $pathAccion - $error" );
	}
	
	$sqlLoadArchivoMono = "LOAD DATA LOCAL INFILE '$pathMono' REPLACE INTO TABLE medimono FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n'";
	//echo $sqlLoadArchivoMono."<br>";
	$resLoadArchivoMono = mysqli_query($linkid, $sqlLoadArchivoMono);
	if (!$resLoadArchivoMono) {
		$error = mysqli_error($linkid);
		throw new PDOException("Error al intentar realizar el LOAD LOCAL INFILE $pathMono - $error" );
	}
	
	$sqlLoadArchivoTamano = "LOAD DATA LOCAL INFILE '$pathTamano' REPLACE INTO TABLE mediextratamano FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n'";
	//echo $sqlLoadArchivoTamano."<br>";
	$resLoadArchivoTamano = mysqli_query($linkid, $sqlLoadArchivoTamano);
	if (!$resLoadArchivoTamano) {
		$error = mysqli_error($linkid);
		throw new PDOException("Error al intentar realizar el LOAD LOCAL INFILE $pathTamano - $error" );
	}
	
	$sqlLoadArchivoFormas = "LOAD DATA LOCAL INFILE '$pathFormas' REPLACE INTO TABLE mediformas FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n'";
	//echo $sqlLoadArchivoFormas."<br>";
	$resLoadArchivoFormas = mysqli_query($linkid, $sqlLoadArchivoFormas);
	if (!$resLoadArchivoFormas) {
		$error = mysqli_error($linkid);
		throw new PDOException("Error al intentar realizar el LOAD LOCAL INFILE $pathFormas - $error" );
	}
	
	$sqlLoadArchivoUpot = "LOAD DATA LOCAL INFILE '$pathUpotenci' REPLACE INTO TABLE mediupotencia FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n'";
	//echo $sqlLoadArchivoUpot."<br>";
	$resLoadArchivoUpot = mysqli_query($linkid, $sqlLoadArchivoUpot);
	if (!$resLoadArchivoUpot) {
		$error = mysqli_error($linkid);
		throw new PDOException("Error al intentar realizar el LOAD LOCAL INFILE $pathUpotenci - $error" );
	}
	
	$sqlLoadArchivoUnidad = "LOAD DATA LOCAL INFILE '$pathUnidad' REPLACE INTO TABLE mediunidad FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n'";
	//echo $sqlLoadArchivoUnidad."<br>";
	$resLoadArchivoUnidad = mysqli_query($linkid, $sqlLoadArchivoUnidad);
	if (!$resLoadArchivoUnidad) {
		$error = mysqli_error($linkid);
		throw new PDOException("Error al intentar realizar el LOAD LOCAL INFILE $pathUnidad - $error" );
	}
	
	$sqlLoadArchivoVias = "LOAD DATA LOCAL INFILE '$pathVias' REPLACE INTO TABLE medivias FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n'";
	//echo $sqlLoadArchivoVias."<br>";
	$resLoadArchivoVias = mysqli_query($linkid, $sqlLoadArchivoVias);
	if (!$resLoadArchivoVias) {
		$error = mysqli_error($linkid);
		throw new PDOException("Error al intentar realizar el LOAD LOCAL INFILE $pathVias - $error" );
	}
	
	$dbh->beginTransaction();
	//echo $sqlInsertControl."<br>";
	$dbh->exec($sqlInsertControl);
	$dbh->commit();
	
	Header("Location: ../../auditoria/medicamentos/controlActualizacion.php");
} catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}

?>