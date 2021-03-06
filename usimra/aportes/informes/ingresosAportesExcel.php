<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php");
require_once($libPath."phpExcel/Classes/PHPExcel.php");

$maquina = $_SERVER['SERVER_NAME'];
$fechacargadadesde=$_POST['fechadesde'];
$fechacargadahasta=$_POST['fechahasta'];
$tipoingreso=$_POST['selectTipo'];
$totalizadores=$_POST['selectTotales'];
$fechaini=substr($fechacargadadesde, 6, 4)."-".substr($fechacargadadesde, 3, 2)."-".substr($fechacargadadesde, 0, 2);
$fechafin=substr($fechacargadahasta, 6, 4)."-".substr($fechacargadahasta, 3, 2)."-".substr($fechacargadahasta, 0, 2);
$fechagenera=date("d/m/Y");

set_time_limit(0);

if(strcmp("A",$tipoingreso)==0) {
	$tipoinforme="Electronicos (Boletas y Link Pagos) y Manuales";
	$cancelacion="'E','M','L'";
}
if(strcmp("E",$tipoingreso)==0) {
	$tipoinforme="Electronicos";
	$cancelacion="'E'";
}
if(strcmp("M",$tipoingreso)==0) {
	$tipoinforme="Manuales";
	$cancelacion="'M'";
}
if(strcmp("L",$tipoingreso)==0) {
	$tipoinforme="Link Pagos";
	$cancelacion="'L'";
}

if(strcmp("localhost",$maquina)==0)
	$archivo_name="Ingresos Aportes ".$tipoinforme." desde el ".substr($fechacargadadesde, 0, 2)."-".substr($fechacargadadesde, 3, 2)."-".substr($fechacargadadesde, 6, 4)." hasta el ".substr($fechacargadahasta, 0, 2)."-".substr($fechacargadahasta, 3, 2)."-".substr($fechacargadahasta, 6, 4).".xls";
else
	$archivo_name="/home/sistemas/Documentos/Repositorio/GGGG1210121610SYS/Ingresos Aportes ".$tipoinforme." desde el ".substr($fechacargadadesde, 0, 2)."-".substr($fechacargadadesde, 3, 2)."-".substr($fechacargadadesde, 6, 4)." hasta el ".substr($fechacargadahasta, 0, 2)."-".substr($fechacargadahasta, 3, 2)."-".substr($fechacargadahasta, 6, 4).".xls";

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
								 ->setTitle("Ingreso por Aportes")
								 ->setSubject("Modulo de Aportes")
								 ->setDescription("Ingreso por Aportes en un Periodo")
								 ->setKeywords("aportes ingresos cancelacion informe")
								 ->setCategory("Informes del Modulo de Aportes");
	// Renombra la hoja
	$objPHPExcel->getActiveSheet()->setTitle(substr($fechacargadadesde, 0, 2).'-'.substr($fechacargadadesde, 3, 2).'-'.substr($fechacargadadesde, 6, 4)." a ".substr($fechacargadahasta, 0, 2).'-'.substr($fechacargadahasta, 3, 2).'-'.substr($fechacargadahasta, 6, 4));

	// Setea la hoja como activa, cuando se abra el Excel esta sera la primer hoja
	$objPHPExcel->setActiveSheetIndex(0);

	// Setea encabezado y pie de pagina
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&BU.S.I.M.R.A.&G&C&H&BIngresos por Aportes '.$tipoinforme.' - '.$objPHPExcel->getActiveSheet()->getTitle().'&R&B'.$fechagenera);
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
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(6);
	$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Deleg.');
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
	$objPHPExcel->getActiveSheet()->setCellValue('B1', 'C.U.I.T.');
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(5);
	$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Mes');
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(5);
	$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Ano');
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
	$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Fecha Pago');
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(9);
	$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Personal');
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(17);
	$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Remuneraciones');
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(16);
	$objPHPExcel->getActiveSheet()->setCellValue('H1', '% Afect. Ingreso');
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(13);
	$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Aporte 0.60%');
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(13);
	$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Aporte 1.00%');
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(13);
	$objPHPExcel->getActiveSheet()->setCellValue('K1', 'Aporte 1.50%');
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(8);
	$objPHPExcel->getActiveSheet()->setCellValue('L1', 'Recargo');
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(16);
	$objPHPExcel->getActiveSheet()->setCellValue('M1', 'Total Depositado');
	$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(10);
	$objPHPExcel->getActiveSheet()->setCellValue('N1', 'Sistema');
	$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(33);
	$objPHPExcel->getActiveSheet()->setCellValue('O1', 'Codigo de Barra/Ticket Link Pagos');
	$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(14);
	$objPHPExcel->getActiveSheet()->setCellValue('P1', 'Acreditacion');
	$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(18);
	$objPHPExcel->getActiveSheet()->setCellValue('Q1', 'Operador');

	// Setea fuente tipo y tama�o a la hoja activa
	$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
	$objPHPExcel->getDefaultStyle()->getFont()->setSize(8); 

	// Setea negrita relleno y alineamiento horizontal a las celdas de titulos
	$objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->getFill()->getStartColor()->setARGB('FF808080');
	$objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$fila=1;
		
	$sqlLeeAportes="
SELECT
j.codidelega,
s.cuit,
s.mespago,
s.anopago,
DATE_FORMAT(s.fechapago, '%d/%m/%Y') AS fechapago,
s.cantidadpersonal,
s.remuneraciones,
j.disgdinero,
IFNULL((ROUND((a.importe)*(j.disgdinero/100),2)),0.00) AS aporte060,
IFNULL((ROUND((b.importe)*(j.disgdinero/100),2)),0.00) AS aporte100,
IFNULL((ROUND((c.importe)*(j.disgdinero/100),2)),0.00) AS aporte150,
ROUND((s.montorecargo)*(j.disgdinero/100),2) AS montorecargo,
ROUND((s.montopagado)*(j.disgdinero/100),2) AS totaldeposito,
s.sistemacancelacion,
s.codigobarra,
DATE_FORMAT(s.fechaacreditacion, '%d/%m/%Y') AS fechaacreditacion,
s.usuarioregistro
FROM ((((seguvidausimra s, empresas e, jurisdiccion j)
LEFT JOIN apor060usimra a ON
a.cuit = s.cuit AND
a.mespago = s.mespago AND
a.anopago = s.anopago AND
a.nropago = s.nropago)
LEFT JOIN apor100usimra b ON
b.cuit = s.cuit AND
b.mespago = s.mespago AND
b.anopago = s.anopago AND
b.nropago = s.nropago)
LEFT JOIN apor150usimra c ON
c.cuit = s.cuit AND
c.mespago = s.mespago AND
c.anopago = s.anopago AND
c.nropago = s.nropago)
WHERE
s.fechaacreditacion >= '$fechaini' AND
s.fechaacreditacion <= '$fechafin' AND
s.sistemacancelacion IN($cancelacion) AND
s.cuit = e.cuit AND
e.cuit = j.cuit AND
j.disgdinero != 0.00
ORDER BY j.codidelega, s.cuit, s.anopago, s.mespago, s.fechapago";
	$resLeeAportes=$dbh->query($sqlLeeAportes);
	foreach($resLeeAportes as $aportes) {
		$fila++;
		// Agrega datos a las celdas de datos
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$fila, $aportes[codidelega]);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$fila, $aportes[cuit]);
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$fila, $aportes[mespago]);
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$fila, $aportes[anopago]);
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$fila, $aportes[fechapago]);
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$fila, $aportes[cantidadpersonal]);
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$fila, $aportes[remuneraciones]);
		$objPHPExcel->getActiveSheet()->setCellValue('H'.$fila, ($aportes[disgdinero]/100.00));
		$objPHPExcel->getActiveSheet()->setCellValue('I'.$fila, $aportes[aporte060]);
		$objPHPExcel->getActiveSheet()->setCellValue('J'.$fila, $aportes[aporte100]);
		$objPHPExcel->getActiveSheet()->setCellValue('K'.$fila, $aportes[aporte150]);
		$objPHPExcel->getActiveSheet()->setCellValue('L'.$fila, $aportes[montorecargo]);
		$objPHPExcel->getActiveSheet()->setCellValue('M'.$fila, $aportes[totaldeposito]);
		$objPHPExcel->getActiveSheet()->setCellValue('N'.$fila, $aportes[sistemacancelacion]);
		$objPHPExcel->getActiveSheet()->setCellValue('O'.$fila, "-".$aportes[codigobarra]."-");
		$objPHPExcel->getActiveSheet()->setCellValue('P'.$fila, $aportes[fechaacreditacion]);
		$objPHPExcel->getActiveSheet()->setCellValue('Q'.$fila, $aportes[usuarioregistro]);
	}

	if($fila > 1) {
		// Setea tipo de dato y alineamiento horizontal a las celdas de datos
		$objPHPExcel->getActiveSheet()->getStyle('A2:A'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
		$objPHPExcel->getActiveSheet()->getStyle('A2:A'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getStyle('B2:B'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$objPHPExcel->getActiveSheet()->getStyle('B2:B'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('C2:C'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
		$objPHPExcel->getActiveSheet()->getStyle('C2:C'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getStyle('D2:D'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
		$objPHPExcel->getActiveSheet()->getStyle('D2:D'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getStyle('E2:E'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
		$objPHPExcel->getActiveSheet()->getStyle('E2:E'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getStyle('F2:F'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
		$objPHPExcel->getActiveSheet()->getStyle('F2:F'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getStyle('G2:G'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$objPHPExcel->getActiveSheet()->getStyle('G2:G'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getStyle('H2:H'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);
		$objPHPExcel->getActiveSheet()->getStyle('H2:H'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getStyle('I2:I'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$objPHPExcel->getActiveSheet()->getStyle('I2:I'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getStyle('J2:J'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$objPHPExcel->getActiveSheet()->getStyle('J2:J'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getStyle('K2:K'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$objPHPExcel->getActiveSheet()->getStyle('K2:K'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getStyle('L2:L'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$objPHPExcel->getActiveSheet()->getStyle('L2:L'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getStyle('M2:M'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
		$objPHPExcel->getActiveSheet()->getStyle('M2:M'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getStyle('N2:N'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$objPHPExcel->getActiveSheet()->getStyle('N2:N'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('O2:O'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$objPHPExcel->getActiveSheet()->getStyle('O2:O'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('P2:P'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);
		$objPHPExcel->getActiveSheet()->getStyle('P2:P'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getStyle('Q2:Q'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$objPHPExcel->getActiveSheet()->getStyle('Q2:Q'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$filaagregada = 0;
		$totalgeneral = 0.00;
		$desde = 2;

		if($totalizadores==1) {
			for( $i=2; $i<($fila+$filaagregada); $i++ ) {
				$celdaactual = $objPHPExcel->getActiveSheet()->getCell('A'.$i);
				$celdaposter = $objPHPExcel->getActiveSheet()->getCell('A'.($i+1));
				$valoractual = $celdaactual->getValue();
				$valorposter = $celdaposter->getValue();
				if( $valoractual != $valorposter ) {
					$filaagregada++;
					$salto=$i+1;
					$objPHPExcel->getActiveSheet()->insertNewRowBefore($salto, 1);
					//Aporte 0.60%
					$objPHPExcel->getActiveSheet()->setCellValue('I'.$salto, '=SUM(I'.$desde.':I'.$i.')');
					$celdaapor060 = $objPHPExcel->getActiveSheet()->getCell('I'.$salto);
					$valorapor060 = $celdaapor060->getCalculatedValue();
					$totalapor060 = $totalapor060+$valorapor060;
					//Aporte 1.00%
					$objPHPExcel->getActiveSheet()->setCellValue('J'.$salto, '=SUM(J'.$desde.':J'.$i.')');
					$celdaapor100 = $objPHPExcel->getActiveSheet()->getCell('J'.$salto);
					$valorapor100 = $celdaapor100->getCalculatedValue();
					$totalapor100 = $totalapor100+$valorapor100;
					//Aporte 1.50%
					$objPHPExcel->getActiveSheet()->setCellValue('K'.$salto, '=SUM(K'.$desde.':K'.$i.')');
					$celdaapor150 = $objPHPExcel->getActiveSheet()->getCell('K'.$salto);
					$valorapor150 = $celdaapor150->getCalculatedValue();
					$totalapor150 = $totalapor150+$valorapor150;
					//Recargo
					$objPHPExcel->getActiveSheet()->setCellValue('L'.$salto, '=SUM(L'.$desde.':L'.$i.')');
					$celdarecargo = $objPHPExcel->getActiveSheet()->getCell('L'.$salto);
					$valorrecargo = $celdarecargo->getCalculatedValue();
					$totalrecargo = $totalrecargo+$valorrecargo;
					//Total Depositado
					$objPHPExcel->getActiveSheet()->setCellValue('M'.$salto, '=SUM(M'.$desde.':M'.$i.')');
					$celdatotal = $objPHPExcel->getActiveSheet()->getCell('M'.$salto);
					$valortotal = $celdatotal->getCalculatedValue();
					$totalgeneral = $totalgeneral+$valortotal;
	
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$salto, 'Totales para la delegacion '.$valoractual);
					$objPHPExcel->getActiveSheet()->mergeCells('A'.$salto.':H'.$salto);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$salto)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$salto)->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getStyle('I'.$salto)->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getStyle('J'.$salto)->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getStyle('K'.$salto)->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getStyle('L'.$salto)->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getStyle('M'.$salto)->getFont()->setBold(true);
					$i=$i+1;
					$objPHPExcel->getActiveSheet()->setBreak('A'.$i, PHPExcel_Worksheet::BREAK_ROW);
					$desde=$i+1;
				}
			}
		
			//Aporte 0.60%
			$objPHPExcel->getActiveSheet()->setCellValue('I'.($fila+$filaagregada+1), '=SUM(I'.$desde.':I'.($fila+$filaagregada).')');
			$celdaapor060 = $objPHPExcel->getActiveSheet()->getCell('I'.($fila+$filaagregada+1));
			$valorapor060 = $celdaapor060->getCalculatedValue();
			$totalapor060 = $totalapor060+$valorapor060;
			//Aporte 1.00%
			$objPHPExcel->getActiveSheet()->setCellValue('J'.($fila+$filaagregada+1), '=SUM(J'.$desde.':J'.($fila+$filaagregada).')');
			$celdaapor100 = $objPHPExcel->getActiveSheet()->getCell('J'.($fila+$filaagregada+1));
			$valorapor100 = $celdaapor100->getCalculatedValue();
			$totalapor100 = $totalapor100+$valorapor100;
			//Aporte 1.50%
			$objPHPExcel->getActiveSheet()->setCellValue('K'.($fila+$filaagregada+1), '=SUM(K'.$desde.':K'.($fila+$filaagregada).')');
			$celdaapor150 = $objPHPExcel->getActiveSheet()->getCell('K'.($fila+$filaagregada+1));
			$valorapor150 = $celdaapor150->getCalculatedValue();
			$totalapor150 = $totalapor150+$valorapor150;
			//Recargo
			$objPHPExcel->getActiveSheet()->setCellValue('L'.($fila+$filaagregada+1), '=SUM(L'.$desde.':L'.($fila+$filaagregada).')');
			$celdarecargo = $objPHPExcel->getActiveSheet()->getCell('L'.($fila+$filaagregada+1));
			$valorrecargo = $celdarecargo->getCalculatedValue();
			$totalrecargo = $totalrecargo+$valorrecargo;
			//Total Depositado
			$objPHPExcel->getActiveSheet()->setCellValue('M'.($fila+$filaagregada+1), '=SUM(M'.$desde.':M'.($fila+$filaagregada).')');
			$celdatotal = $objPHPExcel->getActiveSheet()->getCell('M'.($fila+$filaagregada+1));
			$valortotal = $celdatotal->getCalculatedValue();
			$totalgeneral = $totalgeneral+$valortotal;
	
			$objPHPExcel->getActiveSheet()->setCellValue('A'.($fila+$filaagregada+1), 'Totales para la delegacion '.$valoractual);
			$objPHPExcel->getActiveSheet()->mergeCells('A'.($fila+$filaagregada+1).':H'.($fila+$filaagregada+1));
			$objPHPExcel->getActiveSheet()->getStyle('A'.($fila+$filaagregada+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle('A'.($fila+$filaagregada+1))->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('I'.($fila+$filaagregada+1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$objPHPExcel->getActiveSheet()->getStyle('I'.($fila+$filaagregada+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle('I'.($fila+$filaagregada+1))->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('J'.($fila+$filaagregada+1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$objPHPExcel->getActiveSheet()->getStyle('J'.($fila+$filaagregada+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle('J'.($fila+$filaagregada+1))->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('K'.($fila+$filaagregada+1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$objPHPExcel->getActiveSheet()->getStyle('K'.($fila+$filaagregada+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle('K'.($fila+$filaagregada+1))->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('L'.($fila+$filaagregada+1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$objPHPExcel->getActiveSheet()->getStyle('L'.($fila+$filaagregada+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle('L'.($fila+$filaagregada+1))->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('M'.($fila+$filaagregada+1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$objPHPExcel->getActiveSheet()->getStyle('M'.($fila+$filaagregada+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle('M'.($fila+$filaagregada+1))->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->setBreak('A'.($fila+$filaagregada+1), PHPExcel_Worksheet::BREAK_ROW);
			
			$objPHPExcel->getActiveSheet()->setCellValue('I'.($fila+$filaagregada+2), $totalapor060);
			$objPHPExcel->getActiveSheet()->setCellValue('J'.($fila+$filaagregada+2), $totalapor100);
			$objPHPExcel->getActiveSheet()->setCellValue('K'.($fila+$filaagregada+2), $totalapor150);
			$objPHPExcel->getActiveSheet()->setCellValue('L'.($fila+$filaagregada+2), $totalrecargo);
			$objPHPExcel->getActiveSheet()->setCellValue('M'.($fila+$filaagregada+2), $totalgeneral);
			$objPHPExcel->getActiveSheet()->setCellValue('A'.($fila+$filaagregada+2), 'Totales Generales');
			$objPHPExcel->getActiveSheet()->mergeCells('A'.($fila+$filaagregada+2).':H'.($fila+$filaagregada+2));
			$objPHPExcel->getActiveSheet()->getStyle('A'.($fila+$filaagregada+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle('A'.($fila+$filaagregada+2))->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('I'.($fila+$filaagregada+2))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$objPHPExcel->getActiveSheet()->getStyle('I'.($fila+$filaagregada+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle('I'.($fila+$filaagregada+2))->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('J'.($fila+$filaagregada+2))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$objPHPExcel->getActiveSheet()->getStyle('J'.($fila+$filaagregada+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle('J'.($fila+$filaagregada+2))->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('K'.($fila+$filaagregada+2))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$objPHPExcel->getActiveSheet()->getStyle('K'.($fila+$filaagregada+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle('K'.($fila+$filaagregada+2))->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('L'.($fila+$filaagregada+2))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$objPHPExcel->getActiveSheet()->getStyle('L'.($fila+$filaagregada+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle('L'.($fila+$filaagregada+2))->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('M'.($fila+$filaagregada+2))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			$objPHPExcel->getActiveSheet()->getStyle('M'.($fila+$filaagregada+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle('M'.($fila+$filaagregada+2))->getFont()->setBold(true);
		}
	}

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
	$redire = "Location://".$_SERVER['SERVER_NAME']."/usimra/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}
?>