<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
set_time_limit(0);
include($libPath."controlSessionOspimSistemas.php");
include($libPath."fechas.php"); 
include($libPath."ftpOspim.php"); 
include($libPath."funcionesFTP.php"); 
include($libPath."claves.php");
require_once($libPath."phpExcel/Classes/PHPExcel.php");

function eliminarDir($carpeta) {
	foreach(glob($carpeta."/*") as $archivos_carpeta) {
		if (is_dir($archivos_carpeta)) {
			eliminarDir($archivos_carpeta);
		} else {
			unlink($archivos_carpeta);
		}
	}
	rmdir($carpeta);
}

$periodo = explode('-',$_POST['periodo']);
$mes = $periodo[0];
$mes = str_pad($periodo[0],2,'0',STR_PAD_LEFT);
$anio = $periodo[1];
$fecha = $anio."-".$mes."-01";
$fechaLimite = date('Y-m-j',strtotime('+1 month',strtotime ($fecha)));
$maquina = $_SERVER['SERVER_NAME'];
$carpeta = $mes.$anio;

if(strcmp("localhost",$maquina) == 0) {
	$direArc = $_SERVER['DOCUMENT_ROOT']."/ospim/sistemas/padrones/archivos/".$carpeta;
} else {
	//$direArc="/home/sistemas/Documentos/Liquidaciones/Preliquidaciones/PruebasLiq/".$nombreArc;
}

//eliminarDir($direArc);
if (!file_exists($direArc)) {
	mkdir($direArc);
}

$finalFor = sizeof($_POST) - 2;
$datos = array_values($_POST);
$arrayResultados = array();

for ($f = 0; $f < $finalFor; $f++) {
	$presta = $datos[$f];
	$descriError = "CREACION Y SUBIDA DE PADRON CORRECTA";
	$arrayResultados[$presta] = array('presta' => $presta, 'descri' => $descriError);
	
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
		//print($sqlDelega."<br><br>");
		$resPresta = mysql_query($sqlDelega,$db);
		$totalizador = array();
		while($rowPresta = mysql_fetch_array($resPresta)) { 
			$totalTituXDelega = 0;
			$totalFamiXDelega = 0;
			$delega = $rowPresta['codidelega'];
			
			$sqlTitulares = "SELECT t.*, l.nomlocali, p.descrip as nomprovin, e.nombre as nomempresa FROM titulares t, localidades l, provincia p, empresas e where t.codidelega = $delega and t.cantidadcarnet != 0 and t.fecharegistro < '$fechaLimite' and t.codlocali = l.codlocali and t.codprovin = p.codprovin and t.cuitempresa = e.cuit";
			//print($sqlTitulares."<br>");
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
				//print($sqlFamiliares."<br>");
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
		
		if (file_exists($direCompletaTesoreria)) {
			unlink($direCompletaTesoreria);
		}
		//var_dump($totalizador);
		//print("<br>TOTAL ARCHIVO PRESTADOR $presta <br> Total Titulares: $totalTitulares - Total Familiares: $totalFamiliares <br><br>");
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
			
		//CONTROLO QUE NO HAYA UNA BAJADA PARA ESTE PRESTA Y ESTE PERIDOD
		$hostOspim = "localhost"; //para las pruebas...
		$dbhInternet = new PDO("mysql:host=$hostOspim;dbname=$baseOspimPrestadores",$usuarioOspim ,$claveOspim);
   		$dbhInternet->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbhInternet->beginTransaction();
		
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();
		
		$sqlConsultaBajada = "SELECT count(*) FROM descarga WHERE codigo = $presta and anopad = $anio and mespad = $mes";
		//print($sqlConsultaBajada."<br>");
		$resConsultaBajada = $dbhInternet->query($sqlConsultaBajada);
		$canConsultaBajada = $resConsultaBajada->fetchColumn();
		
		if ($canConsultaBajada == 0) {
			if (file_exists($direCompletaZip) && file_exists($direCompletaTesoreria)) {
				$carpetaFtp = $presta."C23".$presta;
				$pathOspim = "/public_html/prestadores/prueba/$carpetaFtp";
				$resultado = SubirArchivo($direCompletaZip, $nomZip, $pathOspim);
				if ($resultado) {
					$subidaOk = 1;
					$fecsub = date('Y-m-j');
					$horsub = date("H:i:s");
					
					$sqlEliminaSubidaInternet = "DELETE FROM subida WHERE codigo = $presta and anopad = $anio and mespad = $mes";
					//print($sqlEliminaSubidaInternet."<br>");
					$dbhInternet->exec($sqlEliminaSubidaInternet);
				
					$sqlEliminaSubidaMadera = "DELETE FROM subidapadronprestadores WHERE codigoprestador = $presta and anopadron = $anio and mespadron = $mes";
					//print($sqlEliminaSubidaMadera."<br>");
					$dbh->exec($sqlEliminaSubidaMadera);
					
					$sqlInsertInternet = "INSERT INTO subida VALUE($presta, $mes, $anio, '$fecsub', '$horsub', $totalTitulares, $totalFamiliares, $totalBeneficiarios, 'N')";
					//print($sqlInsertInternet."<br>");
					$dbhInternet->exec($sqlInsertInternet);

					$sqlInsertMadera = "INSERT INTO subidapadronprestadores VALUE($presta, $mes, $anio, '$fecsub', '$horsub', $totalTitulares, $totalFamiliares, $totalBeneficiarios)";
					//print($sqlInsertMadera."<br>");
					$dbh->exec($sqlInsertMadera);
					
					$dbhInternet->commit();
					$dbh->commit();
					
					//print("<br>");
				} else {
					$subidaOk = 2;
					$descriError = "ERROR AL SUBIR EL ZIP A OSPIM";
					$arrayResultados[$presta] = array('presta' => $presta, 'descri' => $descriError);
					//print("$descriError<br><br>");
				}
			}
		} else {	
			$descriError = "EXISTE UNA DESCARGA PARA ESTE PERIODO ($mes-$anio) Y ESTE PRESTADOR ($presta) NO SE SUBIRA NUEVAMENTE";
			$arrayResultados[$presta] = array('presta' => $presta, 'descri' => $descriError);
			//print("$descriError<br><br>");
		}
	} catch (PDOException $e) {
		$descriError = $e->getMessage();
		$arrayResultados[$presta] = array('presta' => $presta, 'descri' => $descriError);
		//print("$descriError<br><br>");
		$dbh->rollback();
		$dbhInternet->rollback();
	}	
}

//cambio la hora de secion por ahora para no perder la misma
$ahora = date("Y-n-j H:i:s"); 
$_SESSION["ultimoAcceso"] = $ahora;
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Generacion de Padrones :.</title>
</head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>
<body bgcolor="#CCCCCC">
<div align="center">
  <p class="Estilo2"><span style="text-align:center">
    <input type="reset" name="volver" value="Volver" onclick="location.href = 'menuPadrones.php'" align="center"/>
  </span></p>
  <p class="Estilo2">Resultado del Generacion de Padrones Período (<?php echo $mes." - ".$anio ?>) </p>
			  <table width="800" border="1" align="center">
					<tr>
					  <th>Prestador</th>
					  <th>Descripcion</th>
					</tr>
			  <?php foreach ($arrayResultados as $resultado) {
						print("<tr align='center'>");
						print("<td>".$resultado['presta']."</td>");
						print("<td>".$resultado['descri']."</td>");
						print("</tr>");
					}
			  ?>
			</table>
			   <p class="Estilo2">Información de Control para Tesorería </p>
			  <p><input type="button" name="imprimir" value="Imprimir" onclick="window.print();" align="center"/></p>
</div>
</body>
</html>
