<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php"); 

$cuit = $_POST['cuit'];
$sqlEmpresa = "SELECT * FROM empresas where cuit = $cuit";
$resEmpresa = mysql_query($sqlEmpresa,$db);
$canEmpresa = mysql_num_rows($resEmpresa);
if ($canEmpresa == 0) {
	header ("Location: extraordinariasCuit.php?err=2");
} else {
	$rowEmpresa = mysql_fetch_assoc($resEmpresa);
	$sqlCuotas = "select * from cuotaextraordinariausimra where cuit = $cuit";
	$resCuotas = mysql_query($sqlCuotas,$db);
	$canCuotas = mysql_num_rows($resCuotas);
	if ($canCuotas == 0) {
		header ("Location: extraordinariasCuit.php?err=1");
	}
}
	


?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado de Cuotas Excepcionales por C.U.I.T. :.</title>

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
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
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
			widgets: ["zebra", "filter"], 
			headers:{4:{sorter:false}},
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
	 <input type="reset" class="nover" name="volver" value="Volver" onclick="location.href = 'aportesCuit.php'" />
	<p><span class="Estilo2">Cuotas Excepcionales Empresa "<?php echo $rowEmpresa['nombre'] ?>" - C.U.I.T.: <?php echo $rowEmpresa['cuit'] ?> (U.S.I.M.R.A.) </span></p>
	<table class="tablesorter" id="listado" style="width:800px; font-size:14px">
	<thead>
		<tr>
			<th class="filter-select" data-placeholder="Seleccion Año">Año</th>
			<th class="filter-select" data-placeholder="Seleccion Mes">Mes</th>
			<th>Fecha de Pago</th>
			<th>Personal</th>
			<th>Monto</th>
			<th>Recargo</th>
			<th>Pagado</th>
			<th>Observ</th>
		</tr>
	</thead>
	<tbody>
		<?php
		while($rowCuotas = mysql_fetch_assoc($resCuotas)) {
		?>
		<tr align="center">
			<td><?php echo $rowCuotas['anopago'];?></td>
			<td><?php echo $rowCuotas['mespago'];?></td>
			<td><?php echo invertirFecha($rowCuotas['fechapago']);?></td>
			<td><?php echo $rowCuotas['cantidadaportantes'];?></td>
			<td align="right"><?php print(number_format($rowCuotas['totalaporte'],2,',','.')) ?></td>
			<td align="right"><?php print(number_format($rowCuotas['montorecargo'],2,',','.'))  ?></td>
			<td align="right"><?php print(number_format($rowCuotas['monotopagado'],2,',','.'))  ?></td>
			<td align="center"><?php print($rowAportes['observacion']) ?></td>
		</tr>
		<?php
		}
		?>
	</tbody>
  </table>
    <table style="width: 245; border: 0">
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
		      <option value="<?php echo $canCuotas;?>">Todos</option>
		      </select>
		    </p>
			<p align="center"><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();"/></p>
		  </form>	
		</div>
	</td>
      </tr>
  </table>
</div>
</body>
</html>