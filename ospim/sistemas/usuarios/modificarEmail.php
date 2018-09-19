<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php");
$sqlEmail = "SELECT * FROM emails u WHERE u.id = ".$_GET['id'];
$resEmail = mysql_query($sqlEmail,$db);
$rowEmail = mysql_fetch_assoc($resEmail); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modificar Email :.</title>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

function validar(formulario) {
	if (formulario.email.value == "") {
		alert("Debe ingresar el Email");
		return false;
	}
	if (formulario.usuario.value == 0) {
		alert("Debe seleccionar el Usuario");
		return false;
	}
	if (formulario.password.value == "") {
		alert("Debe ingresar el Password");
		return false;
	}
	formulario.Submit.disabled = true;
	return true;
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = 'emails.php'" /></p>
  <h3>Nuevo Email </h3>
  <form id="nuevoEmail" name="nuevoEmail" method="post" action="guardarModificacionEmail.php" onsubmit="return validar(this)">		
		<input name="id" style="display: none" id="id" size="50" maxlength="50" value="<?php echo $rowEmail['id']?>"/>	
		<table width="400" border="0" style="text-align:left">
        	<tr>
                <td>Email</td>
                <td><input name="email" type="text" id="email" size="50" maxlength="50" value="<?php echo $rowEmail['email']?>" /></td>
            </tr>
            <tr>
                <td>Password</td>
                <td><input name="password" type="text" id="password" size="50" maxlength="50" value="<?php echo $rowEmail['password'] ?>" /></td>
            </tr>
            <tr>
                <td>Usuario</td>
                <td>
                	<select name="usuario" id="usuario">
                		<option value="0">Seleccione Sector</option>
                	<?php 
						$sqlUsuarios = "Select * from usuarios";
						$resUsuarios = mysql_query($sqlUsuarios,$db);
						while ($rowUsuarios = mysql_fetch_assoc($resUsuarios)) { 
							if ($rowUsuarios['id'] == $rowEmail['idusuario'] ) { $selected = "selected"; } else { $selected = '';}?>
                			<option value="<?php echo $rowUsuarios['id'] ?>" <?php echo $selected?>><?php echo $rowUsuarios['nombre'] ?></option>
                  <?php } ?>
                	</select>
                </td>
            </tr>
        </table>
		<p><input type="submit" name="Submit" value="Guardar" /></p>
  </form>
</div>
</body>
</html>