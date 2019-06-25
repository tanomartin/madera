<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."fechas.php"); 
include($libPath."claves.php");
$delega = $_GET['delega'];
$today = date('Y-m-d');
$carpetamesdia = date("YmdGis");
$directorioBK = "backupintraospim/$delega/";
if(!file_exists($directorioBK)) {
	mkdir ($directorioBK, 0777);
	$directorioBK = $directorioBK.$carpetamesdia."/";
	if(!file_exists($directorioBK)) {
		mkdir ($directorioBK, 0777);
	}
} else {
	$directorioBK = $directorioBK.$carpetamesdia."/";
	if(!file_exists($directorioBK)) {
		mkdir ($directorioBK, 0777);
	} 
}

$pathDirectorio = "archivos/$delega/";
$directorio = opendir($pathDirectorio);
$error = 0;
while ($archivo = readdir($directorio)) {
    if (!is_dir($archivo)) {
        $pathArchivo = $pathDirectorio.$archivo;
		$pathArchivoBK = $directorioBK.$archivo;
		if (!copy($pathArchivo, $pathArchivoBK)) {
			$error = 1;
			$descri = "Error al copiar $archivo...<br>";
		} 
    }
}

if ($error == 0) {
	$pathDirectorio = "archivos/$delega/";
	$directorio = opendir($pathDirectorio);
	while ($archivo = readdir($directorio)) {
		if (!is_dir($archivo)) {
			$pathArchivo = $pathDirectorio.$archivo;
			if (!unlink($pathArchivo)) {
				$error = 1;
				$descri = "Error al eliminar $archivo...<br>";
			} 
		}
	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Generacion de Padrones :.</title>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = 'moduloActualizacion.php'" /></p>
  <h3>Resultado del Back Up Archivos Intranet O.S.P.I.M.</h3>
  <h3>Delegación <?php echo $delega ?> - Fecha <?php echo invertirFecha($today) ?> </h3>
<?php if ($error == 0) {
  		$subidaAcceso = 0;
		try {	
			$maquina = $_SERVER['SERVER_NAME'];
			if(strcmp("localhost",$maquina)==0) {
				$hostOspim = "localhost"; //para las pruebas...
			}
			$dbhInternet = new PDO("mysql:host=$hostOspim;dbname=$baseOspimIntranet",$usuarioOspim ,$claveOspim);
			$dbhInternet->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$dbhInternet->beginTransaction();
			$sqlAltaAcceso = "UPDATE usuarios SET acceso = 1, fechaactualizacion = '$today' WHERE delcod = $delega || delcod >= 3200";
			//print($sqlAltaAcceso."<br>");
			$dbhInternet->exec($sqlAltaAcceso);
			$dbhInternet->commit();
			$subidaAcceso = 1;
		} catch (PDOException $e) {
			$descriError = $e->getMessage();
			print("$descriError<br><br>");
			$dbhInternet->rollback();
		}
		if ($subidaAcceso == 1) {
			print("<font color='#0000FF'>Se han movido todos los archivos a la siguiente direccion <b>$directorioBK</b> y se dio de alta el acceso de la delegación</font><br>");
		} else {
			print("<font color='#0000FF'>Se han movido todos los archivos a la siguiente direccion <b>$directorioBK</b><br>");
			print("<font color='#FF0000'>Se ha producido un error al querer dar de alta el acceso a la delegación</font>");
		}
	  } else {
		print("<font color='#FF0000'>Se ha producido un error al querer mover los archivo <br> $descri </font>");
	  } ?>
	<p><input type="button" name="imprimir" value="Imprimir" onclick="window.print();" /></p>
</div>
</body>
</html>