<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspimSistemas.php");
include($libPath."fechas.php"); 
require_once($libPath."phpExcel/Classes/PHPExcel.php");

$maquina = $_SERVER['SERVER_NAME'];
if(strcmp("localhost",$maquina) == 0) {
	$direArc = $_SERVER['DOCUMENT_ROOT']."/ospim/sistemas/padrones/archivos/";
} else {
	//$direArc="/home/sistemas/Documentos/Liquidaciones/Preliquidaciones/PruebasLiq/".$nombreArc;
}

var_dump($_POST);
print("CARPETA DE GUARDADO: ".$direArc."<br>");
$periodo = explode('-',$_POST['periodo']);
$mes = $periodo[0];
$anio = $periodo[1];
print("MES A REALIZAR: ".$mes." - ".$anio."<br><br>");
$finalFor = sizeof($_POST) - 2;
$datos = array_values($_POST);

for ($f = 0; $f < $finalFor; $f++) {
	$presta = $datos[$f];
	$nomExcelTitu = $presta."T".$mes.$anio.".xls";
	$direCompletaTitulares = $direArc.$nomExcelTitu;
	unlink($direCompletaTitulares);
	
	$nomExcelFami = $presta."F".$mes.$anio.".xls";
	$direCompletaFamiliares = $direArc.$nomExcelFami;
	unlink($direCompletaFamiliares);
	
	$nomTxtTeso = $presta."D".$mes.$anio.".txt";

	//Archivi titulares...
	try {
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
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Nro. AFiliado');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Nombre y Apellido');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Tipo Documento');
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Nro. Documento');
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Fecha Nacimiento');
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Sexo');
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Direccion');
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Localidad');
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Provincia');
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Telefono');
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('K1', 'Cod. Delegacion');
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('L1', 'CUIT empresa');
		$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('M1', 'Razon Social');
					
		$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
		$objPHPExcel->getDefaultStyle()->getFont()->setSize(12);
			
		$objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getFill()->getStartColor()->setARGB('FF808080');
		$objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$fila=1;
		$filaFamilia=0;	
		$objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
		$cuerpoFamilia = array();
		
		$sqlDelega = "SELECT * FROM prestadelega where codigopresta = $presta";
		print($sqlDelega."<br>");
		$resPresta = mysql_query($sqlDelega,$db);
		$totalizador = array();
		while($rowPresta = mysql_fetch_array($resPresta)) { 
			$totalTituXDelega = 0;
			$totalFamiXDelega = 0;
			$delega = $rowPresta['codidelega'];
			
			$sqlTitulares = "SELECT t.*, l.nomlocali, p.descrip as nomprovin, e.nombre as nomempresa FROM titulares t, localidades l, provincia p, empresas e where t.codidelega = $delega and t.codlocali = l.codlocali and t.codprovin = p.codprovin and t.cuitempresa = e.cuit";
			print($sqlTitulares."<br>");
			$resTitulares = mysql_query($sqlTitulares, $db);	
			
			while($rowTitulares = mysql_fetch_array($resTitulares)) {
				$fila++;
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$fila, $rowTitulares['nroafiliado']);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$fila, $rowTitulares['apellidoynombre']);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$fila, $rowTitulares['tipodocumento']);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$fila, $rowTitulares['nrodocumento']);
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$fila, invertirFecha($rowTitulares['fechanacimiento']));
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$fila, $rowTitulares['sexo']);
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$fila, $rowTitulares['domicilio']);
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$fila, $rowTitulares['nomlocali']);
				$objPHPExcel->getActiveSheet()->setCellValue('I'.$fila, $rowTitulares['nomprovin']);
				$objPHPExcel->getActiveSheet()->setCellValue('J'.$fila, $rowTitulares['telefono']);
				$objPHPExcel->getActiveSheet()->setCellValue('K'.$fila, $rowTitulares['codidelega']);
				$objPHPExcel->getActiveSheet()->setCellValue('L'.$fila, $rowTitulares['cuitempresa']);
				$objPHPExcel->getActiveSheet()->setCellValue('M'.$fila, $rowTitulares['nomempresa']);
				$totalTituXDelega++;
			
				$nroafil = $rowTitulares['nroafiliado'];
				$resFamiliares = mysql_query("SELECT f.*, p.descrip as parentezco FROM familiares f, parentesco p where f.nroafiliado = $nroafil and f.tipoparentesco = p.codparent",$db);
				while($rowFamiliares = mysql_fetch_array($resFamiliares)) {
					$cuerpoFamilia[$filaFamilia] = array('nroafil' => $rowFamiliares['nroafiliado'], 'parentezco' => $rowFamiliares['parentezco'], 'nombre' => $rowFamiliares['apellidoynombre'], 'tipdoc' => $rowFamiliares['tipodocumento'], 'numdoc' => $rowFamiliares['nrodocumento'], 'fecnac' => invertirFecha($rowFamiliares['fechanacimiento']), 'sexo' => $rowFamiliares['sexo']);
					$filaFamilia++;
					$totalFamiXDelega++;
				}
			}
			$totalizador[$delega] = array('delega' => $delega, 'tottit' => $totalTituXDelega, 'totfam' => $totalFamiXDelega);
		}
		$totalTitulares = $fila - 1;
			
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
			
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Nro. AFiliado');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Parentezco');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Nombre y Apellido');
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Tipo Documento');
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Nro. Documento');
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Fecha Nacimiento');
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Sexo');
			
		$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
		$objPHPExcel->getDefaultStyle()->getFont()->setSize(12);
			
		$objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFill()->getStartColor()->setARGB('FF808080');			
		$objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$fila=1;	
		$objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
			
		foreach($cuerpoFamilia as $familiar) {
		 	$fila++;
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$fila, $familiar['nroafil']);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$fila, $familiar['parentezco']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$fila, $familiar['nombre']);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$fila, $familiar['tipdoc']);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$fila, $familiar['numdoc']);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$fila, $familiar['fecnac']);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$fila, $familiar['sexo']);
		}
			
		$totalFamiliares = $fila - 1;
			
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save($direCompletaFamiliares);
		$objPHPExcel->disconnectWorksheets();
		unset($objWriter, $objPHPExcel);
		//********************************************
		
		
		//aca tengo que crear el txt de totalizadores...
		var_dump($totalizador);
		print("<br>TOTAL ARCHIVOS <br> Total Titulares: $totalTitulares - Total Familiares: $totalFamiliares <br><br>");
		//**********************************************
		
		//aca tenemos que zipiar a un archivo los xls.
		//*******************************************
		
		//ftp al servidor de interent.
		//*******************************************
		
		//subismo el registro de subida a la base de internet.
		//*******************************************
	
	} catch (PDOException $e) {
		echo $e->getMessage();
		$dbh->rollback();
	}
}


?>