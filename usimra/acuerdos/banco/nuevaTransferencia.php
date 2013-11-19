<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php"); 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Nueva Trasnferencia USIMRA :.</title>
</head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>

<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/lib/funcionControl.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
jQuery(function($){
	$("#sucursal").mask("9999");
	$("#cuenta").mask("9999999999");
	$("#cuit").mask("99999999999");
	$("#orden").mask("999999999999");
	$("#fecha").mask("99-99-9999");
});

function validar(formulario) {
	formulario.Submit.disabled = true;
	if (formulario.banco.value == "") {
		formulario.Submit.disabled = false;
		alert("Debe ingresar el nombre del banco");
		formulario.banco.focus();
		return false;
	}
	if (formulario.sucursal.value  == "") {
		formulario.Submit.disabled = false;
		alert("Debe ingresar el codigo de sucursal");
		formulario.sucursal.focus();
		return false;
	}
	if (formulario.cuenta.value  == "") {
		formulario.Submit.disabled = false;
		alert("Debe ingresar el núemro de cuenta");
		formulario.cuenta.focus();
		return false;
	}
	if (!verificaCuil(formulario.cuit.value)){
		formulario.Submit.disabled = false;
		formulario.cuit.focus();
		return false;
	}
	if (!isNumberPositivo(formulario.monto.value) || formulario.monto.value == 0) {
		formulario.Submit.disabled = false;
		alert("Debe ingresar el un monto postivo distinto de 0");
		formulario.monto.focus();
		return false;
	}
	if (formulario.orden.value  == "") {
		formulario.Submit.disabled = false;
		alert("Debe ingresar el núemro de orden");
		formulario.orden.focus();
		return false;
	}
	if (!esFechaValida(formulario.fecha.value)){
		formulario.Submit.disabled = false;
		alert("La fecha no es valida");
		formulario.fecha.focus();
		return false;
	}
	if (!isNumberPositivo(formulario.comision.value)) {
		formulario.Submit.disabled = false;
		alert("Debe ingresar el un monto postivo en la comision");
		formulario.comision.focus();
		return false;
	}
	if (!isNumberPositivo(formulario.ivacomi.value)) {
		formulario.Submit.disabled = false;
		alert("Debe ingresar el un monto postivo en el iva de la comision");
		formulario.ivacomi.focus();
		return false;
	}
	return true;
}

</script>

<body bgcolor="#B2A274">
<div align="center">
	 <input type="reset" name="volver" value="Volver" onclick="location.href = 'trasnferencias.php'" align="center"/>
	<p><span class="Estilo2">Nueva Transferencia</span></p>
	<form id="nuevaTransf" name="nuevaTransf" method="post" action="guardaTrasnferencia.php" onSubmit="return validar(this)">
	  <table width="400" border="0">
        <tr>
          <td><div align="right"><strong>Banco</strong></div></td>
          <td>
            <div align="left">
              <input name="banco" type="text" id="banco" size="30" />
            </div>
		  </td>
        </tr>
        <tr>
          <td><div align="right"><strong>Sucursal</strong></div></td>
          <td><div align="left">
            <input name="sucursal" type="text" id="sucursal" size="4" />
          </div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>N&ordm; Cuenta </strong></div></td>
          <td><div align="left">
            <input name="cuenta" type="text" id="cuenta" size="12" />
          </div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>C.U.I.T.</strong></div></td>
          <td><div align="left">
            <input name="cuit" type="text" id="cuit" size="12" />
          </div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>Monto</strong></div></td>
          <td><div align="left">
            <input name="monto" type="text" id="monto" size="10" />
          </div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>Nro Orden </strong></div></td>
          <td><div align="left">
            <input name="orden" type="text" id="orden" size="14" />
          </div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>Fecha</strong></div></td>
          <td><div align="left">
            <input name="fecha" type="text" id="fecha" size="10" />
          </div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>Importe Comisi&oacute;n </strong></div></td>
          <td><div align="left">
            <input name="comision" type="text" id="comision" size="5" maxlength="10" />
          </div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>Iva Comisi&oacute;n </strong></div></td>
          <td><div align="left">
            <input name="ivacomi" type="text" id="ivacomi" size="5" />
          </div></td>
        </tr>
      </table>
      <p><input type="Submit" name="Submit" value="Guardar Transferencia"/></p>
  </form>
</div>
</body>
</html>