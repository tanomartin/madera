<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");

$id = $_GET['id'];
$sqlPresSSS = "SELECT d.*,
				DATE_FORMAT(d.fechadesde,'%d/%m/%Y') as fechadesde, 
				DATE_FORMAT(d.fechahasta,'%d/%m/%Y') as fechahasta
			   FROM diabetespresentacion d WHERE id = $id";
$resPresSSS = mysql_query($sqlPresSSS,$db);
$rowPresSSS = mysql_fetch_assoc($resPresSSS)
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" href="/madera/lib/tablas.css"/>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modulo Diabetes Presentacion S.S.S. :.</title>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">

jQuery(function($){
	$("#fecha").mask("99-99-9999");
});

function validar(formulario) {
	var fecha = formulario.fecha.value;
	if (fecha == "") {
		alert("Debe ingresar un Fecha de Presentaci�n");
		formulario.fecha.focus();
		return(false);
	} else {
		if (!esFechaValida(fecha)) {
			alert("La fecha de presentacion no es valida");
			formulario.fecha.focus();
			return(false);
		} 
	}

	if (formulario.expediente.value == "") {
		alert("El expediente es obligatorio");
		formulario.expediente.focus();
		return(false);
	}
	formulario.guardar.disabled = true;
	$.blockUI({ message: "<h1>Guardando Presentaci�n a la Super. Aguarde por favor...</h1>" });
	return true;
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = 'moduloPresSSS.php'" /></p>
	<h3>Presentacion Diabetes S.S.S.</h3>
	<div class="grilla">
	  	<table>
	  		<thead>
	  			<tr>
		  			<th>ID</th>
		  			<th>Fecha Desde - Hasta</th>
		  			<th># Beneficiarios</th>
		  			<th>Archivo</th>
		  			<th>Estado</th>
	  			</tr>
	  		</thead>
			<tbody>
			  	<tr>
			  		<td><?php echo $rowPresSSS['id']?></td>
			  		<td><?php echo $rowPresSSS['fechadesde']." - ".$rowPresSSS['fechahasta']?></td>
			  		<td><?php echo $rowPresSSS['cantidadbeneficiario']?></td>
			  		<td><?php echo substr($rowPresSSS['patharchivo'],-22)?></td>
			  	    <?php $estado = "SIN PRESENTAR";
			  			  if ($rowPresSSS['fechapresentacion'] != NULL) {
			  				$estado = "PRESENTADA <br>FEC: ".$rowPresSSS['fechapresentacion']." - EXP: ".$rowPresSSS['nroexpediente'];
			  			  } ?>
			  		<td><?php echo $estado ?></td>
			  	</tr>
		  	</tbody>
	  	</table>
	 </div>
	 <form id="cancelarPresentacion" name="cancelarPresentacion" method="post" onsubmit="return validar(this)" action="presentacionSSSGuardar.php">
	 	<input type="text" id="id" name="id" value="<?php echo $rowPresSSS['id'] ?>" style="display: none"/>
	 	<h3>Datos Presenetaci�n</h3>
	 	<p><b>Fecha Presentaci�n: </b><input type="text" id="fecha" name="fecha" size="8"/></p>
	 	<p><b>Expediente: </b><input type="text" id="expediente" name="expediente" size="20"/></p>
	 	<p><input type="submit" id="guardar" name="guardar" value="GUARDAR PRESENTACION SSS"/></p>
	 </form>
</div>
</body>
</html>
