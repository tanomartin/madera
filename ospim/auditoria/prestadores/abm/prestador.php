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
<title>.: Prestador :.</title>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
}
-->
</style>
<style type="text/css" media="print">
.nover {display:none}
</style>
<link rel="stylesheet" href="/lib/tablas.css">
</head>
<body bgcolor="#CCCCCC">
<div align="center">
  <p><strong>Ficha Prestador</strong></p>
	  <table border="1">
        <tr>
          <td><div align="right"><strong>C&oacute;digo</strong></div></td>
          <td colspan="6"><div align="left"><strong><?php echo $rowConsultaPresta['codigoprestador']  ?></strong></div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>Nombre / Raz&oacute;n Social</strong></div></td>
          <td colspan="6"><div align="left">
              <div align="left"><?php echo $rowConsultaPresta['nombre'] ?></div>
          </div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>Domicilio</strong></div></td>
          <td colspan="6"><div align="left"><?php echo $rowConsultaPresta['domicilio'] ?></div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>C.U.I.T.</strong></div></td>
          <td colspan="6"><div align="left"><?php echo $rowConsultaPresta['cuit'] ?></div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>Codigo Postal</strong></div></td>
          <td width="183"><div align="left"><?php echo $rowConsultaPresta['indpostal']." ".$rowConsultaPresta['numpostal']." ".$rowConsultaPresta['alfapostal'] ?></div>
              <div align="right"></div></td>
          <td><div align="left"><strong>Localidad</strong></div></td>
          <td width="140"><div align="left"><?php echo $rowConsultaPresta['localidad'] ?></div></td>
          <td><div align="left"><strong>Provincia </strong></div>
              <div align="left"></div></td>
          <td width="124"><div align="left"><?php echo $rowConsultaPresta['provincia'] ?></div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>Telefono 1 </strong></div></td>
          <td><div align="left">
            <?php if ($rowConsultaPresta['telefono1'] != 0) echo "(".$rowConsultaPresta['ddn1'].")-".$rowConsultaPresta['telefono1']; ?>
          </div></td>
          <td><div align="left"><strong>Telefono 2 </strong></div></td>
          <td colspan="4"><div align="left">
            <?php if ($rowConsultaPresta['telefono2'] != 0) echo "(".$rowConsultaPresta['ddn2'].")-".$rowConsultaPresta['telefono2']; ?>
          </div></td>
        </tr>
        <tr>
          <td>
              <div align="right"><strong>Telefono FAX </strong></div>
          </td>
          <td><div align="left">
            <?php if ($rowConsultaPresta['telefonofax'] != 0) echo "(".$rowConsultaPresta['ddnfax'].")-".$rowConsultaPresta['telefonofax']; ?>
          </div></td>
          <td><div align="left"><strong>Email</strong></div>
              <div align="left"></div></td>
          <td colspan="4"><div align="left"><?php echo $rowConsultaPresta['email'] ?></div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>Personer&iacute;a</strong></div></td>
          <td><div align="left">
            <?php 	if($rowConsultaPresta['personeria'] == 1) { echo "Profesional"; } 
					if($rowConsultaPresta['personeria'] == 2) { echo "Establecimiento"; } 
					if($rowConsultaPresta['personeria'] == 3) { echo "Círculo"; }
			?>
          </div></td>
          <td> <div align="left"><strong>Numero Registro SSS</strong></div></td>
          <td colspan="4"><div align="left">
            <?php if ($rowConsultaPresta['numeroregistrosss'] != 0) { echo $rowConsultaPresta['numeroregistrosss']; } ?>
          </div></td>
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
		?>
          </div></td>
          <td><div align="left"><strong>Matr&iacute;cula Nacional </strong></div></td>
          <td><div align="left"><?php echo $rowConsultaPresta['matriculanacional'] ?></div></td>
          <td><div align="left"><strong>Matr&iacute;culo Provincial </strong></div></td>
          <td colspan="2"><div align="left"><?php echo $rowConsultaPresta['matriculaprovincial'] ?></div>          </td>
        </tr>
        <tr>
          <td><div align="right"><strong>Capitado</strong></div></td>
          <td colspan="6"><div align="left">
            <?php if ($rowConsultaPresta['capitado'] == 1) { echo "SI"; } else { echo "NO"; } ?>
          </div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>Nomenclador </strong></div></td>
          <td colspan="6"><div align="left">
            <?php if ($rowConsultaPresta['nomenclador'] == 1) { echo "Nacional"; } 
									if ($rowConsultaPresta['nomenclador'] == 2) { echo "No Nomenclado"; }
									if ($rowConsultaPresta['nomenclador'] == 3) { echo "Ambos"; }
							?>
          </div></td>
        </tr>
  	</table>
	  <p>&nbsp;</p>
	  <div class="grilla">
	  <table width="794" border="1">
        <thead>
		<tr>
          <th width="392" height="46"><div align="center" class="Estilo1"><strong>Servicios </strong></div></td>
          <th width="386"><div align="center" class="Estilo1"><strong>Jurisdiccion </strong></div></td>
        </tr>
		</thead>
        <tbody>
		<tr>
          <td valign="top"><div align="left">
            <?php while ($rowConsultaServcio = mysql_fetch_assoc($resConsultaServcio)) {
				echo $rowConsultaServcio['descripcion']."<br>";
		} ?>
          </div></td>
          <td valign="top"><div align="left">
            <?php 
			while ($rowConsultaJuris = mysql_fetch_assoc($resConsultaJuris)) {
				echo $rowConsultaJuris['codidelega']." - ".$rowConsultaJuris['nombre']."<br>";
		} ?>
          </div></td>
        </tr>
		</tbody>
	</table>
	</div>
	 <p>
	  <table width="600" border="0">
      <tr>
        <td width="200"><div align="left">
          <input class="nover" name="modificar" type="button" value="Modificar Prestador"  onClick="location.href = 'modificarPrestador.php?codigo=<?php echo $codigo ?>'" />
        </div></td>
        
		<td width="200"><div align="center">
		<?php if ($rowConsultaPresta['personeria'] == 3) { ?>
            <input class="nover" name="profesionales" type="button" value="Modificar Profesionales"  onclick="location.href = 'modificarProfesionales.php?codigo=<?php echo $codigo ?>'" /><?php } ?>
        </div></td> 
        <td width="200"><div align="center">
          <input class="nover" name="modificar2" type="button" value="Modificar Contrato"  onclick="location.href = 'modificarContrato.php?codigo=<?php echo $codigo ?>'" />
        </div></td>
      </tr>
    </table>
	</p>
    <p> <input class="nover" type="button" class="nover" name="imprimir" value="Imprimir" onclick="window.print();" align="center"/></p>
</div>
</body>
</html>