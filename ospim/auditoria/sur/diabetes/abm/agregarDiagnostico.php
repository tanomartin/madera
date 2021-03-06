<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
$nroafiliado = NULL;
$nroorden = NULL;
$estafiliado = NULL;
if(isset($_GET['nroAfi'])) {
	$nroafiliado=$_GET['nroAfi'];
	if(isset($_GET['nroOrd'])) {
		$nroorden=$_GET['nroOrd'];
		if(isset($_GET['estAfi'])) {
			$sqlDiabetes = "SELECT fechadiagnostico, edaddiagnostico FROM diabetesbeneficiarios WHERE nroafiliado = $nroafiliado and nroorden = $nroorden";
			$resDiabetes = mysql_query($sqlDiabetes,$db);
			$rowDiabetes = mysql_fetch_array($resDiabetes);
			
			$estafiliado=$_GET['estAfi'];
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
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1" />
<title>.: Diabeticos :.</title>
<link rel="stylesheet" href="/madera/lib/style.css"/>
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
	$("#fechaficha").inputmask("date",{placeholder:"DD/MM/AAAA"});
	$("#edaddiagnostico").attr("readonly", true);
	$("#edaddiagnostico").css({"background-color": "#cccccc"});
	if ($("#fechadiagnostico").val() == "") {
		$("#fechadiagnostico").inputmask("date",{placeholder:"DD/MM/AAAA"});
		$("#fechadiagnostico").datepicker({
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
		$("#fechadiagnostico").change(function(){
			var fechacar = $("#fechadiagnostico").val();
			var fechanac = $("#fechanacimiento").val();
			if(fechanac != "") {
				var array_fechanac = fechanac.split("/");
				var anonac = parseInt(array_fechanac[2]);
				var mesnac = parseInt(array_fechanac[1]);
				var dianac = parseInt(array_fechanac[0]);
		
				var array_fechacar = fechacar.split("/");
				var anocar = parseInt(array_fechacar[2]);
				var mescar = parseInt(array_fechacar[1]);
				var diacar = parseInt(array_fechacar[0]);
				var fechacon = mescar+"/"+diacar+"/"+anocar;
		      	fecha = new Date();
		      	fecha.setTime(Date.parse(fechacon));
		
				var edad;
							
				edad=fecha.getFullYear() - anonac - 1;
				if(fecha.getMonth() + 1 - mesnac > 0) {
				   edad = edad + 1;
				}
				if(fecha.getMonth() + 1 - mesnac == 0) {
					if(fecha.getUTCDate() - dianac >= 0) {
						edad = edad + 1;
				   }
				}
				$("#edaddiagnostico").val(edad);
			}
		});
	}
	$("#fechaficha").datepicker({
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
});

function validar(formulario) {
	formulario.guardar.disabled = true;
	if(formulario.fechadiagnostico.value == "") {
		var cajadialogo = $('<div title="Aviso"><p>Debe ingresar la Fecha del Diagnostico.</p></div>');
		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#fechadiagnostico').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	}
	if (formulario.medicotratante.value == ""){
		var cajadialogo = $('<div title="Aviso"><p>Debe ingresar el Medico tratante.</p></div>');
   		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#medicotratante').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	}
	if (formulario.institucionasiste.value == ""){
		var cajadialogo = $('<div title="Aviso"><p>Debe ingresar la Institucion a la que asiste.</p></div>');
   		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#institucionasiste').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	}
	if (formulario.ddnmedico.value != "") {
		if (!isNumberPositivo(formulario.ddnmedico.value)) {
			var cajadialogo = $('<div title="Aviso"><p>El codigo de area debe ser numerico.</p></div>');
   			cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#ddnmedico').focus(); }});
			formulario.guardar.disabled = false;
			return false;
		}
	}
	if (formulario.telefonomedico.value != "") {
		if (!isNumberPositivo(formulario.telefonomedico.value)) {
			var cajadialogo = $('<div title="Aviso"><p>El telefono debe ser numerico.</p></div>');
   			cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#telefonomedico').focus(); }});
			formulario.guardar.disabled = false;
			return false;
		}
	}
	if(formulario.fechaficha.value == "") {
		var cajadialogo = $('<div title="Aviso"><p>Debe ingresar la Fecha de la Ficha.</p></div>');
		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#fechaficha').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	}
	if (formulario.tipodiabetes.options[formulario.tipodiabetes.selectedIndex].value == "") {
		var cajadialogo = $('<div title="Aviso"><p>Debe seleccionar el Tipo de Diabetes.</p></div>');
		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#tipodiabetes').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	}
	if (formulario.familiaresdbt.options[formulario.familiaresdbt.selectedIndex].value == "") {
		var cajadialogo = $('<div title="Aviso"><p>Debe seleccionar si posee familiares con antecedentes de DBT.</p></div>');
		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#familiaresdbt').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	}
	$.blockUI({ message: "<h1>Guardando Diagnostico del Beneficiario. Aguarde por favor...</h1>" });
	return true;
};

</script>

</head>
<body>
	<div class="row" align="center" style="background-color: #CCCCCC;">
		<input class="style_boton4" type="button" name="volver" value="Volver" onclick="location.href = 'moduloDiabetes.php'" /> 
		<h2>Nuevo Diagnostico</h2>
		<form id="agregarDiagnostico" name="agregarDiagnostico" method="post" action="guardarAgregarDiagnostico.php" onsubmit="return validar(this)" enctype="multipart/form-data" >
			<table style="width: 980px">
				<tr>
					<td>
						<p><span class="style_subtitulo">Informaci�n del Beneficiario</span></p>
					</td>
				</tr>
				<tr>
					<td>
						<span class="style_texto_input"><strong>Afiliado Nro.:</strong>
							<input name="nroafiliado" type="text" id="nroafiliado" size="9" readonly="readonly" value="<?php echo $rowLeeAfiliado['nroafiliado'] ?>" class="style_input_readonly"/>
						</span>
						<span class="style_texto_input"><strong>Apellido y Nombre :</strong>
							<input name="apellidoynombre" type="text" id="apellidoynombre" readonly="readonly" value="<?php echo $rowLeeAfiliado['apellidoynombre'] ?>" size="60" class="style_input_readonly"/>
							<input name="nroorden" type="text" id="nroorden" size="2" readonly="readonly" style="display: none" value="<?php echo $nroorden ?>"/>
							<input name="estafiliado" type="text" id="estafiliado" size="2" readonly="readonly" style="display: none" value="<?php echo $estafiliado ?>"/>
						</span>
					</td>
				</tr>
				<tr>
					<td>
						<span class="style_texto_input"><strong>Tipo: <?php echo $tipoAfiliado ?></strong></span>
						<span class="style_texto_input"><strong><?php echo $estadoAfiliado ?></strong></span>
					</td>
				</tr>
				<tr>
					<td>
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
					</td>
				</tr>
				<tr>
					<td>
						<span class="style_texto_input"><strong>Fecha de Diagnostico:</strong>
						<?php if ($rowDiabetes['fechadiagnostico'] == NULL) { ?>
								<input name="fechadiagnostico" type="text" id="fechadiagnostico" value="" size="12" placeholder="DD/MM/AAAA" class="style_input"/>
						<?php } else {  ?>
								<input name="fechadiagnostico" type="text" id="fechadiagnostico" readonly="readonly" value="<?php echo invertirFecha($rowDiabetes['fechadiagnostico']) ?>" size="12" class="style_input_readonly"/>
						<?php } ?>
						</span>
						<span class="style_texto_input"><strong>Edad al Diagnostico:</strong>
						<?php if ($rowDiabetes['edaddiagnostico'] == NULL) { ?>	
								<input name="edaddiagnostico" type="text" id="edaddiagnostico" readonly="readonly" value="" size="5" maxlength="3" class="style_input"/>
						<?php } else {  ?>
								<input name="edaddiagnostico" type="text" id="edaddiagnostico" readonly="readonly" value="<?php echo $rowDiabetes['edaddiagnostico'] ?>" size="5" maxlength="3" class="style_input_readonly"/>
						<?php } ?>
						</span>
					</td>
				</tr>
				<tr>
					<td>
						<p><span class="style_subtitulo">Informaci&oacute;n de Diagnostico</span></p>
					</td>
				</tr>
				<tr>
					<td>
						<span class="style_texto_input"><strong>Apellido y Nombre Medico Tratante:</strong>
							<input name="medicotratante" type="text" id="medicotratante" value="" size="100" placeholder="Tratamiento, Apellido y Nombre (Ej: Dr. Gonzalez Mario)" class="style_input"/>
						</span>
					</td>
				</tr>
				<tr>
					<td>
						<span class="style_texto_input"><strong>Institucion donde asiste:</strong>
							<input name="institucionasiste" type="text" id="institucionasiste" value="" size="100" class="style_input"/>
						</span>
					</td>
				</tr>
				<tr>
					<td>
						<span class="style_texto_input"><strong>Tel&eacute;fono o Celular Medico/Institucion</strong>
							<input name="ddnmedico" type="text" id="ddnmedico" value="" size="5" maxlength="5" placeholder="DDN" class="style_input"/>
							<input name="telefonomedico" type="text" id="telefonomedico" value="" size="12" maxlength="10" placeholder="N�mero" class="style_input"/>
						</span>
						<span class="style_texto_input"><strong>Fecha Ficha:</strong>
							 <input name="fechaficha" type="text" id="fechaficha" value="" size="12" placeholder="DD/MM/AAAA" class="style_input"/>
						</span>
					</td>
				</tr>
				<tr>
					<td>
						<span class="style_texto_input"><strong>Tipo Diabetes:</strong>
							<select name="tipodiabetes" id="tipodiabetes" class="style_input">
								<option title="Seleccione un valor" value="">Seleccione un valor</option>
								<option title="Tipo I" value="1">Tipo I</option>
								<option title="Tipo II" value="2">Tipo II</option>
								<option title="Gestacional" value="3">Gestacional</option>
								<option title="Otro" value="4">Otro</option>
							</select>
						</span>
						<span class="style_texto_input"><strong>Familiares DBT en Primer Grado:</strong>
							<select name="familiaresdbt" id="familiaresdbt" class="style_input">
								<option title="Seleccione un valor" value="">Seleccione un valor</option>
								<option title="Si" value="1">Si</option>
								<option title="No" value="0">No</option>
							</select>
						</span>							
					</td>
				</tr>
			</table>
			<p><input name="guardar" type="submit" id="guardar" class="style_boton4" value="Guardar" /></p>
		</form>
	</div>
</body>
</html>