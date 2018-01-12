<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php");

$codigopresta = $_GET['codigopresta'];
$sqlConsultaPresta = "SELECT codigoprestador, nombre FROM prestadores WHERE codigoprestador = $codigopresta";
$resConsultaPresta = mysql_query($sqlConsultaPresta,$db);
$rowConsultaPresta = mysql_fetch_assoc($resConsultaPresta);

$codigo = $_GET['codigo'];
$sqlConsultaEsta = "SELECT p.*, pr.nombre as prestador, l.nomlocali as localidad, r.descrip as provincia FROM establecimientos p, prestadores pr, localidades l, provincia r WHERE p.codigo = $codigo and p.codlocali = l.codlocali and p.codprovin = r.codprovin and p.codigoprestador = pr.codigoprestador";
$resConsultaEsta = mysql_query($sqlConsultaEsta,$db);
$rowConsultaEsta = mysql_fetch_assoc($resConsultaEsta);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Establecimiento :.</title>
<style type="text/css">
<!--
.Estilo2 {
	font-size: 18px;
	font-weight: bold;
}
-->
</style>
<style type="text/css" media="print">
.nover {display:none}
</style>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><span style="text-align:center"><input class="nover" type="button" name="volver" value="Volver" onclick="location.href = 'modificarEstablecimientos.php?codigo=<?php echo $codigopresta ?>'" /></span></p>
  <p class="Estilo2">Ficha Establecimiento </p>
  <table width="500" border="1" style="margin-bottom: 20px">
    <tr>
      <td width="163"><div align="right"><strong>C&oacute;digo</strong></div></td>
      <td width="321"><div align="left"><strong><?php echo $rowConsultaPresta['codigoprestador']  ?></strong></div></td>
    </tr>
    <tr>
      <td><div align="right"><strong>Raz&oacute;n Social</strong></div></td>
      <td><div align="left"><?php echo $rowConsultaPresta['nombre'] ?></div></td>
    </tr>
  </table>
	  <table border="1">
        <tr>
          <td><div align="right"><strong>C&oacute;digo</strong></div></td>
          <td colspan="6"><div align="left"><strong><?php echo $rowConsultaEsta['codigo']  ?></strong></div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>Nombre</strong></div></td>
          <td colspan="6"><div align="left"><?php echo $rowConsultaEsta['nombre'] ?></div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>Domicilio</strong></div></td>
          <td colspan="6"><div align="left"><?php echo $rowConsultaEsta['domicilio'] ?></div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>Codigo Postal</strong></div></td>
          <td width="183"><div align="left"><?php echo $rowConsultaEsta['indpostal']." ".$rowConsultaEsta['numpostal']." ".$rowConsultaEsta['alfapostal'] ?></div></td>
          <td width="160"><div align="left"><strong>Localidad</strong></div></td>
          <td width="140"><div align="left"><?php echo $rowConsultaEsta['localidad'] ?></div></td>
          <td width="145"><div align="left"><strong>Provincia </strong></div></td>
          <td width="124"><div align="left"><?php echo $rowConsultaEsta['provincia'] ?></div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>Telefono 1 </strong></div></td>
          <td><div align="left"><?php if ($rowConsultaEsta['telefono1'] != NULL) echo "(".$rowConsultaEsta['ddn1'].")-".$rowConsultaEsta['telefono1']; ?></div></td>
          <td><div align="left"><strong>Telefono 2 </strong></div></td>
          <td colspan="4"><div align="left"><?php if ($rowConsultaEsta['telefono2'] != NULL) echo "(".$rowConsultaEsta['ddn2'].")-".$rowConsultaEsta['telefono2']; ?></div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>Telefono FAX </strong></div></td>
          <td><div align="left"><?php if ($rowConsultaEsta['telefonofax'] != NULL) echo "(".$rowConsultaEsta['ddnfax'].")-".$rowConsultaEsta['telefonofax']; ?></div></td>
          <td><div align="left"><strong>Email</strong></div></td>
          <td colspan="4"><div align="left"><?php echo $rowConsultaEsta['email'] ?></div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>Acreditacion Calidad </strong></div></td>
          <td><div align="left"><?php if ($rowConsultaEsta['calidad'] == 0) { echo "NO"; } else { echo "SI"; }  ?></div></td>
          <td><div align="left"><strong>Fecha Desde</strong></div></td>
          <td><div align="left"><?php if ($rowConsultaEsta['fechainiciocalidad'] != NULL) { echo invertirFecha($rowConsultaEsta['fechainiciocalidad']); } ?></div></td>
          <td><div align="left"><strong>Fecha Hasta</strong></div></td>
          <td><div align="left"><?php if ($rowConsultaEsta['fechafincalidad'] != NULL) { echo invertirFecha($rowConsultaEsta['fechafincalidad']); } ?></div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>Circulo </strong></div></td>
          <td colspan="5"><div align="left"><?php if ($rowConsultaEsta['circulo'] == 0) { echo "NO"; } else { echo "SI"; }  ?></div></td>
        </tr>
  </table>
<p><input class="nover" name="modificar" type="button" value="Modificar Establecimiento" onclick="location.href='modificarEstablecimiento.php?codigo=<?php echo $codigo ?>&codigopresta=<?php echo $codigopresta ?>'" /></p>
<p><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" /></p>
</div>
</body>
</html>