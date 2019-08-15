<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSession.php"); 

$sqlPedidos = "SELECT p.*, pe.descripcion as estadodescri,  pedidosprioridad.descripcion as priodescri
				FROM pedidosestado pe, pedidos p 
				LEFT JOIN pedidosprioridad on p.prioridad = pedidosprioridad.id
				WHERE p.usuarioregistro = '".$_SESSION['usuario']."' and p.estado = pe.id
				ORDER BY p.id DESC";
$resPedidos = mysql_query($sqlPedidos,$db);
$numPedidos = mysql_num_rows($resPedidos); ?>

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
  <h3>Listado de Pedidos </h3>
  <h3>Usuario <font color="blue">"<?php echo $_SESSION['usuario']?>"</font></h3>
  <?php if ($numPedidos > 0) { ?>
  			<table class="tablesorter" id="listado" style="width:1000px; font-size:14px; text-align: center">
		  	<thead>
		  		<tr>
			  		<th>ID</th>
			  		<th>Descripcion</th>
			  		<th>Fecha Pedido</th>
			  		<th>Estado</th>
			  		<th>Fechas</th>
			  		<th>Realizador</th>
		  		</tr>
		  	</thead>
		  	<tbody>
	  <?php while ($rowPedidos = mysql_fetch_assoc($resPedidos)) { ?>
		  		<tr>
	 				<td><?php echo $rowPedidos['id'] ?></td>
	 				<td><?php echo $rowPedidos['descripcion'] ?></td>
	 				<td><?php echo $rowPedidos['fecharegistro'] ?></td>
	 				<td><b>
	 			  <?php if ($rowPedidos['estado'] == 4) {
	 						echo "<font style='cursor: pointer' color='red' title='MOTIVO: ".$rowPedidos['motivorechazo']."'><b>RECHAZADO</b></font> <br>"; 
	 					} else { 
	 						echo $rowPedidos['estadodescri'];
	 					} ?>
	 					</b></br>
	 					<b><?php if ($rowPedidos['estado'] == 3) { echo $rowPedidos['priodescri']; } ?></b>
	 				</td>
	 				<td>
	 					<?php if ($rowPedidos['fechaestado'] == NULL ) { echo "-"; } else { echo $rowPedidos['fechaestado']; } ?>
	 					<?php if ($rowPedidos['estado'] == 2) { echo "<br><b>F.E.E:</b> ".$rowPedidos['fechaestudio']; } ?>
	 					<?php if ($rowPedidos['estado'] == 3) { echo "<br><b>F.E.F:</b> ".$rowPedidos['fecharealizacion']; } ?>
	 				</td>
	 				<td><?php if ($rowPedidos['usuariosistemas'] == NULL ) { echo "-"; } else { echo $rowPedidos['usuariosistemas']; } ?></td>
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
										<option value="<?php echo $numPedidos ?>">Todos</option>
									</select>
								</p>
							</form>	
						</div>
					</td>
				</tr>
			</table>
<?php } else { ?>
		<h3><font color="blue">No existen pedidos por su usuario</font></h3>
<?php }?>
	<p><input type="button" value="Nuevo Pedido" onclick="location.href='nuevoPedido.php?origen=<?php echo $origen ?>'"/></p>
</div>
</body>
</html>
