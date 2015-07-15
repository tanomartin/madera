<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Banco USIMRA :.</title>
<style type="text/css">
<!--
.Estilo1 {	font-size: 18px;
	font-weight: bold;
}
-->
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {font-weight: bold}
</style>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
jQuery(function($){
	$("#fecharemito").mask("99-99-9999");
});

function fechaYListarRemito() {
	var CRemito, FRemito;
	CRemito = document.getElementById("selectCuenta").value;
	FRemito = document.getElementById("fecharemito").value;

	if (CRemito == 0) {
		alert("Debe elegir una cuenta para el remito");
		document.getElementById("selectCuenta").focus();
		return false;
	}
	
	if (!esFechaValida(FRemito)) {
		alert("La fecha no es valida");
		document.getElementById("fecharemito").focus();
		return(false);
	}
	else {
		document.location.href = "listarRemitosSueltos.php?ctaRemito="+CRemito+"&fecRemito="+FRemito;
	}
}
</script>
</head>
<body bgcolor="#B2A274">
<div align="center">
  <input type="reset" name="volver" value="Volver" onclick="location.href = '../documentosBancarios.php'" align="left" />
  </div>
  <div align="center">
    <p class="Estilo1">Remitos Sueltos Bancarios</p>
    <p>Cuenta del Remito:
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
    <p>Fecha del Remito:
      <input id="fecharemito" name="fecharemito" type="text" size="10" />
    </p>
    <p class="Estilo1">
      <input type="submit" name="listar" value="Listar" onclick="fechaYListarRemito()" align="left" />
    </p>
  </div>
<p>&nbsp;</p>
</body>
</html>