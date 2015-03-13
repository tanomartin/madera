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
		
		$fila=0;
		$filaFamilia=0;	
		$objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
		$cuerpoFamilia = array();
		
		$sqlDelega = "SELECT * FROM capitadosdelega where codigopresta = $presta";
		$resPresta = mysql_query($sqlDelega,$db);
		$totalizador = array();
		while($rowPresta = mysql_fetch_array($resPresta)) { 
			$totalTituXDelega = 0;
			$totalFamiXDelega = 0;
			$delega = $rowPresta['codidelega'];
			
			$sqlTitulares = "SELECT
								t.nroafiliado, 
								t.apellidoynombre, 
								t.tipodocumento, 
								t.nrodocumento, 
								t.fechanacimiento, 
								t.sexo, 
								t.domicilio, 
								l.nomlocali, 
								t.codprovin, 
								t.codidelega,
								t.cuitempresa, 
								e.nombre as nomempresa 
							FROM titulares t, localidades l, empresas e 
							WHERE t.codidelega = $delega and t.cantidadcarnet != 0 and t.fecharegistro < '$fechaLimite' and t.codlocali = l.codlocali and t.cuitempresa = e.cuit";
			//print($sqlTitulares."<br>");
			$resTitulares = mysql_query($sqlTitulares, $db);	
			
			while($rowTitulares = mysql_fetch_array($resTitulares)) {
				$fila++;
				
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$fila, $rowTitulares['nroafiliado']);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$fila, utf8_encode($rowTitulares['apellidoynombre']));	
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$fila, $rowTitulares['tipodocumento']);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$fila, $rowTitulares['nrodocumento']);
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$fila, invertirFecha($rowTitulares['fechanacimiento']));
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$fila, $rowTitulares['sexo']);
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$fila, utf8_encode($rowTitulares['domicilio']));
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$fila, utf8_encode($rowTitulares['nomlocali']));
				$objPHPExcel->getActiveSheet()->setCellValue('I'.$fila, $rowTitulares['codprovin']);
				$objPHPExcel->getActiveSheet()->setCellValue('J'.$fila, $rowTitulares['cuitempresa']);
				$objPHPExcel->getActiveSheet()->setCellValue('K'.$fila, $rowTitulares['codidelega']);	
				$objPHPExcel->getActiveSheet()->setCellValue('L'.$fila, utf8_encode($rowTitulares['nomempresa']));
				$totalTituXDelega++;
			
				$nroafil = $rowTitulares['nroafiliado'];
				$sqlFamiliares = "SELECT 
									f.nroafiliado, 
									f.tipoparentesco, 
									f.apellidoynombre, 
									f.tipodocumento, 
									f.nrodocumento, 
									f.fechanacimiento, 
									f.sexo 
								FROM familiares f
								WHERE f.nroafiliado = $nroafil and f.cantidadcarnet != 0 and f.fecharegistro < '$fechaLimite'";
				//print($sqlFamiliares."<br>");
				$resFamiliares = mysql_query($sqlFamiliares, $db);
				while($rowFamiliares = mysql_fetch_array($resFamiliares)) {
					$cuerpoFamilia[$filaFamilia] = array('nroafil' => $rowFamiliares['nroafiliado'], 'tipoparentesco' => $rowFamiliares['tipoparentesco'], 'nombre' => $rowFamiliares['apellidoynombre'], 'tipdoc' => $rowFamiliares['tipodocumento'], 'numdoc' => $rowFamiliares['nrodocumento'], 'fecnac' => invertirFecha($rowFamiliares['fechanacimiento']), 'sexo' => $rowFamiliares['sexo']);
					$filaFamilia++;
					$totalFamiXDelega++;
				}
			}
			$totalDele = $totalTituXDelega + $totalFamiXDelega;
			$totalizador[$delega] = array('delega' => $delega, 'tottit' => $totalTituXDelega, 'totfam' => $totalFamiXDelega, "total" => $totalDele);
		}
		
		for($col = 'A'; $col !== 'M'; $col++) {
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
			
		$fila=0;	
		$objPHPExcel->getDefaultStyle()->getFont()->setSize(10);	
		foreach($cuerpoFamilia as $familiar) {
		 	$fila++;
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$fila, $familiar['nroafil']);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$fila, $familiar['tipoparentesco']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$fila, utf8_encode($familiar['nombre']));
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$fila, $familiar['tipdoc']);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$fila, $familiar['numdoc']);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$fila, $familiar['fecnac']);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$fila, $familiar['sexo']);
		}

		for($col = 'A'; $col !== 'G'; $col++) {
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
		//*******************************************

?>