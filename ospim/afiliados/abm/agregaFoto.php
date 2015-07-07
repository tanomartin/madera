<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");

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
<style type="text/css" media="print">
.nover {display:none}
</style>
<title>.: Foto :.</title>
</head>
<body bgcolor="#CCCCCC" >
<form enctype="multipart/form-data" id="formAgregaFoto" name="formAgregaFoto" method="post" action="guardarFoto.php">
	<div align="center">
	<?php
	if($tipafiliado == 1) {
	?>
		<input class="nover" type="reset" name="volver" value="Volver" onClick="location.href = 'afiliado.php?nroAfi=<?php echo $nroafiliado?>&estAfi=1'" align="center"/>
	<?php
	}
	else {
	?>
		<input class="nover" type="reset" name="volver" value="Volver" onClick="location.href = 'fichaFamiliar.php?nroAfi=<?php echo $nroafiliado?>&estAfi=1&estFam=1&nroOrd=<?php echo $ordafiliado?>'" align="center"/>
	<?php
	}
	?>
	</div>
	<p></p>
	<div align="center" class="Estilo4"><?php if ($tipafiliado == 1) echo "Agregar Foto de Titular"; else echo "Agregar Foto de Familiar";?></div>
	<p></p>
	<div align="left"><span class="Estilo4"><strong>Numero Afiliado</strong></span>
		<input name="nroafiliado" type="text" id="nroafiliado" value="<?php echo $nroafiliado ?>" size="9" readonly="true" style="background-color:#CCCCCC" />
		<input name="tipafiliado" type="text" id="tipafiliado" value="<?php echo $tipafiliado ?>" size="1" readonly="true" style="visibility:hidden" />
		<input name="nroorden" type="text" id="nroorden" value="<?php echo $ordafiliado ?>" size="3" readonly="true" style="visibility:hidden" />
    </div>
	<p></p>
	<table width="100%" border="0">
		<tr>
			<td colspan="2">&nbsp;</td>
			<td rowspan="3">
				<div align="center">
					<input class="nover" type="button" name="scanear2" value="Scanear Foto" disabled="disabled" onClick="location.href = 'scanearFoto.php?nroAfi=<?php echo $nroafiliado?>&estAfi=<?php echo $estafiliado?>&estFam=<?php echo $estfamilia?>&tipAfi=<?php echo $tipafiliado?>&nroOrd=<?php echo $ordafiliado?>'" align="center"/>
				</div>
			</td>
		</tr>
		<tr>
			<td width="170"><span class="Estilo4">Archivo Contenedor</span></td>
			<td width="533"><input name="archivofoto" type="file" size="70" /></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><div align="left"><?php if($fotafiliado == 1) echo "Carpeta de la Foto Scaneada: ".$archivofoto ?></div></td>
		</tr>
	</table>
	<p></p>
	<div align="center">
		<input class="nover" type="submit" name="guardar" value="Guardar Foto" align="center"/> 
	</div>
</form>
</body>
</html>
