<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php");
include($libPath."funcionespracticas.php");

$codigo = $_GET['codigo'];
$sqlConsultaPresta = "SELECT codigoprestador, nombre FROM prestadores WHERE codigoprestador = $codigo";
$resConsultaPresta = mysql_query($sqlConsultaPresta,$db);
$rowConsultaPresta = mysql_fetch_assoc($resConsultaPresta);

$sqlCabContrato = "SELECT c.* FROM cabcontratoprestador c  WHERE c.codigoprestador = $codigo";
$resCabContrato = mysql_query($sqlCabContrato,$db);
$numCabContrato = mysql_num_rows($resCabContrato);

$today = date("Y-m-d");
$sqlCabContratoAbiertos = "SELECT c.* FROM cabcontratoprestador c  WHERE c.codigoprestador = $codigo and (c.fechafin is null or c.fechafin > '$today')";
$resCabContratoAbiertos = mysql_query($sqlCabContratoAbiertos,$db);
$numCabContratoAbiertos = mysql_num_rows($resCabContratoAbiertos);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: ABM Contrato :.</title>

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
  <h3>ABM de Contratos </h3>
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
  <h3>Contratos</h3>
  <?php if ($numCabContratoAbiertos == 0) { ?>
			<p><input type="button" name="nuevoContrato" id="nuevoContrato" value="Nuevo Contrato" onclick="location.href='nuevoContrato.php?codigo=<?php echo $codigo ?>'"/></p>
  <?php } 
        if ($numCabContrato > 0) { ?>
        <table style="text-align:center; width:800px" id="contratos" class="tablesorter" >
          <thead>
            <tr>
             	<th>C&oacute;digo</th>
				<th>Fecha Inicio</th>
				<th>Fecha Fin</th>
				<th></th>
            </tr>
          </thead>
          <tbody>
            <?php
			while($rowCabContrato = mysql_fetch_array($resCabContrato)) { ?>
				<tr>
				<td><?php echo $rowCabContrato['idcontrato'];?></td>
				<td><?php echo invertirFecha($rowCabContrato['fechainicio']);?></td>
				<td><?php if($rowCabContrato['fechafin'] == NULL) {
							  echo "-";
						  } else {
						   	  echo invertirFecha($rowCabContrato['fechafin']);
						  }?></td>
				<td><?php if ($rowCabContrato['fechafin'] == NULL || $rowCabContrato['fechafin'] > $today ) { ?> 
							<input type="button" value="Modificar Practicas" name="modifpracticas" id="modifpracticas" onclick="location.href='modificarPracticasContrato.php?idcontrato=<?php echo $rowCabContrato['idcontrato'] ?>&codigo=<?php echo $codigo ?>'"/> -
							<input type="button" value="Modificar Contrato" name="modifcontrato" id="modifcontrato" onclick="location.href='modificarContrato.php?idcontrato=<?php echo $rowCabContrato['idcontrato'] ?>&codigo=<?php echo $codigo ?>'"/> -
							<input type="button" value="Duplicar Contrato con Aumento %" name="aumentocontrato" id="modifcontrato" onclick="location.href='aumentoPorcentaje.php?idcontrato=<?php echo $rowCabContrato['idcontrato'] ?>&codigo=<?php echo $codigo ?>'"/>
					<?php } else { ?>
							<input type="button" value="Ver Practicas" name="verpracticas" id="verpracticas" onclick="location.href = 'consultaPracticasContrato.php?codigo=<?php echo $codigo?>&idcontrato=<?php echo $rowCabContrato['idcontrato']?>' "/>
					<?php }  ?>	
				</td>
				</tr>
         <?php } ?>
          </tbody>
        </table>
        <?php } else { ?> 	
        			<h3><font color="red"> ESTE PRESTADOR NO TIENE CONTRATO CARGADO</font></h3>
        <?php } ?>	
</div>
</body>
</html>