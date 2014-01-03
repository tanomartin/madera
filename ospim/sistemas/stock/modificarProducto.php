<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."fechas.php"); 

$id = $_GET['id'];
$sqlProd = "SELECT p.*, u.* FROM producto p, ubicacionproducto u, departamentos d WHERE p.id = $id and p.id = u.id";
$resProd = mysql_query($sqlProd,$db);
$rowProd = mysql_fetch_assoc($resProd)

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

jQuery(function($){
	$("#fecIni").mask("99-99-9999");
	$("#fecBaja").mask("99-99-9999");
});

function cargoSector(ubicacion) {
	document.forms.modifProducto.sector.length = 0;
	var o
	o = document.createElement("OPTION");
	o.text = 'Seleccione Sector';
	o.value = 0
	document.forms.modifProducto.sector.options.add(o);
	if (ubicacion == 'U') {
		o = document.createElement("OPTION");
		o.text = 'USIMRA';
		o.value = 1;
		document.forms.modifProducto.sector.options.add(o);
	} 
	if (ubicacion == 'O') {
<?php	$sqlDptos= "select * from departamentos where id != 1";
		$resDptos = mysql_query($sqlDptos,$db); 
		while ($rowDptos = mysql_fetch_array($resDptos)) { ?> 
			o = document.createElement("OPTION");
			o.text = '<?php echo $rowDptos["nombre"]; ?>';
			o.value = <?php echo $rowDptos["id"]; ?>;
			document.forms.modifProducto.sector.options.add(o);
  <?php } ?> 
	}
}

function fechaBaja(valor) {
	if (valor == 1) {
		document.forms.modifProducto.fecBaja.value = "";
		document.forms.modifProducto.fecBaja.disabled = true;
	} else {
		document.forms.modifProducto.fecBaja.disabled = false;
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
	if (formulario.activo.value == 0) {
		if (formulario.fecBaja.value != "") {
			if (!esFechaValida(formulario.fecBaja.value)) {
				alert("Fecha de Baja invalida");
				return false;
			}
		} else {
			alert("Debe colocar fecha de Baja del producto");
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
    <input type="reset" name="volver" value="Volver" onclick="location.href = 'productos.php'" align="center"/>
 </p>
  <p><span class="Estilo2">Modificar Producto</span></p>
  <form id="modifProducto" name="modifProducto" method="POST" action="guardarModifProducto.php" onSubmit="return validar(this)">
  	<input name="id" type="text" id="id" size="3" maxlength="3" value="<?php echo $rowProd['id'] ?>" style="visibility:hidden"/>	
				<table width="850" border="0" style="text-align:left">
                  <tr>
                    <td>Nombre</td>
                    <td><input name="nombre" type="text" id="nombre" size="50" maxlength="50" value="<?php echo $rowProd['nombre'] ?>"/></td>
                    <td>Nro Serie</td>
                    <td><input name="nroserie" type="text" id="nroserie" size="50" maxlength="100" value="<?php echo $rowProd['numeroserie'] ?>"/></td>
                  </tr>
                  <tr>
                    <td>Descripcion</td>
                    <td><label>
                      <textarea name="descrip" cols="30" rows="3" id="descrip"><?php echo $rowProd['descripcion'] ?></textarea>
                    </label></td>
                    <td>Valor Original </td>
                    <td><input name="valor" type="text" id="valor" size="14" maxlength="14" value="<?php echo $rowProd['valororiginal'] ?>"/></td>
                  </tr>

                  <tr>
                    <td>Fecha Inicio </td>
                    <td><input name="fecIni" type="text" id="fecIni" size="12" maxlength="12" value="<?php echo invertirFecha($rowProd['fechainicio']) ?>"/></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
				  	  <?php if($rowProd['pertenencia'] == "U") { 
								$selectedU = "selected";	
								$selectedO = "";		
							} else {
								$selectedU = "";	
								$selectedO = "selected";	
							}	
						?>
                    <td>Ubicacion</td>
                    <td><label>
                      <select name="ubicacion" onchange="cargoSector(document.forms.modifProducto.ubicacion[selectedIndex].value)">
                        <option value="0">Seleccione Ubicaci&oacute;n</option>
							<option value="U" <?php echo $selectedU ?>>USIMRA</option>
							<option value="O" <?php echo $selectedO ?>>OSPIM</option>
                      </select>
                    </label></td>
                    <td>Sector</td>
                    <td>
					<select name="sector">
           				<option value=0>Seleccione Sector</option>
				   <?php 
						$sqlSector ="select * from departamentos";
						$resSector = mysql_query($sqlSector,$db);
						while ($rowSector=mysql_fetch_assoc($resSector)) { 
							$selected = '';
							if ($rowProd['departamento'] == $rowSector['id']) { $selected = 'selected'; }
						?>
							<option value="<?php echo $rowSector['id']?>" <?php echo $selected ?>><?php echo $rowSector['nombre'] ?></option>
				  <?php } ?>
          			</select>					</td>
                  </tr>
                  <tr>
                    <td>Usuario</td>
                    <td><input name="usuario" type="text" id="usuario" size="50" maxlength="50" value="<?php echo $rowProd['usuario'] ?>"/></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
				  	 <?php if($rowProd['activo'] == 1) { 
								$selectedSi = "selected";	
								$selectedNO = "";		
							} else {
								$selectedSi = "";	
								$selectedNO = "selected";	
							}	
						?>
                    <td>Activo</td>
                    <td><label>
                      <select name="activo" onchange="fechaBaja(document.forms.modifProducto.activo[selectedIndex].value)">
                        <option value="1" <?php echo $selectedSi ?>>SI</option>
                        <option value="0" <?php echo $selectedNO ?>>NO</option>
                      </select>
                    </label></td>
					
					<?php if ($rowProd['activo'] == 0) { $fechabaja =  invertirFecha($rowProd['fechabaja']); $dis = ""; } else { $fechabaja  = ""; $dis = "disabled"; } ?>
                    <td>Fecha Baja </td>
                    <td><input name="fecBaja" type="text" id="fecBaja" size="12" maxlength="12" value="<?php echo $fechabaja ?>" <?php echo $dis ?>/></td>
                  </tr>
                  <tr>
                    <td>Insumos</td>
                    <td colspan="3"><?php
						$sqlInsumos = "SELECT * FROM insumo";
						$resInsumos = mysql_query($sqlInsumos,$db); 
						while ($rowInsumos = mysql_fetch_array($resInsumos)) {
							$idInsumo = $rowInsumos['id'];
							$sqlInsumoProducto = "SELECT * FROM insumoproducto WHERE idproducto = $id and idinsumo = $idInsumo";
							$resInsumoProducto = mysql_query($sqlInsumoProducto,$db); 
							$numInsumoProducto = mysql_num_rows($resInsumoProducto);
							if ($numInsumoProducto == 1) {?>
						  <input name="insumo<?php echo $rowInsumos['id'] ?>" id="insumo<?php echo $rowInsumos['id'] ?>" type="checkbox" value="<?php echo $rowInsumos['id'] ?>" checked="checked"/><?php echo "[".$rowInsumos['nombre']."] " ?>
						  	<?php } else { ?>
							 <input name="insumo<?php echo $rowInsumos['id'] ?>" id="insumo<?php echo $rowInsumos['id'] ?>" type="checkbox" value="<?php echo $rowInsumos['id'] ?>"/><?php echo "[".$rowInsumos['nombre']."] " ?>
                  	<?php  }
				  		}	?>     
					</td>
                  </tr>
                </table>

				<p>
				  <input type="submit" name="Submit" value="Guardar" sub/>
			   </p>
  </form>
</div>
</body>
</html>
