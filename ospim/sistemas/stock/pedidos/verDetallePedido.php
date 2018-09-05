<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."fechas.php"); 
$id = $_GET['id'];
$sqlDetPedido = "SELECT d.*, d.descripcion as descri, i.* FROM detpedidos d, insumo i where d.idpedido = $id and d.idinsumo = i.id";
$resDetPedido = mysql_query($sqlDetPedido,$db);
$canDetPedido = mysql_num_rows($resDetPedido); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Detalle Pedido :.</title>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>

<script>
	$(function() {
		$("#listado")
		.tablesorter({
			theme: 'blue',
			widthFixed: true, 
			widgets: ["zebra"],
			headers:{2:{sorter:false, filter: false}, 3:{sorter:false, filter: false},  4:{sorter:false, filter: false}, 5:{sorter:false, filter: false}, 6:{sorter:false, filter: false}},
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
<style type="text/css" media="print">
.nover {display:none}
</style>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input class="nover"  type="reset" name="volver" value="Volver" onclick="location.href = 'pedidos.php'" /></p>
  <h3>Detalle del Pedido <?php echo $id ?> </h3>
  <table class="tablesorter" id="listado" style="width:80%; font-size:14px; text-align: center">
	  <thead>
		<tr>
		  <th>Codigo</th>
		  <th>Nombre</th>
		  <th>Descripcion</th>
		  <th>Cant. Pedido</th>
		  <th>Costo Unitario</th>
		  <th>Cant. Entregado</th>
		  <th>Total</th>
		  <th>Descripcion</th>
		</tr>
	 </thead>
	 <tbody>
		<?php	
			$i = 0;
			$total = 0;
			while ($rowDetPedido = mysql_fetch_assoc($resDetPedido)) { ?>
				<tr align="center">
					<td><?php echo $rowDetPedido['id'] ?></td>
					<td><?php echo $rowDetPedido['nombre'] ?></td>	
					<td><?php echo $rowDetPedido['descripcion'] ?></td>	
					<td><?php echo $rowDetPedido['cantidadpedido'] ?></td>
					<td><?php echo number_format($rowDetPedido['costounitario'],2,",","."); ?></td>
					<td><?php echo $rowDetPedido['cantidadentregada'] ?></td>
					<td><?php $totalfila = (float)$rowDetPedido['cantidadpedido'] * (float)$rowDetPedido['costounitario'];
							  $total += $totalfila ; 
							  echo number_format($totalfila,2,",","."); ?>
					</td>
					<td><?php echo $rowDetPedido['descri'] ?></td>
		</tr>
	 <?php $i++;} ?>
    </tbody>
    <tr>
		<th colspan="6">TOTAL</th>
		<th><?php echo number_format($total,2,",","."); ?></th>
		<th></th>
	</tr>
  </table>
  <p><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" align="right"/></p>
</div>
</body>
</html>

