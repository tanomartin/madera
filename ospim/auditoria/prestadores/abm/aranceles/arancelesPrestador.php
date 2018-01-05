<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php");
include($libPath."funcionespracticas.php");

$codigo = $_GET['codigo'];
$sqlConsultaPresta = "SELECT codigoprestador, nombre FROM prestadores WHERE codigoprestador = $codigo";
$resConsultaPresta = mysql_query($sqlConsultaPresta,$db);
$rowConsultaPresta = mysql_fetch_assoc($resConsultaPresta);

$sqlAranceles = "SELECT c.* FROM aranceles c  WHERE c.codigoprestador = $codigo";
$resAranceles = mysql_query($sqlAranceles,$db);
$numAranceles = mysql_num_rows($resAranceles);

$today = date("Y-m-d");
$sqlArancelesAbiertos = "SELECT c.* FROM aranceles c  WHERE c.codigoprestador = $codigo and (c.fechafin is null or c.fechafin > '$today')";
$resArancelesAbiertos = mysql_query($sqlArancelesAbiertos,$db);
$numArancelesAbiertos = mysql_num_rows($resArancelesAbiertos);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: ABM Aranceles :.</title>
<script src="/madera/lib/jquery.js"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script type="text/javascript">


	$(function() {
		$("#contratos")
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
  <p><input type="button" name="volver" value="Volver" onclick="location.href = '../prestador.php?codigo=<?php echo $codigo ?>'" /></p>
  <h3>ABM de Aranceles </h3>
  <table width="500" border="1">
    <tr>
      <td width="163"><div align="right"><strong>C&oacute;digo</strong></div></td>
      <td width="321"><div align="left"><strong><?php echo $rowConsultaPresta['codigoprestador']  ?></strong></div></td>
    </tr>
    <tr>
      <td><div align="right"><strong>Raz&oacute;n Social</strong></div></td>
      <td><div align="left"><?php echo $rowConsultaPresta['nombre'] ?></div></td>
    </tr>
  </table>
   <h3>Aranceles</h3>
	 <?php 
		if ($numArancelesAbiertos == 0) { ?>
			<p><input type="button" name="nuevoArancel" id="nuevoArancel" value="Nuevo Arancel" onclick="location.href='nuevoArancel.php?codigo=<?php echo $codigo ?>'"/></p>
  <?php } 
		if ($numAranceles > 0) { ?>
        <table style="text-align:center; width:800px" id="contratos" class="tablesorter" >
          <thead>
            <tr>
             	<th>C&oacute;digo</th>
				<th>Fecha Inicio</th>
				<th>Fecha Fin</th>
				<th>Monto</th>
				<th></th>
            </tr>
          </thead>
          <tbody>
            <?php
			while($rowAranceles = mysql_fetch_array($resAranceles)) { ?>
				<tr>
					<td><?php echo $rowAranceles['id'];?></td>
					<td><?php echo invertirFecha($rowAranceles['fechainicio']);?></td>
					<td><?php if($rowAranceles['fechafin'] == NULL) {
								  echo "-";
							  } else {
							   	  echo invertirFecha($rowAranceles['fechafin']);
							  }?></td>
					<td><?php echo number_format($rowAranceles['monto'],2,',','.') ?></td>
					<td><?php if ($rowAranceles['fechafin'] == NULL || $rowAranceles['fechafin'] > $today ) { ?> 
								<input type="button" value="Modificar Arancel" name="modifarancel" id="modifarancel" onclick="location.href='modificarArancel.php?id=<?php echo $rowAranceles['id'] ?>&codigo=<?php echo $codigo ?>'"/>
						<?php } ?>
					</td>
				</tr>
         <?php } ?>
          </tbody>
        </table> 
   <?php } else { 	?>
        	<h3><font color="red"> ESTE PRESTADOR NO TIENE ARANCELES CARGADO </font></h3>
  <?php  } ?>	
</div>
</body>
</html>