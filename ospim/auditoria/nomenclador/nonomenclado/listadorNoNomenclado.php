<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listador No Nomenclado :.</title>
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

<link rel="stylesheet" href="/lib/jquery.tablesorter/themes/theme.blue.css">
<script src="/lib/jquery.js"></script>
<script src="/lib/jquery-ui.min.js"></script>
<script src="/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script src="/lib/jquery.maskedinput.js" type="text/javascript"></script>
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
		$("#subcapitulo").html("<option value='0'>Seleccione SubCapitulo</option>");
		$("#practicas").html("");
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
				} else {
					$.ajax({
						type: "POST",
						dataType: 'html',
						url: "getPracticas.php",
						data: {valor:-1},
					}).done(function(respuesta){
						$("#practicas").html(respuesta);
					});
				}
			}
		});
	});
	
	$("#capitulo").change(function(){
		$("#subcapitulo").html("<option value='0'>Seleccione SubCapitulo</option>");
		$("#practicas").html("");
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
			} else {
				$.ajax({
					type: "POST",
					dataType: 'html',
					url: "getPracticas.php",
					data: {valor:valor[1]},
				}).done(function(respuesta){
					$("#practicas").html(respuesta);
				});
			}
		});
	});
	
	$("#subcapitulo").change(function(){
		var valor = $(this).val();
		valor = valor.split('-');
		$.ajax({
			type: "POST",
			dataType: 'html',
			url: "getPracticas.php",
			data: {valor:valor[1]},
		}).done(function(respuesta){
			$("#practicas").html(respuesta);
		});
	});
});


</script>

<body bgcolor="#CCCCCC">
<div align="center">
  <p>
    <input type="reset" name="volver" value="Volver" onclick="location.href = 'menuNoNomenclado.php'" align="center"/>
  </p>
  <p><span class="Estilo2">Listador Practicas No Nomencladas </span>  </p>
  <form id="form1" name="form1" method="post" action="">
    <p>
	  <?php 
			$sqlTipos = "SELECT * FROM tipopracticas";
			$resTipos = mysql_query($sqlTipos,$db);
	  ?>	
      <select name="tipo" id="tipo">
	  		  <option value=0>Seleccione Tipo de Practica</option>
		<?php while($rowTipos = mysql_fetch_assoc($resTipos)) { ?>
			  <option value=<?php echo $rowTipos['id'] ?>><?php echo $rowTipos['descripcion'] ?></option>
		<?php } ?>
      </select>
    </p>
	<p>
      <select name="capitulo" id="capitulo">
	  	<option value=0>Seleccione Capitulo</option>
      </select>
    </p>
	<p>
      <select name="subcapitulo" id="subcapitulo">
	  	<option value=0>Seleccione SubCapitulo</option>
      </select>
	</p>
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
