<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php");?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Nueva Secretaria :.</title>
<script type="text/javascript">

function validar(formulario) {
	if (formulario.denominacion.value == "") {
		alert("Debe completar la Denominación de la Secretaria");
		return(false);
	}
	if (formulario.juzgado.value == 0) {
		alert("Debe Seleccionar un Juzgado");
		return(false);
	}
	formulario.Submit.disabled = true;
	return true;
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = 'secretarias.php'" /></p>
  <h3>Nueva Secretaria </h3>
  <form id="nuevaSecre" name="nuevaSecre" method="post" action="guardarNuevaSecretaria.php" onsubmit="return validar(this)">			
	<p>Denominación <input name="denominacion" type="text" id="denominacion" size="100" maxlength="100"/></p>
	<p>Juzgado <select name="juzgado" id="juzgado">
				   	<option value="0" selected="selected">SELECCIONE JUZGADO</option>
					<?php 
						$sqlJuzgados = "select * from juzgados";
						$resJuzgados = mysql_query($sqlJuzgados,$db); 
						while ($rowJuzgados = mysql_fetch_assoc($resJuzgados)) { ?>
							<option value='<?php echo $rowJuzgados['codigojuzgado']?>'><?php echo $rowJuzgados['codigojuzgado']?> - <?php echo $rowJuzgados['denominacion'] ?></option>	
				<?php 	} ?>
				  </select></p>
	<p><input type="submit" name="Submit" value="Guardar" /></p>
  </form>
</div>
</body>
</html>
