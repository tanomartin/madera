<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php");
include($_SERVER['DOCUMENT_ROOT']."/madera/lib/fechas.php"); 

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Elimiar Liquidacion :.</title>

<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>

<style type="text/css" media="print">
.nover {display:none}
</style>
<script type="text/javascript">

function volver() {
	document.forms.eliminarLiqui.action = "liquiListado.php";
	document.forms.eliminarLiqui.submit();
}

function validar() {
	var motivo = document.forms.eliminarLiqui.motivo.value;
	if (motivo == "") {
		alert("Debe ingresar un motivo de eliminación de liquidación");
	} else {
		document.forms.eliminarLiqui.submit();
	}
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<input type="button" name="volver" value="Volver" onclick="volver();" />
	<p><span class="Estilo2">Eliminar Liquidación </span></p>
	<p><span class="Estilo2">Nro Requerimiento "<?php echo $_GET['nroreq'] ?>" </span></p>
	<form id="eliminarLiqui" name="eliminarLiqui" action="eliminarLiquidacion.php" method="post">
		<input type="hidden" value="<?php echo $_GET['dato'] ?>" id="dato" name="dato" />
		<input type="hidden" value="<?php echo $_GET['group1'] ?>" id="group1" name="group1" />
		<input type="hidden" value="<?php echo $_GET['nroreq'] ?>" id="nroreq" name="nroreq" />
		<input type="hidden" value="<?php echo $_GET['cuit'] ?>" id="cuit" name="cuit" />
		<p><b>Motivo </b><textarea name="motivo" id="motivo" cols="50" rows="4"></textarea></p>
		<p><input type="button" name="anular" value="Anular Liquidacion" onclick="validar();"/></p>
	</form>	
</div>
</body>
</html>