<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Juzgados :.</title>
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
			widgets: ["zebra","filter"], 
			headers:{3:{sorter:false, filter: false}},
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
  <p><input type="button" name="volver" value="Volver" onclick="location.href = '../menuConfiguracion.php'" /></p>
  <h3>Juzgados</h3>
  <p><input type="button" name="nuevo" value="Nuevo" id="nuevo" onclick="location.href = 'nuevoJuzgado.php'" /></p>
  <table class="tablesorter" id="listado" style="width:950px; font-size:14px">
	  <thead>
		<tr>
		  <th data-placeholder="Inserte Código">Codigo</th>
		  <th data-placeholder="Inserte Denominación">Denominación</th>
		  <th class="filter-select" data-placeholder="Selccione Fuero">Fueros</th>
		  <th>Acciones</th>
		</tr>
	 </thead>
	 <tbody>
		<?php	
			$sqlJuzgados = "select * from juzgados";
			$resJuzgados = mysql_query($sqlJuzgados,$db); 
			$canJuzgados = mysql_num_rows($resJuzgados);
			while ($rowJuzgados = mysql_fetch_array($resJuzgados)) { ?>
			<tr align="center">
					<td><?php echo $rowJuzgados['codigojuzgado'] ?></td>
					<td><?php echo $rowJuzgados['denominacion']?></td>
					<td><?php echo $rowJuzgados['fueros']?></td>
					<td><input type="button" value="Modificar" onclick="location.href = 'modificarJuzgado.php?codigo=<?php echo $rowJuzgados['codigojuzgado'] ?>'"/></td>
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
			  <img src="../img/first.png" width="16" height="16" class="first"/> <img src="../img/prev.png" width="16" height="16" class="prev"/>
			  <input name="text" type="text" class="pagedisplay" style="background:#CCCCCC; text-align:center" size="8" readonly="readonly"/>
		    <img src="../img/next.png" width="16" height="16" class="next"/> <img src="../img/last.png" width="16" height="16" class="last"/>
		    <select name="select" class="pagesize">
		      <option selected="selected" value="10">10 por pagina</option>
		      <option value="20">20 por pagina</option>
		      <option value="30">30 por pagina</option>
		      <option value="<?php echo $canJuzgados;?>">Todos</option>
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
