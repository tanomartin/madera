<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php");

if (isset($_POST['dato']) && isset($_POST['filtro'])) {
	$dato = $_POST['dato'];
	$filtro = $_POST['filtro'];
	
	$cartel = "BUSQUEDA POR "; 
	if ($filtro == 0) {
		$cartel .= "C.U.I.L.: <b>'".$dato."'</b>";
	}
	if ($filtro == 1) {
		$cartel .= "NRO. DOCUMENTO: <b>'".$dato."'</b>";
	}
	if ($filtro == 2) {
		$cartel .= "NOMBRE Y APELLIDO: <b>'".$dato."'</b>";
	}
	
	$existe = 0;
	$existeBaja = 0;
	$existeFamilia = 0;
	$existeFamiliaBaja = 0;
	$encontro = 0;
	$resultado = array();
	if (isset($dato)) {
		$sqlSele = "select e.*, empresas.nombre empresa from ";
		if ($filtro == 0) { 
			$where = "where e.nrcuil = $dato"; 
		}
		if ($filtro == 1) { 
			$where = "where e.nrodoc = $dato"; 
		}
		if ($filtro == 2) { 
			$where = "where (e.apelli like '%$dato%' or e.nombre like '%$dato%')"; 	
		}
		
		//TITULARES//
		$tabla = "empleadosusimra e LEFT JOIN empresas ON e.nrcuit=empresas.cuit ";
		$sqlEmpleados = $sqlSele.$tabla.$where;
		//print($sqlEmpleados."<br>");
		$resEmpleados = mysql_query($sqlEmpleados,$db); 
		$canEmpleados = mysql_num_rows($resEmpleados); 
		if ($canEmpleados != 0) {
			$existe = 1;
		}
		
		//TITULARES DE BAJA//
		$tabla = "empleadosdebajausimra e LEFT JOIN empresas ON e.nrcuit = empresas.cuit ";
		$sqlEmpleadosBaja = $sqlSele.$tabla.$where;
		//print($sqlEmpleadosBaja."<br>");
		$resEmpleadosBaja = mysql_query($sqlEmpleadosBaja,$db); 
		$canEmpleadosBaja = mysql_num_rows($resEmpleadosBaja); 
		if ($canEmpleadosBaja != 0) {
			$existeBaja = 1;
		}
		
		//FAMILIARES//
		$tabla = "familiausimra e LEFT JOIN empresas ON e.nrcuit = empresas.cuit ";
		$sqlFamiliares = $sqlSele.$tabla.$where;
		//print($sqlFamiliares."<br>");
		$resFamiliares = mysql_query($sqlFamiliares,$db); 
		$canFamiliares = mysql_num_rows($resFamiliares); 
		if ($canFamiliares != 0) {
			$existeFamilia = 1;
		}
		
		//FAMILIARES//
		$tabla = "familiadebajausimra e LEFT JOIN empresas ON e.nrcuit = empresas.cuit ";
		$sqlFamiliaBaja = $sqlSele.$tabla.$where;
		//print($sqlFamiliaBaja."<br>");
		$resFamiliaresBaja = mysql_query($sqlFamiliaBaja,$db); 
		$canFamiliaresBaja = mysql_num_rows($resFamiliaresBaja); 
		if ($canFamiliaresBaja != 0) {
			$existeFamiliaBaja = 1;
		}
		
		$encontro = $existe + $existeBaja + $existeFamilia +$existeFamiliaBaja;
	}
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Acuerdo ospim :.</title>
<style type="text/css" media="print">
.nover {display:none}
</style>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">


	$(function() {
		$("#listaResultado")
		.tablesorter({
			theme: 'blue', 
			widthFixed: true, 
			widgets: ["zebra", "filter"], 
			headers:{6:{sorter:false},7:{sorter:false},8:{sorter:false},9:{sorter:false, filter:false}},
			widgetOptions : { 
				filter_cssFilter   : '',
				filter_childRows   : false,
				filter_hideFilters : false,
				filter_ignoreCase  : true,
				filter_searchDelay : 300,
				filter_startsWith  : false,
				filter_hideFilters : false,
			}
		});
	
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
		});
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
	var direc = dire + '?cuit=' + cuit + '&cuil=' + cuil + '&estado=' + estado;
	c= window.open(direc,"Ficha Empleado",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=30, left=40");
}


function abrirDDJJAportes(dire, cuil, nombre, cuit) {
	var direc = dire + '?cuil=' + cuil + '&nombre=' + nombre + '&cuit='+ cuit;
	c= window.open(direc,"Ficha Empleado",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=30, left=40");
}

</script>
</head>

<body bgcolor="#B2A274">
<form id="form1" name="form1" method="post" onsubmit="return validar(this)" action="buscadorEmpleados.php">
  <div align="center">
    <input type="button" class="nover" name="volver" value="Volver" onclick="location.href = 'menuEmpleados.php'" />
    <h2>Buscador</h2>
    <div class="nover"> 
		<table style="width: 400">
			<tr align="center">
				<td><b>Buscar por </b></td>
		  		<td style="text-align: left;">
		  			<input type="radio" name="filtro" value="0" checked="checked"/> C.U.I.L. <br>
		  			<input type="radio" name="filtro" value="1" /> Nro. Documento <br>
		  			<input type="radio" name="filtro" value="2" /> Apellido y/o Nombre
		  		</td>
		  	</tr>  
		</table>
		<p><b>Dato </b> <input name="dato" type="text" id="dato" size="20" /></p>
    </div>
	<p><input type="submit" name="Buscar" value="Buscar" class="nover" /></p>
<?php if (isset($dato)) { ?>
		<p><?php echo $cartel ?> </p>
<?php	if ($encontro == 0) { ?>
			<p style="color: red"><b> NO EXISTE TITULAR O FAMILIAR CON ESTE FILTRO DE BUSQUEDA </b></p>
<?php	} 
		if ($existe == 1 || $existeBaja == 1) {  ?>
			<p><b>TITULARES</b></p>
			<table class="tablesorter" id="listaResultado" style="font-size: 14px">
				<thead align="center">
					<tr>
						<th>C.U.I.L.</th>
						<th>Apellido y Nombre</th>
						<th>C.U.I.T.</th>
						<th>Empresa</th>
						<th>Fecha Ingreso</th>
						<th>Tipo y Nro Doc</th>
						<th class="filter-select" data-placeholder="Seleccion Sexo">Sexo</th>
						<th class="filter-select" data-placeholder="Seleccion Estado">Activo</th>
						<th class="filter-select" data-placeholder="Seleccion Estado">Eliminado</th>
						<th>+Info</th>
					</tr>
				</thead>
				<tbody>
		 <?php	if ($existe == 1) { 
		 			while($rowEmpleados = mysql_fetch_assoc($resEmpleados)) { ?>
						<tr align="center">
						  <td><?php echo $rowEmpleados['nrcuil'] ?></td>
						  <td><?php echo $rowEmpleados['apelli'].", ".$rowEmpleados['nombre'];?></td>
						  <td><?php echo $rowEmpleados['nrcuit'] ?></td>
						  <td><?php echo $rowEmpleados['empresa'] ?></td>
						  <td><?php echo $rowEmpleados['fecing'];?></td>	  
						  <td><?php echo $rowEmpleados['tipdoc'].": ".$rowEmpleados['nrodoc'];?></td>
						  <td><?php echo substr($rowEmpleados['ssexxo'],0,1); ?></td>
						  <td><?php echo $rowEmpleados['activo']; ?></td>
						  <td><?php echo "NO" ?></td>
						  <td>
						  	<input type="button" onclick="abrirFicha('fichaEmpleado.php','<?php echo $rowEmpleados['nrcuit'] ?>','<?php echo $rowEmpleados['nrcuil'] ?>','A')" value='FICHA' />
						  	<input type="button" onclick="abrirDDJJAportes('ddjjaportes.php','<?php echo $rowEmpleados['nrcuil'] ?>','<?php echo $rowEmpleados['apelli'].", ".$rowEmpleados['nombre']; ?>','<?php echo $rowEmpleados['nrcuit'] ?>')" value='DDJJ-APOR' />
						  </td>
						</tr>
		  	  <?php } 
			  	} 
		 	  	if ($existeBaja == 1) { 
			   		while($rowEmpleadosBaja = mysql_fetch_assoc($resEmpleadosBaja)) { ?>
						<tr align="center">
						  <td><?php echo $rowEmpleadosBaja['nrcuil'];?></td>
						  <td><?php echo $rowEmpleadosBaja['apelli'].", ".$rowEmpleadosBaja['nombre'];?></td>
						  <td><?php echo $rowEmpleadosBaja['nrcuit'] ?></td>
						  <td><?php echo $rowEmpleadosBaja['empresa'] ; ?></td>
						  <td><?php echo $rowEmpleadosBaja['fecing'];?></td>
						  <td><?php echo $rowEmpleadosBaja['tipdoc'].": ".$rowEmpleadosBaja['nrodoc'];?></td>
						  <td><?php echo substr($rowEmpleadosBaja['ssexxo'],0,1) ?></td>
						  <td><?php echo $rowEmpleadosBaja['activo']; ?></td>
						  <td><?php echo "SI" ?></td>
						  <td>
						  	<input type="button" onclick="abrirFicha('fichaEmpleado.php','<?php echo $rowEmpleadosBaja['nrcuit'] ?>','<?php echo $rowEmpleadosBaja['nrcuil'] ?>','E')" value='FICHA'/>
						  	<input type="button" onclick="abrirDDJJAportes('ddjjaportes.php','<?php echo $rowEmpleadosBaja['nrcuil'] ?>','<?php echo $rowEmpleadosBaja['apelli'].", ".$rowEmpleadosBaja['nombre']; ?>','<?php echo $rowEmpleadosBaja['nrcuit'] ?>')" value='DDJJ-APOR' />
						  </td>
						</tr>
		  	 <?php } 
		 	  	} ?>
			  	</tbody>
			</table>
  <?php }
		if ($existeFamilia == 1 || $existeFamiliaBaja == 1) { ?>
			<p><b>FAMILIARES</b><p>
			<table class="tablesorter" id="listaResultadoFami" style="font-size: 14px">
			  <thead align="center">
				<tr>
				  <th>C.U.I.L. TITULAR</th>
				  <th>Apellido y Nombre</th>
				  <th>C.U.I.T.</th>
				  <th>Empresa</th>
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
				  		<td><?php echo $rowFamiliares['nrcuit']; ?></td>
				  		<td><?php echo $rowFamiliares['empresa']; ?></td>
				  		<td><?php echo invertirFecha($rowFamiliares['fecing']);?></td>
				  		<td><?php echo $rowFamiliares['tipdoc'].": ".$rowFamiliares['nrodoc'];?></td>
				  		<td><?php echo substr($rowFamiliares['ssexxo'],0,1); ?></td>
				  		<td><?php echo $rowFamiliares['benefi']; ?></td>
				  		<td><?php echo "NO" ?></td>
					</tr>
			<?php } 
			   } ?>
		 <?php if ($existeFamiliaBaja == 1) { 
			   	 while($rowFamiliaresBaja = mysql_fetch_assoc($resFamiliaresBaja)) { ?>
					<tr align="center">
				  		<td><?php echo $rowFamiliaresBaja['nrcuil'];?></td>
				  		<td><?php echo $rowFamiliaresBaja['apelli'].", ".$rowFamiliaresBaja['nombre'];?></td>
				  		<td><?php echo $rowFamiliaresBaja['nrcuit']; ?></td>
				  		<td><?php echo $rowFamiliaresBaja['empresa']; ?></td>
				  		<td><?php echo invertirFecha($rowFamiliaresBaja['fecing']);?></td>
				  		<td><?php echo $rowFamiliaresBaja['tipdoc'].": ".$rowFamiliaresBaja['nrodoc'];?></td>
				  		<td><?php echo substr($rowFamiliaresBaja['ssexxo'],0,1); ?></td>
				  		<td><?php echo $rowFamiliaresBaja['benefi']; ?></td>
				  		<td><?php echo "SI" ?></td>
					</tr>
		   <?php } 
			   } ?>
			   </tbody>
			</table>
			<p><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" /></p>			
   <?php } 
 	} ?>
  </div>
</form>
</body>
</html>
