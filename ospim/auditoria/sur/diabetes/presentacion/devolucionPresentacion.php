<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");

$id = $_GET['id'];
$sqlPresSSS = "SELECT d.*, DATE_FORMAT(d.fechapresentacion,'%d/%m/%Y') as fechapresentacion
			   FROM diabetespresentacion d WHERE id = $id";
$resPresSSS = mysql_query($sqlPresSSS,$db);
$rowPresSSS = mysql_fetch_assoc($resPresSSS);
$archivo = "-";
if ($rowPresSSS['patharchivo'] != NULL) {
	$arrayArchivo = explode("/",$rowPresSSS['patharchivo']);
	$archivo = end($arrayArchivo); 
}
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
		alert("Debe ingresar un Fecha de Devolución de la Presentación");
		formulario.fecha.focus();
		return(false);
	} else {
		if (!esFechaValida(fecha)) {
			alert("La fecha de devolución no es valida");
			formulario.fecha.focus();
			return(false);
		} 
	}
	if (formulario.expediente.value == "") {
		alert("El Nro de expediente es obligatorio");
		formulario.expediente.focus();
		return(false);
	} 
	if (formulario.monto.value != "") {
		if (!isNumberPositivo(formulario.monto.value)) {
			alert("El monto de la devolución debe ser un numero positivo");
			formulario.monto.focus();
			return(false);
		}
	} else {
		alert("El monto de la devolución es obligatorio");
		formulario.monto.focus();
		return(false);
	}
	formulario.guardar.disabled = true;
	$.blockUI({ message: "<h1>Guardando Devolución de Presentación. Aguarde por favor...</h1>" });
	return true;
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = 'moduloPresSSS.php'" /></p>
	<h3>Devolucion Presentacion Diabetes S.S.S.</h3>
	<div class="grilla">
	  	<table style="width:100%">
	  		<thead>
	  			<tr>
		  			<th>ID</th>
		  			<th>Periodo</th>
		  			<th># Nuevos</th>
		  			<th># Ant.</th>
		  			<th>Archivo</th>
		  			<th width="25%">Observacion</th>
		  			<th width="20%">Estado</th>
	  			</tr>
	  		</thead>
			<tbody>
			  	<tr>
			  		<td><?php echo $rowPresSSS['id']?></td>
			  		<td><?php echo $rowPresSSS['periodo']?></td>
			  		<td><?php echo $rowPresSSS['cantbenenuevos']?></td>
			  		<td><?php echo $rowPresSSS['cantbeneanteriores']?></td>
			  		<td><?php echo $archivo?></td>
			  		<td><?php echo $rowPresSSS['observacion']  ?></td>
			  		<td><?php echo "PRESENTADA <br>FEC: ".$rowPresSSS['fechapresentacion']."<br>SOL.: ".$rowPresSSS['nrosolicitud']."<br>CANT: ".$rowPresSSS['cantbenesolicitados']; ?></td>
			  	</tr>
		  	</tbody>
	  	</table>
	 </div>
	 <form id="devolucionPresentacion" name="devolucionPresentacion" method="post" onsubmit="return validar(this)" action="devolucionPresentacionGuardar.php">
	 	<input type="text" id="id" name="id" value="<?php echo $rowPresSSS['id'] ?>" style="display: none"/>
	 	<h3>Datos de la Devolución</h3>
	 	<p><b>Fecha Devolución: </b><input type="text" id="fecha" name="fecha" size="8"/></p>
	 	<p><b>Nro. Expediente: </b><input type="text" id="expediente" name="expediente" size="15"/></p>
	 	<p><b>Monto: </b><input type="text" id="monto" name="monto" size="8"/></p>
	 	<p><input type="submit" id="guardar" name="guardar" value="FINALIZAR"/></p>
	 </form>
</div>
</body>
</html>
