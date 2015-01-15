<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php");
include($libPath."funcionespracticas.php");

$nroafiliado = $_GET['nroafiliado'];
$nroorden = $_GET['nroorden'];
$activo = $_GET['activo'];

if ($nroorden == 0) {
	if ($activo == 1) {
		$sqlBeneficiario = "SELECT t.apellidoynombre, d.* FROM titulares t, discapacitados d WHERE t.nroafiliado = $nroafiliado and t.nroafiliado = d.nroafiliado and d.nroorden = $nroorden";
		$tipoBeneficiario = "TITULAR";
	} else {
		$sqlBeneficiario = "SELECT t.apellidoynombre, d.* FROM titularesdebaja t, discapacitados d WHERE t.nroafiliado = $nroafiliado and t.nroafiliado = d.nroafiliado and d.nroorden = $nroorden";
		$tipoBeneficiario = "TITULAR INACTIVO";
	}
	
} else {
	if ($activo == 1) {
		$sqlBeneficiario = "SELECT f.apellidoynombre, p.descrip as parentesco, d.* FROM familiares f, parentesco p, discapacitados d WHERE f.nroafiliado = $nroafiliado and f.nroorden = $nroorden and f.tipoparentesco = p.codparent and f.nroafiliado = d.nroafiliado and d.nroorden = f.nroorden";
		$tipoBeneficiario = "FAMILIAR";
	} else {
		$sqlBeneficiario = "SELECT f.apellidoynombre, p.descrip as parentesco, d.* FROM familiaresdebaja f, parentesco p, discapacitados d WHERE f.nroafiliado = $nroafiliado and f.nroorden = $nroorden and f.tipoparentesco = p.codparent and f.nroafiliado = d.nroafiliado and d.nroorden = f.nroorden";
		$tipoBeneficiario = "FAMILIAR INACTIVO";
	}
	
}
$resBeneficiario = mysql_query($sqlBeneficiario,$db);
$rowBeneficiario = mysql_fetch_assoc($resBeneficiario);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Consulta Discapacitado :.</title>
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

function verCertificado(dire){	
	window.open(dire,'Certificado de Discapacidad','width=800, height=500');
}

</script>
<body bgcolor="#CCCCCC">
<div align="center">
  <p><span style="text-align:center">
   <input type="reset" name="volver" value="Volver" onclick="location.href='moduloABMDisca.php'" align="center"/>
  </span></p>
  <p class="Estilo2">Consulta Certificado de Discapacidad </p>
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
    <p class="Estilo2">Datos Certificado </p>
    <p class="Estilo2">Fecha De Emicion: <label><?php echo invertirFecha($rowBeneficiario['emisioncertificado']) ?></label> 
    <p class="Estilo2">Fecha de Vencimiento: <label><?php echo invertirFecha($rowBeneficiario['vencimientocertificado']) ?></label></p>
    <p><input name="ver" type="button" id="ver" value="Ver Certificado" onclick="verCertificado('verCertificado.php?nroafiliado=<?php echo $nroafiliado ?>&nroorden=<?php echo $nroorden ?>')"/></p>
	<?php if ($activo == 1) { ?><p><input type='button' name='modificar' value='Modificar' onclick="location.href='modificarDiscapacitado.php?nroafiliado=<?php echo $nroafiliado ?>&nroorden=<?php echo $nroorden ?>'" /> <?php } ?></p>
	
</div>
</body>
</html>