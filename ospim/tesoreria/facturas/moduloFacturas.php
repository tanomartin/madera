<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
$err = 0;
$idcomprobante = 0;
$importecomprobante = 0;
$totalfacturas = 0;
$mensajeerror = '';

if(isset($_GET['err']) && isset($_GET['id']) && isset($_GET['importe'])) {
	$err = $_GET['err'];
	$idcomprobante = $_GET['id'];
	$importecomprobante = $_GET['importe'];
	$mensajeerror = 'El comprobante que intenta ingresar ya ha sido cargado con el Id Interno '.$idcomprobante.' con un importe de $ '.$importecomprobante;
}
	$sqlFacturasSinLiquidar = "SELECT p.nombre, p.cuit, f.id, f.puntodeventa, f.nrocomprobante, f.fechacomprobante, f.importecomprobante, f.fechavencimiento, f.fecharecepcion FROM facturas f, prestadores p WHERE f.fechainicioliquidacion = '0000-00-00 00:00:00' AND f.idPrestador = p.codigoprestador ORDER by f.id DESC";
	$resFacturasSinLiquidar = mysql_query($sqlFacturasSinLiquidar,$db);
	$totalfacturas = mysql_num_rows($resFacturasSinLiquidar);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modulo Facturas :.</title>

<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<link rel="stylesheet" href="/madera/lib/jquery-ui-1.9.2.custom/css/smoothness/jquery-ui-1.9.2.custom.css"/>
<script src="/madera/lib/jquery-ui-1.9.2.custom/js/jquery-1.8.3.js"></script>
<script src="/madera/lib/jquery-ui-1.9.2.custom/js/jquery-ui-1.9.2.custom.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(function($){
	$("#nrofactura").mask("9999-99999999");
});
$(document).ready(function(){
	$('#ocultos').hide();
	$('#codigoprestador').hide();
	var errores = $("#error").val();
	var idfactura = $("#idcomprobante").val();
	var importefactura = $("#importecomprobante").val();
	if(errores != 0) {
		$( "#errores" ).dialog();
	}
	$("#ingresar").on( "click", function() {
		$('#errores').hide();
	});
	$("#listaFacturas")
		.tablesorter({
			theme: 'blue', 
			widthFixed: true, 
			headers: {
				0:{sorter:false},
				1:{sorter:false},
				3:{filter: false},
				5:{sorter:false},
				6:{sorter:false, filter: false},
				7:{sorter:false, filter: false},
				8:{sorter:false, filter: false},
				9:{filter: false},
				10:{sorter:false, filter: false},
				11:{sorter:false}
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
			container: $("#paginador")
		});
	$("#prestador").autocomplete({  
		source: function(request, response) {
			$.ajax({
				url: "listaPrestadores.php",
				dataType: "json",
				data: {getPrestador:request.term},
				success: function(data) {
					response(data);
				}
			});
		},
        minLength: 4,
		select: function(event, ui) {
			$("#codigoprestador").val(ui.item.codigoprestador);
		}  
	});
});

function validar(formulario) {
	formulario.ingresar.disabled = true;
	if(formulario.codigoprestador.value == "") {
		var cajadialogo = $('<div title="Aviso"><p>Debe ingresar un prestador.</p></div>');
		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#prestador').focus(); }});
		formulario.ingresar.disabled = false;
		return false;
	}
	if(formulario.nrofactura.value == "") {
		var cajadialogo = $('<div title="Aviso"><p>Debe ingresar un Nro. de Comprobantre.</p></div>');
		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#nrofactura').focus(); }});
		formulario.ingresar.disabled = false;
		return false;
	} else {
		if(formulario.nrofactura.value == "0000-00000000") {
			var cajadialogo = $('<div title="Aviso"><p>El Nro. de Comprobante ingresado no es válido.</p></div>');
			cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#nrofactura').focus(); }});
			formulario.ingresar.disabled = false;
			return false;
		}
	}
	$.blockUI({ message: "<h1>Verificando Existencia de Factura... <br>Esto puede tardar unos segundos.<br> Aguarde por favor</h1>" });
	return true;
}

function abrirPop(dire, id) {
	var idVisto = "visited"+id;
	document.getElementById(idVisto).style.display = "block";
	window.open(dire,"Consulta Factura","width=800,height=500");
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
		<input type="reset" name="volver" value="Volver" onclick="location.href = 'menuFacturas.php'" />
</div>
<div id="ocultos" align="center">
	<input name="error" id="error" type="text" value="<?php echo $err ?>" size="1"/>
	<input name="idcomprobante" id="idcomprobante" type="text" value="<?php echo $idcomprobante ?>" size="5"/>
	<input name="importecomprobante" id="importecomprobante" type="text" value="<?php echo $importecomprobante ?>" size="5"/>
</div>
<div align="center">
	<h1>Facturas de Prestadores</h1>
</div>
<form id="moduloFacturas" name="moduloFacturas" method="post" onsubmit="return validar(this)" action="buscaFactura.php">
	<div align="center">
		<h2>Nuevo Ingreso</h2>
	</div>
	<div align="center">
		<p><strong>Prestador</strong> <textarea name="prestador" rows="3" cols="100" id="prestador"></textarea></p>
		<p><strong>Comprobante Nro.</strong> <input name="nrofactura" type="text" id="nrofactura" size="10" /></p>
		<p><input name="codigoprestador" type="text" id="codigoprestador" size="10" /></p>
		<p><input class="nover" type="submit" id="ingresar" name="ingresar" value="Ingresar" /></p>
	</div>
</form>

<div id="errores" title="Factura Existente">
  <p><?php echo $mensajeerror;?></p>
</div>
<div id="facturasingresadas" align="center">
	<h2>Facturas Ingresadas Sin Inicio de Liquidacion</h2>
	<table style="text-align:center; width:1000px" id="listaFacturas" class="tablesorter" >
		<thead>
			<tr>
				<th colspan="2">Prestador</th>
				<th colspan="8">Factura</th>
			</tr>
			<tr>
				<th>Nombre</th>
				<th>C.U.I.T.</th>
				<th>ID Interno</th>
				<th>Recepcion</th>
				<th>Nro.</th>
				<th>Fecha</th>
				<th>Importe</th>
				<th>Vencimiento</th>
				<th>Acciones</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?php while($rowFacturasSinLiquidar = mysql_fetch_array($resFacturasSinLiquidar)) { ?>
			<tr>
				<td><?php echo $rowFacturasSinLiquidar['nombre'];?></td>
				<td><?php echo $rowFacturasSinLiquidar['cuit'];?></td>
				<td><?php echo $rowFacturasSinLiquidar['id'];?></td>
				<td><?php echo invertirFecha($rowFacturasSinLiquidar['fecharecepcion']);?></td>
				<td><?php echo $rowFacturasSinLiquidar['puntodeventa'].'-'.$rowFacturasSinLiquidar['nrocomprobante'];?></td>
				<td><?php echo invertirFecha($rowFacturasSinLiquidar['fechacomprobante']);?></td>
				<td><?php echo $rowFacturasSinLiquidar['importecomprobante'];?></td>
				<td><?php echo invertirFecha($rowFacturasSinLiquidar['fechavencimiento']);?></td>
				<td>
					<input class="nover" type="button" id="consultarfactura" name="consultarfactura" value="Consultar" onclick="abrirPop('consultarFactura.php?idfactura=<?php echo $rowFacturasSinLiquidar['id'] ?>','<?php echo $rowFacturasSinLiquidar['id'] ?>')"/>
					<input class="nover" type="button" id="editarfactura" name="editarfactura" value="Editar" onclick="location.href = 'editarFactura.php?idfactura=<?php echo $rowFacturasSinLiquidar['id'] ?>'"/>
				</td>
				<td>
				<?php $display = "none";
					  if(isset($_COOKIE[$rowFacturasSinLiquidar['id']])) {
							$display = "display";
					  } ?>
					<img src="../img/visited.png" height="20" width="20" style="vertical-align: middle; display: <?php echo $display ?>" id="visited<?php echo  $rowFacturasSinLiquidar['id'] ?>" name="visited<?php echo  $rowFacturasSinLiquidar['id'] ?>" dis /> 
				</td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
	<table class="nover" align="center" width="245" border="0">
		<tr>
			<td width="239">
				<div id="paginador" class="pager">
					<form>
						<p align="center">
						<img src="../img/first.png" width="16" height="16" class="first"/> <img src="../img/prev.png" width="16" height="16" class="prev"/>
						<input name="text" type="text" class="pagedisplay" style="background:#CCCCCC; text-align:center" size="8" readonly="readonly"/>
						<img src="../img/next.png" width="16" height="16" class="next"/> <img src="../img/last.png" width="16" height="16" class="last"/>
						<select name="select" class="pagesize">
							<option selected="selected" value="15">15 por pagina</option>
							<option value="30">30 por pagina</option>
							<option value="60">60 por pagina</option>
							<option value="<?php echo $totalfacturas;?>">Todas</option>
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
