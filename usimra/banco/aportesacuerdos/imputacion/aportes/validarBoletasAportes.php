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
<title>.: M�dulo Banco USIMRA :.</title>
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
			headers:{0:{sorter:false}, 1:{sorter:false}, 2:{sorter:false}, 3:{sorter:false}, 4:{sorter:false}, 5:{sorter:true}, 6:{sorter:true}, 7:{sorter:true}}
		});
});
function irARegistrar() {
	$.blockUI({ message: "<h1>Procesando el Registro de Pagos.<br>Aguarde por favor...</h1>" });
	document.location.href = "registrarPagosAportes.php";
}
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
		<h1>Resultados de la Validaci&oacute;n de Boletas por Pagos de Aportes</h1>
	</div>
<?php
	$sqlControlValidar="SELECT COUNT(*) FROM banaportesusimra WHERE fechavalidacion = '00000000000000' and estadomovimiento in('P','E')";
	$sqlLeeAValidar="SELECT * FROM banaportesusimra WHERE fechavalidacion = '00000000000000' and estadomovimiento in('P','E') ORDER BY fecharecaudacion ASC, nromovimiento ASC";
	$resultControlValidar=$dbh->query($sqlControlValidar);
	if(!$resultControlValidar) { ?>
		<div align="center">
			<h3>Error en la consulta de la tabla BANAPORTESUSIMRA. Comuniquese con el Depto. de Sistemas.</h3>
		</div>
<?php
	}
	else {
		//Verifica si hay registros a validar
		if($resultControlValidar->fetchColumn()==0) {
			$hayboleta=0; ?>
			<div align="center">
				<h2>No hay boletas que deban ser validadas.</h2>
			</div>
<?php
		}
		else {
			$hayboleta=1;
    		$resultLeeAValidar=$dbh->query($sqlLeeAValidar);
    		if(!$resultLeeAValidar) { ?>
				<div align="center">
					<h3>Error en la consulta de Informaci&oacute;n del Banco. Comuniquese con el Depto. de Sistemas.</h3>
				</div>
<?php
			}
			else {
				set_time_limit(0);
				$cuil="99999999999";
				$cantvali=0;
				$cantnova=0; ?>

				<table id="resultados" class="tablesorter" style="font-size:14px; text-align:center">
					<thead>
						<tr>
							<th>Id. Boleta</th>
							<th>C.U.I.T.</th>
							<th>Cuota</th>
							<th>A�o</th>
							<th>Importe</th>
							<th>Status</th>
							<th>Mensaje</th>
							<th>Fecha Presentacion</th>
						</tr>
					</thead>
					<tbody>
<?php
        		foreach($resultLeeAValidar as $validar) {
					$cuitbanco = $validar[cuit];
					$controlbanco = $validar[nrocontrol];
					$importebanco = $validar[importe];
					$estadobanco = $validar[estadomovimiento];
					$fechabanco = invertirFecha($validar[fecharecaudacion]);
					$sqlControlBuscaCabBoleta="SELECT COUNT(*) FROM ddjjusimra WHERE nrcuit = '$cuitbanco' AND nrcuil = '$cuil' AND nrctrl = '$controlbanco'";
					$resultControlBuscaCabBoleta=$dbh->query($sqlControlBuscaCabBoleta);
					if($resultControlBuscaCabBoleta->fetchColumn()!=0) {
						$sqlBuscaCabBoleta="SELECT * FROM ddjjusimra WHERE nrcuit = :nrcuit AND nrcuil = :nrcuil AND nrctrl = :nrctrl";
						//echo $sqlBuscaCabBoleta; echo "<br>";
						$resultBuscaCabBoleta=$dbh->prepare($sqlBuscaCabBoleta);
						$resultBuscaCabBoleta->execute(array(':nrcuit' => $cuitbanco, ':nrcuil' => $cuil, ':nrctrl' => $controlbanco));
						if($resultBuscaCabBoleta) {
							foreach($resultBuscaCabBoleta as $cabboleta) {
								$totalboleta = round(($cabboleta[totapo]+$cabboleta[recarg]),2);
								$importeadmitido=0; 
								$difdeposito=0.00; ?>
							<tr>
								<td><?php echo $cabboleta[nrctrl]; ?></td>
								<td><?php echo $cabboleta[nrcuit]; ?></td>
								<td><?php echo $cabboleta[permes]; ?></td>
								<td><?php echo $cabboleta[perano]; ?></td>
								<td><?php echo $totalboleta; ?></td>
<?php
								if($importebanco==$totalboleta) {
									$importeadmitido=1;
								} else {
									$difdeposito=round(($importebanco-$totalboleta),2);
									if($difdeposito >= -50.00 && $difdeposito <= 50.00) {
										$importeadmitido=1;
									}
								}

								if($importeadmitido) {
									$sqlActualizaBanco="UPDATE banaportesusimra SET fechavalidacion = :fechavalidacion, usuariovalidacion = :usuariovalidacion WHERE cuit = :cuit AND nrocontrol = :nrocontrol and estadomovimiento = :estadomovimiento";
									$resultActualizaBanco = $dbh->prepare($sqlActualizaBanco);
									//echo $sqlActualizaBanco; echo "<br>";
									if($resultActualizaBanco->execute(array(':fechavalidacion' => $fechavalidacion, ':usuariovalidacion' => $usuariovalidacion, ':cuit' => $cuitbanco, ':nrocontrol' => $controlbanco, ':estadomovimiento' => $estadobanco))) {
										//print "<p>Registro de Banco actualizado correctamente.</p>\n";
										$cantvali++;
										$listastatus="Boleta Validada";
										if($difdeposito==0.00) {
											$listamensaje="TODOS LOS DATOS DE LA IMPUTACION DEL BANCO SON CORRECTOS.";
										} else {
											$listamensaje="DIFERENCIA (".$difdeposito.") EN EL IMPORTE (".$importebanco.") ACREDITADO POR EL BANCO.";
										}
									}
									else {
										//print "<p>Error al actualizar el registro de Banco.</p>\n";
									}
								}
								else {
									$cantnova++;
									$listastatus="Boleta No Validada";
									$listamensaje="EL IMPORTE (".$importebanco.") ACREDITADO POR EL BANCO ES DISTINTO AL DE LA BOLETA GENERADA.";
								} ?>
								<td><?php echo $listastatus; ?></td>
								<td><?php echo $listamensaje; ?></td>
								<td><?php echo $fechabanco; ?></td>
							</tr>
	<?php
							}
						}
					}
					else { ?>
						<tr>
							<td><?php echo $controlbanco; ?></td>
							<td><?php echo $cuitbanco; ?></td>
							<td><?php echo "-"; ?></td>
							<td><?php echo "-"; ?></td>
<?php
						$sqlControlBuscaValidada="SELECT COUNT(*) FROM cabddjjusimra WHERE cuit = '$cuitbanco' AND cuil = '$cuil' AND nrocontrol = '$controlbanco'";
						$resultControlBuscaValidada=$dbh->query($sqlControlBuscaValidada);
						if($resultControlBuscaValidada->fetchColumn()!=0) {
							$sqlBuscaValidada="SELECT * FROM cabddjjusimra WHERE cuit = :cuit AND cuil = :cuil AND nrocontrol = :nrocontrol";
							//echo $sqlBuscaValidada; echo "<br>";
							$resultBuscaValidada=$dbh->prepare($sqlBuscaValidada);
							$resultBuscaValidada->execute(array(':cuit' => $cuitbanco, ':cuil' => $cuil, ':nrocontrol' => $controlbanco));
							if($resultBuscaValidada) {
								foreach($resultBuscaValidada as $validada) {
									$sqlBancoValidada="SELECT * FROM banaportesusimra WHERE cuit = :cuit AND nrocontrol = :nrocontrol AND fechavalidacion != '00000000000000' and estadomovimiento in('P','E')";
									//echo $sqlBancoValidada; echo "<br>";
									$resultBancoValidada=$dbh->prepare($sqlBancoValidada);
									$resultBancoValidada->execute(array(':cuit' => $cuitbanco, ':nrocontrol' => $controlbanco));
									if($resultBancoValidada) {
										foreach($resultBancoValidada as $bancovalidada) {
											$cantnova++;
											$listaimporte=$bancovalidada[importe];
											$listastatus="Boleta No Validada";
											$listamensaje="VALIDACION ANTERIOR PARA BOLETA PRESENTADA EL ".invertirFecha($bancovalidada[fecharecaudacion]).".";
										}
									}
								}
							}
						}
						else {
							$cantnova++;
							$listaimporte=$importebanco;
							$listastatus="Boleta No Validada";
							$listamensaje="DDJJ RELACIONADA A LA BOLETA INEXISTENTE.";
						} ?>
							<td><?php echo $listaimporte; ?></td>
							<td><?php echo $listastatus; ?></td>
							<td><?php echo $listamensaje; ?></td>
							<td><?php echo $fechabanco; ?></td>
						</tr>
<?php
						}
        		} ?>
					</tbody>
				</table>
<?php
				$totabole=$cantvali+$cantnova;
				if($totabole!=0) { ?>
					<div align="center">
						<h3><?php echo $cantvali." boletas VALIDADAS y ".$cantnova." boletas NO VALIDADAS, sobre un TOTAL de ".$totabole." boletas."; ?></h3>
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
		<input type="reset" name="volver" value="Volver" onclick="location.href = 'procesamientoRegistrosAportes.php'"/>
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
		if($cantvali!=0) { ?>
			<p>&nbsp;</p>
			<div align="center">
				<h2>No OLVIDE <input type="submit" name="registrar" value="Registrar Pagos" onclick="javascript:irARegistrar()" align="left" /> de las boletas que acaban de ser validadas.</h2>
			</div>
<?php
		}
	}
	else { ?>
		<p>&nbsp;</p>
		<table width="769" border="1" align="center">
		<tr align="center" valign="top">
	    <td width="769" valign="middle"><input type="reset" name="volver" value="Volver" onclick="location.href = 'procesamientoRegistrosAportes.php'"/>
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