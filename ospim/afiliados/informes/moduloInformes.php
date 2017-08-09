<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Afiliados OSPIM :.</title>

<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<input type="button" name="volver" value="Volver" onclick="location.href = '../menuAfiliados.php'" /> 
</div>
<div align="center">
	<h2>Men&uacute; Consultas e Informes</h2>
</div>
<div align="center">
  <table width="600" border="3">
    <tr>
        <td width="200">
        	<p align="center">DDJJ / Aportes</p>
        	<p align="center"><a class="enlace" href="ddjjAportesCuil.php"><img src="../img/ddjjaportes.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        	<p>&nbsp;</p>
        </td>
      	<td width="200">
      		<p align="center">Titulares por Empresa</p>
          	<p align="center"><a class="enlace" href="titularesPorEmpresa.php"><img src="../img/listado.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        	<p>&nbsp;</p>
        </td>
      	<td width="200">
      		<p align="center">Cantidad de Beneficiarios por Delegaci&oacute;n </p>
        	<p align="center"><a class="enlace" href="cantBeneficiariosPorDelegacion.php"><img src="../img/listado.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      		<p>&nbsp;</p>
      	</td>
    </tr>
    <tr>
     	<td>
     		<p align="center">Beneficiarios por Grupo Etario </p>
        	<p align="center"><a class="enlace" href="beneficiariosPorGrupoEtario.php"><img src="../img/excellogo.png" width="90" height="90" border="0" alt="enviar"/></a></p>
      		<p>&nbsp;</p>
      	</td>
       	<td>
       		<p align="center">Beneficiarios por Delegación</p>
          	<p align="center"><a class="enlace" href="beneficiariosPorDelegacion.php"><img src="../img/excellogo.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        	<p>&nbsp;</p>
        </td>
      	<td>
      		<p align="center">Beneficiarios por Localidad</p>
          	<p align="center"><a class="enlace" href="beneficiariosPorLocalidad.php"><img src="../img/excellogo.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        	<p>&nbsp;</p>
        </td>
    </tr>
    <tr>
    	<td>
    		<p align="center">Beneficiarios por Delegacion por Tipo de Titularidad</p>
          	<p align="center"><a class="enlace" href="beneficiariosPorTipoTitu.php"><img src="../img/listado.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        	<p>&nbsp;</p>
    	</td>
    	<td>
    		<p align="center">Busqueda </p>
        	<p align="center"><a class="enlace" href="buscadorAfiliado.php"><img src="../img/buscar.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        	<p>&nbsp;</p>
    	</td>
    	<td>
    		<p align="center">Padron SSS</p>
        	<p align="center"><a class="enlace" href="menuSSS.php"><img src="../img/padronsss.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        	<p>&nbsp;</p>
    	</td>
    </tr>
  </table>
</div>
</body>
</html>
