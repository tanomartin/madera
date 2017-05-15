<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php");
$fechacancelacion=date("Y-m-d H:i:s");
$fechasubida=date("Y-m-d");
$usuariocancelacion=$_SESSION['usuario'];
$sistemacancelacion='L';
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
			headers:{0:{sorter:false}, 1:{sorter:false}, 2:{sorter:false}, 3:{sorter:false}, 4:{sorter:false}, 5:{sorter:false}, 6:{sorter:false}}
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
		<h1>Resultados de la Imputaci&oacute;n de Pagos por Depositos via Link Pagos</h1>
	</div>
<?php
	$sqlControlImputar="SELECT COUNT(*) FROM linkaportesusimra WHERE fechavalidacion != '0000-00-00 00:00:00' AND fechaimputacion = '0000-00-00 00:00:00'";
	$sqlLeeAImputar="SELECT * FROM linkaportesusimra WHERE  fechavalidacion != '0000-00-00 00:00:00' AND fechaimputacion = '0000-00-00 00:00:00' ORDER BY fechaarchivo, fechadeposito, idmovimiento";
	$resultControlImputar = $dbh->query($sqlControlImputar);
	if(!$resultControlImputar) { ?>
		<div align="center">
			<h3>Error en la consulta de la tabla LINKAPORTESUSIMRA. Comuniquese con el Depto. de Sistemas.</h3>
		</div>
<?php
	} else {
		//Verifica si hay registros a validar
		if($resultControlImputar->fetchColumn()==0) {
			$haypago=0; ?>
			<div align="center">
				<h2>No hay pagos que deban ser imputados.</h2>
			</div>
<?php
		} else {
			$haypago=1;
    		$resultLeeAImputar=$dbh->query($sqlLeeAImputar);
    		if(!$resultLeeAImputar) { ?>
				<div align="center">
					<h3>Error en la consulta de Informaci&oacute;n de Link Pagos. Comuniquese con el Depto. de Sistemas.</h3>
				</div>
<?php
			} else {
				set_time_limit(0);
				$cuil="99999999999";
				$totacanc=0.00;
				$cantcanc=0; ?>

				<table id="resultados" class="tablesorter" style="font-size:14px; text-align:center">
					<thead>
						<tr>
							<th>Id. Ticket</th>
							<th>C.U.I.T.</th>
							<th>Periodos</th>
							<th>Importe</th>
							<th>Status</th>
							<th>Mensaje</th>
							<th>Fecha Acreditacion</th>
						</tr>
					</thead>
					<tbody>
<?php
        		foreach($resultLeeAImputar as $imputar) {
					$fechabanco = $imputar[fechaarchivo]; 
					$movimientobanco = $imputar[idmovimiento]; 
					$cuitbanco = $imputar[cuit];
					$referenciabanco = $imputar[referencia];
					$importebanco = $imputar[importe];
					$depositobanco = $imputar[fechadeposito];
					$actualizabanco=0;
					$anterior=0;
 ?>
						<tr>
							<td><?php echo $referenciabanco; ?></td>
							<td><?php echo $cuitbanco; ?></td>
<?php
					$sqlControlBuscaEmpresa="SELECT COUNT(*) FROM empresas WHERE cuit = '$cuitbanco'";
					$resultControlBuscaEmpresa=$dbh->query($sqlControlBuscaEmpresa);
					if($resultControlBuscaEmpresa->fetchColumn()!=0) {
						$sqlBuscaCabDDJJ="SELECT d.* FROM ddjjusimra d, vinculadocuusimra v WHERE d.nrcuil = '$cuil' AND d.nrcuit = '$cuitbanco' AND d.nrcuit = v.nrcuit AND d.nrctrl = v.nrctrl AND v.referencia = '$referenciabanco'";
						if($resultBuscaCabDDJJ=$dbh->query($sqlBuscaCabDDJJ)) {
							foreach($resultBuscaCabDDJJ as $cabddjj) {
								$sqlAgregaCabDDJJ="INSERT INTO cabddjjusimra VALUES ('$cabddjj[id]','$cabddjj[nrcuit]','$cabddjj[nrcuil]','$cabddjj[permes]','$cabddjj[perano]','$cabddjj[remune]','$cabddjj[apo060]','$cabddjj[apo100]','$cabddjj[apo150]','$cabddjj[totapo]','$cabddjj[recarg]','$cabddjj[nfilas]','$cabddjj[instrumento]','$cabddjj[nrctrl]','$cabddjj[observ]','$fechasubida')";
								echo $sqlAgregaCabDDJJ; echo "<br>";
								if($resultAgregaCabDDJJ = $dbh->query($sqlAgregaCabDDJJ)) {
									$sqlBuscaDetDDJJ="SELECT * FROM ddjjusimra WHERE nrcuil != '$cuil' AND nrcuit = '$cuitbanco' AND nrctrl = '$cabddjj[nrctrl]'";
									if($resultBuscaDetDDJJ = $dbh->query($sqlBuscaDetDDJJ)) {
										foreach($resultBuscaDetDDJJ as $detddjj) {
											$sqlAgregaDetDDJJ="INSERT INTO detddjjusimra VALUES ('$detddjj[id]','$detddjj[nrcuit]','$detddjj[nrcuil]','$detddjj[permes]','$detddjj[perano]','$detddjj[remune]','$detddjj[apo060]','$detddjj[apo100]','$detddjj[apo150]', '$detddjj[nrctrl]','$fechasubida')";
											echo $sqlAgregaDetDDJJ; echo "<br>";
											if($resultAgregaDetDDJJ = $dbh->query($sqlAgregaDetDDJJ)) {
											}
										}
									}

									$ultimopago=0;
									$sqlBuscaUltimoPago="SELECT nropago FROM seguvidausimra WHERE cuit = '$cuitbanco' AND anopago = '$cabddjj[perano]' AND mespago = '$cabddjj[permes]' ORDER BY nropago DESC LIMIT 1";
									if($resultBuscaUltimoPago=$dbh->query($sqlBuscaUltimoPago)) {
										foreach($resultBuscaUltimoPago as $pagofinal) {
											$ultimopago=$pagofinal[nropago];
										}
									}
									$ultimopago=$ultimopago+1;

									$montopagado=$cabddjj[totapo]+$cabddjj[recarg];

									$sqlAgregaPago="INSERT INTO seguvidausimra VALUES ('$cuitbanco','$cabddjj[permes]','$cabddjj[perano]','$ultimopago','$anterior','$depositobanco','$cabddjj[nfilas]','$cabddjj[remune]','$cabddjj[recarg]','$montopagado','$cabddjj[observ]','$sistemacancelacion','$referenciabanco','$fechabanco','$fechacancelacion','$usuariocancelacion','$fechamodificacion','$usuariomodificacion')";
									echo $sqlAgregaPago; echo "<br>";
									if($resultAgregaPago = $dbh->query($sqlAgregaPago)) {
										$sqlAgregaApo060="INSERT INTO apor060usimra VALUES ('$cuitbanco','$cabddjj[permes]','$cabddjj[perano]','$ultimopago','$cabddjj[apo060]')";
										if($resultAgregaApo060 = $dbh->query($sqlAgregaApo060)) {
										}
										$sqlAgregaApo100="INSERT INTO apor100usimra VALUES ('$cuitbanco','$cabddjj[permes]','$cabddjj[perano]','$ultimopago','$cabddjj[apo100]')";
										if($resultAgregaApo100 = $dbh->query($sqlAgregaApo100)) {
										}
										$sqlAgregaApo150="INSERT INTO apor150usimra VALUES ('$cuitbanco','$cabddjj[permes]','$cabddjj[perano]','$ultimopago','$cabddjj[apo150]')";
										if($resultAgregaApo150 = $dbh->query($sqlAgregaApo150)) {
										}
									}
								}

								$sqlBorraDDJJ="DELETE FROM ddjjusimra WHERE nrcuit = '$cuitbanco' AND nrctrl = '$cabddjj[nrctrl]'";
								if($resultBorraDDJJ = $dbh->query($sqlBorraDDJJ)) {

								$periodo=$cabddjj[permes]."-".$cabddjj[perano];
								$totacanc=$totacanc+$montopagado;
								$cantcanc++;
								$actualizabanco=1;
								$listaperi.=$periodo;
								$listaimporte=$importebanco;
								$listastatus="Pago Imputado";
								$listamensaje="IMPUTACION CORRECTA DEL PAGO.";
								}
							}
						}

						if($actualizabanco) {
							$sqlActualizaLink="UPDATE linkaportesusimra SET fechaimputacion = '$fechacancelacion', usuarioimputacion = '$usuariocancelacion' WHERE fechaarchivo = '$fechabanco' AND idmovimiento = $movimientobanco";
							if($resultActualizaBanco = $dbh->query($sqlActualizaBanco)) {
							}
						}
					} else {
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
		<input type="reset" name="volver" value="Volver" onclick="location.href = 'procesamientoRegistrosLinkpagos.php'"/>
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
	    <td width="770" valign="middle"><input type="reset" name="volver" value="Volver" onclick="location.href = 'procesamientoRegistrosLinkpagos.php'"/>
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