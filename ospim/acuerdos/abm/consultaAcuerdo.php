<?php $libPath = $_SERVER ['DOCUMENT_ROOT'] . "/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php"); 
$cuit = $_GET['cuit'];
$nroacu = $_GET['nroacu'];

$sqlCabecera = "SELECT c.*, e.descripcion as estado, t.descripcion as tipo, g.apeynombre as gestor, i.apeynombre as inspector
				FROM cabacuerdosospim c, estadosdeacuerdos e, tiposdeacuerdos t, gestoresdeacuerdos g, inspectores i
				WHERE 
					c.cuit = $cuit and 
					c.nroacuerdo = $nroacu and 
					c.estadoacuerdo = e.codigo and 
					c.tipoacuerdo = t.codigo and
					c.gestoracuerdo = g.codigo and
					c.inspectorinterviene = i.codigo";
$resCabecera = mysql_query($sqlCabecera,$db);
$rowCebecera = mysql_fetch_array($resCabecera); 

$sqlPeriodos = "SELECT * FROM detacuerdosospim d, conceptosdeudas c
				WHERE d.cuit = $cuit and d.nroacuerdo = $nroacu and d.conceptodeuda = c.codigo 
				ORDER BY anoacuerdo, mesacuerdo";
$resPeriodos = mysql_query($sqlPeriodos,$db);
$canPeriodos = mysql_num_rows($resPeriodos);

$sqlCuotas = "SELECT * FROM cuoacuerdosospim c, tiposcancelaciones t 
				WHERE c.cuit = $cuit and c.nroacuerdo = $nroacu and c.tipocancelacion = t.codigo";
$resCuotas = mysql_query($sqlCuotas,$db); 
$canCuotas = mysql_num_rows($resCuotas); 
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>.: Consulta Acuerdo :.</title>
</head>
<body bgcolor="#CCCCCC">
<form name="verificador">
	<div align="center">
	<?php if (!isset($_GET['origen'])) { ?>
			<p><input type="button" name="volver" value="Volver" onClick="location.href = 'acuerdos.php?cuit=<?php echo $cuit ?>'" /></p>
	<?php } 
		include($libPath."cabeceraEmpresaConsulta.php"); 
		include($libPath."cabeceraEmpresa.php"); ?> 
    	<p><b>O.S.P.I.M. - Acuerdo Cargado Nº <?php echo $rowCebecera['nroacuerdo'] ?></b>	</p>
   	 	<p><b>ESTADO "<?php echo $rowCebecera['estado']; ?>"</b></p>
    	
    	<p><b>Cabecera</b></p>
    	
    	<table width="900" border="1" style="text-align: left">
	      	<tr>
		        <td><b>Tipo</b></td>
		        <td><?php echo $rowCebecera['tipo'];?></td>
		        <td><b>Fecha</b></td>
		        <td><?php echo invertirFecha($rowCebecera['fechaacuerdo']) ?></td>
		        <td><b>Nº de Acta</b></td>
		        <td><?php echo $rowCebecera['nroacta'] ?></td>
	      	</tr>
	      	<tr>
	        	<td><b>Gestor</b></td>
	        	<td><?php echo $rowCebecera['gestor'];?></td>
				<td><b>Inspector</b></td>
	        	<td><?php echo $rowCebecera['inspector'];?></td>
	        	<td><b>Req. Origen</b></td>
	        	<td><?php if ($rowCebecera['requerimientoorigen'] == 0) { echo "-"; } else { echo $rowCebecera['requerimientoorigen']; }  ?></td>
	      	</tr>
	      	<tr>
	        	<td><b>Liq. Origen</b></td>
	        	<td>
				<?php 
					if ($rowCebecera['requerimientoorigen'] == 0) {
						echo "-";
					} else {
						echo $rowCebecera['liquidacionorigen'];
					}
				?>
				</td>
	        	<td><b>Monto</b></td>
	       	 	<td><?php echo $rowCebecera['montoacuerdo'] ?></td>
	        	<td><b>Gastos Admin.</b></td>
	        	<td><?php echo $rowCebecera['porcengastoadmin']."%" ?></td>
	      	</tr>
	     	<tr>
	        	<td><b>Observ.</b></td>
	        	<td colspan="5"><?php echo $rowCebecera['observaciones'] ?></td>
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
		<?php while ($rowPeriodos = mysql_fetch_array($resPeriodos)) { ?>
				<tr>
					<td><?php echo $rowPeriodos['mesacuerdo'] ?></td>
					<td><?php echo $rowPeriodos['anoacuerdo'] ?></td>
					<td><?php echo $rowPeriodos['descripcion'] ?></td>
				</tr>
		<?php } ?>
			</table>
<?php 	} else { ?>
			<p style="color: blue">No hay periódos cargados relacionados con este acuerdo</p>
<?php	}	?>
	
    	<p><strong>Cuotas</strong></p>
  <?php if ($canCuotas != 0) { ?>
			<table width="972" border="1" style="text-align: center">
		    	<tr>
			        <th>Nº </th>
			        <th>Monto </th>
			        <th>Fecha </th>
			        <th>Cancelacion</th>
			        <th>Nº Cheque</th>
			        <th>Banco </th>
			        <th>Fecha Cheque </th>
					<th>Observ.</th>
					<th>Estado</th>
					<th>Fecha Pago</th>
		      	</tr> 
	<?php 	while ($rowCuotas = mysql_fetch_array($resCuotas)) { ?>
				<tr>
					<td><?php echo $rowCuotas['nrocuota'] ?></td>
					<td><?php echo $rowCuotas['montocuota'] ?></td>
					<td><?php echo invertirFecha($rowCuotas['fechacuota']) ?></td>		
					<td><?php echo $rowCuotas['descripcion'] ?></td>
		<?php 		if ($rowCuotas['chequenro'] != 0) { ?>
						<td><?php echo $rowCuotas['chequenro'] ?></td>
						<td><?php echo $rowCuotas['chequebanco'] ?></td>
						<td><?php echo invertirFecha($rowCuotas['chequefecha']) ?></td>
		<?php 		} else { ?>
						<td>-</td>
						<td>-</td>
						<td>-</td>
		<?php 	 	}
					if ($rowCuotas['observaciones'] == "") { ?>
						<td>-</td>
	<?php			} else { ?>
						<td><?php echo $rowCuotas['observaciones'] ?></td>
	<?php			}
					if ($rowCuotas['montopagada'] != 0 || $rowCuotas['fechapagada'] != '0000-00-00') { ?>
						<td>CANCELADA (<?php echo $rowCuotas['sistemacancelacion'] ?>)</td>
						<td><?php echo invertirFecha($rowCuotas['fechapagada'])  ?></td>
	<?php			} else {
						if ($rowCuotas['boletaimpresa'] != 0) { ?>
							<td>BOLETA IMPRESA</td>
							<td>-</td>
	<?php				} else { ?>
							<td>A PAGAR</td>
							<td>-</td>
	<?php				}
					} ?>
				</tr>
<?php } ?>
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
<?php } else { ?>
		<p style="color:blue">No existen cuotas cargadas.</p>
<?php }
	  if (!isset($_GET['origen'])) { ?>
		<p><input type="button" name="imprimir" value="Imprimir" onClick="window.print();" /></p>
<?php } ?>
  	</div>
</form>
</body>
</html>