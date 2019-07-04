<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php");
include($libPath."funcionespracticas.php");

$nroafiliado = $_GET['nroafiliado'];
$nroorden = $_GET['nroorden'];
$activo = $_GET['activo'];

if ($nroorden == 0) {
	if ($activo == 1) {
		$sqlBeneficiario = "SELECT t.apellidoynombre, t.cuil as cuilbene, d.*, '' as parentesco 
							FROM titulares t
							LEFT JOIN discapacitados d on t.nroafiliado = d.nroafiliado and d.nroorden = $nroorden
							WHERE  t.nroafiliado = $nroafiliado";
		$tipoBeneficiario = "TITULAR";
	} else {
		$sqlBeneficiario = "SELECT t.apellidoynombre, t.cuil as cuilbene, d.*, '' as parentesco 
							FROM titularesdebaja t
							LEFT JOIN discapacitados d on t.nroafiliado = d.nroafiliado and d.nroorden = $nroorden
							WHERE t.nroafiliado = $nroafiliado"; 
		$tipoBeneficiario = "TITULAR INACTIVO";
	}
	
} else {
	if ($activo == 1) {
		$sqlBeneficiario = "SELECT f.apellidoynombre, f.cuil as cuilbene, p.descrip as parentesco, d.* 
								FROM parentesco p, familiares f
								LEFT JOIN discapacitados d on f.nroafiliado = d.nroafiliado and d.nroorden = f.nroorden
								WHERE f.nroafiliado = $nroafiliado and f.nroorden = $nroorden and f.tipoparentesco = p.codparent";
		$tipoBeneficiario = "FAMILIAR";
	} else {
		$sqlBeneficiario = "SELECT f.apellidoynombre, f.cuil as cuilbene, p.descrip as parentesco, d.* 
								FROM parentesco p, familiaresdebaja f
								LEFT JOIN discapacitados d  on f.nroafiliado = d.nroafiliado and d.nroorden = f.nroorden 
								WHERE f.nroafiliado = $nroafiliado and f.nroorden = $nroorden and f.tipoparentesco = p.codparent ";
		$tipoBeneficiario = "FAMILIAR INACTIVO";
	}
}
$resBeneficiario = mysql_query($sqlBeneficiario,$db);
$rowBeneficiario = mysql_fetch_assoc($resBeneficiario);


$sqlTipoDiscapacidad = "SELECT *, t.descripcion FROM discapacidadbeneficiario d, tipodiscapacidad t WHERE d.nroafiliado = $nroafiliado and d.nroorden = $nroorden and d.iddiscapacidad = t.iddiscapacidad";
$resTipoDiscapacidad = mysql_query($sqlTipoDiscapacidad,$db);
$canTipoDiscapacidad = mysql_num_rows($resTipoDiscapacidad);

$sqlExpediente = "SELECT * FROM discapacitadoexpendiente WHERE nroafiliado = $nroafiliado and nroorden = $nroorden";
$resExpediente = mysql_query($sqlExpediente,$db);
$canExpediente = mysql_num_rows($resExpediente);
$rowExpediente = mysql_fetch_assoc($resExpediente);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Consulta Discapacitado :.</title>
<script type="text/javascript">

function verCertificado(dire){	
	window.open(dire,'Certificado de Discapacidad','width=800, height=500,resizable=yes');
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><?php if (!isset($_GET['nomostrar'])) { ?> <input type="reset" name="volver" value="Volver" onclick="location.href='moduloABMDisca.php?nroafiliado=<?php echo $nroafiliado ?>'" /> <?php } ?></p>
  <h3>Consulta de Discapacidado </h3>
  <table width="500" border="1">
    <tr>
      <td width="163"><div align="right"><strong>Nro Afiliado </strong></div></td>
      <td width="321"><div align="left"><strong><?php echo $nroafiliado ?></strong></div></td>
    </tr>
    <tr>
      <td width="163"><div align="right"><strong>C.U.I.L.</strong></div></td>
      <td width="321"><div align="left"><?php echo $rowBeneficiario['cuilbene'] ?></div></td>
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
  <h3>Tipo Discapacidad</h3>
<?php if ($canTipoDiscapacidad == 0) { 
			echo "No especificado"; 
	  } else {
			while ($rowTipoDiscapacidad = mysql_fetch_assoc($resTipoDiscapacidad)) {
				echo ($rowTipoDiscapacidad['descripcion']."<br>");
			} 
	   } ?>
  <table width="700" style="text-align: center; margin-top: 10px">
    <tr>
      <td height="47" colspan="8"><h3 align="center">Datos Certificado </h3></td>
    </tr>
<?php if ($rowBeneficiario['documentocertificado'] != NULL) { ?>
	    <tr>
	      <td>Fecha Alta: <b><?php echo invertirFecha($rowBeneficiario['fechaalta']) ?></b></td>
	      <td>Fecha Emision: <b><?php echo invertirFecha($rowBeneficiario['emisioncertificado']) ?></b></td>
	      <td>Fecha Vto: <b><?php echo invertirFecha($rowBeneficiario['vencimientocertificado']) ?></b></td>
	    </tr>
	    <tr>
	      <td colspan="2">Codigo Cert: <b><?php echo $rowBeneficiario['codigocertificado'] ?></b></td>
	      <td>Certificado: <input name="ver" type="button" id="ver" value="Ver Certificado" onclick="verCertificado('verCertificado.php?nroafiliado=<?php echo $nroafiliado ?>&nroorden=<?php echo $nroorden ?>')"/></td>
	    </tr>
<?php } else { ?>
		<tr>
			<td>SIN DATOS</td>
		</tr>
<?php } ?>
  </table>
  
<?php if ($canExpediente == 0) { ?>
  		<h3>Datos Expediente [SIN DATOS]</h3>
<?php } else { 
		  if ($rowExpediente['completo'] == 0) { $estado = "[Incompleto]"; } else { $estado = "[Completo: ".$rowExpediente['fechacierre']."]"; } ?>
		  <h3 align="center">Datos Expediente <?php echo $estado ?></h3>	
		  <table width="900" border="0">
		    <tr>
		      <td><div align="right">Pedido Medico: </div></td>
		      <td><b><?php if ($rowExpediente['pedidomedico'] == 0) { echo "NO"; } else { echo "SI"; }?></b></td>
		      <td><div align="right">Presupuesto: </div></td>
		      <td><b><?php if ($rowExpediente['presupuesto'] == 0) { echo "NO"; } else { echo "SI"; }?></b></td>
		      <td><div align="right">Presupuesto Trasnporte: </div></td>
		      <td><b><?php if ($rowExpediente['presupuestotransporte'] == 0) { echo "NO"; } if ($rowExpediente['presupuestotransporte'] == 1) { echo "SI"; }  if ($rowExpediente['presupuestotransporte'] == 2) { echo "No Requerido"; }?></b></td>
		    </tr>
		    <tr>
		      <td><div align="right">Registro SSS: </div></td>
		      <td><b><?php if ($rowExpediente['registrosss'] == 0) { echo "NO"; } if ($rowExpediente['registrosss'] == 1) { echo "SI"; }  if ($rowExpediente['registrosss'] == 2) { echo "No Requerido"; }?></b></td>
		      <td><div align="right">Resoluci&oacute;n SNR: </div></td>
		      <td><b><?php if ($rowExpediente['resolucionsnr'] == 0) { echo "NO"; } if ($rowExpediente['resolucionsnr'] == 1) { echo "SI"; }  if ($rowExpediente['resolucionsnr'] == 2) { echo "No Requerido"; }?></b></td>
		      <td><div align="right">Titulo Habilitante: </div></td>
		      <td><b><?php if ($rowExpediente['titulo'] == 0) { echo "NO"; } if ($rowExpediente['titulo'] == 1) { echo "SI"; }  if ($rowExpediente['titulo'] == 2) { echo "No Requerido"; }?></b></td>
		    </tr>
		    <tr>
		      <td><div align="right">Plan Tratamiento:</div></td>
		      <td><b><?php if ($rowExpediente['plantratamiento'] == 0) { echo "NO"; } else { echo "SI"; }?></b></td>
		      <td><div align="right">Dependencia:</div></td>
		      <td><b><?php if ($rowExpediente['dependencia'] == 0) { echo "NO"; } if ($rowExpediente['dependencia'] == 1) { echo "SI"; }  if ($rowExpediente['dependencia'] == 2) { echo "No Requerido"; }?></b></td>
		      <td><div align="right">Historia Clinica:</div></td>
		      <td><b><?php if ($rowExpediente['resumenhistoria'] == 0) { echo "NO"; } else { echo "SI"; }?></b></td>
		    </tr>
		    <tr>
		      <td><div align="right">Planilla FIM:</div></td>
		      <td><b><?php if ($rowExpediente['planillafim'] == 0) { echo "NO"; } if ($rowExpediente['planillafim'] == 1) { echo "SI"; }  if ($rowExpediente['planillafim'] == 2) { echo "No Requerido"; }?></b></td>
		      <td><div align="right">Consentimiento Tratamiento: </div></td>
		      <td><b><?php if ($rowExpediente['consentimientotratamiento'] == 0) { echo "NO"; } else { echo "SI"; }?></b></td>
		      <td><div align="right">Consentimiento Trasnporte</div></td>
		      <td><b><?php if ($rowExpediente['consentimientotransporte'] == 0) { echo "NO"; } if ($rowExpediente['consentimientotransporte'] == 1) { echo "SI"; }  if ($rowExpediente['consentimientotransporte'] == 2) { echo "No Requerido"; }?></b></td>
		    </tr>
		    <tr>
		      <td><div align="right">Constancia Alumno: </div></td>
		      <td><b><?php if ($rowExpediente['constanciaalumno'] == 0) { echo "NO"; } if ($rowExpediente['constanciaalumno'] == 1) { echo "SI"; }  if ($rowExpediente['constanciaalumno'] == 2) { echo "No Requerido"; }?></b></td>
		      <td><div align="right">Adaptaciones Curriculares: </div></td>
		      <td><b><?php if ($rowExpediente['adaptaciones'] == 0) { echo "NO"; } if ($rowExpediente['adaptaciones'] == 1) { echo "SI"; }  if ($rowExpediente['adaptaciones'] == 2) { echo "No Requerido"; }?></b></td>
		      <td><div align="right">Acta Acuerdo: </div></td>
		      <td><b><?php if ($rowExpediente['actaacuerdo'] == 0) { echo "NO"; } if ($rowExpediente['actaacuerdo'] == 1) { echo "SI"; }  if ($rowExpediente['actaacuerdo'] == 2) { echo "No Requerido"; }?></b></td>
		    </tr>
		    <tr>
		      <td><div align="right">Certificado Discapacidad: </div></td>
		      <td><b><?php if ($rowExpediente['certificadodiscapacidad'] == 0) { echo "NO"; } else { echo "SI"; }?></b></td>
		      <td><div align="right">Recibo de Sueldo: </div></td>
		      <td><b><?php if ($rowExpediente['recibosueldo'] == 0) { echo "NO"; } if ($rowExpediente['recibosueldo'] == 1) { echo "SI"; }  if ($rowExpediente['recibosueldo'] == 2) { echo "No Requerido"; }?></b></td>
		      <td><div align="right">Seguro Desempleo: </div></td>
		      <td><b><?php if ($rowExpediente['segurodesempleo'] == 0) { echo "NO"; } if ($rowExpediente['segurodesempleo'] == 1) { echo "SI"; }  if ($rowExpediente['segurodesempleo'] == 2) { echo "No Requerido"; }?></b></td>
		    </tr>
		    <tr>
		      <td><div align="right">Inf. Evolutivo 1er Semestre: </div></td>
		      <td><b><?php if ($rowExpediente['evolutivoprimer'] == 0) { echo "NO"; } else { echo "SI"; }?></b></td>
		      <td><div align="right">Inf. Evolutivo 2do Semestre: </div></td>
		      <td><b><?php if ($rowExpediente['evolutivosegundo'] == 0) { echo "NO"; } else { echo "SI"; } ?></b></td>
		      <td><div align="right">Entrevista Admisión: </div></td>
		      <td><b><?php if ($rowExpediente['admision'] == 0) { echo "NO"; } else { echo "SI"; } ?></b></td>
		    </tr>
		    <tr>
		      <td><div align="right">Observaciones:</div></td>
		      <td colspan="6"><b>
		        <?php if ($rowExpediente['observaciones'] != '' ) { echo $rowExpediente['observaciones']; } else { echo "-"; }  ?>
		      </b></td>
		    </tr>
		  </table>
<?php } ?>
	  <table width="500" border="0">
	    <tr>
	      <td width="436" height="41">
	        <div align="right">
	          <input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" />
	        </div></td>
	      <td width="454"><p align="left">
	        <?php if ($activo == 1 && !isset($_GET['nomostrar'])) { ?>
	        <input type='button' name='modificar' value='Modificar' onclick="location.href='modificarDiscapacitado.php?nroafiliado=<?php echo $nroafiliado ?>&nroorden=<?php echo $nroorden ?>'" />
	      </p>
	      </td>
	    </tr>
	  </table>
    <?php } ?>
</div>
</body>
</html>