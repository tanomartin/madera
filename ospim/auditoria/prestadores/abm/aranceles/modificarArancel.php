<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php");
include($libPath."funcionespracticas.php");

$codigo = $_GET['codigo'];
$id = $_GET['id'];
$sqlConsultaPresta = "SELECT codigoprestador, nombre FROM prestadores WHERE codigoprestador = $codigo";
$resConsultaPresta = mysql_query($sqlConsultaPresta,$db);
$rowConsultaPresta = mysql_fetch_assoc($resConsultaPresta);

$sqlArancel = "SELECT * FROM aranceles WHERE id = $id";
$resArancel = mysql_query($sqlArancel,$db);
$rowArancel = mysql_fetch_assoc($resArancel);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modificar Arancel :.</title>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(function($){
	$("#fechaInicio").mask("99-99-9999");
	$("#fechaFin").mask("99-99-9999");
});

function validar(formulario) {
	var fechaInicio = formulario.fechaInicio.value;
	var fechaFin = formulario.fechaFin.value;
	var monto = formulario.monto.value;
	if (fechaInicio == "") {
		alert("Debe ingresar un fecha de inicio de contrato");
		return(false);
	} else {
		if (!esFechaValida(fechaInicio)) {
			alert("La fecha de Inicio no es valida");
			return(false);
		} 
	}
	if (fechaFin != "") {
		if (!esFechaValida(fechaFin)) {
			alert("La Fecha Fin no es valida");
			return(false);
		} else {
			fechaInicio = new Date(invertirFecha(fechaInicio));
			fechaFin = new Date(invertirFecha(fechaFin));
			if (fechaInicio >= fechaFin) {
				alert("La Fecha Fin debe ser superior a la Fecha de Inicio");
				return(false);
			}
		}
	}
	if (monto != "") {
		if (!isNumberPositivo(monto) || monto == 0) {
			alert("El monto del arancel debe ser un numero positivo");
			return false;
		}
	} else {
		alert("El monto del arancel es obligatorio");
		return false;
	}
	formulario.Submit.disabled = true;
	return true;
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = 'arancelesPrestador.php?codigo=<?php echo $codigo ?>'" /></p>
  <h3>Modificación Contratos </h3>
  <table width="500" border="1">
    <tr>
      <td width="163"><div align="right"><strong>C&oacute;digo</strong></div></td>
      <td width="321"><div align="left"><strong><?php echo $rowConsultaPresta['codigoprestador']  ?></strong></div></td>
    </tr>
    <tr>
      <td><div align="right"><strong>Nombre / Raz&oacute;n Social</strong></div></td>
      <td><div align="left"><?php echo $rowConsultaPresta['nombre'] ?></div></td>
    </tr>
  </table>
  <h3>Datos Arancel</h3>
  <?php if (isset($_GET['err'])) { ?>
  			<h4><font color='#FF0000'>Existe un contrato con fecha de finalización posterior a la fecha de inicio que quiere ingresar</font></h4>
 <?php	 } ?>
  <form id="modifArancel" name="modifArancel" method="post" onsubmit="return validar(this)" action="guardarModificacionArancel.php?codigo=<?php echo $codigo ?>&id=<?php echo  $rowArancel['id'] ?>">
    <p> <b>Fecha Inicio: </b><input type="text" name="fechaInicio" id="fechaInicio" size="8" value="<?php echo invertirFecha($rowArancel['fechainicio']) ?>"/> - 
    <?php if ($rowArancel['fechafin'] == NULL) {
				$valorfin = "-";
		   } else {
		   		$valorfin = invertirFecha($rowArancel['fechafin']);
		   }
	?>
	<b>Fecha Fin: </b><input type="text" name="fechaFin" id="fechaFin" size="8" value="<?php echo $valorfin ?>"/> -
	<b>Monto:</b> <input type="text" name="monto" id="monto" size="8" value="<?php echo $rowArancel['monto'] ?>"/>
    </p>
    <p>
      <label><input type="submit" name="Submit" value="Guardar" /></label>	
	</p>
  </form>
</div>
</body>
</html>