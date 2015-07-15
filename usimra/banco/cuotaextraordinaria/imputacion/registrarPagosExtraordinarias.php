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
		<h1>Resultados de la Imputaci&oacute;n de Pagos</h1>
	</div>
<?php
	$sqlControlImputar="SELECT COUNT(*) FROM banextraordinariausimra WHERE fechaimputacion = '00000000000000' and estadomovimiento in ('L','E','R')";
	$sqlLeeAImputar="SELECT * FROM banextraordinariausimra WHERE fechaimputacion = '00000000000000' and estadomovimiento in ('L','E','R')";

	$resultControlImputar = $dbh->query($sqlControlImputar);

	if(!$resultControlImputar) { ?>
		<div align="center">
			<h3>Error en la consulta de la tabla BANEXTRAORDINARIAUSIMRA. Comuniquese con el Depto. de Sistemas.</h3>
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
				$cuil="99999999999";
				$fechamodificacion='00000000000000';
				$usuariomodificacion='';
				$totacanc=0.00;
				$cantcanc=0; ?>

				<table id="resultados" class="tablesorter" style="font-size:14px; text-align:center">
					<thead>
						<tr>
							<th>Codigo de Barra</th>
							<th>C.U.I.T.</th>
							<th>Cuota</th>
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
					$actualizabanco=0; ?>
						<tr>
							<td><?php echo $codbarrabanco; ?></td>
							<td><?php echo $cuitbanco; ?></td>
<?php
					$sqlBuscaEmpresa="SELECT * FROM empresas WHERE cuit = :cuit";
					//echo $sqlBuscaEmpresa; echo "<br>";
					$resultBuscaEmpresa = $dbh->prepare($sqlBuscaEmpresa);
					$resultBuscaEmpresa->execute(array(':cuit' => $cuitbanco));
					if($resultBuscaEmpresa) {
						if($estadobanco=='E') {
							if(strcmp($validadabanco,"00000000000000")!=0) {
								$puedeimputar=1;
							}
							else {
								$listacuota="-";
								$listaanio="-";
								$listaimporte=$importebanco;
								$listastatus="Pago No Imputado";
								$listamensaje="LA BOLETA RELACIONADA A ESTE PAGO NO FUE VALIDADA";
							}
						}
						else {
							$sqlBuscaValidacion="SELECT * FROM banextraordinariausimra WHERE nromovimiento = :nromovimiento AND sucursalorigen = :sucursalorigen AND fecharecaudacion = :fecharecaudacion AND estadomovimiento = :estadomovimiento";
							//echo $sqlBuscaValidacion; echo "<br>";
							$resultBuscaValidacion = $dbh->prepare($sqlBuscaValidacion);
							$resultBuscaValidacion->execute(array(':nromovimiento' => $nrobanco, ':sucursalorigen' => $sucursalbanco, ':fecharecaudacion' => $recaudabanco, ':estadomovimiento' => $presentada));
							if($resultBuscaValidacion) {
								foreach($resultBuscaValidacion as $buscavalidacion) {
									if(strcmp($buscavalidacion[fechavalidacion],"00000000000000")!=0) {
										if($estadobanco=='R') {
											$actualizabanco=1;
											$listacuota="-";
											$listaanio="-";
											$listaimporte=$importebanco;
											$listastatus="Pago No Imputado";
											$listamensaje="EL CHEQUE ".$chequebanco." ASIGNADO A ESTE PAGO HA SIDO RECHAZADO";
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
										$listamensaje="LA BOLETA RELACIONADA A ESTE PAGO NO FUE VALIDADA";
									}
								}
							}
							else {
								$listacuota="-";
								$listaanio="-";
								$listaimporte=$importebanco;
								$listastatus="Pago No Imputado";
								$listamensaje="FALTA LA PRESENTACION DE LA BOLETA RELACIONADA A ESTE PAGO. RECLAME AL BANCO";
							}
						}
						if($puedeimputar)
						{
							$sqlBuscaCabBoleta="SELECT * FROM ddjjusimra WHERE nrcuit = :nrcuit AND nrcuil = :nrcuil AND nrctrl = :nrctrl";
							//echo $sqlBuscaCabBoleta; echo "<br>";
							$resultBuscaCabBoleta=$dbh->prepare($sqlBuscaCabBoleta);
							$resultBuscaCabBoleta->execute(array(':nrcuit' => $cuitbanco, ':nrcuil' => $cuil, ':nrctrl' => $controlbanco));
							if($resultBuscaCabBoleta) {
				        		foreach($resultBuscaCabBoleta as $cabboleta) {
									$totalboleta = $cabboleta[totapo]+$cabboleta[recarg];
									if($importebanco==$totalboleta) {
										$ultimopago=0;
										$sqlBuscaUltimoPago="SELECT * FROM cuotaextraordinariausimra WHERE cuit = :cuit AND anopago = :anopago AND mespago = :mespago ORDER BY nropago desc LIMIT 1";
										//echo $sqlBuscaUltimoPago; echo "<br>";
										$resultBuscaUltimoPago=$dbh->prepare($sqlBuscaUltimoPago);
										$resultBuscaUltimoPago->execute(array(':cuit' => $cuitbanco, ':anopago' => $cabboleta[perano], ':mespago' => $cabboleta[permes]));
										if($resultBuscaUltimoPago) {
							        		foreach($resultBuscaUltimoPago as $pagofinal) {
												$ultimopago=$pagofinal[nropago];
											}
										}
										$ultimopago=$ultimopago+1;

										$sqlAgregaCabDDJJ="INSERT INTO cabddjjusimra (id,cuit,cuil,mesddjj,anoddjj,remuneraciones,apor060,apor100,apor150,totalaporte,recargo,cantidadpersonal,instrumentodepago,nrocontrol,observaciones,fechasubida) VALUES (:id,:cuit,:cuil,:mesddjj,:anodjj,:remuneraciones,:apor060,:apor100,:apor150,:totalaporte,:recargo,:cantidadpersonal,:instrumentodepago,:nrocontrol,:observaciones,:fechasubida)";
										//echo $sqlAgregaCabDDJJ; echo "<br>";
										$resultAgregaCabDDJJ = $dbh->prepare($sqlAgregaCabDDJJ);
										if($resultAgregaCabDDJJ->execute(array(':id' => $cabboleta[id], ':cuit' => $cabboleta[nrcuit], ':cuil' => $cabboleta[nrcuil], ':mesddjj' => $cabboleta[permes], ':anoddjj' => $cabboleta[perano], ':remuneraciones' => $cabboleta[remune], ':apor060' => $cabboleta[apo060], ':apor100' => $cabboleta[apo100], ':apor150' => $cabboleta[apo150], ':totalaporte' => $cabboleta[totapo], ':recargo' => $cabboleta[recarg], ':cantidadpersonal' => $cabboleta[nfilas], ':instrumentodepago' => $cabboleta[instrumento], ':nrocontrol' => $cabboleta[nrctrl], ':observaciones' => $cabboleta[observ], ':fechasubida' => $fechasubida))) {
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
														$sqlBorraBoleta="DELETE FROM ddjjusimra WHERE nrcuit = :nrcuit AND nrctrl = :nrctrl";
														//echo $sqlBorraBoleta; echo "<br>";
														$resultBorraBoleta = $dbh->prepare($sqlBorraBoleta);
														if($resultBorraBoleta->execute(array(':nrcuit' => $cuitbanco, ':nrctrl' => $controlbanco))) {
															//print "<p>Registros de Boleta borrado correctamente.</p>\n";
															$sqlAgregaPago="INSERT INTO cuotaextraordinariausimra (cuit,mespago,nopago,nropago,fechapago,cantidadaportantes,totalaporte,montorecargo,montopagado,observaciones,sistemacancelacion,codigobarra,fechaacreditacion,fecharegistro,usuarioregistro,fechamodificacion,usuariomodificacion) VALUES (:cuit,:mespago,:anopago,:nropago,:fechapago,:cantidadaportantes,:totalaporte,:montorecargo,:montopagado,:observaciones,:sistemacancelacion,:codigobarra,:fechaacreditacion,:fecharegistro,:usuarioregistro,:fechamodificacion,:usuariomodificacion)";
															//echo $sqlAgregaPago; echo "<br>";
															$resultAgregaPago = $dbh->prepare($sqlAgregaPago);
															if($resultAgregaPago->execute(array(':cuit' => $cuitbanco, ':mespago' => $cabboleta[permes], ':anopago' => $cabboleta[perano], ':nropago' => $ultimopago, ':fechapago' => $recaudabanco, ':cantidadaportantes' => $cabboleta[nfilas], ':totalaporte' => $cabboleta[totapo], ':montorecargo' => $cabboleta[recarg], ':montopagado' => $totalboleta, ':observaciones' => $cabboleta[observ], ':sistemacancelacion' => $sistemacancelacion, ':codigobarra' => $codbarrabanco, ':fechaacreditacion' => $acreditabanco, ':fecharegistro' => $fechacancelacion, ':usuarioregistro' => $usuariocancelacion, ':fechamodificacion' => $fechamodificacion, ':usuariomodificacion' => $usuariomodificacion))) {
																//print "<p>Registro de Pago insertado correctamente.</p>\n";
																$totacanc=$totacanc+$totalboleta;
																$cantcanc++;
																$actualizabanco=1;
																$listacuota=$cabboleta[permes];
																$listaanio=$cabboleta[perano];
																$listaimporte=$totalboleta;
																$listastatus="Pago Imputado";
																$listamensaje="IMPUTACION CORRECTA DEL PAGO";
															}
														}
														else {
														   //print "<p>Error al borrar los registros de Boleta.</p>\n";
														}
													}
												}
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
							$sqlActualizaBanco="UPDATE banextraordinariausimra SET fechaimputacion = :fechaimputacion, usuarioimputacion = :usuarioimputacion WHERE cuit = :cuit AND nrocontrol = :nrocontrol and estadomovimiento = :estadomovimiento";
							$resultActualizaBanco = $dbh->prepare($sqlActualizaBanco);
							//echo $sqlActualizaBanco; echo "<br>";
							if($resultActualizaBanco->execute(array(':fechaimputacion' => $fechacancelacion, ':usuarioimputacion' => $usuariocancelacion, ':cuit' => $cuitbanco, ':nrocontrol' => $controlbanco, ':estadomovimiento' => $estadobanco))) {
							    //print "<p>Registro de Banco actualizado correctamente.</p>\n";
							}
						}
					}
					else {
						$listacuota="-";
						$listaanio="-";
						$listaimporte=$importebanco;
						$listastatus="Pago No Imputado";
						$listamensaje="EMPRESA INEXISTENTE EN LA BASE DE DATOS DE USIMRA";
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
		<input type="reset" name="volver" value="Volver" onclick="location.href = 'procesamientoRegistrosExtraordinarias.php'"/>
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
	    <td width="770" valign="middle"><input type="reset" name="volver" value="Volver" onclick="location.href = 'procesamientoRegistrosExtraordinarias.php'"/>
		</td>
		</tr>
		</table>
<?php
	}
}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}
?>
</body>
</html>