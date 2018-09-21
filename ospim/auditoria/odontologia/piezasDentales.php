<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
$sqlpiezas = "SELECT * FROM piezadental ORDER BY codigo";
$respiezas = mysql_query($sqlpiezas,$db);

$sqlcaras = "SELECT * FROM piezadentalcaras ORDER BY codigo";
$rescaras = mysql_query($sqlcaras,$db);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M&oacute;dulo Detalle Resoluciones :.</title>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>

<script type="text/javascript">

	$(function() {
		$("#piezas")
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
		}),

		$("#caras")
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

<style type="text/css" media="print">
.nover {display:none}
</style>

</head>

<body bgcolor="#CCCCCC">
<div align="center">
  	<p><input type="button" class="nover" name="volver" value="Volver" onclick="location.href = 'menuOdontologico.php'"/></p>
  	<h3>Odontograma Ejemplo</h3>
  	<img src="img/odontograma.jpg"></img>
  	<h3>Piezas Dentales</h3>
	<table style="width: 600px; text-align: center" id="piezas" class="tablesorter">
	  	<thead>
	  		<tr>
		  		<th>Código</th>
		  		<th>Nombre</th>
		  		<th class="filter-select" data-placeholder="Seleccione Tipo">Tipo</th>
		  		<th class="filter-select" data-placeholder="Seleccione Posicion">Posicion</th>
	  		</tr>
	  	</thead>
	  	<tbody>
 <?php  while ($rowpiezas = mysql_fetch_assoc($respiezas)) { ?>
			<tr>
				<td><?php echo $rowpiezas['codigo'] ?></td>
			  	<td><?php echo $rowpiezas['descripcion'] ?></td>
			  	<td><?php echo $rowpiezas['tipo'] ?></td>
			  	<td><?php echo $rowpiezas['posicion'] ?></td>
			</tr>
  <?php } ?>
	  	</tbody>
	</table>
	<h3>Caras de Piezas</h3>
	<table style="width: 600px; text-align: center" id="caras" class="tablesorter">
	  	<thead>
	  		<tr>
		  		<th>Código</th>
		  		<th>Nombre</th>
		  		<th class="filter-select" data-placeholder="Seleccione Posicion">Posicion</th>
	  		</tr>
	  	</thead>
	  	<tbody>
 <?php  while ($rowcaras = mysql_fetch_assoc($rescaras)) { ?>
			<tr>
				<td><?php echo $rowcaras['codigo'] ?></td>
			  	<td><?php echo $rowcaras['descripcion'] ?></td>
			  	<td><?php echo $rowcaras['posicion'] ?></td>
			</tr>
  <?php } ?>
	  	</tbody>
	</table>
	<p><input type="button" class="nover" name="imprimir" value="Imprimir" onclick="window.print();" /></p>
</div>
</body>
</html>
