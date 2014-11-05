<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");

$nroafiliado=$_GET['nroAfi'];
$nroorden=($_GET['nueOrd']+1);
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
<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/jquery-ui.min.js" type="text/javascript"></script>
<script src="/lib/jquery.blockUI.js" type="text/javascript"></script>
<script src="/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(function($){
	$("#fechanacimiento").mask("99-99-9999");
	$("#fechaobrasocial").mask("99-99-9999");
	$("#cuil").mask("99999999999");
	$("#emisioncertificadoestudio").mask("99-99-9999");
	$("#vencimientocertificadoestudio").mask("99-99-9999");
});

$(document).ready(function(){
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
<form id="formAgregaFamiliar" name="formAgregaFamiliar" method="post" onSubmit="return validar(this)" action="guardaAltaFamiliar.php">
	<div align="center">
		<input class="nover" type="reset" name="volver" value="Volver" onClick="location.href = 'afiliado.php?nroAfi=<?php echo $nroafiliado?>&estAfi=1'" align="center"/> 
	</div>
	<p></p>
	<div align="center" class="Estilo4">Alta de Familiar</div>
	<p></p>	
<table width="1205" height="100" border="0">
  <tr>
	<td width="212" align="left" valign="middle"><img src="../img/Familiar sin Foto.jpg" alt="Foto" width="115" height="115"></td>
    <td width="983" align="left" valign="middle"><div align="left"><span class="Estilo4"><strong>Numero Afiliado</strong></span>
	<input name="nroafiliado" type="text" id="nroafiliado" value="<?php echo $nroafiliado ?>" size="9" readonly="true" style="background-color:#CCCCCC" />
    </div></td>
  </tr>
</table>
<table width="1205" border="0">
  <tr>
    <td colspan="5"><div align="center">
      <p align="left" class="Estilo4">Datos Identificatorios</p>
      </div></td>
  </tr>
  <tr>
    <td width="214">Apellido y Nombre:</td>
    <td colspan="4"><input name="apellidoynombre" type="text" id="apellidoynombre" value="" size="100" maxlength="100" />	</td>
  </tr>
  <tr>
    <td>Documento:</td>
    <td colspan="2"><select name="selectTipDoc" id="selectTipDoc">
                   <option title="Seleccione un valor" value="">Seleccione un valor</option>
                   <?php 
			     		$sqlTipDoc="SELECT * FROM tipodocumento";
						$resTipDoc=mysql_query($sqlTipDoc,$db);
						while($rowTipDoc=mysql_fetch_array($resTipDoc)) { 	
							echo "<option title ='$rowTipDoc[descrip]' value='$rowTipDoc[codtipdoc]'>".$rowTipDoc['descrip']."</option>";
						}
			        ?>
            		</select>
					<input name="nrodocumento" type="text" id="nrodocumento" value="" size="10" maxlength="10" /></td>
    <td width="162">Fecha Nacimiento:</td>
    <td width="474"><input name="fechanacimiento" type="text" id="fechanacimiento" value="" size="12" /> Edad:
					<input name="edad" type="text" id="edad" value="" size="2" readonly="true" style="background-color:#CCCCCC"/></td>
  </tr>
  <tr>
    <td>Nacionalidad:</td>
    <td colspan="2"><select name="selectNacion" id="selectNacion">
                   <option title="Seleccione un valor" value="">Seleccione un valor</option>
                   <?php 
			     		$sqlNacion="SELECT * FROM nacionalidad ORDER BY descrip";
						$resNacion=mysql_query($sqlNacion,$db);
						while($rowNacion=mysql_fetch_array($resNacion)) { 	
							echo "<option title ='$rowNacion[descrip]' value='$rowNacion[codnacion]'>".$rowNacion['descrip']."</option>";
						}
			        ?>
    	</select></td>
    <td>Sexo:</td>
    <td><select name="selectSexo" id="selectSexo">
             <option title="Seleccione un valor" value="">Seleccione un valor</option>
             <option title="Masculino" value="M">Masculino</option>
             <option title="Femenino" value="F">Femenino</option>
   		</select></td>
  </tr>
  <tr>
    <td>Telefono:</td>
    <td colspan="2"><input name="ddn" type="text" id="ddn" value="" size="5" maxlength="5" />
		<input name="telefono" type="text" id="telefono" value="" size="12" maxlength="10" /></td>
    <td>Email:</td>
    <td><input name="email" type="text" id="email" value="" size="60" maxlength="60" /></td>
  </tr>
  <tr>
    <td>C.U.I.L.:</td>
    <td colspan="4"><input name="cuil" type="text" id="cuil" value="" size="13" maxlength="11" /></td>
  </tr>
  <tr>
    <td colspan="5"><div align="center" class="Estilo4">
      <div align="left">Datos Afiliatorios</div>
    </div></td>
  </tr>
  <tr>
    <td>Parentesco:</td>
    <td colspan="2">
		<select name="selectParentesco" id="selectParentesco">
                   <option title="Seleccione un valor" value="">Seleccione un valor</option>
                   <?php 
			     		$sqlParentesco="SELECT * FROM parentesco ORDER BY codparent";
						$resParentesco=mysql_query($sqlParentesco,$db);
						while($rowParentesco=mysql_fetch_array($resParentesco)) { 	
							if($rowParentesco['codparent']!="00" && $rowParentesco['codparent']!="12") {
								echo "<option title ='$rowParentesco[descrip]' value='$rowParentesco[codparent]'>".$rowParentesco['descrip']."</option>";
							}
						}
			        ?>
    	</select></td>
    <td>Fecha Ingreso O.S.:</td>
    <td><input name="fechaobrasocial" type="text" id="fechaobrasocial" value="" size="12" /></td>
  </tr>
  <tr>
    <td>Estudia:</td>
    <td colspan="2">
		<select name="selectEstudia" id="selectEstudia">
        	<option title="Seleccione un valor" value="">Seleccione un valor</option>
            <option title="Si" value="1">Si</option>
            <option title="No" value="0">No</option>
		</select></td>
    <td>Certificado Estudio </td>
    <td><select name="selectCertificadoEstudio" id="selectCertificadoEstudio">
      <option title="Seleccione un valor" value="">Seleccione un valor</option>
      <option title="Si" value="1">Si</option>
      <option title="No" value="0">No</option>
    </select> Emision:
		<input name="emisioncertificadoestudio" type="text" id="emisioncertificadoestudio" value="" size="12"/> 
		Vto:
		<input name="vencimientocertificadoestudio" type="text" id="vencimientocertificadoestudio" value="" size="12"/></td>
  </tr>
  <tr>
    <td colspan="5"><div align="center" class="Estilo4">
      <div align="left">Datos Credencial </div>
    </div></td>
  </tr>
  <tr>
    <td>Emision:</td>
    <td colspan="4"><select name="selectEmiteCarnet" id="selectEmiteCarnet">
             			<option title="Seleccione un valor" value="">Seleccione un valor</option>
             			<option title="Emite Carnet" value="1">Emite Carnet</option>
             			<option title="No Emite Carnet" value="0">No Emite Carnet</option>
			   		</select></td>
    </tr>
</table>
<table width="1205" border="0">
  <tr>
    <td valign="middle"><div align="center">
        <input class="nover" type="submit" name="guardar" value="Guardar" align="center"/> 
        </div></td>
    </tr>
</table>
</form>
</body>
</html>
