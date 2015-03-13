<?php
		//ARCHIVO TITULARES
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator($_SESSION['usuario'])
									 ->setLastModifiedBy($_SESSION['usuario'])
									 ->setTitle($nomExcelTitu)
									 ->setSubject("Titulares Padron");
		$objPHPExcel->getActiveSheet()->setTitle($nomExcelTitu);
		$objPHPExcel->setActiveSheetIndex(0);
		$fechagenera=date("d/m/Y"); 
		$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LEGAL);	
		$objPHPExcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setVerticalCentered(false);
		
		// Setea tamaño de la columna y agrega datos a las celdas de titulos
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'NUMERO AFILIADO ANTERIOR');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'CUIL TITULAR');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'CUIL BENEFICIARIO');
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'TIPO DNI');
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'NRO DNI');
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'NOMBRE BENEFICIARIO');
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'FECHA NACIMIENTO');
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('H1', 'PARENTESCO');
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('I1', 'SEXO');
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('J1', 'FECHA ALTA');
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('K1', 'PROVINCIA');
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('L1', 'LOCALIDAD');
		$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(45);
		$objPHPExcel->getActiveSheet()->setCellValue('M1', 'CALLE');
		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('N1', 'NRO');
		$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('O1', 'PISO');
		$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('P1', 'DPTO');
		$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('Q1', 'PLAN');
			
		// Setea negrita relleno y alineamiento horizontal a las celdas de titulos
		$objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->getFill()->getStartColor()->setARGB('FF808080');
		
		$fila=1;
		$filaFamilia=1;	
		$objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
		$cuerpoFamilia = array();
		
		$sqlDelega = "SELECT * FROM capitadosdelega where codigopresta = $presta";
		//print($sqlDelega."<br><br>");
		$resPresta = mysql_query($sqlDelega,$db);
		$totalizador = array();
		while($rowPresta = mysql_fetch_array($resPresta)) { 
			$totalTituXDelega = 0;
			$totalFamiXDelega = 0;
			$delega = $rowPresta['codidelega'];
			
			$sqlTitulares = "SELECT 
								t.nroafiliado,
								t.cuil, 
								t.tipodocumento, 
								t.nrodocumento, 
								t.apellidoynombre, 
								t.fechanacimiento, 
								t.sexo,
								t.fechaobrasocial, 
								t.codprovin, 
								l.nomlocali,
								t.domicilio
							FROM titulares t, localidades l
							WHERE t.codidelega = $delega and t.cantidadcarnet != 0 and t.fecharegistro < '$fechaLimite' and t.codlocali = l.codlocali";
			//print($sqlTitulares."<br>");
			$resTitulares = mysql_query($sqlTitulares, $db);	
			
			
			
			while($rowTitulares = mysql_fetch_array($resTitulares)) {
				$fila++;
				
				$nroAfil = $rowTitulares['nroafiliado']."/0";
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$fila, $nroAfil);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$fila, $rowTitulares['cuil']);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$fila, $rowTitulares['tipodocumento']);
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$fila, $rowTitulares['nrodocumento']);
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$fila, utf8_encode($rowTitulares['apellidoynombre']));
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$fila, invertirFecha($rowTitulares['fechanacimiento']));
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$fila, 0);
				$objPHPExcel->getActiveSheet()->setCellValue('I'.$fila, $rowTitulares['sexo']);
				$objPHPExcel->getActiveSheet()->setCellValue('J'.$fila, invertirFecha($rowTitulares['fechaobrasocial']));
				$objPHPExcel->getActiveSheet()->setCellValue('K'.$fila, $rowTitulares['codprovin']);
				$objPHPExcel->getActiveSheet()->setCellValue('L'.$fila, utf8_encode($rowTitulares['nomlocali']));		
				$objPHPExcel->getActiveSheet()->setCellValue('M'.$fila, utf8_encode($rowTitulares['domicilio']));
				$totalTituXDelega++;
			
				$nroafil = $rowTitulares['nroafiliado'];
				$sqlFamiliares = "SELECT 
									f.nroafiliado, 
									f.nroorden,
									f.cuil,
									f.tipodocumento,
									f.nrodocumento, 
									f.apellidoynombre, 
									f.fechanacimiento, 
									f.tipoparentesco,
									f.sexo,
									f.fechaobrasocial
									FROM familiares f
									WHERE f.nroafiliado = $nroafil and f.cantidadcarnet != 0 and f.fecharegistro < '$fechaLimite'";
				//print($sqlFamiliares."<br>");
				$resFamiliares = mysql_query($sqlFamiliares, $db);
				
				
				while($rowFamiliares = mysql_fetch_array($resFamiliares)) {
					$nroAfiliadoFamiliar = $rowFamiliares['nroafiliado']."/".$rowFamiliares['nroorden'];
					$cuerpoFamilia[$filaFamilia] = array('nroafil' => $nroAfiliadoFamiliar,
														 'cuiltitular' => $rowTitulares['cuil'], 
														 'cuilbeneficiario' => $rowFamiliares['cuil'],
														 'tipdoc' => $rowFamiliares['tipodocumento'], 
														 'numdoc' => $rowFamiliares['nrodocumento'], 
														 'nombre' => $rowFamiliares['apellidoynombre'], 
														 'fecnac' => invertirFecha($rowFamiliares['fechanacimiento']), 
														 'tipoparentesco' => $rowFamiliares['tipoparentesco'], 
														 'sexo' => $rowFamiliares['sexo'], 
														 'fechaalta' => invertirFecha($rowFamiliares['fechaobrasocial']), 
														 'provincia' => $rowTitulares['codprovin'], 
														 'localidad' => utf8_encode($rowTitulares['nomlocali']), 
														 'domicilio' => utf8_encode($rowTitulares['domicilio']));
					$filaFamilia++;
					$totalFamiXDelega++;
				}
			}
			$totalDele = $totalTituXDelega + $totalFamiXDelega;
			$totalizador[$delega] = array('delega' => $delega, 'tottit' => $totalTituXDelega, 'totfam' => $totalFamiXDelega, "total" => $totalDele);
		}
		
		for($col = 'A'; $col !== 'Q'; $col++) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		}
		
		$totalTitulares = $fila;
			
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save($direCompletaTitulares);
		$objPHPExcel->disconnectWorksheets();
		unset($objWriter, $objPHPExcel);
		//*******************************************	
			
		//ARCHIVO FAMILIARES
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator($_SESSION['usuario'])
									 ->setLastModifiedBy($_SESSION['usuario'])
									 ->setTitle($nomExcelFami)
									 ->setSubject("Familares Padron");
		$objPHPExcel->getActiveSheet()->setTitle($nomExcelFami);
		$objPHPExcel->setActiveSheetIndex(0);
		$fechagenera=date("d/m/Y"); 
		$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LEGAL);	
		$objPHPExcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
		$objPHPExcel->getActiveSheet()->getPageSetup()->setVerticalCentered(false);
		
		// Setea tamaño de la columna y agrega datos a las celdas de titulos
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'NUMERO AFILIADO ANTERIOR');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'CUIL TITULAR');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'CUIL BENEFICIARIO');
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'TIPO DNI');
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'NRO DNI');
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'NOMBRE BENEFICIARIO');
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'FECHA NACIMIENTO');
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('H1', 'PARENTESCO');
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('I1', 'SEXO');
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('J1', 'FECHA ALTA');
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('K1', 'PROVINCIA');
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('L1', 'LOCALIDAD');
		$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(45);
		$objPHPExcel->getActiveSheet()->setCellValue('M1', 'CALLE');
		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('N1', 'NRO');
		$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('O1', 'PISO');
		$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('P1', 'DPTO');
		$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('Q1', 'PLAN');
			
		// Setea negrita relleno y alineamiento horizontal a las celdas de titulos
		$objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->getFill()->getStartColor()->setARGB('FF808080');
			
		$fila=1;	
		$objPHPExcel->getDefaultStyle()->getFont()->setSize(10);	
		foreach($cuerpoFamilia as $familiar) {
		 	$fila++;
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$fila, $familiar['nroafil']);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$fila, $familiar['cuiltitular']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$fila, $familiar['cuilbeneficiario']);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$fila, $familiar['tipdoc']);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$fila, $familiar['numdoc']);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$fila, utf8_encode($familiar['nombre']));
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$fila, $familiar['fecnac']);
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$fila, $familiar['tipoparentesco']);
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$fila, $familiar['sexo']);
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$fila, $familiar['fechaalta']);
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$fila, $familiar['provincia']);
			$objPHPExcel->getActiveSheet()->setCellValue('L'.$fila, $familiar['localidad']);
			$objPHPExcel->getActiveSheet()->setCellValue('M'.$fila, $familiar['domicilio']);		
		}
		
		for($col = 'A'; $col !== 'M'; $col++) {
			$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
		}
				
		$totalFamiliares = $fila;
	
		$totalBeneficiarios = $totalTitulares + $totalFamiliares;
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save($direCompletaFamiliares);
		$objPHPExcel->disconnectWorksheets();
		unset($objWriter, $objPHPExcel);
		//********************************************
		
		//ACHIVO DE ZIP
		if (file_exists($direCompletaZip)) {
			unlink($direCompletaZip);
		}
		$zipPadron = new ZipArchive;
		if ($zipPadron->open($direCompletaZip, ZipArchive::CREATE) === TRUE) {
			$zipPadron->addFile($direCompletaTitulares, $nomExcelTitu);
			$zipPadron->addFile($direCompletaFamiliares, $nomExcelFami);
  			$zipPadron->close();
			unlink($direCompletaTitulares);
			unlink($direCompletaFamiliares);
		} else {
			$descriError = "ERROR AL CREAR EL ZIP";
			$arrayResultados[$presta] = array('presta' => $presta, 'descri' => $descriError);			
			//print("$descriError<br>");
		}
		//********************************************/

?>