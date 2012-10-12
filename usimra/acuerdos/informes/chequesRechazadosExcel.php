<?php 
include($_SERVER['DOCUMENT_ROOT']."/usimra/lib/controlSession.php"); 
include($_SERVER['DOCUMENT_ROOT']."/usimra/lib/fechas.php");
require_once ($_SERVER['DOCUMENT_ROOT']."/usimra/lib/phpExcel/Classes/PHPExcel.php");

$maquina = $_SERVER['SERVER_NAME'];
$fechainforme=date("d-m-Y Hms");
$fechagenera=date("d/m/Y");

if(strcmp("localhost",$maquina)==0)
	$archivo_name="Cheques Rechazados Al ".$fechainforme.".xls";
else
	$archivo_name="/home/sistemas/Documentos/Repositorio/GGGG1210121610SYS/Cheques Rechazados Al ".$fechainforme.".xls";

//conexion y creacion de transaccion.
try{
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	// Crea el objeto PHPExcel
	$objPHPExcel = new PHPExcel();

	// Setea propiedades del documento
	$objPHPExcel->getProperties()->setCreator($_SESSION['usuario'])
								 ->setLastModifiedBy($_SESSION['usuario'])
								 ->setTitle("Cheques Rechazados")
								 ->setSubject("Modulo de Acuerdos")
								 ->setDescription("Informe de Cheques Rechazados a una Fecha")
								 ->setKeywords("cheques rechazados informe acuerdos")
								 ->setCategory("Informes del Sistema de Acuerdos");
	// Renombra la hoja
	$objPHPExcel->getActiveSheet()->setTitle($fechainforme);

	// Setea la hoja como activa, cuando se abra el Excel esta sera la primer hoja
	$objPHPExcel->setActiveSheetIndex(0);

	// Setea encabezado y pie de pagina
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&BU.S.I.M.R.A.&G&C&H&BCheques Rechazados Al '.$objPHPExcel->getActiveSheet()->getTitle().'&R&B'.$fechagenera);
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&R&BPagina &P de &N');

	// Setea en configuracion de pagina orientacion y tama�o
	$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LEGAL);	
	
	// Setea en configuracion de pagina los margenes
	$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.5);
	$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0);
	$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0);
	$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.5);
	$objPHPExcel->getActiveSheet()->getPageMargins()->setHeader(0.25);
	$objPHPExcel->getActiveSheet()->getPageMargins()->setFooter(0.25);
	
	// Setea en configuracion de pagina centrado horizontal y vertical
	$objPHPExcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setVerticalCentered(false);

	// Setea en configuracion de pagina lineas de division (OJO: NO ANDA)
	//$objPHPExcel->getActiveSheet()->getPageSetup()->setShowGridlines(true);

	// Setea en configuracion de pagina repetir filas en extremo superior
	$objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 1);

	// Setea tama�o de la columna y agrega datos a las celdas de titulos
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
	$objPHPExcel->getActiveSheet()->setCellValue('A1', 'C.U.I.T.');
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(8);
	$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Acuerdo');
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(6);
	$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Cuota');
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
	$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Monto Cuota');
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(11);
	$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Vto. Cuota');
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(11);
	$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Cheque Nro.');
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(13);
	$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Cheque Banco');
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(13);
	$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Cheque Fecha');
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(33);
	$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Observaciones');

	$fila=1;
		
	$sqlCuotas="SELECT * FROM cuoacuerdosusimra WHERE tipocancelacion = 10 order by cuit, nroacuerdo, nrocuota";
	$resultCuotas = $dbh->query($sqlCuotas);
	if ($resultCuotas){
		foreach ($resultCuotas as $cuotas){
			$fila++;
			// Agrega datos a las celdas de datos
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$fila, $cuotas[cuit]);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$fila, $cuotas[nroacuerdo]);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$fila, $cuotas[nrocuota]);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$fila, $cuotas[montocuota]);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$fila, invertirFecha($cuotas[fechacuota]));
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$fila, $cuotas[chequenro]);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$fila, $cuotas[chequebanco]);
			if($cuotas[chequefecha]!='0000-00-00')
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$fila, invertirFecha($cuotas[chequefecha]));
			else
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$fila, '');
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$fila, $cuotas[observaciones]);
		}
	}

	// Setea fuente tipo y tama�o a la hoja activa
	$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
	$objPHPExcel->getDefaultStyle()->getFont()->setSize(8); 

	// Setea negrita relleno y alineamiento horizontal a las celdas de titulos
	$objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFill()->getStartColor()->setARGB('FF808080');
	$objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	// Setea tipo de dato y alineamiento horizontal a las celdas de datos
	$objPHPExcel->getActiveSheet()->getStyle('A2:A'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	$objPHPExcel->getActiveSheet()->getStyle('A2:A'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('B2:B'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
	$objPHPExcel->getActiveSheet()->getStyle('B2:B'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('C2:C'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
	$objPHPExcel->getActiveSheet()->getStyle('C2:C'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('D2:D'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$objPHPExcel->getActiveSheet()->getStyle('D2:D'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('E2:E'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
	$objPHPExcel->getActiveSheet()->getStyle('E2:E'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('F2:F'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	$objPHPExcel->getActiveSheet()->getStyle('F2:F'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('G2:G'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	$objPHPExcel->getActiveSheet()->getStyle('G2:G'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('H2:H'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
	$objPHPExcel->getActiveSheet()->getStyle('H2:H'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('I2:I'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	$objPHPExcel->getActiveSheet()->getStyle('I2:I'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('I2:I'.$fila)->getAlignment()->setWrapText(true);

	// Guarda Archivo en Formato Excel 2003
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save($archivo_name);

	$dbh->commit();
	$pagina = "moduloInformes.php";
	Header("Location: $pagina");

}
catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}
?>