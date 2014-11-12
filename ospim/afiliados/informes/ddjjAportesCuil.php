<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: ABM Afiliados :.</title>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
}
-->
</style>
<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/jquery-ui.min.js" type="text/javascript"></script>
<script src="/lib/jquery.blockUI.js" type="text/javascript"></script>
<script src="/lib/funcionControl.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
jQuery(function($){
	$("#cuil").mask("99999999999");
});

function validar(formulario) {
	formulario.aportes.disabled = true;
	if (formulario.cuil.value == "") {
		formulario.aportes.disabled = false;
		alert("Debe ingresar un C.U.I.L. para la busqueda");
		document.getElementById("cuil").focus();
		return false;
	} else {
		if(!verificaCuilCuit(formulario.cuil.value)){
			alert("El C.U.I.L. es invalido");
			formulario.aportes.disabled = false;
			return false;
		}
	}

	param = "cuiAfi=" + formulario.cuil.value;
	opciones = "top=50,left=50,width=900,height=680,toolbar=no,menubar=no,status=no,dependent=yes,hotkeys=no,scrollbars=yes,resizable=no"
	window.open("../abm/ddjjAportesAfiliado.php?" + param, "", opciones);
};
</script>
</head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>
<style type="text/css" media="print">
.nover {display:none}
</style>
<body bgcolor="#CCCCCC">
<form id="ddjjAportesCuil" name="ddjjAportesCuil" method="post"  onSubmit="return validar(this)">
	<div align="center">
		<input class="nover" type="reset" name="volver" value="Volver" onClick="location.href = 'moduloInformes.php'" align="center"/> 
	</div>
	<p align="center" class="Estilo1">Consulta DDJJ / Aportes</p>
	<div align="center">
		<p>C.U.I.L. <input name="cuil" id="cuil" type="text" size="11" /></p>
	</div>
	<p align="center"><input class="nover" type="submit" id="aportes" name="aportes" value="Consultar" align="center"/></p>
</form>
</body>
</html>