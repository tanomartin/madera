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

$sqlTipoDiscapacidad = "SELECT *, t.descripcion FROM discapacidadbeneficiario d, tipodiscapacidad t WHERE d.nroafiliado = $nroafiliado and d.nroorden = $nroorden and d.iddiscapacidad = t.iddiscapacidad";
$resTipoDiscapacidad = mysql_query($sqlTipoDiscapacidad,$db);

$sqlExpediente = "SELECT * FROM discapacitadoexpendiente WHERE nroafiliado = $nroafiliado and nroorden = $nroorden";
$resExpediente = mysql_query($sqlExpediente,$db);
$rowExpediente = mysql_fetch_assoc($resExpediente);

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
	window.open(dire,'Certificado de Discapacidad','width=800, height=500,resizable=yes');
}

</script>
<body bgcolor="#CCCCCC">
<div align="center">
  <p><span style="text-align:center">
    <?php if (!isset($_GET['nomostrar'])) { ?> <input type="reset" name="volver" value="Volver" onclick="location.href='moduloABMDisca.php'" align="center"/> <?php } ?>
  </span></p>
  <p class="Estilo2">Consulta de Discapacidado </p>
  <p>
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
 </p>
  <table width="400" border="0">
    <tr>
      <td width="180"><div align="right"><span class="Estilo2">Tipo Discapacidad</span> </div></td>
      <td width="20">&nbsp;</td>
      <td width="186"><div align="left">
        <?php  
			$cantTipoDisca = mysql_num_rows($resTipoDiscapacidad);
			if ($cantTipoDisca == 0) { 
				echo "No especificado"; 
			} else {
				while ($rowTipoDiscapacidad = mysql_fetch_assoc($resTipoDiscapacidad)) {
					echo ($rowTipoDiscapacidad['descripcion']."<br>");
				} 
			}?>
      </div></td>
    </tr>
  </table>
  <table width="900" border="0">
    <tr>
      <td height="47" colspan="6"><div align="center"><span class="Estilo2">Datos Certificado </span></div></td>
    </tr>
    <tr>
      <td><div align="right">Fecha De Emision: </div></td>
      <td><div align="left"><b><?php echo invertirFecha($rowBeneficiario['emisioncertificado']) ?></b></div></td>
      <td><div align="right">Fecha de Vencimiento: </div></td>
      <td><div align="left"><b><?php echo invertirFecha($rowBeneficiario['vencimientocertificado']) ?></b></div></td>
      <td><div align="right">Certificado</div></td>
      <td><div align="left"><input name="ver" type="button" id="ver" value="Ver Certificado" onclick="verCertificado('verCertificado.php?nroafiliado=<?php echo $nroafiliado ?>&nroorden=<?php echo $nroorden ?>')"/></div></td>
    </tr>
  </table>
  
  <table width="900" border="0">
    <tr>
      <td height="56" colspan="9">
	  	<div align="center">
		     <?php if ($rowExpediente['completo'] == 0) { $estado = "[Incompleto]"; } else { $estado = "[Completo: ".$rowExpediente['fechacierre']."]"; } ?>
			<span class="Estilo2">Datos Expediente <?php echo $estado ?></span>		</div>	</td>
    </tr>
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
      <td><div align="right">Informe Evolutivo:</div></td>
      <td><b><?php if ($rowExpediente['informeevolutivo'] == 0) { echo "NO"; } else { echo "SI"; }?></b></td>
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
      <td><div align="right">Constacia Alumno: </div></td>
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
      <td><div align="right">Dependencia:</div></td>
      <td><b><?php if ($rowExpediente['dependencia'] == 0) { echo "NO"; } if ($rowExpediente['dependencia'] == 1) { echo "SI"; }  if ($rowExpediente['dependencia'] == 2) { echo "No Requerido"; }?>
      </b></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td><div align="right">Observaciones:</div></td>
      <td colspan="6"><b>
        <?php if ($rowExpediente['observaciones'] != '' ) { echo $rowExpediente['observaciones']; } else { echo "-"; }  ?>
      </b></td>
    </tr>
  </table>
  <table width="500" border="0">
    <tr>
      <td width="436" height="41">
        <div align="right">
          <input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" align="center"/>
        </div></td>
      <td width="454"><p align="left">
        <?php if ($activo == 1 && !isset($_GET['nomostrar'])) { ?>
        <input type='button' name='modificar' value='Modificar' onclick="location.href='modificarDiscapacitado.php?nroafiliado=<?php echo $nroafiliado ?>&nroorden=<?php echo $nroorden ?>'" />
      </p>
      </td>
    </tr>
  </table>
  <p>
    <?php } ?>
</div>
</body>
</html>