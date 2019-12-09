<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
$liquidador = $_SESSION['usuario'];
$sqlFacturas = "SELECT f.*, p.nombre, p.cuit, DATE_FORMAT(f.fechacomprobante,'%d-%m-%Y') as fechacomprobante, 
					   t.descripcion as tipocomprobante
					FROM facturas f, prestadores p, tipocomprobante t
					WHERE 
						  f.usuarioliquidacion = '$liquidador' AND 
						  f.idPrestador = p.codigoprestador AND 
						  f.idTipocomprobante = t.id
					ORDER BY f.id DESC";
$resFacturas = mysql_query($sqlFacturas,$db); 
$numFacturas = mysql_num_rows($resFacturas); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado Factura Liquidaciones :.</title>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script type="text/javascript">

$(function() {
	$("#listaFacturasUsuario")
	.tablesorter({
		theme: 'blue', 
		widthFixed: true, 
		widgets: ["zebra", "filter"], 
		headers:{4:{sorter:false, filter: false},
				 5:{sorter:false, filter: false},
				 6:{sorter:false, filter: false},
				 7:{sorter:false, filter: false},
				 8:{sorter:false, filter: false},
				 9:{sorter:false},
				 10:{sorter:false, filter: false}},
		widgetOptions : { 
			filter_cssFilter   : '',
			filter_childRows   : false,
			filter_hideFilters : false,
			filter_ignoreCase  : true,
			filter_searchDelay : 300,
			filter_startsWith  : false,
			filter_hideFilters : false,
		}
	}).tablesorterPager({container: $("#paginador")}); 
});

function abrirPop(dire){	
	window.open(dire,'Planilla De Debito','width=800, height=500,resizable=yes');
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = 'menuLiquidaciones.php'" /></p>
	<h2>Listado de Facturas - Liquidador: "<?php echo $liquidador ?>"</h2>
<?php if ($numFacturas) {  ?>
		<table style="text-align:center; width:95%" id="listaFacturasUsuario">
			<thead>
				<tr>
					<th>Id</th>
					<th>C.U.I.T.</th>
					<th width="25%">Nombre</th>
					<th>Comprobante</th>
					<th>Fecha</th>
					<th>$ Importe</th>
					<th>$ Debito</th>
					<th>$ Liquidado</th>
					<th>$ Pagado</th>
					<th class="filter-select" data-placeholder="--">Estado</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			<?php while ($rowFacturas = mysql_fetch_assoc($resFacturas)) { ?>
				<tr>
					<td><?php echo $rowFacturas['id'] ?></td>
					<td><?php echo $rowFacturas['cuit'] ?></td>
					<td><?php echo $rowFacturas['nombre'] ?></td>
					<td><?php echo $rowFacturas['tipocomprobante']."<br>".$rowFacturas['puntodeventa']."-".$rowFacturas['nrocomprobante'] ?></td>
					<td><?php echo $rowFacturas['fechacomprobante'] ?></td>
					<td><?php echo number_format($rowFacturas['importecomprobante'],2,',','.'); ?></td>
					<td><?php echo number_format($rowFacturas['totaldebito'],2,',','.'); ?></td>
					<td><?php echo number_format($rowFacturas['importeliquidado'],2,',','.'); ?></td>
					<td><?php echo number_format($rowFacturas['totalpagado'],2,',','.'); ?></td>
					<?php $estado = "AUDITORIA";
						  if ($rowFacturas['fechacierreliquidacion'] != "0000-00-00 00:00:00") { 
							if ($rowFacturas['autorizacionpago'] == 1) {
								if ($rowFacturas['restoapagar'] != 0) {
									if ($rowFacturas['restoapagar'] == $rowFacturas['importeliquidado']) {
										$estado = "PARA PAGAR";
									} else {
										$estado = "PAGO PARCIAL";
									}
								} else {
									$estado = "PAGADA";
								}
							} else {
								$estado = "ENVIAR A PAGAR";
							}
						  } ?>
					<td><b><?php echo $estado ?></b></td>
					<td>
						<input type="button" value="Liquidacion" onclick="abrirPop('consultaLiquidacion.php?id=<?php echo $rowFacturas['id'] ?>&estado=<?php echo $estado ?>');" /></br>
						<?php if ($rowFacturas['restoapagar'] != $rowFacturas['importeliquidado'] && $rowFacturas['totaldebito'] != 0) { ?>
							<input type="button" value="Plan. Debito" style="margin-top: 5px"  onclick="abrirPop('planillaDebito.php?id=<?php echo $rowFacturas['id'] ?>');"  />
						<?php } ?>
					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
		<div id="paginador" class="pager">
			<form>
				<p>
					<img src="../img/first.png" width="16" height="16" class="first"/>
					<img src="../img/prev.png" width="16" height="16" class="prev"/>
					<input type="text" class="pagedisplay" size="8" readonly="readonly" style="background:#CCCCCC; text-align:center"/>
					<img src="../img/next.png" width="16" height="16" class="next"/>
					<img src="../img/last.png" width="16" height="16" class="last"/>
				</p>
				<p>
					<select class="pagesize">
						<option selected="selected" value="10">10 por pagina</option>
						<option value="20">20 por pagina</option>
						<option value="30">30 por pagina</option>
						<option value="50">50 por pagina</option>
						<option value="<?php echo $numFacturas?>">Todos</option>
					</select>
				</p>
			</form>
		</div>
<?php } else { ?>
		<h3 style="color: blue">No existen facturas para este Liquidador</h3>
<?php }?>
</div>
</body>
</html>