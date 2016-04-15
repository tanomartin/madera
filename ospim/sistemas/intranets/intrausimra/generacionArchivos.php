<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
set_time_limit(0);
ini_set('memory_limit', '-1');
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."fechas.php");
$today = date('Y-m-d');
$timestamp1 = mktime(date("H"),date("i"),date("s"),date("n"),date("j"),date("Y")); 

//BANDERAS
$errorArchivos = 0;
$errorEscritura = 0;
$resultados = array();

//print("<br>Verifico si ya existen archivos<br>");
$pathArchivo = "archivos/";
$arrayNombreArchivo = array("empresa.txt","cabacuer.txt","detacuer.txt","cuoacuer.txt","ddjjnopa.txt","juicios.txt","pagos.txt","peranter.txt");
foreach ($arrayNombreArchivo as $nombreArc) {
	$archivo = $pathArchivo.$nombreArc;
	//print($archivo."<br>");
	if (file_exists ($archivo)) {
		$errorArc = "Ya existe un archivo ".$nombreArc." en la carpeta de la delegacion<br>";
		//print($errorArc);
		$errorArchivos = 1;
	}
}

if ($errorArchivos == 0) {
	$resultados[0] = array("etapa" => "Comprobacion Existencia Archivos", "estado" => "OK", "descripcion" => "");
	
	$i = 0;
	
	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbl = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbl->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbl->beginTransaction();
		
		foreach ($arrayNombreArchivo as $nombreArc) {
			$splitNombre = explode(".",$nombreArc);
			$tabla = $splitNombre[0];
			if(stripos($tabla,"empresa") !== FALSE) {
				$sqlLeeTablas = "SELECT j.codidelega, j.cuit, e.nombre, j.domireal, l.nomlocali, p.codzeus, j.numpostal, e.telefono1, e.iniobliusi
									FROM jurisdiccion j, empresas e, localidades l, provincia p
									WHERE j.cuit = e.cuit AND j.codlocali = l.codlocali AND j.codprovin = p.codprovin;";
			}
			if(stripos($tabla,"cabacuer") !== FALSE) {
				$sqlLeeTablas = "SELECT c.cuit, c.nroacuerdo, c.estadoacuerdo, c.fechaacuerdo, c.montoacuerdo
									FROM empresas e, cabacuerdosusimra c
									WHERE e.cuit = c.cuit;";
			}
			if(stripos($tabla,"detacuer") !== FALSE) {
				$sqlLeeTablas = "SELECT DISTINCT d.cuit, d.nroacuerdo, d.anoacuerdo, d.mesacuerdo
									FROM empresas e, cabacuerdosusimra c, detacuerdosusimra d
									WHERE e.cuit = c.cuit AND c.cuit = d.cuit AND c.nroacuerdo = d.nroacuerdo;";
			}
			if(stripos($tabla,"cuoacuer") !== FALSE) {
				$sqlLeeTablas = "SELECT t.cuit, t.nroacuerdo, t.nrocuota, t.montocuota, t.fechacuota, t.montopagada, t.fechapagada, t.sistemacancelacion, t.codigobarra
									FROM empresas e, cabacuerdosusimra c, cuoacuerdosusimra t
									WHERE e.cuit = c.cuit AND c.cuit = t.cuit AND c.nroacuerdo = t.nroacuerdo;";
			}
			if(stripos($tabla,"ddjjnopa") !== FALSE) {
				$sqlLeeTablas = "SELECT DISTINCT d.nrcuit, d.perano, d.permes
									FROM empresas e, ddjjusimra d
									WHERE e.cuit = d.nrcuit AND d.nrcuil = '99999999999' AND d.perano > 2010;";
			}
			if(stripos($tabla,"juicios") !== FALSE) {
				$sqlLeeTablas = "SELECT DISTINCT c.cuit, d.anojuicio, d.mesjuicio
									FROM empresas e, cabjuiciosusimra c, detjuiciosusimra d
									WHERE e.cuit = c.cuit AND c.nroorden = d.nroorden AND d.anojuicio > 2010;";
			}
			if(stripos($tabla,"pagos") !== FALSE) {
				$sqlLeeTablas = "SELECT s.cuit, s.anopago, s.mespago, s.nropago, s.fechapago, s.montopagado, s.sistemacancelacion, s.codigobarra
									FROM empresas e, seguvidausimra s
									WHERE e.cuit = s.cuit AND s.anopago > 2010;";
			}
			if(stripos($tabla,"peranter") !== FALSE) {
				$sqlLeeTablas = "SELECT p.cuit, p.mespago, p.anopago, p.mesanterior, p.anoanterior, p.nropago
									FROM empresas e, seguvidausimra s, periodosanterioresusimra p
									WHERE e.cuit = s.cuit AND s.anopago > 2010 AND s.periodoanterior = 1 AND s.cuit = p.cuit AND s.mespago = p.mespago AND s.anopago = p.anopago AND s.nropago = p.nropago AND p.anoanterior > 2010;";
			}
			
			$resLeeTablas = $dbl->query($sqlLeeTablas);
			
			if(!$resLeeTablas){
				$resultados[$i+1] = array("etapa" => "Lectura de Datos Tablas", "estado" => "Error", "descripcion" => "Sin Resultados para Tabla: ".$tabla."<br>");
			} else {
				$pathCompleto = $pathArchivo.$nombreArc;
				$punteroArchivo = fopen($pathCompleto, 'w') or die("Hubo un error al generar el archivo: ".$nombreArc." para escritura de datos<br>");
				
				if(stripos($tabla,"empresa") !== FALSE) {
					$totalRegistros = $resLeeTablas->rowCount();
					$totalLineas = 1;
					foreach($resLeeTablas as $contenidoTabla){
						if($totalLineas == $totalRegistros) {
							$finRegistro = '");';
						} else {
							$finRegistro = '"),';
						}
						$registroTabla = '('.$contenidoTabla[codidelega].',"'.$contenidoTabla[cuit].'","'.$contenidoTabla[nombre].'","'.$contenidoTabla[domireal].'","'.$contenidoTabla[nomlocali].'",'.$contenidoTabla[codzeus].','.$contenidoTabla[numpostal].','.$contenidoTabla[telefono1].',"'.$contenidoTabla[iniobliusi].$finRegistro;
						if(fwrite($punteroArchivo, $registroTabla."\n") === FALSE) {
							$errorEscritura = 1;
							$msgErrorEscritura = "Se produjo un error escribiendo los datos en el archivo: ".$nombreArc."<br>";
						} else {
							$totalLineas++;
						}
					}
					if($errorEscritura == 0) {
						$resultados[$i+1] = array("etapa" => "Escritura de Datos Tablas", "estado" => "OK", "descripcion" => "Datos para Tabla: ".$tabla." - Total Registros: ".($totalLineas-1)."<br>");
					} else {
						$resultados[$i+1] = array("etapa" => "Escritura de Datos Tablas", "estado" => "Error", "descripcion" => $msgErrorEscritura);
					}
				}
				
				if(stripos($tabla,"cabacuer") !== FALSE) {
					$totalRegistros = $resLeeTablas->rowCount();
					$totalLineas = 1;
					foreach($resLeeTablas as $contenidoTabla){
						if($totalLineas == $totalRegistros) {
							$finRegistro = ');';
						} else {
							$finRegistro = '),';
						}
						$registroTabla = '("'.$contenidoTabla[cuit].'",'.$contenidoTabla[nroacuerdo].','.$contenidoTabla[estadoacuerdo].',"'.$contenidoTabla[fechaacuerdo].'",'.$contenidoTabla[montoacuerdo].$finRegistro;
						if(fwrite($punteroArchivo, $registroTabla."\n") === FALSE) {
							$errorEscritura = 1;
							$msgErrorEscritura = "Se produjo un error escribiendo los datos en el archivo: ".$nombreArc."<br>";
						} else {
							$totalLineas++;
						}
					}
					if($errorEscritura == 0) {
						$resultados[$i+1] = array("etapa" => "Escritura de Datos Tablas", "estado" => "OK", "descripcion" => "Datos para Tabla: ".$tabla." - Total Registros: ".($totalLineas-1)."<br>");
					} else {
						$resultados[$i+1] = array("etapa" => "Escritura de Datos Tablas", "estado" => "Error", "descripcion" => $msgErrorEscritura);
					}
				}
				
				if(stripos($tabla,"detacuer") !== FALSE) {
					$totalRegistros = $resLeeTablas->rowCount();
					$totalLineas = 1;
					foreach($resLeeTablas as $contenidoTabla){
						if($totalLineas == $totalRegistros) {
							$finRegistro = ');';
						} else {
							$finRegistro = '),';
						}
						$registroTabla = '("'.$contenidoTabla[cuit].'",'.$contenidoTabla[nroacuerdo].','.$contenidoTabla[anoacuerdo].','.$contenidoTabla[mesacuerdo].$finRegistro;
						if(fwrite($punteroArchivo, $registroTabla."\n") === FALSE) {
							$errorEscritura = 1;
							$msgErrorEscritura = "Se produjo un error escribiendo los datos en el archivo: ".$nombreArc."<br>";
						} else {
							$totalLineas++;
						}
					}
					if($errorEscritura == 0) {
						$resultados[$i+1] = array("etapa" => "Escritura de Datos Tablas", "estado" => "OK", "descripcion" => "Datos para Tabla: ".$tabla." - Total Registros: ".($totalLineas-1)."<br>");
					} else {
						$resultados[$i+1] = array("etapa" => "Escritura de Datos Tablas", "estado" => "Error", "descripcion" => $msgErrorEscritura);
					}
				}
				
				if(stripos($tabla,"cuoacuer") !== FALSE) {
					$totalRegistros = $resLeeTablas->rowCount();
					$totalLineas = 1;
					foreach($resLeeTablas as $contenidoTabla){
						if($totalLineas == $totalRegistros) {
							$finRegistro = '");';
						} else {
							$finRegistro = '"),';
						}
						$registroTabla = '("'.$contenidoTabla[cuit].'",'.$contenidoTabla[nroacuerdo].','.$contenidoTabla[nrocuota].','.$contenidoTabla[montocuota].',"'.$contenidoTabla[fechacuota].'",'.$contenidoTabla[montopagada].',"'.$contenidoTabla[fechapagada].'","'.$contenidoTabla[sistemacancelacion].'","'.$contenidoTabla[codigobarra].$finRegistro;
						if(fwrite($punteroArchivo, $registroTabla."\n") === FALSE) {
							$errorEscritura = 1;
							$msgErrorEscritura = "Se produjo un error escribiendo los datos en el archivo: ".$nombreArc."<br>";
						} else {
							$totalLineas++;
						}
					}
					if($errorEscritura == 0) {
						$resultados[$i+1] = array("etapa" => "Escritura de Datos Tablas", "estado" => "OK", "descripcion" => "Datos para Tabla: ".$tabla." - Total Registros: ".($totalLineas-1)."<br>");
					} else {
						$resultados[$i+1] = array("etapa" => "Escritura de Datos Tablas", "estado" => "Error", "descripcion" => $msgErrorEscritura);
					}
				}
				
				if(stripos($tabla,"ddjjnopa") !== FALSE) {
					$totalRegistros = $resLeeTablas->rowCount();
					$totalLineas = 1;
					foreach($resLeeTablas as $contenidoTabla){
						if($totalLineas == $totalRegistros) {
							$finRegistro = ');';
						} else {
							$finRegistro = '),';
						}
						$registroTabla = '("'.$contenidoTabla[nrcuit].'",'.$contenidoTabla[perano].','.$contenidoTabla[permes].$finRegistro;
						if(fwrite($punteroArchivo, $registroTabla."\n") === FALSE) {
							$errorEscritura = 1;
							$msgErrorEscritura = "Se produjo un error escribiendo los datos en el archivo: ".$nombreArc."<br>";
						} else {
							$totalLineas++;
						}
					}
					if($errorEscritura == 0) {
						$resultados[$i+1] = array("etapa" => "Escritura de Datos Tablas", "estado" => "OK", "descripcion" => "Datos para Tabla: ".$tabla." - Total Registros: ".($totalLineas-1)."<br>");
					} else {
						$resultados[$i+1] = array("etapa" => "Escritura de Datos Tablas", "estado" => "Error", "descripcion" => $msgErrorEscritura);
					}
				}
				
				if(stripos($tabla,"juicios") !== FALSE) {
					$totalRegistros = $resLeeTablas->rowCount();
					$totalLineas = 1;
					foreach($resLeeTablas as $contenidoTabla){
						if($totalLineas == $totalRegistros) {
							$finRegistro = ');';
						} else {
							$finRegistro = '),';
						}
						$registroTabla = '("'.$contenidoTabla[cuit].'",'.$contenidoTabla[anojuicio].','.$contenidoTabla[mesjuicio].$finRegistro;
						if(fwrite($punteroArchivo, $registroTabla."\n") === FALSE) {
							$errorEscritura = 1;
							$msgErrorEscritura = "Se produjo un error escribiendo los datos en el archivo: ".$nombreArc."<br>";
						} else {
							$totalLineas++;
						}
					}
					if($errorEscritura == 0) {
						$resultados[$i+1] = array("etapa" => "Escritura de Datos Tablas", "estado" => "OK", "descripcion" => "Datos para Tabla: ".$tabla." - Total Registros: ".($totalLineas-1)."<br>");
					} else {
						$resultados[$i+1] = array("etapa" => "Escritura de Datos Tablas", "estado" => "Error", "descripcion" => $msgErrorEscritura);
					}
				}
				
				if(stripos($tabla,"pagos") !== FALSE) {
					$totalRegistros = $resLeeTablas->rowCount();
					$totalLineas = 1;
					foreach($resLeeTablas as $contenidoTabla){
						if($totalLineas == $totalRegistros) {
							$finRegistro = '");';
						} else {
							$finRegistro = '"),';
						}
						$registroTabla = '('.$contenidoTabla[cuit].',"'.$contenidoTabla[anopago].'","'.$contenidoTabla[mespago].'","'.$contenidoTabla[nropago].'",'.$contenidoTabla[fechapago].','.$contenidoTabla[montopagado].',"'.$contenidoTabla[sistemacancelacion].'","'.$contenidoTabla[codigobarra].$finRegistro;
						if(fwrite($punteroArchivo, $registroTabla."\n") === FALSE) {
							$errorEscritura = 1;
							$msgErrorEscritura = "Se produjo un error escribiendo los datos en el archivo: ".$nombreArc."<br>";
						} else {
							$totalLineas++;
						}
					}
					if($errorEscritura == 0) {
						$resultados[$i+1] = array("etapa" => "Escritura de Datos Tablas", "estado" => "OK", "descripcion" => "Datos para Tabla: ".$tabla." - Total Registros: ".($totalLineas-1)."<br>");
					} else {
						$resultados[$i+1] = array("etapa" => "Escritura de Datos Tablas", "estado" => "Error", "descripcion" => $msgErrorEscritura);
					}
				}
				
				if(stripos($tabla,"peranter") !== FALSE) {
					$totalRegistros = $resLeeTablas->rowCount();
					$totalLineas = 1;
					foreach($resLeeTablas as $contenidoTabla){
						if($totalLineas == $totalRegistros) {
							$finRegistro = '");';
						} else {
							$finRegistro = '"),';
						}
						$registroTabla = '('.$contenidoTabla[cuit].',"'.$contenidoTabla[mespago].'","'.$contenidoTabla[anopago].'","'.$contenidoTabla[mesanterior].'",'.$contenidoTabla[anoanterior].','.$contenidoTabla[nropago].$finRegistro;
						if(fwrite($punteroArchivo, $registroTabla."\n") === FALSE) {
							$errorEscritura = 1;
							$msgErrorEscritura = "Se produjo un error escribiendo los datos en el archivo: ".$nombreArc."<br>";
						} else {
							$totalLineas++;
						}
					}
					if($errorEscritura == 0) {
						$resultados[$i+1] = array("etapa" => "Escritura de Datos Tablas", "estado" => "OK", "descripcion" => "Datos para Tabla: ".$tabla." - Total Registros: ".($totalLineas-1)."<br>");
					} else {
						$resultados[$i+1] = array("etapa" => "Escritura de Datos Tablas", "estado" => "Error", "descripcion" => $msgErrorEscritura);
					}
				}
				
				fclose($punteroArchivo);
			}
			$i++;
		}
		
	} catch (PDOException $e) {
		$descriError = $e->getMessage();
		$resultados[$i+1] = array("etapa" => "Lectura de Datos Tablas", "estado" => "Error", "descripcion" => $descriError);
		//print("$descriError<br><br>");
		$dbl->rollback();
	}
} else {
	$resultados[0] = array("etapa" => "Comprobacion Existencia Archivos", "estado" => "Error", "descripcion" => $errorArc);
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

<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p class="Estilo2"><span style="text-align:center">
    <input type="button" name="volver" value="Volver" onclick="location.href = 'menuActualizacionUsimra.php'" />
  </span></p>
  <p class="Estilo2">Resultado de la Generacion de Archivos Intranet U.S.I.M.R.A.</p>
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
 	<p><input type="button" name="imprimir" value="Imprimir" onclick="window.print();" /></p>
</div>
</body>
</html>