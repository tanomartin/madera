<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M&oacute;dulo Sistemas Prevencion :.</title>
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

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">
function importar() {
	$.blockUI({ message: "<h1>Importando datos del Programa de Prevención... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
	location.href='importacion/importarInfoProgramaPrevencion.php';
}

</script>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><span style="text-align:center">
    <input type="reset" name="volver" value="Volver" onclick="location.href = '../menuAuditoria.php'" align="center"/>
  </span></p>
  <p><span class="Estilo2">Men&uacute; Programa Prevenci&oacute;n </span></p>
  <table width="600" border="3">
    <tr>
      <td width="200"><p align="center">Descarga Información Programa de Prevención</p>
          <p align="center"><a href="javascript:importar()"><img src="img/Download.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
	  <td width="200"><p align="center">Programas</p>
          <p align="center"><a href="#"><img src="img/prevencion.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
	 <td width="200"><p align="center">Informes</p>
          <p align="center"><a href="#"><img src="img/informes.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
    </tr>
  </table>
</div>
</body>
</html>
