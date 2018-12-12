<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");

if (isset($_POST['dato']) || isset($_GET['nroroden'])) {
	$canOrdenesCabecera = 0;
	$dato = "";
	$filtro = "";
	if (isset($_GET['nroroden'])) {
		$dato = $_GET['nroroden'];
		$filtro = 0;
	} else {
		$dato = $_POST['dato'];
		$filtro = $_POST['filtro'];
	}
	if ($filtro == 0) {
		$cartel = "<b>Nro Orden:<font color='blue'> $dato</font></b>";
		$sqlOrdenesCabecera = "SELECT *, DATE_FORMAT(o.fechaorden, '%d-%m-%Y') as fechaorden FROM prestadores p, ordencabecera o WHERE o.nroordenpago = $dato and o.codigoprestador = p.codigoprestador";
	} 
	if ($filtro == 1) {
		$cartel = "<b>Código:<font color='blue'> $dato</font></b>";
		$sqlOrdenesCabecera = "SELECT *, DATE_FORMAT(o.fechaorden, '%d-%m-%Y') as fechaorden FROM prestadores p, ordencabecera o WHERE o.codigoprestador = $dato and o.codigoprestador = p.codigoprestador order by o.codigoprestador DESC";
	} 
	if ($filtro == 2) {
		$cartel = "<b>C.U.I.T.:<font color='blue'> $dato</font></b>";
		$sqlOrdenesCabecera = "SELECT *, DATE_FORMAT(o.fechaorden, '%d-%m-%Y') as fechaorden FROM prestadores p, ordencabecera o WHERE p.cuit = $dato and o.codigoprestador = p.codigoprestador order by o.codigoprestador DESC";
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
	if (formulario.filtro[0].checked || formulario.filtro[1].checked) {
		resultado = esEnteroPositivo(formulario.dato.value);
		if (!resultado) {
			alert("El Código de Prestador o en Nro. Orden debe ser un numero entero positivo");
			return false;
		} 
	}
	if (formulario.filtro[2].checked) {
		if (!verificaCuilCuit(formulario.dato.value)) {
			alert("C.U.I.T. invalido");
			return false;
		}
	}
	$.blockUI({ message: "<h1>Generando Busqueda... <br>Esto puede tardar unos segundos.<br> Aguarde por favor</h1>" });
	return true;
}
</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = '../moduloOrdenPago.php'" /></p>	
	<form id="buscarFacturas" name="buscarFacturas" method="post" onsubmit="return validar(this)" action="buscarOrden.php">
	  	<h3>Buscador de Ordenes de Pago </h3>
    	<table>
      		<tr>
        		<td rowspan="3"><b>Buscar por </b></td>
        		<td><input type="radio" name="filtro"  value="0" checked="checked" /> Nro Orden </td>
      		</tr>
      		<tr>
        		<td><input type="radio" name="filtro"  value="1" /> Código Prestador </td>
      		</tr> 
      		<tr>
      			<td><input type="radio" name="filtro" value="2" /> C.U.I.T.</td>
      		</tr>
		</table>
    	<p><strong>Dato</strong> <input name="dato" type="text" id="dato" size="14" /></p>
    	<p><input type="submit" name="Buscar" value="Buscar" /></p>
	</form>
	<?php if (isset($_POST['dato']) || isset($_GET['nroroden'])) { 
			echo $cartel; 
	  		if ($canOrdenesCabecera > 0) { ?>
	  				<h3>Ordenes Genearadas</h3>	
	 				<div class="grilla">
		 				<table>
		 					<thead>
			 					<tr>
			 						<th>Nro. Orden</th>
			 						<th>Codigo Prestador</th>
			 						<th>C.U.I.T.</th>
			 						<th>Fecha</th>
			 						<th>Retencion</th>
			 						<th>Importe</th>
			 						<th></th>		 						
			 					</tr>
		 					</thead>
		 					<tbody>
		 		  		<?php while ($rowOrdenesCabecera = mysql_fetch_array($resOrdenesCabecera)) { ?>
		 		  				<tr>
		 		  					<td>
		 		  					<?php echo $rowOrdenesCabecera['nroordenpago'];
		 		  						if ($rowOrdenesCabecera['fechacancelacion'] != null) { 
		 		  							echo "<br><font color='red'> [CANCELADA] </font>"; 
		 		  						}
		 		  					?>
		 		  					</td>
		 		  					<td><?php echo $rowOrdenesCabecera['codigoprestador'] ?></td>
		 		  					<td><?php echo $rowOrdenesCabecera['cuit'] ?></td>
		 		  					<td><?php echo $rowOrdenesCabecera['fechaorden'] ?></td>
		 		  					<td><?php echo $rowOrdenesCabecera['retencion'] ?></td>
		 		  					<td><?php echo $rowOrdenesCabecera['importe'] ?></td>
		 		  					<td><input type="button" value="DETALLE" name="detalle" onclick="location.href = 'ordenPagoConsulta.php?nroorden=<?php echo $rowOrdenesCabecera['nroordenpago'] ?>'" /></td>
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
