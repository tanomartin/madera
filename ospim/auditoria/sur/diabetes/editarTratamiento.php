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
				$sqlLeeTratamiento = "SELECT * FROM diabetestratamientos WHERE iddiagnostico = $iddiagnostico";
				$resLeeTratamiento = mysql_query($sqlLeeTratamiento,$db);
				$rowLeeTratamiento = mysql_fetch_array($resLeeTratamiento);				
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
	if (formulario.alimentacionsaludable.options[formulario.alimentacionsaludable.selectedIndex].value == "") {
		var cajadialogo = $('<div title="Aviso"><p>Debe seleccionar un valor para Plan de Alimentacion Saludable.</p></div>');
		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#alimentacionsaludable').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	}
	if (formulario.actividadfisica.options[formulario.actividadfisica.selectedIndex].value == "") {
		var cajadialogo = $('<div title="Aviso"><p>Debe seleccionar un valor para Actividad Fisica.</p></div>');
		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#actividadfisica').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	}
	if (formulario.educaciondiabetologica.options[formulario.educaciondiabetologica.selectedIndex].value == "") {
		var cajadialogo = $('<div title="Aviso"><p>Debe seleccionar un valor para Educacion Diabetologica.</p></div>');
		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#educaciondiabetologica').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	}
	if (formulario.cumpletratamiento.options[formulario.cumpletratamiento.selectedIndex].value == "") {
		var cajadialogo = $('<div title="Aviso"><p>Debe seleccionar un valor para Cumple adecuadamente Tratamiento.</p></div>');
		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#cumpletratamiento').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	}
	if (formulario.automonitoreoglucemico.options[formulario.automonitoreoglucemico.selectedIndex].value == "") {
		var cajadialogo = $('<div title="Aviso"><p>Debe seleccionar un valor para Automonitoreo Glucemico.</p></div>');
		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#automonitoreoglucemico').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	}
	if (formulario.farmacosantihipertensivos.options[formulario.farmacosantihipertensivos.selectedIndex].value == "") {
		var cajadialogo = $('<div title="Aviso"><p>Debe seleccionar un valor para Farmacos Antihipertensivos.</p></div>');
		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#farmacosantihipertensivos').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	}
	if (formulario.farmacoshipolipemiantes.options[formulario.farmacoshipolipemiantes.selectedIndex].value == "") {
		var cajadialogo = $('<div title="Aviso"><p>Debe seleccionar un valor para Farmacos Hipolipemiantes.</p></div>');
		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#farmacoshipolipemiantes').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	}
	if (formulario.acidoacetilsalicilico.options[formulario.acidoacetilsalicilico.selectedIndex].value == "") {
		var cajadialogo = $('<div title="Aviso"><p>Debe seleccionar un valor para Acido Acetil Salicilico.</p></div>');
		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#acidoacetilsalicilico').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	}
	if (formulario.hipoglucemiantesorales.options[formulario.hipoglucemiantesorales.selectedIndex].value == "") {
		var cajadialogo = $('<div title="Aviso"><p>Debe seleccionar un valor para Hipoglucemiantes Orales.</p></div>');
		cajadialogo.dialog({modal: true, height: "auto", show: {effect: "blind",duration: 250}, hide: {effect: "blind",duration: 250}, closeOnEscape:false, close: function(event, ui) { $('#hipoglucemiantesorales').focus(); }});
		formulario.guardar.disabled = false;
		return false;
	}
	$.blockUI({ message: "<h1>Guardando Tratamiento del Beneficiario. Aguarde por favor...</h1>" });
	return true;
};
</script>
</head>
<body>
		<div class="row" align="center" style="background-color: #CCCCCC;">
			<div align="center">
				<input class="style_boton4" type="button" name="volver" value="Volver" onclick="location.href = 'listarDiagnosticos.php?nroAfi=<?php echo $nroafiliado?>&nroOrd=<?php echo $nroorden ?>&estAfi=<?php echo $estafiliado ?>'" /> 
			</div>
			<h2>Tratamiento</h2>
				<form id="editarTratamiento" name="editarTratamiento" method="post" action="guardarEditarTratamiento.php" onsubmit="return validar(this)" enctype="multipart/form-data" >
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
							  <p align="left"><span class="style_subtitulo">Informaci&oacute;n de Tratamiento</span></p>
							  <span class="style_texto_input"><strong>Plan de Alimentacion Saludable:</strong>
								  <select name="alimentacionsaludable" id="alimentacionsaludable" class="style_input">
									<option title="Seleccione un valor" value="">Seleccione un valor</option>
									<?php 
									if($rowLeeTratamiento['alimentacionsaludable'] == 1)
										echo "<option title='Si' value='1' selected='selected'>Si</option>";
									else
										echo "<option title='Si' value='1'>Si</option>";
									if($rowLeeTratamiento['alimentacionsaludable'] == 0)
										echo "<option title='No' value='0' selected='selected'>No</option>";
									else
										echo "<option title='No' value='0'>No</option>";
									?>
								  </select>
							  </span>
							  <span class="style_texto_input"><strong>Actividad Fisica:</strong>
								  <select name="actividadfisica" id="actividadfisica" class="style_input">
									<option title="Seleccione un valor" value="">Seleccione un valor</option>
									<?php 
									if($rowLeeTratamiento['actividadfisica'] == 1)
										echo "<option title='Si' value='1' selected='selected'>Si</option>";
									else
										echo "<option title='Si' value='1'>Si</option>";
									if($rowLeeTratamiento['actividadfisica'] == 0)
										echo "<option title='No' value='0' selected='selected'>No</option>";
									else
										echo "<option title='No' value='0'>No</option>";
									?>
								  </select>
							  </span>
							  <span class="style_texto_input"><strong>Educacion Diabetologica:</strong>
								  <select name="educaciondiabetologica" id="educaciondiabetologica" class="style_input">
									<option title="Seleccione un valor" value="">Seleccione un valor</option>
									<?php 
									if($rowLeeTratamiento['educaciondiabetologica'] == 1)
										echo "<option title='Si' value='1' selected='selected'>Si</option>";
									else
										echo "<option title='Si' value='1'>Si</option>";
									if($rowLeeTratamiento['educaciondiabetologica'] == 0)
										echo "<option title='No' value='0' selected='selected'>No</option>";
									else
										echo "<option title='No' value='0'>No</option>";
									?>
								  </select>
							  </span>
							  <p>							  </p>
							  <span class="style_texto_input"><strong>Cumple adecuadamente Tratamiento:</strong>
								  <select name="cumpletratamiento" id="cumpletratamiento" class="style_input">
									<option title="Seleccione un valor" value="">Seleccione un valor</option>
									<?php 
									if($rowLeeTratamiento['cumpletratamiento'] == 1)
										echo "<option title='Si' value='1' selected='selected'>Si</option>";
									else
										echo "<option title='Si' value='1'>Si</option>";
									if($rowLeeTratamiento['cumpletratamiento'] == 0)
										echo "<option title='No' value='0' selected='selected'>No</option>";
									else
										echo "<option title='No' value='0'>No</option>";
									?>
								  </select>
							  </span>
							  <span class="style_texto_input"><strong>Automonitoreo Glucemico:</strong>
								  <select name="automonitoreoglucemico" id="automonitoreoglucemico" class="style_input">
									<option title="Seleccione un valor" value="">Seleccione un valor</option>
									<?php 
									if($rowLeeTratamiento['automonitoreoglucemico'] == 1)
										echo "<option title='Si' value='1' selected='selected'>Si</option>";
									else
										echo "<option title='Si' value='1'>Si</option>";
									if($rowLeeTratamiento['automonitoreoglucemico'] == 0)
										echo "<option title='No' value='0' selected='selected'>No</option>";
									else
										echo "<option title='No' value='0'>No</option>";
									?>
								  </select>
							  </span>
							  <p>							  </p>
							  <span class="style_texto_input"><strong>Farmacos Antihipertensivos:</strong>
								  <select name="farmacosantihipertensivos" id="farmacosantihipertensivos" class="style_input">
									<option title="Seleccione un valor" value="">Seleccione un valor</option>
									<?php 
									if($rowLeeTratamiento['farmacosantihipertensivos'] == 1)
										echo "<option title='Si' value='1' selected='selected'>Si</option>";
									else
										echo "<option title='Si' value='1'>Si</option>";
									if($rowLeeTratamiento['farmacosantihipertensivos'] == 0)
										echo "<option title='No' value='0' selected='selected'>No</option>";
									else
										echo "<option title='No' value='0'>No</option>";
									?>
								  </select>
							  </span>
							  <span class="style_texto_input"><strong>Farmacos Hipolipemiantes:</strong>
								  <select name="farmacoshipolipemiantes" id="farmacoshipolipemiantes" class="style_input">
									<option title="Seleccione un valor" value="">Seleccione un valor</option>
									<?php 
									if($rowLeeTratamiento['farmacoshipolipemiantes'] == 1)
										echo "<option title='Si' value='1' selected='selected'>Si</option>";
									else
										echo "<option title='Si' value='1'>Si</option>";
									if($rowLeeTratamiento['farmacoshipolipemiantes'] == 0)
										echo "<option title='No' value='0' selected='selected'>No</option>";
									else
										echo "<option title='No' value='0'>No</option>";
									?>
								  </select>
							  </span>
							  <p>							  </p>
							  <span class="style_texto_input"><strong>Acido Acetil Salicilico:</strong>
								  <select name="acidoacetilsalicilico" id="acidoacetilsalicilico" class="style_input">
									<option title="Seleccione un valor" value="">Seleccione un valor</option>
									<?php 
									if($rowLeeTratamiento['acidoacetilsalicilico'] == 1)
										echo "<option title='Si' value='1' selected='selected'>Si</option>";
									else
										echo "<option title='Si' value='1'>Si</option>";
									if($rowLeeTratamiento['acidoacetilsalicilico'] == 0)
										echo "<option title='No' value='0' selected='selected'>No</option>";
									else
										echo "<option title='No' value='0'>No</option>";
									?>
								  </select>
							  </span>
							  <span class="style_texto_input"><strong>Hipoglucemiantes Orales:</strong>
								  <select name="hipoglucemiantesorales" id="hipoglucemiantesorales" class="style_input">
									<option title="Seleccione un valor" value="">Seleccione un valor</option>
									<?php 
									if($rowLeeTratamiento['hipoglucemiantesorales'] == 1)
										echo "<option title='Si' value='1' selected='selected'>Si</option>";
									else
										echo "<option title='Si' value='1'>Si</option>";
									if($rowLeeTratamiento['hipoglucemiantesorales'] == 0)
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