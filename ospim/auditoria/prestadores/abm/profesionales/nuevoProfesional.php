<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
$codigopresta = $_GET['codigopresta'];
$sqlConsultaPresta = "SELECT codigoprestador, nombre FROM prestadores WHERE codigoprestador = $codigopresta";
$resConsultaPresta = mysql_query($sqlConsultaPresta,$db);
$rowConsultaPresta = mysql_fetch_assoc($resConsultaPresta);

$sqlCategoria = "select * from practicascategorias";
$resCategoria = mysql_query($sqlCategoria,$db); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Alta Profesinoal :.</title>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">

jQuery(function($){
	$("#cuit").mask("99999999999");
	
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
	if (formulario.cuit.value != "") {
		if (!verificaCuilCuit(formulario.cuit.value)){
			alert("C.U.I.T invalido");
			return false;
		}
	}

	var tratamiento = formulario.selectTratamiento.options[formulario.selectTratamiento.selectedIndex].value;
	if (tratamiento == 0) {
		alert("Debe elegir una Tramtamiento para Persona F�sica");
		return false;
	}
	if (formulario.nroRegistro.value != "") {
		if (!esEntero(formulario.nroRegistro.value)) {
			alert("El Nro. de Registro en la SSS debe ser un numero");
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
  <p><input class="nover" type="button" name="volver" value="Volver" onclick="location.href = 'modificarProfesionales.php?codigo=<?php echo $codigopresta ?>'" /></p> 
  <h3>Nuevo Pofesional </h3>
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
  <form name="nuevoPrestador" id="nuevoPrestador" method="post" onsubmit="return validar(this)" action="guardarNuevoProfesional.php?codigopresta=<?php echo $codigopresta ?>">
    <table border="0">
      <tr>
        <td><div align="right"><strong>Nombre</strong></div></td>
        <td colspan="5"><div align="left"><input name="nombre" type="text" id="nombre" size="120" /></div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>C.U.I.T.</strong></div></td>
        <td><div align="left"><input name="cuit" type="text" id="cuit" size="13" /></div></td>
        <td><strong>Categoria</strong>
        	<select name="idcategoria" id="idcategoria">
      			<?php while($rowCategoria = mysql_fetch_assoc($resCategoria)) { ?>
      					<option value='<?php echo $rowCategoria['id'] ?>'><?php echo $rowCategoria['descripcion'] ?></option>
      			<?php } ?>
      		</select>
        </td>
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
        <td colspan="4"><div align="left"><strong>Email</strong><input name="email" type="text" id="email" size="40" /></div></td>
      </tr>
	  <tr>
	    <td><div align="right"><strong>Tratamiento</strong></div></td>
	    <td><div align="left">
	      <select name="selectTratamiento" size="1" id="selectTratamiento">
            <option value="0" selected="selected">Seleccione un valor </option>
            <?php $query="select * from tipotratamiento";
				  $result=mysql_query($query,$db);
				  while ($rowtipos=mysql_fetch_array($result)) { ?>
            		<option value="<?php echo $rowtipos['codigotratamiento'] ?>"><?php echo $rowtipos['descripcion']  ?></option>
            <?php } ?>
          </select>
	    </div></td>
        <td><div align="left"><strong>Matr&iacute;cula Nacional </strong><input name="matriculaNac" type="text" id="matriculaNac" size="10" maxlength="20"/></div></td>
        <td colspan="3"><div align="left"><strong>Matr&iacute;culo Provincial </strong><input name="matriculaPro" type="text" id="matriculaPro" size="10" maxlength="20"/></div></td>
      </tr>
	  <tr>
	    <td><div align="right"><strong>Numero Reg. SSS</strong></div></td>
	    <td colspan="5"><div align="left"><input name="nroRegistro" type="text" id="nroRegistro" size="10" maxlength="20"/></div></td>
      </tr>
    </table>
    <p><input type="submit" name="Submit" id="Submit" value="Guardar" /></p>
  </form>
  </div>
</body>
</html>
