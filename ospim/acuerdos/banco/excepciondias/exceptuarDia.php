<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
$origenExceptuar = $_GET['origen'];
$fechaExceptuar = $_GET['dia'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Banco OSPIM :.</title>
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
<body bgcolor="#CCCCCC">
<form id="form1" onsubmit="return validar(this)"  name="form1" method="post" action="guardaExcepcionDia.php">
<div align="center">
	<p><input type="reset" name="volver" value="Volver" onclick="location.href = '../procesamientoArchivos.php'"/></p>
	<p class="Estilo1">Exceptuar Proceso Bancario</p>
	<p>D&iacute;a a Exceptuar
    <input type="text" name="fecha" id="fecha" size="10" readonly="readonly" style="background-color:#CCCCCC; text-align:center" value="<?php echo $fechaExceptuar ?>"/>
</p>
	<p>Convenio a Aplicar 
		<select name="selectConvenio" id="selectConvenio">
			<option title="5734 - Aportes / Acuerdos" value="5734" selected="selected">5734 - Acuerdos</option>
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