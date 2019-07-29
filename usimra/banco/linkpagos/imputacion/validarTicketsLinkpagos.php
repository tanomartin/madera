<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php");
$fechavalidacion = date("Y-m-d H:i:s");
$fechasubida = date("Y-m-d");
$usuariovalidacion = $_SESSION['usuario'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Banco USIMRA :.</title>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo1 {
	font-family: Arial, Helvetica, sans-serif;
	font-style: italic;
	font-weight: bold;
}
</style>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css" type="text/css" id="" media="print, projection, screen" />
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery-ui.min.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
	$("#resultados")
		.tablesorter({
			theme: 'blue',
			widthFixed: true, 
			widgets: ["zebra"],
			headers:{0:{sorter:false}, 1:{sorter:false}, 2:{sorter:false}, 3:{sorter:false}}
		});
});
function irARegistrar() {
	$.blockUI({ message: "<h1>Procesando el Registro de Pagos.<br>Aguarde por favor...</h1>" });
	document.location.href = "registrarPagosLinkpagos.php";
};
function irANotificar() {
	$.blockUI({ message: "<h1>Procesando las Notificaciones.<br>Aguarde por favor...</h1>" });
	document.location.href = "notificarErroresValidacion.php";
};
</script>
</head>
<body bgcolor="#B2A274">
<?php
//conexion y creacion de transaccion.
try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	//echo "$hostname"; echo "<br>";
	//echo "$dbname"; echo "<br>";
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	//echo 'Connected to database<br/>';
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction(); ?>
	<div align="center">
		<h1>Resultados de la Validaci&oacute;n de Tickets por Depositos via Link Pagos</h1>
	</div>
<?php
	$sqlControlValidar="SELECT COUNT(*) FROM linkaportesusimra WHERE fechavalidacion = '0000-00-00 00:00:00'";
	$sqlLeeAValidar="SELECT * FROM linkaportesusimra WHERE fechavalidacion = '0000-00-00 00:00:00' ORDER BY fechaarchivo, fechadeposito, idmovimiento";
	$resultControlValidar=$dbh->query($sqlControlValidar);
	if(!$resultControlValidar) { ?>
		<div align="center">
			<h3>Error en la consulta de la tabla LINKAPORTESUSIMRA. Comuniquese con el Depto. de Sistemas.</h3>
		</div>
<?php
	} else {
		//Verifica si hay registros a validar
		if($resultControlValidar->fetchColumn()==0) {
			$hayboleta=0; ?>
			<div align="center">
				<h2>No hay tickets que deban ser validados.</h2>
			</div>
<?php
		} else {
			$hayboleta=1;
    		$resultLeeAValidar=$dbh->query($sqlLeeAValidar);
    		if(!$resultLeeAValidar) { ?>
				<div align="center">
					<h3>Error en la consulta de Informaci&oacute;n de Link Pagos. Comuniquese con el Depto. de Sistemas.</h3>
				</div>
<?php
			} else {
				set_time_limit(0);
				$cuil="99999999999";
				$cantvali=0;
				$cantnova=0;
				$senotifica=0; ?>

				<table id="resultados" class="tablesorter" style="font-size:14px; text-align:center">
					<thead>
						<tr>
							<th>Id. Ticket</th>
							<th>C.U.I.T.</th>
							<th>Importe</th>
							<th>Deposito</th>
							<th>Status</th>
							<th>Mensaje</th>
							<th>Notificacion</th>
						</tr>
					</thead>
					<tbody>
<?php
        		foreach($resultLeeAValidar as $validar) {
					$fechabanco = $validar[fechaarchivo]; 
					$movimientobanco = $validar[idmovimiento]; 
					$cuitbanco = $validar[cuit];
					$referenciabanco = $validar[referencia];
					$importebanco = $validar[importe];
					$depositobanco = $validar[fechadeposito];
					$notificabanco = $validar[notificacion];
?>
						<tr>
							<td><?php echo $referenciabanco; ?></td>
							<td><?php echo $cuitbanco; ?></td>
							<td><?php echo $importebanco; ?></td>
							<td><?php echo $depositobanco; ?></td>
<?php
					$listastatus="";
					$listamensaje="";
					$listanotificacion="";
					$sqlControlBuscaEmpresa="SELECT COUNT(*) FROM empresas WHERE cuit = '$cuitbanco'";
					$resultControlBuscaEmpresa=$dbh->query($sqlControlBuscaEmpresa);
					if($resultControlBuscaEmpresa->fetchColumn()!=0) {
						$sqlControlTicket="SELECT COUNT(*) FROM vinculadocuusimra WHERE nrcuit = '$cuitbanco' AND referencia = '$referenciabanco'";
						$resultControlTicket=$dbh->query($sqlControlTicket);
						if($resultControlTicket->fetchColumn()!=0) {
							$noddjj=1;
							$sqlBuscaDDJJ="SELECT COUNT(d.nrctrl) AS cantdj, SUM((d.totapo+d.recarg)) AS totdep FROM ddjjusimra d, vinculadocuusimra v WHERE d.nrcuil = '$cuil' AND d.nrcuit = '$cuitbanco' AND d.nrcuit = v.nrcuit AND d.nrctrl = v.nrctrl AND v.referencia = '$referenciabanco' GROUP BY v.nrcuit, v.referencia";
							if($resultBuscaDDJJ=$dbh->query($sqlBuscaDDJJ)) {
								foreach($resultBuscaDDJJ as $totaddjj) {
									$cantddjj = $totaddjj[cantdj];
									$impoddjj = $totaddjj[totdep];
									$importeadmitido=0; 
									$difdeposito=0.00;
									if($cantddjj>0) {
										if($impoddjj==$importebanco) {
											$importeadmitido=1;
										} else {
											$difdeposito=round(($importebanco-$impoddjj),2);
											if($difdeposito >= -15.00 && $difdeposito <= 15.00) {
												$importeadmitido=1;
											}
										}
										if($importeadmitido) {
											$sqlActualizaLink="UPDATE linkaportesusimra SET fechavalidacion = '$fechavalidacion', usuariovalidacion = '$usuariovalidacion', notificacion = 0 WHERE fechaarchivo = '$fechabanco' AND idmovimiento = $movimientobanco";
											if($resultActualizaLink = $dbh->query($sqlActualizaLink)) {
												$noddjj=0;
												$cantvali++;
												$listastatus="Ticket Validado";
												if($difdeposito==0.00) {
													$listamensaje="TODOS LOS DATOS DE LA IMPUTACION DE LINK PAGOS SON CORRECTOS.";
												} else {
													$listamensaje="DIFERENCIA (".$difdeposito.") EN EL IMPORTE (".$importebanco.") ACREDITADO POR LINK PAGOS.";
												}
												$listanotificacion="No Notificable";
											}
										} else {
											$noddjj=0;
											$cantnova++;
											$listastatus="Ticket No Validado";
											$listamensaje="EL IMPORTE (".$importebanco.") ACREDITADO POR LINK PAGOS ES DISTINTO AL DEL TICKET GENERADO (".$impoddjj.").";
											if($notificabanco==1) {
												$sqlActualizaLink="UPDATE linkaportesusimra SET tiponotificacion = 1 WHERE fechaarchivo = '$fechabanco' AND idmovimiento = $movimientobanco";
												if($resultActualizaLink = $dbh->query($sqlActualizaLink)) {
													$listanotificacion="Notificable";
													$senotifica++;
												}
											} else {
												$listanotificacion="Notificacion Efectuada";
											}
										}
									}
								}
							} 
							if($noddjj==1){
								$sqlBuscaValidada="SELECT COUNT(d.nrocontrol) AS cantdj, SUM((d.totalaporte+d.recargo)) AS totdep FROM cabddjjusimra d, vinculadocuusimra v WHERE d.cuil = '$cuil' AND d.cuit = '$cuitbanco' AND d.cuit = v.nrcuit AND d.nrocontrol = v.nrctrl AND v.referencia = '$referenciabanco' GROUP BY v.nrcuit, v.referencia";
								if($resultBuscaValidada=$dbh->query($sqlBuscaValidada)) {
									foreach($resultBuscaValidada as $totavali) {
										$valicant = $totavali[cantdj];
										$valiimpo = $totavali[totdep];
										if($valicant>0) {
											if($valiimpo==$importebanco) {
												$sqlBancoValidada="SELECT * FROM linkaportesusimra WHERE cuit = '$cuitbanco' AND referencia = '$referenciabanco' AND importe = $importebanco AND fechavalidacion != '0000-00-00 00:00:00' ORDER BY fechaarchivo DESC, fechadeposito DESC, idmovimiento DESC LIMIT 1";
												$resultBancoValidada=$dbh->query($sqlBancoValidada);
												foreach($resultBancoValidada as $bancovalidada) {
													$cantnova++;
													$listastatus="Ticket No Validado";
													$listamensaje="VALIDACION ANTERIOR PARA TICKET PRESENTADO EL ".invertirFecha($bancovalidada[fechaarchivo])." DEPOSITADO EL ".invertirFecha($bancovalidada[fechadeposito]).".";
													if($notificabanco==1) {
														$sqlActualizaLink="UPDATE linkaportesusimra SET tiponotificacion = 2 WHERE fechaarchivo = '$fechabanco' AND idmovimiento = $movimientobanco";
														if($resultActualizaLink = $dbh->query($sqlActualizaLink)) {
															$listanotificacion="Notificable";
															$senotifica++;
														}
													} else {
															$listanotificacion="Notificacion Efectuada";
													}
												}
											}
										}
									}
								}
							}
						} else {
							$cantnova++;
							$listastatus="Ticket No Validado";
							$listamensaje="TICKET RELACIONADO AL PAGO INEXISTENTE.";
							if($notificabanco==1) {
								$sqlActualizaLink="UPDATE linkaportesusimra SET tiponotificacion = 3 WHERE fechaarchivo = '$fechabanco' AND idmovimiento = $movimientobanco";
								if($resultActualizaLink = $dbh->query($sqlActualizaLink)) {
									$listanotificacion="Notificable";
									$senotifica++;
								}
							} else {
								$listanotificacion="Notificacion Efectuada";
							}
						}
					} else {
						$cantnova++;
						$listastatus="Ticket No Validado";
						$listamensaje="EMPRESA INEXISTENTE EN LA BASE DE DATOS DE USIMRA.";
						$listanotificacion="No Notificable";
					}
?>
							<td><?php echo $listastatus; ?></td>
							<td><?php echo $listamensaje; ?></td>
							<td><?php echo $listanotificacion; ?></td>
						</tr>
<?php
				}
?>
					</tbody>
				</table>
<?php
				$totabole=$cantvali+$cantnova;
				if($totabole!=0) {
?>
				<div align="center">
					<h3><?php echo $cantvali." tickets VALIDADOS y ".$cantnova." tickets NO VALIDADOS, sobre un TOTAL de ".$totabole." tickets."; ?></h3>
				</div>
<?php
				}
			}
		}
	}
	
	$dbh->commit();

	if($hayboleta==1) { ?>
		<p>&nbsp;</p>
		<table width="769" border="1" align="center">
		<tr align="center" valign="top">
	    <td width="385" valign="middle">
		<div align="left">
		<input type="reset" name="volver" value="Volver" onclick="location.href = 'procesamientoRegistrosLinkpagos.php'"/>
		</div>
		</td>
	    <td width="384" valign="middle">
		<div align="right">
        <input type="button" name="imprimir" value="Imprimir" onclick="window.print();"/>
	    </div>
		</td>
		</tr>
		</table>
<?php
		if($senotifica!=0) { ?>
			<p>&nbsp;</p>
			<div align="center">
				<h2>Procesar <input style="font-size: 16px; font-weight: bold; color:#FF0000" type="submit" name="notificacion" value="Notificacion" onclick="javascript:irANotificar()" align="left" /> de Errores de Validacion a las Empresas.</h2>
			</div>
<?php
		}
		if($cantvali!=0) { ?>
			<p>&nbsp;</p>
			<div align="center">
				<h2>No OLVIDE <input style="font-size: 16px; font-weight: bold; color:#000099" type="submit" name="registrar" value="Registrar Pagos" onclick="javascript:irARegistrar()" align="left" /> de los Tickets que acaban de ser validados.</h2>
			</div>
<?php
		}
	} else { ?>
		<p>&nbsp;</p>
		<table width="769" border="1" align="center">
		<tr align="center" valign="top">
	    <td width="769" valign="middle"><input type="reset" name="volver" value="Volver" onclick="location.href = 'procesamientoRegistrosLinkpagos.php'"/>
		</td>
		</tr>
		</table>
<?php
	}
}catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/usimra/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}
?>
</body>
</html>