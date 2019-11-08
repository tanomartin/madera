<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");  ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Consulta de Aportes :.</title>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

jQuery(function($){
	$("#cuit").mask("99999999999");
});

</script>
</head>

<body bgcolor="#B2A274">
<form id="form1" name="form1" method="post" action="aportesListado.php">
  <div align="center">
    <p><input type="button" name="volver" value="Volver" onclick="location.href = '../moduloInformes.php'" /></p>
	<h3>Consulta de Aportes por C.U.I.T.</h3>
    <p> <?php if (isset($_GET['err'])) {
   			$err = $_GET['err'];
			if ($err == 1) {
				print("<div align='center' style='color:#FF0000'><p><b> CUIT SIN APORTES REGISTRADOS </b></p></div>");
			}
			if ($err == 2) {
				print("<div align='center' style='color:#FF0000'><p><b> CUIT NO ENCONTRADO </b></p></div>");
			}
  		 } ?>	</p>
    <p><b> C.U.I.T.: </b><input name="cuit" id="cuit" type="text" size="10" /> </p>
    <p><input type="submit" name="Submit" value="Buscar" /></p>
  </div>
</form>
</body>
</html>
