<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
require_once($libPath."phpExcel/Classes/PHPExcel.php");

$id = $_GET['id'];
$maquina = $_SERVER['SERVER_NAME'];
$fechapedido = date("d-m-Y His");
$nombre = "Pedido De Insumos OSPIM ".$fechapedido.".xls";

if(strcmp("localhost",$maquina)==0) {
	$archivoPedido=$_SERVER['DOCUMENT_ROOT']."/madera/ospim/sistemas/stock/Pedidos/$nombre";
}
else {
	$archivoPedido="/home/sistemas/Stock/Pedidos/$nombre";
}

try {
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->getProperties()->setCreator($_SESSION['usuario'])
								 ->setLastModifiedBy($_SESSION['usuario'])
								 ->setTitle("Pedido Cotizacion O.S.P.I.M.")
								 ->setSubject("Modulo de Stock")
								 ->setDescription("Pedido de cotizacion de insumos");
	$objPHPExcel->getActiveSheet()->setTitle("Pedido Cotizacion OSPIM");
	$objPHPExcel->setActiveSheetIndex(0);
	$fechagenera=date("d/m/Y");
	$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&BO.S.P.I.M.&G&C&H&B  Pedido de Insumos - Oficina de Sistemas&R&B'.$fechagenera);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LEGAL);	
	$objPHPExcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
	$objPHPExcel->getActiveSheet()->getPageSetup()->setVerticalCentered(false);
	
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(45);
	$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Producto');
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(45);
	$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Descripción');
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
	$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Cantidad');
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
	$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Costo Unitario');
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
	$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Costo Total');
	
 	$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
	$objPHPExcel->getDefaultStyle()->getFont()->setSize(12);
	
	$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFill()->getStartColor()->setARGB('FF808080');
	$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$sqlInsumos = "SELECT * FROM detpedidos d, insumo i WHERE d.idpedido = '$id' and d.idinsumo = i.id";
	$resInsumos = mysql_query($sqlInsumos,$db);
	$fila=1;	
	while ($rowInsumos = mysql_fetch_assoc($resInsumos)) { 
		$fila++;
		$nombre = $rowInsumos['nombre']." (".$rowInsumos['numeroserie'].")";
		$descri = $rowInsumos['descripcion'];
		$canti = $rowInsumos['cantidadpedido'];
		$costoU = $rowInsumos['costounitario'];
		$totalC = (float)($canti * $costoU);
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$fila, $nombre);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$fila, $descri);
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$fila, $canti);
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$fila, $costoU);
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$fila, $totalC);
	}
	$filaTotal = $fila+2;
	$objPHPExcel->getActiveSheet()->setCellValue('D'.$filaTotal, "TOTAL");
	$objPHPExcel->getActiveSheet()->setCellValue('E'.$filaTotal, "=SUM(E2:E".($fila).")");
	
	
	$objPHPExcel->getActiveSheet()->getStyle('A2:A'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	$objPHPExcel->getActiveSheet()->getStyle('B2:B'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	$objPHPExcel->getActiveSheet()->getStyle('D2:D'.$filaTotal)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$objPHPExcel->getActiveSheet()->getStyle('D2:D'.$filaTotal)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('E2:E'.$filaTotal)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$objPHPExcel->getActiveSheet()->getStyle('E2:E'.$filaTotal)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('C'.$filaTotal)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('D'.$filaTotal)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('E'.$filaTotal)->getFont()->setBold(true);
	
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save($archivoPedido);
	$objPHPExcel->disconnectWorksheets();
	unset($objWriter, $objPHPExcel);
	
	$pagina = "pedidos.php";
	Header("Location: $pagina"); 

} catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}

?>