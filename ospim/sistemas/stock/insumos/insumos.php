<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Productos :.</title>
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
			headers:{7:{sorter:false, filter: false}},
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
  <p>
    <input type="reset" name="volver" value="Volver" onclick="location.href = '../menuStock.php'" />
</p>
  <p><span class="Estilo1">Listado de Insumo </span></p>
  <input name="nuevo" type="button" id="nuevo" onclick="location.href = 'nuevoInsumo.php'"  value="Nuevo" />
  <table class="tablesorter" id="listado" style="width:800px; font-size:14px; text-align: center">
	  <thead>
		<tr>
		  <th>Codigo</th>
		  <th>Nombre</th>
		  <th>Descripcion</th>
		  <th>Producto</th>
		  <th>Pto. Promedio</th>
		  <th>Pto. Pedido</th>
		  <th>Stock Min.</th>
		  <th>Acciones</th>
		</tr>
	 </thead>
	 <tbody>
		<?php	
			$sqlInsumo = "SELECT * FROM insumo";
			$resInsumo = mysql_query($sqlInsumo,$db);
			$canInsumo = mysql_num_rows($resInsumo);
			while ($rowInsumo = mysql_fetch_assoc($resInsumo)) { ?>
			<tr>
				<td><?php echo $rowInsumo['id'] ?></td>
				<td><?php echo $rowInsumo['nombre']?></td>
				<td><?php echo $rowInsumo['descripcion'] ?></td>
				<td><?php 
					$idInsumo = $rowInsumo['id'];
					$sqlInsumoProducto = "SELECT p.activo as activo, p.nombre as prod, d.nombre as depto FROM insumoproducto i, producto p, ubicacionproducto u, departamentos d WHERE i.idinsumo = $idInsumo and i.idproducto = p.id and p.id = u.id and u.departamento = d.id";
					$resInsumoProducto = mysql_query($sqlInsumoProducto,$db);
					while ($rowInsumoProducto = mysql_fetch_assoc($resInsumoProducto)) {
						if ($rowInsumoProducto['activo'] == 0) {
							$color = "#FF0000";
						} else {
							$color = "#000000";
						}
						print("<font color='$color'> * ".$rowInsumoProducto['prod']." (".$rowInsumoProducto['depto'].")"."</font></br>");
					}
				?></td>	
				<td><?php echo $rowInsumo['puntopromedio'] ?></td>
				<td><?php echo $rowInsumo['puntopedido'] ?></td>
				<td><?php echo $rowInsumo['stockminimo'] ?></td>
				<td><input type="button" value="Modificar" onclick="location.href = 'modificarInsumo.php?id=<?php echo $rowInsumo['id'] ?>' "/></td>
			</tr>
	 <?php } ?>
    </tbody>
   </table>
   <table width="245" border="0">
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
		      <option value="<?php echo $canInsumo;?>">Todos</option>
		      </select>
		    </p>
			<p align="center"><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" align="right"/></p>
		  </form>	
		</div>
	</td>
      </tr>
  </table>
</div>
</body>
</html>

