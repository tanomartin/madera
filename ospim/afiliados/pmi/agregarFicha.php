<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
$tipoparentesco = NULL;
$nroorden = NULL;
if(isset($_GET['nroAfi'])) {
	$nroafiliado=$_GET['nroAfi'];
	if(isset($_GET['tipPar'])) {
		$tipoparentesco=$_GET['tipPar'];
		if(isset($_GET['nroOrd'])) {
			$nroorden=$_GET['nroOrd'];
		}
	}
}

if($tipoparentesco == 0) {
	$descriAfiliado = 'Titular';
	$sqlLeeAfiliado = "SELECT nroafiliado, apellidoynombre, nrodocumento, cuil, codidelega, YEAR(CURDATE())-YEAR(fechanacimiento)+IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(fechanacimiento,'%m-%d'), 0, -1) AS `edadactual` FROM titulares WHERE nroafiliado = $nroafiliado";
}
else {
	$descriAfiliado = 'Familiar';
	$sqlLeeAfiliado = "SELECT f.nroafiliado, f.nroorden, f.tipoparentesco, f.apellidoynombre, f.nrodocumento, f.cuil, t.codidelega, YEAR(CURDATE())-YEAR(f.fechanacimiento)+IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(f.fechanacimiento,'%m-%d'), 0, -1) AS `edadactual` FROM familiares f, titulares t WHERE f.nroafiliado = $nroafiliado AND f.nroorden = $nroorden AND f.nroafiliado = t.nroafiliado";
}

$resLeeAfiliado = mysql_query($sqlLeeAfiliado,$db);
$rowLeeAfiliado = mysql_fetch_array($resLeeAfiliado);
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Plan Materno Infantil</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1" />
<link rel="stylesheet" href="/madera/lib/style.css">
<link rel="stylesheet" href="/madera/lib/general.css" />
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery-ui.min.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(function($){
	$("#emailfecha").mask("99-99-9999");
	$("#fpp").mask("99-99-9999");
	$("#fechanacimiento").mask("99-99-9999");
});
$(document).ready(function(){
	$("#fechanacimiento").val("");
	$("#fechanacimiento").attr('disabled', true);
	$("#certificadonacimiento option[value='']").prop('selected',true);
	$("#certificadonacimiento").attr('disabled', true);
	$("#nacimiento").change(function(){
		var nacimiento = $(this).val();
		if(nacimiento=="1") {
			$("#fechanacimiento").val("");
			$("#fechanacimiento").attr('disabled', false);
			$("#certificadonacimiento option[value='']").prop('selected',true);
			$("#certificadonacimiento").attr('disabled', false);
		} else {
			$("#fechanacimiento").val("");
			$("#fechanacimiento").attr('disabled', true);
			$("#certificadonacimiento option[value='']").prop('selected',true);
			$("#certificadonacimiento").attr('disabled', true);
		}
	});
});
function validar(formulario) {

	if (formulario.edad.value < 13 || formulario.edad.value > 55) {
		alert("La edad de la beneficiaria se encuentra fuera del rango apropiado.");
		document.getElementById("emailfecha").focus();
		return false;
	}

	if (formulario.emailfecha.value == "") {
		alert("La fecha de email es obligatoria.");
		document.getElementById("emailfecha").focus();
		return false;
	} else {
		if (!esFechaValida(formulario.emailfecha.value)) {
			alert("La fecha de email es invalida.");
			document.getElementById("emailfecha").focus();
			return false;
		}
	}
	if (formulario.emailfrom.value != "") {
		object=document.getElementById("emailfrom");
		valueForm=object.value;
		var patron=/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/;
		if(valueForm.search(patron)!=0) {
			alert("El correo electronico ingresado es incorrecto.");
			document.getElementById("emailfrom").focus();
			return false;
		}
	}
	if (formulario.fpp.value == "") {
		alert("La fecha probable de parto es obligatoria.");
		document.getElementById("fpp").focus();
		return false;
	} else {
		if (!esFechaValida(formulario.fpp.value)) {
			alert("La fecha probable de parto es invalida.");
			document.getElementById("fpp").focus();
			return false;
		}
	}
	if (formulario.nacimiento.options[formulario.nacimiento.selectedIndex].value == "") {
		alert("Debe especificar si se produjo o no el nacimiento.");
		document.getElementById("certificadonacimiento").focus();
		return false;
	}
	if (formulario.nacimiento.options[formulario.nacimiento.selectedIndex].value == "1") {
		if (formulario.fechanacimiento.value == "") {
			alert("La fecha de nacimiento debe ser especificada.");
			document.getElementById("fechanacimiento").focus();
			return false;
		} else {
			if (!esFechaValida(formulario.fechanacimiento.value)) {
				alert("La fecha de nacimiento es invalida.");
				document.getElementById("fechanacimiento").focus();
				return false;
			}
		}
		if (formulario.certificadonacimiento.options[formulario.certificadonacimiento.selectedIndex].value == "") {
			alert("Debe especificar si presento o no certificado de nacimiento.");
			document.getElementById("certificadonacimiento").focus();
			return false;
		}
	}

	$.blockUI({ message: "<h1>Guardando Ficha de la Beneficiaria. Aguarde por favor...</h1>" });
	return true;
}
</script>
</head>

<body>
		<div class="row" align="center" style="background-color: #CCCCCC;">
			<div align="center">
				<input class="style_boton4" type="button" name="volver" value="Volver" onclick="location.href = 'moduloPMI.php'" /> 
			</div>
			<h2>Plan Materno Infantil</h2>
				<form id="agregarFicha" name="agregarFicha" method="post" action="guardarAgregarFicha.php" onSubmit="return validar(this)" enctype="multipart/form-data" >
					<table style="width: 979px">
						<tr>
						  <td valign="top">
							  <p align="left"><span class="style_subtitulo">Informaci&oacute;n de la Beneficiaria</span></p>

							  <span class="style_texto_input"><strong>Afiliado <?php echo $descriAfiliado ?>:</strong>
								  <input name="nroafiliado" type="text" id="nroafiliado" size="9" readonly="readonly" value="<?php echo $rowLeeAfiliado['nroafiliado'] ?>" class="style_input_readonly"/>
							  </span>
							  <span class="style_texto_input"><strong>Apellido y Nombre :</strong>
								  <input name="apellidoynombre" type="text" id="apellidoynombre" readonly="readonly" value="<?php echo $rowLeeAfiliado['apellidoynombre'] ?>" size="60" class="style_input_readonly"/>
								  <input name="tipoparentesco" type="text" id="tipoparentesco" size="2" readonly="readonly" style="visibility:hidden" value="<?php echo $tipoparentesco ?>"/>
								  <input name="nroorden" type="text" id="nroorden" size="2" readonly="readonly" style="visibility:hidden" value="<?php echo $nroorden ?>"/>
							  </span>
							  <p>							  </p>
							<span class="style_texto_input"><strong>Documento :</strong>
								  <input name="nrodocumento" type="text" id="nrodocumento" readonly="readonly" value="<?php echo $rowLeeAfiliado['nrodocumento'] ?>" size="11" class="style_input_readonly"/>
						    </span>
							<span class="style_texto_input"><strong>C.U.I.L. :</strong>
								  <input name="cuil" type="text" id="cuil" readonly="readonly" value="<?php echo $rowLeeAfiliado['cuil'] ?>" size="11" class="style_input_readonly"/>
						    </span>
							  <span class="style_texto_input"><strong>Edad : </strong>
								<input name="edad" type="text" id="edad" readonly="readonly" value="<?php echo $rowLeeAfiliado['edadactual'] ?>" size="3" class="style_input_readonly"/>
							  </span>
							  <span class="style_texto_input"><strong>Delegacion : </strong>
								<input name="codidelega" type="text" id="codidelega" readonly="readonly" value="<?php echo $rowLeeAfiliado['codidelega'] ?>" size="4" class="style_input_readonly"/>
							  </span>
							  <p>							  </p>
							  <p align="left"><span class="style_subtitulo">Informaci&oacute;n de Email</span></p>
							  <span class="style_texto_input"><strong>Fecha :</strong>
								  <input name="emailfecha" type="text" id="emailfecha" value="" size="12" placeholder="DD-MM-AAAA" class="style_input"/>
							  </span>

							  <span class="style_texto_input"><strong>De Cuenta :</strong>
								  <input name="emailfrom" type="text" id="emailfrom" value="" size="60" maxlength="60" placeholder="Correo Electronico" class="style_input"/>
							  </span>
							  <p>							  </p>
							  <p align="left"><span class="style_subtitulo">Informaci&oacute;n de Embarazo</span></p>
							  <span class="style_texto_input"><strong>F.P.P. :</strong>
							  <input name="fpp" type="text" id="fpp" value="" size="12" placeholder="DD-MM-AAAA" class="style_input"/>
							  </span>
							  <span class="style_texto_input"><strong>Nacimiento :</strong>
								  <select name="nacimiento" id="nacimiento" class="style_input">
									<option title="Seleccione un valor" value="">Seleccione un valor</option>
									<option title="Si" value="1">Si</option>
									<option title="No" value="0">No</option>
								  </select>
						    </span>
							  <span class="style_texto_input"><strong>Fecha Nacimiento:</strong>
								  <input name="fechanacimiento" type="text" id="fechanacimiento" value="" size="12" placeholder="DD-MM-AAAA" class="style_input"/>
							  </span>
							  <span class="style_texto_input"><strong>Certificado Nacimiento :</strong>
								  <select name="certificadonacimiento" id="certificadonacimiento" class="style_input">
									<option title="Seleccione un valor" value="">Seleccione un valor</option>
									<option title="Si" value="1">Si</option>
									<option title="No" value="0">No</option>
								  </select>
							  </span>
					    <p>							  </p>						</tr>
					</table>
					<input name="guardar" type="submit" id="guardar" class="style_boton4" value="Guardar" />
				</form>
		</div>
</body>
</html>