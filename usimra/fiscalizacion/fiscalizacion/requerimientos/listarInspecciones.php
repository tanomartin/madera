<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php"); 

$sqlReque = "SELECT * from reqfiscalizusimra r, inspecfiscalizusimra i 
		where r.procesoasignado = 2 and r.requerimientoanulado = 0 and r.nrorequerimiento = i.nrorequerimiento 
		order by r.nrorequerimiento DESC";
$resReque = mysql_query($sqlReque,$db);
$canReque = mysql_num_rows($resReque);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado de Requerimientos en Inpsección:.</title>

<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
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
			headers:{5:{sorter:false}},
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

function verInspeccion(reque) {
	var dire = 'consultaInspeccion.php?nroreq='+reque;
	window.open(dire, "", "directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=800, height=400");
}

</script>
</head>

<body bgcolor="#B2A274">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = '../menuFiscalizaciones.php'"/></p>
  	<p class="Estilo2">Listado de  Requerimiento en Inspecci&oacute;n  </p>
	<table class="tablesorter" id="listado" style="width:1000px; font-size:14px">
		<thead>
			  <tr>
				<th>Número</th>
				<th>Origen</th>
				<th>Solicitante</th>
				<th>Motivo</th>
				<th>Cuit</th>
				<th>Datos Inpección</th>
			  </tr>
		</thead>
		<tbody>
		  <?php while($rowReque = mysql_fetch_array($resReque)) { 
		  			if ($rowReque['origenrequerimiento'] == 1) {
						$origen = "Fiscalización";
					}
					if ($rowReque['origenrequerimiento'] == 2) {
						$origen = "Afiliaciones";
					}
					if ($rowReque['origenrequerimiento'] == 3) {
						$origen = "Prestación";
					} 
			?>
				  <tr align="center">
						<td><?php echo $rowReque['nrorequerimiento'] ?></td>
						<td><?php echo $origen ?></td>   
						<td><?php echo $rowReque['solicitarequerimiento'] ?></td>   
						<td><?php echo $rowReque['motivorequerimiento'] ?></td>   
						<td><?php echo $rowReque['cuit'] ?></td>   
						<?php if ($rowReque['inspeccionefectuada'] == 0) { ?>
							<td><input type="button" value="Modificar" onclick="location.href='datosInspeccion.php?nroreq=<?php echo $rowReque['nrorequerimiento'] ?>'" /></td>   
				  	    <?php } else {  ?>
				  	    	<td><input type="button" value="Consultar" onclick="verInspeccion('<?php echo $rowReque['nrorequerimiento'] ?>')" /></td>   
				  	    <?php } ?>
				  </tr>
		<?php }?>
     	</tbody>
	</table>
	<table width="245" border="0">
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
		      <option value="<?php echo $canReque;?>">Todos</option>
		      </select>
		    </p>
		  </form>	
		</div>
	</td>
      </tr>
  </table>
</div>
</body>
</html>