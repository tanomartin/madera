<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php"); 
$cuentaResumen=$_GET['ctaResumen'];
$fechaCargada=$_GET['fecEmision'];
$fechaEmision=substr($fechaCargada, 6, 4).substr($fechaCargada, 3, 2).substr($fechaCargada, 0, 2);
$ultimoOrden=$_GET['ultOrden'];

$sqlLeeCuenta="SELECT * FROM cuentasusimra where codigocuenta = $cuentaResumen";
$resultLeeCuenta=mysql_query($sqlLeeCuenta,$db);
$rowLeeCuenta=mysql_fetch_array($resultLeeCuenta);

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
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(function($){
	$("#fechaimputacion").mask("99-99-9999");
});

function validar(formulario) {
	document.body.style.cursor = 'wait';
	var fechaImputacion = formulario.fechaimputacion.value;
	var importeImputado = new Number(formulario.importeimputado.value);
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
<p align="center">
  <input type="reset" name="volver" value="Volver" onclick="location.href = 'listarImputaciones.php?ctaResumen=<?php echo $cuentaResumen?>&amp;fecEmision=<?php echo $fechaCargada?>'" align="left"/>
</p>
<p align="center"><strong>Nueva Imputacion</strong></p>
<form id="nuevaImputacion" name="nuevaImputacion" method="POST" action="guardaNuevaImputacion.php" onSubmit="return validar(this)">
  <table width="400" border="1" align="center">
    <tr>
      <td><div align="right">Cuenta: </div></td>
      <td><input id="nombrecuenta" name="nombrecuenta" value="<?php echo $rowLeeCuenta['descripcioncuenta']?>" type="text" size="6" readonly="readonly"  style="background-color:#CCCCCC"/>
      <input id="codigocuenta" name="codigocuenta" value="<?php echo $cuentaResumen?>" type="text" size="2" readonly="readonly"  style="visibility:hidden"/></td>
    </tr>
    <tr>
      <td><div align="right">Fecha Emision: </div></td>
      <td><input id="fechaemision" name="fechaemision" value="<?php echo $fechaCargada?>" type="text" size="10" readonly="readonly"  style="background-color:#CCCCCC"/></td>
    </tr>
    <tr>
      <td><div align="right">Nro. Orden: </div></td>
      <td><input id="nroorden" name="nroorden" value="<?php echo $ultimoOrden?>" type="text" size="4" readonly="readonly"  style="background-color:#CCCCCC"/></td>
    </tr>
    <tr>
      <td><div align="right">Fecha Imputacion: </div></td>
      <td><input id="fechaimputacion" name="fechaimputacion" value="" type="text" size="10"/></td>
    </tr>
    <tr>
      <td><div align="right">Importe Imputado: </div></td>
      <td><input id="importeimputado" name="importeimputado" value="" type="text" size="10"/></td>
    </tr>
    <tr>
      <td><div align="right">Tipo Imputacion: </div></td>
      <td><select name="selecttipoimputacion" size="1" id="selecttipoimputacion">
        <option value="C" selected="selected">Credito</option>
        <option value="D">Debito</option>
      </select>
      </td>
    </tr>
  </table>
  <p align="center">
    <input type="submit" name="guardar" value="Guardar Imputacion" align="left"/>
    </p>
</p>
</form>
</body>
</html>