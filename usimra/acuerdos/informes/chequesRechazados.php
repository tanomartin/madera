<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/usimra/lib/";
include($libPath."controlSession.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M&oacute;dulo Informes de Acuerdos :.</title>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
}
</style>
<script src="../../lib/jquery.js" type="text/javascript"></script>
<script src="../../lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

function MsgWait(formulario) {
	$.blockUI({ message: "<h1>Generando Informe. Aguarde por favor...</h1>" });
	return true;
}
</script>
</head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>
<body bgcolor="#B2A274">
<form id="form1" name="form1" onSubmit="return MsgWait(this)" method="POST" action="chequesRechazadosExcel.php" enctype="multipart/form-data" >
<p align="center"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><a href="moduloInformes.php">VOLVER</a></strong></font></p>
<p align="center" class="Estilo1">Cheques Rechazados</p>
<p align="center">
  <label>
  <input type="submit" name="Submit" value="Generar Informe"/>
  </label>
</p>
<p align="center">&nbsp;</p>
</form>
</body>
</html>
