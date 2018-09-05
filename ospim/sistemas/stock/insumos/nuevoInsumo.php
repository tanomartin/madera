<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."fechas.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Nuevo Insumo :.</title>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

function validar(formulario) {
	if (formulario.nombre.value == "") {
		alert("Debe ingresar el nombre");
		return false;
	}
	if (formulario.nroserie.value == "") {
		alert("Debe ingresar el numero de serie");
		return false;
	}
	if (formulario.ptoPedido.value == 0 || !esEnteroPositivo(formulario.ptoPedido.value)) {
		alert("Error en el Punto Pedido");
		return false;
	}
	if (formulario.stockmin.value == 0 || !esEnteroPositivo(formulario.stockmin.value)) {
		alert("Error en el Stock Minimo");
		return false;
	}	
	if (formulario.ptoPromedio.value == 0 || !esEnteroPositivo(formulario.ptoPromedio.value)) {
		alert("Error en el Punto Promedio");
		return false;
	}	
	if (parseInt(formulario.ptoPromedio.value) < parseInt(formulario.ptoPedido.value)) {
		alert("Error el Punto Promedio no puede ser menor que el Punto de Pedido");
		return false;
	} 
	if (parseInt(formulario.ptoPromedio.value) < parseInt(formulario.stockmin.value)) {
		alert("Error el Punto Promedio no puede ser menor que el Stock Minimo");
		return false;
	}
	if (parseInt(formulario.ptoPedido.value) < parseInt(formulario.stockmin.value)) {
		alert("Error el Punto de Pedido no puede ser menor que el Stock Minimo");
		return false;
	}
	formulario.Submit.disabled = true;
	return true;
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = 'insumos.php'" /></p>
  <h3>Nuevo Insumo </h3>
  <form id="nuevoInsumo" name="nuevoInsumo" method="post" action="guardarNuevoInsumo.php" onsubmit="return validar(this)">		
	<table width="850" border="0" style="text-align:left">
      <tr>
        <td><b>Nombre</b></td>
        <td><input name="nombre" type="text" id="nombre" size="50" maxlength="50"/></td>
        <td><b>Nro Serie</b></td>
        <td><input name="nroserie" type="text" id="nroserie" size="50" maxlength="100"/></td>
      </tr>
      <tr>
        <td><b>Descripcion</b></td>
        <td><textarea name="descrip" cols="30" rows="3" id="descrip"></textarea></td>
        <td><b>Stock Mínimo</b></td>
        <td><input name="stockmin" type="text" id="stockmin" size="14" maxlength="14"/></td>
      </tr>
      <tr>
        <td><b>Punto de Pedido</b></td>
        <td><input name="ptoPedido" type="text" id="ptoPedido" size="14" maxlength="14"/></td>
        <td><b>Punto Promedio</b> </td>
        <td><input name="ptoPromedio" type="text" id="ptoPromedio" size="14" maxlength="14"/></td>
      </tr>
    </table>
	<p><input type="submit" name="Submit" value="Guardar" /></p>
  </form>
</div>
</body>
</html>