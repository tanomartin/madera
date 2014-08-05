<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
set_time_limit(0);
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."claves.php");
include($libPath."fechas.php");
$today = date('Y-m-d');

//BANDERAS
$errorArchivos = 0;
$bajaacceso = 0;
$deleteTablas = 0;
$loadTablas = 0;
$control = array();
$arrayNombreTablas = array("cabacuer", "cuoacuer", "ddjjnopa", "detacuer", "empresa", "juicios", "pagos" ,"peranter");

print("<br>Verifico que existan el archivo<br>");
$pathArchivo = "archivos/";
foreach ($arrayNombreTablas as $nombreArc) {
	$archivo = $pathArchivo.$nombreArc.".txt";
	print($archivo."<br>");
	if (!file_exists ($archivo)) {
		print("No existe el archivo ".$archivo."<br>");
		$errorArc = "No existe el archivo ".$archivo."<br>";
		$errorArchivos = 1;
	}
}

if ($errorArchivos == 0) {
	print("<br>Doy de baja el acceso todos los accesos<br>");
	$hostUsimra = "localhost"; //para las pruebas...
	$dbhInternet = new PDO("mysql:host=$hostUsimra;dbname=$baseUsimraIntranet",$usuarioUsimra ,$claveUsimra,array(PDO::MYSQL_ATTR_LOCAL_INFILE => 1,));
   	$dbhInternet->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbhInternet->setAttribute(PDO::MYSQL_ATTR_LOCAL_INFILE, true);

	try {
		$dbhInternet->beginTransaction();
		$sqlBajoAcceso = "UPDATE usuarios SET acceso = 0";
		print($sqlBajoAcceso."<br>");
		$dbhInternet->exec($sqlBajoAcceso);
		$dbhInternet->commit();
		$bajaacceso = 1;
	} catch (PDOException $e) {
		$descriError = $e->getMessage();
		$control[0] = array("NO SE PUDO DAR DE BAJA LOS ACCESOS", $descriError);
		print("$descriError<br><br>");
		$dbhInternet->rollback();	
	}
	
	if ($bajaacceso == 1) {
		$i = 0;
		print("<br>Deleteo las tablas<br>");	
		foreach ($arrayNombreTablas as $nombreTabla) {
			$sqlDelete = "DELETE from $nombreTabla";
			try {
				$dbhInternet->beginTransaction();
				print($sqlDelete."<br>");
				$dbhInternet->exec($sqlDelete);
				$dbhInternet->commit();
				$deleteTablas = 1;
			} catch (PDOException $e) {
				$deleteTablas = 0;
				$descriError = $e->getMessage();
				$control[0] = array("NO SE PUDO ELIMINAR LA TABALA $nombreTabla", $descriError);
				print("$descriError<br><br>");
				$dbhInternet->rollback();
			}
		}
	}
	
	if ($deleteTablas == 1) {
		print("<br>Hago el load data.<br>");
		$i = 0;
		$arraySqlLoad = array();
		foreach ($arrayNombreTablas as $nombreTabla) {
			$archivo = $pathArchivo.$nombreTabla.".txt";
			//VER como va a quedar si con \\r\\n (CRLF) o solo con \\n (LF)
			$sqlLoad = "LOAD DATA LOCAL INFILE '".$archivo."' REPLACE INTO TABLE ".$nombreTabla." FIELDS TERMINATED BY '|' LINES TERMINATED BY '\\n'";
			try {
				$dbhInternet->beginTransaction();
				print($sqlLoad."<br>");
				$dbhInternet->exec($sqlLoad);
				$dbhInternet->commit();
				$loadTablas = 1;
			} catch (PDOException $e) {
				$loadTablas = 0;
				$descriError = $e->getMessage();
				$control[0] = array("NO SE PUDO REALIZAR EL LOAD DEL ARCHIVO $nombreTabla", $descriError);
				print("$descriError<br><br>");
				$dbhInternet->rollback();
			}
		}
	}
} else {
	$control[0] = array("NO SE ENCONTRARON LOS ARCHIVO PARA REALIZAR LA ACTUALIZACION", $errorArc);
}

if (($errorArchivos == 0) && ($bajaacceso == 1) && ($deleteTablas == 1) && ($loadTablas == 1)) {
	print("<br>Hacer count de cada tabla actualizada y comprar con el archivo totalizador.<br>");
	$i = 0;
	try {
		$dbhInternet->beginTransaction();
		foreach ($arrayNombreTablas as $nombreTabla) {
			$sqlControlTabla = "SELECT count(*) as total from $nombreTabla";
			print($sqlControlTabla."<br>");
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
		$descriError = $e->getMessage();
		$control[0] = array("NO SE PUDO REALIZAR EL COUNT DE LAS TABLAS DE LA DELEGACION", $descriError);
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
  <p class="Estilo2">Resultado del Actualizacion Intranet U.S.I.M.R.A.</p>
  <p class="Estilo2">Fecha <?php echo invertirFecha($today) ?> </p>
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
	  	<input type="reset" name="volver2" value="Cerrar Proceso" onclick="location.href = 'guardarArchivosBkup.php?delega=<?php echo $delegacion ?>'" align="center"/>
	  </span></p>
<?php	} else { ?>
			<p class="Estilo2"><span style="text-align:center">
				<input type="reset" name="volver" value="Volver" onclick="location.href = 'moduloActualizacion.php'" align="center"/>
			</span></p>
<?php		print("<font color='#FF0000'>".$control[0][0]."</font><br>".$control[0][1]);
		}
?>
	<p><input type="button" name="imprimir" value="Imprimir" onclick="window.print();" align="center"/></p>
</div>
</body>
</html>