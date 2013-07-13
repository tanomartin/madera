<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M&oacute;dulo Recaudaci&oacute;n Bancaria :.</title>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
}
-->
</style>
<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/lib/funcionControl.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
jQuery(function($){
	$("#fechaarchivo").mask("99-99-9999");
});

function validar(formulario) {
	if (!esFechaValida(formulario.fechaarchivo.value)) {
		alert("La fecha no es valida");
		document.getElementById("fechaarchivo").focus();
		return(false);
	} 
}
</script>
</head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>
<body bgcolor="#CCCCCC">
<form id="form1" onSubmit="return validar(this)"  name="form1" method="post" action="verificacionArchivo.php">
<div align="center">
<table width="137" border="0">
	<tr align="center" valign="top">
      <td width="137" valign="middle"><div align="center">
        <input type="reset" name="volver" value="Volver" onClick="location.href = '../moduloBanco.php'" align="center"/> 
        </div></td>
	</tr>
</table>
</div>
<p align="center" class="Estilo1">Procesamiento de Archivos Transferidos</p>
<p align="center">Fecha del Archivo del Banco:<label><input id="fechaarchivo" name="fechaarchivo" type="text" value="<?php echo date("d/m/Y",time());?>" size="10" /></label></p>
<p align="center"><label><input type="submit" name="Submit" value="Enviar" /></label></p>
</form>
</body>
</html>
