<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php"); ?>

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
function abrirModulo(dire, titulo) {
	a= window.open(dire,titulo,
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=10, left=10");
}
</script>


</head>
<body bgcolor="#CCCCCC">
<div align="center">
  <p><span class="Estilo1">Men&uacute; Principal - Sistema OSPIM</span></p>
  <p><img src="img/logo.png" width="168" height="142" /></p>
  <table width="600" border="1">
    <tr>
      <td> 
	  <div align="center">
        <p>Acuerdos</p>
        <p><a href="javascript:abrirModulo('acuerdos/menuAcuerdos.php','AcuerdosOspim')"><img src="img/acuerdos.png" width="90" height="90" border="0"/></a></p>
        <p>&nbsp;</p>
        </div>
	  </td>
      <td>
	  <div align="center">
	    <p>Empresas</p>
	    <p><a href="javascript:abrirModulo('../comun/empresas/menuEmpresa.php?origen=ospim','EmpresasOspim')"><img src="img/empresa.png" width="90" height="90" border="0" alt="enviar"/></a></p>
	    <p>&nbsp;</p>
      </div>
	  </td>
      <td>
	  <div align="center">
	    <p>Afiliados</p>
	    <p><a href="javascript:abrirModulo('afiliados/menuAfiliados.php','AfiliadosOspim')"><img src="img/afiliados.png" width="90" height="90" border="0" /></a></p>
	    <p>&nbsp;</p>
      </div>
	  </td>
    </tr>
	
	<tr>
      <td>
	  <div align="center">
	    <p>Fiscalizaci&oacute;n</p>
	    <p><a href="javascript:abrirModulo('fiscalizacion/menuFiscalizacion.php','FiscalizacionOspim')"><img src="img/fiscalizacion.png" width="90" height="90" border="0" alt="enviar"/></a></p>
	    <p>&nbsp;</p>
      </div>
	  </td>	
      <td width="196"><div align="center">
	    <p>Auditoria Medica </p>
	    <p><a href="javascript:abrirModulo('auditoria/menuAuditoria.php','AuditoriaOspim')"><img src="img/auditoria.png" width="90" height="90" border="0" alt="enviar"/></a></p>
	    <p>&nbsp;</p>
      </div></td> 	
	  <td>
	  <div align="center">
	    <p>Legales </p>
	    <p><a href="javascript:abrirModulo('legales/menuLegales.php','LegalesOspim')"><img src="img/juicios.png" width="90" height="90" border="0" alt="enviar"/></a></p>
	    <p>&nbsp;</p>
      </div>
	  </td>	  
    </tr>
	<tr>
	  <td> <?php if ($_SESSION['usuario'] == 'sistemas') { ?> 
	  <div align="center">
			<p>Sistemas</p>
			<p><a href="javascript:abrirModulo('sistemas/menuSistemas.php','SistemasOspim')"><img src="img/sistemas.png" width="90" height="90" border="0" alt="enviar"/></a></p>
			<p>&nbsp;</p>
	  </div>
	  <?php } ?></td>
	  <td>
	 <div align="center">
	    <p>Tesorería </p>
	    <p><a href="javascript:abrirModulo('tesoreria/menuTesoreria.php','TesoreriaOspim')"><img src="img/tesoreria.png" width="90" height="90" border="0" alt="enviar"/></a></p>
	    <p>&nbsp;</p>
      </div>
	  </td>
	  <td>&nbsp;</td>
    </tr>
  </table>
   <p><strong><a href="logout.php">
     <input type="button" name="salir" value="SALIR" onclick="location.href='logout.php'" />
  </a></strong></p>
</div>
</body>

</html>
