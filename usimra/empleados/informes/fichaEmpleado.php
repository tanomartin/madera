<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionUsimra.php");
include($_SERVER['DOCUMENT_ROOT']."/lib/fechas.php"); 

$cuil = $_GET['cuil'];
$cuit = $_GET['cuit'];
$estado = $_GET['estado'];

if ($estado == 'A') {
	$tabla = "empleadosusimra";
}
if ($estado == 'E') {
	$tabla = "empleadosdebajausimra";
}

$sqlEmpleado = "SELECT e.*, p.descrip as provincia, r.descripcion as rama, c.descri as categoria FROM $tabla e, provincia p, categoriasusimra c, ramausimra r WHERE e.nrcuit = '$cuit' and e.nrcuil = '$cuil' and e.provin = p.codprovin and e.rramaa = c.codram and e.catego = c.codcat and e.rramaa = r.id";
$resEmpleado = mysql_query($sqlEmpleado,$db);
$rowEmpleado = mysql_fetch_assoc($resEmpleado);

$sqlFamilia = "SELECT * FROM familiausimra WHERE nrcuit = '$cuit' and nrcuil = '$cuil'";
$resFamilia = mysql_query($sqlFamilia,$db);
$canFamilia = mysql_num_rows($resFamilia);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Ficha Empleado :.</title>
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
<style type="text/css" media="print">
.nover {display:none}
</style>
<script src="/lib/funcionControl.js" type="text/javascript"></script>
<script src="/lib/jquery.js"></script>
<script src="/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/lib/jquery.tablesorter/themes/theme.blue.css">
<script src="/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
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
		.tablesorterPager({container: $("#paginador")}); 
	});
	
function abrirFicha(dire, cuit, cuil) {
	var dire = dire + '?cuit=' + cuit + '&cuil=' + cuil;
	c= window.open(dire,"Ficha Empleado",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=30, left=40");
}
	
</script>
<body bgcolor="#B2A274">
<div align="center">
	<p><span class="Estilo2"> Empleado  "<?php echo $rowEmpleado['apelli'].", ".$rowEmpleado['nombre'] ?>" - C.U.I.L.: <?php echo $rowEmpleado['nrcuil'] ?> </span></p>
	
	<table width="700" border="0">
		  <tr>
			<td style="text-align:right"><b>Fecha Nac.:</b></td>
			<td><?php echo invertirFecha($rowEmpleado['fecnac']) ?></td>
		  </tr>
		  <tr>
			<td style="text-align:right"><b>Tipo y Nro. Doc.:</b></td>
			<td><?php echo $rowEmpleado['tipdoc'].": ".$rowEmpleado['nrodoc'];?></td>
		  </tr>
		  <tr>
			<td style="text-align:right"><b>Estado Civil:</b></td>
			<td><?php echo $rowEmpleado['estciv'] ?></td>
		  </tr>
		  <tr>
			<td style="text-align:right"><b>Domicilio:</b></td>
			<td><?php echo $rowEmpleado['direcc'].", ".$rowEmpleado['locale']." [C.P.:".$rowEmpleado['copole']."] - ".$rowEmpleado['provincia'] ?></td>
		  </tr>
		  <tr>
			<td style="text-align:right"><b>Fecha Ingreso:</b></td>
			<td><?php echo invertirFecha($rowEmpleado['fecing']) ?></td>
		  </tr>
		  <tr>
			<td style="text-align:right"><b>Rama:</b></td>
			<td><?php echo $rowEmpleado['rama'] ?></td>
		  </tr>
		  <tr>
			<td style="text-align:right"><b>Categoría:</b></td>
			<td><?php echo $rowEmpleado['categoria'] ?></td>
		  </tr>
		  <tr>
			<td style="text-align:right"><b>Activo:</b></td>
			<td><?php echo $rowEmpleado['activo'] ?></td>
		  </tr>
		  <tr>
			<td style="text-align:right"><b>Estado:</b></td>
			<td><?php if ($estado == 'A') { echo 'De Alta'; } else { echo 'De Baja'; } ?></td>
		  </tr>
	</table>
	<?php if ($canFamilia > 0) { ?>
		<p><span class="Estilo2">Familiares </span></p>
		<table class="tablesorter" id="listado" style="width:900px; font-size:14px">
		<thead>
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
			<?php
			while($rowFamilia = mysql_fetch_assoc($resFamilia)) {
			?>
			<tr align="center">
				<td><?php echo $rowFamilia['apelli'].", ".$rowFamilia['nombre'];?></td>
				<td><?php echo $rowFamilia['codpar']; ?></td>
				<td><?php echo $rowFamilia['ssexxo']; ?></td>
				<td><?php echo invertirFecha($rowFamilia['fecnac']);?></td>
				<td><?php echo invertirFecha($rowFamilia['fecing']);?></td>
				<td><?php echo $rowFamilia['tipdoc'].": ".$rowFamilia['nrodoc'];?></td>
				<td><?php echo $rowFamilia['benefi']; ?></td>
			</tr>
			<?php
			}
			?>
		</tbody>
	  </table>
		<table width="245" border="0">
		  <tr>
			<td width="239">
			<div id="paginador" class="pager">
			  <form class="nover" >
				<p align="center">
				  <img src="../img/first.png" width="16" height="16" class="first"/> <img src="../img/prev.png" width="16" height="16" class="prev"/>
				  <input name="text" type="text" class="pagedisplay" style="background:#CCCCCC; text-align:center" size="8" readonly="readonly"/>
				<img src="../img/next.png" width="16" height="16" class="next"/> <img src="../img/last.png" width="16" height="16" class="last"/>
				<select name="select" class="pagesize">
				  <option selected="selected" value="10">10 por pagina</option>
				  <option value="20">20 por pagina</option>
				  <option value="30">30 por pagina</option>
				  <option value="<?php echo $canEmpleados;?>">Todos</option>
				  </select>
				</p>
			  </form>	
			</div>
		</td>
		  </tr>
	  </table>
		<p>
		  <input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" align="right"/>
		</p>
<?php	}  else { 
			print("<p><span class='Estilo2'>No tiene familiares cargados </span><p>");
		}	?>
</div>
</body>
</html>