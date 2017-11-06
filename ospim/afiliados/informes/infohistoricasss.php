<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$sqlHistorico = "SELECT * FROM padronssscabecera ORDER BY id DESC";
$resHistorico = mysql_query($sqlHistorico,$db);
$canHistorico = mysql_num_rows($resHistorico);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M&oacute;dulo SSS OSPIM :.</title>
<style type="text/css" media="print">
.nover {
	display: none
}
</style>
<script src="/madera/lib/jquery.js"></script>
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
			widgets: ["zebra"]
		})
		.tablesorterPager({container: $("#paginador")}); 
	});
</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  	<p><input type="button" name="volver" value="Volver" class="nover" onclick="location.href = 'menuSSS.php'" /> </p>
  	<h3>Información Histórica Padrón SSS </h3>
	<table id="listado" class="tablesorter" style="width:800px; font-size:14px; text-align:center">
		<thead>
			<tr>
				<th>Periodo</th>
				<th>Cant. Titulares</th>
				<th>Cant. Familiares</th>
				<th>Total</th>
				<th>Activo Busqueda</th>
			</tr>
		</thead>
		<tbody>
		<?php while ($rowHistorico = mysql_fetch_assoc($resHistorico)) { ?>
				<tr>
					<td><?php echo $rowHistorico['mes']."-".$rowHistorico['anio']?></td>
					<td><?php echo $rowHistorico['cantidadtitulares']?></td>
					<td><?php echo $rowHistorico['cantidadfamiliares']?></td>
					<td><?php echo $rowHistorico['cantidadregistros']?></td>
					<?php 
						$activo = "NO";
						if($rowHistorico['fechadelete'] == NULL) { $activo = "SI"; } 
					?>
					<td><?php echo $activo?></td>
				</tr>
		<?php } ?>
		</tbody>
	</table>

	<table class="nover" align="center" width="245" border="0">
		<tr>
			<td width="239">
				<div id="paginador" class="pager">
					<form>
						<p align="center">
							<img src="../img/first.png" width="16" height="16" class="first"/> <img src="../img/prev.png" width="16" height="16" class="prev"/>
							<input name="text" type="text" class="pagedisplay" style="background:#CCCCCC; text-align:center" size="8" readonly="readonly"/>
							<img src="../img/next.png" width="16" height="16" class="next"/> <img src="../img/last.png" width="16" height="16" class="last"/>
							<select name="select" class="pagesize">
								<option selected="selected" value="12">12 por pagina</option>
								<option value="24">24 por pagina</option>
								<option value="<?php echo $canHistorico ?>">Todos</option>
							</select>
						</p>
						<p align="center"><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" align="right"/></p>
					</form>	
				</div>
			</td>
		</tr>
	</table>
</div>
</body>
</html>
