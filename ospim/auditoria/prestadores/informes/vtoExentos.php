<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modulo Informes de VTO Exentos :.</title>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
	jQuery(function($){
		$("#fechahasta").mask("99-99-9999");
	});
	
	function validar(formulario) {
		if (!esFechaValida(formulario.fechahasta.value)) {
			alert("La fecha no es valida");
			document.getElementById("fechahasta").focus();
			return(false);
		}
		formulario.submit.disabled = true; 
		return true;
	}
</script>

</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<form id="form1" name="form1" onsubmit="return validar(this)" method="post" action="vtoExentosExcel.php" >
		<p><input type="button" name="volver" value="Volver" onclick="location.href = 'moduloInformes.php'" /> </p>
		<h3>Informe de Prestadores Fecha VTO. Exento</h3>
		<p><b>Hasta el: </b><input id="fechahasta" name="fechahasta" type="text" value="<?php echo date("d/m/Y",time());?>" size="10"/></p>
		<p><input type="submit" name="submit" value="Generar Informe"/></p>
	</form>
</div>
</body>
</html>
