<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$id = $_GET['id'];
$sqlResolucion = "SELECT r.*, 
						DATE_FORMAT(r.fechaemision, '%d-%m-%Y') as fechaemision,
						DATE_FORMAT(r.fechainicio, '%d-%m-%Y') as fechainicio,
						DATE_FORMAT(r.fechafin, '%d-%m-%Y') as fechafin 
					FROM nomencladoresresolucion r WHERE r.id = $id ORDER BY id";
$resResolucion = mysql_query($sqlResolucion,$db);
$rowResolucion = mysql_fetch_assoc($resResolucion);

$sqlResolucionDetalle = "SELECT practicas.idpractica, codigopractica, descripcion, practicasvaloresresolucion.modulo
							FROM practicas 
							LEFT JOIN practicasvaloresresolucion ON practicasvaloresresolucion.idresolucion = $id and 
														   	 		practicasvaloresresolucion.idpractica = practicas.idpractica 
							WHERE practicas.nomenclador = 7 ORDER BY codigopractica";
$resResolucionDetalle = mysql_query($sqlResolucionDetalle,$db);
$canResolucionDetalle = mysql_num_rows($resResolucionDetalle);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M&oacute;dulo Detalle Resoluciones :.</title>
<link rel="stylesheet" href="/madera/lib/tablas.css"/>
<style type="text/css" media="print">
.nover {display:none}
</style>

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">

jQuery(function($){
	for (var i=1; i<=<?php echo $canResolucionDetalle ?>; i++) {
		$("#fd"+i).mask("99-99-9999");
		$("#fh"+i).mask("99-99-9999");
	}
});

function validar(formulario) {
	var nameimp = "";
	var imp = "";

	for (var i=1; i<=<?php echo $canResolucionDetalle ?>; i++) {
		nameimp = "imp"+i;		
		imp =  document.getElementById(nameimp).value;
		if (imp != "") {
			if (!isNumberPositivo(imp)) {
				alert("El importe debe ser un número positivo");
				document.getElementById(nameimp).focus();
				return false;
			}
		}
	}

	for (var i=1; i<=<?php echo $canResolucionDetalle ?>; i++) {
		nameid = "id"+i;
		id = document.getElementById(nameid).value;
	}
	
	formulario.Submit.disabled = true;
	$.blockUI({ message: "<h1>Guardando Practicas de la Resolucion. Aguarde por favor...</h1>" });
	return true;
}

</script>
</head>
<body bgcolor="#CCCCCC">
<div align="center">
  	<p><input type="button" class="nover" name="volver" value="Volver" onclick="location.href = 'resoluciones.php'"/></p>
  	<form name="modificarPracticas" id="modificarPracticas" method="post" onsubmit="return validar(this)" action="guardarModificacionPracticas.php?id=<?php echo $rowResolucion['id'] ?>">
	  	<h3>Modificacion Practicas de Resolución</h3>
	  	<div style="border: solid; width: 600px">
		  	<p><b>Nombre: </b> <?php echo $rowResolucion['nombre'] ?></p>
	  		<p><b>Emisor: </b> <?php echo $rowResolucion['emisor'] ?></p>
	    	<p><b>Fecha Emisión: </b> <?php echo $rowResolucion['fechaemision'] ?></p>
	    	<?php $fin = " al ".$rowResolucion['fechafin'];
	    	  if ($rowResolucion['fechafin'] == NULL) {
	    		$fin = " a la actualidad";
	    	  }?>
	    	<p><b>Vigencia: </b><?php echo $rowResolucion['fechainicio'].$fin ?></p>
	  		<p><b>Observación</b></p> 
	  		<p><?php echo $rowResolucion['observacion'] ?></p>	</div>
	  	<h3>Prácticas de la Resolución</h3>
	  	<div class="grilla">
		  	<table style="width: 900px">
		  		<thead>
		  			<tr>
			  			<th>Código</th>
			  			<th>Nombre</th>
			  			<th>Importe ($)</th>
		  			</tr>
		  		</thead>
		  		<tbody>
		  <?php 
		  		$i = 0;
		  		while ($rowResolucionDetalle = mysql_fetch_assoc($resResolucionDetalle)) { 
		  			$i++; ?>
			  		<tr>
				  		 <td>
				  		 	<?php echo $rowResolucionDetalle['codigopractica'] ?>
				  		 	<input style="display: none" type="text" id="id<?php echo $i ?>" name="id<?php echo $i ?>" value="<?php echo $rowResolucionDetalle['idpractica']?>" />
				  		 </td>
				  		 <td><?php echo $rowResolucionDetalle['descripcion'] ?></td>
				  		 <td><input style="text-align: center" size="10" type="text" id="imp<?php echo $i ?>" name="imp<?php echo $i ?>" value="<?php echo $rowResolucionDetalle['modulo'] ?>"/></td>
				  	</tr>
		 <?php } ?>
		  	 	</tbody>
		  	  	</table>
	   	</div>
	   	<p><input type="submit" name="Submit" id="Submit" value="Guardar Modificacion" /></p>
	</form>
</div>
</body>
</html>