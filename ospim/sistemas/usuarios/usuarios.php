<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Usuarios :.</title>


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
			headers:{3:{sorter:false, filter: false}, 4:{sorter:false, filter: false}, 5:{sorter:false, filter: false}, 6:{sorter:false, filter: false}, 7:{sorter:false, filter: false}},
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
    <input type="reset" name="volver" value="Volver" onclick="location.href = 'menuUsuarios.php'" />
</p>
  <p><span class="Estilo1">Listado de Usuarios </span></p>
  <input name="nuevo" type="button" id="nuevo" onclick="location.href = 'nuevoUsuario.php'"  value="Nuevo" />
  <table class="tablesorter" id="listado" style="width:800px; font-size:14px">
	  <thead>
		<tr>
		  <th>Id</th>
		  <th>Nombre</th>
		  <th class="filter-select" data-placeholder="Seleccion Sector">Sector</th>
		  <th>Usuario Win</th>
		  <th>Pass Win</th>
		  <th>Usuario Sistema</th>
		  <th>Pass Sistema</th>
		  <th>Acciones</th>
		</tr>
	 </thead>
	 <tbody>
		<?php	
			$sqlUsuario = "SELECT u.*, d.nombre as depto FROM usuarios u, departamentos d WHERE u.departamento = d.id";
			$resUsuario = mysql_query($sqlUsuario,$db);
			$canUsuario = mysql_num_rows($resUsuario);
			while ($rowUsuario = mysql_fetch_assoc($resUsuario)) { ?>
			<tr align="center">
					<td><?php echo $rowUsuario['id'] ?></td>
					<td><?php echo $rowUsuario['nombre']?></td>
					<td><?php echo $rowUsuario['depto'] ?></td>
					<td><?php echo $rowUsuario['usuariowin'] ?></td>
					<td><?php echo $rowUsuario['passwin'] ?></td>
					<td><?php echo $rowUsuario['usuariosistema'] ?></td>
					<td><?php echo $rowUsuario['passsistema'] ?></td>
					<td>
						<input type="button" value="+Info" onclick="location.href = 'fichaUsuario.php?id=<?php echo $rowUsuario['id'] ?>'" />
						<input type="button" value="Modificar" onclick="location.href = 'modificarUsuario.php?id=<?php echo $rowUsuario['id'] ?>'" />
						<input type="button" value="Eliminar" onclick="location.href = 'eliminarUsuario.php?id=<?php echo $rowUsuario['id'] ?>'" />
					</td>
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
		      <option value="<?php echo $canUsuario;?>">Todos</option>
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

