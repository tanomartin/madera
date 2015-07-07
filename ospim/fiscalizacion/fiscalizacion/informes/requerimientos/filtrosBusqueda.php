<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Requerimientos :.</title>
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

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

function deshabilitarDato() {
	document.getElementById('dato').disabled = true;
}
function habilitarDato() {
	document.getElementById('dato').disabled = false;
}

function validar(formulario) {
	if (formulario.group1[3].checked) {
		$.blockUI({ message: "<h1>Generando Informe... <br>Esto puede tardar unos segundos.<br> Aguarde por favor</h1>" });
		return true;
	}
	if (formulario.dato.value == "") {
		alert("Debe ingresar dato de busqueda");
		return false;
	}
	if (formulario.group1[0].checked) {
		resultado = esEnteroPositivo(formulario.dato.value);
		if (!resultado) {
			alert("El Nro. de requerimiento debe ser un numero entero positivo");
			return false;
		} 
		return true; 
	}
	if (formulario.group1[1].checked) {
		if (!verificaCuilCuit(formulario.dato.value)) {
			alert("C.U.I.T. invalido");
			return false;
		}
	}
	if (formulario.group1[2].checked) {
		resultado = esFechaValida(formulario.dato.value);
		if (!resultado) {
			alert("Fecha no valida. Debe ingresar una fecha valida con el siguiente formato dd-mm-aaaa");
			return false;
		} 
		return true; 
	}
	$.blockUI({ message: "<h1>Generando Informe... <br>Esto puede tardar unos segundos.<br> Aguarde por favor</h1>" });
	return true;
} 

</script>

<body bgcolor="#CCCCCC">
<form id="form1" name="form1" method="post" onsubmit="return validar(this)" action="requeListado.php">
  <p align="center">
   <input type="reset" name="volver" value="Volver" onClick="location.href = '../moduloInformes.php'" align="center"/>
  </p>
  <p align="center" class="Estilo1">Consulta de Requerimientos </p>
  <p> 
   <?php 
  		if (isset($_GET['err'])) {
			$err = $_GET['err'];
			if ($err == 1) {
				print("<div align='center' style='color:#FF0000'><b> NO EXISTEN REQUERIMIENTOS CON EL FILTRO PEDIDO</b></div>");
			}
		}
  ?>
  </p>
  <div align="center">
    <table border="0">
      <tr>
        <td rowspan="4"><div align="center"><strong>FILTRO</strong></div></td>
        <td><div align="left">
          <input name="group1" type="radio" value="nrorequerimiento" checked="checked" onclick="habilitarDato()"/>
        Nro Requerimiento </div></td>
      </tr>
      <tr>
        <td><div align="left">
          <input type="radio" name="group1" value="cuit" onclick="habilitarDato()"/>
          C.U.I.T.
        </div></td>
      </tr>
      <tr>
        <td><div align="left">
          <input type="radio" name="group1" value="fecharequerimiento" onclick="habilitarDato()"/>
        Fecha Creacion (dd-mm-aaaa) </div></td>
      </tr>
      <tr>
        <td><div align="left">
          <input type="radio" name="group1" value="noatendidos" onclick="deshabilitarDato()"/>
        No Atendidos </div></td>
      </tr>
      <tr>
        <td height="37"><div align="center"><strong>DATO</strong></div></td>
        <td><div align="left">
          <input type="text" name="dato" id="dato" />
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
</body>
</html>
