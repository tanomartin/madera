<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Nuevo Insumo :.</title>

<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

function validar(formulario) {
	if (formulario.nombre.value == "") {
		alert("Debe ingresar el nombre");
		return false;
	}
	if (formulario.depto.value == 0) {
		alert("Debe seleccionar el Sector");
		return false;
	}
	if (formulario.nombrepc.value == "") {
		alert("Debe ingresar el nombre de la PC");
		return false;
	}
	if (formulario.usuariowin.value == "") {
		alert("Debe ingresar el nombre de usuario de Windows");
		return false;
	}	
	if (formulario.passwin.value == "") {
		alert("Debe ingresar la contraseña de Windows");
		return false;
	}
	formulario.Submit.disabled = true;
	return true;
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p>
    <input type="button" name="volver" value="Volver" onclick="location.href = 'usuarios.php'" />
 </p>
  <p><span class="Estilo2">Nuevo Usuario </span></p>
  <form id="nuevoInsumo" name="nuevoInsumo" method="post" action="guardarNuevoUsuario.php" onsubmit="return validar(this)">		
			<table width="850" border="0" style="text-align:left">
              <tr>
                <td>Nombre</td>
                <td><input name="nombre" type="text" id="nombre" size="50" maxlength="50"/></td>
                <td>Sector</td>
                <td>
                	<select name="depto" id="depto">
                		<option value="0">Seleccione Sector</option>
                	<?php 
						$sqlDepto = "Select * from departamentos";
						$resDepto = mysql_query($sqlDepto,$db);
						while ($rowDepto = mysql_fetch_assoc($resDepto)) { ?>
                			<option value="<?php echo $rowDepto['id'] ?>"><?php echo $rowDepto['nombre'] ?></option>
                  <?php } ?>
                	</select>
                </td>
              </tr>
              <tr>
                <td>Nombre PC</td>
                <td><input name="nombrepc" type="text" id="nombrepc" size="50" maxlength="50"/></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td>Usuario Win</td>
                <td><input name="usuariowin" type="text" id="usuariowin" size="25" maxlength="25"/></td>
                <td>Password Win </td>
                <td><input name="passwin" type="text" id="passwin" size="25" maxlength="25"/></td>
              </tr>
               <tr>
                <td>Usuario Sistema</td>
                <td><input name="usuariosistema" type="text" id="usuariosistema" size="25" maxlength="25"/></td>
                <td>Password Sistema </td>
                <td><input name="passsistema" type="text" id="passsistema" size="25" maxlength="25"/></td>
              </tr>
              <tr>
                <td>Puerto</td>
                <td><input name="puerto" type="text" id="puerto" size="25" maxlength="25"/></td>
                <td>Conector</td>
                <td><input name="conector" type="text" id="conector" size="25" maxlength="25"/></td>
              </tr>
            </table>
			<p><input type="submit" name="Submit" value="Guardar" /></p>
  </form>
</div>
</body>
</html>