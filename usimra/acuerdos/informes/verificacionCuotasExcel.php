<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php");
require_once($libPath."phpExcel/Classes/PHPExcel.php");

$maquina = $_SERVER['SERVER_NAME'];
$fechacargadadesde=$_POST['fechadesde'];
$fechacargadahasta=$_POST['fechahasta'];
$fechaini=substr($fechacargadadesde, 6, 4)."-".substr($fechacargadadesde, 3, 2)."-".substr($fechacargadadesde, 0, 2);
$fechafin=substr($fechacargadahasta, 6, 4)."-".substr($fechacargadahasta, 3, 2)."-".substr($fechacargadahasta, 0, 2);
$fechagenera=date("d/m/Y");

if(strcmp("localhost",$maquina)==0)
	$archivo_name="Verificacion de Cuotas desde el ".substr($fechacargadadesde, 0, 2)."-".substr($fechacargadadesde, 3, 2)."-".substr($fechacargadadesde, 6, 4)." hasta el ".substr($fechacargadahasta, 0, 2)."-".substr($fechacargadahasta, 3, 2)."-".substr($fechacargadahasta, 6, 4).".xls";
else
	$archivo_name="/home/sistemas/Documentos/Repositorio/GGGG1210121610SYS/Verificacion de Cuotas desde el ".substr($fechacargadadesde, 0, 2)."-".substr($fechacargadadesde, 3, 2)."-".substr($fechacargadadesde, 6, 4)." hasta el ".substr($fechacargadahasta, 0, 2)."-".substr($fechacargadahasta, 3, 2)."-".substr($fechacargadahasta, 6, 4).".xls";

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
								 ->setTitle("Verificacion de Cuotas")
								 ->setSubject("Modulo de Acuerdos")
								 ->setDescription("Informe de Flujo de Cuotas en un Periodo")
								 ->setKeywords("acuerdos cuotas verificacion informe")
								 ->setCategory("Informes del Sistema de Acuerdos");
	// Renombra la hoja
	$objPHPExcel->getActiveSheet()->setTitle(substr($fechacargadadesde, 0, 2).'-'.substr($fechacargadadesde, 3, 2).'-'.substr($fechacargadadesde, 6, 4)." a ".substr($fechacargadahasta, 0, 2).'-'.substr($fechacargadahasta, 3, 2).'-'.substr($fechacargadahasta, 6, 4));

	// Setea la hoja como activa, cuando se abra el Excel esta sera la primer hoja
	$objPHPExcel->setActiveSheetIndex(0);

	// Setea encabezado y pie de pagina
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&BU.S.I.M.R.A.&G&C&H&BVerificacion de Cuotas - '.$objPHPExcel->getActiveSheet()->getTitle().'&R&B'.$fechagenera);
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&R&BPagina &P de &N');

	// Setea en configuracion de pagina orientacion y tama�o
	$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
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
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(16);
	$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Tipo Cancelacion');
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(11);
	$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Cheque Nro.');
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(13);
	$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Cheque Banco');
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(13);
	$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Cheque Fecha');
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(23);
	$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Observaciones');
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(13);
	$objPHPExcel->getActiveSheet()->setCellValue('K1', 'Monto Pagado');
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(13);
	$objPHPExcel->getActiveSheet()->setCellValue('L1', 'Fecha de Pago');
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(19);
	$objPHPExcel->getActiveSheet()->setCellValue('M1', 'Sistema Cancelacion');
	$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(33);
	$objPHPExcel->getActiveSheet()->setCellValue('N1', 'Codigo de Barra');
	$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(12);
	$objPHPExcel->getActiveSheet()->setCellValue('O1', 'Acred./Canc.');

	$fila=1;
		
	$sqlCuotas="SELECT * FROM cuoacuerdosusimra WHERE sistemacancelacion in ('M','E') order by cuit, nroacuerdo, nrocuota";
	$resultCuotas = $dbh->query($sqlCuotas);
	if ($resultCuotas){
		foreach ($resultCuotas as $cuotas){
			if($cuotas[montopagada] != 0.00){
				if($cuotas[sistemacancelacion] == 'M'){
					if($cuotas[fechacancelacion] >= $fechaini && $cuotas[fechacancelacion] <= $fechafin) {
						$fila++;
						// Agrega datos a las celdas de datos
						$objPHPExcel->getActiveSheet()->setCellValue('A'.$fila, $cuotas[cuit]);
						$objPHPExcel->getActiveSheet()->setCellValue('B'.$fila, $cuotas[nroacuerdo]);
						$objPHPExcel->getActiveSheet()->setCellValue('C'.$fila, $cuotas[nrocuota]);
						$objPHPExcel->getActiveSheet()->setCellValue('D'.$fila, $cuotas[montocuota]);
						$objPHPExcel->getActiveSheet()->setCellValue('E'.$fila, invertirFecha($cuotas[fechacuota]));
						$objPHPExcel->getActiveSheet()->setCellValue('F'.$fila, $cuotas[tipocancelacion]);
						$objPHPExcel->getActiveSheet()->setCellValue('G'.$fila, $cuotas[chequenro]);
						$objPHPExcel->getActiveSheet()->setCellValue('H'.$fila, $cuotas[chequebanco]);
						if($cuotas[chequefecha]!='0000-00-00')
							$objPHPExcel->getActiveSheet()->setCellValue('I'.$fila, invertirFecha($cuotas[chequefecha]));
						else
							$objPHPExcel->getActiveSheet()->setCellValue('I'.$fila, '');
						$objPHPExcel->getActiveSheet()->setCellValue('J'.$fila, $cuotas[observaciones]);
						$objPHPExcel->getActiveSheet()->setCellValue('K'.$fila, $cuotas[montopagada]);
						$objPHPExcel->getActiveSheet()->setCellValue('L'.$fila, invertirFecha($cuotas[fechapagada]));
						$objPHPExcel->getActiveSheet()->setCellValue('M'.$fila, $cuotas[sistemacancelacion]);
						$objPHPExcel->getActiveSheet()->setCellValue('N'.$fila, "-".$cuotas[codigobarra]."-");
						$objPHPExcel->getActiveSheet()->setCellValue('O'.$fila, invertirFecha($cuotas[fechacancelacion]));
					}
				}

				if($cuotas[sistemacancelacion] == 'E'){
					if($cuotas[fechaacreditacion] >= $fechaini && $cuotas[fechaacreditacion] <= $fechafin) {
						$fila++;
						// Agrega datos a las celdas de datos
						$objPHPExcel->getActiveSheet()->setCellValue('A'.$fila, $cuotas[cuit]);
						$objPHPExcel->getActiveSheet()->setCellValue('B'.$fila, $cuotas[nroacuerdo]);
						$objPHPExcel->getActiveSheet()->setCellValue('C'.$fila, $cuotas[nrocuota]);
						$objPHPExcel->getActiveSheet()->setCellValue('D'.$fila, $cuotas[montocuota]);
						$objPHPExcel->getActiveSheet()->setCellValue('E'.$fila, invertirFecha($cuotas[fechacuota]));
						$objPHPExcel->getActiveSheet()->setCellValue('F'.$fila, $cuotas[tipocancelacion]);
						$objPHPExcel->getActiveSheet()->setCellValue('G'.$fila, $cuotas[chequenro]);
						$objPHPExcel->getActiveSheet()->setCellValue('H'.$fila, $cuotas[chequebanco]);
						if($cuotas[chequefecha]!='0000-00-00')
							$objPHPExcel->getActiveSheet()->setCellValue('I'.$fila, invertirFecha($cuotas[chequefecha]));
						else
							$objPHPExcel->getActiveSheet()->setCellValue('I'.$fila, '');	
						$objPHPExcel->getActiveSheet()->setCellValue('J'.$fila, $cuotas[observaciones]);
						$objPHPExcel->getActiveSheet()->setCellValue('K'.$fila, $cuotas[montopagada]);
						$objPHPExcel->getActiveSheet()->setCellValue('L'.$fila, invertirFecha($cuotas[fechapagada]));
						$objPHPExcel->getActiveSheet()->setCellValue('M'.$fila, $cuotas[sistemacancelacion]);
						$objPHPExcel->getActiveSheet()->setCellValue('N'.$fila, "-".$cuotas[codigobarra]."-");
						$objPHPExcel->getActiveSheet()->setCellValue('O'.$fila, invertirFecha($cuotas[fechaacreditacion]));
					}
				}
			}
		}
	}

	// Setea fuente tipo y tama�o a la hoja activa
	$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
	$objPHPExcel->getDefaultStyle()->getFont()->setSize(8); 

	// Setea negrita relleno y alineamiento horizontal a las celdas de titulos
	$objPHPExcel->getActiveSheet()->getStyle('A1:O1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A1:O1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyle('A1:O1')->getFill()->getStartColor()->setARGB('FF808080');
	$objPHPExcel->getActiveSheet()->getStyle('A1:O1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
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
	$objPHPExcel->getActiveSheet()->getStyle('F2:F'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
	$objPHPExcel->getActiveSheet()->getStyle('F2:F'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('G2:G'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	$objPHPExcel->getActiveSheet()->getStyle('G2:G'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('H2:H'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	$objPHPExcel->getActiveSheet()->getStyle('H2:H'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('I2:I'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
	$objPHPExcel->getActiveSheet()->getStyle('I2:I'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('J2:J'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	$objPHPExcel->getActiveSheet()->getStyle('J2:J'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('J2:J'.$fila)->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle('K2:K'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$objPHPExcel->getActiveSheet()->getStyle('K2:K'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('L2:L'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
	$objPHPExcel->getActiveSheet()->getStyle('L2:L'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('M2:M'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	$objPHPExcel->getActiveSheet()->getStyle('M2:M'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('N2:N'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	$objPHPExcel->getActiveSheet()->getStyle('N2:N'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('O2:O'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
	$objPHPExcel->getActiveSheet()->getStyle('O2:O'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

	// Guarda Archivo en Formato Excel 2003
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save($archivo_name);

	$dbh->commit();
	$pagina = "moduloInformes.php";
	Header("Location: $pagina");

}
catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/usimra/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}
?>