<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSession.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M�dulo Empresas :.</title>
</head>
<body style="background-color: <?php echo $bgcolor ?>">
<div align="center">
  <h3>Men� Pedidos a Sistemas </h3>
  <table width="400" border="1" style="text-align: center">
    <tr>
      <td width="200">
      	<p>CORRECCIONES</p>
        <p><a href="correcciones/moduloCorrecciones.php?origen=<?php echo $origen?>"><img src="img/correcciones.png" width="90" height="90" border="0" /></a></p>
      </td>
      <td width="200">
      	<p>PEDIDOS</p>
        <p><a href="pedidos/moduloPedidos.php?origen=<?php echo $origen?>"><img src="img/pedidos.png" width="90" height="90" border="0" /></a></p>
      </td>
    </tr>
  </table>
</div>
</body>
</html>
