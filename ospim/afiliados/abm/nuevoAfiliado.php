<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/ospim/lib/";
include($libPath."controlSession.php");
include($libPath."fechas.php");

$sqlBuscaNro = "SELECT (LAST_INSERT_ID(nroafiliado)+1) AS proximoNroAfiliado FROM titulares";
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
</head>
<script src="../../lib/jquery.js" type="text/javascript"></script>
<script src="../../lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="../../lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">

jQuery(function($){
	$("#cuil").mask("99999999999");
	$("#cuit").mask("99999999999");
	
});

function validar(formulario) {
	formulario.Submit.disabled = true;
	if (!verificaCuil(formulario.cuil.value)){
		return false;
	}
	if (formulario.apellidoynombre.value == "") {
		alert("El campo Apellido y Nombre es Obligatrio");
		return false;
	}
	if (formulario.domicilio.value == "") {
		alert("El campo domicilio es obligatrio");
		return false;
	}
	if (formulario.numpostal.value == "") {
		alert("El campo Codigo Postal es obligatrio");
		return false;
	} else {
		if (!esEnteroPositivo(formulario.numpostal.value)){
		 	alert("El campo Codigo Postal tiene que ser numerico");
			return false;
		}
	}
	if (formulario.ddn.value != "") {
		if (!esEnteroPositivo(formulario.ddn.value)) {
			alert("El codigo de area debe ser un numero");
			return false;
		}
	} else {
		formulario.ddn.value = "0";
	}
	if (formulario.telefono.value != "") {
		if (!esEnteroPositivo(formulario.telefono.value)) {
			alert("El telefono debe ser un numero");
			return false;
		}
	} else {
		formulario.telefono.value = "0";
	}
	return true;
}
</script>


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
    <input name="nroafiliado" type="text" id="nroafiliado" value="<?php echo $rowBuscaNro['proximoNroAfiliado'] ?>" size="9" readonly="true" style="background-color:#CCCCCC" /></strong></div></td>
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
    <td colspan="3"><input name="apellidoynombre" type="text" id="apellidoynombre" value="" size="100" /></td>
  </tr>
  <tr>
    <td>Documento:</td>
    <td width="316"><input name="tipodocumento" type="text" id="tipodocumento" value="" size="2" />
					<input name="nrodocumento" type="text" id="nrodocumento" value="" size="10" /></td>
    <td width="173">Fecha de Nacimiento:</td>
    <td width="460"><input name="fechanacimiento" type="text" id="fechanacimiento" value="" size="10" /></td>
  </tr>
  <tr>
    <td>Nacionalidad:</td>
    <td><input name="nacionalidad" type="text" id="nacionalidad" value="" size="3" /></td>
    <td>Sexo:</td>
    <td><input name="sexo" type="text" id="sexo" value="" size="1" /></td>
  </tr>
  <tr>
    <td>Estado Civil: </td>
    <td colspan="3"><input name="estadocivil" type="text" id="estadocivil" value="" size="2" /></td>
  </tr>
  <tr>
    <td colspan="4"><div align="center" class="Estilo4">
      <div align="left">Datos Domiciliarios</div>
    </div></td>
  </tr>
  <tr>
    <td>Domicilio:</td>
    <td><input name="domicilio" type="text" id="domicilio" value="" size="50" /></td>
    <td>C.P.</td>
    <td><input name="indpostal" type="text" id="indpostal" value="" size="1" />
		<input name="numpostal" type="text" id="numpostal" value="" size="4" />
		<input name="alfapostal" type="text" id="alfapostal" value="" size="3" /></td>
  </tr>
  <tr>
    <td>Localidad:</td>
    <td><input name="codlocali" type="text" id="codlocali" value="" size="6" /></td>
    <td>Provincia:</td>
    <td><input name="codprovin" type="text" id="codprovin" value="" size="2" /></td>
  </tr>
  <tr>
    <td>Telefono:</td>
    <td><input name="ddn" type="text" id="ddn" value="" size="5" />
		<input name="telefono" type="text" id="telefono" value="" size="10" /></td>
    <td>Email:</td>
    <td><input name="email" type="text" id="email" value="" size="60" /></td>
  </tr>
  <tr>
    <td colspan="4"><div align="center" class="Estilo4">
      <div align="left">Datos Afiliatorios</div>
    </div></td>
  </tr>
  <tr>
    <td>Fecha Ingreso O.S.: </td>
    <td>
	<input name="fechaobrasocial" type="text" id="fechaobrasocial" value="" size="10" /></td>
    <td>Tipo Afiliado: </td>
    <td><input name="tipoafiliado" type="text" id="tipoafiliado" value="" size="1" />
		<input name="solicitudopcion" type="text" id="solicitudopcion" value="" size="8" /></td>
  </tr>
  <tr>
    <td>Tipo Titularidad: </td>
    <td colspan="3"><input name="situaciontitularidad" type="text" id="situaciontitularidad" value="" size="2" /></td>
    </tr>
  <tr>
    <td colspan="4"><div align="center" class="Estilo4">
      <div align="left">Datos Laborales</div>
    </div></td>
  </tr>
  <tr>
    <td>C.U.I.L.:</td>
    <td><input name="cuil" type="text" id="cuil" value="" size="11" /></td>
    <td>Empresa:</td>
    <td><input name="cuitempresa" type="text" id="cuitempresa" value="" size="11" />
    <input name="nombreempresa" type="text" id="nombreempresa" value="" size="50" readonly="true" style="background-color:#CCCCCC" /></td>
  </tr>
  <tr>
    <td>Fecha  Ingreso Empresa:</td>
    <td><input name="fechaempresa" type="text" id="fechaempresa" value="" size="10" /></td>
    <td>Jurisdiccion del Titular:</td>
    <td><input name="codidelega" type="text" id="codidelega" value="" size="4" /></td>
  </tr>
  <tr>
    <td>Categoria:</td>
    <td colspan="3"><input name="categoria" type="text" id="categoria" value="" size="100" /></td>
  </tr>
  <tr>
    <td colspan="4"><div align="center" class="Estilo4">
      <div align="left">Datos Credencial</div>
    </div></td>
  </tr>
  <tr>
    <td>Emision:</td>
    <td colspan="3"><input name="emitecarnet" type="text" id="emitecarnet" value="" size="1" /></td>
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
