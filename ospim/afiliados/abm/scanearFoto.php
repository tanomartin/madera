<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/ospim/lib/";
include($libPath."controlSession.php");
$nroafiliado=$_GET['nroAfi'];
$estafiliado=$_GET['estAfi'];
$ordafiliado=$_GET['nroOrd'];
$tipafiliado=$_GET['tipAfi'];
echo $nroafiliado; echo "<br>";
echo $estafiliado; echo "<br>";
echo $ordafiliado; echo "<br>";
echo $tipafiliado; echo "<br>";

exec('wiaacmgr');
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>

<title>.: Foto :.</title>
</head>
<body bgcolor="#CCCCCC" > 
<table width="1205" border="0">
	<tr align="center" valign="top">
      <td width="1205" valign="middle"><div align="center">
<?php
if($tipafiliado == 1) {
	$etiquetafoto = $nroafiliado."T";
?>
        <input type="reset" name="volver" value="Volver" onClick="location.href = 'agregaFoto.php?nroAfi=<?php echo $nroafiliado?>&estAfi=<?php echo $estafiliado?>&tipAfi=<?php echo $tipafiliado?>'" align="center"/>
<?php
}
else {
	$etiquetafoto = $nroafiliado."F".$ordafiliado;
?>
        <input type="reset" name="volver" value="Volver" onClick="location.href = 'agregaFoto.php?nroAfi=<?php echo $nroafiliado?>&estAfi=<?php echo $estafiliado?>&tipAfi=<?php echo $tipafiliado?>&nroOrd=<?php echo $ordafiliado?>'" align="center"/>
<?php
}
?>
        </div>
	  </td>
	</tr>
</table>
<table width="1205" border="0">
  <tr>
	<td align="left" valign="middle"><div align="left"><span class="Estilo4"><strong>Etiqueta Para Importar La Foto:</strong></span>
	  <input name="etiquetafoto" type="text" id="etiquetafoto" value="<?php echo $etiquetafoto ?>" size="9" readonly="true" style="background-color:#CCCCCC" />
      </div></td>
  </tr>
</table>
</body>
</html>
