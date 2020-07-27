<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Informes de Facturas :.</title>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

jQuery(function($){
	$("#fecha").mask("99-99-9999");
});

function validar(formulario) {
	if (!esFechaValida(formulario.fecha.value)) {
		alert("La fecha no es valida");
		document.getElementById("fecha").focus();
		return false;
	} 
	formulario.submit.disabled = true;
	$.blockUI({ message: "<h1>Generando Informe, esto puede demorar unos minutos<br> Aguarde por favor</h1>" });
	return true;
}

</script>
</head>

<body bgcolor="#CCCCCC">
	<div align="center">
		<p><input type="button" name="volver" value="Volver" onclick="location.href = 'menuInformes.php'" /></p>
		<form id="form1" name="form1" onsubmit="return validar(this)" method="post" action="facturasFechasExcel.php" >
			<h3>Listado de Facturas a una fecha</h3>
			<?php if (isset($_GET['tiempo'])) { ?>
					<h3 style="color: blue">El Informe de Facturas al <?php echo $_GET['fecha'] ?> fue generado correctamente </br>
											Tiempo de proceso: <?php echo $_GET['tiempo'] ?> minutos.</br>
											Lo encontrara en la carpeta de informes del sistema</h3>	
			<?php } ?>
			<p><b>Fecha de Recepcion: </b><input id="fecha" name="fecha" type="text" value="<?php echo date("d-m-Y",time());?>" size="6" /></p>
			<p><input type="submit" id="submit" name="submit" value="Generar Informe"/></p>
		</form>
	</div>	
</body>
</html>
