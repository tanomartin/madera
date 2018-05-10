<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Detalle Estado Contable :.</title>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
jQuery(function($){
	$("#cuit").mask("99999999999");
});

function validar(formulario) {
	if (!verificaCuilCuit(formulario.cuit.value)){
		alert("El C.U.I.T. es invalido");
		document.getElementById("cuit").focus();
		return false;
	}
	if (formulario.id.options[formulario.id.selectedIndex].value == 0){
		alert("Debe seleccionar estado contable");
		document.getElementById("id").focus();
		return false;
	}
	$.blockUI({ message: "<h1>Generando Detalle. Aguarde por favor...</h1>" });
	return true;
}
</script>
</head>

<body bgcolor="#CCCCCC">
<form id="form1" name="form1" onsubmit="return validar(this)" method="post" action="detalleEstadoContable.php" >
	<div align="center">
		<p><input type="button" name="volver" value="Volver" onclick="location.href = 'moduloEstadoContable.php'" /> </p>
		<h3>Detalle Estado Contable</h3>
		<?php if(isset($_GET['err'])) { ?> <p align="center" style="color: red;"><b>No existe empresa con el C.U.I.T.: <?php echo $_GET['cuit']?></b></p>   <?php } ?>
		<p><b>C.U.I.T.: </b><label><input id="cuit" name="cuit" type="text" size="10"/></label></p>
		<?php 
			$sqlEstadosContables = "SELECT * FROM estadocontablecontrol ORDER BY anio DESC, mes DESC LIMIT 12";
			$resEstadosContables = mysql_query($sqlEstadosContables,$db);?>		
			<select name="id" id="id">
				<option value='0' selected="selected">Seleccione Estado Contable</option>
		<?php	while($rowEstadoContable = mysql_fetch_array($resEstadosContables)) { ?>
					<option value='<?php echo $rowEstadoContable['id']?>'><?php echo $rowEstadoContable['mes']."-".$rowEstadoContable['anio']?></option>
		  <?php } ?>	
			</select>
		<p><input type="submit" name="Submit" value="Buscar"/></p>
	</div>
</form>
</body>
</html>