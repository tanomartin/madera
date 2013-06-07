<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/ospim/lib/";
include($libPath."controlSession.php");

$nroafiliado=$_GET['nroAfi'];
$estafiliado=$_GET['estAfi'];
$tipafiliado=$_GET['tipAfi'];

if($tipafiliado == 2) {
	$estfamilia=$_GET['estFam'];
	$ordafiliado=$_GET['nroOrd'];
}

$fotafiliado=$_GET['fotAfi'];

//echo $nroafiliado; echo "<br>";
//echo $estafiliado; echo "<br>";
//echo $tipafiliado; echo "<br>";
//echo $ordafiliado; echo "<br>";
//echo $fotafiliado; echo "<br>";

if($tipafiliado == 1 && $fotafiliado == 1)
	$archivofoto = "C:\\FotosCarnets\\".$nroafiliado."T\\".$nroafiliado."T.jpg";

if($tipafiliado == 2 && $fotafiliado == 1)
	$archivofoto = "C:\\FotosCarnets\\".$nroafiliado."F".$ordafiliado."\\".$nroafiliado."F".$ordafiliado.".jpg";

//echo $archivofoto; echo "<br>";
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
<title>.: Foto :.</title>
</head>
<body bgcolor="#CCCCCC" >
<form enctype="multipart/form-data" id="formAgregaFoto" name="formAgregaFoto" method="post" action="guardarFoto.php">
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
      <td width="1205" valign="middle"><div align="center" class="Estilo4"><?php if ($tipafiliado == 1) echo "Agregar Foto de Titular"; else echo "Agregar Foto de Familiar";?></div></td>
	</tr>
</table>
<table width="1205" border="0">
  <tr>
	<td align="left" valign="middle"><div align="left"><span class="Estilo4"><strong>Numero Afiliado</strong></span>
	  <input name="nroafiliado" type="text" id="nroafiliado" value="<?php echo $nroafiliado ?>" size="9" readonly="true" style="background-color:#CCCCCC" />
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
    <td colspan="2">&nbsp;</td>
    <td width="488" colspan="2" rowspan="3"><div align="center">
      <input type="button" name="scanear2" value="Scanear Foto" onClick="location.href = 'scanearFoto.php?nroAfi=<?php echo $nroafiliado?>&estAfi=<?php echo $estafiliado?>&estFam=<?php echo $estfamilia?>&tipAfi=<?php echo $tipafiliado?>&nroOrd=<?php echo $ordafiliado?>'" align="center"/>
    </div></td>
  </tr>
  <tr>
    <td width="170"><span class="Estilo4">Archivo Contenedor</span></td>
    <td width="533"><label>
      <input name="archivofoto" type="file" size="70" />
    </label></td>
  </tr>
  <tr>
    <td></td>
    <td><label><div align="left"><?php if($fotafiliado == 1) echo "Carpeta de la Foto Scaneada: ".$archivofoto ?></label></div></td>
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
        <input type="submit" name="guardar" value="Guardar Foto" align="center"/> 
        </div></td>
  </tr>
</table>
</form>
</body>
</html>
