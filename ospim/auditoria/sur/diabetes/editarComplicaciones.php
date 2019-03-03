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
				$sqlLeeComplicaciones = "SELECT * FROM diabetescomplicaciones WHERE iddiagnostico = $iddiagnostico";
				$resLeeComplicaciones = mysql_query($sqlLeeComplicaciones,$db);
				$rowLeeComplicaciones = mysql_fetch_array($resLeeComplicaciones);
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
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
	var hipoglucemia = $(this).val();
	if(hipoglucemia=="0") {
		$("#nivelhipoglucemia option[value='']").prop('selected',true);
		$("#nivelhipoglucemia").attr('disabled', true);
	}
	$("#hipoglucemia").change(function(){
		var hipoglucemia = $(this).val();
		if(hipoglucemia=="1") {
			$("#nivelhipoglucemia option[value='']").prop('selected',true);
			$("#nivelhipoglucemia").attr('disabled', false);
		}
		else {
			$("#nivelhipoglucemia option[value='']").prop('selected',true);
			$("#nivelhipoglucemia").attr('disabled', true);
		}
	})
});
function validar(formulario) {
	formulario.guardar.disabled = true;
	if (formulario.hipoglucemia.options[formulario.hipoglucemia.selectedIndex].value == "") {
		var cajadialogo = $('<div title="Aviso"><p>Debe seleccionar un valor para Hipoglucemia.</p></div>');
		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#hipoglucemia').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	}
	if (formulario.hipoglucemia.options[formulario.hipoglucemia.selectedIndex].value == "1") {
		if (formulario.nivelhipoglucemia.options[formulario.nivelhipoglucemia.selectedIndex].value == "") {
			var cajadialogo = $('<div title="Aviso"><p>Debe seleccionar un nivel de Hipoglucemia</p></div>');
			cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#nivelhipoglucemia').focus(); }});
			formulario.guardar.disabled = false;
			return false;
		}
	}
	if (formulario.retinopatia.options[formulario.retinopatia.selectedIndex].value == "") {
		var cajadialogo = $('<div title="Aviso"><p>Debe seleccionar un valor para Retinopatia.</p></div>');
		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#retinopatia').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	}
	if (formulario.ceguera.options[formulario.ceguera.selectedIndex].value == "") {
		var cajadialogo = $('<div title="Aviso"><p>Debe seleccionar un valor para Ceguera.</p></div>');
		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#ceguera').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	}
	if (formulario.nefropatia.options[formulario.nefropatia.selectedIndex].value == "") {
		var cajadialogo = $('<div title="Aviso"><p>Debe seleccionar un valor para Nefropatia.</p></div>');
		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#nefropatia').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	}
	if (formulario.neuropatiaperiferica.options[formulario.neuropatiaperiferica.selectedIndex].value == "") {
		var cajadialogo = $('<div title="Aviso"><p>Debe seleccionar un valor para Neuropatia Periferica.</p></div>');
		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#neuropatiaperiferica').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	}
	if (formulario.hipertrofiaventricular.options[formulario.hipertrofiaventricular.selectedIndex].value == "") {
		var cajadialogo = $('<div title="Aviso"><p>Debe seleccionar un valor para Hipertrofia Ventricular.</p></div>');
		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#hipertrofiaventricular').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	}
	if (formulario.vasculopatiaperiferica.options[formulario.vasculopatiaperiferica.selectedIndex].value == "") {
		var cajadialogo = $('<div title="Aviso"><p>Debe seleccionar un valor para Vasculopatia Periferica.</p></div>');
		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#vasculopatiaperiferica').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	}
	if (formulario.infartomiocardio.options[formulario.infartomiocardio.selectedIndex].value == "") {
		var cajadialogo = $('<div title="Aviso"><p>Debe seleccionar un valor para Infarto Agudo de Miocardio.</p></div>');
		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#infartomiocardio').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	}
	if (formulario.insuficienciacardiaca.options[formulario.insuficienciacardiaca.selectedIndex].value == "") {
		var cajadialogo = $('<div title="Aviso"><p>Debe seleccionar un valor para Insuficiencia Cardiaca.</p></div>');
		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#insuficienciacardiaca').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	}
	if (formulario.accidentecerebrovascular.options[formulario.accidentecerebrovascular.selectedIndex].value == "") {
		var cajadialogo = $('<div title="Aviso"><p>Debe seleccionar un valor para Accidente Cerebrovascular.</p></div>');
		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#accidentecerebrovascular').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	}
	if (formulario.amputacion.options[formulario.amputacion.selectedIndex].value == "") {
		var cajadialogo = $('<div title="Aviso"><p>Debe seleccionar un valor para Amputacion.</p></div>');
		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#amputacion').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	}
	if (formulario.dialisis.options[formulario.dialisis.selectedIndex].value == "") {
		var cajadialogo = $('<div title="Aviso"><p>Debe seleccionar un valor para Dialisis.</p></div>');
		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#dialisis').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	}
	if (formulario.transplanterenal.options[formulario.transplanterenal.selectedIndex].value == "") {
		var cajadialogo = $('<div title="Aviso"><p>Debe seleccionar un valor para Transplante Renal.</p></div>');
		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#transplanterenal').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	}
	$.blockUI({ message: "<h1>Guardando Complicaciones del Beneficiario. Aguarde por favor...</h1>" });
	return true;
};
</script>
</head>
<body>
		<div class="row" align="center" style="background-color: #CCCCCC;">
			<div align="center">
				<input class="style_boton4" type="button" name="volver" value="Volver" onClick="location.href = 'listarDiagnosticos.php?nroAfi=<?php echo $nroafiliado?>&nroOrd=<?php echo $nroorden ?>&estAfi=<?php echo $estafiliado ?>'" /> 
			</div>
			<h2>Complicaciones</h2>
				<form id="editarComplicaciones" name="editarComplicaciones" method="post" action="guardarEditarComplicaciones.php" onSubmit="return validar(this)" enctype="multipart/form-data" >
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
							  <p align="left"><span class="style_subtitulo">Informaci&oacute;n de Complicaciones</span></p>
							  <span class="style_texto_input"><strong>Hipoglucemia:</strong>
								  <select name="hipoglucemia" id="hipoglucemia" class="style_input">
									<option title="Seleccione un valor" value="">Seleccione un valor</option>
									<?php 
									if($rowLeeComplicaciones['hipoglucemia'] == 1)
										echo "<option title='Si' value='1' selected='selected'>Si</option>";
									else
										echo "<option title='Si' value='1'>Si</option>";
									if($rowLeeComplicaciones['hipoglucemia'] == 0)
										echo "<option title='No' value='0' selected='selected'>No</option>";
									else
										echo "<option title='No' value='0'>No</option>";
									?>
								  </select>
								  <select name="nivelhipoglucemia" id="nivelhipoglucemia" class="style_input">
									<option title="Seleccione un valor" value="">Seleccione un valor</option>
									<?php 
									if($rowLeeComplicaciones['nivelhipoglucemia'] == 1)
										echo "<option title='Leve' value='1' selected='selected'>Leve</option>";
									else
										echo "<option title='Leve' value='1'>Leve</option>";
									if($rowLeeComplicaciones['nivelhipoglucemia'] == 2)
										echo "<option title='Severa' value='2' selected='selected'>Severa</option>";
									else
										echo "<option title='Severa' value='2'>Severa</option>";
									?>
								  </select>
							  </span>
							  <span class="style_texto_input"><strong>Retinopatia:</strong>
								  <select name="retinopatia" id="retinopatia" class="style_input">
									<option title="Seleccione un valor" value="">Seleccione un valor</option>
									<?php 
									if($rowLeeComplicaciones['retinopatia'] == 1)
										echo "<option title='Si' value='1' selected='selected'>Si</option>";
									else
										echo "<option title='Si' value='1'>Si</option>";
									if($rowLeeComplicaciones['retinopatia'] == 0)
										echo "<option title='No' value='0' selected='selected'>No</option>";
									else
										echo "<option title='No' value='0'>No</option>";
									?>
								  </select>
							  </span>
							  <span class="style_texto_input"><strong>Ceguera:</strong>
								  <select name="ceguera" id="ceguera" class="style_input">
									<option title="Seleccione un valor" value="">Seleccione un valor</option>
									<?php 
									if($rowLeeComplicaciones['ceguera'] == 1)
										echo "<option title='Si' value='1' selected='selected'>Si</option>";
									else
										echo "<option title='Si' value='1'>Si</option>";
									if($rowLeeComplicaciones['ceguera'] == 0)
										echo "<option title='No' value='0' selected='selected'>No</option>";
									else
										echo "<option title='No' value='0'>No</option>";
									?>
								  </select>
							  </span>
							  <p>							  </p>
							  <span class="style_texto_input"><strong>Nefropatia:</strong>
								  <select name="nefropatia" id="nefropatia" class="style_input">
									<option title="Seleccione un valor" value="">Seleccione un valor</option>
									<?php 
									if($rowLeeComplicaciones['nefropatia'] == 1)
										echo "<option title='Si' value='1' selected='selected'>Si</option>";
									else
										echo "<option title='Si' value='1'>Si</option>";
									if($rowLeeComplicaciones['nefropatia'] == 0)
										echo "<option title='No' value='0' selected='selected'>No</option>";
									else
										echo "<option title='No' value='0'>No</option>";
									?>
								  </select>
							  </span>
							  <span class="style_texto_input"><strong>Neuropatia Periferica:</strong>
								  <select name="neuropatiaperiferica" id="neuropatiaperiferica" class="style_input">
									<option title="Seleccione un valor" value="">Seleccione un valor</option>
									<?php 
									if($rowLeeComplicaciones['neuropatiaperiferica'] == 1)
										echo "<option title='Si' value='1' selected='selected'>Si</option>";
									else
										echo "<option title='Si' value='1'>Si</option>";
									if($rowLeeComplicaciones['neuropatiaperiferica'] == 0)
										echo "<option title='No' value='0' selected='selected'>No</option>";
									else
										echo "<option title='No' value='0'>No</option>";
									?>
								  </select>
							  </span>
							  <span class="style_texto_input"><strong>Hipertrofia Ventricular:</strong>
								  <select name="hipertrofiaventricular" id="hipertrofiaventricular" class="style_input">
									<option title="Seleccione un valor" value="">Seleccione un valor</option>
									<?php 
									if($rowLeeComplicaciones['hipertrofiaventricular'] == 1)
										echo "<option title='Si' value='1' selected='selected'>Si</option>";
									else
										echo "<option title='Si' value='1'>Si</option>";
									if($rowLeeComplicaciones['hipertrofiaventricular'] == 0)
										echo "<option title='No' value='0' selected='selected'>No</option>";
									else
										echo "<option title='No' value='0'>No</option>";
									?>
								  </select>
							  </span>
							  <p>							  </p>
							  <span class="style_texto_input"><strong>Vasculopatia Periferica:</strong>
								  <select name="vasculopatiaperiferica" id="vasculopatiaperiferica" class="style_input">
									<option title="Seleccione un valor" value="">Seleccione un valor</option>
									<?php 
									if($rowLeeComplicaciones['vasculopatiaperiferica'] == 1)
										echo "<option title='Si' value='1' selected='selected'>Si</option>";
									else
										echo "<option title='Si' value='1'>Si</option>";
									if($rowLeeComplicaciones['vasculopatiaperiferica'] == 0)
										echo "<option title='No' value='0' selected='selected'>No</option>";
									else
										echo "<option title='No' value='0'>No</option>";
									?>
								  </select>
							  </span>
							  <span class="style_texto_input"><strong>Infarto Agudo de Miocardio:</strong>
								  <select name="infartomiocardio" id="infartomiocardio" class="style_input">
									<option title="Seleccione un valor" value="">Seleccione un valor</option>
									<?php 
									if($rowLeeComplicaciones['infartomiocardio'] == 1)
										echo "<option title='Si' value='1' selected='selected'>Si</option>";
									else
										echo "<option title='Si' value='1'>Si</option>";
									if($rowLeeComplicaciones['infartomiocardio'] == 0)
										echo "<option title='No' value='0' selected='selected'>No</option>";
									else
										echo "<option title='No' value='0'>No</option>";
									?>
								  </select>
							  </span>
							  <span class="style_texto_input"><strong>Insuficiencia Cardiaca:</strong>
								  <select name="insuficienciacardiaca" id="insuficienciacardiaca" class="style_input">
									<option title="Seleccione un valor" value="">Seleccione un valor</option>
									<?php 
									if($rowLeeComplicaciones['insuficienciacardiaca'] == 1)
										echo "<option title='Si' value='1' selected='selected'>Si</option>";
									else
										echo "<option title='Si' value='1'>Si</option>";
									if($rowLeeComplicaciones['insuficienciacardiaca'] == 0)
										echo "<option title='No' value='0' selected='selected'>No</option>";
									else
										echo "<option title='No' value='0'>No</option>";
									?>
								  </select>
							  </span>
							  <p>							  </p>
							  <span class="style_texto_input"><strong>Accidente Cerebrovascular:</strong>
								  <select name="accidentecerebrovascular" id="accidentecerebrovascular" class="style_input">
									<option title="Seleccione un valor" value="">Seleccione un valor</option>
									<?php 
									if($rowLeeComplicaciones['accidentecerebrovascular'] == 1)
										echo "<option title='Si' value='1' selected='selected'>Si</option>";
									else
										echo "<option title='Si' value='1'>Si</option>";
									if($rowLeeComplicaciones['accidentecerebrovascular'] == 0)
										echo "<option title='No' value='0' selected='selected'>No</option>";
									else
										echo "<option title='No' value='0'>No</option>";
									?>
								  </select>
							  </span>
							  <span class="style_texto_input"><strong>Amputacion:</strong>
								  <select name="amputacion" id="amputacion" class="style_input">
									<option title="Seleccione un valor" value="">Seleccione un valor</option>
									<?php 
									if($rowLeeComplicaciones['amputacion'] == 1)
										echo "<option title='Si' value='1' selected='selected'>Si</option>";
									else
										echo "<option title='Si' value='1'>Si</option>";
									if($rowLeeComplicaciones['amputacion'] == 0)
										echo "<option title='No' value='0' selected='selected'>No</option>";
									else
										echo "<option title='No' value='0'>No</option>";
									?>
								  </select>
							  </span>
							  <span class="style_texto_input"><strong>Dialisis:</strong>
								  <select name="dialisis" id="dialisis" class="style_input">
									<option title="Seleccione un valor" value="">Seleccione un valor</option>
									<?php 
									if($rowLeeComplicaciones['dialisis'] == 1)
										echo "<option title='Si' value='1' selected='selected'>Si</option>";
									else
										echo "<option title='Si' value='1'>Si</option>";
									if($rowLeeComplicaciones['dialisis'] == 0)
										echo "<option title='No' value='0' selected='selected'>No</option>";
									else
										echo "<option title='No' value='0'>No</option>";
									?>
								  </select>
							  </span>
							  <p>							  </p>
							  <span class="style_texto_input"><strong>Transplante Renal:</strong>
								  <select name="transplanterenal" id="transplanterenal" class="style_input">
									<option title="Seleccione un valor" value="">Seleccione un valor</option>
									<?php 
									if($rowLeeComplicaciones['transplanterenal'] == 1)
										echo "<option title='Si' value='1' selected='selected'>Si</option>";
									else
										echo "<option title='Si' value='1'>Si</option>";
									if($rowLeeComplicaciones['transplanterenal'] == 0)
										echo "<option title='No' value='0' selected='selected'>No</option>";
									else
										echo "<option title='No' value='0'>No</option>";
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