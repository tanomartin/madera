<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionOspim.php"); 
include($_SERVER['DOCUMENT_ROOT']."/lib/fechas.php"); 
 
$nrocheque = $_GET['nrocheque'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Detalle Valor al Cobro Realizado :.</title>
</head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>
<script src="/lib/jquery.js"></script>
<script src="/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/lib/jquery.tablesorter/themes/theme.blue.css">
<script src="/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
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
		})
	});
	
</script>
<body bgcolor="#CCCCCC">
<div align="center">
  <p><span class="Estilo2">Valor al Cobro Cheque Nro. '<?php echo $nrocheque ?>' </span></p>
  <table class="tablesorter" id="listado" style="width:600px; font-size:14px">
	  <thead>
		<tr>
		  <th>C.U.I.T.</th>
		  <th>Acuerdo</th>
		  <th>Cuota</th>
		  <th>Nro. Cheque</th>
		  <th>Fecha Cheque</th>
		  <th>Banco</th>
		  <th>Importe</th>
		</tr>
	 </thead>
	 <tbody>
		<?php	
			$sqlValores = "SELECT v.cuit, v.nroacuerdo, v.nrocuota, v.chequenro, v.chequefecha, v.chequebanco, c.montocuota
							FROM valoresalcobro v, cuoacuerdosospim c Where
							v.chequenroospim = '$nrocheque' and
							v.chequenro = c.chequenro and
							v.cuit = c.cuit and
							v.nroacuerdo = c.nroacuerdo and
							v.nrocuota = c.nrocuota and
							c.tipocancelacion = 3";
			$resValores = mysql_query($sqlValores,$db); 
			$canValores = mysql_num_rows($resValores);
			while ($rowValores = mysql_fetch_array($resValores)) { 
				$total = (float) ($total +  $rowValores['montocuota']); ?>
			<tr align="center">
					<td><?php echo $rowValores['cuit'] ?></td>
					<td><?php echo $rowValores['nroacuerdo'] ?></td>
					<td><?php echo $rowValores['nrocuota'] ?></td>
					<td><?php echo $rowValores['chequenro'] ?></td>
					<td><?php echo invertirFecha($rowValores['chequefecha'])?></td>
					<td><?php echo $rowValores['chequebanco']?></td>
					<td align="right"><?php echo number_format($rowValores['montocuota'],2,',','.')?></td>
			</tr>
	 <?php } ?>
	 		<tr>
				<td colspan="6" align="right">TOTAL</td>
				<td align="right"><b><?php echo number_format($total,2,',','.') ?></b></td>
			<tr>
			
    </tbody>
  </table>
  <p>
    <input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" align="right"/>
  </p>
</div>
</body>
</html>