<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionUsimra.php"); 
$today = date("d-m-Y");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Requerimientos :.</title>
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
	if (!esFechaValida(formulario.fecha.value)) {
		alert("Debe ingresar una fecha valida");
		formulario.fecha.focus();
		return false;
	}	
	return true
}

</script>

<body bgcolor="#B2A274">
<div align="center">
  <p><span style="text-align:center">
    <input type="reset" name="volver" value="Volver" onclick="location.href = '../menuFiscalizaciones.php'" align="center"/>
  </span></p>
  	<span class="Estilo2">Men&uacute; Requerimiento </span>
        <form id="form1" name="form1" onSubmit="return validar(this)" method="post" action="listarRequerimientos.php">
			<p><strong>Ingrese la Fecha de los requermientos a listar</strong></p>
			<?php 
				if (isset($_GET['err'])) {
					$err = $_GET['err'];
					if ($err == 1) {
						$fechaBuscada = $_GET['fecha'];
						print("<div align='center' style='color:#FF0000'><b> NO EXISTEN REQUERMIENTOS PARA LA FECHA ".$fechaBuscada."</b></div>");
					}
				}
			?>
			<p><input name="fecha" type="text" id="fecha" size="10" value="<?php echo $today ?>"/>
		  </p>
			<p><input type="submit" name="Submit" value="Listar" /></p>
        </form>
</div>
</body>
</html>