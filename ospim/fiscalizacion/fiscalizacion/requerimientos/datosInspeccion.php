<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php"); 

$nroreq = $_GET['nroreq'];

$sqlInspe = "SELECT * from inspecfiscalizospim i, reqfiscalizospim r where i.nrorequerimiento = $nroreq and i.nrorequerimiento = r.nrorequerimiento";
$resInspe = mysql_query($sqlInspe,$db);
$rowInspe = mysql_fetch_assoc($resInspe);
$cuit = $rowInspe['cuit'];

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado de Requerimientos :.</title>
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

<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">

jQuery(function($){
	$("#fechaAsig").mask("99-99-9999");
	$("#fechaRecep").mask("99-99-9999");
	$("#fechaInspec").mask("99-99-9999");
	$("#fechaDevolu").mask("99-99-9999");
});

function formatoFormulario() {
	document.getElementById('formaEnvio').value = <?php echo $rowInspe['formaenviodocumentos'] ?>; 
	if (<?php echo $rowInspe['adjuntadocumentos'] ?> == 0) {
		document.getElementById('detalleDoc').readOnly = true;
		document.getElementById('detalleDoc').style.background = "#CCCCCC";
		$("#formaEnvio option").not(":selected").attr("disabled", "disabled");
		document.getElementById('formaEnvio').style.background = "#CCCCCC";
		document.getElementById('fechaRecep').readOnly = true;
		document.getElementById('fechaRecep').style.background = "#CCCCCC";
		document.getElementById('fechaDevolu').readOnly = true;
		document.getElementById('fechaDevolu').style.background = "#CCCCCC";
	}
	
	if (<?php echo $rowInspe['inspeccionefectuada'] ?> == 0) {
		document.getElementById('fechaInspec').readOnly = true;
		document.getElementById('fechaInspec').style.background = "#CCCCCC";
	}
}

function habilitoDocumentacion(valor) {
	if (valor == 0) {
		document.getElementById('detalleDoc').value = ""; 
		document.getElementById('detalleDoc').readOnly = true;
		document.getElementById('detalleDoc').style.background = "#CCCCCC";
		document.getElementById('formaEnvio').value = 0; 
		$("#formaEnvio option").not(":selected").attr("disabled", "disabled");
		document.getElementById('formaEnvio').style.background = "#CCCCCC";
		document.getElementById('fechaRecep').value = ""; 
		document.getElementById('fechaRecep').readOnly = true;
		document.getElementById('fechaRecep').style.background = "#CCCCCC";	
		document.getElementById('fechaDevolu').value = ""; 
		document.getElementById('fechaDevolu').readOnly = true;
		document.getElementById('fechaDevolu').style.background = "#CCCCCC";	
	} else {
		document.getElementById('detalleDoc').readOnly = false;
		document.getElementById('detalleDoc').style.background = "#FFFFFF";
		$("#formaEnvio option").not(":selected").attr("disabled", false);
		document.getElementById('formaEnvio').style.background = "#FFFFFF";
		document.getElementById('fechaRecep').readOnly = false;
		document.getElementById('fechaRecep').style.background = "#FFFFFF";	
		document.getElementById('fechaDevolu').readOnly = false;
		document.getElementById('fechaDevolu').style.background = "#FFFFFF";	
	}
}

function habilitoFechaInsp(valor) {
	if (valor == 0) {
		document.getElementById('fechaInspec').value = ""; 
		document.getElementById('fechaInspec').readOnly = true;
		document.getElementById('fechaInspec').style.background = "#CCCCCC";	
	} else {
		document.getElementById('fechaInspec').readOnly = false;
		document.getElementById('fechaInspec').style.background = "#FFFFFF";	
	}
}

function validar(formulario) {
	if(formulario.inspector.value == 0) {
		alert("Debe seleccionar un Inspector");
		formulario.inspector.focus();
		return false;
	}
	if(!esFechaValida(formulario.fechaAsig.value)) {
		alert("Fecha invalida");
		formulario.fechaAsig.focus();
		return false;
	}
	if(!esEnteroPositivo(formulario.diasefect.value) || formulario.diasefect.value == 0) {
		alert("Los días de efectivización de la inspección debe ser un entero positivo");
		formulario.diasefect.focus();
		return false;
	}
	if(formulario.docAdjuntos[0].checked) {
		if (formulario.fechaRecep.value != "" && formulario.fechaRecep.value != '00-00-0000') {
			if(!esFechaValida(formulario.fechaRecep.value)) {
				alert("Fecha invalida");
				formulario.fechaRecep.focus();
				return false;
			}
		}
		if (formulario.fechaDevolu.value != "" && formulario.fechaDevolu.value != '00-00-0000') {
			if(!esFechaValida(formulario.fechaDevolu.value)) {
				alert("Fecha invalida");
				formulario.fechaDevolu.focus();
				return false;
			}
		}
	}
	
	if (formulario.fechaDevolu.value != "" && formulario.fechaDevolu.value != '00-00-0000' && formulario.fechaRecep.value != "" && formulario.fechaRecep.value != '00-00-0000') {
		if (formulario.fechaDevolu.value < formulario.fechaRecep.value) {
			alert("La fecha de Devolución de la documentación no puede ser inferior a la fecha de recepción de la misma por parte del inspector");
			formulario.fechaDevolu.focus();
			return false;
		}
	}
	
	if(formulario.inspecEfec[0].checked) {
		if(!esFechaValida(formulario.fechaInspec.value)) {
			alert("Fecha invalida");
			formulario.fechaInspec.focus();
			return false;
		}
	}
	formulario.Submit.disabled = true;
	return true;
}

</script>

<body bgcolor="#CCCCCC" onload="formatoFormulario()">
<div align="center">
  <p><span style="text-align:center">
    <input type="reset" name="volver" value="Volver" onclick="location.href = 'listarInspecciones.php'" align="center"/>
  </span></p>
  	<p class="Estilo2">Datos de Requerimiento en Inspecci&oacute;n Nro. <?php echo $nroreq ?></p>
	<form id="datosInsp" name="datosInsp" method="post" onSubmit="return validar(this)" action="guardarDatosInpseccion.php">
		<input name="nroreq" type="text" id="nroreq" value="<?php echo  $nroreq ?>" style="display:none"/>
		<table width="800" border="0">
		  <tr>
			<td><div align="right">Inspector </div></td>
			<td><div align="left">
			 <select name="inpector" id="inspector" >
			      <option value=0>Seleccionar Inspector</option>
			<?php 
				$sqlInspector="select codigo, apeynombre from inspectores i, jurisdiccion j where j.cuit = $cuit and j.codidelega = i.codidelega";
				$resInspector=mysql_query($sqlInspector,$db);
				while ($rowInspector=mysql_fetch_array($resInspector)) { 
					if ($rowInspector['codigo'] == $rowInspe['inspectorasignado']) { ?>
			    	 	<option value="<?php echo $rowInspector['codigo'] ?>" selected="selected"><?php echo $rowInspector['apeynombre'] ?></option>
			  <?php } else { ?>
			  			<option value="<?php echo $rowInspector['codigo'] ?>"><?php echo $rowInspector['apeynombre'] ?></option>
			  <?php } 
		       } ?>
		        </select>
			</div></td>
		  </tr>
		  <tr>
			<td><div align="right">Fecha Asignaci&oacute;n </div></td>
			<td><div align="left">
			<input name="fechaAsig" type="text" id="fechaAsig" size="12" value="<?php echo  invertirFecha($rowInspe['fechaasignado']) ?>"/>
			</div></td>
		  </tr>
		  <tr>
			<td><div align="right">D&iacute;as Efectivizaci&oacute;n</div></td>
			<td><div align="left"><input name="diasefect" id="diasefect" type="text" size="4" value="<?php echo  $rowInspe['diasefectivizacion'] ?>" />
			</div></td>
		  </tr>
		  <tr>
			<td><div align="right">Doc Adjunta </div></td>
			<td><div align="left">
				<?php if ($rowInspe['adjuntadocumentos'] == 0) { ?>
				<input name="docAdjuntos" id="docAdjuntos" type="radio" value="1" onchange="habilitoDocumentacion(this.value)"/>Si 
		   		<br />
		    	<input name="docAdjuntos" id="docAdjuntos" type="radio" value="0" checked="checked" onchange="habilitoDocumentacion(this.value)"/>No
		  		<?php } else { ?>
				<input name="docAdjuntos" id="docAdjuntos" type="radio" value="1" checked="checked" onchange="habilitoDocumentacion(this.value)"/>Si 
		   		<br />
		    	<input name="docAdjuntos" id="docAdjuntos" type="radio" value="0" onchange="habilitoDocumentacion(this.value)"/>No
				<?php } ?>
				</div></td>
		  </tr>
		  <tr>
			<td><div align="right">Detalle Doc Adjunta </div></td>
			<td><div align="left">
			  <textarea name="detalleDoc" id="detalleDoc" cols="50" rows="4"><?php echo  $rowInspe['detalledocumentos'] ?></textarea>
		    </div></td>
		  </tr>
		  <tr>
			<td><div align="right">Forma de envio Doc Adjunta </div></td>
			<td><div align="left">
			  <label>
			  <select name="formaEnvio" id="formaEnvio">
			    <option value="0" selected="selected">No especificado</option>
			    <option value="1">En mano</option>
			    <option value="2">Correo Postal</option>
			    <option value="3">Correo Electr&oacute;nico</option>
			    <option value="4">FAX</option>
		      </select>
			  </label>
			</div></td>
		  </tr>
		  <tr>
		    <td><div align="right">Fecha recepci&oacute;n Doc Adjunta </div></td>
		    <td><div align="left">
		      <label><input name="fechaRecep" type="text" id="fechaRecep" size="12" value="<?php echo  invertirFecha($rowInspe['fecharecibodocumentos']) ?>"/></label>
		    </div></td>
	      </tr>
		  <tr>
		    <td><div align="right">Fecha devoluci&oacute;n Doc Adjunta </div></td>
		    <td><div align="left">
		      <input name="fechaDevolu" type="text" id="fechaDevolu" size="12" value="<?php echo  invertirFecha($rowInspe['fechadevoluciondocumentos']) ?>"/>
	        </div></td>
	      </tr>
		  <tr>
		    <td><div align="right">Inspecci&oacute;n Efectuada </div></td>
		    <td><div align="left">
		   <?php if ($rowInspe['inspeccionefectuada'] == 0) { ?>
				<input name="inspecEfec" id="inspecEfec" type="radio" value="1" onchange="habilitoFechaInsp(this.value)"/>Si 
		   		<br />
		    	<input name="inspecEfec" id="inspecEfec" type="radio" value="0" checked="checked" onchange="habilitoFechaInsp(this.value)"/>No
		  		<?php } else { ?>
				<input name="inspecEfec" id="inspecEfec" type="radio" value="1" checked="checked" onchange="habilitoFechaInsp(this.value)"/>Si 
		   		<br />
		    	<input name="inspecEfec" id="inspecEfec" type="radio" value="0" onchange="habilitoFechaInsp(this.value)"/>No
				<?php } ?>
				</div></td>
	      </tr>
		  <tr>
		    <td height="21"><div align="right">Fecha Inspecci&oacute;n </div></td>
		    <td><div align="left"><label><input name="fechaInspec" type="text" id="fechaInspec" size="12" value="<?php echo  invertirFecha($rowInspe['fechainspeccion']) ?>"/></label></div></td>
	      </tr>
		</table>

	<p><input type="submit" name="Submit" id="Submit" value="Guardar" /></p>
  </form>
	</p>
</div>
</body>
</html>