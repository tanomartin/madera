<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M&oacute;dulo Sistemas Prevencion :.</title>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">
function importar() {
	$.blockUI({ message: "<h1>Importando datos del Programa de Prevención... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
	location.href='importacion/importarInfoProgramaPrevencion.php';
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = '../menuAuditoria.php'" /></p>
  <h3>Men&uacute; Programa Prevenci&oacute;n </h3>
  <table width="600" border="1" style="text-align: center;vertical-align: middle;">
    <tr>
    	<td width="200">
      		<p>DESCARGA INFORMACION</p>
        	<p><a href="javascript:importar()"><img src="img/Download.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      	</td>
	  	<td width="200">
	  		<p>PROGRAMAS</p>
         	<p><a href="#"><img src="img/prevencion.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        </td>
	 	<td width="200">
	 		<p>INFORME</p>
          	<p><a href="#"><img src="img/informes.png" width="90" height="90" border="0" alt="enviar"/></a></p>
       </td>
    </tr>
  </table>
</div>
</body>
</html>
