<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$sqlPresSSSFinalizadas = "SELECT *,
							DATE_FORMAT(d.fechacancelacion,'%d/%m/%Y') as fechacancelacion, 
							DATE_FORMAT(d.fechapresentacion,'%d/%m/%Y') as fechapresentacion,
							DATE_FORMAT(d.fechadevolucion,'%d/%m/%Y') as fechadevolucion
							FROM diabetespresentacion d
							WHERE d.fechacancelacion is not null or d.fechadevolucion is not null order by id DESC";
$resPresSSSFinalizadas = mysql_query($sqlPresSSSFinalizadas,$db);
$canPresSSSFinalizadas = mysql_num_rows($resPresSSSFinalizadas);

$sqlPresSSSActiva = "SELECT d.*,
						DATE_FORMAT(d.fechapresentacion,'%d/%m/%Y') as fechapresentacion,
						DATE_FORMAT(d.fechasolicitud,'%d/%m/%Y') as fechasolicitud
						FROM diabetespresentacion d
						WHERE d.fechacancelacion is null and d.fechadevolucion is null order by id DESC";
$resPresSSSActiva = mysql_query($sqlPresSSSActiva,$db);
$canPresSSSActiva = mysql_num_rows($resPresSSSActiva);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js" type="text/javascript"></script> 
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

</script>
<link rel="stylesheet" href="/madera/lib/tablas.css"/>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modulo Diabetes Presentacion S.S.S. :.</title>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = '../moduloDiabetes.php'" /></p>
  	<h2>M�dulo Presentacion Diabetes S.S.S.</h2>
  	<h3>Presentacion Activa</h3>
  	<?php if ($canPresSSSActiva != 0) { ?>
	  		<div class="grilla">
	  			<table style="width:1000px">
	  				<thead>
	  					<tr>
		  					<th>ID</th>
		  					<th>Periodo</th>
		  					<th># Bene</th>
		  					<th>Archivo</th>
		  					<th>Estado</th>
		  					<th>Acciones</th>
	  					</tr>
	  				</thead>
					<tbody>
			<?php  while ($rowPresSSSActiva = mysql_fetch_assoc($resPresSSSActiva)) { 
						$arrayArchivo = explode("/",$rowPresSSSActiva['patharchivo']);
						$archivo = end($arrayArchivo);?>
			  			<tr>
			  				<td><?php echo $rowPresSSSActiva['id']?></td>
			  				<td><?php echo $rowPresSSSActiva['periodo']?></td>
			  				<td><?php echo $rowPresSSSActiva['cantidadbeneficiario']?></td>
			  				<td><?php echo $archivo ?></td>
			  			  <?php $estado = "SIN PRESENTAR";
			  					if ($rowPresSSSActiva['fechasolicitud'] != NULL) {
			  						$estado = "SOLICITADA <br>FEC: ".$rowPresSSSActiva['fechasolicitud']."<br>SOL.: ".$rowPresSSSActiva['nrosolicitud'];
			  						if ($rowPresSSSActiva['fechapresentacion'] != NULL) {
			  							$estado = "PRESENTADA <br>FEC: ".$rowPresSSSActiva['fechapresentacion']."<br>EXP.: ".$rowPresSSSActiva['nroexpediente'];
			  						}
			  					} ?>
			  				<td><?php echo $estado ?></td>
			  				<td>			  					
			  					<?php if ($rowPresSSSActiva['fechasolicitud'] == NULL) { ?> 
			  							<input type="button" value="DESCARGAR" onclick="location.href = 'descargaArchivo.php?file=<?php echo $rowPresSSSActiva['patharchivo'] ?>'"/> 
			  							<input type="button" value="SOLICITUD" onclick="location.href = 'presentacionSolicitud.php?id=<?php echo $rowPresSSSActiva['id'] ?>'"/> 
			  							<input type="button" value="CANCELAR" onclick="location.href = 'cancelarPresentacion.php?id=<?php echo $rowPresSSSActiva['id'] ?>'"/>
			  					<?php } 
			  						  if ($rowPresSSSActiva['fechapresentacion'] == NULL && $rowPresSSSActiva['fechasolicitud'] != NULL) { ?> 
			  							<input type="button" value="NOTA" onclick="location.href = 'descargaArchivo.php?file=<?php echo $rowPresSSSActiva['pathsolicitud'] ?>'"/> 
			  							<input type="button" value="EXPEDIENTE" onclick="location.href = 'presentacionExpediente.php?id=<?php echo $rowPresSSSActiva['id'] ?>'"/> 
			  					<?php } 
			  						  if ($rowPresSSSActiva['fechapresentacion'] != NULL) { ?>
			  							<input type="button" value="NOTA" onclick="location.href = 'descargaArchivo.php?file=<?php echo $rowPresSSSActiva['pathsolicitud'] ?>'"/> 
			  							<input type="button" value="DEVOLUCION" onclick="location.href = 'devolucionPresentacion.php?id=<?php echo $rowPresSSSActiva['id'] ?>'"/> 
			  					<?php } ?>
			  				</td>
			  			</tr>
			  	<?php } ?>
		  			</tbody>
	  			</table>
	  		</div>
  	<?php } else { ?>
  			<h3 style="color: blue">No Existe Presentacion Activas</h3>
  	<?php } ?>
  	<p><button onclick="location.href = 'nuevaPresentacion.php'">Nueva Presentacion</button></p>
  	<h3>Presentaciones Finalizadas</h3>
  	<?php if ($canPresSSSFinalizadas != 0) { ?>
  			<table style="text-align:center; width:1000px;" id="finalizadas" class="tablesorter">
  				<thead>
  					<tr>
	  					<th>ID</th>
	  					<th>Periodo</th>
	  					<th># Bene</th>
	  					<th>Archivo</th>
	  					<th>Estado</th>
  					</tr>
  				</thead>
  				<tbody>
  			<?php  while ($rowPresSSSFinalizadas = mysql_fetch_assoc($resPresSSSFinalizadas)) { 
  					$arrayArchivo = explode("/",$rowPresSSSFinalizadas['patharchivo']);
					$archivo = end($arrayArchivo);?>
  					<tr>
  						<td><?php echo $rowPresSSSFinalizadas['id'] ?></td>
  						<td><?php echo $rowPresSSSFinalizadas['periodo']?></td>
  						<td><?php echo $rowPresSSSFinalizadas['cantidadbeneficiario']?></td>
  						<td><?php echo $archivo?></td>
  						 <?php  $estado = "";
			  					$color = "";
			  					if ($rowPresSSSFinalizadas['fechacancelacion'] != NULL) {
			  						$color = "red";
			  						$estado = "CANCELADA (".$rowPresSSSFinalizadas['fechacancelacion']." - MOTIVO: ".$rowPresSSSFinalizadas['motivocancelacion'].")";
			  					}
			  					if ($rowPresSSSFinalizadas['fechadevolucion'] != NULL) {
			  						$color = "blue";
			  						$estado = "FINALIZADA (".$rowPresSSSFinalizadas['fechadevolucion']." - MONTO: $ ".$rowPresSSSFinalizadas['monto'].")";
			  					}?>
			  				<td style="color: <?php echo $color ?>"><?php echo $estado ?></td>
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
  	<?php } else { ?>
  			<h3 style="color: blue">No Existen Presentaciones Finalizadas</h3>
  	<?php } ?>
</div>
</body>
</html>