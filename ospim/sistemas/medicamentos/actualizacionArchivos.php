<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."fechas.php");
set_time_limit(0);

$tipo = $_GET['tipo'];
$fechafile = fechaParaGuardar($_POST['fechafile']);
if ($_FILES['manual']['name'] != "manual.dat") {
	$error =  "El archivo manual.dat tiene nombre incorrecto";
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}

if ($_FILES['extra']['name'] != "manextra.txt") {
	$error =  "El archivo manextra.txt tiene nombre incorrecto";
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}

if ($_FILES['accion']['name'] != "acciofar.txt") {
	$error =  "El archivo acciofar.txt tiene nombre incorrecto";
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}

$deleteMedicamento = "DELETE FROM medicamentos";
$deleteExtra = "DELETE FROM mediextra";
$deleteAccion = "DELETE FROM mediaccion";

$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];

$carpeta = "semanal";
if ($tipo == "M") { $carpeta = "mensual"; }

$maquina = $_SERVER['SERVER_NAME'];
$pathGeneral="/tmp/";
if(strcmp("localhost",$maquina)==0) {
	$pathGeneral=$_SERVER['DOCUMENT_ROOT']."/madera/ospim/sistemas/medicamentos/files/$carpeta/";
}

$archivoManual = $_FILES['manual']['tmp_name'];
$fmanual = fopen($archivoManual, "r");
$cantidadMedicamento = 0;

$pathManual = $pathGeneral."archivomanual.txt";
$filemanual = fopen($pathManual, "w");

$pathPrecio = $pathGeneral."archivoprecio.txt";
$filePrecio = fopen($pathPrecio, "w");

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

$archivoExtra = $_FILES['extra']['tmp_name'];
$fextra = fopen($archivoExtra,"r");
$cantidadExtra = 0;

$pathExtra = $pathGeneral."archivoextra.txt";
$fileExtra = fopen($pathExtra, "w");

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

$archivoAccion = $_FILES['accion']['tmp_name'];
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

$sqlInsertControl = "INSERT INTO medicontrol VALUES(DEFAULT, '$tipo', $cantidadMedicamento, $cantidadExtra, $cantidadAccion,'$fechafile','$fecharegistro', '$usuarioregistro')";

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