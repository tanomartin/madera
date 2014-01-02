<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspimSistemas.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Tratamiento AFIP :.</title>
<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

function MsgWait() {
	$.blockUI({ message: "<h1>Descargando Mensajes. Aguarde por favor...</h1>" });
	return true;
}
</script>
</head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><span style="text-align:center">
    <input type="reset" name="volver" value="Volver" onclick="location.href = '../menuSistemas.php'" align="center"/>
  </span></p>
  <p><span class="Estilo2">Archivos AFIP </span></p>
  <table width="626" border="3">
    <tr>
      <td width="200"><p align="center">Transferencias</p>
          <p align="center"><a class="enlace" href="mailTransferencias.php" onclick="javascript:return MsgWait()"><img src="img/afiptransferencias.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
      <td width="200"><p align="center">N&oacute;minas</p>
        <p align="center"><a class="enlace" href="mailDDJJ.php" onclick="javascript:return MsgWait()"><img src="img/afipddjj.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      <p align="center">&nbsp;</p></td>
      <td width="200"><p align="center">Liquidaciones Especiales</p>
        <p align="center"><a class="enlace" href="mailSubsidio.php" onclick="javascript:return MsgWait()"><img src="img/afipsubidio.png" alt="enviar" width="90" height="90" border="0"/></a></p>
        <p align="center">&nbsp;</p></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><p align="center">Informaci&oacute;n Padr&oacute;n</p>
        <p align="center"><a class="enlace" href="mailPadron.php" onclick="javascript:return MsgWait()"><img src="img/afippadron.png" alt="enviar" width="90" height="90" border="0"/></a></p>
      <p>&nbsp;</p></td>
      <td>&nbsp;</td>
    </tr>
  </table>
</div>
</body>
</html>
