<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Banco USIMRA :.</title>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {font-weight: bold}
</style>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
function irAValidar() {
	$.blockUI({ message: "<h1>Procesando la Validacion de Boletas.<br>Aguarde por favor...</h1>" });
	document.location.href = "validarBoletasExtraordinarias.php";
}
function irARegistrar() {
	$.blockUI({ message: "<h1>Procesando el Registro de Pagos.<br>Aguarde por favor...</h1>" });
	document.location.href = "registrarPagosExtraordinarias.php";
}
</script>
</head>
<body bgcolor="#B2A274">
  
<div align="center">
  <p>
    <input type="reset" name="volver" value="Volver" onclick="location.href = '../moduloExtraordinaria.php'" align="left" />
  </p>
</div>
    <table width="600" border="1" align="center">
  <tr align="center" valign="top">
    <td colspan="2"><div align="center"><strong><font face="Arial, Helvetica, sans-serif">Imputaciones Cuota Extraordinaria</font></strong></div></td>
  </tr>
  
  <tr align="center" valign="top">
    <td><div align="center">
		<input type="submit" name="validar" value="Validar Boletas" onclick="javascript:irAValidar()" align="left" />
</div></td>
    <td><div align="center">
		<input type="submit" name="registrar" value="Registrar Pagos" onclick="javascript:irARegistrar()" align="left" /
    </div>
    </td>
  </tr>
</table>
</body>
</html>
