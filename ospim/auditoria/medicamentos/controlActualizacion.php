<?php  $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$sqlControl = "SELECT m.*, DATE_FORMAT(m.fechaarchivo, '%d-%m-%Y') as fechaarchivo FROM medicontrol m order by id DESC";
$resControl = mysql_query($sqlControl,$db);
$canControl = mysql_num_rows($resControl);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Sistemas Medicmaentos :.</title>

<script src="/madera/lib/jquery.js"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script type="text/javascript">

	$(function() {
		$("#lista")
		.tablesorter({
			theme: 'blue', 
			widthFixed: true, 
			headers:{0:{sorter:false},1:{sorter:false},2:{sorter:false},3:{sorter:false},4:{sorter:false},5:{sorter:false}},
			widgets: ["zebra"], 
			widgetOptions : { 
				filter_cssFilter   : '',
				filter_childRows   : false,
				filter_hideFilters : false,
				filter_ignoreCase  : true,
				filter_searchDelay : 300,
				filter_startsWith  : false,
				filter_hideFilters : false,
			}
		}).tablesorterPager({container: $("#paginador")}); 
	});

	
</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = 'menuMedicamentos.php'" /></p>
	<h3>Listado de Actualizaciones [alfaBETA] </h3>
	
	<table style="text-align:center; width:900px" id="lista" class="tablesorter" >
		<thead>
			<tr>
				<th>ID</th>
				<th>Tipo</th>
				<th># Medicamentos</th>
				<th># Extra</th>
				<th># Accion</th>
				<th># Monodroga</th>
				<th># Tamaño</th>
				<th># Formas</th>
				<th># Unidades</th>
				<th># Tipo Unidades</th>
				<th># Vias</th>
				<th>Fecha Archivo</th>
			</tr>
		</thead>
		<tbody>
	<?php while ($rowControl = mysql_fetch_assoc($resControl)) { ?>
			<tr>
				<td><?php echo $rowControl['id'] ?></td>
				<td><?php echo $rowControl['tipo'] ?></td>
				<td><?php echo $rowControl['cantidamedicamento'] ?></td>
				<td><?php echo $rowControl['cantidadextra'] ?></td>
				<td><?php echo $rowControl['cantidadaccion'] ?></td>
				<td><?php echo $rowControl['cantidadmono'] ?></td>
				<td><?php echo $rowControl['cantidadtamano'] ?></td>
				<td><?php echo $rowControl['cantidadformas'] ?></td>			
				<td><?php echo $rowControl['cantidadupotencia'] ?></td>
				<td><?php echo $rowControl['cantidadunidad'] ?></td>
				<td><?php echo $rowControl['cantidadvias'] ?></td>			
				<td><?php echo $rowControl['fechaarchivo'] ?></td>
			</tr>
	<?php	}	?>
		</tbody>
	</table>
	<div id="paginador" class="pager">
		<form>
			<p>
				<img src="img/first.png" width="16" height="16" class="first"/>
				<img src="img/prev.png" width="16" height="16" class="prev"/>
				<input type="text" class="pagedisplay" size="8" readonly="readonly" style="background:#CCCCCC; text-align:center"/>
				<img src="img/next.png" width="16" height="16" class="next"/>
				<img src="img/last.png" width="16" height="16" class="last"/>
			</p>
			<p>
				<select class="pagesize">
					<option selected="selected" value="10">10 por pagina</option>
					<option value="20">20 por pagina</option>
					<option value="30">30 por pagina</option>
					<option value="50">50 por pagina</option>
					<option value="<?php echo $canControl?>">Todos</option>
				</select>
			</p>
		</form>
	</div>
</div>
</body>
</html>