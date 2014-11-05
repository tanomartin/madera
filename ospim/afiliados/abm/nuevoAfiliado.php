<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
$base = $_SESSION['dbname'];

$sqlBuscaNro = "SELECT AUTO_INCREMENT FROM information_schema.TABLES
WHERE TABLE_SCHEMA = '$base' AND TABLE_NAME = 'titulares'";
$resBuscaNro = mysql_query($sqlBuscaNro,$db);
$rowBuscaNro = mysql_fetch_array($resBuscaNro);
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
<title>.: Afiliado :.</title>
<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/lib/jquery-ui.min.js" type="text/javascript"></script>
<script src="/lib/jquery.blockUI.js" type="text/javascript"></script>
<script src="/lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(function($){
	$("#fechanacimiento").mask("99-99-9999");
	$("#fechaobrasocial").mask("99-99-9999");
	$("#numpostal").mask("9999");
	$("#cuil").mask("99999999999");
	$("#fechaempresa").mask("99-99-9999");
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

	$("#numpostal").change(function(){
		var codigo = $(this).val();
		$.ajax({
			type: "POST",
			dataType: 'html',
			url: "localidadPorCP.php",
			data: {codigo:codigo},
		}).done(function(respuesta){
			$("#selectLocalidad").html(respuesta);
		});
	});

	$("#selectLocalidad").change(function(){
		var locali = $(this).val();
		$.ajax({
			type: "POST",
			dataType: "json",
			url: "cambioProvincia.php",
			data: {locali:locali},
		}).done(function(respuesta){
			$("#indpostal").val(respuesta.indpostal);
			$("#nomprovin").val(respuesta.descrip);
			$("#codprovin").val(respuesta.codprovin);
		});
	});

	$("#selectTipoAfil").change(function(){
		var tipoafi = $(this).val();
		if(tipoafi=="O") {
			document.forms.formAfiliado.solicitudopcion.readOnly=false;
			document.forms.formAfiliado.solicitudopcion.style.backgroundColor="#FFFFFF";
		}
		else {
			document.forms.formAfiliado.solicitudopcion.value="";
			document.forms.formAfiliado.solicitudopcion.readOnly=true;
			document.forms.formAfiliado.solicitudopcion.style.backgroundColor="#CCCCCC";
		}
	});

	$("#selectSitTitular").change(function(){
		var tipotitu = $(this).val();
		if(tipotitu!="00" && tipotitu!="01") {
			document.forms.formAfiliado.cuitempresa.readOnly=true;
			document.forms.formAfiliado.cuitempresa.style.backgroundColor="#CCCCCC";
			if(tipotitu=="08"|| tipotitu=="10") {
				document.forms.formAfiliado.cuitempresa.value="33637617449";
				document.forms.formAfiliado.nombreempresa.value="";
				var cuite = "33637617449";
				$.ajax({
					type: "POST",
					dataType: "html",
					url: "nombreEmpresa.php",
					data: {cuit:cuite},
				}).done(function(respuesta){
					$("#nombreempresa").val(respuesta);
				});
				$("#selectDelega option[value='']").prop('selected',true);
				var cuitj = "33637617449";
				$.ajax({
					type: "POST",
					dataType: 'html',
					url: "buscaJurisdicciones.php",
					data: {cuit:cuitj},
				}).done(function(respuesta){
					$("#selectDelega").html(respuesta);
				});
			}
			if(tipotitu=="04" || tipotitu=="05" || tipotitu=="07") {
				document.forms.formAfiliado.cuitempresa.value="";
				document.forms.formAfiliado.nombreempresa.value="";
			}
		} else {
			document.forms.formAfiliado.cuitempresa.readOnly=false;
			document.forms.formAfiliado.cuitempresa.style.backgroundColor="#FFFFFF";
		}
		$("#cuil").focus();
	});

	$("#cuil").focusout(function(){
		var cuil = $(this).val();
		var titutipo = $("#selectSitTitular option:selected").val();
		
		if(titutipo=="04" || titutipo=="05" || titutipo=="07") {
			document.forms.formAfiliado.cuitempresa.value=cuil;
			document.forms.formAfiliado.nombreempresa.value="";
			$.ajax({
				type: "POST",
				dataType: 'html',
				url: "nombreEmpresa.php",
				data: {cuit:cuil},
			}).done(function(respuesta){
				$("#nombreempresa").val(respuesta);
			});
			$("#selectDelega option[value='']").prop('selected',true);
			$.ajax({
				type: "POST",
				dataType: 'html',
				url: "buscaJurisdicciones.php",
				data: {cuit:cuil},
			}).done(function(respuesta){
				$("#selectDelega").html(respuesta);
			});
		}
		$("#cuitempresa").focus();
	});

	$("#cuitempresa")
	.change(function(){
		var cuite = $(this).val();
		$.ajax({
			type: "POST",
			dataType: 'html',
			url: "nombreEmpresa.php",
			data: {cuit:cuite},
		}).done(function(respuesta){
			$("#nombreempresa").val(respuesta);
		});
	})
	.change(function(){
		var cuitj = $(this).val();
		$.ajax({
			type: "POST",
			dataType: 'html',
			url: "buscaJurisdicciones.php",
			data: {cuit:cuitj},
		}).done(function(respuesta){
			$("#selectDelega").html(respuesta);
		});
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

	if (formulario.edad.value < 14 || formulario.edad.value > 90) {
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

	if (formulario.selectEstCiv.options[formulario.selectEstCiv.selectedIndex].value == "") {
		alert("Debe seleccionar un estado civil");
		document.getElementById("selectEstCiv").focus();
		return false;
	}

	if (formulario.domicilio.value == "") {
		alert("El domicilio es obligatorio");
		document.getElementById("domicilio").focus();
		return false;
	}

	if (formulario.numpostal.value == "") {
		alert("El codigo postal es obligatorio");
		document.getElementById("numpostal").focus();
		return false;
	} else {
		if (!esEnteroPositivo(formulario.numpostal.value)){
		 	alert("El codigo postal debe ser numerico");
			document.getElementById("numpostal").focus();
			return false;
		}
	}

	if (formulario.alfapostal.value != "") {
		if (isNumber(formulario.alfapostal.value)){
		 	alert("El componente alfabetico del codigo postal no puede ser numerico");
			document.getElementById("alfapostal").focus();
			return false;
		}
	}

	if (formulario.selectLocalidad.options[formulario.selectLocalidad.selectedIndex].value == "") {
		alert("Debe Seleccionar una Localidad");
		document.getElementById("selectLocalidad").focus();
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

	if (formulario.selectTipoAfil.options[formulario.selectTipoAfil.selectedIndex].value == "") {
		alert("Debe seleccionar un tipo de afiliado");
		document.getElementById("selectTipoAfil").focus();
		return false;
	}

	if (formulario.selectTipoAfil.options[formulario.selectTipoAfil.selectedIndex].value == "O") {
		if (formulario.solicitudopcion.value == "") {
			alert("Debe especificar el numero de solicitud de opcion");
			document.getElementById("solicitudopcion").focus();
			return false;
		} else {
			if (!esEnteroPositivo(formulario.solicitudopcion.value)) {
		 		alert("El numero de solicitud de opcion debe ser numerico");
				document.getElementById("solicitudopcion").focus();
				return false;
			}
		}
	}

	if (formulario.selectSitTitular.options[formulario.selectSitTitular.selectedIndex].value == "") {
		alert("Debe seleccionar un tipo de titularidad");
		document.getElementById("selectSitTitular").focus();
		return false;
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

	if (formulario.cuitempresa.value == "") {
		alert("El C.U.I.T. de la empresa es obligatorio");
		document.getElementById("cuitempresa").focus();
		return false;
	} else {
		if (!verificaCuilCuit(formulario.cuitempresa.value)) {
			alert("El C.U.I.T. de la empresa es invalido");
			document.getElementById("cuitempresa").focus();
			return false;
		}
		if (formulario.selectSitTitular.options[formulario.selectSitTitular.selectedIndex].value == "00" ||
			formulario.selectSitTitular.options[formulario.selectSitTitular.selectedIndex].value == "01" ||
			formulario.selectSitTitular.options[formulario.selectSitTitular.selectedIndex].value == "08" ||
			formulario.selectSitTitular.options[formulario.selectSitTitular.selectedIndex].value == "10" ) {
			if (formulario.cuil.value == formulario.cuitempresa.value) {
				alert("Para este tipo de Titularidad el C.U.I.L. y el C.U.I.T. no pueden ser iguales");
				document.getElementById("cuil").focus();
				return false;
			}
		} else {
			if (formulario.cuil.value != formulario.cuitempresa.value) {
				alert("Para este tipo de Titularidad el C.U.I.L. y el C.U.I.T. no pueden ser distintos");
				document.getElementById("cuil").focus();
				return false;
			}
		}
	}

	if (formulario.fechaempresa.value == "") {
		alert("La fecha de ingreso a la empresa es obligatoria");
		document.getElementById("fechaempresa").focus();
		return false;
	} else {
		if (!esFechaValida(formulario.fechaempresa.value)) {
			alert("La fecha de ingreso a la empresa es invalida");
			document.getElementById("fechaempresa").focus();
			return false;
		}

		var fechanac = new Date(Date.parse(invertirFecha(formulario.fechanacimiento.value)));
		var fechaemp = new Date(Date.parse(invertirFecha(formulario.fechaempresa.value)));

		if (fechaemp < fechanac) {
			alert("La fecha de ingreso a la empresa no puede ser menor que la fecha de nacimiento");
			document.getElementById("fechaempresa").focus();
			return false;
		}
	}

	if (formulario.selectDelega.options[formulario.selectDelega.selectedIndex].value == "") {
		alert("Debe seleccionar una jurisdiccion");
		document.getElementById("selectDelega").focus();
		return false;
	}

	if (formulario.selectEmiteCarnet.options[formulario.selectEmiteCarnet.selectedIndex].value == "") {
		alert("Debe seleccionar si se emite o no la credencial");
		document.getElementById("selectEmiteCarnet").focus();
		return false;
	}

	$.blockUI({ message: "<h1>Guardando datos. Aguarde por favor...</h1>" });

	return true;
}
</script>
</head>
<body bgcolor="#CCCCCC" >
<form id="formAfiliado" name="formAfiliado" method="post" onSubmit="return validar(this)" action="guardaAltaAfiliado.php">
<div align="center">
	<input class="nover" type="reset" name="volver" value="Volver" onClick="location.href = 'moduloABM.php'" align="center"/> 
</div>
<p></p>
<div align="center" class="Estilo4">Nuevo Afiliado</div>
<table width="1205" height="100" border="0">
  <tr>
	<td width="212" align="left" valign="middle"><img src="../img/Familiar sin Foto.jpg" alt="Foto" width="115" height="115"></td>
    <td width="983" align="left" valign="middle"><div align="left"><span class="Estilo4"><strong>Numero Afiliado</strong></span><strong>  
    <input name="nroafiliado" type="text" id="nroafiliado" value="<?php echo $rowBuscaNro['AUTO_INCREMENT'] ?>" size="9" readonly="true" style="background-color:#CCCCCC" /></strong></div></td>
  </tr>
</table>
<table width="1205" border="0">
  <tr>
    <td colspan="4"><div align="center">
      <p align="left" class="Estilo4">Datos Identificatorios</p>
      </div></td>
  </tr>
  <tr>
    <td width="238">Apellido y Nombre:</td>
    <td colspan="3"><input name="apellidoynombre" type="text" id="apellidoynombre" value="" size="100" maxlength="100" /></td>
  </tr>
  <tr>
    <td>Documento:</td>
    <td width="316"><select name="selectTipDoc" id="selectTipDoc">
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
    <td width="173">Fecha de Nacimiento:</td>
    <td width="460"><input name="fechanacimiento" type="text" id="fechanacimiento" value="" size="12" /> Edad:
					<input name="edad" type="text" id="edad" value="" size="2" readonly="true" style="background-color:#CCCCCC"/></td>
  </tr>
  <tr>
    <td>Nacionalidad:</td>
    <td><select name="selectNacion" id="selectNacion">
                   <option title="Seleccione un valor" value="">Seleccione un valor</option>
                   <?php 
			     		$sqlNacion="SELECT * FROM nacionalidad ORDER BY descrip";
						$resNacion=mysql_query($sqlNacion,$db);
						while($rowNacion=mysql_fetch_array($resNacion)) { 	
							echo "<option title ='$rowNacion[descrip]' value='$rowNacion[codnacion]'>".$rowNacion['descrip']."</option>";
						}
			        ?>
    	</select>
	</td>
    <td>Sexo:</td>
    <td><select name="selectSexo" id="selectSexo">
             <option title="Seleccione un valor" value="">Seleccione un valor</option>
             <option title="Masculino" value="M">Masculino</option>
             <option title="Femenino" value="F">Femenino</option>
   		</select>
	</td>
  </tr>
  <tr>
    <td>Estado Civil:</td>
    <td colspan="3"><select name="selectEstCiv" id="selectEstCiv">
                   <option title="Seleccione un valor" value="">Seleccione un valor</option>
                   <?php 
			     		$sqlEstCiv="SELECT * FROM estadocivil";
						$resEstCiv=mysql_query($sqlEstCiv,$db);
						while($rowEstCiv=mysql_fetch_array($resEstCiv)) { 	
							echo "<option title ='$rowEstCiv[descrip]' value='$rowEstCiv[codestciv]'>".$rowEstCiv['descrip']."</option>";
						}
			        ?>
            		</select>
	</td>
  </tr>
  <tr>
    <td colspan="4"><div align="center" class="Estilo4">
      <div align="left">Datos Domiciliarios</div>
    </div></td>
  </tr>
  <tr>
    <td>Domicilio:</td>
    <td><input name="domicilio" type="text" id="domicilio" value="" size="50" maxlength="50" /></td>
    <td>C.P.</td>
    <td><input name="indpostal" type="text" id="indpostal" value="" size="1" readonly="true" style="background-color:#CCCCCC" />
		<input name="numpostal" type="text" id="numpostal" value="" size="4" maxlength="4" />
		<input name="alfapostal" type="text" id="alfapostal" value="" size="3" maxlength="3" /></td>
  </tr>
  <tr>
    <td>Localidad:</td>
    <td><select name="selectLocalidad" id="selectLocalidad">
        <option title="Seleccione un valor" value="">Seleccione un valor</option>
        </select>
	</td>
    <td>Provincia:</td>
    <td><input name="nomprovin" type="text" id="nomprovin" value="" size="50" readonly="true" style="background-color:#CCCCCC" />
		<input name="codprovin" type="text" id="codprovin" value="" size="2" readonly="true" style="visibility:hidden" />
	</td>
  </tr>
  <tr>
    <td>Telefono:</td>
    <td><input name="ddn" type="text" id="ddn" value="" size="5" maxlength="5" />
		<input name="telefono" type="text" id="telefono" value="" size="12" maxlength="10" /></td>
    <td>Correo Electronico:</td>
    <td><input name="email" type="text" id="email" value="" size="60" maxlength="60" /></td>
  </tr>
  <tr>
    <td colspan="4"><div align="center" class="Estilo4">
      <div align="left">Datos Afiliatorios</div>
    </div></td>
  </tr>
  <tr>
    <td>Fecha Ingreso O.S.: </td>
    <td>
	<input name="fechaobrasocial" type="text" id="fechaobrasocial" value="" size="12" /></td>
    <td>Tipo Afiliado: </td>
    <td><select name="selectTipoAfil" id="selectTipoAfil">
             <option title="Seleccione un valor" value="">Seleccione un valor</option>
             <option title="Regular" value="R">Regular</option>
             <option title="Solo OSPIM" value="S">Solo OSPIM</option>
             <option title="Por Opcion" value="O">Por Opcion</option>
   		</select>
		<input name="solicitudopcion" type="text" id="solicitudopcion" value="" size="8" maxlength="8" readonly="true" style="background-color:#CCCCCC" />
	</td>
  </tr>
  <tr>
    <td>Tipo Titularidad:</td>
    <td colspan="3"><select name="selectSitTitular" id="selectSitTitular">
                   <option title="Seleccione un valor" value="">Seleccione un valor</option>
                   <?php 
			     		$sqlSitTit="SELECT * FROM tipotitular WHERE codtiptit not in(2,3,6,9,11)";
						$resSitTit=mysql_query($sqlSitTit,$db);
						while($rowSitTit=mysql_fetch_array($resSitTit)) { 	
							echo "<option title ='$rowSitTit[descrip]' value='$rowSitTit[codtiptit]'>".$rowSitTit['descrip']."</option>";
						}
			        ?>
            		</select></td>
    </tr>
  <tr>
    <td colspan="4"><div align="center" class="Estilo4">
      <div align="left">Datos Laborales</div>
    </div></td>
  </tr>
  <tr>
    <td>C.U.I.L.:</td>
    <td><input name="cuil" type="text" id="cuil" value="" size="13" maxlength="11" /></td>
    <td>C.U.I.T. Empresa:</td>
    <td><input name="cuitempresa" type="text" id="cuitempresa" value="" size="13" maxlength="11" />
	    <input name="nombreempresa" type="text" id="nombreempresa" value="" size="50" readonly="true" style="background-color:#CCCCCC" />
	</td>
  </tr>
  <tr>
    <td>Fecha  Ingreso Empresa:</td>
    <td><input name="fechaempresa" type="text" id="fechaempresa" value="" size="12" /></td>
    <td>Jurisdiccion del Titular:</td>
    <td><select name="selectDelega" id="selectDelega">
        <option title="Seleccione un valor" value="">Seleccione un valor</option>
        </select>
	</td>
  </tr>
  <tr>
    <td>Categoria:</td>
    <td colspan="3"><input name="categoria" type="text" id="categoria" value="" size="100" maxlength="100" /></td>
  </tr>
  <tr>
    <td colspan="4"><div align="center" class="Estilo4">
      <div align="left">Datos Credencial</div>
    </div></td>
  </tr>
  <tr>
    <td>Emision:</td>
    <td colspan="3"><select name="selectEmiteCarnet" id="selectEmiteCarnet">
             			<option title="Seleccione un valor" value="">Seleccione un valor</option>
             			<option title="Emite Carnet" value="1">Emite Carnet</option>
             			<option title="No Emite Carnet" value="0">No Emite Carnet</option>
			   		</select>
	</td>
    </tr>
</table>
<p></p>
<div align="center">
	<input class="nover" type="submit" name="guardar" value="Guardar" align="center"/>
</div>
</form>
</body>
</html>
