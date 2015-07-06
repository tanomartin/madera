<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
require_once($libPath."phpExcel/Classes/PHPExcel.php");
set_time_limit(0);
$maquina = $_SERVER['SERVER_NAME'];
$fechagenera = date("d-m-Y");
$horagenera = date("hms");
$inWhere = "in (";

foreach($_POST as $delega) {
	$inWhere .= $delega.',';
}
$inWhere = substr($inWhere,0,-1);
$inWhere .= ')';

if(strcmp("localhost",$maquina)==0)
	$archivo_path="informes/";
else
	$archivo_path="/home/sistemas/Documentos/Repositorio/Afiliaciones/";

$sqlTitulares = "SELECT t.codprovin,YEAR(CURDATE())-YEAR(t.fechanacimiento)+IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(t.fechanacimiento,'%m-%d'), 0, -1) AS `edadactual`,
						t.nroafiliado,
						t.sexo
				FROM titulares t
				WHERE t.codprovin $inWhere";

$sqlFamiliares = "SELECT
					t.codprovin,
					YEAR(CURDATE())-YEAR(f.fechanacimiento)+IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(f.fechanacimiento,'%m-%d'), 0, -1) AS `edadactual`,
					f.nroafiliado,
					f.nroorden,
					f.sexo
				  FROM familiares f, titulares t
				  WHERE
					f.nroafiliado = t.nroafiliado and
					t.codprovin $inWhere";

$sqlProvincias = "SELECT * FROM provincia where codprovin > 0 and codprovin < 99";

//print($sqlTitulares."<br>");
//print($sqlFamiliares."<br>");
//print($sqlProvincias."<br>");

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	
	$resultTitulares = $dbh->query($sqlTitulares);
	$resultFamiliares = $dbh->query($sqlFamiliares);
	$resultProvincias = $dbh->query($sqlProvincias);
	
	$resultadoFinalHombres = array();
	$resultadoFinalMujeres = array();
	
	foreach ($resultProvincias as $provincia){ 
		for($i=0; $i<100; $i++) {
			$resultadoFinalHombres[$provincia['codprovin']][$i] = 0;
			$resultadoFinalMujeres[$provincia['codprovin']][$i] = 0;
		}	
	}
	
	if ($resultTitulares){
		foreach ($resultTitulares as $titulares){ 
			$indiceProvincia = str_pad($titulares['codprovin'],2,'0',STR_PAD_LEFT);
			if ($titulares['sexo'] == 'M') {
				$resultadoFinalHombres[$indiceProvincia][$titulares['edadactual']] += 1;
			} else {
				$resultadoFinalMujeres[$indiceProvincia][$titulares['edadactual']] += 1;
			}
		}
	}

	if ($resultFamiliares){
		foreach ($resultFamiliares as $familiares){ 
			$indiceProvincia = str_pad($familiares['codprovin'],2,'0',STR_PAD_LEFT);
			if ($familiares['sexo'] == 'M') {
				$resultadoFinalHombres[$indiceProvincia][$familiares['edadactual']] += 1;
			} else {
				$resultadoFinalMujeres[$indiceProvincia][$familiares['edadactual']] += 1;
			}
		}
	}
	
	//print("MASCULINO<br>");
	//var_dump($resultadoFinalHombres);
	//print("FEMENINO<br>");
	//var_dump($resultadoFinalMujeres);	
		
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->getProperties()->setCreator($_SESSION['usuario'])
								 ->setLastModifiedBy($_SESSION['usuario'])
								 ->setTitle("Afiliados por grupo etario")
								 ->setSubject("Modulo de Afiliados")
								 ->setDescription("Informe de Afiliados por grupo etario.")
								 ->setCategory("Informes del Sistema de Afiliados");
	$positionInExcel=0;
	$resultProvincias = $dbh->query($sqlProvincias);
	foreach ($resultProvincias as $provincia){ 
		$nomprovin = $provincia['descrip'];
		$codprovin = $provincia['codprovin'];
		$objPHPExcel->createSheet($positionInExcel);
		$objPHPExcel->setActiveSheetIndex($positionInExcel);
		$objPHPExcel->getActiveSheet()->setTitle("$nomprovin");
		$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader("&L&BO.S.P.I.M.&G&C&H& Grupo etario $nomprovin al $fechagenera&R&B".$fechagenera);
	
		// Setea en configuracion de pagina orientacion y tamaño
		$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_DEFAULT);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LEGAL);	
		
		// Setea en configuracion de pagina centrado horizontal y vertical
		$objPHPExcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setVerticalCentered(false);
	
		// Setea en configuracion de pagina repetir filas en extremo superior
		$objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 1);
		
		// Setea tamaño de la columna y agrega datos a las celdas de titulos
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'EDAD');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'MASCULINO');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'FEMENINO');
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'TOTAL');
		
		// Setea negrita relleno y alineamiento horizontal a las celdas de titulos
		$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFill()->getStartColor()->setARGB('FF808080');
		
		if (in_array($codprovin,$_POST)) {
			$arrayEdades = $resultadoFinalHombres[$codprovin];
			$fila = 1;
			foreach($arrayEdades as $key => $edad) {
				$fila++;
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$fila, key($arrayEdades));
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$fila, $edad);
				next($arrayEdades);
			}
			$fila = 1;
			$arrayEdades = $resultadoFinalMujeres[$codprovin];
			foreach($arrayEdades as $key => $edad) {
				$fila++;
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$fila, $edad);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$fila, '=SUM(B'.$fila.':C'.$fila.')');
				next($arrayEdades);
			}
		} else {
			$fila = 1;
			for($i=0; $i<100; $i++) {
				$fila++;
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$fila, $i);
			}
		}
		
		$objPHPExcel->getActiveSheet()->getStyle('A1:D'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
		$objPHPExcel->getActiveSheet()->getStyle('A1:D'.$fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
		$positionInExcel++;
	}
	
	$objPHPExcel->setActiveSheetIndex($positionInExcel);
	$objPHPExcel->getActiveSheet()->setTitle("TOTALES");
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader("&L&BO.S.P.I.M.&G&C&H& TOTALES Grupo etario al $fechagenera&R&B".$fechagenera);
	
	// Setea en configuracion de pagina orientacion y tamaño
	$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_DEFAULT);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LEGAL);	
		
	// Setea en configuracion de pagina centrado horizontal y vertical
	$objPHPExcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setVerticalCentered(false);
	
	// Setea en configuracion de pagina repetir filas en extremo superior
	$objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 1);
		
	// Setea tamaño de la columna y agrega datos a las celdas de titulos
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
	$objPHPExcel->getActiveSheet()->setCellValue('A1', 'EDAD');
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
	$objPHPExcel->getActiveSheet()->setCellValue('B1', 'MASCULINO');
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
	$objPHPExcel->getActiveSheet()->setCellValue('C1', 'FEMENINO');
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
	$objPHPExcel->getActiveSheet()->setCellValue('D1', 'TOTAL');
	
	// Setea negrita relleno y alineamiento horizontal a las celdas de titulos
	$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFill()->getStartColor()->setARGB('FF808080');
	
	$fila = 1;
	for($i=0; $i<100; $i++) {
		$fila++;
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$fila, $i);
		$formulaTotalHombre = '=';
		$formulaTotalMujeres = '=';
		$resultProvincias = $dbh->query($sqlProvincias);
		foreach ($resultProvincias as $provincia){ 
			$formulaTotalHombre .= "'".$provincia['descrip']."'".'!B'.$fila.'+';
			$formulaTotalMujeres .= "'".$provincia['descrip']."'".'!C'.$fila.'+';
		}
		$formulaTotalHombre = substr($formulaTotalHombre,0,-1);
		$formulaTotalMujeres = substr($formulaTotalMujeres,0,-1);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$fila, $formulaTotalHombre);
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$fila, $formulaTotalMujeres);
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$fila, '=SUM(B'.$fila.':C'.$fila.')');
	}
	$objPHPExcel->getActiveSheet()->getStyle('A1:D'.$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	
	$objPHPExcel->getActiveSheet()->getStyle('A1:D'.$fila)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	
	// Guarda Archivo en Formato Excel 2003
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$archivo_name = $archivo_path."Beneficiarios grupo etario al $fechagenera $horagenera.xls";
	$objWriter->save($archivo_name);
	$objPHPExcel->disconnectWorksheets();
	unset($objWriter, $objPHPExcel);
	
	$dbh->commit();
	$pagina = "beneficiariosPorGrupoEtario.php?error=0";
	Header("Location: $pagina");
	
} catch (PDOException $e) {
	$error = $e->getMessage();
	$dbh->rollback();
	$pagina = "beneficiariosPorGrupoEtario.php?error=1&mensaje=$error";
	Header("Location: $pagina");
}

?>