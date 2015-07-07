<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M&oacute;dulo Recaudaci&oacute;n Bancaria :.</title>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {font-weight: bold}
</style>
</head>

<body bgcolor="#CCCCCC">
  <table style="width: 762; height: 107" border="1" align="center">
  <tr align="center" valign="top">
    <td height="23" colspan="3"><div align="center"><em><strong><font face="Arial, Helvetica, sans-serif">Imputaciones </font></strong></em></div></td>
  </tr>
  <tr align="center" valign="top">
    <td height="27" colspan="3">&nbsp;</td>
  </tr>
  <tr align="center" valign="top">
    <td width="245" height="47" valign="middle"><div align="center">
        <input type="reset" name="volver" value="Volver" onclick="location.href = 'moduloBanco.php'" align="left" />
        </div>	</td>
	<td width="250" height="47" valign="middle"><div align="center">
	  	<input type="submit" name="validar" value="Validar Boletas" onclick="location.href = 'validarBoletas.php'" align="left" />
    	</div>	</td>
    <td width="245" height="47" valign="middle"><div align="center">
      <input type="submit" name="registrar" value="Registrar Pagos" onclick="location.href = 'registrarPagos.php'" align="left" />
    </div>
    </td>
  </tr>
</table>
</body>
</html>
