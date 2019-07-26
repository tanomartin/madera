<?php
$libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
require_once ($libPath . "phpExcel/Classes/PHPExcel.php");
set_time_limit ( 0 );
ini_set ( 'memory_limit', '-1' );

$fechahasta = $_POST['fechahasta'];
$fechadesde = $_POST['fechadesde'];
$fechainforme=date("dmY Hms");
$maquina = $_SERVER ['SERVER_NAME'];

if (strcmp ( "localhost", $maquina ) == 0) 
	$archivo_name_xls = "Diferencia Contable del ".$fechadesde." al ".$fechahasta." (".$fechainforme.").xls";
else 
	$archivo_name_xls = "/home/sistemas/Documentos/Repositorio/FFFF1208311301SYS/Diferencia Contable del ".$fechadesde." al ".$fechahasta." (".$fechainforme.").xls";

$fechadesde=substr($fechadesde, 6, 4)."-".substr($fechadesde, 3, 2)."-".substr($fechadesde, 0, 2);
$fechahasta=substr($fechahasta, 6, 4)."-".substr($fechahasta, 3, 2)."-".substr($fechahasta, 0, 2);


try {	
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$usuario = $_SESSION['usuario'];
	$pass = $_SESSION['clave'];
	$dbh = new PDO ( "mysql:host=$hostname;dbname=$dbname", $usuario, $pass );
	$dbh->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$dbh->beginTransaction ();
	
	// Totamos el primer y el ultimo disco para las ddjj
	$sqlDiscoDesde = "SELECT nrodisco FROM nominasddjj 
								where fechaarchivoafip >= '" . $fechadesde . "' and 
									  fechaarchivoafip < '" . $fechahasta . "' LIMIT 1";
	$resDiscoDesde = $dbh->query ( $sqlDiscoDesde );
	$rowDiscoDesde = $resDiscoDesde->fetch ();
	$discoDesde = $rowDiscoDesde ['nrodisco'];
	
	$sqlDiscoHasta = "SELECT nrodisco FROM nominasddjj 
								where fechaarchivoafip >= '" . $fechadesde . "' and 
									  fechaarchivoafip < '" . $fechahasta . "' order by nrodisco DESC LIMIT 1";
	$resDiscoHasta = $dbh->query ( $sqlDiscoHasta );
	$rowDiscoHasta = $resDiscoHasta->fetch ();
	$discoHasta = $rowDiscoHasta ['nrodisco'];
	
	$anoDesde = substr ( $fechadesde, 0, 4 );
	$mesDesde = substr ( $fechadesde, 5, 2 );
	
	// OBTENEMOS LAS DDJJ
	$anolimite = 2009;
	$meslimite = 3;
	
	$arrayEmpresas = array();
	$sqlEmpBajaDelega = "SELECT e.cuit, e.nombre, j.codidelega
							FROM empresasdebaja e, jurisdiccion j
							WHERE e.cuit = j.cuit
							ORDER BY cuit, disgdinero ASC";
	$resEmpBajaDelega = $dbh->prepare ($sqlEmpBajaDelega);
	$resEmpBajaDelega->execute ();
	while ( $rowEmpBajaDelega = $resEmpBajaDelega->fetch (PDO::FETCH_LAZY)) {
		$arrayEmpresas[$rowEmpBajaDelega['cuit']] = array("nombre" => $rowEmpBajaDelega['nombre'], "delega" => $rowEmpBajaDelega['codidelega'], "estado" => "DE BAJA");
	}
	
	$sqlEmpAltaDelega = "SELECT e.cuit, e.nombre, j.codidelega
							FROM empresas e, jurisdiccion j 
							WHERE e.cuit = j.cuit
							ORDER BY cuit, disgdinero ASC";
	$resEmpAltaDelega = $dbh->prepare ($sqlEmpAltaDelega);
	$resEmpAltaDelega->execute ();
	while ( $rowEmpAltaDelega = $resEmpAltaDelega->fetch (PDO::FETCH_LAZY)) {
		$arrayEmpresas[$rowEmpAltaDelega['cuit']] = array("nombre" => $rowEmpAltaDelega['nombre'], "delega" => $rowEmpAltaDelega['codidelega'], "estado" => "ACTIVA");
	}

	$arrayDDJJ = array ();
	if ($anoDesde < $anolimite or ($anolimite == $anoDesde and $meslimite < $mesDesde)) {
		$sqlDDJJPrimera = "SELECT
				  ddjj.cuit,
				  ddjj.anoddjj,
				  ddjj.mesddjj,
				  ddjj.secuenciapresentacion,
				  ROUND(SUM(ddjj.remundeclarada),2) AS totremune,
				  ROUND(SUM(IF(ddjj.remundeclarada < 1001, ddjj.remundeclarada * 0.081, ddjj.remundeclarada * 0.0765)),2) AS obligacion
				FROM
				      afipddjj ddjj
				WHERE
					  ddjj.nrodisco >= " . $discoDesde . " AND
				      ddjj.nrodisco <= " . $discoHasta . " AND
				      ((ddjj.anoddjj < " . $anolimite . ") OR 
					   (ddjj.anoddjj = " . $anolimite . " AND ddjj.mesddjj < " . $meslimite . "))
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
				  ddjj.anoddjj,
				  ddjj.mesddjj,
				  ddjj.secuenciapresentacion,
				  ROUND(SUM(ddjj.remundeclarada),2) AS totremune,
				  ROUND(SUM(IF(ddjj.remundeclarada < 2401, ddjj.remundeclarada * 0.081, ddjj.remundeclarada * 0.0765)),2) AS obligacion
				FROM
				      afipddjj ddjj
				WHERE
					  ddjj.nrodisco >= " . $discoDesde . " AND
				      ddjj.nrodisco <= " . $discoHasta . " AND
				      ((ddjj.anoddjj > " . $anolimite . ") OR
	             	   (ddjj.anoddjj = " . $anolimite . " AND ddjj.mesddjj >= " . $meslimite . "))
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
				  ddjj.anoddjj,
				  ddjj.mesddjj,
				  ddjj.secuenciapresentacion,
				  ROUND(SUM(ddjj.remundeclarada),2) AS totremune,
				  ROUND(SUM(IF(ddjj.remundeclarada < 2401, ddjj.remundeclarada * 0.081, ddjj.remundeclarada * 0.0765)),2) AS obligacion
				FROM
				      afipddjj ddjj
				WHERE
					  ddjj.nrodisco >= " . $discoDesde . " AND
				      ddjj.nrodisco <= " . $discoHasta . "
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
	
	// OBTENEMOS LOS PAGOS
	$sqlPagos = "SELECT
				  pagos.cuit,
			      pagos.anopago,
			      pagos.mespago,
				  ROUND(SUM(IF(pagos.debitocredito = 'C', pagos.importe, pagos.importe * -1)),2) AS importepagos
				FROM
				      afiptransferencias pagos
				WHERE
					  pagos.fechaprocesoafip >= '" . $fechadesde . "' AND
				      pagos.fechaprocesoafip <= '" . $fechahasta . "' AND
				      pagos.concepto != 'REM'
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
		}
		
		if (!isset($arrayEmpresas[$cuit])) {
			$arrayEmpresas[$cuit]['delega'] = "-";
			$arrayEmpresas[$cuit]['nombre'] = "-";
			$arrayEmpresas[$cuit]['estado'] = "-";
		}
		
		$estadoContable [$cuit] = array (
				'delega' => $arrayEmpresas[$cuit]['delega'],
				'nombre' => $arrayEmpresas[$cuit]['nombre'],
				'estado' => $arrayEmpresas[$cuit]['estado'],
				'totremune' => $totalRemuneracion,
				'totobligacion' => $totalObligacion
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
	
	// Crea el objeto PHPExcel
	$objPHPExcel = new PHPExcel ();
	
	// Setea propiedades del documento
	$objPHPExcel->getProperties ()->setCreator ( $_SESSION ['usuario'] )->setLastModifiedBy ( $_SESSION ['usuario'] )->setTitle ( "Estado Contable" )->setSubject ( "Modulo de Sistemas" )->setDescription ( "Informe de Estado Contable a una Fecha." )->setKeywords ( "Estado Contable" )->setCategory ( "Informes del Sistema" );
	
	// Renombra la hoja
	$objPHPExcel->getActiveSheet ()->setTitle ( $fechahasta );
	
	// Setea la hoja como activa, cuando se abra el Excel esta sera la primer hoja
	$objPHPExcel->setActiveSheetIndex ( 0 );
	
	// Setea encabezado y pie de pagina
	$objPHPExcel->getActiveSheet ()->getHeaderFooter ()->setOddHeader ( '&L&BU.S.I.M.R.A.&G&C&H&BEstado Contable al ' . $objPHPExcel->getActiveSheet ()->getTitle () . '&R&B' . $fechahasta );
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
	$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'B' )->setWidth ( 13 );
	$objPHPExcel->getActiveSheet ()->setCellValue ( 'B1', 'COD. DEL.' );
	$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'C' )->setWidth ( 100 );
	$objPHPExcel->getActiveSheet ()->setCellValue ( 'C1', 'Razon Social' );
	$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'D' )->setWidth ( 20 );
	$objPHPExcel->getActiveSheet ()->setCellValue ( 'D1', 'Estado' );
	$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'E' )->setWidth ( 20 );
	$objPHPExcel->getActiveSheet ()->setCellValue ( 'E1', 'Total DDJJ' );
	$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'F' )->setWidth ( 20 );
	$objPHPExcel->getActiveSheet ()->setCellValue ( 'F1', 'Obligacion' );
	$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'G' )->setWidth ( 20 );
	$objPHPExcel->getActiveSheet ()->setCellValue ( 'G1', 'Total Pagos' );
	$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'H' )->setWidth ( 20 );
	$objPHPExcel->getActiveSheet ()->setCellValue ( 'H1', 'Debito/Credito' );
	
	$fila = 1;
	$totRem = 0;
	$totObl = 0;
	$totPag = 0;
	$totDif = 0;
	$totInc = 0;
	foreach ( $estadoContable as $cuit => $estado ) {
		$fila ++;
		// Agrega datos a las celdas de datos
		$objPHPExcel->getActiveSheet ()->setCellValue ( 'A' . $fila, $cuit );
		$objPHPExcel->getActiveSheet ()->setCellValue ( 'B' . $fila, $estado['delega'] );
		$objPHPExcel->getActiveSheet ()->setCellValue ( 'C' . $fila, utf8_encode($estado ['nombre']));
		$objPHPExcel->getActiveSheet ()->setCellValue ( 'D' . $fila, utf8_encode($estado ['estado']));
		$objPHPExcel->getActiveSheet ()->setCellValue ( 'E' . $fila, $estado ['totremune'] );
		$objPHPExcel->getActiveSheet ()->setCellValue ( 'F' . $fila, $estado ['totobligacion'] );
		if (!isset($estado ['totpagos'])) {
			$estado ['totpagos'] = 0;
		}
		$objPHPExcel->getActiveSheet ()->setCellValue ( 'G' . $fila, $estado ['totpagos'] );
		$diferencia = $estado ['totobligacion'] - $estado ['totpagos'];
		$objPHPExcel->getActiveSheet ()->setCellValue ( 'H' . $fila, $diferencia);
	}
	
	// Setea fuente tipo y tamaño a la hoja activa
	$objPHPExcel->getDefaultStyle ()->getFont ()->setName ( 'Arial' );
	$objPHPExcel->getDefaultStyle ()->getFont ()->setSize ( 8 );
	
	// Setea negrita relleno y alineamiento horizontal a las celdas de titulos
	$objPHPExcel->getActiveSheet ()->getStyle ( 'A1:H1' )->getFont ()->setBold ( true );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'A1:H1' )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'A1:H1' )->getFill ()->getStartColor ()->setARGB ( 'FF808080' );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'A1:H1' )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
	
	// Setea tipo de dato y alineamiento horizontal a las celdas de datos
	$objPHPExcel->getActiveSheet ()->getStyle ( 'A2:A' . $fila )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_TEXT );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'A2:A' . $fila )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'B2:B' . $fila )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_TEXT );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'B2:B' . $fila )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_LEFT );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'C2:C' . $fila )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_TEXT );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'C2:C' . $fila )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_LEFT );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'D2:D' . $fila )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_TEXT );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'D2:D' . $fila )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_LEFT );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'E2:E' . $fila )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1 );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'E2:E' . $fila )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_RIGHT );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'F2:F' . $fila )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1 );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'F2:F' . $fila )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_RIGHT );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'G2:G' . $fila )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1 );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'G2:G' . $fila )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_RIGHT );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'H2:H' . $fila )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1 );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'H2:H' . $fila )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_RIGHT );
	
	// Guarda Archivo en Formato Excel 2003
	$objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
	$objWriter->save ( $archivo_name_xls );
	
	
	$dbh->commit ();
	
	$pagina = "moduloDiferencia.php?ok=1";
	Header("Location: $pagina");

} catch ( PDOException $e ) {
	echo $e->getMessage ();
	$dbh->rollback ();
}
?>