<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Banco USIMRA :.</title>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

function MsgWait(formulario) {
	$.blockUI({ message: "<h1>Ajustando Valores para Documentos Conciliados con Anterioridad. Aguarde por favor...</h1>" });
	return true;
}
</script>
</head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {font-weight: bold}
</style>

<body bgcolor="#B2A274">
<form id="form1" name="form1" onSubmit="return MsgWait(this)" method="POST" action="ajustaConciliados.php" enctype="multipart/form-data" >
  
  <div align="center">
    <p>
      <input type="reset" name="volver" value="Volver" onclick="location.href = '../documentosBancarios.php'" align="left" />
    </p>
  </div>
    <table width="600" border="1" align="center">
	  <tr align="center">
		<td><div align="center"><strong><font face="Arial, Helvetica, sans-serif">Conciliacion</font></strong></em></div></td>
	  </tr>
	  <tr align="center">
		<td><input type="submit" name="ajustar" value="Ajustar Conciliados" align="left" /></td>
	  </tr>
  </table>
</form>
</body>
</html>