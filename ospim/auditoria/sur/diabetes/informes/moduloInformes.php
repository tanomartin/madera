<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Informes Diabetes :.</title>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">
	function informes(dire) {
		location.href = dire;
	}
</script>
</head>

<body bgcolor="#CCCCCC">
	<div align="center">
	  <p><input type="button" name="volver" value="Volver" onclick="location.href = '../moduloDiabetes.php'" /> </p>
	  <h3>Mení Consultas e Informes</h3>
	  <table width="400" border="1" style="text-align: center">
	    <tr>
	      	<td width="200">
				<p>LISTADO DE DIABETICOS</p>
	          	<p><a href="#"><img onclick="location.href='listadoExcelDiabeticos.php'" src="img/excellogo.png" width="90" height="90" border="0"/></a></p>
	       	</td>
	       	<td width="200">
				<p>DETALLE DE PRESENTACIONES</p>
	          	<p><a href="datellePresentacion.php"><img src="img/excellogo.png" width="90" height="90" border="0"/></a></p>
	       	</td>
	  	</tr>
	  </table>
	</div>
</body>
</html>