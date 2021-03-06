<?php 
	$libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
	include($libPath."controlSessionUsimra.php");
	include($libPath."fechas.php");
	require($libPath."numeros.php");
	require($libPath."fpdf.php");

	$maquina = $_SERVER['SERVER_NAME'];
	$cuit = $_GET["cuit"];
	$acuerdo = $_GET["acuerdo"];

	if (isset($_POST["seleccion"])) {
		try {
			$hostname = $_SESSION['host'];
			$dbname = $_SESSION['dbname'];
			$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$dbh->beginTransaction();
		
			$cuotas = $_POST["seleccion"];
			for($z=0; $z<count($cuotas); $z++) {
				$cuota=$cuotas[$z];
	
				$sqlacuerdos =  "select * from cabacuerdosusimra where cuit = $cuit and nroacuerdo = $acuerdo";
				$resulacuerdos=  mysql_query( $sqlacuerdos,$db); 
				$rowacuerdos = mysql_fetch_array($resulacuerdos);
		
				$sqlcuotas = "select * from cuoacuerdosusimra where cuit = $cuit and nroacuerdo = $acuerdo and nrocuota = $cuota";
				$rescuotas =  mysql_query( $sqlcuotas,$db); 
				$rowcuotas = mysql_fetch_array($rescuotas);
	
				$nroact = $rowacuerdos['nroacta'];
				$nroacu = $acuerdo;
				$nrocuo = $cuota;
				$importe = $rowcuotas['montocuota'];
				$tipopago = $rowcuotas['tipocancelacion'];
				$cantbole = $rowcuotas['boletaimpresa'];
		
				if ($tipopago == 3) {
					$sqlvalor = "select * from valoresalcobrousimra where cuit = $cuit and nroacuerdo = $acuerdo and nrocuota = $cuota";
					$resvalor =  mysql_query( $sqlvalor,$db); 
					$rowvalor = mysql_fetch_array($resvalor);
					$nrocheque = $rowvalor['chequenrousimra'];
					$banco = $rowvalor['chequebancousimra'];
					$fechaChe = invertirFecha($rowvalor['chequefechausimra']);
				} else {
					$nrocheque = $rowcuotas['chequenro'];
					$banco = $rowcuotas['chequebanco'];
					$fechaChe = invertirFecha($rowcuotas['chequefecha']);
				}
	
				$ctrl =  date("YmdHis");
				$ctrlh = substr($ctrl,2,13);
				$h = '99';
				$ctrlh = $h.$ctrlh;
				
				$arrayBoletas[$nrocuo] = array('importe'=>$importe, 'ctrlh' => $ctrlh, 'nroact'=>$nroact, 'nroacu'=>$nroacu, 'nrocuo'=>$nrocuo, 'tipopago'=>$tipopago, 'ctrl' => $ctrl, 'fecha' => $rowcuotas['fechacuota']);
			
				$sql = "select * from empresas where cuit = $cuit";
				$result =  mysql_query( $sql,$db); 
				$row=mysql_fetch_array($result); 
		
				$sqllocalidad = "select * from localidades where codlocali = $row[codlocali]";
				$resultlocalidad =  mysql_query( $sqllocalidad,$db); 
				$rowlocalidad = mysql_fetch_array($resultlocalidad); 
	
				//Ejecucion del sql para ingreso del registro en tabla boletasusimra
				$sqlgrababoleta = "INSERT INTO boletasusimra (cuit,nroacuerdo,nrocuota,importe,nrocontrol,usuarioregistro) VALUES ('$cuit','$acuerdo','$cuota','$importe','$ctrlh','$_SESSION[usuario]')";
				$dbh->exec($sqlgrababoleta);
	
				//Ejecucion del sql para incrementar la cantidad de boletas impresas en tabla cuoacuerdosusimra
				if ($tipopago == 0) {
					$sqlactcuotas = "update cuoacuerdosusimra set tipocancelacion = 8, boletaimpresa = ($cantbole+2) where cuit = $cuit and nroacuerdo = $acuerdo and nrocuota = $cuota";
				}
				else {
					$sqlactcuotas = "update cuoacuerdosusimra set boletaimpresa = ($cantbole+2) where cuit = $cuit and nroacuerdo = $acuerdo and nrocuota = $cuota";
				}
				$dbh->exec($sqlactcuotas);
				sleep(1);
			}
			$dbh->commit();
		} catch (PDOException $e) {
			$error = "<b>No se han emitido las boletas seleccionadas</b><br><br> Para Sistemas: ".$e->getMessage();
			$dbh->rollback();
			$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/usimra/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
			header ($redire);
			exit(0);
		}
				 
		foreach($arrayBoletas as $datosBoleta) {
			$nota[0] = ("1 - Original: Para el BANCO como comprobante de Caja");
			$nota[1] = ("2 - Duplicado: Para el DEPOSITANTE");
			$nota[2] = ("3 - Triplicado: Para U.S.I.M.R.A. como comprobante de Control");
	
			$nume = $datosBoleta['importe'];
			$pepe = cfgValorEnLetras($nume);
	
			$nconvenio = 3617;
			$ncuasifinal = $nconvenio.$cuit.$datosBoleta['ctrlh'];
		
			//PONDERADOR 31 M�DULO 10 (D�gito Verificador)
			$npart3total = 0;
			$npart1total = 0;
			for ($i=0; $i < 29; $i++) {
				$npor3 = substr($ncuasifinal,$i,1);
				$npor33 = $npor3 * 3;
				$npart3total = $npart3total + $npor33;
				$i = $i + 1;
				$npor1 = substr($ncuasifinal,$i,1);
				$npart1total = $npart1total + $npor1;
			}
		
			//Suma de Productos
			$npartot = $npart1total + $npart3total;
		
			//Calculo del resto
			$largonpar = strlen($npartot);
			$ndigito = $largonpar -1;
			$nverifi01 = substr($npartot,$ndigito,1);
		
			//Si es 0 se toma 0, sino 10 - resto
			if ($nverifi01 == 0) {
				$dverifi = 0;
			} else {
				$dverifi = 10 - $nverifi01;
			} 
	
			$pdf = new FPDF();
			$pdf->AddPage('P','Legal');
	
			for ($w = 0; $w <2; $w++) {			  
				$pdf->SetFont('Arial','B',11);
				if($w == 0)
					$pdf->Cell(0,10,'',0,1);
				else
					$pdf->Cell(0,7,'',0,1);
				$pdf->Cell(18);
				$pdf->Cell(160,5,'UNION DE SINDICATOS DE LA INDUSTRIA MADERERA DE LA REPUBLICA','LTR',1,'C');
				$pdf->Cell(18);
				$pdf->Cell(160,6,'ARGENTINA - U.S.I.M.R.A.','LBR',1,'C');
				$pdf->SetFont('Arial','',9);
				$pdf->Cell(18);
				$pdf->Cell(160,4,'NOTA DE CREDITO para la Cuenta de Uni�n de Sindicatos de la Industria Maderera de la Rep�blica','LTR',1,'C');
				$pdf->Cell(18);
				$pdf->Cell(160,4,'Argentina (U.S.I.M.R.A.) y Federaci�n Argentina de la Industria Maderera y Afines (F.A.I.M.A.) - CCT 335/75','LR',1,'C');
				$pdf->Cell(18);
				$pdf->Cell(160,4,'Art�culos 32 y 32 bis.','LBR',1,'C');
				$pdf->Cell(18);
				$pdf->Cell(160,4,'Cta. Cte. N� 900004/93 (F.A.I.M.A. - U.S.I.M.R.A.) BANCO NACION - SUCURSAL CABALLITO',1,1,'C');
				$pdf->SetFont('Arial','',7);
				$pdf->Cell(55);
				$pdf->Cell(0,5,'',0,1);
				$pdf->Cell(50);
				$pdf->Cell(96,3,'BANCO DE LA NACION ARGENTINA - Sucursal Caballito - Rivadavia 5199 - C.A.B.A.',1,1,'C');
				$pdf->Cell(0,6,'',0,1);
				$pdf->Cell(18);	
				$pdf->Cell(25,3,'Empleador:',0,0,'L');
				$pdf->Cell(72,3,$row['nombre'],0,0,'L');
				$pdf->Cell(25,3,'CUIT:',0,0,'L');
				$pdf->Cell(38,3,$cuit,0,1,'L');
				$pdf->Cell(0,2,'',0,1);
				$pdf->Cell(18);
				$pdf->Cell(25,3,'Domicilio:',0,0,'L');
				$pdf->Cell(72,3,$row['domilegal'],0,0,'L');
				$pdf->Cell(25,3,'Localidad:',0,0,'L');
				$pdf->Cell(38,3,$rowlocalidad['nomlocali'],0,1,'L');
				$pdf->Cell(0,1,'',0,1);
				$pdf->Cell(18);
				$pdf->Cell(160,3,'CONCEPTOS DEPOSITADOS',1,1,'C');
				$pdf->Cell(18);
				$pdf->Cell(78.5,3,'Acta - N�Acuerdo - N�Cuota',1,0,'C');
				$pdf->Cell(41,3,'Vencimiento',1,0,'C');
				$pdf->Cell(40.5,3,'Importe',1,1,'C');
				$pdf->Cell(18);
				$pdf->Cell(78.5,3,$datosBoleta['nroact']." - ".$datosBoleta['nroacu']." - ".$datosBoleta['nrocuo'],1,0,'C');
				$pdf->Cell(41,3,invertirFecha($datosBoleta['fecha']),1,0,'C');
				$pdf->Cell(40.5,3,number_format($datosBoleta['importe'], 2, ",", "."),1,1,'C');
				$pdf->Cell(18);
				$pdf->Cell(160,4,'U.S.I.M.R.A. formula expresa reserva de intereses por pagos fuera de t�rmino',0,1,'L');
				$pdf->Cell(18);
				$pdf->Cell(14,3,'Efectivo',1,0,'C');
				if ($datosBoleta['tipopago']) {
					$pdf->Cell(3.5,3,'X',1,0,'C');
				} else {
					$pdf->Cell(3.5,3,' ',1,0,'C');
				}
				$pdf->Cell(14,3,'Cheque',1,0,'C');
				if ($datosBoleta['tipopago'] == 1 or $datosBoleta['tipopago'] == 3) {
					$pdf->Cell(3.5,3,'X',1,0,'C');
					$pdf->Cell(125,3,"Fecha: ".$fechaChe." - Nro.: ".$nrocheque." - Banco: ".$banco,1,1,'C');
				} else {
					$pdf->Cell(3.5,3,' ',1,0,'C');
					$pdf->Cell(125,3,' ',1,1,'C');
				}
				
				$pdf->Cell(18);
				$pdf->Cell(17.5,3,'Son Pesos:',1,0,'C');
				$pdf->Cell(142.5,3,strtoupper($pepe).".-",1,1,'L');
	
				$pdf->Cell(0,13,'',0,1);
				$pdf->Cell(18);
				$pdf->SetFont('Arial','',12);
				$pdf->Cell(160,3,$ncuasifinal.$dverifi,0,1,'C');
				$pdf->SetFont('Arial','',7);
				$pdf->Cell(0,5,'',0,1);
				$pdf->Cell(18);
				$pdf->Cell(160,3,$nota[$w],0,1,'L');
				$pdf->Cell(0,3,'',0,1);
				$pdf->Cell(28);
				$pdf->Cell(150,3,'- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - ',0,1,'L');
			}
	
			for($t=0;$t<2;$t++) {
				if($t==0){
					$poslinima=94;
					$poslintij=114;
				} else {
					$poslinima=$poslinima+106;
					$poslintij=$poslintij+107;
				}
	
				$posiniima=69;
	
				$pdf->Image('jpg/x.jpg',$posiniima,$poslinima,2.6,7);
	
				for ($i=0; $i < 29; $i++) {
					$poscuit = "jpg/".substr($ncuasifinal,$i,1).".jpg";
					$posiniima=$posiniima+2.6;
					$pdf->Image($poscuit,$posiniima,$poslinima,2.6,7);
				}
	
				$pdf->Image('jpg/tijera.jpg',30,$poslintij,6.5,4);
			}
	
			if(strcmp("localhost",$maquina)==0)
				$nombrearchivo = $datosBoleta['ctrl']."-".$cuit."-".$datosBoleta['nroact']."-".$datosBoleta['nroacu']."-".$datosBoleta['nrocuo'].".pdf";
			else
				$nombrearchivo = "/home/sistemas/Documentos/Liquidaciones/BoletasUSIMRA/".$datosBoleta['ctrl']."-".$cuit."-".$datosBoleta['nroact']."-".$datosBoleta['nroacu']."-".$datosBoleta['nrocuo'].".pdf";
						
			$pdf->Output($nombrearchivo,'F');
		}

		$pagina = "fiscalizacionImpBoletas.php?acuerdo=$acuerdo&cuit=$cuit";
		header ("Location: $pagina");
	} else {
		//echo "No paso valores"; echo "<br>";
		$pagina = "fiscalizacionImpBoletas.php?acuerdo=$acuerdo&cuit=$cuit";
		header ("Location: $pagina");
	}
?>