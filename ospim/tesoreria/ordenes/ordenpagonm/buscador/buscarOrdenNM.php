<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");

$maquina = $_SERVER['SERVER_NAME'];
if(strcmp("localhost",$maquina)==0)
	$carpetaOrden="../OrdenesPagoPDF/";
else
	$carpetaOrden="/home/sistemas/Documentos/Repositorio/OrdenesPagoNMPDF/";

if (isset($_POST['dato']) || isset($_GET['nroorden'])) {
	$canOrdenesCabecera = 0;
	$dato = "";
	$filtro = "";
	if (isset($_GET['nroorden'])) {
		$dato = $_GET['nroorden'];
		$filtro = 0;
	} else {
		$dato = $_POST['dato'];
		$filtro = $_POST['filtro'];
	}
	if ($filtro == 0) {
		$cartel = "<b>Nro Orden:<font color='blue'> $dato</font></b>";
		$sqlOrdenesCabecera = "SELECT o.*, DATE_FORMAT(o.fecha, '%d-%m-%Y') as fecha, DATE_FORMAT(o.fechamigracion, '%d-%m-%Y') as fechamigracion, p.nombre as prestador 
								FROM ordennmcabecera o, prestadores p 
								WHERE o.nroorden = $dato and o.codigoprestador = p.codigoprestador";
	} 
	if ($filtro == 1) {
		$cartel = "<b>Beneficiario:<font color='blue'> $dato</font></b>";
		$sqlOrdenesCabecera = "SELECT o.*, DATE_FORMAT(o.fecha, '%d-%m-%Y') as fecha, DATE_FORMAT(o.fechamigracion, '%d-%m-%Y') as fechamigracion, p.nombre as prestador 
								FROM ordennmcabecera o, prestadores p 
								WHERE (p.nombre like '%".$dato."%') and o.codigoprestador = p.codigoprestador";
	} 
	if ($filtro == 2) {
		$datoBusqeuda = fechaParaGuardar($dato);
		$cartel = "<b>Fecha Generacion:<font color='blue'> $dato</font></b>";
		$sqlOrdenesCabecera = "SELECT o.*, DATE_FORMAT(o.fecha, '%d-%m-%Y') as fecha, DATE_FORMAT(o.fechamigracion, '%d-%m-%Y') as fechamigracion, p.nombre as prestador  
								FROM ordennmcabecera o, prestadores p 
								WHERE o.fecha = '$datoBusqeuda' and o.codigoprestador = p.codigoprestador";
	}
	$resOrdenesCabecera = mysql_query($sqlOrdenesCabecera,$db);
	$canOrdenesCabecera = mysql_num_rows($resOrdenesCabecera);
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Ordenes Pago :.</title>
<link rel="stylesheet" href="/madera/lib/tablas.css"/>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">

function validar(formulario) {
	if(formulario.dato.value == "") {
		alert("Debe colocar un dato de busqueda");
		return false;
	}
	if (formulario.filtro[0].checked) {
		resultado = esEnteroPositivo(formulario.dato.value);
		if (!resultado) {
			alert("El Nro. Orden debe ser un numero entero positivo");
			return false;
		} 
	}
	if (formulario.filtro[2].checked) {
		resultado = esFechaValida(formulario.dato.value);
		if (!resultado) {
			alert("La fecha de busqueda no es valida");
			return false;
		} 
	}
	$.blockUI({ message: "<h1>Generando Busqueda... <br>Esto puede tardar unos segundos.<br> Aguarde por favor</h1>" });
	return true;
}

function cancelarOrden(nroorden, boton, fechamigrada, nroarchivo) {
	var cartel = "Desea anular la orden de pago Nro " + nroorden;
	console.log(fechamigrada);
	if (fechamigrada != "") {
		cartel = cartel + "\nTenga en cuenta que esta orden ya fue migrada al sistema contable\nInfo Migracion dia: "+fechamigrada+" - archivo Nro: "+nroarchivo;
	}
	var r = confirm(cartel);
	if (r == true) {
		boton.disabled = true;
		var redireccion = "cancelarOrdenNM.php?nroorden="+nroorden;
		location.href=redireccion;
	}
}


function imputarOrden(nroorden) {
	var redireccion = "../abm/imputaOrdenPagoNM.php?nroorden="+nroorden;
	location.href=redireccion;
}

function verOrden(nroorden) {
	var redireccion = "verOrdenNM.php?nroorden="+nroorden;
	var titulo = "ORDEN DE PAGO NO MEDICA NUM "+nroorden;
	a= window.open(redireccion,titulo,
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=10, left=10");
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = '../moduloOrdenPagoNM.php'" /></p>	
	<form id="buscarFacturas" name="buscarFacturas" method="post" onsubmit="return validar(this)" action="buscarOrdenNM.php">
	  	<h3>Buscador de Ordenes de Pago </h3>
    	<table>
      		<tr>
        		<td rowspan="3"><b>Buscar por </b></td>
        		<td><input type="radio" name="filtro"  value="0" checked="checked" /> Nro Orden </td>
      		</tr>
      		<tr>
        		<td><input type="radio" name="filtro"  value="1" /> Prestador </td>
      		</tr> 
      		<tr>
        		<td><input type="radio" name="filtro"  value="2" /> Fecha Generacion </td>
      		</tr> 
		</table>
    	<p><strong>Dato</strong> <input name="dato" type="text" id="dato" size="14" /></p>
    	<p><input type="submit" name="Buscar" value="Buscar" /></p>
	</form>
	<?php if (isset($_POST['dato']) || isset($_GET['nroorden'])) {
			echo $cartel; 
	  		if ($canOrdenesCabecera > 0) { ?>
	  				<h3>Ordenes Genearadas</h3>	
	 				<div class="grilla">
		 				<table style="width: 900px">
		 					<thead>
			 					<tr>
			 						<th>Nro. Orden</th>
			 						<th>Beneficiario</th>
									<th>Fecha</th>
									<th>Tipo - Nro Pago</th>
			 						<th>Importe</th>
			 						<th>Estado</th>
			 						<th>Acciones</th>		 						
			 					</tr>
		 					</thead>
		 					<tbody>
		 		  		<?php while ($rowOrdenesCabecera = mysql_fetch_array($resOrdenesCabecera)) { ?>
		 		  				<tr>
		 		  					<td><?php echo $rowOrdenesCabecera['nroorden'];?></td>
		 		  					<td><?php echo $rowOrdenesCabecera['prestador'] ?></td>
		 		  					<td><?php echo $rowOrdenesCabecera['fecha'] ?></td>
		 		  					<td><?php echo $rowOrdenesCabecera['tipopago']." - ".$rowOrdenesCabecera['nropago'] ?></td>
		 		  					<td><?php echo number_format($rowOrdenesCabecera['importe'],2,",",".") ?></td>
		 		  					<td>
		 		  					<?php   
		 		  						if ($rowOrdenesCabecera['fechacancelacion'] != null) { 
		 		  							echo "<font color='red'> CANCELADA </font>"; 
		 		  						} else {
	  										if ($rowOrdenesCabecera['fechageneracion'] != null) { 
	  											if ($rowOrdenesCabecera['fechamigracion'] != null) {
	  												echo "<font color='green'> MIGRADA </font>";
	  											} else {
	  												echo "<font color='green'> EMITIDA </font>"; 
	  											}
	  										} else {
	  											if ($rowOrdenesCabecera['fechaimputacion'] != null) {
	  												echo "<font color='olive'> PARA EMITIR </font>";
	  											} else {
	  												echo "<font color='blue'> PARA IMPUTAR </font>";
	  											}
	  										}
		 		  						}
	  								?>
		 		  					</td>
		 		  					
		 		  					<td>
		 		  				 <?php if ($rowOrdenesCabecera['fechageneracion'] != null) {  ?>	
		 		  							<input type="button" value="VER PDF" onclick="verOrden('<?php echo $rowOrdenesCabecera['nroorden'] ?>')" />
		 		  			  	  <?php }
		 		  			  			if ($rowOrdenesCabecera['fechacancelacion'] == null) {   ?>
		 		  							<input type="button" value="ANULAR" onclick="cancelarOrden(<?php echo $rowOrdenesCabecera['nroorden'] ?>, this, '<?php echo $rowOrdenesCabecera['fechamigracion'] ?>','<?php echo $rowOrdenesCabecera['nroarchivomigra']?>' )" />
		 		  				 <?php  }?>
		 		  					</td>
		 		  				</tr>
		 				<?php } ?>
		 					</tbody>
		 				</table>
	 				</div>
			<?php } else { ?>
					<h3 style="color: blue">No Existen Ordenes de Pago</h3>
			<?php }
	  	  } ?>
</div>
</body>
</html>
