<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php");
$cuit = $_GET['cuit'];
$nroacu = $_GET['nroacu'];

$sqlCabecera = "SELECT c.*, e.descripcion as desestado, t.descripcion as destipo, g.apeynombre as gestor, i.apeynombre as inspector
				FROM cabacuerdosusimra c, estadosdeacuerdos e, tiposdeacuerdos t, gestoresdeacuerdos g, inspectores i
				WHERE c.cuit = $cuit and c.nroacuerdo = $nroacu and c.estadoacuerdo = e.codigo and c.tipoacuerdo = t.codigo and c.gestoracuerdo = g.codigo and c.inspectorinterviene = i.codigo LIMIT 1";
$resCabecera = mysql_query($sqlCabecera,$db);
$canCabecera = mysql_num_rows($resCabecera);

$sqlPeriodos = "SELECT * FROM detacuerdosusimra d, conceptosdeudas c 
				WHERE d.cuit = $cuit and d.nroacuerdo = $nroacu and d.conceptodeuda = c.codigo";
$resPeriodos = mysql_query($sqlPeriodos,$db);
$canPeriodos = mysql_num_rows($resPeriodos);

$sqlCuotas = "SELECT * FROM cuoacuerdosusimra c, tiposcancelaciones t 
			  WHERE c.cuit = $cuit and c.nroacuerdo = $nroacu and c.tipocancelacion = t.codigo";
$resCuotas = mysql_query($sqlCuotas,$db);
$canCuotas = mysql_num_rows($resCuotas);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>.: Consulta Acuerdo :.</title>
</head>
<body bgcolor="#B2A274">
	<div align="center">
<?php   if (!isset( $_GET['origen'])) { ?>
			<p><input type="button" name="volver" value="Volver" onclick="location.href = 'acuerdos.php?cuit=<?php echo $cuit?>'" /></p>
<?php 	} 
		include($libPath."cabeceraEmpresaConsulta.php");
		include($libPath."cabeceraEmpresa.php"); ?>
		<h3>U.S.I.M.R.A. - Acuerdo Nº "<?php echo $nroacu ?>"</h3>	
<?php   if ($canCabecera == 1) {
			$rowCebecera = mysql_fetch_array($resCabecera);
		} else { ?>
			<p style="color: red"> Error en la lectura de la cabecera de acuerdo cargada </p>
<?php 	} ?>
		<h3>ESTADO "<?php echo $rowCebecera['desestado']; ?>"</h3>		
		<p><b>Cabecera</b></p>
		<table width="954" border="1">
			<tr>
				<td><div align="left"><b>Tipo</b></div></td>
				<td><div align="left"><?php echo $rowCebecera['destipo']; ?></div></td>
				<td><div align="left"><b>Fecha</b></div></td>
				<td><div align="left"><?php echo invertirFecha($rowCebecera['fechaacuerdo']) ?></div></td>
				<td><div align="left"><b>Nº Acta</b></div></td>
				<td><div align="left"><?php echo $rowCebecera['nroacta'] ?></div></td>
			</tr>
			<tr>
				<td><div align="left"><b>Gestor</b></div></td>
				<td><div align="left"><?php echo $rowCebecera['gestor']; ?></div></td>
				<td><div align="left"><b>Inspector</b></div></td>
				<td><div align="left"><?php echo $rowCebecera['inspector'];?></div></td>
				<td><div align="left"><b>Req. Origen</b></div></td>
				<td><div align="left">
				<?php 	if ($rowCebecera['requerimientoorigen'] == 0) { 
							echo "-";
					 	} else { 
					  		echo $rowCebecera['requerimientoorigen'];
						}  ?>
				</div></td>
			</tr>
			<tr>
				<td><div align="left"><b>Liq. Origen</b></div></td>
				<td><div align="left">
				<?php 
					if ($rowCebecera['requerimientoorigen'] == 0) {
						echo "-";
					} else {
						echo $rowCebecera['liquidacionorigen'];
					}
				?>
				</div></td>
				<td><div align="left"><b>Monto</b></div></td>
				<td><div align="left"><?php echo $rowCebecera['montoacuerdo'] ?></div></td>
				<td><div align="left"><b>Gastos Admin.</b></div></td>
				<td><div align="left"><?php echo $rowCebecera['porcengastoadmin']."%" ?></div></td>
			</tr>
			<tr>
				<td><div align="left"><b>Observaciones</b></div></td>
				<td colspan="5"><div align="left"><?php echo $rowCebecera['observaciones'] ?></div></td>
			</tr>
		</table>
		
		<p><b>Períodos</b></p>
  <?php if ($canPeriodos != 0 ) { ?>
			<table width="300" border="1" style="text-align: center">
				<tr>
					<th>Mes</th>
					<th>Año</th>
					<th>Concepto de deuda </th>
				</tr>
		<?php 	while ($rowPeriodos = mysql_fetch_array($resPeriodos)) { ?>
					<tr>
						<td><?php echo $rowPeriodos['mesacuerdo'] ?></td>
						<td><?php echo $rowPeriodos['anoacuerdo'] ?></td>
						<td><?php echo $rowPeriodos['descripcion'] ?></td>
					</tr>
		<?php 	} ?>
			</table>
<?php 	} else { ?>
			<p style="color: blue">No hay periódos cargados relacionados con este acuerdo</p>
<?php	} ?>
		
		<p><b>Cuotas</b></p>
		
<?php   if ($canCuotas != 0) { ?>
			<table width="1000" border="1" style="text-align: center">
				<tr>
					<th>Nº </th>
					<th>Monto </th>
					<th>Fecha </th>
					<th>Cancelacion</th>
					<th>Nro Cheque</th>
					<th>Banco </th>
					<th>Fecha Cheque</th>
					<th>Observaciones</th>
					<th>Estado</th>
					<th>Fecha Pago</th>
				</tr>
<?php 		while ($rowCuotas = mysql_fetch_array($resCuotas)) { ?>
				<tr>
					<td><?php echo $rowCuotas['nrocuota'] ?></td>
					<td><?php echo $rowCuotas['montocuota'] ?></td>
					<td><?php echo invertirFecha($rowCuotas['fechacuota']) ?></td>
					<td><?php echo $rowCuotas['descripcion'] ?></td>
		<?php	if ($rowCuotas['chequenro'] != 0) { ?>
					<td><?php echo $rowCuotas['chequenro'] ?></td>
					<td><?php echo $rowCuotas['chequebanco'] ?></td>
					<td><?php echo invertirFecha($rowCuotas['chequefecha']) ?></td>
		<?php	} else { ?>
					<td><?php echo "-" ?></td>
					<td><?php echo "-" ?></td>
					<td><?php echo "-" ?></td>
		<?php	}
				if ($rowCuotas['observaciones'] == "") { ?>
					<td><?php echo "-" ?></td>
		<?php	} else { ?>
					<td><?php echo $rowCuotas['observaciones'] ?></td>
		<?php	}
				if ($rowCuotas['montopagada'] != 0 || $rowCuotas['fechapagada'] != '0000-00-00') { ?>
					<td><?php echo "CANCELADA (".$rowCuotas['sistemacancelacion'].")" ?></td>
					<td><?php echo invertirFecha($rowCuotas['fechapagada']) ?></td>
		<?php	} else {
					if ($rowCuotas['boletaimpresa'] != 0) { ?>
						<td><?php echo "BOLETA IMPRESA" ?></td>
						<td><?php echo "-" ?></td>
		<?php		} else { ?>
						<td><?php echo "A PAGAR" ?></td>
						<td><?php echo "-" ?></td>
		<?php		}
				} 
			} ?>
			</tr>
			<tr>
				<td><b>Total <br/> Cuotas</b></td>
				<td><b><?php echo $rowCebecera['montoapagar'] ?></b></td>
			</tr>
			<tr>
				<td><b>Total <br/> Pagado</b></td>
				<td><b><?php echo $rowCebecera['montopagadas'] ?></b></td>
			</tr>
			<tr>
				<td><b>Saldo</b></td>
				<?php $saldoRestante = $rowCebecera['montoapagar'] - $rowCebecera['montopagadas']; ?>
				<td><b><?php echo number_format($saldoRestante,2,'.','') ?></b></td>
			</tr>
		</table>
<?php	} else { ?>
			<p style="color: blue">No hay cuotas cargadas en este acuerdo.</p>
<?php	} ?>
		<p><input type="button" name="imprimir" value="Imprimir" onClick="window.print();" /></p>
	</div>
</body>
</html>
