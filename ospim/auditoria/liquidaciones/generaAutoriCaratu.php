<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
require_once($libPath."fpdf.php");
require_once($libPath."FPDI-1.6.1/fpdi.php"); 
$maquina = $_SERVER['SERVER_NAME'];
if(strcmp("localhost",$maquina)==0)
	$carpetaCaratulas="/home/sistemas/Documentos/Repositorio/LotesCaratulas/";
else
	$carpetaCaratulas="/home/sistemas/Documentos/Repositorio/LotesCaratulas/";
$fechamodificacion = date("Y-m-d H:i:s");
$usuariomodificacion = $_SESSION['usuario'];
//sleep(2);
$whereinautorizaciones='';
$whereincaratulasagrupadas='';
$whereincaratulasindividuales='';
$gastodelegaciones='';
$lineasgastos=0;
$prestadorpertenecia='';
if(isset($_POST)) {
	if(isset($_POST['idFactura'])) {
		// Seccion de Emision de Autorizaciones
		if(isset($_POST['autorizacion'])) {
			foreach($_POST['autorizacion'] as $autorizaciones) {
				$whereinautorizaciones .= $autorizaciones.',';
			}
			$whereinautorizaciones = 'in('.substr($whereinautorizaciones, 0, -1).')';
			try {
				$hostname = $_SESSION['host'];
				$dbname = $_SESSION['dbname'];
				$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
				$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$dbh->beginTransaction();
		
				$sqlActualizaFactura = "UPDATE facturas SET autorizacionpago = :autorizacionpago, fechamodificacion = :fechamodificacion, usuariomodificacion = :usuariomodificacion WHERE id $whereinautorizaciones";
				$resActualizaFactura = $dbh->prepare($sqlActualizaFactura);
				if($resActualizaFactura->execute(array(':autorizacionpago' => 1,':fechamodificacion' => $fechamodificacion, ':usuariomodificacion' => $usuariomodificacion)))
				$dbh->commit();
			}
			catch (PDOException $e) {
				$error = $e->getMessage();
				$dbh->rollback();
				$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?&error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
				header($redire);
				exit(0);
			}
		}
		// Seccion de Emision de Caratulas
		foreach($_POST['idFactura'] as $facturas) {
			$caratula = 'caratula'.$facturas;
			if(isset($_POST[$caratula])) {
				$tipocaratula = $_POST[$caratula][0];
				if(strcmp($tipocaratula, "A")==0) {
					$whereincaratulasagrupadas .= $facturas.',';
				}
				if(strcmp($tipocaratula, "I")==0) {
					$whereincaratulasindividuales .= $facturas.',';
				}
			}
		}
		if(strcmp($whereincaratulasagrupadas, "")!=0 || strcmp($whereincaratulasindividuales, "")!=0) {
			$pdf = new FPDI();
			if(strcmp($whereincaratulasagrupadas, "")!=0) {
				$whereincaratulasagrupadas = 'in('.substr($whereincaratulasagrupadas, 0, -1).')';
				$sqlPrestadoresAgrupadas="SELECT p.cuit AS cuitagrupado, SUM(f.importecomprobante) AS importetotal, SUM(f.totaldebito) AS importedebito, SUM(f.importeliquidado) AS importeapagar, f.usuarioliquidacion, p.nombre, p.domicilio, p.numpostal, l.nomlocali AS localidad, v.descrip AS provincia FROM facturas f, prestadores p, localidades l, provincia v WHERE f.id $whereincaratulasagrupadas AND f.idPrestador = p.codigoprestador AND p.codlocali = l.codlocali  AND p.codprovin = v.codprovin GROUP BY p.cuit ORDER BY p.cuit";
				$resPrestadoresAgrupadas = mysql_query($sqlPrestadoresAgrupadas,$db);
				while($rowPrestadoresAgrupadas=mysql_fetch_array($resPrestadoresAgrupadas)) {
					$pdf->AddPage('P','Legal');
					$pdf->SetFont('TIMES','',10);
					$pdf->Ln(15);
					$pdf->Cell(20);
					$pdf->Cell(172,6,'FACTURAS',1,1,'C');
					$cuitagrupado = $rowPrestadoresAgrupadas['cuitagrupado'];
					$sqlCaratulasAgrupadas="SELECT f.* FROM facturas f , prestadores p WHERE f.id $whereincaratulasagrupadas AND f.idPrestador = p.codigoprestador AND p.cuit = $cuitagrupado";
					$resCaratulasAgrupadas = mysql_query($sqlCaratulasAgrupadas,$db);
					$i=0;
					$lineasgastos=0;
					while($rowCaratulasAgrupadas=mysql_fetch_array($resCaratulasAgrupadas)) {
						$pdf->Cell(20);
						$pdf->Cell(25,6,'ID '.$rowCaratulasAgrupadas['id'],1,0,'L');
						$pdf->Cell(33,6,'NRO '.$rowCaratulasAgrupadas['puntodeventa'].'-'.$rowCaratulasAgrupadas['nrocomprobante'],1,0,'L');
						$pdf->Cell(37,6,'ENTRADA '.invertirFecha($rowCaratulasAgrupadas['fecharecepcion']),1,0,'L');
						$pdf->Cell(77,6,'I.F.: $'.$rowCaratulasAgrupadas['importecomprobante'].'-I.D.: $'.$rowCaratulasAgrupadas['totaldebito'].'-I.P.: $'.$rowCaratulasAgrupadas['importeliquidado'],1,1,'L');
						$i++;
						$gastodelegaciones='';
						$sqlGastoDelegaciones="SELECT t.codidelega AS delegacion, SUM(b.totalcredito) AS distribuciondelegacion FROM facturas f, facturasbeneficiarios b, titulares t WHERE f.id = $rowCaratulasAgrupadas[id] and f.id = b.idFactura and b.nroafiliado = t.nroafiliado GROUP BY delegacion";
						$resGastoDelegaciones = mysql_query($sqlGastoDelegaciones,$db);
						$totaldelegaciones = mysql_num_rows($resGastoDelegaciones);
						$lineasgastos = ceil($totaldelegaciones / 5);
						$j=0;
						while($rowGastoDelegaciones=mysql_fetch_array($resGastoDelegaciones)) {
							if($j<5) {
								$gastodelegaciones .= $rowGastoDelegaciones['delegacion'].' - '.$rowGastoDelegaciones['distribuciondelegacion'].', ';
								$j++;
							} else {
								$gastodelegaciones = substr($gastodelegaciones, 0, -2);
								$pdf->Cell(20);
								$pdf->Cell(172,6,$gastodelegaciones,0,1,'L');
								$gastodelegaciones='';
								$gastodelegaciones .= $rowGastoDelegaciones['delegacion'].' - '.$rowGastoDelegaciones['distribuciondelegacion'].', ';
								$j=0;
							}
						}
						$gastodelegaciones = substr($gastodelegaciones, 0, -2);
						$pdf->Cell(20);
						$pdf->Cell(172,6,$gastodelegaciones,0,1,'L');
						$i = $i + $lineasgastos;
					}
					while($i<20) {
						$pdf->Cell(20);
						$pdf->Cell(25,6,'',1,0,'L');
						$pdf->Cell(33,6,'',1,0,'L');
						$pdf->Cell(37,6,'',1,0,'L');
						$pdf->Cell(77,6,'',1,1,'L');
						$i++;
					}
					$pdf->Ln(5);
					$pdf->Cell(20);
					$pdf->Cell(172,6,'PRESTADOR '.$rowPrestadoresAgrupadas['nombre'],0,1,'L');
					$pdf->Ln(2);
					$pdf->Cell(20);
					$pdf->Cell(172,6,'DIRECCION '.$rowPrestadoresAgrupadas['domicilio'].' - '.$rowPrestadoresAgrupadas['numpostal'].' - '.$rowPrestadoresAgrupadas['localidad'].' - '.$rowPrestadoresAgrupadas['provincia'],0,1,'L');
					$pdf->Ln(2);
					$pdf->Cell(20);
					$prestadorpertenecia='';
					$sqlPrestadorPertenencia="SELECT d.nombre AS sindicato FROM prestadores p, prestadorjurisdiccion j, delegaciones d WHERE p.cuit = '$cuitagrupado' AND p.codigoprestador = j.codigoprestador AND j.pertenencia = 1 and j.codidelega = d.codidelega";
					$resPrestadorPertenencia = mysql_query($sqlPrestadorPertenencia,$db);
					if(mysql_num_rows($resPrestadorPertenencia)!=0) {
						$rowPrestadorPertenencia=mysql_fetch_array($resPrestadorPertenencia);
						$prestadorpertenecia=$rowPrestadorPertenencia['sindicato'];
					}
					$pdf->Cell(172,6,'SINDICATO '.$prestadorpertenecia,0,1,'L');
					$pdf->Ln(2);
					$pdf->Cell(20);
					$pdf->Cell(172,6,'PERIODO/S ',0,1,'L');
					$pdf->Ln(2);
					$pdf->Cell(20);
					$pdf->Cell(86,6,'IMPORTE FACTURADO $ '.$rowPrestadoresAgrupadas['importetotal'],0,0,'L');
					$pdf->Cell(86,6,'DEBITO O DESCUENTO $ '.$rowPrestadoresAgrupadas['importedebito'],0,1,'L');
					$pdf->Ln(8);
					$pdf->Cell(20);
					$pdf->Cell(172,6,'IMPORTE A PAGAR $'.$rowPrestadoresAgrupadas['importeapagar'],0,1,'C');
					$pdf->Ln(90);
					$pdf->Cell(20);
					$pdf->Cell(17,6,'LIQUIDO: ',0,0,'L');
					$pdf->SetFont('Arial','BI',18);
					$pdf->Cell(155,6,'@'.$rowPrestadoresAgrupadas['usuarioliquidacion'],0,1,'L');
					$pdf->SetFont('TIMES','',10);
					$pdf->Ln(8);
					$pdf->Cell(20);
					$pdf->Cell(172,6,'CHEQUE/TRANSF. NRO .............................        ',0,1,'R');
					$pdf->Ln(2);
					$pdf->Cell(20);
					$pdf->Cell(172,6,'FECHA ...........................................................        ',0,1,'R');
					$pdf->Image('img/Firma Giraudo.png',41,220,18,50);
					$pdf->Image('img/Sello Giraudo.png',31,250,35,13);
					$pdf->Image('img/fgornatti.png',120,232,25,30);
					$pdf->Image('img/sgornatti.png',110,245,50,18);
				}
			}
			if(strcmp($whereincaratulasindividuales, "")!=0) {	
				$whereincaratulasindividuales = 'in('.substr($whereincaratulasindividuales, 0, -1).')';
				$sqlCaratulasIndividuales="SELECT f.*, p.nombre, p.domicilio, p.numpostal, p.cuit, l.nomlocali AS localidad, v.descrip AS provincia FROM facturas f, prestadores p, localidades l, provincia v WHERE id $whereincaratulasindividuales AND f.idPrestador = p.codigoprestador AND p.codlocali = l.codlocali AND p.codprovin = v.codprovin";
				$resCaratulasIndividuales = mysql_query($sqlCaratulasIndividuales,$db);
				while($rowCaratulasIndividuales=mysql_fetch_array($resCaratulasIndividuales)) {
					$i=0;
					$pdf->AddPage('P','Legal');
					$pdf->SetFont('TIMES','',10);
					$pdf->Ln(15);
					$pdf->Cell(20);
					$pdf->Cell(172,6,"FACTURAS",1,1,'C');
					$pdf->Cell(20);
					$pdf->Cell(25,6,'ID '.$rowCaratulasIndividuales['id'],1,0,'L');
					$pdf->Cell(33,6,'NRO '.$rowCaratulasIndividuales['puntodeventa'].'-'.$rowCaratulasIndividuales['nrocomprobante'],1,0,'L');
					$pdf->Cell(37,6,'ENTRADA '.invertirFecha($rowCaratulasIndividuales['fecharecepcion']),1,0,'L');
					$pdf->Cell(77,6,'I.F.: $'.$rowCaratulasIndividuales['importecomprobante'].'-I.D.: $'.$rowCaratulasIndividuales['totaldebito'].'-I.P.: $'.$rowCaratulasIndividuales['importeliquidado'],1,1,'L');
					$i++;
					$gastodelegaciones='';
					$sqlGastoDelegaciones="SELECT t.codidelega AS delegacion, SUM(b.totalcredito) AS distribuciondelegacion FROM facturas f, facturasbeneficiarios b, titulares t WHERE f.id = $rowCaratulasIndividuales[id] and f.id = b.idFactura and b.nroafiliado = t.nroafiliado GROUP BY delegacion";
					$resGastoDelegaciones = mysql_query($sqlGastoDelegaciones,$db);
					$totaldelegaciones = mysql_num_rows($resGastoDelegaciones);
					$lineasgastos = ceil($totaldelegaciones / 5);
					$j=0;
					while($rowGastoDelegaciones=mysql_fetch_array($resGastoDelegaciones)) {
						if($j<5) {
							$gastodelegaciones .= $rowGastoDelegaciones['delegacion'].' - '.$rowGastoDelegaciones['distribuciondelegacion'].', ';
							$j++;
						} else {
							$gastodelegaciones = substr($gastodelegaciones, 0, -2);
							$pdf->Cell(20);
							$pdf->Cell(172,6,$gastodelegaciones,0,1,'L');
							$gastodelegaciones='';
							$gastodelegaciones .= $rowGastoDelegaciones['delegacion'].' - '.$rowGastoDelegaciones['distribuciondelegacion'].', ';
							$j=0;
						}
					}
					$gastodelegaciones = substr($gastodelegaciones, 0, -2);
					$pdf->Cell(20);
					$pdf->Cell(172,6,$gastodelegaciones,0,1,'L');
					$i = $i + $lineasgastos;
					while($i<20) {
						$pdf->Cell(20);
						$pdf->Cell(25,6,'',1,0,'L');
						$pdf->Cell(33,6,'',1,0,'L');
						$pdf->Cell(37,6,'',1,0,'L');
						$pdf->Cell(77,6,'',1,1,'L');
						$i++;
					}
					$pdf->Ln(5);
					$pdf->Cell(20);
					$pdf->Cell(172,6,'PRESTADOR '.$rowCaratulasIndividuales['nombre'],0,1,'L');
					$pdf->Ln(2);
					$pdf->Cell(20);
					$pdf->Cell(172,6,'DIRECCION '.$rowCaratulasIndividuales['domicilio'].' - '.$rowCaratulasIndividuales['numpostal'].' - '.$rowCaratulasIndividuales['localidad'].' - '.$rowCaratulasIndividuales['provincia'],0,1,'L');
					$pdf->Ln(2);
					$pdf->Cell(20);
					$prestadorpertenecia='';
					$sqlPrestadorPertenencia="SELECT d.nombre AS sindicato FROM prestadores p, prestadorjurisdiccion j, delegaciones d WHERE p.cuit = '$rowCaratulasIndividuales[cuit]' AND p.codigoprestador = j.codigoprestador AND j.pertenencia = 1 and j.codidelega = d.codidelega";
					$resPrestadorPertenencia = mysql_query($sqlPrestadorPertenencia,$db);
					if(mysql_num_rows($resPrestadorPertenencia)!=0) {
						$rowPrestadorPertenencia=mysql_fetch_array($resPrestadorPertenencia);
						$prestadorpertenecia=$rowPrestadorPertenencia['sindicato'];
					}
					$pdf->Cell(172,6,'SINDICATO '.$prestadorpertenecia,0,1,'L');
					$pdf->Ln(2);
					$pdf->Cell(20);
					$pdf->Cell(172,6,'PERIODO/S ',0,1,'L');
					$pdf->Ln(2);
					$pdf->Cell(20);
					$pdf->Cell(86,6,'IMPORTE FACTURADO $ '.$rowCaratulasIndividuales['importecomprobante'],0,0,'L');
					$pdf->Cell(86,6,'DEBITO O DESCUENTO $ '.$rowCaratulasIndividuales['totaldebito'],0,1,'L');
					$pdf->Ln(8);
					$pdf->Cell(20);
					$pdf->Cell(172,6,'IMPORTE A PAGAR $'.$rowCaratulasIndividuales['importeliquidado'],0,1,'C');
					$pdf->Ln(90);
					$pdf->Cell(20);
					$pdf->Cell(17,6,'LIQUIDO: ',0,0,'L');
					$pdf->SetFont('Arial','BI',18);
					$pdf->Cell(155,6,'@'.$rowCaratulasIndividuales['usuarioliquidacion'],0,1,'L');
					$pdf->SetFont('TIMES','',10);
					$pdf->Ln(8);
					$pdf->Cell(20);
					$pdf->Cell(172,6,'CHEQUE/TRANSF. NRO .............................        ',0,1,'R');
					$pdf->Ln(2);
					$pdf->Cell(20);
					$pdf->Cell(172,6,'FECHA ...........................................................        ',0,1,'R');
					$pdf->Image('img/Firma Giraudo.png',41,220,18,50);
					$pdf->Image('img/Sello Giraudo.png',31,250,35,13);
					$pdf->Image('img/fgornatti.png',120,232,25,30);
					$pdf->Image('img/sgornatti.png',110,245,50,18);
				}
			}
			$usuariogeneracion = $_SESSION['usuario'];
			$fechaGeneracion = date("YmdHis").$usuariogeneracion;
			$nombreCaratula = "C".$fechaGeneracion.".pdf";
			
			//if(strcmp("localhost",$maquina)==0){ 
			//	$nombrearchivo = $nombreCaratula;
			//} else {
				$nombrearchivo = $carpetaCaratulas.$nombreCaratula;
			//}

			$pdf->Output($nombrearchivo,'F');
			chmod($nombrearchivo, 0777);
			echo json_encode(array('result'=> true,'archivopdf'=>$nombrearchivo));
		} else {
			echo json_encode(array('result'=> false,'archivopdf'=>''));
		}
	}
}
?>