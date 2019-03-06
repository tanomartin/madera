<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
$nrosolicitud=$_GET['nroSolicitud'];

$sqlLeeSolicitud = "SELECT a.*, doc.*, d.nombre as delegacion, parentesco.descrip as paretensco, autorizacioneshistoria.detalle
					FROM delegaciones d, autorizacionesdocoriginales doc, autorizacionesatendidas a
					LEFT JOIN parentesco ON a.codiparentesco = parentesco.codparent
					LEFT JOIN autorizacioneshistoria ON a.nrosolicitud = autorizacioneshistoria.nrosolicitud
					WHERE a.nrosolicitud = $nrosolicitud and 
						  a.nrosolicitud = doc.nrosolicitud and
						  a.codidelega = d.codidelega";
$resultLeeSolicitud = mysql_query($sqlLeeSolicitud,$db);
$rowLeeSolicitud = mysql_fetch_array($resultLeeSolicitud);

$patologia = "Sin Clasificar";
if ($rowLeeSolicitud['patologia'] != null) {
	$sqlLeePatologia="SELECT * FROM patologiasautorizaciones WHERE codigo = $rowLeeSolicitud[patologia]";
	$resLeePatologia=mysql_query($sqlLeePatologia,$db);
	$rowLeePatologia=mysql_fetch_array($resLeePatologia);
	$patologia = $rowLeePatologia['descripcion'];
}

if($rowLeeSolicitud['material'] == 1) {
	$sqlLeeMaterial = "SELECT * FROM clasificamaterial WHERE codigo = $rowLeeSolicitud[tipomaterial]";
	$resultLeeMaterial = mysql_query($sqlLeeMaterial,$db);
	$rowLeeMaterial = mysql_fetch_array($resultLeeMaterial);
}

if($rowLeeSolicitud['statusautorizacion'] == 1) {
	$sqlLeeDocumento = "SELECT * FROM autorizaciondocumento WHERE nrosolicitud = $nrosolicitud";
	$resultLeeDocumento = mysql_query($sqlLeeDocumento,$db);
	$rowLeeDocumento = mysql_fetch_array($resultLeeDocumento);
}

$tipoTitular = "-";
if($rowLeeSolicitud['nroafiliado']!=0) {
	$sqlTipoTitular = "SELECT descrip FROM titulares t, tipotitular p WHERE t.nroafiliado = ".$rowLeeSolicitud['nroafiliado']." and t.situaciontitularidad = p.codtiptit";
	$resTipoTitular = mysql_query($sqlTipoTitular,$db);
	$canTipoTitular = mysql_num_rows($resTipoTitular);
	if ($canTipoTitular > 0) {
		$rowTipoTitular = mysql_fetch_assoc($resTipoTitular);
		$tipoTitular = $rowTipoTitular['descrip'];
	}
}

//SACO LA EDAD
if ($rowLeeSolicitud['codiparentesco'] >= 0) {
	if ($rowLeeSolicitud['codiparentesco'] > 0) {
		$sqlEdad = "SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(fechanacimiento)), '%Y')+0 as edad, fechanacimiento FROM familiares WHERE cuil = ".$rowLeeSolicitud['cuil']. " and nroafiliado = ".$rowLeeSolicitud['nroafiliado'];
	} else {
		$sqlEdad = "SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(fechanacimiento)), '%Y')+0 as edad, fechanacimiento FROM titulares WHERE nroafiliado = ".$rowLeeSolicitud['nroafiliado'];
	}
	$resEdad = mysql_query($sqlEdad,$db);
	$rowEdad = mysql_fetch_assoc($resEdad);
	$edad = $rowEdad['edad'];
	$naci = $rowEdad['fechanacimiento'];
} else {
	$edad = "-";
	$naci = "-";
}

//VEO SI ES DISCAPACITADO
if ($rowLeeSolicitud['codiparentesco'] >= 0) {
	if ($rowLeeSolicitud['codiparentesco'] > 0) {
		$sqlDisca = "SELECT f.nroafiliado, f.nroorden as nroorden, DATE_FORMAT(d.fechaalta,'%d/%m/%Y') as fechaalta, DATE_FORMAT(d.emisioncertificado,'%d/%m/%Y') as emisioncertificado, DATE_FORMAT(d.vencimientocertificado,'%d/%m/%Y') as vencimientocertificado
						FROM familiares f, discapacitados d WHERE f.cuil = ".$rowLeeSolicitud['cuil']. " and f.nroafiliado = d.nroafiliado and f.nroorden = d.nroorden";		
	} else {
		$sqlDisca = "SELECT d.*, 0 as nroorden, DATE_FORMAT(d.fechaalta,'%d/%m/%Y') as fechaalta, DATE_FORMAT(d.emisioncertificado,'%d/%m/%Y') as emisioncertificado, DATE_FORMAT(d.vencimientocertificado,'%d/%m/%Y') as vencimientocertificado
						FROM discapacitados d WHERE d.nroafiliado = ".$rowLeeSolicitud['nroafiliado']." and d.nroorden = 0";

	}
	$resDisca = mysql_query($sqlDisca,$db);
	$canDisca = mysql_num_rows($resDisca);
} else {
	$canDisca = 0;
}

//VEO SI ES HIV 
if ($rowLeeSolicitud['codiparentesco'] >= 0) {
	if ($rowLeeSolicitud['codiparentesco'] > 0) {
		$sqlHIV = "SELECT h.* FROM familiares f, hivbeneficiarios h WHERE f.cuil = ".$rowLeeSolicitud['cuil']. " and f.nroafiliado = h.nroafiliado and f.nroorden = h.nroorden";		
	} else {
		$sqlHIV = "SELECT h.* FROM hivbeneficiarios h WHERE h.nroafiliado = ".$rowLeeSolicitud['nroafiliado']." and h.nroorden = 0";
	}
	$resHIV = mysql_query($sqlHIV,$db);
	$canHIV = mysql_num_rows($resHIV);
} else {
	$canHIV = 0;
}

//VEO SI ES ONCO
if ($rowLeeSolicitud['codiparentesco'] >= 0) {
	if ($rowLeeSolicitud['codiparentesco'] > 0) {
		$sqlOnco = "SELECT o.* FROM familiares f, oncologiabeneficiarios o WHERE f.cuil = ".$rowLeeSolicitud['cuil']. " and f.nroafiliado = o.nroafiliado and f.nroorden = o.nroorden";		
	} else {
		$sqlOnco = "SELECT o.* FROM oncologiabeneficiarios o WHERE o.nroafiliado = ".$rowLeeSolicitud['nroafiliado']." and o.nroorden = 0";
	}
	$resOnco = mysql_query($sqlOnco,$db);
	$canOnco = mysql_num_rows($resOnco);
} else {
	$canOnco = 0;
}

//VEO SI ES ESTA EN PMI
if ($rowLeeSolicitud['codiparentesco'] >= 0) {
	$fechaLimite = date('Y-m-d',strtotime('-1 month',strtotime (date('Y-m-d'))));
	if ($rowLeeSolicitud['codiparentesco'] > 0) {
		$sqlPMI = "SELECT p.* FROM familiares f, pmibeneficiarios p 
					WHERE f.cuil = ".$rowLeeSolicitud['cuil']. " and 
						  f.nroafiliado = p.nroafiliado and 
						  f.nroorden = p.nroorden and 
						  (p.fechanacimiento != '0000-00-00' and p.fechanacimiento >= '$fechaLimite'
						  or p.fechanacimiento = '0000-00-00' and p.fpp >= '$fechaLimite')";	
	} else {
		$sqlPMI = "SELECT p.* FROM pmibeneficiarios p 
				 	WHERE p.nroafiliado = ".$rowLeeSolicitud['nroafiliado']." and
				 		  p.nroorden = 0 and
						  (p.fechanacimiento != '0000-00-00' and p.fechanacimiento >= '$fechaLimite'
						  or p.fechanacimiento = '0000-00-00' and p.fpp >= '$fechaLimite')";	
	}
	$resPMI = mysql_query($sqlPMI,$db);
	$canPMI = mysql_num_rows($resPMI);
} else {
	$canPMI = 0;
}

//VEO SI ES DIABETES
if ($rowLeeSolicitud['codiparentesco'] >= 0) {
	if ($rowLeeSolicitud['codiparentesco'] > 0) {
		$sqlDiabetes = "SELECT o.* FROM familiares f, diabetesbeneficiarios o WHERE f.cuil = ".$rowLeeSolicitud['cuil']. " and f.nroafiliado = o.nroafiliado and f.nroorden = o.nroorden";
	} else {
		$sqlDiabetes = "SELECT o.* FROM diabetesbeneficiarios o WHERE o.nroafiliado = ".$rowLeeSolicitud['nroafiliado']." and o.nroorden = 0";
	}
	$resDiabetes = mysql_query($sqlDiabetes,$db);
	$canDiabetes = mysql_num_rows($resDiabetes);
} else {
	$canDiabetes = 0;
}

//VEO SI HAY CORREOS
$canMailsNoEnviados = 0;
$canMailsEnviados = 0;
if($rowLeeSolicitud['statusautorizacion'] != 0) {
	$sqlMailsNoEnviados = "SELECT b.id, b.address FROM autorizacionesemail a, bandejasalida b WHERE a.nrosolicitud = $nrosolicitud and a.idemail = b.id";
	$resMailsNoEnviados = mysql_query($sqlMailsNoEnviados,$db);
	$canMailsNoEnviados = mysql_num_rows($resMailsNoEnviados);
	
	$sqlMailsEnviados = "SELECT b.id, b.address, DATE_FORMAT(b.fechaenvio, '%d/%m/%Y a las %H:%i:%S') as fechaenvio FROM autorizacionesemail a, bandejaenviados b WHERE a.nrosolicitud = $nrosolicitud and a.idemail = b.id";
	$resMailsEnviados = mysql_query($sqlMailsEnviados,$db);
	$canMailsEnviados = mysql_num_rows($resMailsEnviados);
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Detalle Solicitud</title>
<script language="javascript" type="text/javascript">

function verCertificado(dire){	
	window.open(dire,'Certificado de Discapacidad','width=800, height=500,resizable=yes');
}

function muestraHistoria(solicitud,cuil,nombre){	
	var dire = "historiaClinica.php?nrosol="+solicitud+"&cuil="+cuil+"&nombre="+nombre;
	window.open(dire,'Historia Clinica Autorizaciones','width=800, height=500,resizable=yes');
}

function muestraArchivo(solicitud, archivo) {
	param = "nroSolicitud=" + solicitud;
	param += "&archivo=" + archivo;
	opciones = "top=50,left=50,width=1205,height=800,toolbar=no,menubar=no,status=no,dependent=yes,hotkeys=no,scrollbars=no,resizable=no"
	window.open ("mostrarArchivo.php?" + param, "", opciones);
}

function reenviarMail(solicitud, idmail, boton, mail) {
	var r = confirm("Desea reenviar el mail a la siguiente direccion "+mail);
	if (r == true) {
		boton.disabled = true;
		var redireccion = "reenvioMailAutorizacion.php?idmail="+idmail+"&nrosolicitud="+solicitud;
		location.href=redireccion;
	}
}

function varModifHC(buttonModif) {
	buttonModif.style.display = "none";
	document.getElementById("historiaTexto").style.display = "none";
	document.getElementById("guardar").style.display = "block";
	document.getElementById("historiaClinicaTextarea").style.display = "block";
}

function guardarModifHC(buttonGuardar, nrosolicitud) {
	buttonGuardar.disabled = true;
	var textoHC = document.getElementById("historiaClinicaTextarea").value;
	console.log(textoHC);
	var redireccion = "guardarModificacionHC.php?nrosolicitud="+nrosolicitud+"&texto="+textoHC;
	location.href=redireccion;
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<h3>Solicitud N� <?php echo $nrosolicitud ?></h3>
	<table style="width: 50%; text-align: center" border="1">
	    <tr>
	       <td><b>Fecha Solicitud</b></td>
	       <td><?php echo invertirFecha($rowLeeSolicitud['fechasolicitud']);?></td>
	    </tr>
	    <tr>
	       <td><b>Delegaci�n</b></td>
	       <td><?php echo "".$rowLeeSolicitud['codidelega']." - ".$rowLeeSolicitud['delegacion'];?></td>
	     </tr>
	</table>
	<table width="100%" style="text-align: center">
		<tr>
	   		<td width="40%" valign="top">
	    		<p style="color: maroon;"><b>Informaci�n del Beneficiario</b></p>
	    		<p><b>N� de Afiliado:</b> <?php if($rowLeeSolicitud['nroafiliado']!=0) echo $rowLeeSolicitud['nroafiliado']?></p>
	        	<p><b>Clasificacion del Titular: </b> <?php echo $tipoTitular ?></p>
	        	<p><b>Apellido y Nombre: </b><?php echo $rowLeeSolicitud['apellidoynombre']?></p>
	        	<p><b>Comentario: </b><?php echo $rowLeeSolicitud['comentario']?></p>
	        	<p><b>Tipo:</b>
				<?php	if($rowLeeSolicitud['codiparentesco']>=0) {
							if($rowLeeSolicitud['codiparentesco']==0) {
								echo "Titular";
							} else {
								echo "Familiar ".$rowLeeSolicitud['paretensco'];
							}
						} else {
							echo "No Empadronado";
						} ?>
				</p>
	        	<p><b>Fecha Nacimiento:</b> <?php if ($naci != '-') { echo invertirFecha($naci); } else { echo $naci; } ?><strong> | Edad:</strong> <?php echo $edad ?></p>
	        	<p><b>C.U.I.L.:</b> <?php echo $rowLeeSolicitud['cuil'] ?></p>
	        	<p><b>Telefono:</b> <?php echo $rowLeeSolicitud['telefonoafiliado'] ?> </p>
	        	<p><b>Celular:</b> <?php echo $rowLeeSolicitud['movilafiliado'] ?></p>
	        	<p><b>Email:</b> <?php echo $rowLeeSolicitud['emailafiliado'] ?></p>
	       		<p style="color: maroon;"><b>Informacion Medica</b></p>
		  <?php	if ($canDisca == 1) {
					$rowDisca = mysql_fetch_assoc($resDisca); 
					$nroorden = $rowDisca['nroorden']; ?>
					<p><b>Disca.: SI </b>(FA: <?php echo $rowDisca['fechaalta'] ?> - FE: <?php echo $rowDisca['emisioncertificado'] ?> - FV: <?php echo $rowDisca['vencimientocertificado'] ?> ) 
					<input name="ver" type="button" id="ver" value="Ver Certificado" onclick="verCertificado('../sur/discapacitados/abm/verCertificado.php?nroafiliado=<?php echo $rowDisca['nroafiliado'] ?>&nroorden=<?php echo $nroorden ?>')"/></p>
		<?php 	} 
				if ($canHIV == 1) { ?>
					<p><b>H.I.V.:</b> SI </p>
		<?php 	} 
				if ($canOnco == 1) { ?>
					<p><b>Oncol�gico:</b> SI </p>
		<?php 	}
				if ($canDiabetes == 1) { ?>
					<p><b>Diab�tico:</b> SI </p>
		<?php 	} 
				if ($canPMI == 1) {
					$rowPMI = mysql_fetch_assoc($resPMI); ?>
					<p><b>P.M.I.:</b> SI (FPP: <?php echo $rowPMI['fpp'] ?> - FP: <?php if ($rowPMI['fechanacimiento'] != "00/00/0000") { echo $rowPMI['fechanacimiento']; } else { echo "Sin Dato"; } ?>) </p>
		<?php 	}
			    if ($canDisca == 0 && $canHIV == 0 && $canOnco == 0 && $canPMI == 0 && $canDiabetes == 0) { ?>
		    		<p>Sin Informaci�n para mostrar</p>
		 <?php  }
		 		if (!isset($_GET['hc'])) { ?>	
	        		<p style="color: maroon;"><b>Historia Clinica Autorizaciones</b></p>
					<p><input type="button" value="Ver Historia" name="historia" id="historia" onclick="javascript:muestraHistoria(<?php echo  $rowLeeSolicitud['nrosolicitud'] ?>,<?php echo  $rowLeeSolicitud['cuil'] ?>,'<?php echo  $rowLeeSolicitud['apellidoynombre'] ?>')" /></p>
		  <?php } ?>
			</td>
			<td width="20%" valign="top">
				<p style="color: maroon;"><b>Documentaci�n de la Solicitud</b></p>
				<p><b>Tipo:</b> <?php if($rowLeeSolicitud['practica']==1) echo "Practica"; else { if($rowLeeSolicitud['material']==1) echo "Material - ".$rowLeeMaterial['descripcion']; else { if($rowLeeSolicitud['medicamento']==1) echo "Medicamento";}} ?></p>
	      		<p><b>Pedido Medico:</b> <?php if($rowLeeSolicitud['pedidomedico']!=NULL) {?> <input type="button" name="pedidomedico" value="Ver" onclick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,1)" /><?php }?></p>
	      		<p><b>Historia Cl�nica Doc:</b> <?php if($rowLeeSolicitud['resumenhc']!=NULL) {?>  <input type="button" name="historiaclinica" value="Ver" onclick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,2)" /><?php }?></p>
	      		<p><b>Estudios:</b> <?php if($rowLeeSolicitud['avalsolicitud']!=NULL) {?><input type="button" name="estudios" value="Ver" onclick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,3)" /><?php }?></p>
	      		<p><b>Presupuestos:</b></p>
	      		<p><?php if($rowLeeSolicitud['presupuesto1']!=NULL) { echo "1 - ";?><input type="button" name="presupuesto1" value="Ver" onclick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,4)" /><?php if($rowLeeSolicitud['aprobado1']!=0) { print(" (Aprobado)"); };} ?></p>
	      		<p><?php if($rowLeeSolicitud['presupuesto2']!=NULL) { echo "2 - ";?><input type="button" name="presupuesto2" value="Ver" onclick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,5)" /><?php if($rowLeeSolicitud['aprobado2']!=0) { print(" (Aprobado)"); };} ?></p>
	      		<p><?php if($rowLeeSolicitud['presupuesto3']!=NULL) { echo "3 - ";?><input type="button" name="presupuesto3" value="Ver" onclick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,6)" /><?php if($rowLeeSolicitud['aprobado3']!=0) { print(" (Aprobado)"); };} ?></p>
	      		<p><?php if($rowLeeSolicitud['presupuesto4']!=NULL) { echo "4 - ";?><input type="button" name="presupuesto4" value="Ver" onclick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,7)" /><?php if($rowLeeSolicitud['aprobado4']!=0) { print(" (Aprobado)"); };} ?></p>
	      		<p><?php if($rowLeeSolicitud['presupuesto5']!=NULL) { echo "5 - ";?><input type="button" name="presupuesto5" value="Ver" onclick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,8)" /><?php if($rowLeeSolicitud['aprobado5']!=0) { print(" (Aprobado)"); };} ?></p>
	    		
	    		<p style="color: maroon;"><b>Resultado de la Verificaci�n</b></p>
    			<p><b>Consulta SSS:</b> <?php if($rowLeeSolicitud['consultasssverificacion']!=NULL) {?><input type="button" name="consultasss" value="Ver" onclick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,9)"/><?php }?></p>
				<p><b>Verificaci�n:</b> <?php if($rowLeeSolicitud['statusverificacion']==1) echo "Aprobada el ".invertirFecha($rowLeeSolicitud['fechaverificacion']); else echo "Rechazada el ".invertirFecha($rowLeeSolicitud['fechaverificacion']);?></p>
   	  			<p><?php echo "".$rowLeeSolicitud['rechazoverificacion'];?></p>
	    	</td>
    		<td width="40%" valign="top">	
   				<p style="color: maroon;"><b>Resultado de la Autorizaci�n</b></p>
   				<p><b>Autorizaci�n:</b> <?php if($rowLeeSolicitud['statusautorizacion']==1) echo "Aprobada el ".invertirFecha($rowLeeSolicitud['fechaautorizacion']); else { if($rowLeeSolicitud['statusautorizacion']==2) echo "Rechazada el ".invertirFecha($rowLeeSolicitud['fechaautorizacion']);}?></p>
   	  			<p><b>Observacion / Motivo de Rechazo:</b><?php echo " ".$rowLeeSolicitud['rechazoautorizacion'];?></p>
   	  			<p id="historiaTexto"><b>Historia Clinica: </b><?php echo $rowLeeSolicitud['detalle'];?></p>
   	  			<p align="center">
   	  				<textarea style="display: none" name="historiaClinicaTextarea" cols="50" rows="5" id="historiaClinicaTextarea"><?php echo $rowLeeSolicitud['detalle'];?></textarea>
   	  				<button id="modificar" onclick="varModifHC(this)">Modificar</button>
   	  				<button style="display: none" id="guardar"  onclick="guardarModifHC(this, <?php echo $nrosolicitud?>)">Guardar</button>
   	  			</p>
      			<p><b>Expediente SUR:</b><?php if($rowLeeSolicitud['clasificacionape']==1) { echo " SI"; } else { echo " NO";} ?></p>
      			<p><b>Comunica al Prestador ?:</b> <?php if($rowLeeSolicitud['emailprestador']!=NULL) { echo " SI <br/> <b>Email:</b> ".$rowLeeSolicitud['emailprestador']; } else { echo "NO";} ?> </p>
      			<p><b>Clasificacion Patologia:</b> <?php echo $patologia;?></p>
      			<p><b>Monto Coseguro:</b> <?php echo $rowLeeSolicitud['montocoseguro'];?></p>
      			<p><b>Monto Autorizado:</b> <?php echo $rowLeeSolicitud['montoautorizacion'];?></p>
	      <?php if($rowLeeSolicitud['statusautorizacion'] == 1) { ?> 
	      			<p><b>Documento Autorizacion:</b> <?php if($rowLeeDocumento['documentofinal']!=NULL) {?><input type="button" name="docuauto" value="Ver" onclick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,10)" /><?php }?></p>
	   	  <?php } ?>
   		
   				<p style="color: maroon;"><b>Reenvio de Correos</b></p>
	  	  <?php if ($canMailsEnviados > 0) { 
	   				while ($rowSelectMails = mysql_fetch_assoc($resMailsEnviados)) {
	   					echo "<p><b>".$rowSelectMails['address']."</b> - Enviado el ".$rowSelectMails['fechaenvio']." ";?>
	   				<?php if (!isset($_GET['hc'])) { ?><input type="button" name="reenvio" id="reenvio" value="Reenviar" onclick="javascript:reenviarMail(<?php echo $nrosolicitud?>,<?php echo $rowSelectMails['id']?>, this, '<?php echo $rowSelectMails['address']?>')" /> <?php } ?>
	   	  <?php			echo "</p>"; 
	   				}
	   			} 
	   			if ($canMailsNoEnviados > 0) { 
	   				while ($rowSelectMails = mysql_fetch_assoc($resMailsNoEnviados)) {
	   					echo "<p><b>".$rowSelectMails['address']."</b> - En proceso de envio</p>";
	   				}
	   			} 
	   			if ($canMailsEnviados == 0 && $canMailsNoEnviados == 0) {
	   				echo "<b>No hay mails para enviar</b>";
	   			} ?>
    		</td>
  		</tr>
	</table>
</div>
</body>
</html>