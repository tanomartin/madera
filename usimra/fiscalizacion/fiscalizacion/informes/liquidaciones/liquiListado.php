<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php"); 

/*$tipo = $_POST['group1'];
if ($tipo == 0) {
	$titulo = "A REPARTIR";
	$sqlLiqui = "SELECT * from cabliquiospim";
}
if ($tipo == 1) {
	$titulo = "REPARTIDAS";
	$sqlLiqui = "SELECT * from cabliquiospim";
}
if ($tipo == 2) {
	$titulo = "NO NOTIFICADAS";
	$sqlLiqui = "SELECT * from cabliquiospim";
}*/
$sqlLiqui = "SELECT * from cabliquiusimra ORDER BY fechaliquidacion DESC";
$resLiqui = mysql_query($sqlLiqui,$db);
$canLiqui = mysql_num_rows($resLiqui);	
/*if ($canLiqui == 0) {
	if ($tipo == 0) {
		header ("Location: filtrosBusqueda.php?err=1");
	}
	if ($tipo == 1) {
		header ("Location: filtrosBusqueda.php?err=2");
	}
	if ($tipo == 2) {
		header ("Location: filtrosBusqueda.php?err=3");
	}
}*/


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado Requerimientos :.</title>
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
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css">
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
			headers:{3:{sorter:false, filter:false}, 4:{sorter:false, filter:false}, 5:{sorter:false, filter:false}, 6:{sorter:false, filter:false}},
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
<body bgcolor="#B2A274">
<div align="center">
	 <input type="reset" name="volver" value="Volver" onclick="location.href = '../moduloInformes.php'" align="center"/>
	<p><span class="Estilo2">Liquidaciones</span></p>
	<table class="tablesorter" id="listado" style="width:1300px; font-size:14px">
	<thead>
		<tr>
			<th>Nro. Requerimiento</th>
			<th>Fecha - Hora Liquidación</th>
			<th>Liquidación Origen</th>
			<th>Fecha Inspección</th>
			<th>Deuda Nominal</th>
			<th>Intereses</th>
			<th>Gastos Admin.</th>
			<th>Total</th>
			<th>Resolución</th>
			<th>Certificado Deuda</th>
		</tr>
	</thead>
	<tbody>
		<?php
		while($rowLiqui = mysql_fetch_assoc($resLiqui)) {
		?>
		<tr align="center">
			<td><?php echo $rowLiqui['nrorequerimiento'];?></td>
			<td><?php echo invertirFecha($rowLiqui['fechaliquidacion'])." - ".$rowLiqui['horaliquidacion'] ?></td>
			<td><?php echo $rowLiqui['liquidacionorigen'];?></td>
			<td><?php if ($rowLiqui['fechainspeccion'] != NULL) echo invertirFecha($rowLiqui['fechainspeccion']);?></td>
			<td><?php echo $rowLiqui['deudanominal'];?></td>
			<td><?php echo $rowLiqui['intereses'];?></td>
			<td><?php echo $rowLiqui['gtosadmin'];?></td>
			<td><?php echo $rowLiqui['totalliquidado'];?></td>
			<td><?php echo $rowLiqui['nroresolucioninspeccion'];?></td>
			<td><?php echo $rowLiqui['nrocertificadodeuda'];?></td>
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
			  <img src="../img/first.png" width="16" height="16" class="first"/> <img src="../img/prev.png" width="16" height="16" class="prev"/>
			  <input name="text" type="text" class="pagedisplay" style="background:#CCCCCC; text-align:center" size="8" readonly="readonly"/>
		    <img src="../img/next.png" width="16" height="16" class="next"/> <img src="../img/last.png" width="16" height="16" class="last"/>
		    <select name="select" class="pagesize">
		      <option selected="selected" value="10">10 por pagina</option>
		      <option value="20">20 por pagina</option>
		      <option value="30">30 por pagina</option>
			  <option value="50">50 por pagina</option>
		      <option value="<?php echo $canLiqui;?>">Todos</option>
		      </select>
		    </p>
			<p align="center"><input type="button" class="nover" name="imprimir" value="Imprimir" onclick="window.print();" align="right"/></p>
		  </form>	
		</div>
	</td>
      </tr>
  </table>
</div>
</body>
</html>