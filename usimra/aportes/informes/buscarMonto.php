<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php"); 

$canDDJJMonto = 0;
if (isset($_POST['monto'])) {
	$sqlDDJJMonto = "SELECT d.*, p.descripcion as periodo, e.nombre FROM ddjjusimra d, periodosusimra p, empresas e
						WHERE d.nrcuil = '99999999999' and d.totapo + d.recarg = ".$_POST['monto']." and 
							  d.perano = p.anio and d.permes = p.mes and d.nrcuit = e.cuit";
	$resDDJJMonto = mysql_query($sqlDDJJMonto,$db);
	$canDDJJMonto = mysql_num_rows($resDDJJMonto);
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Buscador por Monto :.</title>

<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">

$(function() {
	$("#listaResultado")
	.tablesorter({
		theme: 'blue', 
		widthFixed: true, 
		widgets: ["zebra", "filter"], 
		widgetOptions : { 
			filter_cssFilter   : '',
			filter_childRows   : false,
			filter_hideFilters : false,
			filter_ignoreCase  : true,
			filter_searchDelay : 300,
			filter_startsWith  : false,
			filter_hideFilters : false,
		}
	});
});

function validar(formulario) {
	if (formulario.monto.value == "" || !isNumberPositivo(formulario.monto.value)) {
		alert("El monto a buscar es obligatorio y debe ser positvo");
		return false;
	}
	$.blockUI({ message: "<h1>Generando Busqueda... <br>Esto puede tardar unos segundos.<br> Aguarde por favor</h1>" });
	return true;
}

</script>

</head>
<body bgcolor="#B2A274">
	<div align="center">
		<p><input type="button" name="volver" value="Volver" onclick="location.href = 'moduloInformes.php'" /></p>
		<h3>Buscador DDJJ por Monto</h3>
		<form id="form1" name="form1" method="post" onsubmit="return validar(this)" action="buscarMonto.php">
			<p><b>MONTO </b><input type="text" id="monto" name="monto" size="10"/></p>
			<p><input type="submit" name="submit" value="BUSCAR"/></p>
		</form>
		<?php if (isset($_POST['monto'])) { 
				if ($canDDJJMonto != 0) {  ?>
					<table class="tablesorter" id="listaResultado" style="width:1200px; font-size:14px; text-align: center">
						<thead>
							<tr>
								<th>ID</th>
								<th>Periodo</th>
								<th>CUIT</th>
								<th>Razón Social</th>
								<th>Cant. Remu.</th>
								<th>Remuneracion</th>
								<th>Aporte 0.6</th>
								<th>Aporte 1.0</th>
								<th>Aporte 1.5</th>
								<th>Recargo</th>
								<th>A pagar</th>
								<th class="filter-select" data-placeholder="Seleccione">Doc. para Pagar</th>
							</tr>
						</thead>
						<tbody>
			<?php 		while($rowEmpleados = mysql_fetch_assoc($resDDJJMonto)) { ?>
							<tr>
								<td><?php echo $rowEmpleados['id']?></td>
								<td><?php echo $rowEmpleados['periodo']."<br>(".$rowEmpleados['permes']."-".$rowEmpleados['perano'].")";?></td>
								<td><?php echo $rowEmpleados['nrcuit']?></td>
								<td><?php echo $rowEmpleados['nombre']?></td>
								<td><?php echo $rowEmpleados['nfilas']?></td>
								<td><?php echo $rowEmpleados['remune']?></td>
								<td><?php echo $rowEmpleados['apo060']?></td>
								<td><?php echo $rowEmpleados['apo100']?></td>
								<td><?php echo $rowEmpleados['apo150']?></td>
								<td><?php echo $rowEmpleados['recarg']?></td>
								<td><?php echo $rowEmpleados['recarg']+$rowEmpleados['totapo']?></td>
								<td><?php if ($rowEmpleados['instrumento'] == "B") { echo "BOLETA DE PAGO"; } 
										  if ($rowEmpleados['instrumento'] == "T") { echo "LINK PAGOS"; } ?>
								</td>
							</tr>
			<?php		} ?>
						</tbody>
					</table>
					<input type="button" name="imprimir" value="Imprimir" onclick="window.print();"/>
		<?php	} else { ?>
					<h3 style="color: blue;">No existe D.D.J.J. con el monto "<?php echo $_POST['monto'] ?>"</h3>
		<?php	}
			  } ?>
	</div>
</body>
</html>