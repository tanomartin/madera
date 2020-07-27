<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php"); 
require_once($libPath."phpExcel/Classes/PHPExcel.php");
set_time_limit(0);
ini_set('memory_limit', '4096M');

$timestamp1 = mktime(date("H"),date("i"),date("s"),date("n"),date("j"),date("Y")); 

$maquina = $_SERVER['SERVER_NAME'];
if(strcmp("localhost",$maquina)==0)
    $archivo_path="archivos/";
else
    $archivo_path="/home/sistemas/Documentos/Repositorio/FFFF1208311301SYS/";

$fecha = $_POST['fecha'];
$fechaFin = fechaParaGuardar($fecha);

$sqlPagas = "SELECT f.id, f.fecharecepcion, f.idPrestador, prestadores.nombre as nombre, establecimientos.nombre as establecimiento,
                    concat(f.puntodeventa,'-',f.nrocomprobante) as nrocomprobante, f.fechapago,
                    f.fechacomprobante, f.importecomprobante, f.totaldebito, f.importeliquidado, f.totalpagado, f.restoapagar,
                    if (f.fechacierreliquidacion = '0000-00-00 00:00:00','',f.fechacierreliquidacion) as fechacierreliquidacion
             FROM facturas f 
             LEFT JOIN prestadores on prestadores.codigoprestador = f.idPrestador
             LEFT JOIN establecimientos on establecimientos.codigo = f.idestablecimiento  
             WHERE f.fecharecepcion < '$fechaFin' AND 
                   (f.importeliquidado != 0 OR f.totaldebito = f.importecomprobante) AND 
                   f.restoapagar = 0 
             ORDER BY idPrestador";
$resPagas = mysql_query($sqlPagas,$db);
$canPagas = mysql_num_rows($resPagas);
$arrayPagas = array();
$arrayPendientes = array();
if ($canPagas > 0) {
    while ($rowPagas = mysql_fetch_assoc($resPagas)) {  
        $fechaPago = $rowPagas['fechapago'];
        unset($rowPagas['fechapago']);
        if ($fechaPago < $fechaFin) {
            $arrayPagas[$rowPagas['id']] = $rowPagas;
        } else {
            $sqlPagoPosterior = "SELECT if (sum(importepago) is not null,sum(importepago),0) as importePagadoPosterior
                                    FROM ordendetalle d, ordencabecera c
                                    WHERE d.idfactura = ".$rowPagas['id']." AND 
                                          d.nroordenpago = c.nroordenpago AND 
                                          c.fechacancelacion is null and c.fechaorden >= '$fechaFin'";
            $resPagoPosterior = mysql_query($sqlPagoPosterior,$db);
            $rowPagoPosterior = mysql_fetch_assoc($resPagoPosterior);
            $rowPagas['totalpagado'] = $rowPagas['totalpagado'] - $rowPagoPosterior['importePagadoPosterior'];
            if ($rowPagas['totalpagado'] == 0) { $rowPagas['totalpagado'] = "0"; }
            $rowPagas['restoapagar'] = $rowPagas['restoapagar'] + $rowPagoPosterior['importePagadoPosterior'];
            if ($rowPagas['restoapagar'] == 0) { $rowPagas['restoapagar'] = "0"; }
            $arrayPendientes[$rowPagas['id']] = $rowPagas;
        }
    }
}
unset($resPagas);

$sqlPendientes = "SELECT f.id, f.fecharecepcion, f.idPrestador, prestadores.nombre as nombre, establecimientos.nombre as establecimiento,
                         concat(f.puntodeventa,'-',f.nrocomprobante) as nrocomprobante, f.fechapago,
                         f.fechacomprobante, f.importecomprobante, f.totaldebito, f.importeliquidado, f.totalpagado, f.restoapagar,
                         if (f.fechacierreliquidacion = '0000-00-00 00:00:00','',f.fechacierreliquidacion) as fechacierreliquidacion
                  FROM facturas f
                  LEFT JOIN  prestadores on prestadores.codigoprestador = f.idPrestador
                  LEFT JOIN establecimientos on establecimientos.codigo = f.idestablecimiento
                  WHERE f.fecharecepcion < '$fechaFin' AND 
                        ((f.importeliquidado != 0 AND restoapagar > 0) OR (importeliquidado = 0 AND restoapagar = 0)) AND 
                        f.totaldebito != f.importecomprobante
                  ORDER BY idPrestador";
$resPendientes = mysql_query($sqlPendientes,$db);
$canPendientes = mysql_num_rows($resPendientes);
if ($canPendientes > 0) {
    while ($rowPendientes = mysql_fetch_assoc($resPendientes)) {
        $fechaPago = $rowPendientes['fechapago'];
        unset($rowPendientes['fechapago']);
        if ($fechaPago < $fechaFin) {
            $arrayPendientes[$rowPendientes['id']] = $rowPendientes;
        } else {
            $sqlPagoPosterior = "SELECT if (sum(importepago) is not null,sum(importepago),0) as importePagadoPosterior
                                    FROM ordendetalle d, ordencabecera c
                                    WHERE d.idfactura = ".$rowPendientes['id']." AND
                                          d.nroordenpago = c.nroordenpago AND
                                          c.fechacancelacion is null and c.fechaorden >= '$fechaFin'";
            $resPagoPosterior = mysql_query($sqlPagoPosterior,$db);
            $rowPagoPosterior = mysql_fetch_assoc($resPagoPosterior);
            $rowPendientes['totalpagado'] = $rowPendientes['totalpagado'] - $rowPagoPosterior['importePagadoPosterior'];
            if ($rowPendientes['totalpagado'] == 0) { $rowPendientes['totalpagado'] = "0"; }
            $rowPendientes['restoapagar'] = $rowPendientes['restoapagar'] + $rowPagoPosterior['importePagadoPosterior'];
            if ($rowPendientes['restoapagar'] == 0) { $rowPendientes['restoapagar'] = "0"; }
            $arrayPendientes[$rowPendientes['id']] = $rowPendientes;
        }
    }
}
unset($resPendientes);

array_multisort (array_column($arrayPendientes, 'idPrestador'), SORT_ASC, $arrayPendientes);

$sqlOrdenes = "SELECT idfactura, 
                      group_concat(' N: ',o.nroordenpago,' (',o.formapago,' - ',o.comprobantepago,' - ',o.fechacomprobante,')') as infoPago,
                      group_concat(o.fechacomprobante) as infoFechas
                FROM ordencabecera o, ordendetalle d
                WHERE o.fechacancelacion is null and o.fechaorden < '$fechaFin' and o.nroordenpago = d.nroordenpago
                GROUP BY idfactura";
$resOrdenes = mysql_query($sqlOrdenes,$db);
$canOrdenes = mysql_num_rows($resOrdenes);
$arrayOrdenes = array();
if ($canOrdenes > 0) {
    while ($rowOrdenes = mysql_fetch_assoc($resOrdenes)) {
        $arrayOrdenes[$rowOrdenes['idfactura']] = $rowOrdenes;
    }
}
unset($resOrdenes);

$sqlServicios = "SELECT p.codigoprestador, GROUP_CONCAT(s.descripcion) as infoServicio
                    FROM prestadorservicio p, tiposervicio s
                    WHERE p.codigoservicio = s.codigoservicio
                    GROUP BY p.codigoprestador";
$resServicios = mysql_query($sqlServicios,$db);
$canServicios = mysql_num_rows($resServicios);
$arrayServicios = array();
if ($canServicios > 0) {
    while ($rowServicios = mysql_fetch_assoc($resServicios)) {
        $arrayServicios[$rowServicios['codigoprestador']] = $rowServicios['infoServicio'];
    }
}
unset($resServicios);

foreach ($arrayPendientes as $key => $pendientes) {  
    $servicio = "Sin Datos";
    if (array_key_exists($pendientes['idPrestador'], $arrayServicios)) {
        $servicio = $arrayServicios[$pendientes['idPrestador']];
    }
    $pendientes = array_slice($pendientes, 0, 5, true) + 
                  array("sevicio" => utf8_encode($servicio)) +  
                  array_slice($pendientes, 5, count($pendientes) - 1, true);
    $arrayPendientes[$key] = $pendientes;
    
    $datosOrden = "";
    $datosFecha = "";
    if (array_key_exists($pendientes['id'], $arrayOrdenes)) {
        $datosOrden = $arrayOrdenes[$pendientes['id']]['infoFechas'];
        $datosFecha = $arrayOrdenes[$pendientes['id']]['infoPago'];
    }
    $arrayPendientes[$key]['datosFecha'] = $datosFecha;
    $arrayPendientes[$key]['datoOrden'] = $datosOrden;
    $arrayPendientes[$key]['nombre'] = utf8_encode($pendientes['nombre']);
    $arrayPendientes[$key]['establecimiento'] = utf8_encode($pendientes['establecimiento']);
}

foreach ($arrayPagas as $key => $pagas) {
    $servicio = "Sin Datos";
    if (array_key_exists($pagas['idPrestador'], $arrayServicios)) {
        $servicio = $arrayServicios[$pagas['idPrestador']];
    }
    $pagas = array_slice($pagas, 0, 5, true) +
             array("sevicio" => utf8_encode($servicio)) +
             array_slice($pagas, 5, count($pagas) - 1, true);
    $arrayPagas[$key] = $pagas;
    
    $datosOrden = "";
    $datosFecha = "";
    if (array_key_exists($pagas['id'], $arrayOrdenes)) {
        $datosOrden = $arrayOrdenes[$pagas['id']]['infoFechas'];
        $datosFecha = $arrayOrdenes[$pagas['id']]['infoPago'];
    }
    $arrayPagas[$key]['datosFecha'] = $datosFecha;
    $arrayPagas[$key]['datoOrden'] = $datosOrden;
    $arrayPagas[$key]['nombre'] = utf8_encode($pagas['nombre']);
    $arrayPagas[$key]['establecimiento'] = utf8_encode($pagas['establecimiento']);
}


//***************************** GENERACION DE ARCHIVO *****************************************//
$objPHPExcelFacturas = new PHPExcel();
$objPHPExcelFacturas->getProperties()->setCreator($_SESSION['usuario'])
                                    ->setTitle("Listado Facturas")
                                    ->setSubject("Modulo de Facturacion")
                                    ->setDescription("Informe de Facturas a una fecha.")
                                    ->setCategory("Informes del Sistema de Facturacion");

//PENDIENTES//
$objPHPExcelFacturas->setActiveSheetIndex(0);
$sheet = $objPHPExcelFacturas->getActiveSheet();
$sheet->setTitle("Pendientes");
$sheet->getHeaderFooter()->setOddHeader("&L&BO.S.P.I.M.&G&C&H&B Facturas Pendientes al $fecha");
$sheet->getHeaderFooter()->setOddFooter('&L&R&BPagina &P de &N');
$sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LEGAL);	

//Margenes
$sheet->getPageMargins()->setTop(0.50);
$sheet->getPageMargins()->setRight(0);
$sheet->getPageMargins()->setLeft(0);
$sheet->getPageMargins()->setBottom(0.50);
$sheet->getPageMargins()->setHeader(0.25);
$sheet->getPageMargins()->setFooter(0.25);

$sheet->getPageSetup()->setHorizontalCentered(true);
$sheet->getPageSetup()->setVerticalCentered(false);
$sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 1);

//Cabecera
$sheet->getColumnDimension('A')->setWidth(8);
$sheet->setCellValue('A1', 'ID FACTURA');
$sheet->getColumnDimension('B')->setWidth(40);
$sheet->setCellValue('B1', 'FECHA RECEPCION');
$sheet->getColumnDimension('C')->setWidth(14);
$sheet->setCellValue('C1', 'ID PRESTA');
$sheet->getColumnDimension('D')->setWidth(14);
$sheet->setCellValue('D1', 'NOMBRE');
$sheet->getColumnDimension('E')->setWidth(14);
$sheet->setCellValue('E1', 'ESTABLECIMIENTO PRINCIPAL');
$sheet->getColumnDimension('F')->setWidth(11);
$sheet->setCellValue('F1', 'SERVICIOS');
$sheet->getColumnDimension('G')->setWidth(5);
$sheet->setCellValue('G1', 'NRO. COMP.');
$sheet->getColumnDimension('H')->setWidth(15);
$sheet->setCellValue('H1', 'FECHA COMP.');
$sheet->getColumnDimension('I')->setWidth(50);
$sheet->setCellValue('I1', 'IMP. COMP.');
$sheet->getColumnDimension('J')->setWidth(40);
$sheet->setCellValue('J1', 'IMP. DEBITO');
$sheet->getColumnDimension('K')->setWidth(30);
$sheet->setCellValue('K1', 'IMP. LIQ.');
$sheet->getColumnDimension('L')->setWidth(18);
$sheet->setCellValue('L1', 'PAGADO');
$sheet->getColumnDimension('M')->setWidth(50);
$sheet->setCellValue('M1', 'RESTO A PAGAR');
$sheet->getColumnDimension('N')->setWidth(50);
$sheet->setCellValue('N1', 'FECHA CIERRE LIQ.');
$sheet->getColumnDimension('O')->setWidth(50);
$sheet->setCellValue('O1', 'INFO. PAGO');
$sheet->getColumnDimension('P')->setWidth(50);
$sheet->setCellValue('P1', 'FECHA COMP. PAGO');

$sheet->fromArray($arrayPendientes, null, 'A2'); 

PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);
foreach(range('A','P') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

$sheet->getStyle('A1:P1')->getFont()->setBold(true);
$sheet->getStyle('A1:P1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$sheet->getStyle('A1:P1')->getFill()->getStartColor()->setARGB('FF808080');
$sheet->getStyle('A1:P1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

//******************************************************************************************************//
//PAGAS//
$objPHPExcelFacturas->createSheet(1);
$objPHPExcelFacturas->setActiveSheetIndex(1);
$sheet = $objPHPExcelFacturas->getActiveSheet();
$sheet->setTitle("Pagadas");
$sheet->getHeaderFooter()->setOddHeader("&L&BO.S.P.I.M.&G&C&H&B Facturas Pagas al $fecha");
$sheet->getHeaderFooter()->setOddFooter('&L&R&BPagina &P de &N');
$sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LEGAL);

//Margenes
$sheet->getPageMargins()->setTop(0.50);
$sheet->getPageMargins()->setRight(0);
$sheet->getPageMargins()->setLeft(0);
$sheet->getPageMargins()->setBottom(0.50);
$sheet->getPageMargins()->setHeader(0.25);
$sheet->getPageMargins()->setFooter(0.25);

$sheet->getPageSetup()->setHorizontalCentered(true);
$sheet->getPageSetup()->setVerticalCentered(false);
$sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 1);

//Cabecera
$sheet->getColumnDimension('A')->setWidth(8);
$sheet->setCellValue('A1', 'ID FACTURA');
$sheet->getColumnDimension('B')->setWidth(40);
$sheet->setCellValue('B1', 'FECHA RECEPCION');
$sheet->getColumnDimension('C')->setWidth(14);
$sheet->setCellValue('C1', 'ID PRESTA');
$sheet->getColumnDimension('D')->setWidth(14);
$sheet->setCellValue('D1', 'NOMBRE');
$sheet->getColumnDimension('E')->setWidth(14);
$sheet->setCellValue('E1', 'ESTABLECIMIENTO PRINCIPAL');
$sheet->getColumnDimension('F')->setWidth(11);
$sheet->setCellValue('F1', 'SERVICIOS');
$sheet->getColumnDimension('G')->setWidth(5);
$sheet->setCellValue('G1', 'NRO. COMP.');
$sheet->getColumnDimension('H')->setWidth(15);
$sheet->setCellValue('H1', 'FECHA COMP.');
$sheet->getColumnDimension('I')->setWidth(50);
$sheet->setCellValue('I1', 'IMP. COMP.');
$sheet->getColumnDimension('J')->setWidth(40);
$sheet->setCellValue('J1', 'IMP. DEBITO');
$sheet->getColumnDimension('K')->setWidth(30);
$sheet->setCellValue('K1', 'IMP. LIQ.');
$sheet->getColumnDimension('L')->setWidth(18);
$sheet->setCellValue('L1', 'PAGADO');
$sheet->getColumnDimension('M')->setWidth(50);
$sheet->setCellValue('M1', 'RESTO A PAGAR');
$sheet->getColumnDimension('N')->setWidth(50);
$sheet->setCellValue('N1', 'FECHA CIERRE LIQ.');
$sheet->getColumnDimension('O')->setWidth(50);
$sheet->setCellValue('O1', 'INFO. PAGO');
$sheet->getColumnDimension('P')->setWidth(50);
$sheet->setCellValue('P1', 'FECHA COMP. PAGO');

$sheet->fromArray($arrayPagas, null, 'A2'); 

PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);
foreach(range('A','P') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

$sheet->getStyle('A1:P1')->getFont()->setBold(true);
$sheet->getStyle('A1:P1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$sheet->getStyle('A1:P1')->getFill()->getStartColor()->setARGB('FF808080');
$sheet->getStyle('A1:P1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcelFacturas, 'Excel5');
$fechagenera = date("d-m-Y h.i.s");
$archivo_name = $archivo_path."Facturas al $fecha ($fechagenera).xls";
$objWriter->save($archivo_name); 

//**********************************************************************//

$timestamp2 = mktime(date("H"),date("i"),date("s"),date("n"),date("j"),date("Y"));
$tiempoTranscurrido = ($timestamp2 - $timestamp1)/ 60;
$enMintuos = number_format($tiempoTranscurrido,2,',','.'); 
header("Location: facturasFechas.php?tiempo=$enMintuos&fecha=$fecha"); ?> 