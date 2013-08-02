<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
$base = $_SESSION['dbname'];

$sqlBuscaNro = "SELECT AUTO_INCREMENT FROM information_schema.TABLES
WHERE TABLE_SCHEMA = '$base' AND TABLE_NAME = 'titulares'";
//echo $sqlBuscaNro; echo "<br>";
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
<title>.: Afiliado :.</title>
<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(function($){
	$("#fechanacimiento").mask("99-99-9999");
	$("#fechaobrasocial").mask("99-99-9999");
	$("#numpostal").mask("9999");
	$("#cuil").mask("99999999999");
	$("#fechaempresa").mask("99-99-9999");
});

function localidadesPorCp(codigo) {
	$.ajax({
		type: "POST",
		dataType: 'html',
		url: "localidadPorCP.php",
		data: {codigo:codigo},
	}).done(function(respuesta){
		$("#selectLocalidad").html(respuesta);
       });
};

function cambioProvincia(locali) {
<?php 
	$sqlLocalidad="SELECT codlocali, codprovin FROM localidades";
	$resLocalidad=mysql_query($sqlLocalidad,$db);
	while($rowLocalidad=mysql_fetch_array($resLocalidad)) { ?>
		if (locali == <?php echo $rowLocalidad['codlocali'] ?>)  {
			<?php	
			$codProvincia= $rowLocalidad['codprovin'];
			$sqlProvincia="SELECT indpostal, descrip FROM provincia WHERE codprovin = $codProvincia";
			$resProvincia=mysql_query($sqlProvincia,$db);
			$rowProvincia=mysql_fetch_array($resProvincia);
			?>
			document.forms.formAfiliado.indpostal.value = "<?php echo $rowProvincia['indpostal']; ?>";
			document.forms.formAfiliado.nomprovin.value = "<?php echo $rowProvincia['descrip']; ?>";
			document.forms.formAfiliado.codprovin.value = "<?php echo $rowLocalidad['codprovin']; ?>";
		}
<?php } ?>
};

function nombreEmpresa(cuite) {
<?php 
	$sqlLeeEmpresa="SELECT cuit, nombre FROM empresas";
	$resLeeEmpresa=mysql_query($sqlLeeEmpresa,$db);
	while($rowLeeEmpresa=mysql_fetch_array($resLeeEmpresa)) { ?>
		if (cuite == <?php echo $rowLeeEmpresa['cuit'] ?>) {
			document.forms.formAfiliado.nombreempresa.value = "<?php echo $rowLeeEmpresa['nombre']; ?>";
		}
<?php } ?>
};

function buscarJurisdi(cuitj) {
	var opciones;
	document.forms.formAfiliado.selectDelega.length = 0;
	opciones = document.createElement("option");
	opciones.title = "Seleccione un valor";
	opciones.text = "Seleccione un valor";
	opciones.value = "";
	document.forms.formAfiliado.selectDelega.options.add(opciones);
<?php 
	$sqlLeeJurisdi="SELECT cuit, codidelega FROM jurisdiccion";
	$resLeeJurisdi=mysql_query($sqlLeeJurisdi,$db);
	while($rowLeeJurisdi=mysql_fetch_array($resLeeJurisdi)) { ?>
		if (cuitj == <?php echo $rowLeeJurisdi['cuit'] ?>) {
			<?php 
			$coddelega = $rowLeeJurisdi['codidelega'];
			$sqlLeeDelega = "SELECT codidelega, nombre FROM delegaciones WHERE codidelega = $coddelega";
			$resLeeDelega = mysql_query($sqlLeeDelega,$db);
			$rowLeeDelega = mysql_fetch_array($resLeeDelega);
			?>	
			opciones = document.createElement("option");
			opciones.title = "<?php echo $rowLeeDelega['nombre']; ?>";
			opciones.text = "<?php echo $rowLeeDelega['nombre']; ?>";
			opciones.value = <?php echo $rowLeeJurisdi['codidelega']; ?>;
			document.forms.formAfiliado.selectDelega.options.add(opciones);
		}
<?php } ?>
};

$(document).ready(function(){
	$("#numpostal").change(function(){
		var codigo = $(this).val();
		localidadesPorCp(codigo);
	});

	$("#selectLocalidad").change(function(){
		var locali = $(this).val();
		cambioProvincia(locali);
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
		if(tipotitu=="00" || tipotitu=="01") {
			document.forms.formAfiliado.cuitempresa.readOnly=false;
			document.forms.formAfiliado.cuitempresa.style.backgroundColor="#FFFFFF";
		}
		else {
			document.forms.formAfiliado.cuitempresa.readOnly=true;
			document.forms.formAfiliado.cuitempresa.style.backgroundColor="#CCCCCC";
		}
		document.forms.formAfiliado.cuil.focus();
	});

	$("#cuil").focusout(function(){
		var cuil = $(this).val();
		var titutipo = $("#selectSitTitular option:selected").val();
		if(titutipo=="00" || titutipo=="01" || titutipo=="03" || titutipo=="06" || titutipo=="09" || titutipo=="10" || titutipo=="11") {
			document.forms.formAfiliado.cuitempresa.value="";
			document.forms.formAfiliado.nombreempresa.value="";
		}
		if(titutipo=="02"|| titutipo=="08") {
			document.forms.formAfiliado.cuitempresa.value="33637617449";
			document.forms.formAfiliado.nombreempresa.value="";
		}
		if(titutipo=="04" || titutipo=="05" || titutipo=="07") {
			document.forms.formAfiliado.cuitempresa.value=cuil;
			document.forms.formAfiliado.nombreempresa.value="";
			nombreEmpresa(cuil);
		}
		document.forms.formAfiliado.cuitempresa.focus();
	});

	$("#cuitempresa")
	.change(function(){
		var cuite = $(this).val();
		nombreEmpresa(cuite);
	})
	.change(function(){
		var cuitj = $(this).val();
		buscarJurisdi(cuitj);
	});
});

function validar(formulario) {
	if (formulario.apellidoynombre.value == "") {
		alert("El apellido y nombre es obligatorio");
		return false;
	}
	
	if (formulario.selectTipDoc.options[formulario.selectTipDoc.selectedIndex].value == "") {
		alert("Debe seleccionar un tipo de documento");
		return false;
	}

	if (formulario.nrodocumento.value == "") {
		alert("El numero de documento es obligatorio");
		return false;
	} else {
		if (!esEnteroPositivo(formulario.nrodocumento.value)) {
			alert("El numero de documento debe ser numerico");
			return false;
		}
	}

	if (formulario.fechanacimiento.value == "") {
		alert("La fecha de nacimiento es obligatoria");
		return false;
	} else {
		if (!esFechaValida(formulario.fechanacimiento.value)) {
			alert("La fecha de nacimiento es invalida");
			return false;
		}
	}

	if (formulario.selectNacion.options[formulario.selectNacion.selectedIndex].value == "") {
		alert("Debe seleccionar una nacionalidad");
		return false;
	}

	if (formulario.selectSexo.options[formulario.selectSexo.selectedIndex].value == "") {
		alert("Debe seleccionar un sexo");
		return false;
	}

	if (formulario.selectEstCiv.options[formulario.selectEstCiv.selectedIndex].value == "") {
		alert("Debe seleccionar un estado civil");
		return false;
	}

	if (formulario.domicilio.value == "") {
		alert("El domicilio es obligatorio");
		return false;
	}

	if (formulario.numpostal.value == "") {
		alert("El codigo postal es obligatorio");
		return false;
	} else {
		if (!esEnteroPositivo(formulario.numpostal.value)){
		 	alert("El codigo postal debe ser numerico");
			return false;
		}
	}

	if (formulario.alfapostal.value != "") {
		if (isNumber(formulario.alfapostal.value)){
		 	alert("El componente alfabetico del codigo postal no puede ser numerico");
			return false;
		}
	}

	if (formulario.selectLocalidad.options[formulario.selectLocalidad.selectedIndex].value == "") {
		alert("Debe Seleccionar una Localidad");
		return false;
	}

	if (formulario.ddn.value != "") {
		if (!esEnteroPositivo(formulario.ddn.value)) {
			alert("El codigo de area debe ser numerico");
			return false;
		}
	} else {
		formulario.ddn.value = "0";
	}

	if (formulario.telefono.value != "") {
		if (!esEnteroPositivo(formulario.telefono.value)) {
			alert("El telefono debe ser numerico");
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
			return false;
		}
	}

	if (formulario.fechaobrasocial.value == "") {
		alert("La fecha de ingreso a la obra social es obligatoria");
		return false;
	} else {
		if (!esFechaValida(formulario.fechaobrasocial.value)) {
			alert("La fecha de ingreso a la obra social es invalida");
			return false;
		}
	}

	if (formulario.selectTipoAfil.options[formulario.selectTipoAfil.selectedIndex].value == "") {
		alert("Debe seleccionar un tipo de afiliado");
		return false;
	}

	alert(formulario.selectSitTit.options[formulario.selectSitTit.selectedIndex].value);

	if (formulario.selectTipoAfil.options[formulario.selectTipoAfil.selectedIndex].value == "O") {
		if (formulario.solicitudopcion.value == "") {
			alert("Debe especificar el numero de solicitud de opcion");
			return false;
		} else {
			if (!esEnteroPositivo(formulario.solicitudopcion.value)) {
		 		alert("El numero de solicitud de opcion debe ser numerico");
				return false;
			}
		}
	}

	alert(formulario.selectSitTit.options[formulario.selectSitTit.selectedIndex].value);

//	if (formulario.selectSitTit.options[formulario.selectSitTit.selectedIndex].value == "") {
//		alert("Debe seleccionar un tipo de titularidad");
//		return false;
//	}

	if (formulario.cuil.value == "") {
		alert("El C.U.I.L. es obligatorio");
		return false;
	}

	if (formulario.cuitempresa.value == "") {
		alert("El C.U.I.T. de la empresa es obligatorio");
		return false;
	} else {
		if (!esEnteroPositivo(formulario.cuitempresa.value)) {
		 	alert("El C.U.I.T. de la empresa debe ser numerico");
			return false;
		}
	}

	if (formulario.fechaempresa.value == "") {
		alert("La fecha de ingreso a la empresa es obligatoria");
		return false;
	} else {
		if (!esFechaValida(formulario.fechaempresa.value)) {
			alert("La fecha de ingreso a la empresa es invalida");
			return false;
		}
	}

	if (formulario.selectDelega.options[formulario.selectDelega.selectedIndex].value == "") {
		alert("Debe seleccionar una jurisdiccion");
		return false;
	}

	if (formulario.selectEmiteCarnet.options[formulario.selectEmiteCarnet.selectedIndex].value == "") {
		alert("Debe seleccionar si se emite o no la credencial");
		return false;
	}

	return true;
}
</script>
</head>
<body bgcolor="#CCCCCC" >
<form id="formAfiliado" name="formAfiliado" method="post" onSubmit="return validar(this)" action="guardaAltaAfiliado.php">
<table width="1205" border="0">
	<tr align="center" valign="top">
      <td width="1205" valign="middle"><div align="center">
        <input type="reset" name="volver" value="Volver" onClick="location.href = 'moduloABM.php'" align="center"/> 
        </div></td>
	</tr>
</table>
<table width="1205" border="0">
	<tr>
      <td width="1205" valign="middle"><div align="center" class="Estilo4">Nuevo Afiliado</div></td>
	</tr>
</table>
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
			     		$sqlTipDoc="select * from tipodocumento";
						$resTipDoc=mysql_query($sqlTipDoc,$db);
						while($rowTipDoc=mysql_fetch_array($resTipDoc)) { 	
							echo "<option title ='$rowTipDoc[descrip]' value='$rowTipDoc[codtipdoc]'>".$rowTipDoc['descrip']."</option>";
						}
			        ?>
            		</select>
					<input name="nrodocumento" type="text" id="nrodocumento" value="" size="10" maxlength="10" /></td>
    <td width="173">Fecha de Nacimiento:</td>
    <td width="460"><input name="fechanacimiento" type="text" id="fechanacimiento" value="" size="12" /></td>
  </tr>
  <tr>
    <td>Nacionalidad:</td>
    <td><select name="selectNacion" id="selectNacion">
                   <option title="Seleccione un valor" value="">Seleccione un valor</option>
                   <?php 
			     		$sqlNacion="select * from nacionalidad order by descrip";
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
			     		$sqlEstCiv="select * from estadocivil";
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
		<input name="codprovin" type="text" id="codprovin" value="" size="2" readonly="true" style="background-color:#CCCCCC" />
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
			     		$sqlSitTit="select * from tipotitular";
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
    <td><input name="cuitempresa" type="text" id="cuitempresa" value="" size="11" maxlength="11" readonly="true" style="background-color:#CCCCCC" />
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
<table width="1205" border="0">
  <tr>
    <td valign="middle"><div align="center"><input type="submit" name="guardar" value="Guardar" align="center"/></div></td>
    </tr>
</table>
</form>
</body>
</html>
