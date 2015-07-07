<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."fechas.php"); 

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Nuevo Insumo :.</title>
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

<body bgcolor="#CCCCCC">
<div align="center">
  <p>
    <input type="reset" name="volver" value="Volver" onclick="location.href = 'insumos.php'" align="center"/>
 </p>
  <p><span class="Estilo2">Nuevo Insumo </span></p>
  <form id="nuevoInsumo" name="nuevoInsumo" method="POST" action="guardarNuevoInsumo.php" onSubmit="return validar(this)">		
			<table width="850" border="0" style="text-align:left">
              <tr>
                <td>Nombre</td>
                <td><input name="nombre" type="text" id="nombre" size="50" maxlength="50"/></td>
                <td>Nro Serie</td>
                <td><input name="nroserie" type="text" id="nroserie" size="50" maxlength="100"/></td>
              </tr>
              <tr>
                <td>Descripcion</td>
                <td><label>
                  <textarea name="descrip" cols="30" rows="3" id="descrip"></textarea>
                </label></td>
                <td>Stock M&iacute;nimo</td>
                <td><input name="stockmin" type="text" id="stockmin" size="14" maxlength="14"/></td>
              </tr>
              <tr>
                <td>Punto de Pedido</td>
                <td><input name="ptoPedido" type="text" id="ptoPedido" size="14" maxlength="14"/></td>
                <td>Punto Promedio </td>
                <td><input name="ptoPromedio" type="text" id="ptoPromedio" size="14" maxlength="14"/></td>
              </tr>
            </table>
			<p><input type="submit" name="Submit" value="Guardar" sub/></p>
  </form>
</div>
</body>
</html>