<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php");

$dato = $_POST['dato'];
$filtro = $_POST['filtro'];

if ($filtro == 0) {
	$cartel = "Resultados de Busqueda por C.U.I.L. <b>'".$dato."'</b>";
}
if ($filtro == 1) {
	$cartel = "Resultados de Busqueda por Nro. de Documento <b>'".$dato."'</b>";
}
if ($filtro == 2) {
	$cartel = "Resultados de Busqueda por Nombre y Apellido <b>'".$dato."'</b>";
}

$existe = 0;
$existeBaja = 0;
$existeFamilia = 0;
$existeFamiliaBaja = 0;
$encontro = 0;
$resultado = array();
if (isset($dato)) {
	$sqlSele = "select * from ";
	if ($filtro == 0) { 
		$where = "where nrcuil = $dato"; 
	}
	if ($filtro == 1) { 
		$where = "where nrodoc = $dato"; 
	}
	if ($filtro == 2) { 
		$where = "where apelli like '%$dato%' or nombre like '%$dato%'"; 	
	}
	
	//TITULARES//
	$tabla = "empleadosusimra ";
	$sqlEmpleados = $sqlSele.$tabla.$where;
	//print($sqlEmpleados."<br>");
	$resEmpleados = mysql_query($sqlEmpleados,$db); 
	$canEmpleados = mysql_num_rows($resEmpleados); 
	if ($canEmpleados != 0) {
		$existe = 1;
	}
	
	//TITULARES DE BAJA//
	$tabla = "empleadosdebajausimra ";
	$sqlEmpleadosBaja = $sqlSele.$tabla.$where;
	//print($sqlEmpleadosBaja."<br>");
	$resEmpleadosBaja = mysql_query($sqlEmpleadosBaja,$db); 
	$canEmpleadosBaja = mysql_num_rows($resEmpleadosBaja); 
	if ($canEmpleadosBaja != 0) {
		$existeBaja = 1;
	}
	
	//FAMILIARES//
	$tabla = "familiausimra ";
	$sqlFamiliares = $sqlSele.$tabla.$where;
	//print($sqlFamiliares."<br>");
	$resFamiliares = mysql_query($sqlFamiliares,$db); 
	$canFamiliares = mysql_num_rows($resFamiliares); 
	if ($canFamiliares != 0) {
		$existeFamilia = 1;
	}
	
	//FAMILIARES//
	$tabla = "familiadebajausimra ";
	$sqlFamiliaBaja = $sqlSele.$tabla.$where;
	//print($sqlFamiliaBaja."<br>");
	$resFamiliaresBaja = mysql_query($sqlFamiliaBaja,$db); 
	$canFamiliaresBaja = mysql_num_rows($resFamiliaresBaja); 
	if ($canFamiliaresBaja != 0) {
		$existeFamiliaBaja = 1;
	}
	
	$encontro = $existe + $existeBaja + $existeFamilia +$existeFamiliaBaja;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Acuerdo ospim :.</title>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
}
-->
</style>
</head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>
<script src="/lib/jquery.js"></script>
<script src="/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/lib/jquery.tablesorter/themes/theme.blue.css">
<script src="/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script>
<script src="/lib/funcionControl.js" type="text/javascript"></script>
<script src="/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">

	$(function() {
		$("#listaResultado")
		.tablesorter({
			theme: 'blue', 
			widthFixed: true, 
			widgets: ["zebra", "filter"], 
			headers:{4:{sorter:false},5:{sorter:false},7:{sorter:false, filter:false}},
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
	});
	
	$(function() {
		$("#listaResultadoFami")
		.tablesorter({
			theme: 'blue', 
			widthFixed: true, 
			widgets: ["zebra", "filter"], 
			headers:{4:{sorter:false},5:{sorter:false}},
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
	});

function validar(formulario) {
	if(formulario.dato.value == "") {
		alert("Debe colocar un dato de busqueda");
		return false;
	}

	if (formulario.filtro[0].checked) {
		if (!verificaCuilCuit(formulario.dato.value)) {
			alert("C.U.I.L. invalido");
			return false;
		}
	}
	if (formulario.filtro[1].checked) {
		resultado = esEnteroPositivo(formulario.dato.value);
		if (!resultado) {
			alert("El Número de Documento debe ser un numero entero positivo");
			return false;
		} 
	}
	
	$.blockUI({ message: "<h1>Generando Busqueda... <br>Esto puede tardar unos segundos.<br> Aguarde por favor</h1>" });
	return true;
}

function abrirFicha(dire, cuit, cuil, estado) {
	var dire = dire + '?cuit=' + cuit + '&cuil=' + cuil + '&estado=' + estado;
	c= window.open(dire,"Ficha Empleado",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=30, left=40");
}

</script>
<body bgcolor="#B2A274">
<form id="form1" name="form1" method="post" onSubmit="return validar(this)" action="buscadorEmpleados.php">
  <div align="center">
    <input type="reset" name="volver" value="Volver" onClick="location.href = 'menuInformes.php'" align="center"/>
    <p align="center" class="Estilo1">M&oacute;dulo Buscador de Empleados</p>
    <div align="center"> 
		<table width="400" border="0">
		  <tr>
		  <td rowspan="6"><div align="center"><strong>Buscar por </strong></div></td>
		  <tr>
			<td><div align="left"><input type="radio" name="filtro" value="0" checked="checked"/> C.U.I.L. Empleado</div></td>
		  </tr>
		  <tr>
			<td><div align="left"><input type="radio" name="filtro" value="1" /> Nro. Documento</div></td>
		  </tr>  
		  <tr>
			<td><div align="left"><input type="radio" name="filtro" value="2" /> Apellido y/o Nombre Afiliado</div></td>
		  </tr>  
		</table>
		<p><strong>Dato</strong> 
		  <input name="dato" type="text" id="dato" size="30" />
		</p>
    </div>
	  <p align="center">
		<label>
		<input type="submit" name="Buscar" value="Buscar" />
		</label>
	  </p>
	<?php 
	if (isset($dato)) {
		if ($encontro == 0) {
			print("<div style='color:#FF0000'><b> NO EXISTE TITULAR O FAMILIAR CON ESTE FILTRO DE BUSQUEDA </b></div><br>");
		} else { 
			print("<p> $cartel </p>");
		} 
		if ($existe == 1 || $existeBaja == 1) { ?>
			<table class="tablesorter" id="listaResultado" style="width:900px; font-size:14px">
			  <thead>
				<tr>
				  <th>C.U.I.L.</th>
				  <th>Apellido y Nombre</th>
				  <th>Fecha Ingreso</th>
				  <th>Tipo y Nro Doc</th>
				  <th class="filter-select" data-placeholder="Seleccion Sexo">Sexo</th>
				  <th class="filter-select" data-placeholder="Seleccion Estado">Activo</th>
				  <th class="filter-select" data-placeholder="Seleccion Estado">Eliminado</th>
				  <th>Accion</th>
				</tr>
			  </thead>
			  <tbody>
		<?php if ($existe == 1) { 
					 print("<p><b>TITULARES</b></p>");
				 while($rowEmpleados = mysql_fetch_assoc($resEmpleados)) { ?>
				<tr align="center">
				  <td><?php echo $rowEmpleados['nrcuil'];?></td>
				  <td><?php echo $rowEmpleados['apelli'].", ".$rowEmpleados['nombre'];?></td>
				  <td><?php echo invertirFecha($rowEmpleados['fecing']);?></td>
				  <td><?php echo $rowEmpleados['tipdoc'].": ".$rowEmpleados['nrodoc'];?></td>
				  <td><?php echo $rowEmpleados['ssexxo']; ?></td>
				  <td><?php echo $rowEmpleados['activo']; ?></td>
				  <td><?php echo "NO" ?></td>
				  <td><input type="button" onclick="abrirFicha('fichaEmpleado.php','<?php echo $rowEmpleados['nrcuit'] ?>','<?php echo $rowEmpleados['nrcuil'] ?>','A')" value='Ficha'>
					</input></td>
				</tr>
			<?php } 
			   } ?>
		 <?php if ($existeBaja == 1) { 
			   	while($rowEmpleadosBaja = mysql_fetch_assoc($resEmpleadosBaja)) { ?>
				<tr align="center">
				  <td><?php echo $rowEmpleadosBaja['nrcuil'];?></td>
				  <td><?php echo $rowEmpleadosBaja['apelli'].", ".$rowEmpleadosBaja['nombre'];?></td>
				  <td><?php echo invertirFecha($rowEmpleadosBaja['fecing']);?></td>
				  <td><?php echo $rowEmpleadosBaja['tipdoc'].": ".$rowEmpleadosBaja['nrodoc'];?></td>
				  <td><?php echo $rowEmpleadosBaja['ssexxo']; ?></td>
				  <td><?php echo $rowEmpleadosBaja['activo']; ?></td>
				  <td><?php echo "SI" ?></td>
				  <td><input type="button" onclick="abrirFicha('fichaEmpleado.php','<?php echo $rowEmpleadosBaja['nrcuit'] ?>','<?php echo $rowEmpleadosBaja['nrcuil'] ?>','E')" value='Ficha'>
					</input></td>
				</tr>
				<?php } 
			   } ?>
			  </tbody>
			</table>
    <?php }
		if ($existeFamilia == 1 || $existeFamiliaBaja == 1) { 
			 print("<p><b>FAMILIARES</b><p>");?>
			<table class="tablesorter" id="listaResultadoFami" style="width:900px; font-size:14px">
			  <thead>
				<tr>
				  <th>C.U.I.L. TITULAR</th>
				  <th>Apellido y Nombre</th>
				  <th>Fecha Ingreso</th>
				  <th>Tipo y Nro Doc</th>
				  <th class="filter-select" data-placeholder="Seleccion Sexo">Sexo</th>
				  <th class="filter-select" data-placeholder="Seleccion Estado">Beneficiario</th>
				  <th class="filter-select" data-placeholder="Seleccion Estado">Eliminado</th>
				</tr>
			  </thead>
			  <tbody>
		<?php if ($existeFamilia == 1) { 
				 while($rowFamiliares = mysql_fetch_assoc($resFamiliares)) { ?>
				<tr align="center">
				  <td><?php echo $rowFamiliares['nrcuil'];?></td>
				  <td><?php echo $rowFamiliares['apelli'].", ".$rowFamiliares['nombre'];?></td>
				  <td><?php echo invertirFecha($rowFamiliares['fecing']);?></td>
				  <td><?php echo $rowFamiliares['tipdoc'].": ".$rowFamiliares['nrodoc'];?></td>
				  <td><?php echo $rowFamiliares['ssexxo']; ?></td>
				  <td><?php echo $rowFamiliares['benefi']; ?></td>
				  <td><?php echo "NO" ?></td>
				</tr>
			<?php } 
			   } ?>
		 <?php if ($existeFamiliaBaja == 1) { 
			   	while($rowFamiliaresBaha = mysql_fetch_assoc($resFamiliaresBaja)) { ?>
				<tr align="center">
				  <td><?php echo $rowFamiliaresBaha['nrcuil'];?></td>
				  <td><?php echo $rowFamiliaresBaha['apelli'].", ".$rowFamiliaresBaha['nombre'];?></td>
				  <td><?php echo invertirFecha($rowFamiliaresBaha['fecing']);?></td>
				  <td><?php echo $rowFamiliaresBaha['tipdoc'].": ".$rowFamiliaresBaha['nrodoc'];?></td>
				  <td><?php echo $rowFamiliaresBaha['ssexxo']; ?></td>
				  <td><?php echo $rowFamiliaresBaha['benefi']; ?></td>
				  <td><?php echo "SI" ?></td>
				</tr>
				<?php } 
			   } ?>
			  </tbody>
			</table>
    <?php }
	} ?>
  </div>
</form>
</body>
</html>
