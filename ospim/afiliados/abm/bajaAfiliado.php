<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php");

$nroafiliado=$_GET['nroAfi'];
$estafiliado=$_GET['estAfi'];
$tipafiliado=$_GET['tipAfi'];

if($tipafiliado == 1) {
	$sqlLeeAfiliado = "SELECT apellidoynombre FROM titulares WHERE nroafiliado = $nroafiliado";
}
else {
	$ordafiliado=$_GET['nroOrd'];
	$sqlLeeAfiliado = "SELECT apellidoynombre FROM familiares WHERE nroafiliado = $nroafiliado and nroorden = $ordafiliado";
}

$resLeeAfiliado = mysql_query($sqlLeeAfiliado,$db);
$rowLeeAfiliado = mysql_fetch_array($resLeeAfiliado);

//echo $nroafiliado; echo "<br>";
//echo $estafiliado; echo "<br>";
//echo $tipafiliado; echo "<br>";
//echo $ordafiliado; echo "<br>";

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
<title>.: Baja :.</title>
</head>
<body bgcolor="#CCCCCC" >
<form enctype="multipart/form-data" id="formBajaAfiliado" name="formBajaAfiliado" method="post" action="guardarBaja.php">
<table width="1205" border="0">
	<tr align="center" valign="top">
      <td width="1205" valign="middle"><div align="center">
<?php
if($tipafiliado == 1) {
?>
        <input type="reset" name="volver" value="Volver" onClick="location.href = 'afiliado.php?nroAfi=<?php echo $nroafiliado?>&estAfi=1'" align="center"/>
<?php
}
else {
?>
        <input type="reset" name="volver" value="Volver" onClick="location.href = 'fichaFamiliar.php?nroAfi=<?php echo $nroafiliado?>&estAfi=1&estFam=1&nroOrd=<?php echo $ordafiliado?>'" align="center"/>
<?php
}
?>
        </div>
	  </td>
	</tr>
</table>
<table width="1205" border="0">
	<tr>
      <td width="1205" valign="middle"><div align="center" class="Estilo4"><?php if ($tipafiliado == 1) echo "Baja de Titular"; else echo "Baja de Familiar";?></div></td>
	</tr>
</table>
<table width="1205" border="0">
  <tr>
	<td align="left" valign="middle"><div align="left"><span class="Estilo4"><strong>Numero Afiliado</strong></span>
	  <input name="nroafiliado" type="text" id="nroafiliado" value="<?php echo $nroafiliado ?>" size="9" readonly="true" style="background-color:#CCCCCC" />
	  <input name="apellidoynombre" type="text" id="nroafiliado" value="<?php echo $rowLeeAfiliado['apellidoynombre'] ?>" size="100" readonly="true" style="background-color:#CCCCCC" />
	  <input name="tipafiliado" type="text" id="tipafiliado" value="<?php echo $tipafiliado ?>" size="1" readonly="true" style="visibility:hidden" />
	  <input name="nroorden" type="text" id="nroorden" value="<?php echo $ordafiliado ?>" size="3" readonly="true" style="visibility:hidden" />
    </div></td>
  </tr>
</table>
<table width="1205" border="0">
  <tr>
    <td colspan="4"><div align="center">
      <p align="left" class="Estilo4">&nbsp;</p>
      </div></td>
  </tr>
  <tr>
    <td width="131">Fecha de Baja:</td>
    <td width="1064"><input name="fechabaja" type="text" id="fechabaja" value="" size="10"/></td>
  </tr>
  <tr>
    <td width="131">Motivo de Baja:</td>
    <td width="1064"><textarea name="motivobaja" cols="120" rows="5" id="motivobaja"></textarea></td>
  </tr>
  <tr>
    <td></td>
    <td><label><div align="left"></label></div></td>
  </tr>
</table>
<table width="1205" border="0">
  <tr>
    <td valign="middle">&nbsp;</td>
  </tr>
  <tr>
    <td valign="middle">&nbsp;</td>
  </tr>
  <tr>
    <td valign="middle"><div align="center">
        <input type="submit" name="guardar" value="Bajar" align="center"/> 
        </div></td>
  </tr>
</table>
</form>
</body>
</html>
