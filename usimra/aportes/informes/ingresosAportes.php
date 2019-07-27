<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M&oacute;dulo Informes de Aportes :.</title>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
}
</style>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
jQuery(function($){
	$("#fechadesde").mask("99-99-9999");
	$("#fechahasta").mask("99-99-9999");
});

function validar(formulario) {
	if (!esFechaValida(formulario.fechadesde.value)) {
		alert("La fecha desde no es valida");
		document.getElementById("fechadesde").focus();
		return(false);
	} 
	if (!esFechaValida(formulario.fechahasta.value)) {
		alert("La fecha hasta no es valida");
		document.getElementById("fechahasta").focus();
		return(false);
	}
	if (formulario.selectTipo.options[formulario.selectTipo.selectedIndex].value == "") {
		alert("Debe seleccionar un tipo de ingresos para el informe");
		document.getElementById("selectTipo").focus();
		return false;
	}
	if (formulario.selectTotales.options[formulario.selectTotales.selectedIndex].value == "") {
		alert("Debe seleccionar si necesita o no necesita totalizadores para el informe");
		document.getElementById("selectTotales").focus();
		return false;
	}
	$.blockUI({ message: "<h1>Generando Informe. Aguarde por favor...</h1>" });
	return true;
}
</script>

<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>
</head>
<body bgcolor="#B2A274">
<form id="form1" name="form1" onsubmit="return validar(this)" method="post" action="ingresosAportesExcel.php" enctype="multipart/form-data" >
<div align="center">
<table width="137" border="0">
	<tr align="center" valign="top">
      <td width="137" valign="middle"><div align="center">
        <input type="button" name="volver" value="Volver" onclick="location.href = 'moduloInformes.php'"/> 
        </div></td>
	</tr>
</table>
</div>
<p align="center" class="Estilo1">Ingresos por Aportes</p>
<p align="center">Desde el : <input id="fechadesde" name="fechadesde" type="text" value="<?php echo date("d/m/Y",time());?>" size="10"/></p>
<p align="center">Hasta el : <input id="fechahasta" name="fechahasta" type="text" value="<?php echo date("d/m/Y",time());?>" size="10"/></p>
<p align="center">Tipo de Ingresos 
		<select name="selectTipo" id="selectTipo">
			<option title="Seleccione un valor" value="">Seleccione un valor</option>
			<option title="Electronicos" value="E">Electronicos</option>
			<option title="Manuales" value="M">Manuales</option>
			<option title="LinkPagos" value="L">LinkPagos</option>
			<option title="Todos" value="A">Todos</option>
   		</select>
	</p>
<p align="center">Totalizadores 
		<select name="selectTotales" id="selectTotales">
			<option title="Seleccione un valor" value="">Seleccione un valor</option>
			<option title="Sin Totales por Delegacion" value=0>Sin Totales por Delegacion</option>
			<option title="Con Totales por Delegacion" value=1>Con Totales por Delegacion</option>
   		</select>
	</p>
<p align="center"><input type="submit" name="Submit" value="Generar Informe"/></p>
</form>
</body>
</html>
