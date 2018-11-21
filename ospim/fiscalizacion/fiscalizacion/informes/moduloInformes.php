<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Fiscalisador OSPIM :.</title>
</head>
<body bgcolor="#CCCCCC">
	<div align="center">
	  <p><input type="button" name="volver" value="Volver" onclick="location.href = '../menuFiscalizaciones.php'" /></p>
	  <h3>Men&uacute; Consultas </h3>
	  <table width="600" border="1" style="text-align: center">
        <tr>
          <td width="200">
          	<p>APORTES</p>
            <p><a href="aportes/consultaAportes.php"><img src="img/consultas.png" width="90" height="90" border="0" /></a></p>
          </td>
          <td width="200">
          	<p>DDJJ</p>
            <p><a href="ddjj/consultaddjj.php"><img src="img/consultas.png" width="90" height="90" border="0" /></a></p>
          </td>
          <td width="200">
          	<p>REQUERIMIENTO </p>
            <p><a href="requerimientos/filtrosBusqueda.php"><img src="img/consultas.png" width="90" height="90" border="0"/></a></p>
          </td>
        </tr>
        <tr>
          <td>
          	<p>CANT. DDJJ OSPIM-USIMRA</p>
            <p><a href="ddjj/ospimvsusimra.php"><img src="img/consultas.png" width="90" height="90" border="0" /></a></p>
          </td>
          <td>
          	<p>LIQUIDACIONES</p>
            <p><a href="liquidaciones/filtrosBusqueda.php"><img src="img/consultas.png" width="90" height="90" border="0"/></a></p>
          </td>
          <td>&nbsp;</td>
        </tr>
      </table>
	</div>
</body>
</html>
