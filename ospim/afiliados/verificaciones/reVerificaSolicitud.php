<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php"); 
$nrosolicitud = $_GET['nroSolicitud'];
$sqlLeeSolicitud = "SELECT a.*, d.nombre as delegacion, parentesco.descrip as paretensco, doc.consultasssverificacion
					FROM delegaciones d, autorizacionesdocoriginales doc, autorizaciones a
					LEFT JOIN parentesco ON a.codiparentesco = parentesco.codparent
					WHERE a.nrosolicitud = $nrosolicitud and 
						  a.nrosolicitud = doc.nrosolicitud and
						  a.codidelega = d.codidelega";				 
$resultLeeSolicitud = mysql_query($sqlLeeSolicitud,$db);
$rowLeeSolicitud = mysql_fetch_array($resultLeeSolicitud); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Detalle Solicitud</title>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

function muestraArchivo(solicitud, archivo) {
	param = "nroSolicitud=" + solicitud;
	param += "&archivo=" + archivo;
	opciones = "top=50,left=50,width=1205,height=800,toolbar=no,menubar=no,status=no,dependent=yes,hotkeys=no,scrollbars=no,resizable=no";
	window.open ("mostrarArchivo.php?" + param, "", opciones);
}

function mostrarMotivo(muestra) {
	if (muestra != 1) {
		document.forms.verificaSolicitud.motivoRechazo.value="";
		document.forms.verificaSolicitud.motivoRechazo.disabled=true;
	} else {
		document.forms.verificaSolicitud.motivoRechazo.disabled=false;
	}	
}

function validar(formulario) {
	if (formulario.rechazada.checked == true) {
		if(document.getElementById("motivoRechazo").value == "") {
			alert("Debe especificar un Motivo de Rechazo de la Solicitud");
			return false;
		}
	}
	$.blockUI({ message: "<h1>Guardando Verificacion. Aguarde por favor...</h1>" });
	return true;
}
</script>
</head>
<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="reset" name="volver" value="Volver" onclick="location.href = 'listarSolicitudes.php'"/></p>
	<form id="verificaSolicitud" name="verificaSolicitud" method="post" action="guardaReVerificacion.php" onsubmit="return validar(this)" enctype="multipart/form-data" >
		<input id="solicitud" name="solicitud" value="<?php echo $nrosolicitud ?>" type="text" style="display: none"/>
		<h3>Solicitud Nº <?php echo $nrosolicitud ?></h3>
		
		<table style="width: 50%; text-align: center" border="1">
	    	<tr>
	          <td><b>Fecha Solicitud</b></td>
	          <td><?php echo invertirFecha($rowLeeSolicitud['fechasolicitud']);?></td>
	        </tr>
	        <tr>
	          <td><b>Delegación</b></td>
	          <td><?php echo "".$rowLeeSolicitud['codidelega']." - ".$rowLeeSolicitud['delegacion'];?></td>
	        </tr>
	   	</table>
		<table width="90%" style="text-align: center">
  			<tr>
    			<td width="50%" valign="top">
    				<p style="color: maroon;"><b>Información del Beneficiario</b></p>
    				<p><b>Número de Afiliado:</b><?php if($rowLeeSolicitud['nroafiliado']!=0) echo $rowLeeSolicitud['nroafiliado']?></p>
      				<p><b>Apellido y Nombre: </b><?php echo $rowLeeSolicitud['apellidoynombre']?></p>
      				<p><b>C.U.I.L.:</b> <?php echo $rowLeeSolicitud['cuil'] ?></p>
      				<p><b>Tipo:</b>
				<?php	if($rowLeeSolicitud['codiparentesco']>=0) {
							echo "Familiar ".$rowLeeSolicitud['paretensco'];
						} else {
							echo "No Empadronado";
						} ?>
      				</p>
        			<p><b>Telefono:</b> <?php echo $rowLeeSolicitud['telefonoafiliado'] ?> </p>
        			<p><b>Celular:</b> <?php echo $rowLeeSolicitud['movilafiliado'] ?></p>
        			<p><b>Email:</b> <?php echo $rowLeeSolicitud['emailafiliado'] ?></p>
      			</td>
    			<td valign="top">
    				<p style="color: maroon;"><b>Resultado de la Verificación</b></p>
    				<p><b>Consulta SSS:</b>
				  <?php if($rowLeeSolicitud['consultasssverificacion']!=NULL) {?>
				      		<input type="button" name="consultasss" value="Ver" onclick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,9)" />
				  <?php }?>
    				</p>
      				<p><b>Verificación:</b>
          	   	  <?php if($rowLeeSolicitud['statusverificacion']==1) echo "Aprobada"; else echo "Rechazada";?>
     				</p>
      				<p><?php echo "".$rowLeeSolicitud['rechazoverificacion'];?></p>
      				<p><b>Motivo de Solicitud de Reverificación:</b></p>
      				<p><?php echo "".$rowLeeSolicitud['motivopidereverificacion'];?></p>
      			
      				<p style="color: maroon;"><b>Resultado de la Reverificación</b></p>
      				<p><b>Re-Verificación:</b></p>
      				<p>
      					<input name="veri" id="aprobada" type="radio" value="1" onchange="mostrarMotivo(0)" checked="checked"/>Aprobada
				      	<br />
				      	<input name="veri" id="rechazada" type="radio" value="2" onchange="mostrarMotivo(1)"/>Rechazada
      				</p>
      				<p>
          				<textarea name="motivoRechazo" cols="80" rows="5" id="motivoRechazo" disabled="disabled"></textarea>
    				</p>
      			</td>
  			</tr>
		</table>
		<p><input type="submit" name="guardar" id="guardar" value="Guardar"/></p>
	</form>
</div>
</body>
</html>