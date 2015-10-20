<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php");
$cuit = $_GET['cuit'];
$nroacu = $_GET['nroacu'];
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style>
A:link {
	text-decoration: none;
	color: #0033FF
}

A:visited {
	text-decoration: none
}

A:hover {
	text-decoration: none;
	color: #00FFFF
}
</style>
<title>.: Consulta Acuerdo :.</title>
</head>
<body bgcolor="#B2A274">
	<form name="verificador">
		<div align="center">
		<?php 
			$origen = $_GET['origen'];
			if (is_null($origen)) { ?>
				<input type="button" name="volver" value="Volver" onclick="location.href = 'acuerdos.php?cuit=<?php echo $cuit?>'" />
	<?php 	} 
			include($libPath."cabeceraEmpresaConsulta.php");
			include($libPath."cabeceraEmpresa.php"); ?>
			<p>
				<strong>U.S.I.M.R.A. - Acuerdo N� "<?php echo $nroacu ?>"</strong>
			</p>
			<p>
				<strong>ESTADO </strong>
				<?php 
				$sqlCabecera = "SELECT c.*, e.descripcion as desestado, t.descripcion as destipo, g.apeynombre as gestor, i.apeynombre as inspector
				FROM cabacuerdosusimra c, estadosdeacuerdos e, tiposdeacuerdos t, gestoresdeacuerdos g, inspectores i
				WHERE c.cuit = $cuit and c.nroacuerdo = $nroacu and c.estadoacuerdo = e.codigo and c.tipoacuerdo = t.codigo and c.gestoracuerdo = g.codigo and c.inspectorinterviene = i.codigo LIMIT 1";
				$resCabecera = mysql_query($sqlCabecera,$db);
				$canCabecera = mysql_num_rows($resCabecera);
				if ($canCabecera == 1) {
		$rowCebecera = mysql_fetch_array($resCabecera);
	} else {
		echo ("<div align='center'> Error en la lectura de la cabecera de acuerdo cargada </div>");
	}
	echo $rowCebecera['desestado']; ?>
			</p>
			<p>
				<strong>Cabecera</strong>
			</p>
			<table width="954" border="1">
				<tr>
					<td width="126" valign="bottom"><div align="left">
							<b>Tipo de Acuerdo</b>
						</div></td>
					<td width="225" valign="bottom"><div align="left">
							<?php echo $rowCebecera['destipo']; ?>
						</div></td>
					<td width="106" valign="bottom"><div align="left">
							<b>Fecha Acuerdo</b>
						</div></td>
					<td width="144" valign="bottom"><div align="left">
							<?php echo invertirFecha($rowCebecera['fechaacuerdo']) ?>
						</div></td>
					<td width="158" valign="bottom"><div align="left">
							<b>N&uacute;mero de Acta</b>
						</div></td>
					<td valign="bottom"><div align="left">
							<?php echo $rowCebecera['nroacta'] ?>
						</div></td>
				</tr>
				<tr>
					<td valign="bottom"><div align="left">
							<b>Gestor</b>
						</div></td>
					<td valign="bottom"><div align="left">
							<?php echo $rowCebecera['gestor']; ?>
						</div></td>
					<td valign="bottom"><div align="left">
							<b>Inspector</b>
						</div></td>
					<td valign="bottom"><div align="left">
							<?php echo $rowCebecera['inspector'];?>
						</div></td>
					<td valign="bottom"><div align="left">
							<b>Requerimiento de Origen</b>
						</div></td>
					<td valign="bottom"><div align="left">
							<?php if ($rowCebecera['requerimientoorigen'] == 0) { 
								echo "-";
							} else { echo $rowCebecera['requerimientoorigen'];
}  ?>
						</div></td>
				</tr>
				<tr>
					<td valign="bottom"><div align="left">
							<b>Liquidacion Origen</b>
						</div></td>
					<td valign="bottom"><div align="left">
							<?php 
							if ($rowCebecera['requerimientoorigen'] == 0) {
				echo "-";
			} else {
				echo $rowCebecera['liquidacionorigen'];
			}
			?>
						</div></td>
					<td valign="bottom"><div align="left">
							<b>Monto Acuerdo</b>
						</div></td>
					<td valign="bottom"><div align="left">
							<?php echo $rowCebecera['montoacuerdo'] ?>
						</div></td>
					<td valign="bottom"><div align="left">
							<b>Gastos Administrativos</b>
						</div></td>
					<td valign="bottom"><div align="left">
							<?php echo $rowCebecera['porcengastoadmin']."%" ?>
						</div></td>
				</tr>
				<tr>
					<td height="23" valign="bottom"><div align="left">
							<b>Observaciones</b>
						</div></td>
					<td colspan="5" valign="bottom"><div align="left">
							<?php echo $rowCebecera['observaciones'] ?>
						</div></td>
				</tr>
			</table>
			<p>
				<strong>Per&iacute;odos</strong>
			</p>
			<?php 
			$sqlPeriodos = "select * from detacuerdosusimra where cuit = $cuit and nroacuerdo = $nroacu";
			$resPeriodos = mysql_query($sqlPeriodos,$db);
			$canPeriodos = mysql_num_rows($resPeriodos);
		if ($canPeriodos != 0 ) { ?>
			<table width="431" height="32" border="1">
				<tr>
					<td width="107"><div align="center">
							<b>Mes</b>
						</div></td>
					<td width="140"><div align="center">
							<b>A&ntilde;o</b>
						</div></td>
					<td width="170"><div align="center">
							<b>Concepto de deuda </b>
						</div></td>
				</tr>


				<?php 
				while ($rowPeriodos = mysql_fetch_array($resPeriodos)) {
				print ("<td width=107 align='center'><font face=Verdana size=2>".$rowPeriodos['mesacuerdo']."</font></td>");
				print ("<td width=140 align='center'><font face=Verdana size=2>".$rowPeriodos['anoacuerdo']."</font></td>");
				$sqlConcep = "select * from conceptosdeudas where codigo = '".$rowPeriodos['conceptodeuda']."'";
				$resConcep = mysql_query($sqlConcep,$db);
				$rowConcep = mysql_fetch_array($resConcep);
				print ("<td width=170 align='center'><font face=Verdana size=2>".$rowConcep['descripcion']."</font></td>");
				print ("</tr>");
			}
			?>
			</table>
			<?php 
		} else {
			echo ("<div align='center'>No hay peri�dos cargados relacionados con este acuerdo</div>");
		}
		?>


			<p>
				<strong>Cuotas</strong>
			</p>
			<table width="1000" border="1">
				<tr>
					<td width="79"><div align="center">
							<b>N&deg; </b>
						</div></td>
					<td width="64"><div align="center">
							<b>Monto </b>
						</div></td>
					<td width="85"><div align="center">
							<b>Fecha </b>
						</div></td>
					<td width="91"><div align="center">
							<b>Cancelacion</b>
						</div></td>
					<td width="91"><div align="center">
							<b>Nro Cheque</b>
						</div></td>
					<td width="83"><div align="center">
							<b>Banco </b>
						</div></td>
					<td width="99"><div align="center">
							<b>Fecha Cheque </b>
						</div></td>
					<td width="125"><div align="center">
							<b>Observaciones</b>
						</div></td>
					<td width="119"><div align="center">
							<b>Estado</b>
						</div></td>
					<td width="100"><div align="center">
							<b>Fecha Pago</b>
						</div></td>
				</tr>
				<?php 
				$sqlCuotas = "select * from cuoacuerdosusimra where cuit = $cuit and nroacuerdo = $nroacu";
				$resCuotas = mysql_query($sqlCuotas,$db);
				$canCuotas = mysql_num_rows($resCuotas);
				if ($canCuotas != 0) {
			while ($rowCuotas = mysql_fetch_array($resCuotas)) {
				print ("<td width=79 align='center'><font face=Verdana size=2>".$rowCuotas['nrocuota']."</font></td>");
				print ("<td width=81 align='center'><font face=Verdana size=2>".$rowCuotas['montocuota']."</font></td>");
				print ("<td width=82 align='center'><font face=Verdana size=2>".invertirFecha($rowCuotas['fechacuota'])."</font></td>");

				$sqlTipo = "select * from tiposcancelaciones where codigo =".$rowCuotas['tipocancelacion'];
				$resTipo = mysql_query($sqlTipo,$db);
				$rowTipo = mysql_fetch_array($resTipo);
				print ("<td width=106 align='center'><font face=Verdana size=2>".$rowTipo['descripcion']."</font></td>");

				if ($rowCuotas['chequenro'] != 0) {
					print ("<td width=91 align='center'><font face=Verdana size=2>".$rowCuotas['chequenro']."</font></td>");
					print ("<td width=83 align='center'><font face=Verdana size=2>".$rowCuotas['chequebanco']."</font></td>");
					print ("<td width=99 align='center'><font face=Verdana size=2>".invertirFecha($rowCuotas['chequefecha'])."</font></td>");
				} else {
					print ("<td width=91 align='center'><font face=Verdana size=2>-</font></td>");
					print ("<td width=83 align='center'><font face=Verdana size=2>-</font></td>");
					print ("<td width=99 align='center'><font face=Verdana size=2>-</font></td>");
				}

				if ($rowCuotas['observaciones'] == "") {
					print ("<td width=125 align='center'><font face=Verdana size=2>-</font></td>");
				} else {
					print ("<td width=125 align='center'><font face=Verdana size=2>".$rowCuotas['observaciones']."</font></td>");
				}
				if ($rowCuotas['montopagada'] != 0 || $rowCuotas['fechapagada'] != '0000-00-00') {
					print ("<td width=119 align='center'><font face=Verdana size=2>CANCELADA (".$rowCuotas['sistemacancelacion'].")</font></td>");
					print ("<td width=94 align='center'><font face=Verdana size=2>".invertirFecha($rowCuotas['fechapagada'])."</font></td>");
				} else {
					if ($rowCuotas['boletaimpresa'] != 0) {
						print ("<td width=119 align='center'><font face=Verdana size=2>BOLETA IMPRESA</font></td>");
						print ("<td width=94 align='center'><font face=Verdana size=2>-</font></td>");
					} else {
						print ("<td width=119 align='center'><font face=Verdana size=2>A PAGAR</font></td>");
						print ("<td width=94 align='center'><font face=Verdana size=2>-</font></td>");
					}
				}

				print ("</tr>");
			}
		} else {
			echo ("<div align='center'>Error al leer las cuotas recien cargadas.</div>");
		}
		?>
				<tr>
					<td width="79"><div align="center">
							<b>Total Cuotas</b>
						</div></td>
					<?php print ("<td width=79 align='center'><font face=Verdana size=2><b>".$rowCebecera['montoapagar']."</b></font></td>"); ?>
				</tr>
				<tr>
					<td width="79"><div align="center">
							<b>Total Pagado</b>
						</div></td>
					<?php print ("<td width=79 align='center'><font face=Verdana size=2><b>".$rowCebecera['montopagadas']."</b></font></td>"); ?>
				</tr>
				<tr>
					<td width="79"><div align="center">
							<b>Saldo</b>
						</div></td>
					<?php 
				$saldoRestante = $rowCebecera['montoapagar'] - $rowCebecera['montopagadas'];
				print ("<td width=79 align='center'><font face=Verdana size=2><b>".number_format($saldoRestante,2,'.','')."</b></font></td>"); 
				?>
				</tr>
			</table>
		</div>
		<div align="center">
			<p>
				<input type="button" name="imprimir" value="Imprimir"
					onClick="window.print();" />
			</p>
		</div>
	</form>
</body>
</html>
