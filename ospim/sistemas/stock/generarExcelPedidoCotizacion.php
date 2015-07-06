<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
require_once($libPath."phpExcel/Classes/PHPExcel.php");

$id = $_GET['id'];
$maquina = $_SERVER['SERVER_NAME'];
$fechapedido = date("d-m-Y His");
$nombre = "Pedido Cotizacion OSPIM ".$fechapedido.".xls";

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
	
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(54);
	$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Producto');
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(54);
	$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Descripción');
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
	$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Costo Unitario');
	
 	$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
	$objPHPExcel->getDefaultStyle()->getFont()->setSize(12);
	
	$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFill()->getStartColor()->setARGB('FF808080');
	$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$sqlInsumos = "SELECT * FROM detpedidos d, insumo i WHERE d.idpedido = '$id' and d.idinsumo = i.id order by nombre";
	$resInsumos = mysql_query($sqlInsumos,$db);
	$fila=1;	
	while ($rowInsumos = mysql_fetch_assoc($resInsumos)) { 
		$fila++;
		$nombre = $rowInsumos['nombre']." (".$rowInsumos['numeroserie'].")";
		$descri = $rowInsumos['descripcion'];
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$fila, $nombre);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$fila, $descri);
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$fila, 0);
	}
	$filaTotal = $fila+2;
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$filaTotal, "TOTAL");
	$objPHPExcel->getActiveSheet()->setCellValue('C'.$filaTotal, "=SUM(C2:C".($fila).")");
	
	
	$objPHPExcel->getActiveSheet()->getStyle('A2:A'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	$objPHPExcel->getActiveSheet()->getStyle('B2:B'.$fila)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
	$objPHPExcel->getActiveSheet()->getStyle('C2:C'.$filaTotal)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
	$objPHPExcel->getActiveSheet()->getStyle('C2:C'.$filaTotal)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('B'.$filaTotal)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('B'.$filaTotal)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('C'.$filaTotal)->getFont()->setBold(true);
	
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save($archivoPedido);
	$objPHPExcel->disconnectWorksheets();
	unset($objWriter, $objPHPExcel);
	
	$pagina = "pedidos.php";
	Header("Location: $pagina"); 

} catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}

?>