<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$sqlOrdenesAImputar = "SELECT *, DATE_FORMAT(o.fecha, '%d-%m-%Y') as fecha, p.dirigidoa as beneficiario 
						FROM ordennmcabecera o, prestadoresnm p 
						WHERE o.fechageneracion is NULL and o.fechacancelacion is NULL and o.codigoprestador = p.codigo";
$resOrdenesAImputar = mysql_query($sqlOrdenesAImputar,$db);
$canOrdenesAImputar = mysql_num_rows($resOrdenesAImputar); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script src="/madera/lib/jquery.js"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script>

$(function() {
	$("#listado")
	.tablesorter({
		theme: 'blue',
		widthFixed: true, 
		widgets: ["zebra","filter"],
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

function cancelarOrden(nroorden, boton, migrada) {
	var cartel = "Desea anular la orden de pago Nro " + nroorden;
	if (migrada == 1) {
		cartel = cartel + "\nTenga en cuenta que esta orden ya fue migrada al sistema contable";
	}
	var r = confirm(cartel);
	if (r == true) {
		boton.disabled = true;
		var redireccion = "cancelarOrdenNM.php?nroorden="+nroorden;
		location.href=redireccion;
	}
}

</script>
<title>.: Modulo Imputacion Ordenes de Pago :.</title>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = '../moduloOrdenPagoNM.php'" /></p>
	<h3>Módulo Imputación Ordenes de Pago No Médicas </h3>
<?php if ($canOrdenesAImputar > 0) { ?>
	  	<h3>Ordenes a Imputar Contablemete</h3>	
	 	<table class="tablesorter" id="listado" style="width:70%; font-size:14px; text-align: center">
		 	<thead>
			 	<tr>
			 		<th width="10%">Nro. Orden</th>
			 		<th>Beneficiario</th>
					<th>Fecha</th>
			 		<th>Importe</th>
			 		<th width="20%">Acciones</th>		 						
			 	</tr>
		 	</thead>
		 	<tbody>
	  <?php while ($rowOrdenesAImputar = mysql_fetch_array($resOrdenesAImputar)) { ?>
	  	  		<tr>
		 		  	<td><?php echo $rowOrdenesAImputar['nroorden'];?></td>
		 		  	<td><?php echo $rowOrdenesAImputar['beneficiario'] ?></td>
		 		  	<td><?php echo $rowOrdenesAImputar['fecha'] ?></td>
		 		  	<td><?php echo number_format($rowOrdenesAImputar['importe'],2,",",".") ?></td>
		 		  	<td>
		 		  <?php if ($rowOrdenesAImputar['fechaimputacion'] == NULL) { ?>
		 		  			<input type="button" value="IMPUTAR" onclick="window.location = 'imputaOrdenPagoNM.php?nroorden=<?php echo $rowOrdenesAImputar['nroorden'] ?>'" />
		 	  	  <?php } else { ?>
		 	  	  			<input type="button" value="VER" onclick="window.location = 'verOrdenPagoNM.php?nroorden=<?php echo $rowOrdenesAImputar['nroorden'] ?>'" />
		 	  	  <?php } 
		 	  	 	 	$migrada = 0; 
		 		  		if ($rowOrdenesAImputar['fechamigracion'] != null) { $migrada = 1; } ?>
		 	  			<input type="button" value="ANULAR" onclick="cancelarOrden(<?php echo $rowOrdenesAImputar['nroorden'] ?>, this, <?php echo $migrada ?>)" />
		 	 		</td>
		 	 	</tr>
	  <?php } ?>
		 	</tbody>
		</table>
<?php } else { ?>
		<h3 style="color: blue">No Existen Ordenes de Pago para Imputar Contablemente</h3>
<?php } ?>
</div>
</body>
</html>