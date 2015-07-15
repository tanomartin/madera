<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php"); 

$sqlTransfe = "SELECT * FROM transferenciasusimra order by fecha DESC, idtransferencia DESC";
$resTransfe = mysql_query($sqlTransfe,$db);
$canTransfe = mysql_num_rows($resTransfe);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" href="/lib/jquery.tablesorter/themes/blue/style.css" type="text/css" id="" media="print, projection, screen" />
<link rel="stylesheet" href="/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.css" type="text/css" id="" media="print, projection, screen" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Trasnferencia USIMRA :.</title>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
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
			widgets: ["zebra","filter"],
			headers:{5:{sorter:false, filter: false}},
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
		.tablesorterPager({container: $("#paginador")}); 
	});
</script>
</head>
<body bgcolor="#B2A274">
<div align="center">
	 <input type="reset" name="volver" value="Volver" onclick="location.href = '../documentosBancarios.php'"/>
	<p><span class="Estilo2">Transferencias Bancarias</span></p>
	<p>
	  <label>
	  <input type="submit" name="Submit" value="Cargar Nueva Transferencia" onclick="location.href = 'nuevaTransferencia.php'"/>
	  </label>
	</p>
	<?php if ($canTransfe > 0) { ?>
		<table class="tablesorter" id="listado" style="width:800px">
		<thead>
			<tr>
				<th>Nro.</th>
				<th>Banco</th>
				<th>C.U.I.T.</th>
				<th>Fecha</th>
				<th>Monto</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
			<?php
			while($rowTransfe = mysql_fetch_assoc($resTransfe)) {
				$nroTrans = $rowTransfe['idtransferencia'];
			?>
			<tr>
				<td align="center"><?php echo $nroTrans;?></td>
				<td><?php echo $rowTransfe['banco'];?></td>
				<td align="center"><?php echo $rowTransfe['cuit'];?></td>
				<td align="center"><?php echo invertirFecha($rowTransfe['fecha']);?></td>
				<td align="right"><?php echo $rowTransfe['monto'];?></td>
				<td align="center">
					<input type="button" name="consultar" value="Consultar" onclick="location.href='consultaTransferencia.php?nrotrans=<?php echo $nroTrans ?>'" />
					<input type="button" name="modificar" value="Modificar" onclick="location.href='modificaTransferencia.php?nrotrans=<?php echo $nroTrans ?>'" />
				</td>
			</tr>
			<?php
			}
			?>
		</tbody>
	  </table>
		<table width="245" border="0">
		  <tr>
			<td width="239">
			<div id="paginador" class="pager">
			  <form>
				<p align="center">
				  <img src="img/first.png" width="16" height="16" class="first"/> <img src="img/prev.png" width="16" height="16" class="prev"/>
				  <input name="text" type="text" class="pagedisplay" style="background:#CCCCCC; text-align:center" size="8" readonly="readonly"/>
				<img src="img/next.png" width="16" height="16" class="next"/> <img src="img/last.png" width="16" height="16" class="last"/>
				<select name="select" class="pagesize">
				  <option selected="selected" value="10">10 por pagina</option>
				  <option value="20">20 por pagina</option>
				  <option value="30">30 por pagina</option>
				  <option value="<?php echo $canTransfe;?>">Todos</option>
				  </select>
				</p>
				<p align="center"><input type="button" name="imprimir" value="Imprimir" onclick="window.print();" align="right"/></p>
			  </form>	
			</div>
		</td>
		  </tr>
	  </table>
	 <?php } else {
	 	print("<div aling='center'>No Existen Transferencias Cargadas</div>");
	 } ?>
</div>
</body>
</html>