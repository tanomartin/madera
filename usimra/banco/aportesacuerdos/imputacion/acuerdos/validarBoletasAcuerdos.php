<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php");
$fechavalidacion = date("Y-m-d H:i:s");
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
		<h1>Resultados de la Validaci&oacute;n de Boletas por Pagos de Acuerdos</h1>
	</div>
<?php
	$sqlControlValidar="SELECT COUNT(*) FROM banacuerdosusimra WHERE fechavalidacion = '00000000000000' and estadomovimiento in ('P','E')";
	$sqlLeeAValidar="SELECT * FROM banacuerdosusimra WHERE fechavalidacion = '00000000000000' and estadomovimiento in ('P','E')";
	$resultControlValidar = $dbh->query($sqlControlValidar);
	if(!$resultControlValidar) { ?>
		<div align="center">
			<h3>Error en la consulta de la tabla BANACUERDOSUSIMRA. Comuniquese con el Depto. de Sistemas.</h3>
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
    		$resultLeeAValidar = $dbh->query($sqlLeeAValidar);
    		if(!$resultLeeAValidar) { ?>
				<div align="center">
					<h3>Error en la consulta de Informaci&oacute;n del Banco. Comuniquese con el Depto. de Sistemas.</h3>
				</div>
<?php
			}
			else {
				$cantvali=0;
				$cantnova=0; ?>

				<table id="resultados" class="tablesorter" style="font-size:14px; text-align:center">
					<thead>
						<tr>
							<th>Id. Boleta</th>
							<th>C.U.I.T.</th>
							<th>Acuerdo</th>
							<th>Cuota</th>
							<th>Importe</th>
							<th>Status</th>
							<th>Mensaje</th>
						</tr>
					</thead>
					<tbody>
<?php
        		foreach ($resultLeeAValidar as $validar) {
					$control = $validar[nrocontrol];
					$estado = $validar[estadomovimiento];
					$importebanco = $validar[importe];
					$cuitbanco = $validar[cuit];
					$fechabanco = $validar[fechaacreditacion];

					$sqlControlaBoleta="SELECT * FROM anuladasusimra WHERE nrocontrol = :nrocontrol";
					$resultControlaBoleta = $dbh->prepare($sqlControlaBoleta);
					$resultControlaBoleta->execute(array(':nrocontrol' => $control));
					if($resultControlaBoleta) {
						foreach($resultControlaBoleta as $anuladas) {
							$controlanulada = $anuladas[nrocontrol];
							$cantnova++; ?>
						<tr>
							<td><?php echo $controlanulada; ?></td>
						    <td><?php echo "-"; ?></td>
						    <td><?php echo "-"; ?></td>
						    <td><?php echo "-"; ?></td>
				    		<td><?php echo "-"; ?></td>
							<td><?php echo "No Validada"; ?></td>
							<td><?php echo "LA BOLETA SE ENCUENTRA ANULADA"; ?></td>
						</tr>
<?php
						}
					}

					$sqlBuscaBoleta="SELECT * FROM boletasusimra WHERE nrocontrol = :nrocontrol";
					//echo $sqlBuscaBoleta; echo "<br>";
					$resultBuscaBoleta = $dbh->prepare($sqlBuscaBoleta);
					$resultBuscaBoleta->execute(array(':nrocontrol' => $control));
					if($resultBuscaBoleta) {
		        		foreach($resultBuscaBoleta as $boletas) {
							$id = $boletas[idboleta];
							$cuitboleta = $boletas[cuit];
							$acuerdo = $boletas[nroacuerdo];
							$cuota = $boletas[nrocuota];
							$importeboleta = $boletas[importe];
							$control = $boletas[nrocontrol];
							$usuario = $boletas[usuarioregistro]; ?>
						<tr>
							<td><?php echo $control; ?></td>
						    <td><?php echo $cuitboleta; ?></td>
						    <td><?php echo $acuerdo; ?></td>
						    <td><?php echo $cuota; ?></td>
						    <td><?php echo $importeboleta; ?></td>
<?php
							if($importebanco==$importeboleta) {
								if($cuitbanco==$cuitboleta) {
									$sqlAgregaValida="INSERT INTO validasusimra (idboleta, cuit, nroacuerdo, nrocuota, importe, nrocontrol, usuarioregistro) VALUES (:idboleta,:cuit,:nroacuerdo,:nrocuota,:importe,:nrocontrol,:usuarioregistro)";
									$resultAgregaValida = $dbh->prepare($sqlAgregaValida);
									//echo $sqlAgregaValida; echo "<br>";
									if($resultAgregaValida->execute(array(':idboleta' => $id, ':cuit' => $cuitboleta, ':nroacuerdo' => $acuerdo, ':nrocuota' => $cuota, ':importe' => $importeboleta, ':nrocontrol' => $control, ':usuarioregistro' => $usuario))) {
										$cantvali++; 
										$listastatus="Boleta Validada";
										$listamensaje="TODOS LOS DATOS DE LA IMPUTACION DEL BANCO SON CORRECTOS.";
									}
									else {
										$listastatus="Error VAI";
										$listamensaje="COMUNIQUESE CON EL DEPTO. DE SISTEMAS.";
									}

									$sqlBorraBoleta="DELETE FROM boletasusimra WHERE nrocontrol = :nrocontrol";
									$resultBorraBoleta = $dbh->prepare($sqlBorraBoleta);
									//echo $sqlBorraBoleta; echo "<br>";
									if($resultBorraBoleta->execute(array(':nrocontrol' => $control))) {
									    //print "<p>Registro Boleta borrado correctamente.</p>\n";
									}
									else {
									    //print "<p>Error al borrar el registro Boleta.</p>\n";
									}

									$sqlActualizaBanco="UPDATE banacuerdosusimra SET fechavalidacion = :fechavalidacion, usuariovalidacion = :usuariovalidacion WHERE nrocontrol = :nrocontrol and estadomovimiento = :estadomovimiento";
									$resultActualizaBanco = $dbh->prepare($sqlActualizaBanco);
									//echo $sqlActualizaBanco; echo "<br>";
									if($resultActualizaBanco->execute(array(':fechavalidacion' => $fechavalidacion, ':usuariovalidacion' => $usuariovalidacion, ':nrocontrol' => $control, ':estadomovimiento' => $estado))) {
									    //print "<p>Registro Banco actualizado correctamente.</p>\n";
									}
									else {
									    //print "<p>Error al actualizar el registro Banco.</p>\n";
									}
								}
								else {
									$cantnova++; 
									$listastatus="Boleta No Validada";
									$listamensaje="EL CUIT (".$cuitbanco.") EN QUE IMPUTA EL BANCO ES DISTINTO AL DE LA BOLETA GENERADA.";
								}
							}
							else {
								$cantnova++; 
								$listastatus="Boleta No Validada";
								$listamensaje="EL IMPORTE (".$importebanco.") ACREDITADO POR EL BANCO ES DISTINTO AL DE LA BOLETA GENERADA.";
							} ?>
							<td><?php echo $listastatus; ?></td>
							<td><?php echo $listamensaje; ?></td>
						</tr>
<?php
						}
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
		<input type="reset" name="volver" value="Volver" onclick="location.href = 'procesamientoRegistrosAcuerdos.php'" align="left"/>
		</div>
		</td>
	    <td width="384" valign="middle">
		<div align="right">
        <input type="button" name="imprimir" value="Imprimir" onclick="window.print();" align="left"/>
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
	echo $e->getMessage();
	$dbh->rollback();
}
?>
</body>
</html>