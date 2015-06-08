<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionUsimra.php"); 


$sqlDescargas = "SELECT * FROM aporcontroldescarga order by id DESC LIMIT 30";
$resDescargas = mysql_query($sqlDescargas,$db);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado de Descargas :.</title>
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
<style type="text/css" media="print">
.nover {display:none}
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
			widgets: ["zebra"],
			headers:{3:{sorter:false, filter: false}},
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
	
	function detalleDescasga(id) {
		var dire = 'detalleDescarga.php?idControl='+id;
		c= window.open(dire,"Detalle DDJJ","toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=30, left=40");
	}
	
</script>
<body bgcolor="#B2A274">
<div align="center">
  <p><input type="reset" name="volver" value="Volver" onClick="location.href = 'moduloInformes.php'" align="center"/></p>
  <p><span class="Estilo2">Listado de Descargas</span></p>
	<table class="tablesorter" id="listado" style="width:600px; font-size:14px">
	<thead>
		<tr>
			<th>Usuario</th>
			<th>Fecha</th>
			<th>Ultimo Nro Control</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php
		while($rowDescargas = mysql_fetch_assoc($resDescargas)) {?>
		<tr align="center">
			<td><?php echo $rowDescargas['usuariodescarga'];?></td>
			<td><?php echo $rowDescargas['fechadescarga'];?></td>
			<td><?php echo $rowDescargas['nrocontrol'];?></td>
			<td><input type="button" value="Detalle" onclick="detalleDescasga('<?php echo $rowDescargas['id'] ?>')" /></td>
		</tr>
		<?php
		}
		?>
	</tbody>
  </table>
</div>
</body>
</html>