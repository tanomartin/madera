<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php");; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Men� OSPIM :.</title>
<style type="text/css">
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
}
</style>

<script language="javascript">
function abrirAcuerdos(dire) {
	a= window.open(dire,"AcuerdosOspim",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=10, left=10");
}

function abrirAfil(dire) {
	b= window.open(dire,"AfiliadosOspim",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=20, left=20");
}

function abrirEmpresa(dire) {
	c= window.open(dire,"EmpresasOspim",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=30, left=30");
}

function abrirAuditoria(dire) {
	c= window.open(dire,"Auditoria",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=30, left=40");
}

function abrirFiscalizacion(dire) {
	c= window.open(dire,"Fiscalizacion",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=30, left=40");
}

function abrirSistemas(dire) {
	c= window.open(dire,"Sistemas",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=30, left=40");
}
</script>


</head>
<body bgcolor="#CCCCCC">
<div align="center">
  <p><span class="Estilo1">Men&uacute; Principal - Sistema OSPIM</span></p>
  <p>
    <img src="img/logo.jpg" width="168" height="139" /></p>
  <table width="599" border="2">
    <tr>
      <td width="196"> <div align="center">
        <p>Acuerdos</p>
        <p><a href="javascript:abrirAcuerdos('acuerdos/menuAcuerdos.php')"><img src="img/manos.jpg" width="101" height="86" border="0"/></a></p>
        <p>&nbsp;</p>
        </div></td>
      <td width="196"><div align="center">
	    <p>Empresas</p>
	    <p><a href="javascript:abrirEmpresa('../comun/empresas/menuEmpresa.php?origen=ospim')"><img src="img/empresa.jpg" width="101" height="86" border="0" alt="enviar"/></a></p>
	    <p>&nbsp;</p>
      </div></td>
	  
      <td width="183"><div align="center">
	    <p>Afiliados</p>
	    <p><a href="javascript:abrirAfil('afiliados/menuAfiliados.php')"><img src="img/empleado.jpg" width="101" height="86" border="0" /></a></p>
	    <p>&nbsp;</p>
      </div></td>
    </tr>
	<tr>
      <td width="196"><div align="center">
	    <p>Fiscalizaci&oacute;n</p>
	    <p><a href="javascript:abrirFiscalizacion('fiscalizacion/menuFiscalizacion.php')"><img src="img/fiscalizacion.jpg" width="101" height="86" border="0" alt="enviar"/></a></p>
	    <p>&nbsp;</p>
      </div></td>
		
      <td width="196"><div align="center">
	    <p>Auditoria Medica </p>
	    <p><a href="javascript:abrirAuditoria('auditoria/menuAuditoria.php')"><img src="img/auditoria.jpg" width="101" height="86" border="0" alt="enviar"/></a></p>
	    <p>&nbsp;</p>
      </div></td> 
	  <?php if ($_SESSION['usuario'] == 'sistemas') { ?>
		  <td width="183"><div align="center">
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
</div>
</body>

</html>
