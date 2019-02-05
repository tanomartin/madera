<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."claves.php");
include($libPath."fechas.php");
include($libPath."bandejaSalida.php");
require_once($libPath."fpdf.php");
require_once($libPath."FPDI-1.6.1/fpdi.php"); 

//var_dump($_POST);

$nrosoli = $_POST['solicitud'];
$presupue = 0;

if(isset($_POST['elige1'])) {
	if(strcmp($_POST['elige1'],"on")==0) {
		if(isset($_POST['elegido1'])) {
			$presupue = $_POST['elegido1'];
		}
	}
}

if(isset($_POST['elige2'])) {
	if(strcmp($_POST['elige2'],"on")==0) {
		if(isset($_POST['elegido2'])) {
			$presupue = $_POST['elegido2'];
		}
	}
}

if(isset($_POST['elige3'])) {
	if(strcmp($_POST['elige3'],"on")==0) {
		if(isset($_POST['elegido3'])) {
			$presupue = $_POST['elegido3'];
		}
	}
}

if(isset($_POST['elige4'])) {
	if(strcmp($_POST['elige4'],"on")==0) {
		if(isset($_POST['elegido4'])) {
			$presupue = $_POST['elegido4'];
		}
	}
}

if(isset($_POST['elige5'])) {
	if(strcmp($_POST['elige5'],"on")==0) {
		if(isset($_POST['elegido5'])) {
			$presupue = $_POST['elegido5'];
		}
	}
}

if(isset($_POST['autori'])) {
	$staauto = $_POST['autori'];
}

$fecauto = date("Y-m-d H:i:s");
$usuauto = $_SESSION['usuario'];

if($staauto==2) {
	$presupue = 0;
	$estauto = "Rechazada";
	$recauto = $_POST['motivoRechazo'];
	$historia = $_POST['historiaClinica'];
	$apeauto = "";
	$apefech = "";
	$presauto = "";
	$presmail = "";
	$presfech = "";
	$patoauto = "";
	$montcose = "0.00";
	$montauto = "0.00";
	$montoMostrar = $montauto;
} else {
	$estauto = "Aprobada";
	$recauto = $_POST['motivoRechazo'];
	$historia = $_POST['historiaClinica'];
	
	if(isset($_POST['ape'])) {
		$apeauto = $_POST['ape'];
	}

	$apefech = "";

	if($apeauto==1) {
		$apefech = date("Y-m-d H:i:s");
	}

	if(isset($_POST['presta'])) {
		$presauto = $_POST['presta'];
	}

	$presmail = "";
	$presfech = "";	

	if($presauto==1) {
		$presmail = $_POST['emailPresta'];
		$presfech =  date("Y-m-d H:i:s");
	}

	if(isset($_POST['selectPatologia'])) {
		$patoauto = $_POST['selectPatologia'];
	}

	if(isset($_POST['montoCoseguro'])) {
		$montcose = $_POST['montoCoseguro'];
	}
	
	if($montcose > 0.00) {
		$fuentePath = $_SERVER['DOCUMENT_ROOT']."/madera/ospim/auditoria/autorizaciones/";
		$imagencose="../tempautorizaciones/cose".$nrosoli.".png";
		$im = imagecreate(560, 130);
		$fondo = imagecolorallocatealpha($im, 255, 255, 255, 127);
		$color_texto = imagecolorallocate($im, 0, 0, 0);
		$fuente = $fuentePath.'arialbd.ttf';
		imagettftext($im, 20, 0, 50, 60, $color_texto, $fuente, 'Monto Coseguro: '.$montcose);
		imagepng($im, $imagencose);
		imagedestroy($im);
	}
	
	if(isset($_POST['montoAutoriza'])) {
		$montauto = $_POST['montoAutoriza'];
	}
	
	if(isset($_POST['tipomonto'])) {
		$tipomonto = $_POST['tipomonto'];
	}
	
	$montoMostrar = $montauto;
	if ($tipomonto == 2) {
		$montoMostrar = $montauto." %";
	}

	if($montauto > 0.00) {
		$fuentePath = $_SERVER['DOCUMENT_ROOT']."/madera/ospim/auditoria/autorizaciones/";
		// Crear una imagen fondo blanco transparente y a�adir texto negro
		$imagenmonto="../tempautorizaciones/monto".$nrosoli.".png"; 
		$im = imagecreate(560, 130);
		$fondo = imagecolorallocatealpha($im, 255, 255, 255, 127);
		$color_texto = imagecolorallocate($im, 0, 0, 0);
		$fuente = $fuentePath.'arialbd.ttf';
		imagettftext($im, 20, 0, 50, 60, $color_texto, $fuente, 'Monto Autorizado: '.$montoMostrar);
		// Guardar la imagen como archivo .png
		imagepng($im, $imagenmonto);
		// Liberar memoria
		imagedestroy($im);
	}
}

switch ($presupue) {
	case 0:
		$presapr1 = 0;
		$presapr2 = 0;
		$presapr3 = 0;
		$presapr4 = 0;
		$presapr5 = 0;
		break;
	case 1:
		$presapr1 = 1;
		$presapr2 = 0;
		$presapr3 = 0;
		$presapr4 = 0;
		$presapr5 = 0;
		break;
	case 2:
		$presapr1 = 0;
		$presapr2 = 1;
		$presapr3 = 0;
		$presapr4 = 0;
		$presapr5 = 0;
		break;
	case 3:
		$presapr1 = 0;
		$presapr2 = 0;
		$presapr3 = 1;
		$presapr4 = 0;
		$presapr5 = 0;
		break;
	case 4:
		$presapr1 = 0;
		$presapr2 = 0;
		$presapr3 = 0;
		$presapr4 = 1;
		$presapr5 = 0;
		break;
	case 5:
		$presapr1 = 0;
		$presapr2 = 0;
		$presapr3 = 0;
		$presapr4 = 0;
		$presapr5 = 1;
		break;
}

//echo "Nro Solicitud: "; echo $nrosoli; echo "<br>";
//echo "Presupuesto: "; echo $presupue; echo "<br>";
//echo "Autorizacion: "; echo $staauto; echo "<br>";
//echo "Estado: "; echo $estauto; echo "<br>";
//echo "Rechazo: "; echo $recauto; echo "<br>";
//echo "APE: "; echo $apeauto; echo "<br>";
//echo "Fecha Mail APE: "; echo $apefech; echo "<br>";
//echo "Prestador: "; echo $presauto; echo "<br>";
//echo "Mail Prestador: "; echo $presmail; echo "<br>";
//echo "Fecha Mail Prestador: "; echo $presfech; echo "<br>";
//echo "Patologia: "; echo $patoauto; echo "<br>";
//echo "Monto Autorizado: "; echo $montauto; echo "<br>";
//echo "Aprobado 1: "; echo $presapr1; echo "<br>";
//echo "Aprobado 2: "; echo $presapr2; echo "<br>";
//echo "Aprobado 3: "; echo $presapr3; echo "<br>";
//echo "Aprobado 4: "; echo $presapr4; echo "<br>";
//echo "Aprobado 5: "; echo $presapr5; echo "<br>";

$fechamail=date("d/m/Y");
$horamail=date("H:i");

$sqlLeeSolicitud = "SELECT a.*, d.*, del.nombre as delegacion 
					FROM autorizaciones a, autorizacionesdocoriginales d, delegaciones del
					WHERE a.nrosolicitud = $nrosoli and a.nrosolicitud = d.nrosolicitud and a.codidelega = del.codidelega";
$resultLeeSolicitud = mysql_query($sqlLeeSolicitud,$db);
$rowLeeSolicitud = mysql_fetch_array($resultLeeSolicitud);

$cuilSolicitud = $rowLeeSolicitud['cuil'];
$sqlLeeTitular = "SELECT nroafiliado, tipodocumento, nrodocumento FROM titulares where cuil = $cuilSolicitud";
$resultLeeTitular = mysql_query($sqlLeeTitular,$db); 
if(mysql_num_rows($resultLeeTitular)!=0) {
	$rowLeeAfiliado = mysql_fetch_array($resultLeeTitular);
	$nrobeneficiario = $rowLeeAfiliado['nroafiliado']."/0";
	$docuTyNro = $rowLeeAfiliado['tipodocumento']." ".$rowLeeAfiliado['nrodocumento'];
} else {
	$sqlLeeFamiliar = "SELECT nroafiliado, tipoparentesco, tipodocumento, nrodocumento FROM familiares where cuil = $cuilSolicitud";
	$resultLeeFamiliar = mysql_query($sqlLeeFamiliar,$db);
	if(mysql_num_rows($resultLeeFamiliar)!=0) {
		$rowLeeAfiliado = mysql_fetch_array($resultLeeFamiliar);
		$nrobeneficiario = $rowLeeAfiliado['nroafiliado']."/".$rowLeeAfiliado['tipoparentesco'];
		$docuTyNro = $rowLeeAfiliado['tipodocumento']." ".$rowLeeAfiliado['nrodocumento'];
	} else {
		if($rowLeeSolicitud['nroafiliado'] == 0) {
			$nrobeneficiario = "-/-";
		} else {
			$nrobeneficiario = $rowLeeSolicitud['nroafiliado']."/".$rowLeeSolicitud['codiparentesco'];
		}
		$docuTyNro = " ";
	}
}

if($rowLeeSolicitud['practica']==1)
	$tiposoli="Practica";
else {
	if($rowLeeSolicitud['material']==1)	{
		$sqlLeeMaterial = "SELECT descripcion FROM clasificamaterial where codigo = ".$rowLeeSolicitud['tipomaterial'];
		$resultLeeMaterial = mysql_query($sqlLeeMaterial,$db); 
		$rowLeeMaterial = mysql_fetch_array($resultLeeMaterial);

		$tiposoli="Material - ".$rowLeeMaterial['descripcion'];
	}
	else {
		if($rowLeeSolicitud['medicamento']==1)
			$tiposoli="Medicamento";
	}
}

$docpm=0;
$docrh=0;
$docas=0;
$docpa=0;

//Conexion local y remota.
$maquina = $_SERVER['SERVER_NAME'];
if(strcmp("poseidon",$maquina)==0)
	$hostremoto = $hostOspim;
else
	$hostremoto = "localhost";
	
$dbremota = $baseOspimIntranet;
$hostlocal = $_SESSION['host'];
$dblocal = $_SESSION['dbname'];

//Creacion de transaccion.
try {
	$dbl = new PDO("mysql:host=$hostlocal;dbname=$dblocal",$_SESSION['usuario'],$_SESSION['clave']);
	//echo 'Connected to database local<br/>';
	$dbl->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbl->beginTransaction();

	$dbr = new PDO("mysql:host=$hostremoto;dbname=$dbremota",$usuarioOspim,$claveOspim);
	//echo 'Connected to database remota<br/>';
	$dbr->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbr->beginTransaction();

	set_time_limit(0);
	
	
	if($staauto!=2) {	
		$pdf = new FPDI();
		$pdf->AddPage('P','Letter');
		$pdf->Image('../img/Logo Membrete OSPIM.jpg',21,13,28,22);
		$pdf->SetFont('Times','BI',28);
		$pdf->Ln(10);
		$pdf->Cell(39);
		$pdf->Cell(34,8,'OSPIM',0,1,'L');
		$pdf->SetFont('Times','BI',9);
		$pdf->Cell(39);
		$pdf->Cell(40,3,'Obra   Social   del   Personal',0,1,'L');
		$pdf->Cell(39);
		$pdf->Cell(40,3,'de   la   Industria   Maderera',0,1,'L');
		$pdf->SetFont('Times','I',8);
		$pdf->Cell(10);
		$pdf->Cell(69,5,'Solidaridad  y  Organizaci�n  al  Servicio   de   la  Familia',0,0,'L');
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(74);
		$pdf->Cell(40,5,date("j")." de ".date("F")." de ".date("Y").".-",0,1,'R');
//		$pdf->Cell(40,5,date("j")." de Julio de ".date("Y").".-",0,1,'R');
		$pdf->Ln(20);
		$pdf->SetFont('Arial','B',18);
		$pdf->Cell(10);
		$pdf->Cell(183,8,"Autorizaci�n Nro. ".$nrosoli,1,1,'C');
		$pdf->Ln(5);
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(10);
		$pdf->Cell(113,6,"Beneficiario: ".$rowLeeSolicitud['apellidoynombre'],1,0,'L');
		$pdf->Cell(30,6,"Nro: ".$nrobeneficiario,1,0,'L');
		$pdf->Cell(40,6,"C.U.I.L.: ".$rowLeeSolicitud['cuil'],1,1,'L');
		$pdf->Cell(10);
		$pdf->Cell(113,6,"Tipo: ".$tiposoli,1,0,'L');
		$pdf->Cell(70,6,"Documento: ".$docuTyNro,1,1,'L');
		$pdf->Cell(10);
		$pdf->Cell(57,6,"Monto Autorizado: ".$montoMostrar,1,0,'L');
		$pdf->Cell(56,6,"Monto Coseguro: ".$montcose,1,0,'L');
		$pdf->Cell(70,6,"Delegacion: ".$rowLeeSolicitud['delegacion'],1,1,'L');

		if($rowLeeSolicitud['pedidomedico']!=NULL) {
			$docpm=1;
			$documentacion="Pedido Medico";
			$contenidoarchivo=$rowLeeSolicitud['pedidomedico'];
			$nombrepedido="../tempautorizaciones/pedidomedico".$nrosoli.".pdf"; 
			$fch=fopen($nombrepedido, "w");
			fwrite($fch, $contenidoarchivo); 
			fclose($fch);
		}

		if($rowLeeSolicitud['resumenhc']!=NULL) {
			$docrh=1;
			$documentacion.=" / Resumen Historia Clinica";
			$contenidoarchivo=$rowLeeSolicitud['resumenhc'];
			$nombreresumen="../tempautorizaciones/resumenhc".$nrosoli.".pdf"; 
			$fch=fopen($nombreresumen, "w");
			fwrite($fch, $contenidoarchivo); 
			fclose($fch);
		}

		if($rowLeeSolicitud['avalsolicitud']!=NULL) {
			$docas=1;
			$documentacion.=" / Estudios";
			$contenidoarchivo=$rowLeeSolicitud['avalsolicitud'];
			$nombreaval="../tempautorizaciones/avalsolicitud".$nrosoli.".pdf"; 
			$fch=fopen($nombreaval, "w");
			fwrite($fch, $contenidoarchivo); 
			fclose($fch);
		}

		if($presupue!= 0) {
			$docpa=1;
			$documentacion.=" / Presupuesto Aprobado";
			if($presupue == 1)
				$contenidoarchivo=$rowLeeSolicitud['presupuesto1'];
			if($presupue == 2)
				$contenidoarchivo=$rowLeeSolicitud['presupuesto2'];
			if($presupue == 3)
				$contenidoarchivo=$rowLeeSolicitud['presupuesto3'];
			if($presupue == 4)
				$contenidoarchivo=$rowLeeSolicitud['presupuesto4'];
			if($presupue == 5)
				$contenidoarchivo=$rowLeeSolicitud['presupuesto5'];
			$nombrepresupue="../tempautorizaciones/presupuesto".$nrosoli.".pdf"; 
			$fch=fopen($nombrepresupue, "w");
			fwrite($fch, $contenidoarchivo); 
			fclose($fch);
		}
		$pdf->Cell(10);
		$pdf->Cell(183,6,"Documentacion Complementaria: ".$documentacion,1,1,'L');
		$pdf->Image('../img/Sello Autorizado.png',87,130,50,30);
		$pdf->Image('../img/Sello OSPIM.png',21,190,45,45);
		if(strcmp($usuauto,"gflongo")==0) {
			$pdf->Image('../img/Firma Longo.png',160,190,18,50);
			$pdf->Image('../img/Sello Longo.png',150,220,35,13);
		} else {
			$pdf->Image('../img/Firma Giraudo.png',160,190,18,50);
			$pdf->Image('../img/Sello Giraudo.png',150,220,35,13);
		}
		
		if($docpm==1) {
			$totalpaginas=$pdf->setSourceFile($nombrepedido);
			for($nropagina=1; $nropagina<=$totalpaginas; $nropagina++) { 
				$pdf->AddPage('P','Letter');
				$pdf->SetFont('Arial','B',18);
				$pdf->Cell(10);
				$pdf->Cell(183,8,"Pedido Medico - Hoja ".$nropagina,1,1,'C');
				$tplIdx = $pdf->importPage($nropagina);
				$pdf->useTemplate($tplIdx, 10, 30, 196);
				
				$pusoAprobado = 0;
				if($rowLeeSolicitud['medicamento'] == 1) {
					$pdf->Image('../img/Sello Presupuesto.png',37,130,60,40);
					$pusoAprobado = 1;
					if($montauto > 0.00) {
						$pdf->Image($imagenmonto,39,140,60,40);
					}
				} 
				if($montcose > 0.00) {
					$pdf->Image('../img/Sello Presupuesto.png',117,130,60,40);
					$pdf->Image($imagencose,119,140,60,40);
					$pusoAprobado = 1;
				}
				
				if($pusoAprobado == 0) {
					$pdf->Image('../img/Sello Autorizado.png',87,130,50,30);
				}
				
				$pdf->Image('../img/Sello OSPIM.png',21,190,45,45);
				if(strcmp($usuauto,"gflongo")==0) {
					$pdf->Image('../img/Firma Longo.png',160,190,18,50);
					$pdf->Image('../img/Sello Longo.png',150,220,35,13);
				} else {
					$pdf->Image('../img/Firma Giraudo.png',160,190,18,50);
					$pdf->Image('../img/Sello Giraudo.png',150,220,35,13);
				}
			}
		}
		
		if($docrh==1) {
			$totalpaginas=$pdf->setSourceFile($nombreresumen);
			for($nropagina=1; $nropagina<=$totalpaginas; $nropagina++) { 
				$pdf->AddPage('P','Letter');
				$pdf->SetFont('Arial','B',18);
				$pdf->Cell(10);
				$pdf->Cell(183,8,"Resumen Historia Clinica - Hoja ".$nropagina,1,1,'C');
				$tplIdx = $pdf->importPage($nropagina);
				$pdf->useTemplate($tplIdx, 10, 30, 196);
				$pdf->Image('../img/Sello Autorizado.png',87,130,50,30);
				$pdf->Image('../img/Sello OSPIM.png',21,190,45,45);
				if(strcmp($usuauto,"gflongo")==0) {
					$pdf->Image('../img/Firma Longo.png',160,190,18,50);
					$pdf->Image('../img/Sello Longo.png',150,220,35,13);
				} else {
					$pdf->Image('../img/Firma Giraudo.png',160,190,18,50);
					$pdf->Image('../img/Sello Giraudo.png',150,220,35,13);
				}
			}
		}
		
		if($docas==1) {
			$totalpaginas=$pdf->setSourceFile($nombreaval);
			for($nropagina=1; $nropagina<=$totalpaginas; $nropagina++) { 
				$pdf->AddPage('P','Letter');
				$pdf->SetFont('Arial','B',18);
				$pdf->Cell(10);
				$pdf->Cell(183,8,"Estudios - Hoja ".$nropagina,1,1,'C');
				$tplIdx = $pdf->importPage($nropagina);
				$pdf->useTemplate($tplIdx, 10, 30, 196);
				$pdf->Image('../img/Sello Autorizado.png',87,130,50,30);
				$pdf->Image('../img/Sello OSPIM.png',21,190,45,45);
				if(strcmp($usuauto,"gflongo")==0) {
					$pdf->Image('../img/Firma Longo.png',160,190,18,50);
					$pdf->Image('../img/Sello Longo.png',150,220,35,13);
				} else {
					$pdf->Image('../img/Firma Giraudo.png',160,190,18,50);
					$pdf->Image('../img/Sello Giraudo.png',150,220,35,13);
				}
			}
		}
		
		if($docpa==1) {
			$totalpaginas=$pdf->setSourceFile($nombrepresupue);
			for($nropagina=1; $nropagina<=$totalpaginas; $nropagina++) { 
				$pdf->AddPage('P','Letter');
				$pdf->SetFont('Arial','B',18);
				$pdf->Cell(10);
				$pdf->Cell(183,8,"Presupuesto Aprobado - Hoja ".$nropagina,1,1,'C');
				$tplIdx = $pdf->importPage($nropagina);
				$pdf->useTemplate($tplIdx, 10, 30, 196);
				$pdf->Image('../img/Sello Presupuesto.png',87,130,60,40);
				if($montauto > 0.00) {
					$pdf->Image($imagenmonto,89,140,60,40);
				}
				$pdf->Image('../img/Sello OSPIM.png',21,190,45,45);
				if(strcmp($usuauto,"gflongo")==0) {
					$pdf->Image('../img/Firma Longo.png',160,190,18,50);
					$pdf->Image('../img/Sello Longo.png',150,220,35,13);
				} else {
					$pdf->Image('../img/Firma Giraudo.png',160,190,18,50);
					$pdf->Image('../img/Sello Giraudo.png',150,220,35,13);
				}
			}
		}
		

		$nombrearchivo = $_SERVER['DOCUMENT_ROOT']."/madera/ospim/auditoria/tempautorizaciones/Autorizacion Nro ".$nrosoli.".pdf";
		$pdf->Output($nombrearchivo,'F');

		$fph = fopen($nombrearchivo,"r");
		$contenidodoc = fread($fph, filesize($nombrearchivo));
		fclose($fph);
//		echo $contenidodoc;
	}

	
	$sqlActualizaAuto="UPDATE autorizacionesatendidas SET aprobado1 = :aprobado1, aprobado2 = :aprobado2, aprobado3 = :aprobado3, aprobado4 = :aprobado4, aprobado5 = :aprobado5, statusautorizacion = :statusautorizacion, fechaautorizacion = :fechaautorizacion, usuarioautorizacion = :usuarioautorizacion, clasificacionape = :clasificacionape, fechaemailape = :fechaemailape, rechazoautorizacion = :rechazoautorizacion, fechaemaildelega = :fechaemaildelega, emailprestador = :emailprestador, fechaemailprestador = :fechaemailprestador, patologia = :patologia, montocoseguro = :montocoseguro,  montoautorizacion = :montoautorizacion WHERE nrosolicitud = :nrosolicitud";
	//echo $sqlActualizaAuto; echo "<br>";
	$resultActualizaAuto = $dbl->prepare($sqlActualizaAuto);
	if($resultActualizaAuto->execute(array(':aprobado1' => $presapr1, ':aprobado2' => $presapr2, ':aprobado3' => $presapr3, ':aprobado4' => $presapr4, ':aprobado5' => $presapr5, ':statusautorizacion' => $staauto, ':fechaautorizacion' => $fecauto, ':usuarioautorizacion' => $usuauto, ':clasificacionape' => $apeauto, ':fechaemailape' => $apefech, ':rechazoautorizacion' => $recauto, ':fechaemaildelega' => $fecauto, ':emailprestador' => $presmail, ':fechaemailprestador' => $presfech, ':patologia' => $patoauto, ':montocoseguro' => $montcose ,':montoautorizacion' => $montoMostrar, ':nrosolicitud' => $nrosoli))) {	
		$sqlActualizaProcesadas="UPDATE autorizacionprocesada SET statusautorizacion = :statusautorizacion, fechaautorizacion = :fechaautorizacion, rechazoautorizacion = :rechazoautorizacion, fechaemail = :fechaemail WHERE nrosolicitud = :nrosolicitud";
		//echo $sqlActualizaProcesadas; echo "<br>";
		$resultActualizaProcesadas = $dbr->prepare($sqlActualizaProcesadas);
		if($resultActualizaProcesadas->execute(array(':statusautorizacion' => $staauto, ':fechaautorizacion' => $fecauto, ':rechazoautorizacion' => $recauto, ':fechaemail' => $fecauto, ':nrosolicitud' => $nrosoli))) { 
			if ($historia != "") {
				$sqlHistoria = "INSERT INTO autorizacioneshistoria (nrosolicitud, detalle) VALUES (:nrosolicitud, :detalle)";
				$resHistoria  = $dbl->prepare($sqlHistoria);
				if ($resHistoria->execute(array(':nrosolicitud' => $nrosoli, ':detalle' => $historia))) {
					$sqlDeleteAuto = "DELETE FROM autorizaciones WHERE nrosolicitud = :nrosolicitud";
					$resDeleteAuto = $dbl->prepare($sqlDeleteAuto);
					if ($resDeleteAuto->execute(array(':nrosolicitud' => $nrosoli))) {}
				}
			} else {
				$sqlDeleteAuto = "DELETE FROM autorizaciones WHERE nrosolicitud = :nrosolicitud";
				$resDeleteAuto = $dbl->prepare($sqlDeleteAuto);
				if ($resDeleteAuto->execute(array(':nrosolicitud' => $nrosoli))) {}
			}
		}
		if ($staauto==1)  {
			$sqlAddDocumento="INSERT INTO autorizaciondocumento (nrosolicitud, documentofinal) VALUES (:nrosolicitud, :documentofinal)";
			$resultAddDocumento = $dbl->prepare($sqlAddDocumento);
			if($resultAddDocumento->execute(array(':nrosolicitud' => $nrosoli, ':documentofinal' => $contenidodoc))) { }
		}
	}
	
	
	
	$bodymail="<body><br><br>Este es un mensaje de Aviso.<br><br>Su Solicitud de Autorizacion Nro: <strong>".$nrosoli."</strong>, ha sido <strong>".$estauto."</strong> por el Depto. de Autorizaciones de OSPIM el dia ".$fechamail." a las ".$horamail.".";
	$username ="autorizaciones@ospim.com.ar";
	$addressDelega = "autorizaciones".$rowLeeSolicitud['codidelega']."@ospim.com.ar";
	$subjectDelega = "AVISO!!! Solicitud de Autorizacion Atendida";
	$modulo = "Autorizaciones";
	//EMAIL RECHAZO
	if ($staauto==2) {
		$bodymail.="<br>Verifique la situacion de la solicitud a traves del modulo INTRANET DELEGACIONES.<br><br><br><br />Depto. de Autorizaciones<br />O.S.P.I.M.<br /></body>";
		$idMailDelegaRechazo = guardarEmail($username, $subjectDelega, $bodymail, $addressDelega, $modulo, null);
		if ($idMailDelegaRechazo == -1) {
			throw new PDOException('Error al intentar guardar el correo electronico');
		} else {
			$sqlInsertAutoMail = "INSERT INTO autorizacionesemail VALUES(:nrosolicitud,:idmail)";
			$resInsertAutoMail = $dbl->prepare($sqlInsertAutoMail);
			$resInsertAutoMail->execute(array(':nrosolicitud' => $nrosoli, ':idmail' => $idMailDelegaRechazo));
		}
	} 
	
	//EMAIL AUTORIZADO
	if ($staauto==1) {
		$arrayAttachment[] = $nombrearchivo;
		if(!empty($recauto)) {
			$bodymail.="<br>La aprobacion incluye una comunicacion de la que podra tomar conocimiento a traves del modulo INTRANET DELEGACIONES.";
		}
		$bodymail.="<br>Se envia adjunto documento PDF con los detalles de la Autorizacion.<br><br><br><br />Depto. de Autorizaciones<br />O.S.P.I.M.<br /></body>";
		$idMailDelega = guardarEmail($username, $subjectDelega, $bodymail, $addressDelega, $modulo, $arrayAttachment);
		if ($idMailDelega == -1) {
			throw new PDOException('Error al intentar guardar el correo electronico');
		} else {
			$sqlInsertAutoMail = "INSERT INTO autorizacionesemail VALUES(:nrosolicitud,:idmail)";
			$resInsertAutoMail = $dbl->prepare($sqlInsertAutoMail);
			$resInsertAutoMail->execute(array(':nrosolicitud' => $nrosoli, ':idmail' => $idMailDelega));
		}
		
		if($apeauto==1) {
			$bodymail="<body><br><br>Este es un mensaje de Aviso.<br><br>La Solicitud de Autorizacion Nro: <strong>".$nrosoli."</strong>, ha sido <strong>".$estauto."</strong> por el Depto. de Autorizaciones de OSPIM el dia ".$fechamail." a las ".$horamail.".";
			$bodymail.="<br>Se envia adjunto documento PDF con los detalles de la Autorizacion.<br><br><br><br />Depto. de Autorizaciones<br />O.S.P.I.M.<br /></body>";
			$subject = "AVISO: Autorizacion Aprobada incluye prestaciones SUR";
			$address = "expedientessur@ospim.com.ar";
			$idMailSur = guardarEmail($username, $subject, $bodymail, $address, $modulo, $arrayAttachment);
			if ($idMailSur == -1) {
				throw new PDOException('Error al intentar guardar el correo electronico');
			} else {
				$sqlInsertAutoMail = "INSERT INTO autorizacionesemail VALUES(:nrosolicitud,:idmail)";
				$resInsertAutoMail = $dbl->prepare($sqlInsertAutoMail);
				$resInsertAutoMail->execute(array(':nrosolicitud' => $nrosoli, ':idmail' => $idMailSur));
			}
		}
		
		if($presauto==1) {
			$bodymail="<body><br><br>Este es un mensaje de Aviso.<br><br>Autorizacion de Prestacion Nro: <strong>".$nrosoli."</strong>, <strong>".$estauto."</strong> por el Depto. de Autorizaciones de OSPIM el dia ".$fechamail." a las ".$horamail.".";
			if(!empty($recauto)) {
				$bodymail.="<br>Comentarios/Observaciones de la Autorizacion: ".$recauto.".";
			}
			$bodymail.="<br>Se envia adjunto documento PDF con los detalles de la Autorizacion.<br><br><br><br />Depto. de Autorizaciones<br />O.S.P.I.M. - Obra Social del Personal de la Industria Maderera<br /></body>";
			$subject = "AVISO: Autorizacion Aprobada";
			$address = $presmail;
			$idMailPresta = guardarEmail($username, $subject, $bodymail, $address, $modulo, $arrayAttachment);
			if ($idMailPresta == -1) {
				throw new PDOException('Error al intentar guardar el correo electronico');
			} else {
				$sqlInsertAutoMail = "INSERT INTO autorizacionesemail VALUES(:nrosolicitud,:idmail)";
				$resInsertAutoMail = $dbl->prepare($sqlInsertAutoMail);
				$resInsertAutoMail->execute(array(':nrosolicitud' => $nrosoli, ':idmail' => $idMailPresta));
			}
		}
	}

	$dbl->commit();
	$dbr->commit();

	$pagina = "listarSolicitudes.php";
	Header("Location: $pagina");
}
catch (Exception $e) {
	$error = $e->getMessage();
	$dbl->rollback();
	$dbr->rollback();	
	
	$pos = strpos($error, "FPDI");
	if ($pos !== false) {
		$pos = strpos($error, "pedidomedico");
		if ($pos !== false) {
			$archivo = 	"Pedido Medico";
		}
		$pos = strpos($error, "resumenhc");
		if ($pos !== false) {
			$archivo = 	"Resumen Historia Cl�nia";
		}
		$pos = strpos($error, "avalsolicitud");
		if ($pos !== false) {
			$archivo = 	"Estudios";
		}
		$pos = strpos($error, "presupuesto");
		if ($pos !== false) {
			$archivo = 	"Presupuesto Aprobado";
		}
		$error = "<b>Descripci�n:</b> El archivo de ".$archivo. " de la solicitud n� ".$nrosoli." se genero de manera incorrecta.<br><br> 
				  Si rechaza la solicitud por favor coloque la descripci�n de este error en el motivo del rechazo.<br><br> 
				  <b>Informaci�n para sistemas:</b> ".$error;
	}
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}
?>