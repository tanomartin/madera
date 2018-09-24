<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
set_time_limit(0);
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."claves.php");
include($libPath."fechas.php");
$today = date('Y-m-d');
$timestamp1 = mktime(date("H"),date("i"),date("s"),date("n"),date("j"),date("Y")); 

//BANDERAS
$errorArchivos = 0;
$bajaacceso = 0;
$deleteTablas = 0;
$loadTablas = 0;
$control = array();
$resultado = array();
$arrayNombreTablas = array("empresa","cabacuer","detacuer","cuoacuer","ddjjnopa","juicios","pagos","peranter");

//print("<br>Verifico que existan el archivo<br>");
$pathArchivo = "archivos/";
foreach ($arrayNombreTablas as $nombreArc) {
	$archivo = $pathArchivo.$nombreArc.".txt";
	if (!file_exists ($archivo)) {
		//print("No existe el archivo ".$archivo."<br>");
		$errorArc = "No existe el archivo ".$archivo."<br>";
		$errorArchivos = 1;
	}
}

if ($errorArchivos == 0) {
	$resultados[0] = array("etapa" => "Existencia Archivos", "estado" => "OK", "descripcion" => "");
	//print("<br>Doy de baja el acceso todos los accesos<br>");
	$maquina = $_SERVER['SERVER_NAME'];
	if(strcmp("localhost",$maquina)==0) {
		$hostUsimra = "localhost"; //para las pruebas...
	}
	$dbhInternet = new PDO("mysql:host=$hostUsimra;dbname=$baseUsimraIntranet",$usuarioUsimra ,$claveUsimra,array(PDO::MYSQL_ATTR_LOCAL_INFILE => 1,));
   	$dbhInternet->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbhInternet->setAttribute(PDO::MYSQL_ATTR_LOCAL_INFILE, true);

	try {
		$dbhInternet->beginTransaction();
		$sqlBajoAcceso = "UPDATE usuarios SET acceso = 0";
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
		//print("<br>Deleteo las tablas<br>");	
		foreach ($arrayNombreTablas as $nombreTabla) {
			$sqlDelete = "DELETE from $nombreTabla";
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
		//print("<br>Hago el load data.<br>");
		$i = 0;
		$loadTablas = 1;
		try {
			foreach ($arrayNombreTablas as $nombreTabla) {
				$archivo = $pathArchivo.$nombreTabla.".txt";
				if (filesize($archivo) > 0) {
					$gestor = fopen($archivo, "r");
					$contenido = fread($gestor, filesize($archivo));
					fclose($gestor);
					$insertLinea = "INSERT IGNORE INTO $nombreTabla VALUES ".$contenido;
					$dbhInternet->beginTransaction();
					//print($insertLinea."<br>");
					$dbhInternet->exec($insertLinea);
					$dbhInternet->commit();
				}
				
			}
		} catch (PDOException $e) {
			$loadTablas = 0;
			$descriError = $e->getMessage();
			$resultados[3] = array("etapa" => "Subida de Información", "estado" => "Error", "descripcion" => "ARCHIVO: ".$nombreTabla."<br>".$descriError);
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
		$dbhInternet->beginTransaction();
		foreach ($arrayNombreTablas as $nombreTabla) {
			$sqlControlTabla = "SELECT count(*) as total from $nombreTabla";
			//print($sqlControlTabla."<br>");
			$query = $dbhInternet->prepare($sqlControlTabla);
			$query->execute();
			$row = $query->fetch();
			$contador = $row['total'];
			
			$archivo = $pathArchivo.$nombreTabla.".txt";		
			$file = fopen ($archivo, "r"); 
			$num_lineas = 0; 
			while (!feof ($file)) { 
				if ($linea = fgets($file)){ 
				   $num_lineas++; 
				} 
			} 
			fclose ($file); 
			
			
			$control[$i] = array('tabla' => $nombreTabla, 'archivo' => $num_lineas, 'count' => $contador);
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
		$tablasMayores = array("pagos","detacuer","juicios","ddjjnopa");
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
	}
	
	if ($errorUpdate == 0) {
		$resultados[5] = array("etapa" => "Control de Subida", "estado" => "OK", "descripcion" => "");
		$carpetames = date("YmdGis");
		$directorioBK = "backupintrausimra/$carpetames/";
		if(!file_exists($directorioBK)) {
			mkdir ($directorioBK);
		} 
		$pathDirectorio = "archivos/";
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
			$directorio = opendir($pathDirectorio);
			while ($archivo = readdir($directorio)) {
				if (!is_dir($archivo)) {
					$pathArchivo = $pathDirectorio.$archivo;
					if (!unlink($pathArchivo)) {
						$errorBkARchivos = 1;
						$resultados[6] = array("etapa" => "BackUp Archivos", "estado" => "Error", "descripcion" => $descriError);
					}
				}
			}
			
			if ($errorBkARchivos == 0) {
				$resultados[6] = array("etapa" => "BackUp Archivos", "estado" => "OK", "descripcion" => "BackUp Path: ".$directorioBK);
				try {	
					if(strcmp("localhost",$maquina)==0) {
						$hostOspim = "localhost"; //para las pruebas...
					}
					$dbhInternet = new PDO("mysql:host=$hostUsimra;dbname=$baseUsimraIntranet",$usuarioUsimra ,$claveUsimra);
					$dbhInternet->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$dbhInternet->setAttribute(PDO::MYSQL_ATTR_LOCAL_INFILE, true);
					$dbhInternet->beginTransaction();
					$sqlBajoAcceso = "UPDATE usuarios SET acceso = 1, fechaactualizacion = '$today'";
					$dbhInternet->exec($sqlBajoAcceso);
					$dbhInternet->commit();
					$subidaAcceso = 1;
					$resultados[7] = array("etapa" => "Alta Acceso Usuario", "estado" => "OK", "descripcion" => "");
				} catch (PDOException $e) {
					$descriError = $e->getMessage();
					$resultados[7] = array("etapa" => "Alta Acceso Usuario", "estado" => "Error", "descripcion" => $descriError);
					$dbhInternet->rollback();
				}				
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
<title>.: Actualizacion Intra USIMRA :.</title>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
   	<p><input type="button" name="volver" value="Volver" onclick="location.href = 'moduloActualizacion.php'"/></p>
   	<h3>Resultado del Actualizacion Intranet U.S.I.M.R.A. </h3>
   	<h3>Fecha <?php echo invertirFecha($today) ?> </h3>
  	<h3 style="color:#0000FF">Tiempo de Proceso: <?php echo $enMintuos ?> Minutos</h3>
  	<h3>Procesos</h3>
   	<table border="1" align="center" width="800">
	  <tr>
		<th>Etapa</th>
		<th>Resultado</th>
		<th>Descripcion</th>
	  </tr>
<?php foreach ($resultados as $res) { ?>
		<tr>
			<td><?php echo $res['etapa'] ?></td>
			<td><?php echo $res['estado'] ?></td>
			<td><?php echo $res['descripcion'] ?></td>
		</tr>
<?php } ?>
  	</table>
  	<h3>Control</h3>
<?php if (sizeof($control) > 1) { ?>
	  <table border="1" align="center">
			<tr>
				<th>Tabla</th>
				<th>Archivo</th>
				<th>Count</th>
				<th>Control</th>
			</tr>
	<?php foreach ($control as $resultado) { ?>
			<tr>
				<td><?php echo $resultado['tabla'] ?></td>
				<td><?php echo $resultado['archivo'] ?></td>
				<td><?php echo $resultado['count'] ?></td>
		<?php 	$resta = (int)$resultado['archivo'] - (int)$resultado['count'];
				if ($resta != 0) { ?>
					<td><font color='#FF0000'><?php echo $resta ?></font></td>
		<?php	} else {  ?>
					<td>-</td>
		<?php	}  ?>
			</tr>
	<?php  } ?>
	  </table>
<?php }
	if ($resultados[5]['estado'] == "Error" or $resultados[6]['estado'] == "Error" or $resultados[7]['estado'] == "Error") { ?>
	  <p><input type="reset" name="volver2" value="Forzar Cierre Proceso" onclick="location.href = 'guardarArchivosBkup.php'" /></p><?php	} ?>
	  <p><input type="button" name="imprimir" value="Imprimir" onclick="window.print();" /></p>
</div>
</body>
</html>