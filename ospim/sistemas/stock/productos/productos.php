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
<script language="javascript" type="text/javascript">
	$(function() {
		$("#listado")
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
</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="reset" name="volver" value="Volver" onclick="location.href = '../menuStock.php'" /></p>
  <h3>Listado de Productos </h3>
  <input name="nuevo" type="button" id="nuevo" onclick="location.href = 'nuevoProducto.php'"  value="Nuevo" />
  <table class="tablesorter" id="listado" style="width:80%; font-size:14px">
	  <thead>
		<tr>
		  <th>Codigo</th>
		  <th>Nombre</th>
		  <th>Descripcion</th>
		  <th class="filter-select" data-placeholder="Seleccion Estado">Activo</th>
		  <th class="filter-select" data-placeholder="Seleccion Seguro">Seguro</th>
		  <th class="filter-select" data-placeholder="Seleccione Ubicacion">Ubicacion</th>
		  <th>Usuario</th>
		  <th>Acciones</th>
		</tr>
	 </thead>
	 <tbody>
		<?php	
			$sqlProd = "SELECT p.*, u.* ,usuarios.nombre as usuario, d.nombre as deptos
						  FROM stockproducto p, departamentos d, stockubicacionproducto u 
						  LEFT OUTER JOIN usuarios on u.idusuario = usuarios.id
						  WHERE u.id = p.id and u.departamento = d.id order by p.id";
			$resProd = mysql_query($sqlProd,$db);
			$canProd = mysql_num_rows($resProd);
			while ($rowProd = mysql_fetch_assoc($resProd)) { ?>
			<tr align="center">
					<td><?php echo $rowProd['id'] ?></td>
					<td><?php echo $rowProd['nombre']?></td>
					<td><?php echo $rowProd['descripcion'] ?></td>
					<td><?php if ($rowProd['activo'] == 1) { echo "SI"; } else { echo "NO"; } ?></td>
					<td><?php if ($rowProd['seguro'] == 1) { echo "SI"; } else { echo "NO"; } ?></td>
					<td><?php echo $rowProd['deptos'] ?></td>
					<td><?php echo $rowProd['usuario'] ?></td>
					<td><input type="button" value="Modificar" onclick="location.href = 'modificarProducto.php?id=<?php echo $rowProd['id'] ?>' "/></td>
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
		      <option value="<?php echo $canProd;?>">Todos</option>
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

