<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado De Beneficiarios por Localidad :.</title>

<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>
<style type="text/css" media="print">
.nover {display:none}
</style>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">

function validar(formulario) {
	if (formulario.localidad.value == 0) {
		alert("Debe elegir una Localidad");
		return false;
	}
	$.blockUI({ message: "<h1>Generando Archivo<br>Aguarde por favor...</h1>" });
	return true;
}

jQuery(function($){	
	$("#provincia").change(function(){
		var valor = $(this).val();
		valor = valor.split('-');
		$.ajax({
			type: "POST",
			dataType: 'html',
			url: "getLocalidades.php",
			data: {valor:valor[0]},
		}).done(function(respuesta){
			if (respuesta != 0) {
				$("#localidad").html(respuesta);
			}
		});
	});	
});


</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  	<p><input type="reset" name="volver" value="Volver" class="nover" onclick="location.href = 'moduloInformes.php'" /></p>
	<form  name="listadoEmpresa" id="listadoEmpresa" method="post" onsubmit="return validar(this)" action="beneficiariosPorLocalidadExcel.php">
  	<p><span class="Estilo2">Beneficiarios por Localidad </span></p>
  	<?php if (isset($_GET['error'])) { 
			if ($_GET['error'] == 0) {
				$localidad = $_GET['locali'];
				print("<p><font color='#0000FF'><b> Se generó correctamente el informe de la localidad $localidad.<br>Lo encontrara en la carpeta correspondiente </b></font></p>");
		 	} 
			if ($_GET['error'] == 1) {
				$descerror = $_GET['mensaje'];
				print("<p><font color='#FF0000'><b> Hubo un error. $descerror. Comuníquese con el Dpto. de Sistemas </b></font></p>");
			}
  	 } ?>
	<p><strong>Provincia</strong>
		<select name="provincia" id="provincia" class="nover">
			<option value="-1" selected="selected">Seleccione un Valor </option>
				  <?php 
							$sqlProvi="SELECT * FROM provincia";
							$resProvi= mysql_query($sqlProvi,$db);
							while ($rowProvi=mysql_fetch_array($resProvi)) { 	?>
				  <option value="<?php echo $rowProvi['codprovin']."-".$rowProvi['descrip'] ?>"><?php echo utf8_encode($rowProvi['descrip'])  ?></option>
				  <?php } ?>
		</select></p>	
	<p><strong>Localidad</strong>
		<select name="localidad" id="localidad" class="nover">
			<option value="0" selected="selected">Seleccione Localidad</option>
		</select></p>
	<p><input type="submit" name="Submit" value="Generar Archivo" class="nover"/></p>
	</form>
</div>
</body>
</html>
