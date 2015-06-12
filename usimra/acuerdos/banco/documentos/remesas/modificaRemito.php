<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php"); 
$cuentaRemesa=$_GET['ctaRemesa'];
$fechaCargada=$_GET['fecRemesa'];
$fechaRemesa=substr($fechaCargada, 6, 4).substr($fechaCargada, 3, 2).substr($fechaCargada, 0, 2);
$ultimaRemesa=$_GET['ultRemesa'];
$ultimoRemito=$_GET['ultRemito'];

$sqlLeeCuenta="SELECT * FROM cuentasusimra where codigocuenta = $cuentaRemesa";
$resultLeeCuenta=mysql_query($sqlLeeCuenta,$db);
$rowLeeCuenta=mysql_fetch_array($resultLeeCuenta);

$sqlLeeRemito="SELECT * FROM remitosremesasusimra WHERE codigocuenta = $cuentaRemesa and sistemaremesa = 'M' and fecharemesa = $fechaRemesa and nroremesa = $ultimaRemesa and nroremito = $ultimoRemito";
$resultLeeRemito=mysql_query($sqlLeeRemito,$db);
$rowLeeRemito=mysql_fetch_array($resultLeeRemito);

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
jQuery(function($){
	$("#fecharemito").mask("99-99-9999");
});

function controlaFecha() {
	var fecha = document.forms.modificaRemito.fecharemito.value;
	if (!esFechaValida(fecha)) {
		alert("La fecha del remito no es valida");
		document.getElementById("fecharemito").focus();
		return(false);
	}
}

function controlaBruto() {
	var bruto = new Number(document.forms.modificaRemito.importebruto.value);
	if (bruto <= 0 || !isNumber(bruto)) {
		alert("Debe ingresar un importe bruto mayor que cero");
		document.getElementById('fecharemito').focus();
		return false;
	}
}

function controlaComisionYCargaNeto() {
	var bruto = new Number(document.forms.modificaRemito.importebruto.value);
	var comision = new Number(document.forms.modificaRemito.importecomision.value);
	if (comision < 0 || !isNumber(comision) || comision >= bruto) {
		alert("El importe de la comision es incorrecto");
		document.getElementById('fecharemito').focus();
		return false;
	}
	var calculo = new Number(bruto - comision);
	if (calculo >= 0) {
		document.forms.modificaRemito.importeneto.value = calculo;
	}
	else {
		document.forms.modificaRemito.importeneto.value = 0.00;
	}

}

function controlaNeto() {
	var neto = new Number(document.forms.modificaRemito.importeneto.value);
	if (neto <= 0) {
		alert("El importe neto es incorrecto");
		document.getElementById('fecharemito').focus();
		return false;
	}
}

function controlaBoletas() {
	var boletas = new Number(document.forms.modificaRemito.boletasremito.value);
	if (boletas <= 0 || !isNumber(boletas)) {
		alert("La cantidad de boletas es incorrecta");
		document.getElementById('fecharemito').focus();
		return false;
	}
}

function validar(formulario) {
	document.body.style.cursor = 'wait';
	var fechaRemito = formulario.fecharemito.value;
	var importeBruto = new Number(formulario.importebruto.value);
	var importeComis = new Number(formulario.importecomision.value);
	var cantidadBoletas = new Number(formulario.boletasremito.value);

	if (!esFechaValida(fechaRemito)) {
		alert("La fecha del remito no es valida");
		document.body.style.cursor = 'default';
		return false;
	}
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
	if (cantidadBoletas <= 0 || !isNumber(cantidadBoletas)) {
		alert("La cantidad de boletas es incorrecta");
		document.body.style.cursor = 'default';
		return false;
	}

	return true;
}
</script>
<body bgcolor="#B2A274">
<p align="center">
  <input type="reset" name="volver" value="Volver" onclick="location.href = 'listarRemitos.php?ctaRemesa=<?php echo $cuentaRemesa?>&amp;fecRemesa=<?php echo $fechaCargada?>&amp;ultRemesa=<?php echo $ultimaRemesa?>&amp;sisRemesa=M'" align="left"/>
</p>
<p align="center"><strong>Modificacion de Remito</strong></p>
<form id="modificaRemito" name="modificaRemito" method="POST" action="guardaModificaRemito.php" onSubmit="return validar(this)">
  <table width="500" border="1" align="center">
    <tr>
      <td colspan="2"><div align="right">Cuenta: </div></td>
      <td width="180"><input id="nombrecuenta" name="nombrecuenta" value="<?php echo $rowLeeCuenta['descripcioncuenta']?>" type="text" size="6" readonly="readonly" style="background-color:#CCCCCC"/>
      <input id="codigocuenta" name="codigocuenta" value="<?php echo $cuentaRemesa?>" type="text" size="2" readonly="readonly"  style="visibility:hidden"/></td>
    </tr>
    <tr>
      <td width="150" rowspan="2"><div align="center"><strong>Datos de la Remesa</strong></div></td>
      <td width="170"><div align="right">Fecha:</div></td>
      <td width="180"><input id="fecharemesa" name="fecharemesa" value="<?php echo $fechaCargada?>" type="text" size="10" readonly="readonly" style="background-color:#CCCCCC"/></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Nro.:</div></td>
      <td width="180"><input id="nroremesa" name="nroremesa" value="<?php echo $ultimaRemesa?>" type="text" size="4" readonly="readonly" style="background-color:#CCCCCC"/></td>
    </tr>
    <tr>
      <td width="150" rowspan="8"><div align="center"><strong>Datos del Remito</strong></div></td>
      <td width="170"><div align="right">Nro:</div></td>
      <td width="180"><input id="nroremito" name="nroremito" value="<?php echo $ultimoRemito?>" type="text" size="4"  readonly="readonly" style="background-color:#CCCCCC"/></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Fecha:</div></td>
      <td width="180"><input id="fecharemito" name="fecharemito" value="<?php echo invertirFecha($rowLeeRemito['fecharemito'])?>" type="text" size="10" onfocusout="controlaFecha()"/></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Bruto:</div></td>
      <td width="180"><input id="importebruto" name="importebruto" value="<?php echo $rowLeeRemito['importebruto']?>" type="text" size="10" onfocusout="controlaBruto()"/></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Comision:</div></td>
      <td width="180"><input id="importecomision" name="importecomision" value="<?php echo $rowLeeRemito['importecomision']?>" type="text" size="10" onfocusout="controlaComisionYCargaNeto()"/></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Neto:</div></td>
      <td width="180"><input id="importeneto" name="importeneto" value="<?php echo $rowLeeRemito['importeneto']?>" type="text" size="10" readonly="readonly" onfocusout="controlaNeto()"/></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Cantidad de Boletas: </div></td>
      <td width="180"><input id="boletasremito" name="boletasremito" value="<?php echo $rowLeeRemito['boletasremito']?>" type="text" size="5" onfocusout="controlaBoletas()"/></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Sucursal Banco: </div></td>
      <td width="180"><input id="sucursalbanco" name="sucursalbanco" value="<?php echo $rowLeeRemito['sucursalbanco']?>" type="text" size="4" /></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Estado:</div></td>
      <td width="180"><input id="descriconciliacion" name="descriconciliacion" value="No Conciliado" type="text" size="13" readonly="readonly" style="background-color:#CCCCCC"/>
	  <input id="estadoconciliacion" name="estadoconciliacion"  value="<?php echo $rowLeeRemito['estadoconciliacion']?>" type="text" size="1" readonly="readonly" style="visibility:hidden"/></td>
    </tr>
    <tr>
      <td width="150" rowspan="7"><div align="center"><strong>Datos de las Boletas</strong></div></td>
      <td width="170"><div align="right">Aportes:</div></td>
      <td width="180"><input id="importeboletasaporte" name="importeboletasaporte" value="<?php echo $rowLeeRemito['importeboletasaporte']?>" type="text" size="10" readonly="readonly" style="background-color:#CCCCCC"/></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Recargos:</div></td>
      <td width="180"><input id="importeboletasrecargo" name="importeboletasrecargo" value="<?php echo $rowLeeRemito['importeboletasrecargo']?>" type="text" size="10" readonly="readonly" style="background-color:#CCCCCC"/></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Varios:</div></td>
      <td width="180"><input id="importeboletasvarios" name="importeboletasvarios" value="<?php echo $rowLeeRemito['importeboletasvarios']?>" type="text" size="10" readonly="readonly" style="background-color:#CCCCCC"/></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Total de Aportes:</div></td>
      <td width="180"><input id="importeboletaspagos" name="importeboletaspagos" value="<?php echo $rowLeeRemito['importeboletaspagos']?>" type="text" size="10" readonly="readonly" style="background-color:#CCCCCC"/></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Acuerdos:</div></td>
      <td width="180"><input id="importeboletascuotas" name="importeboletascuotas" value="<?php echo $rowLeeRemito['importeboletascuotas']?>" type="text" size="10" readonly="readonly" style="background-color:#CCCCCC"/></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Total Bruto:</div></td>
      <td width="180"><input id="importeboletasbruto" name="importeboletasbruto" value="<?php echo $rowLeeRemito['importeboletasbruto']?>" type="text" size="10" readonly="readonly" style="background-color:#CCCCCC"/></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Cantidad de Boletas:</div></td>
      <td width="180"><input id="cantidadboletas" name="cantidadboletas" value="<?php echo $rowLeeRemito['cantidadboletas']?>" type="text" size="5" readonly="readonly" style="background-color:#CCCCCC"/></td>
    </tr>
    <tr>
      <td width="150"><div align="center"><strong>Datos del Resumen</strong></div></td>
      <td width="170"><div align="right">Fecha de Acreditacion:</div></td>
      <td width="180"><input id="fechaacreditacion" name="fechaacreditacion" value="<?php echo invertirFecha($rowLeeRemito['fechaacreditacion'])?>" type="text" size="10" readonly="readonly" style="background-color:#CCCCCC"/></td>
    </tr>
  </table>
  <p align="center">
    <input type="submit" name="guardar" value="Guardar Remito" align="left"/>
  </p>
  </form>
</body>
</html>