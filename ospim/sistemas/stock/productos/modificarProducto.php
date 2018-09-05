<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."fechas.php"); 

$id = $_GET['id'];
$sqlProd = "SELECT p.*, u.* FROM producto p, ubicacionproducto u, departamentos d WHERE p.id = $id and p.id = u.id";
$resProd = mysql_query($sqlProd,$db);
$rowProd = mysql_fetch_assoc($resProd);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Nuevo Producto :.</title>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

$(function() {
	$("#listadoinsumos")
	.tablesorter({
		theme: 'blue',
		widthFixed: true, 
		widgets: ["zebra","filter"],
		headers:{2:{sorter:false, filter: false},6:{sorter:false, filter: false}},
		widgetOptions : { 
			filter_cssFilter   : '',
			filter_childRows   : false,
			filter_hideFilters : false,
			filter_ignoreCase  : true,
			filter_searchDelay : 300,
			filter_startsWith  : false,
			filter_hideFilters : false,
		}
		
	})
	.tablesorterPager({container: $("#paginador")}); 
});

jQuery(function($){
	$("#fecIni").mask("99-99-9999");
	$("#fecBaja").mask("99-99-9999");

	$("#sisop").change(function(){
		var sisop = $(this).val();
		$("#idsisop").val("");
		if (sisop == '') {
			$("#idsisop").prop("disabled", true );
		} else {
			$("#idsisop").prop("disabled", false );
		}
	});

	$("#office").change(function(){
		var sisop = $(this).val();
		$("#idoffice").val("");
		if (sisop == '') {
			$("#idoffice").prop("disabled", true );
		} else {
			$("#idoffice").prop("disabled", false );
		}
	});
});

function cargoSector(ubicacion) {
	document.forms.modifProducto.sector.length = 0;
	var o = document.createElement("OPTION");
	o.text = 'Seleccione Sector';
	o.value = '0';
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

function cargoUsuario(sector) {
		document.forms.modifProducto.usuario.length = 0;
		var o = document.createElement("OPTION");
		o.text = 'Seleccione Usuario';
		o.value = '0';
		document.forms.modifProducto.usuario.options.add(o);

<?php	$sqlUsuario = "select * from usuarios";
		$resUsuario = mysql_query($sqlUsuario,$db); 
		while ($rowUsuario = mysql_fetch_array($resUsuario)) { ?> 
			o = document.createElement("OPTION");
			o.text = '<?php echo $rowUsuario["nombre"]; ?>';
			o.value = <?php echo $rowUsuario["id"]; ?>;
			if (sector == <?php echo $rowUsuario["departamento"]; ?>) {
				document.forms.modifProducto.usuario.options.add(o);
			}
<?php } ?> 
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
	if (formulario.sisop.value != "") {
		if (formulario.idsisop.value == "") {
			alert("Debe cargar el id del Sistema Operativo");
			return(false);
		}
	}
	if (formulario.office.value != "") {
		if (formulario.idoffice.value == "") {
			alert("Debe cargar el id del Office");
			return(false);
		}
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
</head>
  
<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="reset" name="volver" value="Volver" onclick="location.href = 'productos.php'" /></p>
  	<h3>Modificar Producto</h3>
  	<form id="modifProducto" name="modifProducto" method="post" action="guardarModifProducto.php" onsubmit="return validar(this)">
  		<input name="id" type="text" id="id" size="3" maxlength="3" value="<?php echo $rowProd['id'] ?>" style="display: none"/>	
		<table width="80%" border="0" style="text-align:left">
        	<tr>
            	<td><b>Nombre</b></td>
				<td><input name="nombre" type="text" id="nombre" size="50" maxlength="50" value="<?php echo $rowProd['nombre'] ?>"/></td>
                <td><b>Nro Serie</b></td>
                <td><input name="nroserie" type="text" id="nroserie" size="50" maxlength="100" value="<?php echo $rowProd['numeroserie'] ?>"/></td>
            </tr>
            <tr>
				<td><b>Descripcion</b></td>
				<td><textarea name="descrip" cols="30" style="width: 378px; height: 80px;" rows="3" id="descrip"><?php echo $rowProd['descripcion'] ?></textarea></td>
                <td><b>Valor Original</b></td>
                <td><input name="valor" type="text" id="valor" size="14" maxlength="14" value="<?php echo $rowProd['valororiginal'] ?>"/></td>
           	</tr>
			<tr>
            	<td><b>Sistema Operativo</b></td>
                <td><input id="sisop" name="sisop" size="50" value="<?php echo $rowProd['sistemaoperativo'] ?>"/></td>
                <td><b>ID Sis-Op</b></td>
                <?php if ($rowProd['sistemaoperativo'] == '') { $disabled = 'disabled'; } else { $disabled = ''; } ?>
                <td><input id="idsisop" name="idsisop" size="50" value="<?php echo $rowProd['idso'] ?>" <?php echo $disabled?>/></td>
            </tr>
            <tr>
                <td><b>Version Office</b></td>
                <td><input id="office" name="office" size="50" value="<?php echo $rowProd['office'] ?>"/></td>
                <td><b>ID Office</b></td>
                <?php if ($rowProd['office'] == '') { $disabled = 'disabled'; } else { $disabled = ''; } ?>
                <td><input id="idoffice" name="idoffice" size="50" value="<?php echo $rowProd['idoffice'] ?>" <?php echo $disabled?>/></td>
            </tr>
            <tr>
                <td><b>Fecha Inicio</b></td>
                <td><input name="fecIni" type="text" id="fecIni" size="12" maxlength="12" value="<?php echo invertirFecha($rowProd['fechainicio']) ?>"/></td>
                <td><b>Nro. Poliza</b></td>
                <td><input name="valor2" type="text" id="valor2" size="50" readonly="readonly" style="background-color:#CCCCCC" value="<?php echo $rowProd['numeropoliza'] ?>"/></td>
            </tr>
            <tr>
		<?php if($rowProd['pertenencia'] == "U") { 
					$selectedU = "selected";	
					$selectedO = "";		
			  } else {
					$selectedU = "";	
					$selectedO = "selected";	
			  }	?>
             	<td><b>Ubicacion</b></td>
                <td>
                	<select name="ubicacion" onchange="cargoSector(document.forms.modifProducto.ubicacion[selectedIndex].value)">
                    	<option value="0">Seleccione Ubicaci&oacute;n</option>
						<option value="U" <?php echo $selectedU ?>>USIMRA</option>
						<option value="O" <?php echo $selectedO ?>>OSPIM</option>
                    </select>
                </td>
                <td><b>Sector</b></td>
                <td>
					<select name="sector" onchange="cargoUsuario(document.forms.modifProducto.sector[selectedIndex].value)">
           				<option value="0">Seleccione Sector</option>
				  <?php $sqlSector ="select * from departamentos";
						$resSector = mysql_query($sqlSector,$db);
						while ($rowSector=mysql_fetch_assoc($resSector)) { 
							$selected = '';
							if ($rowProd['departamento'] == $rowSector['id']) { $selected = 'selected'; } ?>
							<option value="<?php echo $rowSector['id']?>" <?php echo $selected ?>><?php echo $rowSector['nombre'] ?></option>
				  <?php } ?>
          			</select>
          		</td>
         	</tr>
           	<tr>
            	<td><b>Usuario</b></td>
                <td>
                	<select name="usuario">
           				<option value="0">Seleccione Usuario</option>
				  <?php $sqlUsuario ="select * from usuarios where departamento = ".$rowProd['departamento'];
						$resUsuario = mysql_query($sqlUsuario,$db);
						while ($rowUsuario = mysql_fetch_assoc($resUsuario)) { 
							$selected = '';
							if ($rowProd['idusuario'] == $rowUsuario['id']) { $selected = 'selected'; }?>
							<option value="<?php echo $rowUsuario['id']?>" <?php echo $selected ?>><?php echo $rowUsuario['nombre'] ?></option>
				  <?php } ?>
          			</select>
          		</td>
            </tr>
			<tr>
			<?php if($rowProd['activo'] == 1) { 
						$selectedSi = "selected";	
						$selectedNO = "";		
				  } else {
						$selectedSi = "";	
						$selectedNO = "selected";	
				  }	?>
                <td><b>Activo</b></td>
                <td>
                	<select name="activo" onchange="fechaBaja(document.forms.modifProducto.activo[selectedIndex].value)">
                        <option value="1" <?php echo $selectedSi ?>>SI</option>
                        <option value="0" <?php echo $selectedNO ?>>NO</option>
                    </select>
                </td>
				<?php if ($rowProd['activo'] == 0) { $fechabaja =  invertirFecha($rowProd['fechabaja']); $dis = ""; } else { $fechabaja  = ""; $dis = "disabled"; } ?>
                <td><b>Fecha Baja</b></td>
                <td><input name="fecBaja" type="text" id="fecBaja" size="12" maxlength="12" value="<?php echo $fechabaja ?>" <?php echo $dis ?>/></td>
             </tr>
             
             </table>
		<h3>Insumos</h3>
		<table class="tablesorter" id="listadoinsumos" style="width:400px; font-size:14px; text-align: center">
        	<thead>
				<tr>
            		<th></th>
               		<th>Descripcion</th>
               	</tr>
            </thead>
            <tbody>
			<?php $sqlInsumos = "SELECT * FROM insumo order by nombre";
				  $resInsumos = mysql_query($sqlInsumos,$db); 
				  $canInsumos = mysql_num_rows($resInsumos);
				  while ($rowInsumos = mysql_fetch_array($resInsumos)) {
					$idInsumo = $rowInsumos['id'];
					$sqlInsumoProducto = "SELECT * FROM insumoproducto WHERE idproducto = $id and idinsumo = $idInsumo";
					$resInsumoProducto = mysql_query($sqlInsumoProducto,$db); 
					$numInsumoProducto = mysql_num_rows($resInsumoProducto);
					if ($numInsumoProducto == 1) { ?>
	                   	<tr>
							<td><input name="insumo<?php echo $rowInsumos['id'] ?>" id="insumo<?php echo $rowInsumos['id'] ?>" type="checkbox" value="<?php echo $rowInsumos['id'] ?>" checked="checked"/></td>
							<td><?php echo $rowInsumos['nombre'] ?></td>
						</tr>
			  <?php } else { ?>
						<tr>
							<td><input name="insumo<?php echo $rowInsumos['id'] ?>" id="insumo<?php echo $rowInsumos['id'] ?>" type="checkbox" value="<?php echo $rowInsumos['id'] ?>"/></td>
							<td><?php echo $rowInsumos['nombre'] ?></td>
						</tr>
			  <?php  }
				   } ?>                      
			 </tbody>
		</table>
		<table style="width: 245; border: 0">
			<tr>
				<td width="239">
					<div id="paginador" class="pager">
						<form>
							<p align="center">
								<img src="../img/first.png" width="16" height="16" class="first"/> <img src="../img/prev.png" width="16" height="16" class="prev"/>
								<input name="text" type="text" class="pagedisplay" style="background:#CCCCCC; text-align:center" size="8" readonly="readonly"/>
								<img src="../img/next.png" width="16" height="16" class="next"/> <img src="../img/last.png" width="16" height="16" class="last"/>
								<select name="select" class="pagesize">
								    <option selected="selected" value="10">10 por pagina</option>
								    <option value="20">20 por pagina</option>
								    <option value="30">30 por pagina</option>
								    <option value="<?php echo $canInsumos;?>">Todos</option>
				      			</select>
				    		</p>
						</form>	
					</div>
				</td>
			</tr>
		</table>
		<input type="submit" name="Submit" value="Guardar" />
  </form>
</div>
</body>
</html>
