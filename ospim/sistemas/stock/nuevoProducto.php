<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
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

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

jQuery(function($){
	$("#fecIni").mask("99-99-9999");
});

function cargoSector(ubicacion) {
	document.forms.nuevoProducto.sector.length = 0;
	var o
	o = document.createElement("OPTION");
	o.text = 'Seleccione Sector';
	o.value = 0
	document.forms.nuevoProducto.sector.options.add(o);
	if (ubicacion == 'U') {
		o = document.createElement("OPTION");
		o.text = 'USIMRA';
		o.value = 1;
		document.forms.nuevoProducto.sector.options.add(o);
	} 
	if (ubicacion == 'O') {
<?php	$sqlDptos= "select * from departamentos where id != 1";
		$resDptos = mysql_query($sqlDptos,$db); 
		while ($rowDptos = mysql_fetch_array($resDptos)) { ?> 
			o = document.createElement("OPTION");
			o.text = '<?php echo $rowDptos["nombre"]; ?>';
			o.value = <?php echo $rowDptos["id"]; ?>;
			document.forms.nuevoProducto.sector.options.add(o);
  <?php } ?> 
	}
}

function validar(formulario) {
	if (formulario.nombre.value == "") {
		alert("Debe completar en Nombre");
		return(false);
	}
	if (formulario.valor.value == "" || !isNumberPositivo(formulario.valor.value)) {
		alert("Error en valor original");
		return(false);
	}
	if (formulario.fecIni.value != "") {
		if (!esFechaValida(formulario.fecIni.value)) {
			alert("Fecha de Inicio invalida");
			return false;
		}
	} else {
		alert("Debe colocar fecha de Inicio del producto");
		return false;
	}
	if (formulario.ubicacion.value == 0) {
		alert("Debe seleccionar Ubicacion");
		return(false);
	}
	if (formulario.sector.value == 0) {
		alert("Debe seleccionar Sector");
		return(false);
	}
	formulario.Submit.disabled = true;
	return true;
}

</script>

<body bgcolor="#CCCCCC">
<div align="center">
  <p>
    <input type="reset" name="volver" value="Volver" onclick="location.href = 'productos.php'" align="center"/>
 </p>
  <p><span class="Estilo2">Nuevo Producto</span></p>
  <form id="nuevoProducto" name="nuevoProducto" method="POST" action="guardarNuevoProducto.php" onSubmit="return validar(this)">		
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
                    <td>Valor Original </td>
                    <td><input name="valor" type="text" id="valor" size="14" maxlength="14"/></td>
                  </tr>

                  <tr>
                    <td>Fecha Inicio </td>
                    <td><input name="fecIni" type="text" id="fecIni" size="12" maxlength="12"/></td>
                    <td>Usuario</td>
                    <td><input name="usuario" type="text" id="usuario" size="50" maxlength="50"/></td>
                  </tr>
                  <tr>
                    <td>Ubicacion</td>
                    <td><label>
                      <select name="ubicacion" onchange="cargoSector(document.forms.nuevoProducto.ubicacion[selectedIndex].value)">
                        <option value="0">Seleccione Ubicaci&oacute;n</option>
                        <option value="U">USIMRA</option>
                        <option value="O">OSPIM</option>
                      </select>
                    </label></td>
                    <td>Sector</td>
                    <td><select name="sector">
                      <option value="0">Seleccione Sector</option>
					</select></td>
                  </tr>
                  <tr>
                    <td height="48">Insumos</td>
                    <td>
					<table>
					<?php
						$sqlInsumos = "SELECT * FROM insumo order by nombre";
						$resInsumos = mysql_query($sqlInsumos,$db); 
						while ($rowInsumos = mysql_fetch_array($resInsumos)) {?>
                      		<tr>
								<td>
								<input name="insumo<?php echo $rowInsumos['id'] ?>" id="insumo<?php echo $rowInsumos['id'] ?>" type="checkbox" value="<?php echo $rowInsumos['id'] ?>"/>								</td>
								<td>
								<?php echo "[".$rowInsumos['nombre']."] " ?>								</td>
							</tr>
                  <?php }	?>                      
				  	</table>					</td>
                  
				    <td colspan="2"><div align="center">
				      <input type="submit" name="Submit" value="Guardar" sub/>
			        </div></td>
			      </tr>
                </table>

			   <p>&nbsp;</p>
  </form>
</div>
</body>
</html>
