<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php"); 

$cuit = $_POST['cuit'];
$sqlEmpresa = "SELECT * FROM empresas where cuit = $cuit";
$resEmpresa = mysql_query($sqlEmpresa,$db);
$canEmpresa = mysql_num_rows($resEmpresa);
if ($canEmpresa == 0) {
	header ("Location: aportesCuit.php?err=2");
} else {
	$rowEmpresa = mysql_fetch_assoc($resEmpresa);
	$sqlAportes = "select s.*, ap6.importe as importeap6, ap1.importe as importeap1, ap15.importe as importeap15
					from seguvidausimra s
					LEFT OUTER JOIN
					apor060usimra ap6 on
					s.cuit = ap6.cuit and
					s.anopago = ap6.anopago and
					s.mespago = ap6.mespago and
					s.nropago = ap6.nropago
					LEFT OUTER JOIN
					apor100usimra ap1 on
					s.cuit = ap1.cuit and
					s.anopago = ap1.anopago and
					s.mespago = ap1.mespago and
					s.nropago = ap1.nropago
					LEFT OUTER JOIN
					apor150usimra ap15 on
					s.cuit = ap15.cuit and
					s.anopago = ap15.anopago and
					s.mespago = ap15.mespago and
					s.nropago = ap15.nropago
					where 
					s.cuit = $cuit";
	$resAportes = mysql_query($sqlAportes,$db);
	$canAportes = mysql_num_rows($resAportes);
	if ($canAportes == 0) {
		header ("Location: aportesCuit.php?err=1");
	}
}
	


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
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
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
<body bgcolor="#B2A274">
<div align="center">
	 <input type="reset" class="nover" name="volver" value="Volver" onclick="location.href = 'aportesCuit.php'" align="center"/>
	<p><span class="Estilo2">Aportes Empresa "<?php echo $rowEmpresa['nombre'] ?>" - C.U.I.T.: <?php echo $rowEmpresa['cuit'] ?> </span></p>
	<table class="tablesorter" id="listado" style="width:800px; font-size:14px">
	<thead>
		<tr>
			<th class="filter-select" data-placeholder="Seleccion Año">Año</th>
			<th class="filter-select" data-placeholder="Seleccion Mes">Mes</th>
			<th>Fecha de Pago</th>
			<th>Personal</th>
			<th>Remuneracion</th>
			<th>Aporte 0.6%</th>
			<th>Contri 1%</th>
			<th>Aporte 1.5%</th>
			<th>Recargo</th>
			<th>Pagado</th>
			<th>Observ</th>
		</tr>
	</thead>
	<tbody>
		<?php
		while($rowAportes = mysql_fetch_assoc($resAportes)) {
		?>
		<tr align="center">
			<td><?php echo $rowAportes['anopago'];?></td>
			<td><?php echo $rowAportes['mespago'];?></td>
			<td><?php echo invertirFecha($rowAportes['fechapago']);?></td>
			<td><?php echo $rowAportes['cantidadpersonal'];?></td>
			<td align="right"><?php print(number_format($rowAportes['remuneraciones'],2,',','.')) ?></td>
			<td align="right"><?php print(number_format($rowAportes['importeap6'],2,',','.'))  ?></td>
			<td align="right"><?php print(number_format($rowAportes['importeap1'],2,',','.'))  ?></td>
			<td align="right"><?php print(number_format($rowAportes['importeap15'],2,',','.'))  ?></td>
			<td align="right"><?php print(number_format($rowAportes['montorecargo'],2,',','.')) ?></td>
			<td align="right"><?php print(number_format($rowAportes['montopagado'],2,',','.')) ?></td>
			<td align="center"><?php print($rowAportes['observacion']) ?></td>
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
		      <option value="<?php echo $canAportes;?>">Todos</option>
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