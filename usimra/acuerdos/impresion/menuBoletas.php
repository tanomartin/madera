<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M�dulo Acuerdo USIMRA :.</title>
</head>

<body bgcolor="#B2A274">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = '../menuAcuerdos.php'"/></p>	
  <h3>Men&uacute; Boletas </h3>
  <table width="600" border="1" style="text-align: center">
    <tr>
      <td width="200">
		  <p>ANULAR</p>
		  <p><a href="cargaAnulacion.php"><img src="img/anulacion.png" width="90" height="90" border="0"/></a></p>
	  </td>
      <td width="200">
		  <p>IMPRIMIR </p>
		  <p><a href="moduloImpresion.php"><img src="img/impresora.png" width="90" height="90" border="0"/></a></p>
	  </td>
      <td width="200">
		  <p>BUSCAR </p>
		  <p><a href="buscadorBoleta.php"><img src="img/lupa.png" width="90" height="90" border="0"/></a></p>
	  </td>
    </tr>
  </table>
</div>
</body>
</html>
