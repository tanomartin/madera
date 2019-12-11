<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Alta Beneficiario Orden de Pago :.</title>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">

jQuery(function($){
	$("#cuit").mask("99999999999");

	$("#cuit").change(function(){
		var cuit = $(this).val();
		$.ajax({
			type: "POST",
			dataType: "json",
			url: "lib/existePrestaCuit.php",
			data: {cuit:cuit},
		}).done(function(respuesta){
			if (respuesta != 0) {
				$("#errorCuit").html("El C.U.I.T. '" + cuit + "' ya existe (Codigo Prestador '"+ respuesta +"')");
				$("#cuit").val("");
			} else {
				$("#errorCuit").html("");
			} 
		});
	});
	
	$("#codPos").change(function(){
		var codigo = $(this).val();
		$.ajax({
			type: "POST",
			dataType: 'html',
			url: "lib/localidadPorCP.php",
			data: {codigo:codigo},
		}).done(function(respuesta){
			$("#selectLocali").html(respuesta);
			$("#indpostal").val("");
			$("#provincia").val("");
			$("#codprovin").val("");
		});
	});

	$("#selectLocali").change(function(){
		var locali = $(this).val();
		$.ajax({
			type: "POST",
			dataType: "json",
			url: "lib/cambioProvincia.php",
			data: {locali:locali},
		}).done(function(respuesta){
			$("#indpostal").val(respuesta.indpostal);
			$("#provincia").val(respuesta.descrip);
			$("#codprovin").val(respuesta.codprovin);
		});
	});

});

function validar(formulario) {	
	if (formulario.nombre.value == "") {
		alert("El campo Nombre es Obligatrio");
		return false;
	}
	if (!verificaCuilCuit(formulario.cuit.value)){
		alert("C.U.I.T invalido");
		return false;
	}
	if (formulario.domicilio.value == "") {
		alert("El campo domicilio es obligatrio");
		return false;
	}	
	if (formulario.codPos.value == "") {
		alert("El campo Codigo Postal es obligatrio");
		return false;
	} else {
		if (!esEnteroPositivo(formulario.codPos.value)){
		 	alert("El campo Codigo Postal tiene que ser numerico");
			return false;
		}
	}
	if (formulario.selectLocali.options[formulario.selectLocali.selectedIndex].value == 0) {
		alert("Debe elegir una Localidad");
		return false;
	}
	if (formulario.telefono.value != "") {
		if (!esEnteroPositivo(formulario.telefono.value)) {
			alert("El telefono debe ser un numero");
			return false;
		}
	}
	if (formulario.telefono1.value != "") {
		if (!esEnteroPositivo(formulario.telefono1.value)) {
			alert("El telefono 1 debe ser un numero");
			return false;
		}
	}
	if (formulario.telfax.value != "") {
		if (!esEnteroPositivo(formulario.telfax.value)) {
			alert("El telefono Fax debe ser un numero");
			return false;
		}
	}
	if (formulario.email.value != "") {
		if (!esCorreoValido(formulario.email.value)){
			alert("Email Primario invalido");
			return false;
		}
	}
	if (formulario.email2.value != "") {
		if (!esCorreoValido(formulario.email2.value)){
			alert("Email Secundario invalido");
			return false;
		}
	}
	formulario.Submit.disabled = true;
	return true;
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = 'menuPrestadores.php'" /></p>
	<form name="nuevoPrestador" id="nuevoPrestador" method="post" onsubmit="return validar(this)" action="guardarNuevoPrestador.php">
		<h3>Nuevo Prestador No Médico </h3>
    	<table>
      		<tr>
        		<td><b>Nombre</b></td>
        		<td colspan="3"><input name="nombre" type="text" id="nombre" size="100" /></td>
        		
     	 	</tr>
     	 	<tr>
     	 		<td><b>C.U.I.T. </b></td>
     	 		<td colspan="3">
     	 			<input name="cuit" type="text" id="cuit" size="10" />
     	 			<span id="errorCuit" style="color:#FF0000;font-weight: bold;"></span>	
     	 		</td>
     	 	</tr>
     		<tr>
        		<td><b>Domicilio</b></td>
        		<td colspan="3"><input name="domicilio" type="text" id="domicilio" size="100" /></td>
      		</tr>
      		
      		<tr>
        		<td><b>C.P.</b></td>
        		<td>
	          		<input style="background-color:#CCCCCC" readonly="readonly" name="indpostal" id="indpostal" type="text" size="1"/>
	          		-<input name="codPos" type="text" id="codPos" size="7" />-<input name="alfapostal"  id="alfapostal" type="text" size="3"/>
		        </td>
		        <td>	
		        	<b>Localidad</b>
		        	<select name="selectLocali" id="selectLocali">
		            		<option value="0">Seleccione una localidad </option>
		          	</select>
		        </td>
		        <td>
		          	<b>Provincia</b>
	          		<input readonly="readonly" style="background-color:#CCCCCC" name="provincia" type="text" id="provincia" />
	           		<input style="background-color:#CCCCCC; visibility:hidden " readonly="readonly" name="codprovin" id="codprovin" type="text" size="2"/>
        		</td>
	   		</tr>
      		<tr>
        		<td><b>Telefono</b></td>
        		<td><input name="telefono" type="text" id="telefono" size="20" /></td>
        		<td><b>Telefono 1</b>	
        			<input name="telefono1" type="text" id="telefono1" size="20" /></td>
        		<td>
        			<b>Tel. Fax</b>	
        			<input name="telfax" type="text" id="telfax" size="20" />
        		</td>
        	</tr>
        	<tr><td><b>Email </b></td>
        		<td colspan="3"><input name="email" type="text" id="email" size="60" /></td>
        	</tr>
        	<tr>
        		<td><b>Email Sec. </b></td>
        		<td colspan="3"><input name="email2" type="text" id="email2" size="60" /></td>
      		</tr>
    	</table>
    	<p><input type="submit" name="Submit" id="Submit" value="Guardar" /></p>
	</form>
</div>
</body>
</html>
