<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php"); 
include($libPath."fechas.php");

if (isset($_POST['cuit'])) {
	$err = 0;
	$cuit = $_POST['cuit'];
	$sqlEmpresa = "SELECT * FROM empresas where cuit = $cuit";
	$resEmpresa = mysql_query($sqlEmpresa,$db);
	$canEmpresa = mysql_num_rows($resEmpresa);
	if ($canEmpresa == 0) {
		$err = 2;
	} else {
		$rowEmpresa = mysql_fetch_assoc($resEmpresa);
		$sqlEmpleados = "select * from empleadosusimra where nrcuit = $cuit";
		$resEmpleados = mysql_query($sqlEmpleados,$db);
		$canEmpleados = mysql_num_rows($resEmpleados);
	} 
}?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Empleados Por Empresa :.</title>
<style type="text/css" media="print">
.nover {display:none}
</style>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script type="text/javascript">

jQuery(function($){
	$("#cuit").mask("99999999999");
});


$(function() {
	$("#listado")
	.tablesorter({
		theme: 'blue', 
		widthFixed: true, 
		widgets: ["zebra", "filter"], 
		headers:{4:{sorter:false},5:{sorter:false},6:{sorter:false, filter:false}},
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

function abrirFicha(dire, cuit, cuil) {
	var direc = dire + '?cuit=' + cuit + '&cuil=' + cuil + '&estado=A';
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
<form id="form1" name="form1" method="post" action="titularesPorEmpresa.php">
	<div align="center">
		<input type="button" name="volver" class="nover" value="Volver" onclick="location.href = 'menuEmpleados.php'" /> 
		<h3 class="nover"> Titulares por Empresa </h3>
		<p class="nover">C.U.I.T. <input name="cuit" id="cuit" type="text" size="10" /></p>
		<p class="nover"><input type="submit" name="Submit" value="Buscar" /></p>
  <?php if (isset($_POST['cuit'])) { ?>
  			<h3>Resultado de Busqueda - C.U.I.T. <?php echo $cuit ?></h3>
	<?php	if ($err == 2) { ?>
				<p style='color:red'><b> C.U.I.T. NO ENCONTRADO </b></p>
	  <?php } else { 
				if ($canEmpleados > 0) { ?>
				    <h3>Empresa  "<?php echo $rowEmpresa['nombre'] ?>"</h3>
					<h3>Nómina de Titulares </h3>
					<table class="tablesorter" id="listado" style="width:900px; font-size:14px">
						<thead style="text-align: center">
							<tr>
								<th>C.U.I.L.</th>
								<th>Apellido y Nombre</th>
								<th>Fecha Ingreso</th>
								<th>Tipo y Nro Doc</th>
								<th class="filter-select" data-placeholder="Seleccion Sexo">Sexo</th>
								<th class="filter-select" data-placeholder="Seleccion Sexo">Activo</th>
								<th>Accion</th>
							</tr>
						</thead>
						<tbody>
				  <?php while($rowEmpleados = mysql_fetch_assoc($resEmpleados)) { ?>
							<tr align="center">
								<td><?php echo $rowEmpleados['nrcuil'];?></td>
								<td><?php echo $rowEmpleados['apelli'].", ".$rowEmpleados['nombre'];?></td>
								<td><?php echo invertirFecha($rowEmpleados['fecing']);?></td>
								<td><?php echo $rowEmpleados['tipdoc'].": ".$rowEmpleados['nrodoc'];?></td>
								<td><?php echo $rowEmpleados['ssexxo']; ?></td>
								<td><?php echo $rowEmpleados['activo']; ?></td>
								<td>
									<input type="button" onclick="abrirFicha('fichaEmpleado.php','<?php echo $cuit ?>','<?php echo $rowEmpleados['nrcuil'] ?>' )" value='FICHA'></input>
									<input type="button" onclick="abrirDDJJAportes('ddjjaportes.php','<?php echo $rowEmpleados['nrcuil'] ?>','<?php echo $rowEmpleados['apelli'].", ".$rowEmpleados['nombre']; ?>','<?php echo $cuit ?>')" value='DDJJ-APOR' />	
								</td>
							</tr>
				  <?php } ?>
						</tbody>
	  				</table>			
	<?php 	 	} else { ?>
					<p style='color:red'><b> NO EXISTEN EMPLEADOS PARA EL C.U.I.T. '$cuit' </b></p>
		<?php	} ?>
			 	<p><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();"/></p>
	<?php	} 
		} ?>
	</div>
</form>
</body>
</html>
