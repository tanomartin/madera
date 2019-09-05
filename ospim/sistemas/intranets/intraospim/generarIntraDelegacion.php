<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
set_time_limit(0);
ini_set('memory_limit', '-1');
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."fechas.php");
$today = date('Y-m-d');
$timestamp1 = mktime(date("H"),date("i"),date("s"),date("n"),date("j"),date("Y")); 
$delegacion = $_GET['delcod'];
$anoLimite = date("Y") - 6;

//BANDERAS
$errorArchivos = 0;
$errorEscritura = 0;
$resultados = array();

//print("<br>Verifico si ya existen archivos<br>");
$pathArchivo = "archivos/".$delegacion."/";
$arrayNombreArchivo = array("empresa.txt","titular.txt","familia.txt","bajatit.txt","bajafam.txt","cabjur.txt","cuij$delegacion.txt","pagos.txt","apoi$delegacion.txt", "cabacuer.txt","detacuer.txt","cuoacuer.txt","juicios.txt","discapacitados.txt","requerimientos.txt");
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
				$sqlLeeTablas="SELECT j.codidelega AS del, e.nombre AS nom, e.domilegal AS dom, l.nomlocali AS loc, p.codzeus AS pro, e.numpostal AS cpo, CONCAT(e.ddn1,e.telefono1) AS tel, e.iniobliosp AS fio, e.cuit AS cui 
								FROM jurisdiccion j, empresas e, localidades l, provincia p 
								WHERE j.codidelega = '$delegacion' AND j.cuit = e.cuit AND e.codlocali = l.codlocali AND e.codprovin = p.codprovin";
			}
			if(stripos($tabla,"titular") !== FALSE) {
				$sqlLeeTablas="SELECT t.nroafiliado AS naf, t.fechaobrasocial AS fos, t.apellidoynombre AS nom, t.tipodocumento AS tdo, t.nrodocumento AS ndo, t.fechanacimiento AS fna, t.sexo AS sex, t.estadocivil AS eci, t.nacionalidad AS nac, t.domicilio AS dom, l.nomlocali AS loc, p.codzeus AS pro, t.numpostal AS cpo, t.cuitempresa AS cue, t.codidelega AS del, t.fechaempresa AS fem, t.categoria  AS cat, t.fechacarnet AS fca, t.cuil AS cua, t.tipoafiliado AS taf 
								FROM titulares t, localidades l, provincia p 
								WHERE t.codidelega = '$delegacion' AND t.codlocali = l.codlocali AND t.codprovin = p.codprovin";
			}
			if(stripos($tabla,"familia") !== FALSE) {
				$sqlLeeTablas="SELECT f.nroafiliado AS naf, f.nroorden AS nor, f.tipoparentesco AS tpa, f.estudia AS est, f.discapacidad AS dis, f.apellidoynombre AS nom, f.tipodocumento AS tdo, f.nrodocumento AS ndo, f.fechanacimiento AS fna, f.sexo AS sex, f.fechaobrasocial AS fos, f.fechacarnet AS fca, f.cuil AS cua, t.codidelega AS del 
								FROM familiares f, titulares t 
								WHERE f.nroafiliado = t.nroafiliado AND t.codidelega = '$delegacion'";
			}
			if(stripos($tabla,"bajatit") !== FALSE) {
				$sqlLeeTablas="SELECT t.nroafiliado AS naf, t.fechaobrasocial AS fos, t.fechabaja AS fba, t.apellidoynombre AS nom, t.tipodocumento AS tdo, t.nrodocumento AS ndo, t.fechanacimiento AS fna, t.sexo AS sex, t.estadocivil AS eci, t.nacionalidad AS nac, t.domicilio AS dom, l.nomlocali AS loc, p.codzeus AS pro, t.numpostal AS cpo, t.cuitempresa AS cue, t.codidelega AS del, t.fechaempresa AS fem, t.categoria AS cat, t.fechacarnet AS fca, t.cuil AS cua, t.tipoafiliado AS taf 
								FROM titularesdebaja t, localidades l, provincia p 
								WHERE t.codidelega = '$delegacion' AND t.codlocali = l.codlocali AND t.codprovin = p.codprovin";
			}
			if(stripos($tabla,"bajafam") !== FALSE) {
				$sqlLeeTablas="SELECT f.nroafiliado AS naf, f.nroorden AS nor, f.tipoparentesco AS tpa, f.estudia AS est, f.discapacidad AS dis, f.apellidoynombre AS nom, f.tipodocumento AS tdo, f.nrodocumento AS ndo, f.fechanacimiento AS fna, f.sexo AS sex, f.fechaobrasocial AS fos, f.fechabaja AS fba, f.fechacarnet AS fca, f.cuil AS cua, t.codidelega AS del 
								FROM familiaresdebaja f, titularesdebaja t 
								WHERE f.nroafiliado = t.nroafiliado AND t.codidelega = '$delegacion'";
			}
			if(stripos($tabla,"cabjur") !== FALSE) {
				$sqlLeeTablas="SELECT j.codidelega AS del, c.cuit AS cui, c.anoddjj AS ano, c.mesddjj AS mes, c.totalpersonal AS tpe, c.totalremundeclarada AS tre, c.totalremundecreto AS tde 
								FROM jurisdiccion j, cabddjjospim c 
								WHERE j.codidelega = '$delegacion' AND j.cuit = c.cuit AND c.anoddjj > $anoLimite";
			}
			if(stripos($tabla,$delegacion) !== FALSE) {
				if(stripos($tabla,"cuij") !== FALSE) {
					$sqlLeeTablas="SELECT j.codidelega AS del, d.cuit AS cue, d.anoddjj AS ano, d.mesddjj AS mes, d.cuil AS cua, d.remundeclarada AS rem 
									FROM jurisdiccion j, detddjjospim d, empresas e
									WHERE j.codidelega = '$delegacion' AND j.cuit = d.cuit AND d.cuit = e.cuit AND d.anoddjj > $anoLimite";
				}
				if(stripos($tabla,"apoi") !== FALSE) {
					$sqlLeeTablas="SELECT j.codidelega AS del, a.cuit AS cue, a.cuil AS cua, a.anopago AS ano, a.mespago AS mes, a.concepto AS con, a.fechapago AS fpa, a.debitocredito AS deb, a.importe AS imp 
									FROM jurisdiccion j, afiptransferencias a, empresas e
									WHERE j.codidelega = '$delegacion' AND j.cuit = a.cuit AND a.cuit = e.cuit AND a.anopago > $anoLimite AND a.concepto in('381','C14','T14','T55')";
				}
			}
			if(stripos($tabla,"pagos") !== FALSE) {
				$sqlLeeTablas="SELECT j.codidelega AS del, a.cuit AS cui, a.anopago AS ano, a.mespago AS mes, a.debitocredito AS deb, a.concepto AS con, a.fechapago AS fpa, a.importe AS imp 
								FROM jurisdiccion j, afipprocesadas a, empresas e 
								WHERE j.codidelega = '$delegacion' AND j.cuit = a.cuit AND a.cuit = e.cuit AND a.anopago > $anoLimite";
			}
			if(stripos($tabla,"cabacuer") !== FALSE) {
				$sqlLeeTablas="SELECT c.cuit AS cui, c.nroacuerdo AS nac, c.tipoacuerdo AS tac, c.estadoacuerdo AS eac, c.fechaacuerdo AS fac, c.montoacuerdo AS mac 
								FROM jurisdiccion j, cabacuerdosospim c, empresas e
								WHERE j.codidelega = '$delegacion' AND j.cuit = c.cuit AND c.cuit = e.cuit";
			}
			if(stripos($tabla,"detacuer") !== FALSE) {
				$sqlLeeTablas="SELECT DISTINCT d.cuit AS cui, d.nroacuerdo AS nac, d.anoacuerdo AS ano, d.mesacuerdo AS mes 
								FROM jurisdiccion j, detacuerdosospim d 
								WHERE j.codidelega = '$delegacion' AND j.cuit = d.cuit AND d.anoacuerdo > $anoLimite";
			}
			if(stripos($tabla,"cuoacuer") !== FALSE) {
				$sqlLeeTablas="SELECT c.cuit AS cui, c.nroacuerdo AS nac, c.nrocuota AS ncu, c.montocuota AS mcu, c.fechacuota AS fcu, c.montopagada AS mpa, c.fechapagada AS fpa 
								FROM jurisdiccion j, cuoacuerdosospim c, empresas e
								WHERE j.codidelega = '$delegacion' AND j.cuit = c.cuit AND c.cuit = e.cuit";
			}
			if(stripos($tabla,"juicios") !== FALSE) {
				$sqlLeeTablas="SELECT DISTINCT c.cuit AS cui, d.anojuicio AS ano, d.mesjuicio AS mes 
								FROM jurisdiccion j, cabjuiciosospim c, detjuiciosospim d 
								WHERE j.codidelega = '$delegacion' AND j.cuit = c.cuit AND c.nroorden = d.nroorden AND d.anojuicio > $anoLimite";
			}
			if(stripos($tabla,"discapacitados") !== FALSE) {
				$sqlLeeTablas="SELECT d.nroafiliado as naf, d.nroorden as nrd, t.codidelega as delalta, b.codidelega as delbaja
     								FROM discapacitados d
									LEFT JOIN titulares t ON d.nroafiliado =  t.nroafiliado
									LEFT JOIN titularesdebaja b ON d.nroafiliado =  b.nroafiliado
									WHERE t.codidelega = $delegacion or b.codidelega = $delegacion";
			}
			if(stripos($tabla,"requerimientos") !== FALSE) {
				$sqlLeeTablas="SELECT r.cuit, r.nrorequerimiento, d.anofiscalizacion, d.mesfiscalizacion
									FROM reqfiscalizospim r, detfiscalizospim d, jurisdiccion j
									WHERE j.codidelega = '$delegacion'  and j.cuit = r.cuit and 
										  r.requerimientoanulado = 0 and r.nrorequerimiento = d.nrorequerimiento and 
										  d.anofiscalizacion > $anoLimite";
			}

			//print($sqlLeeTablas."<br>");
			$resLeeTablas = $dbl->query($sqlLeeTablas);
			if(!$resLeeTablas){
				$resultados[$i+1] = array("etapa" => "Lectura de Datos Tablas", "estado" => "Error", "descripcion" => "Sin Resultados para Tabla: ".$tabla."<br>");
			} else {
				$pathCompleto = $pathArchivo.$nombreArc;
				$punteroArchivo = fopen($pathCompleto, 'w') or die("Hubo un error al generar el archivo: ".$nombreArc." para escritura de datos<br>");
				if(stripos($tabla,"empresa") !== FALSE) {
					$totalRegistros = $resLeeTablas->rowCount();
					//print($totalRegistros." Empresa<br>");
					$totalLineas = 1;
					foreach($resLeeTablas as $contenidoTabla){
						if($totalLineas == $totalRegistros) {
							$finRegistro = '");';
						} else {
							$finRegistro = '"),';						
						}
						$registroTabla = '('.$contenidoTabla['del'].',"'.$contenidoTabla['nom'].'","'.$contenidoTabla['dom'].'","'.$contenidoTabla['loc'].'",'.$contenidoTabla['pro'].','.$contenidoTabla['cpo'].',"'.$contenidoTabla['tel'].'","'.$contenidoTabla['fio'].'","'.$contenidoTabla['cui'].$finRegistro;
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
				if(stripos($tabla,"titular") !== FALSE) {
					$totalRegistros = $resLeeTablas->rowCount();
					//print($totalRegistros." Titular<br>");
					$totalLineas = 1;
					foreach($resLeeTablas as $contenidoTabla){
						if($totalLineas == $totalRegistros) {
							$finRegistro = '");';
						} else {
							$finRegistro = '"),';						
						}
						$registroTabla = '('.$contenidoTabla['naf'].',"'.$contenidoTabla['fos'].'","'.$contenidoTabla['nom'].'","'.$contenidoTabla['tdo'].'",'.$contenidoTabla['ndo'].',"'.$contenidoTabla['fna'].'","'.$contenidoTabla['sex'].'",'.$contenidoTabla['eci'].','.$contenidoTabla['nac'].',"'.$contenidoTabla['dom'].'","'.$contenidoTabla['loc'].'",'.$contenidoTabla['pro'].','.$contenidoTabla['cpo'].',"'.$contenidoTabla['cue'].'",'.$contenidoTabla['del'].',"'.$contenidoTabla['fem'].'","'.$contenidoTabla['cat'].'","'.$contenidoTabla['fca'].'","'.$contenidoTabla['cua'].'","'.$contenidoTabla['taf'].$finRegistro;
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
				if(stripos($tabla,"familia") !== FALSE) {
					$totalRegistros = $resLeeTablas->rowCount();
					//print($totalRegistros." Familia<br>");
					$totalLineas = 1;
					foreach($resLeeTablas as $contenidoTabla){
						if($totalLineas == $totalRegistros) {
							$finRegistro = ');';
						} else {
							$finRegistro = '),';						
						}
						$registroTabla = '('.$contenidoTabla['naf'].','.$contenidoTabla['nor'].','.$contenidoTabla['tpa'].','.$contenidoTabla['est'].','.$contenidoTabla['dis'].',"'.$contenidoTabla['nom'].'","'.$contenidoTabla['tdo'].'",'.$contenidoTabla['ndo'].',"'.$contenidoTabla['fna'].'","'.$contenidoTabla['sex'].'","'.$contenidoTabla['fos'].'","'.$contenidoTabla['fca'].'","'.$contenidoTabla['cua'].'",'.$contenidoTabla['del'].$finRegistro;
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
				if(stripos($tabla,"bajatit") !== FALSE) {
					$totalRegistros = $resLeeTablas->rowCount();
					//print($totalRegistros." Bajatit<br>");
					$totalLineas = 1;
					foreach($resLeeTablas as $contenidoTabla){
						if($totalLineas == $totalRegistros) {
							$finRegistro = '");';
						} else {
							$finRegistro = '"),';						
						}
						$registroTabla = '('.$contenidoTabla['naf'].',"'.$contenidoTabla['fos'].'","'.$contenidoTabla['fba'].'","'.$contenidoTabla['nom'].'","'.$contenidoTabla['tdo'].'",'.$contenidoTabla['ndo'].',"'.$contenidoTabla['fna'].'","'.$contenidoTabla['sex'].'",'.$contenidoTabla['eci'].','.$contenidoTabla['nac'].',"'.$contenidoTabla['dom'].'","'.$contenidoTabla['loc'].'",'.$contenidoTabla['pro'].','.$contenidoTabla['cpo'].',"'.$contenidoTabla['cue'].'",'.$contenidoTabla['del'].',"'.$contenidoTabla['fem'].'","'.$contenidoTabla['cat'].'","'.$contenidoTabla['fca'].'","'.$contenidoTabla['cua'].'","'.$contenidoTabla['taf'].$finRegistro;
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
				if(stripos($tabla,"bajafam") !== FALSE) {
					$totalRegistros = $resLeeTablas->rowCount();
					//print($totalRegistros." Bajafam<br>");
					$totalLineas = 1;
					foreach($resLeeTablas as $contenidoTabla){
						if($totalLineas == $totalRegistros) {
							$finRegistro = ');';
						} else {
							$finRegistro = '),';						
						}
						$registroTabla = '('.$contenidoTabla['naf'].','.$contenidoTabla['nor'].','.$contenidoTabla['tpa'].','.$contenidoTabla['est'].','.$contenidoTabla['dis'].',"'.$contenidoTabla['nom'].'","'.$contenidoTabla['tdo'].'",'.$contenidoTabla['ndo'].',"'.$contenidoTabla['fna'].'","'.$contenidoTabla['sex'].'","'.$contenidoTabla['fos'].'","'.$contenidoTabla['fba'].'","'.$contenidoTabla['fca'].'","'.$contenidoTabla['cua'].'",'.$contenidoTabla['del'].$finRegistro;
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
				if(stripos($tabla,"cabjur") !== FALSE) {
					$totalRegistros = $resLeeTablas->rowCount();
					//print($totalRegistros." Cabjur<br>");
					$totalLineas = 1;
					foreach($resLeeTablas as $contenidoTabla){
						if($totalLineas == $totalRegistros) {
							$finRegistro = ');';
						} else {
							$finRegistro = '),';						
						}
						$registroTabla = '('.$contenidoTabla['del'].',"'.$contenidoTabla['cui'].'",'.$contenidoTabla['ano'].','.$contenidoTabla['mes'].','.$contenidoTabla['tpe'].','.$contenidoTabla['tre'].','.$contenidoTabla['tde'].$finRegistro;
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
				if(stripos($tabla,$delegacion) !== FALSE) {
					if(stripos($tabla,"cuij") !== FALSE) {
						$totalRegistros = $resLeeTablas->rowCount();
						//print($totalRegistros." Cuij<br>");
						$totalLineas = 1;
						foreach($resLeeTablas as $contenidoTabla){
							if($totalLineas == $totalRegistros) {
								$finRegistro = ');';
							} else {
								$finRegistro = '),';						
							}
							$registroTabla = '('.$contenidoTabla['del'].',"'.$contenidoTabla['cue'].'",'.$contenidoTabla['ano'].','.$contenidoTabla['mes'].',"'.$contenidoTabla['cua'].'",'.$contenidoTabla['rem'].$finRegistro;
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
					if(stripos($tabla,"apoi") !== FALSE) {
						$totalRegistros = $resLeeTablas->rowCount();
						//print($totalRegistros." Apoi<br>");
						$totalLineas = 1;
						foreach($resLeeTablas as $contenidoTabla){
							if($totalLineas == $totalRegistros) {
								$finRegistro = ');';
							} else {
								$finRegistro = '),';						
							}
							$registroTabla = '('.$contenidoTabla['del'].',"'.$contenidoTabla['cue'].'","'.$contenidoTabla['cua'].'",'.$contenidoTabla['ano'].','.$contenidoTabla['mes'].',"'.$contenidoTabla['con'].'","'.$contenidoTabla['fpa'].'","'.$contenidoTabla['deb'].'",'.$contenidoTabla['imp'].$finRegistro;
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
				}
				if(stripos($tabla,"pagos") !== FALSE) {
					$totalRegistros = $resLeeTablas->rowCount();
					//print($totalRegistros." Pagos<br>");
					$totalLineas = 1;
					foreach($resLeeTablas as $contenidoTabla){
						if($totalLineas == $totalRegistros) {
							$finRegistro = ');';
						} else {
							$finRegistro = '),';						
						}
						$registroTabla = '('.$contenidoTabla['del'].',"'.$contenidoTabla['cui'].'",'.$contenidoTabla['ano'].','.$contenidoTabla['mes'].',"'.$contenidoTabla['deb'].'","'.$contenidoTabla['con'].'","'.$contenidoTabla['fpa'].'",'.$contenidoTabla['imp'].$finRegistro;
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
					//print($totalRegistros." Cabacuer<br>");
					$totalLineas = 1;
					foreach($resLeeTablas as $contenidoTabla){
						if($totalLineas == $totalRegistros) {
							$finRegistro = ');';
						} else {
							$finRegistro = '),';						
						}
						$registroTabla = '("'.$contenidoTabla['cui'].'",'.$contenidoTabla['nac'].','.$contenidoTabla['tac'].','.$contenidoTabla['eac'].',"'.$contenidoTabla['fac'].'",'.$contenidoTabla['mac'].$finRegistro;
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
					//print($totalRegistros." Detacuer<br>");
					$totalLineas = 1;
					foreach($resLeeTablas as $contenidoTabla){
						if($totalLineas == $totalRegistros) {
							$finRegistro = ');';
						} else {
							$finRegistro = '),';						
						}
						$registroTabla = '("'.$contenidoTabla['cui'].'",'.$contenidoTabla['nac'].','.$contenidoTabla['ano'].','.$contenidoTabla['mes'].$finRegistro;
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
					//print($totalRegistros." Cuoacuer<br>");
					$totalLineas = 1;
					foreach($resLeeTablas as $contenidoTabla){
						if($totalLineas == $totalRegistros) {
							$finRegistro = '");';
						} else {
							$finRegistro = '"),';						
						}
						$registroTabla = '("'.$contenidoTabla['cui'].'",'.$contenidoTabla['nac'].','.$contenidoTabla['ncu'].','.$contenidoTabla['mcu'].',"'.$contenidoTabla['fcu'].'",'.$contenidoTabla['mpa'].',"'.$contenidoTabla['fpa'].$finRegistro;
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
					//print($totalRegistros." Juicios<br>");
					$totalLineas = 1;
					foreach($resLeeTablas as $contenidoTabla){
						if($totalLineas == $totalRegistros) {
							$finRegistro = ');';
						} else {
							$finRegistro = '),';						
						}
						$registroTabla = '("'.$contenidoTabla['cui'].'",'.$contenidoTabla['ano'].','.$contenidoTabla['mes'].$finRegistro;
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
				if(stripos($tabla,"discapacitados") !== FALSE) {
					$totalRegistros = $resLeeTablas->rowCount();
					//print($totalRegistros." Juicios<br>");
					$totalLineas = 1;
					foreach($resLeeTablas as $contenidoTabla){
						if($totalLineas == $totalRegistros) {
							$finRegistro = ');';
						} else {
							$finRegistro = '),';
						}
						if ($contenidoTabla['delalta'] == null) {
							$delega = $contenidoTabla['delbaja'];
						} else {
							$delega = $contenidoTabla['delalta'];
						}
						$registroTabla = '("'.$contenidoTabla['naf'].'",'.$contenidoTabla['nrd'].','.$delega.$finRegistro;
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
				
				if(stripos($tabla,"requerimientos") !== FALSE) {
					$totalRegistros = $resLeeTablas->rowCount();
					$totalLineas = 1;
					foreach($resLeeTablas as $contenidoTabla){
						if($totalLineas == $totalRegistros) {
							$finRegistro = ');';
						} else {
							$finRegistro = '),';
						}
						$registroTabla = '("'.$contenidoTabla['cuit'].'",'.$contenidoTabla['nrorequerimiento'].','.$contenidoTabla['anofiscalizacion'].','.$contenidoTabla['mesfiscalizacion'].$finRegistro;
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
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  	<p><input type="button" name="volver" value="Volver" onclick="location.href = 'moduloGeneracion.php'" /></p>
  	<h3>Resultado de la Generacion de Archivos Intranet O.S.P.I.M.</h3>
  	<h3>Delegación <?php echo $delegacion ?> - Fecha <?php echo invertirFecha($today) ?> </h3>
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
 	<p><input type="button" name="imprimir" value="Imprimir" onclick="window.print();" /></p>
</div>
</body>
</html>