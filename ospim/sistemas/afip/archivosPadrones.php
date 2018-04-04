<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."fechas.php");
$base = $_SESSION['dbname'];
$fechaarchivo=$_GET['fechaArch'];
$nombrearchivo=substr($fechaarchivo, 2, 6);
$fechamensaje=$_GET['fechaMens'];
$nromensaje=$_GET['nroMail'];
$maquina = $_SERVER['SERVER_NAME'];
$noHayPadron=FALSE;
$fechahoy=date("YmdHis",time());
$usuarioproceso = $_SESSION['usuario'];

if(strcmp("localhost",$maquina)==0) {
	$archivo_padron=$_SERVER['DOCUMENT_ROOT']."/madera/ospim/sistemas/afip/Padrones/DDJJ_PADRON_OS111001.zip";
	$carpeta_padron=$_SERVER['DOCUMENT_ROOT']."/madera/ospim/sistemas/afip/Padrones/";
} else {
	$archivo_padron="/home/sistemas/ArchivosAfip/Padrones/DDJJ_PADRON_OS111001.zip";
	$carpeta_padron="/home/sistemas/ArchivosAfip/Padrones/";
}

if(!file_exists($archivo_padron))
	$noHayPadron=TRUE;

if($noHayPadron) {
	$tituloform = "ERROR";
	$mensaje = 'No se encontraron archivos de Padron. Verifique si fue descargado o si ya fue procesado.';
} else {
	// Tratamiento de los Archivos de Padrones
	if(file_exists($archivo_padron)) {
		$zipPadron = new ZipArchive;
		if ($zipPadron->open($archivo_padron) === TRUE) {
			$zipPadron->extractTo($carpeta_padron);
			$zipPadron->close();
			$archivo_descom = $carpeta_padron."DDJJ_PADRON_OS111001.txt";
			$registros = file($archivo_descom, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
			for($i=0; $i < count($registros); $i++) {
				if($i == 0) {
					$headertransf = substr($registros[$i], 0, 21);
					$fechatransfcorta = substr($registros[$i], 23, 4).substr($registros[$i], 28, 2).substr($registros[$i], 31, 2);
					//$fechatransflarga = substr($registros[$i], 22, 14);
				}

				if(strcmp("HFOS111001DDJJ-PADRON", $headertransf)==0) {
					if(strcmp($fechaarchivo, $fechatransfcorta)==0) {
						if($i == 0) {
							$sqlArchivoExiste = "SELECT * FROM padronesddjj WHERE fechaarchivoafip = '$fechatransfcorta' AND fechaemailafip = '$fechamensaje'";
							$resArchivoExiste = mysql_query($sqlArchivoExiste,$db);
							$canArchivoExiste = mysql_num_rows($resArchivoExiste);

							if($canArchivoExiste!=0) {
								$tituloform = "ERROR";
								$mensaje = 'Ya EXISTEN archivos procesados para esa fecha de email y con esa fecha de transferencia.';
								if(unlink($archivo_descom)) {
									$tituloaviso = "AVISO";
									$aviso = 'El archivo descomprimido desde el ZIP SE elimino correctamente.';
								} else {
									$tituloaviso = "AVISO";
									$aviso = 'El archivo descomprimido desde el ZIP NO pudo ser eliminado.';
								}
								break;
							} else {
								$sqlBuscaNroDisco = "SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = '$base' AND TABLE_NAME = 'padronesddjj'";
								$resBuscaNroDisco = mysql_query($sqlBuscaNroDisco,$db);
								$rowBuscaNroDisco = mysql_fetch_array($resBuscaNroDisco);
								$proximonro = $rowBuscaNroDisco['AUTO_INCREMENT'];

								if(strcmp("localhost",$maquina)==0) {
									$destino_padron=$_SERVER['DOCUMENT_ROOT']."/madera/ospim/sistemas/afip/Padrones/DDJJ/Disco".$proximonro."/";
								} else {
									$destino_padron="/home/sistemas/ArchivosAfip/Padrones/DDJJ/Disco".$proximonro."/";
								}

								mkdir($destino_padron, 0777);
								$archivo_salida = $destino_padron."PADJ".$proximonro.".txt";
								//echo $archivo_salida;
								$punteroarchivo = fopen($archivo_salida, 'w') or die("Hubo un error al generar el archivo de Padron");
							}
						} else {
							$codobs = substr($registros[$i], 124, 6);
							if(strcmp("111001", $codobs)==0) {
								$nroregistro = $i;
								$cuit = substr($registros[$i], 0, 11);
								$nombre = substr($registros[$i], 11, 50);
								$calle = substr($registros[$i], 61, 20);
								$numero = substr($registros[$i], 81, 6);
								$piso = substr($registros[$i], 87, 3);
								$depto = substr($registros[$i], 90, 3);
								$localidad = substr($registros[$i], 93, 20);
								$provincia = substr($registros[$i], 113, 3);
								$codpos = substr($registros[$i], 116, 4);
								$vacios = substr($registros[$i], 120, 4);
								$codobs = substr($registros[$i], 124, 6);

								$registrosalida = str_replace(' ', '', $proximonro).'|'.str_replace(' ', '', $nroregistro).'|'.str_replace(' ', '', $cuit).'|'.$nombre.'|'.$calle.'|'.(int)$numero.'|'.str_replace(' ', '', $piso).'|'.str_replace(' ', '', $depto).'|'.$localidad.'|'.(int)$provincia.'|'.(int)$codpos.'|'.str_replace(' ', '', $vacios).'|'.str_replace(' ', '', $codobs);
								fwrite($punteroarchivo, $registrosalida."\n");
								$registrosleidos = $registrosleidos + 1;
							} else {
								//echo $registrosleidos; echo "-";
								$footertransf = substr($registros[$i], 0, 21);
								if(strcmp("TFOS111001DDJJ-PADRON", $footertransf)==0) {
									$totalregistros = substr($registros[$i], 33, 12);
									//echo $totalregistros;
									fclose($punteroarchivo);

									try {
										$hostname = $_SESSION['host'];
										$dbname = $_SESSION['dbname'];
										$dbl = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
										$dbl->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
										$dbl->beginTransaction();

										$sqlAddDisco = "INSERT INTO padronesddjj (nrodisco, fechaarchivoafip, fechaemailafip, registrosafip, fechaprocesoospim, usuarioprocesoospim, registrosprocesoospim, carpetaarchivoospim) VALUES (:nrodisco,:fechaarchivoafip,:fechaemailafip,:registrosafip,:fechaprocesoospim,:usuarioprocesoospim,:registrosprocesoospim,:carpetaarchivoospim)";
										$resAddDisco = $dbl->prepare($sqlAddDisco);
										if($resAddDisco->execute(array(':nrodisco' => $proximonro, ':fechaarchivoafip' => $fechatransfcorta, ':fechaemailafip' => $fechamensaje, ':registrosafip' => (int)$totalregistros, ':fechaprocesoospim' => $fechahoy, ':usuarioprocesoospim' => $usuarioproceso, ':registrosprocesoospim' => (int)$registrosleidos, ':carpetaarchivoospim' => $archivo_salida))) {
											
											$sqlLoadArchivo = "LOAD DATA LOCAL INFILE '$archivo_salida' REPLACE INTO TABLE afippadrones FIELDS TERMINATED BY '|' LINES TERMINATED BY '\n'";
											$linkid = mysqli_init();
											mysqli_options($linkid, MYSQLI_OPT_LOCAL_INFILE, true);
											mysqli_real_connect($linkid, $hostname, $_SESSION['usuario'], $_SESSION['clave'], $dbname);
											$resLoadArchivo = mysqli_query($linkid, $sqlLoadArchivo);
											if (!$resLoadArchivo) {
												$tituloform = "ERROR";
												$mensaje = 'La carga de los registros de Padron por DDJJ (AFIPPADRONES) desde el archivo FALLO.';
											} else {
												if(!unlink($archivo_descom)) {
													$tituloaviso = "AVISO";
													$aviso = 'El archivo descomprimido desde el ZIP NO pudo ser eliminado.';
												}

												rename($archivo_padron,$destino_padron."DDJJ_PADRON_OS111001.zip");
	
												$sqlAddMensaje = "INSERT INTO afipmensajes (nromensaje, fechaemailafip, cuentaderecepcion, tipoarchivo, nrodisco) VALUES (:nromensaje,:fechaemailafip,:cuentaderecepcion,:tipoarchivo,:nrodisco)";
												$resAddMensaje = $dbl->prepare($sqlAddMensaje);
												if($resAddMensaje->execute(array(':nromensaje' => $nromensaje, ':fechaemailafip' => $fechamensaje, ':cuentaderecepcion' => 'afippadron@ospim.com.ar', ':tipoarchivo' => 'PADJ', ':nrodisco' => $proximonro))) {
													$tituloaviso = "AVISO";
													$aviso = 'El mensaje de AFIP ha sido marcado como leido.';
												} else {
													$tituloaviso = "AVISO";
													$aviso = 'El mensaje de AFIP NO ha podido ser marcado como leido.';
												}
	
												$tituloform = "PROCESO EXITOSO";
												$mensaje = 'El PROCESAMIENTO del archivo de Padron por DDJJ ha sido EXITOSO.';
											}
										} else {
											$tituloform = "ERROR";
											$mensaje = 'La identificacion del archivo de Padron por DDJJ por Nro. de disco FALLO.';
										}
										$dbl->commit();
									} catch (PDOException $e) {
										$error =  $e->getMessage();
										$dbl->rollback();
										$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
										header ($redire);
										exit(0);
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
						$mensaje = 'El archivo encontrado NO corresponde a un padron por DDJJ.';
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
			$mensaje = 'NO se pudo extraer archivo de Padron por DDJJ desde el ZIP.';
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/blue/style.css" type="text/css" id="" media="print, projection, screen" />
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.css" type="text/css" id="" media="print, projection, screen" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Transferencias AFIP :.</title>

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
	<p><input type="button" name="volver" value="Volver" onclick="location.href = 'menuAfip.php'" /></p>
	<h1><?php echo $tituloform;?></h1>
	<h3><?php echo $mensaje;?></h3>
	<h3><?php echo $tituloaviso;?></h3>
	<h4><?php echo $aviso;?></h4>
	<p><input type="button" name="imprimir" value="Imprimir" onclick="window.print();"/></p>
</div>
</body>
</html>