<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php");
include($libPath."funcionespracticas.php");

$nroafiliado = $_GET['nroafiliado'];
$nroorden = $_GET['nroorden'];

if ($nroorden == 0) {
	$sqlBeneficiario = "SELECT t.apellidoynombre, d.* FROM titulares t, discapacitados d WHERE t.nroafiliado = $nroafiliado and t.nroafiliado = d.nroafiliado and d.nroorden = $nroorden";
	$tipoBeneficiario = "TITULAR";
} else {
	$sqlBeneficiario = "SELECT f.apellidoynombre, p.descrip as parentesco, d.* FROM familiares f, parentesco p, discapacitados d WHERE f.nroafiliado = $nroafiliado and f.nroorden = $nroorden and f.tipoparentesco = p.codparent and f.nroafiliado = d.nroafiliado and d.nroorden = $nroorden";
	$tipoBeneficiario = "FAMILIAR";
}
$resBeneficiario = mysql_query($sqlBeneficiario,$db);
$rowBeneficiario = mysql_fetch_assoc($resBeneficiario);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modificar Discapacitado :.</title>
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
<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/funcionControl.js" type="text/javascript"></script>
<script src="/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(function($){
	$("#fechaInicio").mask("99-99-9999");
	$("#fechaFin").mask("99-99-9999");
});

function validar(formulario) {
	var fechaInicio = formulario.fechaInicio.value;
	var fechaFin = formulario.fechaFin.value;
	if (fechaInicio == "") {
		alert("Debe ingresar un fecha de emisión del certificado");
		return(false)
	} else {
		if (!esFechaValida(fechaInicio)) {
			alert("La fecha de Emisión de certificado no es valida");
			return(false);
		} 
	}
	if (fechaFin != "") {
		if (!esFechaValida(fechaFin)) {
			alert("La Fecha de Vencimiento no es valida");
			return(false);
		} else {
			fechaInicio = new Date(invertirFecha(fechaInicio));
			fechaFin = new Date(invertirFecha(fechaFin));
			if (fechaInicio >= fechaFin) {
				alert("La Fecha de Vencimiento debe ser superior a la Fecha de Emisión de certificado");
				return(false);
			}
		}
	}
	var archivo = formulario.certificado.value;
	var extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase(); 
	if (extension != '.jpg' && extension != '' ) {
		alert("El certificado debe ser un archivo .jpg");
		return(false);
	}
	formulario.Submit.disabled = true;
	return true;
}

function verCertificado(dire){	
	window.open(dire,'Certificado de Discapacidad','width=800, height=500');
}

</script>
<body bgcolor="#CCCCCC">
<div align="center">
  <p><span style="text-align:center">
   <input type="reset" name="volver" value="Volver" onclick="location.href='moduloABMDisca.php'" align="center"/>
  </span></p>
  <p class="Estilo2">Modificar Certificado de Discapacidad </p>
  <table width="500" border="1">
    <tr>
      <td width="163"><div align="right"><strong>Nro Afiliado </strong></div></td>
      <td width="321"><div align="left"><strong><?php echo $nroafiliado ?></strong></div></td>
    </tr>
    <tr>
      <td><div align="right"><strong>Apellido y Nombre </strong></div></td>
      <td><div align="left"><?php echo $rowBeneficiario['apellidoynombre'] ?></div></td>
    </tr>
    <tr>
      <td><div align="right"><strong>Tipo de Beneficiario </strong></div></td>
      <td><div align="left"><?php echo $tipoBeneficiario." - ".$rowBeneficiario['parentesco'] ?></div></td>
    </tr>
  </table>
  <p>
  <form action="guardarModificacionDiscapacitado.php?nroafiliado=<?php echo $nroafiliado ?>&nroorden=<?php echo $nroorden ?>" method="post" enctype="multipart/form-data" name="modifDisca" id="modifDisca" onSubmit="return validar(this)">
	<p class="Estilo2">Datos Certificado </p>
    <p><b>Fecha De Emision:</b> 
      <input type="text" name="fechaInicio" id="fechaInicio" size="8" value="<?php echo invertirFecha($rowBeneficiario['emisioncertificado']) ?>"/></p>
	
    <p><b>Fecha de Vencimiento:</b> <input type="text" name="fechaFin" id="fechaFin" size="8" value="<?php echo invertirFecha($rowBeneficiario['vencimientocertificado']) ?>" /></p>
	
	<p><b>Certificado</b> </p>
		<input name="ver" type="button" id="ver" value="Ver Certificado" onclick="verCertificado('verCertificado.php?nroafiliado=<?php echo $nroafiliado ?>&nroorden=<?php echo $nroorden ?>')" />
		
	<p><b>	Modificar: </b><input name="certificado" type="file" id="certificado" /></p>
	
	<p><input type="submit" name="Submit" value="Guardar" /></p>
</form>
</div>
</body>
</html>