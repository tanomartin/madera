<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php");
$cuit = $_GET['cuit'];
$nroacu = $_GET['nroacu'];
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>.: Consulta Acuerdo :.</title>
</head>
<body bgcolor="#B2A274">
	<form name="verificador">
		<div align="center">
	  <?php if (!isset($_GET['origen'])) { ?>
				<input type="button" name="volver" value="Volver" onclick="location.href = 'acuerdos.php?cuit=<?php echo $cuit?>'" />
	  <?php }
			include($libPath."cabeceraEmpresaConsulta.php");
			include($libPath."cabeceraEmpresa.php"); ?>
			<p><b>U.S.I.M.R.A. - Acuerdo Nº "<?php echo $nroacu ?>"</b></p> 
		<?php  $sqlCabecera = "SELECT c.*, e.descripcion as desestado, t.descripcion as destipo, g.apeynombre as gestor, i.apeynombre as inspector
								FROM cabacuerdosusimra c, estadosdeacuerdos e, tiposdeacuerdos t, gestoresdeacuerdos g, inspectores i
								WHERE c.cuit = $cuit and c.nroacuerdo = $nroacu and c.estadoacuerdo = e.codigo and c.tipoacuerdo = t.codigo and c.gestoracuerdo = g.codigo and c.inspectorinterviene = i.codigo LIMIT 1";
				$resCabecera = mysql_query($sqlCabecera,$db);
				$canCabecera = mysql_num_rows($resCabecera);
				if ($canCabecera == 1) {
					$rowCebecera = mysql_fetch_array($resCabecera);
				} else { ?>
					<p style="color: red"> Error en la lectura de la cabecera de acuerdo cargada </p>
		<?php	}   ?>	
			<p><b>ESTADO </b><?php echo $rowCebecera['desestado']; ?></p>	
			<h3>Cabecera</h3>
			<table width="954" border="1">
				<tr>
					<td><div align="left"><b>Tipo de Acuerdo</b></div></td>
					<td><div align="left"><?php echo $rowCebecera['destipo']; ?></div></td>
					<td><div align="left"><b>Fecha Acuerdo</b></div></td>
					<td><div align="left"><?php echo invertirFecha($rowCebecera['fechaacuerdo']) ?></div></td>
					<td><div align="left"><b>Número de Acta</b></div></td>
					<td><div align="left"><?php echo $rowCebecera['nroacta'] ?></div></td>
				</tr>
				<tr>
					<td><div align="left"><b>Gestor</b></div></td>
					<td><div align="left"><?php echo $rowCebecera['gestor']; ?></div></td>
					<td><div align="left"><b>Inspector</b></div></td>
					<td><div align="left"><?php echo $rowCebecera['inspector'];?></div></td>
					<td><div align="left"><b>Requerimiento de Origen</b></div></td>
					<td>
						<div align="left">
				  <?php if ($rowCebecera['requerimientoorigen'] == 0) { 
							echo "-";
						} else { 
							echo $rowCebecera['requerimientoorigen'];
						}  ?>
						</div>
					</td>
				</tr>
				<tr>
					<td><div align="left"><b>Liquidacion Origen</b></div></td>
					<td>
						<div align="left">
					<?php if ($rowCebecera['requerimientoorigen'] == 0) {
							echo "-";
						  } else {
							echo $rowCebecera['liquidacionorigen'];
						  } ?>
						</div>
					</td>
					<td><div align="left"><b>Monto Acuerdo</b></div></td>
					<td><div align="left"><?php echo $rowCebecera['montoacuerdo'] ?></div></td>
					<td><div align="left"><b>Gastos Administrativos</b></div></td>
					<td><div align="left"><?php echo $rowCebecera['porcengastoadmin']."%" ?></div></td>
				</tr>
				<tr>
					<td><div align="left"><b>Observaciones</b></div></td>
					<td colspan="5" valign="bottom"><div align="left"><?php echo $rowCebecera['observaciones'] ?></div></td>
				</tr>
			</table>
			<h3>Per&iacute;odos</h3>
	<?php   $sqlPeriodos = "select * from detacuerdosusimra where cuit = $cuit and nroacuerdo = $nroacu";
			$resPeriodos = mysql_query($sqlPeriodos,$db);
			$canPeriodos = mysql_num_rows($resPeriodos);
			if ($canPeriodos != 0 ) { ?>
				<table width="400" border="1" style="text-align: center">
					<tr>
						<td><b>Mes</b></td>
						<td><b>Año</b></td>
						<td><b>Concepto de deuda </b></td>
					</tr>
			  <?php while ($rowPeriodos = mysql_fetch_array($resPeriodos)) {
					  	$sqlConcep = "select * from conceptosdeudas where codigo = '".$rowPeriodos['conceptodeuda']."'";
					  	$resConcep = mysql_query($sqlConcep,$db);
					  	$rowConcep = mysql_fetch_array($resConcep); ?>
					  	<tr>
							<td><?php echo $rowPeriodos['mesacuerdo'] ?></td>
							<td><?php echo $rowPeriodos['anoacuerdo'] ?></td>
							<td><?php echo $rowConcep['descripcion'] ?></td>
						</tr>
			  <?php } ?>
				</table>
			<?php 
		} else { ?>
			<p style="color: blue"><b>No hay periódos cargados relacionados con este acuerdo</b></p>
<?php	} ?>
			<h3>Cuotas</h3>
			<table width="1000" border="1" style="text-align: center">
				<tr>
					<td><b>Nº </b></td>
					<td><b>Monto </b></td>
					<td><b>Fecha </b></td>
					<td><b>Cancelacion</b></td>
					<td><b>Nro Cheque</b></td>
					<td><b>Banco </b></td>
					<td><b>Fecha Cheque </b></td>
					<td><b>Observaciones</b></td>
					<td><b>Estado</b></td>
					<td><b>Fecha Pago</b></td>
				</tr>
		 <?php  $sqlCuotas = "select * from cuoacuerdosusimra where cuit = $cuit and nroacuerdo = $nroacu";
				$resCuotas = mysql_query($sqlCuotas,$db);
				$canCuotas = mysql_num_rows($resCuotas);
				if ($canCuotas != 0) {
					while ($rowCuotas = mysql_fetch_array($resCuotas)) {
						$sqlTipo = "select * from tiposcancelaciones where codigo =".$rowCuotas['tipocancelacion'];
						$resTipo = mysql_query($sqlTipo,$db);
						$rowTipo = mysql_fetch_array($resTipo); ?>
						<tr>
							<td><?php echo $rowCuotas['nrocuota'] ?></td>
							<td><?php echo $rowCuotas['montocuota'] ?></td>
							<td><?php echo invertirFecha($rowCuotas['fechacuota']) ?></td>
							<td><?php echo $rowTipo['descripcion'] ?></td>
					<?php 	if ($rowCuotas['chequenro'] != 0) { ?>
								<td><?php echo $rowCuotas['chequenro'] ?></td>
								<td><?php echo $rowCuotas['chequebanco'] ?></td>
								<td><?php echo invertirFecha($rowCuotas['chequefecha']) ?></td>
					<?php	} else { ?>
								<td>-</td>
								<td>-</td>
								<td>-</td>
					<?php	}
							if ($rowCuotas['observaciones'] == "") { ?>
								<td>-</td>
					<?php	} else { ?>
								<td><?php echo $rowCuotas['observaciones'] ?></td>
					<?php	} 
							if ($rowCuotas['montopagada'] != 0 || $rowCuotas['fechapagada'] != '0000-00-00') { ?>
								<td>CANCELADA (<?php echo $rowCuotas['sistemacancelacion'] ?>)</td>
								<td><?php echo invertirFecha($rowCuotas['fechapagada']) ?></td>
					<?php	} else {
								if ($rowCuotas['boletaimpresa'] != 0) { ?>
									<td>BOLETA IMPRESA</td>
									<td>-</td>
					<?php		} else { ?>
									<td>A PAGAR</td>
									<td>-</td>
						<?php	}
							} ?>
						</tr>
			<?php }
				$saldoRestante = $rowCebecera['montoapagar'] - $rowCebecera['montopagadas']; ?>
				<tr>
					<td><b>Total<br/> Cuotas</b></td>
					<td><b><?php echo $rowCebecera['montoapagar'] ?></b></td>
				</tr>
				<tr>
					<td><b>Total<br/> Pagado</b></td>
					<td><b><?php echo $rowCebecera['montopagadas'] ?></b></td>
				</tr>
				<tr>
					<td><b>Saldo</b></td>
					<td><b><?php echo number_format($saldoRestante,2,'.','') ?></b></td>
				</tr>
			</table>
	<?php	} else { ?>
				<p style="color: red">Error al leer las cuotas recien cargadas.</p>
	<?php	} ?>
			<p><input type="button" name="imprimir" value="Imprimir" onClick="window.print();" /></p>
		</div>
	</form>
</body>
</html>
