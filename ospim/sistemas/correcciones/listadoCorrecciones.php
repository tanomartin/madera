<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 

$origen = $_GET['origen'];
$sqlCorrecciones = "SELECT c.*, u.nombre as usuario, m.etiquetadato1, m.etiquetadato2, m.etiquetadato3, m.etiquetadato4, 
						   m.nombre as modulo, mm.descripcion as motivo 
					FROM correcciones c, usuarios u, modulos m, modulosmotivos mm
					WHERE c.origen = '$origen' and 
						  (c.corrector is null or  
						  c.fechacorrector is not null) and 
						  c.fecharechazo is null and 
						  c.fechafinalizacion is null and
						  c.idmodulo = m.id and 
						  c.idmotivo = mm.id and
						  c.usuarioregistro = u.usuariosistema";
$resCorrecciones = mysql_query($sqlCorrecciones,$db);
$numCorrecciones = mysql_num_rows($resCorrecciones); 

$sqlCorrectores = "SELECT * FROM usuarios WHERE departamento = 11";
$resCorrectores = mysql_query($sqlCorrectores,$db); ?>

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
	if (accion == "T") {
		var nombre = "corrector-"+id;
		var corrector = document.getElementById(nombre).value;
	 	if (corrector == 0) {
			alert("Debe seleccionar un Corrector");
			document.getElementById(nombre).focus();
	 	} else {
	 		var r = confirm("Desea Tomar la corrección con 'ID "+id+"' - 'CORRECTOR: "+corrector+"'");
			if (r == true) {
				$.blockUI({ message: "<h1>Tomando Corrección<br>Aguarde por favor...</h1>" });
				window.location.href = "tomarCorreccion.php?id="+id+"&origen="+origen+"&corrector="+corrector;
			} 
	 	}
	}
	
	var nombreMotivo = "motivo-"+id;
	var modoDisplay = document.getElementById(nombreMotivo).style.display;
	if (accion == "R" && modoDisplay == "none") {
		document.getElementById(nombreMotivo).style.display = "block";
	}
	if (accion == "R" && modoDisplay == "block") {
		var motivo = document.getElementById(nombreMotivo).value;
		if (motivo == "") {
			alert("Debe ingresar un motivo de rechazo");
			document.getElementById(nombreMotivo).focus();
		} else {
			$.blockUI({ message: "<h1>Rechazando Corrección<br>Aguarde por favor...</h1>" });
			window.location.href = "rechazarCorreccion.php?id="+id+"&origen="+origen+"&motivo="+motivo;
		}
	}
	
	if (accion == "F") {
		var r = confirm("Desea finalizar la corrección con 'ID "+id+"'");
		if (r == true) {
			$.blockUI({ message: "<h1>Finalizando Corrección<br>Aguarde por favor...</h1>" });
			window.location.href = "finalizarCorreccion.php?id="+id+"&origen="+origen;
		} 
	}
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = 'menuCorrecciones.php'"/> </p>
	
	
	<h3>Listado de Correcciones</h3>
<?php if ($origen == "U") { ?><h3 style="color: brown">U.S.I.M.R.A</h3> <?php } ?>
<?php if ($origen == "O") { ?><h3 style="color: blue">O.S.P.I.M.</h3> <?php } ?>
<?php if ($numCorrecciones > 0) { ?> 
	 <table class="tablesorter" id="listado" style="width:100%; font-size:14px">
		  	<thead>
		  		<tr>
			  		<th>ID</th>
			  		<th>Usuario</th>
			  		<th>Modulo</th>
			  		<th width="150px">Datos</th>
			  		<th>Fecha P.</th>
			  		<th>Motivo</th>
			  		<th>Descripcion</th>
			  		<th>Estado</th>
			  		<th>Fecha E.</th>
			  		<th>Corrector</th>
			  		<th width="170px">Acciones</th>
		  		</tr>
		  	</thead>
		  	<tbody>
	 	<?php while ($rowCorrec = mysql_fetch_assoc($resCorrecciones)) { 
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
	 				<td><?php echo $rowCorrec['usuario'] ?></td>
	 				<td><?php echo $rowCorrec['modulo'] ?></td>
	 				<td><?php echo $datos ?></td>
	 				<td><?php echo $rowCorrec['fecharegistro'] ?></td>
	 				<td><?php echo $rowCorrec['motivo'] ?></td>
	 				<td><?php echo $rowCorrec['observacion'] ?></td>
	 		  <?php $estado = "<b>PENDIENTE</b>";
	 			    $fechaestado = "-";
	 			    $corrector = "-";
	 			    if ($rowCorrec['corrector'] != NULL) { ?>
	 			  		<td style="color: blue"><b>EN PROCESO</b></td> 
	 			  		<td><?php echo $rowCorrec['fechacorrector'] ?></td>
	 			  		<td align="center"><?php echo $rowCorrec['corrector'] ?></td>
	 			  		<td align="center">
	 			  			<input type="button" value="FINALIZAR" onclick="atender('<?php echo $rowCorrec['id'] ?>','F','<?php echo $origen?>')" /> |
	 			  			<input type="button" value="RECHAZAR" onclick="atender('<?php echo $rowCorrec['id'] ?>','R','<?php echo $origen?>')" />
	 			  			<p><textarea id="motivo-<?php echo $rowCorrec['id'] ?>" name="motivo-<?php echo $rowCorrec['id'] ?>" rows="4" cols="23" style="display: none"></textarea></p>
	 			  		</td>	
	 		  <?php } else { ?>
	 					<td><b>PENDIENTE</b></td> 
	 			  		<td>-</td>
	 			  		<td align="center">
	 			  			<select id="corrector-<?php echo $rowCorrec['id'] ?>" name="corrector-<?php echo $rowCorrec['id'] ?>">
	 			  				<option value="0">Seleccione Corrector</option>
		 			  		    <?php mysql_data_seek( $resCorrectores, 0 );
		 			  		    	  while($rowCorrectores = mysql_fetch_assoc($resCorrectores)) { ?>
		 			  		    		<option value="<?php echo $rowCorrectores['nombre']?>"><?php echo $rowCorrectores['nombre']?></option>
		 			  		    <?php }?>
	 			  			</select>
	 			  		</td>
	 			  		<td align="center">
	 			  			<input type="button" value="TOMAR" onclick="atender('<?php echo $rowCorrec['id'] ?>','T','<?php echo $origen?>')"/>			  			
	 			  		</td>
	 		  <?php } ?>
	 			</tr>
	 	<?php } ?>
		  	</tbody>
		  </table>
<?php } else { ?>
		<h3><font color="blue">No existen correciones PENDIENTES ni EN PROCESO</font></h3>
<?php }?>
</div>
</body>
</html>