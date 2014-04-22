<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspimSistemas.php");
include($libPath."fechas.php"); 
require_once($libPath."phpExcel/Classes/PHPExcel.php");

function eliminarDir($carpeta) {
	foreach(glob($carpeta."/*") as $archivos_carpeta) {
		echo $archivos_carpeta."<br>";
		if (is_dir($archivos_carpeta)) {
			eliminarDir($archivos_carpeta);
		} else {
			unlink($archivos_carpeta);
		}
	}
	rmdir($carpeta);
	echo "<br>";
}

var_dump($_POST);
$periodo = explode('-',$_POST['periodo']);
$mes = $periodo[0];
$anio = $periodo[1];
$fecha = $anio."-".$mes."-01";
$fechaLimite = date('Y-m-j',strtotime('+1 month',strtotime ($fecha)));
print("MES A REALIZAR: ".$mes." - ".$anio." CON FECHA REGISTRO < ".$fechaLimite."<br><br>");
$maquina = $_SERVER['SERVER_NAME'];
$carpeta = $mes.$anio;

if(strcmp("localhost",$maquina) == 0) {
	$direArc = $_SERVER['DOCUMENT_ROOT']."/ospim/sistemas/padrones/archivos/".$carpeta;
} else {
	//$direArc="/home/sistemas/Documentos/Liquidaciones/Preliquidaciones/PruebasLiq/".$nombreArc;
}

eliminarDir($direArc);
mkdir($direArc);

$finalFor = sizeof($_POST) - 2;
$datos = array_values($_POST);
for ($f = 0; $f < $finalFor; $f++) {
	$presta = $datos[$f];
	
	$nomExcelTitu = $presta."T".$mes.$anio.".xls";
	$direCompletaTitulares = $direArc."/".$nomExcelTitu;
	
	$nomExcelFami = $presta."F".$mes.$anio.".xls";
	$direCompletaFamiliares = $direArc."/".$nomExcelFami;
	
	$nomTxtTeso = $presta."D".$mes.$anio.".txt";
	$direCompletaTesoreria = $direArc."/".$nomTxtTeso;
	
	$nomZip = $presta.$mes.$anio.".zip";
	$direCompletaZip = $direArc."/".$nomZip;
	
	try {
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
		
		$sqlDelega = "SELECT * FROM prestadelega where codigopresta = $presta";
		print($sqlDelega."<br>");
		$resPresta = mysql_query($sqlDelega,$db);
		$totalizador = array();
		while($rowPresta = mysql_fetch_array($resPresta)) { 
			$totalTituXDelega = 0;
			$totalFamiXDelega = 0;
			$delega = $rowPresta['codidelega'];
			
			$sqlTitulares = "SELECT t.*, l.nomlocali, p.descrip as nomprovin, e.nombre as nomempresa FROM titulares t, localidades l, provincia p, empresas e where t.codidelega = $delega and t.cantidadcarnet != 0 and t.fecharegistro < '$fechaLimite' and t.codlocali = l.codlocali and t.codprovin = p.codprovin and t.cuitempresa = e.cuit";
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
				$sqlFamiliares = "SELECT f.*, p.descrip as parentezco FROM familiares f, parentesco p where f.nroafiliado = $nroafil and f.cantidadcarnet != 0 and f.fecharegistro < '$fechaLimite' and f.tipoparentesco = p.codparent";
				print($sqlFamiliares."<br>");
				$resFamiliares = mysql_query($sqlFamiliares, $db);
				while($rowFamiliares = mysql_fetch_array($resFamiliares)) {
					$cuerpoFamilia[$filaFamilia] = array('nroafil' => $rowFamiliares['nroafiliado'], 'parentezco' => $rowFamiliares['parentezco'], 'nombre' => $rowFamiliares['apellidoynombre'], 'tipdoc' => $rowFamiliares['tipodocumento'], 'numdoc' => $rowFamiliares['nrodocumento'], 'fecnac' => invertirFecha($rowFamiliares['fechanacimiento']), 'sexo' => $rowFamiliares['sexo']);
					$filaFamilia++;
					$totalFamiXDelega++;
				}
			}
			$totalDele = $totalTituXDelega + $totalFamiXDelega;
			$totalizador[$delega] = array('delega' => $delega, 'tottit' => $totalTituXDelega, 'totfam' => $totalFamiXDelega, "total" => $totalDele);
		}
		$objPHPExcel->getActiveSheet()->getStyle('A1:M'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);	
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
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$fila, $familiar['parentezco']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$fila, $familiar['nombre']);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$fila, $familiar['tipdoc']);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$fila, $familiar['numdoc']);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$fila, $familiar['fecnac']);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$fila, $familiar['sexo']);
		}
		$objPHPExcel->getActiveSheet()->getStyle('A1:G'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);	
		$totalFamiliares = $fila;
	
		$totalBeneficiarios = $totalTitulares + $totalFamiliares;
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save($direCompletaFamiliares);
		$objPHPExcel->disconnectWorksheets();
		unset($objWriter, $objPHPExcel);
		//********************************************
		
		//ARCHIVO TOTALIZADOR PARA TESORERIA
		var_dump($totalizador);
		print("<br>TOTAL ARCHIVO PRESTADOR $presta <br> Total Titulares: $totalTitulares - Total Familiares: $totalFamiliares <br><br>");
		$rowPresta = mysql_fetch_array(mysql_query("SELECT * FROM prestadores WHERE codigo = $presta", $db));
		$archivoTeso=fopen($direCompletaTesoreria,"a") or die("Problemas en la creacion");
		$primeraLineaTexto = "Prestador: ".$presta." - ".$rowPresta['nombre'];
		fputs($archivoTeso,$primeraLineaTexto);
		fputs($archivoTeso,"\r\n\r\n");
		$titulosTexto = "Delegacion  Titulares  Familiares  Beneficiarios";
		fputs($archivoTeso,$titulosTexto);
		fputs($archivoTeso,"\r\n");
		foreach ($totalizador as $totalDele) {
			$lineaDelega = "   ".$totalDele['delega']."      ".$totalDele['tottit']."           ".$totalDele['totfam']."            ".$totalDele['total'];
			fputs($archivoTeso,$lineaDelega);
			fputs($archivoTeso,"\r\n");
		}
		fputs($archivoTeso,"------------------------------------------------");
		fputs($archivoTeso,"\r\n");
		$totalesTexto= "TOTALES      $totalTitulares           $totalFamiliares           $totalBeneficiarios";
		fputs($archivoTeso,$totalesTexto);
		fclose($archivoTeso);
		//**********************************************
		
		//ACHIVO DE ZIP
		$zipPadron = new ZipArchive;
		if ($zipPadron->open($direCompletaZip, ZipArchive::CREATE) === TRUE) {
			$zipPadron->addFile($direCompletaTitulares, $nomExcelTitu);
			$zipPadron->addFile($direCompletaFamiliares, $nomExcelFami);
  			$zipPadron->close();
		} else {
			print("ERROR AL ABRIR EL ZIP <br>");
		}
		//*******************************************
		
		//ftp al servidor de internet.
		//*******************************************
		
		//subismo el registro de subida a la base de internet.
		//*******************************************
	
	} catch (Exception $e) {
		echo $e->getMessage();
		//$dbhInternet->rollback();
	}
}


?>