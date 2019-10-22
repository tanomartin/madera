<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
include($_SERVER['DOCUMENT_ROOT']."/madera/lib/fechas.php");
$codigo = $_GET['codigo'];
$sqlConsultaPresta = "SELECT codigoprestador, nombre FROM prestadores WHERE codigoprestador = $codigo";
$resConsultaPresta = mysql_query($sqlConsultaPresta,$db);
$rowConsultaPresta = mysql_fetch_assoc($resConsultaPresta);

$sqlCabContrato = "SELECT c.*, prestadores.nombre, prestadores.codigoprestador
					FROM cabcontratoprestador c  
					LEFT JOIN cabcontratoprestador ON cabcontratoprestador.idcontrato = c.idcontratotercero
					LEFT JOIN prestadores ON prestadores.codigoprestador = cabcontratoprestador.codigoprestador
					WHERE c.codigoprestador = $codigo";
$resCabContrato = mysql_query($sqlCabContrato,$db);
$numCabContrato = mysql_num_rows($resCabContrato); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Contrato Prestador :.</title>

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
  <h3>Contrato Prestador</h3>
	  <table width="500" border="1">
        <tr>
          <td width="100"><div align="right"><strong>C&oacute;digo</strong></div></td>
          <td width="400"><div align="left"><strong><?php echo $rowConsultaPresta['codigoprestador']  ?></strong></div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>Nombre / Raz&oacute;n Social</strong></div></td>
          <td><div align="left">
              <div align="left"><?php echo $rowConsultaPresta['nombre'] ?></div>
          </div></td>
        </tr>
  </table>
  <h3>Contratos</h3>
  <?php if ($numCabContrato > 0) { ?>
	 <table style="text-align:center; width:80%" id="practicas" class="tablesorter" >
			<thead>
			  <tr>
				<th>Código</th>
				<th>Fecha Inicio</th>
				<th>Fecha Fin</th>
				<th>Practicas de Tercero</th>
				<th>Contratos Asociados [ID - Prestador (codigo)]</th>
				<th></th>
			  </tr>
			</thead>
			<tbody>
 	<?php while($rowCabContrato = mysql_fetch_array($resCabContrato)) { 
 				$sqlContraRelacionados = "SELECT c.*, p.nombre, p.codigoprestador FROM cabcontratoprestador c, prestadores p
											WHERE c.idcontratotercero = ".$rowCabContrato['idcontrato']." and 
												  c.codigoprestador = p.codigoprestador";
 				$resContraRelacionados = mysql_query($sqlContraRelacionados,$db);
 				$numContraRelacionados = mysql_num_rows($resContraRelacionados); ?>
			  <tr>
				<td><?php echo $rowCabContrato['idcontrato'];?></td>
				<td><?php echo invertirFecha($rowCabContrato['fechainicio']);?></td>
				<td><?php if($rowCabContrato['fechafin'] == NULL) { echo "-"; } else { echo invertirFecha($rowCabContrato['fechafin']); } ?></td>
				<td><?php if ($rowCabContrato['idcontratotercero'] == 0) { echo "-"; } else { echo $rowCabContrato['idcontratotercero']." - ".$rowCabContrato['nombre']. " (".$rowCabContrato['codigoprestador'].")"; } ?></td>
				<td><?php if ($numContraRelacionados == 0) { 
							echo "-"; 
						  } else {
							while($rowContraRelacionados = mysql_fetch_array($resContraRelacionados)) {
								echo $rowContraRelacionados['idcontrato']." - ".$rowContraRelacionados['nombre']." (".$rowContraRelacionados['codigoprestador'].")"."<hr>";
							}
 						  } ?>
 				</td>
				<td><input type="button" value="Practicas" name="verpracticas" id="verpracticas" onclick="location.href = 'consultaPracticasContrato.php?codigo=<?php echo $codigo?>&idcontrato=<?php echo $rowCabContrato['idcontrato']?>' " /></td>
			  </tr>
	<?php } ?>
			</tbody>
		  </table>
	<?php } else { 	?>
			<h3><font color="red"> ESTE PRESTADOR NO TIENE CONTRATO CARGADO </font></h3>
	<?php } ?>
</div>
</body>
</html>