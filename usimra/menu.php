<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Men&uacute; USIMRA :.</title>
<script language="javascript">
function abrirModulo(dire, titulo) {
	a= window.open(dire,titulo,
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=10, left=10");
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
        <p><a href="javascript:abrirModulo('acuerdos/menuAcuerdos.php','AcuerdosUsimra')"><img src="img/acuerdos.png" width="90" height="90" border="0"/></a></p>
      </td>
      <td width="200">
	    <p>EMPRESAS</p>
	    <p><a  href="javascript:abrirModulo('../comun/empresas/menuEmpresa.php?origen=usimra','EmpresasUsimra')"><img src="img/empresa.png" width="90" height="90" border="0"/></a></p>
      </td>
	  <td width="200">
	  	<p>EMPLEADOS</p>
      	<p><a href="javascript:abrirModulo('empleados/menuEmpleados.php','EmpleadosUsimra')"><img src="img/empleados.png" width="90" height="90" border="0"/></a></p>
      </td>
    </tr>
    <tr>
      <td>
       <p>APORTES</p>
       <p><a href="javascript:abrirModulo('aportes/menuAportes.php','AportesUsimra')"><img src="img/aportes.png" width="90" height="90" border="0"/></a></p>
	  </td>
      <td>
        <p>FISCALIZACION</p>
        <p><a href="javascript:abrirModulo('fiscalizacion/menuFiscalizacion.php','FiscalizacionUsimra')"><img src="img/fiscalizacion.png" width="90" height="90" border="0"/></a></p>
      </td>
	  <td>
        <p>JUICIOS</p>
	    <p><a href="javascript:abrirModulo('legales/menuLegales.php','LegalesUsimra')"><img src="img/juicios.png" width="90" height="90" border="0"/></a></p>
      </td>
    </tr>
    <tr>
       <td>
      	<p>SISTEMAS</p>
      	<p><a href="moduloNoDisponible.php"><img src="img/sistemas.png" width="90" height="90" border="0"/></a></p>
      </td>
      <td>
          <p>BANCO</p>
          <p><a href="javascript:abrirModulo('banco/moduloBanco.php','BancoUsimra')"><img src="img/banco.png" width="90" height="90" border="0"/></a></p>
      </td>    
      <td> 
       	<?php if ($_SESSION['usuario'] == 'sistemas' || $_SESSION['usuario'] == 'dbarreiro' || $_SESSION['usuario'] == 'mvoilhaborda') { ?> 
			      <p>HERRAMIENTAS</p>
			      <p><a href="javascript:abrirModulo('herramientas/menuHerramientas.php','HerramientasUsimra')"><img src="img/herramientas.png" width="90" height="90" border="0"/></a></p>
	   	<?php } ?>
	   </td>
    </tr>
  </table>
   <p><a href="logout.php"><input type="button" name="salir" value="SALIR" onclick="location.href='logout.php'" /></a></p>
</div>
</body>

</html>
