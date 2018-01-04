<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Minutas USIMRA :.</title>
<style>
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
jQuery(function($){
	$("#fecha").mask("99-99-9999");
});


function cargarContadores(objeto) {
	
}

function validar(formulario) {

	if (!esFechaValida(formulario.fecha.value)) {
		alert("La Fecha de la minúta no es valida");
		return false;
	}
	
	if (formulario.asiento.value != '') {
		if (!isNumberPositivo(formulario.asiento.value)) {
			alert("El Asiente debe ser un número entero positvo o vació");
			return false;
		}
	}

	if (formulario.cuenta.value == '') {
		alert("La cuenta es obligatorio");
		return false;
	}

	if (!isNumber(formulario.importe.value) || formulario.importe.value == '') {
		alert("El importe debe ser un número positvo");
		return false;
	}

	if (formulario.detalle.value == '') {
		alert("El Detalle es obligatorio");
		return false;
	} else {
		maximo_lineas = 15;
		lineas=formulario.detalle.value.split("\n");
		if(lineas.length > maximo_lineas){
			alert("El Detalle tiene como máximo 15 lineas");
			return false;
		} else {
			maximo_linea = 70;	
			for (var i = 0; i < lineas.length; i++) {
				if (lineas[i].length > maximo_linea) {
					var nroLinea = i + 1;
					alert("La linea "+nroLinea+" del Detalle debe tener como máximo 70 caracteres");
					return false;
				}
			}
		}
	}	

	if (formulario.debe.value != '') {
		maximo_lineas = 3;
		lineas=formulario.debe.value.split("\n");
		if(lineas.length > maximo_lineas){
			alert("El Debe tiene como máximo 3 lineas");
			return false;
		} else {
			maximo_linea = 60;	
			for (var i = 0; i < lineas.length; i++) {
				if (lineas[i].length > maximo_linea) {
					var nroLinea = i + 1;
					alert("La linea "+nroLinea+" del Debe debe tener como máximo 60 caracteres");
					return false;
				}
			}
		}
		
	}	

	if (formulario.haber.value != '') {
		maximo_lineas = 3;
		lineas=formulario.haber.value.split("\n");
		if(lineas.length > maximo_lineas){
			alert("El Haber tiene como máximo 3 lineas");
			return false;
		} else {
			maximo_linea = 60;	
			for (var i = 0; i < lineas.length; i++) {
				if (lineas[i].length > maximo_linea) {
					var nroLinea = i + 1;
					alert("La linea "+nroLinea+" del Haber debe tener como máximo 60 caracteres");
					return false;
				}
			}
		}
	}
	
	window.open("", "formpopup", "width=800,height=570");
	formulario.target = 'formpopup';
	
}

</script>

</head>

<body bgcolor="#B2A274">
	<div align="center">
		<p><span class="Estilo2">Minutas Contables</span></p>
	  	<form id="minutasContables" name="minutasContables" method="post" action="generaMinuta.php" onsubmit="return validar(this)">
	  		<table>
	  			<tr>
	  				<td>Fecha</td>
	  				<td><input id="fecha" name="fecha" type="text" size="6"/></td>
	  			</tr>
	  			<tr>
	  				<td>Asiento Nº</td>
	  				<td><input id="asiento" name="asiento" type="text"/></td>
	  			</tr>
	  			<tr>
	  				<td>Cuenta</td>
	  				<td><input id="cuenta" name="cuenta" type="text"/></td>
	  			</tr>
	  			<tr>
	  				<td>Cheque Nº</td>
	  				<td><input id="cheque" name="cheque" type="text"/></td>
	  			</tr>
	  			<tr>
	  				<td></td>
	  				<td>
	  					<input type="radio" name="tipo" value="deposito"/> Depósito
	  					<input type="radio" name="tipo" value="debito"/> Débito
	  					<input type="radio" name="tipo" value="credito"/> Crédito
	  				</td>
	  			</tr>
	  			<tr>
	  				<td>Importe</td>
	  				<td><b>$</b> <input id="importe" name="importe" type="text" size="18"/></td>
	  			</tr>
	  			<tr>
	  				<td>Detalle <br />(15 Lineas) </td>
	  				<td><textarea style="resize:none;" name="detalle" id="detalle" cols="71" rows="18"></textarea>  </td>
	  			</tr>
	  			<tr>
	  				<td>Cuenta Debe <br /> (3 Lineas)</td>
	  				<td><textarea style="resize:none;" name="debe" id="debe" cols="61" rows="4"></textarea> </td>
	  			</tr>
	  			<tr>
	  				<td>Cuenta Haber <br /> (3 Lineas)</td>
	  				<td>
	  					<textarea style="resize:none;" name="haber" id="haber" cols="61" rows="4"></textarea>  
	  				</td>
	  			</tr>
	  		</table>
	  		<p><input type="submit" value="Vista Previa"/></p>
	  	</form>
	</div>
</body>
</html>
