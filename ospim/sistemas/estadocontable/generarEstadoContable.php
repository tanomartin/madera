<?php
$libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php");
set_time_limit ( 0 );
ini_set ( 'memory_limit', '-1' );
$libPath = $_SERVER ['DOCUMENT_ROOT'] . "/madera/lib/";
require_once ($libPath . "phpExcel/Classes/PHPExcel.php");
$fecharegistro = date ( "Y-m-d H:i:s" );
$usuarioregistro = "sistemas";

if (isset ( $_POST ['fechaHasta'] )) {
	$fechageneracion = $_POST ['fechaHasta'];
} else {
	$fechageneracion = date ( 'd-m-Y' );
}
$fechageneracion = substr ( $fechageneracion, 6, 4 ) . "-" . substr ( $fechageneracion, 3, 2 ) . "-01";

$fechaEstadoContable = strtotime ( '-1 month', strtotime ( $fechageneracion ) );
$fechaEstadoContable = date ( 'd-m-Y', $fechaEstadoContable );
$mesEstado = substr ( $fechaEstadoContable, 3, 2 );
$anoEstado = substr ( $fechaEstadoContable, 6, 4 );

$fechadesde = strtotime ( '-10 year', strtotime ( $fechageneracion ) );
$fechadesde = date ( 'Y-m-d', $fechadesde );

$maquina = $_SERVER ['SERVER_NAME'];

if (strcmp ( "localhost", $maquina ) == 0) 
	$archivo_name_xls = "../../contaduria/estadocontable/Estado Contable " . $fechageneracion . ".xls";
else 
	$archivo_name_xls = "/home/sistemas/Documentos/Repositorio/FFFF1208311301SYS/Estado Contable " . $fechageneracion . ".xls";

$archivo_name_htm = "../../contaduria/estadocontable/archivosHtm/Estado Contable " . $fechageneracion . ".htm";

try {	
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$usuario = $_SESSION['usuario'];
	$pass = $_SESSION['clave'];
	$dbh = new PDO ( "mysql:host=$hostname;dbname=$dbname", $usuario, $pass );
	$dbh->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$dbh->beginTransaction ();
	
	$sqlConsultaEstado = "SELECT count(*) as existe FROM estadocontablecontrol WHERE anio = ".$anoEstado." and mes = ".$mesEstado;
	$resConsultaEstado = $dbh->prepare ( $sqlConsultaEstado );
	$resConsultaEstado->execute ();
	$rowConsultaEstado = $resConsultaEstado->fetch ( PDO::FETCH_LAZY );
	if ($rowConsultaEstado['existe'] != 0) {
		$pagina = "estadoContable.php?ok=0";
		Header("Location: $pagina");
		exit(0);
	}
	
	// Totamos el primer y el ultimo disco para las ddjj
	$sqlDiscoDesde = "SELECT nrodisco FROM nominasddjj 
								where fechaarchivoafip >= '" . $fechadesde . "' and fechaarchivoafip < '" . $fechageneracion . "' LIMIT 1";
	$resDiscoDesde = $dbh->query ( $sqlDiscoDesde );
	$rowDiscoDesde = $resDiscoDesde->fetch ();
	$discoDesde = $rowDiscoDesde ['nrodisco'];
	
	$sqlDiscoHasta = "SELECT nrodisco FROM nominasddjj 
								where fechaarchivoafip >= '" . $fechadesde . "' and fechaarchivoafip < '" . $fechageneracion . "' order by nrodisco DESC LIMIT 1";
	$resDiscoHasta = $dbh->query ( $sqlDiscoHasta );
	$rowDiscoHasta = $resDiscoHasta->fetch ();
	$discoHasta = $rowDiscoHasta ['nrodisco'];
	
	$anoDesde = substr ( $fechadesde, 0, 4 );
	$mesDesde = substr ( $fechadesde, 5, 2 );
	
	$anoHasta = substr ( $fechageneracion, 0, 4 );
	$mesHasta = substr ( $fechageneracion, 5, 2 );
	
	// OBTENEMOS LAS DDJJ
	
	$anolimite = 2009;
	$meslimite = 3;
	
	$arrayDDJJ = array ();
	if ($anoDesde < $anolimite or ($anolimite == $anoDesde and $meslimite < $mesDesde)) {
		$sqlDDJJPrimera = "SELECT
				  ddjj.cuit,
				  e.nombre,
				  ddjj.anoddjj,
				  ddjj.mesddjj,
				  ddjj.secuenciapresentacion,
				  ROUND(SUM(ddjj.remundeclarada),2) AS totremune,
				  ROUND(SUM(IF(ddjj.remundeclarada < 1001, ddjj.remundeclarada * 0.081, ddjj.remundeclarada * 0.0765)),2) AS obligacion
				FROM
				      afipddjj ddjj, empresas e
				WHERE
					  ddjj.nrodisco >= " . $discoDesde . " AND
				      ddjj.nrodisco <= " . $discoHasta . " AND
				      ((ddjj.anoddjj = " . $anoDesde . " AND ddjj.mesddjj > " . $mesDesde . ") OR 
				       (ddjj.anoddjj > " . $anoDesde . " AND ddjj.anoddjj < " . $anolimite . ") OR 
					   (ddjj.anoddjj = " . $anolimite . " AND ddjj.mesddjj < " . $meslimite . ")) AND
					  ddjj.cuit = e.cuit
				GROUP by ddjj.cuit, ddjj.anoddjj, ddjj.mesddjj, ddjj.secuenciapresentacion
				ORDER by ddjj.cuit, ddjj.anoddjj, ddjj.mesddjj, ddjj.secuenciapresentacion ASC";
		$resDDJJ = $dbh->prepare ( $sqlDDJJPrimera );
		$resDDJJ->execute ();
		
		while ( $rowDDJJ = $resDDJJ->fetch ( PDO::FETCH_LAZY ) ) {
			$indexPeriodo = $rowDDJJ ['anoddjj'] . $rowDDJJ ['mesddjj'];
			$arrayDDJJ [$rowDDJJ ['cuit']] [$indexPeriodo] = array (
					'remuneracion' => $rowDDJJ ['totremune'],
					'obligacion' => $rowDDJJ ['obligacion'],
			);
			$arrayDDJJ [$rowDDJJ ['cuit']] ['nombre'] = $rowDDJJ ['nombre'];
		}
		unset ( $resDDJJ );
		
		$sqlDDJJSegunda = "SELECT
				  ddjj.cuit,
				  e.nombre,
				  ddjj.anoddjj,
				  ddjj.mesddjj,
				  ddjj.secuenciapresentacion,
				  ROUND(SUM(ddjj.remundeclarada),2) AS totremune,
				  ROUND(SUM(IF(ddjj.remundeclarada < 2401, ddjj.remundeclarada * 0.081, ddjj.remundeclarada * 0.0765)),2) AS obligacion
				FROM
				      afipddjj ddjj, empresas e
				WHERE
					  ddjj.nrodisco >= " . $discoDesde . " AND
				      ddjj.nrodisco <= " . $discoHasta . " AND
				      ((ddjj.anoddjj > " . $anolimite . ") OR
	             	   (ddjj.anoddjj = " . $anolimite . " AND ddjj.mesddjj >= " . $meslimite . ")) AND
					    ddjj.cuit = e.cuit
				GROUP by ddjj.cuit, ddjj.anoddjj, ddjj.mesddjj, ddjj.secuenciapresentacion
				ORDER by ddjj.cuit, ddjj.anoddjj, ddjj.mesddjj, ddjj.secuenciapresentacion ASC";
		
		$resDDJJ = $dbh->prepare ( $sqlDDJJSegunda );
		$resDDJJ->execute ();
		while ( $rowDDJJ = $resDDJJ->fetch ( PDO::FETCH_LAZY ) ) {
			$indexPeriodo = $rowDDJJ ['anoddjj'] . $rowDDJJ ['mesddjj'];
			$arrayDDJJ [$rowDDJJ ['cuit']] [$indexPeriodo] = array (
					'remuneracion' => $rowDDJJ ['totremune'],
					'obligacion' => $rowDDJJ ['obligacion'],
			);
			$arrayDDJJ [$rowDDJJ ['cuit']] ['nombre'] = $rowDDJJ ['nombre'];
		}
		unset ( $resDDJJ );
	} else {
		$sqlDDJJ = "SELECT
				  ddjj.cuit,
				  e.nombre,
				  ddjj.anoddjj,
				  ddjj.mesddjj,
				  ddjj.secuenciapresentacion,
				  ROUND(SUM(ddjj.remundeclarada),2) AS totremune,
				  ROUND(SUM(IF(ddjj.remundeclarada < 2401, ddjj.remundeclarada * 0.081, ddjj.remundeclarada * 0.0765)),2) AS obligacion
				FROM
				      afipddjj ddjj, empresas e
				WHERE
					  ddjj.nrodisco >= " . $discoDesde . " AND
				      ddjj.nrodisco <= " . $discoHasta . " AND
				      ((ddjj.anoddjj > " . $anoDesde . ") OR 
					   (ddjj.anoddjj = " . $anoDesde . " AND ddjj.mesddjj >= " . $mesDesde . ")) AND
					  ddjj.cuit = e.cuit
				GROUP by ddjj.cuit, ddjj.anoddjj, ddjj.mesddjj, ddjj.secuenciapresentacion
				ORDER by ddjj.cuit, ddjj.anoddjj, ddjj.mesddjj, ddjj.secuenciapresentacion ASC";
		
		$resDDJJ = $dbh->prepare ( $sqlDDJJ );
		$resDDJJ->execute ();
		
		$arrayDDJJ = array ();
		while ( $rowDDJJ = $resDDJJ->fetch ( PDO::FETCH_LAZY ) ) {
			$indexPeriodo = $rowDDJJ ['anoddjj'] . $rowDDJJ ['mesddjj'];
			$arrayDDJJ [$rowDDJJ ['cuit']] [$indexPeriodo] = array (
					'remuneracion' => $rowDDJJ ['totremune'],
					'obligacion' => $rowDDJJ ['obligacion'],
			);
			$arrayDDJJ [$rowDDJJ ['cuit']] ['nombre'] = $rowDDJJ ['nombre'];
		}
		unset ( $resDDJJ );
	}
	
	
	// OBTENEMOS LOS ACUERDOS Y SE LOS SACO A LAS DDJJ
	$sqlAcuerdos = "SELECT
				 	 acuerdos.cuit,
					 acuerdos.anoacuerdo,
					 acuerdos.mesacuerdo
					FROM
					      detacuerdosospim acuerdos
					WHERE
					   ((acuerdos.anoacuerdo > " . $anoDesde . ") OR (acuerdos.anoacuerdo = " . $anoDesde . " AND acuerdos.mesacuerdo >= " . $mesDesde . "))
					GROUP by acuerdos.cuit, acuerdos.anoacuerdo, acuerdos.mesacuerdo
				    ORDER by acuerdos.cuit, acuerdos.anoacuerdo, acuerdos.mesacuerdo";
	$resAcuerdos = $dbh->prepare ( $sqlAcuerdos );
	$resAcuerdos->execute ();
	
	while ( $rowAcuerdos = $resAcuerdos->fetch ( PDO::FETCH_LAZY ) ) {
		$cuitAcuerdo = $rowAcuerdos ['cuit'];
		$indexPeriodo = $rowAcuerdos ['anoacuerdo'] . $rowAcuerdos ['mesacuerdo'];
		if (array_key_exists ( $cuitAcuerdo, $arrayDDJJ )) {
			$arrayDDJJperiodos = $arrayDDJJ[$cuitAcuerdo];
			if(array_key_exists ( $indexPeriodo, $arrayDDJJperiodos)) {
				unset($arrayDDJJ[$cuitAcuerdo][$indexPeriodo]);
			}
		}
	}
		
	// OBTENEMOS LOS PAGOS
	$sqlPagos = "SELECT
				  pagos.cuit,
			      pagos.anopago,
			      pagos.mespago,
				  e.nombre,
				  ROUND(SUM(IF(pagos.debitocredito = 'C', pagos.importe, pagos.importe * -1)),2) AS importepagos
				FROM
				      afiptransferencias pagos, empresas e
				WHERE
					  pagos.fechaprocesoafip >= '" . $fechadesde . "' AND
				      pagos.fechaprocesoafip <= '" . $fechageneracion . "' AND
				      pagos.concepto != 'REM' AND
				      ((pagos.anopago = " . $anoDesde . " AND pagos.mespago > " . $mesDesde . ") OR (pagos.anopago > " . $anoDesde . ")) AND
				      pagos.cuit = e.cuit 
				GROUP by pagos.cuit, pagos.anopago, pagos.mespago
				ORDER by pagos.cuit, pagos.anopago ASC, pagos.mespago ASC";
	$resPagos = $dbh->prepare ( $sqlPagos );
	$resPagos->execute ();
	
	$arrayPagos = array();
	while ( $rowPagos = $resPagos->fetch ( PDO::FETCH_LAZY ) ) {
		$cuit = $rowPagos ['cuit'];
		$indexPeriodo = $rowPagos ['anopago'] . $rowPagos ['mespago'];
		$arrayPagos [$rowPagos ['cuit']] [$indexPeriodo] =  array (
					'pagos' => $rowPagos ['importepagos'],
			);
	}
	unset ($resPagos);
	
	
	// OBTENEMOS LOS ACUERDOS Y SE LOS SACO A LOS PAGOS
	$resAcuerdos->execute ();
	while ( $rowAcuerdos = $resAcuerdos->fetch ( PDO::FETCH_LAZY ) ) {
		$cuitAcuerdo = $rowAcuerdos ['cuit'];
		$indexPeriodo = $rowAcuerdos ['anoacuerdo'] . $rowAcuerdos ['mesacuerdo'];
		if (array_key_exists ( $cuitAcuerdo, $arrayPagos )) {
			$arrayPagosperiodos = $arrayPagos[$cuitAcuerdo];
			if(array_key_exists ( $indexPeriodo, $arrayPagosperiodos)) {
				unset($arrayPagos[$cuitAcuerdo][$indexPeriodo]);
			}
		}
	}
	unset($resAcuerdos);
	
	
	//REDONDEO LOS QUE LA DIFERENCIA SE ENTRE -50 y +50
	foreach ( $arrayDDJJ as $cuit => $ddjjCuit ) {
		if (array_key_exists($cuit,$arrayPagos)) {
			foreach ( $ddjjCuit as $periodo => $ddjjperido ) {
				if ($periodo != 'nombre') {
					if (array_key_exists($periodo,$arrayPagos[$cuit])) {
						$pago = $arrayPagos[$cuit][$periodo]['pagos'];
						$obli = $arrayDDJJ[$cuit][$periodo]['obligacion'];
						$dife = $obli - $pago;
						if ($dife < 50 && $dife > -50) {
							$arrayDDJJ[$cuit][$periodo]['dife'] = 0;
						} else {
							$arrayDDJJ[$cuit][$periodo]['dife'] = $dife;
						}
					} else {
						$obli = $arrayDDJJ[$cuit][$periodo]['obligacion'];
						if ($obli < 50) {
							$arrayDDJJ[$cuit][$periodo]['dife'] = 0;
						} else {
							$arrayDDJJ[$cuit][$periodo]['dife'] = $obli;
						}
					}
				}
			}	
		} else {
			foreach ( $ddjjCuit as $periodo => $ddjjperido ) {
				if ($periodo != 'nombre') {
					$obli = $arrayDDJJ[$cuit][$periodo]['obligacion'];
					if ($obli < 50) {
						$arrayDDJJ[$cuit][$periodo]['dife'] = 0;
					} else {
						$arrayDDJJ[$cuit][$periodo]['dife'] = $obli;
					}
				}
			}
		}
	}
	
	
	
	
	// ARMO EL ESTADO CONTABLE
	$estadoContable = array ();
	reset($arrayDDJJ);
	foreach ( $arrayDDJJ as $cuit => $ddjjCuit ) {
		$totalRemuneracion = 0;
		$totalObligacion = 0;
		$totalDiferencia = 0;
		foreach ( $ddjjCuit as $ddjjperido ) {
			$totalRemuneracion += $ddjjperido ['remuneracion'];
			$totalObligacion += $ddjjperido ['obligacion'];
			$totalDiferencia += $ddjjperido ['dife'];
		}
		$estadoContable [$cuit] = array (
				'nombre' => $arrayDDJJ[$cuit]['nombre'],
				'totremune' => $totalRemuneracion,
				'totobligacion' => $totalObligacion,
				'totaldiferencia' => $totalDiferencia
		);
	}
	unset ( $arrayDDJJ );
	
	reset($arrayPagos);
	foreach ($arrayPagos as $cuit => $pagosCuit) {
		$totalPagos = 0;
		foreach ( $pagosCuit as $pagosperido ) {
			$totalPagos += $pagosperido ['pagos'];
		}
		if (array_key_exists ( $cuit, $estadoContable )) {
			$estadoContable [$cuit] += array (
					'totpagos' => $totalPagos
			);
		} 
	}
	unset ( $arrayPagos );
	
	$sqlInsertControl = "INSERT INTO estadocontablecontrol VALUE(DEFAULT,".$anoEstado.", ".$mesEstado.", 0, 0, 0, 0,'".$archivo_name_htm."',".$discoDesde.",".$discoHasta.",'".$fechadesde ."','".$fechageneracion ."','".$fecharegistro ."','".$usuarioregistro."')";
	$resInsertControl = $dbh->prepare ( $sqlInsertControl );
	$resInsertControl->execute ();
	$lastId = $dbh->lastInsertId();
	
	// Crea el objeto PHPExcel
	$objPHPExcel = new PHPExcel ();
	
	// Setea propiedades del documento
	$objPHPExcel->getProperties ()->setCreator ( $_SESSION ['usuario'] )->setLastModifiedBy ( $_SESSION ['usuario'] )->setTitle ( "Estado Contable" )->setSubject ( "Modulo de Sistemas" )->setDescription ( "Informe de Estado Contable a una Fecha." )->setKeywords ( "Estado Contable" )->setCategory ( "Informes del Sistema" );
	
	// Renombra la hoja
	$objPHPExcel->getActiveSheet ()->setTitle ( $fechageneracion );
	
	// Setea la hoja como activa, cuando se abra el Excel esta sera la primer hoja
	$objPHPExcel->setActiveSheetIndex ( 0 );
	
	// Setea encabezado y pie de pagina
	$objPHPExcel->getActiveSheet ()->getHeaderFooter ()->setOddHeader ( '&L&BU.S.I.M.R.A.&G&C&H&BEstado Contable al ' . $objPHPExcel->getActiveSheet ()->getTitle () . '&R&B' . $fechageneracion );
	$objPHPExcel->getActiveSheet ()->getHeaderFooter ()->setOddFooter ( '&L&R&BPagina &P de &N' );
	
	// Setea en configuracion de pagina orientacion y tamaño
	$objPHPExcel->getActiveSheet ()->getPageSetup ()->setOrientation ( PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE );
	$objPHPExcel->getActiveSheet ()->getPageSetup ()->setPaperSize ( PHPExcel_Worksheet_PageSetup::PAPERSIZE_LEGAL );
	
	// Setea en configuracion de pagina los margenes
	$objPHPExcel->getActiveSheet ()->getPageMargins ()->setTop ( 0.5 );
	$objPHPExcel->getActiveSheet ()->getPageMargins ()->setRight ( 0 );
	$objPHPExcel->getActiveSheet ()->getPageMargins ()->setLeft ( 0 );
	$objPHPExcel->getActiveSheet ()->getPageMargins ()->setBottom ( 0.5 );
	$objPHPExcel->getActiveSheet ()->getPageMargins ()->setHeader ( 0.25 );
	$objPHPExcel->getActiveSheet ()->getPageMargins ()->setFooter ( 0.25 );
	
	// Setea en configuracion de pagina centrado horizontal y vertical
	$objPHPExcel->getActiveSheet ()->getPageSetup ()->setHorizontalCentered ( true );
	$objPHPExcel->getActiveSheet ()->getPageSetup ()->setVerticalCentered ( false );
	
	// Setea en configuracion de pagina repetir filas en extremo superior
	$objPHPExcel->getActiveSheet ()->getPageSetup ()->setRowsToRepeatAtTopByStartAndEnd ( 1, 1 );
	
	// Setea tamaño de la columna y agrega datos a las celdas de titulos
	$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'A' )->setWidth ( 13 );
	$objPHPExcel->getActiveSheet ()->setCellValue ( 'A1', 'C.U.I.T.' );
	$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'B' )->setWidth ( 120 );
	$objPHPExcel->getActiveSheet ()->setCellValue ( 'B1', 'Razon Social' );
	$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'C' )->setWidth ( 20 );
	$objPHPExcel->getActiveSheet ()->setCellValue ( 'C1', 'Total DDJJ' );
	$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'D' )->setWidth ( 20 );
	$objPHPExcel->getActiveSheet ()->setCellValue ( 'D1', 'Obligacion' );
	$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'E' )->setWidth ( 20 );
	$objPHPExcel->getActiveSheet ()->setCellValue ( 'E1', 'Total Pagos' );
	$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'F' )->setWidth ( 20 );
	$objPHPExcel->getActiveSheet ()->setCellValue ( 'F1', 'Diferencia' );
	
	$fila = 1;
	$totRem = 0;
	$totObl = 0;
	$totPag = 0;
	$totDif = 0;
	foreach ( $estadoContable as $cuit => $estado ) {
		$totRem +=  $estado ['totremune'];
		$totObl +=  $estado ['totobligacion'];
		$totPag +=  $estado ['totpagos'];
		$totDif +=  $estado ['totaldiferencia'];
		$fila ++;
		// Agrega datos a las celdas de datos
		$objPHPExcel->getActiveSheet ()->setCellValue ( 'A' . $fila, $cuit );
		$objPHPExcel->getActiveSheet ()->getCellByColumnAndRow('A',$fila)->getHyperlink()->setUrl("../detalleEstadoContable.php?cuit=$cuit&id=$lastId");
		$objPHPExcel->getActiveSheet ()->setCellValue ( 'B' . $fila, utf8_encode($estado ['nombre']));
		$objPHPExcel->getActiveSheet ()->setCellValue ( 'C' . $fila, $estado ['totremune'] );
		$objPHPExcel->getActiveSheet ()->setCellValue ( 'D' . $fila, $estado ['totobligacion'] );
		$objPHPExcel->getActiveSheet ()->setCellValue ( 'E' . $fila, $estado ['totpagos'] );
		$objPHPExcel->getActiveSheet ()->setCellValue ( 'F' . $fila, $estado ['totaldiferencia'] );
	}
	$fila ++;
	$objPHPExcel->getActiveSheet ()->setCellValue ( 'C' . $fila, $totRem );
	$objPHPExcel->getActiveSheet ()->setCellValue ( 'D' . $fila, $totObl );
	$objPHPExcel->getActiveSheet ()->setCellValue ( 'E' . $fila, $totPag );
	$objPHPExcel->getActiveSheet ()->setCellValue ( 'F' . $fila, $totDif );
	
	// Setea fuente tipo y tamaño a la hoja activa
	$objPHPExcel->getDefaultStyle ()->getFont ()->setName ( 'Arial' );
	$objPHPExcel->getDefaultStyle ()->getFont ()->setSize ( 8 );
	
	// Setea negrita relleno y alineamiento horizontal a las celdas de titulos
	$objPHPExcel->getActiveSheet ()->getStyle ( 'A1:F1' )->getFont ()->setBold ( true );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'A1:F1' )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'A1:F1' )->getFill ()->getStartColor ()->setARGB ( 'FF808080' );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'A1:F1' )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
	
	// Setea tipo de dato y alineamiento horizontal a las celdas de datos
	$objPHPExcel->getActiveSheet ()->getStyle ( 'A2:A' . $fila )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_TEXT );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'A2:A' . $fila )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'B2:B' . $fila )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_TEXT );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'B2:B' . $fila )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_LEFT );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'C2:C' . $fila )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1 );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'C2:C' . $fila )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_RIGHT );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'D2:D' . $fila )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1 );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'D2:D' . $fila )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_RIGHT );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'E2:E' . $fila )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1 );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'E2:E' . $fila )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_RIGHT );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'F2:F' . $fila )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1 );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'F2:F' . $fila )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_RIGHT );
	
	$sqlUdapteControl = "UPDATE estadocontablecontrol SET remuneracion = $totRem, obligacion = $totObl, pagos = $totPag, diferencia = $totDif WHERE id = $lastId";
	$resUdapteControl = $dbh->prepare ( $sqlUdapteControl );
	$resUdapteControl->execute ();
	
	// Guarda Archivo en Formato HTML
	$objWriterXtm = PHPExcel_IOFactory::createWriter($objPHPExcel, 'HTML');
	$objWriterXtm->save ( $archivo_name_htm ); 
	
	for( $i=2; $i<$fila; $i++ ) {
		$objPHPExcel->getActiveSheet ()->getCellByColumnAndRow('A',$i)->setHyperlink();
	}
	
	// Guarda Archivo en Formato Excel 2003
	$objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
	$objWriter->save ( $archivo_name_xls );
	
	
	$dbh->commit ();
	
	$pagina = "estadoContable.php?ok=1";
	Header("Location: $pagina");

} catch ( PDOException $e ) {
	echo $e->getMessage ();
	$dbh->rollback ();
}
?>