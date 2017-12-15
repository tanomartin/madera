<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Menu Prestadores Contaduria OSPIM :.</title>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = '../menuContaduria.php'" /></p> 
  <h2>Menú Prestadores - Datos Auxiliares</h2>
  <table width="400" border="3" style="text-align: center">
    <tr>
	  <td width="200">
	  	  <p>ABMC Datos Auxiliares</p>
          <p><a class="enlace" href="abm/abmPrestadores.php"><img src="../img/prestador.png" width="90" height="90" border="0"/></a></p>
      </td>
	  <td width="200">
	      <p>Informes</p>
          <p><a class="enlace" href="informes/informeDatosAuxiliares.php"><img src="../img/excellogo.png" width="90" height="90" border="0" alt="enviar"/></a></p>
     </td>
    </tr>
  </table>
</div>
</body>
</html>
