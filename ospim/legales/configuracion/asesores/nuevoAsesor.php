<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php");
$rs = mysql_query("SELECT MAX(codigo) FROM asesoreslegales");
if ($row = mysql_fetch_row($rs)) {
	$codigo = trim($row[0]) + 1;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Nuevo Gestor :.</title>
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
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = 'asesores.php'" /></p>
  <h3>Nuevo Asesor Legal </h3>
  <form id="modifGestor" name="modifGestor" method="post" action="guardarNuevoAsesor.php?codigo=<?php echo $codigo ?>" onsubmit="return validar(this)">			
	<p>Apellido y Nombre <input name="apeynombre" type="text" id="apeynombre" size="100" maxlength="100"/></p>
	<p> <?php if (isset($_GET['error'])) {
				$error = $_GET['error'];
				if ($error == 1) {
					print("<div align='center' style='color:#FF0000'><b> Debe elegir una o varias delegaciones </b></div>");
				}
              } ?></p>
	<h4>Delegaciones</h4>
		<table width="300" border="1">
                  <?php 
					$i = 0;
					$resDelega= mysql_query("SELECT * FROM delegaciones where codidelega > 1001 and codidelega < 3500", $db);
					while($rowDelega= mysql_fetch_array($resDelega)) { 
						echo '<tr>';
						$codigoDelega = $rowDelega['codidelega'];
						echo '<td><input type="checkbox" id="delega'.$i.'" name="delega'.$i.'" value='.$codigoDelega.'></td>';
						echo '<td><span class="Estilo1">'.$rowDelega["nombre"].'</span><br></td>'; 
						$i = $i + 1;
						echo '</tr>';
					} 
					?>
		</table>
	<p><input type="submit" name="Submit" value="Guardar" /></p>        
  </form>
</div>
</body>
</html>
