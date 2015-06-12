<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php"); 
include($libPath."fechas.php"); 

$cuit = $_POST['cuit'];
if (!isset($_POST['cuit'])) {
	$cuit = $_GET['cuit'];
}

$sqlEmpresa = "SELECT * FROM empresas where cuit = $cuit";
$resEmpresa = mysql_query($sqlEmpresa,$db);
$canEmpresa = mysql_num_rows($resEmpresa);
if ($canEmpresa == 0) {
	header ("Location: titularesPorEmpresa.php?err=2");
} else {
	$rowEmpresa = mysql_fetch_assoc($resEmpresa);
	$sqlEmpleados = "select * from empleadosusimra where nrcuit = $cuit";
	$resEmpleados = mysql_query($sqlEmpleados,$db);
	$canEmpleados = mysql_num_rows($resEmpleados);
}
	


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado de Titulares por Empresa :.</title>
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
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css">
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
		})
		.tablesorterPager({container: $("#paginador")}); 
	});
	
function abrirFicha(dire, cuit, cuil) {
	var dire = dire + '?cuit=' + cuit + '&cuil=' + cuil + '&estado=A';
	c= window.open(dire,"Ficha Empleado",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=30, left=40");
}
	
</script>
<body bgcolor="#B2A274">
<div align="center">
	<?php if (isset($_POST['cuit'])) { ?> <input type="reset" class="nover" name="volver" value="Volver" onclick="location.href = 'titularesPorEmpresa.php'" align="center"/> <?php } ?>
	<p><span class="Estilo2"> Empresa  "<?php echo $rowEmpresa['nombre'] ?>" - C.U.I.T.: <?php echo $rowEmpresa['cuit'] ?> </span></p>
	<p><span class="Estilo2">N&oacute;mina de Titulares </span></p>
	
<?php if ($canEmpleados > 0) { ?>

	<table class="tablesorter" id="listado" style="width:900px; font-size:14px">
	<thead>
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
		<?php
		while($rowEmpleados = mysql_fetch_assoc($resEmpleados)) {
		?>
		<tr align="center">
			<td><?php echo $rowEmpleados['nrcuil'];?></td>
			<td><?php echo $rowEmpleados['apelli'].", ".$rowEmpleados['nombre'];?></td>
			<td><?php echo invertirFecha($rowEmpleados['fecing']);?></td>
			<td><?php echo $rowEmpleados['tipdoc'].": ".$rowEmpleados['nrodoc'];?></td>
			<td><?php echo $rowEmpleados['ssexxo']; ?></td>
			<td><?php echo $rowEmpleados['activo']; ?></td>
			<td><input type="button" onclick="abrirFicha('fichaEmpleado.php','<?php echo $cuit ?>','<?php echo $rowEmpleados['nrcuil'] ?>' )" value='Ficha'></input></td>
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
 <?php } else {
   		print("<p><span class='Estilo2'>No tiene empleados cargados en la nómina</span><p>");
    }?>
</div>
</body>
</html>