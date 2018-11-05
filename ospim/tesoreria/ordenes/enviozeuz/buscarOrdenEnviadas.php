<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");

$maquina = $_SERVER['SERVER_NAME'];
if(strcmp("localhost",$maquina)==0)
	$carpetaOrden="../OrdenesPagoPDF/";
else
	$carpetaOrden="/home/sistemas/Documentos/Repositorio/OrdenesPagoPDF/";

if (isset($_POST['dato']) || isset($_GET['fecha']) || isset($_GET['nroorden'])) {
	$canOrdenesCabecera = 0;
	$dato = "";
	$filtro = "";
	if (isset($_GET['fecha'])) {
		$dato = $_GET['fecha'];
		$filtro = 0;
	} else {
		if (isset($_GET['nroorden'])) {
			$dato = $_GET['nroorden'];
			$filtro = 2;
		} else { 
			$dato = $_POST['dato'];
			$filtro = $_POST['filtro'];
			if ($filtro == 0) {
				$dato = fechaParaGuardar($dato);
			}
		}
	}
	if ($filtro == 0) {
		$cartel = "<b>Busqueda por Fecha:<font color='blue'> $dato</font></b>";
		$sqlCorreosEnviados = "SELECT * FROM bandejaenviados WHERE modulocreador like '%Ordenes de Pago' and fecharegistro like '$dato%'";
		$sqlCorreosAEnviar = "SELECT * FROM bandejasalida WHERE modulocreador like '%Ordenes de Pago' and fecharegistro like '$dato%'";
	} 
	if ($filtro == 1) {
		$cartel = "<b>Busqueda por Código de Prestador:<font color='blue'> $dato</font></b>";
		$sqlCorreosEnviados = "SELECT * FROM bandejaenviados WHERE modulocreador like '%Ordenes de Pago' and subject like '%Cod $dato%'";
		$sqlCorreosAEnviar = "SELECT * FROM bandejasalida WHERE modulocreador like '%Ordenes de Pago' and subject like '%Cod $dato%'";
	} 
	if ($filtro == 2) {
		$cartel = "<b>Busqueda por Nro. Orden:<font color='blue'> $dato</font></b>";
		$sqlCorreosEnviados = "SELECT * FROM bandejaenviados WHERE modulocreador like '%Ordenes de Pago' and subject like '%Nro $dato%'";
		$sqlCorreosAEnviar = "SELECT * FROM bandejasalida WHERE modulocreador like '%Ordenes de Pago' and subject like '%Nro $dato%'";
	}

	$resCorreosEnviados = mysql_query($sqlCorreosEnviados,$db);
	$canCorreosEnviado = mysql_num_rows($resCorreosEnviados);
	
	$resCorreosAEnviar = mysql_query($sqlCorreosAEnviar,$db);
	$canCorreosAEnviar = mysql_num_rows($resCorreosAEnviar);
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<style type="text/css" media="print">
.nover {display:none}
</style>
<title>.: Módulo Envio Ordenes de Pago :.</title>
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
		if (!esFechaValida(formulario.dato.value)) {
			return false;
		}
	}
	if (formulario.filtro[1].checked || formulario.filtro[2].checked) {
		resultado = esEnteroPositivo(formulario.dato.value);
		if (!resultado) {
			alert("El Código de Prestador o en Nro. Orden debe ser un numero entero positivo");
			return false;
		} 
	}
	$.blockUI({ message: "<h1>Generando Busqueda... <br>Esto puede tardar unos segundos.<br> Aguarde por favor</h1>" });
	return true;
}

function reenviarMail(nroorden, idmail, boton, mail) {
	var r = confirm("Desea reenviar la orden de pago a la siguiente direccion "+mail);
	if (r == true) {
		boton.disabled = true;
		var redireccion = "reenviarOrden.php?idmail="+idmail+"&nroorden="+nroorden;
		location.href=redireccion;
	}
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input class="nover" type="button" name="volver" value="Volver" onclick="location.href = 'moduloEnvio.php'" /></p>	
	<form  class="nover" id="buscarOrdenPago" name="buscarOrdenPago" method="post" onsubmit="return validar(this)" action="buscarOrdenEnviadas.php">
	  	<h3>Buscador de Ordenes de Pago Enviadas de Zeuz</h3>
    	<table>
      		<tr>
        		<td rowspan="3"><b>Buscar por </b></td>
        		<td><input type="radio" name="filtro"  value="0" checked="checked" /> Fecha Generación </td>
      		</tr>
      		<tr>
        		<td><input type="radio" name="filtro"  value="1" /> Código Prestador </td>
      		</tr> 
      		<tr>
        		<td><input type="radio" name="filtro"  value="2" /> Nro. Orden </td>
      		</tr> 
		</table>
    	<p><strong>Dato</strong> <input name="dato" type="text" id="dato" size="14" /></p>
    	<p><input type="submit" name="Buscar" value="Buscar" /></p>
	</form>
<?php if (isset($_POST['dato']) || isset($_GET['fecha']) || isset($_GET['nroorden'])) { 
			echo $cartel; 
	  		if ($canCorreosAEnviar > 0) { ?>
	  				<h3>Ordenes de Pago En proceso de Envio</h3>	
	 				<div class="grilla">
		 				<table>
		 					<thead>
			 					<tr>
			 						<th>Codigo</th>
			 						<th>Razon Social</th>
			 						<th>C.U.I.T.</th>
			 						<th>Nro. Orden</th>
			 						<th>Email</th>
			 						<th>Fecha</th>
			 					<!-- 	<th></th>	 --> 						
			 					</tr>
		 					</thead>
		 					<tbody>
		 		  		<?php while ($rowCorreosAEnviar = mysql_fetch_assoc($resCorreosAEnviar)) { 
		 		  				$arraySubject = explode("-",$rowCorreosAEnviar['subject']);
		 		  				$nroorden = intval(preg_replace('/[^0-9]+/', '',  $arraySubject[1]), 10);
		 		  				$codigo = intval(preg_replace('/[^0-9]+/', '',  $arraySubject[2]), 10); 
		 		  				
		 		  				$sqlPresta = "SELECT nombre, cuit FROM prestadores WHERE codigoprestador = $codigo";
		 		  				$resPresta = mysql_query($sqlPresta,$db);
		 		  				$rowPresta = mysql_fetch_assoc($resPresta); ?>
		 		  				<tr>
		 		  					<td><?php echo $codigo;?></td>
		 		  					<td><?php echo $rowPresta['nombre'];?></td>
		 		  					<td><?php echo $rowPresta['cuit'];?></td>
		 		  					<td><?php echo $nroorden;?></td>
		 		  					<td><?php echo $rowCorreosAEnviar['address'] ?></td>
		 		  					<td><?php echo $rowCorreosAEnviar['fecharegistro'] ?></td>
		 		  					<!-- <td><input type="button" value="Ver Orden" name="orden" onclick="window.open('<?php // echo $carpetaOrden ?>OP<?php //echo $nroorden ?>O.pdf', '_blank', 'fullscreen=yes');" /></td> -->
		 		  				</tr>
		 				<?php } ?>
		 					</tbody>
		 				</table>
	 				</div>
			<?php } else { ?>
					<h3>No Existen Ordenes de Pago En proceso de Envio</h3>
			<?php }
			
			if ($canCorreosEnviado > 0) { ?>
				  	<h3>Ordenes de Pago Enviadas</h3>	
				 	<div class="grilla">
					 	<table>
					 		<thead>
						 		<tr>
						 			<th>Motivo</th>
						 			<th>Email</th>
						 			<th>Fecha Generacion</th>
						 			<th>Fecha Envio</th>
						 			<th></th>	 						
						 		</tr>
					 		</thead>
					 		<tbody>
					   <?php while ($rowCorreosEnviados = mysql_fetch_array($resCorreosEnviados)) { 
					   			$arraySubject = explode("-",$rowCorreosEnviados['subject']);
		 		  				$nroorden = intval(preg_replace('/[^0-9]+/', '',  $arraySubject[1]), 10);
		 		  				$codigo = intval(preg_replace('/[^0-9]+/', '',  $arraySubject[2]), 10);?>
					 		  	<tr>
					 		  		<td><?php echo $rowCorreosEnviados['subject'];?></td>
					 		  		<td><?php echo $rowCorreosEnviados['address'] ?></td>
					 		  		<td><?php echo $rowCorreosEnviados['fecharegistro'] ?></td>
					 		  		<td><?php echo $rowCorreosEnviados['fechaenvio'] ?></td>
					 		  		<td>
					 		  	<!-- 	<input type="button" value="Ver Orden" name="orden" onclick="window.open('<?php // echo $carpetaOrden ?>OP<?php // echo $nroorden ?>O.pdf', '_blank', 'fullscreen=yes');" />  -->	
					 		  			<input type="button" value="Reenviar" onclick="reenviarMail(<?php echo $nroorden?>,<?php echo $rowCorreosEnviados['id']?>, this, '<?php echo $rowCorreosEnviados['address']?>')" />
					 		  		</td>
					 		  	</tr>
					 	<?php } ?>
					 		</tbody>
					 	</table>
				 	</div>
			<?php } else { ?>
						<h3>No Existen Ordenes de Pago Enviadas</h3>
			<?php }
	  	 	 } ?>
	  	<p><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();"/></p>
</div>
</body>
</html>
