<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M�dulo Fiscalisador OSPIM :.</title>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
}
-->
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>
</head>
<body bgcolor="#B2A274">
	<div align="center">
	  <p>
	    <input type="reset" name="volver" value="Volver" onclick="location.href = '../moduloBanco.php'"/>
      </p>
	  <p><span class="Estilo1">Men&uacute; Consultas Banco</span></p>
	  <table width="400" border="3">
        <tr>
          <td width="200"><p align="center">Movimientos Bancarios<a class="enlace" href="#"></a></p>
              <p align="center"><a class="enlace" href="movimientoBanco.php"><img src="img/consultas.png" width="90" height="90" border="0" alt="enviar"/></a></p>
            <p align="center">&nbsp;</p></td>
			<td width="200"><p align="center">D�as Procesados<a class="enlace" href="#"></a></p>
              <p align="center"><a class="enlace" href="diasProcesados.php"><img src="img/diasprocesados.png" width="90" height="90" border="0" alt="enviar"/></a></p>
           	  <p align="center">&nbsp;</p></td>
			 
        </tr>
      </table>
	  </div>
</body>
</html>
