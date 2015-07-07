<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 

$codigopresta = $_GET['codigopresta'];
$sqlConsultaPresta = "SELECT codigoprestador, nombre, nomenclador FROM prestadores WHERE codigoprestador = $codigopresta";
$resConsultaPresta = mysql_query($sqlConsultaPresta,$db);
$rowConsultaPresta = mysql_fetch_assoc($resConsultaPresta);

$codigoprof = $_GET['codigoprof'];
$sqlConsultaProf = "SELECT p.*, pr.nombre as prestador, l.nomlocali as localidad, r.descrip as provincia FROM profesionales p, prestadores pr, localidades l, provincia r WHERE p.codigoprofesional = $codigoprof and p.codlocali = l.codlocali and p.codprovin = r.codprovin and p.codigoprestador = pr.codigoprestador";
$resConsultaProf = mysql_query($sqlConsultaProf,$db);
$rowConsultaProf = mysql_fetch_assoc($resConsultaProf);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Profesional :.</title>
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
	<p><span style="text-align:center"><input class="nover" type="reset" name="volver" value="Volver" onclick="location.href = 'modificarProfesionales.php?codigo=<?php echo $codigopresta ?>'" align="center"/></span></p>
  <p class="Estilo2">Ficha Pofesional </p>
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
  <p>
	  <table border="1">
        <tr>
          <td><div align="right"><strong>C&oacute;digo</strong></div></td>
          <td colspan="6"><div align="left"><strong><?php echo $rowConsultaProf['codigoprofesional']  ?></strong></div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>Nombre</strong></div></td>
          <td colspan="6"><div align="left"><?php echo $rowConsultaProf['nombre'] ?></div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>Domicilio</strong></div></td>
          <td colspan="6"><div align="left"><?php echo $rowConsultaProf['domicilio'] ?></div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>C.U.I.T.</strong></div></td>
          <td colspan="6"><div align="left"><?php echo $rowConsultaProf['cuit'] ?></div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>Codigo Postal</strong></div></td>
          <td width="183"><div align="left"><?php echo $rowConsultaProf['indpostal']." ".$rowConsultaProf['numpostal']." ".$rowConsultaProf['alfapostal'] ?></div></td>
          <td width="160"><div align="left"><strong>Localidad</strong></div></td>
          <td width="140"><div align="left"><?php echo $rowConsultaProf['localidad'] ?></div></td>
          <td width="145"><div align="left"><strong>Provincia </strong></div></td>
          <td width="124"><div align="left"><?php echo $rowConsultaProf['provincia'] ?></div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>Telefono 1 </strong></div></td>
          <td><div align="left"><?php if ($rowConsultaProf['telefono1'] != 0) echo "(".$rowConsultaProf['ddn1'].")-".$rowConsultaProf['telefono1']; ?></div></td>
          <td><div align="left"><strong>Telefono 2 </strong></div></td>
          <td colspan="4"><div align="left"><?php if ($rowConsultaProf['telefono2'] != 0) echo "(".$rowConsultaProf['ddn2'].")-".$rowConsultaProf['telefono2']; ?></div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>Telefono FAX </strong></div></td>
          <td><div align="left"><?php if ($rowConsultaProf['telefonofax'] != 0) echo "(".$rowConsultaProf['ddnfax'].")-".$rowConsultaProf['telefonofax']; ?></div></td>
          <td><div align="left"><strong>Email</strong></div></td>
          <td colspan="4"><div align="left"><?php echo $rowConsultaProf['email'] ?></div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>Tratamiento</strong></div></td>
          <td><div align="left">
        <?php 
		if($rowConsultaProf['tratamiento'] != 0) {
			$codigoTrat = $rowConsultaProf['tratamiento'];
			$sqlConsultaTrata = "SELECT descripcion FROM tipotratamiento WHERE codigotratamiento = $codigoTrat";
			$resConsultaTrata = mysql_query($sqlConsultaTrata,$db);
			$rowConsultaTrata = mysql_fetch_assoc($resConsultaTrata);
			echo $rowConsultaTrata['descripcion'];
		} else {
			echo "-";
		}
		?>
          </div></td>
          <td><div align="left"><strong>Matr&iacute;cula Nacional </strong></div></td>
          <td><div align="left"><?php echo $rowConsultaProf['matriculanacional'] ?></div></td>
          <td><div align="left"><strong>Matr&iacute;culo Provincial </strong></div></td>
          <td colspan="2"><div align="left"><?php echo $rowConsultaProf['matriculaprovincial'] ?></div></td>
        </tr>
		<tr>
          <td><div align="left"><strong>Numero Registro SSS</strong></div></td>
          <td><div align="left"><?php if ($rowConsultaProf['numeroregistrosss'] != 0) { echo $rowConsultaProf['numeroregistrosss']; } ?></div></td>
          <td><strong>Activo</strong></td>
		  <td colspan="3"><?php if ($rowConsultaProf['activo'] == 0 ) { echo "NO"; } else { echo "SI"; } ?></td>
		</tr>
  </table>
  </p>
<p><input class="nover" name="modificar" type="button" value="Modificar Profesional"  onClick="location.href='modificarProfesional.php?codigoprof=<?php echo $codigoprof ?>&codigopresta=<?php echo $codigopresta ?>'" /></p>
<p><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" align="center" /></p>
</div>
</body>
</html>