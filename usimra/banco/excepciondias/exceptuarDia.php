<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
$origenExceptuar = $_GET['origen'];
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
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>
<script type="text/javascript">
function validar(formulario) {
	if (formulario.selectConvenio.options[formulario.selectConvenio.selectedIndex].value == "") {
		alert("Debe seleccionar un convenio al que aplicar la excepcion");
		document.getElementById("selectConvenio").focus();
		return false;
	}
	if(formulario.motivo.value == "") {
		alert("Debe ingresar el motivo por el cual se exceptua del proceso bancario");
		document.getElementById("motivo").focus();
		return false;
	}
	formulario.exceptuar.disabled = true;
	return true;
}
</script>
</head>
<body bgcolor="#B2A274">
<form id="form1" onsubmit="return validar(this)"  name="form1" method="post" action="guardaExcepcionDia.php">
<div align="center">
<?php
if(strcmp("A", $origenExceptuar)==0) {
?>
	<input type="reset" name="volver" value="Volver" onclick="location.href = '../aportesacuerdos/archivos/procesamientoArchivosAportes.php'"/>
<?php
}
if(strcmp("E", $origenExceptuar)==0) {
?>
	<input type="reset" name="volver" value="Volver" onclick="location.href = '../cuotaextraordinaria/archivos/procesamientoArchivosExtraordinarias.php'"/>
<?php
}
if(strcmp("L", $origenExceptuar)==0) {
?>
	<input type="reset" name="volver" value="Volver" onclick="location.href = '../linkpagos/archivos/procesamientoArchivosLinkpagos.php'"/>
<?php
}
?>
	<p class="Estilo1">Exceptuar Proceso Bancario</p>
	<p>D&iacute;a a Exceptuar
    <input type="text" name="fecha" id="fecha" size="10" readonly="readonly" style="background-color:#CCCCCC; text-align:center" value="<?php echo $fechaExceptuar ?>"/>
	<input type="text" name="origen" id="origen" size="1" readonly="readonly" style="visibility:hidden" value="<?php echo $origenExceptuar ?>"/>
</p>
	<p>Convenio a Aplicar 
		<select name="selectConvenio" id="selectConvenio">
			<option title="Seleccione un valor" value="">Seleccione un valor</option>
			<option title="3617 - Aportes / Acuerdos" value="3617">3617 - Aportes / Acuerdos</option>
			<option title="5866 - Cuota Excepcional" value="5866">5866 - Cuota Excepcional</option>
			<option title="0XO0 - Link Pagos" value="0XO0">0XO0 - Link Pagos</option>
			<option title="Todos Convenios" value="0000">Todos Convenios</option>
   		</select>
	</p>
	<p>Motivo de Excepción</p>
	<p>
	  <textarea name="motivo" id="motivo" cols="40" rows="4"></textarea>
    </p>
	<input type='submit' name='exceptuar' id="exceptuar" value='Exceptuar' />
</div>
</form>
</body>
</html>