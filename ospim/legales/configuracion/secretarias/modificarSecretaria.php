<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
$codigosecre = $_GET['secre'];
$codigoJuzga = $_GET['juz'];

$sqlJuzgado = "select * from juzgados where codigojuzgado = $codigoJuzga";
$resJuzgado = mysql_query($sqlJuzgado,$db); 
$rowJuzgado = mysql_fetch_assoc($resJuzgado);


$sqlSecretaria = "select * from secretarias where codigojuzgado = $codigoJuzga and codigosecretaria = $codigosecre";
$resSecretaria = mysql_query($sqlSecretaria,$db); 
$rowSecretaria = mysql_fetch_array($resSecretaria);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modificar Secretaria :.</title>
<script type="text/javascript">

function validar(formulario) {
	if (formulario.denominacion.value == "") {
		alert("Debe completar la Denominación de la Secretaria");
		return(false);
	}
	formulario.guardar.disabled = true;
	formulario.eliminar.disabled = true;
	return true;
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="reset" name="volver" value="Volver" onclick="location.href = 'secretarias.php'" /></p>
  <h3>Modificar Juzgado </h3>
  <form id="modifSecre" name="modifSecre" method="post" action="guardarModifSecretaria.php?codsecre=<?php echo $codigosecre ?>&codjuz=<?php echo $codigoJuzga ?>" onsubmit="return validar(this)">
	<?php
		$sqlTraJuicios = "select * from trajuiciosospim where codigojuzgado = $codigoJuzga and codigosecretaria = $codigosecre";
		$resTraJuicios = mysql_query($sqlTraJuicios,$db); 
		$canTraJuicios = mysql_num_rows($resTraJuicios); 				 			  
		if ($canTraJuicios == 0) { ?>
			<p><input type="button" name="eliminar" onclick="location.href = 'eliminarSecretaria.php?codsecre=<?php echo $codigosecre ?>&codjuz=<?php echo $codigoJuzga ?>'" value="Eliminar" /></p>
  <?php } ?>
	<p>Codigo Secretaría: <b> <?php echo $codigosecre ?> </b></p>
	<p>Juzgado: <b> <?php echo $codigoJuzga ?> - <?php echo $rowJuzgado['denominacion'] ?></b></p>
	<p>Denominación <input name="denominacion" type="text" id="denominacion" value="<?php echo $rowSecretaria['denominacion'];?>" size="100" maxlength="100"/></p>
	<p><input type="submit" name="guardar" value="Guardar Cambios" /></p>
    </form>
</div>
</body>
</html>
