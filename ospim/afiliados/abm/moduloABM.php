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
function validar(formulario) {
	formulario.buscar.disabled = true;
	var elementos = document.forms.moduloABM.elements;
	var longitud = document.forms.moduloABM.length;
	var elementoradio = 0;
	for(var i=0; i<longitud; i++) {
		if(elementos[i].name == "seleccion" && elementos[i].type == "radio" && elementos[i].checked == true) {
			elementoradio=i;
		}
	}

	if(elementoradio == 0) {
		formulario.buscar.disabled = false;
		alert("Debe seleccionar una opcion de busqueda");
		return false;
	} else {
		if (formulario.valor.value == "") {
			formulario.buscar.disabled = false;
			alert("Debe ingresar algun dato para la busqueda");
			document.getElementById("valor").focus();
			return false;
		} else {
			if(elementoradio == 3) {
				if(!verificaCuilCuit(formulario.valor.value)){
					alert("El C.U.I.L. es invalido");
					formulario.buscar.disabled = false;
					return false;
				}
			}
		}
	}

	$.blockUI({ message: "<h1>Buscando Afiliado. Aguarde por favor...</h1>" });

	return true;
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
<form id="moduloABM" name="moduloABM" method="post"  onSubmit="return validar(this)" action="buscaAfiliado.php">
	<div align="center">
		<input class="nover" type="reset" name="volver" value="Volver" onClick="location.href = '../menuAfiliados.php'" align="center"/> 
	</div>
	<p align="center" class="Estilo1">Afiliados</p>
	<p>
    <?php 
		$err = $_GET['err'];
		if ($err == 1) {
			print("<div align='center' style='color:#FF0000'><b> LA BUSQUEDA DE BENEFICIARIO POR NRO DE AFILIADO NO GENERO RESULTADOS </b></div>");
		}
		if ($err == 2) {
			print("<div align='center' style='color:#FF0000'><b> LA BUSQUEDA DE BENEFICIARIO POR NRO DE DOCUMENTO NO GENERO RESULTADOS </b></div>");
		}
		if ($err == 3) {
			print("<div align='center' style='color:#FF0000'><b> LA BUSQUEDA DE BENEFICIARIO POR CUIL NO GENERO RESULTADOS </b></div>");
		}
	?>
	</p>
	<div align="center">
		<table width="137" border="0">
		  <tr>
			<td width="23"><input name="seleccion" type="radio" value="nroafiliado" /></td>
			<td width="104"><div align="left">Nro Afiliado</div></td>
		  </tr>
		  <tr>
			<td><input name="seleccion" type="radio" value="nrodocumento" /></td>
			<td><div align="left">Nro Documento</div></td>
		  </tr>
		  <tr>
			<td><input name="seleccion" type="radio" value="cuil" /></td>
			<td><div align="left">CUIL</div></td>
		  </tr>
		</table>
		<p><input name="valor" id="valor" type="text" size="11" /></p>
	</div>
	<p align="center"><input class="nover" type="submit" name="buscar" value="Buscar" /></p>
	<p align="center"><input class="nover" type="button" value="Nuevo Afiliado" onclick="location.href='nuevoAfiliado.php'"/></p>
</form>
</body>
</html>