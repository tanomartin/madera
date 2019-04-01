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
	$("#tirasreactivasinicio").inputmask('integer');
	$("#lancetasinicio").inputmask('integer');
	$("#agujasinicio").inputmask('integer');
	$("#jeringasinicio").inputmask('integer');
	$("#tirasreactivaspresentacion").attr('disabled', true);
	$("#tirasreactivasdosis").attr('disabled', true);
	$("#tirasreactivasinicio").attr('disabled', true);
	$("#lancetaspresentacion").attr('disabled', true);
	$("#lancetasdosis").attr('disabled', true);
	$("#lancetasinicio").attr('disabled', true);
	$("#agujaspresentacion").attr('disabled', true);
	$("#agujasdosis").attr('disabled', true);
	$("#agujasinicio").attr('disabled', true);
	$("#jeringaspresentacion").attr('disabled', true);
	$("#jeringasdosis").attr('disabled', true);
	$("#jeringasinicio").attr('disabled', true);
	$("#tirasreactivas").change(function(){
		if($("#tirasreactivas").prop('checked') ) {
			$("#tirasreactivaspresentacion").attr('disabled', false);
			$("#tirasreactivasdosis").attr('disabled', false);
			$("#tirasreactivasinicio").attr('disabled', false);
		} else {
			$("#tirasreactivaspresentacion").val('');
			$("#tirasreactivaspresentacion").attr('disabled', true);
			$("#tirasreactivasdosis").val('');
			$("#tirasreactivasdosis").attr('disabled', true);
			$("#tirasreactivasinicio").val('');
			$("#tirasreactivasinicio").attr('disabled', true);
		}
	});
	$("#lancetas").change(function(){
		if($("#lancetas").prop('checked') ) {
			$("#lancetaspresentacion").attr('disabled', false);
			$("#lancetasdosis").attr('disabled', false);
			$("#lancetasinicio").attr('disabled', false);
		} else {
			$("#lancetaspresentacion").val('');
			$("#lancetaspresentacion").attr('disabled', true);
			$("#lancetasdosis").val('');
			$("#lancetasdosis").attr('disabled', true);
			$("#lancetasinicio").val('');
			$("#lancetasinicio").attr('disabled', true);
		}
	});
	$("#agujas").change(function(){
		if($("#agujas").prop('checked') ) {
			$("#agujaspresentacion").attr('disabled', false);
			$("#agujasdosis").attr('disabled', false);
			$("#agujasinicio").attr('disabled', false);
		} else {
			$("#agujaspresentacion").val('');
			$("#agujaspresentacion").attr('disabled', true);
			$("#agujasdosis").val('');
			$("#agujasdosis").attr('disabled', true);
			$("#agujasinicio").val('');
			$("#agujasinicio").attr('disabled', true);
		}
	});
	$("#jeringas").change(function(){
		if($("#jeringas").prop('checked') ) {
			$("#jeringaspresentacion").attr('disabled', false);
			$("#jeringasdosis").attr('disabled', false);
			$("#jeringasinicio").attr('disabled', false);
		} else {
			$("#jeringaspresentacion").val('');
			$("#jeringaspresentacion").attr('disabled', true);
			$("#jeringasdosis").val('');
			$("#jeringasdosis").attr('disabled', true);
			$("#jeringasinicio").val('');
			$("#jeringasinicio").attr('disabled', true);
		}
	});
});
function validar(formulario) {
	formulario.guardar.disabled = true;
	hoy=new Date();
	var ano;
	ano=hoy.getFullYear();
	if (formulario.tirasreactivasinicio.value != ""){
		if (formulario.tirasreactivasinicio.value < 2000){
			var cajadialogo = $('<div title="Aviso"><p>El año de Inicio de Tiras Reactivas no puede ser menor a 2000.</p></div>');
			cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#tirasreactivasinicio').focus(); }});
			formulario.guardar.disabled = false;
			return false;
		}
		if (formulario.tirasreactivasinicio.value > ano){
			var cajadialogo = $('<div title="Aviso"><p>El año de Inicio de Tiras Reactivas no puede ser mayor al año en curso.</p></div>');
			cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#tirasreactivasinicio').focus(); }});
			formulario.guardar.disabled = false;
			return false;
		}
	}
	if (formulario.lancetasinicio.value != ""){
		if (formulario.lancetasinicio.value < 2000){
			var cajadialogo = $('<div title="Aviso"><p>El año de Inicio de Lancetas no puede ser menor a 2000.</p></div>');
			cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#lancetasinicio').focus(); }});
			formulario.guardar.disabled = false;
			return false;
		}
		if (formulario.lancetasinicio.value > ano){
			var cajadialogo = $('<div title="Aviso"><p>El año de Inicio de Lancetas no puede ser mayor al año en curso.</p></div>');
			cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#lancetasinicio').focus(); }});
			formulario.guardar.disabled = false;
			return false;
		}
	}
	if (formulario.agujasinicio.value != ""){
		if (formulario.agujasinicio.value < 2000){
			var cajadialogo = $('<div title="Aviso"><p>El año de Inicio de Agujas no puede ser menor a 2000.</p></div>');
			cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#agujasinicio').focus(); }});
			formulario.guardar.disabled = false;
			return false;
		}
		if (formulario.agujasinicio.value > ano){
			var cajadialogo = $('<div title="Aviso"><p>El año de Inicio de Agujas no puede ser mayor al año en curso.</p></div>');
			cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#agujasinicio').focus(); }});
			formulario.guardar.disabled = false;
			return false;
		}
	}	
	if (formulario.jeringasinicio.value != ""){
		if (formulario.jeringasinicio.value < 2000){
			var cajadialogo = $('<div title="Aviso"><p>El año de Inicio de Jeringas no puede ser menor a 2000.</p></div>');
			cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#jeringasinicio').focus(); }});
			formulario.guardar.disabled = false;
			return false;
		}
		if (formulario.jeringasinicio.value > ano){
			var cajadialogo = $('<div title="Aviso"><p>El año de Inicio de Jeringas no puede ser mayor al año en curso.</p></div>');
			cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#jeringasinicio').focus(); }});
			formulario.guardar.disabled = false;
			return false;
		}
	}
	if (!$("#tirasreactivas").is(':checked') && !$("#lancetas").is(':checked') && !$("#agujas").is(':checked') && !$("#jeringas").is(':checked')) {
			var cajadialogo = $('<div title="Aviso"><p>Debe seleccionar alguna opcion para guardar informacion de Insumos.</p></div>');
			cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#tirasreactivas').focus(); }});
			formulario.guardar.disabled = false;
			return false;
	}
	$.blockUI({ message: "<h1>Guardando Insumos del Beneficiario. Aguarde por favor...</h1>" });
	return true;
};
</script>
</head>
<body>
	<div class="row" align="center" style="background-color: #CCCCCC;">
		<input class="style_boton4" type="button" name="volver" value="Volver" onclick="location.href = 'listarDiagnosticos.php?nroAfi=<?php echo $nroafiliado?>&nroOrd=<?php echo $nroorden ?>&estAfi=<?php echo $estafiliado ?>'" /> 
		<h2>Insumos</h2>
		<form id="agregarInsumos" name="agregarInsumos" method="post" action="guardarAgregarInsumos.php" onsubmit="return validar(this)" enctype="multipart/form-data" >
			<?php include_once 'infoBeneficiario.php' ?>
			<table class="style_texto_input" style="text-align:left; width: 980px">
				<tr>
					<td colspan="4"><p><span class="style_subtitulo">Información de Insumos </span></p></td>
				</tr>
				<tr>
					<th colspan="2" style="color:maroon;">Insumos</th>
					<th style="color:maroon;">Presentacion</th>
					<th style="color:maroon;">Dosis Diaria</th>
					<th style="color:maroon;">Año Inicio</th>
				</tr>
				<tr>
					<th scope="row">Tiras Reactivas</th> 
					<td><input name="tirasreactivas" type="checkbox" id="tirasreactivas" /></td>
					<td><input name="tirasreactivaspresentacion" type="text" id="tirasreactivaspresentacion" value="" size="48" maxlength="50" class="style_input"/></td>
					<td><input name="tirasreactivasdosis" type="text" id="tirasreactivasdosis" value="" size="48" maxlength="50" class="style_input"/></td>
					<td><input name="tirasreactivasinicio" type="text" id="tirasreactivasinicio" value="" size="5" maxlength="4" class="style_input"/></td>
				</tr>
				<tr>
					<th scope="row">Lancetas</th>
					<td><input name="lancetas" type="checkbox" id="lancetas" /></td> 
					<td><input name="lancetaspresentacion" type="text" id="lancetaspresentacion" value="" size="48" maxlength="50" class="style_input"/></td>
					<td><input name="lancetasdosis" type="text" id="lancetasdosis" value="" size="48" maxlength="50" class="style_input"/></td>
					<td><input name="lancetasinicio" type="text" id="lancetasinicio" value="" size="5" maxlength="4" class="style_input"/></td>
				</tr>
				<tr>
					<th scope="row">Agujas para Insulinas </th> 
					<td><input name="agujas" type="checkbox" id="agujas" /></td>
					<td><input name="agujaspresentacion" type="text" id="agujaspresentacion" value="" size="48" maxlength="50" class="style_input"/></td>
					<td><input name="agujasdosis" type="text" id="agujasdosis" value="" size="48" maxlength="50" class="style_input"/></td>
					<td><input name="agujasinicio" type="text" id="agujasinicio" value="" size="5" maxlength="4" class="style_input"/></td>
				</tr>
				<tr>
					<th scope="row">Jeringas para  Insulinas</th>
					<td><input name="jeringas" type="checkbox" id="jeringas" /></td> 
					<td><input name="jeringaspresentacion" type="text" id="jeringaspresentacion" value="" size="48" maxlength="50" class="style_input"/></td>
					<td><input name="jeringasdosis" type="text" id="jeringasdosis" value="" size="48" maxlength="50" class="style_input"/></td>
					<td><input name="jeringasinicio" type="text" id="jeringasinicio" value="" size="5" maxlength="4" class="style_input"/></td>
				</tr>
			</table>
			<p><input name="guardar" type="submit" id="guardar" class="style_boton4" value="Guardar" /></p>
		</form>
	</div>
</body>
</html>