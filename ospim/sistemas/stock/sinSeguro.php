<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Productos Seguro :.</title>


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

<style type="text/css" media="print">
<!--
.nover {display:none}
-->

</style>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p>
    <input class="nover" type="reset" name="volver" value="Volver" onclick="location.href = 'moduloSeguro.php'" />
</p>
  <p><span class="Estilo1">Productos Sin Poliza</span></p>
  <table id="listado" style="width:800px; font-size:14px" border="1">
	  <thead>
		<tr>
		  <th>Nombre</th>
		  <th>Descripcion</th>
		  <th>Fecha Inicio</th>
		  <th>Nro de Serie</th>
		  <th>Valor Original</th>
	    </tr>
	 </thead>
	 <tbody>
	 	
		<?php	
			$sqlProd = "SELECT * FROM producto p, ubicacionproducto u WHERE p.activo = 1 and p.id = u.id and u.pertenencia = 'O' and p.numeropoliza is null";
			$resProd = mysql_query($sqlProd,$db);
			$canProd = mysql_num_rows($resProd);
			while ($rowProd = mysql_fetch_assoc($resProd)) { ?>		
			<tr align="center">
					<td width="100"><?php echo $rowProd['nombre']?></td>
					<td><?php echo $rowProd['descripcion'] ?></td>
					<td width="100"><?php echo $rowProd['fechainicio'] ?></td>
					<td><?php echo $rowProd['numeroserie'] ?></td>
					<td width="100"><?php echo "$ ".$rowProd['valororiginal'] ?></td>
		</tr>
	 <?php } ?>
	 
    </tbody>
  </table>
   <table width="245" border="0" class="nover">
     <tr>
       <td width="239"><div id="paginador" class="pager">
           <form>
             <p align="center"> <img src="img/first.png" width="16" height="16" class="first"/> <img src="img/prev.png" width="16" height="16" class="prev"/>
                 <input name="text" type="text" class="pagedisplay" style="background:#CCCCCC; text-align:center" size="8" readonly="readonly"/>
                 <img src="img/next.png" width="16" height="16" class="next"/> <img src="img/last.png" width="16" height="16" class="last"/>
                 <select name="select" class="pagesize">
                   <option selected="selected" value="10">10 por pagina</option>
                   <option value="20">20 por pagina</option>
                   <option value="30">30 por pagina</option>
                   <option value="<?php echo $canProd;?>">Todos</option>
                 </select>
             </p>
             <p align="center">
               <input class="nover" type="button" name="imprimir2" value="Imprimir" onclick="window.print();" align="right"/>
             </p>
           </form>
       </div></td>
     </tr>
   </table>
   </div>
</body>
</html>

