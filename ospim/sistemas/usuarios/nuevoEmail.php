<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Nuevo Insumo :.</title>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

function cargoUsuario(sector) {
	document.forms.nuevoEmail.usuario.length = 0;
	var o = document.createElement("OPTION");
	o.text = 'Seleccione Usuario';
	o.value = '0';
	document.forms.nuevoEmail.usuario.options.add(o);

<?php	$sqlUsuario = "select * from usuarios";
		$resUsuario = mysql_query($sqlUsuario,$db); 
		while ($rowUsuario = mysql_fetch_array($resUsuario)) { ?> 
			o = document.createElement("OPTION");
			o.text = '<?php echo $rowUsuario["nombre"]; ?>';
			o.value = <?php echo $rowUsuario["id"]; ?>;
			if (sector == <?php echo $rowUsuario["departamento"]; ?>) {
				document.forms.nuevoEmail.usuario.options.add(o);
			}
<?php } ?> 
}

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
  <form id="nuevoEmail" name="nuevoEmail" method="post" action="guardarNuevoEmail.php" onsubmit="return validar(this)">		
		<table width="400" border="0" style="text-align:left">
           <tr>
                <td>Email</td>
                <td><input name="email" type="text" id="email" size="50" maxlength="50"/></td>
           </tr>
           <tr>
                <td>Password</td>
                <td><input name="password" type="text" id="password" size="50" maxlength="50"/></td>
           </tr>
           <tr>
              <td>Sector</td>
                <td>
                	<select name="depto" id="depto" onchange="cargoUsuario(document.forms.nuevoEmail.depto[selectedIndex].value)">
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
                <td>Usuario</td>
                 <td><select name="usuario">
                      <option value="0">Seleccione Usuario</option>
					</select></td>
           </tr>
    	</table>
		<p><input type="submit" name="Submit" value="Guardar" /></p>
  </form>
</div>
</body>
</html>