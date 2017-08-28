<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 

$sqlCabPadronToDelete = "SELECT * FROM padronssscabecera WHERE fechadelete is null ORDER BY id LIMIT 1";
$resCabPadronToDelete = mysql_query($sqlCabPadronToDelete, $db);
$rowCabPadronToDelete = mysql_fetch_array($resCabPadronToDelete);

$sqlCabPadronActive = "SELECT * FROM padronssscabecera WHERE fechacierre is null";
$resCabPadronActive = mysql_query($sqlCabPadronActive, $db);
$canCabPadronActive = mysql_num_rows($resCabPadronActive);
if ($canCabPadronActive == 1) {
	$rowCabPadronActive = mysql_fetch_array($resCabPadronActive);
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Padron SSS :.</title>

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script>

function validar(formulario) {
	$.blockUI({ message: "<h1>Generando Proceso de eliminacion y cierre de padron<br>Aguarde por favor...</h1>" });
	return true;
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = 'moduloSSS.php'" /></p>
  <h2>Elimnacion y Cierre de Padron SSS</h2>
  <?php if ($canCabPadronActive == 1) {?>
  <form id="form1" name="form1" method="post" action="generarEliminacionCierre.php" onsubmit="return validar(this)">
    <h3>Periodo a Eliminar Histórico </h3>
    <h3><font color="blue"><?php echo "ID: ".$rowCabPadronToDelete['id']." - PERIODO: ".$rowCabPadronToDelete['mes']."-".$rowCabPadronToDelete['anio'] ?></font></h3>
    <h3>Periodo a Cerrar</h3>
    <h3><font color="blue"> <?php echo "ID: ".$rowCabPadronActive['id']." - PERIODO: ".$rowCabPadronActive['mes']."-".$rowCabPadronActive['anio'] ?></font></h3>
    <input type="text" value="<?php echo $rowCabPadronToDelete['id'] ?>" name="idDelete" id="idDelete" style="display: none"/> 
    <input type="text" value="<?php echo $rowCabPadronActive['id'] ?>" name="idClose" id="idClose" style="display: none" /> 
    <p><input type="submit" name="Submit" value="Eliminar y Cerrar Padrones" /> </p>
  </form>
  <?php } else { ?>
  	<h3><font color="blue">No existe padron activo para cerrar</font></h3>
  <?php } ?>
  </div>
</body>
</html>
