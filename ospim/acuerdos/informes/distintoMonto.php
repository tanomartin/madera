<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Montos Diferentes :.</title>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

function validar(formulario) {
	$.blockUI({ message: "<h1>Generando Informe. Aguarde por favor...</h1>" });
	return true;
}
</script>
</head>
<body bgcolor="#CCCCCC">
<form id="form1" name="form1" onsubmit="return validar(this)" method="post" action="distintoMontoExcel.php" enctype="multipart/form-data" >
<div align="center">
	<input type="button" name="volver" value="Volver" onclick="location.href = 'moduloInformes.php'" /> 

<p><strong>Acuerdos con suma de cuotas distintas a monto de Cabecera </strong></p>
<p><label><input type="submit" name="Submit" value="Generar Informe"/></label></p>
</div>
</form>
</body>
</html>
