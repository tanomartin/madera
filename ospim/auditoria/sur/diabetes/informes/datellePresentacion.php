<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
$sqlPresSSSFinalizadas = "SELECT *,
							DATE_FORMAT(d.fechacancelacion,'%d/%m/%Y') as fechacancelacion,
							DATE_FORMAT(d.fechadevolucion,'%d/%m/%Y') as fechadevolucion
							FROM diabetespresentacion d
							WHERE d.fechacancelacion is not null or d.fechadevolucion is not null order by id DESC";
$resPresSSSFinalizadas = mysql_query($sqlPresSSSFinalizadas,$db);
$canPresSSSFinalizadas = mysql_num_rows($resPresSSSFinalizadas); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Informes Diabetes :.</title>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script type="text/javascript" src="/madera/lib/jquery.js"></script>
<script type="text/javascript" src="/madera/lib/jquery.maskedinput.js" ></script>
<script type="text/javascript" src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script type="text/javascript" src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script type="text/javascript" src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script type="text/javascript">
	$(function() {
		$("#finalizadas")
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
		.tablesorterPager({
			container: $("#paginador")
		});
	});
	
	
	function informes(dire) {
		location.href = dire;
	}
</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = 'moduloInformes.php'" /></p>
  	<h2>Detalle Presentacion Diabetes S.S.S.</h2>
  	<h3>Presentaciones Finalizadas con Detalle Consolidado</h3>
  <?php if ($canPresSSSFinalizadas != 0) { ?>
  			<table style="text-align:center; width:1150px;" id="finalizadas" class="tablesorter">
  				<thead>
  					<tr>
	  					<th>ID</th>
	  					<th>Periodo</th>
	  					<th># Nue.</th>
		  				<th># Ant.</th>
	  					<th>Archivo</th>
	  					<th width="25%">Observacion</th>
	  					<th>Estado</th>
	  					<th>+ Info</th>
	  					<th></th>
  					</tr>
  				</thead>
  				<tbody>
  		 <?php  while ($rowPresSSSFinalizadas = mysql_fetch_assoc($resPresSSSFinalizadas)) { 
  					$sqlCantDetalle = "SELECT * FROM diabetespresentaciondetalle WHERE idpresentacion = ".$rowPresSSSFinalizadas['id'];
  					$resCantDetalle = mysql_query($sqlCantDetalle,$db);
  					$numCantDetalle = mysql_num_rows($resCantDetalle);
  				
  					$arrayArchivo = explode("/",$rowPresSSSFinalizadas['patharchivo']);
					$archivo = end($arrayArchivo);
					$arrayArchivo = explode("/",$rowPresSSSFinalizadas['pathsolicitud']); 
					$archivo .= "<br>".end($arrayArchivo); ?>
  					<tr>
  						<td><?php echo $rowPresSSSFinalizadas['id'] ?></td>
  						<td><?php echo $rowPresSSSFinalizadas['periodo']?></td>
  						<td><?php echo $rowPresSSSFinalizadas['cantbenenuevos']?></td>
			  			<td><?php echo $rowPresSSSFinalizadas['cantbeneanteriores']?></td>
  						<td><?php echo $archivo?></td>
  						<td><?php echo $rowPresSSSFinalizadas['observacion']?></td>
  						 <?php  $estado = "";
			  					$color = "";
			  					if ($rowPresSSSFinalizadas['fechacancelacion'] != NULL) {
			  						$color = "red";
			  						$estado = "CANCELADA<br>".$rowPresSSSFinalizadas['fechacancelacion'];
			  						$info = $rowPresSSSFinalizadas['motivocancelacion'];
			  					}
			  					if ($rowPresSSSFinalizadas['fechadevolucion'] != NULL) {
			  						$color = "blue";
			  						$estado = "FINALIZADA<br>".$rowPresSSSFinalizadas['fechadevolucion'];
			  						$info = "<b>EXP:</b> ".$rowPresSSSFinalizadas['nroexpediente']."<br><b>MONTO:</b> $ ".$rowPresSSSFinalizadas['monto']."<br><b>CANT: </b>".$rowPresSSSFinalizadas['cantbenesolicitados'];
			  					} ?>
			  			<td style="color: <?php echo $color ?>"><?php echo $estado."<br>"; ?></td>
			  			<td><?php echo $info."<br>" ?></td>
			  			<td>
			  			<?php if ($rowPresSSSFinalizadas['fechadevolucion'] != NULL) { 
			  					if ($numCantDetalle == $rowPresSSSFinalizadas['cantbenesolicitados']) { ?>
			  						<input type="button" value="DETALLE" onclick="location.href='detallePresentacionExcel.php?id=<?php echo $rowPresSSSFinalizadas['id'] ?>'"/>
			  		  	  <?php } 
			  				  } ?>	
			  			</td>
  					</tr>
  		  <?php } ?>
  				</tbody>
  			</table>
  			<table class="nover" align="center" width="245" border="0">
				<tr>
					<td width="239">
						<div id="paginador" class="pager">
							<form>
								<p align="center">
								<img src="../../img/first.png" width="16" height="16" class="first"/> <img src="../../img/prev.png" width="16" height="16" class="prev"/>
								<input name="text" type="text" class="pagedisplay" style="background:#CCCCCC; text-align:center" size="8" readonly="readonly"/>
								<img src="../../img/next.png" width="16" height="16" class="next"/> <img src="../../img/last.png" width="16" height="16" class="last"/>
								</p>
								<p align="center">
									<select name="select" class="pagesize">
									<option selected="selected" value="10">10 por pagina</option>
									<option value="20">20 por pagina</option>
									<option value="30">30 por pagina</option>
									<option value="<?php echo $canPresSSSFinalizadas;?>">Todos</option>
									</select>
								</p>
							</form>	
						</div>
					</td>
				</tr>
			</table>
 <?php } ?>
</div>
</body>
</html>