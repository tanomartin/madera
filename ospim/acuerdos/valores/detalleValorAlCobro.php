<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
include($_SERVER['DOCUMENT_ROOT']."/madera/lib/fechas.php"); 
 
$nrocheque = $_GET['nrocheque'];
$fecdep = $_GET['fecdep'];
$feccheque =  $_GET['feccheque'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Detalle Valor al Cobro Realizado :.</title>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script type="text/javascript">
	$(function() {
		$("#listado")
		.tablesorter({
			theme: 'blue', 
			widthFixed: true, 
			widgets: ["zebra", "filter"], 
			widgetOptions : { 
				filter_cssFilter   : '',
				filter_childRows   : false,
				filter_hideFilters : false,
				filter_ignoreCase  : true,
				filter_searchDelay : 300,
				filter_startsWith  : false,
				filter_hideFilters : false,		
			}	
		});
	});
	
</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = 'valoresRealizados.php'"/></p>
  <h3>Valor al Cobro Cheque Nro. '<?php echo $nrocheque ?>' con fecha '<?php echo $feccheque ?>' generado el '<?php echo $fecdep ?>'  </h3>
  <table class="tablesorter" id="listado" style="width:1000px; font-size:14px">
	  <thead>
		<tr>
		  <th>C.U.I.T.</th>
		  <th>Acuerdo</th>
		  <th>Cuota</th>
		  <th>Nro. Cheque</th>
		  <th>Fecha Cheque</th>
		  <th>Banco</th>
		  <th>Id. Resumen</th>
		  <th>Fecha Resumen</th>
		  <th>Importe</th>
		</tr>
	 </thead>
	 <tbody>
		<?php	
			$sqlValores = "SELECT v.cuit, v.nroacuerdo, v.nrocuota, v.chequenro, DATE_FORMAT(v.chequefecha,'%d/%m/%Y') as chequefecha, v.chequebanco, 
								  v.idresumenbancario, DATE_FORMAT(v.fecharesumenbancario,'%d/%m/%Y') as fecharesumenbancario, c.montocuota
							FROM valoresalcobro v, cuoacuerdosospim c 
							WHERE v.chequenroospim = '$nrocheque' and
							v.chequenro = c.chequenro and
							v.cuit = c.cuit and
							v.nroacuerdo = c.nroacuerdo and
							v.nrocuota = c.nrocuota and
							c.tipocancelacion = 3";
			$resValores = mysql_query($sqlValores,$db); 
			$canValores = mysql_num_rows($resValores);
			$total = 0;
			while ($rowValores = mysql_fetch_array($resValores)) { 
				$total += (float) $rowValores['montocuota'] ?>
				<tr align="center">
					<td><?php echo $rowValores['cuit'] ?></td>
					<td><?php echo $rowValores['nroacuerdo'] ?></td>
					<td><?php echo $rowValores['nrocuota'] ?></td>
					<td><?php echo $rowValores['chequenro'] ?></td>
					<td><?php echo $rowValores['chequefecha'] ?></td>
					<td><?php echo $rowValores['chequebanco']?></td>
					<td><?php echo $rowValores['idresumenbancario']?></td>
					<td><?php echo $rowValores['fecharesumenbancario']?></td>
					<td align="right"><?php echo number_format($rowValores['montocuota'],2,',','.')?></td>
				</tr>
	 <?php } ?>
	 		<tr>
				<td colspan="8" align="right"><b>TOTAL</b></td>
				<td align="right"><b><?php echo number_format($total,2,',','.') ?></b></td>
			</tr>	
    </tbody>
  </table>
  <p><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" align="right"/></p>
</div>
</body>
</html>