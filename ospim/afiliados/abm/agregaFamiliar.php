<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/ospim/lib/";
include($libPath."controlSession.php");
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
<title>.: Familiar :.</title>
</head>
<body bgcolor="#CCCCCC" >
<form id="formAgregaFamiliar" name="formAgregaFamiliar" method="post" action="guardaAltaFamiliar.php">
<table width="1205" border="0">
	<tr align="center" valign="top">
      <td width="1205" valign="middle"><div align="center">
        <input type="reset" name="volver" value="Volver" onClick="location.href = 'afiliado.php?nroAfi=<?php echo $nroafiliado?>&estAfi=1'" align="center"/> 
        </div></td>
	</tr>
</table>
<table width="1205" border="0">
	<tr>
      <td width="1205" valign="middle"><div align="center" class="Estilo4">Alta de Familiar</div></td>
	</tr>
</table>
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
    <td colspan="4"><div align="center">
      <p align="left" class="Estilo4">Datos Identificatorios</p>
      </div></td>
  </tr>
  <tr>
    <td width="238">Apellido y Nombre:</td>
    <td colspan="3"><input name="apellidoynombre" type="text" id="apellidoynombre" value="" size="100" />	</td>
  </tr>
  <tr>
    <td>Documento:</td>
    <td width="316"><input name="tipodocumento" type="text" id="tipodocumento" value="" size="2" />
					<input name="nrodocumento" type="text" id="nrodocumento" value="" size="10" />	</td>
    <td width="173">Fecha de Nacimiento:</td>
    <td width="460"><input name="fechanacimiento" type="text" id="fechanacimiento" value="" size="10" />	</td>
  </tr>
  <tr>
    <td>Nacionalidad:</td>
    <td><input name="nacionalidad" type="text" id="nacionalidad" value="" size="3" />	</td>
    <td>Sexo:</td>
    <td><input name="sexo" type="text" id="sexo" value="" size="1" />	</td>
  </tr>
  <tr>
    <td>Telefono:</td>
    <td><input name="ddn" type="text" id="ddn" value="" size="5" />
		<input name="telefono" type="text" id="telefono" value="" size="10" />	</td>
    <td>Email:</td>
    <td><input name="email" type="text" id="email" value="" size="60" />	</td>
  </tr>
  <tr>
    <td>C.U.I.L.:</td>
    <td colspan="3"><input name="cuil" type="text" id="cuil" value="" size="11" />	</td>
  </tr>
  <tr>
    <td colspan="4"><div align="center" class="Estilo4">
      <div align="left">Datos Afiliatorios</div>
    </div></td>
  </tr>
  <tr>
    <td>Parentesco:</td>
    <td><input name="tipoparentesco" type="text" id="tipoparentesco" value="" size="2" />	</td>
    <td>Fecha Ingreso O.S.:</td>
    <td><input name="fechaobrasocial" type="text" id="fechaobrasocial" value="" size="10" />	</td>
  </tr>
  <tr>
    <td>Discapacidad:</td>
    <td><input name="discapacidad" type="text" id="discapacidad" value="" size="2" />
		<input name="certificadodiscapacidad" type="text" id="certificadodiscapacidad" value="" size="1" />	</td>
    <td>Estudia:</td>
    <td><input name="estudia" type="text" id="estudia" value="" size="2" />
		<input name="certificadoestudio" type="text" id="certificadoestudio" value="" size="1" />	</td>
  </tr>
  <tr>
    <td colspan="4"><div align="center" class="Estilo4">
      <div align="left">Datos Credencial </div>
    </div></td>
  </tr>
  <tr>
    <td>Emision:</td>
    <td colspan="3"><input name="emitecarnet" type="text" id="emitecarnet" value="" size="1" />	</td>
    </tr>
</table>
<table width="1205" border="0">
  <tr>
    <td valign="middle"><div align="center">
        <input type="submit" name="guardar" value="Guardar" align="center"/> 
        </div></td>
    </tr>
</table>

<table width="1205" border="0">
  <tr>
    <td width="1205" valign="middle"><div align="center">
        <input type="button" name="imprimir" value="Imprimir" onClick="window.print();" align="center"/> 
        </div></td>
  </tr>
</table>
</form>
</body>
</html>
