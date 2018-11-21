<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php");
include($_SERVER['DOCUMENT_ROOT']."/madera/lib/fechas.php"); 
set_time_limit(0);
$cuit = $_GET['cuit'];
$anoddjj = $_GET['anoddjj'];
$mesddjj = $_GET['mesddjj'];
$sqlEmpresa = "SELECT * FROM empresas where cuit = $cuit";
$resEmpresa = mysql_query($sqlEmpresa,$db);
$rowEmpresa = mysql_fetch_assoc($resEmpresa);
	
$sqlDetalle = "SELECT * FROM detddjjospim FORCE INDEX (busqueda) where cuit = $cuit and anoddjj = $anoddjj  and mesddjj = $mesddjj";
$resDetalle = mysql_query($sqlDetalle,$db);
$canDetalle = mysql_num_rows($resDetalle);

$sqlDetalleAdicional = "SELECT cuil, importeosadicional FROM afipddjj where cuit = $cuit and anoddjj = $anoddjj  and mesddjj = $mesddjj order by secuenciapresentacion";
$resDetalleAdicional = mysql_query($sqlDetalleAdicional,$db);
$canDetalleAdicional = mysql_num_rows($resDetalleAdicional);
$arrayAdicional = array();
if ($canDetalleAdicional > 0) {
	while($rowDetalleAdicional = mysql_fetch_assoc($resDetalleAdicional)) {
		$arrayAdicional[$rowDetalleAdicional['cuil']] = $rowDetalleAdicional['importeosadicional'];
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado de Aportes por C.U.I.T. :.</title>

<style type="text/css" media="print">
.nover {display:none}
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
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <h3>Detalle de DDJJ Empresa "<?php echo $rowEmpresa['nombre'] ?>" - C.U.I.T.: <?php echo $rowEmpresa['cuit'] ?> (O.S.P.I.M.)</h3>
  <h3>Periodo: <?php echo $mesddjj ?>-<?php echo $anoddjj ?></h3>
  <table class="tablesorter" id="listado" style="width:800px; font-size:14px">
	<thead>
		<tr>
			<th>C.U.I.L.</th>
			<th>Remuneracion</th>
			<th>Ap. Adicional</th>
			<th>Total</th>
			<th>Adherentes</th>
		</tr>
	</thead>
	<tbody>
<?php	$total = 0;
		$totAdic = 0;
		$remuntotal = 0;
		while($rowDetalle = mysql_fetch_assoc($resDetalle)) {
			$total += $rowDetalle['remundeclarada'];
			$remun = $rowDetalle['remundeclarada'] - $arrayAdicional[$rowDetalle['cuil']]; 
			$remuntotal += $remun;
			$totAdic += $arrayAdicional[$rowDetalle['cuil']]; ?>
			<tr align="center">
				<td><?php echo $rowDetalle['cuil'];?></td>
				<td><?php echo number_format($remun,2,',','.'); ?></td>
				<td><?php echo number_format($arrayAdicional[$rowDetalle['cuil']],2,',','.');?></td>
				<td><?php echo number_format($rowDetalle['remundeclarada'],2,',','.');  ?></td>
				<td><?php echo $rowDetalle['adherentes'];?></td>
			</tr>
 <?php } ?>
	</tbody>
	<tr>
		<td align="center" style="background-color: #99bfe6"><b>TOTAL</b></td>
		<td align="center" style="background-color: #99bfe6"><b><?php echo number_format($remuntotal,2,',','.');?></b></td>
		<td align="center" style="background-color: #99bfe6"><b><?php echo number_format($totAdic,2,',','.');?></b></td>
		<td align="center" style="background-color: #99bfe6"><b><?php echo number_format($total,2,',','.');?></b></td>
		<td style="background-color: #99bfe6"></td>
	</tr>
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