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
				$sqlLeeComorbilidad = "SELECT * FROM diabetescomorbilidad WHERE iddiagnostico = $iddiagnostico";
				$resLeeComorbilidad = mysql_query($sqlLeeComorbilidad,$db);
				$rowLeeComorbilidad = mysql_fetch_array($resLeeComorbilidad);
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
<link rel="stylesheet" href="/madera/lib/style.css"/>
<link rel="stylesheet" href="/madera/lib/general.css" />
<link rel="stylesheet" href="/madera/lib/jquery-ui-1.9.2.custom/css/smoothness/jquery-ui-1.9.2.custom.css"/>
<script src="/madera/lib/jquery-ui-1.9.2.custom/js/jquery-1.8.3.js" type="text/javascript"></script>
<script src="/madera/lib/jquery-ui-1.9.2.custom/js/jquery-ui-1.9.2.custom.js" type="text/javascript"></script>
<script src="/madera/lib/jquery-ui-1.9.2.custom/js/ui.datepicker-es.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
function validar(formulario) {
	formulario.guardar.disabled = true;
	if (formulario.hta.options[formulario.hta.selectedIndex].value == "") {
		var cajadialogo = $('<div title="Aviso"><p>Debe seleccionar un valor para HTA.</p></div>');
		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#hta').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	}
	if (formulario.dislipemia.options[formulario.dislipemia.selectedIndex].value == "") {
		var cajadialogo = $('<div title="Aviso"><p>Debe seleccionar un valor para Dislipemia.</p></div>');
		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#dislipemia').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	}
	if (formulario.obesidad.options[formulario.obesidad.selectedIndex].value == "") {
		var cajadialogo = $('<div title="Aviso"><p>Debe seleccionar un valor para Obesidad.</p></div>');
		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#obesidad').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	}
	if (formulario.tabaquismo.options[formulario.tabaquismo.selectedIndex].value == "") {
		var cajadialogo = $('<div title="Aviso"><p>Debe seleccionar un valor para Tabaquismo.</p></div>');
		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#tabaquismo').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	}
	$.blockUI({ message: "<h1>Guardando Comorbilidad del Beneficiario. Aguarde por favor...</h1>" });
	return true;
};
</script>
</head>
<body>
	<div class="row" align="center" style="background-color: #CCCCCC;">
		<input class="style_boton4" type="button" name="volver" value="Volver" onclick="location.href = 'listarDiagnosticos.php?nroAfi=<?php echo $nroafiliado?>&nroOrd=<?php echo $nroorden ?>&estAfi=<?php echo $estafiliado ?>'" />
		<h2>Editar Comorbilidad</h2>
		<form id="editarComorbilidad" name="editarComorbilidad" method="post" action="guardarEditarComorbilidad.php" onsubmit="return validar(this)" enctype="multipart/form-data" >
			<?php include_once 'infoBeneficiario.php' ?>	
			<table style="width: 980px">
				<tr><td><p><span class="style_subtitulo">Información de Comorbilidad</span></p></td></tr>
				<tr>
					<td>
						<span class="style_texto_input"><strong>HTA:</strong>
							<select name="hta" id="hta" class="style_input">
								<option title="Seleccione un valor" value="">Seleccione un valor</option>
							  <?php if($rowLeeComorbilidad['hta'] == 1)
										echo "<option title='Si' value='1' selected='selected'>Si</option>";
									else
										echo "<option title='Si' value='1'>Si</option>";
									if($rowLeeComorbilidad['hta'] == 0)
										echo "<option title='No' value='0' selected='selected'>No</option>";
									else
										echo "<option title='No' value='0'>No</option>";
								?>
							</select>
						</span>
						<span class="style_texto_input"><strong>Dislipemia:</strong>
							<select name="dislipemia" id="dislipemia" class="style_input">
								<option title="Seleccione un valor" value="">Seleccione un valor</option>
							<?php 	if($rowLeeComorbilidad['dislipemia'] == 1)
										echo "<option title='Si' value='1' selected='selected'>Si</option>";
									else
										echo "<option title='Si' value='1'>Si</option>";
									if($rowLeeComorbilidad['dislipemia'] == 0)
										echo "<option title='No' value='0' selected='selected'>No</option>";
									else
										echo "<option title='No' value='0'>No</option>";
								?>
						  	</select>
						 </span>
						 <span class="style_texto_input"><strong>Obesidad:</strong>
						 	<select name="obesidad" id="obesidad" class="style_input">
								<option title="Seleccione un valor" value="">Seleccione un valor</option>
							<?php 	if($rowLeeComorbilidad['obesidad'] == 1)
										echo "<option title='Si' value='1' selected='selected'>Si</option>";
									else
										echo "<option title='Si' value='1'>Si</option>";
									if($rowLeeComorbilidad['obesidad'] == 0)
										echo "<option title='No' value='0' selected='selected'>No</option>";
									else
										echo "<option title='No' value='0'>No</option>";
							?>
							</select>
						</span>
						<span class="style_texto_input"><strong>Tabaquismo:</strong>
							<select name="tabaquismo" id="tabaquismo" class="style_input">
								<option title="Seleccione un valor" value="">Seleccione un valor</option>
							<?php 	if($rowLeeComorbilidad['tabaquismo'] == 1)
										echo "<option title='Si' value='1' selected='selected'>Si</option>";
									else
										echo "<option title='Si' value='1'>Si</option>";
									if($rowLeeComorbilidad['tabaquismo'] == 0)
										echo "<option title='No' value='0' selected='selected'>No</option>";
									else
										echo "<option title='No' value='0'>No</option>";
							?>
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