<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
$sqlEmails = "SELECT u.nombre, d.nombre as depto, e.* FROM usuarios u, departamentos d, emails e WHERE u.id = e.idusuario and u.departamento = d.id";
$resEmails = mysql_query($sqlEmails,$db);
$canEmails = mysql_num_rows($resEmails); ?>

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
			headers:{4:{sorter:false, filter: false}, 5:{sorter:false, filter: false}},
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

function controlEliminacion(idEmail) {
	var r = confirm("Debe confirmar la eliminacion del correo electronico");
	if (r == true) {
		window.location="eliminarEmail.php?id="+idEmail;
	} 
}
	
</script>
<style type="text/css" media="print">
.nover {display:none}
</style>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="reset" name="volver" value="Volver" onclick="location.href = 'menuUsuarios.php'" /></p>
  <h3>Listado de Emails </h3>
  <input name="nuevo" type="button" id="nuevo" onclick="location.href = 'nuevoEmail.php'"  value="Nuevo" />
  <table class="tablesorter" id="listado" style="width:800px; font-size:14px">
	  <thead>
		<tr>
		  <th>Id</th>
		  <th>Nombre</th>
		  <th class="filter-select" data-placeholder="Seleccion Sector">Sector</th>
		  <th>Email</th>
		  <th>Password</th>
		  <th>Acciones</th>
		</tr>
	 </thead>
	 <tbody>
	<?php	while ($rowEmails = mysql_fetch_assoc($resEmails)) { ?>
			<tr align="center">
				<td><?php echo $rowEmails['id'] ?></td>
				<td><?php echo $rowEmails['nombre']?></td>
				<td><?php echo $rowEmails['depto'] ?></td>
				<td><?php echo $rowEmails['email'] ?></td>
				<td><?php echo $rowEmails['password'] ?></td>
				<td>
					<input type="button" value="Modificar" onclick="location.href = 'modificarEmail.php?id=<?php echo $rowEmails['id'] ?>'" />
					<input type="button" value="Eliminar" onclick="controlEliminacion('<?php echo $rowEmails['id'] ?>')" />
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
		      <option value="<?php echo $canEmails;?>">Todos</option>
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

