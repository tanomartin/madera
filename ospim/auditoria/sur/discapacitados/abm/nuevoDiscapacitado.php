<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php");
include($libPath."funcionespracticas.php");

$nroafiliado = $_GET['nroafiliado'];
$nroorden = $_GET['nroorden'];

if ($nroorden == 0) {
	$sqlBeneficiario = "SELECT apellidoynombre, cuil, '' as parentesco FROM titulares WHERE nroafiliado = $nroafiliado";
	$tipoBeneficiario = "TITULAR";
} else {
	$sqlBeneficiario = "SELECT f.apellidoynombre, f.cuil, p.descrip as parentesco FROM familiares f, parentesco p WHERE f.nroafiliado = $nroafiliado and f.nroorden = $nroorden and f.tipoparentesco = p.codparent";
	$tipoBeneficiario = "FAMILIAR";
}
$resBeneficiario = mysql_query($sqlBeneficiario,$db);
$rowBeneficiario = mysql_fetch_assoc($resBeneficiario);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Nuevo Discapacitado :.</title>
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
	if (extension != '.jpg') {
		alert("El certificado debe ser un archivo .jpg");
		return(false);
	}
	formulario.Submit.disabled = true;
	return true;
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href='moduloABMDisca.php'" /></p>
  <h3>Alta de Discapacitado  </h3>
  <table width="500" border="1">
    <tr>
      <td width="163"><div align="right"><strong>Nro Afiliado </strong></div></td>
      <td width="321"><div align="left"><strong><?php echo $nroafiliado ?></strong></div></td>
    </tr>
    <tr>
      <td width="163"><div align="right"><strong>C.U.I.L.</strong></div></td>
      <td width="321"><div align="left"><?php echo $rowBeneficiario['cuil'] ?></div></td>
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
  <form action="guardarNuevoDiscapacitado.php?nroafiliado=<?php echo $nroafiliado ?>&nroorden=<?php echo $nroorden ?>" method="post" enctype="multipart/form-data" name="nuevoDisca" id="nuevoDisca" onsubmit="return validar(this)">
   <table width="400" style="margin-top: 10px">
     <tr>
       <td width="181"><h3 align="center">Tipo Discapacidad</h3></td>
       <td width="209" align="left">
	   <?php $sqlTipoDiscapacidad = "Select * from tipodiscapacidad";
	   	     $resTipoDiscapacidad = mysql_query($sqlTipoDiscapacidad,$db);
			 while ($rowTipoDiscapacidad = mysql_fetch_assoc($resTipoDiscapacidad)) { ?>
				<input type='checkbox' id='tipodisca' name='tipodisca<?php echo $rowTipoDiscapacidad['iddiscapacidad'] ?>' value='<?php echo $rowTipoDiscapacidad['iddiscapacidad'] ?>' /><?php echo $rowTipoDiscapacidad['descripcion']."<br>" ?>
	   <?php } ?>
       </td>
     </tr>
   </table>
   <table width="900" style="text-align: center">
      <tr>
        <td colspan="6"><h3 align="center">Datos Certificado </h3></td>
      </tr>
      <tr>
      	<td>Fecha Alta: <input type="text" name="fechaAlta" id="fechaAlta" size="8"/></td>
        <td>Fecha Emision: <input type="text" name="fechaInicio" id="fechaInicio" size="8"/></td>
        <td>Fecha Vto: <input type="text" name="fechaFin" id="fechaFin" size="8" /></td>
      </tr>
      <tr>
        <td colspan="2">Codigo Cert.: <input name="codigocertificado" type="text" id="codigocertificado" size="40" maxlength="40"/></td>
      	<td>Certificado: <input name="certificado" type="file" id="certificado" /></td>
      </tr> 
    </table>
    <table width="900" border="0">
      <tr>
        <td height="56" colspan="8"><h3 align="center">Datos Expediente </h3></td>
      </tr>
      <tr>
        <td><div align="right">Pedido Medico</div></td>
        <td><select name="pedidomedico" id="pedidomedico">
          <option value="0">NO</option>
          <option value="1">SI</option>
        </select></td>
        <td><div align="right">Presupuesto</div></td>
        <td><select name="presupuesto" id="presupuesto">
          <option value="0">NO</option>
          <option value="1">SI</option>
        </select></td>
        <td><div align="right">Presupuesto Trasnporte </div></td>
        <td><select name="presupuestotrasnporte" id="presupuestotrasnporte">
          <option value="0">NO</option>
          <option value="1">SI</option>
		  <option value="2">No Requerido</option>
        </select></td>
      </tr>
	  <tr>
	  	<td><div align="right">Registro SSS </div></td>
	  	<td><select name="registrosss" id="registrosss">
          <option value="0">NO</option>
          <option value="1">SI</option>
		  <option value="2">No Requerido</option>
        </select></td>
        <td><div align="right">Resolución SNR</div></td>
        <td><select name="resolucionsnr" id="resolucionsnr">
          <option value="0">NO</option>
          <option value="1">SI</option>
		  <option value="2">No Requerido</option>
        </select></td>
        <td><div align="right">Titulo Habilitante</div></td>
        <td><select name="titulo" id="titulo">
          <option value="0">NO</option>
          <option value="1">SI</option>
		  <option value="2">No Requerido</option>
        </select></td>
      </tr>
	  <tr>
	    <td><div align="right">Plan Tratamiento </div></td>
        <td><select name="plantratamiento" id="plantratamiento">
          <option value="0">NO</option>
          <option value="1">SI</option>
        </select></td>
        <td><div align="right">Dependencia</div></td>
	     <td>
	       <select name="dependencia" id="dependencia">
             <option value="0">NO</option>
             <option value="1">SI</option>
			 <option value="2">No Requerido</option>
           </select>
	     </td>
        <td><div align="right">Historia Clinica</div></td>
        <td><select name="historia" id="historia">
          <option value="0">NO</option>
          <option value="1">SI</option>
        </select></td>
      </tr>
	   <tr>
	     <td><div align="right">Planilla FIM</div></td>
	     <td><select name="planillafim" id="planillafim">
             <option value="0">NO</option>
             <option value="1">SI</option>
             <option value="2">No Requerido</option>
         </select></td>
	     <td><div align="right">Consentimiento Tratamiento </div></td>
	     <td><select name="consentimientotratamiento" id="consentimientotratamiento">
             <option value="0">NO</option>
             <option value="1">SI</option>
         </select></td>
	     <td><div align="right">Consentimiento Trasnporte</div></td>
	     <td><select name="consentimientotransporte" id="consentimientotransporte">
             <option value="0">NO</option>
             <option value="1">SI</option>
             <option value="2">No Requerido</option>
         </select></td>
      </tr>
	   <tr>
		<td><div align="right">Constancia Alumno </div></td>
        <td><select name="constancia" id="constancia">
          <option value="0">NO</option>
          <option value="1">SI</option>
		  <option value="2">No Requerido</option>
        </select></td>
        <td><div align="right">Adaptaciones Curriculares </div></td>
        <td><select name="adaptaciones" id="adaptaciones">
          <option value="0">NO</option>
          <option value="1">SI</option>
          <option value="2">No Requerido</option>
        </select></td>
        <td><div align="right">Acta Acuerdo </div></td>
        <td><select name="acta" id="acta">
          <option value="0">NO</option>
          <option value="1">SI</option>
		   <option value="2">No Requerido</option>
        </select></td>
      </tr>
	   <tr>
		<td><div align="right">Certificado Discapacidad</div></td>
        <td><select name="certificadodisca" id="certificadodisca">
          <option value="0">NO</option>
          <option value="1">SI</option>
        </select></td>
        <td><div align="right">Recibo de Sueldo</div></td>
        <td><select name="recibo" id="recibo">
          <option value="0">NO</option>
          <option value="1">SI</option>
          <option value="2">No Requerido</option>
        </select></td>
        <td><div align="right">Seguro Desempleo</div></td>
        <td><select name="seguro" id="seguro">
          <option value="0">NO</option>
          <option value="1">SI</option>
		   <option value="2">No Requerido</option>
        </select></td>
      </tr>
      
      <tr>
		<td><div align="right">Inf. Evolutivo 1er Semestre</div></td>
        <td><select name="evolutivoprimer" id="evolutivoprimer">
          <option value="0">NO</option>
          <option value="1">SI</option>
        </select></td>
        <td><div align="right">Inf. Evolutivo 2do Semestre</div></td>
        <td><select name="evolutivosegundo" id="evolutivosegundo">
          <option value="0">NO</option>
          <option value="1">SI</option>
        </select></td>
        <td><div align="right">Entrevista Admisión</div></td>
        <td><select name="admision" id="admision">
          <option value="0">NO</option>
          <option value="1">SI</option>
        </select></td>
      </tr>
      
	   <tr>
	     <td><div align="right">Observaciones</div></td>
	     <td colspan="5"><label>
           <textarea name="observacion" cols="90" rows="3" id="observacion"></textarea>
         </label></td>
      </tr>
    </table>
    <p><input type="submit" name="Submit" value="Guardar" /></p>
  </form>
</div>
</body>
</html>