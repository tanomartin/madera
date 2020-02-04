<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Consulta de D.D.J.J. :.</title>
</head>
<body bgcolor="#B2A274">
	<div align="center">
	  <p><input type="button" name="volver" value="Volver" onclick="location.href = 'moduloInformes.php'" /></p>
	  <h3>Menú Consultas D.D.J.J. </h3>
	  <table width="600" border="1" style="text-align: center">
        <tr>
          <td width="200">
          	<p>TODAS</p>
            <p><a href="ddjjCuit.php"><img src="../img/consultas.png" width="90" height="90" border="0" /></a></p>
          </td>
          <td width="200">
          	<p>VALIDAS</p>
            <p><a href="ddjjCuit.php?tipo=validas"><img src="../img/validas.png" width="90" height="90" border="0" /></a></p>
          </td>
          <td width="200">
          	<p>NO VALIDAS</p>
            <p><a href="ddjjCuit.php?tipo=novalidas"><img src="../img/novalidas.png" width="90" height="90" border="0" /></a></p>
          </td>
        </tr>
      </table>
	  </div>
</body>
</html>