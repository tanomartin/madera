<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php"); 
include($libPath."fechas.php"); 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Valores al Cobro Realizados :.</title>
<style type="text/css" media="print">
.nover {display:none}
</style>
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
			headers:{3:{sorter:false, filter:false}, 4:{sorter:false, filter:false}},
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
	
function abrirDetalle(dire) {
	a= window.open(dire,"DetalleValor",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=10, left=10");
}
</script>
</head>

<body bgcolor="#B2A274">
<div align="center">
  <p><input class="nover" type="button" name="volver" value="Volver" onclick="location.href = 'menuValores.php'" /></p>
  <h3>Valores al Cobro Realizados </h3>
  <table class="tablesorter" id="listado" style="width:600px; font-size:14px">
	  <thead>
		<tr>
		  <th>Fecha Desposito / Generacion</th>
		  <th>Nro. Cheque</th>
		  <th>Fecha Cheque</th>
		  <th>Banco</th>
		  <th>Acciones</th>
		</tr>
	 </thead>
	 <tbody>
		<?php	
			$sqlValores = "SELECT fechadepositousimra, DATE_FORMAT(fechadepositousimra,'%d/%m/%Y') as fechadepositousimraver, chequenrousimra, DATE_FORMAT(chequefechausimra,'%d/%m/%Y') as chequefechausimra, chequebancousimra FROM valoresalcobrousimra WHERE chequenrousimra != '' GROUP BY chequenrousimra, chequefechausimra, chequebancousimra ORDER BY fechadepositousimra DESC";
			$resValores = mysql_query($sqlValores,$db); 
			$canValores = mysql_num_rows($resValores);
			while ($rowValores = mysql_fetch_array($resValores)) { ?>
			<tr align="center">
					<td><?php echo $rowValores['fechadepositousimraver'] ?></td>
					<td><?php echo $rowValores['chequenrousimra'] ?></td>
					<td><?php echo $rowValores['chequefechausimra'] ?></td>
					<td><?php echo $rowValores['chequebancousimra']?></td>
					<td><input type="button" onclick="javascript:location.href='detalleValorAlCobro.php?nrocheque=<?php echo $rowValores['chequenrousimra'] ?>&feccheque=<?php echo $rowValores['chequefechausimra'] ?>&fecdep=<?php echo $rowValores['fechadepositousimraver'] ?>'" value="Detalle" /></td>
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
		      <option value="<?php echo $canValores;?>">Todos</option>
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