<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php");

$codigo = $_GET['codigo'];
$sqlConsultaPresta = "SELECT codigoprestador, nombre FROM prestadores WHERE codigoprestador = $codigo";
$resConsultaPresta = mysql_query($sqlConsultaPresta,$db);
$rowConsultaPresta = mysql_fetch_assoc($resConsultaPresta); 

$sqlEstable = "SELECT codigo, nombre FROM establecimientos WHERE codigoprestador = $codigo";
$resEstable = mysql_query($sqlEstable,$db);
$numEstable = mysql_num_rows($resEstable); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modificar Establecimientos :.</title>
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
			headers:{2:{sorter:false}},
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
  <p><input type="button" name="volver" value="Volver" onclick="location.href = '../prestador.php?codigo=<?php echo $codigo ?>'" /></p>
  <h3>Modificación de Establecimientos </h3>
  <table width="500" border="1">
    <tr>
      <td width="163" align="right"><b>Código</b></td>
      <td width="321" align="left"><b><?php echo $rowConsultaPresta['codigoprestador']  ?></b></td>
    </tr>
    <tr>
      <td align="right"><b>Raz&oacute;n Social</b></td>
      <td align="left"><?php echo $rowConsultaPresta['nombre'] ?></td>
    </tr>
  </table>
  <p><input type="button" name="nuevo" value="Nuevo Establecimientos" onclick="location.href = 'nuevoEstablecimientos.php?codigopresta=<?php echo $codigo ?>' " /></p> 
<?php if ($numEstable > 0) { ?>
       <table style="text-align:center; width:600px" id="establecimientos" class="tablesorter" >
			<thead>
			  <tr>
				<th>Código</th>
				<th>Nombre</th>
				<th></th>
			  </tr>
			</thead>
          <tbody>
      <?php while($rowEstable= mysql_fetch_array($resEstable)) { ?>
			  <tr>
				<td><?php echo $rowEstable['codigo'];?></td>
				<td><?php echo $rowEstable['nombre'];?></td>
				<td> <input class="nover" name="ficha" type="button" value="Ver Ficha"  onclick="location.href = 'establecimiento.php?codigo=<?php echo $rowEstable['codigo']?>&codigopresta=<?php echo $codigo ?>'" /> </td>
			  </tr>
	  <?php } ?>
		  </tbody>
        </table>
<?php } else { ?>	
		  <h3 style='color:#000099'>ESTE PRESTADOR NO TIENE ESTABLECIMIENTOS CARGADO </h3>
<?php } ?>
</div>
</body>
</html>