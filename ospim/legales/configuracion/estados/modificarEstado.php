<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
$codigo = $_GET['codigo'];

$sqlEstado = "select * from estadosprocesales where codigo = $codigo";
$resEstado = mysql_query($sqlEstado,$db); 
$rowEstado = mysql_fetch_array($resEstado);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modificar Estado Procesal :.</title>
<script type="text/javascript">

function validar(formulario) {
	if (formulario.descri.value == "") {
		alert("Debe completar la Denominación del Juzgado");
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
  <p><input type="button" name="volver" value="Volver" onclick="location.href = 'estados.php'" /></p>
  <h3>Modificar Estado Procesal </h3>
  <form id="modifEstado" name="modifEstado" method="post" action="guardarModifEstado.php?codigo=<?php echo $codigo ?>" onsubmit="return validar(this)">
	 <?php
		$sqlTraJuicios = "select * from trajuiciosospim where estadoprocesal = $codigo";
		$resTraJuicios = mysql_query($sqlTraJuicios,$db); 
		$canTraJuicios = mysql_num_rows($resTraJuicios); 
		if ($canTraJuicios == 0) { ?>
			<p><input type="button" name="eliminar" onclick="location.href = 'eliminarEstado.php?codigo=<?php echo $codigo ?>'" value="Eliminar" /></p>
  <?php } ?>
		<p>Codigo: <b> <?php echo $rowEstado['codigo']; ?> </b></p>
		<p>Denominación <input name="descri" type="text" id="descri" value="<?php echo $rowEstado['descripcion'];?>" size="100" maxlength="100"/></p>
		<p><input type="submit" name="guardar" value="Guardar Cambios" /></p>
	</form>
</div>
</body>
</html>
