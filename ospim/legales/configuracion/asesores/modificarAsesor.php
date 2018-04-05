<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
$codigo = $_GET['codigo'];

$sqlAsesor = "select * from asesoreslegales where codigo = $codigo";
$resAsesor = mysql_query($sqlAsesor,$db); 
$rowAsesor = mysql_fetch_array($resAsesor);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modificar Asesores :.</title>
<script type="text/javascript">

function validar(formulario) {
	if (formulario.apeynombre.value == "") {
		alert("Debe completar en Nombre y el Apellido");
		return(false);
	}

	var grupo = formulario.delega;
	var total = grupo.length;
	var checkeados = 0; 
	for (var i = 0; i < total; i++) {
		if (grupo[i].checked) {
			checkeados++;
		}
	}
	if (checkeados == 0) {
		alert("Debe seleccionar por lo menos una delegacion");
		return false;
	}
	formulario.guardar.disabled = true;
	formulario.eliminar.disabled = true;
	return true;
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = 'asesores.php'" /></p>
  <h3>Modificar Asesores Legales </h3>
  <form id="modifGestor" name="modifGestor" method="post" action="guardarModifAsesor.php?codigo=<?php echo $codigo ?>" onsubmit="return validar(this)">
	<?php
		$sqlCabJuicios = "select * from cabjuiciosospim where codasesorlegal = $codigo";
		$resCabJuicios = mysql_query($sqlCabJuicios,$db); 
		$canCabJuicios = mysql_num_rows($resCabJuicios); 		  
		if ($canCabJuicios == 0) { ?>
			<p><input type="button" name="eliminar" onclick="location.href = 'eliminarAsesor.php?codigo=<?php echo $codigo ?>'" value="Eliminar" /></p>
 <?php } ?>
	<p>Codigo: <b> <?php echo $rowAsesor['codigo']; ?> </b></p>
	<p>Apellido y Nombre <input name="apeynombre" type="text" id="apeynombre" value="<?php echo $rowAsesor['apeynombre'];?>" size="100" maxlength="100"/></p>
	<h4>Delegaciones</h4>
	<table width="300" border="1">
		<?php 
					$i = 0;
					$resDelega= mysql_query("SELECT * FROM delegaciones where codidelega > 1001 and codidelega < 3500", $db);
					while($rowDelega= mysql_fetch_array($resDelega)) { 
						$codigoDelega = $rowDelega['codidelega'];
						$sqlExiste = "select * from asesoreslegales where codigo = $codigo and codidelega = $codigoDelega";
						$resExiste = mysql_query($sqlExiste,$db); 
						$numExiste = mysql_num_rows($resExiste); ?>
						<tr>
			 <?php		if ($numExiste == 1) { ?>
							<td><input type="checkbox" id="delega" name="delega<?php echo $i ?>" value="<?php echo $codigoDelega ?>" checked="checked" /></td>
			<?php		} else { ?>
							<td><input type="checkbox" id="delega" name="delega<?php echo $i ?>" value="<?php echo $codigoDelega ?>" /></td>
			<?php		}
						$i = $i + 1; ?>
							<td><span class="Estilo1"><?php echo $rowDelega["nombre"] ?></span></td>
						</tr>
			<?php	} ?>
    </table>
	<p><input type="submit" name="guardar" value="Guardar Cambios" /></p>
  </form>
</div>
</body>
</html>
