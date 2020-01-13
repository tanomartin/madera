<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
	$sqlFacturasSinLiquidar = "SELECT p.nombre, p.cuit, f.id, f.puntodeventa, f.nrocomprobante, f.fechacomprobante, f.importecomprobante, f.fechavencimiento FROM facturas f, prestadores p WHERE f.fechainicioliquidacion = '0000-00-00 00:00:00' AND f.idPrestador = p.codigoprestador AND p.personeria != 5 ORDER by f.id DESC";
	$resFacturasSinLiquidar = mysql_query($sqlFacturasSinLiquidar,$db);
	$totalfacturasingresadas = mysql_num_rows($resFacturasSinLiquidar);

	$sqlFacturasUsuario = "SELECT p.nombre, p.cuit, f.id, f.puntodeventa, f.nrocomprobante, f.fechacomprobante, f.importecomprobante, f.fechavencimiento FROM facturas f, prestadores p WHERE f.fechainicioliquidacion != '0000-00-00 00:00:00' AND f.usuarioliquidacion = '$_SESSION[usuario]' AND f.fechacierreliquidacion = '0000-00-00 00:00:00' AND f.idPrestador = p.codigoprestador ORDER by f.id DESC";
	$resFacturasUsuario = mysql_query($sqlFacturasUsuario,$db);
	$totalfacturasusuario = mysql_num_rows($resFacturasUsuario);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modulo Liquidaciones :.</title>

<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<link rel="stylesheet" href="/madera/lib/jquery-ui-1.9.2.custom/css/smoothness/jquery-ui-1.9.2.custom.css"/>
<script src="/madera/lib/jquery-ui-1.9.2.custom/js/jquery-1.8.3.js"></script>
<script src="/madera/lib/jquery-ui-1.9.2.custom/js/jquery-ui-1.9.2.custom.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script type="text/javascript">
$(document).ready(function(){
	$("#listaFacturasIngresadas")
		.tablesorter({
			theme: 'blue', 
			widthFixed: true, 
			headers: {
				0:{sorter:false},
				1:{sorter:false},
				5:{sorter:false, filter: false},
				6:{sorter:false, filter: false},
				7:{sorter:false, filter: false},
				9:{sorter:false, filter: false}
			},
			widgets: ["zebra", "filter"], 
			widgetOptions: { 
				filter_cssFilter   : '',
				filter_childRows   : false,
				filter_hideFilters : false,
				filter_ignoreCase  : true,
				filter_searchDelay : 300,
				filter_startsWith  : false,
				filter_hideFilters : false,
			}
		})
		.tablesorterPager({
			container: $("#paginadorIngresadas")
		});
	$("#listaFacturasUsuario")
		.tablesorter({
			theme: 'blue', 
			widthFixed: true, 
			headers: {
				0:{sorter:false, filter: false},
				1:{sorter:false, filter: false},
				5:{sorter:false, filter: false},
				6:{sorter:false, filter: false},
				7:{sorter:false, filter: false},
				9:{sorter:false, filter: false}
			},
			widgets: ["zebra", "filter"], 
			widgetOptions: { 
				filter_cssFilter   : '',
				filter_childRows   : false,
				filter_hideFilters : false,
				filter_ignoreCase  : true,
				filter_searchDelay : 300,
				filter_startsWith  : false,
				filter_hideFilters : false,
			}
		})
		.tablesorterPager({
			container: $("#paginadorUsuario")
		});
});

function abrirPop(dire){	
	window.open(dire,'Planilla De Debito','width=800, height=500,resizable=yes');
}

</script>
</head>
<body bgcolor="#CCCCCC">
<div align="center">
		<input type="reset" name="volver" value="Volver" onclick="location.href = 'menuLiquidaciones.php'" />
</div>
<div align="center">
	<h1>Liquidacion Prestadores</h1>
</div>
<div id="facturasingresadas" align="center">
	<h2>Facturas Ingresadas Sin Inicio de Liquidacion</h2>
	<table style="text-align:center; width:1000px" id="listaFacturasIngresadas" class="tablesorter" >
		<thead>
			<tr>
				<th colspan="2">Prestador</th>
				<th colspan="6">Factura</th>
			</tr>
			<tr>
				<th>Nombre</th>
				<th>C.U.I.T.</th>
				<th>ID Interno</th>
				<th>Nro.</th>
				<th>Fecha</th>
				<th>Importe</th>
				<th>Vencimiento</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
		<?php while($rowFacturasSinLiquidar = mysql_fetch_array($resFacturasSinLiquidar)) { ?>
			<tr>
				<td><?php echo $rowFacturasSinLiquidar['nombre'];?></td>
				<td><?php echo $rowFacturasSinLiquidar['cuit'];?></td>
				<td><?php echo $rowFacturasSinLiquidar['id'];?></td>
				<td><?php echo $rowFacturasSinLiquidar['puntodeventa'].'-'.$rowFacturasSinLiquidar['nrocomprobante'];?></td>
				<td><?php echo invertirFecha($rowFacturasSinLiquidar['fechacomprobante']);?></td>
				<td><?php echo $rowFacturasSinLiquidar['importecomprobante'];?></td>
				<td><?php echo invertirFecha($rowFacturasSinLiquidar['fechavencimiento']);?></td>
				<td>
					<input class="nover" type="button" id="iniciarLiquidacion" name="iniciarLiquidacion" value="Iniciar Liquidacion" onclick="location.href = 'iniciarLiquidacion.php?idfactura=<?php echo $rowFacturasSinLiquidar['id'] ?>'"/>
				</td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
	<table class="nover" align="center" width="245" border="0">
		<tr>
			<td width="239">
				<div id="paginadorIngresadas" class="pager">
					<form>
						<p align="center">
						<img src="../img/first.png" width="16" height="16" class="first"/> <img src="../img/prev.png" width="16" height="16" class="prev"/>
						<input name="text" type="text" class="pagedisplay" style="background:#CCCCCC; text-align:center" size="8" readonly="readonly"/>
						<img src="../img/next.png" width="16" height="16" class="next"/> <img src="../img/last.png" width="16" height="16" class="last"/>
						<select name="select" class="pagesize">
							<option selected="selected" value="15">15 por pagina</option>
							<option value="30">30 por pagina</option>
							<option value="60">60 por pagina</option>
							<option value="<?php echo $totalfacturasingresadas;?>">Todas</option>
							</select>
						</p>
					</form>	
				</div>
			</td>
		</tr>
	</table>
</div>


<div id="facturasusuario" align="center">
	<h2>Facturas En Proceso de Liquidacion del Usuario @<?php echo $_SESSION['usuario'];?></h2>
	<table style="text-align:center; width:1000px" id="listaFacturasUsuario" class="tablesorter" >
		<thead>
			<tr>
				<th colspan="2">Prestador</th>
				<th colspan="6">Factura</th>
			</tr>
			<tr>
				<th>Nombre</th>
				<th>C.U.I.T.</th>
				<th>ID Interno</th>
				<th>Nro.</th>
				<th>Fecha</th>
				<th>Importe</th>
				<th>Vencimiento</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
		<?php while($rowFacturasUsuario = mysql_fetch_array($resFacturasUsuario)) { ?>
			<tr>
				<td><?php echo $rowFacturasUsuario['nombre'];?></td>
				<td><?php echo $rowFacturasUsuario['cuit'];?></td>
				<td><?php echo $rowFacturasUsuario['id'];?></td>
				<td><?php echo $rowFacturasUsuario['puntodeventa'].'-'.$rowFacturasUsuario['nrocomprobante'];?></td>
				<td><?php echo invertirFecha($rowFacturasUsuario['fechacomprobante']);?></td>
				<td><?php echo $rowFacturasUsuario['importecomprobante'];?></td>
				<td><?php echo invertirFecha($rowFacturasUsuario['fechavencimiento']);?></td>
				<td>
					<input class="nover" type="button" id="continuarLiquidacion" name="continuarLiquidacion" value="Continuar Liquidacion" onclick="location.href = 'continuarLiquidacion.php?idfactura=<?php echo $rowFacturasUsuario['id'] ?>'"/></br>
					<input type="button" value="Ver Liquidacion" onclick="abrirPop('consultaLiquidacion.php?id=<?php echo $rowFacturasUsuario['id'] ?>&estado=AUDITORIA');" />
				</td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
	<table class="nover" align="center" width="245" border="0">
		<tr>
			<td width="239">
				<div id="paginadorUsuario" class="pager">
					<form>
						<p align="center">
						<img src="../img/first.png" width="16" height="16" class="first"/> <img src="../img/prev.png" width="16" height="16" class="prev"/>
						<input name="text" type="text" class="pagedisplay" style="background:#CCCCCC; text-align:center" size="8" readonly="readonly"/>
						<img src="../img/next.png" width="16" height="16" class="next"/> <img src="../img/last.png" width="16" height="16" class="last"/>
						<select name="select" class="pagesize">
							<option selected="selected" value="15">15 por pagina</option>
							<option value="30">30 por pagina</option>
							<option value="60">60 por pagina</option>
							<option value="<?php echo $totalfacturasusuario;?>">Todas</option>
							</select>
						</p>
					</form>	
				</div>
			</td>
		</tr>
	</table>
</div>
</body>
</html>
