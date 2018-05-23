<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$codigo = $_GET['codigo'];
$sqlPrestador = "SELECT prestadores.cuit, prestadores.nombre, prestadores.codigoprestador, 
prestadores.telefono1, prestadores.email1, prestadoresauxiliar.cbu, 
prestadoresauxiliar.cuenta, prestadoresauxiliar.banco, prestadoresauxiliar.interbanking, DATE_FORMAT(prestadoresauxiliar.fechainterbanking ,'%d-%m-%Y') as fechainterbanking
FROM prestadores LEFT JOIN prestadoresauxiliar on prestadores.codigoprestador = prestadoresauxiliar.codigoprestador
WHERE prestadores.codigoprestador = $codigo ORDER BY codigoprestador DESC";
$resPrestador = mysql_query($sqlPrestador,$db);
$rowPrestador = mysql_fetch_array($resPrestador);
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Datos Auxiliares Prestadores :.</title>

<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>

<script type="text/javascript">

jQuery(function($){
	$("#cbu").mask("9999999999999999999999");
});

function validar(formulario) {	
	if (formulario.inter.value == 1) {
		if (formulario.cbu.value == "" || formulario.banco.value == "" || formulario.cuenta.value == "") {
			alert("Si desea asociar el prestador a INTERBANKING todos los datos son obligatorios");
			return false;
		}
	}
	formulario.Submit.disabled = true;
	$.blockUI({ message: "<h1>Guardando Datos Auxiliares. Aguarde por favor...</h1>" });
	return true;
}

</script>

</head>

<body bgcolor="#CCCCCC">
<form id="form1" name="form1" method="post" onsubmit="return validar(this)" action="guardarDatosAuxiliares.php">
	<div align="center">
	  <input type="reset" name="volver" value="Volver" onclick="location.href = 'abmPrestadores.php?codigo=<?php echo $rowPrestador['codigoprestador']?>'" />
	  <h3>Carga Datos Auxiliares Prestadores </h3>
	  <h3><?php echo $rowPrestador['codigoprestador']." - ".utf8_encode($rowPrestador['nombre'])." [".$rowPrestador['cuit']."]" ?></h3>
	  <input type="text" value="<?php echo $rowPrestador['codigoprestador']?>" name="codigo" id="codigo" style="display: none"/>
	  <p><b>C.B.U.</b> <input type="text" value="<?php echo $rowPrestador['cbu']?>" name="cbu" id="cbu" /></p>
	  <p><b>Banco</b> <input type="text" value="<?php echo $rowPrestador['banco']?>" name="banco" id="banco" /></p>
	  <p><b>Cuenta</b> <input type="text" value="<?php echo $rowPrestador['cuenta']?>" name="cuenta" id="cuenta" /></p>
	  <?php if ($rowPrestador['interbanking'] == 0) { ?>
	  	<p>
	  		<b>Asociar a Interbanking</b> 
	  		<select name="inter" id="inter">
	  			<option value="0" selected="selected">NO</option>
	  			<option value="1">SI</option>
	  		</select>
	  	</p>
	  <?php } else { ?>
	  	<p style="color: blue"><b>Prestador Asociado a Interbanking el <?php echo $rowPrestador['fechainterbanking'] ?></b></p>
	  <?php }?>
	  <p><input type="submit" name="Submit" id="Submit" value="Guardar" /></p>
	</div>
</form>
</body>
</html>
