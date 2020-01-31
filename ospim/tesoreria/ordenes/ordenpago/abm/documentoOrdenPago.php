<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
require ($libPath."numeros.php");
include($libPath."bandejaSalida.php");

$nroOrden = $_GET['nroorden'];
$email = $_GET['email'];
$sqlCabecera = "SELECT *, DATE_FORMAT(o.fechaorden, '%d-%m-%Y') as fechaorden, pr.descrip as provincia, l.nomlocali as localidad
				FROM ordencabecera o, prestadores p, provincia pr, localidades l 
				WHERE o.nroordenpago = $nroOrden and 
					  o.codigoprestador = p.codigoprestador and 
					  p.codprovin = pr.codprovin and
					  p.codlocali = l.codlocali";
$resCabecera = mysql_query($sqlCabecera,$db);
$rowCabecera = mysql_fetch_assoc($resCabecera);

if ($rowCabecera['debito'] > 0) {
	$sqlDebito = "SELECT *, DATE_FORMAT(o.fechadebito, '%d-%m-%Y') as fechadebito, pr.descrip as provincia, l.nomlocali as localidad
				  FROM ordendebito o, prestadores p, provincia pr, localidades l 
				  WHERE nroordenpago = $nroOrden and 
					    o.codigoprestador = p.codigoprestador and 
	 					p.codprovin = pr.codprovin and 
						p.codlocali = l.codlocali";
	$resDebito = mysql_query($sqlDebito,$db);
	$rowDebito = mysql_fetch_assoc($resDebito);
	
	$sqlLote = "SELECT cai, DATE_FORMAT(fechavto, '%d-%m-%Y') as fechavto FROM ordendebitolote WHERE nroinicio <= ".$rowDebito['nronotadebito']." and nrofin >= ".$rowDebito['nronotadebito']." LIMIT 1";;
	$resLote = mysql_query($sqlLote,$db);
	$rowLote = mysql_fetch_assoc($resLote);
	
	$arrayDetalleDebito = array();
	$i= 0;
	
	if ($rowCabecera['personeria'] != 6) { 
		//NO FARMACEUTICOS VE LAS PRACTICAS
		$sqlDetalleDebito = "SELECT f.id, f.puntodeventa, f.nrocomprobante, 
		 						    DATE_FORMAT(f.fechacomprobante, '%d-%m-%Y') as fechacomprobante, 
								    p.codigopractica, fb.nroafiliado, fb.nroorden, fp.motivodebito, fp.totaldebito
								FROM facturasprestaciones fp, facturasbeneficiarios fb, facturas f, practicas p
								WHERE fp.idfactura in (SELECT idFactura FROM ordendebitodetalle WHERE nroordenpago = $nroOrden) and 
									  fp.totaldebito > 0 and
									  fp.idFacturabeneficiario = fb.id and
									  fp.idfactura = f.id and
									  fp.idPractica = p.idpractica";
	} else {
		//FARMACEUTICOS VE MEDICAMENTOS
		$sqlDetalleDebito = "SELECT f.id, f.puntodeventa, f.nrocomprobante,
									DATE_FORMAT(f.fechacomprobante, '%d-%m-%Y') as fechacomprobante,
									CONCAT(m.codigo,' ',m.nombre) as codigopractica, fb.nroafiliado, fb.nroorden, fp.motivodebito, fp.totaldebito
								FROM facturasprestaciones fp, facturasbeneficiarios fb, facturas f, medicamentos m
								WHERE fp.idfactura in (SELECT idFactura FROM ordendebitodetalle WHERE nroordenpago = $nroOrden) and
									fp.totaldebito > 0 and
									fp.idFacturabeneficiario = fb.id and
									fp.idfactura = f.id and
									fp.idPractica = m.codigo";
	}
	$resDetalleDebito = mysql_query($sqlDetalleDebito,$db);
	$numDetalleDebito = mysql_num_rows($resDetalleDebito);
	if ($numDetalleDebito > 0) {
		while ($rowDebitoDetalle = mysql_fetch_assoc($resDetalleDebito)) {
			$bene = "";
			if ($rowDebitoDetalle['nroorden'] == 0) {
				$sqlTitular = "SELECT apellidoynombre FROM titulares WHERE nroafiliado = ".$rowDebitoDetalle['nroafiliado'];
				$resTitular = mysql_query($sqlTitular,$db);
				$numTitular = mysql_num_rows($resTitular);
				if ($numTitular > 0) {
					$rowTitular = mysql_fetch_assoc($resTitular);
					$bene = $rowTitular['apellidoynombre'];
				} else {
					$sqlTitulardebaja = "SELECT apellidoynombre FROM titularesdebaja WHERE nroafiliado = ".$rowDebitoDetalle['nroafiliado'];
					$resTitulardebaja = mysql_query($sqlTitulardebaja,$db);
					$numTitulardebaja = mysql_num_rows($resTitulardebaja);
					if ($numTitulardebaja > 0) {
						$rowTitulardebaja = mysql_fetch_assoc($resTitulardebaja);
						$bene = $rowTitulardebaja['apellidoynombre'];
					}
				}
			} else {
				$sqlFamiliar = "SELECT apellidoynombre FROM familiares WHERE nroafiliado = ".$rowDebitoDetalle['nroafiliado']." and nroorden = ".$rowDebitoDetalle['nroorden'];
				$resFamiliar = mysql_query($sqlFamiliar,$db);
				$numFamiliar = mysql_num_rows($resFamiliar);
				if ($numFamiliar > 0) {
					$rowFamiliar = mysql_fetch_assoc($resFamiliar);
					$bene = $rowFamiliar['apellidoynombre'];
				} else {
					$sqlFamiliarBaja = "SELECT apellidoynombre FROM familiaresdebaja WHERE nroafiliado = ".$rowDebitoDetalle['nroafiliado']." and nroorden = ".$rowDebitoDetalle['nroorden'];
					$resFamiliarBaja = mysql_query($sqlFamiliarBaja,$db);
					$numFamiliarBaja = mysql_num_rows($resFamiliarBaja);
					if ($numFamiliar > 0) {
						$rowFamiliarBaja = mysql_fetch_assoc($resFamiliarBaja);
						$bene = $rowFamiliarBaja['apellidoynombre'];
					}
				}
			}
			
			
			$arrayDetalleDebito[$i] = array("id"=>$rowDebitoDetalle['id'],
											"factura"=> $rowDebitoDetalle['puntodeventa']."-".$rowDebitoDetalle['nrocomprobante'],
											"fecha" => $rowDebitoDetalle['fechacomprobante'],
											"practica" => $rowDebitoDetalle['codigopractica'],
											"bene" => $bene,
											"motivo" => $rowDebitoDetalle['motivodebito'],
											"importe" => $rowDebitoDetalle['totaldebito']);
			$i++;
		}
	}
	
	$sqlDetalleCarencia = "SELECT f.id, f.puntodeventa, f.nrocomprobante,
								DATE_FORMAT(f.fechacomprobante, '%d-%m-%Y') as fechacomprobante,
								fc.motivocarencia, fc.identidadbeneficiario, fc.totaldebito
							FROM facturascarenciasbeneficiarios fc, facturas f
							WHERE fc.idfactura in (SELECT idFactura FROM ordendetalle WHERE nroordenpago = $nroOrden) and
								  fc.totaldebito > 0 and
								  fc.idfactura = f.id";
	$resDetalleCarencia = mysql_query($sqlDetalleCarencia,$db);
	$numDetalleCarencia = mysql_num_rows($resDetalleCarencia);
	if ($numDetalleCarencia > 0) {
		while ($rowDetalleCarencia = mysql_fetch_assoc($resDetalleCarencia)) {
			$arrayDetalleDebito[$i] = array("id"=>$rowDetalleCarencia['id'],
											"factura"=> $rowDetalleCarencia['puntodeventa']."-".$rowDetalleCarencia['nrocomprobante'],
											"fecha" => $rowDetalleCarencia['fechacomprobante'],
											"practica" => "",
											"bene" => $rowDetalleCarencia['identidadbeneficiario'],
											"motivo" => $rowDetalleCarencia['motivocarencia'],
											"importe" => $rowDetalleCarencia['totaldebito']);
			$i++;
		}
	}
}

require($libPath."fpdf.php");
$maquina = $_SERVER['SERVER_NAME'];
if(strcmp("localhost",$maquina)==0)
	$carpetaOrden="../OrdenesPagoPDF/";
else
	$carpetaOrden="/home/sistemas/Documentos/Repositorio/OrdenesPagoPDF/";

function printHeader($pdf) {
	$pdf->Image('../img/Logo Membrete OSPIM.jpg',7,1,25,20,'JPG');
	$pdf->SetFont('Courier','B',30);
	$pdf->SetXY(35, 1);
	$pdf->Cell(35,10,"OSPIM",0,0);
	$pdf->SetFont('Courier','B',10);
	$pdf->SetXY(35, 10);
	$pdf->Cell(55,5,"Obra Social del Personal",0,0);
	$pdf->SetXY(35, 13);
	$pdf->Cell(55,5,"de la Industria Maderera",0,0);
	$pdf->SetFont('Courier','B',7);
	$pdf->SetXY(35, 16);
	$pdf->Cell(55,5,"RNOS 11.100-1",0,0);
	$pdf->SetFont('Courier','B',11);
	$pdf->SetXY(135, 1);
	$pdf->Cell(75,5,"C.U.I.T.: 30-50289264-5",0,0,"R");
	$pdf->SetXY(135, 6);
	$pdf->Cell(75,5,"I.V.A. EXENTO",0,0,"R");
	$pdf->SetXY(135, 11);
	$pdf->Cell(75,5,"INGRESOS BRUTOS EXENTO",0,0,"R");
	$pdf->SetFont('Courier','B',7);
	$pdf->SetXY(100, 16);
	$pdf->Cell(80,5,"Rojas 254 - CP 1405 - Cap. Fed. | Tel: 4431-4089/4791 | www.ospim.com.ar",0,0);
	$pdf->Line(7, 21, 210, 21);
}

function printDetalle($pdf, $rowCabecera, $db, $tipo) {
	$nroOrden = $rowCabecera['nroordenpago'];
	$pdf->SetFont('Courier','B',8);
	$pdf->SetXY(7, 21);
	$pdf->Cell(20,5,$rowCabecera['fechaorden'],0,0,"R");
	$pdf->SetXY(130, 21);
	$pdf->Cell(80,5,"Orden de Pago: ".$rowCabecera['nroordenpago']." - $tipo",0,0,"R");
	$pdf->SetXY(7, 25);
	$pdf->Cell(203,5,"NOMBRE: ".$rowCabecera['nombre']." - CUIT: ".$rowCabecera['cuit'],0,0,"L");
	$pdf->SetXY(7, 28);
	$pdf->Cell(203,5,"DIRECCION: ".$rowCabecera['domicilio']." - CP: ".$rowCabecera['numpostal']." - ".$rowCabecera['localidad']." - ".$rowCabecera['provincia'],0,0,"L");
	$pdf->SetXY(7, 31);
	$pdf->Cell(203,5,"EMAIL: ".$rowCabecera['email1']." - ".$rowCabecera['email2']." | TEL: ".$rowCabecera['ddn1']."-".$rowCabecera['telefono1'],0,0,"L");
	
	$principioOrden = "Ha sido emitido el pago";
	if ($rowCabecera['formapago'] == "E") {
		$principioOrden .= " en efectivo correspondiente a prestaciones medicas";
	}
	if ($rowCabecera['formapago'] == "T") {
		$principioOrden .= " a traves de la transferencia Nro ".$rowCabecera['comprobantepago']." correspondiente a prestaciones medicas";
	}
	if ($rowCabecera['formapago'] == "C") {
		$principioOrden .= " a traves del cheque Nro ".$rowCabecera['comprobantepago']." correspondiente a prestaciones medicas";
	}
	$finalOrden = "asistenciales brindadas a nuestros afiliados, segun el siguiente detalle: ";
	
	$pdf->Line(7, 36, 210, 36);
	$pdf->SetXY(7, 36);
	$pdf->Cell(203,5,$principioOrden,0,0,"L");
	$pdf->SetXY(7, 39);
	$pdf->Cell(203,5,$finalOrden,0,0,"L");
	
	$y = 44;
	$pdf->SetXY(7, $y);
	$pdf->Cell(84.72,5,"FACTURA Nº",1,1,"C");
	$pdf->SetXY(91.72, $y);
	$pdf->Cell(27.06,5,"MONTO",1,1,"C");
	$pdf->SetXY(118.78, $y);
	$pdf->Cell(27.06,5,"DEBITO",1,1,"C");
	$pdf->SetXY(145.84, $y);
	$pdf->Cell(27.06,5,"A PAGAR",1,1,"C");
	$pdf->SetXY(172.9, $y);
	$pdf->Cell(10,5,"PAGO",1,1,"C");
	$pdf->SetXY(182.9, $y);
	$pdf->Cell(27.06,5,"SALDO",1,1,"C");
	
	$pdf->SetFont('Courier','B',6);
	$y += 5;
	$sqlDetalle = "SELECT o.*, f.*, establecimientos.nombre as establecimiento FROM ordendetalle o, facturas f 
					LEFT JOIN establecimientos ON establecimientos.codigo = f.idestablecimiento
					WHERE o.nroordenpago = $nroOrden and o.idfactura = f.id";
	$resDetalle = mysql_query($sqlDetalle,$db);
	while($rowDetalle = mysql_fetch_array($resDetalle)) {
		 $pdf->SetXY(7, $y);
		 $establecimiento = "";
		 if ($rowDetalle['establecimiento'] != NULL) {
		 	$establecimiento = " | ".$rowDetalle['establecimiento'];
		 	$establecimiento = substr($establecimiento, 0, 50);
		 }
		 $pdf->Cell(84.72,4,$rowDetalle['puntodeventa']."-".$rowDetalle['nrocomprobante'].$establecimiento,1,1,"C");
		 $pdf->SetXY(91.72, $y);
		 $pdf->Cell(27.06,4,$rowDetalle['importecomprobante'],1,1,"C");
		 $pdf->SetXY(118.78, $y);
		 $pdf->Cell(27.06,4,$rowDetalle['totaldebito'],1,1,"C");
		 $pdf->SetXY(145.84, $y);
		 $pdf->Cell(27.06,4,$rowDetalle['importepago'],1,1,"C");
		 $pdf->SetXY(172.9, $y);
		 $pdf->Cell(10,4,$rowDetalle['tipocancelacion'],1,1,"C");
		 $pdf->SetXY(182.9, $y);
		 $pdf->Cell(27.06,4,$rowDetalle['restoapagar'],1,1,"C");
	 	 $y += 4;
	}
	$pdf->SetFont('Courier','B',8);
	$y += 2;
	$pdf->SetXY(118.78, $y);
	$pdf->Cell(27.06,5,"TOTAL",0,0,"C");
	$pdf->SetXY(145.84, $y);
	$total = number_format(round($rowCabecera['importe']+$rowCabecera['retencion'],2),2,'.','');
	$pdf->Cell(27.06,5,$total,0,0,"C");
	$y += 5;
	$pdf->SetXY(118.78, $y);
	$pdf->Cell(27.06,5,"RETENCION",0,0,"C");
	$pdf->SetXY(145.84, $y);
	$pdf->Cell(27.06,5,$rowCabecera['retencion'],0,0,"C");
	$y += 5;
	$pdf->SetXY(118.78, $y);
	$pdf->Cell(27.06,5,"A PAGAR",0,0,"C");
	$pdf->SetXY(145.84, $y);
	$pdf->Cell(27.06,5,$rowCabecera['importe'],0,0,"C");
	
	$y = 190;
	$pdf->Image('../img/fgornatti.png',25,$y,15,20,'PNG');
	$pdf->Image('../img/fguzman.png',170,$y,25,20,'PNG');
	$y += 12;
	$pdf->Image('../img/sgornatti.png',15,$y,40,18,'PNG');
	$pdf->Image('../img/sguzman.png',165,$y,35,20,'PNG');
}

function printRecibo($pdf, $rowCabecera) {
	$pdf->SetFont('Courier','B',8);
	$y = 218;
	$pdf->Line(7, $y, 210, $y);
	
	$pdf->SetXY(130, $y);
	$pdf->Cell(80,5,"Recibo Nro: ".$rowCabecera['nroordenpago'],0,0,"R");
	$pdf->SetXY(7, $y);
	$pdf->Cell(43,5,"Buenos Aires, ".date("d-m-Y"),0,0,"R");
	
	$y += 4;
	$textoRecibo = "Recibimos de O.S.P.I.M. la cantidad de pesos ".cfgValorEnLetras($rowCabecera['importe']);
	if ($rowCabecera['formapago'] == "E") {
		$textoRecibo .= " en efectivo";
	}
	if ($rowCabecera['formapago'] == "T") {
		$textoRecibo2 = "Por intermedio de la transferencia Nro. ".$rowCabecera['comprobantepago'];
	}
	if ($rowCabecera['formapago'] == "C") {
		$textoRecibo2 = "Por intermedio del Cheque Nro ".$rowCabecera['comprobantepago'];
	}
	$textoFin = "En concepto de pago de prestaciones medicas asistenciales detallas en la orden de pago nro ".$rowCabecera['nroordenpago'];
	$pdf->SetXY(7, $y);
	$pdf->Cell(200,5,$textoRecibo,0,0,"L");
	
	if ($rowCabecera['formapago'] != "E") {
		$y += 4;
		$pdf->SetXY(7, $y);
		$pdf->Cell(200,5,$textoRecibo2,0,0,"L");
	}
	
	$y += 4;
	$pdf->SetXY(7, $y);
	$pdf->Cell(200,5,$textoFin,0,0,"L");
	$y += 5;
	$pdf->SetFont('Courier','B',8);
	$pdf->SetXY(160, $y);
	$pdf->Cell(50,4,"SON $: ".$rowCabecera['importe'],1,1,"R");
	$y += 4;
	$pdf->SetFont('Courier','B',6);
	$pdf->SetXY(7, $y);
	$pdf->Cell(200,4,"Remitir a O.S.P.I.M. conjuntamente con vuestro recibo oficial. En caso de hornorarios el talon debe ser firmado y sellado por el profesional",0,0,"L");
	$y += 16;
	$pdf->SetXY(7, $y);
	$pdf->Cell(150,1,"---------------------------------------",0,0,"C");
	$y += 1;
	$pdf->SetXY(7, $y);
	$pdf->Cell(150,3,"FIRMA PRESTADOR",0,0,"C");
}

function printHeaderPlanillaDebito($pdfPlanilla, $rowDebito) {
	$pdfPlanilla->Image('../img/Logo Membrete OSPIM.jpg',7,1,25,20,'JPG');
	$pdfPlanilla->SetFont('Courier','B',30);
	$pdfPlanilla->SetXY(35, 1);
	$pdfPlanilla->Cell(35,10,"OSPIM",0,0);
	$pdfPlanilla->SetFont('Courier','B',10);
	$pdfPlanilla->SetXY(35, 10);
	$pdfPlanilla->Cell(55,5,"Obra Social del Personal",0,0);
	$pdfPlanilla->SetXY(35, 13);
	$pdfPlanilla->Cell(55,5,"de la Industria Maderera",0,0);
	
	$pdfPlanilla->SetFont('Courier','I',7);
	$pdfPlanilla->SetXY(15, 20);
	$pdfPlanilla->Cell(60,5,"Rojas 254 (C1405ABB) Capital Federal",0,0);
	$pdfPlanilla->SetXY(15, 24);
	$pdfPlanilla->Cell(60,5,"Tel.: 4431-4791/4089 - Fax: 4431-2567",0,0);
	$pdfPlanilla->SetXY(30, 28);
	$pdfPlanilla->Cell(30,5,"tesoreria@ospim.com.ar",0,0);

	$pdfPlanilla->SetFont('Courier','B',30);
	$pdfPlanilla->SetXY(105, 1);
	$pdfPlanilla->Cell(9,10,"X",1,1);
	
	$pdfPlanilla->SetFont('Courier','B',15);
	$pdfPlanilla->SetXY(135, 1);
	$pdfPlanilla->Cell(70,8,"Planilla de Debito",0,0);
	$pdfPlanilla->SetFont('Courier','B',12);
	$pdfPlanilla->SetXY(150, 9);
	$pdfPlanilla->Cell(45,7,"Nº ".str_pad ($rowDebito['ptoventa'],4,0,STR_PAD_LEFT)."-".str_pad($rowDebito['nronotadebito'],8,0,STR_PAD_LEFT),0,0);
	$pdfPlanilla->SetXY(135, 16);
	$pdfPlanilla->Cell(60,7,"Fecha: ".$rowDebito['fechadebito'],0,0);
	
	$pdfPlanilla->SetFont('Courier','',8);
	$pdfPlanilla->SetXY(135, 22);
	$pdfPlanilla->Cell(55,5,"RNOS Nro.: 11100-1",0,0);
	$pdfPlanilla->SetXY(135, 25);
	$pdfPlanilla->Cell(55,5,"Nro. Ord.: ".$rowDebito['nroordenpago'],0,0);
	
	$pdfPlanilla->Line(7, 33, 210, 33);
	$pdfPlanilla->Line(109, 11, 109, 33);
	
	$pdfPlanilla->SetFont('Courier','',10);
	$pdfPlanilla->SetXY(15, 34);
	$pdfPlanilla->Cell(190,5,"ENTIDAD: ".$rowDebito['nombre'],0,0);
	$pdfPlanilla->SetXY(15, 38);
	$pdfPlanilla->Cell(190,5, "CUIT: ".$rowDebito['cuit'],0,0);
	$pdfPlanilla->SetXY(15, 42);
	$pdfPlanilla->Cell(190,5,"DOMICILIO: ".$rowDebito['domicilio']." - CP: ".$rowDebito['numpostal'],0,0);
	$pdfPlanilla->SetXY(15, 46);
	$pdfPlanilla->Cell(190,5,"LOCALIDAD: ".$rowDebito['localidad']." - (".$rowDebito['provincia'].")",0,0);
	
	$pdfPlanilla->Line(7, 52, 210, 52);
	
	$pdfPlanilla->SetFont('Courier','B',10);
	$pdfPlanilla->SetXY(50, 53);
	$pdfPlanilla->Cell(55,5,"DETALLE",0,0);
	$pdfPlanilla->SetXY(180, 53);
	$pdfPlanilla->Cell(55,5,"IMPORTE",0,0);
	
	$pdfPlanilla->Line(7, 59, 210, 59);
}

function printHeaderDebito($pdf, $rowDebito, $tipoDebito) {
	$pdf->Image('../img/Logo Membrete OSPIM.jpg',7,1,25,20,'JPG');
	$pdf->SetFont('Courier','B',30);
	$pdf->SetXY(35, 1);
	$pdf->Cell(35,10,"OSPIM",0,0);
	$pdf->SetFont('Courier','B',10);
	$pdf->SetXY(35, 10);
	$pdf->Cell(55,5,"Obra Social del Personal",0,0);
	$pdf->SetXY(35, 13);
	$pdf->Cell(55,5,"de la Industria Maderera",0,0);
	
	$pdf->SetFont('Courier','I',7);
	$pdf->SetXY(15, 20);
	$pdf->Cell(60,5,"Rojas 254 (C1405ABB) Capital Federal",0,0);
	$pdf->SetXY(15, 24);
	$pdf->Cell(60,5,"Tel.: 4431-4791/4089 - Fax: 4431-2567",0,0);
	$pdf->SetXY(15, 28);
	$pdf->Cell(30,5,"tesoreria@ospim.com.ar - IVA EXENTO",0,0);
	
	$pdf->SetFont('Courier','B',30);
	$pdf->SetXY(105, 1);
	$pdf->Cell(9,10,"C",1,1);
	
	$pdf->SetFont('Courier','B',13);
	if ($tipoDebito == 'D') {
		$pdf->SetXY(96, 25);
		$pdf->Cell(9,10,"DUPLICADO",0,0);
	} else {
		$pdf->SetXY(97, 25);
		$pdf->Cell(9,10,"ORIGINAL",0,0);
	}
	
	
	$pdf->SetFont('Courier','B',15);
	$pdf->SetXY(135, 1);
	$pdf->Cell(70,8,"Nota de Debito",0,0);
	$pdf->SetFont('Courier','B',12);
	$pdf->SetXY(150, 9);
	$pdf->Cell(45,7,"Nº ".str_pad ($rowDebito['ptoventa'],4,0,STR_PAD_LEFT)."-".str_pad($rowDebito['nronotadebito'],8,0,STR_PAD_LEFT),0,0);
	$pdf->SetXY(135, 14);
	$pdf->Cell(60,7,"Fecha: ".$rowDebito['fechadebito'],0,0);
	
	$pdf->SetFont('Courier','',8);
	$pdf->SetXY(135, 20);
	$pdf->Cell(55,5,"CUIT: Nro.: 30-50289264-5",0,0);
	$pdf->SetXY(135, 23);
	$pdf->Cell(55,5,"INGRESOS BRUTOS: EXENTOS",0,0);
	$pdf->SetXY(135, 26);
	$pdf->Cell(55,5,"RNOS Nro.: 11100-1",0,0);
	$pdf->SetXY(135, 29);
	$pdf->Cell(55,5,"Fecha de Inicio de Actividad: 01/01/1972",0,0);
	
	$pdf->Line(7, 33, 210, 33);
	$pdf->Line(109, 11, 109, 27);
	
	$pdf->SetFont('Courier','',10);
	$pdf->SetXY(15, 34);
	$pdf->Cell(190,5,"ENTIDAD: ".$rowDebito['nombre'],0,0);
	$pdf->SetXY(15, 38);
	$pdf->Cell(190,5, "CUIT: ".$rowDebito['cuit'],0,0);
	$pdf->SetXY(15, 42);
	$pdf->Cell(190,5,"DOMICILIO: ".$rowDebito['domicilio']." - CP: ".$rowDebito['numpostal'],0,0);
	$pdf->SetXY(15, 46);
	$pdf->Cell(190,5,"LOCALIDAD: ".$rowDebito['localidad']." - (".$rowDebito['provincia'].")",0,0);
	
	$pdf->Line(7, 52, 210, 52);
	
	$pdf->SetFont('Courier','B',10);
	$pdf->SetXY(50, 53);
	$pdf->Cell(55,5,"DETALLE",0,0);
	$pdf->SetXY(180, 53);
	$pdf->Cell(55,5,"IMPORTE",0,0);
	
	$pdf->Line(7, 59, 210, 59);
}

function printDetalleDebito($pdf, $arrayDetalleDebito) {
	$total = 0;
	$cordY = 60;
	foreach ($arrayDetalleDebito as $id => $detalle) {
		$total += $detalle['importe'];
		
		$pdf->SetFont('Courier','',8);
		$pdf->SetXY(7, $cordY);
		$pdf->Cell(173,5,$detalle['factura']."|".$detalle['fecha']);
		
		$pdf->SetFont('Courier','B',8);
		$pdf->SetXY(180, $cordY);
		$pdf->Cell(30,5,"$ ".number_format($detalle['importe'],2,',','.'),0,0);
		
		$cordY += 5;
		
		$pdf->Line(7, $cordY, 210, $cordY);
	}
	
	$pdf->SetFont('Courier','B',8);
	$pdf->SetXY(7, 240);
	$pdf->Cell(30,5,"SON PESOS: ".cfgValorEnLetras($total),0,0);
	
	$pdf->SetXY(180, 240);
	$pdf->Cell(30,5,"$ ".number_format($total,2,',','.'),0,0);
}

function printDetallePlanillaDebito($pdf, $arrayDetalleDebito, $hoja, $totalHojas) {
	$total = 0;
	$cordY = 60;
	$arrayDebXFac = array();
	foreach ($arrayDetalleDebito as $detalle) {
		$total += $detalle['importe'];

		$pdf->SetFont('Courier','',8);
		$pdf->SetXY(7, $cordY);
		$pdf->Cell(173,5,$detalle['factura']."|".$detalle['fecha']."|".$detalle['practica']."|".$detalle['bene'],0,0);

		$cordY += 5;

		$pdf->SetXY(7, $cordY);
		$pdf->Cell(173,5,"MOTIVO: ".substr($detalle['motivo'],0,90),0,0);

		$pdf->SetFont('Courier','B',8);
		$pdf->SetXY(180, $cordY);
		$pdf->Cell(30,5,"$ ".number_format($detalle['importe'],2,',','.'),0,0);

		$cordY += 5;

		$pdf->Line(7, $cordY, 210, $cordY);
		
		$pdf->SetFont('Courier','',8);
		$pdf->SetXY(105, 250);
		$pdf->Cell(20,5,$hoja." de ".$totalHojas,0,0);
	}

}

function printFooterPlanilla($pdf, $totalDebito) {
	$pdf->SetFont('Courier','B',8);
	$pdf->SetXY(7, 240);
	$pdf->Cell(30,5,"SON PESOS: ".cfgValorEnLetras($totalDebito),0,0);
	
	$pdf->SetXY(180, 240);
	$pdf->Cell(30,5,"$ ".number_format($totalDebito,2,',','.'),0,0);
}

function printFooterDebito($pdf, $cai, $vto) {
	$pdf->SetFont('Courier','B',7);
	$pdf->Line(7, 245, 210, 245);
	
	$pdf->SetXY(7, 245);
	$pdf->Cell(80,5,"Nota: Se adjunta documentación respaldatoria",0,0,"L");
	$pdf->SetXY(7, 247);
	$pdf->Cell(130,5,"Vencimiento presentación de deducciones, con documentación respaldatoria: 60 días",0,0,"L");
	
	$pdf->Line(7, 251, 210, 251);
	
	$pdf->SetXY(160, 251);
	$pdf->Cell(50,5,"C.A.I.: ".$cai,0,0,"R");
	$pdf->SetXY(160, 254);
	$pdf->Cell(50,5,"FECHA DE VENC.: ".$vto,0,0,"R");
	$pdf->SetXY(7, 251);
	$pdf->Cell(130,5,"Debitos de gastos prestacionales según planilla de Debito que se adjunta",0,0,"L");
	$pdf->SetXY(7, 254);
	$pdf->Cell(130,5,"147 Telefono Gratuito CABA, Área de Defensa y Protección del Consumidor",0,0,"L");
	
	$pdf->Line(7, 258, 210, 258);
}

/************************************************/
/************	ORDEN ORIGINAL	*****************/
$ordenNombreArchivo = str_pad($nroOrden, 8, '0', STR_PAD_LEFT);
$nombreArchivoO = "OP".$ordenNombreArchivo."O.pdf";
$pdf = new FPDF('P','mm','Letter');
$pdf->AddPage();
printHeader($pdf);
printDetalle($pdf, $rowCabecera, $db, "ORIGINAL");
printRecibo($pdf, $rowCabecera);
$nombrearchivoO = $carpetaOrden.$nombreArchivoO;
$pdf->Output($nombrearchivoO,'F');
/************************************************/

/************	ORDEN COPIA		*****************/
$nombreArchivoC = "OP".$ordenNombreArchivo."C.pdf";
$pdf = new FPDF('P','mm','Letter');
$pdf->AddPage();
printHeader($pdf);
printDetalle($pdf, $rowCabecera, $db, "DUPLICADO");
$pdf->AddPage();
printHeader($pdf);
printDetalle($pdf, $rowCabecera, $db, "TRIPLICADO");
$nombrearchivoC = $carpetaOrden.$nombreArchivoC;
$pdf->Output($nombrearchivoC,'F');
/************************************************/

if ($rowCabecera['debito'] > 0) {
	/***** AGRUPAMOS EL DETALLE DEL DEBITO POR FACTURA ******/
	$arrayDebitoXFactura = array();
	foreach ($arrayDetalleDebito as $debitodetalle) {
		if (isset($arrayDebitoXFactura[$debitodetalle['id']]['importe'])) { 
			$arrayDebitoXFactura[$debitodetalle['id']]['importe'] += $debitodetalle['importe'];
		} else {
			$arrayDebitoXFactura[$debitodetalle['id']]['factura'] = $debitodetalle['factura'];
			$arrayDebitoXFactura[$debitodetalle['id']]['fecha'] = $debitodetalle['fecha'];
			$arrayDebitoXFactura[$debitodetalle['id']]['importe'] = $debitodetalle['importe'];
		}
	}
	reset($arrayDetalleDebito);
	/********************************************************/
	
	/*********	NOTA DEBITO ORIGINAL 	*********************/
	$nombreNotaDebito = $carpetaOrden."OP".$ordenNombreArchivo."DEB.pdf";
	$pdfNotaDebito = new FPDF('P','mm','Letter');
	$pdfNotaDebito->AddPage();
	printHeaderDebito($pdfNotaDebito, $rowDebito, 'O');
	printDetalleDebito($pdfNotaDebito, $arrayDebitoXFactura);
	printFooterDebito($pdfNotaDebito, $rowLote['cai'], $rowLote['fechavto']);
	$pdfNotaDebito->Output($nombreNotaDebito,'F');
	/********************************************************/
	
	/*********	NOTA DEBITO DUPLICADO 	*********************/
	$nombreNotaDebitoDup = $carpetaOrden."OP".$ordenNombreArchivo."DEBDUP.pdf";
	$pdfNotaDebitoDup = new FPDF('P','mm','Letter');
	$pdfNotaDebitoDup->AddPage();
	printHeaderDebito($pdfNotaDebitoDup, $rowDebito, 'D');
	printDetalleDebito($pdfNotaDebitoDup, $arrayDebitoXFactura);
	printFooterDebito($pdfNotaDebitoDup, $rowLote['cai'], $rowLote['fechavto']);
	$pdfNotaDebitoDup->Output($nombreNotaDebitoDup,'F');
	/********************************************************/
	
	/***********	PLANILLA DE DEBITO MULTIHOJA ************/
	$nombrePlanillaDebito = $carpetaOrden."OP".$ordenNombreArchivo."PL.pdf";
	$pdfPlanilla = new FPDF('P','mm','Letter');	
	$arrayDetalleDividio = array_chunk($arrayDetalleDebito, 18, true);
	foreach ($arrayDetalleDividio as $key =>$arrayDetalleDebitoDiv) {
		$pdfPlanilla->AddPage();
		printHeaderPlanillaDebito($pdfPlanilla, $rowDebito);
		$hoja = $key + 1;
		printDetallePlanillaDebito($pdfPlanilla, $arrayDetalleDebitoDiv,$hoja, sizeof($arrayDetalleDividio));
	}
	printFooterPlanilla($pdfPlanilla, $rowCabecera['debito']);
	$pdfPlanilla->Output($nombrePlanillaDebito,'F');
	/********************************************************/
}

if ($email != "") {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	
	$subject = "Orden de Pago O.S.P.I.M.";
	$bodymail= "<body>Sr. Prestador de O.S.P.I.M.<br><br>Recientemente se le ha efectuado una transferencia bancaria a vuestro C.B.U.<br>Los datos de la transferencia figuran en la \"Orden de Pago\" adjunta.<br>Solicitamos imprimir el adjunto firmarlo, sellarlo y <b>enviarlo</b> junto a vuestro recibo oficial a:<br><br>O.S.P.I.M.<br>Rojas 254 - 1405 C.A.B.A.<br><br>La recepción de la órden de pago y de vuestro recibo son requisitos necesarios para agilizar futuros pagos.<br>Agradecemos vuestra atención<br><br><b>TESORERIA<br>O.S.P.I.M.</b><br><br></body>";
	$username ="tesoreria@ospim.com.ar";
	$modulo = "Ordenes de Pago";
	$arrayAttachment[] = $nombrearchivoO;
	$idMail = guardarEmail($username, $subject, $bodymail, $email, $modulo, $arrayAttachment);
	$updateIdMail = "UPDATE ordencabecera SET idemail = $idMail WHERE nroordenpago = $nroOrden";
	//print($updateIdMail."<br>");
	$dbh->exec($updateIdMail);
	
	$dbh->commit();
}

$pagina = "../buscador/ordenPagoConsulta.php?nroorden=$nroOrden";
Header("Location: $pagina");

/************************************************/
?>