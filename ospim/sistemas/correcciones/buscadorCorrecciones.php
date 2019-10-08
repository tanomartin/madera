<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 

$sqlCorrecFinalizadas = "SELECT c.*, u.nombre as usuario, m.etiquetadato1, m.etiquetadato2, m.etiquetadato3, m.etiquetadato4, 
						   m.nombre as modulo, mm.descripcion as motivo 
							FROM correcciones c, usuarios u, modulos m, modulosmotivos mm
							WHERE (c.fecharechazo is not null or c.fechafinalizacion is not null) and 
								  c.idmodulo = m.id and 
								  c.idmotivo = mm.id and
								  c.usuarioregistro = u.usuariosistema
							ORDER BY id DESC";
$resCorrecFinalizadas = mysql_query($sqlCorrecFinalizadas,$db);
$numCorrecFinalizadas = mysql_num_rows($resCorrecFinalizadas); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="/madera/lib/tablas.css"/>
<title>.: Módulo Empresas :.</title>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script>
<script language="javascript" type="text/javascript">

$(function() {
	$("#listado")
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
	}).tablesorterPager({
		container: $("#paginador")
	});
});

</script>
</head>
<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = 'menuCorrecciones.php'"/> </p>
	<h3>Listado de Correcciones Finalizadas</h3>
	 <table class="tablesorter" id="listado" style="width:100%; font-size:14px">
		  	<thead>
		  		<tr>
			  		<th>ID</th>
			  		<th class="filter-select" data-placeholder="--">Origen</th>
			  		<th class="filter-select" data-placeholder="--">Usuario</th>
			  		<th class="filter-select" data-placeholder="--">Modulo</th>
			  		<th width="160px">Datos</th>
			  		<th>Fecha P.</th>
			  		<th>Motivo</th>
			  		<th width="200px">Descripcion</th>
			  		<th class="filter-select" data-placeholder="--">Estado</th>
			  		<th>Fecha</th>
			  		<th class="filter-select" data-placeholder="--">Corrector</th>
			  	</tr>
			</thead>
		  	<tbody>
		 	<?php while ($rowCorrec = mysql_fetch_assoc($resCorrecFinalizadas)) { 
			 		$datos = "";
			 		if ($rowCorrec['etiquetadato1'] != NULL) {
			 			$datos .= "<b>".$rowCorrec['etiquetadato1'].":</b> ".$rowCorrec['dato1']."<br>";
			 		}
			 		if ($rowCorrec['etiquetadato2'] != NULL) {
			 			$datos .= "<b>".$rowCorrec['etiquetadato2'].":</b> ".$rowCorrec['dato2']."<br>";
			 		}
			 		if ($rowCorrec['etiquetadato3'] != NULL) {
			 			$datos .= "<b>".$rowCorrec['etiquetadato3'].":</b> ".$rowCorrec['dato3']."<br>";
			 		}
			 		if ($rowCorrec['etiquetadato4'] != NULL) {
			 			$datos .= "<b>".$rowCorrec['etiquetadato4'].":</b> ".$rowCorrec['dato4'];
			 		} ?>
			 		<tr>
			 			<td><?php echo $rowCorrec['id'] ?></td>
			 			<td><?php $origen = "O.S.P.I.M.";
			 					  if ($rowCorrec['origen'] == "U") { $origen = "U.S.I.M.R.A."; } 
			 					  echo $origen; ?></td>
			 			<td><?php echo $rowCorrec['usuario'] ?></td>
			 			<td><?php echo $rowCorrec['modulo'] ?></td>
			 			<td><?php echo $datos ?></td>
			 			<td><?php echo $rowCorrec['fecharegistro'] ?></td>
			 			<td><?php echo $rowCorrec['motivo'] ?></td>
			 			<td><?php echo $rowCorrec['observacion'] ?></td>
		 		<?php 	if ($rowCorrec['fecharechazo'] != NULL) { 
	 			  			$estado = "<font style='cursor: pointer' color='red' title='MOTIVO: ".$rowCorrec['motivorechazo']."'><b>RECHAZADA</b></font> <br>";
	 			  			$fechaestado = $rowCorrec['fecharechazo'];
	 			  		}
	 			  		if ($rowCorrec['fechafinalizacion'] != NULL) { 
	 			  			$estado = "<font color='blue'><b>FINALIZADA</b></font>"; 
	 			  			$fechaestado = $rowCorrec['fechafinalizacion'];
	 			  		} ?>
	 			  		<td><?php echo $estado ?></td>
	 			  		<td><?php echo $fechaestado ?></td>
	 			  		<td><?php echo $rowCorrec['corrector'] ?></td>
	 			  	</tr>
		<?php 	} ?>
	 	</tbody>
	 </table>
	 <table class="nover" align="center" width="245" border="0">
				<tr>
					<td width="239">
						<div id="paginador" class="pager">
							<form>
								<p align="center">
								<img src="img/first.png" width="16" height="16" class="first"/> <img src="img/prev.png" width="16" height="16" class="prev"/>
								<input name="text" type="text" class="pagedisplay" style="background:#CCCCCC; text-align:center" size="8" readonly="readonly"/>
								<img src="img/next.png" width="16" height="16" class="next"/> <img src="img/last.png" width="16" height="16" class="last"/>
								</p>
								<p align="center">
									<select name="select" class="pagesize">
									<option selected="selected" value="10">10 por pagina</option>
									<option value="20">20 por pagina</option>
									<option value="30">30 por pagina</option>
									<option value="<?php echo $numCorrecFinalizadas;?>">Todos</option>
									</select>
								</p>
							</form>	
						</div>
					</td>
				</tr>
	</table>
	 </div>
</body>
</html>