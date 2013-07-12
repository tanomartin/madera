<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php"); 
$cuentaResumen=$_GET['ctaResumen'];
$fechaCargada=$_GET['fecEmision'];
$fechaEmision=substr($fechaCargada, 6, 4).substr($fechaCargada, 3, 2).substr($fechaCargada, 0, 2);
$ultimoOrden=$_GET['ultOrden'];

$sqlLeeCuenta="SELECT * FROM cuentasusimra where codigocuenta = $cuentaResumen";
$resultLeeCuenta=mysql_query($sqlLeeCuenta,$db);
$rowLeeCuenta=mysql_fetch_array($resultLeeCuenta);

$sqlLeeResumen="SELECT * FROM resumenusimra where codigocuenta = $cuentaResumen and fechaemision = $fechaEmision and nroordenimputacion = $ultimoOrden";
$resultLeeResumen=mysql_query($sqlLeeResumen,$db);
$rowLeeResumen=mysql_fetch_array($resultLeeResumen);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Banco USIMRA :.</title></head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo1 {
	font-family: Arial, Helvetica, sans-serif;
	font-style: italic;
	font-weight: bold;
}
</style>
<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(function($){
	$("#fechaimputacion").mask("99-99-9999");
});

function validar(formulario) {
	document.body.style.cursor = 'wait';
	var fechaImputacion = formulario.fechaimputacion.value;
	var importeImputado = formulario.importeimputado.value;
	if (!esFechaValida(fechaImputacion)) {
		alert("La fecha de imputacion no es valida");
		return false;
	}
	if (importeImputado <= 0 || !isNumber(importeImputado)) {
		alert("Debe ingresar un importe mayor que cero");
		document.body.style.cursor = 'default';
		return false;
	}
	return true;
}
</script>
<body bgcolor="#B2A274">
<p align="center"><strong>Modificacion de Imputacion</strong></p>
<form id="modificaImputacion" name="modificaImputacion" method="POST" action="guardaModificaImputacion.php" onSubmit="return validar(this)">
  <table width="260" border="0" align="center">
    <tr>
      <td width="130"><div align="right">Cuenta: </div></td>
      <td width="130"><input id="nombrecuenta" name="nombrecuenta" value="<?php echo $rowLeeCuenta['descripcioncuenta']?>" type="text" size="6" readonly="readonly"  style="background-color:#CCCCCC"/>
      <input id="codigocuenta" name="codigocuenta" value="<?php echo $cuentaResumen?>" type="text" size="2" readonly="readonly"  style="visibility:hidden"/></td>
    </tr>
    <tr>
      <td width="130"><div align="right">Fecha Emision: </div></td>
      <td width="130"><input id="fechaemision" name="fechaemision" value="<?php echo $fechaCargada?>" type="text" size="10" readonly="readonly" style="background-color:#CCCCCC"/></td>
    </tr>
    <tr>
      <td width="130"><div align="right">Nro. Orden: </div></td>
      <td width="130"><input id="nroorden" name="nroorden" value="<?php echo $ultimoOrden?>" type="text" size="4" readonly="readonly"  style="background-color:#CCCCCC"/></td>
    </tr>
    <tr>
      <td width="130"><div align="right">Fecha Imputacion: </div></td>
      <td width="130"><input id="fechaimputacion" name="fechaimputacion" value="<?php echo invertirFecha($rowLeeResumen['fechaimputacion'])?>" type="text" size="10"/></td>
    </tr>
    <tr>
      <td width="130"><div align="right">Importe Imputado: </div></td>
      <td width="130"><input id="importeimputado" name="importeimputado" value="<?php echo $rowLeeResumen['importeimputado']?>" type="text" size="10"/></td>
    </tr>
    <tr>
      <td width="130"><div align="right">Tipo Imputacion: </div></td>
      <td width="130">
		<?php if($rowLeeResumen['tipoimputacion']=="C") {?>
			<select name="selecttipoimputacion" size="1" id="selecttipoimputacion">
        	<option value="C" selected="selected">Credito</option>
        	<option value="D">Debito</option>
		<?php }
			  else {?>
			<select name="selecttipoimputacion" size="1" id="selecttipoimputacion">
			<option value="C">Credito</option>
        	<option value="D" selected="selected">Debito</option>
		<?php }?>
      </select>
      </td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <table width="260" border="0" align="center">
    <tr>
      <td width="130"><div align="left">
        <input type="reset" name="volver" value="Volver" onClick="location.href = 'listarImputaciones.php?ctaResumen=<?php echo $cuentaResumen?>&fecEmision=<?php echo $fechaCargada?>'" align="left"/>
      </div></td>
      <td width="130"><div align="right">
        <input type="submit" name="guardar" value="Guardar Imputacion" align="left"/>
      </div></td>
    </tr>
  </table>
</form>
</body>
</html>