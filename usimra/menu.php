<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionUsimra.php");?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Men&uacute; USIMRA :.</title>
<style type="text/css">
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
}
</style>

<script language="javascript">
function abrirAcuerdos(dire) {
	a= window.open(dire,"AcuerdosUsimra",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=10, left=10");
}

function abrirEmpresa(dire) {
	c= window.open(dire,"EmpresasUsimra",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=500, height=500, top=185, left=840");
}

function abrirSistemas(dire) {
	c= window.open(dire,"Sistemas",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=30, left=40");
}

</script>


</head>
<body bgcolor="#B2A274">
<div align="center">
  <p><span class="Estilo1">Men&uacute; Principal</span></p>
  <p>
    <img src="img/logo.jpg" width="168" height="168" /></p>
  <table width="600" border="2">
    <tr>
      <td width="192"> <div align="center">
        <p>M&oacute;dulo Acuerdos</p>
		
        <p><a href="javascript:abrirAcuerdos('acuerdos/menuAcuerdos.php')"><img src="img/manos.jpg" width="101" height="86" border="0"/></a></p>
        <p>&nbsp;</p>
        </div></td>
		
      <td width="192"><div align="center">
	    <p>M&oacute;dulo Empresas</p>
	    <p><a  href="javascript:abrirEmpresa('../comun/empresas/menuEmpresa.php?origen=usimra')"><img src="img/empresa.jpg" width="101" height="86" border="0" alt="enviar"/></a></p>
	    <p>&nbsp;</p>
      </div></td>
	   <?php if ($_SESSION['usuario'] == 'sistemas') { ?>
		  <td width="192"><div align="center">
			<p>Sistemas</p>
			<p><a href="javascript:abrirSistemas('sistemas/menuSistemas.php')"><img src="img/sistemas.jpg" width="101" height="86" border="0" alt="enviar"/></a></p>
			<p>&nbsp;</p>
		  </div></td>
	 <?php } ?>
    </tr>
  </table>
   <p><strong><a href="logout.php">
     <input type="button" name="salir" value="SALIR" onclick="location.href='logout.php'" />
   </a></strong></p>
  <p>&nbsp;</p>
</div>
<p align="center">&nbsp;</p>
<p align="center">&nbsp;</p>
</body>

</html>
