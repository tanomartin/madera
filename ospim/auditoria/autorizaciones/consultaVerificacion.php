<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
$nrosolicitud=$_GET['nroSolicitud'];
setcookie($nrosolicitud, $_SESSION['usuario'], time() + (86400 * 7));
$sqlLeeSolicitud = "SELECT a.*, doc.*, d.nombre as delegacion, clasificamaterial.descripcion as tipomaterial
					FROM delegaciones d, autorizacionesdocoriginales doc, autorizaciones a
					LEFT JOIN clasificamaterial ON a.tipomaterial = clasificamaterial.codigo
					WHERE a.nrosolicitud = $nrosolicitud and 
						  a.nrosolicitud = doc.nrosolicitud and
						  a.codidelega = d.codidelega";
$resultLeeSolicitud = mysql_query($sqlLeeSolicitud,$db);
$rowLeeSolicitud = mysql_fetch_array($resultLeeSolicitud);

$nombre = $rowLeeSolicitud['apellidoynombre'];
$nroafiliado = "-"; 
if ($rowLeeSolicitud['nroafiliado'] != 0) { 
	$nroafiliado = $rowLeeSolicitud['nroafiliado']; 
}
$tipoAfiliado = "NO EMPADRONADO";
$tipoTitular = "-";
$nroorden = 0;
$naci = "-";
$edad = "-";

if ($rowLeeSolicitud['codiparentesco'] <= 0) {
	$sqlTipoTitular = "SELECT nroafiliado, apellidoynombre, descrip, cuil, DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(fechanacimiento)), '%Y')+0 as edad, fechanacimiento 
						FROM titulares t, tipotitular p 
						WHERE t.cuil = ".$rowLeeSolicitud['cuil']." and t.situaciontitularidad = p.codtiptit";
	$resTipoTitular = mysql_query($sqlTipoTitular,$db);
	$canTipoTitular = mysql_num_rows($resTipoTitular);
	if ($canTipoTitular > 0) {
		$tipoAfiliado = "TITULAR";
		$rowTipoTitular = mysql_fetch_assoc($resTipoTitular);
		$tipoTitular = $rowTipoTitular['descrip'];
		$edad = $rowTipoTitular['edad'];
		$naci = $rowTipoTitular['fechanacimiento'];
		$nombre = $rowTipoTitular['apellidoynombre'];
		$nroafiliado = $rowTipoTitular['nroafiliado'];
	} else {
		$sqlTipoTitularBaja = "SELECT nroafiliado, apellidoynombre, descrip, cuil, DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(fechanacimiento)), '%Y')+0 as edad, fechanacimiento
								FROM titularesdebaja t, tipotitular p
								WHERE t.cuil = ".$rowLeeSolicitud['cuil']." and t.situaciontitularidad = p.codtiptit";
		$resTipoTitularBaja = mysql_query($sqlTipoTitularBaja,$db);
		$canTipoTitularBaja = mysql_num_rows($resTipoTitularBaja);
		if ($canTipoTitularBaja > 0) {
			$tipoAfiliado = "TITULAR DE BAJA";
			$rowTipoTitularBaja = mysql_fetch_assoc($resTipoTitularBaja);
			$tipoTitular = $rowTipoTitularBaja['descrip'];
			$edad = $rowTipoTitularBaja['edad'];
			$naci = $rowTipoTitularBaja['fechanacimiento'];
			$nombre = $rowTipoTitularBaja['apellidoynombre'];
			$nroafiliado = $rowTipoTitularBaja['nroafiliado'];
		}
	}
}

if ($tipoAfiliado == "NO EMPADRONADO" && $rowLeeSolicitud['codiparentesco'] != 0) {
	$sqlFamiliar = "SELECT nroafiliado, apellidoynombre, nroorden, cuil, fechanacimiento, parentesco.descrip as paretensco,
						DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(fechanacimiento)), '%Y')+0 as edad 
					 	FROM familiares 
						LEFT JOIN parentesco ON familiares.tipoparentesco = parentesco.codparent
						WHERE cuil = ".$rowLeeSolicitud['cuil'];
	$resFamiliar = mysql_query($sqlFamiliar,$db);
	$canFamiliar = mysql_num_rows($resFamiliar);
	if ($canFamiliar > 0) {
		$rowFamiliar = mysql_fetch_assoc($resFamiliar);
		$tipoAfiliado = "FAMILIAR - ".$rowFamiliar['paretensco'];
		$edad = $rowFamiliar['edad'];
		$naci = $rowFamiliar['fechanacimiento'];
		$nroorden = $rowFamiliar['nroorden'];
		$nombre = $rowFamiliar['apellidoynombre'];
		$nroafiliado = $rowFamiliar['nroafiliado'];
	} else {
		$sqlFamiliarBaja = "SELECT nroafiliado, apellidoynombre, nroorden, cuil, fechanacimiento, parentesco.descrip as paretensco,
								DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(fechanacimiento)), '%Y')+0 as edad
							FROM familiaresdebaja 
							LEFT JOIN parentesco ON familiaresdebaja.tipoparentesco = parentesco.codparent
							WHERE cuil = ".$rowLeeSolicitud['cuil'];
		$resFamiliarBaja = mysql_query($sqlFamiliarBaja,$db);
		$canFamiliarBaja = mysql_num_rows($resFamiliarBaja);
		if ($canFamiliarBaja > 0) {
			$rowFamiliarBaja = mysql_fetch_assoc($resFamiliarBaja);
			$tipoAfiliado = "FAMILIAR DE BAJA - ".$rowFamiliarBaja['paretensco'];
			$edad = $rowFamiliarBaja['edad'];
			$naci = $rowFamiliarBaja['fechanacimiento'];
			$nroorden = $rowFamiliarBaja['nroorden'];
			$nombre = $rowFamiliarBaja['apellidoynombre'];
			$nroafiliado = $rowFamiliarBaja['nroafiliado'];
		}
	}
}

$canDisca = 0;
$canHIV = 0;
$canOnco = 0;
$canPMI = 0;
$canDiabetes = 0;
if ($tipoAfiliado != "NO EMPADRONADO") {
	//VEO SI ES DISCA
	$sqlDisca = "SELECT DATE_FORMAT(fechaalta,'%d/%m/%Y') as fechaalta, nroorden, nroafiliado, 
					DATE_FORMAT(emisioncertificado,'%d/%m/%Y') as emisioncertificado,
					DATE_FORMAT(vencimientocertificado,'%d/%m/%Y') as vencimientocertificado
					FROM discapacitados
			WHERE cuil = ".$rowLeeSolicitud['cuil'];
	$resDisca = mysql_query($sqlDisca,$db);
	$canDisca = mysql_num_rows($resDisca);

	//VEO SI ES HIV
	$sqlHIV = "SELECT *
				FROM hivbeneficiarios
				WHERE nroafiliado = ".$rowLeeSolicitud['nroafiliado']." and nroorden = $nroorden";
	$resHIV = mysql_query($sqlHIV,$db);
	$canHIV = mysql_num_rows($resHIV);

	//VEO SI ES ONCO
	$sqlOnco = "SELECT *
				FROM oncologiabeneficiarios o
				WHERE nroafiliado = ".$rowLeeSolicitud['nroafiliado']." and nroorden = $nroorden";
	$resOnco = mysql_query($sqlOnco,$db);
	$canOnco = mysql_num_rows($resOnco);

	//VEO SI ES ESTA EN PMI
	$fechaLimite = date('Y-m-d',strtotime('-1 month',strtotime (date('Y-m-d'))));
	$sqlPMI = "SELECT * FROM pmibeneficiarios
				WHERE nroafiliado = ".$rowLeeSolicitud['nroafiliado']." and
				nroorden = $nroorden and
				(fechanacimiento != '0000-00-00' and fechanacimiento >= '$fechaLimite'
				or fechanacimiento = '0000-00-00' and fpp >= '$fechaLimite')";
	$resPMI = mysql_query($sqlPMI,$db);
	$canPMI = mysql_num_rows($resPMI);

	//VEO SI ES DIABETES
	$sqlDiabetes = "SELECT d.nroafiliado, d.nroorden, fechaficha, DATE_FORMAT(fechaficha, '%d/%m/%Y') as fechafichaform, tipodiabetes 
						FROM diabetesbeneficiarios d
						LEFT JOIN diabetesdiagnosticos ON diabetesdiagnosticos.nroafiliado = d.nroafiliado AND 
													  	  diabetesdiagnosticos.nroorden = d.nroorden
						WHERE d.nroafiliado = ".$rowLeeSolicitud['nroafiliado']." AND d.nroorden = $nroorden
						ORDER BY fechaficha DESC LIMIT 1";
	$resDiabetes = mysql_query($sqlDiabetes,$db);
	$canDiabetes = mysql_num_rows($resDiabetes);
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Detalle Solicitud</title>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

function verCertificado(dire){	
	window.open(dire,'Certificado de Discapacidad','width=800, height=500,resizable=yes');
}

function muestraArchivo(solicitud, archivo) {
	param = "nroSolicitud=" + solicitud;
	param += "&archivo=" + archivo;
	opciones = "top=50,left=50,width=1205,height=800,toolbar=no,menubar=no,status=no,dependent=yes,hotkeys=no,scrollbars=no,resizable=no";
	window.open ("mostrarArchivo.php?" + param, "", opciones);
}

function muestraHistoria(solicitud,cuil,nombre){	
	var dire = "historiaClinica.php?nrosol="+solicitud+"&cuil="+cuil+"&nombre="+nombre;
	window.open(dire,'Historia Clinica Autorizaciones','width=800, height=500,resizable=yes');
}

function validar(formulario) {
	if (formulario.rechazada.checked == true) {
		if(document.getElementById("motivoRechazo").value == "") {
			alert("Debe especificar un Motivo de Rechazo de la Autorizacion");
			document.getElementById("motivoRechazo").focus();
			return false;
		}
	}

	if (formulario.reverificar.checked == true) {
		if(document.getElementById("motivoRechazo").value == "") {
			alert("Debe especificar el Motivo para Solicitar Reverificacion");
			document.getElementById("motivoRechazo").focus();
			return false;		
		}
	}
	
	$.blockUI({ message: "<h1>Guardando Autorizacion. Aguarde por favor...</h1>" });
	return true;
}

</script>
</head>
<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = 'listarSolicitudes.php'"/></p>
	<form id="consultaVerificacion" name="consultaVerificacion" method="post" action="guardaAutorizacionReverifica.php" onsubmit="return validar(this)" enctype="multipart/form-data" >
		<input id="solicitud" name="solicitud" value="<?php echo $nrosolicitud ?>" type="text" readonly="readonly"  style="display: none"/>	
		<input id="delegacion" name="delegacion" value="<?php echo $rowLeeSolicitud['codidelega']."-".$rowLeeSolicitud['delegacion']?>" type="text" readonly="readonly"  style="display: none"/>	
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

		<table width="100%" style="text-align: center">
  			<tr>
   				<td width="40%" valign="top">
	    			<p style="color: maroon;"><b>Información del Beneficiario</b></p>
	    			<p><b>Nº Afiliado:</b> <?php echo $nroafiliado ?></p>
	        		<p><b>Clasificacion del Titular: </b> <?php echo $tipoTitular ?></p>
	        		<p><b>Apellido y Nombre: </b><?php echo $nombre ?></p>
	       		 	<p><b>Comentario: </b><?php echo $rowLeeSolicitud['comentario']?></p>
	        		<?php $color = ""; if ($tipoAfiliado == "NO EMPADRONADO") { $color = 'color="red"'; } ?>
	        		<p><b>Tipo: </b><font <?php echo $color ?>><?php echo $tipoAfiliado; ?></font></p>
	        		<p><b>Fecha Nacimiento:</b> <?php if ($naci != '-') { echo invertirFecha($naci); } else { echo $naci; } ?><strong> | Edad:</strong> <?php echo $edad ?></p>
	        		<p><b>C.U.I.L.:</b> <?php echo $rowLeeSolicitud['cuil'] ?></p>
	        		<p><b>Telefono:</b> <?php echo $rowLeeSolicitud['telefonoafiliado'] ?></p> 
	        		<p><b>Celular:</b> <?php echo $rowLeeSolicitud['movilafiliado'] ?></p>
	        		<p><b>Email:</b> <?php echo $rowLeeSolicitud['emailafiliado'] ?></p>
	        		<p style="color: maroon;"><b>Informacion Medica</b></p>
			  <?php	if ($canDisca == 1) {
						$rowDisca = mysql_fetch_assoc($resDisca);  ?>
						<p><b>Disca.: SI </b>(FA: <?php echo $rowDisca['fechaalta'] ?> - FE: <?php echo $rowDisca['emisioncertificado'] ?> - FV: <?php echo $rowDisca['vencimientocertificado'] ?> ) 
						<input name="ver" type="button" id="ver" value="Ver Cert." onclick="verCertificado('../sur/discapacitados/abm/verCertificado.php?nroafiliado=<?php echo $rowDisca['nroafiliado'] ?>&nroorden=<?php echo $rowDisca['nroorden'] ?>')"/></p>
			<?php 	} ?>
			<?php	if ($canHIV == 1) { ?>
						<p><b>H.I.V.:</b> SI </p>
			<?php 	} ?>
			<?php	if ($canOnco == 1) { ?>
						<p><b>Oncológico:</b> SI </p>
			<?php 	} 
					if ($canDiabetes == 1) { 
						$rowDiabetes = mysql_fetch_assoc($resDiabetes); ?>
						<p><b>Diabético:</b> SI 
					 	(<?php if ($rowDiabetes['fechaficha'] != NULL) { 
					 				$tipoDiabetes = $rowDiabetes['tipodiabetes'];
					 				if ($rowDiabetes['tipodiabetes'] == 3) {
					 					$tipoDiabetes = "Gestacional";
					 				}
					 				if ($rowDiabetes['tipodiabetes'] == 4) {
					 					$tipoDiabetes = "Otro";
					 				}
									echo "Tipo.: ".$tipoDiabetes." - F.F.: ".$rowDiabetes['fechafichaform'];
						 	   } else { 
									echo "SIN DIAG."; 
						  	   } ?>)
						</p>
			<?php 	} 
					if ($canPMI == 1) {
						$rowPMI = mysql_fetch_assoc($resPMI); ?>
						<p><b>P.M.I.:</b> SI (FPP: <?php echo $rowPMI['fpp'] ?> - FP: <?php if ($rowPMI['fechanacimiento'] != "00/00/0000") { echo $rowPMI['fechanacimiento']; } else { echo "Sin Dato"; } ?>) </p>
			<?php 	} ?>
			  <?php if ($canDisca == 0 && $canHIV == 0 && $canOnco == 0 && $canPMI == 0 && $canDiabetes == 0) { ?>
			    		<p>Sin Información para mostrar</p>
			 <?php  }?>
	        		<p style="color: maroon;"><b>Historia Clinica Autorizaciones</b></p>
					<p><input type="button" value="Ver Historia" name="historia" id="historia" onclick="javascript:muestraHistoria(<?php echo  $rowLeeSolicitud['nrosolicitud'] ?>,<?php echo  $rowLeeSolicitud['cuil'] ?>,'<?php echo  $rowLeeSolicitud['apellidoynombre'] ?>')" /></p>
	       		</td>
	       		<td width="20%" valign="top">
	    			<p style="color: maroon;"><b>Documentación de la Solicitud</b></p>
	    			<p><b>Tipo:</b> <?php if($rowLeeSolicitud['practica']==1) echo "Practica"; else { if($rowLeeSolicitud['material']==1) echo "Material - ".$rowLeeSolicitud['tipomaterial']; else { if($rowLeeSolicitud['medicamento']==1) echo "Medicamento";}} ?></p>
	     			<p><b>Pedido Medico:</b> <?php if($rowLeeSolicitud['pedidomedico']!=NULL) {?><input type="button" name="pedidomedico" value="Ver" onclick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,1)" /><?php }?></p>
	      			<p><b>Historia Clínica:</b> <?php if($rowLeeSolicitud['resumenhc']!=NULL) {?><input type="button" name="historiaclinica" value="Ver" onclick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,2)" /><?php }?></p>
	      			<p><b>Estudios:</b> <?php if($rowLeeSolicitud['avalsolicitud']!=NULL) {?><input type="button" name="estudios" value="Ver" onclick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,3)" /><?php }?></p>
	      			<p><b>Presupuestos:</b></p>
			      	<p><?php if($rowLeeSolicitud['presupuesto1']!=NULL) {?><input type="button" name="presupuesto1" value="Ver" onclick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,4)" /><?php }?></p>
			      	<p><?php if($rowLeeSolicitud['presupuesto2']!=NULL) {?><input type="button" name="presupuesto2" value="Ver" onclick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,5)" /><?php }?></p>
			      	<p><?php if($rowLeeSolicitud['presupuesto3']!=NULL) {?><input type="button" name="presupuesto3" value="Ver" onclick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,6)" /><?php }?></p>
			      	<p><?php if($rowLeeSolicitud['presupuesto4']!=NULL) {?><input type="button" name="presupuesto4" value="Ver" onclick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,7)" /><?php }?></p>
			      	<p><?php if($rowLeeSolicitud['presupuesto5']!=NULL) {?><input type="button" name="presupuesto5" value="Ver" onclick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,8)" /><?php }?></p>
    				
    				<p style="color: maroon;"><b>Resultado de la Verificación</b></p>
			    	<p><b>Consulta SSS:</b> <?php if($rowLeeSolicitud['consultasssverificacion']!=NULL) {?><input type="button" name="consultasss" value="Ver" onclick="javascript:muestraArchivo(<?php echo $rowLeeSolicitud['nrosolicitud'] ?>,9)" /><?php }?></p>
					<p><b>Verificación:</b> <?php if($rowLeeSolicitud['statusverificacion']==1) echo "Aprobada"; else echo "Rechazada";?></p>
			   	  	<p><b>Observacion:</b> <?php echo "".$rowLeeSolicitud['rechazoverificacion'];?></p> 	
    			</td>
			    <td width="40%" valign="top">
			   	  	<p style="color: maroon;"><b>Autorización</b></p>
			   	  	<p><input name="autori" id="reverificar" type="radio" value="1" checked="checked"/>Solicitar Reverificacion</p>
			      	<p><input name="autori" id="rechazada" type="radio" value="2"/>Rechazada</p>
			        <p><textarea name="motivoRechazo" cols="60" rows="7" id="motivoRechazo"></textarea></p>
			   	</td>
		  	</tr>
		</table>
		<p><input type="submit" name="guardar" id="guardar" value="Guardar"/></p>
	</form>
</div>
</body>
</html>