<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$codigopresta = $_GET['codigopresta'];
$sqlConsultaPresta = "SELECT codigoprestador, nombre FROM prestadores WHERE codigoprestador = $codigopresta";
$resConsultaPresta = mysql_query($sqlConsultaPresta,$db);
$rowConsultaPresta = mysql_fetch_assoc($resConsultaPresta); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Alta Establecimientos :.</title>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">

jQuery(function($){
	$("#fechadesde").mask("99-99-9999");
	$("#fechahasta").mask("99-99-9999");
	
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

function habilitaCalidad(valor) {
	habilitaFecha(0);
	var calidadSI = document.getElementById("calidadSI");
	var calidadNO = document.getElementById("calidadNO");
	calidadSI.checked = "";
	calidadNO.checked = "checked";
	calidadSI.disabled = true;
	calidadNO.disabled = true;
	if (valor == 1) {
		calidadSI.disabled = false;
		calidadNO.disabled = false;
	}
}

function habilitaFecha(valor) {
	var fechadesde = document.getElementById("fechadesde");
	var fechahasta = document.getElementById("fechahasta");
	fechadesde.value = "";
	fechahasta.value = "";
	fechadesde.disabled = true;
	fechahasta.disabled = true;
	if(valor == 1) {
		fechadesde.disabled = false;
		fechahasta.disabled = false;
	}
}

function validar(formulario) {
	if (formulario.nombre.value == "") {
		alert("El campo Nombre es Obligatrio");
		return false;
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
	if (formulario.telefono1.value != "") {
		if (!esEnteroPositivo(formulario.telefono1.value)) {
			alert("El telefono 1 debe ser un numero");
			return false;
		}
	}
	if (formulario.ddn1.value != "") {
		if (!esEnteroPositivo(formulario.ddn1.value)) {
			alert("El codigo de area 1 debe ser un numero");
			return false;
		}
	}
	if (formulario.telefono2.value != "") {
		if (!esEnteroPositivo(formulario.telefono2.value)) {
			alert("El telefono 2 debe ser un numero");
			return false;
		}
	}
	if (formulario.ddn2.value != "") {
		if (!esEnteroPositivo(formulario.ddn2.value)) {
			alert("El codigo de area 2 debe ser un numero");
			return false;
		}
	}
	if (formulario.telefonofax.value != "") {
		if (!esEnteroPositivo(formulario.telefonofax.value)) {
			alert("El telefono 2 debe ser un numero");
			return false;
		}
	}
	if (formulario.ddnfax.value != "") {
		if (!esEnteroPositivo(formulario.ddnfax.value)) {
			alert("El codigo de area fax debe ser un numero");
			return false;
		}
	}
	if (formulario.email.value != "") {
		if (!esCorreoValido(formulario.email.value)){
			alert("Email invalido");
			return false;
		}
	}
	if (formulario.calidad.value == 1) {
		var fechadesde = formulario.fechadesde.value;
		var fechahasta = formulario.fechahasta.value;
		if (!esFechaValida(fechadesde)) {
			alert("La Fecha Desde de la acreditacion no de calidad no es valida");
			return false
		}
		if (fechahasta != "") {
			if (!esFechaValida(fechahasta)) {
				alert("La Fecha Hasta de la acreditacion no de calidad no es valida");
				return false
			}
		}
		fechaInicio = new Date(invertirFecha(fechadesde));
		if (fechahasta != "") {
			fechaFin = new Date(invertirFecha(fechahasta));
			if (fechaInicio >= fechaFin) {
				alert("La Fecha Desde debe ser superior a la Fecha de Hasta");
				return false ;
			}
		}
	}
	formulario.Submit.disabled = true;
	return true;
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input class="nover" type="button" name="volver" value="Volver" onclick="location.href = 'modificarEstablecimientos.php?codigo=<?php echo $codigopresta ?>'" /></p> 
  <h3>Nuevo Establecimientos </h3>
  <table width="500" border="1" style="margin-bottom: 20px">
    <tr>
      <td width="163"><div align="right"><strong>C&oacute;digo</strong></div></td>
      <td width="321"><div align="left"><strong><?php echo $rowConsultaPresta['codigoprestador']  ?></strong></div></td>
    </tr>
    <tr>
      <td><div align="right"><strong>Raz&oacute;n Social</strong></div></td>
      <td><div align="left"><?php echo $rowConsultaPresta['nombre'] ?></div></td>
    </tr>
  </table>
  <form name="nuevoEstablecimientos" id="nuevoEstablecimientos" method="post" onsubmit="return validar(this)" action="guardarNuevoEstablecimientos.php?codigopresta=<?php echo $codigopresta ?>">
    <table border="0">
      <tr>
        <td><div align="right"><strong>Nombre</strong></div></td>
        <td colspan="5"><div align="left"><input name="nombre" type="text" id="nombre" size="120" /></div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Domicilio</strong></div></td>
        <td colspan="5"><div align="left"><input name="domicilio" type="text" id="domicilio" size="120" /></div></td>
      </tr>
      <tr>
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
        <td><div align="right"><strong>Telefono 1 </strong></div></td>
        <td><div align="left">(<input name="ddn1" type="text" id="ddn1" size="5" />)-<input name="telefono1" type="text" id="telefono1" size="20" /></div></td>
        <td colspan="4"><div align="left"><strong>Telefono 2 </strong>(<input name="ddn2" type="text" id="ddn2" size="5"/>)-<input name="telefono2" type="text" id="telefono2" size="20"/></div></td>
      </tr>
	  <tr>
        <td><div align="right"><strong>Telefono FAX </strong></div></td>
        <td><div align="left">(<input name="ddnfax" type="text" id="ddnfax" size="5"/>)-<input name="telefonofax" type="text" id="telefonofax" size="20" /></div></td>
        <td colspan="4"><div align="left"><strong>Email </strong><input name="email" type="text" id="email" size="40" /></div></td>
      </tr>
      <tr>
	    <td><div align="right"><strong>Circulo</strong></div></td>
	    <td colspan="3">
	    	<div align="left">
          		<input name="circulo" type="radio" value="0" checked="checked" onclick="habilitaCalidad(this.value)"/> NO
  		  		<input name="circulo" type="radio" value="1" onclick="habilitaCalidad(this.value)"/>SI
		  	</div>
		</td>
      </tr>
      <tr>
      	<td><div align="right"><strong>Acrditacion Calidad</strong></div></td>
      	<td>
	    	<div align="left">
          		<input name="calidad" id="calidadNO" type="radio" value="0" checked="checked" onclick="habilitaFecha(this.value)" disabled="disabled"/> NO
  		  		<input name="calidad" id="calidadSI" type="radio" value="1" onclick="habilitaFecha(this.value)" disabled="disabled"/>SI
		  	</div>
		</td>
		<td><b>Fecha Desde</b> <input id="fechadesde" name="fechadesde" size="8" disabled="disabled"></input></td>
		<td><b>Fecha Hasta</b> <input id="fechahasta" name="fechahasta" size="8" disabled="disabled"></input></td>
      </tr>
    </table>
    <p><input type="submit" name="Submit" id="Submit" value="Guardar" /></p>
  </form>
  </div>
</body>
</html>
