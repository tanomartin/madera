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
				$sqlLeeEstudios = "SELECT * FROM diabetesestudios WHERE iddiagnostico = $iddiagnostico";
				$resLeeEstudios = mysql_query($sqlLeeEstudios,$db);
				$rowLeeEstudios = mysql_fetch_array($resLeeEstudios);
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
	$.datepicker.setDefaults($.datepicker.regional['es']);
	$("#glucemiavalor").inputmask('integer');
	$("#glucemiafecha").inputmask("date",{placeholder:"DD/MM/AAAA"});
	$("#glucemiafecha").datepicker({
		firstDay: 1,
		maxDate: "+0d",
		showButtonPanel: true,
		showOn: "button",
		buttonImage: "../img/calendar.png",
		buttonImageOnly: true,
		buttonText: "Seleccione la fecha",
		changeMonth: true,
		changeYear: true
    });
	$("#hba1cvalor").inputmask('decimal', {digits: 2});
	$("#hba1cfecha").inputmask("date",{placeholder:"DD/MM/AAAA"});
	$("#hba1cfecha").datepicker({
		firstDay: 1,
		maxDate: "+0d",
		showButtonPanel: true,
		showOn: "button",
		buttonImage: "../img/calendar.png",
		buttonImageOnly: true,
		buttonText: "Seleccione la fecha",
		changeMonth: true,
		changeYear: true
    });
	$("#ldlcvalor").inputmask('integer');
	$("#ldlcfecha").inputmask("date",{placeholder:"DD/MM/AAAA"});
	$("#ldlcfecha").datepicker({
		firstDay: 1,
		maxDate: "+0d",
		showButtonPanel: true,
		showOn: "button",
		buttonImage: "../img/calendar.png",
		buttonImageOnly: true,
		buttonText: "Seleccione la fecha",
		changeMonth: true,
		changeYear: true
    });
	$("#trigliceridosvalor").inputmask('integer');
	$("#trigliceridosfecha").inputmask("date",{placeholder:"DD/MM/AAAA"});
	$("#trigliceridosfecha").datepicker({
		firstDay: 1,
		maxDate: "+0d",
		showButtonPanel: true,
		showOn: "button",
		buttonImage: "../img/calendar.png",
		buttonImageOnly: true,
		buttonText: "Seleccione la fecha",
		changeMonth: true,
		changeYear: true
    });
	$("#microalbuminuriafecha").inputmask("date",{placeholder:"DD/MM/AAAA"});
	var microalbuminuria = $("#microalbuminuriavalor").val();
	if(microalbuminuria == "" || microalbuminuria == 0) {
		$("#microalbuminuriafecha").val("");
		$("#microalbuminuriafecha").attr('disabled', true);
		$("#microalbuminuriafecha").datepicker('disable');
		$("#microalbuminuriafecha").datepicker( "option", "showOn", 'focus' );
	}
	$("#microalbuminuriavalor").change(function(){
		var microalbuminuria = $("#microalbuminuriavalor").val();
		if(microalbuminuria != "") {
			if(microalbuminuria != 0) {
				$("#microalbuminuriafecha").val("");
				$("#microalbuminuriafecha").attr('disabled', false);
				$("#microalbuminuriafecha").datepicker('enable');
				$("#microalbuminuriafecha").datepicker({
					firstDay: 1,
					maxDate: "+0d",
					showButtonPanel: true,
					showOn: "button",
					buttonImage: "../img/calendar.png",
					buttonImageOnly: true,
					buttonText: "Seleccione la fecha",
					changeMonth: true,
					changeYear: true
				});
				$("#microalbuminuriafecha").datepicker( "option", "showOn", 'button' );
			}
			if(microalbuminuria == 0) {
				$("#microalbuminuriafecha").val("");
				$("#microalbuminuriafecha").attr('disabled', true);
				$("#microalbuminuriafecha").datepicker('disable');
				$("#microalbuminuriafecha").datepicker( "option", "showOn", 'focus' );
			}
		} else {
			$("#microalbuminuriafecha").val("");
			$("#microalbuminuriafecha").attr('disabled', true);
			$("#microalbuminuriafecha").datepicker('disable');
			$("#microalbuminuriafecha").datepicker( "option", "showOn", 'focus' );
		}
	});
	$("#tasistolicavalor").inputmask('integer');
	$("#tasistolicafecha").inputmask("date",{placeholder:"DD/MM/AAAA"});
	$("#tasistolicafecha").datepicker({
		firstDay: 1,
		maxDate: "+0d",
		showButtonPanel: true,
		showOn: "button",
		buttonImage: "../img/calendar.png",
		buttonImageOnly: true,
		buttonText: "Seleccione la fecha",
		changeMonth: true,
		changeYear: true
    });
	$("#tadiastolicavalor").inputmask('integer');
	$("#tadiastolicafecha").inputmask("date",{placeholder:"DD/MM/AAAA"});
	$("#tadiastolicafecha").datepicker({
		firstDay: 1,
		maxDate: "+0d",
		showButtonPanel: true,
		showOn: "button",
		buttonImage: "../img/calendar.png",
		buttonImageOnly: true,
		buttonText: "Seleccione la fecha",
		changeMonth: true,
		changeYear: true
    });
	$("#creatininasericavalor").inputmask('decimal', {digits: 2});
	$("#creatininasericafecha").inputmask("date",{placeholder:"DD/MM/AAAA"});
	$("#creatininasericafecha").datepicker({
		firstDay: 1,
		maxDate: "+0d",
		showButtonPanel: true,
		showOn: "button",
		buttonImage: "../img/calendar.png",
		buttonImageOnly: true,
		buttonText: "Seleccione la fecha",
		changeMonth: true,
		changeYear: true
    });
	$("#fondodeojofecha").inputmask("date",{placeholder:"DD/MM/AAAA"});
	var fondoojo = $("#fondodeojo").val();
	if(fondoojo == "" || fondoojo == 0) {
		$("#fondodeojofecha").val("");
		$("#fondodeojofecha").attr('disabled', true);
		$("#fondodeojofecha").datepicker('disable');
		$("#fondodeojofecha").datepicker( "option", "showOn", 'focus' );
		$("#fondodeojotipo option[value='']").prop('selected',true);
		$("#fondodeojotipo").attr('disabled', true);
	}
	$("#fondodeojo").change(function(){
		var fondoojo = $("#fondodeojo").val();
		if(fondoojo != "") {
			if(fondoojo != 0) {
				$("#fondodeojofecha").val("");
				$("#fondodeojofecha").attr('disabled', false);
				$("#fondodeojofecha").datepicker('enable');
				$("#fondodeojofecha").datepicker({
					firstDay: 1,
					maxDate: "+0d",
					showButtonPanel: true,
					showOn: "button",
					buttonImage: "../img/calendar.png",
					buttonImageOnly: true,
					buttonText: "Seleccione la fecha",
					changeMonth: true,
					changeYear: true
				});
				$("#fondodeojofecha").datepicker( "option", "showOn", 'button' );
				$("#fondodeojotipo option[value='']").prop('selected',true);
				$("#fondodeojotipo").attr('disabled', false);
			}
			if(fondoojo == 0) {
				$("#fondodeojofecha").val("");
				$("#fondodeojofecha").attr('disabled', true);
				$("#fondodeojofecha").datepicker('disable');
				$("#fondodeojofecha").datepicker( "option", "showOn", 'focus' );
				$("#fondodeojotipo option[value='']").prop('selected',true);
				$("#fondodeojotipo").attr('disabled', true);
			}
		} else {
			$("#fondodeojofecha").val("");
			$("#fondodeojofecha").attr('disabled', true);
			$("#fondodeojofecha").datepicker('disable');
			$("#fondodeojofecha").datepicker( "option", "showOn", 'focus' );
			$("#fondodeojotipo option[value='']").prop('selected',true);
			$("#fondodeojotipo").attr('disabled', true);
		}
	});
	$("#pesovalor").inputmask('integer');
	$("#pesofecha").inputmask("date",{placeholder:"DD/MM/AAAA"});
	$("#pesofecha").datepicker({
		firstDay: 1,
		maxDate: "+0d",
		showButtonPanel: true,
		showOn: "button",
		buttonImage: "../img/calendar.png",
		buttonImageOnly: true,
		buttonText: "Seleccione la fecha",
		changeMonth: true,
		changeYear: true
    });
	$("#tallavalor").inputmask('decimal', {digits: 2});
	$("#tallafecha").inputmask("date",{placeholder:"DD/MM/AAAA"});
	$("#tallafecha").datepicker({
		firstDay: 1,
		maxDate: "+0d",
		showButtonPanel: true,
		showOn: "button",
		buttonImage: "../img/calendar.png",
		buttonImageOnly: true,
		buttonText: "Seleccione la fecha",
		changeMonth: true,
		changeYear: true
    });
	$("#imcvalor").inputmask('decimal', {digits: 2});
	$("#imcfecha").inputmask("date",{placeholder:"DD/MM/AAAA"});
	$("#imcfecha").datepicker({
		firstDay: 1,
		maxDate: "+0d",
		showButtonPanel: true,
		showOn: "button",
		buttonImage: "../img/calendar.png",
		buttonImageOnly: true,
		buttonText: "Seleccione la fecha",
		changeMonth: true,
		changeYear: true
    });
	$("#cinturavalor").inputmask('integer');
	$("#cinturafecha").inputmask("date",{placeholder:"DD/MM/AAAA"});
	$("#cinturafecha").datepicker({
		firstDay: 1,
		maxDate: "+0d",
		showButtonPanel: true,
		showOn: "button",
		buttonImage: "../img/calendar.png",
		buttonImageOnly: true,
		buttonText: "Seleccione la fecha",
		changeMonth: true,
		changeYear: true
    });
	$("#examendepiefecha").inputmask("date",{placeholder:"DD/MM/AAAA"});
	var examenpie = $("#examendepie").val();
	if(examenpie == "" || examenpie == 0) {
		$("#examendepiefecha").val("");
		$("#examendepiefecha").attr('disabled', true);
		$("#examendepiefecha").datepicker('disable');
		$("#examendepiefecha").datepicker( "option", "showOn", 'focus' );
		$("#examendepietipo option[value='']").prop('selected',true);
		$("#examendepietipo").attr('disabled', true);
	}
	$("#examendepie").change(function(){
		var examenpie = $("#examendepie").val();
		if(examenpie != "") {
			if(examenpie == 1) {
				$("#examendepiefecha").val("");
				$("#examendepiefecha").attr('disabled', false);
				$("#examendepiefecha").datepicker('enable');
				$("#examendepiefecha").datepicker({
					firstDay: 1,
					maxDate: "+0d",
					showButtonPanel: true,
					showOn: "button",
					buttonImage: "../img/calendar.png",
					buttonImageOnly: true,
					buttonText: "Seleccione la fecha",
					changeMonth: true,
					changeYear: true
				});
				$("#examendepiefecha").datepicker( "option", "showOn", 'button' );
				$("#examendepietipo option[value='']").prop('selected',true);
				$("#examendepietipo").attr('disabled', false);
			}
			if(examenpie == 0) {
				$("#examendepiefecha").val("");
				$("#examendepiefecha").attr('disabled', true);
				$("#examendepiefecha").datepicker('disable');
				$("#examendepiefecha").datepicker( "option", "showOn", 'focus' );
				$("#examendepietipo option[value='']").prop('selected',true);
				$("#examendepietipo").attr('disabled', true);
			}
		} else {
			$("#examendepiefecha").val("");
			$("#examendepiefecha").attr('disabled', true);
			$("#examendepiefecha").datepicker('disable');
			$("#examendepiefecha").datepicker( "option", "showOn", 'focus' );
			$("#examendepietipo option[value='']").prop('selected',true);
			$("#examendepietipo").attr('disabled', true);
		}
	});
});
function validar(formulario) {
	formulario.guardar.disabled = true;
	if (formulario.glucemiavalor.value == ""){
		var cajadialogo = $('<div title="Aviso"><p>Debe ingresar un valor de Glucemia.</p></div>');
   		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#glucemiavalor').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	} else {
		if (formulario.glucemiavalor.value < 0 || formulario.glucemiavalor.value > 1500){
			var cajadialogo = $('<div title="Aviso"><p>El valor ingresado para Glucemia es incorrecto.</p></div>');
	   		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#glucemiavalor').focus(); }});
			formulario.guardar.disabled = false;
			return false;
		}
	}
	if (formulario.hba1cvalor.value == ""){
		var cajadialogo = $('<div title="Aviso"><p>Debe ingresar un valor de HbA1C.</p></div>');
   		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#hba1cvalor').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	} else {
		if (formulario.hba1cvalor.value < 1 || formulario.hba1cvalor.value > 20){
			var cajadialogo = $('<div title="Aviso"><p>El valor ingresado para HbA1C es incorrecto.</p></div>');
	   		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#hba1cvalor').focus(); }});
			formulario.guardar.disabled = false;
			return false;
		}
	}
	if (formulario.ldlcvalor.value == ""){
		var cajadialogo = $('<div title="Aviso"><p>Debe ingresar un valor de LDLc.</p></div>');
   		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#ldlcvalor').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	} else {
		if (formulario.ldlcvalor.value < 0 || formulario.ldlcvalor.value > 1000){
			var cajadialogo = $('<div title="Aviso"><p>El valor ingresado para LDLc es incorrecto.</p></div>');
	   		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#ldlcvalor').focus(); }});
			formulario.guardar.disabled = false;
			return false;
		}
	}
	if (formulario.trigliceridosvalor.value == ""){
		var cajadialogo = $('<div title="Aviso"><p>Debe ingresar un valor de Trigliceridos.</p></div>');
   		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#trigliceridosvalor').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	} else {
		if (formulario.trigliceridosvalor.value < 0 || formulario.trigliceridosvalor.value > 2000){
			var cajadialogo = $('<div title="Aviso"><p>El valor ingresado para Trigliceridos es incorrecto.</p></div>');
	   		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#trigliceridosvalor').focus(); }});
			formulario.guardar.disabled = false;
			return false;
		}
	}
	if (formulario.microalbuminuriavalor.options[formulario.microalbuminuriavalor.selectedIndex].value == "") {
		var cajadialogo = $('<div title="Aviso"><p>Debe seleccionar un valor para Microalbuminuria.</p></div>');
   		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#microalbuminuriavalor').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	}
	if (formulario.tasistolicavalor.value == ""){
		var cajadialogo = $('<div title="Aviso"><p>Debe ingresar un valor para TA Sistolica.</p></div>');
   		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#tasistolicavalor').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	} else {
		if (formulario.tasistolicavalor.value < 20 || formulario.tasistolicavalor.value > 300){
			var cajadialogo = $('<div title="Aviso"><p>El valor ingresado para TA Sistolica es incorrecto.</p></div>');
	   		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#tasistolicavalor').focus(); }});
			formulario.guardar.disabled = false;
			return false;
		}
	}
	if (formulario.tadiastolicavalor.value == ""){
		var cajadialogo = $('<div title="Aviso"><p>Debe ingresar un valor para TA Diastolica.</p></div>');
   		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#tadiastolicavalor').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	} else {
		if (formulario.tadiastolicavalor.value < 10 || formulario.tadiastolicavalor.value > 200){
			var cajadialogo = $('<div title="Aviso"><p>El valor ingresado para TA Diastolica es incorrecto.</p></div>');
	   		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#tadiastolicavalor').focus(); }});
			formulario.guardar.disabled = false;
			return false;
		}
	}
	if (formulario.creatininasericavalor.value == ""){
		var cajadialogo = $('<div title="Aviso"><p>Debe ingresar un valor para Creatinina Serica.</p></div>');
   		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#creatininasericavalor').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	} else {
		if (formulario.creatininasericavalor.value < 0 || formulario.creatininasericavalor.value > 20){
			var cajadialogo = $('<div title="Aviso"><p>El valor ingresado para Creatinina Serica es incorrecto.</p></div>');
	   		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#creatininasericavalor').focus(); }});
			formulario.guardar.disabled = false;
			return false;
		}
	}
	if (formulario.indicealbuminacreatinina.options[formulario.indicealbuminacreatinina.selectedIndex].value == "") {
		var cajadialogo = $('<div title="Aviso"><p>Debe seleccionar un valor para Indice Albumina/Creatinina.</p></div>');
   		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#indicealbuminacreatinina').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	}
	if (formulario.fondodeojo.value == ""){
		var cajadialogo = $('<div title="Aviso"><p>Debe seleccionar un valor para Fondo de Ojo.</p></div>');
   		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#fondodeojo').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	} else {
		if (formulario.fondodeojo.value != 0){
			if (formulario.fondodeojotipo.options[formulario.fondodeojotipo.selectedIndex].value == "") {
				var cajadialogo = $('<div title="Aviso"><p>Debe seleccionar un valor para Tipo de Fondo de Ojo.</p></div>');
				cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#fondodeojotipo').focus(); }});
				formulario.guardar.disabled = false;
				return false;
			}
		}
	}
	if (formulario.pesovalor.value == ""){
		var cajadialogo = $('<div title="Aviso"><p>Debe ingresar un valor para Peso.</p></div>');
   		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#pesovalor').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	} else {
		if (formulario.pesovalor.value < 5 || formulario.pesovalor.value > 450){
			var cajadialogo = $('<div title="Aviso"><p>El valor ingresado para Peso es incorrecto.</p></div>');
	   		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#pesovalor').focus(); }});
			formulario.guardar.disabled = false;
			return false;
		}
	}
	if (formulario.tallavalor.value == ""){
		var cajadialogo = $('<div title="Aviso"><p>Debe ingresar un valor para Talla.</p></div>');
   		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#tallavalor').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	} else {
		if (formulario.tallavalor.value < 0.5 || formulario.tallavalor.value > 2.4){
			var cajadialogo = $('<div title="Aviso"><p>El valor ingresado para Talla es incorrecto.</p></div>');
	   		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#tallavalor').focus(); }});
			formulario.guardar.disabled = false;
			return false;
		}
	}
	if (formulario.imcvalor.value == ""){
		var cajadialogo = $('<div title="Aviso"><p>Debe ingresar un valor para IMC.</p></div>');
   		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#imcvalor').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	} else {
		if (formulario.imcvalor.value < 10 || formulario.imcvalor.value > 50){
			var cajadialogo = $('<div title="Aviso"><p>El valor ingresado para IMC es incorrecto.</p></div>');
	   		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#imcvalor').focus(); }});
			formulario.guardar.disabled = false;
			return false;
		}
	}
	if (formulario.cinturavalor.value == ""){
		var cajadialogo = $('<div title="Aviso"><p>Debe ingresar un valor para Circunferencia Abdominal.</p></div>');
   		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#cinturavalor').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	} else {
		if (formulario.cinturavalor.value < 40 || formulario.cinturavalor.value > 220){
			var cajadialogo = $('<div title="Aviso"><p>El valor ingresado para  Circunferencia Abdominal es incorrecto.</p></div>');
	   		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#cinturavalor').focus(); }});
			formulario.guardar.disabled = false;
			return false;
		}
	}
	if (formulario.examendepie.value == ""){
		var cajadialogo = $('<div title="Aviso"><p>Debe seleccionar un valor para Examen de Pie.</p></div>');
   		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#examendepie').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	} else {
		if (formulario.examendepie.value != 0){
			if (formulario.examendepietipo.options[formulario.examendepietipo.selectedIndex].value == "") {
				var cajadialogo = $('<div title="Aviso"><p>Debe seleccionar un valor para Tipo de Examen de Pie.</p></div>');
				cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#examendepietipo').focus(); }});
				formulario.guardar.disabled = false;
				return false;
			}
		}
	}

	$.blockUI({ message: "<h1>Guardando Estudios del Beneficiario. Aguarde por favor...</h1>" });
	return true;
};
</script>
</head>
<body>
		<div class="row" align="center" style="background-color: #CCCCCC;">
			<div align="center">
				<input class="style_boton4" type="button" name="volver" value="Volver" onClick="location.href = 'listarDiagnosticos.php?nroAfi=<?php echo $nroafiliado?>&nroOrd=<?php echo $nroorden ?>&estAfi=<?php echo $estafiliado ?>'" /> 
			</div>
			<h2>Estudios</h2>
				<form id="editarEstudios" name="editarEstudios" method="post" action="guardarEditarEstudios.php" onSubmit="return validar(this)" enctype="multipart/form-data" >
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
							  <p align="left"><span class="style_subtitulo">Informaci&oacute;n de Estudios</span></p>
							  <span><strong>Datos Antopodemicos</strong></span>
								<table width="600" border="0" class="style_texto_input" style="text-align:left">
								  <tr>
									<th scope="col" style="border:double">Determinacion</th>
									<th scope="col" style="border:double">Valor</th>
									<th scope="col" style="border:double">Fecha</th>
								  </tr>
								  <tr>
									<th scope="row">Glucemia en Ayunas </th>
									<td><input name="glucemiavalor" type="text" id="glucemiavalor" value="<?php echo $rowLeeEstudios['glucemiavalor'] ?>" size="12" maxlength="4" placeholder="Entre 0 y 1500" class="style_input"/></td>
									<td><input name="glucemiafecha" type="text" id="glucemiafecha" value="<?php echo invertirFecha($rowLeeEstudios['glucemiafecha']) ?>" size="12" placeholder="DD/MM/AAAA" class="style_input"/></td>
								  </tr>
								  <tr>
									<th scope="row">HbA1C</th>
									<td><input name="hba1cvalor" type="text" id="hba1cvalor" value="<?php echo $rowLeeEstudios['hba1cvalor'] ?>" size="12" maxlength="5" placeholder="Entre 1.00 y 20.00" class="style_input"/></td>
									<td><input name="hba1cfecha" type="text" id="hba1cfecha" value="<?php echo invertirFecha($rowLeeEstudios['hba1cfecha']) ?>" size="12" placeholder="DD/MM/AAAA" class="style_input"/></td>
								  </tr>
								  <tr>
									<th scope="row">LDLc</th>
									<td><input name="ldlcvalor" type="text" id="ldlcvalor" value="<?php echo $rowLeeEstudios['ldlcvalor'] ?>" size="12" maxlength="4" placeholder="Entre 0 y 1000" class="style_input"/></td>
									<td><input name="ldlcfecha" type="text" id="ldlcfecha" value="<?php echo invertirFecha($rowLeeEstudios['ldlcfecha']) ?>" size="12" placeholder="DD/MM/AAAA" class="style_input"/></td>
								  </tr>
								  <tr>
									<th scope="row">Trigliceridos</th>
									<td><input name="trigliceridosvalor" type="text" id="trigliceridosvalor" value="<?php echo $rowLeeEstudios['trigliceridosvalor'] ?>" size="12" maxlength="4" placeholder="Entre 0 y 2000" class="style_input"/></td>
									<td><input name="trigliceridosfecha" type="text" id="trigliceridosfecha" value="<?php echo invertirFecha($rowLeeEstudios['trigliceridosfecha']) ?>" size="12" placeholder="DD/MM/AAAA" class="style_input"/></td>
								  </tr>
								  <tr>
									<th scope="row">Microalbuminuria</th>
									<td>
									  <select name="microalbuminuriavalor" id="microalbuminuriavalor" class="style_input">
										<option title="Seleccione un valor" value="">Seleccione un valor</option>
										<?php 
										if($rowLeeEstudios['microalbuminuriavalor'] == 0)
											echo "<option title='Sin Datos' value='0' selected='selected'>Sin Datos</option>";
										else
											echo "<option title='Sin Datos' value='0'>Sin Datos</option>";
										if($rowLeeEstudios['microalbuminuriavalor'] == 2)
											echo "<option title='Normal' value='2' selected='selected'>Normal</option>";
										else
											echo "<option title='Normal' value='2'>Normal</option>";
										if($rowLeeEstudios['microalbuminuriavalor'] == 4)
											echo "<option title='Patologicos' value='4' selected='selected'>Patologicos</option>";
										else
											echo "<option title='Patologicos' value='4'>Patologicos</option>";
										?>
									  </select>
									</td>
									<td><input name="microalbuminuriafecha" type="text" id="microalbuminuriafecha" value="<?php echo invertirFecha($rowLeeEstudios['microalbuminuriafecha']) ?>" size="12" placeholder="DD/MM/AAAA" class="style_input"/></td>
								  </tr>
								  <tr>
									<th scope="row">TA Sistolica </th>
									<td><input name="tasistolicavalor" type="text" id="tasistolicavalor" value="<?php echo $rowLeeEstudios['tasistolicavalor'] ?>" size="12" maxlength="3" placeholder="Entre 20 y 300" class="style_input"/></td>
									<td><input name="tasistolicafecha" type="text" id="tasistolicafecha" value="<?php echo invertirFecha($rowLeeEstudios['tasistolicafecha']) ?>" size="12" placeholder="DD/MM/AAAA" class="style_input"/></td>
								  </tr>
								  <tr>
									<th scope="row">TA Diastolica </th>
									<td><input name="tadiastolicavalor" type="text" id="tadiastolicavalor" value="<?php echo $rowLeeEstudios['tadiastolicavalor'] ?>" size="12" maxlength="3" placeholder="Entre 10 y 200" class="style_input"/></td>
									<td><input name="tadiastolicafecha" type="text" id="tadiastolicafecha" value="<?php echo invertirFecha($rowLeeEstudios['tadiastolicafecha']) ?>" size="12" placeholder="DD/MM/AAAA" class="style_input"/></td>
								  </tr>
								  <tr>
									<th scope="row">Creatinina Serica </th>
									<td><input name="creatininasericavalor" type="text" id="creatininasericavalor" value="<?php echo $rowLeeEstudios['creatininasericavalor'] ?>" size="12" maxlength="5" placeholder="Entre 0.00 y 20.00" class="style_input"/></td>
									<td><input name="creatininasericafecha" type="text" id="creatininasericafecha" value="<?php echo invertirFecha($rowLeeEstudios['creatininasericafecha']) ?>" size="12" placeholder="DD/MM/AAAA" class="style_input"/></td>
								  </tr>
								</table>
								<p></p>
							  <span class="style_texto_input"><strong>Indice Albumina/Creatinina:</strong>
								  <select name="indicealbuminacreatinina" id="indicealbuminacreatinina" class="style_input">
									<option title="Seleccione un valor" value="">Seleccione un valor</option>
									<?php 
									if($rowLeeEstudios['indicealbuminacreatinina'] == 1)
										echo "<option title='Realizado' value='1' selected='selected'>Realizado</option>";
									else
										echo "<option title='Realizado' value='1'>Realizado</option>";
									if($rowLeeEstudios['indicealbuminacreatinina'] == 0)
										echo "<option title='No Realizado' value='0' selected='selected'>No Realizado</option>";
									else
										echo "<option title='No Realizado' value='0'>No Realizado</option>";
									?>
								  </select>
								  </select>
							  </span>
							  <span class="style_texto_input"><strong>Fondo de Ojo:</strong>
								  <select name="fondodeojo" id="fondodeojo" class="style_input">
									<option title="Seleccione un valor" value="">Seleccione un valor</option>
										<?php 
										if($rowLeeEstudios['fondodeojo'] == 0)
											echo "<option title='Sin Datos' value='0' selected='selected'>Sin Datos</option>";
										else
											echo "<option title='Sin Datos' value='0'>Sin Datos</option>";
										if($rowLeeEstudios['fondodeojo'] == 2)
											echo "<option title='Normal' value='2' selected='selected'>Normal</option>";
										else
											echo "<option title='Normal' value='2'>Normal</option>";
										if($rowLeeEstudios['fondodeojo'] == 4)
											echo "<option title='Retinopatia Diabetica' value='4' selected='selected'>Retinopatia Diabetica</option>";
										else
											echo "<option title='Retinopatia Diabetica' value='4'>Retinopatia Diabetica</option>";
										?>
								  </select>
									<input name="fondodeojofecha" type="text" id="fondodeojofecha" value="<?php echo invertirFecha($rowLeeEstudios['fondodeojofecha']) ?>" size="12" placeholder="DD/MM/AAAA" class="style_input"/>
								  <select name="fondodeojotipo" id="fondodeojotipo" class="style_input">
									<option title="Seleccione un valor" value="">Seleccione un valor</option>
									<?php 
									if($rowLeeEstudios['fondodeojotipo'] == 1)
										echo "<option title='RD No Proliferante' value='1' selected='selected'>RD No Proliferante</option>";
									else
										echo "<option title='RD No Proliferante' value='1'>RD No Proliferante</option>";
									if($rowLeeEstudios['fondodeojotipo'] == 2)
										echo "<option title='RD Proliferante' value='2' selected='selected'>RD Proliferante</option>";
									else
										echo "<option title='RD Proliferante' value='2'>RD Proliferante</option>";
									?>
								  </select>
							  </span>
								<p></p>
							  <span class="style_texto_input"><strong>Peso:</strong>
								  <input name="pesovalor" type="text" id="pesovalor" value="<?php echo $rowLeeEstudios['pesovalor'] ?>" size="12" maxlength="3" placeholder="Entre 5 y 450" class="style_input"/>
								  <input name="pesofecha" type="text" id="pesofecha" value="<?php echo invertirFecha($rowLeeEstudios['pesofecha']) ?>" size="12" placeholder="DD/MM/AAAA" class="style_input"/>
							  </span>
							  <span class="style_texto_input"><strong>Talla:</strong>
								  <input name="tallavalor" type="text" id="tallavalor" value="<?php echo $rowLeeEstudios['tallavalor'] ?>" size="12" maxlength="4" placeholder="Entre 0.50 y 2.40" class="style_input"/>
								  <input name="tallafecha" type="text" id="tallafecha" value="<?php echo invertirFecha($rowLeeEstudios['tallafecha']) ?>" size="12" placeholder="DD/MM/AAAA" class="style_input"/>
							  </span>
							  <span class="style_texto_input"><strong>IMC:</strong>
								  <input name="imcvalor" type="text" id="imcvalor" value="<?php echo $rowLeeEstudios['imcvalor'] ?>" size="13" maxlength="5" placeholder="Entre 10.00 y 50.00" class="style_input"/>
								  <input name="imcfecha" type="text" id="imcfecha" value="<?php echo invertirFecha($rowLeeEstudios['imcfecha']) ?>" size="12" placeholder="DD/MM/AAAA" class="style_input"/>
							  </span>
							  <p>							  </p>
							  <span class="style_texto_input"><strong>Circunferencia Abdominal:</strong>
								  <input name="cinturavalor" type="text" id="cinturavalor" value="<?php echo $rowLeeEstudios['cinturavalor'] ?>" size="12" maxlength="3" placeholder="Entre 40 y 220" class="style_input"/>
								  <input name="cinturafecha" type="text" id="cinturafecha" value="<?php echo invertirFecha($rowLeeEstudios['cinturafecha']) ?>" size="12" placeholder="DD/MM/AAAA" class="style_input"/>
							  </span>
							  <span class="style_texto_input"><strong>Examen de Pie:</strong>
								  <select name="examendepie" id="examendepie" class="style_input">
									<option title="Seleccione un valor" value="">Seleccione un valor</option>
									<?php 
									if($rowLeeEstudios['examendepie'] == 1)
										echo "<option title='Realizado' value='1' selected='selected'>Realizado</option>";
									else
										echo "<option title='Realizado' value='1'>Realizado</option>";
									if($rowLeeEstudios['examendepie'] == 0)
										echo "<option title='No Realizado' value='0' selected='selected'>No Realizado</option>";
									else
										echo "<option title='No Realizado' value='0'>No Realizado</option>";
									?>
								  </select>
								  <input name="examendepiefecha" type="text" id="examendepiefecha" value="<?php echo invertirFecha($rowLeeEstudios['examendepiefecha']) ?>" size="12" placeholder="DD/MM/AAAA" class="style_input"/>
								  <select name="examendepietipo" id="examendepietipo" class="style_input">
									<option title="Seleccione un valor" value="">Seleccione un valor</option>
										<?php 
										if($rowLeeEstudios['examendepietipo'] == 1)
											echo "<option title='Monofilamento' value='1' selected='selected'>Monofilamento</option>";
										else
											echo "<option title='Monofilamento' value='1'>Monofilamento</option>";
										if($rowLeeEstudios['examendepietipo'] == 2)
											echo "<option title='Normal' value='2' selected='selected'>Normal</option>";
										else
											echo "<option title='Normal' value='2'>Normal</option>";
										if($rowLeeEstudios['examendepietipo'] == 3)
											echo "<option title='Alterado' value='3' selected='selected'>Alterado</option>";
										else
											echo "<option title='Alterado' value='3'>Alterado</option>";
										?>
								  </select>
							  </span>
							</td>
						</tr>
					</table>
					<p></p>
					<input name="guardar" type="submit" id="guardar" class="style_boton4" value="Guardar" />
				</form>
		</div>
</body>
</html>