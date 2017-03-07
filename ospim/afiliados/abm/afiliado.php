<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
$nroafiliado=$_GET['nroAfi'];
$estafiliado=$_GET['estAfi'];

if($estafiliado == 1) {
	$sqlTitular = "SELECT * FROM titulares WHERE nroafiliado = '$nroafiliado'";
}

if($estafiliado == 0) {
	$sqlTitular = "SELECT * FROM titularesdebaja WHERE nroafiliado = '$nroafiliado'";
}

$resTitular = mysql_query($sqlTitular,$db);
$rowTitular = mysql_fetch_array($resTitular);

$cuil = $rowTitular['cuil'];
$cuitempresa = $rowTitular['cuitempresa'];
$delegacion = $rowTitular['codidelega'];
$provincia = $rowTitular['codprovin'];

$sqlEmpresa = "SELECT * FROM empresas WHERE cuit = '$cuitempresa'";
$resEmpresa = mysql_query($sqlEmpresa,$db);
if (mysql_num_rows($resEmpresa)== 0) {
	$sqlEmpresa = "SELECT * FROM empresasdebaja WHERE cuit = '$cuitempresa'";
	$resEmpresa = mysql_query($sqlEmpresa,$db);
	$rowEmpresa = mysql_fetch_array($resEmpresa);
}
else
	$rowEmpresa = mysql_fetch_array($resEmpresa);

$sqlDelegacion = "SELECT * FROM delegaciones WHERE codidelega = '$delegacion'";
$resDelegacion = mysql_query($sqlDelegacion,$db);
$rowDelegacion = mysql_fetch_array($resDelegacion);

$sqlProvi = "SELECT codprovin, descrip FROM provincia WHERE codprovin = '$provincia'";
$resProvi = mysql_query($sqlProvi,$db);
$rowProvi = mysql_fetch_array($resProvi);

if($rowTitular['discapacidad'] == 1) {
	$sqlLeeDiscapacidad = "SELECT emisioncertificado, vencimientocertificado FROM discapacitados WHERE nroafiliado = '$nroafiliado' and nroorden = 0";
	$resLeeDiscapacidad = mysql_query($sqlLeeDiscapacidad,$db);
	$rowLeeDiscapacidad = mysql_fetch_array($resLeeDiscapacidad);
	
	$discapacidad = "Si";
	if($rowTitular['certificadodiscapacidad'] == 1) {
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
<title>.: Afiliado :.</title>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css" type="text/css" id="" media="print, projection, screen" />
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery-ui.min.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(function($){
	$("#fechanacimiento").mask("99-99-9999");
	$("#fechaobrasocial").mask("99-99-9999");
	$("#numpostal").mask("9999");
	$("#cuil").mask("99999999999");
	$("#fechaempresa").mask("99-99-9999");
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

	$("#familiares")
		.tablesorter({
			theme: 'blue',
			widthFixed: true, 
			widgets: ["zebra"],
			headers:{0:{sorter:false}, 7:{sorter:false}}
		});
});

function consultaDdjjAportes(cuilafi) {
	param = "cuiAfi=" + cuilafi;
	opciones = "top=50,left=50,width=900,height=680,toolbar=no,menubar=no,status=no,dependent=yes,hotkeys=no,scrollbars=yes,resizable=no";
	window.open("ddjjAportesAfiliadoLess.php?" + param, "", opciones);
};

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
	
	$.blockUI({ message: "<h1>Guardando cambios. Aguarde por favor...</h1>" });

	return true;
};

</script>
</head>
<body bgcolor="#CCCCCC" >
<form id="formAfiliado" name="formAfiliado" method="post" onsubmit="return validar(this)" action="guardaModificacionAfiliado.php">
<div align="center">
	<input class="nover" type="button" name="volver" value="Volver" onClick="location.href = 'moduloABM.php'" /> 
</div>
<p></p>
<div align="center" class="Estilo4">
<?php if ($estafiliado == 1) { echo "Titular Activo"; } else { echo "Titular Inactivo";}?>
</div>
<table width="100%" height="100" border="0">
  <tr>
	<td width="165" align="left" valign="middle">
		<?php if ($rowTitular['foto'] != NULL) { 
				echo "<img src='mostrarFoto.php?nroAfi=".$nroafiliado."&estAfi=".$estafiliado."' alt='Foto' width='115' height='115'>"; 
			  } else {
			  	echo "<img src='../img/sinFoto.jpg' alt='Foto' width='115' height='115'>";
			  }?>
	</td>
    <td align="left" valign="middle"><div align="left"><span class="Estilo4"><strong>Numero Afiliado</strong></span><strong>  
    <input name="nroafiliado" type="text" id="nroafiliado" value="<?php echo $rowTitular['nroafiliado'] ?>" size="9" readonly="readonly" style="background-color:#CCCCCC" /></strong></div></td>
  </tr>
</table>
<table width="100%" border="0">
  <tr>
    <td colspan="4"><div align="center">
      <p align="left" class="Estilo4">Datos Identificatorios</p>
      </div></td>
  </tr>
  <tr>
    <td width="165">Apellido y Nombre:</td>
    <td colspan="3"><input name="apellidoynombre" type="text" id="apellidoynombre" value="<?php echo $rowTitular['apellidoynombre'] ?>" size="100" maxlength="100" />	</td>
  </tr>
  <tr>
    <td>Documento:</td>
    <td width="316"><select name="selectTipDoc" id="selectTipDoc">
                   <option title="Seleccione un valor" value="">Seleccione un valor</option>
                   <?php 
			     		$sqlTipDoc="SELECT * FROM tipodocumento";
						$resTipDoc=mysql_query($sqlTipDoc,$db);
						while($rowTipDoc=mysql_fetch_array($resTipDoc)) {
							if ($rowTitular['tipodocumento'] == $rowTipDoc['codtipdoc'])
								echo "<option title ='$rowTipDoc[descrip]' value='$rowTipDoc[codtipdoc]' selected='selected'>".$rowTipDoc['descrip']."</option>";
							else
								echo "<option title ='$rowTipDoc[descrip]' value='$rowTipDoc[codtipdoc]'>".$rowTipDoc['descrip']."</option>";
						}
			        ?>
            		</select>
					<input name="nrodocumento" type="text" id="nrodocumento" value="<?php echo $rowTitular['nrodocumento'] ?>" size="12" maxlength="10" />	</td>
    <td width="173">Fecha de Nacimiento:</td>
    <td><input name="fechanacimiento" type="text" id="fechanacimiento" value="<?php echo invertirFecha($rowTitular['fechanacimiento']) ?>" size="12" /> Edad:
					<input name="edad" type="text" id="edad" value="" size="2" readonly="readonly" style="background-color:#CCCCCC"/></td>
  </tr>
  <tr>
    <td>Nacionalidad:</td>
    <td><select name="selectNacion" id="selectNacion">
                   <option title="Seleccione un valor" value="">Seleccione un valor</option>
                   <?php 
			     		$sqlNacion="SELECT * FROM nacionalidad ORDER BY descrip";
						$resNacion=mysql_query($sqlNacion,$db);
						while($rowNacion=mysql_fetch_array($resNacion)) { 	
							if ($rowTitular['nacionalidad'] == $rowNacion['codnacion'])
								echo "<option title ='$rowNacion[descrip]' value='$rowNacion[codnacion]' selected='selected'>".$rowNacion['descrip']."</option>";
							else
								echo "<option title ='$rowNacion[descrip]' value='$rowNacion[codnacion]'>".$rowNacion['descrip']."</option>";
						}
			        ?>
    	</select>
	</td>
    <td>Sexo:</td>
    <td><select name="selectSexo" id="selectSexo">
		<option title="Seleccione un valor" value="">Seleccione un valor</option>
			<?php 
			if($rowTitular['sexo'] == "M")
				echo "<option title='Masculino' value='M' selected='selected'>Masculino</option>";
			else
				echo "<option title='Masculino' value='M'>Masculino</option>";
			if($rowTitular['sexo'] == "F")
				echo "<option title='Femenino' value='F' selected='selected'>Femenino</option>";
			else
				echo "<option title='Femenino' value='F'>Femenino</option>";
			?>
   		</select>
	</td>
  </tr>
  <tr>
    <td>Estado Civil: </td>
    <td colspan="3"><select name="selectEstCiv" id="selectEstCiv">
                   <option title="Seleccione un valor" value="">Seleccione un valor</option>
                   <?php 
			     		$sqlEstCiv="SELECT * FROM estadocivil";
						$resEstCiv=mysql_query($sqlEstCiv,$db);
						while($rowEstCiv=mysql_fetch_array($resEstCiv)) { 	
							if ($rowTitular['estadocivil'] == $rowEstCiv['codestciv'])
								echo "<option title ='$rowEstCiv[descrip]' value='$rowEstCiv[codestciv]' selected='selected'>".$rowEstCiv['descrip']."</option>";
							else
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
    <td><input name="domicilio" type="text" id="domicilio" value="<?php echo $rowTitular['domicilio'] ?>" size="50" maxlength="50" />	</td>
    <td>C.P.</td>
    <td><input name="indpostal" type="text" id="indpostal" value="<?php echo $rowTitular['indpostal'] ?>" size="1" readonly="readonly" style="background-color:#CCCCCC" />
		<input name="numpostal" type="text" id="numpostal" value="<?php echo $rowTitular['numpostal'] ?>" size="4" maxlength="4" />
		<input name="alfapostal" type="text" id="alfapostal" value="<?php echo $rowTitular['alfapostal'] ?>" size="3" />	</td>
  </tr>
  <tr>
    <td>Localidad:</td>
    <td><select name="selectLocalidad" id="selectLocalidad">
        <option title="Seleccione un valor" value="">Seleccione un valor</option>
		<?php 
			$titLoca=$rowTitular['codlocali'];
			$sqlLoca="SELECT * FROM localidades WHERE codlocali = '$titLoca'";
			$resLoca=mysql_query($sqlLoca,$db);
			$rowLoca=mysql_fetch_array($resLoca);
			echo "<option title ='$rowLoca[nomlocali]' value='$rowLoca[codlocali]' selected='selected'>".$rowLoca['nomlocali']."</option>";
        ?>
        </select>
	</td>
    <td>Provincia:</td>
    <td><input name="nomprovin" type="text" id="nomprovin" value="<?php echo $rowProvi['descrip'] ?>" size="50" readonly="readonly" style="background-color:#CCCCCC" />
		<input name="codprovin" type="text" id="codprovin" value="<?php echo $rowTitular['codprovin'] ?>" size="2" readonly="readonly" style="visibility:hidden" />
	</td>
  </tr>
  <tr>
    <td>Telefono:</td>
    <td><input name="ddn" type="text" id="ddn" value="<?php echo $rowTitular['ddn'] ?>" size="5" maxlength="5" />
		<input name="telefono" type="text" id="telefono" value="<?php echo $rowTitular['telefono'] ?>" size="12" maxlength="10" />	</td>
    <td>Email:</td>
    <td><input name="email" type="text" id="email" value="<?php echo $rowTitular['email'] ?>" size="60" maxlength="60" />	</td>
  </tr>
  <tr>
    <td colspan="4"><div align="center" class="Estilo4">
      <div align="left">Datos Afiliatorios</div>
    </div></td>
  </tr>
  <tr>
    <td>Fecha Ingreso O.S.: </td>
    <td>
	<input name="fechaobrasocial" type="text" id="fechaobrasocial" value="<?php echo invertirFecha($rowTitular['fechaobrasocial']) ?>" size="12" />	</td>
    <td>Tipo Afiliado: </td>
    <td>
	<select name="selectTipoAfil" id="selectTipoAfil">
		<option title="Seleccione un valor" value="">Seleccione un valor</option>
		<?php 
		if($rowTitular['tipoafiliado'] == "R")
			echo "<option title='Regular' value='R' selected='selected'>Regular</option>";
		else
			echo "<option title='Regular' value='R'>Regular</option>";
		if($rowTitular['tipoafiliado'] == "S")
			echo "<option title='Solo OSPIM' value='S' selected='selected'>Solo OSPIM</option>";
		else
			echo "<option title='Solo OSPIM' value='S'>Solo OSPIM</option>";
		if($rowTitular['tipoafiliado'] == "O")
			echo "<option title='Por Opcion' value='O' selected='selected'>Por Opcion</option>";
		else
			echo "<option title='Por Opcion' value='O'>Por Opcion</option>";
	 	?>	 
   		</select>
	<input name="solicitudopcion" type="text" id="solicitudopcion" value="<?php echo $rowTitular['solicitudopcion'] ?>" size="8" maxlength="8" readonly="readonly" style="background-color:#CCCCCC" />
	</td>
  </tr>
  <tr>
    <td>Tipo Titularidad: </td>
    <td><select name="selectSitTitular" id="selectSitTitular">
			<option title="Seleccione un valor" value="">Seleccione un valor</option>
			<?php 
			$sqlSitTit="SELECT * FROM tipotitular WHERE codtiptit NOT IN(2,3,6,9,11)";
			$resSitTit=mysql_query($sqlSitTit,$db);
			while($rowSitTit=mysql_fetch_array($resSitTit)) { 	
				if ($rowTitular['situaciontitularidad'] == $rowSitTit['codtiptit'])
					echo "<option title ='$rowSitTit[descrip]' value='$rowSitTit[codtiptit]' selected='selected'>".$rowSitTit['descrip']."</option>";
				else
					echo "<option title ='$rowSitTit[descrip]' value='$rowSitTit[codtiptit]'>".$rowSitTit['descrip']."</option>";
			}
	        ?>
		</select>
	</td>
    <td>Discapacidad:</td>
    <td><input name="discapacidad" type="text" id="discapacidad" value="<?php echo $discapacidad?>" size="2" readonly="readonly" style="background-color:#CCCCCC" /> 
    Certif:
		<input name="certificadodiscapacidad" type="text" id="certificadodiscapacidad" value="<?php echo $certificadodiscapacidad?>" size="2" readonly="readonly" style="background-color:#CCCCCC" /> Emision:
		<input name="emisiondiscapacidad" type="text" id="emisiondiscapacidad" value="<?php echo $emisiondiscapacidad?>" size="10" readonly="readonly" style="background-color:#CCCCCC" /> 
		Vto:
		<input name="vencimientodiscapacidad" type="text" id="vencimientodiscapacidad" value="<?php echo $vencimientodiscapacidad?>" size="10" readonly="readonly" style="background-color:#CCCCCC" /></td>
  </tr>
  <tr>
    <td colspan="4"><div align="center" class="Estilo4">
      <div align="left">Datos Laborales </div>
    </div></td>
  </tr>
  <tr>
    <td>C.U.I.L.:</td>
    <td><input name="cuil" type="text" id="cuil" value="<?php echo $rowTitular['cuil'] ?>" size="13" maxlength="11" />	</td>
    <td>Empresa:</td>
    <td><input name="cuitempresa" type="text" id="cuitempresa" value="<?php echo $rowTitular['cuitempresa'] ?>" size="13" maxlength="11" />
    <input name="nombreempresa" type="text" id="nombreempresa" value="<?php echo $rowEmpresa['nombre'] ?>" size="50" readonly="readonly" style="background-color:#CCCCCC" />    </td>
  </tr>
  <tr>
    <td>Fecha  Ingreso Empresa:</td>
    <td><input name="fechaempresa" type="text" id="fechaempresa" value="<?php echo invertirFecha($rowTitular['fechaempresa']) ?>" size="12" />	</td>
    <td>Jurisdiccion del Titular:</td>
    <td><select name="selectDelega" id="selectDelega">
        <option title="Seleccione un valor" value="">Seleccione un valor</option>
		<?php 
		$cuiJurisdi=$rowEmpresa['cuit'];
		$sqlJurisdi="SELECT cuit, codidelega FROM jurisdiccion WHERE cuit = '$cuiJurisdi'";
		$resJurisdi=mysql_query($sqlJurisdi,$db);
		while($rowJurisdi=mysql_fetch_array($resJurisdi)) {
			$coddelega = $rowJurisdi['codidelega'];
			$sqlLeeDelega = "SELECT codidelega, nombre FROM delegaciones WHERE codidelega = '$coddelega'";
			$resLeeDelega = mysql_query($sqlLeeDelega,$db);
			$rowLeeDelega = mysql_fetch_array($resLeeDelega);
			if($rowTitular['codidelega'] == $rowJurisdi['codidelega']) {
				echo "<option title ='$rowLeeDelega[nombre]' value='$rowJurisdi[codidelega]' selected='selected'>".$rowLeeDelega['nombre']."</option>";
			}
			else {
				echo "<option title ='$rowLeeDelega[nombre]' value='$rowJurisdi[codidelega]'>".$rowLeeDelega['nombre']."</option>";
			}
		}
		?>
        </select>
	</td>
  </tr>
  <tr>
    <td>Categoria:</td>
    <td colspan="3"><input name="categoria" type="text" id="categoria" value="<?php echo $rowTitular['categoria'] ?>" size="100" maxlength="100" />	</td>
  </tr>
  <tr>
    <td colspan="4"><div align="center" class="Estilo4">
      <div align="left">Datos Credencial </div>
    </div></td>
  </tr>
  <tr>
    <td>Emision:</td>
    <td><select name="selectEmiteCarnet" id="selectEmiteCarnet">
		<option title="Seleccione un valor" value="">Seleccione un valor</option>
		<?php 
		if($rowTitular['emitecarnet'] == 1)
			echo "<option title='Emite Carnet' value='1' selected='selected'>Emite Carnet</option>";
		else
			echo "<option title='Emite Carnet' value='1'>Emite Carnet</option>";
		if($rowTitular['emitecarnet'] == 0)
			echo "<option title='No Emite Carnet' value='0' selected='selected'>No Emite Carnet</option>";
		else
			echo "<option title='No Emite Carnet' value='0'>No Emite Carnet</option>";
	 	?>	
		</select>
	</td>
    <td>Cantidad Emitida:</td>
    <td><input name="cantidadcarnet" type="text" id="cantidadcarnet" value="<?php echo $rowTitular['cantidadcarnet'] ?>" size="4" readonly="readonly" style="background-color:#CCCCCC" />	</td>
  </tr>
  <tr>
    <td>Fecha Ultima Emision:</td>
    <td><input name="fechacarnet" type="text" id="fechacarnet" value="<?php echo invertirFecha($rowTitular['fechacarnet']) ?>" size="10" readonly="readonly" style="background-color:#CCCCCC" />	</td>
    <td>Tipo Credencial:</td>
    <td><input name="tipocarnet" type="text" id="tipocarnet" value="<?php echo $rowTitular['tipocarnet'] ?>" size="1" readonly="readonly" style="background-color:#CCCCCC" />	</td>
  </tr>
  <tr>
    <td>Vencimiento:</td>
    <td colspan="3"><input name="vencimientocarnet" type="text" id="vencimientocarnet" value="<?php echo invertirFecha($rowTitular['vencimientocarnet']) ?>" size="10" readonly="readonly" style="background-color:#CCCCCC" />	</td>
  </tr>
  <tr>
    <td colspan="4"><div align="center" class="Estilo4">
      <div align="left"><?php if ($estafiliado == 0)  echo "Datos Inactividad"; ?> </div>
    </div></td>
  </tr>
  <tr>
    <td align="left" valign="top"><?php if ($estafiliado == 0)  echo "Fecha de Baja:"; ?> </td>
    <td align="left" valign="top"><?php if ($estafiliado == 0) {
				echo "<input name='fechabaja' type='text' id='fechabaja' value='".invertirFecha($rowTitular['fechabaja'])."' size='10' readonly='true' style='background-color:#CCCCCC' />";
		  	  }?> </td>
	<td align="left" valign="top"><?php if ($estafiliado == 0)  echo "Motivo de Baja:"; ?> </td>
    <td align="left" valign="top"><?php if ($estafiliado == 0) {
          		echo "<textarea name='motivobaja' cols='50' rows='5' id='motivobaja' readonly='readonly' style='background-color:#CCCCCC'>".$rowTitular['motivobaja']."</textarea>";
		  	  }?> </td>
  </tr>
</table> 
<table id="familiares" class="tablesorter" style="font-size:14px; text-align:center">
	<thead>
		<tr>
			<th colspan="7">Grupo Familiar</th>
		</tr>
		<tr>
			<th>Parentesco</th>
			<th>Apellido y Nombre</th>
			<th>Nacimiento</th>
			<th>Documento</th>
			<th>C.U.I.L.</th>
			<th>Estado</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?php
$canfamilia = 0;
$sqlFamilia = "SELECT nroorden, tipoparentesco, apellidoynombre, fechanacimiento, nrodocumento, cuil FROM familiares WHERE nroafiliado = '$nroafiliado' ORDER BY nroafiliado, tipoparentesco, fechanacimiento";
$resFamilia = mysql_query($sqlFamilia,$db);
if(mysql_num_rows($resFamilia)!=0) {
	while($rowFamilia = mysql_fetch_array($resFamilia)) {
?>
		<tr>
<?php
		$parentesco = $rowFamilia['tipoparentesco'];
		$sqlParentesco = "SELECT * FROM parentesco WHERE codparent = '$parentesco'";
		$resParentesco = mysql_query($sqlParentesco,$db);
		$rowParentesco = mysql_fetch_array($resParentesco);
?>
			<td><?php echo $rowParentesco['descrip'] ?></td>
			<td><?php echo $rowFamilia['apellidoynombre'] ?></td>
			<td><?php echo invertirFecha($rowFamilia['fechanacimiento']) ?></td>
			<td><?php echo $rowFamilia['nrodocumento'] ?></td>
			<td><?php echo $rowFamilia['cuil'] ?></td>
			<td>ACTIVO</td>
			<td><input class="nover" type="button" name="fichafamactivo" value="Ficha" onClick="location.href = 'fichaFamiliar.php?nroAfi=<?php echo $nroafiliado?>&estAfi=<?php echo $estafiliado?>&estFam=1&nroOrd=<?php echo $rowFamilia['nroorden']?>'"/></td>
		</tr>
<?php
		$canfamilia++;
	}
}
$sqlFamBaja = "SELECT nroorden, tipoparentesco, apellidoynombre, fechanacimiento, nrodocumento, cuil FROM familiaresdebaja WHERE nroafiliado = '$nroafiliado' ORDER BY nroafiliado, tipoparentesco, fechanacimiento";
$resFamBaja = mysql_query($sqlFamBaja,$db);
if(mysql_num_rows($resFamBaja)!=0) {
	while($rowFamBaja = mysql_fetch_array($resFamBaja)) {
?>
		<tr>
<?php
		$parentesco = $rowFamBaja['tipoparentesco'];
		$sqlParentesco = "SELECT * FROM parentesco WHERE codparent = '$parentesco'";
		$resParentesco = mysql_query($sqlParentesco,$db);
		$rowParentesco = mysql_fetch_array($resParentesco);
?>
			<td><?php echo $rowParentesco['descrip'] ?></td>
			<td><?php echo $rowFamBaja['apellidoynombre'] ?></td>
			<td><?php echo invertirFecha($rowFamBaja['fechanacimiento']) ?></td>
			<td><?php echo $rowFamBaja['nrodocumento'] ?></td>
			<td><?php echo $rowFamBaja['cuil'] ?></td>
			<td>INACTIVO</td>
			<td><input class="nover" type="button" name="fichafamdebaja" value="Ficha" onClick="location.href = 'fichaFamiliar.php?nroAfi=<?php echo $nroafiliado?>&estAfi=<?php echo $estafiliado?>&estFam=0&nroOrd=<?php echo $rowFamBaja['nroorden']?>'"/></td>
		</tr>
<?php
		$canfamilia++;
	}
}
?>
	</tbody>
</table>
<?php
if($estafiliado == 1) { 
?>
<table width="100%" border="0">
  <tr>
    <td width="241" valign="middle"><div align="center">
        <input class="nover" type="submit" name="guardar" value="Guardar Cambios" /> 
        </div></td>
    <td width="241" valign="middle"><div align="center">
        <input class="nover" type="button" name="familia" value="Agregar Familiar" onClick="location.href = 'agregaFamiliar.php?nroAfi=<?php echo $nroafiliado?>&nueOrd=<?php echo $canfamilia?>'" /> 
        </div></td>
    <td width="241" valign="middle"><div align="center">
        <input class="nover" type="button" name="foto" value="Cargar Foto" onClick="location.href = 'agregaFoto.php?nroAfi=<?php echo $nroafiliado?>&estAfi=<?php echo $estafiliado?>&tipAfi=1&fotAfi=0'" /> 
        </div></td>
    <td width="241" valign="middle"><div align="center">
        <input class="nover" type="button" name="aportes" value="DDJJ / Aportes" onClick="javascript:consultaDdjjAportes(<?php echo $cuil ?>)" />
        </div></td>
    <td width="241" valign="middle"><div align="center">
        <input class="nover" type="button" name="bajar" value="Dar de Baja" onClick="location.href = 'bajaAfiliado.php?nroAfi=<?php echo $nroafiliado?>&estAfi=<?php echo $estafiliado?>&tipAfi=1'" /> 
        </div></td>
  </tr>
</table>
<?php
}

if($estafiliado == 0) { 
?>
<table width="100%" border="0">
  <tr>
    <td width="603" valign="middle"><div align="center">
        <input class="nover" type="button" name="reactiva" value="Reactivar" onClick="location.href = 'reactivaAfiliado.php?nroAfi=<?php echo $nroafiliado?>&estAfi=<?php echo $estafiliado?>&tipAfi=1'" /> 
      </div></td>
    <td width="602" valign="middle"><div align="center">
        <input class="nover" type="button" name="aportes" value="DDJJ / Aportes" onClick="javascript:consultaDdjjAportes(<?php echo $cuil ?>)" />
      </div></td>
  </tr>
</table>
<?php
}
?>
<div align="center">
	<input class="nover" type="button" name="imprimir" value="Imprimir" onClick="window.print();" /> 
</div>
</form>
</body>
</html>
