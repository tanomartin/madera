<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Informes de Juicios :.</title>

<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<input type="button" name="volver" value="Volver" onclick="location.href = '../menuLegales.php'" /> 
</div>
<div align="center">
  <p><span class="Estilo2">M&oacute;dulo De Informes</span></p>
  <table style="border: double; text-align: center;">
    <tr>
     <td><p>Juicios Fec. Expedicion</p>
        <p><a href="juciosFecExpedicion.php"><img src="img/excellogo.png" border="0" width="105" height="105"/></a></p>
     </td>
    </tr>
  </table>
</div>
</body>
</html>
