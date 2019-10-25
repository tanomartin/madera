<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$sqlPracticas = "SELECT * FROM practicas WHERE nomenclador = 7 ORDER BY idpractica";
$resPracticas = mysql_query($sqlPracticas,$db);

$arrayResultados = array();
$i = 0;
if (isset($_POST['practica'])) {
	$resultado = array();
	$codigopractica = $_POST['practica'];
	
	$sqlResolucionDetalle = "SELECT r.*,p.*,c.nombre,
								DATE_FORMAT(c.fechainicio, '%d-%m-%Y') as fechainicio,
								DATE_FORMAT(c.fechafin, '%d-%m-%Y') as fechafin
								FROM 
									practicasvaloresresolucion r, practicas p, nomencladoresresolucion c
								WHERE 
									r.idpractica = $codigopractica and 
									r.idpractica = p.idpractica and 
									r.idresolucion = c.id ORDER BY c.id DESC";
	$resResolucionDetalle = mysql_query($sqlResolucionDetalle,$db);
	$canResolucionDetalle = mysql_num_rows($resResolucionDetalle);
	
	if ($canResolucionDetalle != 0) {
		while ($rowResolucionDetalle = mysql_fetch_assoc($resResolucionDetalle)) {
			$practicaCodigo = $rowResolucionDetalle['codigopractica'];
			$practicaNombre = $rowResolucionDetalle['descripcion'];
			$arrayResultados[$i] = $rowResolucionDetalle;
			$i++;
		}
	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M&oacute;dulo Servicio Disca Buscador :.</title>
<link rel="stylesheet" href="/madera/lib/tablas.css"/>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">

function validar(formulario) {
	if (formulario.practica.value == 0) {
		alert("Debe seleccionar una Practica");
		return false;
	}
	formulario.buscar.disabled = true;
	$.blockUI({ message: "<h1>Generando Busqueda... <br>Esto puede tardar unos segundos.<br> Aguarde por favor</h1>" });
	return true;
}

</script>

</head>
<body bgcolor="#CCCCCC">
<div align="center">
	<form id="form1" name="form1" method="post" onsubmit="return validar(this)" action="buscador.php">
		<p><input type="button" name="volver" value="Volver" class="nover" onclick="location.href = 'moduloResoluciones.php'" /> </p>
	  	<h3>Buscador de Precios historicos por Practica</h3>
		
		<h3>Practica</h3>
		<select id="practica" name="practica">
			<option value="0" selected="selected">Seleccione Practica</option>
		<?php while ($rowPracticas = mysql_fetch_assoc($resPracticas)) { ?>
				<option value='<?php echo $rowPracticas['idpractica'] ?>'><?php echo $rowPracticas['codigopractica']."-".$rowPracticas['descripcion']?></option> 
		<?php } ?>
		</select>
		
		<p><input type="submit" name="buscar" value="Buscar" /></p>
	
		<?php if (isset($_POST['practica'])) { ?>
			<h3>Listado de Precios Historicos</h3>
			<?php if ($canResolucionDetalle != 0) { ?>
			<h3><font color="blue"><?php echo  $practicaCodigo." - ".$practicaNombre ?></font></h3>
			<div class="grilla">
				<table style="width:800px; font-size:14px; text-align:center">
					<thead>
						<tr>
							<th>Nombre Resolución</th>
		  					<th>Fecha Desde</th>
		  					<th>Fecha Hasta</th>
		  					<th>Importe ($)</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach($arrayResultados as $resultado) { ?>
							<tr>
								<td><?php echo $resultado['nombre'] ?></td>
			  			 		<td><?php echo $resultado['fechainicio'] ?></td>
			  			 		<td><?php if ($resultado['fechafin']!=NULL) { echo $resultado['fechafin']; } else { echo "-"; } ?></td>
			  			 		<td><?php echo number_format($resultado['modulo'],2,',','.') ?></td>	  		
							</tr>
					<?php } ?>
					</tbody>
				</table>
			</div>
		<?php } else {?>
				<h3><font color="red">No existen Resoluciones con esta practica cargada</font></h3>
		<?php }
			} ?>
	</form>
</div>
</body>
</html>
