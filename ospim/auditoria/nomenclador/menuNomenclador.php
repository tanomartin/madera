<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modulo Nomencladores :.</title>

</head>
<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="reset" name="volver" value="Volver" onclick="location.href = '../menuAuditoria.php'"/></p>
  <h3>Men&uacute; Nomencladores </h3>
  <table width="600" border="3" style="text-align: center">
    <tr>
	  <td width="200">
	  	<p>NOMENCLADOS</p>
        <p><a href="nomenclado/menuNomenclado.php"><img src="img/nomenclador.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      </td>
      <td width="200">
      	<p>NO NOMENCLADO</p>
        <p><a href="nonomenclado/menuNoNomenclado.php"><img src="img/nomenclador.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      </td>
      <td width="200">
        <p>RESOLUCIONES</p>
        <p><a href="resoluciones/moduloResoluciones.php"><img src="img/resoluciones.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      </td>
     </tr>
     <tr>
      <td>
        <p>BUSCADOR</p>
        <p><a href="buscador/buscadorPractica.php"><img src="img/buscar.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      </td>
       <td>
        <p>PROPIEDADES</p>
        <p><a href="propiedades/menuPropiedades.php"><img src="img/propiedades.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      </td>
      <td>
      </td>
   </tr>
  </table>
</div>
</body>
</html>
