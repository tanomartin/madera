<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
set_time_limit(0);
ini_set('memory_limit', '-1');
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."claves.php");
include($libPath."fechas.php");
$today = date('Y-m-d');
$timestamp1 = mktime(date("H"),date("i"),date("s"),date("n"),date("j"),date("Y")); 
$delegacion = $_GET['delcod'];

//print("DELE A ACTULAIZAR ".$delegacion."<br>");
//BANDERAS
$errorArchivos = 0;
$bajaacceso = 0;
$deleteTablas = 0;
$loadTablas = 0;
$control = array();
$resultados = array();

//print("<br>Verifico que existan los archivos<br>");
$pathArchivo = "archivos/".$delegacion."/";
$arrayNombreArchivo = array("empresa.txt","titular.txt","familia.txt","bajatit.txt","bajafam.txt","cabjur.txt","cuij$delegacion.txt","pagos.txt","apoi$delegacion.txt", "cabacuer.txt","detacuer.txt","cuoacuer.txt","juicios.txt",);
foreach ($arrayNombreArchivo as $nombreArc) {
	$archivo = $pathArchivo.$nombreArc;
	//print($archivo."<br>");
	if (!file_exists ($archivo)) {
		$errorArc = "No existe el archivo ".$archivo."<br>";
		//print($errorArc);
		$errorArchivos = 1;
	}
}

if ($errorArchivos == 0) {
	$resultados[0] = array("etapa" => "Existencia Archivos", "estado" => "OK", "descripcion" => "");
	//print("<br>Doy de baja el acceso a la delegacion<br>");
	$maquina = $_SERVER['SERVER_NAME'];
	if(strcmp("localhost",$maquina)==0) {
		$hostOspim = "localhost"; //para las pruebas...
	}
	$dbhInternet = new PDO("mysql:host=$hostOspim;dbname=$baseOspimIntranet",$usuarioOspim ,$claveOspim);
   	$dbhInternet->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	//Bajo el acceso de la delegacion
	try {
		$dbhInternet->beginTransaction();
		$sqlBajoAcceso = "UPDATE usuarios SET acceso = 0 WHERE delcod = $delegacion";
		//print($sqlBajoAcceso."<br>");
		$dbhInternet->exec($sqlBajoAcceso);
		$dbhInternet->commit();
		$bajaacceso = 1;
	} catch (PDOException $e) {
		$descriError = $e->getMessage();
		$resultados[1] = array("etapa" => "Bajada Acceso Usuario", "estado" => "Error", "descripcion" => $descriError);
		//print("$descriError<br><br>");
		$dbhInternet->rollback();	
	}
	if ($bajaacceso == 1) {
		$resultados[1] = array("etapa" => "Bajada Acceso Usuario", "estado" => "OK", "descripcion" => "");
		$i = 0;
		//print("<br>Deleteo las tablas de esa delegación.<br>");
		$arraySqlDelete = array();
		$i = 0;
		
		foreach ($arrayNombreArchivo as $nombreArc) {
			$splitNombre = explode(".",$nombreArc);
			$tabla = $splitNombre[0];
			if (stripos($tabla,$delegacion) !== FALSE) {
				$sqlControlTabla = "DELETE from $tabla";
			} else {
				if (stripos($tabla,"acuer") !== FALSE || stripos($tabla,"juicios") !== FALSE) {
					$sqlControlTabla = "DELETE from $tabla WHERE nrcuit in (SELECT nrcuit FROM empresa WHERE delcod = ".$delegacion.")";
				} else {
					$sqlControlTabla = "DELETE from $tabla WHERE delcod = $delegacion";
				}
			}
			$arraySqlDelete[$i] = $sqlControlTabla;
			$i++;
		}
		
		foreach ($arraySqlDelete as $sqlDelete) { 
			try {
				$dbhInternet->beginTransaction();
				//print($sqlDelete."<br>");
				$dbhInternet->exec($sqlDelete);
				$dbhInternet->commit();
				$deleteTablas = 1;
			} catch (PDOException $e) {
				$deleteTablas = 0;
				$descriError = $e->getMessage();
				$resultados[2] = array("etapa" => "Eliminacion de Tablas", "estado" => "Error", "descripcion" => $descriError);
				//print("$descriError<br><br>");
				$dbhInternet->rollback();
			}
		}
	}
	
	if ($deleteTablas == 1) {
		$resultados[2] = array("etapa" => "Eliminacion de Tablas", "estado" => "OK", "descripcion" => "");
		$loadTablas = 1;
		try {
			foreach ($arrayNombreArchivo as $nombreArc) {
				//print("<br>Hago el load data de $nombreArc.<br>");
				$pathCompleto = $pathArchivo.$nombreArc;
				$splitNombre = explode('.',$nombreArc);
				$tabla = $splitNombre[0];
				if (filesize($pathCompleto) > 0) {
					$gestor = fopen($pathCompleto, "r");
					$contenido = fread($gestor, filesize($pathCompleto));
					fclose($gestor);
					$insertLinea = "INSERT IGNORE INTO $tabla VALUES ".$contenido;
					$dbhInternet->beginTransaction();
					//print($insertLinea."<br>");
					$dbhInternet->exec($insertLinea);
					$dbhInternet->commit();
				}
			}
		} catch (PDOException $e) {
			$loadTablas = 0;
			$descriError = $e->getMessage();
			$resultados[3] = array("etapa" => "Subida de Información", "estado" => "Error", "descripcion" => "ARCHIVO: ".$nombreArc."<br>".$descriError);
			//print("$descriError<br><br>");
			$dbhInternet->rollback();
		}
	}
} else {
	$resultados[0] = array("etapa" => "Existencia Archivos", "estado" => "Error", "descripcion" => $errorArc);
}

if (($errorArchivos == 0) && ($bajaacceso == 1) && ($deleteTablas == 1) && ($loadTablas == 1)) {
	$countControl = 0;
	$resultados[3] = array("etapa" => "Subida de Información", "estado" => "OK", "descripcion" => "");
	//print("<br>Hacer count de cada tabla actualizada y comprar con el archivo totalizador.<br>");
	$i = 0;
	try {
		foreach ($arrayNombreArchivo as $nombreArc) {
			$splitNombre = explode(".",$nombreArc);
			$tabla = $splitNombre[0];
			if (stripos($tabla,$delegacion) !== FALSE) {
				$sqlControlTabla = "SELECT count(*) as total from $tabla";
			} else {
				if (stripos($tabla,"acuer") !== FALSE || stripos($tabla,"juicios") !== FALSE) {
					$sqlControlTabla = "SELECT count(*) as total from $tabla t, empresa e WHERE e.delcod = $delegacion and e.nrcuit = t.nrcuit";
				} else {
					$sqlControlTabla = "SELECT count(*) as total from $tabla WHERE delcod = $delegacion";
				}
			}
			//print($sqlControlTabla."<br>");
			$query = $dbhInternet->prepare($sqlControlTabla);
			$query->execute();
			$row = $query->fetch();
			$contador = $row['total'];
			
			$pathCompleto = $pathArchivo.$nombreArc;			
			$file = fopen ($pathCompleto, "r"); 
			$num_lineas = 0; 
			while (!feof($file)) { 
				if ($linea = fgets($file)){ 
					if (sizeof($linea) > 0) {
				   		$num_lineas++;
					} 
				} 
			} 
			fclose($file); 
			$control[$i] = array('tabla' => $tabla, 'archivo' => $num_lineas, 'count' => $contador);
			$i++;
		}
	} catch (PDOException $e) {
		$countControl = 1;
		$descriError = $e->getMessage();
		$resultados[4] = array("etapa" => "Count Tablas", "estado" => "Error", "descripcion" => $descriError);
		//print("$descriError<br><br>");
		$dbhInternet->rollback();
	}

	if ($countControl == 0) {
		$resultados[4] = array("etapa" => "Count Tablas", "estado" => "OK", "descripcion" => "");
		//controlo y si da ok levanto acceso y backapeo archivos.
		$tablasMayores = array("cuij$delegacion","pagos","apoi$delegacion","detacuer","juicios");
		$errorUpdate = 0;
		foreach ($control as $con) {
			if (in_array($con['tabla'],$tablasMayores)) {
				if ($con['count'] > $con['archivo']) {
					$descriError = "Tabla: ".$con['tabla']." error en la cantidad de registros actualizados";
					$resultados[5] = array("etapa" => "Control de Subida", "estado" => "Error", "descripcion" => $descriError);
					$errorUpdate = 1;
				}
			} else {
				if ($con['count'] != $con['archivo']) {
					$descriError = "Tabla: ".$con['tabla']." error en la cantidad de registros actualizados";
					$resultados[5] = array("etapa" => "Control de Subida", "estado" => "Error", "descripcion" => $descriError);
					$errorUpdate = 1;
				}
			}
		}
		
		if ($errorUpdate == 0) {
			$resultados[5] = array("etapa" => "Control de Subida", "estado" => "OK", "descripcion" => "");
			$carpetamesdia = date("YmdGis");
			$directorioBK = "backupintraospim/$delegacion/";
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
			$pathDirectorio = "archivos/$delegacion/";
			$directorio = opendir($pathDirectorio);
			$errorBkARchivos = 0;
			while ($archivo = readdir($directorio)) {
				if (!is_dir($archivo)) {
					$pathArchivo = $pathDirectorio.$archivo;
					$pathArchivoBK = $directorioBK.$archivo;
					if (!copy($pathArchivo, $pathArchivoBK)) {
						$errorBkARchivos = 1;
						$descriError = "Error al copiar $archivo";
						$resultados[6] = array("etapa" => "BackUp Archivos", "estado" => "Error", "descripcion" => $descriError);
					}
				}
			}
			
			if ($errorBkARchivos == 0) {
				$pathDirectorio = "archivos/$delegacion/";
				$directorio = opendir($pathDirectorio);
				while ($archivo = readdir($directorio)) {
					if (!is_dir($archivo)) {
						$pathArchivo = $pathDirectorio.$archivo;
						if (!unlink($pathArchivo)) {
							$errorBkARchivos = 1;
							$descriError = "Error al eliminar $archivo";
							$resultados[6] = array("etapa" => "BackUp Archivos", "estado" => "Error", "descripcion" => $descriError);
						} 
					}
				}
			}
			
			if ($errorBkARchivos == 0) {
				$resultados[6] = array("etapa" => "BackUp Archivos", "estado" => "OK", "descripcion" => "BackUp Path: ".$directorioBK);
				try {	
					if(strcmp("localhost",$maquina)==0) {
						$hostOspim = "localhost"; //para las pruebas...
					}
					$dbhInternet = new PDO("mysql:host=$hostOspim;dbname=$baseOspimIntranet",$usuarioOspim ,$claveOspim);
					$dbhInternet->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$dbhInternet->beginTransaction();
					$sqlAltaAcceso = "UPDATE usuarios SET acceso = 1, fechaactualizacion = '$today' WHERE delcod = $delegacion || delcod >= 3200";
					//print($sqlAltaAcceso."<br>");
					$dbhInternet->exec($sqlAltaAcceso);
					$dbhInternet->commit();
					$subidaAcceso = 1;
				} catch (PDOException $e) {
					$descriError = $e->getMessage();
					$resultados[7] = array("etapa" => "Alta Acceso Usuario", "estado" => "Error", "descripcion" => $descriError);
					$dbhInternet->rollback();
				}		
				$resultados[7] = array("etapa" => "Alta Acceso Usuario", "estado" => "OK", "descripcion" => "");
			}
		}
	}
}

$ahora = date("Y-n-j H:i:s"); 
$_SESSION["ultimoAcceso"] = $ahora;
$timestamp2 = mktime(date("H"),date("i"),date("s"),date("n"),date("j"),date("Y"));
$tiempoTranscurrido = ($timestamp2 - $timestamp1)/ 60;
$enMintuos = number_format($tiempoTranscurrido,2,',','.');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Resultado Actua OSPIM  :.</title>
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
  <p class="Estilo2">Resultado del Actualizacion Intranet O.S.P.I.M.</p>
  <p class="Estilo2">Delegación <?php echo $delegacion ?> - Fecha <?php echo invertirFecha($today) ?> </p>
  <p class="Estilo2" style="color:#0000FF">Tiempo de Proceso: <?php echo $enMintuos ?> Minutos</p>
  <p class="Estilo2">Procesos</p>
   <table border="1" align="center" width="800">
	  <tr>
		<th>Etapa</th>
		<th>Resultado</th>
		<th>Descripcion</th>
	  </tr>
<?php foreach ($resultados as $res) {
			print("<tr align='center'>");
			print("<td>".$res['etapa']."</td>");
			print("<td>".$res['estado']."</td>");
			print("<td>".$res['descripcion']."</td>");
			print("</tr>");
		}
?>
  </table>
 
  <?php if (sizeof($control) > 1) { ?>
  	 <p class="Estilo2">Control</p>
	  <table border="1" align="center">
			<tr>
				<th>Tabla</th>
				<th>Archivo</th>
				<th>Count</th>
				<th>Control</th>
			</tr>
	   <?php foreach ($control as $resultado) {
				print("<tr align='center'>");
				print("<td>".$resultado['tabla']."</td>");
				print("<td>".$resultado['archivo']."</td>");
				print("<td>".$resultado['count']."</td>");
				$resta = (int)$resultado['archivo'] - (int)$resultado['count'];
				if ($resta != 0) {
					print("<td><font color='#FF0000'>$resta</font></td>");
				} else {
					print("<td>-</td>");
				}
				print("</tr>");
			} ?>
  </table>
<?php	} 

		if ($resultados[5]['estado'] == "Error" or $resultados[6]['estado'] == "Error" or $resultados[7]['estado'] == "Error") { ?>
			<p><input type="reset" name="volver2" value="Forzar Cierre de Proceso" onclick="location.href = 'guardarArchivosBkup.php?delega=<?php echo $delegacion ?>'" align="center"/></p>
<?php	} ?>
	 
	  <p><span style="text-align:center">
	  </span></p>
	<p><input type="button" name="imprimir" value="Imprimir" onclick="window.print();" align="center"/></p>
</div>
</body>
</html>