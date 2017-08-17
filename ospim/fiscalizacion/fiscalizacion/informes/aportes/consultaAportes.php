<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Consulta de Aportes :.</title>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
}
-->
</style>

<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

jQuery(function($){
	$("#dato").mask("99999999999");
});

function validar(formulario) {
	if (formulario.dato.value == "") {
		alert("Debe ingresar el C.U.I.T. a buscar");
		return false;
	}
	$.blockUI({ message: "<h1>Generando Informe... <br>Esto puede tardar unos segundos.<br> Aguarde por favor</h1>" });
	formulario.action = direccion;
	formulario.submit();
}

</script>
</head>

<body bgcolor="#CCCCCC">
<form id="form1" name="form1" method="post" action="aportesListado.php" onsubmit="return validar(this)">
  <p align="center">
   <input type="reset" name="volver" value="Volver" onclick="location.href = '../moduloInformes.php'" />
  </p>
  <p align="center" class="Estilo1">Consulta de Aportes</p>
  <p> 
   <?php 
    if (isset($_GET['err'])) {
  		$err = $_GET['err'];
		if ($err == 1) {
			print("<div align='center' style='color:#FF0000'><b> CUIT SIN APORTES REGISTRADOS </b></div>");
		}
		if ($err == 2) {
			print("<div align='center' style='color:#FF0000'><b> CUIT NO ENCONTRADO </b></div>");
		}
    }
  ?>
  </p>
  <div align="center">
    <table width="294" border="0">
      
      <tr>
        <td width="137"><div align="right"> C.U.I.T.</div></td>
        <td width="147"><div align="left">
          <input name="dato" id="dato" type="text" size="13" />
        </div></td>
      </tr>
    </table>
    <label></label>
    <label></label>
  </div>
  <div align="center"></div>
  <p align="center"><input type="submit" name="Submit" value="Buscar" /></p>
</form>
<p align="center">&nbsp;</p>
</body>
</html>
