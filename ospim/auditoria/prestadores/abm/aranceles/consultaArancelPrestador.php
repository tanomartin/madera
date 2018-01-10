<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
include($_SERVER['DOCUMENT_ROOT']."/madera/lib/fechas.php");
$codigo = $_GET['codigo'];
$sqlConsultaPresta = "SELECT codigoprestador, nombre FROM prestadores WHERE codigoprestador = $codigo";
$resConsultaPresta = mysql_query($sqlConsultaPresta,$db);
$rowConsultaPresta = mysql_fetch_assoc($resConsultaPresta);

$sqlAranceles = "SELECT c.* FROM aranceles c  WHERE c.codigoprestador = $codigo";
$resAranceles = mysql_query($sqlAranceles,$db);
$numAranceles = mysql_num_rows($resAranceles);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Aranceles Prestador :.</title>
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
		$("#practicas")
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
  <p><strong>Aranceles Prestador</strong></p>
	  <table width="500" border="1">
        <tr>
          <td width="163"><div align="right"><strong>C&oacute;digo</strong></div></td>
          <td width="321"><div align="left"><strong><?php echo $rowConsultaPresta['codigoprestador']  ?></strong></div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>Nombre / Raz&oacute;n Social</strong></div></td>
          <td><div align="left">
              <div align="left"><?php echo $rowConsultaPresta['nombre'] ?></div>
          </div></td>
        </tr>
  </table>
  <?php if ($numAranceles > 0) { ?>
		<p><strong>Aranceles</strong></p>
		   <table style="text-align:center; width:400px" id="practicas" class="tablesorter" >
				<thead>
				  <tr>
					<th>C&oacute;digo</th>
					<th>Fecha Inicio</th>
					<th>Fecha Fin</th>
					<th>Monto</th>
				  </tr>
				</thead>
				<tbody>
				  <?php
				while($rowArancel = mysql_fetch_array($resAranceles)) { ?>
				  <tr>
					<td><?php echo $rowArancel['id'];?></td>
					<td><?php echo invertirFecha($rowArancel['fechainicio']);?></td>
					<td><?php if($rowArancel['fechafin'] == NULL) {
								  echo "-";
							  } else {
							   	  echo invertirFecha($rowArancel['fechafin']);
							  }
							 ?>
					</td>
					<td><?php echo number_format($rowArancel['monto'],2,',','.')  ?></td>
				  </tr>
			<?php } ?>
			</tbody>
		  </table>
	<?php } else { ?>	
	 		<p><font style='color:#FF0000'><b> ESTE PRESTADOR NO TIENE ARANCEL CARGADO </b></font></p>
	<?php } ?>
</div>
</body>
</html>