<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Consulta de D.D.J.J. :.</title>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
}
-->
</style>
</head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>

<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/lib/jquery.blockUI.js" type="text/javascript"></script>
<script src="/lib/funcionControl.js" type="text/javascript"></script>
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

<body bgcolor="#CCCCCC">
<form id="form1" name="form1" method="post" action="ddjjListado.php" onsubmit="return validar(this)">
  <p align="center">
   <input type="reset" name="volver" value="Volver" onClick="location.href = '../moduloInformes.php'" align="center"/>
  </p>
  <p align="center" class="Estilo1">Consulta de D.D.J.J.</p>
  <p> 
   <?php 
  		$err = $_GET['err'];
		if ($err == 1) {
			print("<div align='center' style='color:#FF0000'><b> CUIT SIN DDJJ REGISTRADOS </b></div>");
		}
		if ($err == 2) {
			print("<div align='center' style='color:#FF0000'><b> CUIT NO ENCONTRADO </b></div>");
		}
  ?>
  </p>
  <div align="center">
    <table width="295" border="0">
      
      <tr>
        <td width="174"><div align="right">C.U.I.T.</div></td>
        <td width="173"><div align="left">
            <input name="dato" id="dato" type="text" size="13" />
        </div></td>
      </tr>
    </table>
  </div>
  <p align="center">
    <label>
    <input type="submit" name="Submit" value="Buscar" />
    </label>
  </p>
</form>
<p align="center">&nbsp;</p>
</body>
</html>
