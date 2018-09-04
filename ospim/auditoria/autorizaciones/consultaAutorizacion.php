<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
$nrosolicitud=$_GET['nroSolicitud'];

$sqlLeeSolicitud="SELECT * FROM autorizaciones WHERE nrosolicitud = $nrosolicitud";
$resultLeeSolicitud=mysql_query($sqlLeeSolicitud,$db);
$rowLeeSolicitud=mysql_fetch_array($resultLeeSolicitud);

if($rowLeeSolicitud['codiparentesco']>0) {
	$sqlLeeParentesco = "SELECT * FROM parentesco where codparent = $rowLeeSolicitud[codiparentesco]";
	$resultLeeParentesco = mysql_query($sqlLeeParentesco,$db);
	$rowLeeParentesco = mysql_fetch_array($resultLeeParentesco);
}

$sqlLeeDeleg = "SELECT * FROM delegaciones WHERE codidelega = $rowLeeSolicitud[codidelega]";
$resultLeeDeleg = mysql_query($sqlLeeDeleg,$db);
$rowLeeDeleg = mysql_fetch_array($resultLeeDeleg);

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

//VEO SI ES DISCAPACITADO Y SACO EDAD
if ($rowLeeSolicitud['codiparentesco'] >=0) {
	if ($rowLeeSolicitud['codiparentesco']>0) {
		$sqlDisca = "SELECT f.nroafiliado, f.nroorden as nroorden, DATE_FORMAT(d.fechaalta,'%d/%m/%Y') as fechaalta, DATE_FORMAT(d.emisioncertificado,'%d/%m/%Y') as emisioncertificado, DATE_FORMAT(d.vencimientocertificado,'%d/%m/%Y') as vencimientocertificado
						FROM familiares f, discapacitados d WHERE f.cuil = ".$rowLeeSolicitud['cuil']. " and f.nroafiliado = d.nroafiliado and f.nroorden = d.nroorden";
		$sqlEdad = "SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(fechanacimiento)), '%Y')+0 as edad, fechanacimiento FROM familiares WHERE cuil = ".$rowLeeSolicitud['cuil']. " and nroafiliado = ".$rowLeeSolicitud['nroafiliado'];
	} else {
		$sqlDisca = "SELECT d.*, 0 as nroorden, DATE_FORMAT(d.fechaalta,'%d/%m/%Y') as fechaalta, DATE_FORMAT(d.emisioncertificado,'%d/%m/%Y') as emisioncertificado, DATE_FORMAT(d.vencimientocertificado,'%d/%m/%Y') as vencimientocertificado
						FROM discapacitados d WHERE d.nroafiliado = ".$rowLeeSolicitud['nroafiliado']." and d.nroorden = 0";
		$sqlEdad = "SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(fechanacimiento)), '%Y')+0 as edad, fechanacimiento FROM titulares WHERE nroafiliado = ".$rowLeeSolicitud['nroafiliado'];
	}
	$resDisca = mysql_query($sqlDisca,$db);
	$canDisca = mysql_num_rows($resDisca);

	$resEdad = mysql_query($sqlEdad,$db);
	$rowEdad = mysql_fetch_assoc($resEdad);
	$edad = $rowEdad['edad'];
	$naci = $rowEdad['fechanacimiento'];
} else {
	$edad = "-";
	$naci = "-";
	$canDisca = 0;
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
<style type="text/css">
<!--
.Estilo3 {
	font-family: Papyrus;
	font-weight: bold;
	font-size: 24px;
}
body {
	background-color: #CCCCCC;
}
.Estilo4 {
	color: #990000;
	font-weight: bold;
}
-->
</style>
<script language="javascript" type="text/javascript">

function verCertificado(dire){	
	window.open(dire,'Certificado de Discapacidad','width=800, height=500,resizable=yes');
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

</script>
</head>

<body>
<table width="1100">
  <tr>
    <td colspan="2" scope="row">
    	<div align="left"><p class="Estilo3">Consulta de Solicitud N&uacute;mero <?php echo $nrosolicitud ?></p></div>
    </td>
    <td width="550">
	    <div align="right">
	      <table style="width: 450; height: 60" border="2">
	        <tr>
	          <td width="143" height="25"><div align="center"><strong>Fecha Solicitud</strong> </div></td>
	          <td width="289"><div align="center"><?php echo invertirFecha($rowLeeSolicitud['fechasolicitud']);?></div></td>
	        </tr>
	        <tr>
	          <td width="143" height="25"><div align="center"><strong>Delegaci&oacute;n</strong></div></td>
	          <td width="289"><div align="center"><?php echo "".$rowLeeSolicitud['codidelega']." - ".$rowLeeDeleg['nombre'];?></div></td>
	        </tr>
	      </table>
	    </div>
    </td>
  </tr>
</table>

<table width="100%">
  <tr>
    <td width="50%" valign="top">
    	<h3 align="left" class="Estilo4">Informaci&oacute;n del Beneficiario</h3>
    	<p><strong>N&uacute;mero de Afiliado:</strong> <?php if($rowLeeSolicitud['nroafiliado']!=0) echo $rowLeeSolicitud['nroafiliado']?></p>
        <p><strong>Clasificacion del Titular: </strong> <?php echo $tipoTitular ?></p>
        <p><strong>Apellido y Nombre: </strong><?php echo $rowLeeSolicitud['apellidoynombre']?></p>
        <p><strong>Comentario: </strong><?php echo $rowLeeSolicitud['comentario']?></p>
        <p><strong>Tipo:</strong>
		<?php	if($rowLeeSolicitud['codiparentesco']>=0) {
					if($rowLeeSolicitud['codiparentesco']==0) {
						echo "Titular";
					} else {
						echo "Familiar ".$rowLeeParentesco['descrip'];
					}
				} else {
					echo "No Empadronado";
				} ?>
			</p>
			<p>
				<b>Discapacitado:</b>
		<?php	if ($canDisca == 1) {
					$rowDisca = mysql_fetch_assoc($resDisca); 
					$nroorden = $rowDisca['nroorden']; 
					echo "SI (FA: ".$rowDisca['fechaalta']." - FE: ".$rowDisca['emisioncertificado']." - FV: ".$rowDisca['vencimientocertificado'].")"; ?>
					<input name="ver" type="button" id="ver" value="Ver Certificado" onclick="verCertificado('../sur/discapacitados/abm/verCertificado.php?nroafiliado=<?php echo $rowDisca['nroafiliado'] ?>&nroorden=<?php echo $nroorden ?>')"/>
		<?php 	} else { 
					echo "NO"; 
				} ?>
			</p>
        <p><strong>Fecha Nacimiento:</strong> <?php if ($naci != '-') { echo invertirFecha($naci); } else { echo $naci; } ?><strong> | Edad:</strong> <?php echo $edad ?></p>
        <p><strong>C.U.I.L.:</strong> <?php echo $rowLeeSolicitud['cuil'] ?></p>
        <p><strong>Telefono:</strong> <?php echo $rowLeeSolicitud['telefonoafiliado'] ?> </p>
        <p><strong>Celular:</strong> <?php echo $rowLeeSolicitud['movilafiliado'] ?></p>
        <p><strong>Email:</strong> <?php echo $rowLeeSolicitud['emailafiliado'] ?></p>
		
		<h3 align="left" class="Estilo4">Documentaci&oacute;n de la Solicitud</h3>
		<p><strong>Tipo:</strong> <?php if($rowLeeSolicitud['practica']==1) echo "Practica"; else { if($rowLeeSolicitud['material']==1) echo "Material - ".$rowLeeMaterial['descripcion']; else { if($rowLeeSolicitud['medicamento']==1) echo "Medicamento";}} ?></p>
      	<p><strong>Pedido Medico:</strong> <?php if($rowLeeSolicitud['pedidomedico']!=NULL) {?> <input type="button" name="pedidomedico" value="Ver" onclick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,1)" /><?php }?></p>
      	<p><strong>Historia Cl&iacute;nica:</strong> <?php if($rowLeeSolicitud['resumenhc']!=NULL) {?>  <input type="button" name="historiaclinica" value="Ver" onclick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,2)" /><?php }?></p>
      	<p><strong>Estudios:</strong> <?php if($rowLeeSolicitud['avalsolicitud']!=NULL) {?><input type="button" name="estudios" value="Ver" onclick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,3)" /><?php }?></p>
      	<p><strong>Presupuestos:</strong></p>
      	<p><?php if($rowLeeSolicitud['presupuesto1']!=NULL) {?><input type="button" name="presupuesto1" value="Ver" onclick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,4)" /><?php if($rowLeeSolicitud['aprobado1']!=0) { print(" ===> Presupuesto Aprobado"); };} ?></p>
      	<p><?php if($rowLeeSolicitud['presupuesto2']!=NULL) {?><input type="button" name="presupuesto2" value="Ver" onclick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,5)" /><?php if($rowLeeSolicitud['aprobado2']!=0) { print(" ===> Presupuesto Aprobado"); };} ?></p>
      	<p><?php if($rowLeeSolicitud['presupuesto3']!=NULL) {?><input type="button" name="presupuesto3" value="Ver" onclick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,6)" /><?php if($rowLeeSolicitud['aprobado3']!=0) { print(" ===> Presupuesto Aprobado"); };} ?></p>
      	<p><?php if($rowLeeSolicitud['presupuesto4']!=NULL) {?><input type="button" name="presupuesto4" value="Ver" onclick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,7)" /><?php if($rowLeeSolicitud['aprobado4']!=0) { print(" ===> Presupuesto Aprobado"); };} ?></p>
      	<p><?php if($rowLeeSolicitud['presupuesto5']!=NULL) {?><input type="button" name="presupuesto5" value="Ver" onclick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,8)" /><?php if($rowLeeSolicitud['aprobado5']!=0) { print(" ===> Presupuesto Aprobado"); };} ?></p>
    </td>
    
    <td valign="top">
    	<h3 align="left" class="Estilo4">Resultado de la Verificaci&oacute;n</h3>
    	<p><strong>Consulta SSS:</strong> <?php if($rowLeeSolicitud['consultasssverificacion']!=NULL) {?><input type="button" name="consultasss" value="Ver" onclick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,9)"/><?php }?></p>
		<p><strong>Verificaci&oacute;n:</strong> <?php if($rowLeeSolicitud['statusverificacion']==1) echo "Aprobada el ".invertirFecha($rowLeeSolicitud['fechaverificacion']); else echo "Rechazada el ".invertirFecha($rowLeeSolicitud['fechaverificacion']);?></p>
   	  	<p><?php echo "".$rowLeeSolicitud['rechazoverificacion'];?></p>
   		
   		<h3 align="left" class="Estilo4">Resultado de la Autorizaci&oacute;n</h3>
   		<p><strong>Autorizaci&oacute;n:</strong> <?php if($rowLeeSolicitud['statusautorizacion']==1) echo "Aprobada el ".invertirFecha($rowLeeSolicitud['fechaautorizacion']); else { if($rowLeeSolicitud['statusautorizacion']==2) echo "Rechazada el ".invertirFecha($rowLeeSolicitud['fechaautorizacion']);}?></p>
   	  	<p><strong>Observacion / Motivo de Rechazo:</strong><?php echo " ".$rowLeeSolicitud['rechazoautorizacion'];?></p>
      	<p><strong>Expediente SUR:</strong><?php if($rowLeeSolicitud['clasificacionape']==1) { echo " SI"; } else { echo " NO";} ?></p>
      	<p><strong>Comunica al Prestador ?:</strong> <?php if($rowLeeSolicitud['emailprestador']!=NULL) { echo " SI - Email: ".$rowLeeSolicitud['emailprestador']; } else { echo "NO";} ?> </p>
      	<p><strong>Clasificacion Patologia:</strong> <?php echo $patologia;?></p>
      	<p><strong>Monto Autorizado:</strong> <?php echo $rowLeeSolicitud['montoautorizacion'];?></p>
      <?php if($rowLeeSolicitud['statusautorizacion'] == 1) { ?> 
      		<p><strong>Documento Autorizacion:</strong> <?php if($rowLeeDocumento['documentofinal']!=NULL) {?><input type="button" name="docuauto" value="Ver" onclick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,10)" /><?php }?></p>
   	  <?php } ?>
   		
   		<h3 align="left" class="Estilo4">Reenvio de Correos</h3>
  	  <?php if ($canMailsEnviados > 0) { 
   				while ($rowSelectMails = mysql_fetch_assoc($resMailsEnviados)) {
   					echo "<p><b>".$rowSelectMails['address']."</b> - Enviado el ".$rowSelectMails['fechaenvio']." ";?>
   					<input type="button" name="reenvio" id="reenvio" value="Reenviar" onclick="javascript:reenviarMail(<?php echo $nrosolicitud?>,<?php echo $rowSelectMails['id']?>, this, '<?php echo $rowSelectMails['address']?>')" />
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
</body>
</html>