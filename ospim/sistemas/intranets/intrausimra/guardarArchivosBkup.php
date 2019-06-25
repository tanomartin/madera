<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."fechas.php"); 
include($libPath."claves.php"); 
$today = date('Y-m-d');
$carpetames = date("YmdGis");

$directorioBK = "backupintrausimra/$carpetames/";
if(!file_exists($directorioBK)) {
	mkdir ($directorioBK);
	echo "Se ha creado el directorio: ".$directorioBK."<br>";	
} else {
	echo "la ruta: ".$directorioBK." ya existe<br>";
}
$pathDirectorio = "archivos/";
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

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = 'moduloActualizacion.php'" /></p>
  <h3>Resultado del Back Up Archivos Intranet U.S.I.M.R.A.</h3>
  <h3>Fecha <?php echo invertirFecha($today) ?> </h3>
  <?php if ($error == 0) {
			$subidaAcceso = 0;
			try {	
				$maquina = $_SERVER['SERVER_NAME'];
				if(strcmp("localhost",$maquina)==0) {
					$hostUsimra = "localhost"; //para las pruebas...
				}
				$dbhInternet = new PDO("mysql:host=$hostUsimra;dbname=$baseUsimraIntranet",$usuarioUsimra ,$claveUsimra);
				$dbhInternet->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$dbhInternet->setAttribute(PDO::MYSQL_ATTR_LOCAL_INFILE, true);
				$dbhInternet->beginTransaction();
				$sqlBajoAcceso = "UPDATE usuarios SET acceso = 1, fechaactualizacion = '$today'";
				$dbhInternet->exec($sqlBajoAcceso);
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
			print("<font color='#FF0000'>Se ha producido un errro al querer mover los archivo</font>");
		}
?> <p><input type="button" name="imprimir" value="Imprimir" onclick="window.print();" /></p>
</div>
</body>
</html>