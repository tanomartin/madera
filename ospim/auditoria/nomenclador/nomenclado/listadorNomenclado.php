<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
$idNomenclador = $_GET['codigo'];
$nomenclador = $_GET['nombre'];
$contrato = $_GET['contrato'];?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listador Nomenclador Nacional :.</title>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
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
		$("#practicas").html("");
		var nomenclador = $("#nomenclador").val();
		var contrato = $("#contrato").val();
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
					$("#capitulo").prop("disabled",false);
				} 
				$.ajax({
					type: "POST",
					dataType: 'html',
					url: "getPracticas.php",
					data: {valor:-1, tipo:valor, nomenclador:nomenclador, contrato:contrato},
				}).done(function(respuesta){
					if (respuesta != 0) {	
						$("#practicas").html(respuesta);
					}
				});
			}
		});
	});
	
	$("#capitulo").change(function(){
		$("#subcapitulo").html("<option value='0'>Seleccione SubCapitulo</option>");
		$("#subcapitulo").prop("disabled",true);	
		$("#practicas").html("");
		var nomenclador = $("#nomenclador").val();
		var valor = $(this).val();
		valor = valor.split('-');
		var contrato = $("#contrato").val();
		tipo = $("#tipo").val();
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
				url: "getPracticas.php",
				data: {valor:valor[1], tipo:tipo, nomenclador:nomenclador, contrato:contrato, padre:valor[0]},
			}).done(function(respuesta){
				if (respuesta != 0) {
					$("#practicas").html(respuesta);
				}
			});
		});
	});
	
	$("#subcapitulo").change(function(){
		$("#practicas").html("");
		tipo = $("#tipo").val();
		var nomenclador = $("#nomenclador").val();
		var valor = $(this).val();
		var contrato = $("#contrato").val();
		if (valor == 0) { 
			valor = $("#capitulo").val();
			valor = valor.split('-');
			var contrato = $("#contrato").val();
			$.ajax({
				type: "POST",
				dataType: 'html',
				url: "getPracticas.php",
				data: {valor:valor[1], tipo:tipo, nomenclador:nomenclador, contrato:contrato, padre:valor[0]},
			}).done(function(respuesta){
				if (respuesta != 0) {
					$("#practicas").html(respuesta);
				}
			});
		} else {
			valor = valor.split('-');
			$.ajax({
				type: "POST",
				dataType: 'html',
				url: "getPracticas.php",
				data: {valor:valor[1], tipo:tipo, nomenclador:nomenclador, contrato:contrato, padre:valor[0]},
			}).done(function(respuesta){
				if (respuesta != 0) {
					$("#practicas").html(respuesta);
				}
			});
		}
	});
});


</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = 'menuNomenclado.php'" /></p>
  <h3>Listador Nomenclador <?php echo $nomenclador ?></h3>
  <form id="listaNomencla" name="listaNomencla" method="post">
  	<input style="display: none" type="text" id="nomenclador" value="<?php echo $idNomenclador ?>"/>
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
	<p><input type="text" id="contrato" name="contrato" value="<?php echo $contrato ?>" style="display: none"/></p>
	<table style="text-align:center; width:1000px" id="practicas" class="tablesorter" >
     <thead>
     </thead>
     <tbody>
	 </tbody>
   </table>
  </form>
</div>
</body>
</html>
