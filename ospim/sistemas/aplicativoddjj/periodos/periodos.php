<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."claves.php"); 

$maquina = $_SERVER['SERVER_NAME'];
if(strcmp("localhost",$maquina)==0)
	$hostaplicativo = "localhost";
else
	$hostaplicativo = $hostUsimra;

$usuarioaplicativo = $usuarioUsimra;
$claveaplicativo = $claveUsimra;
$dbaplicativo =  mysql_connect($hostaplicativo, $usuarioaplicativo, $claveaplicativo);
if (!$dbaplicativo) {
	die('No pudo conectarse: ' . mysql_error());
}
$dbnameaplicativo = $baseUsimraNewAplicativo;
mysql_select_db($dbnameaplicativo);

$sqlPeriodosApli = "SELECT 
p.anio as anio, p.mes as mes, p.descripcion, e.relacionmes, p.activo, e.tipo, e.valor, e.retiene060, e.retiene100, e.retiene150, e.mensaje 
FROM periodos as p
LEFT OUTER JOIN
extraordinarios as e on p.anio = e.anio and p.mes = e.mes
order by p.anio DESC, p.mes DESC limit 30";
$resPeriodosApli = mysql_query($sqlPeriodosApli,$dbaplicativo);
$canPeriodosApli = mysql_num_rows($resPeriodosApli);

$sqlPeriodos = "SELECT 
p.anio as anio, p.mes as mes, p.descripcion, e.relacionmes, e.tipo, e.valor, e.retiene060, e.retiene100, e.retiene150, e.mensaje 
FROM periodosusimra as p
LEFT OUTER JOIN
extraordinariosusimra as e on p.anio = e.anio and p.mes = e.mes
order by p.anio DESC, p.mes DESC limit 30";
$resPeriodos = mysql_query($sqlPeriodos,$db);
$canPeriodos = mysql_num_rows($resPeriodos); ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado de Titulares por Empresa :.</title>
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
		$("#listadoApli")
		.tablesorter({
			theme: 'blue', 
			widthFixed: true, 
			widgets: ["zebra", "filter"], 
			headers:{2:{sorter:false, filter:false},9:{sorter:false, filter:false}},
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
		.tablesorterPager({container: $("#paginadorApli")}); 

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
	
</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input class="nover" type="button" name="volver" value="Volver" onclick="location.href = '../menuAplicativoddjj.php'" /></p>
	<p><input class="nover" type="button" name="nuevo" value="Nuevo Periodo" onclick="location.href = 'nuevoPeriodo.php'" /></p>
	<h3>Periodos Aplicativo </h3>
	<?php if ($canPeriodosApli > 0) { ?>
	<table class="tablesorter" id="listadoApli" style="width:1100px; font-size:14px">
	<thead>
		<tr>
			<th class="filter-select" data-placeholder="---">Año</th>
			<th>Mes</th>
			<th>Descripcion</th>
			<th>Mes Relacionado</th>
			<th>Tipo</th>
			<th>Valor Fijo</th>
			<th>Ret. 060</th>
			<th>Ret. 100</th>
			<th>Ret. 150</th>
			<th>Mensaje</th>
			<th class="filter-select" data-placeholder="---">Activo</th>
		</tr>
	</thead>
	<tbody>
	<?php while($rowPeriodosApli = mysql_fetch_assoc($resPeriodosApli)) { ?>
		<tr align="center">
			<td><?php echo $rowPeriodosApli['anio'];?></td>
			<td><?php echo $rowPeriodosApli['mes'];?></td>
			<td><?php echo $rowPeriodosApli['descripcion'];?></td>
			<td><?php echo $rowPeriodosApli['relacionmes'];?></td>
			<td><?php echo $rowPeriodosApli['tipo'];?></td>
			<td><?php echo $rowPeriodosApli['valor'];?></td>
			<td><?php echo $rowPeriodosApli['retiene060'];?></td>
			<td><?php echo $rowPeriodosApli['retiene100'];?></td>
			<td><?php echo $rowPeriodosApli['retiene150'];?></td>
			<td><?php echo $rowPeriodosApli['mensaje'];?></td>
			<td><?php if ($rowPeriodosApli['activo'] == 1) { echo "SI"; } else { echo "NO"; } ?>
		</tr>
	<?php } ?>
	</tbody>
  </table>
    <table style="width: 245; border: 0">
      <tr>
        <td width="239">
		<div id="paginadorApli" class="pager">
		  <form class="nover" >
			<p align="center">
			  <img src="../img/first.png" width="16" height="16" class="first"/> <img src="../img/prev.png" width="16" height="16" class="prev"/>
			  <input name="text" type="text" class="pagedisplay" style="background:#CCCCCC; text-align:center" size="8" readonly="readonly"/>
		    <img src="../img/next.png" width="16" height="16" class="next"/> <img src="../img/last.png" width="16" height="16" class="last"/>
		    <select name="select" class="pagesize">
		      <option selected="selected" value="10">10 por pagina</option>
		      <option value="20">20 por pagina</option>
		      <option value="30">30 por pagina</option>
		      <option value="<?php echo $canPeriodosApli;?>">Todos</option>
		      </select>
			</p>
		  </form>	
		</div>
	</td>
      </tr>
  </table>
<?php } else { ?>
   		<h3>No existen periodos en Aplicativo DDJJ (Intranet)</h3>
<?php } ?>
    
    <h3>Periodos POSEIDON </h3>
<?php if ($canPeriodos > 0) { ?>
	<table class="tablesorter" id="listado" style="width:1100px; font-size:14px">
	<thead>
		<tr>
			<th class="filter-select" data-placeholder="Seleccion Año">Año</th>
			<th>Mes</th>
			<th>Descripcion</th>
			<th>Mes Relacionado</th>
			<th>Tipo</th>
			<th>Valor Fijo</th>
			<th>Ret. 060</th>
			<th>Ret. 100</th>
			<th>Ret. 150</th>
			<th>Mensaje</th>
		</tr>
	</thead>
	<tbody>
	<?php while($rowPeriodos = mysql_fetch_assoc($resPeriodos)) { ?>
		<tr align="center">
			<td><?php echo $rowPeriodos['anio'];?></td>
			<td><?php echo $rowPeriodos['mes'];?></td>
			<td><?php echo $rowPeriodos['descripcion'];?></td>
			<td><?php echo $rowPeriodos['relacionmes'];?></td>
			<td><?php echo $rowPeriodos['tipo'];?></td>
			<td><?php echo $rowPeriodos['valor'];?></td>
			<td><?php echo $rowPeriodos['retiene060'];?></td>
			<td><?php echo $rowPeriodos['retiene100'];?></td>
			<td><?php echo $rowPeriodos['retiene150'];?></td>
			<td><?php echo $rowPeriodos['mensaje'];?></td>
		</tr>
	<?php } ?>
	</tbody>
  </table>
    <table style="width: 245; border: 0">
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
		      <option value="<?php echo $canPeriodos;?>">Todos</option>
		      </select>
			</p>
		  </form>	
		</div>
	</td>
      </tr>
  </table>
<?php } else { ?>
   		<h3>No existen periodos en Poseidon</h3>
<?php } ?>
    <p><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();"/></p>
</div>
</body>
</html>