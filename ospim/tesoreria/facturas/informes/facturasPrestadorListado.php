<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");

$codigo = $_GET['codigo'];

$sqlPrestador = "SELECT codigoprestador, cuit, nombre FROM prestadores WHERE codigoprestador = $codigo";
$resPrestador = mysql_query($sqlPrestador,$db);
$rowPrestador = mysql_fetch_assoc($resPrestador);

$sqlFacturas = "SELECT f.*,DATE_FORMAT(fechacomprobante, '%d/%m/%Y') as fechacomprobante, 
						   DATE_FORMAT(fecharecepcion, '%d/%m/%Y') as fecharecepcion,
						   DATE_FORMAT(fechapago, '%d/%m/%Y') as fechapago
				FROM facturas f
				WHERE idPrestador = $codigo 
				ORDER BY f.id DESC";
$resFacturas = mysql_query($sqlFacturas,$db);
$canFacturas = mysql_num_rows($resFacturas);
$arrayFacturas = array();
if ($canFacturas > 0) {
	$whereIn = "(";
	$buscarPagos = 0;
	while ($rowFacturas = mysql_fetch_assoc($resFacturas)) {
		$arrayFacturas[$rowFacturas['id']] = $rowFacturas;
		if ($rowFacturas['fechapago'] != "00/00/0000") {
			$whereIn .= "'".$rowFacturas['id']."',";
			$buscarPagos = 1;
		}
	}
	$whereIn = substr($whereIn, 0, -1);
	$whereIn .= ")";
	if ($buscarPagos == 1) {
		$sqlOrdenesPago = "SELECT c.nroordenpago,formapago, comprobantepago, idfactura
							FROM ordencabecera c, ordendetalle d 
							WHERE d.idfactura in $whereIn and 
								  d.nroordenpago = c.nroordenpago and
								  c.fechacancelacion is null 
								  ORDER BY c.nroordenpago ASC";
		$resOrdenesPago = mysql_query($sqlOrdenesPago,$db);
		$canOrdenesPago = mysql_num_rows($resOrdenesPago);
		$arrayPagos = array();
		if ($canOrdenesPago > 0) {
			while ($rowOrdenesPago = mysql_fetch_assoc($resOrdenesPago)) {
				$arrayPagos[$rowOrdenesPago['idfactura']] = array("op" => $rowOrdenesPago['nroordenpago'],  "formapago" => $rowOrdenesPago['formapago'], "nrocompro" => $rowOrdenesPago['comprobantepago']);
			}
		}
	}
}  ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado de Facturas :.</title>
<style type="text/css" media="print">
.nover {display:none}
</style>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script type="text/javascript">

	$(function() {
		$("#listaResultado")
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
</head>
<body bgcolor="#CCCCCC">
	<div align="center">
		<h3>Informe de Facturas </h3>
		<h3>Prestador:<font color="blue"> <?php echo $rowPrestador['nombre']?> - <?php echo $rowPrestador['cuit']?> (<?php echo $rowPrestador['codigoprestador'] ?>)</font></h3>
	 	<?php if (sizeof($arrayFacturas) > 0 ) { ?>
		 	<table style="text-align:center; width:90%" id="listaResultado" class="tablesorter" >
		 		<thead>
					<tr>
				 		<th>ID Fac.</th>
				 		<th>Nro Compr.</th>
				 		<th>Fecha Compr.</th>
				 		<th>Fecha Recp.</th>
				 		<th>Facturado</th>
				 		<th>Debitos</th>
				 		<th>Liquidado</th>
				 		<th>Pagos</th>
				 		<th>Ultimo Pago</th>
				 		<th>Comprobante</th>
				 		<th>Saldo</th>
				 	</tr>
				 </thead>
				 <tbody>
			<?php $totalFac = 0;
				  $totalDeb = 0;
				  $totalLiq = 0;
				  $totalPag = 0;
				  $totalSaldo = 0;
				  foreach ($arrayFacturas as $facturas) { 
				  	$totalFac += $facturas['importecomprobante'];
				    $totalDeb += $facturas['totaldebito'];
				    $totalLiq += $facturas['importeliquidado'];
				    $totalPag += $facturas['totalpagado']; ?>
					<tr>
						<td><?php echo $facturas['id'] ?></td>
						<td><?php echo $facturas['puntodeventa']."-".$facturas['nrocomprobante'] ?></td>
						<td><?php echo $facturas['fechacomprobante'] ?></td>
						<td><?php echo $facturas['fecharecepcion'] ?></td>
						<td><?php echo number_format($facturas['importecomprobante'],2,",","."); ?></td>
						<td><?php echo number_format($facturas['totaldebito'],2,",","."); ?></td>
						<td><?php echo number_format($facturas['importeliquidado'],2,",","."); ?></td>
						<td><?php echo number_format($facturas['totalpagado'],2,",","."); ?></td>
						<?php 	
							$fechapago = "-";
							$comprobante = "-";
							if ($facturas['fechapago'] != "00/00/0000") { 
								$fechapago = $facturas['fechapago'];
								if (isset($arrayPagos[$facturas['id']])) {
									$comprobante = "O.P.: ".$arrayPagos[$facturas['id']]['op']." - ".$arrayPagos[$facturas['id']]['formapago']." ".$arrayPagos[$facturas['id']]['nrocompro'];
								}
							} ?>
						<td><?php echo $fechapago ?> </td>
						<td><?php echo $comprobante ?> </td>
						<?php $saldo = $facturas['restoapagar'];
							  if ($facturas['restoapagar'] == 0 && $facturas['importeliquidado'] == 0) { 
									$saldo = $facturas['importecomprobante']; 
							  } 
							  $totalSaldo += $saldo; ?>
						<td><?php echo number_format($saldo,2,",","."); ?></td>				
					</tr>
			<?php } ?>
				  </tbody>
				  <tr>
				  	<th colspan="4">TOTALES</th>
				  	<th><?php echo number_format($totalFac,2,",","."); ?></th>
				  	<th><?php echo number_format($totalDeb,2,",","."); ?></th>
				  	<th><?php echo number_format($totalLiq,2,",","."); ?></th>
				  	<th><?php echo number_format($totalPag,2,",","."); ?></th>
				  	<th colspan="2"></th>
				  	<th><?php echo number_format($totalSaldo,2,",","."); ?></th>
				  </tr>
		 	</table>
	 <?php  } else { ?>
		 		<h3>No existen Facturas Pendientes de Pago</h3>
	  <?php }?>
	  <p><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" /></p>
	</div>
</body>
</html>