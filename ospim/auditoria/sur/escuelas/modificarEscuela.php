<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 

$codigo = $_GET['id'];
$sqlEscuela = "SELECT p.*, l.nomlocali as localidad, r.descrip as provincia FROM escuelas p, localidades l, provincia r WHERE p.id = $codigo and p.codlocali = l.codlocali and p.codprovin = r.codprovin";
$resEscuela = mysql_query($sqlEscuela,$db);
$rowEscuela = mysql_fetch_assoc($resEscuela);
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
   <h3>Modificar Escuela</h3>
  <form name="modifEscuela" id="modifEscuela" method="post" onsubmit="return validar(this)" action="guardarModificacionEscuela.php?id=<?php echo $codigo ?>">
    <table border="0">
      <tr>
        <td><div align="right"><strong>C&oacute;digo</strong></div></td>
        <td colspan="5"><div align="left">
          <input name="codigo" readonly="readonly" style="background:#CCCCCC" type="text" id="codigo" size="4" value="<?php echo $rowEscuela['id'] ?>"/>
        </div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Nombre</strong></div></td>
        <td colspan="5"><div align="left"><input name="nombre" type="text" id="nombre" size="120" value="<?php echo $rowEscuela['nombre'] ?>"/></div></td>
      </tr>
        <tr>
        <td><div align="right"><strong>C.U.E.</strong></div></td>
        <td colspan="5"><div align="left"><input name="cue" type="text" id="cue" size="10" value="<?php echo $rowEscuela['cue'] ?>"/></div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Domicilio</strong></div></td>
        <td colspan="5"><div align="left"><input name="domicilio" type="text" id="domicilio" size="120" value="<?php echo $rowEscuela['domicilio'] ?>" /> </div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Codigo Postal</strong></div></td>
        <td width="244"><div align="left">
          <input style="background-color:#CCCCCC" readonly="readonly" name="indpostal" id="indpostal" type="text" size="1" value="<?php echo $rowEscuela['indpostal'] ?>"/>
-
<input name="codPos" type="text" id="codPos" size="7" value="<?php echo $rowEscuela['numpostal'] ?>" />
-
<input name="alfapostal"  id="alfapostal" type="text" size="3" value="<?php echo $rowEscuela['alfapostal'] ?>"/>
		</div></td>
        <td width="365"><div align="left"><strong>Localidad</strong><strong>
          <select name="selectLocali" id="selectLocali">
            <option value="0">Seleccione un valor </option>
            <option value="<?php echo $rowEscuela['codlocali'] ?>" selected="selected"><?php echo $rowEscuela['localidad'] ?></option>
          </select>
        </strong></div></td>
        <td><div align="left"><strong>Provincia</strong><strong>
          <input readonly="readonly" style="background-color:#CCCCCC" name="provincia" type="text" id="provincia" value="<?php echo $rowEscuela['provincia'] ?>"/>
          <input style="background-color:#CCCCCC; visibility:hidden " readonly="readonly" name="codprovin" id="codprovin" type="text" size="2" value="<?php echo $rowEscuela['codprovin'] ?>"/>
        </strong></div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Telefono </strong></div></td>
        <td><div align="left"><input name="telefono" type="text" id="telefono" size="15" value="<?php echo $rowEscuela['telefono'] ?>"/></div></td>
        <td colspan="4"><div align="left"><strong>Email</strong> <input name="email" type="text" id="email" size="30" value="<?php echo $rowEscuela['email'] ?>"/></div></td>
      </tr>
    </table>
    <p><input type="submit" name="Submit" id="Submit" value="Guardar Modificaci&oacute;n" /></p>
  </form>
  </div>
</body>
</html>