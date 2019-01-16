<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
$sqlOrdenesAMigrar = "SELECT *, DATE_FORMAT(o.fecha, '%d-%m-%Y') as fecha, p.dirigidoa as beneficiario 
						FROM ordennmcabecera o, prestadoresnm p 
						WHERE o.fechageneracion is not NULL and 
							  o.fechacancelacion is NULL and 
							  o.fechamigracion is NULL and
							  o.codigoprestador = p.codigo";
$resOrdenesAMigrar = mysql_query($sqlOrdenesAMigrar,$db);
$canOrdenesAMigrar = mysql_num_rows($resOrdenesAMigrar); ?>

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

</script>
<title>.: Modulo Migracion Ordenes de Pago :.</title>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = '../moduloOrdenPagoNM.php'" /></p>
	<h3>Módulo de Migración Ordenes de Pago No Médicas </h3>
<?php if ($canOrdenesAMigrar > 0) { ?>
	  	<h3>Ordenes a Migrar al Sistema Contable</h3>	
	 	<table class="tablesorter" id="listado" style="width:70%; font-size:14px; text-align: center">
		 	<thead>
			 	<tr>
			 		<th width="10%">Nro. Orden</th>
			 		<th>Beneficiario</th>
					<th>Fecha</th>
			 		<th>Importe</th>					
			 	</tr>
		 	</thead>
		 	<tbody>
	  <?php while ($rowOrdenesAMigrar = mysql_fetch_array($resOrdenesAMigrar)) { ?>
	  	  		<tr>
		 		  	<td><?php echo $rowOrdenesAMigrar['nroorden'];?></td>
		 		  	<td><?php echo $rowOrdenesAMigrar['beneficiario'] ?></td>
		 		  	<td><?php echo $rowOrdenesAMigrar['fecha'] ?></td>
		 		  	<td><?php echo number_format($rowOrdenesAMigrar['importe'],2,",",".") ?></td>
		 	 	</tr>
	  <?php } ?>
		 	</tbody>
		</table>
<?php } else { ?>
		<h3 style="color: blue">No Existen Ordenes de Pago para Migrar</h3>
<?php } ?>
	<p><input type="button" value="Generar Archivo"/></p>
</div>
</body>
</html>