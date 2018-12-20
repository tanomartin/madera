<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
require_once ($libPath . "phpExcel/Classes/PHPExcel.php");
set_time_limit ( 0 );
$maquina = $_SERVER['SERVER_NAME'];
$fechagenera = date("d-m-Y");
$fechainforme = date("d/m/Y");
$delegacion = $_POST['delegacion'];
if(strcmp("localhost",$maquina)==0)
	$archivo_name_xls = "Total de Beneficiarios por Localidad - Delegacion ".$delegacion." al ".$fechagenera.".xls";
else
	$archivo_name_xls = "/home/sistemas/Documentos/Repositorio/Afiliaciones/Total de Beneficiarios por Localidad - Delegacion ".$delegacion." al ".$fechagenera.".xls";

try {	
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$usuario = $_SESSION['usuario'];
	$pass = $_SESSION['clave'];
	$dbh = new PDO ( "mysql:host=$hostname;dbname=$dbname", $usuario, $pass );
	$dbh->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$dbh->beginTransaction ();
	
	$sqlDelegacion = "SELECT codidelega, nombre FROM delegaciones WHERE codidelega = '" . $delegacion . "'";
	$resDelegacion = $dbh->query ( $sqlDelegacion );
	$rowDelegacion = $resDelegacion->fetch ();
	$nombreDelegacion = $rowDelegacion['codidelega'].' - '.$rowDelegacion['nombre'];

	$arrayLocalidades = array ();
	$sqlTitulares = "SELECT l.nomlocali, count(t.nroafiliado) AS totaltitulares FROM titulares t, localidades l
						WHERE t.codidelega = '" . $delegacion . "' AND t.codlocali = l.codlocali GROUP BY l.nomlocali";
	$resTitulares = $dbh->query($sqlTitulares);
	while($rowTitulares = $resTitulares->fetch ()) {
		$arrayLocalidades[$rowTitulares ['nomlocali']] ['totaltitulares'] = $rowTitulares ['totaltitulares'];
		$arrayLocalidades[$rowTitulares ['nomlocali']] ['totalfamiliares'] = 0;
	}

	$sqlFamiliares = "SELECT l.nomlocali, count(f.nroafiliado) AS totalfamiliares FROM titulares t, localidades l, familiares f
						WHERE t.codidelega = '" . $delegacion . "' AND t.codlocali = l.codlocali AND t.nroafiliado = f.nroafiliado GROUP BY l.nomlocali";
	$resFamiliares = $dbh->query($sqlFamiliares);
	while($rowFamiliares = $resFamiliares->fetch ()) {
		$arrayLocalidades[$rowFamiliares ['nomlocali']] ['totalfamiliares'] = $rowFamiliares ['totalfamiliares'];
	}
	
	//echo("Localidades <br>");
	//var_dump($arrayLocalidades);
	//echo("<br><br>");

	// Crea el objeto PHPExcel
	$objPHPExcel = new PHPExcel ();
	
	// Setea propiedades del documento
	$objPHPExcel->getProperties ()->setCreator ( $_SESSION ['usuario'] )->setLastModifiedBy ( $_SESSION ['usuario'] )->setTitle ( "Beneficiarios Localidades Delegacion" )->setSubject ( "Modulo de Sistemas" )->setDescription ( "Total de Beneficiarios por Localidades por Delegacion." )->setKeywords ( "Localidades Delegacion" )->setCategory ( "Informes del Sistema" );
	
	// Renombra la hoja
	$objPHPExcel->getActiveSheet ()->setTitle ( $nombreDelegacion );
	
	// Setea la hoja como activa, cuando se abra el Excel esta sera la primer hoja
	$objPHPExcel->setActiveSheetIndex ( 0 );
	
	// Setea encabezado y pie de pagina
	$objPHPExcel->getActiveSheet ()->getHeaderFooter ()->setOddHeader ( '&L&BO.S.P.I.M.&G&C&H&BTotal de Beneficiarios por Localidad - Delegacion ' . $objPHPExcel->getActiveSheet ()->getTitle () . '&R&B' . $fechainforme );
	$objPHPExcel->getActiveSheet ()->getHeaderFooter ()->setOddFooter ( '&L&R&BPagina &P de &N' );
	
	// Setea en configuracion de pagina orientacion y tamaño
	$objPHPExcel->getActiveSheet ()->getPageSetup ()->setOrientation ( PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT );
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
	$objPHPExcel->getActiveSheet ()->setShowGridlines(true);
	$objPHPExcel->getActiveSheet ()->setPrintGridlines(true);
	
	// Setea tamaño de la columna y agrega datos a las celdas de titulos
	$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'A' )->setWidth ( 60 );
	$objPHPExcel->getActiveSheet ()->setCellValue ( 'A1', 'Localidad' );
	$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'B' )->setWidth ( 19 );
	$objPHPExcel->getActiveSheet ()->setCellValue ( 'B1', 'Total Titulares' );
	$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'C' )->setWidth ( 19 );
	$objPHPExcel->getActiveSheet ()->setCellValue ( 'C1', 'Total Familiares' );
	$objPHPExcel->getActiveSheet ()->getColumnDimension ( 'D' )->setWidth ( 19 );
	$objPHPExcel->getActiveSheet ()->setCellValue ( 'D1', 'Total Beneficiarios' );
	
	$fila = 1;
	foreach ( $arrayLocalidades as $locali => $totales ) {
		$fila ++;
		// Agrega datos a las celdas de datos
		$objPHPExcel->getActiveSheet ()->setCellValue ( 'A' . $fila, utf8_encode($locali));
		$objPHPExcel->getActiveSheet ()->setCellValue ( 'B' . $fila, $totales ['totaltitulares']);
		$objPHPExcel->getActiveSheet ()->setCellValue ( 'C' . $fila, $totales ['totalfamiliares']);
		$totalbeneficiarios = $totales ['totaltitulares'] + $totales ['totalfamiliares'];
		$objPHPExcel->getActiveSheet ()->setCellValue ( 'D' . $fila, $totalbeneficiarios);
	}

	$objPHPExcel->getActiveSheet ()->setCellValue ( 'A' . ($fila+1), 'TOTAL GENERAL' );
	$objPHPExcel->getActiveSheet ()->setCellValue ( 'B' . ($fila+1), '=SUM(B2:B'.$fila.')');
	$objPHPExcel->getActiveSheet ()->setCellValue ( 'C' . ($fila+1), '=SUM(C2:C'.$fila.')');
	$objPHPExcel->getActiveSheet ()->setCellValue ( 'D' . ($fila+1), '=SUM(D2:D'.$fila.')');


	// Setea fuente tipo y tamaño a la hoja activa
	$objPHPExcel->getDefaultStyle ()->getFont ()->setName ( 'Arial' );
	$objPHPExcel->getDefaultStyle ()->getFont ()->setSize ( 8 );
	
	// Setea negrita relleno y alineamiento horizontal a las celdas de titulos
	$objPHPExcel->getActiveSheet ()->getStyle ( 'A1:D1' )->getFont ()->setBold ( true );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'A1:D1' )->getFill ()->setFillType ( PHPExcel_Style_Fill::FILL_SOLID );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'A1:D1' )->getFill ()->getStartColor ()->setARGB ( 'FF808080' );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'A1:D1' )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );

	// Setea tipo de dato y alineamiento horizontal a las celdas de datos
	$objPHPExcel->getActiveSheet ()->getStyle ( 'A2:A' . $fila )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_TEXT );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'A2:A' . $fila )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_CENTER );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'B2:B' . $fila )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_NUMBER );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'B2:B' . $fila )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_RIGHT );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'C2:C' . $fila )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_NUMBER );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'C2:C' . $fila )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_RIGHT );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'D2:D' . $fila )->getNumberFormat ()->setFormatCode ( PHPExcel_Style_NumberFormat::FORMAT_NUMBER );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'D2:D' . $fila )->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_RIGHT );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'A' . ($fila+1))->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_RIGHT );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'B' . ($fila+1))->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_RIGHT );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'C' . ($fila+1))->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_RIGHT );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'D' . ($fila+1))->getAlignment ()->setHorizontal ( PHPExcel_Style_Alignment::HORIZONTAL_RIGHT );
	$objPHPExcel->getActiveSheet ()->getStyle ( 'A'.($fila+1).':D'.($fila+1) )->getFont ()->setBold ( true );
	// Guarda Archivo en Formato Excel 2003
	$objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
	$objWriter->save ( $archivo_name_xls );	

	$dbh->commit ();

	$pagina = "totalBeneficiariosLocalidadDelegacion.php?error=0&delegacion=$delegacion";
	Header("Location: $pagina");

} catch ( PDOException $e ) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}
?>