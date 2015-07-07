<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php");
include($libPath."funcionespracticas.php");

$nroafiliado = $_GET['nroafiliado'];
$nroorden = $_GET['nroorden'];

if ($nroorden == 0) {
	$sqlBeneficiario = "SELECT apellidoynombre FROM titulares WHERE nroafiliado = $nroafiliado";
	$tipoBeneficiario = "TITULAR";
} else {
	$sqlBeneficiario = "SELECT f.apellidoynombre, p.descrip as parentesco FROM familiares f, parentesco p WHERE f.nroafiliado = $nroafiliado and f.nroorden = $nroorden and f.tipoparentesco = p.codparent";
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

<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
.Estilo3 {font-size: 18px}
</style>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(function($){
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
	if (fechaInicio == "") {
		alert("Debe ingresar un fecha de emisión del certificado");
		return(false);
	} else {
		if (!esFechaValida(fechaInicio)) {
			alert("La fecha de Emisión de certificado no es valida");
			return(false);
		} 
	}
	if (fechaFin != "") {
		if (!esFechaValida(fechaFin)) {
			alert("La Fecha de Vencimiento no es valida");
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
  <p><span style="text-align:center">
   <input type="button" name="volver" value="Volver" onclick="location.href='moduloABMDisca.php'" />
  </span></p>
  <p class="Estilo2">Alta de Discapacitado  </p>
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
  <form action="guardarNuevoDiscapacitado.php?nroafiliado=<?php echo $nroafiliado ?>&nroorden=<?php echo $nroorden ?>" method="post" enctype="multipart/form-data" name="nuevoDisca" id="nuevoDisca" onsubmit="return validar(this)">
   <table width="400" border="0">
     <tr>
       <td width="181"><div align="right"><span class="Estilo2">Tipo Discapacidad</span> </div></td>
       <td width="209">
	     <div align="left">
	       <?php  
	   		$sqlTipoDiscapacidad = "Select * from tipodiscapacidad";
	   	    $resTipoDiscapacidad = mysql_query($sqlTipoDiscapacidad,$db);
			while ($rowTipoDiscapacidad = mysql_fetch_assoc($resTipoDiscapacidad)) {
				echo ("<input type='checkbox' id='tipodisca' name='tipodisca".$rowTipoDiscapacidad['iddiscapacidad']."' value='".$rowTipoDiscapacidad['iddiscapacidad']."' />".$rowTipoDiscapacidad['descripcion']."<br>");
			} ?>
         </div></td>
     </tr>
   </table>
   <table width="900" border="0">
      <tr>
        <td height="47" colspan="6"><div align="center"><span class="Estilo2">Datos Certificado </span></div></td>
      </tr>
      <tr>
        <td><div align="right">Fecha De Emision</div></td>
        <td><div align="left">
          <input type="text" name="fechaInicio" id="fechaInicio" size="8"/>
        </div></td>
        <td><div align="right">Fecha de Vencimiento</div></td>
        <td><div align="left">
          <input type="text" name="fechaFin" id="fechaFin" size="8" />
        </div></td>
        <td><div align="right">Certificado</div></td>
        <td><div align="left">
          <input name="certificado" type="file" id="certificado" />
        </div></td>
      </tr>
    </table>
    <table width="900" border="0">
      <tr>
        <td height="56" colspan="8"><div align="center"><span class="Estilo2">Datos Expediente </span></div></td>
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
        <td><div align="right">Informe Evolutivo</div></td>
        <td><select name="informe" id="informe">
          <option value="0">NO</option>
          <option value="1">SI</option>
        </select></td>
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
	     <td><div align="right">Dependencia</div></td>
	     <td><label>
	       <select name="dependencia" id="dependencia">
             <option value="0">NO</option>
             <option value="1">SI</option>
			 <option value="2">No Requerido</option>
           </select>
	     </label></td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
         <td>&nbsp;</td>
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