<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
if(isset($_GET)) {
	$codigoprestador = $_GET['prestador'];
	$nrocomprobante = $_GET['comprobante'];

	$sqlLeePrestador="SELECT codigoprestador, nombre, cuit, personeria FROM prestadores WHERE codigoprestador = $codigoprestador";
	$resLeePrestador=mysql_query($sqlLeePrestador,$db);
	$rowLeePrestador=mysql_fetch_array($resLeePrestador);
	$prestador = $rowLeePrestador['nombre'].' | CUIT: '.$rowLeePrestador['cuit'].' | Codigo: '.$rowLeePrestador['codigoprestador'];
	$personeria = $rowLeePrestador['personeria'];

	if($personeria==4) {
		$sqlLeeEstablecimientos="SELECT codigo, nombre FROM establecimientos WHERE codigoprestador = $codigoprestador";
		$resLeeEstablecimientos=mysql_query($sqlLeeEstablecimientos,$db);
	}

	$sqlConsultaTipoComprobante = "SELECT * FROM tipocomprobante";
	$resConsultaTipoComprobante = mysql_query($sqlConsultaTipoComprobante,$db);
	
	$sqlConsultaCodigoAutorizacion = "SELECT * FROM codigoautorizacion";
	$resConsultaCodigoAutorizacion = mysql_query($sqlConsultaCodigoAutorizacion,$db);
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modulo Facturas :.</title>
<link rel="stylesheet" href="/madera/lib/jquery-ui-1.9.2.custom/css/smoothness/jquery-ui-1.9.2.custom.css"/>
<script src="/madera/lib/jquery-ui-1.9.2.custom/js/jquery-1.8.3.js" type="text/javascript"></script>
<script src="/madera/lib/jquery-ui-1.9.2.custom/js/jquery-ui-1.9.2.custom.js" type="text/javascript"></script>
<script src="/madera/lib/jquery-ui-1.9.2.custom/js/ui.datepicker-es.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
	$("#idEstablecimiento").attr('disabled', true);
	$.datepicker.setDefaults($.datepicker.regional['es']);
	$("#fecharecepcion").mask("99/99/9999");
	$("#fecharecepcion").datepicker({
		firstDay: 1,
		maxDate: "+0d",
		showButtonPanel: true,
		showOn: "button",
		buttonImage: "../img/calendar.png",
		buttonImageOnly: true,
		buttonText: "Seleccione la fecha",
		changeMonth: true,
		changeYear: true
    });
	$("#fecharecepcion").change(function(){
		var dias = $("#diasvencimiento").val();
		if(dias == '') {
			dias = '30';
		};
		$("#diasvencimiento").val(dias);
		var fecha = $("#fecharecepcion").datepicker("getDate");
		fecha.setDate(fecha.getDate() + parseInt(dias));
		$("#fechavencimiento").datepicker("setDate", fecha);
	});
	$("#fechacomprobante").mask("99/99/9999");
	$("#fechacomprobante").datepicker({
		firstDay: 1,
		maxDate: "+0d",
		showButtonPanel: true,
		showOn: "button",
		buttonImage: "../img/calendar.png",
		buttonImageOnly: true,
		buttonText: "Seleccione la fecha",
		changeMonth: true,
		changeYear: true
    });
	$("#nroautorizacion").mask("99999999999999");
	$("#fechacorreo").mask("99/99/9999");
	$("#fechacorreo").datepicker({
		firstDay: 1,
		maxDate: "+0d",
		showButtonPanel: true,
		showOn: "button",
		buttonImage: "../img/calendar.png",
		buttonImageOnly: true,
		buttonText: "Seleccione la fecha",
		changeMonth: true,
		changeYear: true
    });
	$("#diasvencimiento").change(function(){
		var dias = $(this).val();
		if(dias == '') {
			dias = '30';
		};
		$("#diasvencimiento").val(dias);
		var fecha = $("#fecharecepcion").datepicker("getDate");
		fecha.setDate(fecha.getDate() + parseInt(dias));
		$("#fechavencimiento").datepicker("setDate", fecha);
	});
	$("#fechavencimiento").mask("99/99/9999");
	$("#fechavencimiento").datepicker({
		firstDay: 1,
		showButtonPanel: true,
		showOn: "button",
		buttonImage: "../img/calendar.png",
		buttonImageOnly: true,
		buttonText: "Seleccione la fecha",
		changeMonth: true,
		changeYear: true
    });
	if($("#personeria").val()==4) {
		$("#idEstablecimiento").attr('disabled', false);
	};
});
function validar(formulario) {
	formulario.ingresar.disabled = true;
	if (formulario.idTipocomprobante.options[formulario.idTipocomprobante.selectedIndex].value == "") {
		var cajadialogo = $('<div title="Aviso"><p>Debe seleccionar el Tipo de Comprobante.</p></div>');
		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#idTipocomprobante').focus(); }});
		formulario.ingresar.disabled = false;
		return false;
	}
	if(formulario.fechacomprobante.value == "") {
		var cajadialogo = $('<div title="Aviso"><p>Debe ingresar la Fecha del Comprobante.</p></div>');
		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#fechacomprobante').focus(); }});
		formulario.ingresar.disabled = false;
		return false;
	} else {
		if(!FechaValida(formulario.fechacomprobante.value)) {
			var cajadialogo = $('<div title="Aviso"><p>La Fecha de Comprobante ingresada no es válida.</p></div>');
			cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#fechacomprobante').focus(); }});
			formulario.ingresar.disabled = false;
			return false;
		}
	}
	if (formulario.idCodigoautorizacion.options[formulario.idCodigoautorizacion.selectedIndex].value == "") {
		var cajadialogo = $('<div title="Aviso"><p>Debe seleccionar el Tipo de Autorizacion AFIP.</p></div>');
		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#idCodigoautorizacion').focus(); }});
		formulario.ingresar.disabled = false;
		return false;
	}
	if(formulario.nroautorizacion.value == "") {
		var cajadialogo = $('<div title="Aviso"><p>Debe ingresar un Nro. de Autorizacion AFIP.</p></div>');
		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#nroautorizacion').focus(); }});
		formulario.ingresar.disabled = false;
		return false;
	} else {
		if(formulario.nroautorizacion.value == "00000000000000") {
			var cajadialogo = $('<div title="Aviso"><p>El Nro. de Autorizacion AFIP ingresado no es válido.</p></div>');
			cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#nroautorizacion').focus(); }});
			formulario.ingresar.disabled = false;
			return false;
		}
	}
	if(formulario.fecharecepcion.value == "") {
		var cajadialogo = $('<div title="Aviso"><p>Debe ingresar la Fecha de Recepción del comprobante.</p></div>');
		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#fecharecepcion').focus(); }});
		formulario.ingresar.disabled = false;
		return false;
	} else {
		if(!FechaValida(formulario.fecharecepcion.value)) {
			var cajadialogo = $('<div title="Aviso"><p>La Fecha de Recepción ingresada no es válida.</p></div>');
			cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#fecharecepcion').focus(); }});
			formulario.ingresar.disabled = false;
			return false;
		}
	}
	if(formulario.fechacorreo.value == "") {
		var cajadialogo = $('<div title="Aviso"><p>Debe ingresar la Fecha del Correo.</p></div>');
		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#fechacorreo').focus(); }});
		formulario.ingresar.disabled = false;
		return false;
	} else {
		if(!FechaValida(formulario.fechacorreo.value)) {
			var cajadialogo = $('<div title="Aviso"><p>La Fecha de Correo ingresada no es válida.</p></div>');
			cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#fechacorreo').focus(); }});
			formulario.ingresar.disabled = false;
			return false;
		}
	}
	if(!isNumberPositivo(formulario.diasvencimiento.value)) {
		var cajadialogo = $('<div title="Aviso"><p>Los dias para el vencimiento ingresados no son válidos.</p></div>');
		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#diasvencimiento').focus(); }});
		formulario.ingresar.disabled = false;
		return false;
	}
	if(formulario.fechavencimiento.value == "") {
		var cajadialogo = $('<div title="Aviso"><p>Debe ingresar la Fecha de Vencimiento.</p></div>');
		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#fechavencimiento').focus(); }});
		formulario.ingresar.disabled = false;
		return false;
	} else {
		if(!FechaValida(formulario.fechavencimiento.value)) {
			var cajadialogo = $('<div title="Aviso"><p>La Fecha de Vencimiento ingresada no es válida.</p></div>');
			cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#fechavencimiento').focus(); }});
			formulario.ingresar.disabled = false;
			return false;
		}
	}
	if(formulario.importecomprobante.value == "") {
		var cajadialogo = $('<div title="Aviso"><p>Debe ingresar el Importe del comprobante.</p></div>');
		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#importecomprobante').focus(); }});
		formulario.ingresar.disabled = false;
		return false;
	} else {
		if(!isNumberPositivo(formulario.importecomprobante.value)) {
			var cajadialogo = $('<div title="Aviso"><p>El Importe del comprobante ingresado no es válido.</p></div>');
			cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#importecomprobante').focus(); }});
			formulario.ingresar.disabled = false;
			return false;
		}
	}
	if(formulario.personeria.value == 4) {
		if (formulario.idEstablecimiento.options[formulario.idEstablecimiento.selectedIndex].value == "") {
			var cajadialogo = $('<div title="Aviso"><p>El Prestador es Entidad Agrupadora. Debe seleccionar el Establecimiento Efector de la Prestacion.</p></div>');
			cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#idEstablecimiento').focus(); }});
			formulario.ingresar.disabled = false;
			return false;
		}
	}
	$.blockUI({ message: "<h1>Ingresando comprobante... <br>Esto puede tardar unos segundos.<br> Aguarde por favor</h1>" });
	return true;
};

function FechaValida(fecha){
	if (fecha != undefined && fecha.value != "" ){
        var dia  =  parseInt(fecha.substring(0,2),10);
        var mes  =  parseInt(fecha.substring(3,5),10);
        var anio =  parseInt(fecha.substring(6),10);
		switch(mes){
			case 1:
			case 3:
			case 5:
			case 7:
			case 8:
			case 10:
			case 12:
            	numDias=31;
            break;
			case 4: case 6: case 9: case 11:
            	numDias=30;
            break;
			case 2:
				if(comprobarSiBisisesto(anio)){ numDias=29 } else { numDias=28 };
            break;
			default:
            return (false);
		}
		if (dia>numDias || dia==0){
			return (false);
		}
		return true;
    }
}
</script>
</head>
<body bgcolor="#CCCCCC">
<div align="center">
	<input type="reset" name="volver" value="Volver" onclick="location.href = 'moduloFacturas.php'" />
</div>
<div align="center">
	<h1>Ingresar Nuevo  Comprobante</h1>
</div>
<form name="ingresarFactura" id="ingresarFactura" method="post" onsubmit="return validar(this)" action="guardarIngresarFactura.php">
	<div align="center">
		<table border="0">
			<tr>
				<td align="right">Prestador</td>
				<td colspan="5"><textarea name="prestador" rows="3" cols="100" id="prestador" readonly="readonly" style="background:#CCCCCC"><?php echo $prestador;?></textarea>
				<input name="idPrestador" type="hidden" id="idPrestador" size="5" value="<?php echo $codigoprestador;?>"/>
				<input name="personeria" type="hidden" id="personeria" size="2" value="<?php echo $personeria;?>"/>
				</td>
			</tr>
			<tr>
				<td align="right">Tipo</td>
				<td><select name="idTipocomprobante" id="idTipocomprobante">
					<option title="Seleccione un valor" value="">Seleccione un valor</option>
					<?php 
						while($rowConsultaTipoComprobante = mysql_fetch_array($resConsultaTipoComprobante)) { 	
							echo "<option title ='$rowConsultaTipoComprobante[descripcion]' value='$rowConsultaTipoComprobante[id]'>".$rowConsultaTipoComprobante['descripcion']."</option>";
						}
					?>
					</select>
				</td>
				<td align="right">Nro.</td>
				<td><input name="numero" type="text" id="numero" size="10" readonly="readonly" style="background:#CCCCCC" value="<?php echo $nrocomprobante;?>"/></td>
				<td align="right" valign="bottom">Fecha</td>
				<td><input name="fechacomprobante" type="text" id="fechacomprobante" size="6" value=""/></td>
			</tr>
			<tr>
				<td align="right">Autorizacion AFIP</td>
				<td colspan="5"><select name="idCodigoautorizacion" id="idCodigoautorizacion">
					<option title="Seleccione un valor" value="">Seleccione un valor</option>
					<?php 
						while($rowConsultaCodigoAutorizacion = mysql_fetch_array($resConsultaCodigoAutorizacion)) { 	
							echo "<option title ='$rowConsultaCodigoAutorizacion[descripcioncorta]' value='$rowConsultaCodigoAutorizacion[id]'>".$rowConsultaCodigoAutorizacion['descripcioncorta']."</option>";
						}
					?>
					</select>
					<input name="nroautorizacion" type="text" id="nroautorizacion" size="12" value=""/></td>
			</tr>
			<tr>
				<td align="right" valign="bottom">Fecha Recepcion</td>
				<td><input name="fecharecepcion" type="text" id="fecharecepcion" size="6" value=""/></td>
				<td align="right" valign="bottom">Fecha de Correo</td>
				<td><input name="fechacorreo" type="text" id="fechacorreo" size="6" value=""/></td>
				<td align="right" valign="bottom">Vencimiento</td>
				<td><input name="diasvencimiento" type="text" id="diasvencimiento" size="1" maxlength="3" value=""/> dias <input name="fechavencimiento" type="text" id="fechavencimiento" size="6" value=""/></td>
			</tr>
			<tr>
				<td align="right">Importe</td>
				<td colspan="5"><input name="importecomprobante" type="text" id="importecomprobante" size="10" maxlength="9" value=""/></td>
			</tr>
			<tr>
				<td align="right">Est. Efector de Prestacion</td>
				<td colspan="5"><select name="idEstablecimiento" id="idEstablecimiento">
					<option title="Seleccione un valor" value="">Seleccione un valor</option>
					<?php 
						if($personeria==4) {
							while($rowLeeEstablecimientos = mysql_fetch_array($resLeeEstablecimientos)) { 	
								echo "<option title ='$rowLeeEstablecimientos[nombre]' value='$rowLeeEstablecimientos[codigo]'>".$rowLeeEstablecimientos['nombre']."</option>";
							}
						}
					?>
					</select>
					</td>
			</tr>
		</table>
	</div>
<div align="center">
	<p><input name="ingresar" type="submit" id="ingrear" value="Ingresar"/></p>
</div>
</form>
</body>
</html>