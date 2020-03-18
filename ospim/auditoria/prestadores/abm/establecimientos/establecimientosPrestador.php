<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
$codigo = $_GET['codigo'];
$sqlConsultaPresta = "SELECT codigoprestador, nombre FROM prestadores WHERE codigoprestador = $codigo";
$resConsultaPresta = mysql_query($sqlConsultaPresta,$db);
$rowConsultaPresta = mysql_fetch_assoc($resConsultaPresta); 

$sqlEstable = "SELECT codigo, nombre FROM establecimientos WHERE codigoprestador = $codigo";
$resEstable = mysql_query($sqlEstable,$db);
$numEstable  = mysql_num_rows($resEstable); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Establecimientos Prestador :.</title>
<style type="text/css" media="print">
.nover {display:none}
</style>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">

	$(function() {
		$("#establecimientos")
		.tablesorter({
			theme: 'blue', 
			widthFixed: true, 
			widgets: ["zebra", "filter"], 
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
	});
	
</script>
</head>
<body bgcolor="#CCCCCC">
<div align="center">
  	<h3>Profesionales del  Prestador</h3>
	<table width="500" border="1" style="margin-bottom: 20px">
        <tr>
          <td width="163" align="right"><b>Código</b></td>
          <td width="321" align="left"><b><?php echo $rowConsultaPresta['codigoprestador']  ?></b></td>
        </tr>
        <tr>
          <td align="right"><b>Razón Social</b></td>
          <td align="left"><?php echo $rowConsultaPresta['nombre'] ?></td>
        </tr>
  </table>
  <?php if ($numEstable > 0) {  ?>
			<table style="text-align:center; width:600px" id="establecimientos" class="tablesorter" >
				<thead>
					<tr>
						<th>Código</th>
						<th>Nombre</th>
					</tr>
				</thead>
				<tbody>
				 <?php while($rowEstable= mysql_fetch_array($resEstable)) { ?>
						  <tr>
							<td><?php echo $rowEstable['codigo'];?></td>
							<td><?php echo $rowEstable['nombre'];?></td>
						  </tr>
				 <?php } ?>
				</tbody>
		    </table>
			<p><input type="button" class="nover" name="imprimir" value="Imprimir" onclick="window.print();" /></p>	  
  <?php } else { ?>
  			<h3 style='color:#FF0000'>ESTE PRESTADOR NO TIENE ESTABLECIMIENTOS CARGADO </h3> 
  <?php } ?>
</div>
</body>
</html>