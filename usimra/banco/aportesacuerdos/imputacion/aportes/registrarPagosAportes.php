<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php");
$fechacancelacion=date("Y-m-d H:i:s");
$fechasubida=date("Y-m-d");
$usuariocancelacion=$_SESSION['usuario'];
$sistemacancelacion='E';
$fechamodificacion="0000-00-00 00:00:00";
$usuariomodificacion="";
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
			headers:{0:{sorter:false}, 1:{sorter:false}, 2:{sorter:false}, 3:{sorter:false}, 4:{sorter:false}, 5:{sorter:true}, 6:{sorter:true}, 7:{sorter:false}}
		});
});
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
		<h1>Resultados de la Imputaci&oacute;n de Pagos por Aportes</h1>
	</div>
<?php
	$sqlControlImputar="SELECT COUNT(*) FROM banaportesusimra WHERE fechaimputacion = '00000000000000' and estadomovimiento in ('L','E','R')";
	$sqlLeeAImputar="SELECT * FROM banaportesusimra WHERE fechaimputacion = '00000000000000' and estadomovimiento in ('L','E','R') ORDER BY fechaacreditacion ASC";
	$resultControlImputar = $dbh->query($sqlControlImputar);
	if(!$resultControlImputar) { ?>
		<div align="center">
			<h3>Error en la consulta de la tabla BANAPORTESUSIMRA. Comuniquese con el Depto. de Sistemas.</h3>
		</div>
<?php
	}
	else {
		//Verifica si hay registros a validar
		if($resultControlImputar->fetchColumn()==0) {
			$haypago=0; ?>
			<div align="center">
				<h2>No hay pagos que deban ser imputados.</h2>
			</div>
<?php
		}
		else {
			$haypago=1;
    		$resultLeeAImputar=$dbh->query($sqlLeeAImputar);
    		if(!$resultLeeAImputar) { ?>
				<div align="center">
					<h3>Error en la consulta de Informaci&oacute;n del Banco. Comuniquese con el Depto. de Sistemas.</h3>
				</div>
<?php
			}
			else {
				set_time_limit(0);
				$cuil="99999999999";
				$cuentaboleta = '2';
				$cuentaremesa = '2';
				$nroremesa = '1';
				$nroremitoremesa='0';
				$cuentaremitosuelto='0';
				$fecharemitosuelto='0000-00-00';
				$nroremitosuelto='0';
				$estadoconciliacion='1';
				$fechaconciliacion=$fechacancelacion;
				$usuarioconciliacion=$usuariocancelacion;
				$totacanc=0.00;
				$cantcanc=0; ?>

				<table id="resultados" class="tablesorter" style="font-size:14px; text-align:center">
					<thead>
						<tr>
							<th>Codigo de Barra</th>
							<th>C.U.I.T.</th>
							<th>Mes</th>
							<th>Año</th>
							<th>Importe</th>
							<th>Status</th>
							<th>Mensaje</th>
							<th>Fecha Acreditacion</th>
						</tr>
					</thead>
					<tbody>
<?php
        		foreach($resultLeeAImputar as $imputar) {
					$nrobanco = $imputar[nromovimiento];
					$sucursalbanco = $imputar[sucursalorigen];
					$recaudabanco = $imputar[fecharecaudacion];
					$acreditabanco = $imputar[fechaacreditacion];
					$estadobanco = $imputar[estadomovimiento];
					$controlbanco = $imputar[nrocontrol];
					$importebanco = $imputar[importe];
					$codbarrabanco = $imputar[codigobarra];
					$cuitbanco = $imputar[cuit];
					$fechabanco = invertirFecha($imputar[fechaacreditacion]);
					$validadabanco = $imputar[fechavalidacion];
					$chequebanco = $imputar[chequenro];
					$presentada='P';
					$puedeimputar=0;
					$importeadmitido=0;
					$difdeposito=0.00;
					$montopagado=0.00;
					$recargo=0.00;
					$actualizabanco=0;
					$anterior=0; ?>
						<tr>
							<td><?php echo $codbarrabanco; ?></td>
							<td><?php echo $cuitbanco; ?></td>
<?php
					$sqlControlBuscaEmpresa="SELECT COUNT(*) FROM empresas WHERE cuit = '$cuitbanco'";
					$resultControlBuscaEmpresa=$dbh->query($sqlControlBuscaEmpresa);
					if($resultControlBuscaEmpresa->fetchColumn()!=0) {
						$sqlBuscaEmpresa="SELECT * FROM empresas WHERE cuit = :cuit";
						//echo $sqlBuscaEmpresa; echo "<br>";
						$resultBuscaEmpresa = $dbh->prepare($sqlBuscaEmpresa);
						$resultBuscaEmpresa->execute(array(':cuit' => $cuitbanco));
						if($resultBuscaEmpresa) {
							if($estadobanco=='E') {
								if(strcmp($validadabanco,"0000-00-00 00:00:00")!=0) {
									$puedeimputar=1;
								}
								else {
									$listacuota="-";
									$listaanio="-";
									$listaimporte=$importebanco;
									$listastatus="Pago No Imputado";
									$listamensaje="LA BOLETA RELACIONADA A ESTE PAGO NO FUE VALIDADA.";
								}
							}
							else {
								$encontropresentada=0;
								$sqlBuscaValidacion="SELECT * FROM banaportesusimra WHERE nromovimiento = :nromovimiento AND sucursalorigen = :sucursalorigen AND fecharecaudacion = :fecharecaudacion AND estadomovimiento = :estadomovimiento";
								//echo $sqlBuscaValidacion; echo "<br>";
								$resultBuscaValidacion = $dbh->prepare($sqlBuscaValidacion);
								$resultBuscaValidacion->execute(array(':nromovimiento' => $nrobanco, ':sucursalorigen' => $sucursalbanco, ':fecharecaudacion' => $recaudabanco, ':estadomovimiento' => $presentada));
								if($resultBuscaValidacion) {
									foreach($resultBuscaValidacion as $buscavalidacion) {
										if(strcmp($buscavalidacion[fechavalidacion],"0000-00-00 00:00:00")!=0) {
											if($estadobanco=='R') {
												$actualizabanco=1;
												$listacuota="-";
												$listaanio="-";
												$listaimporte=$importebanco;
												$listastatus="Pago No Imputado";
												$listamensaje="EL CHEQUE ".$chequebanco." ASIGNADO A ESTE PAGO HA SIDO RECHAZADO.";
											}
											if($estadobanco=='L') {
												$puedeimputar=1;
											}
										}
										else {
											$listacuota="-";
											$listaanio="-";
											$listaimporte=$importebanco;
											$listastatus="Pago No Imputado";
											$listamensaje="LA BOLETA RELACIONADA A ESTE PAGO NO FUE VALIDADA.";
										}
										$encontropresentada=1;
									}
								}
								if(!$encontropresentada) {
									$listacuota="-";
									$listaanio="-";
									$listaimporte=$importebanco;
									$listastatus="Pago No Imputado";
									$listamensaje="FALTA LA PRESENTACION DE LA BOLETA RELACIONADA A ESTE PAGO. RECLAME AL BANCO.";
								}
							}
							if($puedeimputar) {
								$sqlBuscaCabBoleta="SELECT * FROM ddjjusimra WHERE nrcuit = :nrcuit AND nrcuil = :nrcuil AND nrctrl = :nrctrl";
								//echo $sqlBuscaCabBoleta; echo "<br>";
								$resultBuscaCabBoleta=$dbh->prepare($sqlBuscaCabBoleta);
								$resultBuscaCabBoleta->execute(array(':nrcuit' => $cuitbanco, ':nrcuil' => $cuil, ':nrctrl' => $controlbanco));
								if($resultBuscaCabBoleta) {
									foreach($resultBuscaCabBoleta as $cabboleta) {
										$totalboleta = round(($cabboleta[totapo]+$cabboleta[recarg]),2);
										if($importebanco==$totalboleta) {
											$importeadmitido=1;
										} else {
											$difdeposito=round(($importebanco-$totalboleta),2);
											if($difdeposito >= -50.00 && $difdeposito <= 50.00) {
												$importeadmitido=1;
											}
										}
										if($importeadmitido) {
											$ultimopago=0;
											$sqlBuscaUltimoPago="SELECT * FROM seguvidausimra WHERE cuit = :cuit AND anopago = :anopago AND mespago = :mespago ORDER BY nropago desc LIMIT 1";
											//echo $sqlBuscaUltimoPago; echo "<br>";
											$resultBuscaUltimoPago=$dbh->prepare($sqlBuscaUltimoPago);
											$resultBuscaUltimoPago->execute(array(':cuit' => $cuitbanco, ':anopago' => $cabboleta[perano], ':mespago' => $cabboleta[permes]));
											if($resultBuscaUltimoPago) {
												foreach($resultBuscaUltimoPago as $pagofinal) {
													$ultimopago=$pagofinal[nropago];
												}
											}
											$ultimopago=$ultimopago+1;
											//echo $ultimopago; echo "<br>";	
											$sqlAgregaCabDDJJ="INSERT INTO cabddjjusimra VALUES ('$cabboleta[id]','$cabboleta[nrcuit]','$cabboleta[nrcuil]','$cabboleta[permes]','$cabboleta[perano]','$cabboleta[remune]','$cabboleta[apo060]','$cabboleta[apo100]','$cabboleta[apo150]','$cabboleta[totapo]','$cabboleta[recarg]','$cabboleta[nfilas]','$cabboleta[instrumento]','$cabboleta[nrctrl]','$cabboleta[observ]','$fechasubida')";
											//echo $sqlAgregaCabDDJJ; echo "<br>";
											if($resultAgregaCabDDJJ = $dbh->query($sqlAgregaCabDDJJ)) {
											//$sqlAgregaCabDDJJ="INSERT INTO cabddjjusimra (id,cuit,cuil,mesddjj,anoddjj,remuneraciones,apor060,apor100,apor150,totalaporte,recargo,cantidadpersonal,instrumentodepago,nrocontrol,observaciones,fechasubida) VALUES (:id,:cuit,:cuil,:mesddjj,:anodjj,:remuneraciones,:apor060,:apor100,:apor150,:totalaporte,:recargo,:cantidadpersonal,:instrumentodepago,:nrocontrol,:observaciones,:fechasubida)";
											//echo $sqlAgregaCabDDJJ; echo "<br>";
											//$resultAgregaCabDDJJ = $dbh->prepare($sqlAgregaCabDDJJ);
											//if($resultAgregaCabDDJJ->execute(array(':id' => $cabboleta[id], ':cuit' => $cabboleta[nrcuit], ':cuil' => $cabboleta[nrcuil], ':mesddjj' => $cabboleta[permes], ':anoddjj' => $cabboleta[perano], ':remuneraciones' => $cabboleta[remune], ':apor060' => $cabboleta[apo060], ':apor100' => $cabboleta[apo100], ':apor150' => $cabboleta[apo150], ':totalaporte' => $cabboleta[totapo], ':recargo' => $cabboleta[recarg], ':cantidadpersonal' => $cabboleta[nfilas], ':instrumentodepago' => $cabboleta[instrumento], ':nrocontrol' => $cabboleta[nrctrl], ':observaciones' => $cabboleta[observ], ':fechasubida' => $fechasubida))) {
												//print "<p>Registro de Cabecera de Boleta insertado correctamente.</p>\n";
												$sqlBuscaDetBoleta="SELECT * FROM ddjjusimra WHERE nrcuit = :nrcuit AND nrcuil != :nrcuil AND nrctrl = :nrctrl";
												//echo $sqlBuscaDetBoleta; echo "<br>";
												$resultBuscaDetBoleta=$dbh->prepare($sqlBuscaDetBoleta);
												$resultBuscaDetBoleta->execute(array(':nrcuit' => $cuitbanco, ':nrcuil' => $cuil, ':nrctrl' => $controlbanco));
												if($resultBuscaDetBoleta) {
													foreach($resultBuscaDetBoleta as $detboleta) {
														$sqlAgregaDetDDJJ="INSERT INTO detddjjusimra (id,cuit,cuil,mesddjj,anoddjj,remuneraciones,apor060,apor100,apor150,nrocontrol,fechasubida) VALUES (:id,:cuit,:cuil,:mesddjj,:anoddjj,:remuneraciones,:apor060,:apor100,:apor150,:nrocontrol,:fechasubida)";
														//echo $sqlAgregaDetDDJJ; echo "<br>";
														$resultAgregaDetDDJJ = $dbh->prepare($sqlAgregaDetDDJJ);
														if($resultAgregaDetDDJJ->execute(array(':id' => $detboleta[id], ':cuit' => $detboleta[nrcuit], ':cuil' => $detboleta[nrcuil], ':mesddjj' => $detboleta[permes], ':anoddjj' => $detboleta[perano], ':remuneraciones' => $detboleta[remune], ':apor060' => $detboleta[apo060], ':apor100' => $detboleta[apo100], ':apor150' => $detboleta[apo150], ':nrocontrol' => $detboleta[nrctrl], ':fechasubida' => $fechasubida))) {
															//print "<p>Registro de Detalle de Boleta insertado correctamente.</p>\n";
														}
													}
												}
												$sqlBorraBoleta="DELETE FROM ddjjusimra WHERE nrcuit = :nrcuit AND nrctrl = :nrctrl";
												//echo $sqlBorraBoleta; echo "<br>";
												$resultBorraBoleta = $dbh->prepare($sqlBorraBoleta);
												if($resultBorraBoleta->execute(array(':nrcuit' => $cuitbanco, ':nrctrl' => $controlbanco))) {
													//print "<p>Registros de Boleta borrado correctamente.</p>\n";
													$montopagado=$importebanco;
													$recargo=($cabboleta[recarg])+($difdeposito);
													$sqlAgregaPago="INSERT INTO seguvidausimra (cuit,mespago,anopago,nropago,periodoanterior,fechapago,cantidadpersonal,remuneraciones,montorecargo,montopagado,observaciones,sistemacancelacion,codigobarra,fechaacreditacion,fecharegistro,usuarioregistro,fechamodificacion,usuariomodificacion) VALUES (:cuit,:mespago,:anopago,:nropago,:periodoanterior,:fechapago,:cantidadpersonal,:remuneraciones,:montorecargo,:montopagado,:observaciones,:sistemacancelacion,:codigobarra,:fechaacreditacion,:fecharegistro,:usuarioregistro,:fechamodificacion,:usuariomodificacion)";
													//echo $sqlAgregaPago; echo "<br>";
													$resultAgregaPago = $dbh->prepare($sqlAgregaPago);
													if($resultAgregaPago->execute(array(':cuit' => $cuitbanco, ':mespago' => $cabboleta[permes], ':anopago' => $cabboleta[perano], ':nropago' => $ultimopago, ':periodoanterior' => $anterior,':fechapago' => $recaudabanco, ':cantidadpersonal' => $cabboleta[nfilas], ':remuneraciones' => $cabboleta[remune], ':montorecargo' => $recargo, ':montopagado' => $montopagado, ':observaciones' => $cabboleta[observ], ':sistemacancelacion' => $sistemacancelacion, ':codigobarra' => $codbarrabanco, ':fechaacreditacion' => $acreditabanco, ':fecharegistro' => $fechacancelacion, ':usuarioregistro' => $usuariocancelacion, ':fechamodificacion' => $fechamodificacion, ':usuariomodificacion' => $usuariomodificacion))) {
														//print "<p>Registro de Pago insertado correctamente.</p>\n";
														$sqlAgregaApo060="INSERT INTO apor060usimra (cuit,mespago,anopago,nropago,importe) VALUES (:cuit,:mespago,:anopago,:nropago,:importe)";
														//echo $sqlAgregaApo060; echo "<br>";
														$resultAgregaApo060 = $dbh->prepare($sqlAgregaApo060);
														if($resultAgregaApo060->execute(array(':cuit' => $cuitbanco, ':mespago' => $cabboleta[permes], ':anopago' => $cabboleta[perano], ':nropago' => $ultimopago, ':importe' => $cabboleta[apo060]))) {
														}
														$sqlAgregaApo100="INSERT INTO apor100usimra (cuit,mespago,anopago,nropago,importe) VALUES (:cuit,:mespago,:anopago,:nropago,:importe)";
														//echo $sqlAgregaApo100; echo "<br>";
														$resultAgregaApo100 = $dbh->prepare($sqlAgregaApo100);
														if($resultAgregaApo100->execute(array(':cuit' => $cuitbanco, ':mespago' => $cabboleta[permes], ':anopago' => $cabboleta[perano], ':nropago' => $ultimopago, ':importe' => $cabboleta[apo100]))) {
														}
														$sqlAgregaApo150="INSERT INTO apor150usimra (cuit,mespago,anopago,nropago,importe) VALUES (:cuit,:mespago,:anopago,:nropago,:importe)";
														//echo $sqlAgregaApo150; echo "<br>";
														$resultAgregaApo150 = $dbh->prepare($sqlAgregaApo150);
														if($resultAgregaApo150->execute(array(':cuit' => $cuitbanco, ':mespago' => $cabboleta[permes], ':anopago' => $cabboleta[perano], ':nropago' => $ultimopago, ':importe' => $cabboleta[apo150]))) {
														}
														$sqlLeeRemitosRemesas="SELECT * FROM remitosremesasusimra WHERE codigocuenta = '$cuentaremesa' and sistemaremesa = '$sistemacancelacion' and fecharemesa = '$acreditabanco' and nroremesa = '$nroremesa' and nrocontrol = '$controlbanco' and importebruto = '$importebanco'";
														$resultLeeRemitosRemesas = $dbh->query($sqlLeeRemitosRemesas);
														foreach($resultLeeRemitosRemesas as $remitos) {
															$nroremitoremesa = $remitos[nroremito];
															$sqlAddConcilia="INSERT INTO conciliapagosusimra (cuit, mespago, anopago, nropago, cuentaboleta, cuentaremesa, fecharemesa, nroremesa, nroremitoremesa, cuentaremitosuelto, fecharemitosuelto, nroremitosuelto, estadoconciliacion, fechaconciliacion, usuarioconciliacion, fecharegistro, usuarioregistro, fechamodificacion, usuariomodificacion) VALUES ('$cuitbanco','$cabboleta[permes]','$cabboleta[perano]','$ultimopago','$cuentaboleta','$cuentaremesa','$acreditabanco','$nroremesa','$nroremitoremesa','$cuentaremitosuelto','$fecharemitosuelto','$nroremitosuelto','$estadoconciliacion','$fechaconciliacion','$usuarioconciliacion','$fechacancelacion','$usuariocancelacion','$fechamodificacion','$usuariomodificacion')";
															$resultAddConcilia = $dbh->query($sqlAddConcilia);
															//echo $sqlAddConcilia; echo "<br>";
														}
														$totacanc=$totacanc+$montopagado;
														$cantcanc++;
														$actualizabanco=1;
														$listacuota=$cabboleta[permes];
														$listaanio=$cabboleta[perano];
														$listaimporte=$montopagado;
														$listastatus="Pago Imputado";
														if($difdeposito==0.00) {
															$listamensaje="IMPUTACION CORRECTA DEL PAGO.";
														} else {
															$listamensaje="IMPUTACION CORRECTA DEL PAGO. DIFERENCIA (".$difdeposito.") CON LA DDJJ.";
														}
													}
												}
												else {
												   //print "<p>Error al borrar los registros de Boleta.</p>\n";
												}
											}
											else {
												$listacuota=$cabboleta[permes];
												$listaanio=$cabboleta[perano];
												$listaimporte=$totalboleta;
												$listastatus="ERROR CRV";
												$listamensaje="COMUNIQUESE CON EL DEPTO. DE SISTEMAS PARA INFORMAR EL ERROR.";
											}
										}
										else {
											$listacuota=$cabboleta[permes];
											$listaanio=$cabboleta[perano];
											$listaimporte=$totalboleta;
											$listastatus="Pago No Imputado";
											$listamensaje="EL IMPORTE (".$importebanco.") ACREDITADO POR EL BANCO ES DISTINTO AL DE LA BOLETA GENERADA.";
										}
									}
								}
							}
							if($actualizabanco) {
								$sqlActualizaBanco="UPDATE banaportesusimra SET fechaimputacion = :fechaimputacion, usuarioimputacion = :usuarioimputacion WHERE cuit = :cuit AND nrocontrol = :nrocontrol and estadomovimiento = :estadomovimiento";
								$resultActualizaBanco = $dbh->prepare($sqlActualizaBanco);
								//echo $sqlActualizaBanco; echo "<br>";
								if($resultActualizaBanco->execute(array(':fechaimputacion' => $fechacancelacion, ':usuarioimputacion' => $usuariocancelacion, ':cuit' => $cuitbanco, ':nrocontrol' => $controlbanco, ':estadomovimiento' => $estadobanco))) {
									//print "<p>Registro de Banco actualizado correctamente.</p>\n";
								}
							}
						}
					}
					else {
						$listacuota="-";
						$listaanio="-";
						$listaimporte=$importebanco;
						$listastatus="Pago No Imputado";
						$listamensaje="EMPRESA INEXISTENTE EN LA BASE DE DATOS DE USIMRA.";
					} ?>
							<td><?php echo $listacuota; ?></td>
							<td><?php echo $listaanio; ?></td>
							<td><?php echo $listaimporte; ?></td>
							<td><?php echo $listastatus; ?></td>
							<td><?php echo $listamensaje; ?></td>
							<td><?php echo $fechabanco; ?></td>
						</tr>
<?php
        		} ?>
					</tbody>
				</table>
<?php
				if($totacanc!=0.00) { ?>
					<div align="center">
						<h3><?php echo $cantcanc." pagos REGISTRADOS por un TOTAL de $".$totacanc; ?></h3>
					</div>
<?php					
				}
    		}
		}
	}

	$dbh->commit();

	if($haypago==1) { ?>
		<p>&nbsp;</p>
		<table width="770" border="1" align="center">
		<tr align="center" valign="top">
	    <td width="385" valign="middle">
		<div align="left">
		<input type="reset" name="volver" value="Volver" onclick="location.href = 'procesamientoRegistrosAportes.php'"/>
		</div>
		</td>
	    <td width="385" valign="middle">
		<div align="right">
        <input type="button" name="imprimir" value="Imprimir" onclick="window.print();"/>
	    </div>
		</td>
		</tr>
		</table>
<?php
	}
	else { ?>
		<p>&nbsp;</p>
		<table width="770" border="1" align="center">
		<tr align="center" valign="top">
	    <td width="770" valign="middle"><input type="reset" name="volver" value="Volver" onclick="location.href = 'procesamientoRegistrosAportes.php'"/>
		</td>
		</tr>
		</table>
<?php
	}
}catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/usimra/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}
?>
</body>
</html>