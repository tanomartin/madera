<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 

$origen = $_GET['origen'];
$sqlPedidos = "SELECT p.*, pe.descripcion as estadodescri 
				FROM pedidos p, pedidosestado pe 
				WHERE p.origen = '$origen' and p.estado not in (4,5) and p.estado = pe.id";
$resPedidos = mysql_query($sqlPedidos,$db);
$numPedidos = mysql_num_rows($resPedidos); 

$sqlRealizadores = "SELECT * FROM usuarios WHERE departamento = 11";
$resRealizadores = mysql_query($sqlRealizadores,$db); ?>

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
});

function atender(id,accion, origen) {
	if (accion == "E") {
		var nombrePE = "pest-"+id;
		var modoDisplayFE = document.getElementById(nombrePE).style.display;
		if (modoDisplayFE == "none") {
			document.getElementById(nombrePE).style.display = "block";
		}
		if (modoDisplayFE == "block") {
			var nombre = "realizador-"+id;
			var nombreFE = "fechaestudio-"+id;
			var realizador = document.getElementById(nombre).value;
			var fechae =  document.getElementById(nombreFE).value;
		 	if (realizador == 0 || fechae == "") {
				alert("Debe seleccionar un Corrector y debe ingresar un fecha finalizacion estudio");
				document.getElementById(nombre).focus();
		 	} else {
		 		var r = confirm("Desea pasar a Estudio el pedido con 'ID "+id+"' - 'REALIZADOR: "+realizador+"' - 'FECHA ESTIMADA ESTUDIO: "+fechae+"'");
				if (r == true) {
					$.blockUI({ message: "<h1>Pasando a Estudio Pedido<br>Aguarde por favor...</h1>" });
					window.location.href = "estudioPedido.php?id="+id+"&origen="+origen+"&realizador="+realizador+"&fecha="+fechae;
				} 
		 	}
		}
	}

	if (accion == "R") {
		var nombreMotivo = "motivo-"+id;
		console.log(nombreMotivo);
		var modoDisplay = document.getElementById(nombreMotivo).style.display;
		
		if (modoDisplay == "none") {
			document.getElementById(nombreMotivo).style.display = "block";
		}
		if (modoDisplay == "block") {
			var motivo = document.getElementById(nombreMotivo).value;
			if (motivo == "") {
				alert("Debe ingresar un motivo de rechazo");
				document.getElementById(nombreMotivo).focus();
			} else {
				$.blockUI({ message: "<h1>Rechazando Pedido<br>Aguarde por favor...</h1>" });
				window.location.href = "rechazarPedido.php?id="+id+"&origen="+origen+"&motivo="+motivo;
			}
		}
	}

	if (accion == "J")	{
		var nombrePJ = "peje-"+id;
		var modoDisplayFJ = document.getElementById(nombrePJ).style.display;
		if (modoDisplayFJ == "none") {
			document.getElementById(nombrePJ).style.display = "block";
		}
		if (modoDisplayFJ == "block") {
			var nombreFJ = "fechaejecucion-"+id;
			var fechaj =  document.getElementById(nombreFJ).value;
			if (fechaj == "") {
				alert("Debe ingresar un fecha de ejecucion estimada");
				document.getElementById(fechaj).focus();
			} else { 
				var r = confirm("Desea pasar a Ejecucion el pedido con 'ID "+id+"' con fecha estimada de finalizacion '"+fechaj+"'");
				if (r == true) {
					$.blockUI({ message: "<h1>Pasando a Ejecución Pedido<br>Aguarde por favor...</h1>" });
					window.location.href = "ejecucionPedido.php?id="+id+"&origen="+origen+"&fecha="+fechaj;
				} 
			}
		}
	}
	
	if (accion == "F") {
		var r = confirm("Desea finalizar el pedido con 'ID "+id+"'");
		if (r == true) {
			$.blockUI({ message: "<h1>Finalizando Pedido<br>Aguarde por favor...</h1>" });
			window.location.href = "finalizarPedido.php?id="+id+"&origen="+origen;
		} 
	}
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = 'menuPedidos.php'"/> </p>
	<h3>Listado de Pedidos</h3>
<?php if ($origen == "U") { ?><h3 style="color: brown">U.S.I.M.R.A</h3> <?php } ?>
<?php if ($origen == "O") { ?><h3 style="color: blue">O.S.P.I.M.</h3> <?php } ?>
<?php if ($numPedidos > 0) { ?> 
	 <table class="tablesorter" id="listado" style="width:100%; font-size:14px; text-align: center">
		  	<thead>
		  		<tr>
			  		<th>ID</th>
			  		<th>Usuario</th>
			  		<th>Fecha P.</th>
			  		<th>Descripcion</th>
			  		<th>Estado</th>
			  		<th>Fechas</th>
			  		<th>Realizador</th>
			  		<th width="170px">Acciones</th>
		  		</tr>
		  	</thead>
		  	<tbody>
	 	<?php while ($rowPedido = mysql_fetch_assoc($resPedidos)) { ?>
	 			<tr>
	 				<td><?php echo $rowPedido['id'] ?></td>
	 				<td><?php echo $rowPedido['usuarioregistro'] ?></td>
	 				<td><?php echo $rowPedido['fecharegistro'] ?></td>
	 				<td><?php echo $rowPedido['descripcion'] ?></td>
	 				<td><b><?php echo $rowPedido['estadodescri'] ?></b></td>
	 				<td><?php echo $rowPedido['fechaestado'] ?></td>
	 				<td><?php echo $rowPedido['usuariosistemas']; 
	 					if ($rowPedido['usuariosistemas'] == NULL) { ?>
			 				<select id="realizador-<?php echo $rowPedido['id'] ?>" name="realizador-<?php echo $rowPedido['id'] ?>">
			 			  		<option value="0">Seleccione Corrector</option>
				 		  <?php mysql_data_seek( $resRealizadores, 0 );
				 		     	while($rowRealizadores = mysql_fetch_assoc($resRealizadores)) { ?>
				 			  		<option value="<?php echo $rowRealizadores['nombre']?>"><?php echo $rowRealizadores['nombre']?></option>
				 		  <?php }?>
			 			  	</select>
			 	  <?php } ?>	
	 				</td>
	 				<td>
	 				<?php if ($rowPedido['estado'] == 1) { ?>
	 						<input type="button" value="PASAR A ESTUDIO" onclick="atender('<?php echo $rowPedido['id'] ?>','E','<?php echo $origen?>')"/>
	 						<p id="pest-<?php echo $rowPedido['id'] ?>" align="center" style="display: none">
	 							<input type="text" id="fechaestudio-<?php echo $rowPedido['id'] ?>" name="fechaestudio-<?php echo $rowPedido['id'] ?>" size="10"/>
	 						</p>
	 				<?php }
	 					  if ($rowPedido['estado'] == 2) { ?>
	 						<input type="button" value="PASAR A EJECUCION" onclick="atender('<?php echo $rowPedido['id'] ?>','J','<?php echo $origen?>')"/>
	 						<p id="peje-<?php echo $rowPedido['id'] ?>" align="center" style="display: none">
	 							<input type="text" id="fechaejecucion-<?php echo $rowPedido['id'] ?>" name="fechaejecucion-<?php echo $rowPedido['id'] ?>" size="10" />
	 						</p>
	 						<p><input type="button" value="RECHAZAR" onclick="atender('<?php echo $rowPedido['id'] ?>','R','<?php echo $origen?>')"/></p>
	 						<p align="center"><textarea id="motivo-<?php echo $rowPedido['id'] ?>" name="motivo-<?php echo $rowPedido['id'] ?>" rows="4" cols="23" style="display: none"></textarea></p>
	 				<?php }
	 					  if ($rowPedido['estado'] == 3) { ?>
	 						<input type="button" value="FINALIZAR" onclick="atender('<?php echo $rowPedido['id'] ?>','F','<?php echo $origen?>')"/>
	 				<?php } ?>
	 				</td>
	 			</tr>
	 	<?php } ?>
		  	</tbody>
		  </table>
<?php } else { ?>
		<h3><font color="blue">No existen Pedidos PENDIENTES, EN ESTUDIO, EN PROCESO</font></h3>
<?php }?>
</div>
</body>
</html>