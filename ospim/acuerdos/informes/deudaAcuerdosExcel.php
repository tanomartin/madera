<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
require_once($libPath."phpExcel/Classes/PHPExcel.php");

$maquina = $_SERVER['SERVER_NAME'];
$fechacargada=$_POST['fechaarchivo'];
$fechainicio="19960101";
$fechafin=substr($fechacargada, 6, 4).substr($fechacargada, 3, 2).substr($fechacargada, 0, 2);
$fechacancelacion=substr($fechacargada, 6, 4)."-".substr($fechacargada, 3, 2)."-".substr($fechacargada, 0, 2);
$fechagenera=date("d/m/Y");

if(strcmp("localhost",$maquina)==0)
	$archivo_name="Ve que pedis-".substr($fechacargada, 0, 2)."-".substr($fechacargada, 3, 2)."-".substr($fechacargada, 6, 4)."!!!.xls";
else
	$archivo_name="/home/sistemas/Documentos/Repositorio/FFFF1208311301SYS/Ve que pedis-".substr($fechacargada, 0, 2)."-".substr($fechacargada, 3, 2)."-".substr($fechacargada, 6, 4)."!!!.xls";

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
								 ->setTitle("Deuda de Acuerdos por Año")
								 ->setSubject("Modulo de Acuerdos")
								 ->setDescription("Informe de Estado Financiero de los Acuerdos.")
								 ->setKeywords("acuerdos deuda estado informe")
								 ->setCategory("Informes del Sistema de Acuerdos");
	// Renombra la hoja
	$objPHPExcel->getActiveSheet()->setTitle('Deuda al '.substr($fechacargada, 0, 2).'-'.substr($fechacargada, 3, 2).'-'.substr($fechacargada, 6, 4));

	// Setea la hoja como activa, cuando se abra el Excel esta sera la primer hoja
	$objPHPExcel->setActiveSheetIndex(0);

	// Setea encabezado y pie de pagina
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&BO.S.P.I.M.&G&C&H&BAcuerdos por Anio - '.$objPHPExcel->getActiveSheet()->getTitle().'&R&B'.$fechagenera);
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
	$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Acuerdo');
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(13);
	$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Fecha');
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(7);
	$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Estado');
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(18);
	$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Tipo Acuerdo');
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(6);
	$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Anio');
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(13);
	$objPHPExcel->getActiveSheet()->setCellValue('G1', 'A Pagar');
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(13);
	$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Pagado');
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(13);
	$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Cheques En Cartera');
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(14);
	$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Cheques Presentados');
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(13);
	$objPHPExcel->getActiveSheet()->setCellValue('K1', 'Adeudado');

	$fila=1;	

	$sqlAcuerdos="SELECT * FROM cabacuerdosospim c, empresas e WHERE c.estadoacuerdo = 1 and c.tipoacuerdo = 1 and c.fechaacuerdo >= $fechainicio and c.fechaacuerdo <= $fechafin and c.cuit = e.cuit order by c.fechaacuerdo, c.cuit, c.nroacuerdo";
	$resultAcuerdos = $dbh->query($sqlAcuerdos);
	if ($resultAcuerdos){
		foreach ($resultAcuerdos as $acuerdos){
			$fila++;
			$cuit = $acuerdos[cuit];
			$acuerdo = $acuerdos[nroacuerdo];
			$fechaacuerdo = invertirFecha($acuerdos[fechaacuerdo]);
			$anioacuerdo = substr($fechaacuerdo, 6, 4);
			$montocuota = 0.00;
			$montopagada = 0.00;
			$montocheque = 0.00;
			$montodepositado = 0.00;

			$sqlCuotas="SELECT * FROM cuoacuerdosospim where cuit = $cuit and nroacuerdo = $acuerdo";
			$resultCuotas = $dbh->query($sqlCuotas);
			if ($resultCuotas){
				set_time_limit(0);
				foreach ($resultCuotas as $cuotas){
					$montocuota = $montocuota + $cuotas[montocuota];
					if($cuotas[montopagada] != 0.00){
						if($cuotas[sistemacancelacion] == 'M'){
							if($cuotas[fechacancelacion] <= $fechacancelacion)
								$montopagada = $montopagada + $cuotas[montopagada];
							else {
								if($cuotas[tipocancelacion] == 1 || $cuotas[tipocancelacion] == 3){
									$montocheque = $montocheque + $cuotas[montocuota];
								}
							}
						}
						if($cuotas[sistemacancelacion] == 'E'){
							if($cuotas[fechaacreditacion] <= $fechacancelacion)
								$montopagada = $montopagada + $cuotas[montopagada];
							else {
								if($cuotas[tipocancelacion] == 1 || $cuotas[tipocancelacion] == 3){
									$fechaboleta="20".substr($cuotas[codigobarra], 17, 2)."-".substr($cuotas[codigobarra], 19, 2)."-".substr($cuotas[codigobarra], 21,2);
									if($fechaboleta>=$fechacancelacion)
										$montocheque = $montocheque + $cuotas[montocuota];
									else
										$montodepositado = $montodepositado + $cuotas[montocuota];
								}
							}
						}
					}
					else{
						if($cuotas[tipocancelacion] == 1 || $cuotas[tipocancelacion] == 3){
							if($cuotas[boletaimpresa]==0)
								$montocheque = $montocheque + $cuotas[montocuota];
							else
								$montodepositado = $montodepositado + $cuotas[montocuota];
						}
					}
				}
			}

			$montoadeudado = $montocuota - ($montopagada + $montocheque + $montodepositado);

			// Agrega datos a las celdas de datos
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$fila, $cuit);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$fila, $acuerdo);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$fila, $fechaacuerdo);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$fila, 'Vigente');
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$fila, 'Acuerdo Extrajudicial');
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$fila, $anioacuerdo);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$fila, $montocuota);
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$fila, $montopagada);
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$fila, $montocheque);
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$fila, $montodepositado);	
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$fila, $montoadeudado);	
		}
	}

	// Setea fuente tipo y tamaño a la hoja activa
	$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
	$objPHPExcel->getDefaultStyle()->getFont()->setSize(8); 

	$objPHPExcel->getActiveSheet()->getStyle('I1')->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle('J1')->getAlignment()->setWrapText(true);

	// Setea negrita relleno y alineamiento horizontal a las celdas de titulos
	$objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getFill()->getStartColor()->setARGB('FF808080');
	$objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	// Setea tipo de dato y alineamiento horizontal a las celdas de datos
	$objPHPExcel->getActiveSheet()->getStyle('A2:A'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	$objPHPExcel->getActiveSheet()->getStyle('A2:A'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('B2:B'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
	$objPHPExcel->getActiveSheet()->getStyle('B2:B'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('C2:C'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
	$objPHPExcel->getActiveSheet()->getStyle('C2:C'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('D2:D'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	$objPHPExcel->getActiveSheet()->getStyle('D2:D'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('E2:E'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	$objPHPExcel->getActiveSheet()->getStyle('E2:E'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('F2:F'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
	$objPHPExcel->getActiveSheet()->getStyle('F2:F'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('G2:G'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$objPHPExcel->getActiveSheet()->getStyle('G2:G'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('H2:H'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$objPHPExcel->getActiveSheet()->getStyle('H2:H'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('I2:I'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$objPHPExcel->getActiveSheet()->getStyle('I2:I'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('J2:J'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$objPHPExcel->getActiveSheet()->getStyle('J2:J'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('K2:K'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$objPHPExcel->getActiveSheet()->getStyle('K2:K'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

	$desde = 2;
	for( $i=2; $i<$fila; $i++ ) {
		$celdaactual = $objPHPExcel->getActiveSheet()->getCell('F'.$i);
		$celdaposter = $objPHPExcel->getActiveSheet()->getCell('F'.($i+1));
		$valoractual = $celdaactual->getCalculatedValue();
		$valorposter = $celdaposter->getCalculatedValue();
		if( $valoractual != $valorposter ) {
			$filaagregada++;
			$salto=$i+1;
			$objPHPExcel->getActiveSheet()->insertNewRowBefore($salto, 1);
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$salto, '=SUM(K'.$desde.':K'.$i.')');
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$salto, 'Total para el anio '.$valoractual);
			$objPHPExcel->getActiveSheet()->mergeCells('A'.$salto.':J'.$salto);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$salto)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$salto)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('K'.$salto)->getFont()->setBold(true);
			$i=$i+1;
			$objPHPExcel->getActiveSheet()->setBreak('A'.$i, PHPExcel_Worksheet::BREAK_ROW);
			$desde=$i+1;
		}
	}

	$objPHPExcel->getActiveSheet()->setCellValue('K'.($fila+$filaagregada+1), '=SUM(K'.$desde.':K'.($fila+$filaagregada).')');
	$objPHPExcel->getActiveSheet()->setCellValue('A'.($fila+$filaagregada+1), 'Total para el anio '.$valoractual);
	$objPHPExcel->getActiveSheet()->mergeCells('A'.($fila+$filaagregada+1).':J'.($fila+$filaagregada+1));
	$objPHPExcel->getActiveSheet()->getStyle('A'.($fila+$filaagregada+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('A'.($fila+$filaagregada+1))->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('K'.($fila+$filaagregada+1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$objPHPExcel->getActiveSheet()->getStyle('K'.($fila+$filaagregada+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('K'.($fila+$filaagregada+1))->getFont()->setBold(true);

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