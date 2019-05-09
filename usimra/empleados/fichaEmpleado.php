<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php");

$cuil = $_GET['cuil'];
$cuit = $_GET['cuit'];
$estado = $_GET['estado'];

if ($estado == 'A') {
	$tabla = "empleadosusimra";
}
if ($estado == 'E') {
	$tabla = "empleadosdebajausimra";
}

$sqlEmpleado = "SELECT 
					e.*, p.descrip as provincia, r.descripcion as rama, c.descri as categoria 
				FROM 
					$tabla e, provincia p, categoriasusimra c, ramausimra r
				WHERE 
					e.nrcuit = '$cuit' and 
					e.nrcuil = '$cuil' and 
					e.provin = p.codprovin and 
					e.rramaa = c.codram and 
					e.catego = c.codcat and 
					e.rramaa = r.id";
$resEmpleado = mysql_query($sqlEmpleado,$db);
$rowEmpleado = mysql_fetch_assoc($resEmpleado);

$sqlFamilia = "SELECT * FROM familiausimra WHERE nrcuit = '$cuit' and nrcuil = '$cuil'";
$resFamilia = mysql_query($sqlFamilia,$db);
$canFamilia = mysql_num_rows($resFamilia);

$sqlEmpresa = "SELECT * FROM empresas WHERE cuit = '$cuit'";
$resEmpresa = mysql_query($sqlEmpresa,$db);
$canEmpresa = mysql_num_rows($resEmpresa);
if ($canEmpresa == 0) {
	$sqlEmpresa = "SELECT * FROM empresasdebaja WHERE cuit = '$cuit'";
	$resEmpresa = mysql_query($sqlEmpresa,$db);
	$canEmpresa = mysql_num_rows($resEmpresa);
	if ($canEmpresa == 0) {
		$nombreEmpresa = "No existe empresa";
	} else {
		$rowEmpresa = mysql_fetch_assoc($resEmpresa);
		$nombreEmpresa = $rowEmpresa['nombre']." <font color='red'> (De Baja)</font>";
	}
} else {
	$rowEmpresa = mysql_fetch_assoc($resEmpresa);
	$nombreEmpresa = $rowEmpresa['nombre'];
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Ficha Empleado :.</title>
<style type="text/css" media="print">
.nover {display:none}
</style>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script type="text/javascript">
	$(function() {
		$("#listado")
		.tablesorter({
			theme: 'blue', 
			widthFixed: true, 
			widgets: ["zebra", "filter"], 
			headers:{2:{sorter:false}},
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
</script>
</head>

<body bgcolor="#B2A274">
<div align="center">
	<h3> Empleado  "<?php echo $rowEmpleado['apelli'].", ".$rowEmpleado['nombre'] ?>" - C.U.I.L.: <?php echo $rowEmpleado['nrcuil'] ?> </h3>
	<h3> Empresa  "<?php echo $nombreEmpresa ?>" - C.U.I.T.: <?php echo $cuit ?> </h3>
	<table width="700" border="1"> 
		  <tr>
			<td style="text-align:right; width: 20%"><b>Fecha Nac.</b></td>
			<td><?php echo invertirFecha($rowEmpleado['fecnac']) ?></td>
		  </tr>
		  <tr>
			<td style="text-align:right"><b>Tipo - Nro. Doc.</b></td>
			<td><?php echo $rowEmpleado['tipdoc'].": ".$rowEmpleado['nrodoc'];?></td>
		  </tr>
		  <tr>
			<td style="text-align:right"><b>Estado Civil</b></td>
			<td><?php echo $rowEmpleado['estciv'] ?></td>
		  </tr>
		  <tr>
			<td style="text-align:right"><b>Domicilio</b></td>
			<td><?php echo $rowEmpleado['direcc'].", ".$rowEmpleado['locale']." [C.P.:".$rowEmpleado['copole']."] - ".$rowEmpleado['provincia'] ?></td>
		  </tr>
		  <tr>
			<td style="text-align:right"><b>Fecha Ingreso</b></td>
			<td><?php echo invertirFecha($rowEmpleado['fecing']) ?></td>
		  </tr>
		  <tr>
			<td style="text-align:right"><b>Rama</b></td>
			<td><?php echo $rowEmpleado['rama'] ?></td>
		  </tr>
		  <tr>
			<td style="text-align:right"><b>Categoría</b></td>
			<td><?php echo $rowEmpleado['categoria'] ?></td>
		  </tr>
		  <tr>
			<td style="text-align:right"><b>Activo</b></td>
			<td><?php echo $rowEmpleado['activo'] ?></td>
		  </tr>
		  <tr>
			<td style="text-align:right"><b>Estado</b></td>
			<td><?php if ($estado == 'A') { echo 'De Alta'; } else { echo 'De Baja'; } ?></td>
		  </tr>
  	</table>
  	  <h3>Familiares </h3>	
<?php if ($canFamilia > 0) { ?>
		<table class="tablesorter" id="listado" style="width:900px; font-size:14px">
		<thead align="center">
			<tr>
				<th>Apellido y Nombre</th>
				<th>Parentesco</th>
				<th>Sexo</th>
				<th>Fecha Nac.</th>
				<th>Fecha Ing.</th>
				<th>Tipo y Nro Doc</th>
				<th>Beneficiario</th>
			</tr>
		</thead>
		<tbody>
	<?php while($rowFamilia = mysql_fetch_assoc($resFamilia)) { ?>
			<tr align="center">
				<td><?php echo $rowFamilia['apelli'].", ".$rowFamilia['nombre'];?></td>
				<td><?php echo $rowFamilia['codpar']; ?></td>
				<td><?php echo $rowFamilia['ssexxo']; ?></td>
				<td><?php echo invertirFecha($rowFamilia['fecnac']);?></td>
				<td><?php echo invertirFecha($rowFamilia['fecing']);?></td>
				<td><?php echo $rowFamilia['tipdoc'].": ".$rowFamilia['nrodoc'];?></td>
				<td><?php echo $rowFamilia['benefi']; ?></td>
			</tr>
	<?php } ?>
		</tbody>
	  </table>
<?php } else { ?>
		 <h3>No tiene familiares cargados </h3>
<?php }	?>
 	<p><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" align="right"/></p>
</div>
</body>
</html>