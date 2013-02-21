<?php include($_SERVER['DOCUMENT_ROOT']."/comun/lib/controlSession.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Empresa :.</title>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
}
-->
</style>
</head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>
<script src="../../lib/jquery.js" type="text/javascript"></script>
<script src="../../lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="../../lib/funcionControl.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

jQuery(function($){
	$("#cuit").mask("99999999999");
});

</script>

<body bgcolor=<?php echo $bgcolor ?>>
<form id="form1" name="form1" method="post" action="empresa.php?origen=<?php echo $origen ?>">
  <p align="center" class="Estilo1"><strong><a href="../menuEmpresa.php?origen=<?php echo $origen ?>"><font face="Verdana" size="2"><b>VOLVER</b></font></a></strong>
    </p>
  <p align="center" class="Estilo1">M&oacute;dulo De ABM de Empresas</p>
  <?php 
		$err = $_GET['err'];
		if ($err == 1) {
			print("<div align='center' style='color:#FF0000'><b> EMPRESA NO ENCONTRADA </b></div>");
		}

  ?>
  <p>
  <label>
  <div align="center">CUIT
      <input name="cuit" id="cuit" type="text"  size="10" />
  </label>
    <p align="center">
    <label>
    <input type="submit" name="Submit" value="Enviar" />
    </label>
  </p>
    <p align="center"><input type="button" value="Nueva Empresa" onclick="location.href='nuevaEmpresa.php?origen=<?php echo $origen ?>'"/></p>
    <p>&nbsp;</p>
</form>
<p align="center">&nbsp;</p>
</body>
</html>
