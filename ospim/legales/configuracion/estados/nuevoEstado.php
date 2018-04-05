<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php");
$rs = mysql_query("SELECT MAX(codigo) FROM estadosprocesales");
if ($row = mysql_fetch_row($rs)) {
	$codigo = trim($row[0]) + 1;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Nuevo Juzgado :.</title>
<script type="text/javascript">

function validar(formulario) {
	if (formulario.descri.value == "") {
		alert("Debe completar la Descripción del Estado Procesal");
		return(false);
	}
	formulario.Submit.disabled = true;
	return true;
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="reset" name="volver" value="Volver" onclick="location.href = 'estados.php'"/></p>
  <h3>Nuevo Estado </h3>
  <form id="nuevoEstado" name="nuevoEstado" method="post" action="guardarNuevoEstado.php?codigo=<?php echo $codigo ?>" onsubmit="return validar(this)">
	<p>Nuevo Código: <b><?php echo $codigo ?></b></p>			
	<p>Descripción <input name="descri" type="text" id="descri" size="100" maxlength="100"/></p>
	<p><input type="submit" name="Submit" value="Guardar" /></p>
  </form>
</div>
</body>
</html>
