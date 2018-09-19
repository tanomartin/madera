<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Sistemas Aplicativo :.</title>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">

function importar() {
	$.blockUI({ message: "<h1>Importando datos Aplicativo DDJJ... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
	location.href='importacion/importarInfoAplicativoDDJJ.php';
}

function periodos() {
	$.blockUI({ message: "<h1>Consultado Peridos... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
	location.href='periodos/periodos.php';
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = '../menuSistemas.php'" /></p>
  <h3>Menú Aplicativo DDJJ</h3>
  <table width="400" border="1" style="text-align: center">
    <tr>
      <td width="200">
      	<p>IMP. EMPRESAS, EMPLEADOS Y FAMILIARES </p>
        <p><a href="javascript:importar()"><img src="img/Download.png" width="90" height="90" border="0" /></a></p>
      </td>
      <td width="200">
      	<p>ABM DE PERIODOS </p>
        <p><a href="javascript:periodos()"><img src="img/periodos.png" width="90" height="90" border="0" /></a></p>
      </td>
    </tr>
  </table>
</div>
</body>
</html>
