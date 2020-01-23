<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
$idNomenclador = $_GET['codigo'];
$nomenclador = $_GET['nombre'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Carga de Valores Nomenclador Nacional :.</title>

<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>

<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">

function abrirPantalla(dire) {
	a= window.open(dire,"detallePresatadoresPracticas",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=10, left=10");
}

$(function() {
	$("#practicas")
	.tablesorter({
		theme: 'blue', 
		widthFixed: true, 
		widgets: ["zebra", "filter"], 
		widgetOptions : { 
			filter_cssFilter   : '',
			filter_childRows   : false,
			filter_hideFilters : false,
			filter_ignoreCase  : true,
			filter_searchDelay : 300,
			filter_startsWith  : false,
			filter_hideFilters : false,
		}
	})
});
	
jQuery(function($){	
	$("#tipo").change(function(){
		$("#capitulo").html("<option value='0'>Seleccione Capitulo</option>");
		$("#capitulo").prop("disabled",true);
		$("#subcapitulo").html("<option value='0'>Seleccione SubCapitulo</option>");
		$("#subcapitulo").prop("disabled",true);
		$("#res650total").val(0);
		$("#res650total").prop("disabled",true);
		$("#intetotal").val(0);
		$("#intetotal").prop("disabled",true);
		$("#practicas").html("");
		$("#guardar").css("display", "none");
		var valor = $(this).val();
		var nomenclador = $("#nomenclador").val();
		$.ajax({
			type: "POST",
			dataType: 'html',
			url: "getCapitulos.php",
			data: {valor:valor},
		}).done(function(respuesta){
			if (valor != 0) {
				if (respuesta != 0) {
					$("#capitulo").html(respuesta);
					$("#capitulo").prop("disabled",false);
				} else {
					$.ajax({
						type: "POST",
						dataType: 'html',
						url: "getPracticasPropiedades.php",
						data: {valor:-1, tipo:valor, nomenclador:nomenclador},
					}).done(function(respuesta){
						$("#practicas").html(respuesta);
						$("#res650total").prop("disabled",false);
						$("#intetotal").prop("disabled",false);
						$("#guardar").prop("disabled",false);	
						$("#guardar").css("display", "block");
					});
				}
			}
		});
	});
	
	$("#capitulo").change(function(){
		$("#subcapitulo").html("<option value='0'>Seleccione SubCapitulo</option>");
		$("#subcapitulo").prop("disabled",true);
		$("#res650total").val(0);
		$("#res650total").prop("disabled",true);
		$("#intetotal").val(0);
		$("#intetotal").prop("disabled",true);
		$("#practicas").html("");
		$("#guardar").css("display", "none");
		tipo = $("#tipo").val();
		var valor = $(this).val();
		valor = valor.split('-');
		var nomenclador = $("#nomenclador").val();
		$.ajax({
			type: "POST",
			dataType: 'html',
			url: "getSubCapitulos.php",
			data: {valor:valor[0]},
		}).done(function(respuesta){
			if (respuesta != 0) {
				$("#subcapitulo").html(respuesta);	
				$("#subcapitulo").prop("disabled",false);			
			}
			$.ajax({
				type: "POST",
				dataType: 'html',
				url: "getPracticasPropiedades.php",
				data: {valor:valor[1], tipo:tipo, nomenclador:nomenclador},
			}).done(function(respuesta){
				if (respuesta != 0) {
					$("#practicas").html(respuesta);
					$("#res650total").prop("disabled",false);
					$("#intetotal").prop("disabled",false);
					$("#guardar").prop("disabled",false);
					$("#guardar").css("display", "block");
				}
			});
		});
	});
	
	$("#subcapitulo").change(function(){
		$("#res650total").val(0);
		$("#res650total").prop("disabled",true);
		$("#intetotal").val(0);
		$("#intetotal").prop("disabled",true);
		var valor = $(this).val();
		var tipo = $("#tipo").val();
		var nomenclador = $("#nomenclador").val();
		if(valor == 0) {
			valor = $("#capitulo").val();
			valor = valor.split('-');
			$.ajax({
				type: "POST",
				dataType: 'html',
				url: "getPracticasPropiedades.php",
				data: {valor:valor[1],tipo:tipo,nomenclador:nomenclador},
			}).done(function(respuesta){
				if (respuesta != 0) {
					$("#practicas").html(respuesta);
					$("#guardar").prop("disabled",false);
					$("#guardar").css("display", "block");
				} else {
					$("#guardar").css("display", "none");
					$("#practicas").html("");
				}
			});
		} else {		
			valor = valor.split('-');
			$.ajax({
				type: "POST",
				dataType: 'html',
				url: "getPracticasPropiedades.php",
				data: {valor:valor[1],tipo:tipo,nomenclador:nomenclador},
			}).done(function(respuesta){
				if (respuesta != 0) {
					$("#practicas").html(respuesta);
					$("#res650total").prop("disabled",false);
					$("#intetotal").prop("disabled",false);
					$("#guardar").prop("disabled",false);	
					$("#guardar").css("display", "block");
				}
			});
		}
	});
});

function cambiarClasificaion(valor) {
	var tabla = document.getElementById('practicas');
	var nombre = "";
	cantFilas = tabla.rows.length;
	cantFilas--;
	console.log(cantFilas);
	for (var i = 0; i < cantFilas; i++) {
		nombre = "complejidad" + i;
		selectElemento = document.getElementById(nombre);
		selectElemento.value = valor;
	}
}

function cambiarInternacion(valor) {
	var tabla = document.getElementById('practicas');
	var nombre = "";
	cantFilas = tabla.rows.length;
	cantFilas--;
	for (var i = 0; i < cantFilas; i++) {
		nombre = "internacion" + i;
		selectElemento = document.getElementById(nombre);
		selectElemento.value = valor;
	}
}

function validar(formulario) {
	formulario.nomenclador.disabled = true;
	formulario.tipo.disabled = true;
	formulario.capitulo.disabled = true;
	formulario.subcapitulo.disabled = true;
	formulario.res650total.disabled = true;
	formulario.intetotal.disabled = true;
	formulario.guardar.disabled = true;
	$.blockUI({ message: "<h1>Guardando Propiedades de las Practicas. Aguarde un minuto</h1>" });
	return true;
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p>
    <input type="button" name="volver" value="Volver" onclick="location.href = 'menuNomenclado.php'" />
  </p>
  <p><span class="Estilo2">Carga de Valores del Nomenclador <?php echo $nomenclador ?> </span>  </p>
  <form id="form1" name="form1" method="post" onsubmit="return validar(this)" action="guardarPropiedadesNomenclado.php">
    <input type="text" id="nomenclador" name="nomenclador" value="<?php echo $idNomenclador?>" style="display: none"/>
    <p>
      <select name="tipo" id="tipo">
	  		  <option value='0'>Seleccione Tipo de Practica</option>
		<?php $sqlTipos = "SELECT tn.id, t.descripcion FROM tipopracticas t, tipopracticasnomenclador tn WHERE tn.codigonomenclador = $idNomenclador and tn.idtipo = t.id";
			  $resTipos = mysql_query($sqlTipos,$db);
			  while($rowTipos = mysql_fetch_assoc($resTipos)) { ?>
			  <option value='<?php echo $rowTipos['id'] ?>'><?php echo $rowTipos['descripcion'] ?></option>
		<?php } ?>
      </select>
    </p>
	<p>
      <select name="capitulo" id="capitulo" disabled="disabled">
	  	<option value='0'>Seleccione Capitulo</option>
      </select>
    </p>
	<p>
      <select name="subcapitulo" id="subcapitulo" disabled="disabled">
	  	<option value='0'>Seleccione SubCapitulo</option>
      </select>
	</p>
	<p><b>Complejidad Capitulo Completo</b>
		<select name="res650total" id="res650total" disabled="disabled" onchange="cambiarClasificaion(this.value)">
	<?php   $sqlComplejida = "SELECT * FROM tipocomplejidad";
			$resComplejida = mysql_query($sqlComplejida,$db);
			while($rowComplejida = mysql_fetch_assoc($resComplejida)) { ?>
				<option value="<?php echo $rowComplejida['codigocomplejidad']?>"><?php echo $rowComplejida['descripcion']?></option>
	  <?php } ?>
		</select>
	</p>
	<p><b>Internacino Capitulo Completo</b>
		<select name="intetotal" id="intetotal" disabled="disabled" onchange="cambiarInternacion(this.value)">
			<option value="0" selected="selected">NO</option>
			<option value="1">SI</option>
		</select>
	</p>
	<table style="text-align:center; width:1000px" id="practicas" class="tablesorter" >
     <thead>
     </thead>
     <tbody>
	 </tbody>
   </table>
   <input type='submit' id='guardar' name='guardar' value='Guardar Cambios' style="display:none"/>
  </form>
</div>
</body>
</html>
