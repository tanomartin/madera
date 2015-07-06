<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M&oacute;dulo Solicitudes de Autorizacion :.</title>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
}
</style>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

function MsgWait(formulario) {
	$.blockUI({ message: "<h1>Descargando Nuevas Solicitudes. Aguarde por favor...</h1>" });
	return true;
}
</script>
</head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>
<body bgcolor="#CCCCCC">
<form id="form1" name="form1" onSubmit="return MsgWait(this)" method="POST" action="descargaSolicitudes.php" enctype="multipart/form-data" >
<div align="center">
<table width="137" border="0">
	<tr align="center" valign="top">
      <td width="137" valign="middle"><div align="center">
        <input type="reset" name="volver" value="Volver" onClick="location.href = '../menuAfiliados.php'" align="center"/> 
        </div></td>
	</tr>
</table>
</div>
<p align="center" class="Estilo1">Solicitudes de Autorizacion</p>
<p align="center"><label><input type="submit" name="Submit" value="Descargar Nuevas Solicitudes"/></label></p>
</form>
</body>
</html>