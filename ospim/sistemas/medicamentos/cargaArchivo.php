<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Actualizacion Alfa Beta :.</title>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

function validar(formulario) {
	if (formulario.manual.value == "") {
		alert("El archivo MANUAL.dat es obligatorio");
		return false;
	}
	if (formulario.extra.value == "") {
		alert("El archivo MANEXTRA.txt es obligatorio");
		return false;
	}
	if (formulario.accion.value == "") {
		alert("El archivo ACCIOFAR.txt es obligatorio");
		return false;
	}
	$.blockUI({ message: "<h1>Cargando archivos. Aguarde por favor...</h1>" });
	return true;
}
</script>

</head>

<body bgcolor="#CCCCCC">
	<div align="center">
	 	<p><input type="button" name="volver" value="Volver" onclick="location.href = 'menuMedicamentos.php'" /></p>
	 	<h3>Actualizacion Alfa Beta </h3>
	 	<h3><font color="blue"><?php if ($_GET['tipo'] == "M") { echo "MENSUAL"; } else { echo "SEMANAL"; }?></font></h3>
	 	
	 	<form onsubmit="return validar(this)"  action="<?php echo "actualizacionArchivos.php?tipo=".$_GET['tipo']; ?>" enctype="multipart/form-data" method="post">
 			<h4>Cargar Archivo manual.dat</h4>
 			<p><input type="file" name="manual" id="manual" accept=".dat" /></p>
 			<h4>Cargar Archivo manextra.txt</h4>
 			<p><input type="file" name="extra" id="extra" accept=".txt" /></p>
 			<h4>Cargar Archivo acciofar.txt</h4>
 			<p><input type="file" name="accion" id="accion" accept=".txt" /></p>
 			
 			<p><input type="submit" name="importar"  value="Actualizar"/></p>
 		</form>
	</div>
</body>
</html>