<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionUsimra.php");
include($_SERVER['DOCUMENT_ROOT']."/lib/fechas.php"); 

$cuit = $_GET['cuit'];
$anoddjj = $_GET['anoddjj'];
$mesddjj = $_GET['mesddjj'];
$control = $_GET['control'];
$sqlEmpresa = "SELECT * FROM empresas where cuit = $cuit";
$resEmpresa = mysql_query($sqlEmpresa,$db);
$rowEmpresa = mysql_fetch_assoc($resEmpresa);
	
$sqlDetalle = "SELECT * FROM tempddjjusimra FORCE INDEX (busqueda) where cuit = $cuit and cuil != '99999999999' and anoddjj = $anoddjj  and mesddjj = $mesddjj and nrocontrol = '$control'";
$resDetalle = mysql_query($sqlDetalle,$db);
$canDetalle = mysql_num_rows($resDetalle);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Detalle de DDJJ No Paga :.</title>
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
<body bgcolor="#B2A274">
<div align="center">
  <p><span class="Estilo2">Detalle de DDJJ No Paga Empresa "<?php echo $rowEmpresa['nombre'] ?>" - C.U.I.T.: <?php echo $rowEmpresa['cuit'] ?></span></p>
  <p><span class="Estilo2">Periodo: <?php echo $mesddjj ?>-<?php echo $anoddjj ?></span></p>
	<table class="tablesorter" id="listado" style="width:800px; font-size:14px">
	<thead>
		<tr>
			<th>C.U.I.L.</th>
			<th>Remuneracion</th>
			<th>Aporte 0.6%</th>
			<th>Contri 1%</th>
			<th>Aporte 1.5%</th>
		</tr>
	</thead>
	<tbody>
		<?php
		while($rowDetalle = mysql_fetch_assoc($resDetalle)) {
		?>
		<tr align="center">
			<td><?php echo $rowDetalle['cuil'];?></td>
			<td align="right"><?php print(number_format($rowDetalle['remuneraciones'],2,',','.')) ?></td>
			<td align="right"><?php print(number_format($rowDetalle['apor060'],2,',','.')) ?></td>
			<td align="right"><?php print(number_format($rowDetalle['apor100'],2,',','.')) ?></td>
			<td align="right"><?php print(number_format($rowDetalle['apor150'],2,',','.')) ?></td>
		</tr>
		<?php
		}
		?>
	</tbody>
  </table>
 </div>
</body>
</html>