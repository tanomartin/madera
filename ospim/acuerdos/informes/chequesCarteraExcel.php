<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
require_once($libPath."phpExcel/Classes/PHPExcel.php");

$maquina = $_SERVER['SERVER_NAME'];
$fechacargada=$_POST['fechahasta'];
$fechafin=substr($fechacargada, 6, 4)."-".substr($fechacargada, 3, 2)."-".substr($fechacargada, 0, 2);
$fechagenera=date("d/m/Y");

if(strcmp("localhost",$maquina)==0)
	$archivo_name="Cheques en Cartera al ".substr($fechacargada, 0, 2)."-".substr($fechacargada, 3, 2)."-".substr($fechacargada, 6, 4).".xls";
else
	$archivo_name="/home/sistemas/Documentos/Repositorio/FFFF1208311301SYS/Cheques en Cartera al ".substr($fechacargada, 0, 2)."-".substr($fechacargada, 3, 2)."-".substr($fechacargada, 6, 4).".xls";

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
								 ->setTitle("Cheques en Cartera")
								 ->setSubject("Modulo de Acuerdos")
								 ->setDescription("Informe de Cheques en Cartera a una Fecha.")
								 ->setKeywords("cheques cartera estado informe")
								 ->setCategory("Informes del Sistema de Acuerdos");
	// Renombra la hoja
	$objPHPExcel->getActiveSheet()->setTitle(substr($fechacargada, 0, 2).'-'.substr($fechacargada, 3, 2).'-'.substr($fechacargada, 6, 4));

	// Setea la hoja como activa, cuando se abra el Excel esta sera la primer hoja
	$objPHPExcel->setActiveSheetIndex(0);

	// Setea encabezado y pie de pagina
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&BO.S.P.I.M.&G&C&H&BCheques en Cartera al '.$objPHPExcel->getActiveSheet()->getTitle().'&R&B'.$fechagenera);
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&R&BPagina &P de &N');

	// Setea en configuracion de pagina orientacion y tamaño
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

	// Setea tamaño de la columna y agrega datos a las celdas de titulos
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
	$objPHPExcel->getActiveSheet()->setCellValue('A1', 'C.U.I.T.');
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
	$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Nombre / Razon Social');
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(8);
	$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Acuerdo');
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(13);
	$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Fecha Acuerdo');
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(6);
	$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Cuota');
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(13);
	$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Cuota Cargada');
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
	$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Monto Cuota');
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(11);
	$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Vto. Cuota');
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(11);
	$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Cheque Nro.');
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(13);
	$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Cheque Banco');
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(13);
	$objPHPExcel->getActiveSheet()->setCellValue('K1', 'Cheque Fecha');
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(31);
	$objPHPExcel->getActiveSheet()->setCellValue('L1', 'Observaciones');
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(28);
	$objPHPExcel->getActiveSheet()->setCellValue('M1', 'Estado');

	$fila=1;	

	$sqlCuotas="SELECT c.cuit, e.nombre, c.nroacuerdo, a.fechaacuerdo, c.nrocuota, DATE_FORMAT(c.fecharegistro, '%Y-%m-%d') AS fecharegistro, c.montocuota, c.fechacuota, c.chequenro, c.chequebanco, c.chequefecha, c.observaciones, c.fechacancelacion, c.codigobarra, c.fechaacreditacion, c.sistemacancelacion, c.montopagada FROM cuoacuerdosospim c, empresas e, cabacuerdosospim a WHERE c.tipocancelacion IN(1,3) AND c.cuit = e.cuit AND c.cuit = a.cuit AND c.nroacuerdo = a.nroacuerdo ORDER BY c.cuit, c.nroacuerdo, c.nrocuota";
	$resultCuotas = $dbh->query($sqlCuotas);
	if ($resultCuotas){
		foreach ($resultCuotas as $cuotas){
			if($cuotas[montopagada] != 0.00){
				if($cuotas[sistemacancelacion] == 'M'){
					if($cuotas[fechacancelacion] >= $fechafin) {
						$fila++;
						// Agrega datos a las celdas de datos
						$objPHPExcel->getActiveSheet()->setCellValue('A'.$fila, $cuotas[cuit]);
						$objPHPExcel->getActiveSheet()->setCellValue('B'.$fila, $cuotas[nombre]);
						$objPHPExcel->getActiveSheet()->setCellValue('C'.$fila, $cuotas[nroacuerdo]);
						if($cuotas[fechaacuerdo]!='0000-00-00')
							$objPHPExcel->getActiveSheet()->setCellValue('D'.$fila, invertirFecha($cuotas[fechaacuerdo]));
						else
							$objPHPExcel->getActiveSheet()->setCellValue('D'.$fila, '');
						$objPHPExcel->getActiveSheet()->setCellValue('E'.$fila, $cuotas[nrocuota]);
						$objPHPExcel->getActiveSheet()->setCellValue('F'.$fila, invertirFecha($cuotas[fecharegistro]));
						$objPHPExcel->getActiveSheet()->setCellValue('G'.$fila, $cuotas[montocuota]);
						$objPHPExcel->getActiveSheet()->setCellValue('H'.$fila, invertirFecha($cuotas[fechacuota]));
						$objPHPExcel->getActiveSheet()->setCellValue('I'.$fila, $cuotas[chequenro]);
						$objPHPExcel->getActiveSheet()->setCellValue('J'.$fila, $cuotas[chequebanco]);
						if($cuotas[chequefecha]!='0000-00-00')
							$objPHPExcel->getActiveSheet()->setCellValue('K'.$fila, invertirFecha($cuotas[chequefecha]));
						else
							$objPHPExcel->getActiveSheet()->setCellValue('K'.$fila, '');
						$objPHPExcel->getActiveSheet()->setCellValue('L'.$fila, $cuotas[observaciones]);
						$objPHPExcel->getActiveSheet()->setCellValue('M'.$fila, 'Cancelado Manualmente el '.invertirFecha($cuotas[fechacancelacion]));
					}
				}

				if($cuotas[sistemacancelacion] == 'E'){
					if($cuotas[fechaacreditacion] >= $fechafin) {
						$fila++;
						// Agrega datos a las celdas de datos
						$objPHPExcel->getActiveSheet()->setCellValue('A'.$fila, $cuotas[cuit]);
						$objPHPExcel->getActiveSheet()->setCellValue('B'.$fila, $cuotas[nombre]);
						$objPHPExcel->getActiveSheet()->setCellValue('C'.$fila, $cuotas[nroacuerdo]);
						if($cuotas[fechaacuerdo]!='0000-00-00')
							$objPHPExcel->getActiveSheet()->setCellValue('D'.$fila, invertirFecha($cuotas[fechaacuerdo]));
						else
							$objPHPExcel->getActiveSheet()->setCellValue('D'.$fila, '');
						$objPHPExcel->getActiveSheet()->setCellValue('E'.$fila, $cuotas[nrocuota]);
						$objPHPExcel->getActiveSheet()->setCellValue('F'.$fila, invertirFecha($cuotas[fecharegistro]));
						$objPHPExcel->getActiveSheet()->setCellValue('G'.$fila, $cuotas[montocuota]);
						$objPHPExcel->getActiveSheet()->setCellValue('H'.$fila, invertirFecha($cuotas[fechacuota]));
						$objPHPExcel->getActiveSheet()->setCellValue('I'.$fila, $cuotas[chequenro]);
						$objPHPExcel->getActiveSheet()->setCellValue('J'.$fila, $cuotas[chequebanco]);
						if($cuotas[chequefecha]!='0000-00-00')
							$objPHPExcel->getActiveSheet()->setCellValue('K'.$fila, invertirFecha($cuotas[chequefecha]));
						else
							$objPHPExcel->getActiveSheet()->setCellValue('K'.$fila, '');
						$objPHPExcel->getActiveSheet()->setCellValue('L'.$fila, $cuotas[observaciones]);
						$fechaboleta="20".substr($cuotas[codigobarra], 17, 2)."-".substr($cuotas[codigobarra], 19, 2)."-".substr($cuotas[codigobarra], 21,2);
						if($fechaboleta>=$fechafin)
							$objPHPExcel->getActiveSheet()->setCellValue('M'.$fila, 'Hasta el '.substr($fechacargada, 0, 2).'/'.substr($fechacargada, 3, 2).'/'.substr($fechacargada, 6, 4).' en cartera. Acreditado el '.invertirFecha($cuotas[fechaacreditacion]));
						else
							$objPHPExcel->getActiveSheet()->setCellValue('M'.$fila, 'Presentado al Banco. Acreditado el '.invertirFecha($cuotas[fechaacreditacion]));
					}
				}
			}
			else {
				$fila++;
				// Agrega datos a las celdas de datos
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$fila, $cuotas[cuit]);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$fila, $cuotas[nombre]);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$fila, $cuotas[nroacuerdo]);
				if($cuotas[fechaacuerdo]!='0000-00-00')
					$objPHPExcel->getActiveSheet()->setCellValue('D'.$fila, invertirFecha($cuotas[fechaacuerdo]));
				else
					$objPHPExcel->getActiveSheet()->setCellValue('D'.$fila, '');
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$fila, $cuotas[nrocuota]);
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$fila, invertirFecha($cuotas[fecharegistro]));
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$fila, $cuotas[montocuota]);
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$fila, invertirFecha($cuotas[fechacuota]));
				$objPHPExcel->getActiveSheet()->setCellValue('I'.$fila, $cuotas[chequenro]);
				$objPHPExcel->getActiveSheet()->setCellValue('J'.$fila, $cuotas[chequebanco]);
				if($cuotas[chequefecha]!='0000-00-00')
					$objPHPExcel->getActiveSheet()->setCellValue('K'.$fila, invertirFecha($cuotas[chequefecha]));
				else
					$objPHPExcel->getActiveSheet()->setCellValue('K'.$fila, '');
				$objPHPExcel->getActiveSheet()->setCellValue('L'.$fila, $cuotas[observaciones]);
				if($cuotas[boletaimpresa]==0)
					$objPHPExcel->getActiveSheet()->setCellValue('M'.$fila, 'Hasta la actualidad en cartera.');
				else
					$objPHPExcel->getActiveSheet()->setCellValue('M'.$fila, 'Presentado al Banco. Aun sin acreditar');
			}
		}
	}

	// Setea fuente tipo y tamaño a la hoja activa
	$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
	$objPHPExcel->getDefaultStyle()->getFont()->setSize(8); 

	// Setea negrita relleno y alineamiento horizontal a las celdas de titulos
	$objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getFill()->getStartColor()->setARGB('FF808080');
	$objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	// Setea tipo de dato y alineamiento horizontal a las celdas de datos
	$objPHPExcel->getActiveSheet()->getStyle('A2:A'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	$objPHPExcel->getActiveSheet()->getStyle('A2:A'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('B2:B'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	$objPHPExcel->getActiveSheet()->getStyle('B2:B'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('C2:C'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
	$objPHPExcel->getActiveSheet()->getStyle('C2:C'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('D2:D'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
	$objPHPExcel->getActiveSheet()->getStyle('D2:D'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('E2:E'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
	$objPHPExcel->getActiveSheet()->getStyle('E2:E'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('F2:F'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
	$objPHPExcel->getActiveSheet()->getStyle('F2:F'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('G2:G'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$objPHPExcel->getActiveSheet()->getStyle('G2:G'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('H2:H'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
	$objPHPExcel->getActiveSheet()->getStyle('H2:H'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('I2:I'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	$objPHPExcel->getActiveSheet()->getStyle('I2:I'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('J2:J'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	$objPHPExcel->getActiveSheet()->getStyle('J2:J'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('K2:K'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
	$objPHPExcel->getActiveSheet()->getStyle('K2:K'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('L2:L'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	$objPHPExcel->getActiveSheet()->getStyle('L2:L'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('L2:L'.$fila)->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle('M2:M'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	$objPHPExcel->getActiveSheet()->getStyle('M2:M'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('M2:M'.$fila)->getAlignment()->setWrapText(true);

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
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}
?>