<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSession.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Empresas :.</title>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

jQuery(function($){
	$("#cuit").mask("99999999999");
});

function validar(formulario) {
	if (!verificaCuilCuit(formulario.cuit.value)){
		alert("C.U.I.T invalido");
		return false;
	}
	formulario.Submit.disabled = true;
	return true;
}

</script>
</head>

<body style="background-color: <?php echo $bgcolor ?>">
<form id="form1" name="form1" method="post" onsubmit="return validar(this)" action="empresa.php?origen=<?php echo $origen ?>">
	<div align="center">
	  <p><input type="button" name="volver" value="Volver" onclick="location.href = '../menuEmpresa.php?origen=<?php echo $origen ?>'"/> </p>
	  <h3>Módulo De ABM de Empresas</h3>
	  <p><b>C.U.I.T.:</b> <input name="cuit" id="cuit" type="text" size="9" /></p>
	  <p><input type="submit" name="Submit" value="Buscar" /></p>
  	</div>
</form>
</body>
</html>
