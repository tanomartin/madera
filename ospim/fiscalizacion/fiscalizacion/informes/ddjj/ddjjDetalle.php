<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php");
include($_SERVER['DOCUMENT_ROOT']."/madera/lib/fechas.php"); 

$cuit = $_GET['cuit'];
$anoddjj = $_GET['anoddjj'];
$mesddjj = $_GET['mesddjj'];
$sqlEmpresa = "SELECT * FROM empresas where cuit = $cuit";
$resEmpresa = mysql_query($sqlEmpresa,$db);
$rowEmpresa = mysql_fetch_assoc($resEmpresa);
	
$sqlDetalle = "SELECT * FROM detddjjospim FORCE INDEX (busqueda) where cuit = $cuit and anoddjj = $anoddjj  and mesddjj = $mesddjj";
$resDetalle = mysql_query($sqlDetalle,$db);
$canDetalle = mysql_num_rows($resDetalle);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado de Aportes por C.U.I.T. :.</title>
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
			widgets: ["zebra"],
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
<body bgcolor="#CCCCCC">
<div align="center">
  <p><span class="Estilo2">Detalle de DDJJ Empresa "<?php echo $rowEmpresa['nombre'] ?>" - C.U.I.T.: <?php echo $rowEmpresa['cuit'] ?></span></p>
  <p><span class="Estilo2">Periodo: <?php echo $mesddjj ?>-<?php echo $anoddjj ?></span></p>
	<table class="tablesorter" id="listado" style="width:800px; font-size:14px">
	<thead>
		<tr>
			<th>C.U.I.L.</th>
			<th>Remuneracion</th>
			<th>Adherentes</th>
		</tr>
	</thead>
	<tbody>
		<?php
		while($rowDetalle = mysql_fetch_assoc($resDetalle)) {
			$total = $total + $rowDetalle['remundeclarada'];
		?>
		<tr align="center">
			<td><?php echo $rowDetalle['cuil'];?></td>
			<td><?php echo $rowDetalle['remundeclarada'];?></td>
			<td><?php echo $rowDetalle['adherentes'];?></td>
		</tr>
		<?php
		}
		?>
	</tbody>
  </table>
  <div><b>TOTAL: <?php echo number_format($total,2,',','.');?></b></div>
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
		      <option value="<?php echo $canDetalle;?>">Todos</option>
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