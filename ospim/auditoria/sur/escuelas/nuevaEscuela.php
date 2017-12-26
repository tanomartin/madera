<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Alta Escuela :.</title>
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
			url: "../lib/localidadPorCP.php",
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
			url: "../lib/cambioProvincia.php",
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
	if (formulario.cue.value == "") {
		alert("El campo CUE es Obligatrio");
		return false;
	} else {
		if (!esEnteroPositivo(formulario.cue.value)){
		 	alert("El campo CUE tiene que ser numerico");
			return false;
		}
	}
	if (formulario.codPos.value != "") {
		if (!esEnteroPositivo(formulario.codPos.value)){
		 	alert("El campo Codigo Postal tiene que ser numerico");
			return false;
		}
		if (formulario.domicilio.value == "") {
			alert("El campo domicilio es obligatrio, si se ingresa un codigo postal");
			return false;
		}
		if (formulario.selectLocali.options[formulario.selectLocali.selectedIndex].value == 0) {
			alert("Debe elegir una Localidad, si se ingresa una direccion");
			return false;
		}
	}
	if (formulario.email.value != "") {
		if (!esCorreoValido(formulario.email.value)){
			alert("Email invalido");
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
  <h3>Nueva Escuela </h3>
  <form name="nuevaEscuela" id="nuevaEscuela" method="post" onsubmit="return validar(this)" action="guardarNuevaEscuela.php">
    <table border="0">
      <tr>
        <td><div align="right"><strong>Nombre</strong></div></td>
        <td colspan="5"><div align="left"><input name="nombre" type="text" id="nombre" size="90" /></div></td>
      </tr>
       <tr>
        <td><div align="right"><strong>C.U.E.</strong></div></td>
        <td colspan="5"><div align="left"><input name="cue" type="text" id="cue" size="10" /></div></td>
      </tr>
      <tr>
      <tr>
        <td><div align="right"><strong>Domicilio</strong></div></td>
        <td colspan="5"><div align="left"><input name="domicilio" type="text" id="domicilio" size="90" /></div></td>
      </tr>
        <td><div align="right"><strong>Codigo Postal</strong></div></td>
        <td><div align="left">
          <input style="background-color:#CCCCCC" readonly="readonly" name="indpostal" id="indpostal" type="text" size="1"/>
          -<input name="codPos" type="text" id="codPos" size="7" />-<input name="alfapostal"  id="alfapostal" type="text" size="3"/></div></td>
        <td><div align="left"><strong>Localidad</strong>
          <select name="selectLocali" id="selectLocali">
            <option value="0">Seleccione un valor </option>
          </select>
        </div></td>
        <td><div align="left"><strong>Provincia</strong>
          <input readonly="readonly" style="background-color:#CCCCCC" name="provincia" type="text" id="provincia" />
  		  <input style="background-color:#CCCCCC; visibility:hidden " readonly="readonly" name="codprovin" id="codprovin" type="text" size="2"/>
       </div></td>
      </tr> 
      <tr>
        <td><div align="right"><strong>Telefono </strong></div></td>
        <td><div align="left"><input name="telefono" type="text" id="telefono" size="20" /></div></td>
        <td colspan="4"><div align="left"><strong>Email</strong><input name="email" type="text" id="email" size="40" /></div></td>
      </tr>
    </table>
    <p><input type="submit" name="Submit" id="Submit" value="Guardar" /></p>
  </form>
  </div>
</body>
</html>