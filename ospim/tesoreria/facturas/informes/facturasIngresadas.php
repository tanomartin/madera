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
	$("#fechaingreso").mask("99-99-9999");
});

function habilitar() {
	document.getElementById('submit').disabled = false;
}

function validar(formulario) {
	document.getElementById('error').innerHTML= '';
	if (!esFechaValida(formulario.fechaingreso.value)) {
		alert("La fecha no es valida");
		document.getElementById("fechaingreso").focus();
		return false;
	} 
	formulario.submit.disabled = true;
	return true;
}
</script>
</head>

<body bgcolor="#CCCCCC">
	<div align="center">
		<p><input type="button" name="volver" value="Volver" onclick="location.href = 'menuInformes.php'" /></p>
		<form id="form1" name="form1" onsubmit="return validar(this)" method="post" action="facturasIngresadasExcel.php" enctype="multipart/form-data" >
			<h3>Listado de Facturas ingresadas en una fecha</h3>
			<label id="error"><?php if (isset($_GET['err'])) { ?><b style="color: brown">No existen facturas ingresadas para la fecha '<?php echo $_GET['fechaingreso']?>'</b> <?php }?></label>
			<p><b>Fecha de Ingreso: </b><input id="fechaingreso" name="fechaingreso" type="text" value="<?php echo date("d-m-Y",time());?>" size="6" onchange="habilitar()"/></p>
			<p><input type="submit" id="submit" name="submit" value="Generar Informe"/></p>
		</form>
	</div>	
</body>
</html>