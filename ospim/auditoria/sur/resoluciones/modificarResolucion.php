<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$id = $_GET['id'];
$sqlResolucion = "SELECT r.*, DATE_FORMAT(r.fecha, '%d-%m-%Y') as fecha FROM resolucioncabecera r WHERE r.id = $id ORDER BY id";
$resResolucion = mysql_query($sqlResolucion,$db);
$rowResolucion = mysql_fetch_assoc($resResolucion);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M&oacute;dulo Carga Resoluciones :.</title>

<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script type="text/javascript">

jQuery(function($){
	$("#fecha").mask("99-99-9999");
});

function validar(formulario) {	
	if (formulario.nombre.value == "") {
		alert("El nombre de la resolución es obligatoria");
		return false;
	}
	if (formulario.emisor.value == "") {
		alert("El Emisor de la resolución es obligatoria");
		return false;
	}
	if (formulario.fecha.value == "") {
		alert("La fecha de la resolución es obligatoria");
		return false;
	} else {
		if (!esFechaValida(formulario.fecha.value)) {
			alert("La fecha de la resolución no es valida");
			return false;
		}
	}
	formulario.Submit.disabled = true;
	return true;
}
</script>


</head>

<body bgcolor="#CCCCCC">
<div align="center">
  	<p><input type="button" name="volver" value="Volver" onclick="location.href = 'resoluciones.php'"/></p>
  	<h3>Modificar Cabecera Resolución</h3>
	<form name="modificarResolucion" id="modificarResolucion" method="post" onsubmit="return validar(this)" action="guardarModificacionResolucion.php?id=<?php echo $rowResolucion['id'] ?>">
		<p><b>Nombre </b><input type="text" name="nombre" id="nombre" value="<?php echo $rowResolucion['nombre'] ?>" /></p>
		<p><b>Emisor </b><input type="text" name="emisor" id="emisor" value="<?php echo $rowResolucion['emisor'] ?>" /></p>
		<p><b>Fecha </b><input type="text" name="fecha" id="fecha" size="10" value="<?php echo $rowResolucion['fecha'] ?>"/></p>
		<p><b>Observcacion</b></p>
		<p><textarea rows="5" cols="60" id="obs" name="obs"><?php echo $rowResolucion['observacion'] ?></textarea></p>
		<p><input type="submit" name="Submit" id="Submit" value="Guardar Modificacion" /></p>
	</form>
</div>
</body>
</html>