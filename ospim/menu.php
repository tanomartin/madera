<?php include($_SERVER['DOCUMENT_ROOT']."/ospim/lib/controlSession.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Menú OSPIM :.</title>
<style type="text/css">
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
}
</style>

<script language="javascript">
function abrirAcuerdos(dire) {
	a= window.open(dire,"Acuerdos",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=10, left=10");
}

function abrirAfil(dire) {
	b= window.open(dire,"Afiliados",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=500, height=500, top=185, left=840");
}

function abrirEmpresa(dire) {
	c= window.open(dire,"Empresas",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=500, height=500, top=185, left=840");
}
</script>


</head>
<body bgcolor="#CCCCCC">
<div align="center">
  <p><span class="Estilo1">Men&uacute; Princal - Sistema OSPIM</span></p>
  <p>
    <img src="img/logo.jpg" width="168" height="139" /></p>
  <table width="599" border="2">
    <tr>
      <td width="196"> <div align="center">
        <p>M&oacute;dulo Acuerdos</p>
        <p><a href="acuerdos/menuAcuerdos.php" onclick = "window.open(this.href,'Acuerdos','resizable=YES, Scrollbars=YES, height=800'); return false"><img src="img/manos.jpg" width="101" height="86" border="0"/></a></p>
        <p>&nbsp;</p>
        </div></td>
		
      <td width="196"><div align="center">
	    <p>M&oacute;dulo Empresas</p>
	    <p><a href="javascript:abrirEmpresa('empresas/menuEmpresa.php')"><img src="img/empresa.jpg" width="101" height="86" border="0" alt="enviar"/></a></p>
	    <p>&nbsp;</p>
      </div></td>
	  
      <td width="183"><div align="center">
	    <p>M&oacute;dulo Afiliados</p>
	    <p><a href="javascript:abrirAfil('afiliados/menuAfiliados.php')"><img src="img/empleado.jpg" width="101" height="86" border="0" /></a></p>
	    <p>&nbsp;</p>
      </div></td>
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
