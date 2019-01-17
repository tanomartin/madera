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
	if (formulario.dirigidoa.value == "") {
		alert("El campo Dirigido A es Obligatrio");
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
		if (!esEnteroPositivo(formulario.telefono1.value)) {
			alert("El telefono debe ser un numero");
			return false;
		}
	}
	if (formulario.email.value != "") {
		if (!esCorreoValido(formulario.email1.value)){
			alert("Email Primario invalido");
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
	<p><input type="button" name="volver" value="Volver" onclick="location.href = 'menuBeneficiario.php'" /></p>
	<form name="nuevoPrestador" id="nuevoPrestador" method="post" onsubmit="return validar(this)" action="guardarNuevoBeneficiario.php">
		<h3>Nuevo Beneficiario Ordenes de Pago </h3>
    	<table border="0">
      		<tr>
        		<td><b>Nombre</b></td>
        		<td colspan="2"><input name="nombre" type="text" id="nombre" size="100" /></td>
     	 	</tr>
     	 	<tr>
        		<td><b>Dirigido A</b></td>
        		<td colspan="2"><input name="dirigidoa" type="text" id="dirigidoa" size="100" /></td>
     	 	</tr>
     		<tr>
        		<td><b>Domicilio</b></td>
        		<td colspan="2"><input name="domicilio" type="text" id="domicilio" size="100" /></td>
      		</tr>
      		<tr>
        		<td><b>C.P.</b></td>
        		<td colspan="2">
	          		<input style="background-color:#CCCCCC" readonly="readonly" name="indpostal" id="indpostal" type="text" size="1"/>
	          		-<input name="codPos" type="text" id="codPos" size="7" />-<input name="alfapostal"  id="alfapostal" type="text" size="3"/>
	   			</td>
	   		</tr>
	   		<tr>
        		<td><b>Localidad</b></td>
		        <td>
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
        		<td><div align="left"><input name="telefono" type="text" id="telefono" size="15" /></div></td>
        		<td>
        			<div align="left">
        				<b>Email </b><input name="email" type="text" id="email" size="60" />
       	 			</div>
       	 		</td>
      		</tr>
    	</table>
    	<p><input type="submit" name="Submit" id="Submit" value="Guardar" /></p>
	</form>
</div>
</body>
</html>
