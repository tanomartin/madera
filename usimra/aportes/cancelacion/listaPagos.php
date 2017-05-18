<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php"); 
include($libPath."fechas.php");
if(isset($_POST['cuit'])) {
	$cuit=$_POST['cuit'];
} else {
	$cuit=$_GET['cuit'];
}
include($libPath."cabeceraEmpresaConsulta.php");
$fechaInicio=$row['iniobliusi'];
include($libPath."limitesTemporalesEmpresasUsimra.php");
if($tipo=="noexiste") {
	header('Location: moduloCancelacion.php?err=1');
}
?>
<!DOCTYPE>
<html>
<head>
<title>.: Selecci&oacute;n de Periodo a Cancelar :.</title>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none;color:#0033FF}
A:hover {text-decoration: none;color:#33CCFF }
</style>
<style type="text/css" media="print">
.nover {display:none}
</style>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css" type="text/css" id="" media="print, projection, screen" />
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery-ui.min.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script type="text/javascript">
$(document).ready(function(){
	$("#resultados")
		.tablesorter({
			theme: 'blue',
			widthFixed: true, 
			widgets: ["zebra","filter"],
			headers: {0:{sorter:true, filter: true}, 1:{sorter:true, filter: true}, 2:{sorter:false, filter: false}, 3:{sorter:false, filter: false}, 4:{sorter:false, filter: false}, 5:{sorter:false, filter: false}, 6:{sorter:false, filter: false}},
			widgetOptions: {filter_cssFilter:'',filter_childRows:false,filter_hideFilters:false,filter_ignoreCase:true,filter_searchDelay:300,filter_startsWith:false,filter_hideFilters:false}
		})
		.tablesorterPager({container: $("#paginador")}); 
});
</script>
</head>
<body bgcolor="#B2A274">
	<div align="center">
		<input type="button" name="volver" value="Volver" onClick="location.href = 'moduloCancelacion.php'" />
<?php 	
include($libPath."cabeceraEmpresa.php"); 
?>
		<h1>Pagos Existentes</h1>
		<table id="resultados" class="tablesorter" style="font-size:14px; text-align:center">
			<thead>
				<tr>
					<th class="filter-select" data-placeholder="Seleccion Mes">Mes</th>
					<th class="filter-select" data-placeholder="Seleccion Año">A&ntilde;o</th>
					<th>Fecha de Pago</th>
					<th>Total Depositado</th>
					<th>Sistema de Cancelaci&oacute;n</th>
					<th class="nover">Acci&oacute;n</th>
				</tr>
			</thead>
			<tbody>
<?php
$sqlListaPagos="SELECT s.mespago, p.descripcion AS mesnombre, s.anopago, s.nropago, s.fechapago, s.montopagado, s.sistemacancelacion, s.codigobarra FROM seguvidausimra s, periodosusimra p WHERE s.cuit = '$cuit' AND ((s.anopago > $anoinicio and s.anopago <= $anofin) or (s.anopago = $anoinicio and s.mespago >= $mesinicio)) AND s.anopago = p.anio AND s.mespago = p.mes ORDER BY s.anopago DESC, s.mespago ASC, s.nropago DESC";
$resListaPagos=mysql_query($sqlListaPagos,$db);
$totalpagos=mysql_num_rows($resListaPagos);
while($rowListaPagos=mysql_fetch_array($resListaPagos)) {
?>
				<tr>
					<td><?php echo $rowListaPagos['mesnombre'];?></td>
					<td><?php echo $rowListaPagos['anopago'];?></td>
					<td><?php echo invertirFecha($rowListaPagos['fechapago']);?></td>
					<td><?php echo $rowListaPagos['montopagado'];?></td>
					
<?php
	if($rowListaPagos['sistemacancelacion']=='E') { ?>
					<td><?php echo "Electronico ".$rowListaPagos['codigobarra']; ?></td>
					<td><?php echo "-"; ?></td>
<?php
	} else {
		if($rowListaPagos['sistemacancelacion']=='L') { ?>
					<td><?php echo "Link Pagos ".$rowListaPagos['codigobarra']; ?></td>
					<td><?php echo "-"; ?></td>
<?php
		} else { ?>
					<td><?php echo "Manual"; ?></td>
					<td class="nover"><input class="nover" type="button" id="modificapago" name="modificapago" value="Modificar" onClick="location.href = 'modificaPago.php?cuit=<?php echo $cuit?>&mespago=<?php echo $rowListaPagos['mespago']?>&anopago=<?php echo $rowListaPagos['anopago']?>&nropago=<?php echo $rowListaPagos['nropago']?>'"/></td>
<?php
		}
	}
?>
				</tr>
<?php
}
?>
			</tbody>
		</table>
	</div>
	<div id="paginador" class="pager" align="center">
		<form>
			<img src="../img/first.png" width="16" height="16" class="first"/>
			  <img src="../img/prev.png" width="16" height="16" class="prev"/>
			  <input type="text" class="pagedisplay" size="8" readonly="readonly" style="background:#CCCCCC; text-align:center"/>
			  <img src="../img/next.png" width="16" height="16" class="next"/>
			  <img src="../img/last.png" width="16" height="16" class="last"/>
				<select class="pagesize">
				  <option selected="selected" value="10">10 por pagina</option>
				  <option value="20">20 por pagina</option>
				  <option value="30">30 por pagina</option>
				  <option value="<?php echo $totalpagos;?>">Todos</option>
				</select>
		</form>
	</div>
	<div align="center">
		<p><input class="nover" type="button" id="cancelapago" name="cancelapago" value="Cancelar Período" onClick="location.href = 'cancelaPago.php?cuit=<?php echo $cuit?>'"/></p>
	</div>
</body>
</html>
