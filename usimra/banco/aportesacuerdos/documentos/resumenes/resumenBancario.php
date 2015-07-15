<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Banco USIMRA :.</title>
<style type="text/css">
.Estilo1 {	font-size: 18px;
	font-weight: bold;
}
.Estilo2 {font-weight: bold}
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
jQuery(function($){
	$("#fechaemision").mask("99-99-9999");
});

function fechaYListarResumen() {
	var CResumen, FEmision;
	CResumen = document.getElementById("selectCuenta").value;
	FEmision = document.getElementById("fechaemision").value;

	if (CResumen == 0) {
		alert("Debe elegir una cuenta para el resumen");
		document.getElementById("selectCuenta").focus();
		return false;
	}
	
	if (!esFechaValida(FEmision)) {
		alert("La fecha no es valida");
		document.getElementById("fechaemision").focus();
		return(false);
	}
	else {
		document.location.href = "listarImputaciones.php?ctaResumen="+CResumen+"&fecEmision="+FEmision;
	}
}
</script>
</head>
<body bgcolor="#B2A274">
<div align="center"><p>
<input type="reset" name="volver" value="Volver" onclick="location.href = '../documentosBancarios.php'"/></p></div>
<div align="center">
  <p class="Estilo1">Resumenes Bancarios</p>
  <p>Cuenta del Resumen:
    <label>
      <select name="selectCuenta"  id="selectCuenta">
        <option value="0" selected="selected">Seleccione una Cuenta</option>
        <?php 
					$sqlLeeCuenta="SELECT * FROM cuentasusimra";
					$resultLeeCuenta=mysql_query($sqlLeeCuenta,$db);
					while ($rowLeeCuenta=mysql_fetch_array($resultLeeCuenta)) { ?>
        <option value="<?php echo $rowLeeCuenta['codigocuenta']?>"><?php echo $rowLeeCuenta['descripcioncuenta']?></option>
        <?php } ?>
      </select>
      </label>
  </p>
  <p>Fecha de Emision:
    <input id="fechaemision" name="fechaemision" type="text" size="10" /></p>
</div>
<div align="center">
  <input type="button" name="listar" value="Listar" onclick="fechaYListarResumen()" align="left" />
</div>
</body>
</html>