<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
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
</style>
<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/lib/funcionControl.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
jQuery(function($){
	$("#fecharemesa").mask("99-99-9999");
});

function fechaYListarRemesa() {
	var CRemesa, FRemesa;
	CRemesa = document.getElementById("selectCuenta").value;
	FRemesa = document.getElementById("fecharemesa").value;

	if (CRemesa == 0) {
		alert("Debe elegir una cuenta para la remesa");
		document.getElementById("selectCuenta").focus();
		return false;
	}
	
	if (!esFechaValida(FRemesa)) {
		alert("La fecha no es valida");
		document.getElementById("fecharemesa").focus();
		return(false);
	}
	else {
		document.location.href = "listarRemesas.php?ctaRemesa="+CRemesa+"&fecRemesa="+FRemesa;
	}
}
</script>
</head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {font-weight: bold}
</style>
<body bgcolor="#B2A274">
<table width="762" height="142" border="1" align="center">
  <tr align="center" valign="top">
    <td height="23" colspan="2"><div align="center"><span class="Estilo1">Remesas Bancarias</span></div></td>
  </tr>
  <tr align="center" valign="top">
    <td height="27" colspan="2">
	  <p>Cuenta de la Remesa:
        <label>
        <select name="selectCuenta"  id="selectCuenta">
		          <option value=0 selected="selected">Seleccione una Cuenta</option>
		          <?php 
					$sqlLeeCuenta="SELECT * FROM cuentasusimra";
					$resultLeeCuenta=mysql_query($sqlLeeCuenta,$db);
					while ($rowLeeCuenta=mysql_fetch_array($resultLeeCuenta)) { ?>
		          <option value="<?php echo $rowLeeCuenta['codigocuenta']?>"><?php echo $rowLeeCuenta['descripcioncuenta']?></option>
		          <?php } ?>
       </select>
       </label>
      </p>
      <p>Fecha de la Remesa: 
        <input id="fecharemesa" name="fecharemesa" type="text" size="10" />
      </p></td>
  </tr>
  <tr align="center" valign="top">
    <td width="245" height="39" valign="middle">
	  <div align="center">
	    <input type="reset" name="volver" value="Volver" onclick="location.href = 'documentosBancarios.php'" align="left" />
	  </div>	</td>
	<td width="250" height="39" valign="middle">
	  <div align="center">
        <input type="submit" name="listar" value="Listar" onclick="fechaYListarRemesa()" align="left" />
	  </div></td>
  </tr>
</table>
</body>
</html>