<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php"); 
$cuentaRemesa=$_GET['ctaRemesa'];
$fechaCargada=$_GET['fecRemesa'];
$fechaRemesa=substr($fechaCargada, 6, 4).substr($fechaCargada, 3, 2).substr($fechaCargada, 0, 2);
$ultimaRemesa=$_GET['ultRemesa'];

$sqlLeeCuenta="SELECT * FROM cuentasusimra where codigocuenta = $cuentaRemesa";
$resultLeeCuenta=mysql_query($sqlLeeCuenta,$db);
$rowLeeCuenta=mysql_fetch_array($resultLeeCuenta);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Banco USIMRA :.</title>
</head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">

function controlaBruto() {
	var bruto = new Number(document.forms.nuevaRemesa.importebruto.value);
	if (bruto <= 0 || !isNumber(bruto)) {
		alert("Debe ingresar un importe bruto mayor que cero");
		document.getElementById('importebruto').focus();
		return false;
	}
}

function controlaComisionYCargaFaima() {
	var bruto = new Number(document.forms.nuevaRemesa.importebruto.value);
	var comision = new Number(document.forms.nuevaRemesa.importecomision.value);
	var porcentaje = new Number(0.0968);
	var faima = new Number(Math.round(((bruto-comision)*porcentaje)*100)/100);

	if (comision < 0 || !isNumber(comision) || comision >= bruto) {
		alert("El importe de la comision es incorrecto");
		document.getElementById('importebruto').focus();
		return false;
	}

	if (faima >= 0) {
		document.forms.nuevaRemesa.importefaima.value = faima;
	}
}

function controlaFaimaYCargaNeto() {
	var faima = new Number(document.forms.nuevaRemesa.importefaima.value);
	var bruto = new Number(document.forms.nuevaRemesa.importebruto.value);
	var comision = new Number(document.forms.nuevaRemesa.importecomision.value);
	if (faima < 0 || !isNumber(faima)) {
		alert("El importe para FAIMA es incorrecto");
		document.getElementById('importebruto').focus();
		return false;
	}
	var calculo = new Number(Math.round((bruto - (comision + faima))*100)/100);
	if (calculo >= 0) {
		document.forms.nuevaRemesa.importeneto.value = calculo;
	}
	else {
		document.forms.nuevaRemesa.importeneto.value = 0.00;
	}
}

function controlaNeto() {
	var neto = new Number(document.forms.nuevaRemesa.importeneto.value);
	if (neto <= 0) {
		alert("El importe neto es incorrecto");
		document.getElementById('importebruto').focus();
		return false;
	}
}

function validar(formulario) {
	document.body.style.cursor = 'wait';
	var importeBruto = new Number(formulario.importebruto.value);
	var importeComis = new Number(formulario.importecomision.value);
	var importeFaima = new Number(formulario.importefaima.value);
	if (importeBruto <= 0 || !isNumber(importeBruto)) {
		alert("Debe ingresar un importe bruto mayor que cero");
		document.body.style.cursor = 'default';
		return false;
	}
	if (importeComis < 0 || !isNumber(importeComis) || importeComis >= importeBruto) {
		alert("El importe de la comision es incorrecto");
		document.body.style.cursor = 'default';
		return false;
	}
	if (importeFaima < 0 || !isNumber(importeFaima)) {
		alert("El importe para FAIMA es incorrecto");
		document.body.style.cursor = 'default';
		return false;
	}
	return true;
}
</script>
<body bgcolor="#B2A274">
<p align="center">
  <input type="reset" name="volver" value="Volver" onclick="location.href = 'listarRemesas.php?ctaRemesa=<?php echo $cuentaRemesa?>&amp;fecRemesa=<?php echo $fechaCargada?>'" align="left"/>
</p>
<p align="center"><strong>Nueva Remesa</strong></p>
<form id="nuevaRemesa" name="nuevaRemesa" method="POST" action="guardaNuevaRemesa.php" onSubmit="return validar(this)">
  <table width="500" border="1" align="center">
    <tr>
      <td colspan="2"><div align="right">Cuenta: </div></td>
      <td width="180"><input id="nombrecuenta" name="nombrecuenta" value="<?php echo $rowLeeCuenta['descripcioncuenta']?>" type="text" size="6" readonly="readonly" style="background-color:#CCCCCC"/>
      <input id="codigocuenta" name="codigocuenta" value="<?php echo $cuentaRemesa?>" type="text" size="2" readonly="readonly"  style="visibility:hidden"/></td>
    </tr>
    <tr>
      <td width="150" rowspan="7"><div align="center"><strong>Datos de la Remesa</strong></div></td>
      <td width="170"><div align="right">Fecha:</div></td>
      <td width="180"><input id="fecharemesa" name="fecharemesa" value="<?php echo $fechaCargada?>" type="text" size="10" readonly="readonly" style="background-color:#CCCCCC"/></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Nro.:</div></td>
      <td width="180"><input id="nroremesa" name="nroremesa" value="<?php echo $ultimaRemesa?>" type="text" size="4" readonly="readonly" style="background-color:#CCCCCC"/></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Bruto:</div></td>
      <td width="180"><input id="importebruto" name="importebruto" value="" type="text" size="10" onfocusout="controlaBruto()"/></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Comision:</div></td>
      <td width="180"><input id="importecomision" name="importecomision" value="" type="text" size="10" onfocusout="controlaComisionYCargaFaima()"/></td>
    </tr>
    <tr>
      <td width="170"><div align="right">FAIMA:</div></td>
      <td width="180"><input id="importefaima" name="importefaima" value="" type="text" size="10" onfocusout="controlaFaimaYCargaNeto()"/></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Neto:</div></td>
      <td width="180"><input id="importeneto" name="importeneto" value="0.00" type="text" size="10" readonly="readonly" onfocusout="controlaNeto()"/></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Estado:</div></td>
      <td width="180"><input id="descriconciliacion" name="descriconciliacion" value="No Conciliado" type="text" size="13" readonly="readonly" style="background-color:#CCCCCC"/>
	  <input id="estadoconciliacion" name="estadoconciliacion"  value="0" type="text" size="1" readonly="readonly" style="visibility:hidden"/></td>
    </tr>
    <tr>
      <td width="150" rowspan="3"><div align="center"><strong>Datos de los Remitos</strong></div></td>
      <td width="170"><div align="right">Bruto:</div></td>
      <td width="180"><input id="importebrutoremitos" name="importebrutoremitos" value="0.00" type="text" size="10" readonly="readonly" style="background-color:#CCCCCC"/></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Comision:</div></td>
      <td width="180"><input id="importecomisionesremitos" name="importecomisionesremitos" value="0.00" type="text" size="10" readonly="readonly" style="background-color:#CCCCCC"/></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Neto:</div></td>
      <td width="180"><input id="importenetoremitos" name="importenetoremitos" value="0.00" type="text" size="10" readonly="readonly" style="background-color:#CCCCCC"/></td>
    </tr>
    <tr>
      <td width="150" rowspan="7"><div align="center"><strong>Datos de las Boletas</strong></div></td>
      <td width="170"><div align="right">Aportes:</div></td>
      <td width="180"><input id="importeboletasaporte" name="importeboletasaporte" value="0.00" type="text" size="10" readonly="readonly" style="background-color:#CCCCCC"/></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Recargos:</div></td>
      <td width="180"><input id="importeboletasrecargo" name="importeboletasrecargo" value="0.00" type="text" size="10" readonly="readonly" style="background-color:#CCCCCC"/></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Varios:</div></td>
      <td width="180"><input id="importeboletasvarios" name="importeboletasvarios" value="0.00" type="text" size="10" readonly="readonly" style="background-color:#CCCCCC"/></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Total de Aportes:</div></td>
      <td width="180"><input id="importeboletaspagos" name="importeboletaspagos" value="0.00" type="text" size="10" readonly="readonly" style="background-color:#CCCCCC"/></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Acuerdos:</div></td>
      <td width="180"><input id="importeboletascuotas" name="importeboletascuotas" value="0.00" type="text" size="10" readonly="readonly" style="background-color:#CCCCCC"/></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Total Bruto:</div></td>
      <td width="180"><input id="importeboletasbruto" name="importeboletasbruto" value="0.00" type="text" size="10" readonly="readonly" style="background-color:#CCCCCC"/></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Cantidad de Boletas:</div></td>
      <td width="180"><input id="cantidadboletas" name="cantidadboletas" value="0" type="text" size="5" readonly="readonly" style="background-color:#CCCCCC"/></td>
    </tr>
    <tr>
      <td width="150"><div align="center"><strong>Datos del Resumen</strong></div></td>
      <td width="170"><div align="right">Fecha de Acreditacion:</div></td>
      <td width="180"><input id="fechaacreditacion" name="fechaacreditacion" value="" type="text" size="10" readonly="readonly" style="background-color:#CCCCCC"/></td>
    </tr>
  </table>
  <p align="center">
    <input type="submit" name="guardar" value="Guardar Remesa" align="left"/>
  </p>
  </form>
</body>
</html>