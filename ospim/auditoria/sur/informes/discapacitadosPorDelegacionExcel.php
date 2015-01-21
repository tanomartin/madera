<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
require_once($libPath."phpExcel/Classes/PHPExcel.php");

$arrayDelegacion = explode("-",$_POST['delegacion']);
$delegacion = $arrayDelegacion[0];
$nomdelega = $arrayDelegacion[1];

$maquina = $_SERVER['SERVER_NAME'];
$fechagenera = date("d-m-Y");
if(strcmp("localhost",$maquina)==0)
	$archivo_path="informes/";
else
	$archivo_path="/home/sistemas/Documentos/Repositorio/Discapacitados/";

//conexion y creacion de transaccion.
try{
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	// Crea el objeto PHPExcel
	$objPHPExcelTitular = new PHPExcel();

	// Setea propiedades del documento
	$objPHPExcelTitular->getProperties()->setCreator($_SESSION['usuario'])
								 ->setLastModifiedBy($_SESSION['usuario'])
								 ->setTitle("Discapacitados por delegación")
								 ->setSubject("Modulo de Discapacitados")
								 ->setDescription("Informe de Discapacitados por delegación.")
								 ->setCategory("Informes del Sistema de Discapacitados");
	// Renombra la hoja
	$objPHPExcelTitular->getActiveSheet()->setTitle("T. Disca $delegacion al $fechagenera");

	// Setea la hoja como activa, cuando se abra el Excel esta sera la primer hoja
	$objPHPExcelTitular->setActiveSheetIndex(0);

	// Setea encabezado y pie de pagina
	$objPHPExcelTitular->getActiveSheet()->getHeaderFooter()->setOddHeader("&L&BO.S.P.I.M.&G&C&H&BDiscapacitados $nomdelega ($delegacion) al $fechagenera&R&B".$fechagenera);
	$objPHPExcelTitular->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&R&BPagina &P de &N');

	// Setea en configuracion de pagina orientacion y tamaño
	$objPHPExcelTitular->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	$objPHPExcelTitular->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LEGAL);	
	
	// Setea en configuracion de pagina los margenes
	$objPHPExcelTitular->getActiveSheet()->getPageMargins()->setTop(0.50);
	$objPHPExcelTitular->getActiveSheet()->getPageMargins()->setRight(0);
	$objPHPExcelTitular->getActiveSheet()->getPageMargins()->setLeft(0);
	$objPHPExcelTitular->getActiveSheet()->getPageMargins()->setBottom(0.50);
	$objPHPExcelTitular->getActiveSheet()->getPageMargins()->setHeader(0.25);
	$objPHPExcelTitular->getActiveSheet()->getPageMargins()->setFooter(0.25);
	
	// Setea en configuracion de pagina centrado horizontal y vertical
	$objPHPExcelTitular->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
	$objPHPExcelTitular->getActiveSheet()->getPageSetup()->setVerticalCentered(false);

	// Setea en configuracion de pagina lineas de division (OJO: NO ANDA)
	//$objPHPExcelTitular->getActiveSheet()->getPageSetup()->setShowGridlines(true);

	// Setea en configuracion de pagina repetir filas en extremo superior
	$objPHPExcelTitular->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 1);

	// ************************ TITULAR ************************** //

	// Setea tamaño de la columna y agrega datos a las celdas de titulos
	$objPHPExcelTitular->getActiveSheet()->getColumnDimension('A')->setWidth(8);
	$objPHPExcelTitular->getActiveSheet()->setCellValue('A1', 'N. Afiliado');
	$objPHPExcelTitular->getActiveSheet()->getColumnDimension('B')->setWidth(40);
	$objPHPExcelTitular->getActiveSheet()->setCellValue('B1', 'Nombre y Apellido');
	$objPHPExcelTitular->getActiveSheet()->getColumnDimension('C')->setWidth(14);
	$objPHPExcelTitular->getActiveSheet()->setCellValue('C1', 'Tipo Doc.');
	$objPHPExcelTitular->getActiveSheet()->getColumnDimension('D')->setWidth(14);
	$objPHPExcelTitular->getActiveSheet()->setCellValue('D1', 'Nro. Doc.');
	$objPHPExcelTitular->getActiveSheet()->getColumnDimension('E')->setWidth(11);
	$objPHPExcelTitular->getActiveSheet()->setCellValue('E1', 'Fec. Nac.');
	$objPHPExcelTitular->getActiveSheet()->getColumnDimension('F')->setWidth(5);
	$objPHPExcelTitular->getActiveSheet()->setCellValue('F1', 'Sexo');
	$objPHPExcelTitular->getActiveSheet()->getColumnDimension('G')->setWidth(15);
	$objPHPExcelTitular->getActiveSheet()->setCellValue('G1', 'C.U.I.L.');
	$objPHPExcelTitular->getActiveSheet()->getColumnDimension('H')->setWidth(50);
	$objPHPExcelTitular->getActiveSheet()->setCellValue('H1', 'Domicilio');
	$objPHPExcelTitular->getActiveSheet()->getColumnDimension('I')->setWidth(10);
	$objPHPExcelTitular->getActiveSheet()->setCellValue('I1', 'C.P.');
	$objPHPExcelTitular->getActiveSheet()->getColumnDimension('J')->setWidth(40);
	$objPHPExcelTitular->getActiveSheet()->setCellValue('J1', 'Localidad');
	$objPHPExcelTitular->getActiveSheet()->getColumnDimension('K')->setWidth(30);
	$objPHPExcelTitular->getActiveSheet()->setCellValue('K1', 'Provincia');
	$objPHPExcelTitular->getActiveSheet()->getColumnDimension('L')->setWidth(18);
	$objPHPExcelTitular->getActiveSheet()->setCellValue('L1', 'Emision Certificado');
	$objPHPExcelTitular->getActiveSheet()->getColumnDimension('M')->setWidth(18);
	$objPHPExcelTitular->getActiveSheet()->setCellValue('M1', 'Vto. Certificado');
	$objPHPExcelTitular->getActiveSheet()->getColumnDimension('N')->setWidth(15);
	$objPHPExcelTitular->getActiveSheet()->setCellValue('N1', 'Certificado Escaneado');
	$objPHPExcelTitular->getActiveSheet()->getColumnDimension('O')->setWidth(15);
	$objPHPExcelTitular->getActiveSheet()->setCellValue('O1', 'Estado');

	$fila=1;	
	$sqlTitulares = "SELECT 
					t.nroafiliado, 
					t.apellidoynombre, 
					tipo.descrip as tipodocumento, 
					t.nrodocumento, 
					t.fechanacimiento,
					t.sexo,
					t.cuil,
					t.domicilio,
					t.numpostal,
					l.nomlocali,
					p.descrip as provincia,
					d.emisioncertificado,
					d.vencimientocertificado,
					d.certificadodiscapacidad
					FROM discapacitados d, titulares t, tipodocumento tipo, localidades l, provincia p
					WHERE
					d.nroorden = 0 and
					d.nroafiliado = t.nroafiliado and
					t.codidelega = $delegacion and
					t.tipodocumento = tipo.codtipdoc and
					t.codlocali = l.codlocali and
					t.codprovin = p.codprovin";
					
	$resultTitulares = $dbh->query($sqlTitulares);
	if ($resultTitulares){
		foreach ($resultTitulares as $titulares){
			$fila++;
			// Agrega datos a las celdas de datos
			$objPHPExcelTitular->getActiveSheet()->setCellValue('A'.$fila, $titulares['nroafiliado']);
			$objPHPExcelTitular->getActiveSheet()->setCellValue('B'.$fila, $titulares['apellidoynombre']);
			$objPHPExcelTitular->getActiveSheet()->setCellValue('C'.$fila, $titulares['tipodocumento']);
			$objPHPExcelTitular->getActiveSheet()->setCellValue('D'.$fila, $titulares['nrodocumento']);
			$objPHPExcelTitular->getActiveSheet()->setCellValue('E'.$fila, invertirfecha($titulares['fechanacimiento']));
			$objPHPExcelTitular->getActiveSheet()->setCellValue('F'.$fila, $titulares['sexo']);
			$objPHPExcelTitular->getActiveSheet()->setCellValue('G'.$fila, $titulares['cuil']);
			$objPHPExcelTitular->getActiveSheet()->setCellValue('H'.$fila, $titulares['domicilio']);
			$objPHPExcelTitular->getActiveSheet()->setCellValue('I'.$fila, $titulares['numpostal']);
			$objPHPExcelTitular->getActiveSheet()->setCellValue('J'.$fila, $titulares['nomlocali']);
			$objPHPExcelTitular->getActiveSheet()->setCellValue('K'.$fila, $titulares['provincia']);
			$objPHPExcelTitular->getActiveSheet()->setCellValue('L'.$fila, invertirfecha($titulares['emisioncertificado']));
			$objPHPExcelTitular->getActiveSheet()->setCellValue('M'.$fila, invertirfecha($titulares['vencimientocertificado']));
			if ($titulares['certificadodiscapacidad'] == 1) {
				$objPHPExcelTitular->getActiveSheet()->setCellValue('N'.$fila, 'SI');
			} else {
				$objPHPExcelTitular->getActiveSheet()->setCellValue('N'.$fila, 'NO');
			}
			$objPHPExcelTitular->getActiveSheet()->setCellValue('O'.$fila, "ACTIVO");
		}
	}
	
	$sqlTitularesBaja = "SELECT 
					t.nroafiliado, 
					t.apellidoynombre, 
					tipo.descrip as tipodocumento, 
					t.nrodocumento, 
					t.fechanacimiento,
					t.sexo,
					t.cuil,
					t.domicilio,
					t.numpostal,
					l.nomlocali,
					p.descrip as provincia,
					d.emisioncertificado,
					d.vencimientocertificado,
					d.certificadodiscapacidad
					FROM discapacitados d, titularesdebaja t, tipodocumento tipo, localidades l, provincia p
					WHERE
					d.nroorden = 0 and
					d.nroafiliado = t.nroafiliado and
					t.codidelega = $delegacion and
					t.tipodocumento = tipo.codtipdoc and
					t.codlocali = l.codlocali and
					t.codprovin = p.codprovin";
					
	$resultTitularesBaja = $dbh->query($sqlTitularesBaja);
	if ($resultTitularesBaja){
		foreach ($resultTitularesBaja as $titularesbaja){
			$fila++;
			// Agrega datos a las celdas de datos
			$objPHPExcelTitular->getActiveSheet()->setCellValue('A'.$fila, $titularesbaja['nroafiliado']);
			$objPHPExcelTitular->getActiveSheet()->setCellValue('B'.$fila, $titularesbaja['apellidoynombre']);
			$objPHPExcelTitular->getActiveSheet()->setCellValue('C'.$fila, $titularesbaja['tipodocumento']);
			$objPHPExcelTitular->getActiveSheet()->setCellValue('D'.$fila, $titularesbaja['nrodocumento']);
			$objPHPExcelTitular->getActiveSheet()->setCellValue('E'.$fila, invertirfecha($titularesbaja['fechanacimiento']));
			$objPHPExcelTitular->getActiveSheet()->setCellValue('F'.$fila, $titularesbaja['sexo']);
			$objPHPExcelTitular->getActiveSheet()->setCellValue('G'.$fila, $titularesbaja['cuil']);
			$objPHPExcelTitular->getActiveSheet()->setCellValue('H'.$fila, $titularesbaja['domicilio']);
			$objPHPExcelTitular->getActiveSheet()->setCellValue('I'.$fila, $titularesbaja['numpostal']);
			$objPHPExcelTitular->getActiveSheet()->setCellValue('J'.$fila, $titularesbaja['nomlocali']);
			$objPHPExcelTitular->getActiveSheet()->setCellValue('K'.$fila, $titularesbaja['provincia']);
			$objPHPExcelTitular->getActiveSheet()->setCellValue('L'.$fila, invertirfecha($titularesbaja['emisioncertificado']));
			$objPHPExcelTitular->getActiveSheet()->setCellValue('M'.$fila, invertirfecha($titularesbaja['vencimientocertificado']));
			if ($titularesbaja['certificadodiscapacidad'] == 1) {
				$objPHPExcelTitular->getActiveSheet()->setCellValue('N'.$fila, 'SI');
			} else {
				$objPHPExcelTitular->getActiveSheet()->setCellValue('N'.$fila, 'NO');
			}
			$objPHPExcelTitular->getActiveSheet()->setCellValue('O'.$fila, "INACTIVO");
		}
	}
	
	
	

	// Setea fuente tipo y tamaño a la hoja activa
	$objPHPExcelTitular->getDefaultStyle()->getFont()->setName('Arial');
	$objPHPExcelTitular->getDefaultStyle()->getFont()->setSize(8); 

	// Setea negrita relleno y alineamiento horizontal a las celdas de titulos
	$objPHPExcelTitular->getActiveSheet()->getStyle('A1:O1')->getFont()->setBold(true);
	$objPHPExcelTitular->getActiveSheet()->getStyle('A1:O1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcelTitular->getActiveSheet()->getStyle('A1:O1')->getFill()->getStartColor()->setARGB('FF808080');
	$objPHPExcelTitular->getActiveSheet()->getStyle('A1:O1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	// Guarda Archivo en Formato Excel 2003
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcelTitular, 'Excel5');
	$archivo_name = $archivo_path."Titulares Discapacitados $nomdelega ($delegacion) al $fechagenera.xls";
	$objWriter->save($archivo_name);
	$objPHPExcelTitular->disconnectWorksheets();
	unset($objWriter, $objPHPExcelTitular);
	//*************************************************************************************************************************** //

	// ************************ FAMILIAR ************************** //
	$objPHPExcelFamiliar = new PHPExcel();

	// Setea propiedades del documento
	$objPHPExcelFamiliar->getProperties()->setCreator($_SESSION['usuario'])
								 ->setLastModifiedBy($_SESSION['usuario'])
								 ->setTitle("Discapacitados por delegación")
								 ->setSubject("Modulo de Discapacitados")
								 ->setDescription("Informe de Afiliados Discapacitados por delegación.")
								 ->setCategory("Informes del Sistema de Discapacitados");
	// Renombra la hoja
	$objPHPExcelFamiliar->getActiveSheet()->setTitle("F. Disca $delegacion al $fechagenera");

	// Setea la hoja como activa, cuando se abra el Excel esta sera la primer hoja
	$objPHPExcelFamiliar->setActiveSheetIndex(0);

	// Setea encabezado y pie de pagina
	$objPHPExcelFamiliar->getActiveSheet()->getHeaderFooter()->setOddHeader("&L&BO.S.P.I.M.&G&C&H&BFamiliares Discapacitados $nomdelega ($delegacion) al $fechagenera&R&B".$fechagenera);
	$objPHPExcelFamiliar->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&R&BPagina &P de &N');

	// Setea en configuracion de pagina orientacion y tamaño
	$objPHPExcelFamiliar->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	$objPHPExcelFamiliar->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LEGAL);	
	
	// Setea en configuracion de pagina los margenes
	$objPHPExcelFamiliar->getActiveSheet()->getPageMargins()->setTop(0.50);
	$objPHPExcelFamiliar->getActiveSheet()->getPageMargins()->setRight(0);
	$objPHPExcelFamiliar->getActiveSheet()->getPageMargins()->setLeft(0);
	$objPHPExcelFamiliar->getActiveSheet()->getPageMargins()->setBottom(0.50);
	$objPHPExcelFamiliar->getActiveSheet()->getPageMargins()->setHeader(0.25);
	$objPHPExcelFamiliar->getActiveSheet()->getPageMargins()->setFooter(0.25);
	
	// Setea en configuracion de pagina centrado horizontal y vertical
	$objPHPExcelFamiliar->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
	$objPHPExcelFamiliar->getActiveSheet()->getPageSetup()->setVerticalCentered(false);

	// Setea en configuracion de pagina lineas de division (OJO: NO ANDA)
	//$objPHPExcelFamiliar->getActiveSheet()->getPageSetup()->setShowGridlines(true);

	// Setea en configuracion de pagina repetir filas en extremo superior
	$objPHPExcelFamiliar->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 1);
	
	// Setea tamaño de la columna y agrega datos a las celdas de titulos
	$objPHPExcelFamiliar->getActiveSheet()->getColumnDimension('A')->setWidth(8);
	$objPHPExcelFamiliar->getActiveSheet()->setCellValue('A1', 'N. Afiliado');
	$objPHPExcelFamiliar->getActiveSheet()->getColumnDimension('B')->setWidth(30);
	$objPHPExcelFamiliar->getActiveSheet()->setCellValue('B1', 'Parentesco');
	$objPHPExcelFamiliar->getActiveSheet()->getColumnDimension('C')->setWidth(40);
	$objPHPExcelFamiliar->getActiveSheet()->setCellValue('C1', 'Nombre y Apellido');
	$objPHPExcelFamiliar->getActiveSheet()->getColumnDimension('D')->setWidth(15);
	$objPHPExcelFamiliar->getActiveSheet()->setCellValue('D1', 'Tipo Doc.');
	$objPHPExcelFamiliar->getActiveSheet()->getColumnDimension('E')->setWidth(15);
	$objPHPExcelFamiliar->getActiveSheet()->setCellValue('E1', 'Nro. Doc.');
	$objPHPExcelFamiliar->getActiveSheet()->getColumnDimension('F')->setWidth(11);
	$objPHPExcelFamiliar->getActiveSheet()->setCellValue('F1', 'Fec. Nac.');
	$objPHPExcelFamiliar->getActiveSheet()->getColumnDimension('G')->setWidth(5);
	$objPHPExcelFamiliar->getActiveSheet()->setCellValue('G1', 'Sexo');
	$objPHPExcelFamiliar->getActiveSheet()->getColumnDimension('H')->setWidth(15);
	$objPHPExcelFamiliar->getActiveSheet()->setCellValue('H1', 'C.U.I.L.');
	$objPHPExcelFamiliar->getActiveSheet()->getColumnDimension('I')->setWidth(50);
	$objPHPExcelFamiliar->getActiveSheet()->setCellValue('I1', 'Domicilio');
	$objPHPExcelFamiliar->getActiveSheet()->getColumnDimension('J')->setWidth(10);
	$objPHPExcelFamiliar->getActiveSheet()->setCellValue('J1', 'C.P.');
	$objPHPExcelFamiliar->getActiveSheet()->getColumnDimension('K')->setWidth(40);
	$objPHPExcelFamiliar->getActiveSheet()->setCellValue('K1', 'Localidad');
	$objPHPExcelFamiliar->getActiveSheet()->getColumnDimension('L')->setWidth(30);
	$objPHPExcelFamiliar->getActiveSheet()->setCellValue('L1', 'Provincia');
	$objPHPExcelFamiliar->getActiveSheet()->getColumnDimension('M')->setWidth(15);
	$objPHPExcelFamiliar->getActiveSheet()->setCellValue('M1', 'Emision Certificado');
	$objPHPExcelFamiliar->getActiveSheet()->getColumnDimension('N')->setWidth(15);
	$objPHPExcelFamiliar->getActiveSheet()->setCellValue('N1', 'Vto. Certificado');
	$objPHPExcelFamiliar->getActiveSheet()->getColumnDimension('O')->setWidth(10);
	$objPHPExcelFamiliar->getActiveSheet()->setCellValue('O1', 'Certificado Escaneado');
	$objPHPExcelFamiliar->getActiveSheet()->getColumnDimension('P')->setWidth(10);
	$objPHPExcelFamiliar->getActiveSheet()->setCellValue('P1', 'Estado');

	$fila=1;	
	$sqlFamiliares = "SELECT 
	f.nroafiliado,
	p.descrip as parentesco, 
	f.apellidoynombre, 
	tipo.descrip as tipodocumento, 
	f.nrodocumento, 
	f.fechanacimiento,
	f.sexo,
	f.cuil,
	t.domicilio,
	t.numpostal,
	l.nomlocali,
	pr.descrip as provincia,
	d.emisioncertificado, 
	d.vencimientocertificado,
	d.certificadodiscapacidad
	FROM discapacitados d, titulares t, familiares f, tipodocumento tipo, parentesco p, localidades l, provincia pr
	WHERE
	d.nroorden != 0 and
	d.nroafiliado = f.nroafiliado and
	d.nroorden = f.nroorden and
	f.nroafiliado = t.nroafiliado and
	f.tipodocumento = tipo.codtipdoc and
	t.codidelega = $delegacion and
	f.tipoparentesco = p.codparent and
	t.codlocali = l.codlocali and
	t.codprovin = pr.codprovin";
	
	$resultFamiliares = $dbh->query($sqlFamiliares);
	if ($resultFamiliares){
		foreach ($resultFamiliares as $familiar){
			$fila++;
			// Agrega datos a las celdas de datos
			$objPHPExcelFamiliar->getActiveSheet()->setCellValue('A'.$fila, $familiar['nroafiliado']);
			$objPHPExcelFamiliar->getActiveSheet()->setCellValue('B'.$fila, $familiar['parentesco']);
			$objPHPExcelFamiliar->getActiveSheet()->setCellValue('C'.$fila, $familiar['apellidoynombre']);
			$objPHPExcelFamiliar->getActiveSheet()->setCellValue('D'.$fila, $familiar['tipodocumento']);
			$objPHPExcelFamiliar->getActiveSheet()->setCellValue('E'.$fila, $familiar['nrodocumento']);
			$objPHPExcelFamiliar->getActiveSheet()->setCellValue('F'.$fila, invertirfecha($familiar['fechanacimiento']));
			$objPHPExcelFamiliar->getActiveSheet()->setCellValue('G'.$fila, $familiar['sexo']);
			$objPHPExcelFamiliar->getActiveSheet()->setCellValue('H'.$fila, $familiar['cuil']);
			$objPHPExcelFamiliar->getActiveSheet()->setCellValue('I'.$fila, $familiar['domicilio']);
			$objPHPExcelFamiliar->getActiveSheet()->setCellValue('J'.$fila, $familiar['numpostal']);
			$objPHPExcelFamiliar->getActiveSheet()->setCellValue('K'.$fila, $familiar['nomlocali']);
			$objPHPExcelFamiliar->getActiveSheet()->setCellValue('L'.$fila, $familiar['provincia']);
			$objPHPExcelFamiliar->getActiveSheet()->setCellValue('M'.$fila, invertirfecha($familiar['emisioncertificado']));
			$objPHPExcelFamiliar->getActiveSheet()->setCellValue('N'.$fila, invertirfecha($familiar['vencimientocertificado']));
			if ($familiar['certificadodiscapacidad'] == 1) {
				$objPHPExcelFamiliar->getActiveSheet()->setCellValue('O'.$fila, 'SI');
			} else {
				$objPHPExcelFamiliar->getActiveSheet()->setCellValue('O'.$fila, 'NO');
			}
			$objPHPExcelFamiliar->getActiveSheet()->setCellValue('P'.$fila, "ACTIVO");
		}
	}

	$sqlFamiliaresBaja = "SELECT 
	f.nroafiliado,
	p.descrip as parentesco, 
	f.apellidoynombre, 
	tipo.descrip as tipodocumento, 
	f.nrodocumento, 
	f.fechanacimiento,
	f.sexo,
	f.cuil,
	t.domicilio,
	t.numpostal,
	l.nomlocali,
	pr.descrip as provincia,
	d.emisioncertificado, 
	d.vencimientocertificado,
	d.certificadodiscapacidad
	FROM discapacitados d, titulares t, familiaresdebaja f, tipodocumento tipo, parentesco p, localidades l, provincia pr
	WHERE
	d.nroorden != 0 and
	d.nroafiliado = f.nroafiliado and
	d.nroorden = f.nroorden and
	f.nroafiliado = t.nroafiliado and
	f.tipodocumento = tipo.codtipdoc and
	t.codidelega = $delegacion and
	f.tipoparentesco = p.codparent and
	t.codlocali = l.codlocali and
	t.codprovin = pr.codprovin";
	
	$resultFamiliaresBaja = $dbh->query($sqlFamiliaresBaja);
	if ($resultFamiliaresBaja){
		foreach ($resultFamiliaresBaja as $familiarBaja){
			$fila++;
			// Agrega datos a las celdas de datos
			$objPHPExcelFamiliar->getActiveSheet()->setCellValue('A'.$fila, $familiarBaja['nroafiliado']);
			$objPHPExcelFamiliar->getActiveSheet()->setCellValue('B'.$fila, $familiarBaja['parentesco']);
			$objPHPExcelFamiliar->getActiveSheet()->setCellValue('C'.$fila, $familiarBaja['apellidoynombre']);
			$objPHPExcelFamiliar->getActiveSheet()->setCellValue('D'.$fila, $familiarBaja['tipodocumento']);
			$objPHPExcelFamiliar->getActiveSheet()->setCellValue('E'.$fila, $familiarBaja['nrodocumento']);
			$objPHPExcelFamiliar->getActiveSheet()->setCellValue('F'.$fila, invertirfecha($familiarBaja['fechanacimiento']));
			$objPHPExcelFamiliar->getActiveSheet()->setCellValue('G'.$fila, $familiarBaja['sexo']);
			$objPHPExcelFamiliar->getActiveSheet()->setCellValue('H'.$fila, $familiarBaja['cuil']);
			$objPHPExcelFamiliar->getActiveSheet()->setCellValue('I'.$fila, $familiarBaja['domicilio']);
			$objPHPExcelFamiliar->getActiveSheet()->setCellValue('J'.$fila, $familiarBaja['numpostal']);
			$objPHPExcelFamiliar->getActiveSheet()->setCellValue('K'.$fila, $familiarBaja['nomlocali']);
			$objPHPExcelFamiliar->getActiveSheet()->setCellValue('L'.$fila, $familiarBaja['provincia']);
			$objPHPExcelFamiliar->getActiveSheet()->setCellValue('M'.$fila, invertirfecha($familiarBaja['emisioncertificado']));
			$objPHPExcelFamiliar->getActiveSheet()->setCellValue('N'.$fila, invertirfecha($familiarBaja['vencimientocertificado']));
			if ($familiarBaja['certificadodiscapacidad'] == 1) {
				$objPHPExcelFamiliar->getActiveSheet()->setCellValue('O'.$fila, 'SI');
			} else {
				$objPHPExcelFamiliar->getActiveSheet()->setCellValue('O'.$fila, 'NO');
			}
			$objPHPExcelFamiliar->getActiveSheet()->setCellValue('P'.$fila, "INACTIVO");
		}
	}


	// Setea fuente tipo y tamaño a la hoja activa
	$objPHPExcelFamiliar->getDefaultStyle()->getFont()->setName('Arial');
	$objPHPExcelFamiliar->getDefaultStyle()->getFont()->setSize(8); 

	// Setea negrita relleno y alineamiento horizontal a las celdas de titulos
	$objPHPExcelFamiliar->getActiveSheet()->getStyle('A1:P1')->getFont()->setBold(true);
	$objPHPExcelFamiliar->getActiveSheet()->getStyle('A1:P1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcelFamiliar->getActiveSheet()->getStyle('A1:P1')->getFill()->getStartColor()->setARGB('FF808080');
	$objPHPExcelFamiliar->getActiveSheet()->getStyle('A1:P1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	// Guarda Archivo en Formato Excel 2003
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcelFamiliar, 'Excel5');
	$archivo_name = $archivo_path."Familiares Discapacitados $nomdelega ($delegacion) al $fechagenera.xls";
	$objWriter->save($archivo_name);
	$objPHPExcelFamiliar->disconnectWorksheets();
	unset($objWriter, $objPHPExcelFamiliar);
	//*************************************************************************************************************************** //

	$dbh->commit();
	$pagina = "discapacitadosPorDelegacion.php?error=0&delega=$nomdelega";
	Header("Location: $pagina");
}
catch (PDOException $e) {
	$error = $e->getMessage();
	$dbh->rollback();
	$pagina = "discapacitadosPorDelegacion.php?error=1&mensaje=$error";
	Header("Location: $pagina");
}
?>