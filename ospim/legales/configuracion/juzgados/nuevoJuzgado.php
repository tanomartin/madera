<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php");?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Nuevo Juzgado :.</title>
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

<script type="text/javascript">

function validar(formulario) {
	if (formulario.denominacion.value == "") {
		alert("Debe completar la Denominación del Juzgado");
		return(false);
	}
	if (formulario.fuero.value == 0) {
		alert("Debe Seleccionar un Fuero");
		return(false);
	}
	formulario.Submit.disabled = true;
	return true;
}

</script>

<body bgcolor="#CCCCCC">
<div align="center">
  <p>
    <input type="reset" name="volver" value="Volver" onclick="location.href = 'juzgados.php'" align="center"/>
 </p>
  <p><span class="Estilo2">Nuevo Juzgado </span></p>
  <form id="nuevoJuzgado" name="nuevoJuzgado" method="post" action="guardarNuevoJuzgado.php" onSubmit="return validar(this)">
				
	<p>
	  <label></label>
	</p>			
				<p>
				  <label>Denominación
				  <input name="denominacion" type="text" id="denominacion" size="100" maxlength="100"/>
				  </label>
				</p>
				<p>Fuero 
				  <label>
				  <select name="fuero" id="fuero">
				  	<option value="0" selected="selected">SELECCIONE FUERO</option>
				    <option value="CIVIL Y COMERCIAL">CIVIL Y COMERCIAL</option>
				    <option value="COMERCIAL">COMERCIAL</option>
				    <option value="COMERCIAL CAP.FEDERAL">COMERCIAL CAP.FEDERAL</option>
				    <option value="FEDERAL">FEDERAL</option>
				    <option value="FEDERAL SEGURIDAD SOCIAL">FEDERAL SEGURIDAD SOCIAL</option>
			      </select>
				  </label>
				</p>
				<table width="173" border="0">
                  <tr>
                    <td width="167"><div align="center">
                      <input type="submit" name="Submit" value="Guardar" sub/>
                    </div></td>
                  </tr>
                </table>
  </form>
</div>
</body>
</html>
