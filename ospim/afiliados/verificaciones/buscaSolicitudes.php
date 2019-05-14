<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Solicitudes de Autorizacion :.</title>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

function MsgWait(formulario) {
	formulario.Submit.disabled = true;
	$.blockUI({ message: "<h1>Descargando Nuevas Solicitudes. Aguarde por favor...</h1>" });
	return true;
}
</script>
</head>
<body bgcolor="#CCCCCC">
<form id="form1" name="form1" onsubmit="return MsgWait(this)" method="post" action="descargaSolicitudes.php" enctype="multipart/form-data" >
	<div align="center">
	    <p><input type="button" name="volver" value="Volver" onclick="location.href = '../menuAfiliados.php'" /></p>
		<h3>Solicitudes de Autorizacion</h3>
		<p><input type="submit" name="Submit" value="Descargar Nuevas Solicitudes"/></p>
	</div>
</form>
</body>
</html>