<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Nueva Practica :.</title>
</head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>

<script src="/lib/jquery.js"></script>
<script src="/lib/jquery-ui.min.js"></script>
<script src="/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">

function abrirPantalla(dire) {
	a= window.open(dire,"nuevaPractica",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=10, left=10");
}
	
jQuery(function($){	
	$("#tipo").change(function(){
		//reset Capitulo
		$("#capitulo").html("<option value='0'>Seleccione Capitulo</option>");
		$("#capitulo").prop("disabled",true);
		$("#nuevoCap").prop("disabled",true);
		//reset Subcatiulo
		$("#subcapitulo").html("<option value='0'>Seleccione SubCapitulo</option>");
		$("#subcapitulo").prop("disabled",true);
		$("#nuevoSub").prop("disabled",true);
		//reset formularios de carga
		$("#formularioCargaCapitulo").html("");
		$("#formularioCargaSubCapitulo").html("");
		$("#formularioCargaPractica").html("");
		
		var valor = $(this).val();
		$.ajax({
			type: "POST",
			dataType: 'html',
			url: "getCapitulos.php",
			data: {valor:valor, tipo:valor},
		}).done(function(respuesta){
			if (valor != 0) {
				if (respuesta != 0) {
					$("#capitulo").html(respuesta);
					$("#capitulo").prop("disabled",false);
					$("#nuevoCap").prop("disabled",false);
				} else {
					$.ajax({
						type: "POST",
						dataType: 'html',
						url: "cargarNuevaPractica.php",
						data: {valor:-1, tipo:valor},
					}).done(function(respuesta){
						$("#formularioCargaPractica").html(respuesta);
					});
				}
			}
		});
	});
	
	$("#capitulo").change(function(){
		//reset SubCapitulo
		$("#subcapitulo").html("<option value='0'>Seleccione SubCapitulo</option>");
		$("#subcapitulo").prop("disabled",true);
		$("#nuevoSub").prop("disabled",true);
		//reset formulario de carga
		$("#formularioCargaCapitulo").html("");
		$("#formularioCargaSubCapitulo").html("");
		$("#formularioCargaPractica").html("");
		
		var valor = $(this).val();
		valor = valor.split('-');
		var tipo = $("#tipo").val();
		$.ajax({
			type: "POST",
			dataType: 'html',
			url: "getSubCapitulos.php",
			data: {valor:valor[0], tipo:tipo},
		}).done(function(respuesta){
			if (respuesta != 0) {
				$("#subcapitulo").html(respuesta);	
				$("#subcapitulo").prop("disabled",false);
				$("#nuevoSub").prop("disabled",false);	
			} else {
				if (($("#tipo").val() == 2 || $("#tipo").val() == 4) && valor != 0) {
					$("#nuevoSub").prop("disabled",false);
				} else {
					$.ajax({
						type: "POST",
						dataType: 'html',
						url: "cargarNuevaPractica.php",
						data: {valor:valor[1], tipo:tipo},
					}).done(function(respuesta){
						$("#formularioCargaPractica").html(respuesta);
					});
				}
			}
		});
	});
	
	$("#subcapitulo").change(function(){
		//reset formulario de carga
		$("#formularioCargaCapitulo").html("");
		$("#formularioCargaSubCapitulo").html("");
		$("#formularioCargaPractica").html("");
		var valor = $(this).val();
		valor = valor.split('-');
		var tipo = $("#tipo").val();
		$.ajax({
			type: "POST",
			dataType: 'html',
			url: "cargarNuevaPractica.php",
			data: {valor:valor[1], tipo:tipo},
		}).done(function(respuesta){
			$("#formularioCargaPractica").html(respuesta);
		});
	});
	
	$("#nuevoCap").click(function() {
		tipo = $("#tipo").val();
		$.ajax({
			type: "POST",
			dataType: 'html',
			url: "cargarNuevoCapitulo.php",
			data: {tipo:tipo},
		}).done(function(respuesta){
			$("#formularioCargaCapitulo").html(respuesta);
		});
	});
	
	$("#nuevoSub").click(function() {
		tipo = $("#tipo").val();
		capitulo = $("#capitulo").val();
		$.ajax({
			type: "POST",
			dataType: 'html',
			url: "cargarNuevoSubCapitulo.php",
			data: {tipo:tipo, capitulo:capitulo},
		}).done(function(respuesta){
			$("#formularioCargaSubCapitulo").html(respuesta);
		});
	});
	
});

function deshabilitarNuevoCap(valor) {
	if (valor == 0) {
		document.forms.form1.nuevoCap.disabled = false;
	} else {
		document.forms.form1.nuevoCap.disabled = true;
	}
}

function deshabilitarNuevoSub(valor) {
	if (valor == 0) {
		document.forms.form1.nuevoSub.disabled = false;
	} else {
		document.forms.form1.nuevoSub.disabled = true;
	}
}

function validarPractica(formulario) {
	var codigo = formulario.codigo.value;
	var tipo =  formulario.tipo.value;
	if(esEnteroPositivo(codigo)) {
		if((codigo <= 0 || codigo > 99) && tipo != -1) {
			alert("Debe ingresar un codigo de dos digitos entre 01 y 99 para este tipo de practica");
			return false;
		}
		if((codigo <= 999 || codigo > 1999) && tipo == -1) {
		alert("Debe ingresar un codigo de cuatro digitos entre 1000 y 1999 para este tipo de practica");
			return false;
		}
	} else {
		alert("Debe ingresar un codigo de dos digitos entre 01 y 99 para la practica");
		return false;
	}
	if(formulario.descri.value == "") {
		alert("Debe ingresar una descripción para la practica");
		return false;
	}
	formulario.Submit.disabled = true;
	return true;
}

function validarCapituloSubcapitulo(formulario) {
	var codigo = formulario.codigo.value;
	if(esEnteroPositivo(codigo)) {
		if(codigo <= 0 || codigo > 99) {
			alert("Debe ingresar un codigo de dos digitos entre 01 y 99 para este tipo de practica");
			return false;
		}
	} else {
		alert("Debe ingresar un codigo de dos digitos entre 01 y 99 para la practica");
		return false;
	}
	if(formulario.descri.value == "") {
		alert("Debe ingresar una descripción para la practica");
		return false;
	}
	formulario.Submit.disabled = true;
	return true;
}


</script>

<body bgcolor="#CCCCCC">
<div align="center">
  <p>
    <input type="reset" name="volver" value="Volver" onclick="location.href = 'menuNoNomenclado.php'" align="center"/>
  </p>
  <p><span class="Estilo2">Nueva Practica No Nomenclada </span>  </p>
  <form id="form1" name="form1">
  <table width="322" border="0">
      <tr>
        <td width="195"><div align="right">
          <select name="tipo" id="tipo">
            <option value=0>Seleccione Tipo de Practica</option>
            <?php 
				$sqlTipos = "SELECT * FROM tipopracticas";
				$resTipos = mysql_query($sqlTipos,$db);
				while($rowTipos = mysql_fetch_assoc($resTipos)) { ?>
            <option value=<?php echo $rowTipos['id'] ?>><?php echo $rowTipos['descripcion'] ?></option>
            <?php } ?>
          </select>
        </div></td>
        <td width="111"><div align="left"></div></td>
      </tr>
      <tr>
        <td><div align="right">
          <select name="capitulo" id="capitulo" onchange="deshabilitarNuevoCap(this.value)" disabled="disabled">
            <option value=0>Seleccione Capitulo</option>
          </select>
        </div></td>
        <td>
          <div align="left">
            <input type="button" name="nuevoCap" id="nuevoCap" value="Nuevo Capitulo" disabled="disabled"/>
          </div>
      </td>
      </tr>
      <tr>
        <td><div align="right">
          <select name="subcapitulo" id="subcapitulo" onchange="deshabilitarNuevoSub(this.value)" disabled="disabled">
            <option value=0>Seleccione SubCapitulo</option>
          </select>
        </div></td>
        <td><div align="left">
          <input type="button" name="nuevoSub" id="nuevoSub" value="Nuevo SubCapitulo" disabled="disabled"/>
        </div></td>
      </tr>
    </table>
	</form>
	
	<!--Formulario de Carga -->
	<form id="formularioCargaCapitulo" name="formularioCargaCapitulo" onSubmit='return validarCapituloSubcapitulo(this)' method="post" action="guardarNuevoCapitulo.php">
	</form>
	<form id="formularioCargaSubCapitulo" name="formularioCargaSubCapitulo" onSubmit='return validarCapituloSubcapitulo(this)' method="post" action="guardarNuevoSubCapitulo.php">
	</form>
    <form id="formularioCargaPractica" name="formularioCargaPractica" onSubmit='return validarPractica(this)' method="post" action="guardarNuevaPractica.php">
	</form>
	<!------------------------->
</div>
</body>
</html>
