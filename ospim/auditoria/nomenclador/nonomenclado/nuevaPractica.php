<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Nueva Practica :.</title>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">

function abrirPantalla(dire) {
	a= window.open(dire,"nuevaPractica",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=10, left=10");
}
	
jQuery(function($){	
	
	$("#tipo").change(function(){
		var valor = $(this).val();
		//reset Capitulo
		$("#capitulo").html("<option value='0'>Seleccione Capitulo</option>");
		$("#capitulo").prop("disabled",true);
		if (valor != 0) {
			$("#nuevoCap").prop("disabled",false);
		} else {
			$("#nuevoCap").prop("disabled",true);
		}
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
			url: "../lib/getCapitulos.php",
			data: {valor:valor, tipo:valor},
		}).done(function(respuesta){
			if (valor != 0) {
				if (respuesta != 0) {
					$("#capitulo").html(respuesta);
					$("#capitulo").prop("disabled",false);
					$("#nuevoCap").prop("disabled",false);
				} 
				$.ajax({
					type: "POST",
					dataType: 'html',
					url: "cargarNuevaPractica.php",
					data: {valor:-1, tipo:valor},
				}).done(function(respuesta){
					$("#formularioCargaPractica").html(respuesta);
				});
			}
		});
	});
	
	$("#capitulo").change(function(){
		var valor = $(this).val();
		valor = valor.split('-');
		var tipo = $("#tipo").val();
		//reset SubCapitulo
		$("#subcapitulo").html("<option value='0'>Seleccione SubCapitulo</option>");
		$("#subcapitulo").prop("disabled",true);
		if (valor != 0) {
			$("#nuevoSub").prop("disabled",false);
		} else {
			$("#nuevoSub").prop("disabled",true);
		}
		//reset formulario de carga
		$("#formularioCargaCapitulo").html("");
		$("#formularioCargaSubCapitulo").html("");
		$("#formularioCargaPractica").html("");
		
		$.ajax({
			type: "POST",
			dataType: 'html',
			url: "../lib/getSubCapitulos.php",
			data: {valor:valor[0], tipo:tipo},
		}).done(function(respuesta){
			if (respuesta != 0) {
				$("#subcapitulo").html(respuesta);	
				$("#subcapitulo").prop("disabled",false);
				$("#nuevoSub").prop("disabled",false);	
			} 
			$.ajax({
				type: "POST",
				dataType: 'html',
				url: "cargarNuevaPractica.php",
				data: {valor:valor[1], tipo:tipo, padre: valor[0]},
			}).done(function(respuesta){
				$("#formularioCargaPractica").html(respuesta);
			});
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
			data: {valor:valor[1], tipo:tipo, padre: valor[0]},
		}).done(function(respuesta){
			$("#formularioCargaPractica").html(respuesta);
		});
	});
	
	$("#nuevoCap").click(function() {
		//reset formulario de carga
		$("#formularioCargaCapitulo").html("");
		$("#formularioCargaSubCapitulo").html("");
		$("#formularioCargaPractica").html("");
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
		//reset formulario de carga
		$("#formularioCargaCapitulo").html("");
		$("#formularioCargaSubCapitulo").html("");
		$("#formularioCargaPractica").html("");
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
	if (codigo != "") {
		if(esEnteroPositivo(codigo)) {
			if((codigo < 0 || codigo > 99) && tipo != -1) {
				alert("Debe ingresar un codigo de dos digitos entre 00 y 99 para este tipo de practica");
				return false;
			}
			if((codigo < 1 || codigo > 10000) && tipo == -1) {
				alert("Debe ingresar un codigo de cuatro digitos entre 1 y 10000 para este tipo de practica");
				return false;
			}
		} else {
			alert("Debe ingresar un codigo de dos digitos entre 01 y 99 para la practica");
			return false;
		}
	} else {
		alert("Debe ingresar un codigo para la practica");
		return false;
	}
	if(formulario.descri.value == "") {
		alert("Debe ingresar una descripci�n para la practica");
		return false;
	}
	if (formulario.complejidad.value == 0) {
		alert("Debe especificar la si aplica o no la resolucion 650");
		return false;
	}
	if (formulario.internacion.value == "") {
		alert("Debe especificar si la practica es una internaci�n o no");
		return false;
	}
	formulario.Submit.disabled = true;
	return true;
}

function validarCapituloSubcapitulo(formulario) {
	var codigo = formulario.codigo.value;
	if (codigo != "") {
		if(esEnteroPositivo(codigo)) {
			if(codigo < 0 || codigo > 99) {
				alert("Debe ingresar un codigo de dos digitos entre 00 y 99 para este tipo de practica");
				return false;
			}
		} else {
			alert("Debe ingresar un codigo de dos digitos entre 00 y 99 para la practica");
			return false;
		}
	} else {
		alert("Debe ingresar un codigo para el capitulo o subcapitulo a guardar");
		return false;
	}
	if(formulario.descri.value == "") {
		alert("Debe ingresar una descripci�n del capitulo o subcapitulo a guardar");
		return false;
	}
	formulario.Submit.disabled = true;
	return true;
}


</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p>
    <input type="button" name="volver" value="Volver" onclick="location.href = 'menuNoNomenclado.php'"/>
  </p>
  <h3>Nueva Practica No Nomenclada </h3>
  <?php if (isset($_GET['id'])) { 
  			$idpracticanueva = $_GET['id'];
  			$sqlPractica = "SELECT p.codigopractica, p.descripcion, t.descripcion as tipo
					  		FROM practicas p, tipopracticasnomenclador tn, tipopracticas t
					  		WHERE p.idpractica = $idpracticanueva and 
					  			  p.tipopractica = tn.id and 
					  			  tn.idtipo = t.id";
  			$resPractica = mysql_query($sqlPractica,$db);
  			$rowPractica = mysql_fetch_assoc($resPractica); ?>
  			<p><b style="color: blue">Se cre� correctamente la practica</b></p>
  			<p><b style="color: blue">"<?php echo $rowPractica['codigopractica']." - ".$rowPractica['descripcion']." - ".$rowPractica['tipo'] ?>"</b></p>
  <?php	} ?>
  <form id="form1" name="form1">
  <table width="322" border="0">
      <tr>
        <td width="195"><div align="right">
          <select name="tipo" id="tipo">
            <option value='0'>Seleccione Tipo de Practica</option>
            <?php 
				$sqlTipos = "SELECT tn.id, t.descripcion FROM tipopracticas t, tipopracticasnomenclador tn WHERE tn.codigonomenclador = 2 and tn.idtipo = t.id";
				$resTipos = mysql_query($sqlTipos,$db);
				while($rowTipos = mysql_fetch_assoc($resTipos)) { ?>
            <option value='<?php echo $rowTipos['id'] ?>'><?php echo $rowTipos['descripcion'] ?></option>
            <?php } ?>
          </select>
        </div></td>
        <td width="111"><div align="left"></div></td>
      </tr>
      <tr>
        <td><div align="right">
          <select name="capitulo" id="capitulo" onchange="deshabilitarNuevoCap(this.value)" disabled="disabled">
            <option value='0'>Seleccione Capitulo</option>
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
            <option value='0'>Seleccione SubCapitulo</option>
          </select>
        </div></td>
        <td><div align="left">
          <input type="button" name="nuevoSub" id="nuevoSub" value="Nuevo SubCapitulo" disabled="disabled"/>
        </div></td>
      </tr>
    </table>
	</form>
	
	<!--Formulario de Carga -->
	<form id="formularioCargaCapitulo" name="formularioCargaCapitulo" onsubmit='return validarCapituloSubcapitulo(this)' method="post" action="guardarNuevoCapitulo.php">
	</form>
	<form id="formularioCargaSubCapitulo" name="formularioCargaSubCapitulo" onsubmit='return validarCapituloSubcapitulo(this)' method="post" action="guardarNuevoSubCapitulo.php">
	</form>
    <form id="formularioCargaPractica" name="formularioCargaPractica" onsubmit='return validarPractica(this)' method="post" action="guardarNuevaPractica.php">
	</form>
	<!------------------------->
</div>
</body>
</html>
