<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 

$sqlCabPadronActive = "SELECT * FROM padronssscabecera WHERE fechacierre is null";
$resCabPadronActive = mysql_query($sqlCabPadronActive, $db);
$canCabPadronActive = mysql_num_rows($resCabPadronActive);
if ($canCabPadronActive == 0) {
	$sqlCabPadronLastActive = "SELECT * FROM padronssscabecera ORDER BY id DESC LIMIT 1";
	$resCabPadronLastActive = mysql_query($sqlCabPadronLastActive, $db);
	$resCabPadronLastActive = mysql_fetch_array($resCabPadronLastActive);
	$ultimaFecha = $resCabPadronLastActive['anio']."-".$resCabPadronLastActive['mes']."-1";
	$nuevafecha = strtotime ( '+1 month' , strtotime ( $ultimaFecha ) ) ;
	$nuevafecha = date ( 'Y-m-j' , $nuevafecha );
	$anionuevo = date("Y", strtotime($nuevafecha));
	$mesnuevo = date("m", strtotime($nuevafecha));
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Padron SSS :.</title>

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script>

function validar(formulario) {
	if (formulario.archivo.value == "") {
		alert("Debe seleccionar un archivo de padron para subir");
		return false;
	} 
	
	cadena = formulario.archivo.value;
	nombre = <?php echo $anionuevo.$mesnuevo ?>;
	if (cadena.indexOf(nombre) == -1) {
		alert("El archivo seleccionado no concuerda con el mes a subir");
		return false;
	}
	$.blockUI({ message: "<h1>Generando Proceso de importacion de padron<br>Aguarde por favor...</h1>" });
	return true;
}

</script>

</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = 'moduloSSS.php'" /></p>
  <h2>Importacion Padron SSS</h2>
  <?php if ($canCabPadronActive == 0) {?>
  <form id="form1" name="form1" method="post"  enctype="multipart/form-data"  action="generarImportacionPadron.php" onsubmit="return validar(this)">
	 	<h3>Periodo a importar <font color="blue"> <?php echo $mesnuevo."-".$anionuevo ?></font></h3>
	 	<input style="display: none" type="text" value="<?php echo $mesnuevo ?>" name="mes" id="mes"/>
	 	<input style="display: none" type="text" value="<?php echo $anionuevo ?>" name="anio" id="anio"/>
	 	<p><input type="file" name="archivo" id="archivo" accept=".txt" /></p>
	 	<p><input type="submit" name="Submit" value="Importar Padron" /> </p>
  </form>
  <?php } else { ?>
  	<h3><font color="blue">Existe un padron activo. Primero cierre este padron y luego cargue el nuevo</font></h3>
  <?php } ?>
  </div>
</body>
</html>
