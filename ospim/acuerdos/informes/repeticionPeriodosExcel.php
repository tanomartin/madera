<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
require_once($libPath."phpExcel/Classes/PHPExcel.php");

$maquina = $_SERVER['SERVER_NAME'];
$fechacargada=$_POST['fechaarchivo'];
$fechagenera=date("d/m/Y");
$hora=date("his");

if(strcmp("localhost",$maquina)==0)
	$archivo_name="PeriodosRepetidosAl-".substr($fechagenera, 0, 2)."-".substr($fechagenera, 3, 2)."-".substr($fechagenera, 6, 4)." (".$hora.").xls";
else
	$archivo_name="/home/sistemas/Documentos/Repositorio/FFFF1208311301SYS/PeriodosRepetidosAl-".substr($fechagenera, 0, 2)."-".substr($fechagenera, 3, 2)."-".substr($fechagenera, 6, 4)." (".$hora.").xls";

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
								 ->setTitle("Empresas con acuerdos que repiten periodos")
								 ->setSubject("Modulo de Acuerdos")
								 ->setDescription("Informe de Empresas con acuerdos que repiten periodos")
								 ->setCategory("Informes del Sistema de Acuerdos");
	// Renombra la hoja
	$objPHPExcel->getActiveSheet()->setTitle('PeriodosRepetidosAl'.substr($fechagenera, 0, 2).'-'.substr($fechagenera, 3, 2).'-'.substr($fechagenera, 6, 4));

	// Setea la hoja como activa, cuando se abra el Excel esta sera la primer hoja
	$objPHPExcel->setActiveSheetIndex(0);

	// Setea encabezado y pie de pagina
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&BO.S.P.I.M.&G&C&H&BPeriodos Repetidos - '.$objPHPExcel->getActiveSheet()->getTitle().'&R&B'.$fechagenera);
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&R&BPagina &P de &N');

	// Setea en configuracion de pagina orientacion y tamaño
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

	// Setea tamaño de la columna y agrega datos a las celdas de titulos
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
	$objPHPExcel->getActiveSheet()->setCellValue('A1', 'C.U.I.T.');
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(8);
	$objPHPExcel->getActiveSheet()->setCellValue('B1', '1er Acuerdo');
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(8);
	$objPHPExcel->getActiveSheet()->setCellValue('C1', '2do Acuerdo');
	$fila=1;	

	$sqlAcuerdos="SELECT c.cuit, d.nroacuerdo as acu1, d1.nroacuerdo as acu2 FROM detacuerdosospim d, detacuerdosospim d1, cabacuerdosospim c
					where d.mesacuerdo = d1.mesacuerdo
					and d.anoacuerdo = d1.anoacuerdo
					and d.cuit = d1.cuit
					and d.nroacuerdo != d1.nroacuerdo
					and d.cuit = c.cuit
					and d.nroacuerdo = c.nroacuerdo
					and c.tipoacuerdo != 3
					and c.estadoacuerdo = 1
					group by c.cuit, c.nroacuerdo
					order by c.cuit, d.mesacuerdo, d.anoacuerdo;";
	$resultAcuerdos = $dbh->query($sqlAcuerdos);
	if ($resultAcuerdos){
		foreach ($resultAcuerdos as $acuerdos){
			$fila++;
			$cuit = $acuerdos[cuit];
			$acuerdo1 = $acuerdos[acu1];
			$acuerdo2 = $acuerdos[acu2];
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$fila, $cuit);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$fila, $acuerdo1);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$fila, $acuerdo2);
		}
	}

	// Setea fuente tipo y tamaño a la hoja activa
	$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
	$objPHPExcel->getDefaultStyle()->getFont()->setSize(8); 

	// Setea negrita relleno y alineamiento horizontal a las celdas de titulos
	$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFill()->getStartColor()->setARGB('FF808080');
	$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	// Setea tipo de dato y alineamiento horizontal a las celdas de datos
	$objPHPExcel->getActiveSheet()->getStyle('A2:A'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	$objPHPExcel->getActiveSheet()->getStyle('A2:A'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('B2:B'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	$objPHPExcel->getActiveSheet()->getStyle('B2:B'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('C2:C'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	$objPHPExcel->getActiveSheet()->getStyle('C2:C'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


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