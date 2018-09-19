<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Usuarios :.</title>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = '../menuSistemas.php'"/></p>
  <h3>Menú Usuarios </h3>
  <table width="400" border="1" style="text-align: center">
    <tr>
      <td width="200">
      	<p>USUARIO </p>
        <p><a href="usuarios.php"><img src="img/users.png" width="90" height="90" border="0" /></a></p>
      </td>
      <td  width="200">
      	<p>E-MAILS </p>
        <p><a href="emails.php"><img src="img/email.png" width="90" height="90" border="0" /></a></p>
      </td>
    </tr>
  </table>
</div>
</body>
</html>