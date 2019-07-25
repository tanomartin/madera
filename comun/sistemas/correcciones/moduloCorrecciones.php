<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSession.php"); 

$sqlCorrec = "SELECT c.*, m.etiquetadato1, m.etiquetadato2, m.etiquetadato3, m.etiquetadato4, m.nombre as modulo, mm.descripcion as motivo
				FROM correcciones c, modulos m, modulosmotivos mm
				WHERE c.usuarioregistro = '".$_SESSION['usuario']."' and c.idmodulo = m.id and c.idmotivo = mm.id
				ORDER BY c.id DESC";
$resCorrec = mysql_query($sqlCorrec,$db);
$numCorrec = mysql_num_rows($resCorrec);
?>

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
	})
	.tablesorterPager({
		container: $("#paginador")
	});
});

</script>

</head>
<body style="background-color: <?php echo $bgcolor ?>">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = '../menuSistemas.php?origen=<?php echo $origen ?>'"/> </p>
  <h3>Listado de Correcciones </h3>
  <h3>Usuario <font color="blue">"<?php echo $_SESSION['usuario']?>"</font></h3>
  <p><b>(Si la corrección fue rechazada poder ver el motivo colocando el cursor sobre el estado <font color="brown">RECHAZADA</font>)</b></p>
<?php if ($numCorrec > 0) { ?>
	  <table class="tablesorter" id="listado" style="width:100%; font-size:14px">
		  	<thead>
		  		<tr>
			  		<th>ID</th>
			  		<th>Modulo</th>
			  		<th>Datos</th>
			  		<th>Fecha</th>
			  		<th>Motivo</th>
			  		<th>Descripcion</th>
			  		<th>Estado</th>
			  		<th>Fecha Estado</th>
			  		<th>Corrector</th>
		  		</tr>
		  	</thead>
		  	<tbody>
	 	<?php while ($rowCorrec = mysql_fetch_assoc($resCorrec)) { 
	 			$datos = "";
	 			if ($rowCorrec['etiquetadato1'] != NULL && $rowCorrec['dato1'] != NULL) {
	 				$datos .= "<b>".$rowCorrec['etiquetadato1'].":</b> ".$rowCorrec['dato1']."<br>";
	 			}
	 			if ($rowCorrec['etiquetadato2'] != NULL && $rowCorrec['dato2'] != NULL) {
	 				$datos .= "<b>".$rowCorrec['etiquetadato2'].":</b> ".$rowCorrec['dato2']."<br>";
	 			}
	 			if ($rowCorrec['etiquetadato3'] != NULL && $rowCorrec['dato3'] != NULL) {
	 				$datos .= "<b>".$rowCorrec['etiquetadato3'].":</b> ".$rowCorrec['dato3']."<br>";
	 			} 
				if ($rowCorrec['etiquetadato4'] != NULL && $rowCorrec['dato4'] != NULL) {
	 				$datos .= "<b>".$rowCorrec['etiquetadato4'].":</b> ".$rowCorrec['dato4'];
	 			}?>
	 			<tr>
	 				<td><?php echo $rowCorrec['id'] ?></td>
	 				<td><?php echo $rowCorrec['modulo'] ?></td>
	 				<td><?php echo $datos ?></td>
	 				<td><?php echo $rowCorrec['fecharegistro'] ?></td>
	 				<td><?php echo $rowCorrec['motivo'] ?></td>
	 				<td><?php echo $rowCorrec['observacion'] ?></td>
	 		<?php $estado = "<b>PENDIENTE</b>";
	 			  $fechaestado = "-";
	 			  $corrector = "-";
	 			  if ($rowCorrec['corrector'] != NULL) { 
	 			  	$estado = "<b>EN PROCESO</b>"; 
	 			  	$fechaestado = $rowCorrec['fechacorrector']; 
	 			  	$corrector =  $rowCorrec['corrector'];
	 			  } 
	 			  if ($rowCorrec['fecharechazo'] != NULL) { 
	 			  	$estado = "<font style='cursor: pointer' color='red' title='MOTIVO: ".$rowCorrec['motivorechazo']."'><b>RECHAZADA</b></font> <br>";
	 			  	$fechaestado = $rowCorrec['fecharechazo'];
	 			  }
	 			  if ($rowCorrec['fechafinalizacion'] != NULL) { 
	 			  	$estado = "<font color='blue'><b>FINALIZADA</b></font>"; 
	 			  	$fechaestado = $rowCorrec['fechafinalizacion'];
	 			  }
	 			  ?>
	 			  	<td><?php echo $estado ?></td>
	 			  	<td><?php echo $fechaestado ?></td>
	 			  	<td><?php echo $corrector ?></td>
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
								<img src="../img/first.png" width="16" height="16" class="first"/> <img src="../img/prev.png" width="16" height="16" class="prev"/>
								<input name="text" type="text" class="pagedisplay" style="background:#CCCCCC; text-align:center" size="8" readonly="readonly"/>
								<img src="../img/next.png" width="16" height="16" class="next"/> <img src="../img/last.png" width="16" height="16" class="last"/>
								</p>
								<p align="center">
									<select name="select" class="pagesize">
									<option selected="selected" value="10">10 por pagina</option>
									<option value="20">20 por pagina</option>
									<option value="30">30 por pagina</option>
									<option value="<?php echo $numCorrec;?>">Todos</option>
									</select>
								</p>
							</form>	
						</div>
					</td>
				</tr>
			</table>
<?php } else { ?>
		<h3><font color="blue">No existen correciones pedidas por su usuario</font></h3>
<?php }?>
  <p><input type="button" value="Pedir Correccion" onclick="location.href='nuevaCorreccion.php?origen=<?php echo $origen ?>'"/></p>
</div>
</body>
</html>
