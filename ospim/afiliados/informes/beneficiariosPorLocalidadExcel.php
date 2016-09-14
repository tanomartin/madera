<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
require_once($libPath."phpExcel/Classes/PHPExcel.php");

$maquina = $_SERVER['SERVER_NAME'];
$fechagenera = date("d-m-Y");
$arrayLocalidad = explode("-",$_POST['localidad']);

$codLocali = $arrayLocalidad[0];
$nomLocali = $arrayLocalidad[1];

var_dump($arrayLocalidad);echo"<br><br>";

if(strcmp("localhost",$maquina)==0)
	$archivo_path="informes/";
else
	$archivo_path="/home/sistemas/Documentos/Repositorio/Afiliaciones/";

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
								 ->setTitle("Afiliados por localidad")
								 ->setSubject("Modulo de Afiliados")
								 ->setDescription("Informe de Afiliados por localidad.")
								 ->setCategory("Informes del Sistema de Afiliados");
	// Renombra la hoja
	$objPHPExcelTitular->getActiveSheet()->setTitle("Titulares");

	// Setea la hoja como activa, cuando se abra el Excel esta sera la primer hoja
	$objPHPExcelTitular->setActiveSheetIndex(0);

	// Setea encabezado y pie de pagina
	$objPHPExcelTitular->getActiveSheet()->getHeaderFooter()->setOddHeader("&L&BO.S.P.I.M.&G&C&H&BTitulares localidad $nomLocali al $fechagenera&R&B".$fechagenera);
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
	$objPHPExcelTitular->getActiveSheet()->getColumnDimension('I')->setWidth(40);
	$objPHPExcelTitular->getActiveSheet()->setCellValue('I1', 'Localidad');
	$objPHPExcelTitular->getActiveSheet()->getColumnDimension('J')->setWidth(30);
	$objPHPExcelTitular->getActiveSheet()->setCellValue('J1', 'Provincia');
	$objPHPExcelTitular->getActiveSheet()->getColumnDimension('K')->setWidth(18);
	$objPHPExcelTitular->getActiveSheet()->setCellValue('K1', 'C.U.I.T. Empresa');
	$objPHPExcelTitular->getActiveSheet()->getColumnDimension('L')->setWidth(50);
	$objPHPExcelTitular->getActiveSheet()->setCellValue('L1', 'Nombre Empresa');
	$objPHPExcelTitular->getActiveSheet()->getColumnDimension('M')->setWidth(50);
	$objPHPExcelTitular->getActiveSheet()->setCellValue('M1', 'Delegacion');

	$fila=1;	
	$sqlTitulares = "SELECT
	t.nroafiliado,
	t.apellidoynombre,
	td.descrip as tipodocumento,
	t.nrodocumento,
	date_format(t.fechanacimiento,'%d/%m/%Y') as fechanacimiento,
	t.sexo,
	t.cuil,
	t.domicilio,
	l.nomlocali,
	p.descrip as provincia,
	t.cuitempresa,
	e.nombre,
	d.nombre as delegacion
	FROM
	titulares t,
	localidades l,
	provincia p,
	empresas e,
	tipodocumento td,
	delegaciones d
	WHERE
	t.codlocali = $codLocali and
	t.codidelega = d.codidelega and
	t.codprovin = p.codprovin and
	t.codlocali = l.codlocali and
	t.cuitempresa = e.cuit and
	t.tipodocumento = td.codtipdoc";
	
	echo $sqlTitulares."<br><br>";
	
	$resultTitulares = $dbh->query($sqlTitulares);
	if ($resultTitulares){
		foreach ($resultTitulares as $titulares){
			$fila++;
			// Agrega datos a las celdas de datos
			$objPHPExcelTitular->getActiveSheet()->setCellValue('A'.$fila, $titulares['nroafiliado']);
			$objPHPExcelTitular->getActiveSheet()->setCellValue('B'.$fila, utf8_encode($titulares['apellidoynombre']));
			$objPHPExcelTitular->getActiveSheet()->setCellValue('C'.$fila, $titulares['tipodocumento']);
			$objPHPExcelTitular->getActiveSheet()->setCellValue('D'.$fila, $titulares['nrodocumento']);
			$objPHPExcelTitular->getActiveSheet()->setCellValue('E'.$fila, $titulares['fechanacimiento']);
			$objPHPExcelTitular->getActiveSheet()->setCellValue('F'.$fila, $titulares['sexo']);
			$objPHPExcelTitular->getActiveSheet()->setCellValue('G'.$fila, $titulares['cuil']);
			$objPHPExcelTitular->getActiveSheet()->setCellValue('H'.$fila, utf8_encode($titulares['domicilio']));
			$objPHPExcelTitular->getActiveSheet()->setCellValue('I'.$fila, utf8_encode($titulares['nomlocali']));
			$objPHPExcelTitular->getActiveSheet()->setCellValue('J'.$fila, utf8_encode($titulares['provincia']));
			$objPHPExcelTitular->getActiveSheet()->setCellValue('K'.$fila, $titulares['cuitempresa']);
			$objPHPExcelTitular->getActiveSheet()->setCellValue('L'.$fila, utf8_encode($titulares['nombre']));
			$objPHPExcelTitular->getActiveSheet()->setCellValue('M'.$fila, $titulares['delegacion']);
		}
	}

	// Setea fuente tipo y tamaño a la hoja activa
	$objPHPExcelTitular->getDefaultStyle()->getFont()->setName('Arial');
	$objPHPExcelTitular->getDefaultStyle()->getFont()->setSize(8); 

	// Setea negrita relleno y alineamiento horizontal a las celdas de titulos
	$objPHPExcelTitular->getActiveSheet()->getStyle('A1:M1')->getFont()->setBold(true);
	$objPHPExcelTitular->getActiveSheet()->getStyle('A1:M1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcelTitular->getActiveSheet()->getStyle('A1:M1')->getFill()->getStartColor()->setARGB('FF808080');
	$objPHPExcelTitular->getActiveSheet()->getStyle('A1:M1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	// Guarda Archivo en Formato Excel 2003
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcelTitular, 'Excel5');
	$archivo_name = $archivo_path."Titulares $nomLocali al $fechagenera.xls";
	$objWriter->save($archivo_name);
	$objPHPExcelTitular->disconnectWorksheets();
	unset($objWriter, $objPHPExcelTitular);
	//*************************************************************************************************************************** //

	// ************************ FAMILIAR ************************** //
	$objPHPExcelFamiliar = new PHPExcel();

	// Setea propiedades del documento
	$objPHPExcelFamiliar->getProperties()->setCreator($_SESSION['usuario'])
								 ->setLastModifiedBy($_SESSION['usuario'])
								 ->setTitle("Afiliados por localidad")
								 ->setSubject("Modulo de Afiliados")
								 ->setDescription("Informe de Afiliados por Localidad.")
								 ->setCategory("Informes del Sistema de Afiliados");
	// Renombra la hoja
	$objPHPExcelFamiliar->getActiveSheet()->setTitle("Familiares");

	// Setea la hoja como activa, cuando se abra el Excel esta sera la primer hoja
	$objPHPExcelFamiliar->setActiveSheetIndex(0);

	// Setea encabezado y pie de pagina
	$objPHPExcelFamiliar->getActiveSheet()->getHeaderFooter()->setOddHeader("&L&BO.S.P.I.M.&G&C&H&BFamiliares de la localidad $nomLocali al $fechagenera&R&B".$fechagenera);
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
	$objPHPExcelFamiliar->getActiveSheet()->getColumnDimension('I')->setWidth(15);
	$objPHPExcelFamiliar->getActiveSheet()->setCellValue('I1', 'Delegacion');

	$fila=1;	
	$sqlFamiliares = "SELECT
	f.nroafiliado,
	p.descrip as parentesco,
	f.apellidoynombre,
	td.descrip as tipodocumento,
	f.nrodocumento,
	f.cuil,
	date_format(f.fechanacimiento,'%d/%m/%Y') as fechanacimiento,
	t.sexo,
	d.nombre as delegacion
	FROM
	titulares t,
	familiares f,
	parentesco p,
	tipodocumento td,
	delegaciones d
	WHERE
	t.codlocali = $codLocali and
	t.codidelega = d.codidelega and
	t.nroafiliado = f.nroafiliado and
	f.tipoparentesco = p.codparent and
	f.tipodocumento = td.codtipdoc";
	
	echo $sqlFamiliares."<br><br>";
	
	$resultFamiliares = $dbh->query($sqlFamiliares);
	if ($resultFamiliares){
		foreach ($resultFamiliares as $familiar){
			$fila++;
			// Agrega datos a las celdas de datos
			$objPHPExcelFamiliar->getActiveSheet()->setCellValue('A'.$fila, $familiar['nroafiliado']);
			$objPHPExcelFamiliar->getActiveSheet()->setCellValue('B'.$fila, $familiar['parentesco']);
			$objPHPExcelFamiliar->getActiveSheet()->setCellValue('C'.$fila, utf8_encode($familiar['apellidoynombre']));
			$objPHPExcelFamiliar->getActiveSheet()->setCellValue('D'.$fila, $familiar['tipodocumento']);
			$objPHPExcelFamiliar->getActiveSheet()->setCellValue('E'.$fila, $familiar['nrodocumento']);
			$objPHPExcelFamiliar->getActiveSheet()->setCellValue('F'.$fila, $familiar['fechanacimiento']);
			$objPHPExcelFamiliar->getActiveSheet()->setCellValue('G'.$fila, $familiar['sexo']);
			$objPHPExcelFamiliar->getActiveSheet()->setCellValue('H'.$fila, $familiar['cuil']);
			$objPHPExcelFamiliar->getActiveSheet()->setCellValue('I'.$fila, utf8_encode($familiar['delegacion']));
		}
	}

	// Setea fuente tipo y tamaño a la hoja activa
	$objPHPExcelFamiliar->getDefaultStyle()->getFont()->setName('Arial');
	$objPHPExcelFamiliar->getDefaultStyle()->getFont()->setSize(8); 

	// Setea negrita relleno y alineamiento horizontal a las celdas de titulos
	$objPHPExcelFamiliar->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);
	$objPHPExcelFamiliar->getActiveSheet()->getStyle('A1:I1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcelFamiliar->getActiveSheet()->getStyle('A1:I1')->getFill()->getStartColor()->setARGB('FF808080');
	$objPHPExcelFamiliar->getActiveSheet()->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	// Guarda Archivo en Formato Excel 2003
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcelFamiliar, 'Excel5');
	$archivo_name = $archivo_path."Familiares $nomLocali al $fechagenera.xls";
	$objWriter->save($archivo_name);
	$objPHPExcelFamiliar->disconnectWorksheets();
	unset($objWriter, $objPHPExcelFamiliar);
	//*************************************************************************************************************************** //

	$dbh->commit();
	//$pagina = "beneficiariosPorLocalidad.php?error=0&locali=$nomLocali";
	//Header("Location: $pagina");
	
}
catch (PDOException $e) {
	$error = $e->getMessage();
	$dbh->rollback();
	$pagina = "beneficiariosPorDelegacion.php?error=1&mensaje=$error";
	Header("Location: $pagina");
}
?>