<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
$codigo = $_GET['codigo'];
$sqlConsultaPresta = "SELECT p.*, l.nomlocali as localidad, r.descrip as provincia
						FROM prestadores p
						LEFT JOIN localidades l on p.codlocali = l.codlocali
						LEFT JOIN provincia r on p.codprovin = r.codprovin
						WHERE p.codigoprestador = $codigo and personeria = 5";
$resConsultaPresta = mysql_query($sqlConsultaPresta,$db);
$rowConsultaPresta = mysql_fetch_assoc($resConsultaPresta); ?>

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
	<form name="nuevoPrestador" id="nuevoPrestador" method="post" onsubmit="return validar(this)" action="guardarModificarPrestador.php">
		<h3>Modificar Prestador No Médico </h3>
    	<table>
    		<tr>
        		<td><b>Codigo</b></td>
        		<td colspan="3"><input readonly="readonly" style="background:#CCCCCC" name="codigo" type="text" id="codigo" size="5" value="<?php echo $rowConsultaPresta['codigoprestador'] ?>" /></td>
     	 	</tr>
      		<tr>
        		<td><b>Nombre</b></td>
        		<td colspan="3"><input name="nombre" type="text" id="nombre" size="100" value="<?php echo $rowConsultaPresta['nombre'] ?>" /></td>
     	 	</tr>
     	 	<tr>
     	 		<td><b>C.U.I.T.</b></td>
     	 		<td><input name="cuit" type="text" id="cuit" size="10" value="<?php echo $rowConsultaPresta['cuit'] ?>"/>
     	 			<span id="errorCuit" style="color:#FF0000;font-weight: bold;"></span>	</td>
     	 	</tr>
     		<tr>
        		<td><b>Domicilio</b></td>
        		<td colspan="3"><input name="domicilio" type="text" id="domicilio" size="100" value="<?php echo $rowConsultaPresta['domicilio'] ?>" /></td>
      		</tr>
      		<tr>
        		<td><b>C.P.</b></td>
        		<td>
	          		<input style="background-color:#CCCCCC" readonly="readonly" name="indpostal" id="indpostal" type="text" size="1" value="<?php echo $rowConsultaPresta['indpostal'] ?>"/>
	          		-<input name="codPos" type="text" id="codPos" size="7" value="<?php echo $rowConsultaPresta['numpostal'] ?>"/>
	          		-<input name="alfapostal"  id="alfapostal" type="text" size="3" value="<?php echo $rowConsultaPresta['alfapostal'] ?>"/>
	   			</td>
	   			<td><b>Localidad</b>
	   				<select name="selectLocali" id="selectLocali">
				        <option value="0">Seleccione un valor </option>
				        <option value="<?php echo $rowConsultaPresta['codlocali'] ?>" selected="selected"><?php echo $rowConsultaPresta['localidad'] ?></option>
				    </select>
	   			</td>
	   			<td><b>Provincia</b>
	   				<input readonly="readonly" style="background-color:#CCCCCC" name="provincia" type="text" id="provincia" value="<?php echo $rowConsultaPresta['provincia'] ?>"/>
	           		<input style="background-color:#CCCCCC; visibility:hidden " readonly="readonly" name="codprovin" id="codprovin" type="text" size="2" value="<?php echo $rowConsultaPresta['codprovin'] ?>"/>
        		</td>
	   		</tr>
      		<tr>
        		<td><b>Telefono</b></td>
        		<td><div align="left"><input name="telefono" type="text" id="telefono" size="20" value="<?php echo $rowConsultaPresta['telefono1'] ?>"/></div></td>
        		<td><b>Telefono 1</b>	
        			<input name="telefono1" type="text" id="telefono1" size="20" value="<?php echo $rowConsultaPresta['telefono2'] ?>"/></td>
        		<td>
        			<b>Tel. Fax</b>	
        			<input name="telfax" type="text" id="telfax" size="20" value="<?php echo $rowConsultaPresta['telefonofax'] ?>"/>
        		</td>
      		</tr>
      		<tr>
      			<td><b>Email </b></td>
        		<td colspan="3"><input name="email" type="text" id="email" size="60" value="<?php echo $rowConsultaPresta['email1'] ?>"/></td>
       	 	</tr>
       	 	<tr>
      			<td><b>Email Sec. </b></td>
       	 		<td colspan="3"><input name="email1" type="text" id="email" size="60" value="<?php echo $rowConsultaPresta['email2'] ?>"/></td>
       	 	</tr>
    	</table>
    	<p><input type="submit" name="Submit" id="Submit" value="Guardar" /></p>
	</form>
</div>
</body>
</html>
