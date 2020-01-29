<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
$today = date("d/m/Y h:m:s");   
$estado = $_GET['estado'];
$id = $_GET['id'];

$sqlFactura = "SELECT f.*, p.nombre, p.cuit, p.personeria,
					  DATE_FORMAT(f.fechacomprobante,'%d/%m/%Y') as fechacomprobante,
					  DATE_FORMAT(f.fecharecepcion,'%d/%m/%Y') as fecharecepcion,
					  DATE_FORMAT(f.fechacorreo,'%d/%m/%Y') as fechacorreo,
					  DATE_FORMAT(f.fechavencimiento,'%d/%m/%Y') as fechavencimiento,
					  t.descripcion as tipocomprobante, c.descripcioncorta,
					  establecimientos.nombre as establecimiento
					FROM prestadores p, tipocomprobante t, codigoautorizacion c, facturas f 
					LEFT JOIN establecimientos ON establecimientos.codigo = f.idestablecimiento
					WHERE f.id = $id AND 
						  f.idPrestador = p.codigoprestador AND 
						  f.idTipocomprobante = t.id and f.idCodigoautorizacion = c.id
					ORDER BY f.id DESC";
$resFactura = mysql_query($sqlFactura,$db); 
$rowFactura = mysql_fetch_assoc($resFactura);
$tipopresta = $rowFactura['personeria'];

$sqlBeneficiarios = "SELECT f.*, IF(titulares.apellidoynombre is NULL,
										titularesdebaja.apellidoynombre,
										titulares.apellidoynombre) as nombretitu,
								 IF(familiares.apellidoynombre is NULL,
										familiaresdebaja.apellidoynombre,
										familiares.apellidoynombre) as nombrefami
						FROM facturasbeneficiarios f 
						LEFT JOIN titulares ON f.tipoafiliado = 0 and 
											   titulares.nroafiliado = f.nroafiliado 
						LEFT JOIN titularesdebaja ON f.tipoafiliado = 0 and 
													 titularesdebaja.nroafiliado = f.nroafiliado 
						LEFT JOIN familiares ON f.tipoafiliado != 0 and 
												familiares.nroafiliado = f.nroafiliado and 
												familiares.nroorden = f.nroorden
						LEFT JOIN familiaresdebaja ON f.tipoafiliado != 0 and
													  familiaresdebaja.nroafiliado = f.nroafiliado and 
													  familiaresdebaja.nroorden = f.nroorden  
						WHERE idFactura = $id";
$resBeneficiarios = mysql_query($sqlBeneficiarios,$db);
$numBeneficiarios = mysql_num_rows($resBeneficiarios);

$sqlCarencias = "SELECT * FROM facturascarenciasbeneficiarios f WHERE idFactura = $id";
$resCarencias = mysql_query($sqlCarencias,$db);
$numCarencias = mysql_num_rows($resCarencias);

$numEstadistica = 0;
$numAgrupaEfector = 0;
if ($numBeneficiarios > 0) {
	if ($tipopresta != 6) {
		$sqlPretaciones = "SELECT p.codigopractica, f.*, DATE_FORMAT(f.fechapractica,'%d/%m/%Y') as fechapractica, 
								  facturasintegracion.totalsolicitado,
								  facturasintegracion.dependencia,
								  facturasintegracion.id as inte, practicas.codigopractica as codigoescuela,
								  escuelas.nombre as nombreescuela, escuelas.cue ";
		if ($tipopresta == 3) {
			$sqlPretaciones .= ",profesionales.nombre as efector ";
		}
		if ($tipopresta == 4) {
			$sqlPretaciones .= ",establecimientos.nombre as efector ";
		}
		$sqlPretaciones .=	"FROM practicas p, facturasprestaciones f 
							    LEFT JOIN facturasintegracion ON facturasintegracion.idFacturaprestacion = f.id 
								LEFT JOIN practicas ON practicas.idpractica = facturasintegracion.tipoescuela 
								LEFT JOIN escuelas ON escuelas.id = facturasintegracion.idEscuela ";
		if ($tipopresta == 3) {
			$sqlPretaciones .= "LEFT JOIN profesionales ON profesionales.codigoprofesional = f.efectorpractica";
		}
		if ($tipopresta == 4) {
			$sqlPretaciones .= "LEFT JOIN establecimientos ON establecimientos.codigo = f.efectorpractica";
		}
		$sqlPretaciones .= " WHERE f.idFactura = $id and f.idpractica = p.idpractica";
	} else {
		$sqlPretaciones = "SELECT m.codigo as codigopractica, f.*, 
								  DATE_FORMAT(f.fechapractica,'%d/%m/%Y') as fechapractica,
								  establecimientos.nombre as efector 
						   FROM medicamentos m, facturasprestaciones f 
						   LEFT JOIN establecimientos ON establecimientos.codigo = f.efectorpractica
						   WHERE f.idFactura = $id and f.idpractica = m.codigo";
	}
	
	$resPretaciones = mysql_query($sqlPretaciones,$db);
	$numPretaciones = mysql_num_rows($resPretaciones);
	$arrayPresta = array();
	if ($numPretaciones > 0) {
		$whereIn = "(";
		while ($rowPretaciones = mysql_fetch_assoc($resPretaciones)) {
			$arrayPresta[$rowPretaciones['idFacturabeneficiario']][$rowPretaciones['id']] = $rowPretaciones;
			$whereIn .= "'".$rowPretaciones['id']."',";
		}
		$whereIn = substr($whereIn, 0, -1);
		$whereIn .= ")";
		
		$sqlEstadistica = "SELECT r.descripcion, sum(f.cantidadcomputo) as cantidadcomputo 
							FROM facturasestadisticas f, resol650configuracion r
							WHERE f.idFacturaprestacion in $whereIn and f.valorcomputo = r.valorcomputo 
							GROUP BY f.valorcomputo";
		$resEstadistica = mysql_query($sqlEstadistica,$db);
		$numEstadistica = mysql_num_rows($resEstadistica);
		
		$sqlAgrupaEfector = "";
		if ($tipopresta == 3) {
			$sqlAgrupaEfector = "SELECT sum(f.totalfacturado) as facturado,
										sum(f.totaldebito) as debito,
										sum(f.totalcredito) as credito,
										profesionales.nombre,
										profesionales.codigoprofesional as codigo
									FROM facturasprestaciones f
									LEFT JOIN profesionales ON profesionales.codigoprofesional = f.efectorpractica
									WHERE idFactura = $id
									GROUP by f.efectorpractica";
		}
		if ($tipopresta == 4 || $tipopresta == 6) {
			$sqlAgrupaEfector = "SELECT sum(f.totalfacturado) as facturado, 
										sum(f.totaldebito) as debito,
										sum(f.totalcredito) as credito,
										establecimientos.nombre,
										establecimientos.codigo
								FROM facturasprestaciones f
								LEFT JOIN establecimientos ON establecimientos.codigo = f.efectorpractica
								WHERE idFactura = $id
								GROUP by f.efectorpractica";
		}
		if ($sqlAgrupaEfector != "") {
			$resAgrupaEfector = mysql_query($sqlAgrupaEfector,$db);
			$numAgrupaEfector = mysql_num_rows($resAgrupaEfector);
		}
	} 	
} ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Consulta Factura Liquidaciones :.</title>
<link rel="stylesheet" href="/madera/lib/tablas.css"/>
<style type="text/css" media="print">
.nover {display:none}
</style>

<script>
	
function mostrarInfo(divid) {	
	if (divid != "debitos") {
		var divObject = document.getElementById(divid);
		if (divObject.style.display == "none") {
			divObject.style.display = "block"; 
		} else {
			divObject.style.display = "none"; 
		}
	} else {
		var divObject = document.getElementById("debitos");
		var divObjectCarencias = document.getElementById("carencias");
		if (divObject.style.display == "none") {
			divObject.style.display = "block"; 
			divObjectCarencias.style.display = "block"; 
		} else {
			divObject.style.display = "none";
			divObjectCarencias.style.display = "none";  
		}	
	}
}

function abrirDetalleEfector(idEfector,idFactura, tipopresta, nombreEfe) {
	if (idEfector == "") { idEfector = 0; }
	var dire = "consultaLiquidacionDetalleEfector.php?id="+idFactura+"&idEfector="+idEfector+"&tipopresta="+tipopresta+"&nombreEfe="+nombreEfe;
	window.open(dire,"Detalle por Efector",'width=800, height=500,resizable=yes');
}

</script>

</head>
<body bgcolor="#CCCCCC">
<div align="center">
	<h3>Detalle de Liquidacion</h3>
	<div class="nover">
		<h3 style="color: blue">Estado: <?php echo $_GET['estado']; ?></br>Auditor: '<?php echo $rowFactura['usuarioliquidacion'] ?>'</br> (<?php echo $today ?>) </h3>
		<h3 style="margin-bottom:1px">ID Interno: <?php echo $rowFactura['id'];?> - Fecha de Recepcion: <?php echo $rowFactura['fecharecepcion'];?> - Fecha de Correo: <?php echo $rowFactura['fechacorreo'];?></h3>
	</div>
	<div class="grilla" style="margin-top:10px; margin-bottom:10px; width: 60%">
		<table>
			<tr>
				<td colspan="2" class="title">Prestador</td>
			</tr>
			<tr>
				<td align="right" width="18%">Codigo: </td>
				<td align="left"><?php echo $rowFactura['idPrestador'];?></td>
			</tr>
			<tr>
				<td align="right">Razon Social: </td>
				<td align="left"><?php echo $rowFactura['nombre'];?></td>
			</tr>
			<tr>
				<td align="right">C.U.I.T.: </td>
				<td align="left"><?php echo $rowFactura['cuit'];?></td>
			</tr>
		</table>
	</div>
	<div class="grilla" style="margin-top:10px; margin-bottom:10px; width: 60%">
		<table>
			<tr>
				<td colspan="4" class="title">Comprobante</td>
			</tr>
			<tr>
				<td><?php echo $rowFactura['tipocomprobante'].' Nro.: '.$rowFactura['puntodeventa'].'-'.$rowFactura['nrocomprobante'];?></td>
				<td align="center">Fecha: <?php echo $rowFactura['fechacomprobante'];?></td>
				<td><?php echo $rowFactura['descripcioncorta'].' Nro.: '.$rowFactura['nroautorizacion'];?></td>
			</tr>
			<tr>
				<td align="center">ID Interno: <?php echo $rowFactura['id'];?></td>
				<td></td>
				<td align="center">Importe: <?php echo number_format($rowFactura['importecomprobante'],2,",",".");?></td>
			</tr>
			<?php if ($rowFactura['establecimiento'] != NULL) { ?>
				<tr>
					<td colspan="4"><?php echo "Establecimiento: ".$rowFactura['establecimiento'] ?></td>
				</tr>
			<?php }?>
		</table>
	</div>
	<div class="grilla" style="margin-top:10px; margin-bottom:10px; width: 60%">
		<table>
			<tr>
				<td colspan="5" class="title">Totalizador</td>
			</tr>
			<tr>
				<td class="title">Credito</td>
				<td class="title">Debitos</td>
				<td class="title">Imp. Liquidado</td>
				<td class="title">A Pagar</td>
				<td class="title">Pago</td>
			</tr>
			<tr>
				<td><?php echo number_format($rowFactura['totalcredito'],2,",",".");?></td>
				<td><?php echo number_format($rowFactura['totaldebito'],2,",",".");?></td>
				<td><?php echo number_format($rowFactura['importeliquidado'],2,",",".");?></td>
				<td><?php echo number_format($rowFactura['restoapagar'],2,",",".");?></td>
				<td><?php echo number_format($rowFactura['totalpagado'],2,",",".");?></td>
			</tr>
		</table>
	</div>
	<table>
		<tr>
			<td><input class="nover" type="button" value="Beneficiarios y Prestaciones" onclick="mostrarInfo('bene')"/></td>
			<td><input class="nover" type="button" value="Carencias" onclick="mostrarInfo('carencias')"/></td>
			<td><input class="nover" type="button" value="Estadisticas" onclick="mostrarInfo('estadistica')"/></td>
			<td><input class="nover" type="button" value="Detalle Debito" onclick="mostrarInfo('debitos')"/></td>
			<td><input class="nover" type="button" value="Detalle x Efector" onclick="mostrarInfo('agrupaEfector')"/></td>
		</tr>
	</table>
	
	
	<div id="bene" style="display: none">
		<h3>Detalle de Beneficiarios y Prestaciones</h3>
	<?php $cantDebitosPresta = 0;
		  if ($numBeneficiarios > 0) { 
			while ($rowBeneficiarios = mysql_fetch_assoc($resBeneficiarios)) {
				 $nombreBene = $rowBeneficiarios['nombretitu'];
				 if ($rowBeneficiarios['tipoafiliado'] != 0) { $nombreBene = $rowBeneficiarios['nombrefami']; } 
				 if ($rowBeneficiarios['totaldebito'] > 0) { $cantDebitosPresta = 1; } ?>
				 <div class="grilla" style="width: 90%; margin-top: 10px">			
					<table>
							<tr>
								<td class="title" colspan="3" width="50%">Beneficiario</td>
								<td class="title">Facturado</td>
								<td class="title">Debito</td>
								<td class="title">Credito</td>
								<td class="title">Exceptuado</td>
							</tr>
							<tr>
								<td colspan="3"><?php echo $nombreBene." - ".$rowBeneficiarios['nroafiliado']."/". $rowBeneficiarios['nroorden'] ?></td>
								<td><?php echo number_format($rowBeneficiarios['totalfacturado'],2,",","."); ?></td>
								<td><?php echo number_format($rowBeneficiarios['totaldebito'],2,",","."); ?></td>
								<td><?php echo number_format($rowBeneficiarios['totalcredito'],2,",","."); ?></td>
								<td><?php if ($rowBeneficiarios['exceptuado'] == 0) { echo "NO"; } else { echo "SI";} ?></td>
							</tr>
					<?php	if (isset($arrayPresta[$rowBeneficiarios['id']])) { ?>
								<tr>
									<td class="title" colspan="7">Prestaciones</td>
								</tr>
								<tr>
									<td class="title">Codigo</td>
									<td class="title">Fecha</td>
									<td class="title">Cantidad</td>
									<td class="title">Facturado</td>
									<td class="title">Debito</td>
									<td class="title">Credito</td>
									<td class="title">Efector</td>
								</tr>
						  <?php foreach ($arrayPresta[$rowBeneficiarios['id']] as $pretacion) { ?>
									<tr>
										<td><?php echo $pretacion['codigopractica'] ?></td>
										<td><?php echo $pretacion['fechapractica'] ?></td>
										<td><?php echo number_format($pretacion['cantidad'],3,",","."); ?></td>
										<td><?php echo number_format($pretacion['totalfacturado'],2,",","."); ?></td>
										<td><?php echo number_format($pretacion['totaldebito'],2,",","."); ?></td>
										<td><?php echo number_format($pretacion['totalcredito'],2,",","."); ?></td>
										<td><?php if (isset($pretacion['efector'])) { echo $pretacion['efector']."<br>".$pretacion['profesionalestablecimientocirculo']; } ?></td>
									</tr>
							  <?php if ($pretacion['totaldebito'] > 0) { ?>
							  		<tr>
							  			<td colspan="7">MOTIVO DEBITO: <?php echo $pretacion['motivodebito']?></td>
							  		</tr>
							  <?php	}	
							 	    if ($pretacion['inte'] != NULL) { ?>
							  		<tr>
										<td class="title" colspan="7">Integracion</td>
									</tr>
									<tr>
										<td class="title">Solicitado</td>
										<td class="title">Dependencia</td>
										<td class="title" colspan="5">Tipo | Escuela | C.U.E.</td>
									</tr>
									<tr>
										<td><?php echo number_format($pretacion['totalsolicitado'],2,",","."); ?></td>
										<td><?php if ($pretacion['dependencia'] == 1) { echo "SI"; } else { echo "NO"; } ?></td>
										<td colspan="5"><?php echo $pretacion['codigoescuela']." - ".$pretacion['nombreescuela']." - ".$pretacion['cue'] ?></td>
									</tr>
							  <?php } ?>
						  <?php } ?>
					<?php   } else { ?>
								<tr><td class="title" colspan="7">Sin Prestaciones Cargadas</td></tr>
					<?php   }?>		
					</table>
				</div>
		<?php }
		  } else { ?>
			<p style="color: blue"><b>Sin Beneficiarios cargadas</b></p>
	<?php } ?>
	</div>
	
	<div id="debitos" style="display: none">
		<h3>Detalle de Debitos en Prestaciones</h3>
		<?php if ($cantDebitosPresta == 1) {
				 $resBeneficiarios = mysql_query($sqlBeneficiarios,$db);
				 while ($rowBeneficiarios = mysql_fetch_assoc($resBeneficiarios)) {
					 $nombreBene = $rowBeneficiarios['nombretitu'];
					 if ($rowBeneficiarios['tipoafiliado'] != 0) { $nombreBene = $rowBeneficiarios['nombrefami']; } 
					 if ($rowBeneficiarios['totaldebito'] > 0) { ?>
					 <div class="grilla" style="width: 90%; margin-top: 10px">			
						<table>
								<tr>
									<td class="title" colspan="3" width="50%">Beneficiario</td>
									<td class="title">Facturado</td>
									<td class="title">Debito</td>
									<td class="title">Credito</td>
									<td class="title">Exceptuado</td>
								</tr>
								<tr>
									<td colspan="3"><?php echo $nombreBene." - ".$rowBeneficiarios['nroafiliado']."/". $rowBeneficiarios['nroorden'] ?></td>
									<td><?php echo number_format($rowBeneficiarios['totalfacturado'],2,",","."); ?></td>
									<td><?php echo number_format($rowBeneficiarios['totaldebito'],2,",","."); ?></td>
									<td><?php echo number_format($rowBeneficiarios['totalcredito'],2,",","."); ?></td>
									<td><?php if ($rowBeneficiarios['exceptuado'] == 0) { echo "NO"; } else { echo "SI";} ?></td>
								</tr>
						<?php	if (isset($arrayPresta[$rowBeneficiarios['id']])) { ?>
									<tr>
										<td class="title" colspan="7">Prestaciones</td>
									</tr>
									<tr>
										<td class="title">Codigo</td>
										<td class="title">Fecha</td>
										<td class="title">Cantidad</td>
										<td class="title">Facturado</td>
										<td class="title">Debito</td>
										<td class="title">Credito</td>
										<td class="title">Efector</td>
									</tr>
							  <?php foreach ($arrayPresta[$rowBeneficiarios['id']] as $pretacion) {	
							  	  		if ($pretacion['totaldebito'] > 0) { ?>
										<tr>
											<td><?php echo $pretacion['codigopractica'] ?></td>
											<td><?php echo $pretacion['fechapractica'] ?></td>
											<td><?php echo number_format($pretacion['cantidad'],3,",","."); ?></td>
											<td><?php echo number_format($pretacion['totalfacturado'],2,",","."); ?></td>
											<td><?php echo number_format($pretacion['totaldebito'],2,",","."); ?></td>
											<td><?php echo number_format($pretacion['totalcredito'],2,",","."); ?></td>
											<td><?php if (isset($pretacion['efector'])) { echo $pretacion['efector']."<br>".$pretacion['profesionalestablecimientocirculo']; } ?></td>
										</tr>
								  		<tr>
								  			<td colspan="7">MOTIVO DEBITO: <?php echo $pretacion['motivodebito']?></td>
								  		</tr>								  
								  <?php if ($pretacion['inte'] != NULL) { ?>
								  		<tr>
											<td class="title" colspan="7">Integracion</td>
										</tr>
										<tr>
											<td class="title">Solicitado</td>
											<td class="title">Dependencia</td>
											<td class="title" colspan="5">Tipo | Escuela | C.U.E.</td>
										</tr>
										<tr>
											<td><?php echo number_format($pretacion['totalsolicitado'],2,",","."); ?></td>
											<td><?php if ($pretacion['dependencia'] == 1) { echo "SI"; } else { echo "NO"; } ?></td>
											<td colspan="5"><?php echo $pretacion['codigoescuela']." - ".$pretacion['nombreescuela']." - ".$pretacion['cue'] ?></td>
										</tr>
									<?php	}	
								 		} ?>
							  <?php } ?>
						<?php   } else { ?>
									<tr><td class="title" colspan="7">Sin Prestaciones Cargadas</td></tr>
						<?php   }?>		
						</table>
					 </div>
			   <?php }
		 		}
			 } else {?>
				<p style="color: blue"><b>Sin Debitos en Prestaciones</b></p>
  	   <?php } ?>
	</div>
	
	<div id="agrupaEfector" style="display: none">
	<h3>Detalle de Facturación Por Efector</h3>
		<?php if ($numAgrupaEfector > 1) { ?>
				<div class="grilla">
					<table>
						<tr>
							<td class="title">Cod Efector</td>
							<td class="title">Nombre</td>
							<td class="title">Facturado</td>
							<td class="title">Debito</td>
							<td class="title">Credito</td>
							<td class="title"></td>
						</tr>
			 <?php  $totFacEfe = 0;
			 		$totDebEfe = 0;
			 		$totCreEfe = 0;
			 		while ($rowAgrupaEfector = mysql_fetch_assoc($resAgrupaEfector)) { 	
			 			$totFacEfe += $rowAgrupaEfector['facturado'];
			 			$totDebEfe += $rowAgrupaEfector['debito'];
			 			$totCreEfe += $rowAgrupaEfector['credito'];
			 			$nombreEfe = "SIN EFECTOR";
			 			$codigoEfe = "";
			 			if ($rowAgrupaEfector['nombre'] != NULL) { $nombreEfe = $rowAgrupaEfector['nombre']; 
			 													   $codigoEfe = $rowAgrupaEfector['codigo']; } ?>
			 			<tr>
			 				<td><?php echo $codigoEfe; ?></td>
							<td><?php echo $nombreEfe; ?></td>
							<td><?php echo number_format($rowAgrupaEfector['facturado'],2,",","."); ?></td>
							<td><?php echo number_format($rowAgrupaEfector['debito'],2,",","."); ?></td>
							<td><?php echo number_format($rowAgrupaEfector['credito'],2,",","."); ?></td>
							<td><input type="button" value="DETALLE" onclick="abrirDetalleEfector('<?php echo $codigoEfe ?>', '<?php echo $id ?>', '<?php echo $tipopresta ?>', '<?php echo $nombreEfe ?>')"/></td>
						</tr>
			 <?php } ?>
					<tr>
			  			<td class="title" colspan="2">TOTAL</td>
			  			<td class="title"><?php echo number_format($totFacEfe,2,",","."); ?></td>
			  			<td class="title"><?php echo number_format($totDebEfe,2,",","."); ?></td>
			  			<td class="title"><?php echo number_format($totCreEfe,2,",","."); ?></td>
			  			<td class="title"></td>
			  		</tr>
				</table>
			</div>
		<?php } else { ?>
				<p style="color: blue"><b>No existe efector o el efector es el mismo para todas las practicas cargada</b></p>
		<?php } ?>
	</div>
	
	<div id="carencias" style="display: none">
	<h3>Detalle de Carencias</h3>
<?php if ($numCarencias > 0) { ?>
			<div class="grilla" style="width: 90%">
				<table>
					<tr>
						<td class="title">Carencia</td>
						<td class="title">Facturado</td>
						<td class="title">Debito</td>
						<td class="title">Efector</td>
						<td class="title" width="50%">Motivo</td>
					</tr>
			  <?php $totalCarencia = 0;
			  		$totalCarenciaFacturado = 0;
			  	    while ($rowCarencias = mysql_fetch_assoc($resCarencias)) { 
			  			$totalCarencia += $rowCarencias['totaldebito']; 
			  			$totalCarenciaFacturado += $rowCarencias['totalfacturado']; ?>
						<tr>
							<td><?php echo $rowCarencias['identidadbeneficiario'] ?></td>
							<td><?php echo number_format($rowCarencias['totalfacturado'],2,",","."); ?></td>
							<td><?php echo number_format($rowCarencias['totaldebito'],2,",","."); ?></td>
							<td><?php echo $rowCarencias['efectorcarencia'] ?></td>
							<td><?php echo $rowCarencias['motivocarencia'] ?></td>
						</tr>
			  <?php } ?>
			  		<tr>
			  			<td class="title">TOTAL</td>
			  			<td class="title"><?php echo number_format($totalCarenciaFacturado,2,",","."); ?></td>
			  			<td class="title"><?php echo number_format($totalCarencia,2,",","."); ?></td>
			  			<td class="title" colspan="2"></td>
			  		</tr>
				</table>
			</div>
<?php } else { ?>
		<p style="color: blue"><b>Sin Carencia de Beneficiarios</b></p>
<?php } ?>
	</div>
		
	<div id="estadistica" style="display: none">
		<h3>Estadistica Res. 650</h3>
	<?php if ($numEstadistica > 0) { ?>
			<div class="grilla">
				<table>
					<tr>
						<td class="title">Descripcion</td>
						<td class="title">Cantidad</td>
					</tr>
			 <?php  while ($rowEstadistica = mysql_fetch_assoc($resEstadistica)) { 	?>
			 			<tr>
							<td><?php echo $rowEstadistica['descripcion'] ?></td>
							<td><?php echo $rowEstadistica['cantidadcomputo'] ?></td>
						</tr>
			 <?php } ?>
				</table>
			</div>
	<?php } else { ?>
			<p style="color: blue"><b>Sin Estadística cargada</b></p>
	<?php } ?>
	</div>
	<p><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" /> </p>
</div>
</body>
</html>