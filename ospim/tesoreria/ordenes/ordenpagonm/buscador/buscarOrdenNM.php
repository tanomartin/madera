<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");

$maquina = $_SERVER['SERVER_NAME'];
if(strcmp("localhost",$maquina)==0)
	$carpetaOrden="../OrdenesPagoNMPDF/";
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
		$sqlOrdenesCabecera = "SELECT o.*,  d.idfactura, f.puntodeventa, f.nrocomprobante, DATE_FORMAT(o.fechaorden, '%d-%m-%Y') as fecha, p.nombre, p.codigoprestador, p.cuit 
								FROM ordencabecera o, prestadores p, ordendetalle d, facturas f 
								WHERE o.nroordenpago = $dato AND 
									  o.nroordenpago = d.nroordenpago AND 
									  o.codigoprestador = p.codigoprestador AND 
									  d.idfactura = f.id AND
									  p.personeria = 5";
	} 
	if ($filtro == 1) {
		$cartel = "<b>Prestador:<font color='blue'> $dato</font></b>";
		$sqlOrdenesCabecera = "SELECT o.*, d.idfactura, f.puntodeventa, f.nrocomprobante, DATE_FORMAT(o.fechaorden, '%d-%m-%Y') as fecha,  p.nombre, p.codigoprestador, p.cuit 
								FROM ordencabecera o, prestadores p, ordendetalle d, facturas f  
								WHERE o.codigoprestador = $dato AND 
									  o.nroordenpago = d.nroordenpago AND 
									  d.idfactura = f.id AND
								      o.codigoprestador = p.codigoprestador AND 
									  p.personeria = 5";
	} 
	if ($filtro == 2) {
		$datoBusqeuda = fechaParaGuardar($dato);
		$cartel = "<b>Fecha Generacion:<font color='blue'> $dato</font></b>";
		$sqlOrdenesCabecera = "SELECT o.*, d.idfactura, f.puntodeventa, f.nrocomprobante, DATE_FORMAT(o.fechaorden, '%d-%m-%Y') as fecha, p.nombre, p.codigoprestador, p.cuit
								FROM ordencabecera o, prestadores p, ordendetalle d, facturas f 
								WHERE o.fechaorden = '$datoBusqeuda' AND 
									  o.nroordenpago = d.nroordenpago AND 
									  d.idfactura = f.id AND
									  o.codigoprestador = p.codigoprestador AND 
									  p.personeria = 5";
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

function cancelarOrden(nroorden, idfactura, importe) {
	var cartel = "Desea anular la orden de pago Nro " + nroorden;
	var r = confirm(cartel);
	if (r == true) {
		var redireccion = "cancelarOrdenNM.php?nroorden="+nroorden+"&idFac="+idfactura+"&importe="+importe;
		$.blockUI({ message: "<h1>Anulando ORden de Pago... <br>Esto puede tardar unos segundos.<br> Aguarde por favor</h1>" });
		location.href=redireccion;
	}
}


function imputarOrden(nroorden) {
	var redireccion = "../abm/imputaOrdenPagoNM.php?nroorden="+nroorden;
	location.href=redireccion;
}

function abrirPop(dire, titulo){	
	window.open(dire,titulo,'width=800, height=500,resizable=yes');
}


</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = '../moduloOrdenPagoNM.php'" /></p>	
	<form id="buscarFacturas" name="buscarFacturas" method="post" onsubmit="return validar(this)" action="buscarOrdenNM.php">
	  	<h3>Buscador de Ordenes de Pago No Médicas </h3>
    	<table>
      		<tr>
        		<td rowspan="3"><b>Buscar por </b></td>
        		<td><input type="radio" name="filtro"  value="0" checked="checked" /> Nro Orden </td>
      		</tr>
      		<tr>
        		<td><input type="radio" name="filtro"  value="1" /> Codigo Prestador </td>
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
		 				<table style="width: 100%">
		 					<thead>
			 					<tr>
			 						<th>Nro. Orden</th>
			 						<th>Fecha</th>
			 						<th>Codigo</th>
			 						<th>C.U.I.T.</th>
			 						<th>Nombre</th>
			 						<th>Id. Fac.</th>
			 						<th>Factura</th>
									<th>Tipo - Nro Pago</th>
			 						<th>Importe</th>
			 						<th>Estado</th>
			 						<th>Acciones</th>		 						
			 					</tr>
		 					</thead>
		 					<tbody>
		 		  		<?php while ($rowOrdenesCabecera = mysql_fetch_array($resOrdenesCabecera)) { ?>
		 		  				<tr>
		 		  					<td><?php echo $rowOrdenesCabecera['nroordenpago'];?></td>
		 		  					<td><?php echo $rowOrdenesCabecera['fecha'] ?></td>
		 		  					<td><?php echo $rowOrdenesCabecera['codigoprestador'] ?></td>
		 		  					<td><?php echo $rowOrdenesCabecera['cuit'] ?></td>
		 		  					<td><?php echo $rowOrdenesCabecera['nombre'] ?></td>
		 		  					<td><?php echo $rowOrdenesCabecera['idfactura'] ?></td>
		 		  					<td><?php echo $rowOrdenesCabecera['puntodeventa']."-".$rowOrdenesCabecera['nrocomprobante'] ?></td>
		 		  					<td><?php echo $rowOrdenesCabecera['formapago']." - ".$rowOrdenesCabecera['comprobantepago'] ?></td>
		 		  					<td><?php echo number_format($rowOrdenesCabecera['importe'],2,",",".") ?></td>
		 		  					<td>
		 		  				<?php   if ($rowOrdenesCabecera['fechacancelacion'] != null) { 
		 		  							echo "<font color='red'> ANULADA </font>"; 
		 		  						} else {
	  										echo "<font color='green'> EMITIDA </font>"; 
		 		  						} ?>
		 		  					</td>
		 		  					<td>
		 		  						<input class="nover" id="verorden" type="button" value="ORDEN PDF" onclick="abrirPop('verDocumento.php?documento=OP<?php echo str_pad($rowOrdenesCabecera['nroordenpago'], 8, '0', STR_PAD_LEFT) ?>NM.pdf', 'Orden Pago No Medica');"/>
		 		  			  	<?php	if ($rowOrdenesCabecera['fechacancelacion'] == null) {   ?>		
		 		  							| <input type="button" value="ANULAR" onclick="cancelarOrden(<?php echo $rowOrdenesCabecera['nroordenpago'] ?>, <?php echo $rowOrdenesCabecera['idfactura'] ?>, <?php echo $rowOrdenesCabecera['importe']?>)" />
		 		  				 <?php  }?>
		 		  					</td>
		 		  				</tr>
		 				<?php } ?>
		 					</tbody>
		 				</table>
	 				</div>
			<?php } else { ?>
					<h3 style="color: blue">No Existen Ordenes de Pago No Médicas para el filtro utilizado</h3>
			<?php }
	  	  } ?>
</div>
</body>
</html>
