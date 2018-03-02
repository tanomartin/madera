<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Afiliados OSPIM :.</title>
</head>
<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = '../menuAfiliados.php'" /> </p>
	<h2>Men&uacute; Consultas e Informes</h2>

  	<table width="800" border="3" style="text-align: center;">
    	<tr>
	        <td width="200">
	        	<p>DDJJ / APORTES</p>
	        	<p><a class="enlace" href="ddjjAportesCuil.php"><img src="../img/ddjjaportes.png" width="90" height="90" border="0" alt="enviar"/></a></p>
	        </td>
	      	<td width="200">
	      		<p>TITU x EMPRESA</p>
	          	<p><a class="enlace" href="titularesPorEmpresa.php"><img src="../img/listado.png" width="90" height="90" border="0" alt="enviar"/></a></p>
	        </td>
	      	<td width="200">
	      		<p>CANT. BENEFICIARIOS x DELEGACION </p>
	        	<p><a class="enlace" href="cantBeneficiariosPorDelegacion.php"><img src="../img/listado.png" width="90" height="90" border="0" alt="enviar"/></a></p>
	      	</td>
	      	<td width="200">
	    		<p>BENEFICIARIOS x DELEGACION Y TIPO</p>
	          	<p><a class="enlace" href="beneficiariosPorTipoTitu.php"><img src="../img/listado.png" width="90" height="90" border="0" alt="enviar"/></a></p>
	    	</td>
    	</tr>
   	 	<tr>
	     	<td>
	     		<p>BENEFICIARIOS X GRUPO ETARIO </p>
	        	<p><a class="enlace" href="beneficiariosPorGrupoEtario.php"><img src="../img/excellogo.png" width="90" height="90" border="0" alt="enviar"/></a></p>
	      	</td>
	       	<td>
	       		<p>BENEFICIARIOS X DELEGACION</p>
	          	<p><a class="enlace" href="beneficiariosPorDelegacion.php"><img src="../img/excellogo.png" width="90" height="90" border="0" alt="enviar"/></a></p>
	        </td>
	      	<td>
	      		<p>BENEFICIARIOS X LOCALIDAD</p>
	          	<p><a class="enlace" href="beneficiariosPorLocalidad.php"><img src="../img/excellogo.png" width="90" height="90" border="0" alt="enviar"/></a></p>
	        </td>
	        <td>
	        	<p>PLANILLA ALTA SSS X DELEGACION</p>
	        	<p><a class="enlace" href="titularesaltasss.php"><img src="../img/pdflogo.png" width="100" height="100" border="0" alt="enviar"/></a></p>
	        </td>
	    </tr>
    	<tr>
	    	<td></td>
	    	<td>
	    		<p>BUSCADOR</p>
	        	<p><a class="enlace" href="buscadorAfiliado.php"><img src="../img/buscar.png" width="90" height="90" border="0" alt="enviar"/></a></p>
	    	</td>
	    	<td>
	    		<p>PADRON S.S.S.</p>
	        	<p><a class="enlace" href="menuSSS.php"><img src="../img/padronsss.png" width="90" height="90" border="0" alt="enviar"/></a></p>
	    	</td>
	    	<td></td>
    	</tr>
  	</table>
</div>
</body>
</html>
