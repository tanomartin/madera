<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
$codigo = $_GET['codigo'];
$sqlJuzgado = "select * from juzgados where codigojuzgado = $codigo";
$resJuzgado = mysql_query($sqlJuzgado,$db); 
$rowJuzgado = mysql_fetch_array($resJuzgado);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modificar Juzgado :.</title>
<script type="text/javascript">

function validar(formulario) {
	if (formulario.denominacion.value == "") {
		alert("Debe completar la Denominación del Juzgado");
		return(false);
	}
	if (formulario.fuero.value == 0) {
		alert("Debe Seleccionar un Fuero");
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
  <p><input type="button" name="volver" value="Volver" onclick="location.href = 'juzgados.php'" /></p>
  <h3>Modificar Juzgado </h3>
  <form id="modifGestor" name="modifGestor" method="post" action="guardarModifJuzgado.php?codigo=<?php echo $codigo ?>" onsubmit="return validar(this)">
		<?php 	$sqlTraJuicios = "select * from trajuiciosospim where codigojuzgado = $codigo";
				$resTraJuicios = mysql_query($sqlTraJuicios,$db); 
				$canTraJuicios = mysql_num_rows($resTraJuicios); 
				if ($canTraJuicios == 0) { ?>
					<p><input type="button" name="eliminar" onclick="location.href = 'eliminarJuzgado.php?codigo=<?php echo $codigo ?>'" value="Eliminar" /></p>
		  <?php } ?>  
		<p>Codigo: <b> <?php echo $codigo; ?> </b></p>
		<p>Denominación <input name="denominacion" type="text" id="denominacion" value="<?php echo $rowJuzgado['denominacion'];?>" size="100" maxlength="100"/></p>
		<p>Fuero <select name="fuero" id="fuero">
				<option value="0" selected="selected">SELECCIONE FUERO</option>
					<?php if ($rowJuzgado['fueros'] == "CIVIL Y COMERCIAL") { ?>
						<option value="CIVIL Y COMERCIAL" selected="selected">CIVIL Y COMERCIAL</option>
					<?php } else { ?>
						<option value="CIVIL Y COMERCIAL">CIVIL Y COMERCIAL</option>
					<?php }?>
					<?php if ($rowJuzgado['fueros'] == "COMERCIAL") { ?>
						<option value="COMERCIAL" selected="selected">COMERCIAL</option>
					<?php } else { ?>
						<option value="COMERCIAL">COMERCIAL</option>
					<?php }?>
					<?php if ($rowJuzgado['fueros'] == "COMERCIAL CAP.FEDERAL") { ?>
						<option value="COMERCIAL CAP.FEDERAL" selected="selected">COMERCIAL CAP.FEDERAL</option>
					<?php } else { ?>
						<option value="COMERCIAL CAP.FEDERAL">COMERCIAL CAP.FEDERAL</option>
					<?php }?>
					<?php if ($rowJuzgado['fueros'] == "FEDERAL") { ?>
						<option value="FEDERAL" selected="selected">FEDERAL</option>
					<?php } else { ?>
						<option value="FEDERAL">FEDERAL</option>
					<?php }?>
					<?php if ($rowJuzgado['fueros'] == "FEDERAL SEGURIDAD SOCIAL") { ?>
						<option value="FEDERAL SEGURIDAD SOCIAL" selected="selected">FEDERAL SEGURIDAD SOCIAL</option>
					<?php } else { ?>
						<option value="FEDERAL SEGURIDAD SOCIAL">FEDERAL SEGURIDAD SOCIAL</option>
					<?php }?>
			 </select></p>
        <p><input type="submit" name="guardar" value="Guardar Cambios" /></p>
    </form>
</div>
</body>
</html>
