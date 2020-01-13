<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$sqlFacturas = "SELECT p.codigoprestador, p.cuit, p.nombre, f.*, 
				DATE_FORMAT(f.fechacomprobante, '%d/%m/%Y') as fechacomprobante,
				DATE_FORMAT(f.fechavencimiento, '%d/%m/%Y') as fechavencimiento
				FROM facturas f, prestadores p 
				WHERE f.idPrestador = p.codigoprestador AND p.personeria = 5 AND
					  (fechapago = 0000-00-00 OR (fechapago != 0000-00-00 AND restoapagar > 0))
				ORDER BY p.cuit, f.id";
$resFacturas = mysql_query($sqlFacturas,$db);
$canFacturas = mysql_num_rows($resFacturas); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modulo Ordenes de Pago Medicas No Medicas :.</title>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script type="text/javascript">


$(function() {
	$("#listaFacturas")
	.tablesorter({
		theme: 'blue',
		widthFixed: true, 
		widgets: ["zebra","filter"],
		headers:{5:{sorter:false, filter: false}, 
				 6:{sorter:false, filter: false}, 
				 7:{sorter:false, filter: false},
				 8:{sorter:false, filter: false},
				 9:{sorter:false, filter: false},
				 10:{sorter:false, filter: false},
				 11:{sorter:false, filter: false}},
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

function habilitarBoton() {
	document.getElementById("cargarOrden").disabled = false;
}

function validar(fomrulario) {
	fomrulario.cargarOrden.disabled = true;
	return true;
}
	
</script>

</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = '../moduloOrdenPagoNM.php'" /></p>	
	<h3>Listado Facturas No Médias a Liquidar/Pagar</h3>
<?php if ($canFacturas > 0) { ?>
		<form id="formNoMedicas" name="formNoMedicas" method="post" onsubmit="return validar(this)" action="cargarOrdenPagoNM.php">	
			 <table style="text-align:center; width:90%" id="listaFacturas" class="tablesorter" >
				<thead>
					<tr>
						<th>Codigo</th>
						<th>C.U.I.T.</th>
						<th>Razon Social</th>
						<th>Id Factura</th>
						<th>Nro. Factura</th>
						<th>Fecha</th>
						<th>Fecha Vto.</th>
						<th>Imp. Factura</th>
						<th>Imp. Debitos</th>
						<th>A pagar</th>
						<th></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
	      <?php while($rowFacturas = mysql_fetch_assoc($resFacturas)) { ?>
					<tr>
						<td><?php echo $rowFacturas['codigoprestador'];?></td>
						<td><?php echo $rowFacturas['cuit'];?></td>
						<td><?php echo $rowFacturas['nombre'];?></td>
						<td><?php echo $rowFacturas['id'];?></td>
						<td><?php echo $rowFacturas['puntodeventa']."-".$rowFacturas['nrocomprobante'] ?></td>
						<td><?php echo $rowFacturas['fechacomprobante'];?></td>
						<td><?php echo $rowFacturas['fechavencimiento'];?></td>
						<td><?php echo number_format($rowFacturas['importecomprobante'],2,',','.');?></td>
						<td><?php echo number_format($rowFacturas['totaldebito'],2,',','.');?></td>
						<td><?php echo number_format($rowFacturas['totalpagado'],2,',','.');?></td>
						<td><input type="button" value="+INFO" onclick="javascript:location.href='consultaFacturaNM.php?id=<?php echo $rowFacturas['id'] ?>'"/></td>
						<td>
						<?php if($rowFacturas['autorizacionpago'] == "0") {  ?>
								<input type="button" value="LIQUIDAR" onclick="javascript:location.href='liquidarFacturaNM.php?id=<?php echo $rowFacturas['id'] ?>'"/>
						<?php } else { ?>
								<input type="radio" value="<?php echo $rowFacturas['cuit']."-".$rowFacturas['id']."-".$rowFacturas['codigoprestador'] ?>" id="<?php echo $rowFacturas['cuit']."-".$rowFacturas['id']."-".$rowFacturas['codigoprestador'] ?>" name="generar" onclick="habilitarBoton()"/>
						<?php } ?>
						</td>
					</tr>
		  <?php } ?>
				</tbody>
		  	</table>
	 		<p><input disabled="disabled" type="submit" name="cargarOrden" id="cargarOrden" value="Cargar Orden de Pago" /></p>
	 	</form>
<?php } else { ?>
		<h3 style="color: blue">No existen facturas No Médicas pendientes de pago</h3>
<?php } ?>
</div>
</body>
</html>