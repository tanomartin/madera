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
$rowContrato = mysql_fetch_assoc($resContrato);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modificar Contrato :.</title>

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
	formulario.Submit.disabled = true;
	return true;
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><span style="text-align:center">
   <input type="button" name="volver" value="Volver" onclick="location.href = 'contratosPrestador.php?codigo=<?php echo $codigo ?>'" />
  </span></p>
  <p class="Estilo2">Modificación Contratos </p>
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
  <?php if (isset($_GET['err'])) {
  			print("<font color='#FF0000'><b>Existe un contrato con fecha de finalización posterior a la fecha de inicio que quiere ingresar</b></font>");
 		 } ?>
  <form id="modifContrato" name="modifContrato" method="post" onsubmit="return validar(this)" action="guardarModificacionContrato.php?codigo=<?php echo $codigo ?>&idcontrato=<?php echo  $rowContrato['idcontrato'] ?>">
    <p class="Estilo2">Datos Contrato</p>
    <p class="Estilo2">
	Fecha Inicio: <label><input type="text" name="fechaInicio" id="fechaInicio" size="8" value="<?php echo invertirFecha($rowContrato['fechainicio']) ?>"/></label> - 
    <?php if ($rowContrato['fechafin'] == NULL) {
				$valorfin = "-";
		   } else {
		   		$valorfin = invertirFecha($rowContrato['fechafin']);
		   }
	?>
	Fecha Fin: <label><input type="text" name="fechaFin" id="fechaFin" size="8" value="<?php echo $valorfin ?>"/> </label>
    </p>
    <p>
      <label><input type="submit" name="Submit" value="Guardar" /></label>	
	</p>
  </form>
</div>
</body>
</html>