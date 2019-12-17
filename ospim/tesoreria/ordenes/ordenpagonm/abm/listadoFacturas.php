<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$sqlFacturas = "SELECT * 
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
		headers:{3:{sorter:false, filter: false}, 4:{sorter:false, filter: false}, 5:{sorter:false, filter: false}, 6:{sorter:false, filter: false}, 7:{sorter:false, filter: false}},
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

function habilitarMonto(id, totalApagar, seleccion, tipo) {
	console.log(totalApagar);
	var nombreinput = "apagar"+id;	
	var apagar = document.getElementById(nombreinput);
	var valorAnterior = apagar.value;
	apagar.style.readOnly = true;
	apagar.value = "0.00";
	apagar.style.backgroundColor = "silver";

	if (seleccion == "P") {
		apagar.style.readOnly = false;
		apagar.style.backgroundColor = "";
		apagar.value = "";
		apagar.focus();
	}
	if (seleccion == "T") {
		apagar.value = totalApagar;	
	}
}

function validarValor(valor, totalApagar, id) {
	var nombreinput = "apagar"+id;
	var apagar = document.getElementById(nombreinput);
	valor = parseFloat(valor).toFixed(2);
	apagar.value = valor;
	
	var ok = 0;
	if (isNumber(valor)) {
		if (valor > totalApagar || valor <= 0) {
			alert("El valor de pago parcial no puede ser mayor al Resto a Pagar y debe ser positivo");
		} else {
			var total = document.getElementById("totalNoInte");
			total.value = parseFloat(total.value) + parseFloat(valor);
			ok = 1;
		}
	} else {
		alert("El valor debe ser un numero y no puede quedar vacio");
	}
	if (ok == 0) {
		apagar.value = "0.00";
		var nombreSelect = "tipopago"+id;
		var selectTipo = document.getElementById(nombreSelect);
		selectTipo.value = "0";
		selectTipo.focus();
	}		
	apagar.style.readOnly = true;
	apagar.style.backgroundColor = "silver";
}
	
</script>

</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = '../moduloOrdenPagoNM.php'" /></p>	
	<h3>Listado Facturas No Médias Pendientes de Pago </h3>
<?php if ($canFacturas > 0) { ?>
		<form id="formNoMedicas" name="formNoMedicas" method="post" onsubmit="return validar(this)" action="nuevaOrdenPagoNM.php">	
			 <table style="text-align:center; width:80%" id="listaFacturas" class="tablesorter" >
				<thead>
					<tr>
						<th>C.U.I.T.</th>
						<th>Nro. Interno</th>
						<th>Nro. Factura</th>
						<th>Fecha</th>
						<th>Fecha Vto.</th>
						<th>Imp. Factura</th>
						<th>Pagos Ant.</th>
						<th>A pagar</th>
						<th>+INFO</th>
						<th>Accion</th>
					</tr>
				</thead>
				<tbody>
	      <?php while($rowFacturas = mysql_fetch_assoc($resFacturas)) { ?>
					<tr>
						<td><?php echo $rowFacturas['cuit'];?></td>
						<td><?php echo $rowFacturas['id'];?></td>
						<td><?php echo $rowFacturas['puntodeventa']."-".$rowFacturas['nrocomprobante'] ?></td>
						<td><?php echo $rowFacturas['fechacomprobante'];?></td>
						<td><?php echo $rowFacturas['fechavencimiento'];?></td>
						<td><?php echo number_format($rowFacturas['importecomprobante'],2,',','.');?></td>
						<td><?php echo number_format($rowFacturas['totalpagado'],2,',','.');?></td>
						<?php $restoApagar = $rowFacturas['restoapagar']; 
							  if ($rowFacturas['fechapago'] == "0000-00-00") { 
							   	$restoApagar = $rowFacturas['importecomprobante'];
							  }	?>
						<td><?php echo number_format($restoApagar,2,',','.');?></td>
						<td>
						<?php if($rowFacturas['fechacierreliquidacion'] != "0000-00-00 00:00:00") {  ?>
								<input type="button" value="Liquidacion" />
						<?php }?>
						</td>
						<td>
						<?php if($rowFacturas['fechacierreliquidacion'] == "0000-00-00 00:00:00") {  ?>
								<input type="button" value="LIQUIDAR" onclick="javascript:location.href='liquidarFacturaNM.php?id=<?php echo $rowFacturas['id'] ?>'"/>
						<?php } else { ?>
								<input type="checkbox" value="<?php echo $rowFacturas['cuit']."-".$rowFacturas['id'] ?>" />
						<?php } ?>
						</td>
					</tr>
		  <?php } ?>
				</tbody>
		  	</table>
	 		<p><input type="submit" name="cargarOrden" value="Cargar Orden de Pago" /></p>
	 	</form>
<?php } else { ?>
		<h3 style="color: blue">No existen facturas No Médicas pendientes de pago</h3>
<?php } ?>
</div>
</body>
</html>