<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionOspim.php");
$rs = mysql_query("SELECT MAX(codigo) FROM gestoresdeacuerdos");
if ($row = mysql_fetch_row($rs)) {
	$codigo = trim($row[0]) + 1;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Nuevo Gestor :.</title>
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
	if (formulario.apeynombre.value == "") {
		alert("Debe completar en Nombre y el Apellido");
		return(false);
	}
	formulario.Submit.disabled = true;
	return true;
}

</script>

<body bgcolor="#CCCCCC">
<div align="center">
  <p>
    <input type="reset" name="volver" value="Volver" onclick="location.href = 'gestores.php'" align="center"/>
 </p>
  <p><span class="Estilo2">Nuevo Gestor de Acuerdos </span></p>
  <form id="modifGestor" name="modifGestor" method="POST" action="guardarNuevoGestor.php?codigo=<?php echo $codigo ?>" onSubmit="return validar(this)">
				
				<p>
				  <label>Codigo: <b>  <?php echo $codigo; ?> </b></label>
				</p>			
				<p>
				  <label>Apellido y Nombre 
				  <input name="apeynombre" type="text" id="apeynombre" size="100" maxlength="100"/>
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
