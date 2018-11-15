<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSession.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Empresas :.</title>
</head>
<body style="background-color: <?php echo $bgcolor ?>">
<div align="center">
  <h3>Menú Empresas </h3>
  <table width="600" border="1" style="text-align: center">
    <tr>
      <td width="200">
      	<p>ALTA, MODIFICACION Y CONSULTA</p>
        <p><a href="abm/moduloABM.php?origen=<?php echo $origen?>"><img src="img/abemp.png" width="90" height="90" border="0" /></a></p>
      </td>
      <td width="200">
      	<p>BUSCADOR</p>
        <p><a href="buscador/buscador.php?origen=<?php echo $origen?>"><img src="img/buscar.png" width="90" height="90" border="0" /></a></p>
      </td>
	  <td width="200">
	  	<p>LISTADO DE EMPRESA</p>
        <p><a href="informes/listadoEmpresas.php?origen=<?php echo $origen?>"><img src="img/informes.png" width="90" height="90" border="0" /></a></p>
      </td>
    </tr>
  </table>
</div>
</body>
</html>
