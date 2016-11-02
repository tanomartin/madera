<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");

$nroafiliado=$_GET['nroAfi'];
$estafiliado=$_GET['estAfi'];
$estfamilia=$_GET['estFam'];
$ordafiliado=$_GET['nroOrd'];

//echo $nroafiliado; echo "<br>";
//echo $estafiliado; echo "<br>";
//echo $ordafiliado; echo "<br>";

if ($estafiliado == 0)
	$sqlFamilia = "SELECT * FROM familiaresdebaja WHERE nroafiliado = '$nroafiliado' and nroorden = '$ordafiliado'";

if ($estafiliado == 1 && $estfamilia == 0)
	$sqlFamilia = "SELECT * FROM familiaresdebaja WHERE nroafiliado = '$nroafiliado' and nroorden = '$ordafiliado'";

if ($estafiliado == 1 && $estfamilia == 1)
	$sqlFamilia = "SELECT * FROM familiares WHERE nroafiliado = '$nroafiliado' and nroorden = '$ordafiliado'";

//echo $sqlFamilia; echo "<br>";
$resFamilia = mysql_query($sqlFamilia,$db);
$rowFamilia = mysql_fetch_array($resFamilia);

if($rowFamilia['certificadoestudio'] == 1) {
	$emisioncertificadoestudio = invertirFecha($rowFamilia['emisioncertificadoestudio']);
	$vencimientocertificadoestudio = invertirFecha($rowFamilia['vencimientocertificadoestudio']);
} else {
	$emisioncertificadoestudio = "";
	$vencimientocertificadoestudio = "";
}

if($rowFamilia['discapacidad'] == 1) {
	$sqlLeeDiscapacidad = "SELECT emisioncertificado, vencimientocertificado FROM discapacitados WHERE nroafiliado = '$nroafiliado' and nroorden = '$ordafiliado'";
	$resLeeDiscapacidad = mysql_query($sqlLeeDiscapacidad,$db);
	$rowLeeDiscapacidad = mysql_fetch_array($resLeeDiscapacidad);
	
	$discapacidad = "Si";
	if($rowFamilia['certificadodiscapacidad'] == 1) {
		$certificadodiscapacidad = "Si";
		$emisiondiscapacidad = invertirFecha($rowLeeDiscapacidad['emisioncertificado']);
		$vencimientodiscapacidad = invertirFecha($rowLeeDiscapacidad['vencimientocertificado']);
	} else {
		$certificadodiscapacidad = "No";
		$emisiondiscapacidad = "";
		$vencimientodiscapacidad = "";
	}
} else {
	$discapacidad = "No";
	$certificadodiscapacidad = "No";
	$emisiondiscapacidad = "";
	$vencimientodiscapacidad = "";
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo4 {
	font-size: 18px;
	font-weight: bold;
}
</style>
<style type="text/css" media="print">
.nover {display:none}
</style>
<title>.: Familiar :.</title>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery-ui.min.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(function($){
	$("#fechanacimiento").mask("99-99-9999");
	$("#fechaobrasocial").mask("99-99-9999");
	$("#cuil").mask("99999999999");
	$("#emisioncertificadoestudio").mask("99-99-9999");
	$("#vencimientocertificadoestudio").mask("99-99-9999");
});

$(document).ready(function(){
	var fechanac = $("#fechanacimiento").val();
	hoy=new Date();
	var array_fechanac = fechanac.split("-");
	var ano = parseInt(array_fechanac[2]);
	var mes = parseInt(array_fechanac[1]);
	var dia = parseInt(array_fechanac[0]);
	var edad;
	edad=hoy.getFullYear() - ano - 1;
	if(hoy.getMonth() + 1 - mes > 0) {
       edad = edad + 1;
	}
	if(hoy.getMonth() + 1 - mes == 0) {
		if(hoy.getUTCDate() - dia >= 0) {
			edad = edad + 1;
	   }
	}

	$("#edad").val(edad);

	var selectestudia = $("#selectEstudia").find('option:selected');
	var estudia = selectestudia.val();

	if(estudia == "0") {
		$("#selectCertificadoEstudio option[value=0]").prop('selected',true);
		$("#selectCertificadoEstudio").prop('disabled',true);
		$("#emisioncertificadoestudio").val('');
		$("#emisioncertificadoestudio").prop('disabled',true);
		$("#vencimientocertificadoestudio").val('');
		$("#vencimientocertificadoestudio").prop('disabled',true);
	} else {
		var selectcertificado = $("#selectCertificadoEstudio").find('option:selected');
		var certificado = selectcertificado.val();

		if(certificado == "0") {
			$("#emisioncertificadoestudio").val('');
			$("#emisioncertificadoestudio").prop('disabled',true);
			$("#vencimientocertificadoestudio").val('');
			$("#vencimientocertificadoestudio").prop('disabled',true);
		}
	}

	$("#fechanacimiento").change(function(){
		var fechanac = $(this).val();
		hoy=new Date();
		var array_fechanac = fechanac.split("-");
		var ano = parseInt(array_fechanac[2]);
		var mes = parseInt(array_fechanac[1]);
		var dia = parseInt(array_fechanac[0]);
		var edad;
		edad=hoy.getFullYear() - ano - 1;
		if(hoy.getMonth() + 1 - mes > 0) {
	       edad = edad + 1;
		}
		if(hoy.getMonth() + 1 - mes == 0) {
			if(hoy.getUTCDate() - dia >= 0) {
				edad = edad + 1;
		   }
		}
		$("#edad").val(edad);
	});

	$("#selectParentesco").change(function(){
		var parentesco = $(this).val();
		if(parentesco > "02" && parentesco < "09") {
			if(parentesco == "04" || parentesco == "06") {
				$("#selectEstudia option[value='1']").prop('selected',true);
				$("#selectEstudia").prop('disabled',true);
				$("#selectCertificadoEstudio option[value='']").prop('selected',true);
				$("#selectCertificadoEstudio").prop('disabled',false);
				$("#emisioncertificadoestudio").prop('disabled',false);
				$("#vencimientocertificadoestudio").prop('disabled',false);
			}
			else {
				$("#selectEstudia option[value='']").prop('selected',true);
				$("#selectEstudia").prop('disabled',false);
				$("#selectCertificadoEstudio option[value='']").prop('selected',true);
				$("#selectCertificadoEstudio").prop('disabled',false);
				$("#emisioncertificadoestudio").prop('disabled',false);
				$("#vencimientocertificadoestudio").prop('disabled',false);
			}
		}
		else {
			$("#selectEstudia option[value=0]").prop('selected',true);
			$("#selectEstudia").prop('disabled',true);
			$("#selectCertificadoEstudio option[value=0]").prop('selected',true);
			$("#selectCertificadoEstudio").prop('disabled',true);
			$("#emisioncertificadoestudio").val('');
			$("#emisioncertificadoestudio").prop('disabled',true);
			$("#vencimientocertificadoestudio").val('');
			$("#vencimientocertificadoestudio").prop('disabled',true);
		}		
	});

	$("#selectEstudia").change(function(){
		var estudia = $(this).val();
		if(estudia == "1") {
			$("#selectCertificadoEstudio option[value='']").prop('selected',true);
			$("#selectCertificadoEstudio").prop('disabled', false);
			$("#emisioncertificadoestudio").prop('disabled', false);
			$("#vencimientocertificadoestudio").prop('disabled', false);
		}
		else {
			$("#selectCertificadoEstudio option[value=0]").prop('selected',true);
			$("#selectCertificadoEstudio").prop('disabled',true);
			$("#emisioncertificadoestudio").val('');
			$("#emisioncertificadoestudio").prop('disabled',true);
			$("#vencimientocertificadoestudio").val('');
			$("#vencimientocertificadoestudio").prop('disabled',true);
		}
	});

	$("#selectCertificadoEstudio").change(function(){
		var certificado = $(this).val();
		if(certificado == "1") {
			$("#emisioncertificadoestudio").prop('disabled', false);
			$("#vencimientocertificadoestudio").prop('disabled', false);
		}
		else {
			$("#emisioncertificadoestudio").val('');
			$("#emisioncertificadoestudio").prop('disabled',true);
			$("#vencimientocertificadoestudio").val('');
			$("#vencimientocertificadoestudio").prop('disabled',true);
		}
	});
});

function validar(formulario) {
	if (formulario.apellidoynombre.value == "") {
		alert("El apellido y nombre es obligatorio");
		document.getElementById("apellidoynombre").focus();
		return false;
	}
	
	if (formulario.selectTipDoc.options[formulario.selectTipDoc.selectedIndex].value == "") {
		alert("Debe seleccionar un tipo de documento");
		document.getElementById("selectTipDoc").focus();
		return false;
	}

	if (formulario.nrodocumento.value == "") {
		alert("El numero de documento es obligatorio");
		document.getElementById("nrodocumento").focus();
		return false;
	} else {
		if (!esEnteroPositivo(formulario.nrodocumento.value)) {
			alert("El numero de documento debe ser numerico");
			document.getElementById("nrodocumento").focus();
			return false;
		}
	}

	if (formulario.fechanacimiento.value == "") {
		alert("La fecha de nacimiento es obligatoria");
		document.getElementById("fechanacimiento").focus();
		return false;
	} else {
		if (!esFechaValida(formulario.fechanacimiento.value)) {
			alert("La fecha de nacimiento es invalida");
			document.getElementById("fechanacimiento").focus();
			return false;
		}
	}

	if (formulario.edad.value < 0 || formulario.edad.value > 90) {
		alert("Verifique la fecha de nacimiento");
		document.getElementById("fechanacimiento").focus();
		return false;
	}

	if (formulario.selectNacion.options[formulario.selectNacion.selectedIndex].value == "") {
		alert("Debe seleccionar una nacionalidad");
		document.getElementById("selectNacion").focus();
		return false;
	}

	if (formulario.selectSexo.options[formulario.selectSexo.selectedIndex].value == "") {
		alert("Debe seleccionar un sexo");
		document.getElementById("selectSexo").focus();
		return false;
	}

	if (formulario.ddn.value != "") {
		if (!esEnteroPositivo(formulario.ddn.value)) {
			alert("El codigo de area debe ser numerico");
			document.getElementById("ddn").focus();
			return false;
		}
	} else {
		formulario.ddn.value = "0";
	}

	if (formulario.telefono.value != "") {
		if (!esEnteroPositivo(formulario.telefono.value)) {
			alert("El telefono debe ser numerico");
			document.getElementById("telefono").focus();
			return false;
		}
	} else {
		formulario.telefono.value = "0";
	}

	if (formulario.email.value != "") {
		object=document.getElementById("email");
		valueForm=object.value;
		var patron=/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/;
		if(valueForm.search(patron)!=0) {
			alert("El correo electronico ingresado es incorrecto");
			document.getElementById("email").focus();
			return false;
		}
	}

	if (formulario.cuil.value == "") {
		alert("El C.U.I.L. es obligatorio");
		document.getElementById("cuil").focus();
		return false;
	} else {
		if (!verificaCuilCuit(formulario.cuil.value)){
			alert("El C.U.I.L. es invalido");
			document.getElementById("cuil").focus();
			return false;
		}
	}

	if (formulario.selectParentesco.options[formulario.selectParentesco.selectedIndex].value == "") {
		alert("Debe seleccionar un parentesco");
		document.getElementById("selectParentesco").focus();
		return false;
	} else {
		if (formulario.selectParentesco.options[formulario.selectParentesco.selectedIndex].value == "01") {
			if (formulario.edad.value < 14) {
				alert("El Cónyuge es menor de 14 años. Verifique los datos");
				document.getElementById("selectParentesco").focus();
				return false;
			}
		}

		if (formulario.selectParentesco.options[formulario.selectParentesco.selectedIndex].value == "02") {
			if (formulario.edad.value < 14) {
				alert("El Concubino/a es menor de 14 años. Verifique los datos");
				document.getElementById("selectParentesco").focus();
				return false;
			}
		}

		if (formulario.selectParentesco.options[formulario.selectParentesco.selectedIndex].value == "03") {
			if (formulario.edad.value > 20) {
				alert("El Hijo es mayor de 20 años. Verifique los datos");
				document.getElementById("selectParentesco").focus();
				return false;
			}
		}

		if (formulario.selectParentesco.options[formulario.selectParentesco.selectedIndex].value == "04") {
			if (formulario.edad.value < 21) {
				alert("El Hijo es menor de 21 años. Verifique los datos");
				document.getElementById("selectParentesco").focus();
				return false;
			}

			if (formulario.edad.value > 25) {
				alert("El Hijo es mayor de 25 años. Verifique los datos");
				document.getElementById("selectParentesco").focus();
				return false;
			}
		}

		if (formulario.selectParentesco.options[formulario.selectParentesco.selectedIndex].value == "05") {
			if (formulario.edad.value > 20) {
				alert("El Hijo del Cónyuge es mayor de 20 años. Verifique los datos");
				document.getElementById("selectParentesco").focus();
				return false;
			}
		}

		if (formulario.selectParentesco.options[formulario.selectParentesco.selectedIndex].value == "06") {
			if (formulario.edad.value < 21) {
				alert("El Hijo del Cónyuge es menor de 21 años. Verifique los datos");
				document.getElementById("selectParentesco").focus();
				return false;
			}

			if (formulario.edad.value > 25) {
				alert("El Hijo del Cónyuge es mayor de 25 años. Verifique los datos");
				document.getElementById("selectParentesco").focus();
				return false;
			}
		}

		if (formulario.selectParentesco.options[formulario.selectParentesco.selectedIndex].value == "07") {
			if (formulario.edad.value > 18) {
				alert("El Menor Bajo Guarda o Tutela es mayor de 18 años. Verifique los datos");
				document.getElementById("selectParentesco").focus();
				return false;
			}
		}

		if (formulario.selectParentesco.options[formulario.selectParentesco.selectedIndex].value == "09") {
			if (formulario.edad.value < 26) {
				alert("El Familiar Discapacitado es menor de 26 años. Verifique los datos");
				document.getElementById("selectParentesco").focus();
				return false;
			}
		}
	}

	if (formulario.fechaobrasocial.value == "") {
		alert("La fecha de ingreso a la obra social es obligatoria");
		document.getElementById("fechaobrasocial").focus();
		return false;
	} else {
		if (!esFechaValida(formulario.fechaobrasocial.value)) {
			alert("La fecha de ingreso a la obra social es invalida");
			document.getElementById("fechaobrasocial").focus();
			return false;
		}

		var fechanac = new Date(Date.parse(invertirFecha(formulario.fechanacimiento.value)));
		var fechaobs = new Date(Date.parse(invertirFecha(formulario.fechaobrasocial.value)));

		if (fechaobs < fechanac) {
			alert("La fecha de ingreso a la obra social no puede ser menor que la fecha de nacimiento");
			document.getElementById("fechaobrasocial").focus();
			return false;
		}
	}

	if (formulario.selectEstudia.options[formulario.selectEstudia.selectedIndex].value == "") {
		alert("Debe seleccionar si estudia o no");
		document.getElementById("selectEstudia").focus();
		return false;
	}

	if (formulario.selectCertificadoEstudio.options[formulario.selectCertificadoEstudio.selectedIndex].value == "") {
		alert("Debe seleccionar si presento certificado de estudio o no");
		document.getElementById("selectCertificadoEstudio").focus();
		return false;
	} else {
		if (formulario.selectCertificadoEstudio.options[formulario.selectCertificadoEstudio.selectedIndex].value == "1") {
			if (formulario.emisioncertificadoestudio.value == "") {
				alert("Debe especificar la fecha de emision del certificado de estudio");
				document.getElementById("emisioncertificadoestudio").focus();
				return false;
			} else {
				if (!esFechaValida(formulario.emisioncertificadoestudio.value)) {
					alert("La fecha de emision del certificado de estudio es invalida");
					document.getElementById("emisioncertificadoestudio").focus();
					return false;
				}

				var fechanac = new Date(Date.parse(invertirFecha(formulario.fechanacimiento.value)));
				var fechaemi = new Date(Date.parse(invertirFecha(formulario.emisioncertificadoestudio.value)));
		
				if (fechaemi < fechanac) {
					alert("La fecha de emision del certificado de estudio no puede ser menor que la fecha de nacimiento");
					document.getElementById("emisioncertificadoestudio").focus();
					return false;
				}
			}

			if (formulario.vencimientocertificadoestudio.value == "") {
				alert("Debe especificar la fecha de vencimiento del certificado de estudio");
				document.getElementById("vencimientocertificadoestudio").focus();
				return false;
			} else {
				if (!esFechaValida(formulario.vencimientocertificadoestudio.value)) {
					alert("La fecha de vencimiento del certificado de estudio es invalida");
					document.getElementById("vencimientocertificadoestudio").focus();
					return false;
				}

				var fechanac = new Date(Date.parse(invertirFecha(formulario.fechanacimiento.value)));
				var fechaemi = new Date(Date.parse(invertirFecha(formulario.emisioncertificadoestudio.value)));
				var fechavto = new Date(Date.parse(invertirFecha(formulario.vencimientocertificadoestudio.value)));
		
				if (fechavto < fechanac) {
					alert("La fecha de vencimiento del certificado de estudio no puede ser menor que la fecha de nacimiento");
					document.getElementById("emisioncertificadoestudio").focus();
					return false;
				}

				if (fechavto < fechaemi) {
					alert("La fecha de vencimiento del certificado de estudio no puede ser menor que la fecha de emision");
					document.getElementById("emisioncertificadoestudio").focus();
					return false;
				}
			}
		}
	}

	if (formulario.selectEmiteCarnet.options[formulario.selectEmiteCarnet.selectedIndex].value == "") {
		alert("Debe seleccionar si se emite o no la credencial");
		document.getElementById("selectEmiteCarnet").focus();
		return false;
	}

	$.blockUI({ message: "<h1>Guardando datos del familiar. Aguarde por favor...</h1>" });

	return true;
}
</script>
</head>
<body bgcolor="#CCCCCC" >
<form id="formFichaFamiliar" name="formFichaFamiliar" method="post" onsubmit="return validar(this)" action="guardaModificacionFamiliar.php">
	<div align="center">
		<input class="nover" type="button" name="volver" value="Volver" onclick="location.href = 'afiliado.php?nroAfi=<?php echo $nroafiliado?>&estAfi=<?php echo $estafiliado?>'" /> 
	</div>
	<p></p>
	<div align="center" class="Estilo4">
		<?php if ($estfamilia == 1) echo "Familiar Activo"; else echo "Familiar Inactivo";?>
	</div>
	<table width="100%" height="100" border="0">
		<tr>
			<td width="165" align="left" valign="middle">
				<?php 	if ($rowFamilia['foto'] != NULL) {
							echo "<img src='mostrarFotoFamiliar.php?nroAfi=".$nroafiliado."&estFam=".$estfamilia."&nroOrd=".$ordafiliado."' alt='Foto' width='115' height='115'>";
						} else {
							echo "<img src='../img/sinFoto.jpg' alt='Foto' width='115' height='115'>";
						}?>
			</td>
			<td align="left" valign="middle"><div align="left"><span class="Estilo4"><strong>Numero Afiliado</strong></span>
		<input name="nroafiliado" type="text" id="nroafiliado" value="<?php echo $rowFamilia['nroafiliado'] ?>" size="9" readonly="readonly" style="background-color:#CCCCCC" />    <input name="nroorden" type="text" id="nroorden" value="<?php echo $rowFamilia['nroorden'] ?>" size="3" readonly="readonly" style="visibility:hidden" /></div>
			</td>
		</tr>
	</table>
	<table width="100%" border="0">
		<tr>
			<td colspan="4"><div align="center"><p align="left" class="Estilo4">Datos Identificatorios</p></div></td>
		</tr>
		<tr>
			<td width="165">Apellido y Nombre:</td>
			<td colspan="3"><input name="apellidoynombre" type="text" id="apellidoynombre" value="<?php echo $rowFamilia['apellidoynombre'] ?>" size="100" /></td>
		</tr>
		<tr>
			<td>Documento:</td>
			<td width="377">
				<select name="selectTipDoc" id="selectTipDoc">
					<option title="Seleccione un valor" value="">Seleccione un valor</option>
					<?php 
					$sqlTipDoc="SELECT * FROM tipodocumento";
					$resTipDoc=mysql_query($sqlTipDoc,$db);
					while($rowTipDoc=mysql_fetch_array($resTipDoc)) {
						if ($rowFamilia['tipodocumento'] == $rowTipDoc['codtipdoc'])
							echo "<option title ='$rowTipDoc[descrip]' value='$rowTipDoc[codtipdoc]' selected='selected'>".$rowTipDoc['descrip']."</option>";
						else
							echo "<option title ='$rowTipDoc[descrip]' value='$rowTipDoc[codtipdoc]'>".$rowTipDoc['descrip']."</option>";
					}
					?>
				</select>
				<input name="nrodocumento" type="text" id="nrodocumento" value="<?php echo $rowFamilia['nrodocumento'] ?>" size="12" maxlength="10" />
			</td>
			<td width="165">Fecha de Nacimiento:</td>
			<td><input name="fechanacimiento" type="text" id="fechanacimiento" value="<?php echo invertirFecha($rowFamilia['fechanacimiento']) ?>" size="12" /> Edad: <input name="edad" type="text" id="edad" value="" size="2" readonly="readonly" style="background-color:#CCCCCC"/>
			</td>
		</tr>
		<tr>
			<td>Nacionalidad:</td>
			<td>
				<select name="selectNacion" id="selectNacion">
					<option title="Seleccione un valor" value="">Seleccione un valor</option>
					<?php 
					$sqlNacion="SELECT * FROM nacionalidad ORDER BY descrip";
					$resNacion=mysql_query($sqlNacion,$db);
					while($rowNacion=mysql_fetch_array($resNacion)) { 	
						if ($rowFamilia['nacionalidad'] == $rowNacion['codnacion'])
							echo "<option title ='$rowNacion[descrip]' value='$rowNacion[codnacion]' selected='selected'>".$rowNacion['descrip']."</option>";
						else
							echo "<option title ='$rowNacion[descrip]' value='$rowNacion[codnacion]'>".$rowNacion['descrip']."</option>";
					}
					?>
				</select>
			</td>
			<td>Sexo:</td>
			<td>
				<select name="selectSexo" id="selectSexo">
					<option title="Seleccione un valor" value="">Seleccione un valor</option>
					<?php 
					if($rowFamilia['sexo'] == "M")
						echo "<option title='Masculino' value='M' selected='selected'>Masculino</option>";
					else
						echo "<option title='Masculino' value='M'>Masculino</option>";
					if($rowFamilia['sexo'] == "F")
						echo "<option title='Femenino' value='F' selected='selected'>Femenino</option>";
					else
						echo "<option title='Femenino' value='F'>Femenino</option>";
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td>Telefono:</td>
			<td>
				<input name="ddn" type="text" id="ddn" value="<?php echo $rowFamilia['ddn'] ?>" size="5" />
				<input name="telefono" type="text" id="telefono" value="<?php echo $rowFamilia['telefono'] ?>" size="10" />
			</td>
			<td>Email:</td>
			<td><input name="email" type="text" id="email" value="<?php echo $rowFamilia['email'] ?>" size="60" /></td>
		</tr>
		<tr>
			<td>C.U.I.L.:</td>
			<td colspan="3"><input name="cuil" type="text" id="cuil" value="<?php echo $rowFamilia['cuil'] ?>" size="11" /></td>
		</tr>
		<tr>
			<td colspan="4"><div align="center"><p align="left" class="Estilo4">Datos Afiliatorios</p></div></td>
		</tr>
		<tr>
			<td>Parentesco:</td>
			<td>
				<select name="selectParentesco" id="selectParentesco">
					<option title="Seleccione un valor" value="">Seleccione un valor</option>
					<?php 
					$sqlParentesco="select * from parentesco order by codparent";
					$resParentesco=mysql_query($sqlParentesco,$db);
					while($rowParentesco=mysql_fetch_array($resParentesco)) { 	
						if($rowParentesco['codparent']!="00" && $rowParentesco['codparent']!="12") {	
							if ($rowFamilia['tipoparentesco'] == (int)$rowParentesco['codparent'])
								echo "<option title ='$rowParentesco[descrip]' value='$rowParentesco[codparent]' selected='selected'>".$rowParentesco['descrip']."</option>";
							else
								echo "<option title ='$rowParentesco[descrip]' value='$rowParentesco[codparent]'>".$rowParentesco['descrip']."</option>";
						}
					}
					?>
				</select>
			</td>
			<td>Fecha Ingreso O.S.:</td>
			<td><input name="fechaobrasocial" type="text" id="fechaobrasocial" value="<?php echo invertirFecha($rowFamilia['fechaobrasocial']) ?>" size="12" /></td>
		</tr>
		<tr>
			<td>Discapacidad:</td>
			<td>
				<input name="discapacidad" type="text" id="discapacidad" value="<?php echo $discapacidad?>" size="2" readonly="readonly" style="background-color:#CCCCCC" />
			  Certif:
				<input name="certificadodiscapacidad" type="text" id="certificadodiscapacidad" value="<?php echo $certificadodiscapacidad?>" size="2" readonly="readonly" style="background-color:#CCCCCC" />
			  Emision:
				<input name="emisiondiscapacidad" type="text" id="emisiondiscapacidad" value="<?php echo $emisiondiscapacidad?>" size="10" readonly="readonly" style="background-color:#CCCCCC" />
			  Vto:
				<input name="vencimientodiscapacidad" type="text" id="vencimientodiscapacidad" value="<?php echo $vencimientodiscapacidad?>" size="10" readonly="readonly" style="background-color:#CCCCCC" />
			</td>
			<td colspan="2"></td>
		</tr>
		<tr>
			<td>Estudia:</td>
			<td>
				<select name="selectEstudia" id="selectEstudia">
					<option title="Seleccione un valor" value="">Seleccione un valor</option>
					<?php 
					if($rowFamilia['estudia'] == 0)
						echo "<option title='No' value='0' selected='selected'>No</option>";
					else
						echo "<option title='No' value='0'>No</option>";
					if($rowFamilia['estudia'] == 1)
						echo "<option title='Si' value='1' selected='selected'>Si</option>";
					else
						echo "<option title='Si' value='1'>Si</option>";
					?>
				</select>
			</td>
			<td>Certificado Estudio </td>
			<td>
				<select name="selectCertificadoEstudio" id="selectCertificadoEstudio">
					<option title="Seleccione un valor" value="">Seleccione un valor</option>
					<?php 
					if($rowFamilia['certificadoestudio'] == 0)
						echo "<option title='No' value='0' selected='selected'>No</option>";
					else
						echo "<option title='No' value='0'>No</option>";
					if($rowFamilia['certificadoestudio'] == 1)
						echo "<option title='Si' value='1' selected='selected'>Si</option>";
					else
						echo "<option title='Si' value='1'>Si</option>";
					?>
				</select>
				Emision:
				<input name="emisioncertificadoestudio" type="text" id="emisioncertificadoestudio" value="<?php echo $emisioncertificadoestudio ?>" size="12"/> 
				Vto:
				<input name="vencimientocertificadoestudio" type="text" id="vencimientocertificadoestudio" value="<?php echo $vencimientocertificadoestudio ?>" size="12"/>
			</td>
		</tr>
		<tr>
			<td colspan="4"><div align="center"><p align="left" class="Estilo4">Datos Credencial</p></div></td>
		</tr>
		<tr>
			<td>Emision:</td>
			<td>
				<select name="selectEmiteCarnet" id="selectEmiteCarnet">
					<option title="Seleccione un valor" value="">Seleccione un valor</option>
					<?php 
					if($rowFamilia['emitecarnet'] == 1)
						echo "<option title='Emite Carnet' value='1' selected='selected'>Emite Carnet</option>";
					else
						echo "<option title='Emite Carnet' value='1'>Emite Carnet</option>";
					if($rowFamilia['emitecarnet'] == 0)
						echo "<option title='No Emite Carnet' value='0' selected='selected'>No Emite Carnet</option>";
					else
						echo "<option title='No Emite Carnet' value='0'>No Emite Carnet</option>";
					?>	
				</select>
			</td>
			<td>Cantidad Emitida:</td>
			<td>
				<input name="cantidadcarnet" type="text" id="cantidadcarnet" value="<?php echo $rowFamilia['cantidadcarnet'] ?>" size="4" readonly="readonly" style="background-color:#CCCCCC" />
			</td>
		</tr>
		<tr>
			<td>Fecha Ultima Emision:</td>
			<td>
				<input name="fechacarnet" type="text" id="fechacarnet" value="<?php echo invertirFecha($rowFamilia['fechacarnet']) ?>" size="10" readonly="readonly" style="background-color:#CCCCCC" />
			</td>
			<td>Tipo Credencial:</td>
			<td>
				<input name="tipocarnet" type="text" id="tipocarnet" value="<?php echo $rowFamilia['tipocarnet'] ?>" size="1" readonly="readonly" style="background-color:#CCCCCC" />
			</td>
		</tr>
		<tr>
			<td>Vencimiento:</td>
			<td colspan="3">
				<input name="vencimientocarnet" type="text" id="vencimientocarnet" value="<?php echo invertirFecha($rowFamilia['vencimientocarnet']) ?>" size="10" readonly="readonly" style="background-color:#CCCCCC" />
			</td>
		</tr>
		<tr>
			<td colspan="4"><div align="center"><p align="left" class="Estilo4"><?php if ($estfamilia == 0)  echo "Datos Inactividad"; ?></p></div></td>
		</tr>
		<tr>
			<td align="left" valign="top">
			<?php if ($estfamilia == 0)  echo "Fecha de Baja:"; ?>
			</td>
			<td align="left" valign="top">
			<?php if ($estfamilia == 0) {
					echo "<input name='fechabaja' type='text' id='fechabaja' value='".invertirFecha($rowFamilia['fechabaja'])."' size='10' readonly='true' style='background-color:#CCCCCC' />";
		  	}?>
			</td>
			<td align="left" valign="top">
			<?php if ($estfamilia == 0)  echo "Motivo de Baja:"; ?>
			</td>
			<td align="left" valign="top">
			<?php if ($estfamilia == 0) {
					echo "<textarea name='motivobaja' cols='50' rows='5' id='motivobaja' readonly='readonly' style='background-color:#CCCCCC'>".$rowFamilia['motivobaja']."</textarea>";
			}?>
			</td>
		</tr>
	</table>
<?php
if($estfamilia == 1) { 
?>
	<p></p>
	<table width="100%" border="0">
	  <tr>
		<td width="402" valign="middle"><div align="center">
			<input class="nover" type="submit" name="guardar" value="Guardar Cambios" /> 
			</div></td>
		<td width="402" valign="middle"><div align="center">
			<input class="nover" type="button" name="foto" value="Cargar Foto" onclick="location.href = 'agregaFoto.php?nroAfi=<?php echo $nroafiliado?>&estAfi=<?php echo $estafiliado?>&estFam=<?php echo $estfamilia?>&tipAfi=2&nroOrd=<?php echo $ordafiliado?>&fotAfi=0'" />
			</div></td>
		<td width="401" valign="middle"><div align="center">
			<input class="nover" type="button" name="bajar" value="Dar de Baja" onclick="location.href = 'bajaAfiliado.php?nroAfi=<?php echo $nroafiliado?>&estAfi=<?php echo $estafiliado?>&tipAfi=2&nroOrd=<?php echo $ordafiliado?>'"/> 
			</div></td>
	  </tr>
	</table>
<?php
}
if($estafiliado == 1 && $estfamilia == 0) { 
?>
	<p></p>
	<div align="center">
		<input class="nover" type="button" name="reactiva" value="Reactivar" onclick="location.href = 'reactivaAfiliado.php?nroAfi=<?php echo $nroafiliado?>&estAfi=<?php echo $estafiliado?>&tipAfi=2&nroOrd=<?php echo $ordafiliado?>'" /> 
	</div>
<?php
}
?>
	<p></p>
	<div align="center">
		<input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" /> 
	</div>
</form>
</body>
</html>
