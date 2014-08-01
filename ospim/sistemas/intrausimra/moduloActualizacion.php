<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspimSistemas.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Stock :.</title>
</head>

<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
.Estilo7 {font-weight: bold}
</style>
<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">
function validar(formulario) {
	if (formulario.file.value == '') {
		alert("Debe seleccionar un Archivo");
		return false;
	} else {
		archivo = formulario.file.value;
		extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
		if (extension != ".txt") {
			alert("El archivo debe ser un texto plano (.txt)");
			return false;
		} 
	}
	var mensaje = "<h1>Actualizando Intranet U.S.I.M.R.A.<br>Aguarde por favor...</h1>"
	$.blockUI({ message: mensaje });
	return true;
}

</script>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><span style="text-align:center">
    <input type="reset" name="volver" value="Volver" onclick="location.href = 'menuActualizacionUsimra.php'" align="center"/>
  </span></p>
  <p><span class="Estilo2">Men&uacute; Actualizacion Intranet U.S.I.M.R.A. </span></p>
  <form action="actualizarIntraUsimra.php" method="post" enctype="multipart/form-data" name="form1" id="form1" onsubmit="return validar(this)">
    <p><strong>Cargar archivo para la Actualizaci&oacute;n </strong></p>
    <p>
      <label>
      <input type="file" name="file" id="file" />
      </label>
    </p>
    <p>
      <label>
      <input type="submit" name="Submit" value="Actualizar" />
      </label>
    </p>
  </form>
</div>
</body>
</html>
