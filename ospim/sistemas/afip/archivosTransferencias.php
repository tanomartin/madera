<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."fechas.php");
$base = $_SESSION['dbname'];
$fechaarchivo=$_GET['fechaArch'];
$fechamensaje=$_GET['fechaMens'];
$nromensaje=$_GET['nroMail'];
$maquina = $_SERVER['SERVER_NAME'];
$noHayAportes=FALSE;
$noHayAutoges=FALSE;
$fechahoy=date("YmdHis",time());
$usuarioproceso = $_SESSION['usuario'];

if(strcmp("localhost",$maquina)==0) {
	$archivo_aporte=$_SERVER['DOCUMENT_ROOT']."/ospim/sistemas/afip/Transferencias/OS1110.txt.zip";
	$carpeta_aporte=$_SERVER['DOCUMENT_ROOT']."/ospim/sistemas/afip/Transferencias/";
	$archivo_autogestion=$_SERVER['DOCUMENT_ROOT']."/ospim/sistemas/afip/Transferencias/Detalledemovimientos";
}
else {
	$archivo_aporte="/home/sistemas/ArchivosAfip/Transferencias/OS1110.txt.zip";
	$carpeta_aporte="/home/sistemas/ArchivosAfip/Transferencias/";
	$archivo_autogestion="/home/sistemas/ArchivosAfip/Transferencias/Detalledemovimientos";
}

if(!file_exists($archivo_aporte))
	$noHayAportes=TRUE;

if(!file_exists($archivo_autogestion))
	$noHayAutoges=TRUE;

if($noHayAportes && $noHayAutoges) {
	$tituloform = "ERROR";
	$mensaje = 'No se encontraron archivos ni de APORTES ni de AUTOGESTION. Verifique si fue descargado o si ya fue procesado.';
}
else {
	// Tratamiento de los Archivos de Transferencias por Aportes
	if(file_exists($archivo_aporte)) {
		$zipAporte = new ZipArchive;
		if ($zipAporte->open($archivo_aporte) === TRUE) {
			$zipAporte->extractTo($carpeta_aporte);
			$zipAporte->close();
			$archivo_descom = $carpeta_aporte."OS1110.txt";
			$registros = file($archivo_descom, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
			for($i=0; $i < count($registros); $i++) {
				if($i == 0) {
					$headertransf = substr($registros[$i], 0, 16);
					$fechatransfcorta = substr($registros[$i], 22, 8);
					$fechatransflarga = substr($registros[$i], 22, 14);
				}

				if(strcmp("HFTRANSF-DGI1110", $headertransf)==0) {
					if(strcmp($fechaarchivo, $fechatransfcorta)==0) {
						if($i == 0) {
							$sqlArchivoExiste = "SELECT * FROM transferenciasaportes WHERE fechaarchivoafip = '$fechatransflarga' AND fechaemailafip = '$fechamensaje'";
							$resArchivoExiste = mysql_query($sqlArchivoExiste,$db);
							$canArchivoExiste = mysql_num_rows($resArchivoExiste);

							if($canArchivoExiste!=0) {
								$tituloform = "ERROR";
								$mensaje = 'Ya EXISTEN archivos procesados para esa fecha de email y con esa fecha de transferencia.';
								if(unlink($archivo_descom)) {
									$tituloaviso = "AVISO";
									$aviso = 'El archivo descomprimido desde el ZIP SE elimino correctamente.';
								}
								else {
									$tituloaviso = "AVISO";
									$aviso = 'El archivo descomprimido desde el ZIP NO pudo ser eliminado.';
								}
								break;
							} else {
								$sqlBuscaNroDisco = "SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = '$base' AND TABLE_NAME = 'transferenciasaportes'";
								$resBuscaNroDisco = mysql_query($sqlBuscaNroDisco,$db);
								$rowBuscaNroDisco = mysql_fetch_array($resBuscaNroDisco);
								$proximonro = $rowBuscaNroDisco['AUTO_INCREMENT'];

								if(strcmp("localhost",$maquina)==0) {
									$destino_aporte=$_SERVER['DOCUMENT_ROOT']."/ospim/sistemas/afip/Transferencias/Aportes/Disco".$proximonro."/";
									$destino_autogestion=$_SERVER['DOCUMENT_ROOT']."/ospim/sistemas/afip/Transferencias/Autogestion/Disco".$proximonro."/";
								}
								else {
									$destino_aporte="/home/sistemas/ArchivosAfip/Transferencias/Aportes/Disco".$proximonro."/";
									$destino_autogestion="/home/sistemas/ArchivosAfip/Transferencias/Autogestion/Disco".$proximonro."/";
								}

								mkdir($destino_aporte, 0777);
								$archivo_salida = $destino_aporte."TRAP".$proximonro.".txt";
								$punteroarchivo = fopen($archivo_salida, 'w') or die("Hubo un error al generar el archivo de transferencias");
							}
						} else {
							$codbos = substr($registros[$i], 0, 4);
							if(strcmp("1110", $codbos)==0) {
								$nroregistro = $i;
								$codbos = substr($registros[$i], 0, 4);
								$concepto = substr($registros[$i], 4, 3);
								$importeentero = substr($registros[$i], 7, 13);
								$importedecimal = substr($registros[$i], 20, 2);
								$importe = $importeentero.".".$importedecimal;
								$debitocredito = substr($registros[$i], 22, 1);
								if(strcmp("REM", $concepto)!=0) {
									if(strcmp("C", $debitocredito)==0) {
										$totalcredito = $totalcredito + (float)$importe;
									}
									else {
										$totaldebito = $totaldebito + (float)$importe;
									}
								}
								$fechaprocesoafip = substr($registros[$i], 23, 10);
								$fechapago = substr($registros[$i], 33, 10);
								$cuit = substr($registros[$i], 43, 11);
								$anopagocorto = substr($registros[$i], 54, 2);
								if(strcmp("49", $anopagocorto)>=0) {
									$anopago = "20".$anopagocorto;
								}
								else {
									$anopago = "19".$anopagocorto;;
								}
								$mespago = substr($registros[$i], 56, 2);
								$numeroobligacion = substr($registros[$i], 58, 12);
								$secuenciapresentacion = substr($registros[$i], 70, 3);
								$cuil = substr($registros[$i], 73, 11);
								$codigobanco = substr($registros[$i], 84, 3);
								$codigosucursal = substr($registros[$i], 87, 3);
								$codigozona = substr($registros[$i], 90, 2);
								$porcenreduccionentero = substr($registros[$i], 92, 2);
								$porcenreducciondecimal = substr($registros[$i], 94, 2);
								$porcenreduccion = $porcenreduccionentero.".".$porcenreducciondecimal;
								$familiares = substr($registros[$i], 96, 2);
								$adherentes = 0;
								$desconocido = substr($registros[$i], 98, 2);
								$registrosalida = str_replace(' ', '', $proximonro).'|'.str_replace(' ', '', $nroregistro).'|'.str_replace(' ', '', $cuit).'|'.str_replace(' ', '', $anopago).'|'.str_replace(' ', '', $mespago).'|'.str_replace(' ', '', $concepto).'|'.str_replace(' ', '', $fechapago).'|'.str_replace(' ', '', $importe).'|'.str_replace(' ', '', $debitocredito).'|'.str_replace(' ', '', $porcenreduccion).'|'.str_replace(' ', '', $cuil).'|'.str_replace(' ', '', $familiares).'|'.str_replace(' ', '', $adherentes).'|'.str_replace(' ', '', $numeroobligacion).'|'.str_replace(' ', '', $secuenciapresentacion).'|'.str_replace(' ', '', $codigobanco).'|'.str_replace(' ', '', $codigosucursal).'|'.str_replace(' ', '', $codigozona).'|'.str_replace(' ', '', $fechaprocesoafip);
								fwrite($punteroarchivo, $registrosalida."\n");
								$registrosleidos = $registrosleidos + 1;
							}
							else {
								$footertransf = substr($registros[$i], 0, 16);
								if(strcmp("TFTRANSF-DGI1110", $footertransf)==0) {
									$totalregistros = substr($registros[$i], 36, 9);
									$transferidoentero = substr($registros[$i], 45, 13);
									$transferidodecimal = substr($registros[$i], 58, 2);
									$totaltransferido = $transferidoentero.".".$transferidodecimal;
									fclose($punteroarchivo);

									try {
										$hostname = $_SESSION['host'];
										$dbname = $_SESSION['dbname'];
										$dbl = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
										$dbl->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
										$dbl->beginTransaction();

										$sqlAddDisco = "INSERT INTO transferenciasaportes (nrodisco, fechaarchivoafip, fechaemailafip, registrosafip, importeafip, fechaprocesoospim, usuarioprocesoospim, registrosprocesoospim, creditoprocesoospim, debitoprocesoospim, importeprocesoospim, carpetaarchivoospim) VALUES (:nrodisco,:fechaarchivoafip,:fechaemailafip,:registrosafip,:importeafip,:fechaprocesoospim,:usuarioprocesoospim,:registrosprocesoospim,:creditoprocesoospim,:debitoprocesoospim,:importeprocesoospim,:carpetaarchivoospim)";
										$resAddDisco = $dbl->prepare($sqlAddDisco);
										if($resAddDisco->execute(array(':nrodisco' => $proximonro, ':fechaarchivoafip' => $fechatransflarga, ':fechaemailafip' => $fechamensaje, ':registrosafip' => (int)$totalregistros, ':importeafip' => round((float)$totaltransferido,2), ':fechaprocesoospim' => $fechahoy, ':usuarioprocesoospim' => $usuarioproceso, ':registrosprocesoospim' => (int)$registrosleidos, ':creditoprocesoospim' => round($totalcredito,2), ':debitoprocesoospim' => round($totaldebito,2), ':importeprocesoospim' => round(($totalcredito - $totaldebito),2), ':carpetaarchivoospim' => $archivo_salida))) {
											chmod($archivo_salida, 0777);
											$sqlLoadArchivo = "LOAD DATA LOCAL INFILE '$archivo_salida' REPLACE INTO TABLE afiptransferencias FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n'";
											$resLoadArchivo = mysql_query($sqlLoadArchivo,$db);
											if (!$resLoadArchivo) {
												$tituloform = "ERROR";
												$mensaje = 'La carga de los registros de transferencias (AFIPTRANSFERENCIAS) del archivo FALLO.';
											} else {
												$sqlAgrupaAfip = "SELECT cuit, anopago, mespago, concepto, fechapago, debitocredito, fechaprocesoafip, SUM(importe) AS importetotal FROM afiptransferencias WHERE nrodisco = '$proximonro' GROUP BY cuit, anopago, mespago, concepto, fechapago, debitocredito, fechaprocesoafip";
												$resAgrupaAfip = $dbl->query($sqlAgrupaAfip);
												if (!$resAgrupaAfip){
													$tituloform = "ERROR";
													$mensaje = 'El proceso de agrupamiento de los registros de transferencias del archivo FALLO.';
												} else {
													$archivo_agrupa = $destino_aporte."TRAG".$proximonro.".txt";
													$punteroagrupado = fopen($archivo_agrupa, 'w') or die("Hubo un error al generar el archivo de agrupamientos");
													foreach ($resAgrupaAfip as $agrupado){
														$registroagrupado = $agrupado[cuit].'|'.$agrupado[anopago].'|'.$agrupado[mespago].'|'.$agrupado[concepto].'|'.$agrupado[fechapago].'|'.$agrupado[debitocredito].'|'.$agrupado[fechaprocesoafip].'|'.$agrupado[importetotal];
														fwrite($punteroagrupado, $registroagrupado."\n");
													}
													fclose($punteroagrupado);
													chmod($archivo_agrupa, 0777);
													$sqlLoadAgrupa = "LOAD DATA LOCAL INFILE '$archivo_agrupa' REPLACE INTO TABLE afipprocesadas FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n'";
													$resLoadAgrupa = mysql_query($sqlLoadAgrupa,$db);
													if (!$resLoadAgrupa) {
														$tituloform = "ERROR";
														$mensaje = 'La carga de los registros de agrupamientos (AFIPPROCESADAS) del archivo FALLO.';
													} else {
														if(!unlink($archivo_descom)) {
															$tituloaviso = "AVISO";
															$aviso = 'El archivo descomprimido desde el ZIP NO pudo ser eliminado.';
														}
														rename($archivo_aporte,$destino_aporte."OS1110.txt.zip");
														$sqlAddMensaje = "INSERT INTO afipmensajes (nromensaje, fechaemailafip, cuentaderecepcion, tipoarchivo, nrodisco) VALUES (:nromensaje,:fechaemailafip,:cuentaderecepcion,:tipoarchivo,:nrodisco)";
														$resAddMensaje = $dbl->prepare($sqlAddMensaje);
														if($resAddMensaje->execute(array(':nromensaje' => $nromensaje, ':fechaemailafip' => $fechamensaje, ':cuentaderecepcion' => 'afiptransferencias@ospim.com.ar', ':tipoarchivo' => 'TRAP', ':nrodisco' => $proximonro))) {
															$tituloaviso = "AVISO";
															$aviso = 'El mensaje de AFIP ha sido marcado como leido.';
														} else {
															$tituloaviso = "AVISO";
															$aviso = 'El mensaje de AFIP NO ha podido ser marcado como leido.';
														}
														$tituloform = "PROCESO EXITOSO";
														$mensaje = 'El PROCESAMIENTO del archivo de transferencias por APORTES ha sido EXITOSO.';
													}
												}
											}
										} else {
											$tituloform = "ERROR";
											$mensaje = 'La identificacion del archivo de transferencia por Nro. de disco FALLO.';
										}
										$dbl->commit();
									}catch (PDOException $e) {
										echo $e->getMessage();
										$dbl->rollback();
									}
								}
							}
						}
					}
					else {
						if($i == 0) {
							$tituloform = "ERROR";
							$mensaje = 'El archivo encontrado no se corresponde con el mensaje de AFIP.';
							if(unlink($archivo_descom)) {
								$tituloaviso = "AVISO";
								$aviso = 'El archivo descomprimido desde el ZIP SE elimino correctamente.';
							}
							else {
								$tituloaviso = "AVISO";
								$aviso = 'El archivo descomprimido desde el ZIP NO pudo ser eliminado.';
							}
							break;
						}
					}
				}
				else {
					if($i == 0) {
						$tituloform = "ERROR";
						$mensaje = 'El archivo encontrado NO corresponde a una transferencia de APORTES.';
						if(unlink($archivo_descom)) {
							$tituloaviso = "AVISO";
							$aviso = 'El archivo descomprimido desde el ZIP SE elimino correctamente.';
						}
						else {
							$tituloaviso = "AVISO";
							$aviso = 'El archivo descomprimido desde el ZIP NO pudo ser eliminado.';
						}
						break;
					}
				}
			}
		} else {
			$tituloform = "ERROR";
			$mensaje = 'NO se pudo extraer archivo de transferencias de APORTES desde el ZIP.';
		}
	}

	// Tratamiento de los Archivos de Transferencias por Autogestion
	//if(file_exists($archivo_autogestion)) {
	//}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" href="/lib/jquery.tablesorter/themes/blue/style.css" type="text/css" id="" media="print, projection, screen" />
<link rel="stylesheet" href="/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.css" type="text/css" id="" media="print, projection, screen" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Transferencias AFIP :.</title>
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
	<h1><?php echo $tituloform;?></h1>
</div>
<div align="center">
	<h3><?php echo $mensaje;?></h3>
</div>
<div align="center">
	<h3><?php echo $tituloaviso;?></h3>
</div>
<div align="center">
	<h4><?php echo $aviso;?></h4>
</div>
<div align="center">
	<input type="button" name="imprimir" value="Imprimir" onClick="window.print();" align="right"/>
</div>
</body>
</html>