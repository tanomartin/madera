<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
set_time_limit(0);
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."claves.php");
include($libPath."fechas.php");
$today = date('Y-m-d');
$delegacion = $_POST['selectDelegacion'];
print("DELE A ACTULAIZAR ".$delegacion."<br>");

//BANDERAS
$errorArchivos = 0;
$bajaacceso = 0;
$deleteTablas = 0;
$loadTablas = 0;
$control = array();

print("<br>Verifico que existan los archivos<br>");
$pathArchivo = "archivos/".$delegacion."/";
$nombreArchivoTotalizador =  "tota".$delegacion.".txt";
$arrayNombreArchivo = array("apoind.txt", "bajafam.txt", "bajatit.txt", "cabacuer.txt", "cabjur.txt", "cuijur.txt", "cuoacuer.txt", "detacuer.txt", "empresa.txt", "familia.txt", "juicios.txt", "pagos.txt", "titular.txt", $nombreArchivoTotalizador );
foreach ($arrayNombreArchivo as $nombreArc) {
	$archivo = $pathArchivo.$nombreArc;
	print($archivo."<br>");
	if (!file_exists ($archivo)) {
		print("No existe el archivo ".$archivo."<br>");
		$errorArc = "No existe el archivo ".$archivo."<br>";
		$errorArchivos = 1;
	}
}

if ($errorArchivos == 0) {
	print("<br>Doy de baja el acceso a la delegacion<br>");
	$hostOspim = "localhost"; //para las pruebas...
	$dbhInternet = new PDO("mysql:host=$hostOspim;dbname=$baseOspimIntranet",$usuarioOspim ,$claveOspim,array(PDO::MYSQL_ATTR_LOCAL_INFILE => 1,));
   	$dbhInternet->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbhInternet->setAttribute(PDO::MYSQL_ATTR_LOCAL_INFILE, true);
	//Bajo el acceso de la delegacion
	$dbhInternet->beginTransaction();
	try {
		$sqlBajoAcceso = "UPDATE usuarios SET acceso = 0 WHERE delcod = $delegacion";
		print($sqlBajoAcceso."<br>");
		$dbhInternet->exec($sqlBajoAcceso);
		$dbhInternet->commit();
		$bajaacceso = 1;
	} catch (PDOException $e) {
		$descriError = $e->getMessage();
		$control[0] = array("NO SE PUDO DAR DE BAJA EL ACCESO DE LA DELEGACIÓN", $descriError);
		print("$descriError<br><br>");
		$dbhInternet->rollback();	
	}
	if ($bajaacceso == 1) {
		$i = 0;
		print("<br>Deleteo las tablas de esa delegación.<br>");
		$nombreTablaApoi = "apoi".$delegacion;
		$nombreTablacuij = "cuij".$delegacion;

	   /* $arraySqlDelete = array(
		"DELETE from ".$nombreTablaApoi,
		"DELETE from bajafam where delcod = ".$delegacion,
		"DELETE from bajatit where delcod = ".$delegacion,
		"DELETE from cabacuer where nrcuit in (SELECT nrcuit FROM empresa WHERE delcod = ".$delegacion.")",
		"DELETE from cabjur where delcod = ".$delegacion,
		"DELETE from ".$nombreTablacuij,
		"DELETE from cuoacuer where nrcuit in (SELECT nrcuit FROM empresa WHERE delcod = ".$delegacion.")",
		"DELETE from detacuer where nrcuit in (SELECT nrcuit FROM empresa WHERE delcod = ".$delegacion.")",
		"DELETE from familia where delcod = ".$delegacion,
		"DELETE from juicios where delcod = ".$delegacion,
		"DELETE from pagos where delcod = ".$delegacion,
		"DELETE from titular where delcod = ".$delegacion,
		"DELETE from empresa where delcod = ".$delegacion);
		var_dump($arraySqlDelete);*/
		
		$arraySqlDelete = array();
		$i = 0;
		$pathTotal = $pathArchivo.$nombreArchivoTotalizador;
		$file = fopen($pathTotal, "r") or exit("Unable to open file!");
		while(!feof($file)) {
			$linea = fgets($file);
			if (sizeof($linea) > 0) {
				$contenido = explode(":",$linea);
				$tabla = $contenido[0];
				$total = (int)$contenido[1];
				
				if (stripos($tabla,$delegacion) !== FALSE) {
					$sqlControlTabla = "DELETE from $tabla";
				} else {
					if (stripos($tabla,"acuer") !== FALSE) {
						$sqlControlTabla = "DELETE from $tabla WHERE nrcuit in (SELECT nrcuit FROM empresa WHERE delcod = ".$delegacion.")";
					} else {
						$sqlControlTabla = "DELETE from $tabla WHERE delcod = $delegacion";
					}
				}
				$arraySqlDelete[$i] = $sqlControlTabla;
				$i++;
			}
		}
		
		foreach ($arraySqlDelete as $sqlDelete) { 
			try {
				$dbhInternet->beginTransaction();
				print($sqlDelete."<br>");
				$dbhInternet->exec($sqlDelete);
				$dbhInternet->commit();
				$deleteTablas = 1;
			} catch (PDOException $e) {
				$deleteTablas = 0;
				$descriError = $e->getMessage();
				$control[0] = array("NO SE PUDO ELIMINAR LAS TABALAS DE LA DELEGACION A ACTUALIZAR", $descriError);
				print("$descriError<br><br>");
				$dbhInternet->rollback();
			}
		}
	}
	
	if ($deleteTablas == 1) {
		print("<br>Hago el load data.<br>");
		$i = 0;
		$nombreTablaApoi = "apoi".$delegacion;
		$nombreTablacuij = "cuij".$delegacion;
		$arraySqlLoad = array();
		foreach ($arrayNombreArchivo as $nombreArc) {
			$pathCompleto = $pathArchivo.$nombreArc;
			$splitNombre = explode('.',$nombreArc);
			$tabla = $splitNombre[0];
			if ($nombreArc == 'apoind.txt') {
				$tabla = $nombreTablaApoi;
			}
			if ($nombreArc == 'cuijur.txt') {
				$tabla = $nombreTablacuij;
			}
			if ($nombreArc != $nombreArchivoTotalizador) {
				$sqlLoad = "LOAD DATA LOCAL INFILE '".$pathCompleto."' REPLACE INTO TABLE ".$tabla." FIELDS TERMINATED BY '|' LINES TERMINATED BY '\\n'";
				$arraySqlLoad[$i] = $sqlLoad;
				$i++;
			}
		}
	//	var_dump($arraySqlLoad);
		foreach ($arraySqlLoad as $sqlLoad) { 
			try {
				$dbhInternet->beginTransaction();
				print($sqlLoad."<br>");
				$dbhInternet->exec($sqlLoad);
				$dbhInternet->commit();
				$loadTablas = 1;
			} catch (PDOException $e) {
				$loadTablas = 0;
				$descriError = $e->getMessage();
				$control[0] = array("NO SE PUDO REALIZAR EL LOAD DE LOS ARCHIVOS DE LA DELEGACION", $descriError);
				print("$descriError<br><br>");
				$dbhInternet->rollback();
			}
		}
	}
	
	if ($loadTablas == 1) {
		$subidaAcceso = 0;
		print("<br>Actualizo la fecha de actualizacion a today y levanto el acceso.<br>");
		try {	
			$dbhInternet->beginTransaction();
			//ver que fecha queda.
			$sqlBajoAcceso = "UPDATE usuarios SET acceso = 1, fechaactualizacion = '$today' WHERE delcod = $delegacion";
			print($sqlBajoAcceso."<br>");
			$dbhInternet->exec($sqlBajoAcceso);
			$dbhInternet->commit();
			$subidaAcceso = 1;
		} catch (PDOException $e) {
			$descriError = $e->getMessage();
			print("$descriError<br><br>");
			$control[0] = array("NO SE PUDO DAR DE ALTA EL ACCESO A LA DELEGACION", $descriError);
			$dbhInternet->rollback();
		}
	}
} else {
	$control[0] = array("NO SE ENCONTRÓ ALGUN ARCHIVO PARA REALIZAR LA ACTUALIZACION", $errorArc);
}

if (($errorArchivos == 0) && ($bajaacceso == 1) && ($deleteTablas == 1) && ($loadTablas == 1) && ($subidaAcceso == 1)) {
	print("<br>Hacer count de cada tabla actualizada y comprar con el archivo totalizador.<br>");
	$pathTotal = $pathArchivo.$nombreArchivoTotalizador;
	$file = fopen($pathTotal, "r") or exit("Unable to open file!");
	$i = 0;
	try {
		$dbhInternet->beginTransaction();
		while(!feof($file)) {
			$linea = fgets($file);
			if (sizeof($linea) > 0) {
				$contenido = explode(":",$linea);
				$tabla = $contenido[0];
				$total = (int)$contenido[1];
				if (stripos($tabla,$delegacion) !== FALSE) {
					$sqlControlTabla = "SELECT count(*) as total from $tabla";
				} else {
					if (stripos($tabla,"acuer") !== FALSE) {
						$sqlControlTabla = "SELECT count(*) as total from $tabla t, empresa e WHERE e.delcod = $delegacion and e.nrcuit = t.nrcuit";
					} else {
						$sqlControlTabla = "SELECT count(*) as total from $tabla WHERE delcod = $delegacion";
					}
				}
				print($sqlControlTabla."<br>");
				$query = $dbhInternet->prepare($sqlControlTabla);
				$query->execute();
				$row = $query->fetch();
				$contador = $row['total'];
				$control[$i] = array('tabla' => $tabla, 'archivo' => $total, 'count' => $contador);
				$i++;
			}
		}
		fclose($file);
	} catch (PDOException $e) {
		$descriError = $e->getMessage();
		$control[0] = array("NO SE PUDO REALIZAR EL LOAD DE LOS ARCHIVOS DE LA DELEGACION", $descriError);
		print("$descriError<br><br>");
		$dbhInternet->rollback();
	}
}

$ahora = date("Y-n-j H:i:s"); 
$_SESSION["ultimoAcceso"] = $ahora;

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
  <p class="Estilo2">Resultado del Actualizacion Intranet O.S.P.I.M.</p>
  <p class="Estilo2">Delegación <?php echo $delegacion ?> - Fecha <?php echo invertirFecha($today) ?> </p>
  <?php if (sizeof($control) > 1) { ?>
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
			}
		?>
	  </table>
	  <p><span style="text-align:center">
	  	<input type="reset" name="volver2" value="BackUp Archivos" onclick="location.href = 'guardarArchivosBkup.php?delega=<?php echo $delegacion ?>'" align="center"/>
	  </span></p>
<?php	} else {
			print("<font color='#FF0000'>".$control[0][0]."</font><br>".$control[0][1]);
		}
?>
	<p><input type="button" name="imprimir" value="Imprimir" onclick="window.print();" align="center"/></p>
</div>
</body>
</html>