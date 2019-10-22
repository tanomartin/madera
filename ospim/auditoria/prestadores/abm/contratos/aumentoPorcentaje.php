<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php");
include($libPath."funcionespracticas.php");

$codigo = $_GET['codigo'];
$idcontrato = $_GET['idcontrato'];
$sqlConsultaPresta = "SELECT codigoprestador, nombre FROM prestadores WHERE codigoprestador = $codigo";
$resConsultaPresta = mysql_query($sqlConsultaPresta,$db);
$rowConsultaPresta = mysql_fetch_assoc($resConsultaPresta);

$sqlContrato = "SELECT * FROM cabcontratoprestador WHERE idcontrato = $idcontrato";
$resContrato = mysql_query($sqlContrato,$db);
$rowContrato = mysql_fetch_assoc($resContrato); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modificar por Porcentaje Contrato :.</title>

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script type="text/javascript">

jQuery(function($){
	$("#fechaInicio").mask("99-99-9999");
	$("#fechaFin").mask("99-99-9999");
	$("#porcentaje").mask("99.99");
});

function validar(formulario) {
	var porcentaje = formulario.porcentaje.value;
	var fechaInicio = formulario.fechaInicio.value;
	var fechaFin = formulario.fechaFin.value;
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
	if (porcentaje == "") {
		alert("Debe ingresar el porcentaje de aumento para el contrato. Valores entre 00.00 - 99.99");
		return(false);
	}
	formulario.Submit.disabled = true;
	return true;
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = 'contratosPrestador.php?codigo=<?php echo $codigo ?>'" /></p>
  <h3>Duplicacion de Contrato con Aumento por Porcentaje </h3>
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
  <form id="modifContrato" name="modifContrato" method="post" onsubmit="return validar(this)" action="guardarAumentoContrato.php?codigo=<?php echo $codigo ?>&idcontrato=<?php echo  $rowContrato['idcontrato'] ?>">
    <h3>Datos Contrato a Duplicar</h3>
    <p>
		<b>Id:</b> <?php echo $idcontrato ?>
		<b>- Fecha Inicio:</b> <?php echo invertirFecha($rowContrato['fechainicio']) ?>
		<b>- Fecha Fin:</b> <?php if ($rowContrato['fechafin'] != NULL) { echo invertirFecha($rowContrato['fechafin']); } else { echo "-"; } ?>
    </p>
 <?php  $fi = ""; $ff = "";
		if (isset($_GET['err'])) { 
			$fi = $_GET['fi'];
			$ff = $_GET['ff']; ?>
			<h4 style="color: red">Existe un contrato con fechas en conflicto con las que se quieren cargar. Verifique las fechas.</h4>
 <?php	} ?>
    <h3>Datos Nuevo Contrato</h3>
    <p>
		<b>Fecha Inicio: </b><input type="text" name="fechaInicio" id="fechaInicio" size="8" value="<?php echo $fi ?>"/> - 
   		<b>Fecha Fin: </b><input type="text" name="fechaFin" id="fechaFin" size="8" value="<?php echo $ff ?>"/>
    </p>
    <p><b>Aumento:</b> <input type="text" id="porcentaje" name="porcentaje" size="4"/> %</p>
    <p><input type="submit" name="Submit" value="Guardar" /></p>
  </form>
</div>
</body>
</html>
