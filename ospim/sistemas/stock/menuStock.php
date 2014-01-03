<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspimSistemas.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Stock :.</title>
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
  <p><span class="Estilo2">Men&uacute; Stock</span></p>
  <table width="600" border="3">
    <tr>
       <td><p align="center">Productos </p>
         <p align="center"><a class="enlace" href="productos.php"><img src="img/productos.png" width="90" height="90" border="0" alt="enviar"/></a></p>
         <p align="center">&nbsp;</p></td>
	   <td><p align="center">Seguro</p>
       <p align="center"><a class="enlace" href="seguro.php"><img src="img/seguro.png" width="90" height="90" border="0" alt="enviar"/></a></p>
       <p align="center">&nbsp;</p></td>
	   <td><p align="center">Pedidos</p>
         <p align="center"><a class="enlace" href="pedidos.php"><img src="img/pedidos.png" width="90" height="90" border="0" alt="enviar"/></a></p>
       <p align="center">&nbsp;</p></td>
    </tr>
    <tr>
      <td><p align="center">Insumos  </p>
      <p align="center"><a class="enlace" href="insumos.php"><img src="img/insumos.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      <p align="center">&nbsp;</p></td>
      <td><p align="center">Stock Insumos </p>
        <p align="center"><a class="enlace" href="stock.php"><img src="img/stock.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      <p align="center">&nbsp;</p></td>
      <td></td>
    </tr>
  </table>
</div>
</body>
</html>
