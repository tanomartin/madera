<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
include($_SERVER['DOCUMENT_ROOT']."/madera/lib/fechas.php");
$codigo = $_GET['codigo'];
$sqlConsultaPresta = "SELECT p.*, l.nomlocali as localidad, r.descrip as provincia, t.descripcion, b.descripcion as barrio, s.descripcion as situacionfiscal FROM prestadores p, localidades l, provincia r, tipoprestador t, barrios b, tiposituacionfiscal s 
						WHERE p.codigoprestador = $codigo and p.codlocali = l.codlocali and p.codprovin = r.codprovin and p.personeria = t.id and p.idBarrio = b.id and p.situacionfiscal = s.id";
$resConsultaPresta = mysql_query($sqlConsultaPresta,$db);
$rowConsultaPresta = mysql_fetch_assoc($resConsultaPresta);

$sqlConsultaNomenclador = "SELECT p.*, n.id, n.nombre FROM prestadornomenclador p, nomencladores n WHERE p.codigoprestador = $codigo and p.codigonomenclador = n.id";
$resConsultaNomenclador = mysql_query($sqlConsultaNomenclador,$db);
$canConsultaNomenclador = mysql_num_rows($resConsultaNomenclador);

$sqlConsultaServcio = "SELECT p.*, s.descripcion FROM prestadorservicio p, tiposervicio s WHERE p.codigoprestador = $codigo and p.codigoservicio = s.codigoservicio";
$resConsultaServcio = mysql_query($sqlConsultaServcio,$db);
$canConsultaServcio = mysql_num_rows($resConsultaServcio);

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
<link rel="stylesheet" href="/madera/lib/tablas.css"/>
</head>
<body bgcolor="#CCCCCC">
<div align="center">
  <p><strong>Ficha Prestador</strong></p>
	  <div class="grilla">
	  <table>
        <tr>
          <td><div align="right" class="title"><strong>C&oacute;digo</strong></div></td>
          <td colspan="5"><div align="left"><strong><?php echo $rowConsultaPresta['codigoprestador']  ?></strong></div></td>
        </tr>
        <tr>
          <td><div align="right" class="title"><strong>C.U.I.T.</strong></div></td>
          <td><div align="left"><?php echo $rowConsultaPresta['cuit'] ?></div></td>
          <td><div align="left" class="title"><strong>Situacion Fiscal</strong></div></td>
          <td><div align="left"><?php echo $rowConsultaPresta['situacionfiscal'] ?></div></td>
          <td><div align="left" class="title"><strong>Fecha Vto. Exento</strong></div></td>
          <td><div align="left"><?php if ($rowConsultaPresta['vtoexento'] != NULL) { echo invertirFecha($rowConsultaPresta['vtoexento']); } ?></div></td>
        </tr>
        <tr>
          <td><div align="right" class="title"><strong>Raz&oacute;n Social</strong></div></td>
          <td colspan="5"><div align="left"><?php echo $rowConsultaPresta['nombre'] ?></div></td>
        </tr>
        <tr>
          <td><div align="right" class="title"><strong>Domicilio</strong></div></td>
          <td colspan="3"><div align="left"><?php echo $rowConsultaPresta['domicilio'] ?></div></td>
          <td><div align="left" class="title"><strong>Provincia</strong></div></td>
          <td><div align="left"><?php echo $rowConsultaPresta['provincia'] ?></div></td>       
        </tr>
        <tr>
          <td><div align="right" class="title"><strong>Codigo Postal</strong></div></td>
          <td><div align="left"><?php echo $rowConsultaPresta['indpostal']." ".$rowConsultaPresta['numpostal']." ".$rowConsultaPresta['alfapostal'] ?></div></td>
          <td><div align="left" class="title"><strong>Localidad</strong></div></td>
          <td><div align="left"><?php echo $rowConsultaPresta['localidad'] ?></div></td>
          <td><div align="left" class="title"><strong>Barrio </strong></div></td>
          <td><div align="left"><?php echo $rowConsultaPresta['barrio'] ?></div></td>
        </tr>
        <tr>
          <td><div align="right" class="title"><strong>Telefono 1 </strong></div></td>
          <td>
          	<div align="left">
            	<?php if ($rowConsultaPresta['telefono1'] != NULL) echo "(".$rowConsultaPresta['ddn1'].")-".$rowConsultaPresta['telefono1']; ?>
          	</div>
          </td>
          <td><div align="left" class="title"><strong>Telefono 2 </strong></div></td>
          <td>
          	<div align="left">
            	<?php if ($rowConsultaPresta['telefono2'] != NULL) echo "(".$rowConsultaPresta['ddn2'].")-".$rowConsultaPresta['telefono2']; ?>
          	</div>
          </td>
          <td><div align="left" class="title"><strong>Telefono FAX </strong></div></td>
          <td>
          	<div align="left">
            	<?php if ($rowConsultaPresta['telefonofax'] != NULL) echo "(".$rowConsultaPresta['ddnfax'].")-".$rowConsultaPresta['telefonofax']; ?>
          	</div>
          </td>
        </tr>
        <tr>
          <td><div align="right" class="title"><strong>Email Primario</strong></div></td>
          <td colspan="5"><div align="left"><?php echo $rowConsultaPresta['email1'] ?></div></td>
        </tr>
        <tr>
          <td><div align="right" class="title"><strong>Email Secundario</strong></div></td>
          <td colspan="5"><div align="left"><?php echo $rowConsultaPresta['email2'] ?></div></td>
        </tr>
        <tr>
       	  <td><div align="right" class="title"><strong>Numero Registro SSS</strong></div></td>
          <td>
          	<div align="left">
            	<?php  echo $rowConsultaPresta['numeroregistrosss']; ?>
          	</div>
          </td>
          <td><div align="left" class="title"><strong>Vto. Registro SSS</strong></div></td>
          <td colspan="3">
          	<div align="left">
            	<?php if ($rowConsultaPresta['vtoregistrosss'] != NULL) { echo invertirFecha($rowConsultaPresta['vtoregistrosss']); } ?>
          	</div>
          </td>
        </tr>
        <tr>
	       <td><div align="left" class="title"><strong>Numero Registro SNR</strong></div></td>
	       <td>
	         <div align="left">
	           <?php  echo $rowConsultaPresta['numeroregistrosnr'];  ?>
	          </div>
	       </td>
	       <td><div align="left" class="title"><strong>Vto. Registro SNR</strong></div></td>
	       <td colspan="3">
	          <div align="left">
	            <?php if ($rowConsultaPresta['vtoregistrosnr'] != NULL) { echo invertirFecha($rowConsultaPresta['vtoregistrosnr']); } ?>
	          </div>
	       </td>
        </tr>
        <tr>
          <td><div align="right" class="title"><strong>Personer&iacute;a</strong></div></td>
          <td colspan="5"><div align="left"><?php echo $rowConsultaPresta['descripcion'] ?></div></td>         
        </tr>
        <tr>
          <td><div align="right" class="title"><strong>Tratamiento</strong></div></td>
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
          <td><div align="left" class="title"><strong>Matr&iacute;cula Nacional </strong></div></td>
          <td><div align="left"><?php echo $rowConsultaPresta['matriculanacional'] ?></div></td>
          <td><div align="left" class="title"><strong>Matr&iacute;cula Provincial </strong></div></td>
          <td colspan="2"><div align="left"><?php echo $rowConsultaPresta['matriculaprovincial'] ?></div></td>
        </tr>
        <tr>
          <td><div align="right" class="title"><strong>Capitado</strong></div></td>
          <td colspan="2"><div align="left">
            <?php if ($rowConsultaPresta['capitado'] == 1) { echo "SI"; } else { echo "NO"; } ?>
          </div></td>
          <td><div align="right" class="title"><strong>Arancel Fijo</strong></div></td>
            <td colspan="2"><div align="left">
            <?php if ($rowConsultaPresta['montofijo'] == 1) { echo "SI"; } else { echo "NO"; } ?>
          </div></td>
        </tr>
  	  </table>
  	  </div>
	  <div class="grilla" style="margin-top: 20px;margin-bottom: 20px">
	  <table width="700" border="1">
        <thead>
			<tr>
			  <th width="233"><div align="center" class="Estilo1"><strong>Nomencladores </strong></div></th>
	          <th width="233"><div align="center" class="Estilo1"><strong>Servicios </strong></div></th>
	          <th width="233"><div align="center" class="Estilo1"><strong>Jurisdiccion </strong></div></th>
	        </tr>
		</thead>
        <tbody>
			<tr>
			 <td valign="top"><div align="left">
	            <?php while ($rowConsultaNomenclador = mysql_fetch_assoc($resConsultaNomenclador)) {
						echo $rowConsultaNomenclador['nombre']."<br>";
						$codNomenclador = $rowConsultaNomenclador['codigonomenclador'];
					  } ?>
	          </div></td>		
	          <td valign="top"><div align="left">
	            <?php while ($rowConsultaServcio = mysql_fetch_assoc($resConsultaServcio)) {
						echo $rowConsultaServcio['descripcion']."<br>";
						$codServicio = $rowConsultaServcio['codigoservicio'];
					  } ?>
	          </div></td>
	          <td valign="top"><div align="left">
	            <?php while ($rowConsultaJuris = mysql_fetch_assoc($resConsultaJuris)) {
						echo $rowConsultaJuris['codidelega']." - ".$rowConsultaJuris['nombre']."<br>";
					  } ?>
	          </div></td>
	        </tr>
		</tbody>
	</table>
	</div>
	  <table width="600" border="0">
      <tr>
        <td width="200"><div align="left">
          <input class="nover" name="modificar" type="button" value="Modificar Prestador" onclick="location.href = 'modificarPrestador.php?codigo=<?php echo $codigo ?>'" />
        </div></td>
		<td width="200"><div align="center">
		<?php if ($rowConsultaPresta['personeria'] == 3) { ?>
            <input class="nover" name="profesionales" type="button" value="Modificar Profesionales"  onclick="location.href = 'profesionales/modificarProfesionales.php?codigo=<?php echo $codigo ?>'" /><?php } ?>
        <?php if ($rowConsultaPresta['personeria'] == 4) { ?>
            <input class="nover" name="establecimientos" type="button" value="Modificar Establecimientos"  onclick="location.href = 'establecimientos/modificarEstablecimientos.php?codigo=<?php echo $codigo ?>'" /><?php } ?>
        </div></td> 

        <td width="200"><div align="center">		
      <?php if ($rowConsultaPresta['montofijo'] == 0 ) {
        		if (($canConsultaNomenclador == 1 and $canConsultaServcio == 1 and $codNomenclador == 7 and $codServicio == 8) or ($canConsultaNomenclador == 0)) { $disabled = "disabled='disabled'"; } else { $disabled = ""; } ?>
         		<input <?php echo $disabled?> class="nover" name="modificarContrato" type="button" value="Modificar Contratos"  onclick="location.href = 'contratos/contratosPrestador.php?codigo=<?php echo $codigo ?>'" />
      <?php } else { ?>
      			<input class="nover" name="modificarArancel" type="button" value="Modificar Aranceles"  onclick="location.href = 'aranceles/arancelesPrestador.php?codigo=<?php echo $codigo ?>'" />
      <?php	} ?>
        </div></td>
      </tr>
    </table>
    <p> <input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" /></p>
</div>
</body>
</html>