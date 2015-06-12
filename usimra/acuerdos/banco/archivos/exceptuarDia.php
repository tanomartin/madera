<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
$fechaExceptuar = $_GET['dia'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Banco USIMRA :.</title>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
}
-->
</style>
<script type="text/javascript">
function validar(formulario) {
	if(formulario.motivo.value == "") {
		alert("Debe ingresar el motivo por el cual se exceptua del proceso bancario");
		return false;
	}
	formulario.exceptuar.disabled = true;
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
<form id="form1" onSubmit="return validar(this)"  name="form1" method="post" action="guardaExcepcionDia.php">
<div align="center"><input type="reset" name="volver" value="Volver" onClick="location.href = 'procesamientoArchivos.php'" align="center"/> 
	<p class="Estilo1">Exceptuar Proceso Bancario</p>
	<p>D&iacute;a a Exceptuar
    <input type="text" name="fecha" id="fecha" size="10" readonly style="background-color:#CCCCCC; text-align:center" value="<?php echo $fechaExceptuar ?>"/></p>
	<p>Motivo de Excepción</p>
	<p>
	  <textarea name="motivo" id="motivo" cols="40" rows="4"></textarea>
    </p>
	<input type='submit' name='exceptuar' id="exceptuar" value='Exceptuar' />
</div>
</form>
</body>
</html>