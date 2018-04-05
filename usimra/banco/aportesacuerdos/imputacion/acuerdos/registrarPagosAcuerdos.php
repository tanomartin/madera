<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php");
$fechacancelacion = date("Y-m-d H:i:s");
$usuariocancelacion = $_SESSION['usuario'];
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
			headers:{0:{sorter:false}, 1:{sorter:false}, 2:{sorter:false}, 3:{sorter:false}, 4:{sorter:false}, 5:{sorter:false}, 6:{sorter:false}, 7:{sorter:false}}
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
		<h1>Resultados de la Imputaci&oacute;n de Pagos por Acuerdos</h1>
	</div>
<?php
	$sqlControlImputar="SELECT COUNT(*) FROM banacuerdosusimra WHERE fechaimputacion = '00000000000000' and estadomovimiento in ('L','E','R')";
	$sqlLeeAImputar="SELECT * FROM banacuerdosusimra WHERE fechaimputacion = '00000000000000' and estadomovimiento in ('L','E','R')";
	$resultControlImputar = $dbh->query($sqlControlImputar);
	if(!$resultControlImputar) { ?>
		<div align="center">
			<h3>Error en la consulta de la tabla BANACUERDOSUSIMRA. Comuniquese con el Depto. de Sistemas.</h3>
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
				$cuentaboleta = '2';
				$cuentaremesa = '2';
				$nroremesa = '1';
				$nroremitoremesa='0';
				$cuentaremitosuelto='0';
				$fecharemitosuelto='00000000';
				$nroremitosuelto='0';
				$estadoconciliacion='1';
				$fechaconciliacion=$fechacancelacion;
				$usuarioconciliacion=$usuariocancelacion;
				$fechamodificacion='00000000000000';
				$usuariomodificacion='';
				$totacanc=0.00;
				$cantcanc=0; ?>

				<table id="resultados" class="tablesorter" style="font-size:14px; text-align:center">
					<thead>
						<tr>
							<th>Codigo de Barra</th>
							<th>C.U.I.T.</th>
							<th>Acuerdo</th>
							<th>Cuota</th>
							<th>Importe</th>
							<th>Status</th>
							<th>Mensaje</th>
							<th>Fecha Acreditacion</th>
						</tr>
					</thead>
					<tbody>
<?php
        		foreach($resultLeeAImputar as $imputar) {
					$controlbanco = $imputar[nrocontrol];
					$estado = $imputar[estadomovimiento];
					$importebanco = $imputar[importe];
					$cuitbanco = $imputar[cuit];
					$recaudabanco = $imputar[fecharecaudacion];
					$acreditabanco = $imputar[fechaacreditacion];
					$fechabanco = invertirFecha($imputar[fechaacreditacion]);
					$codbarrabanco = $imputar[codigobarra];
					$validadabanco = $imputar[fechavalidacion];
					
					$sqlBuscaValida="SELECT * FROM validasusimra WHERE nrocontrol = :nrocontrol AND cuit = :cuit";
					//echo $sqlBuscaValida; echo "<br>";
					$resultBuscaValida = $dbh->prepare($sqlBuscaValida);
					$resultBuscaValida->execute(array(':nrocontrol' => $controlbanco, ':cuit' => $cuitbanco));
					if($resultBuscaValida) {
		        		foreach($resultBuscaValida as $validas) {
							$cuitboleta = $validas[cuit];
							$acuerdo = $validas[nroacuerdo];
							$cuota = $validas[nrocuota];
							$importeboleta = $validas[importe];
							$controlboleta = $validas[nrocontrol]; ?>

						<tr>
						    <td><?php echo $codbarrabanco; ?></td>
						    <td><?php echo $cuitboleta; ?></td>
						    <td><?php echo $acuerdo; ?></td>
						    <td><?php echo $cuota; ?></td>
						    <td><?php echo $importeboleta; ?></td>
<?php
							if($importebanco==$importeboleta) {
								if($cuitbanco==$cuitboleta) {
									$sqlVerificaCuota="SELECT * from cuoacuerdosusimra WHERE cuit = :cuit and nroacuerdo = :nroacuerdo and nrocuota = :nrocuota";
									$resultVerificaCuota = $dbh->prepare($sqlVerificaCuota);
									if($resultVerificaCuota->execute(array(':cuit' => $cuitboleta, ':nroacuerdo' => $acuerdo, ':nrocuota' => $cuota))) {
						        		foreach($resultVerificaCuota as $cuotas) {
											$cuitcuota = $cuotas[cuit];
											$acuerdocuota = $cuotas[nroacuerdo];
											$cuotacuota = $cuotas[nrocuota];
											$importecuota = $cuotas[montocuota];
											$pagada = $cuotas[montopagada];
											$cancelacion = $cuotas[tipocancelacion];
											$sistema = $cuotas[sistemacancelacion];
											$boleta = $cuotas[boletaimpresa];

											$puedecancelar = 0;

											if($estado=='R' && $cancelacion==1) {
												$tiposcanc = '10';
												$observaci = "Rechazo Electronico-Cheque: ".$cuotas[chequenro]." ".$cuotas[chequebanco]." ".invertirFecha($cuotas[chequefecha]);
												$boletaimp = '0';
												$montopago = '0.00';
												$fechapago = '00000000';
												$fechacanc = '00000000';
												$sistecanc = '';
												$codibarra = '';
												$puedecancelar = 1;
											}

											if($estado=='L' && $cancelacion==1) {
												$tiposcanc = $cancelacion;
												$observaci = '';
												$boletaimp = $boleta;
												$montopago = $importebanco;
												$fechapago = $recaudabanco;
												$fechacanc = $fechacancelacion;
												$sistecanc = 'E';
												$codibarra = $codbarrabanco;
												$puedecancelar = 1;
											}

											if($estado=='L' && $cancelacion==3) {
												//lee valoresalcobro
												$sqlLeeValorAlCobro = "SELECT * from valoresalcobrousimra where cuit = :cuit and nroacuerdo = :nroacuerdo and nrocuota = :nrocuota";
												$resultLeeValorAlCobro = $dbh->prepare($sqlLeeValorAlCobro); 
												if($resultLeeValorAlCobro->execute(array(':cuit' => $cuitcuota, ':nroacuerdo' => $acuerdocuota, ':nrocuota' => $cuotacuota))) {
						        					foreach($resultLeeValorAlCobro as $valoralcobro) {
														$nrocheque = $valoralcobro[chequenrousimra];
														$fechaChe = invertirFecha($valoralcobro[chequefechausimra]);
													}

													$tiposcanc = $cancelacion;
													$observaci = "Cancelada cheque USIMRA Nro. ".$nrocheque." de Fecha ".$fechaChe;
													$boletaimp = $boleta;
													$montopago = $importebanco;
													$fechapago = $recaudabanco;
													$fechacanc = $fechacancelacion;
													$sistecanc = 'E';
													$codibarra = $codbarrabanco;
													$puedecancelar = 1;
												}
												else {
													$puedecancelar = 0;
												}
											}

											if($estado=='E' && $cancelacion==2) {
												if($validadabanco!='00000000000000') {
													$tiposcanc = $cancelacion;
													$observaci = '';
													$boletaimp = $boleta;
													$montopago = $importebanco;
													$fechapago = $recaudabanco;
													$fechacanc = $fechacancelacion;
													$sistecanc = 'E';
													$codibarra = $codbarrabanco;
													$puedecancelar = 1;
												}
												else {
													$puedecancelar = 0;
												}
											}

											if($estado=='E' && $cancelacion==3) {
												if($validadabanco!='00000000000000') {
													//lee valoresalcobro
													$sqlLeeValorAlCobro = "SELECT * from valoresalcobrousimra where cuit = :cuit and nroacuerdo = :nroacuerdo and nrocuota = :nrocuota";
													$resultLeeValorAlCobro = $dbh->prepare($sqlLeeValorAlCobro); 
													if($resultLeeValorAlCobro->execute(array(':cuit' => $cuitcuota, ':nroacuerdo' => $acuerdocuota, ':nrocuota' => $cuotacuota))) {
							        					foreach($resultLeeValorAlCobro as $valoralcobro) {
															$nrocheque = $valoralcobro[chequenrousimra];
															$fechaChe = invertirFecha($valoralcobro[chequefechausimra]);
														}

														$tiposcanc = $cancelacion;
														$observaci = "Cancelada cheque USIMRA Nro. ".$nrocheque." de Fecha ".$fechaChe;
														$boletaimp = $boleta;
														$montopago = $importebanco;
														$fechapago = $recaudabanco;
														$fechacanc = $fechacancelacion;
														$sistecanc = 'E';
														$codibarra = $codbarrabanco;
														$puedecancelar = 1;
													}
													else {
														$puedecancelar = 0;
													}
												}
												else {
													$puedecancelar = 0;
												}
											}

											$cancelacuota = 0;

											if($importecuota==$importebanco) {
												if($cancelacion<='3') {
													if($pagada=='0') {
														if($sistema=='') {
															$cancelacuota = 1;
														}
													}
												}
											}

											if($cancelacuota==1 && $puedecancelar==1) {
												$sqlActualizaCuota="UPDATE cuoacuerdosusimra SET tipocancelacion = :tipocancelacion, observaciones = :observaciones, boletaimpresa = :boletaimpresa, montopagada = :montopagada, fechapagada = :fechapagada, fechacancelacion = :fechacancelacion, sistemacancelacion = :sistemacancelacion, codigobarra = :codigobarra, fechaacreditacion = :fechaacreditacion WHERE cuit = :cuit and nroacuerdo = :nroacuerdo and nrocuota = :nrocuota";
												$resultActualizaCuota = $dbh->prepare($sqlActualizaCuota);
												//echo $sqlActualizaCuota; echo "<br>";
												if($resultActualizaCuota->execute(array(':tipocancelacion' => $tiposcanc, ':observaciones' => $observaci, ':boletaimpresa' => $boletaimp, ':montopagada' => $montopago, ':fechapagada' => $fechapago, ':fechacancelacion' => $fechacanc, ':sistemacancelacion' => $sistecanc, ':codigobarra' => $codibarra, ':fechaacreditacion' => $acreditabanco, ':cuit' => $cuitboleta, ':nroacuerdo' => $acuerdo, ':nrocuota' => $cuota))) {
													if($tiposcanc=='10') {
														$listastatus="Cheque Rechazado";
														$listamensaje="EL RECHAZO DEL CHEQUE HA SIDO IMPUTADO EN LA CUOTA DEL ACUERDO.";
													}
													else {
														$sqlLeeRemitosRemesas="SELECT * FROM remitosremesasusimra WHERE codigocuenta = '$cuentaremesa' and sistemaremesa = 'E' and fecharemesa = '$acreditabanco' and nroremesa = '$nroremesa' and nrocontrol = '$controlboleta' and importebruto = '$montopago'";
														$resultLeeRemitosRemesas = $dbh->query($sqlLeeRemitosRemesas);
														foreach($resultLeeRemitosRemesas as $remitos) {
															$nroremitoremesa = $remitos[nroremito];
															$sqlAddConcilia="INSERT INTO conciliacuotasusimra (cuit, nroacuerdo, nrocuota, cuentaboleta, cuentaremesa, fecharemesa, nroremesa, nroremitoremesa, cuentaremitosuelto, fecharemitosuelto, nroremitosuelto, estadoconciliacion, fechaconciliacion, usuarioconciliacion, fecharegistro, usuarioregistro, fechamodificacion, usuariomodificacion) VALUES ('$cuitboleta','$acuerdo','$cuota','$cuentaboleta','$cuentaremesa','$acreditabanco','$nroremesa','$nroremitoremesa','$cuentaremitosuelto','$fecharemitosuelto','$nroremitosuelto','$estadoconciliacion','$fechaconciliacion','$usuarioconciliacion','$fechacancelacion','$usuariocancelacion','$fechamodificacion','$usuariomodificacion')";
															$resultAddConcilia = $dbh->query($sqlAddConcilia);
															//echo $sqlAddConcilia; echo "<br>";
														}

														$totacanc=$totacanc+$montopago;
														$cantcanc++;
														$listastatus="Cuota Cancelada";
														$listamensaje="IMPUTACION CORRECTA DEL PAGO EN LA CUOTA DEL ACUERDO.";
													}
												}
												else {
													$listastatus="ERROR CAU";
													$listamensaje="COMUNIQUESE CON EL DEPTO. DE SISTEMAS PARA INFORMAR EL ERROR.";
												}

												$sqlActualizaBanco="UPDATE banacuerdosusimra SET fechaimputacion = :fechaimputacion, usuarioimputacion = :usuarioimputacion WHERE nrocontrol = :nrocontrol and estadomovimiento = :estadomovimiento";
												$resultActualizaBanco = $dbh->prepare($sqlActualizaBanco);
												//echo $sqlActualizaBanco; echo "<br>";
												if($resultActualizaBanco->execute(array(':fechaimputacion' => $fechacancelacion, ':usuarioimputacion' => $usuariocancelacion, ':nrocontrol' => $controlboleta, ':estadomovimiento' => $estado))) {
													//print "<p>Registro Banco actualizado correctamente.</p>\n";
												}
												else {
													//print "<p>Error al actualizar el registro Banco.</p>\n";
												}

												$cantidadcuotaspagas = 0;
												$montocuotaspagas = 0.00;
												$fechacancela = date("Y-m-d"); 

												$sqlLeeCuotas="SELECT * FROM cuoacuerdosusimra WHERE cuit = $cuitboleta and nroacuerdo = $acuerdo";
												$resultLeeCuotas = $dbh->query($sqlLeeCuotas);
												foreach($resultLeeCuotas as $leidas) {
													if($leidas[montopagada]!=0 && $leidas[fechapagada]!='00000000') {
														$cantidadcuotaspagas = $cantidadcuotaspagas + 1;
														$montocuotaspagas = $montocuotaspagas + $leidas[montopagada];
													}
												}

												$sqlActualizaCabecera="UPDATE cabacuerdosusimra SET cuotaspagadas = :cuotaspagadas, montopagadas = :montopagadas, fechapagadas = :fechapagadas WHERE cuit = :cuit and nroacuerdo = :nroacuerdo";
												$resultActualizaCabecera = $dbh->prepare($sqlActualizaCabecera);
												if($resultActualizaCabecera->execute(array(':cuotaspagadas' => $cantidadcuotaspagas, ':montopagadas' => $montocuotaspagas, ':fechapagadas' => $fechacancela,':cuit' => $cuitboleta, ':nroacuerdo' => $acuerdo))) {
													//print "<p>Registro Cabecera Acuerdo actualizado correctamente.</p>\n";
												}
												else {
													//print "<p>Error al actualizar el registro Cabecera Acuerdo.</p>\n";
												}

												$sqlLeeCabecera="SELECT * FROM cabacuerdosusimra WHERE cuit = $cuitboleta and nroacuerdo = $acuerdo";
												$resultLeeCabecera = $dbh->query($sqlLeeCabecera);
												foreach($resultLeeCabecera as $cabecera) {
													$estadodeacuerdo=$cabecera[estadoacuerdo];
													if($cabecera[cuotasapagar]==$cabecera[cuotaspagadas]) {
														if($cabecera[montoapagar]==$cabecera[montopagadas])
															$estadodeacuerdo=0;
													}
													$saldodeacuerdo=$cabecera[montoapagar]-$cabecera[montopagadas];
												}

												$sqlActualizaCabecera="UPDATE cabacuerdosusimra SET estadoacuerdo = :estadoacuerdo, saldoacuerdo = :saldoacuerdo WHERE cuit = :cuit and nroacuerdo = :nroacuerdo";
												$resultActualizaCabecera = $dbh->prepare($sqlActualizaCabecera);
												if($resultActualizaCabecera->execute(array(':estadoacuerdo' => $estadodeacuerdo, ':saldoacuerdo' => $saldodeacuerdo, ':cuit' => $cuitboleta, ':nroacuerdo' => $acuerdo))) {
													//print "<p>Registro Cabecera Acuerdo actualizado correctamente.</p>\n";
												}
												else {
													//print "<p>Error al actualizar el registro Cabecera Acuerdo.</p>\n";
												}
											}
											else {
												$listastatus="Cuota No Cancelada";
												$listamensaje="ANOMALIA EN EL ESTADO DE LA IMPUTACION O EN LOS DATOS DE LA CUOTA.";
											}
										}
									}
									else {
										$listastatus="Cuota No Cancelada";
										$listamensaje="EL ACUERDO/CUOTA A QUE REFIERE LA IMPUTACION NO EXISTE.";
									}
								}
								else {
									$listastatus="Pago No Imputado";
									$listamensaje="EL CUIT (".$cuitbanco.") EN QUE IMPUTA EL BANCO ES DISTINTO AL DE LA BOLETA GENERADA.";
								}
							}
							else {
								$listastatus="Pago No Imputado";
								$listamensaje="EL IMPORTE (".$importebanco.") ACREDITADO POR EL BANCO ES DISTINTO AL DE LA BOLETA GENERADA.";
							} ?>
							<td><?php echo $listastatus; ?></td>
							<td><?php echo $listamensaje; ?></td>
							<td><?php echo $fechabanco; ?></td>
						</tr>
<?php
						}
					}
        		} ?>
        		</tbody>
				</table>
<?php
				if($totacanc!=0.00) { ?>
					<div align="center">
						<h3><?php echo $cantcanc." cuotas CANCELADAS por un TOTAL IMPUTADO de $".$totacanc; ?></h3>
					</div>
<?php
				}
    		}
		}
	}
	
	$dbh->commit();
	
	if($haypago==1) { ?>
		<p>&nbsp;</p>
		<table width="769" border="1" align="center">
		<tr align="center" valign="top">
	    <td width="385" valign="middle">
		<div align="left">
		<input type="reset" name="volver" value="Volver" onclick="location.href = 'procesamientoRegistrosAcuerdos.php'"/>
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
	}
	else { ?>
		<p>&nbsp;</p>
		<table width="769" border="1" align="center">
		<tr align="center" valign="top">
	    <td width="769" valign="middle"><input type="reset" name="volver" value="Volver" onclick="location.href = 'procesamientoRegistrosAcuerdos.php'"/>
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
} ?>
</body>
</html>