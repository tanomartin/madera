<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php"); 
$fecha = $_GET['fecha'];
$nroreq = $_GET['nroreq'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Anular Requerimiento :.</title>
</head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>

<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">

jQuery(function($){
	$("#fecha").mask("99-99-9999");
});

function validar(formulario) {
	if ((formulario.motivo.value == "")) {
		alert("Debe ingresar el motivo de la anulación");
		formulario.motivo.focus();
		return false;
	}
	formulario.Submit.disabled = true;
	return true
}

</script>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><span style="text-align:center">
    <input type="reset" name="volver" value="Volver" onclick="location.href = 'listarRequerimientos.php?fecha=<?php echo $fecha ?>'" align="center"/>
  </span></p>
  	<span class="Estilo2">Anulaci&oacute;n Requerimiento N&uacute;mero <?php echo $nroreq?> </span>
  <form id="form1" name="form1" onSubmit="return validar(this)" method="post" action="guardaAnulacionRequerimiento.php?fecha=<?php echo $fecha ?>">
	  <input type="text" name="nroreq" id="nroreq" value="<?php echo $nroreq?>" size="5" style="display:none"/>
	  <p>
	    <label>Motivo
	    <textarea name="motivo" id="motivo" cols="50" rows="5"></textarea>
	    </label>
	  </p>
	  <p><input type="submit" name="Submit" id="Submit" value="Anular" /></p>
  		</form>
</div>
</body>
</html>