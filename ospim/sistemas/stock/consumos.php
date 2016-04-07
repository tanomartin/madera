<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."fechas.php"); 
?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Consumos :.</title>


<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 


<script>
	$(function() {
		$("#listado")
		.tablesorter({
			theme: 'blue',
			widthFixed: true, 
			widgets: ["zebra","filter"],
			/*headers:{2:{sorter:false, filter: false},5:{sorter:false, filter: false},6:{sorter:false, filter: false}},*/
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
<style type="text/css">
<!--
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
}
-->
</style>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p>
    <input type="reset" name="volver" value="Volver" onclick="location.href = 'menuStock.php'" />
</p>
  <p><span class="Estilo1">Listado de Consumo </span></p>
  <table class="tablesorter" id="listado" style="width:800px; font-size:14px">
	  <thead>
		<tr>
		  <th>Codigo Consumo</th>
		  <th>Nombre</th>
		  <th>Descripción</th>
		  <th>Productos</th>
		  <th class="filter-select" data-placeholder="Seleccione Usuario">Usuario Consumo </th>
		  <th>Fecha Consumo </th>
	    </tr>
	 </thead>
	 <tbody>
		<?php	
			$sqlConsumo = "SELECT c.*, i.nombre as nombre, i.descripcion as descri, u.nombre as usuario FROM consumoinsumo c, insumo i, usuarios u WHERE i.id = c.idinsumo and c.idusuario = u.id";
			$resConsumo = mysql_query($sqlConsumo,$db);
			$canConsumo = mysql_num_rows($resConsumo);
			while ($rowConsumo = mysql_fetch_assoc($resConsumo)) { ?>
			<tr align="center">
					<td><?php echo $rowConsumo['id'] ?></td>
					<td><?php echo $rowConsumo['nombre']?></td>
					<td><?php echo $rowConsumo['descri']?></td>
					<td><?php 
						$idInsumo = $rowConsumo['idinsumo'];
						$sqlInsumoProducto = "SELECT p.activo as activo, p.nombre as prod, d.nombre as depto FROM insumoproducto i, producto p, ubicacionproducto u, departamentos d WHERE i.idinsumo = $idInsumo and i.idproducto = p.id and p.id = u.id and u.departamento = d.id";
						$resInsumoProducto = mysql_query($sqlInsumoProducto,$db);
						while ($rowInsumoProducto = mysql_fetch_assoc($resInsumoProducto)) {
							print("* ".$rowInsumoProducto['prod']." (".$rowInsumoProducto['depto'].")"."</br>");
						}
					?></td>	
					<td><?php echo $rowConsumo['usuario'] ?></td>			
					<td><?php echo invertirFecha($rowConsumo['fechaconsumo']) ?></td>
		</tr>
	 <?php } ?>
    </tbody>
  </table>
   <table style="width: 245; border: 0">
      <tr>
        <td width="239">
		<div id="paginador" class="pager">
		  <form>
			<p align="center">
			  <img src="img/first.png" width="16" height="16" class="first"/> <img src="img/prev.png" width="16" height="16" class="prev"/>
			  <input name="text" type="text" class="pagedisplay" style="background:#CCCCCC; text-align:center" size="8" readonly="readonly"/>
		    <img src="img/next.png" width="16" height="16" class="next"/> <img src="img/last.png" width="16" height="16" class="last"/>
		    <select name="select" class="pagesize">
		      <option selected="selected" value="10">10 por pagina</option>
		      <option value="20">20 por pagina</option>
		      <option value="30">30 por pagina</option>
		      <option value="<?php echo $canConsumo;?>">Todos</option>
		      </select>
		    </p>
			<p align="center"><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();"/></p>
		  </form>	
		</div>
	</td>
      </tr>
  </table>
</div>
</body>
</html>

