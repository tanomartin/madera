<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionOspim.php"); 
$codigo = $_GET['codigo'];
$sqlConsultaPresta = "SELECT p.*, l.nomlocali as localidad, r.descrip as provincia FROM prestadores p, localidades l, provincia r WHERE p.codigoprestador = $codigo and p.codlocali = l.codlocali and p.codprovin = r.codprovin";
$resConsultaPresta = mysql_query($sqlConsultaPresta,$db);
$rowConsultaPresta = mysql_fetch_assoc($resConsultaPresta);

$sqlConsultaServcio = "SELECT s.descripcion FROM prestadorservicio p, tiposervicio s WHERE p.codigoprestador = $codigo and p.codigoservicio = s.codigoservicio";
$resConsultaServcio = mysql_query($sqlConsultaServcio,$db);

$sqlConsultaJuris = "SELECT p.codidelega, d.nombre FROM prestadorjurisdiccion p, delegaciones d WHERE p.codigoprestador = $codigo and p.codidelega = d.codidelega";
$resConsultaJuris = mysql_query($sqlConsultaJuris,$db);


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Alta Empresa :.</title>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
}
-->
</style>
</head>
<body bgcolor="#CCCCCC">
<div align="center">
  <input type="reset" name="volver" value="Volver" onClick="location.href = 'moduloAbmPrestadores.php'" align="center"/> 
  <p><strong>Prestador - Código <?php echo $rowConsultaPresta['codigoprestador']  ?></strong></p>
  <table width="800" border="1">
      <tr>
        <td width="177"><div align="right"><strong>Nombre / Raz&oacute;n Social</strong></div></td>
        <td><div align="left"><?php echo $rowConsultaPresta['nombre'] ?> </div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Domicilio</strong></div></td>
        <td><div align="left"><?php echo $rowConsultaPresta['domicilio'] ?> </div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Codigo Postal</strong></div></td>
        <td><div align="left"><?php echo $rowConsultaPresta['indpostal'].$rowConsultaPresta['numpostal'].$rowConsultaPresta['alfapostal'] ?> </div>
        <div align="right"></div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Localidad</strong></div></td>
        <td><div align="left"><?php echo $rowConsultaPresta['localidad'] ?></div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Provincia</strong></div></td>
        <td><div align="left"><?php echo $rowConsultaPresta['provincia'] ?></div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Telefono 1 </strong></div></td>
        <td><div align="left"><?php if ($rowConsultaPresta['telefono1'] != 0) echo "(".$rowConsultaPresta['ddn1'].")-".$rowConsultaPresta['telefono1']; ?></div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Telefono 2 </strong></div></td>
        <td><div align="left"><?php if ($rowConsultaPresta['telefono2'] != 0) echo "(".$rowConsultaPresta['ddn2'].")-".$rowConsultaPresta['telefono2']; ?></div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Telefono FAX </strong></div></td>
        <td><div align="left"><?php if ($rowConsultaPresta['telefonofax'] != 0) echo "(".$rowConsultaPresta['ddnfax'].")-".$rowConsultaPresta['telefonofax']; ?></div></td>
      </tr>
	  <tr>
        <td><div align="right"><strong>Email</strong></div></td>
        <td><div align="left"><?php echo $rowConsultaPresta['email'] ?></div></td>
      </tr>
	  <tr>
	    <td><div align="right"><strong>C.U.I.T.</strong></div></td>
	    <td><div align="left"><?php echo $rowConsultaPresta['cuit'] ?></div></td>
      </tr>
	  <tr>
        <td><div align="right"><strong>Personería</strong></div></td>
        <td><div align="left"><?php if($rowConsultaPresta['personeria'] == 1) { echo "Profesional"; } else { echo "Establecimiento"; } ?></div></td>
      </tr>
	  <tr>
	    <td><div align="right"><strong>Tratamiento</strong></div></td>
	    <td><div align="left">
		<?php 
		if($rowConsultaPresta['tratamiento'] != 0) {
			$codigoTrat = $rowConsultaPresta['tratamiento'];
			$sqlConsultaTrata = "SELECT descripcion FROM tipotratamiento WHERE codigotratamiento = $codigoTrat";
			$resConsultaTrata = mysql_query($sqlConsultaTrata,$db);
			$rowConsultaTrata = mysql_fetch_assoc($resConsultaTrata);
			echo $rowConsultaTrata['descripcion'];
		} else {
			echo "-";
		}
		?></div></td>
      </tr>
	  <tr>
	    <td><div align="right"><strong>Matr&iacute;cula Nacional </strong></div></td>
	    <td><div align="left"><?php echo $rowConsultaPresta['matriculanacional'] ?></div></td>
      </tr>
	  <tr>
	    <td><div align="right"><strong>Matr&iacute;culo Provincial </strong></div></td>
	    <td><div align="left"><?php echo $rowConsultaPresta['matriculaprovincial'] ?></div></td>
      </tr>
	  <tr>
	    <td><div align="right"><strong>Numero Registro SSS </strong></div></td>
	    <td><div align="left"><?php if ($rowConsultaPresta['numeroregistrosss'] != 0) { echo $rowConsultaPresta['numeroregistrosss']; } ?></div></td>
      </tr>
	  <tr>
	    <td><div align="right"><strong>Capitado</strong></div></td>
	    <td><div align="left"><?php if ($rowConsultaPresta['capitado'] == 1) { echo "SI"; } else { echo "NO"; } ?></div></td>
      </tr>
	  <tr>
	    <td><div align="right"><strong>Nomenclador </strong></div></td>
	    <td><div align="left"><?php if ($rowConsultaPresta['nomenclador'] == 1) { echo "Nacional"; } 
									if ($rowConsultaPresta['nomenclador'] == 2) { echo "No Nomenclado"; }
									if ($rowConsultaPresta['nomenclador'] == 3) { echo "Ambos"; }
							?></div></td>
      </tr>
  </table>
	  <p><div align="center" class="Estilo1"><strong>Servicios </strong></div><p>
  <table width="800" border="1">
	  <tr>
        <td width="179"><div align="right"><strong>Tipos de Servicios </strong></div></td>
	    <td width="605"><div align="left">
		<?php while ($rowConsultaServcio = mysql_fetch_assoc($resConsultaServcio)) {
				echo "<li>".$rowConsultaServcio['descripcion'];
		} ?></div></td>
      </tr>
  </table>
	  <p><div align="center" class="Estilo1"><strong>Jurisdiccion </strong></div></p>
  <table width="800" border="1">
	  <tr>
        <td width="179"><div align="right"><strong>Delegaciones </strong></div></td>
	    <td width="605"><div align="left">
	      <?php 
			while ($rowConsultaJuris = mysql_fetch_assoc($resConsultaJuris)) {
				echo "<li>".$rowConsultaJuris['codidelega']." - ".$rowConsultaJuris['nombre'];
		} ?>
	    </div></td>
      </tr>
  </table>
    <p>
    <input type="button" class="nover" name="imprimir" value="Imprimir" onclick="window.print();" align="center"/></p>
    </div>
</body>
</html>