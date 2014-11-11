<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
require($libPath."fpdf.php");
$maquina = $_SERVER['SERVER_NAME'];

if(strcmp("localhost",$maquina)==0)
	$carpetaLote=$_SERVER['DOCUMENT_ROOT']."/ospim/afiliados/carnets/lotesimpresion/";
else
	$carpetaLote="/home/sistemas/Documentos/Repositorio/LotesImpresion/";

$lote = date("YmdHis");
$usuariolote = $_SESSION['usuario'];
$fechalote = date("Y-m-d H:i:s");

switch(date("m"))  
{  
	case "01":  
		$nombremes="Enero";
	break;  
	case "02":  
		$nombremes="Febrero";
	break;  
	case "03":  
		$nombremes="Marzo";
	break;  
	case "04":  
		$nombremes="Abril";
	break;  
	case "05":  
		$nombremes="Mayo";
	break;  
	case "06":  
		$nombremes="Junio";
	break;  
	case "07":  
		$nombremes="Julio";
	break;  
	case "08":  
		$nombremes="Agosto";
	break;  
	case "09":  
		$nombremes="Setiembre";
	break;  
	case "10":  
		$nombremes="Octubre";
	break;  
	case "11":  
		$nombremes="Noviembre";
	break;  
	case "12":  
		$nombremes="Diciembre";
	break;  
}  

if (isset($_POST['titularSeleccionado'])) {
	$titular = $_POST['titularSeleccionado'];
	for($i=0; $i<count($titular); $i++) {
		$titulares.=$titular[$i];
		if(($i+1)!=count($titular)) {
			$titulares.=",";
		}
		//echo $titulares;  echo "<br>";
	}

	$canTitularesAzul = 0;
	$totCarnetAzul = 0;
	$sqlTitularesAzul="SELECT nroafiliado, apellidoynombre, fechaobrasocial, cuil, tipodocumento, nrodocumento, domicilio, codlocali, indpostal, numpostal, alfapostal, codidelega, cuitempresa FROM titulares WHERE nroafiliado IN($titulares) and tipoafiliado = 'R'";
	//echo $sqlTitularesAzul;  echo "<br>";
	$resTitularesAzul=mysql_query($sqlTitularesAzul,$db);
	if(mysql_num_rows($resTitularesAzul)!=0) {
		$contTitulares = 1;
		$pdf = new FPDF('P','mm',array(215,266));
		$pdf->SetMargins(10, 16);
		$pdf->AddPage();
		while($rowTitularesAzul=mysql_fetch_assoc($resTitularesAzul)) {
			if($contTitulares == 5) {
				$pdf->AddPage();
				$contTitulares = 1;
			}
			$pdf->SetFont('Courier','B',8);
			$pdf->Cell(95,3,"",0,1,'R');
			$pdf->Cell(73,3,"AFIL.: ".$rowTitularesAzul['apellidoynombre'],0,0,'L');
			$pdf->Cell(22,3,"NRO : ".$rowTitularesAzul['nroafiliado'],0,1,'R');
			$pdf->SetFont('Courier','',8);
			$pdf->Cell(35,3,"F.ING: ".invertirFecha($rowTitularesAzul['fechaobrasocial']),0,0,'L');
			$pdf->Cell(35,3,"CUIL: ".$rowTitularesAzul['cuil'],0,0,'C');
			$pdf->Cell(25,3,$rowTitularesAzul['tipodocumento']." ".$rowTitularesAzul['nrodocumento'],0,1,'R');
			$pdf->Cell(95,3,"DOMIC: ".substr($rowTitularesAzul['domicilio'],0,45),0,1,'L');
			$nomlocali = buscarLocalidad($rowTitularesAzul['codlocali'],$db);
			$pdf->Cell(70,3,"LOCAL: ".$nomlocali,0,0,'L');
			$pdf->Cell(25,3,"C.P.: ".$rowTitularesAzul['indpostal']."".$rowTitularesAzul['numpostal']."".$rowTitularesAzul['alfapostal'],0,1,'R');
			$nomdelega = buscarDelegacion($rowTitularesAzul['codidelega'],$db,0);
			$pdf->Cell(45,3,"SINDI: ".substr($nomdelega,0,19),0,0,'L');
			$nomempresa = buscarEmpresa($rowTitularesAzul['cuitempresa'],$db);
			$pdf->Cell(50,3,"EMP: ".substr($nomempresa,0,24),0,1,'L');

			$listadoTitulares[] = array('nroafiliado' => $rowTitularesAzul['nroafiliado'], 'apellidoynombre' => $rowTitularesAzul['apellidoynombre'], 'afiliadosindical' => "S", 'cuitempresa' => $rowTitularesAzul['cuitempresa'], 'nombreempresa' => $nomempresa);

			$nroafiliado = $rowTitularesAzul['nroafiliado'];
			$sqlFamiliaresAzul="SELECT tipoparentesco, apellidoynombre, tipodocumento, nrodocumento, fechanacimiento FROM familiares WHERE nroafiliado = '$nroafiliado' AND emitecarnet = 1";
			//echo $sqlFamiliaresAzul;  echo "<br>";
			$resFamiliaresAzul=mysql_query($sqlFamiliaresAzul,$db);
			if(mysql_num_rows($resFamiliaresAzul)!=0) {
				$contFamiliares = 1;
				$pdf->Ln(1);
				$pdf->SetFillColor(222,222,222);
				$pdf->SetFont('Courier','B',8);
				$pdf->Cell(95,3,"GRUPO FAMILIAR",0,1,'C');
				$pdf->Ln(1);
				$pdf->SetFont('Courier','',7);
				$pdf->Cell(17,3,"PARENTESCO",1,0,'C',true);
				$pdf->Cell(41,3,"APELLIDO Y NOMBRE",1,0,'C',true);
				$pdf->Cell(19,3,"DOCUMENTO",1,0,'C',true);
				$pdf->Cell(18,3,"NACIMIENTO",1,1,'C',true);
				while($rowFamiliaresAzul=mysql_fetch_assoc($resFamiliaresAzul)) {
					if($contFamiliares == 8) {
						if($contTitulares == 4) {
							$pdf->AddPage();
							$contTitulares = 1;
						} else {
							$contTitulares++;
						}
						$pdf->Ln(16);
						$pdf->SetFont('Courier','B',8);
						$pdf->Cell(95,3,"CARNET ADICIONAL",0,1,'R');
						$pdf->Cell(73,3,"AFIL.: ".$rowTitularesAzul['apellidoynombre'],0,0,'L');
						$pdf->Cell(22,3,"NRO : ".$rowTitularesAzul['nroafiliado'],0,1,'R');
						$pdf->SetFont('Courier','',8);
						$pdf->Cell(35,3,"F.ING: ".invertirFecha($rowTitularesAzul['fechaobrasocial']),0,0,'L');
						$pdf->Cell(35,3,"CUIL: ".$rowTitularesAzul['cuil'],0,0,'C');
						$pdf->Cell(25,3,$rowTitularesAzul['tipodocumento']." ".$rowTitularesAzul['nrodocumento'],0,1,'R');
						$pdf->Cell(95,3,"DOMIC: ".substr($rowTitularesAzul['domicilio'],0,45),0,1,'L');
						$nomlocali = buscarLocalidad($rowTitularesAzul['codlocali'],$db);
						$pdf->Cell(70,3,"LOCAL: ".$nomlocali,0,0,'L');
						$pdf->Cell(25,3,"C.P.: ".$rowTitularesAzul['indpostal']."".$rowTitularesAzul['numpostal']."".$rowTitularesAzul['alfapostal'],0,1,'R');
						$nomdelega = buscarDelegacion($rowTitularesAzul['codidelega'],$db,0);
						$pdf->Cell(45,3,"SINDI: ".substr($nomdelega,0,19),0,0,'L');
						$nomempresa = buscarEmpresa($rowTitularesAzul['cuitempresa'],$db);
						$pdf->Cell(50,3,"EMP: ".substr($nomempresa,0,24),0,1,'L');
						$pdf->Ln(1);
						$pdf->SetFont('Courier','B',8);
						$pdf->Cell(95,3,"GRUPO FAMILIAR",0,1,'C');
						$pdf->Ln(1);
						$pdf->SetFont('Courier','',7);
						$pdf->Cell(17,3,"PARENTESCO",1,0,'C',true);
						$pdf->Cell(41,3,"APELLIDO Y NOMBRE",1,0,'C',true);
						$pdf->Cell(19,3,"DOCUMENTO",1,0,'C',true);
						$pdf->Cell(18,3,"NACIMIENTO",1,1,'C',true);
						$contFamiliares = 1;
						$totCarnetAzul++;
					}

					if($rowFamiliaresAzul['tipoparentesco'] <= 2) {
						$parentesco = "CONYUGE";
					}
					if($rowFamiliaresAzul['tipoparentesco'] >= 3 && $rowFamiliaresAzul['tipoparentesco'] <= 6) {
						$parentesco = "HIJO";
					}
					if($rowFamiliaresAzul['tipoparentesco'] >= 7 && $rowFamiliaresAzul['tipoparentesco'] <= 8) {
						$parentesco = "A CARGO";
					}
					if($rowFamiliaresAzul['tipoparentesco'] == 9) {
						$parentesco = "HIJO";
					}
					$pdf->Cell(17,3,$parentesco,0,0,'C');
					$pdf->Cell(41,3,substr($rowFamiliaresAzul['apellidoynombre'],0,26),0,0,'C');
					$pdf->Cell(19,3,$rowFamiliaresAzul['tipodocumento']." ".$rowFamiliaresAzul['nrodocumento'],0,0,'C');
					$pdf->Cell(18,3,invertirFecha($rowFamiliaresAzul['fechanacimiento']),0,1,'C');

					$contFamiliares++;
				}
				$pdf->Ln(41-($contFamiliares*3));
			} else {
				$pdf->Ln(46);
			}

			$contTitulares++;
			$totCarnetAzul++;
			$canTitularesAzul++;
		}
		$nombrearchivoA = $carpetaLote."A".$lote.$usuariolote.".pdf";
		$pdf->Output($nombrearchivoA,'F');
	}

	$canTitularesBordo = 0;
	$totCarnetBordo = 0;
	$sqlTitularesBordo="SELECT nroafiliado, apellidoynombre, fechaobrasocial, cuil, tipodocumento, nrodocumento, domicilio, codlocali, indpostal, numpostal, alfapostal, codidelega, cuitempresa FROM titulares WHERE nroafiliado IN($titulares) and tipoafiliado = 'S'";
	//echo $sqlTitularesBordo;  echo "<br>";
	$resTitularesBordo=mysql_query($sqlTitularesBordo,$db);
	if(mysql_num_rows($resTitularesBordo)!=0) {
		$contTitulares = 1;
		$pdf = new FPDF('P','mm',array(215,266));
		$pdf->SetMargins(10, 16);
		$pdf->AddPage();
		while($rowTitularesBordo=mysql_fetch_assoc($resTitularesBordo)) {
			if($contTitulares == 5) {
				$pdf->AddPage();
				$contTitulares = 1;
			}
			$pdf->SetFont('Courier','B',8);
			$pdf->Cell(95,3,"",0,1,'R');
			$pdf->Cell(73,3,"AFIL.: ".$rowTitularesBordo['apellidoynombre'],0,0,'L');
			$pdf->Cell(22,3,"NRO : ".$rowTitularesBordo['nroafiliado'],0,1,'R');
			$pdf->SetFont('Courier','',8);
			$pdf->Cell(35,3,"F.ING: ".invertirFecha($rowTitularesBordo['fechaobrasocial']),0,0,'L');
			$pdf->Cell(35,3,"CUIL: ".$rowTitularesBordo['cuil'],0,0,'C');
			$pdf->Cell(25,3,$rowTitularesBordo['tipodocumento']." ".$rowTitularesBordo['nrodocumento'],0,1,'R');
			$pdf->Cell(95,3,"DOMIC: ".substr($rowTitularesBordo['domicilio'],0,45),0,1,'L');
			$nomlocali = buscarLocalidad($rowTitularesBordo['codlocali'],$db);
			$pdf->Cell(70,3,"LOCAL: ".$nomlocali,0,0,'L');
			$pdf->Cell(25,3,"C.P.: ".$rowTitularesBordo['indpostal']."".$rowTitularesBordo['numpostal']."".$rowTitularesBordo['alfapostal'],0,1,'R');
			$nomdelega = buscarDelegacion($rowTitularesBordo['codidelega'],$db,0);
			$pdf->Cell(45,3,"SINDI: ".substr($nomdelega,0,19),0,0,'L');
			$nomempresa = buscarEmpresa($rowTitularesBordo['cuitempresa'],$db);
			$pdf->Cell(50,3,"EMP: ".substr($nomempresa,0,24),0,1,'L');

			$listadoTitulares[] = array('nroafiliado' => $rowTitularesBordo['nroafiliado'], 'apellidoynombre' => $rowTitularesBordo['apellidoynombre'], 'afiliadosindical' => "N", 'cuitempresa' => $rowTitularesBordo['cuitempresa'], 'nombreempresa' => $nomempresa);

			$nroafiliado = $rowTitularesBordo['nroafiliado'];
			$sqlFamiliaresBordo="SELECT tipoparentesco, apellidoynombre, tipodocumento, nrodocumento, fechanacimiento FROM familiares WHERE nroafiliado = '$nroafiliado' AND emitecarnet = 1";
			//echo $sqlFamiliaresBordo;  echo "<br>";
			$resFamiliaresBordo=mysql_query($sqlFamiliaresBordo,$db);
			if(mysql_num_rows($resFamiliaresBordo)!=0) {
				$contFamiliares = 1;
				$pdf->Ln(1);
				$pdf->SetFillColor(222,222,222);
				$pdf->SetFont('Courier','B',8);
				$pdf->Cell(95,3,"GRUPO FAMILIAR",0,1,'C');
				$pdf->Ln(1);
				$pdf->SetFont('Courier','',7);
				$pdf->Cell(17,3,"PARENTESCO",1,0,'C',true);
				$pdf->Cell(41,3,"APELLIDO Y NOMBRE",1,0,'C',true);
				$pdf->Cell(19,3,"DOCUMENTO",1,0,'C',true);
				$pdf->Cell(18,3,"NACIMIENTO",1,1,'C',true);
				while($rowFamiliaresBordo=mysql_fetch_assoc($resFamiliaresBordo)) {
					if($contFamiliares == 8) {
						if($contTitulares == 4) {
							$pdf->AddPage();
							$contTitulares = 1;
						} else {
							$contTitulares++;
						}
						$pdf->Ln(16);
						$pdf->SetFont('Courier','B',8);
						$pdf->Cell(95,3,"CARNET ADICIONAL",0,1,'R');
						$pdf->Cell(73,3,"AFIL.: ".$rowTitularesBordo['apellidoynombre'],0,0,'L');
						$pdf->Cell(22,3,"NRO : ".$rowTitularesBordo['nroafiliado'],0,1,'R');
						$pdf->SetFont('Courier','',8);
						$pdf->Cell(35,3,"F.ING: ".invertirFecha($rowTitularesBordo['fechaobrasocial']),0,0,'L');
						$pdf->Cell(35,3,"CUIL: ".$rowTitularesBordo['cuil'],0,0,'C');
						$pdf->Cell(25,3,$rowTitularesBordo['tipodocumento']." ".$rowTitularesBordo['nrodocumento'],0,1,'R');
						$pdf->Cell(95,3,"DOMIC: ".substr($rowTitularesBordo['domicilio'],0,45),0,1,'L');
						$nomlocali = buscarLocalidad($rowTitularesBordo['codlocali'],$db);
						$pdf->Cell(70,3,"LOCAL: ".$nomlocali,0,0,'L');
						$pdf->Cell(25,3,"C.P.: ".$rowTitularesBordo['indpostal']."".$rowTitularesBordo['numpostal']."".$rowTitularesBordo['alfapostal'],0,1,'R');
						$nomdelega = buscarDelegacion($rowTitularesBordo['codidelega'],$db,0);
						$pdf->Cell(45,3,"SINDI: ".substr($nomdelega,0,19),0,0,'L');
						$nomempresa = buscarEmpresa($rowTitularesBordo['cuitempresa'],$db);
						$pdf->Cell(50,3,"EMP: ".substr($nomempresa,0,24),0,1,'L');
						$pdf->Ln(1);
						$pdf->SetFont('Courier','B',8);
						$pdf->Cell(95,3,"GRUPO FAMILIAR",0,1,'C');
						$pdf->Ln(1);
						$pdf->SetFont('Courier','',7);
						$pdf->Cell(17,3,"PARENTESCO",1,0,'C',true);
						$pdf->Cell(41,3,"APELLIDO Y NOMBRE",1,0,'C',true);
						$pdf->Cell(19,3,"DOCUMENTO",1,0,'C',true);
						$pdf->Cell(18,3,"NACIMIENTO",1,1,'C',true);
						$contFamiliares = 1;
						$totCarnetBordo++;
					}

					if($rowFamiliaresBordo['tipoparentesco'] <= 2) {
						$parentesco = "CONYUGE";
					}
					if($rowFamiliaresBordo['tipoparentesco'] >= 3 && $rowFamiliaresBordo['tipoparentesco'] <= 6) {
						$parentesco = "HIJO";
					}
					if($rowFamiliaresBordo['tipoparentesco'] >= 7 && $rowFamiliaresBordo['tipoparentesco'] <= 8) {
						$parentesco = "A CARGO";
					}
					if($rowFamiliaresBordo['tipoparentesco'] == 9) {
						$parentesco = "HIJO";
					}
					$pdf->Cell(17,3,$parentesco,0,0,'C');
					$pdf->Cell(41,3,substr($rowFamiliaresBordo['apellidoynombre'],0,26),0,0,'C');
					$pdf->Cell(19,3,$rowFamiliaresBordo['tipodocumento']." ".$rowFamiliaresBordo['nrodocumento'],0,0,'C');
					$pdf->Cell(18,3,invertirFecha($rowFamiliaresBordo['fechanacimiento']),0,1,'C');

					$contFamiliares++;
				}
				$pdf->Ln(41-($contFamiliares*3));
			} else {
				$pdf->Ln(46);
			}
			$contTitulares++;
			$totCarnetBordo++;
			$canTitularesBordo++;
		}
		$nombrearchivoB = $carpetaLote."B".$lote.$usuariolote.".pdf";
		$pdf->Output($nombrearchivoB,'F');
	}

	$canTitularesRojo = 0;
	$totCarnetRojo = 0;
	$sqlTitularesRojo="SELECT nroafiliado, apellidoynombre, fechaobrasocial, cuil, tipodocumento, nrodocumento, domicilio, codlocali, indpostal, numpostal, alfapostal, codidelega, cuitempresa FROM titulares WHERE nroafiliado IN($titulares) and tipoafiliado = 'O'";
	//echo $sqlTitularesRojo;  echo "<br>";
	$resTitularesRojo=mysql_query($sqlTitularesRojo,$db);
	if(mysql_num_rows($resTitularesRojo)!=0) {
		$contTitulares = 1;
		$pdf = new FPDF('P','mm',array(215,266));
		$pdf->SetMargins(10, 16);
		$pdf->AddPage();
		while($rowTitularesRojo=mysql_fetch_assoc($resTitularesRojo)) {
			if($contTitulares == 5) {
				$pdf->AddPage();
				$contTitulares = 1;
			}
			$pdf->SetFont('Courier','B',8);
			$pdf->Cell(95,3,"",0,1,'R');
			$pdf->Cell(73,3,"AFIL.: ".$rowTitularesRojo['apellidoynombre'],0,0,'L');
			$pdf->Cell(22,3,"NRO : ".$rowTitularesRojo['nroafiliado'],0,1,'R');
			$pdf->SetFont('Courier','',8);
			$pdf->Cell(35,3,"F.ING: ".invertirFecha($rowTitularesRojo['fechaobrasocial']),0,0,'L');
			$pdf->Cell(35,3,"CUIL: ".$rowTitularesRojo['cuil'],0,0,'C');
			$pdf->Cell(25,3,$rowTitularesRojo['tipodocumento']." ".$rowTitularesRojo['nrodocumento'],0,1,'R');
			$pdf->Cell(95,3,"DOMIC: ".substr($rowTitularesRojo['domicilio'],0,45),0,1,'L');
			$nomlocali = buscarLocalidad($rowTitularesRojo['codlocali'],$db);
			$pdf->Cell(70,3,"LOCAL: ".$nomlocali,0,0,'L');
			$pdf->Cell(25,3,"C.P.: ".$rowTitularesRojo['indpostal']."".$rowTitularesRojo['numpostal']."".$rowTitularesRojo['alfapostal'],0,1,'R');
			$nomdelega = buscarDelegacion($rowTitularesRojo['codidelega'],$db,0);
			$pdf->Cell(45,3,"SINDI: ".substr($nomdelega,0,19),0,0,'L');
			$nomempresa = buscarEmpresa($rowTitularesRojo['cuitempresa'],$db);
			$pdf->Cell(50,3,"EMP: ".substr($nomempresa,0,24),0,1,'L');

			$listadoTitulares[] = array('nroafiliado' => $rowTitularesRojo['nroafiliado'], 'apellidoynombre' => $rowTitularesRojo['apellidoynombre'], 'afiliadosindical' => "N", 'cuitempresa' => $rowTitularesRojo['cuitempresa'], 'nombreempresa' => $nomempresa);

			$nroafiliado = $rowTitularesRojo['nroafiliado'];
			$sqlFamiliaresRojo="SELECT tipoparentesco, apellidoynombre, tipodocumento, nrodocumento, fechanacimiento FROM familiares WHERE nroafiliado = '$nroafiliado' AND emitecarnet = 1";
			//echo $sqlFamiliaresRojo;  echo "<br>";
			$resFamiliaresRojo=mysql_query($sqlFamiliaresRojo,$db);
			if(mysql_num_rows($resFamiliaresRojo)!=0) {
				$contFamiliares = 1;
				$pdf->Ln(1);
				$pdf->SetFillColor(222,222,222);
				$pdf->SetFont('Courier','B',8);
				$pdf->Cell(95,3,"GRUPO FAMILIAR",0,1,'C');
				$pdf->Ln(1);
				$pdf->SetFont('Courier','',7);
				$pdf->Cell(17,3,"PARENTESCO",1,0,'C',true);
				$pdf->Cell(41,3,"APELLIDO Y NOMBRE",1,0,'C',true);
				$pdf->Cell(19,3,"DOCUMENTO",1,0,'C',true);
				$pdf->Cell(18,3,"NACIMIENTO",1,1,'C',true);
				while($rowFamiliaresRojo=mysql_fetch_assoc($resFamiliaresRojo)) {
					if($contFamiliares == 8) {
						if($contTitulares == 4) {
							$pdf->AddPage();
							$contTitulares = 1;
						} else {
							$contTitulares++;
						}
						$pdf->Ln(16);
						$pdf->SetFont('Courier','B',8);
						$pdf->Cell(95,3,"CARNET ADICIONAL",0,1,'R');
						$pdf->Cell(73,3,"AFIL.: ".$rowTitularesRojo['apellidoynombre'],0,0,'L');
						$pdf->Cell(22,3,"NRO : ".$rowTitularesRojo['nroafiliado'],0,1,'R');
						$pdf->SetFont('Courier','',8);
						$pdf->Cell(35,3,"F.ING: ".invertirFecha($rowTitularesRojo['fechaobrasocial']),0,0,'L');
						$pdf->Cell(35,3,"CUIL: ".$rowTitularesRojo['cuil'],0,0,'C');
						$pdf->Cell(25,3,$rowTitularesBordo['tipodocumento']." ".$rowTitularesRojo['nrodocumento'],0,1,'R');
						$pdf->Cell(95,3,"DOMIC: ".substr($rowTitularesRojo['domicilio'],0,45),0,1,'L');
						$nomlocali = buscarLocalidad($rowTitularesRojo['codlocali'],$db);
						$pdf->Cell(70,3,"LOCAL: ".$nomlocali,0,0,'L');
						$pdf->Cell(25,3,"C.P.: ".$rowTitularesRojo['indpostal']."".$rowTitularesRojo['numpostal']."".$rowTitularesRojo['alfapostal'],0,1,'R');
						$nomdelega = buscarDelegacion($rowTitularesRojo['codidelega'],$db,0);
						$pdf->Cell(45,3,"SINDI: ".substr($nomdelega,0,19),0,0,'L');
						$nomempresa = buscarEmpresa($rowTitularesRojo['cuitempresa'],$db);
						$pdf->Cell(50,3,"EMP: ".substr($nomempresa,0,24),0,1,'L');
						$pdf->Ln(1);
						$pdf->SetFont('Courier','B',8);
						$pdf->Cell(95,3,"GRUPO FAMILIAR",0,1,'C');
						$pdf->Ln(1);
						$pdf->SetFont('Courier','',7);
						$pdf->Cell(17,3,"PARENTESCO",1,0,'C',true);
						$pdf->Cell(41,3,"APELLIDO Y NOMBRE",1,0,'C',true);
						$pdf->Cell(19,3,"DOCUMENTO",1,0,'C',true);
						$pdf->Cell(18,3,"NACIMIENTO",1,1,'C',true);
						$contFamiliares = 1;
						$totCarnetRojo++;
					}

					if($rowFamiliaresRojo['tipoparentesco'] <= 2) {
						$parentesco = "CONYUGE";
					}
					if($rowFamiliaresRojo['tipoparentesco'] >= 3 && $rowFamiliaresRojo['tipoparentesco'] <= 6) {
						$parentesco = "HIJO";
					}
					if($rowFamiliaresRojo['tipoparentesco'] >= 7 && $rowFamiliaresRojo['tipoparentesco'] <= 8) {
						$parentesco = "A CARGO";
					}
					if($rowFamiliaresRojo['tipoparentesco'] == 9) {
						$parentesco = "HIJO";
					}
					$pdf->Cell(17,3,$parentesco,0,0,'C');
					$pdf->Cell(41,3,substr($rowFamiliaresRojo['apellidoynombre'],0,26),0,0,'C');
					$pdf->Cell(19,3,$rowFamiliaresRojo['tipodocumento']." ".$rowFamiliaresRojo['nrodocumento'],0,0,'C');
					$pdf->Cell(18,3,invertirFecha($rowFamiliaresRojo['fechanacimiento']),0,1,'C');

					$contFamiliares++;
				}
				$pdf->Ln(41-($contFamiliares*3));
			} else {
				$pdf->Ln(46);
			}
			$contTitulares++;
			$totCarnetRojo++;
			$canTitularesRojo++;
		}
		$nombrearchivoR = $carpetaLote."R".$lote.$usuariolote.".pdf";
		$pdf->Output($nombrearchivoR,'F');
	}

	$canTitularesVerde = 0;
	$totCarnetVerde = 0;
	$sqlTitularesVerde="SELECT nroafiliado, apellidoynombre, fechaobrasocial, cuil, tipodocumento, nrodocumento, domicilio, codlocali, indpostal, numpostal, alfapostal, codidelega, cuitempresa FROM titulares WHERE nroafiliado IN($titulares) and tipoafiliado IN('R','S')";
	//echo $sqlTitularesVerde;  echo "<br>";
	$resTitularesVerde=mysql_query($sqlTitularesVerde,$db);
	if(mysql_num_rows($resTitularesVerde)!=0) {
		$contTitulares = 1;
		$pdf = new FPDF('P','mm',array(215,266));
		$pdf->SetMargins(10, 16);
		$pdf->AddPage();
		while($rowTitularesVerde=mysql_fetch_assoc($resTitularesVerde)) {
			if($contTitulares == 5) {
				$pdf->AddPage();
				$contTitulares = 1;
			}
			$pdf->SetFont('Courier','B',8);
			$pdf->Cell(95,3,"",0,1,'R');
			$pdf->Cell(73,3,"AFIL.: ".$rowTitularesVerde['apellidoynombre'],0,0,'L');
			$pdf->Cell(22,3,"NRO : ".$rowTitularesVerde['nroafiliado'],0,1,'R');
			$pdf->SetFont('Courier','',8);
			$pdf->Cell(35,3,"F.ING: ".invertirFecha($rowTitularesVerde['fechaobrasocial']),0,0,'L');
			$pdf->Cell(35,3,"CUIL: ".$rowTitularesVerde['cuil'],0,0,'C');
			$pdf->Cell(25,3,$rowTitularesVerde['tipodocumento']." ".$rowTitularesVerde['nrodocumento'],0,1,'R');
			$pdf->Cell(95,3,"DOMIC: ".substr($rowTitularesVerde['domicilio'],0,45),0,1,'L');
			$nomlocali = buscarLocalidad($rowTitularesVerde['codlocali'],$db);
			$pdf->Cell(70,3,"LOCAL: ".$nomlocali,0,0,'L');
			$pdf->Cell(25,3,"C.P.: ".$rowTitularesVerde['indpostal']."".$rowTitularesVerde['numpostal']."".$rowTitularesVerde['alfapostal'],0,1,'R');
			$nomdelega = buscarDelegacion($rowTitularesVerde['codidelega'],$db,0);
			$pdf->Cell(45,3,"SINDI: ".substr($nomdelega,0,19),0,0,'L');
			$nomempresa = buscarEmpresa($rowTitularesVerde['cuitempresa'],$db);
			$pdf->Cell(50,3,"EMP: ".substr($nomempresa,0,24),0,1,'L');

			$nroafiliado = $rowTitularesVerde['nroafiliado'];
			$sqlFamiliaresVerde="SELECT tipoparentesco, apellidoynombre, tipodocumento, nrodocumento, fechanacimiento FROM familiares WHERE nroafiliado = '$nroafiliado' AND emitecarnet = 1";
			//echo $sqlFamiliaresVerde;  echo "<br>";
			$resFamiliaresVerde=mysql_query($sqlFamiliaresVerde,$db);
			if(mysql_num_rows($resFamiliaresVerde)!=0) {
				$contFamiliares = 1;
				$pdf->Ln(1);
				$pdf->SetFillColor(222,222,222);
				$pdf->SetFont('Courier','B',8);
				$pdf->Cell(95,3,"GRUPO FAMILIAR",0,1,'C');
				$pdf->Ln(1);
				$pdf->SetFont('Courier','',7);
				$pdf->Cell(17,3,"PARENTESCO",1,0,'C',true);
				$pdf->Cell(41,3,"APELLIDO Y NOMBRE",1,0,'C',true);
				$pdf->Cell(19,3,"DOCUMENTO",1,0,'C',true);
				$pdf->Cell(18,3,"NACIMIENTO",1,1,'C',true);
				while($rowFamiliaresVerde=mysql_fetch_assoc($resFamiliaresVerde)) {
					if($contFamiliares == 8) {
						if($contTitulares == 4) {
							$pdf->AddPage();
							$contTitulares = 1;
						} else {
							$contTitulares++;
						}
						$pdf->Ln(16);
						$pdf->SetFont('Courier','B',8);
						$pdf->Cell(95,3,"CARNET ADICIONAL",0,1,'R');
						$pdf->Cell(73,3,"AFIL.: ".$rowTitularesVerde['apellidoynombre'],0,0,'L');
						$pdf->Cell(22,3,"NRO : ".$rowTitularesVerde['nroafiliado'],0,1,'R');
						$pdf->SetFont('Courier','',8);
						$pdf->Cell(35,3,"F.ING: ".invertirFecha($rowTitularesVerde['fechaobrasocial']),0,0,'L');
						$pdf->Cell(35,3,"CUIL: ".$rowTitularesVerde['cuil'],0,0,'C');
						$pdf->Cell(25,3,$rowTitularesVerde['tipodocumento']." ".$rowTitularesVerde['nrodocumento'],0,1,'R');
						$pdf->Cell(95,3,"DOMIC: ".substr($rowTitularesVerde['domicilio'],0,45),0,1,'L');
						$nomlocali = buscarLocalidad($rowTitularesVerde['codlocali'],$db);
						$pdf->Cell(70,3,"LOCAL: ".$nomlocali,0,0,'L');
						$pdf->Cell(25,3,"C.P.: ".$rowTitularesVerde['indpostal']."".$rowTitularesVerde['numpostal']."".$rowTitularesVerde['alfapostal'],0,1,'R');
						$nomdelega = buscarDelegacion($rowTitularesVerde['codidelega'],$db,0);
						$pdf->Cell(45,3,"SINDI: ".substr($nomdelega,0,19),0,0,'L');
						$nomempresa = buscarEmpresa($rowTitularesVerde['cuitempresa'],$db);
						$pdf->Cell(50,3,"EMP: ".substr($nomempresa,0,24),0,1,'L');
						$pdf->Ln(1);
						$pdf->SetFont('Courier','B',8);
						$pdf->Cell(95,3,"GRUPO FAMILIAR",0,1,'C');
						$pdf->Ln(1);
						$pdf->SetFont('Courier','',7);
						$pdf->Cell(17,3,"PARENTESCO",1,0,'C',true);
						$pdf->Cell(41,3,"APELLIDO Y NOMBRE",1,0,'C',true);
						$pdf->Cell(19,3,"DOCUMENTO",1,0,'C',true);
						$pdf->Cell(18,3,"NACIMIENTO",1,1,'C',true);
						$contFamiliares = 1;
						$totCarnetVerde++;
					}

					if($rowFamiliaresVerde['tipoparentesco'] <= 2) {
						$parentesco = "CONYUGE";
					}
					if($rowFamiliaresVerde['tipoparentesco'] >= 3 && $rowFamiliaresVerde['tipoparentesco'] <= 6) {
						$parentesco = "HIJO";
					}
					if($rowFamiliaresVerde['tipoparentesco'] >= 7 && $rowFamiliaresVerde['tipoparentesco'] <= 8) {
						$parentesco = "A CARGO";
					}
					if($rowFamiliaresVerde['tipoparentesco'] == 9) {
						$parentesco = "HIJO";
					}
					$pdf->Cell(17,3,$parentesco,0,0,'C');
					$pdf->Cell(41,3,substr($rowFamiliaresVerde['apellidoynombre'],0,26),0,0,'C');
					$pdf->Cell(19,3,$rowFamiliaresVerde['tipodocumento']." ".$rowFamiliaresVerde['nrodocumento'],0,0,'C');
					$pdf->Cell(18,3,invertirFecha($rowFamiliaresVerde['fechanacimiento']),0,1,'C');

					$contFamiliares++;
				}
				$pdf->Ln(41-($contFamiliares*3));
			} else {
				$pdf->Ln(46);
			}
			$contTitulares++;
			$totCarnetVerde++;
			$canTitularesVerde++;
		}
		$nombrearchivoV = $carpetaLote."V".$lote.$usuariolote.".pdf";
		$pdf->Output($nombrearchivoV,'F');
	}

	$canTitulares = $canTitularesAzul + $canTitularesBordo + $canTitularesRojo;
	$totCarnetOspim = $totCarnetAzul + $totCarnetBordo;
	(float)$totHojaAzul = $totCarnetAzul / 4;
	(float)$totHojaBordo = $totCarnetBordo / 4;
	(float)$totHojaRojo = $totCarnetRojo / 4;
	(float)$totHojaVerde = $totCarnetVerde / 4;

	foreach($listadoTitulares as $clave => $fila) {
	    $cuit[$clave] = $fila['cuitempresa'];
    	$afil[$clave] = $fila['nroafiliado'];
	}

	array_multisort($cuit, SORT_ASC, $afil, SORT_ASC, $listadoTitulares);
	
	if($canTitulares > 0) {
		$pdfn = new FPDF('P','mm','Letter');
		$pdfn->AddPage();
		$pdfn->Image('../img/Logo Membrete OSPIM.jpg',21,13,28,22);
		$pdfn->Ln(6);
		$pdfn->SetFont('Times','BI',10);
		$pdfn->Cell(39);
		$pdfn->Cell(157,4,'Rojas 254',0,1,'R');
		$pdfn->SetFont('Times','BI',28);
		$pdfn->Cell(39);
		$pdfn->Cell(112,8,'OSPIM',0,0,'L');
		$pdfn->SetFont('Times','I',10);
		$pdfn->Multicell(45,4,'(C1405ABB) Capital Federal Tel.: 4431-4791/4089',0,'R');
		$pdfn->SetFont('Times','BI',9);
		$pdfn->Cell(39);
		$pdfn->Cell(40,3,'Obra   Social   del   Personal',0,0,'L');
		$pdfn->SetFont('Times','I',10);
		$pdfn->Cell(117,3,'Fax: 4431-2567',0,1,'R');
		$pdfn->SetFont('Times','BI',9);
		$pdfn->Cell(39);
		$pdfn->Cell(40,3,'de   la   Industria   Maderera',0,0,'L');
		$pdfn->SetFont('Times','I',10);
		$pdfn->Cell(117,3,'afiliaciones@ospim.com.ar',0,1,'R');
		$pdfn->SetFont('Times','I',8);
		$pdfn->Cell(10);
		$pdfn->Cell(69,5,'Solidaridad  y  Organización  al  Servicio   de   la  Familia',0,1,'L');
		$pdfn->Ln(22);
		$pdfn->SetFont('Arial','',11);
		$pdfn->Cell(196,5,"Buenos Aires, ".date("j")." de ".$nombremes." de ".date("Y").".-",0,1,'R');
		$pdfn->Ln(5);
		$pdfn->Cell(196,4,'Compañero',0,1,'L');
		$pdfn->SetFont('Arial','B',11);
		$autoridad = buscarDelegacion($_POST['delegacion'],$db,1);
		$pdfn->Cell(196,4,$autoridad,0,1,'L');
		$pdfn->SetFont('Arial','',11);
		$cargo = buscarDelegacion($_POST['delegacion'],$db,2);
		$pdfn->Cell(196,4,$cargo,0,1,'L');
		$pdfn->Cell(196,4,'SINDICATO DE LA MADERA',0,1,'L');
		$nomdelega = buscarDelegacion($_POST['delegacion'],$db,0);
		$pdfn->Cell(196,4,'DE '.$nomdelega,0,1,'L');
		$pdfn->Ln(12);
		$pdfn->Cell(60,4,'De nuestra mayor consideración:',0,1,'L');
		$pdfn->Ln(4);
		$pdfn->Cell(60,4,'',0,0,'L');
		$pdfn->Cell(136,4,'Mediante  la presente le hacemos llegar a Ud. la  cantidad de '.$totCarnetOspim.' carnets de afi-',0,1,'L');
		if($totCarnetRojo > 0) {
			$pdfn->Multicell(196,4,'liados de O.S.P.I.M., '.$totCarnetRojo.' carnets de afiliados por OPCION y '.$totCarnetVerde.' carnets de afiliados de U.S.I.M.R.A. pertenecientes a beneficiarios del ámbito jurisdiccional de vuestra delegación.',0,'J');
		} else {
			$pdfn->Multicell(196,4,'liados de O.S.P.I.M. y '.$totCarnetVerde.' carnets de afiliados de U.S.I.M.R.A. pertenecientes a beneficiarios del ámbito jurisdiccional de vuestra delegación.',0,'J');
		}
		$pdfn->Cell(60,4,'',0,0,'L');
		$pdfn->Cell(136,4,'Se adjunta nómina de beneficiarios TITULARES que se corresponden con los',0,1,'L');
		$pdfn->Cell(196,4,'carnets mencionados.',0,1,'L');
		$pdfn->Ln(4);
		$pdfn->Cell(60,4,'',0,0,'L');
		$pdfn->Cell(136,4,'Sin otro particular, lo saludamos muy atentamente.',0,1,'L');
		$pdfn->Ln(20);
		$pdfn->SetFont('Arial','B',11);
		$pdfn->Cell(151);
		$pdfn->Cell(45,4,'Depto. de Afiliaciones',0,1,'C');
		$pdfn->Cell(151);
		$pdfn->Cell(45,4,'O.S.P.I.M.',0,1,'C');
		$nombrearchivoN = $carpetaLote."N".$lote.$usuariolote.".pdf";
		$pdfn->Output($nombrearchivoN,'F');

		//Las cabeceras y pies de páginas ya existen en la clase FPDF original y se llaman automáticamente, pero no hacen nada. Por eso heredo la clase para modificarlas y sobreescribirlas
		class PDF extends FPDF
		{
			// Cabecera de página
			function Header()
			{
				$this->SetFont('Courier','B',10);
				$this->Cell(22,5,'O.S.P.I.M.',0,0,'L');
				$this->Cell(152,5,'TITULARES DE LOS CARNETS EMITIDOS',0,0,'C');
				$fechacabecera = date("d/m/Y");
				$this->Cell(22,5,$fechacabecera,0,1,'R');
				$this->Ln(5);
			}
		
			// Pie de página
			function Footer()
			{
				// Posición: a 1,5 cm del final
				$this->SetY(-15);
				$this->SetFont('Arial','I',8);
				// Número de página
				$this->Cell(0,5,'Página '.$this->PageNo().' de {nb}',0,0,'C');
			}
		}
	
		$delegacabecera = buscarDelegacion($_POST['delegacion'],$db,0);
	
		$pdfl = new PDF('P','mm','Letter');
		$pdfl->AliasNbPages();
		$pdfl->AddPage();
		$pdfl->SetFillColor(222,222,222);
		$pdfl->Cell(196,3,"DELEGACION ".$delegacabecera,0,1,'C');
		$pdfl->Ln(2);
		$pdfl->Cell(105,3,"Beneficiario",1,0,'C',true);
		$pdfl->Cell(91,3,"Empresa",1,1,'C',true);
		$pdfl->Cell(12,3,"Nro.",1,0,'C',true);
		$pdfl->Cell(75,3,"Apellido y Nombre",1,0,'C',true);
		$pdfl->Cell(18,3,"Sindical",1,0,'C',true);
		$pdfl->Cell(20,3,"C.U.I.T.",1,0,'C',true);
		$pdfl->Cell(71,3,"Razon Social",1,1,'C',true);
		$pdfl->SetFont('Courier','',7);
		$pdfl->Ln(1);
		foreach($listadoTitulares as $listado) {
			$pdfl->Cell(12,3,$listado['nroafiliado'],0,0,'R');
			$pdfl->Cell(75,3,$listado['apellidoynombre'],0,0,'L');
			$pdfl->Cell(18,3,$listado['afiliadosindical'],0,0,'C');
			$pdfl->Cell(20,3,$listado['cuitempresa'],0,0,'C');
			$pdfl->Cell(71,3,$listado['nombreempresa'],0,1,'L');
		}
		$pdfl->SetFont('Courier','B',10);
		$pdfl->Ln(1);
		$pdfl->Cell(196,4,"Total de Titulares con Carnets : " .$canTitulares,1,1,'L', true);
		$nombrearchivoL = $carpetaLote."L".$lote.$usuariolote.".pdf";
		$pdfl->Output($nombrearchivoL,'F');

		$actualizaLote = FALSE;

		try {
			$hostname = $_SESSION['host'];
			$dbname = $_SESSION['dbname'];
			$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$dbh->beginTransaction();
		
			$sqlAgregaImpresion = "INSERT INTO impresioncarnets (lote, usuarioemision, fechaemision, codidelega, totaltitulares, totalcarnetsazul, totalhojasazul, totalcarnetsbordo, totalhojasbordo, totalcarnetsrojo, totalhojasrojo, totalcarnetsverde, totalhojasverde) VALUES (:lote, :usuarioemision, :fechaemision, :codidelega, :totaltitulares, :totalcarnetsazul, :totalhojasazul, :totalcarnetsbordo, :totalhojasbordo, :totalcarnetsrojo, :totalhojasrojo, :totalcarnetsverde, :totalhojasverde)";
			$resAgregaImpresion = $dbh->prepare($sqlAgregaImpresion);
			if($resAgregaImpresion->execute(array(':lote' => $lote, ':usuarioemision' => $usuariolote, ':fechaemision' => $fechalote, ':codidelega' => $_POST['delegacion'], ':totaltitulares' => $canTitulares, ':totalcarnetsazul' => $totCarnetAzul, ':totalhojasazul' => ceil($totHojaAzul), ':totalcarnetsbordo' => $totCarnetBordo, ':totalhojasbordo' => ceil($totHojaBordo), ':totalcarnetsrojo' => $totCarnetRojo, ':totalhojasrojo' => ceil($totHojaRojo), ':totalcarnetsverde' => $totCarnetVerde, ':totalhojasverde' => ceil($totHojaVerde)))) {
			}
		
			$dbh->commit();
			$actualizaLote = TRUE;
		}
		catch (PDOException $e) {
			echo $e->getMessage();
			$dbh->rollback();
		}
	}

	if($actualizaLote) {
		$emite = 0;
		$emitido = 1;
		$cantidad = 1;
		$fechacarnet = date("Ymd");
		$tipocarnet = "P";
		try {
			$hostname = $_SESSION['host'];
			$dbname = $_SESSION['dbname'];
			$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$dbh->beginTransaction();

			$sqlActualizaTitulares="UPDATE titulares SET emitecarnet = :emite, cantidadcarnet = (cantidadcarnet + :cantidad), fechacarnet = :fechacarnet, lote = :lote, tipocarnet = :tipocarnet WHERE nroafiliado IN($titulares)";
			$resActualizaTitulares=$dbh->prepare($sqlActualizaTitulares);
			if($resActualizaTitulares->execute(array(':emite' => $emite, ':cantidad' => $cantidad, ':fechacarnet' => $fechacarnet, ':lote' => $lote, ':tipocarnet' => $tipocarnet))) {
			}
			$sqlActualizaFamiliares="UPDATE familiares SET emitecarnet = :emite, cantidadcarnet = (cantidadcarnet + :cantidad), fechacarnet = :fechacarnet, lote = :lote, tipocarnet = :tipocarnet WHERE nroafiliado IN($titulares) AND emitecarnet = :emitido";
			$resActualizaFamiliares=$dbh->prepare($sqlActualizaFamiliares);
			if($resActualizaFamiliares->execute(array(':emite' => $emite, ':cantidad' => $cantidad, ':fechacarnet' => $fechacarnet, ':lote' => $lote, ':tipocarnet' => $tipocarnet, ':emitido' => $emitido))) {
			}
			$dbh->commit();
			$pagina = "mensajeLote.php?nroLote=$lote";
			Header("Location: $pagina"); 
		}
		catch (PDOException $e) {
			echo $e->getMessage();
			$dbh->rollback();
		}
	}
}

function buscarLocalidad($codlocali, $base)
{
	$sqlLeeLocalidad="SELECT nomlocali FROM localidades WHERE codlocali = '$codlocali'";
	$resLeeLocalidad=mysql_query($sqlLeeLocalidad,$base);
	$rowLeeLocalidad=mysql_fetch_assoc($resLeeLocalidad);
	
	return $rowLeeLocalidad['nomlocali'];
}

function buscarDelegacion($codidelega, $base, $origen)
{
	$sqlLeeDelegacion="SELECT nombre, autoridad, cargo FROM delegaciones WHERE codidelega = '$codidelega'";
	$resLeeDelegacion=mysql_query($sqlLeeDelegacion,$base);
	$rowLeeDelegacion=mysql_fetch_assoc($resLeeDelegacion);
	
	if($origen == 0) {
		return $rowLeeDelegacion['nombre'];
	}

	if($origen == 1) {
		return $rowLeeDelegacion['autoridad'];
	}

	if($origen == 2) {
		return $rowLeeDelegacion['cargo'];
	}
}

function buscarEmpresa($cuitempresa, $base)
{
	$sqlLeeEmpresa="SELECT nombre FROM empresas WHERE cuit = '$cuitempresa'";
	$resLeeEmpresa=mysql_query($sqlLeeEmpresa,$base);
	$rowLeeEmpresa=mysql_fetch_assoc($resLeeEmpresa);
	
	return $rowLeeEmpresa['nombre'];
}
?>