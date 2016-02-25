<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
require_once($libPath."phpExcel/Classes/PHPExcel.php");

$maquina = $_SERVER['SERVER_NAME'];
$fechacargadadesde=$_POST['fechadesde'];
$fechacargadahasta=$_POST['fechahasta'];
$fechaini=substr($fechacargadadesde, 6, 4)."-".substr($fechacargadadesde, 3, 2)."-".substr($fechacargadadesde, 0, 2);
$fechafin=substr($fechacargadahasta, 6, 4)."-".substr($fechacargadahasta, 3, 2)."-".substr($fechacargadahasta, 0, 2);
$fechagenera=date("d/m/Y");

if(strcmp("localhost",$maquina)==0)
	$archivo_name="Liquidaciones desde el ".substr($fechacargadadesde, 0, 2)."-".substr($fechacargadadesde, 3, 2)."-".substr($fechacargadadesde, 6, 4)." hasta el ".substr($fechacargadahasta, 0, 2)."-".substr($fechacargadahasta, 3, 2)."-".substr($fechacargadahasta, 6, 4).".xls";
else
	$archivo_name="/home/sistemas/Documentos/Repositorio/FFFF1208311301SYS/Liquidaciones desde el ".substr($fechacargadadesde, 0, 2)."-".substr($fechacargadadesde, 3, 2)."-".substr($fechacargadadesde, 6, 4)." hasta el ".substr($fechacargadahasta, 0, 2)."-".substr($fechacargadahasta, 3, 2)."-".substr($fechacargadahasta, 6, 4).".xls";

//conexion y creacion de transaccion.
try{
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();

	// Set document properties
	$objPHPExcel->getProperties()->setCreator($_SESSION['usuario'])
								 ->setLastModifiedBy($_SESSION['usuario'])
								 ->setTitle("Liquidacion de Comisiones")
								 ->setSubject("Modulo de Acuerdos")
								 ->setDescription("Liquidacion de Comisiones en un periodo")
								 ->setKeywords("liquidacion comisiones administrativo informe")
								 ->setCategory("Informes del Sistema de Acuerdos");
	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle(substr($fechacargadadesde, 0, 2).'-'.substr($fechacargadadesde, 3, 2).'-'.substr($fechacargadadesde, 6, 4)." a ".substr($fechacargadahasta, 0, 2).'-'.substr($fechacargadahasta, 3, 2).'-'.substr($fechacargadahasta, 6, 4));

	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);

	// Set header and footer. When no different headers for odd/even are used, odd header is assumed.
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&BO.S.P.I.M.&G&C&H&BLiquidacion Comisiones - '.$objPHPExcel->getActiveSheet()->getTitle().'&R&B'.$fechagenera);
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&R&BPagina &P de &N');

	// Set page orientation and size
	$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LEGAL);	
	
	// Set page margin
	$objPHPExcel->getActiveSheet()->getPageMargins()->setTop(0.5);
	$objPHPExcel->getActiveSheet()->getPageMargins()->setRight(0);
	$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft(0);
	$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom(0.5);
	$objPHPExcel->getActiveSheet()->getPageMargins()->setHeader(0.25);
	$objPHPExcel->getActiveSheet()->getPageMargins()->setFooter(0.25);
	
	// Set page center horizontally/vertically
	$objPHPExcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setVerticalCentered(false);

	// Set show gridlines when printing
	//$objPHPExcel->getActiveSheet()->getPageSetup()->setShowGridlines(true);

	// Set rows/columns to repeat at top/left
	$objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 1);

	// Add some data, we will use printing features
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
	$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Deleg.');
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
	$objPHPExcel->getActiveSheet()->setCellValue('B1', 'C.U.I.T.');
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(8);
	$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Acuerdo');
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(6);
	$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Cuota');
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
	$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Monto Cuota');
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(11);
	$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Vto. Cuota');
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(13);
	$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Monto Pagado');
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(13);
	$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Fecha de Pago');
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);
	$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Acred./Canc.');
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(33);
	$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Codigo de Barra');
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(11);
	$objPHPExcel->getActiveSheet()->setCellValue('K1', 'Gestor');
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(13);
	$objPHPExcel->getActiveSheet()->setCellValue('L1', 'Gto. Adm.');
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(19);
	$objPHPExcel->getActiveSheet()->setCellValue('M1', 'Inspector');
	$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(33);
	$objPHPExcel->getActiveSheet()->setCellValue('N1', '% Sobre Total');
	$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(12);
	$objPHPExcel->getActiveSheet()->setCellValue('O1', 'Monto Liquidacion');
	$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(12);
	$objPHPExcel->getActiveSheet()->setCellValue('P1', '% de Comision');
	$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(13);
	$objPHPExcel->getActiveSheet()->setCellValue('Q1', 'Monto Comision');

	$fila=1;

	$sqlCuotas="SELECT * FROM cuoacuerdosospim WHERE montopagada != 0.00";
	$resultCuotas = $dbh->query($sqlCuotas);
	if ($resultCuotas){
		set_time_limit(0);
		foreach ($resultCuotas as $cuotas){
			$cuit=$cuotas[cuit];
			$acuerdo=$cuotas[nroacuerdo];

			$sqlJurisdiccion="SELECT * FROM jurisdiccion WHERE cuit = $cuit";
			$resultJurisdiccion = mysql_query( $sqlJurisdiccion,$db); 
			while($rowJurisdiccion = mysql_fetch_array($resultJurisdiccion)) {
				if($rowJurisdiccion['disgdinero']==100.00) {
					$delegacion=$rowJurisdiccion['codidelega'];
				}
			}

			$sqlAcuerdos="SELECT * FROM cabacuerdosospim WHERE cuit = $cuit and nroacuerdo = $acuerdo";
			$resultAcuerdos = mysql_query( $sqlAcuerdos,$db);						
			$rowAcuerdos = mysql_fetch_array($resultAcuerdos);

			if($cuotas[sistemacancelacion] == 'M'){
				if($cuotas[fechacancelacion] >= $fechaini && $cuotas[fechacancelacion] <= $fechafin) {
					$fila++;
					// Add some data, we will use printing features
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$fila, $delegacion);
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$fila, $cuotas[cuit]);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$fila, $cuotas[nroacuerdo]);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.$fila, $cuotas[nrocuota]);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.$fila, $cuotas[montocuota]);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$fila, invertirFecha($cuotas[fechacuota]));
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$fila, $cuotas[montopagada]);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$fila, invertirFecha($cuotas[fechapagada]));
					$objPHPExcel->getActiveSheet()->setCellValue('I'.$fila, invertirFecha($cuotas[fechacancelacion]));
					$objPHPExcel->getActiveSheet()->setCellValue('J'.$fila, '-'.$cuotas[codigobarra].'-');
					$objPHPExcel->getActiveSheet()->setCellValue('K'.$fila, $rowAcuerdos['gestoracuerdo']);
					$objPHPExcel->getActiveSheet()->setCellValue('L'.$fila, $rowAcuerdos['porcengastoadmin']);
					$objPHPExcel->getActiveSheet()->setCellValue('M'.$fila, $rowAcuerdos['inspectorinterviene']);
					$objPHPExcel->getActiveSheet()->setCellValue('N'.$fila, '=(100-L'.$fila.')/100');
					$objPHPExcel->getActiveSheet()->setCellValue('O'.$fila, '=G'.$fila.'*N'.$fila);
					$objPHPExcel->getActiveSheet()->setCellValue('P'.$fila, '3');
					$objPHPExcel->getActiveSheet()->setCellValue('Q'.$fila, '=O'.$fila.'*P'.$fila);
				}
			}
			if($cuotas[sistemacancelacion] == 'E'){
				if($cuotas[fechaacreditacion] >= $fechaini && $cuotas[fechaacreditacion] <= $fechafin) {
					$fila++;
					// Add some data, we will use printing features
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$fila, $delegacion);
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$fila, $cuotas[cuit]);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$fila, $cuotas[nroacuerdo]);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.$fila, $cuotas[nrocuota]);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.$fila, $cuotas[montocuota]);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$fila, invertirFecha($cuotas[fechacuota]));
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$fila, $cuotas[montopagada]);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$fila, invertirFecha($cuotas[fechapagada]));
					$objPHPExcel->getActiveSheet()->setCellValue('I'.$fila, invertirFecha($cuotas[fechaacreditacion]));
					$objPHPExcel->getActiveSheet()->setCellValue('J'.$fila, '-'.$cuotas[codigobarra].'-');
					$objPHPExcel->getActiveSheet()->setCellValue('K'.$fila, $rowAcuerdos['gestoracuerdo']);
					$objPHPExcel->getActiveSheet()->setCellValue('L'.$fila, $rowAcuerdos['porcengastoadmin']);
					$objPHPExcel->getActiveSheet()->setCellValue('M'.$fila, $rowAcuerdos['inspectorinterviene']);
					$objPHPExcel->getActiveSheet()->setCellValue('N'.$fila, '=(100-L'.$fila.')/100');
					$objPHPExcel->getActiveSheet()->setCellValue('O'.$fila, '=G'.$fila.'*N'.$fila);
					$objPHPExcel->getActiveSheet()->setCellValue('P'.$fila, '3');
					$objPHPExcel->getActiveSheet()->setCellValue('Q'.$fila, '=O'.$fila.'*P'.$fila);
				}
			}
		}
	}

	// Set font type and size
	$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
	$objPHPExcel->getDefaultStyle()->getFont()->setSize(8); 

	// Set bold fills color and horizontal alignment to title cells
	$objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->getFill()->getStartColor()->setARGB('FF808080');
	$objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	// Set data type and horizontal alignment to data cells
	$objPHPExcel->getActiveSheet()->getStyle('A2:A'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
	$objPHPExcel->getActiveSheet()->getStyle('A2:A'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('B2:B'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	$objPHPExcel->getActiveSheet()->getStyle('B2:B'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('C2:C'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
	$objPHPExcel->getActiveSheet()->getStyle('C2:C'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('D2:D'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
	$objPHPExcel->getActiveSheet()->getStyle('D2:D'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('E2:E'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$objPHPExcel->getActiveSheet()->getStyle('E2:E'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('F2:F'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
	$objPHPExcel->getActiveSheet()->getStyle('F2:F'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('G2:G'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$objPHPExcel->getActiveSheet()->getStyle('G2:G'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('H2:H'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
	$objPHPExcel->getActiveSheet()->getStyle('H2:H'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('I2:I'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
	$objPHPExcel->getActiveSheet()->getStyle('I2:I'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('J2:J'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	$objPHPExcel->getActiveSheet()->getStyle('J2:J'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('K2:K'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
	$objPHPExcel->getActiveSheet()->getStyle('K2:K'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('L2:L'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$objPHPExcel->getActiveSheet()->getStyle('L2:L'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('M2:M'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
	$objPHPExcel->getActiveSheet()->getStyle('M2:M'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('N2:N'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);
	$objPHPExcel->getActiveSheet()->getStyle('N2:N'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('O2:O'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$objPHPExcel->getActiveSheet()->getStyle('O2:O'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('P2:P'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);
	$objPHPExcel->getActiveSheet()->getStyle('P2:P'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('Q2:Q'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$objPHPExcel->getActiveSheet()->getStyle('Q2:Q'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

	// Save Excel 2003 file
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