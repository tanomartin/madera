<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Ordenes Pago :.</title>
<link rel="stylesheet" href="/madera/lib/tablas.css"/>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">

function validar(formulario) {
	if(formulario.dato.value == "") {
		alert("Debe colocar un dato de busqueda");
		return false;
	}
	if (formulario.filtro[0].checked) {
		resultado = esEnteroPositivo(formulario.dato.value);
		if (!resultado) {
			alert("El Código de Prestador debe ser un numero entero positivo");
			return false;
		} 
	}
	if (formulario.filtro[1].checked) {
		if (!verificaCuilCuit(formulario.dato.value)) {
			alert("C.U.I.T. invalido");
			return false;
		}
	}
	$.blockUI({ message: "<h1>Generando Busqueda... <br>Esto puede tardar unos segundos.<br> Aguarde por favor</h1>" });
	return true;
}
</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = '../menuTesoreria.php'" /></p>	
	<form id="buscarFacturas" name="buscarFacturas" method="post" onsubmit="return validar(this)" action="listadoFacturas.php">
	  	<h3>Módulo Ordenes de Pago </h3>
    	<table>
      		<tr>
        		<td rowspan="2"><b>Buscar por </b></td>
        		<td><input type="radio" name="filtro"  value="0" checked="checked" /> Código </td>
      		</tr>
      		<tr>
        		<td><input type="radio" name="filtro" value="1" /> C.U.I.T.</td>
      		</tr> 
		</table>
    	<p><strong>Dato</strong> <input name="dato" type="text" id="dato" size="14" /></p>
    	<p><input type="submit" name="Buscar" value="Buscar" /></p>
    	<?php 
	  		if (isset($_GET['err'])) {
				$err = $_GET['err'];
				$des =  $_GET['error'];
				if ($err == 1) {
					echo $des;
					echo "<font color='blue'>NO EXISTEN FACTURAS PENDIENTES DE PAGO</font>";
				}
				if ($err == 2) {
					echo $des;
					echo "<font color='red'>NO EXISTEN PRESTADOR CON ESTOS DATOS</font>";
				}
	  		}
	  	?>
	</form>	
</div>
</body>
</html>
