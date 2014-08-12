<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
set_time_limit(0);
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."claves.php");
include($libPath."fechas.php");
$today = date('Y-m-d');
$horaInicio = date("H:i:s");
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
$arrayNombreArchivo = array("apoi$delegacion.txt", "bajafam.txt", "bajatit.txt", "cabacuer.txt", "cabjur.txt", "cuij$delegacion.txt", "cuoacuer.txt", "detacuer.txt", "empresa.txt", "familia.txt", "juicios.txt", "pagos.txt", "titular.txt");
//$arrayNombreArchivo = array("apoi$delegacion.txt");
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
	$maquina = $_SERVER['SERVER_NAME'];
	if(strcmp("localhost",$maquina)==0) {
		$hostOspim = "localhost"; //para las pruebas...
	}
	$dbhInternet = new PDO("mysql:host=$hostOspim;dbname=$baseOspimIntranet",$usuarioOspim ,$claveOspim);
   	$dbhInternet->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
		$arraySqlDelete = array();
		$i = 0;
		
		foreach ($arrayNombreArchivo as $nombreArc) {
			$splitNombre = explode(".",$nombreArc);
			$tabla = $splitNombre[0];
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
		foreach ($arrayNombreArchivo as $nombreArc) {
			print("<br>Hago el load data de $nombreArc.<br>");
			//$n = 0;
			$insertArray = array();
			$pathCompleto = $pathArchivo.$nombreArc;
			$splitNombre = explode('.',$nombreArc);
			$tabla = $splitNombre[0];
			$file = fopen ($pathCompleto, "r"); 
			$insertLinea = "INSERT IGNORE INTO $tabla VALUES ";
			$cuerpo = "";
			while (!feof($file)) {
				if ($linea = fgets($file)){ 
					if (strlen($linea) > 0) {
						$values = "";
						$lineaExplode = explode("|",$linea);
						$cantidadCampos = sizeof($lineaExplode);
						for ($i=0; $i < $cantidadCampos; $i++) {
							$values = $values.'"'.$lineaExplode[$i].'",'; 
							//print($values);
						}
						$cuerpo = "(".substr($values,0,strlen($values)-1)."),".$cuerpo;	
					}	
				} 
			} 
			$insertLinea = $insertLinea.substr($cuerpo,0,strlen($cuerpo)-1);
			//$n++;
			try {
				$dbhInternet->beginTransaction();
				//foreach ($insertArray as $insert) {
					//print($insertLinea."<br>");
					$dbhInternet->exec($insertLinea);
				//}
				$dbhInternet->commit();
			} catch (PDOException $e) {
				$loadTablas = 0;
				$descriError = $e->getMessage();
				$control[0] = array("NO SE PUDO REALIZAR EL LOAD DE LOS ARCHIVOS DE LA DELEGACION", $descriError);
				print("$descriError<br><br>");
				$dbhInternet->rollback();
			}
		}
	}
	$loadTablas = 1;	
} else {
	$control[0] = array("NO SE ENCONTRÓ ALGUN ARCHIVO PARA REALIZAR LA ACTUALIZACION", $errorArc);
}

if (($errorArchivos == 0) && ($bajaacceso == 1) && ($deleteTablas == 1) && ($loadTablas == 1)) {
	print("<br>Hacer count de cada tabla actualizada y comprar con el archivo totalizador.<br>");
	$i = 0;
	try {
		foreach ($arrayNombreArchivo as $nombreArc) {
			$splitNombre = explode(".",$nombreArc);
			$tabla = $splitNombre[0];
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
			fclose ($file); 
			$control[$i] = array('tabla' => $tabla, 'archivo' => $num_lineas, 'count' => $contador);
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
$horaFin = date("H:i:s");
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
  <p class="Estilo2">Resultado del Actualizacion Intranet O.S.P.I.M.</p>
  <p class="Estilo2">Delegación <?php echo $delegacion ?> - Fecha <?php echo invertirFecha($today) ?> </p>
   <p class="Estilo2">Hora Inicio <?php echo $horaInicio ?> - Hora Fin <?php echo $horaFin ?> </p>
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