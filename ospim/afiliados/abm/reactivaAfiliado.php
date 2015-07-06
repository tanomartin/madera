<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");

$nroafiliado=$_GET['nroAfi'];
$estafiliado=$_GET['estAfi'];
$tipafiliado=$_GET['tipAfi'];

if($tipafiliado == 1) {
	$sqlLeeAfiliado = "SELECT apellidoynombre, cuil FROM titularesdebaja WHERE nroafiliado = $nroafiliado";
}
else {
	$ordafiliado=$_GET['nroOrd'];
	$sqlLeeAfiliado = "SELECT apellidoynombre FROM familiaresdebaja WHERE nroafiliado = $nroafiliado and nroorden = $ordafiliado";
}
//echo $nroafiliado; echo "<br>";
//echo $estafiliado; echo "<br>";
//echo $tipafiliado; echo "<br>";
//echo $ordafiliado; echo "<br>";
//echo $sqlLeeAfiliado; echo "<br>";

$resLeeAfiliado = mysql_query($sqlLeeAfiliado,$db);
$rowLeeAfiliado = mysql_fetch_array($resLeeAfiliado);

$cuilafiliado = $rowLeeAfiliado['cuil'];
//echo $cuilafiliado; echo "<br>";

$reactiva = 1;
$reactivaEmpresa = 1;

if($tipafiliado == 1) {
	$mesfin = (int)date("m");
	$anofin = date("Y");
	if($mesfin > 2) {
		$mesini = $mesfin - 2;
		$anoini = $anofin;
	} else {
		$mesini = ($mesfin - 2) + 12 ;
		$anoini = $anofin - 1;
	}
//	echo $mesini; echo "<br>";
//	echo $anoini; echo "<br>";
//	echo $mesfin; echo "<br>";
//	echo $anofin; echo "<br>";

	$sqlLeeDDJJ = "SELECT cuil, cuit FROM detddjjospim WHERE cuil = '$cuilafiliado' AND ((anoddjj > '$anoini' AND anoddjj <= '$anofin') OR (anoddjj = '$anoini' and mesddjj >= '$mesini'))";
	$resLeeDDJJ = mysql_query($sqlLeeDDJJ,$db);
	$cantddjj = mysql_num_rows($resLeeDDJJ);

	$sqlLeeAportes = "SELECT cuil, cuit FROM afiptransferencias WHERE cuil = '$cuilafiliado' AND concepto = '381' AND ((anopago > '$anoini' AND anopago <= '$anofin') OR (anopago = '$anoini' and mespago >= '$mesini'))";
	$resLeeAportes = mysql_query($sqlLeeAportes,$db);
	$cantapor = mysql_num_rows($resLeeAportes);
//	echo $sqlLeeDDJJ; echo "<br>";
//	echo $sqlLeeAportes; echo "<br>";
//	echo $cantapor; echo "<br>";
//	echo $cantddjj; echo "<br>";

	if($cantddjj < 1) {
		if($cantapor < 1) {
			$reactiva = 0;
		} else {
			$rowLeeAportes = mysql_fetch_array($resLeeAportes);
			$cuit = $rowLeeAportes['cuit'];
		}
	} else {
		$rowLeeDDJJ = mysql_fetch_array($resLeeDDJJ);
		$cuit = $rowLeeDDJJ['cuit'];
	}
	
	$sqlJurisEmpresa = "SELECT codidelega FROM jurisdiccion WHERE cuit = '$cuit' order by disgdinero DESC LIMIT 1";
	$resJurisEmpresa  = mysql_query($sqlJurisEmpresa,$db);
	$canJurisEmpresa = mysql_num_rows($resJurisEmpresa);
	if ($canJurisEmpresa < 1) {
		$reactivaEmpresa = 0;
	}
	
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
<title>.: Reactivacion :.</title>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery-ui.min.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">
function validar(formulario) {
	if (formulario.tipafiliado.value == "1") {
		$.blockUI({ message: "<h1>Reactivando Titular y su Grupo Familiar. Aguarde por favor...</h1>" });
	} else {
		$.blockUI({ message: "<h1>Reactivando Familiar. Aguarde por favor...</h1>" });
	}
	return true;
}
</script>
</head>
<body bgcolor="#CCCCCC" >
<form id="formReactivaAfiliado" name="formReactivaAfiliado" method="post" onSubmit="return validar(this)" action="guardarReactivacion.php">
<table width="1205" border="0">
	<tr align="center" valign="top">
      <td width="1205" valign="middle"><div align="center">
<?php
if($tipafiliado == 1) {
?>
        <input class="nover" type="reset" name="volver" value="Volver" onClick="location.href = 'afiliado.php?nroAfi=<?php echo $nroafiliado?>&estAfi=<?php echo $estafiliado?>'" align="center"/>
<?php
}
else {
?>
        <input class="nover" type="reset" name="volver" value="Volver" onClick="location.href = 'fichaFamiliar.php?nroAfi=<?php echo $nroafiliado?>&estAfi=<?php echo $estafiliado?>&estFam=0&nroOrd=<?php echo $ordafiliado?>'" align="center"/>
<?php
}
?>
        </div>
	  </td>
	</tr>
	<tr>
      <td width="1205" valign="middle"><div align="center" class="Estilo4"><?php if ($tipafiliado == 1) echo "Reactivacion de Titular"; else echo "Reactivacion de Familiar";?></div></td>
	</tr>
  <tr>
	<td align="left" valign="middle"><div align="left"><span class="Estilo4"><strong>Numero Afiliado</strong></span>
	  <input name="nroafiliado" type="text" id="nroafiliado" value="<?php echo $nroafiliado ?>" size="9" readonly="true" style="background-color:#CCCCCC" />
	  <input name="apellidoynombre" type="text" id="nroafiliado" value="<?php echo $rowLeeAfiliado['apellidoynombre'] ?>" size="100" readonly="true" style="background-color:#CCCCCC" />
	  <input name="tipafiliado" type="text" id="tipafiliado" value="<?php echo $tipafiliado ?>" size="1" readonly="true" style="visibility:hidden" />
	  <input name="nroorden" type="text" id="nroorden" value="<?php echo $ordafiliado ?>" size="3" readonly="true" style="visibility:hidden" />
    </div></td>
  </tr>
  <tr>
    <td colspan="2"><div align="center">
      <p align="left" class="Estilo4">&nbsp;</p>
      </div></td>
  </tr>
  <tr>
    <td colspan="2"><div align="center"><h2>
		<?php if($reactiva == 0) {?>El titular NO tiene en el &uacute;ltimo trimestre ni las DDJJ ni los APORTES necesarios para su reactivaci&oacute;n.<?php }?></h2></div></td>
		<?php if($reactivaEmpresa == 0) {?>La Empresa a la cual hay que activar el empleado no Existe.<?php }?></h2></div></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td valign="middle"><div align="center">
        <?php if($reactiva && $reactivaEmpresa) {?><input class="nover" type="submit" name="guardar" value="Reactivar" align="center"/><?php }?>
        </div></td>
  </tr>
</table>
</form>
</body>
</html>
