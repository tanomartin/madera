<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M�dulo Sistemas :.</title>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <h2>Men&uacute; Sistemas </h2>
  
  <table width="900" border="3" style="text-align: center;vertical-align: middle;">
    <tr>
      <td width="200">
      	<p>Fiscalizaci&oacute;n</p>
        <p><a class="enlace" href="fiscalizacion/menuFiscalizacion.php"><img src="img/fiscalizacion.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      </td>
      <td width="200">
      	<p>Aplicativo DDJJ </p>
      	<p><a class="enlace" href="aplicativoddjj/menuAplicativoddjj.php"><img src="img/aplicativoddjj.png" width="97" height="85" border="0" alt="enviar"/></a></p>
      </td>
      <td width="200">
      	<p>Padrones</p>
        <p><a class="enlace" href="padrones/menuPadrones.php"><img src="img/padrones.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      </td>
      <td width="200">
      	<p>Stock</p>
      	<p><a class="enlace" href="stock/menuStock.php"><img src="img/stock.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      </td>
    </tr>
    <tr>
      <td>
      	<p>Tratamiento A.F.I.P.</p>
        <p><a class="enlace" href="afip/menuAfip.php"><img src="img/afip.png" width="110" height="90" border="0" alt="enviar"/></a></p>
      </td>
       <td>
      	<p>Desempleo</p>
      	<p><a class="enlace" href="desempleo/menuDesempleo.php"><img src="img/anses.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      </td>
      <td>
      	<p>Medicamentos (Alfa Beta)</p>
      	<p><a class="enlace" href="medicamentos/menuMedicamentos.php"><img src="img/medicamentos.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      </td>
      <td>
      	<p>Intranets</p>
        <p><a class="enlace" href="intranets/menuIntranet.php"><img src="img/intranets.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      </td>
    </tr>
    <tr>
      <td>
      	<p>Usuarios</p>
        <p><a href="usuarios/menuUsuarios.php"><img src="img/users.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      </td>
      <td>
      	<p>Estado Contable</p>
        <p><a href="estadocontable/estadoContable.php"><img src="img/estadocontable.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      </td>
      <td>
      </td>
      <td>
      </td>
    </tr>
  </table>
</div>
</body>
</html>
