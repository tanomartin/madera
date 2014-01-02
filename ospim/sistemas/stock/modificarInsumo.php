<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."fechas.php"); 

$idProd = $_GET['id'];

$sqlProd = "SELECT p.*, u.* FROM producto p, ubicacionproducto u, departamentos d WHERE p.id = $idProd and p.id = u.id";
$resProd = mysql_query($sqlProd,$db);
$rowProd = mysql_fetch_assoc($resProd);
$nombreProd = $rowProd['nombre'];
$cantInsumos = $rowProd['cantidadinsumos'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Nuevo Producto :.</title>
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

function validar(formulario) {
	cantidadInsumo = <?php echo $cantInsumos ?>;
	for (i = 1; i <= cantidadInsumo; i++) {
		var campNombre = "nombre" + i;
		var campNroserie = "nroserie" + i;
		var campDescrip = "descrip" + i;
		var campPtoPedido = "ptoPedido" + i;
		var campStockmin = "stockmin" + i;
		var campPtoPromedio = "ptoPromedio" + i;
	
		if (document.getElementById(campNombre).value == "") {
			alert("Debe ingresar el nombre del insumo numero " + i);
			return false;
		}
		if (document.getElementById(campNroserie).value == "") {
			alert("Debe ingresar el numero de serie del insumo numero " + i);
			return false;
		}
		if (document.getElementById(campPtoPedido).value == 0 || !esEnteroPositivo(document.getElementById(campPtoPedido).value)) {
			alert("Error en el Punto Pedido del insumo numero " + i);
			return false;
		}
		if (document.getElementById(campStockmin).value == 0 || !esEnteroPositivo(document.getElementById(campStockmin).value)) {
			alert("Error en el Stock Minimo del insumo numero " + i);
			return false;
		}	
		if (document.getElementById(campPtoPromedio).value == 0 || !esEnteroPositivo(document.getElementById(campPtoPromedio).value)) {
			alert("Error en el Punto Promedio del insumo numero " + i);
			return false;
		}	
		if (parseInt(document.getElementById(campPtoPromedio).value) < parseInt(document.getElementById(campPtoPedido).value)) {
			alert("Error el Punto Promedio no puede ser menor que el Punto de Pedido en el insumo " + i);
			return false;
		} 
		if (parseInt(document.getElementById(campPtoPromedio).value) < parseInt(document.getElementById(campStockmin).value)) {
			alert("Error el Punto Promedio no puede ser menor que el Stock Minimo en el insumo " + i);
			return false;
		}
		if (parseInt(document.getElementById(campPtoPedido).value) < parseInt(document.getElementById(campStockmin).value)) {
			alert("Error el Punto de Pedido no puede ser menor que el Stock Minimo en el insumo " + i);
			return false;
		}
	}
	formulario.Submit.disabled = true;
	return true;
}

</script>

<body bgcolor="#CCCCCC">
<div align="center">
  <p>
    <input type="reset" name="volver" value="Volver" onclick="location.href = 'modificarProducto.php?id=<?php echo $idProd ?>'" align="center"/>
 </p>
  <p><span class="Estilo2">Modificar Insumo del Producto "<?php echo $nombreProd ?>" </span></p>
  <form id="nuevoInsumo" name="nuevoInsumo" method="POST" action="guardarNuevoProducto.php" onSubmit="return validar(this)">		
			<input name="producto" type="hidden" value='<?php echo $arrayProducto ?>' />
	  <?php for ($i = 1; $i <= $cantInsumos; $i++) { ?>
			<table width="850" border="0" style="text-align:left">
              <tr>
                <td colspan="4"> <strong>INSUMO NÚMERO <?php echo $i ?></strong></td>
              </tr>
              <tr>
                <td>Nombre</td>
                <td><input name="nombre<?php echo $i?>" type="text" id="nombre<?php echo $i?>" size="50" maxlength="50"/></td>
                <td>Nro Serie</td>
                <td><input name="nroserie<?php echo $i?>" type="text" id="nroserie<?php echo $i?>" size="50" maxlength="100"/></td>
              </tr>
              <tr>
                <td>Descripcion</td>
                <td><label>
                  <textarea name="descrip<?php echo $i?>" cols="30" rows="3" id="descrip<?php echo $i?>"></textarea>
                </label></td>
                <td>Stock M&iacute;nimo</td>
                <td><input name="stockmin<?php echo $i?>" type="text" id="stockmin<?php echo $i?>" size="14" maxlength="14"/></td>
              </tr>
              <tr>
                <td>Punto de Pedido</td>
                <td><input name="ptoPedido<?php echo $i?>" type="text" id="ptoPedido<?php echo $i?>" size="14" maxlength="14"/></td>
                <td>Punto Promedio </td>
                <td><input name="ptoPromedio<?php echo $i?>" type="text" id="ptoPromedio<?php echo $i?>" size="14" maxlength="14"/></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
            </table>
			<?php } ?>
			<p><input type="submit" name="Submit" value="Guardar" sub/></p>
  </form>
</div>
</body>
</html>