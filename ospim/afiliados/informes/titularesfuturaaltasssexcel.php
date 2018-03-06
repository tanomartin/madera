<?php $libPath = $_SERVER ['DOCUMENT_ROOT'] . "/madera/lib/";
include ($libPath . "controlSessionOspim.php");
require_once($libPath."phpExcel/Classes/PHPExcel.php");
set_time_limit(0);

$maquina = $_SERVER['SERVER_NAME'];
$fechagenera = date("d-m-Y");
$time = date("His");
$arrayDelegacion = explode("-",$_POST['delegacion']);
$codidelega = $arrayDelegacion[0];
$nomdelega = $arrayDelegacion[1];


$sqlMesPadron = "SELECT * FROM padronssscabecera c WHERE fechacierre is null ORDER BY c.id DESC LIMIT 1";
$resMesPadron = mysql_query ( $sqlMesPadron, $db );
$rowMesPadron = mysql_fetch_assoc ($resMesPadron);

$sqlEmpresasCuit = "SELECT e.cuit,j.codidelega FROM empresas e, jurisdiccion j WHERE e.cuit = j.cuit order by e.cuit, j.disgdinero;";
$resEmpresasCuit = mysql_query ( $sqlEmpresasCuit, $db );
$arrayCuit = array();
while ($rowEmpresasCuit = mysql_fetch_assoc ($resEmpresasCuit)) {
	$arrayCuit[$rowEmpresasCuit['cuit']] = $rowEmpresasCuit;
}

//DEJO SOLO LA DELEGACION PEDIDA
$whereCuit = "(";
foreach ($arrayCuit as $empresas) {
	if ($empresas['codidelega'] == $codidelega) {
		$whereCuit .= "'".$empresas['cuit']."',";
	} else {
		unset($empresas[$empresas['cuit']]);
	}
}
$whereCuit = substr($whereCuit, 0, -1);
$whereCuit .= ")";

$arrayAlta = array();
if ($whereCuit != ")") {
	$arrayTipo = array();
	$sqlTituSSS = "SELECT DISTINCT p.cuiltitular, p.nrodocumento, p.tipodocumento, p.sexo,
						  p.cuit, p.apellidoynombre, p.tipotitular, pr.descrip as provincia, 
						  p.osopcion, t.descrip, p.calledomicilio, p.telefono,
						  p.localidad, p.codigopostal, DATE_FORMAT(p.fechanacimiento, '%d/%I/%Y') as fechanacimiento,
						  empresas.nombre as empresa, 
						  empresas.domilegal as domiempresa,
						  empresas.numpostal as postalempresa,
						  empresas.ddn1 as ddn1, empresas.telefono1 as telefono1, 
					      empresas.ddn2 as ddn2, empresas.telefono2 as telefono2,
						  provincia.descrip as proviempresa, localidades.nomlocali as localiempresa
					FROM tipotitular t, provincia pr, padronsss p
					LEFT JOIN empresas ON p.cuit = empresas.cuit
				    LEFT JOIN provincia ON empresas.codprovin = provincia.codprovin
				    LEFT JOIN localidades ON empresas.codlocali = localidades.codlocali
					WHERE p.cuit in $whereCuit and 
						  p.tipotitular in (0,2,4,5,8) and 
						  p.osopcion = 0 and 
						  p.parentesco = 0 and 
						  p.tipotitular = t.codtiptit and
						  p.codprovin = pr.codprovin";
	$resTituSSS = mysql_query ( $sqlTituSSS, $db );
	$arrayTituSSS = array();
	while ($rowTituSSS = mysql_fetch_assoc ($resTituSSS)) {
		$arrayTituSSS[$rowTituSSS['cuiltitular']] = $rowTituSSS;
		$arrayTipo[$rowTituSSS['cuiltitular']] = $rowTituSSS['descrip'];
	}

	$sqlTitu = "SELECT DISTINCT t.cuil, t.cuitempresa, t.nrodocumento, t.nroafiliado, p.descrip  FROM titulares t, tipotitular p WHERE t.situaciontitularidad = p.codtiptit";
	$resTitu = mysql_query ( $sqlTitu, $db );
	$arrayTitu = array();
	while ($rowTitu = mysql_fetch_assoc ($resTitu)) {
		$arrayTitu[$rowTitu['cuil']] = $rowTitu['nrodocumento'];
		$arrayTipo[$rowTitu['cuil']] = $rowTitu['descrip'];
	}

	$sqlTitu = "SELECT DISTINCT t.cuil, t.nrodocumento, t.nroafiliado, p.descrip FROM titularesdebaja t, tipotitular p WHERE t.situaciontitularidad = p.codtiptit";
	$resTitu = mysql_query ( $sqlTitu, $db );
	$arrayTituBaja = array();
	while ($rowTitu = mysql_fetch_assoc ($resTitu)) {
		$arrayTituBaja[$rowTitu['cuil']] = $rowTitu['nrodocumento'];
		$arrayTipo[$rowTitu['cuil']] = $rowTitu['descrip'];
	}

	$arrayTiposAceptados = array(0,2,4,5,8);

	foreach ($arrayTituSSS as $cuil => $titu) {
		if (in_array($titu['tipotitular'],$arrayTiposAceptados)) {
			if (!array_key_exists ($cuil , $arrayTitu)) {
				if (!array_key_exists ($cuil , $arrayTituBaja)) {
					if(!in_array($titu['nrodocumento'], $arrayTitu)) {
						if(!in_array($titu['nrodocumento'], $arrayTituBaja)) {
							$arrayAlta[$cuil] = $titu;
						}
					}
				}
			}
		}
	}
}

if (sizeof($arrayAlta) > 0) {
	if(strcmp("localhost",$maquina)==0)
		$archivo_path="informes/";
	else
		$archivo_path="/home/sistemas/Documentos/Repositorio/Afiliaciones/";
	
	$archivo_name = $archivo_path."Titulares Futura ALTA SSS - $nomdelega - al $fechagenera $time.xls";
	
	$objPHPExcelTitular = new PHPExcel();
	$objPHPExcelTitular->getProperties()->setCreator($_SESSION['usuario'])
										->setLastModifiedBy($_SESSION['usuario'])
										->setTitle("Titulares Futuras Alta SSS")
										->setSubject("Modulo de Afiliados")
										->setDescription("Futura Alta Titulares SSS")
										->setCategory("Informes del Sistema de Afiliados");
	$objPHPExcelTitular->getActiveSheet()->setTitle("Futuro SSS $codidelega al $fechagenera");
	$objPHPExcelTitular->setActiveSheetIndex(0);		
	$objPHPExcelTitular->getActiveSheet()->getHeaderFooter()->setOddHeader("&L&BO.S.P.I.M.&G&C&H&BTitulares futura alta delegacion $nomdelega ($codidelega) al $fechagenera&R&B".$fechagenera);
	$objPHPExcelTitular->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&R&BPagina &P de &N');
	$objPHPExcelTitular->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	$objPHPExcelTitular->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LEGAL);
	$objPHPExcelTitular->getActiveSheet()->getPageMargins()->setTop(0.50);
	$objPHPExcelTitular->getActiveSheet()->getPageMargins()->setRight(0);
	$objPHPExcelTitular->getActiveSheet()->getPageMargins()->setLeft(0);
	$objPHPExcelTitular->getActiveSheet()->getPageMargins()->setBottom(0.50);
	$objPHPExcelTitular->getActiveSheet()->getPageMargins()->setHeader(0.25);
	$objPHPExcelTitular->getActiveSheet()->getPageMargins()->setFooter(0.25);
	$objPHPExcelTitular->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
	$objPHPExcelTitular->getActiveSheet()->getPageSetup()->setVerticalCentered(false);
	$objPHPExcelTitular->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 1);
	
	$objPHPExcelTitular->getActiveSheet()->getColumnDimension('A')->setWidth(40);
	$objPHPExcelTitular->getActiveSheet()->setCellValue('A1', 'Nombre y Apellido');
	$objPHPExcelTitular->getActiveSheet()->getColumnDimension('B')->setWidth(14);
	$objPHPExcelTitular->getActiveSheet()->setCellValue('B1', 'Tipo. Doc.');
	$objPHPExcelTitular->getActiveSheet()->getColumnDimension('C')->setWidth(14);
	$objPHPExcelTitular->getActiveSheet()->setCellValue('C1', 'Nro. Doc.');
	$objPHPExcelTitular->getActiveSheet()->getColumnDimension('D')->setWidth(11);
	$objPHPExcelTitular->getActiveSheet()->setCellValue('D1', 'Fec. Nac.');
	$objPHPExcelTitular->getActiveSheet()->getColumnDimension('E')->setWidth(5);
	$objPHPExcelTitular->getActiveSheet()->setCellValue('E1', 'Sexo');
	$objPHPExcelTitular->getActiveSheet()->getColumnDimension('F')->setWidth(15);
	$objPHPExcelTitular->getActiveSheet()->setCellValue('F1', 'C.U.I.L.');
	$objPHPExcelTitular->getActiveSheet()->getColumnDimension('G')->setWidth(50);
	$objPHPExcelTitular->getActiveSheet()->setCellValue('G1', 'Domicilio');
	$objPHPExcelTitular->getActiveSheet()->getColumnDimension('H')->setWidth(40);
	$objPHPExcelTitular->getActiveSheet()->setCellValue('H1', 'Localidad');
	$objPHPExcelTitular->getActiveSheet()->getColumnDimension('I')->setWidth(30);
	$objPHPExcelTitular->getActiveSheet()->setCellValue('I1', 'Provincia');
	
	$objPHPExcelTitular->getActiveSheet()->getColumnDimension('J')->setWidth(18);
	$objPHPExcelTitular->getActiveSheet()->setCellValue('J1', 'C.U.I.T. Empresa');
	$objPHPExcelTitular->getActiveSheet()->getColumnDimension('K')->setWidth(50);
	$objPHPExcelTitular->getActiveSheet()->setCellValue('K1', 'Nombre Empresa');
	$objPHPExcelTitular->getActiveSheet()->getColumnDimension('L')->setWidth(50);
	$objPHPExcelTitular->getActiveSheet()->setCellValue('L1', 'Direccion Empresa');
	$objPHPExcelTitular->getActiveSheet()->getColumnDimension('M')->setWidth(50);
	$objPHPExcelTitular->getActiveSheet()->setCellValue('M1', 'Localidad Empresa');
	$objPHPExcelTitular->getActiveSheet()->getColumnDimension('N')->setWidth(50);
	$objPHPExcelTitular->getActiveSheet()->setCellValue('N1', 'Privincia Empresa');
	$objPHPExcelTitular->getActiveSheet()->getColumnDimension('O')->setWidth(50);
	$objPHPExcelTitular->getActiveSheet()->setCellValue('O1', 'Telefono 1');
	$objPHPExcelTitular->getActiveSheet()->getColumnDimension('P')->setWidth(50);
	$objPHPExcelTitular->getActiveSheet()->setCellValue('P1', 'Telefono 2');
	
	$fila=1;
	foreach ($arrayAlta as $titu){
		$fila++;
		$objPHPExcelTitular->getActiveSheet()->setCellValue('A'.$fila, utf8_encode($titu['apellidoynombre']));
		$objPHPExcelTitular->getActiveSheet()->setCellValue('B'.$fila, $titu['tipodocumento']);
		$objPHPExcelTitular->getActiveSheet()->setCellValue('C'.$fila, $titu['nrodocumento']);
		$objPHPExcelTitular->getActiveSheet()->setCellValue('D'.$fila, $titu['fechanacimiento']);
		$objPHPExcelTitular->getActiveSheet()->setCellValue('E'.$fila, $titu['sexo']);
		$objPHPExcelTitular->getActiveSheet()->setCellValue('F'.$fila, $titu['cuiltitular']);
		$objPHPExcelTitular->getActiveSheet()->setCellValue('G'.$fila, $titu['calledomicilio']);
		$objPHPExcelTitular->getActiveSheet()->setCellValue('H'.$fila, $titu['localidad']);
		$objPHPExcelTitular->getActiveSheet()->setCellValue('I'.$fila, $titu['provincia']);
		$objPHPExcelTitular->getActiveSheet()->setCellValue('J'.$fila, $titu['cuit']);
		$objPHPExcelTitular->getActiveSheet()->setCellValue('K'.$fila, $titu['empresa']);
		$direccion = $titu['domiempresa']." - C.P.: ".$titu['postalempresa'];	
		$objPHPExcelTitular->getActiveSheet()->setCellValue('L'.$fila, $direccion);
		$objPHPExcelTitular->getActiveSheet()->setCellValue('M'.$fila, $titu['localiempresa']);
		$objPHPExcelTitular->getActiveSheet()->setCellValue('N'.$fila, $titu['proviempresa']);
		$tel1 = "(".$titu['ddn1'].") ".$titu['telefono1'];
		$objPHPExcelTitular->getActiveSheet()->setCellValue('O'.$fila, $tel1);
		$tel2 = "(".$titu['ddn2'].") ".$titu['telefono2'];
		$objPHPExcelTitular->getActiveSheet()->setCellValue('P'.$fila, $tel2);
	}
	
	$objPHPExcelTitular->getDefaultStyle()->getFont()->setName('Arial');
	$objPHPExcelTitular->getDefaultStyle()->getFont()->setSize(8);
	
	$objPHPExcelTitular->getActiveSheet()->getStyle('A1:P1')->getFont()->setBold(true);
	$objPHPExcelTitular->getActiveSheet()->getStyle('A1:P1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcelTitular->getActiveSheet()->getStyle('A1:P1')->getFill()->getStartColor()->setARGB('FF808080');
	$objPHPExcelTitular->getActiveSheet()->getStyle('A1:P1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcelTitular, 'Excel5');
	$objWriter->save($archivo_name);
	$objPHPExcelTitular->disconnectWorksheets();
	unset($objWriter, $objPHPExcelTitular);
	
	$pagina = "titularesfuturaaltasss.php?error=0&delega=$nomdelega";
	Header("Location: $pagina");
} else {
	$pagina = "titularesfuturaaltasss.php?error=2&delega=$nomdelega";
	Header("Location: $pagina");
}
?>