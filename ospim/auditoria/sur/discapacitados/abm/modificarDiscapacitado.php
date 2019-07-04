<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php");
include($libPath."funcionespracticas.php");

$nroafiliado = $_GET['nroafiliado'];
$nroorden = $_GET['nroorden'];

if ($nroorden == 0) {
	$sqlBeneficiario = "SELECT t.apellidoynombre, t.cuil as cuilbene, d.*, '' as parentesco 
							FROM titulares t
							LEFT JOIN discapacitados d on t.nroafiliado = d.nroafiliado and d.nroorden = $nroorden
							WHERE  t.nroafiliado = $nroafiliado";
	$tipoBeneficiario = "TITULAR";
} else {
	$sqlBeneficiario = "SELECT f.apellidoynombre, f.cuil as cuilbene, p.descrip as parentesco, d.* 
								FROM parentesco p, familiares f
								LEFT JOIN discapacitados d on f.nroafiliado = d.nroafiliado and d.nroorden = f.nroorden
								WHERE f.nroafiliado = $nroafiliado and f.nroorden = $nroorden and f.tipoparentesco = p.codparent";
	$tipoBeneficiario = "FAMILIAR";
}
$resBeneficiario = mysql_query($sqlBeneficiario,$db);
$rowBeneficiario = mysql_fetch_assoc($resBeneficiario);

$arrayTipoBene = array();
$sqlTipoDiscaBene = "SELECT * FROM discapacidadbeneficiario WHERE nroafiliado = $nroafiliado and nroorden = $nroorden";
$resTipoDiscaBene = mysql_query($sqlTipoDiscaBene,$db);
$canTipoDiscaBene = mysql_num_rows($resTipoDiscaBene);
if ($canTipoDiscaBene > 0) {
	while ($rowTipoDiscaBene = mysql_fetch_assoc($resTipoDiscaBene)) {
		$arrayTipoBene[$rowTipoDiscaBene['iddiscapacidad']] = $rowTipoDiscaBene['iddiscapacidad'];
	}
}

$sqlExpediente = "SELECT * FROM discapacitadoexpendiente WHERE nroafiliado = $nroafiliado and nroorden = $nroorden";
$resExpediente = mysql_query($sqlExpediente,$db);
$rowExpediente = mysql_fetch_assoc($resExpediente);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modificar Discapacitado :.</title>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(function($){
	$("#fechaAlta").mask("99-99-9999");
	$("#fechaInicio").mask("99-99-9999");
	$("#fechaFin").mask("99-99-9999");
});

function validar(formulario) {
	var grupo = formulario.tipodisca;
	var total = grupo.length;
	var checkeados = 0; 
	for (var i = 0; i < total; i++) {
		if (grupo[i].checked) {
			checkeados = 1; 
		}
	}
	if (checkeados == 0) {
		alert("Debe elegir al menos un tipo de Discapcidad");
		return false;	
	}
	
	var fechaInicio = formulario.fechaInicio.value;
	var fechaFin = formulario.fechaFin.value;
	var fechaAlta = formulario.fechaAlta.value;

	if (fechaAlta == "") {
		alert("Debe ingresar un fecha de alta del certificado");
		return(false);
	} else {
		if (!esFechaValida(fechaAlta)) {
			alert("La fecha de alta de certificado no es valida");
			return(false);
		} 
	}
	
	if (fechaInicio == "") {
		alert("Debe ingresar un fecha de emisión del certificado");
		return(false);
	} else {
		if (!esFechaValida(fechaInicio)) {
			alert("La fecha de Emisión de certificado no es valida");
			return(false);
		} 
	}
	
	if (fechaFin == "") {
		alert("Debe ingresar un fecha de vto del certificado");
		return(false);
	} else {
		if (!esFechaValida(fechaFin)) {
			alert("La fecha de vto de certificado no es valida");
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

	if (formulario.codigocertificado.value == "") {
		alert("El codigo de certificado es obligatorio");
		return(false);
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
	window.open(dire,'Certificado de Discapacidad','width=800, height=500, resizable=yes');
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href='moduloABMDisca.php?nroafiliado=<?php echo $nroafiliado ?>'" /></p>
  <h3>Modificar  Discapacitado</h3>
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
  <form action="guardarModificacionDiscapacitado.php?nroafiliado=<?php echo $nroafiliado ?>&nroorden=<?php echo $nroorden ?>&idexpediente=<?php echo $rowExpediente['idexpediente'] ?>&cuil=<?php echo $rowBeneficiario['cuil'] ?>" method="post" enctype="multipart/form-data" name="modifDisca" id="modifDisca" onsubmit="return validar(this)">
	<table width="400" style="margin-top: 10px">
      <tr>
        <td width="181"><h3 align="center">Tipo Discapacidad</h3></td>
        <td width="209"><div align="left">
            <?php  
	   		$sqlTipoDiscapacidad = "Select * from tipodiscapacidad";
	   	    $resTipoDiscapacidad = mysql_query($sqlTipoDiscapacidad,$db);
			while ($rowTipoDiscapacidad = mysql_fetch_assoc($resTipoDiscapacidad)) {
				$checked = '';
				foreach($arrayTipoBene as $disca) {
					if ($rowTipoDiscapacidad['iddiscapacidad'] == $disca) {
						$checked = 'checked';
					}
				} ?>
				<input type='checkbox' id='tipodisca' name='tipodisca<?php echo $rowTipoDiscapacidad['iddiscapacidad'] ?>' value='<?php echo $rowTipoDiscapacidad['iddiscapacidad'] ?>' <?php echo $checked ?>/><?php echo $rowTipoDiscapacidad['descripcion']."<br>" ?>
	 <?php } ?>
        </div></td>
      </tr>
    </table>
	<table width="1000" style="text-align: center">
      <tr>
        <td colspan="6"><h3 align="center">Datos Certificado </h3></td>
      </tr>
      <tr>
      	<td>Fecha Alta: <input type="text" name="fechaAlta" id="fechaAlta" size="8" value="<?php echo invertirFecha($rowBeneficiario['fechaalta']) ?>"/></td>
        <td>Fecha Emision: <input type="text" name="fechaInicio" id="fechaInicio" size="8" value="<?php echo invertirFecha($rowBeneficiario['emisioncertificado']) ?>"/></td>
        <td>Fecha Vto:  <input type="text" name="fechaFin" id="fechaFin" size="8" value="<?php echo invertirFecha($rowBeneficiario['vencimientocertificado']) ?>" /></td>
     	<td><input name="ver2" type="button" id="ver2" value="Ver Certificado" onclick="verCertificado('verCertificado.php?nroafiliado=<?php echo $nroafiliado ?>&amp;nroorden=<?php echo $nroorden ?>')" /></td>
      </tr>
      <tr>
      	<td colspan="2">
	        Codigo Cert: <input name="codigocertificado" type="text" id="codigocertificado" size="40" value="<?php echo $rowBeneficiario['codigocertificado'] ?>" maxlength="50"/>
	     </td>
	     <td colspan="2">
	        Modificar:  <input name="certificado" type="file" id="certificado" />
	     </td>
      </tr>
    </table>
	<table width="1000" border="0">
      <tr>
	  	<?php if ($rowExpediente['completo'] == 0) { $estado = "[Incompleto]"; } else { $estado = "[Completo: ".$rowExpediente['fechacierre']."]"; } ?>	
        <td height="56" colspan="8"><h3 align="center">Datos Expediente <?php echo $estado ?></h3></td>
      </tr>
      <tr>
        <td><div align="right">Pedido Medico</div></td>
        <td>
		<?php if ($rowExpediente['pedidomedico'] == 0) { $seletedNO = 'selected'; $seletedSI = ''; } else { $seletedNO = ''; $seletedSI = 'selected'; } ?>
		<select name="pedidomedico" id="pedidomedico">
            <option value="0" <?php echo $seletedNO ?>>NO</option>
            <option value="1" <?php echo $seletedSI ?>>SI</option>
        </select>		</td>
        <td><div align="right">Presupuesto</div></td>
        <td>
		<?php if ($rowExpediente['presupuesto'] == 0) { $seletedNO = 'selected'; $seletedSI = ''; } else { $seletedNO = ''; $seletedSI = 'selected'; } ?>
		<select name="presupuesto" id="presupuesto">
            <option value="0" <?php echo $seletedNO ?>>NO</option>
            <option value="1" <?php echo $seletedSI ?>>SI</option>
        </select></td>
        <td><div align="right">Presupuesto Trasnporte </div></td>
        <td>
		<?php 
			if ($rowExpediente['presupuestotransporte'] == 0) { $seletedNO = 'selected'; $seletedSI = ''; $seletedNR = ''; } 
			if ($rowExpediente['presupuestotransporte'] == 1) { $seletedNO = ''; $seletedSI = 'selected'; $seletedNR = ''; } 
			if ($rowExpediente['presupuestotransporte'] == 2) { $seletedNO = ''; $seletedSI = ''; $seletedNR = 'selected'; }  
		?>
		<select name="presupuestotrasnporte" id="presupuestotrasnporte">
            <option value="0" <?php echo $seletedNO ?>>NO</option>
            <option value="1" <?php echo $seletedSI ?>>SI</option>
            <option value="2" <?php echo $seletedNR ?>>No Requerido</option>
        </select></td>
      </tr>
      <tr>
        <td><div align="right">Registro SSS </div></td>
        <td>
		<?php 
			if ($rowExpediente['registrosss'] == 0) { $seletedNO = 'selected'; $seletedSI = ''; $seletedNR = ''; } 
			if ($rowExpediente['registrosss'] == 1) { $seletedNO = ''; $seletedSI = 'selected'; $seletedNR = ''; } 
			if ($rowExpediente['registrosss'] == 2) { $seletedNO = ''; $seletedSI = ''; $seletedNR = 'selected'; }  
		?>
		<select name="registrosss" id="registrosss">
            <option value="0" <?php echo $seletedNO ?>>NO</option>
            <option value="1" <?php echo $seletedSI ?>>SI</option>
            <option value="2" <?php echo $seletedNR ?>>No Requerido</option>
        </select></td>
        <td><div align="right">Resoluci&oacute;n SNR</div></td>
        <td>
		<?php 
			if ($rowExpediente['resolucionsnr'] == 0) { $seletedNO = 'selected'; $seletedSI = ''; $seletedNR = ''; } 
			if ($rowExpediente['resolucionsnr'] == 1) { $seletedNO = ''; $seletedSI = 'selected'; $seletedNR = ''; } 
			if ($rowExpediente['resolucionsnr'] == 2) { $seletedNO = ''; $seletedSI = ''; $seletedNR = 'selected'; }  
		?>
		<select name="resolucionsnr" id="resolucionsnr">
            <option value="0" <?php echo $seletedNO ?>>NO</option>
            <option value="1" <?php echo $seletedSI ?>>SI</option>
            <option value="2" <?php echo $seletedNR ?>>No Requerido</option>
        </select></td>
        <td><div align="right">Titulo Habilitante</div></td>
        <td>
		<?php 
			if ($rowExpediente['titulo'] == 0) { $seletedNO = 'selected'; $seletedSI = ''; $seletedNR = ''; } 
			if ($rowExpediente['titulo'] == 1) { $seletedNO = ''; $seletedSI = 'selected'; $seletedNR = ''; } 
			if ($rowExpediente['titulo'] == 2) { $seletedNO = ''; $seletedSI = ''; $seletedNR = 'selected'; }  
		?>
		<select name="titulo" id="titulo">
            <option value="0" <?php echo $seletedNO ?>>NO</option>
            <option value="1" <?php echo $seletedSI ?>>SI</option>
            <option value="2" <?php echo $seletedNR ?>>No Requerido</option>
        </select></td>
      </tr>
      <tr>
        <td><div align="right">Plan Tratamiento </div></td>
        <td>
		<?php if ($rowExpediente['plantratamiento'] == 0) { $seletedNO = 'selected'; $seletedSI = ''; } else { $seletedNO = ''; $seletedSI = 'selected'; } ?>
		<select name="plantratamiento" id="plantratamiento">
            <option value="0" <?php echo $seletedNO ?>>NO</option>
            <option value="1" <?php echo $seletedSI ?>>SI</option>
        </select></td>
        <td><div align="right">Dependencia</div></td>
        <td>
          <?php 
			 if ($rowExpediente['dependencia'] == 0) { $seletedNO = 'selected'; $seletedSI = ''; $seletedNR = ''; } 
			 if ($rowExpediente['dependencia'] == 1) { $seletedNO = ''; $seletedSI = 'selected'; $seletedNR = ''; } 
			 if ($rowExpediente['dependencia'] == 2) { $seletedNO = ''; $seletedSI = ''; $seletedNR = 'selected'; }  
		  ?>
          <select name="dependencia" id="dependencia">
            <option value="0" <?php echo $seletedNO ?>>NO</option>
            <option value="1" <?php echo $seletedSI ?>>SI</option>
			<option value="2" <?php echo $seletedNR ?>>No Requerido</option>
          </select>
        </td>
        <td><div align="right">Historia Clinica</div></td>
        <td>
		<?php if ($rowExpediente['resumenhistoria'] == 0) { $seletedNO = 'selected'; $seletedSI = ''; } else { $seletedNO = ''; $seletedSI = 'selected'; } ?>
		<select name="historia" id="historia">
             <option value="0" <?php echo $seletedNO ?>>NO</option>
             <option value="1" <?php echo $seletedSI ?>>SI</option>
        </select></td>
      </tr>
      <tr>
        <td><div align="right">Planilla FIM</div></td>
        <td>
		<?php 
			if ($rowExpediente['planillafim'] == 0) { $seletedNO = 'selected'; $seletedSI = ''; $seletedNR = ''; } 
			if ($rowExpediente['planillafim'] == 1) { $seletedNO = ''; $seletedSI = 'selected'; $seletedNR = ''; } 
			if ($rowExpediente['planillafim'] == 2) { $seletedNO = ''; $seletedSI = ''; $seletedNR = 'selected'; }  
		?>
		<select name="planillafim" id="planillafim">
            <option value="0" <?php echo $seletedNO ?>>NO</option>
            <option value="1" <?php echo $seletedSI ?>>SI</option>
            <option value="2" <?php echo $seletedNR ?>>No Requerido</option>
        </select></td>
        <td><div align="right">Consentimiento Tratamiento </div></td>
        <td>
		<?php if ($rowExpediente['consentimientotratamiento'] == 0) { $seletedNO = 'selected'; $seletedSI = ''; } else { $seletedNO = ''; $seletedSI = 'selected'; } ?>
		<select name="consentimientotratamiento" id="consentimientotratamiento">
            <option value="0" <?php echo $seletedNO ?>>NO</option>
            <option value="1" <?php echo $seletedSI ?>>SI</option>
        </select></td>
        <td><div align="right">Consentimiento Trasnporte</div></td>
        <td>
		<?php 
			if ($rowExpediente['consentimientotransporte'] == 0) { $seletedNO = 'selected'; $seletedSI = ''; $seletedNR = ''; } 
			if ($rowExpediente['consentimientotransporte'] == 1) { $seletedNO = ''; $seletedSI = 'selected'; $seletedNR = ''; } 
			if ($rowExpediente['consentimientotransporte'] == 2) { $seletedNO = ''; $seletedSI = ''; $seletedNR = 'selected'; }  
		?>
		<select name="consentimientotransporte" id="consentimientotransporte">
            <option value="0" <?php echo $seletedNO ?>>NO</option>
            <option value="1" <?php echo $seletedSI ?>>SI</option>
            <option value="2" <?php echo $seletedNR ?>>No Requerido</option>
        </select></td>
      </tr>
      <tr>
        <td><div align="right">Constancia Alumno </div></td>
        <td>
		<?php 
			if ($rowExpediente['constanciaalumno'] == 0) { $seletedNO = 'selected'; $seletedSI = ''; $seletedNR = ''; } 
			if ($rowExpediente['constanciaalumno'] == 1) { $seletedNO = ''; $seletedSI = 'selected'; $seletedNR = ''; } 
			if ($rowExpediente['constanciaalumno'] == 2) { $seletedNO = ''; $seletedSI = ''; $seletedNR = 'selected'; }  
		?>
		<select name="constancia" id="constancia">
            <option value="0" <?php echo $seletedNO ?>>NO</option>
            <option value="1" <?php echo $seletedSI ?>>SI</option>
            <option value="2" <?php echo $seletedNR ?>>No Requerido</option>
        </select></td>
        <td><div align="right">Adaptaciones Curriculares </div></td>
        <td>
		<?php 
			if ($rowExpediente['adaptaciones'] == 0) { $seletedNO = 'selected'; $seletedSI = ''; $seletedNR = ''; } 
			if ($rowExpediente['adaptaciones'] == 1) { $seletedNO = ''; $seletedSI = 'selected'; $seletedNR = ''; } 
			if ($rowExpediente['adaptaciones'] == 2) { $seletedNO = ''; $seletedSI = ''; $seletedNR = 'selected'; }  
		?>
		<select name="adaptaciones" id="adaptaciones">
            <option value="0" <?php echo $seletedNO ?>>NO</option>
            <option value="1" <?php echo $seletedSI ?>>SI</option>
            <option value="2" <?php echo $seletedNR ?>>No Requerido</option>
        </select></td>
        <td><div align="right">Acta Acuerdo </div></td>
        <td>
		<?php 
			if ($rowExpediente['actaacuerdo'] == 0) { $seletedNO = 'selected'; $seletedSI = ''; $seletedNR = ''; } 
			if ($rowExpediente['actaacuerdo'] == 1) { $seletedNO = ''; $seletedSI = 'selected'; $seletedNR = ''; } 
			if ($rowExpediente['actaacuerdo'] == 2) { $seletedNO = ''; $seletedSI = ''; $seletedNR = 'selected'; }  
		?>
		<select name="acta" id="acta">
            <option value="0" <?php echo $seletedNO ?>>NO</option>
            <option value="1" <?php echo $seletedSI ?>>SI</option>
            <option value="2" <?php echo $seletedNR ?>>No Requerido</option>
        </select></td>
      </tr>
      <tr>
        <td><div align="right">Certificado Discapacidad</div></td>
        <td>
		<?php if ($rowExpediente['certificadodiscapacidad'] == 0) { $seletedNO = 'selected'; $seletedSI = ''; } else { $seletedNO = ''; $seletedSI = 'selected'; } ?>
		<select name="certificadodisca" id="certificadodisca">
            <option value="0" <?php echo $seletedNO ?>>NO</option>
            <option value="1" <?php echo $seletedSI ?>>SI</option>
        </select></td>
        <td><div align="right">Recibo de Sueldo</div></td>
        <td>
		<?php 
			if ($rowExpediente['recibosueldo'] == 0) { $seletedNO = 'selected'; $seletedSI = ''; $seletedNR = ''; } 
			if ($rowExpediente['recibosueldo'] == 1) { $seletedNO = ''; $seletedSI = 'selected'; $seletedNR = ''; } 
			if ($rowExpediente['recibosueldo'] == 2) { $seletedNO = ''; $seletedSI = ''; $seletedNR = 'selected'; }  
		?>
		<select name="recibo" id="recibo">
            <option value="0" <?php echo $seletedNO ?>>NO</option>
            <option value="1" <?php echo $seletedSI ?>>SI</option>
            <option value="2" <?php echo $seletedNR ?>>No Requerido</option>
        </select></td>
        <td><div align="right">Seguro Desempleo</div></td>
        <td>
		<?php 
			if ($rowExpediente['segurodesempleo'] == 0) { $seletedNO = 'selected'; $seletedSI = ''; $seletedNR = ''; } 
			if ($rowExpediente['segurodesempleo'] == 1) { $seletedNO = ''; $seletedSI = 'selected'; $seletedNR = ''; } 
			if ($rowExpediente['segurodesempleo'] == 2) { $seletedNO = ''; $seletedSI = ''; $seletedNR = 'selected'; }  
		?>
		<select name="seguro" id="seguro">
            <option value="0" <?php echo $seletedNO ?>>NO</option>
            <option value="1" <?php echo $seletedSI ?>>SI</option>
            <option value="2" <?php echo $seletedNR ?>>No Requerido</option>
        </select></td>
      </tr>
      
       <tr>
        <td><div align="right">Informe Evolutivo 1er Semestre</div></td>
        <td>
		<?php if ($rowExpediente['evolutivoprimer'] == 0) { $seletedNO = 'selected'; $seletedSI = ''; } else { $seletedNO = ''; $seletedSI = 'selected'; } ?>
		<select name="evolutivoprimer" id="evolutivoprimer">
            <option value="0" <?php echo $seletedNO ?>>NO</option>
            <option value="1" <?php echo $seletedSI ?>>SI</option>
        </select></td>
        <td><div align="right">Informe Evolutivo 2do Semestre</div></td>
        <td>
		<?php if ($rowExpediente['evolutivosegundo'] == 0) { $seletedNO = 'selected'; $seletedSI = ''; } else { $seletedNO = ''; $seletedSI = 'selected'; } ?>
		<select name="evolutivosegundo" id="evolutivosegundo">
            <option value="0" <?php echo $seletedNO ?>>NO</option>
            <option value="1" <?php echo $seletedSI ?>>SI</option>
        </select></td>
        <td><div align="right">Entrevista Admisión</div></td>
        <td>
		<?php if ($rowExpediente['admision'] == 0) { $seletedNO = 'selected'; $seletedSI = ''; } else { $seletedNO = ''; $seletedSI = 'selected'; } ?>
		<select name="admision" id="admision">
            <option value="0" <?php echo $seletedNO ?>>NO</option>
            <option value="1" <?php echo $seletedSI ?>>SI</option>
        </select></td>
      </tr>

      <tr>
        <td><div align="right">Observaciones</div></td>
        <td colspan="5"><label>
          <textarea name="observacion" cols="90" rows="3" id="observacion"><?php echo $rowExpediente['observaciones'] ?></textarea>
        </label></td>
      </tr>
    </table>
	<p><input type="submit" name="Submit" value="Guardar" /></p>
</form>
</div>
</body>
</html>