<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSession.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Empresas :.</title>
<link rel="stylesheet" href="/madera/lib/tablas.css"/>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css" type="text/css" id="" media="print, projection, screen" />
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery-ui.min.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

function validar(formulario) {
	if (formulario.descripcion.value == "") {
		alert("Debe ingresar la descripcion del pedido a realizar");
		return false;
	}
	$.blockUI({ message: "<h1>Guardando Pedido...<br> Aguarde por favor</h1>" });
	document.getElementById(formulario.guardar).disabled  = true;
	return true
}

</script>

</head>
<body style="background-color: <?php echo $bgcolor ?>">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = 'moduloPedidos.php?origen=<?php echo $origen ?>'"/> </p>
  <h3>Nueva Correcciones </h3>
  <h3>Usuario <font color="blue">"<?php echo $_SESSION['usuario']?>"</font> </h3>
  <form method="post" id="nuevoPedido" onsubmit="return validar(this)" action="guardarNuevoPedido.php?origen=<?php echo $origen ?>">
	<p><b>Descripcion del Pedido</b></p>
	<p><textarea rows="6" cols="100" name="descripcion" id="descripcion"></textarea></p>
	<input type="submit" id="guardar" name="guardar" value="Guardar"/>
  </form>
</div>
</body>
</html>
