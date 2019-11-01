<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");

$sqlNomen = "SELECT * FROM nomencladores WHERE contrato = 0";
$resNomen = mysql_query($sqlNomen,$db); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Menu Resoluciones :.</title>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script type="text/javascript">

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
	$("#nomenclador").change(function(){
		$("#resolucion").html("<option value='0'>Seleccione Resoluciones</option>");
		$("#resolucion").prop("disabled",true);
		$("#practicas").html("");
		var nomenclador = $(this).val();
		if (nomenclador != 0) {
			$.ajax({
				type: "POST",
				dataType: 'html',
				url: "getResoluciones.php",
				data: {nomenclador:nomenclador},
			}).done(function(respuesta){
				if (respuesta != 0) {
					$("#resolucion").html(respuesta);
					$("#resolucion").prop("disabled",false);
				}
			});
		}
	});

	$("#resolucion").change(function(){
		var resolucion = $(this).val();
		if (resolucion != 0) {
			$.ajax({
				type: "POST",
				dataType: 'html',
				url: "getPracticas.php",
				data: {resolucion:resolucion},
			}).done(function(respuesta){
				$("#practicas").html(respuesta);	
			});
		} else {
			$("#practicas").html("<thead></thead><tbody></tbody>");	
		}
	});
	
});

</script>

</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = '../menuNomenclador.php'" /></p>
  <h3>Módulo Resoluciones</h3>
  <p>
	 <select id="nomenclador" name="nomenclador">
	  	<option value="">Seleccione Nomenclador</option>
	  	<?php while($rowNomen = mysql_fetch_assoc($resNomen)) { ?>
	  		<option value="<?php echo $rowNomen['id']."-".$rowNomen['contrato'] ?>"><?php echo $rowNomen['nombre']?></option>
	  	<?php }?>
	  </select>
  </p>
  <p>
  	<select id="resolucion" name="resolucion" disabled="disabled">
  		<option value="0">Seleccione Resoluciones</option>
  	</select>
  </p>
  <table style="text-align:center; width:1000px" id="practicas" class="tablesorter" >
  	 <thead>
     </thead>
     <tbody>
	 </tbody>
  </table>
</div>
</body>
</html>