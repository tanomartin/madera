<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
$iddiagnostico = NULL;
$nroafiliado = NULL;
$nroorden = NULL;
$estafiliado = NULL;
if(isset($_GET['idDiag'])) {
	$iddiagnostico = $_GET['idDiag'];
	if(isset($_GET['nroAfi'])) {
		$nroafiliado=$_GET['nroAfi'];
		if(isset($_GET['nroOrd'])) {
			$nroorden=$_GET['nroOrd'];
			if(isset($_GET['estAfi'])) {
				$estafiliado=$_GET['estAfi'];
				$sqlLeeFarmacos = "SELECT * FROM diabetesfarmacos WHERE iddiagnostico = $iddiagnostico";
				$resLeeFarmacos = mysql_query($sqlLeeFarmacos,$db);
				$rowLeeFarmacos = mysql_fetch_array($resLeeFarmacos);	
				if($nroorden == 0) {
					if(strcmp($estafiliado, 'A')==0) {
						$sqlLeeAfiliado = "SELECT nroafiliado, apellidoynombre, nrodocumento, cuil, fechanacimiento, YEAR(CURDATE())-YEAR(fechanacimiento)+IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(fechanacimiento,'%m-%d'), 0, -1) AS `edadactual` FROM titulares WHERE nroafiliado = $nroafiliado";
					}
					if(strcmp($estafiliado, 'I')==0) {
						$sqlLeeAfiliado = "SELECT nroafiliado, apellidoynombre, nrodocumento, cuil, fechabaja, fechanacimiento, YEAR(CURDATE())-YEAR(fechanacimiento)+IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(fechanacimiento,'%m-%d'), 0, -1) AS `edadactual` FROM titularesdebaja WHERE nroafiliado = $nroafiliado";
					}
				} else {
					if(strcmp($estafiliado, 'A')==0) {
						$sqlLeeAfiliado = "SELECT f.nroafiliado, f.nroorden, k.descrip, f.apellidoynombre, f.nrodocumento, f.cuil, f.fechanacimiento, YEAR(CURDATE())-YEAR(f.fechanacimiento)+IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(f.fechanacimiento,'%m-%d'), 0, -1) AS `edadactual` FROM familiares f, parentesco k WHERE f.nroafiliado = $nroafiliado AND f.nroorden = $nroorden AND f.tipoparentesco = k.codparent";
					}
					if(strcmp($estafiliado, 'I')==0) {
						$sqlLeeAfiliado = "SELECT f.nroafiliado, f.nroorden, k.descrip, f.apellidoynombre, f.nrodocumento, f.cuil, f.fechabaja, f.fechanacimiento, YEAR(CURDATE())-YEAR(f.fechanacimiento)+IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(f.fechanacimiento,'%m-%d'), 0, -1) AS `edadactual` FROM familiaresdebaja f, parentesco k WHERE f.nroafiliado = $nroafiliado AND f.nroorden = $nroorden AND f.tipoparentesco = k.codparent";
					}
				}
				$resLeeAfiliado = mysql_query($sqlLeeAfiliado,$db);
				$rowLeeAfiliado = mysql_fetch_array($resLeeAfiliado);
	
				if($nroorden == 0) {
					$tipoAfiliado = 'Titular';
				} else {
					$tipoAfiliado = 'Familiar '.$rowLeeAfiliado['descrip'];
				}
	
				if(strcmp($estafiliado, 'A')==0) {
					$estadoAfiliado = 'Activo';
				}
				if(strcmp($estafiliado, 'I')==0) {
					$estadoAfiliado = 'Inactivo desde '.invertirFecha($rowLeeAfiliado['fechabaja']);
				}

	     		$sqlBuscaInsulinaBasal="SELECT * FROM diabetesinsulinas";
				$resBuscaInsulinaBasal=mysql_query($sqlBuscaInsulinaBasal,$db);
	     		$sqlBuscaInsulinaCorrecion="SELECT * FROM diabetesinsulinas";
				$resBuscaInsulinaCorrecion=mysql_query($sqlBuscaInsulinaCorrecion,$db);
			}
		}
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1" />
<title>.: Diabeticos :.</title>
<link rel="stylesheet" href="/madera/lib/style.css">
<link rel="stylesheet" href="/madera/lib/general.css" />
<link rel="stylesheet" href="/madera/lib/jquery-ui-1.9.2.custom/css/smoothness/jquery-ui-1.9.2.custom.css"/>
<script src="/madera/lib/jquery-ui-1.9.2.custom/js/jquery-1.8.3.js" type="text/javascript"></script>
<script src="/madera/lib/jquery-ui-1.9.2.custom/js/jquery-ui-1.9.2.custom.js" type="text/javascript"></script>
<script src="/madera/lib/jquery-ui-1.9.2.custom/js/ui.datepicker-es.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/inputmask/dist/jquery.inputmask.bundle.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
	$("#metforminainicio").inputmask('integer');
	$("#sulfonilureasinicio").inputmask('integer');
	$("#idpp4inicio").inputmask('integer');
	$("#insulinabasalinicio").inputmask('integer');
	$("#insulinacorreccioninicio").inputmask('integer');
	$("#otros1inicio").inputmask('integer');
	$("#otros2inicio").inputmask('integer');
	if($("#metformina").prop('checked') ) {
		$("#metforminapresentacion").attr('disabled', false);
		$("#metforminadosis").attr('disabled', false);
		$("#metforminainicio").attr('disabled', false);
	} else {
		$("#metforminapresentacion").val('');
		$("#metforminapresentacion").attr('disabled', true);
		$("#metforminadosis").val('');
		$("#metforminadosis").attr('disabled', true);
		$("#metforminainicio").val('');
		$("#metforminainicio").attr('disabled', true);
	}
	if($("#sulfonilureas").prop('checked') ) {
		$("#sulfonilureasnombre").attr('disabled', false);
		$("#sulfonilureaspresentacion").attr('disabled', false);
		$("#sulfonilureasdosis").attr('disabled', false);
		$("#sulfonilureasinicio").attr('disabled', false);
	} else {
		$("#sulfonilureasnombre").val('');
		$("#sulfonilureasnombre").attr('disabled', true);
		$("#sulfonilureaspresentacion").val('');
		$("#sulfonilureaspresentacion").attr('disabled', true);
		$("#sulfonilureasdosis").val('');
		$("#sulfonilureasdosis").attr('disabled', true);
		$("#sulfonilureasinicio").val('');
		$("#sulfonilureasinicio").attr('disabled', true);
	}
	if($("#idpp4").prop('checked') ) {
		$("#idpp4nombre").attr('disabled', false);
		$("#idpp4presentacion").attr('disabled', false);
		$("#idpp4dosis").attr('disabled', false);
		$("#idpp4inicio").attr('disabled', false);
	} else {
		$("#idpp4nombre").val('');
		$("#idpp4nombre").attr('disabled', true);
		$("#idpp4presentacion").val('');
		$("#idpp4presentacion").attr('disabled', true);
		$("#idpp4dosis").val('');
		$("#idpp4dosis").attr('disabled', true);
		$("#idpp4inicio").val('');
		$("#idpp4inicio").attr('disabled', true);
	}
	if($("#insulinabasal").prop('checked') ) {
		$("#insulinabasalcodigo").attr('disabled', false);
		$("#insulinabasalpresentacion").attr('disabled', false);
		$("#insulinabasaldosis").attr('disabled', false);
		$("#insulinabasalinicio").attr('disabled', false);
	} else {
		$("#insulinabasalcodigo option[value='']").prop('selected',true);
		$("#insulinabasalcodigo").attr('disabled', true);
		$("#insulinabasalpresentacion").val('');
		$("#insulinabasalpresentacion").attr('disabled', true);
		$("#insulinabasaldosis").val('');
		$("#insulinabasaldosis").attr('disabled', true);
		$("#insulinabasalinicio").val('');
		$("#insulinabasalinicio").attr('disabled', true);
	}
	if($("#insulinacorreccion").prop('checked') ) {
		$("#insulinacorreccioncodigo").attr('disabled', false);
		$("#insulinacorreccionpresentacion").attr('disabled', false);
		$("#insulinacorrecciondosis").attr('disabled', false);
		$("#insulinacorreccioninicio").attr('disabled', false);
	} else {
		$("#insulinacorreccioncodigo option[value='']").prop('selected',true);
		$("#insulinacorreccioncodigo").attr('disabled', true);
		$("#insulinacorreccionpresentacion").val('');
		$("#insulinacorreccionpresentacion").attr('disabled', true);
		$("#insulinacorrecciondosis").val('');
		$("#insulinacorrecciondosis").attr('disabled', true);
		$("#insulinacorreccioninicio").val('');
		$("#insulinacorreccioninicio").attr('disabled', true);
	}
	if($("#otros1").prop('checked') ) {
		$("#otros1nombre").attr('disabled', false);
		$("#otros1presentacion").attr('disabled', false);
		$("#otros1dosis").attr('disabled', false);
		$("#otros1inicio").attr('disabled', false);
	} else {
		$("#otros1nombre").val('');
		$("#otros1nombre").attr('disabled', true);
		$("#otros1presentacion").val('');
		$("#otros1presentacion").attr('disabled', true);
		$("#otros1dosis").val('');
		$("#otros1dosis").attr('disabled', true);
		$("#otros1inicio").val('');
		$("#otros1inicio").attr('disabled', true);
	}
	if($("#otros2").prop('checked') ) {
		$("#otros2nombre").attr('disabled', false);
		$("#otros2presentacion").attr('disabled', false);
		$("#otros2dosis").attr('disabled', false);
		$("#otros2inicio").attr('disabled', false);
	} else {
		$("#otros2nombre").val('');
		$("#otros2nombre").attr('disabled', true);
		$("#otros2presentacion").val('');
		$("#otros2presentacion").attr('disabled', true);
		$("#otros2dosis").val('');
		$("#otros2dosis").attr('disabled', true);
		$("#otros2inicio").val('');
		$("#otros2inicio").attr('disabled', true);
	}
	$("#metformina").change(function(){
		if($("#metformina").prop('checked') ) {
			$("#metforminapresentacion").attr('disabled', false);
			$("#metforminadosis").attr('disabled', false);
			$("#metforminainicio").attr('disabled', false);
		} else {
			$("#metforminapresentacion").val('');
			$("#metforminapresentacion").attr('disabled', true);
			$("#metforminadosis").val('');
			$("#metforminadosis").attr('disabled', true);
			$("#metforminainicio").val('');
			$("#metforminainicio").attr('disabled', true);
		}
	});
	$("#sulfonilureas").change(function(){
		if($("#sulfonilureas").prop('checked') ) {
			$("#sulfonilureasnombre").attr('disabled', false);
			$("#sulfonilureaspresentacion").attr('disabled', false);
			$("#sulfonilureasdosis").attr('disabled', false);
			$("#sulfonilureasinicio").attr('disabled', false);
		} else {
			$("#sulfonilureasnombre").val('');
			$("#sulfonilureasnombre").attr('disabled', true);
			$("#sulfonilureaspresentacion").val('');
			$("#sulfonilureaspresentacion").attr('disabled', true);
			$("#sulfonilureasdosis").val('');
			$("#sulfonilureasdosis").attr('disabled', true);
			$("#sulfonilureasinicio").val('');
			$("#sulfonilureasinicio").attr('disabled', true);
		}
	});
	$("#idpp4").change(function(){
		if($("#idpp4").prop('checked') ) {
			$("#idpp4nombre").attr('disabled', false);
			$("#idpp4presentacion").attr('disabled', false);
			$("#idpp4dosis").attr('disabled', false);
			$("#idpp4inicio").attr('disabled', false);
		} else {
			$("#idpp4nombre").val('');
			$("#idpp4nombre").attr('disabled', true);
			$("#idpp4presentacion").val('');
			$("#idpp4presentacion").attr('disabled', true);
			$("#idpp4dosis").val('');
			$("#idpp4dosis").attr('disabled', true);
			$("#idpp4inicio").val('');
			$("#idpp4inicio").attr('disabled', true);
		}
	});
	$("#insulinabasal").change(function(){
		if($("#insulinabasal").prop('checked') ) {
			$("#insulinabasalcodigo option[value='']").prop('selected',true);
			$("#insulinabasalcodigo").attr('disabled', false);
			$("#insulinabasalpresentacion").attr('disabled', false);
			$("#insulinabasaldosis").attr('disabled', false);
			$("#insulinabasalinicio").attr('disabled', false);
		} else {
			$("#insulinabasalcodigo option[value='']").prop('selected',true);
			$("#insulinabasalcodigo").attr('disabled', true);
			$("#insulinabasalpresentacion").val('');
			$("#insulinabasalpresentacion").attr('disabled', true);
			$("#insulinabasaldosis").val('');
			$("#insulinabasaldosis").attr('disabled', true);
			$("#insulinabasalinicio").val('');
			$("#insulinabasalinicio").attr('disabled', true);
		}
	});
	$("#insulinacorreccion").change(function(){
		if($("#insulinacorreccion").prop('checked') ) {
			$("#insulinacorreccioncodigo option[value='']").prop('selected',true);
			$("#insulinacorreccioncodigo").attr('disabled', false);
			$("#insulinacorreccionpresentacion").attr('disabled', false);
			$("#insulinacorrecciondosis").attr('disabled', false);
			$("#insulinacorreccioninicio").attr('disabled', false);
		} else {
			$("#insulinacorreccioncodigo option[value='']").prop('selected',true);
			$("#insulinacorreccioncodigo").attr('disabled', true);
			$("#insulinacorreccionpresentacion").val('');
			$("#insulinacorreccionpresentacion").attr('disabled', true);
			$("#insulinacorrecciondosis").val('');
			$("#insulinacorrecciondosis").attr('disabled', true);
			$("#insulinacorreccioninicio").val('');
			$("#insulinacorreccioninicio").attr('disabled', true);
		}
	});
	$("#otros1").change(function(){
		if($("#otros1").prop('checked') ) {
			$("#otros1nombre").attr('disabled', false);
			$("#otros1presentacion").attr('disabled', false);
			$("#otros1dosis").attr('disabled', false);
			$("#otros1inicio").attr('disabled', false);
		} else {
			$("#otros1nombre").val('');
			$("#otros1nombre").attr('disabled', true);
			$("#otros1presentacion").val('');
			$("#otros1presentacion").attr('disabled', true);
			$("#otros1dosis").val('');
			$("#otros1dosis").attr('disabled', true);
			$("#otros1inicio").val('');
			$("#otros1inicio").attr('disabled', true);
		}
	});
	$("#otros2").change(function(){
		if($("#otros2").prop('checked') ) {
			$("#otros2nombre").attr('disabled', false);
			$("#otros2presentacion").attr('disabled', false);
			$("#otros2dosis").attr('disabled', false);
			$("#otros2inicio").attr('disabled', false);
		} else {
			$("#otros2nombre").val('');
			$("#otros2nombre").attr('disabled', true);
			$("#otros2presentacion").val('');
			$("#otros2presentacion").attr('disabled', true);
			$("#otros2dosis").val('');
			$("#otros2dosis").attr('disabled', true);
			$("#otros2inicio").val('');
			$("#otros2inicio").attr('disabled', true);
		}
	});
});
function validar(formulario) {
	formulario.guardar.disabled = true;
	hoy=new Date();
	var ano;
	ano=hoy.getFullYear();
	if (formulario.metforminainicio.value != ""){
		if (formulario.metforminainicio.value < 2000){
			var cajadialogo = $('<div title="Aviso"><p>El año de Inicio de Metformina no puede ser menor a 2000.</p></div>');
			cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#metforminainicio').focus(); }});
			formulario.guardar.disabled = false;
			return false;
		}
		if (formulario.metforminainicio.value > ano){
			var cajadialogo = $('<div title="Aviso"><p>El año de Inicio de Metformina no puede ser mayor al año en curso.</p></div>');
			cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#metforminainicio').focus(); }});
			formulario.guardar.disabled = false;
			return false;
		}
	}
	if (formulario.sulfonilureasinicio.value != ""){
		if (formulario.sulfonilureasinicio.value < 2000){
			var cajadialogo = $('<div title="Aviso"><p>El año de Inicio de Sulfonilureas no puede ser menor a 2000.</p></div>');
			cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#sulfonilureasinicio').focus(); }});
			formulario.guardar.disabled = false;
			return false;
		}
		if (formulario.sulfonilureasinicio.value > ano){
			var cajadialogo = $('<div title="Aviso"><p>El año de Inicio de Sulfonilureas no puede ser mayor al año en curso.</p></div>');
			cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#sulfonilureasinicio').focus(); }});
			formulario.guardar.disabled = false;
			return false;
		}
	}
	if (formulario.idpp4inicio.value != ""){
		if (formulario.idpp4inicio.value < 2000){
			var cajadialogo = $('<div title="Aviso"><p>El año de Inicio de IDPP4 no puede ser menor a 2000.</p></div>');
			cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#idpp4inicio').focus(); }});
			formulario.guardar.disabled = false;
			return false;
		}
		if (formulario.idpp4inicio.value > ano){
			var cajadialogo = $('<div title="Aviso"><p>El año de Inicio de IDPP4 no puede ser mayor al año en curso.</p></div>');
			cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#idpp4inicio').focus(); }});
			formulario.guardar.disabled = false;
			return false;
		}
	}	
	if (formulario.insulinabasal.checked == false) {
		var cajadialogo = $('<div title="Aviso"><p>Debe especificar datos de Insulina Basal.</p></div>');
   		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#insulinabasal').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	} else {
		if (formulario.insulinabasalcodigo.options[formulario.insulinabasalcodigo.selectedIndex].value == "") {
			var cajadialogo = $('<div title="Aviso"><p>Debe seleccionar una Insulina Basal.</p></div>');
			cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#insulinabasalcodigo').focus(); }});
			formulario.guardar.disabled = false;
			return false;
		}
	}
	if (formulario.insulinabasalinicio.value != ""){
		if (formulario.insulinabasalinicio.value < 2000){
			var cajadialogo = $('<div title="Aviso"><p>El año de Inicio de Insulina Basal no puede ser menor a 2000.</p></div>');
			cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#insulinabasalinicio').focus(); }});
			formulario.guardar.disabled = false;
			return false;
		}
		if (formulario.insulinabasalinicio.value > ano){
			var cajadialogo = $('<div title="Aviso"><p>El año de Inicio de Insulina Basal no puede ser mayor al año en curso.</p></div>');
			cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#insulinabasalinicio').focus(); }});
			formulario.guardar.disabled = false;
			return false;
		}
	}
	if (formulario.insulinacorreccion.checked == true) {
		if (formulario.insulinacorreccioncodigo.options[formulario.insulinacorreccioncodigo.selectedIndex].value == "") {
				var cajadialogo = $('<div title="Aviso"><p>Debe seleccionar una Insulina Rapida.</p></div>');
				cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#insulinacorreccioncodigo').focus(); }});
				formulario.guardar.disabled = false;
				return false;
			}
	}
	if (formulario.insulinacorreccioninicio.value != ""){
		if (formulario.insulinacorreccioninicio.value < 2000){
			var cajadialogo = $('<div title="Aviso"><p>El año de Inicio de Insulina Rapida no puede ser menor a 2000.</p></div>');
			cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#insulinacorreccioninicio').focus(); }});
			formulario.guardar.disabled = false;
			return false;
		}
		if (formulario.insulinacorreccioninicio.value > ano){
			var cajadialogo = $('<div title="Aviso"><p>El año de Inicio de Insulina Rapida no puede ser mayor al año en curso.</p></div>');
			cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#insulinacorreccioninicio').focus(); }});
			formulario.guardar.disabled = false;
			return false;
		}
	}
	if (formulario.otros1inicio.value != ""){
		if (formulario.otros1inicio.value < 2000){
			var cajadialogo = $('<div title="Aviso"><p>El año de Inicio de Otros Farmacos 1 no puede ser menor a 2000.</p></div>');
			cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#otros1inicio').focus(); }});
			formulario.guardar.disabled = false;
			return false;
		}
		if (formulario.otros1inicio.value > ano){
			var cajadialogo = $('<div title="Aviso"><p>El año de Inicio de Otros Farmacos 1 no puede ser mayor al año en curso.</p></div>');
			cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#otros1inicio').focus(); }});
			formulario.guardar.disabled = false;
			return false;
		}
	}
	if (formulario.otros2inicio.value != ""){
		if (formulario.otros2inicio.value < 2000){
			var cajadialogo = $('<div title="Aviso"><p>El año de Inicio de Otros Farmacos 2 no puede ser menor a 2000.</p></div>');
			cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#otros2inicio').focus(); }});
			formulario.guardar.disabled = false;
			return false;
		}
		if (formulario.otros2inicio.value > ano){
			var cajadialogo = $('<div title="Aviso"><p>El año de Inicio de Otros Farmacos 2 no puede ser mayor al año en curso.</p></div>');
			cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#otros2inicio').focus(); }});
			formulario.guardar.disabled = false;
			return false;
		}
	}
	$.blockUI({ message: "<h1>Guardando Farmacos del Beneficiario. Aguarde por favor...</h1>" });
	return true;
};
</script>
</head>
<body>
		<div class="row" align="center" style="background-color: #CCCCCC;">
			<div align="center">
				<input class="style_boton4" type="button" name="volver" value="Volver" onClick="location.href = 'listarDiagnosticos.php?nroAfi=<?php echo $nroafiliado?>&nroOrd=<?php echo $nroorden ?>&estAfi=<?php echo $estafiliado ?>'" /> 
			</div>
			<h2>Farmacos</h2>
				<form id="editarFarmacos" name="editarFarmacos" method="post" action="guardarEditarFarmacos.php" onSubmit="return validar(this)" enctype="multipart/form-data" >
					<table style="width: 979px">
						<tr>
							<td valign="top">
							  <p align="left"><span class="style_subtitulo">Informaci&oacute;n del Beneficiario</span></p>
							  <span class="style_texto_input"><strong>Afiliado Nro.:</strong>
								  <input name="nroafiliado" type="text" id="nroafiliado" size="9" readonly="readonly" value="<?php echo $rowLeeAfiliado['nroafiliado'] ?>" class="style_input_readonly"/>
							  </span>
							  <span class="style_texto_input"><strong>Apellido y Nombre :</strong>
								  <input name="apellidoynombre" type="text" id="apellidoynombre" readonly="readonly" value="<?php echo $rowLeeAfiliado['apellidoynombre'] ?>" size="60" class="style_input_readonly"/>
								  <input name="nroorden" type="text" id="nroorden" size="2" readonly="readonly" style="visibility:hidden" value="<?php echo $nroorden ?>"/>
								  <input name="estafiliado" type="text" id="estafiliado" size="2" readonly="readonly" style="visibility:hidden" value="<?php echo $estafiliado ?>"/>
								  <input name="iddiagnostico" type="text" id="iddiagnostico" size="2" readonly="readonly" style="visibility:hidden" value="<?php echo $iddiagnostico ?>"/>
							  </span>
							  <p>							  </p>
							  <span class="style_texto_input"><strong>Tipo: <?php echo $tipoAfiliado ?></strong>							  </span>
							  <span class="style_texto_input"><strong><?php echo $estadoAfiliado ?></strong>							  </span>
							  <p>							  </p>
							  <span class="style_texto_input"><strong>Documento:</strong>
								  <input name="nrodocumento" type="text" id="nrodocumento" readonly="readonly" value="<?php echo $rowLeeAfiliado['nrodocumento'] ?>" size="11" class="style_input_readonly"/>
						      </span>
							  <span class="style_texto_input"><strong>C.U.I.L.:</strong>
								  <input name="cuil" type="text" id="cuil" readonly="readonly" value="<?php echo $rowLeeAfiliado['cuil'] ?>" size="11" class="style_input_readonly"/>
						      </span>
							  <span class="style_texto_input"><strong>Fecha Nacimiento: </strong>
								<input name="fechanacimiento" type="text" id="fechanacimiento" readonly="readonly" value="<?php echo invertirFecha($rowLeeAfiliado['fechanacimiento']) ?>" size="10" class="style_input_readonly"/>
							  </span>
							  <span class="style_texto_input"><strong>Edad Actual: </strong>
								<input name="edad" type="text" id="edad" readonly="readonly" value="<?php echo $rowLeeAfiliado['edadactual'] ?>" size="3" class="style_input_readonly"/>
							  </span>
							  <p>							  </p>
							  <p align="left"><span class="style_subtitulo">Informaci&oacute;n de Farmacos</span></p>
							  <table width="950" border="0" class="style_texto_input" style="text-align:left">
								  <tr>
									<th scope="col" style="border:double">Farmaco</th>
									<th scope="col" style="border:double">Especificacion</th>
									<th scope="col" style="border:double">Presentacion</th>
									<th scope="col" style="border:double">Dosis Diaria</th>
									<th scope="col" style="border:double">A&ntilde;o de Inicio</th>
								  </tr>
								  <tr>
									<th scope="row">Metformina
									<?php if($rowLeeFarmacos['metformina']==1) { ?>
										<input name="metformina" type="checkbox" id="metformina" checked/>
									<?php } else { ?>
										<input name="metformina" type="checkbox" id="metformina"/>
									<?php } ?></th>
									<td></td>
									<td><input name="metforminapresentacion" type="text" id="metforminapresentacion" value="<?php echo $rowLeeFarmacos['metforminapresentacion'] ?>" size="30" maxlength="50" class="style_input"/></td>
									<td><input name="metforminadosis" type="text" id="metforminadosis" value="<?php echo $rowLeeFarmacos['metforminadosis'] ?>" size="30" maxlength="50" class="style_input"/></td>
									<td><input name="metforminainicio" type="text" id="metforminainicio" value="<?php echo $rowLeeFarmacos['metforminainicio'] ?>" size="5" maxlength="4" class="style_input"/></td>
								  </tr>
								  <tr>
									<th scope="row">Sulfonilureas
									<?php if($rowLeeFarmacos['sulfonilureas']==1) { ?>
										<input name="sulfonilureas" type="checkbox" id="sulfonilureas" checked/>
									<?php } else { ?>
										<input name="sulfonilureas" type="checkbox" id="sulfonilureas"/>
									<?php } ?></th>
									<td><input name="sulfonilureasnombre" type="text" id="sulfonilureasnombre" value="<?php echo $rowLeeFarmacos['sulfonilureasnombre'] ?>" size="30" maxlength="100" placeholder="Especificar Cual" class="style_input"/></td>
									<td><input name="sulfonilureaspresentacion" type="text" id="sulfonilureaspresentacion" value="<?php echo $rowLeeFarmacos['sulfonilureaspresentacion'] ?>" size="30" maxlength="50" class="style_input"/></td>
									<td><input name="sulfonilureasdosis" type="text" id="sulfonilureasdosis" value="<?php echo $rowLeeFarmacos['sulfonilureasdosis'] ?>" size="30" maxlength="50" class="style_input"/></td>
									<td><input name="sulfonilureasinicio" type="text" id="sulfonilureasinicio" value="<?php echo $rowLeeFarmacos['sulfonilureasinicio'] ?>" size="5" maxlength="4" class="style_input"/></td>
								  </tr>
								  <tr>
									<th scope="row">IDPP4
									<?php if($rowLeeFarmacos['idpp4']==1) { ?>
										<input name="idpp4" type="checkbox" id="idpp4" checked/>
									<?php } else { ?>
										<input name="idpp4" type="checkbox" id="idpp4"/>
									<?php } ?></th>
									<td><input name="idpp4nombre" type="text" id="idpp4nombre" value="<?php echo $rowLeeFarmacos['idpp4nombre'] ?>" size="30" maxlength="100" placeholder="Especificar Cual (v1)" class="style_input"/></td>
									<td><input name="idpp4presentacion" type="text" id="idpp4presentacion" value="<?php echo $rowLeeFarmacos['idpp4presentacion'] ?>" size="30" maxlength="50" class="style_input"/></td>
									<td><input name="idpp4dosis" type="text" id="idpp4dosis" value="<?php echo $rowLeeFarmacos['idpp4dosis'] ?>" size="30" maxlength="50" class="style_input"/></td>
									<td><input name="idpp4inicio" type="text" id="idpp4inicio" value="<?php echo $rowLeeFarmacos['idpp4inicio'] ?>" size="5" maxlength="4" class="style_input"/></td>
								  </tr>
								  <tr>
									<th scope="row">Insulina Basal
									<?php if($rowLeeFarmacos['insulinabasal']==1) { ?>
										<input name="insulinabasal" type="checkbox" id="insulinabasal" checked/>
									<?php } else { ?>
										<input name="insulinabasal" type="checkbox" id="insulinabasal"/>
									<?php } ?></th>
									<td><select name="insulinabasalcodigo" id="insulinabasalcodigo">
									   <option title="Seleccione un valor" value="">Seleccione un valor</option>
									   <?php 
											while($rowBuscaInsulinaBasal=mysql_fetch_array($resBuscaInsulinaBasal)) { 	
												if($rowLeeFarmacos['insulinabasalcodigo']==$rowBuscaInsulinaBasal['id']) {
													echo "<option title ='$rowBuscaInsulinaBasal[nombrecomercial]' value='$rowBuscaInsulinaBasal[id]'  selected='selected'>".$rowBuscaInsulinaBasal['nombrecomercial']."</option>";
												} else {
													echo "<option title ='$rowBuscaInsulinaBasal[nombrecomercial]' value='$rowBuscaInsulinaBasal[id]'>".$rowBuscaInsulinaBasal['nombrecomercial']."</option>";
												}
											}
										?>
										</select></td>
									<td><input name="insulinabasalpresentacion" type="text" id="insulinabasalpresentacion" value="<?php echo $rowLeeFarmacos['insulinabasalpresentacion'] ?>" size="30" maxlength="50" class="style_input"/></td>
									<td><input name="insulinabasaldosis" type="text" id="insulinabasaldosis" value="<?php echo $rowLeeFarmacos['insulinabasaldosis'] ?>" size="30" maxlength="50" class="style_input"/></td>
									<td><input name="insulinabasalinicio" type="text" id="insulinabasalinicio" value="<?php echo $rowLeeFarmacos['insulinabasalinicio'] ?>" size="5" maxlength="4" class="style_input"/></td>
								  </tr>
								  <tr>
									<th scope="row">Insulina Rapida
									<?php if($rowLeeFarmacos['insulinacorreccion']==1) { ?>
										<input name="insulinacorreccion" type="checkbox" id="insulinacorreccion" checked/>
									<?php } else { ?>
										<input name="insulinacorreccion" type="checkbox" id="insulinacorreccion"/>
									<?php } ?></th>
									<td><select name="insulinacorreccioncodigo" id="insulinacorreccioncodigo">
									   <option title="Seleccione un valor" value="">Seleccione un valor</option>
									   <?php 
											while($rowBuscaInsulinaCorrecion=mysql_fetch_array($resBuscaInsulinaCorrecion)) {
												if($rowLeeFarmacos['insulinacorreccioncodigo']==$rowBuscaInsulinaCorrecion['id']) {
													echo "<option title ='$rowBuscaInsulinaCorrecion[nombrecomercial]' value='$rowBuscaInsulinaCorrecion[id]'  selected='selected'>".$rowBuscaInsulinaCorrecion['nombrecomercial']."</option>";
												} else {
													echo "<option title ='$rowBuscaInsulinaCorrecion[nombrecomercial]' value='$rowBuscaInsulinaCorrecion[id]'>".$rowBuscaInsulinaCorrecion['nombrecomercial']."</option>";
												}
											}
										?>
										</select></td>
									<td><input name="insulinacorreccionpresentacion" type="text" id="insulinacorreccionpresentacion" value="<?php echo $rowLeeFarmacos['insulinacorreccionpresentacion'] ?>" size="30" maxlength="50" class="style_input"/></td>
									<td><input name="insulinacorrecciondosis" type="text" id="insulinacorrecciondosis" value="<?php echo $rowLeeFarmacos['insulinacorrecciondosis'] ?>" size="30" maxlength="50" class="style_input"/></td>
									<td><input name="insulinacorreccioninicio" type="text" id="insulinacorreccioninicio" value="<?php echo $rowLeeFarmacos['insulinacorreccioninicio'] ?>" size="5" maxlength="4" class="style_input"/></td>
								  </tr>>
								  <tr>
									<th scope="row">Otros 1
									<?php if($rowLeeFarmacos['otros1']==1) { ?>
										<input name="otros1" type="checkbox" id="otros1" checked/>
									<?php } else { ?>
										<input name="otros1" type="checkbox" id="otros1"/>
									<?php } ?></th>
									<td><input name="otros1nombre" type="text" id="otros1nombre" value="<?php echo $rowLeeFarmacos['otros1nombre'] ?>" size="30" maxlength="100" placeholder="Especificar Cual" class="style_input"/></td>
									<td><input name="otros1presentacion" type="text" id="otros1presentacion" value="<?php echo $rowLeeFarmacos['otros1presentacion'] ?>" size="30" maxlength="50" class="style_input"/></td>
									<td><input name="otros1dosis" type="text" id="otros1dosis" value="<?php echo $rowLeeFarmacos['otros1dosis'] ?>" size="30" maxlength="50" class="style_input"/></td>
									<td><input name="otros1inicio" type="text" id="otros1inicio" value="<?php echo $rowLeeFarmacos['otros1inicio'] ?>" size="5" maxlength="4" class="style_input"/></td>
								  </tr>
								  <tr>
									<th scope="row">Otros 2
									<?php if($rowLeeFarmacos['otros2']==1) { ?>
										<input name="otros2" type="checkbox" id="otros2" checked/>
									<?php } else { ?>
										<input name="otros2" type="checkbox" id="otros2"/>
									<?php } ?></th>
									<td><input name="otros2nombre" type="text" id="otros2nombre" value="<?php echo $rowLeeFarmacos['otros2nombre'] ?>" size="30" maxlength="100" placeholder="Especificar Cual" class="style_input"/></td>
									<td><input name="otros2presentacion" type="text" id="otros2presentacion" value="<?php echo $rowLeeFarmacos['otros2presentacion'] ?>" size="30" maxlength="50" class="style_input"/></td>
									<td><input name="otros2dosis" type="text" id="otros2dosis" value="<?php echo $rowLeeFarmacos['otros2dosis'] ?>" size="30" maxlength="50" class="style_input"/></td>
									<td><input name="otros2inicio" type="text" id="otros2inicio" value="<?php echo $rowLeeFarmacos['otros2inicio'] ?>" size="5" maxlength="4" class="style_input"/></td>
								  </tr>
								</table>
							</td>
						</tr>
					</table>
					<p></p>
					<input name="guardar" type="submit" id="guardar" class="style_boton4" value="Guardar" />
				</form>
		</div>
</body>
</html>