<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Men&uacute; USIMRA :.</title>

<script language="javascript">
function abrirAcuerdos(dire) {
	a= window.open(dire,"AcuerdosUsimra",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=10, left=10");
}

function abrirAportes(dire) {
	a= window.open(dire,"AportesUsimra",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=10, left=10");
}

function abrirEmpresa(dire) {
	c= window.open(dire,"EmpresasUsimra",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=500, height=500, top=185, left=840");
}

function abrirLegales(dire) {
	c= window.open(dire,"LegalesUsimra",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=30, left=40");
}

function abrirAportes(dire) {
	c= window.open(dire,"AportesUsimra",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=30, left=40");
}

function abrirBanco(dire) {
	c= window.open(dire,"BancoUsimra",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=30, left=40");
}

function abrirFiscalizacion(dire) {
	c= window.open(dire,"FiscalizacionUsimra",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=30, left=40");
}

function abrirEmpleados(dire) {
	c= window.open(dire,"EmpleadosUsimra",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=30, left=40");
}

function abrirMinutas(dire) {
	c= window.open(dire,"HerramientasUsimra",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=30, left=40");
}

</script>


</head>
<body bgcolor="#B2A274">
<div align="center">
  <p><img src="img/logo.png" width="168" height="168" /></p>
  <table width="600" border="2" style="text-align: center;">
    <tr>
      <td width="200">
        <p>ACUERDOS</p>
        <p><a href="javascript:abrirAcuerdos('acuerdos/menuAcuerdos.php')"><img src="img/acuerdos.png" width="90" height="90" border="0"/></a></p>
      </td>
      <td width="200">
	    <p>EMPRESAS</p>
	    <p><a  href="javascript:abrirEmpresa('../comun/empresas/menuEmpresa.php?origen=usimra')"><img src="img/empresa.png" width="90" height="90" border="0"/></a></p>
      </td>
	  <td width="200">
	  	<p>EMPLEADOS</p>
      	<p><a href="javascript:abrirEmpleados('empleados/menuEmpleados.php')"><img src="img/empleados.png" width="90" height="90" border="0"/></a></p>
      </td>
    </tr>
    <tr>
      <td>
       <p>APORTES</p>
       <p><a href="javascript:abrirAportes('aportes/menuAportes.php')"><img src="img/aportes.png" width="90" height="90" border="0"/></a></p>
	  </td>
      <td>
        <p>FISCALIZACION</p>
        <p><a href="javascript:abrirFiscalizacion('fiscalizacion/menuFiscalizacion.php')"><img src="img/fiscalizacion.png" width="90" height="90" border="0"/></a></p>
      </td>
	  <td>
        <p>JUICIOS</p>
	    <p><a href="javascript:abrirLegales('legales/menuLegales.php')"><img src="img/juicios.png" width="90" height="90" border="0"/></a></p>
      </td>
    </tr>
    <tr>
       <td> 
       	<?php if ($_SESSION['usuario'] == 'sistemas' || $_SESSION['usuario'] == 'dbarreiro' || $_SESSION['usuario'] == 'mvoilhaborda') { ?> 
			      <p>HERRAMIENTAS</p>
			      <p><a href="javascript:abrirMinutas('herramientas/menuHerramientas.php')"><img src="img/herramientas.png" width="90" height="90" border="0"/></a></p>
	   	<?php } ?>
	   </td>
      <td>
          <p>BANCO</p>
          <p><a href="javascript:abrirBanco('banco/moduloBanco.php')"><img src="img/banco.png" width="90" height="90" border="0"/></a></p>
      </td>
      <td>&nbsp;</td>
    </tr>
  </table>
   <p><a href="logout.php"><input type="button" name="salir" value="SALIR" onclick="location.href='logout.php'" /></a></p>
</div>
</body>

</html>
