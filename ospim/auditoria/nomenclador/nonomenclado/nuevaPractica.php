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
<script type="text/javascript">

function abrirPantalla(dire) {
	a= window.open(dire,"nuevaPractica",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=10, left=10");
}
	
jQuery(function($){	
	$("#tipo").change(function(){
		$("#capitulo").html("<option value='0'>Seleccione Capitulo</option>");
		$("#nuevoCap").prop("disabled",true);
		$("#subcapitulo").html("<option value='0'>Seleccione SubCapitulo</option>");
		$("#nuevoSub").prop("disabled",true);
		$("#nuevaPractica").html("");
		var valor = $(this).val();
		$.ajax({
			type: "POST",
			dataType: 'html',
			url: "getCapitulos.php",
			data: {valor:valor},
		}).done(function(respuesta){
			if (valor != 0) {
				if (respuesta != 0) {
					$("#capitulo").html(respuesta);
					$("#nuevoCap").prop("disabled",false);
				} else {
					$.ajax({
						type: "POST",
						dataType: 'html',
						url: "cargarNuevaPractica.php",
						data: {valor:-1},
					}).done(function(respuesta){
						$("#nuevaPractica").html(respuesta);
					});
				}
			}
		});
	});
	
	$("#capitulo").change(function(){
		$("#subcapitulo").html("<option value='0'>Seleccione SubCapitulo</option>");
		$("#nuevoSub").prop("disabled",true);
		$("#nuevaPractica").html("");
		var valor = $(this).val();
		valor = valor.split('-');
		$.ajax({
			type: "POST",
			dataType: 'html',
			url: "getSubCapitulos.php",
			data: {valor:valor[0]},
		}).done(function(respuesta){
			if (respuesta != 0) {
				$("#subcapitulo").html(respuesta);	
				$("#nuevoSub").prop("disabled",false);	
			} else {
				if ($("#tipo").val() == 2 || $("#tipo").val() == 4) {
					$("#nuevoSub").prop("disabled",false);
				} else {
					$.ajax({
						type: "POST",
						dataType: 'html',
						url: "cargarNuevaPractica.php",
						data: {valor:valor[1]},
					}).done(function(respuesta){
						$("#nuevaPractica").html(respuesta);
					});
				}
			}
		});
	});
	
	$("#subcapitulo").change(function(){
		var valor = $(this).val();
		valor = valor.split('-');
		$.ajax({
			type: "POST",
			dataType: 'html',
			url: "cargarNuevaPractica.php",
			data: {valor:valor[1]},
		}).done(function(respuesta){
			$("#nuevaPractica").html(respuesta);
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

</script>

<body bgcolor="#CCCCCC">
<div align="center">
  <p>
    <input type="reset" name="volver" value="Volver" onclick="location.href = 'menuNoNomenclado.php'" align="center"/>
  </p>
  <p><span class="Estilo2">Nueva Practica No Nomenclada </span>  </p>
    <?php 
			$sqlTipos = "SELECT * FROM tipopracticas";
			$resTipos = mysql_query($sqlTipos,$db);
	  ?>
  <form id="form1" name="form1" method="post" action="">
  <table width="322" border="0">
      <tr>
        <td width="195"><div align="right">
          <select name="tipo" id="tipo">
            <option value=0>Seleccione Tipo de Practica</option>
            <?php while($rowTipos = mysql_fetch_assoc($resTipos)) { ?>
            <option value=<?php echo $rowTipos['id'] ?>><?php echo $rowTipos['descripcion'] ?></option>
            <?php } ?>
          </select>
        </div></td>
        <td width="111"><div align="left"></div></td>
      </tr>
      <tr>
        <td><div align="right">
          <select name="capitulo" id="capitulo" onchange="deshabilitarNuevoCap(this.value)">
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
          <select name="subcapitulo" id="subcapitulo" onchange="deshabilitarNuevoSub(this.value)">
            <option value=0>Seleccione SubCapitulo</option>
          </select>
        </div></td>
        <td><div align="left">
          <input type="button" name="nuevoSub" id="nuevoSub" value="Nuevo SubCapitulo" disabled="disabled"/>
        </div></td>
      </tr>
    </table>
	</form>
	<br>
    <form id="nuevaPractica" name="nuevaPractica" method="post" action="guardarNuevaPractica.php">
	</form>
</div>
</body>
</html>
