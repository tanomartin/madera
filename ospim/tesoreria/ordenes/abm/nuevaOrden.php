<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); ?>

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
			alert("El Código de Prestador debe ser un numero entero positivo");
			return false;
		} 
	}
	if (formulario.filtro[1].checked) {
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
	<p><input type="button" name="volver" value="Volver" onclick="location.href = '../menuOrdenes.php'" /></p>	
	<form id="buscarFacturas" name="buscarFacturas" method="post" onsubmit="return validar(this)" action="listadoFacturas.php">
	  	<h3>Nueva Orden de Pago </h3>
	  	<table>
      		<tr>
        		<td rowspan="2"><b>Buscar por </b></td>
        		<td><input type="radio" name="filtro"  value="0" checked="checked" /> Código </td>
      		</tr>
      		<tr>
        		<td><input type="radio" name="filtro" value="1" /> C.U.I.T.</td>
      		</tr> 
		</table>
    	<p><strong>Dato</strong> <input name="dato" type="text" id="dato" size="14" /></p>
    	<p><input type="submit" name="Buscar" value="Buscar" /></p>
	</form>
	<?php if (isset($_GET['error'])) {
			echo "<font color='red'><b>NO EXISTEN PRESTADOR CON ESTOS DATOS</b></font>";
	  	  } else { 
	  	  	if (isset($_POST['dato']) || isset($_GET['codigo'])) { ?>
	  	  		<h4> Código: <font color='blue'><?php echo $rowPrestador['codigoprestador']?></font> - C.U.I.T.: <font color='blue'><?php echo $rowPrestador['cuit']?></font> 
				<br/> Razon Social: <font color='blue'><?php echo $rowPrestador['nombre'] ?></font></h4>
	  	  		<input type="button" value="Nueva Orden" name="nueva" onclick="location.href = 'listadoFacturas.php?codigo=<?php echo $rowPrestador['codigoprestador'] ?>'"/>
	 		<?php if ($canOrdenesCabecera > 0) { ?>
	 				<h3>Ordenes Genearadas</h3>	
	 				<div class="grilla">
		 				<table>
		 					<thead>
			 					<tr>
			 						<th>Nro. Orden</th>
			 						<th>Fecha</th>
			 						<th>Retencion</th>
			 						<th>Importe</th>
			 						<th></th>		 						
			 					</tr>
		 					</thead>
		 					<tbody>
		 		  		<?php while ($rowOrdenesCabecera = mysql_fetch_array($resOrdenesCabecera)) { ?>
		 		  				<tr>
		 		  					<td><?php echo $rowOrdenesCabecera['nroordenpago'] ?></td>
		 		  					<td><?php echo $rowOrdenesCabecera['fecha'] ?></td>
		 		  					<td><?php echo $rowOrdenesCabecera['retencion'] ?></td>
		 		  					<td><?php echo $rowOrdenesCabecera['importepago'] ?></td>
		 		  					<td><input type="button" value="DETALLE" name="detalle" onclick="location.href = 'ordenPagoConsulta.php?nroorden=<?php echo $rowOrdenesCabecera['nroordenpago'] ?>'" /></td>
		 		  				</tr>
		 				<?php } ?>
		 					</tbody>
		 				</table>
	 				</div>
			<?php } else { ?>
					<h3 style="color: blue">No Existen Ordenes de Pago realizadas</h3>
			<?php }
		  	}
	  	  }?>
</div>
</body>
</html>
