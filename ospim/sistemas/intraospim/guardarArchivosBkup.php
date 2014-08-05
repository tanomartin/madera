<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."fechas.php"); 
include($libPath."claves.php");
$delega = $_GET['delega'];
$today = date('Y-m-d');
$carpetamesdia = date("YmdGis");
$directorioBK = "backupintraospim/$delega/";
if(!file_exists($directorioBK)) {
	mkdir ($directorioBK);
	echo "Se ha creado el directorio: ".$directorioBK."<br>";
	$directorioBK = $directorioBK.$carpetamesdia."/";
	if(!file_exists($directorioBK)) {
		mkdir ($directorioBK);
		echo "Se ha creado el directorio: ".$directorioBK."<br>";
	} else {
		echo "la ruta: ".$directorioBK. " ya existe<br>";
	}	
} else {
	echo "la ruta: ".$directorioBK." ya existe<br>";
	$directorioBK = $directorioBK.$carpetamesdia."/";
	if(!file_exists($directorioBK)) {
		mkdir ($directorioBK);
			echo "Se ha creado el directorio: ".$directorioBK."<br>";
	} else {
		echo "la ruta: ".$directorioBK." ya existe<br>";
	}
}
print($directorioBK."<br><br>");
/*if(strcmp("localhost",$maquina)==0) {
	$pathBkup=$_SERVER['DOCUMENT_ROOT']."/ospim/sistemas/intraospim/backupintraospim/$carpetames/$delega";
} else {
	$pathBkup="/home/sistemas/IntraOspim/bkup/$carpetames";
}*/

$pathDirectorio = "archivos/$delega/";
$directorio = opendir($pathDirectorio);
$error = 0;
while ($archivo = readdir($directorio)) {
    if (!is_dir($archivo)) {
        $pathArchivo = $pathDirectorio.$archivo;
		$pathArchivoBK = $directorioBK.$archivo;
		if (!copy($pathArchivo, $pathArchivoBK)) {
			$error = 1;
			echo "Error al copiar $archivo...<br>";
		} else {
			echo "Se copia el archivo $archivo...<br>";
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
				echo "Error al eliminar $archivo...<br>";
			} else {
				echo "Se ha eliminado el archivo $archivo...<br>";
			}
		}
	}
} else {
	echo "No se borraran los archivos de su lugar original ya que ha ocurrido un error...\n";
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Generacion de Padrones :.</title>
</head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>
<body bgcolor="#CCCCCC">
<div align="center">
  <p class="Estilo2"><span style="text-align:center">
  	<input type="reset" name="volver" value="Volver" onclick="location.href = 'moduloActualizacion.php'" align="center"/>
  </span></p>
  <p class="Estilo2">Resultado del Back Up Archivos Intranet O.S.P.I.M.</p>
  <p class="Estilo2">Delegación <?php echo $delega ?> - Fecha <?php echo invertirFecha($today) ?> </p>
  <?php if ($error == 0) {
  			$subidaAcceso = 0;
			try {	
				$hostOspim = "localhost"; //para las pruebas...
				$dbhInternet = new PDO("mysql:host=$hostOspim;dbname=$baseOspimIntranet",$usuarioOspim ,$claveOspim);
				$dbhInternet->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$dbhInternet->setAttribute(PDO::MYSQL_ATTR_LOCAL_INFILE, true);
				$dbhInternet->beginTransaction();
				$sqlAltaAcceso = "UPDATE usuarios SET acceso = 1, fechaactualizacion = '$today' WHERE delcod = $delega";
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
			print("<font color='#FF0000'>Se ha producido un error al querer mover los archivo</font>");
		}
?>
	<p><input type="button" name="imprimir" value="Imprimir" onclick="window.print();" align="center"/></p>
</div>
</body>
</html>