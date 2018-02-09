<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$sqlEscuelas = "SELECT e.*, p.descrip as provincia FROM escuelas e, provincia p WHERE e.codprovin = p.codprovin";
$resEscuelas = mysql_query($sqlEscuelas,$db);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado Escuelas :.</title>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script language="javascript" type="text/javascript">

$(function() {
	$("#lista")
	.tablesorter({
		theme: 'blue', 
		widthFixed: true, 
		widgets: ["zebra", "filter"], 
		headers:{4:{sorter:false, filter: false}},
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

function abrirPantalla(dire) {
	a= window.open(dire,'',
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=10, left=10");
}

</script>
<style type="text/css" media="print">
.nover {display:none}
</style>
</head>

<body bgcolor="#CCCCCC">
	<div align="center">
	  <p><input type="button" class="nover" name="volver" value="Volver" onclick="location.href='moduloEscuelas.php'" /></p>
	  <h3>Listado de Escuelas</h3>
		<table style="text-align:center; width:1000px" id="lista" class="tablesorter" >
			<thead>
				<tr>
					<th>Codigo</th>
					<th>Nombre</th>
					<th>C.U.E.</th>
					<th class="filter-select" data-placeholder="Seleccione Delegación">Provincia</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			<?php while ($rowEscuelas = mysql_fetch_assoc($resEscuelas)) { ?>
					<tr>
						<td><?php echo $rowEscuelas['id'] ?></td>
						<td><?php echo $rowEscuelas['nombre'] ?></td>
						<td><?php echo $rowEscuelas['cue'] ?></td>
						<td><?php echo $rowEscuelas['provincia'] ?></td>
						<td><input type="button" value="Ficha" onclick="abrirPantalla('escuela.php?id=<?php echo  $rowEscuelas['id'] ?>')"/></td>
					</tr>
			<?php } ?>
			</tbody>
		</table>
		<p><input type="button" class="nover" name="imprimir" value="Imprimir" onclick="window.print();" /></p>
	</div>
</body>
</html>
