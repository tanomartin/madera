<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspimSistemas.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Stock :.</title>
</head>

<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
.Estilo7 {font-weight: bold}
</style>
<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">
function validar(formulario) {
	if (formulario.selectDelegacion.options[formulario.selectDelegacion.selectedIndex].value == 0) {
		alert("Debe seleccionar una Delegación");
		return false;
	}
	var dele = formulario.selectDelegacion.options[formulario.selectDelegacion.selectedIndex].value;
	var nombre = formulario.selectDelegacion.options[formulario.selectDelegacion.selectedIndex].label;
	var mensaje = "<h1>Actualizando Delegacion "+ nombre + " ("+dele+")"+" <br>Aguarde por favor...</h1>"
	$.blockUI({ message: mensaje });
	return true;
}

</script>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><span style="text-align:center">
    <input type="reset" name="volver" value="Volver" onclick="location.href = 'menuActualizacionOspim.php'" align="center"/>
  </span></p>
  <p><span class="Estilo2">Men&uacute; Actualizacion Intranet O.S.P.I.M. </span></p>
  <form id="form1" name="form1" method="post" action="actualizarIntraDelegacion.php" onsubmit="return validar(this)">
    <p><strong>Seleccionar Delegación a Actualizar</strong></p>
    <table>
	<tr>
		<td>
			<div align="left">
			  <select name="selectDelegacion" id="selectDelegacion">
			    <option value="0">Seleccione una Delegación </option>        
		        <?php 
				$resDelega = mysql_query("SELECT * FROM delegaciones where codidelega >= 1002 and codidelega <= 3101 ", $db);
				while($rowDelega = mysql_fetch_array($resDelega)) { 
					print("<option value='".$rowDelega['codidelega']."' label='".$rowDelega['nombre']."'>".$rowDelega['nombre']."</option>");        
				}
			?>
	          </select>
        </div></td>
	  </tr>
    </table>  
    <p>
      <label>
      <input type="submit" name="Submit" value="Actualizar Delegación" />
      </label>
    </p>
  </form>
</div>
</body>
</html>
