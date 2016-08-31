<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 

$codigopresta = $_GET['codigopresta'];
$sqlConsultaPresta = "SELECT p.*, l.nomlocali as localidad, r.descrip as provincia FROM prestadores p, localidades l, provincia r WHERE p.codigoprestador = $codigopresta and p.codlocali = l.codlocali and p.codprovin = r.codprovin";
$resConsultaPresta = mysql_query($sqlConsultaPresta,$db);
$rowConsultaPresta = mysql_fetch_assoc($resConsultaPresta);

$codigo = $_GET['codigo'];
$sqlConsultaEsta = "SELECT p.*, pr.nombre as prestador, l.nomlocali as localidad, r.descrip as provincia FROM establecimientos p, prestadores pr, localidades l, provincia r WHERE p.codigo = $codigo and p.codlocali = l.codlocali and p.codprovin = r.codprovin and p.codigoprestador = pr.codigoprestador";
$resConsultaEsta = mysql_query($sqlConsultaEsta,$db);
$rowConsultaEsta = mysql_fetch_assoc($resConsultaEsta);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modificar Establecimiento :.</title>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
}
-->
</style>
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
	formulario.Submit.disabled = true;
	return true;
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><span style="text-align:center">
    <input type="button" name="volver" value="Volver" onclick="location.href = 'establecimiento.php?codigoprof=<?php echo $codigoprof ?>&codigopresta=<?php echo $codigopresta ?>'" />
  </span>  
  </p>
   <p class="Estilo1">Modificar Establecimiento</p>
   <table width="500" border="1">
    <tr>
      <td width="163"><div align="right"><strong>C&oacute;digo</strong></div></td>
      <td width="321"><div align="left"><strong><?php echo $rowConsultaPresta['codigoprestador']  ?></strong></div></td>
    </tr>
    <tr>
      <td><div align="right"><strong>Raz&oacute;n Social</strong></div></td>
      <td><div align="left"><?php echo $rowConsultaPresta['nombre'] ?></div></td>
    </tr>
  </table>
  <form name="modifEstablecimiento" id="modifEstablecimiento" method="post" onsubmit="return validar(this)" action="guardarModificacionEstablecimiento.php?codigopresta=<?php echo $codigopresta ?>">
    <table border="0">
      <tr>
        <td><div align="right"><strong>C&oacute;digo</strong></div></td>
        <td colspan="5"><div align="left">
          <input name="codigo" readonly="readonly" style="background:#CCCCCC" type="text" id="codigo" size="4" value="<?php echo $rowConsultaEsta['codigo'] ?>"/>
        </div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Nombre</strong></div></td>
        <td colspan="5"><div align="left"><input name="nombre" type="text" id="nombre" size="120" value="<?php echo $rowConsultaEsta['nombre'] ?>"/></div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Domicilio</strong></div></td>
        <td colspan="5"><div align="left"><input name="domicilio" type="text" id="domicilio" size="120" value="<?php echo $rowConsultaEsta['domicilio'] ?>" /> </div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Codigo Postal</strong></div></td>
        <td width="244"><div align="left">
          <input style="background-color:#CCCCCC" readonly="readonly" name="indpostal" id="indpostal" type="text" size="1" value="<?php echo $rowConsultaEsta['indpostal'] ?>"/>
-
<input name="codPos" type="text" id="codPos" size="7" value="<?php echo $rowConsultaEsta['numpostal'] ?>" />
-
<input name="alfapostal"  id="alfapostal" type="text" size="3" value="<?php echo $rowConsultaEsta['alfapostal'] ?>"/>
		</div></td>
        <td width="365"><div align="left"><strong>Localidad</strong><strong>
          <select name="selectLocali" id="selectLocali">
            <option value="0">Seleccione un valor </option>
            <option value="<?php echo $rowConsultaEsta['codlocali'] ?>" selected="selected"><?php echo $rowConsultaEsta['localidad'] ?></option>
          </select>
        </strong></div></td>
        <td><div align="left"><strong>Provincia</strong><strong>
          <input readonly="readonly" style="background-color:#CCCCCC" name="provincia" type="text" id="provincia" value="<?php echo $rowConsultaEsta['provincia'] ?>"/>
          <input style="background-color:#CCCCCC; visibility:hidden " readonly="readonly" name="codprovin" id="codprovin" type="text" size="2" value="<?php echo $rowConsultaEsta['codprovin'] ?>"/>
        </strong></div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Telefono 1 </strong></div></td>
        <td><div align="left">(<input name="ddn1" type="text" id="ddn1" size="3" value="<?php echo $rowConsultaEsta['ddn1'] ?>"/>)-<input name="telefono1" type="text" id="telefono1" size="15" value="<?php echo $rowConsultaEsta['telefono1'] ?>"/></div></td>
        <td colspan="4"><div align="left"><strong>Telefono 2 </strong>( <strong><input name="ddn2" type="text" id="ddn2" size="3" value="<?php echo $rowConsultaEsta['ddn2'] ?>"/></strong> )-<strong><input name="telefono2" type="text" id="telefono2" size="15" value="<?php echo $rowConsultaEsta['telefono2'] ?>"/></strong></div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Telefono FAX </strong></div></td>
        <td><div align="left">(<input name="ddnfax" type="text" id="ddnfax" size="3" value="<?php echo $rowConsultaEsta['ddnfax'] ?>"/>)-<input name="telefonofax" type="text" id="telefonofax" size="15" value="<?php echo $rowConsultaEsta['telefonofax'] ?>"/>
</div></td>
        <td colspan="4"><div align="left"><strong>Email</strong> <input name="email" type="text" id="email" size="30" value="<?php echo $rowConsultaEsta['email'] ?>"/></div></td>
      </tr>
    </table>
    <p><input type="submit" name="Submit" id="Submit" value="Guardar Modificaci&oacute;n" /></p>
  </form>
  </div>
</body>
</html>