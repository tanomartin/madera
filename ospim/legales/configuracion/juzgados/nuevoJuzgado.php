<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php");?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Nuevo Juzgado :.</title>
<script type="text/javascript">

function validar(formulario) {
	if (formulario.denominacion.value == "") {
		alert("Debe completar la Denominación del Juzgado");
		return(false);
	}
	if (formulario.fuero.value == 0) {
		alert("Debe Seleccionar un Fuero");
		return(false);
	}
	formulario.Submit.disabled = true;
	return true;
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = 'juzgados.php'" /></p>
  <h3>Nuevo Juzgado </h3>
  <form id="nuevoJuzgado" name="nuevoJuzgado" method="post" action="guardarNuevoJuzgado.php" onsubmit="return validar(this)">				
	<p>Denominación <input name="denominacion" type="text" id="denominacion" size="100" maxlength="100"/></p>
	<p>Fuero 	<select name="fuero" id="fuero">
				  	<option value="0" selected="selected">SELECCIONE FUERO</option>
				    <option value="CIVIL Y COMERCIAL">CIVIL Y COMERCIAL</option>
				    <option value="COMERCIAL">COMERCIAL</option>
				    <option value="COMERCIAL CAP.FEDERAL">COMERCIAL CAP.FEDERAL</option>
				    <option value="FEDERAL">FEDERAL</option>
				    <option value="FEDERAL SEGURIDAD SOCIAL">FEDERAL SEGURIDAD SOCIAL</option>
			      </select></p>
	<p><input type="submit" name="Submit" value="Guardar" /></p>
  </form>
</div>
</body>
</html>
