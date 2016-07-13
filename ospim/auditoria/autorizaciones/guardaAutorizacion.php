<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
require_once($libPath."PHPMailer_5.2.2/class.phpmailer.php");
require_once($libPath."fpdf.php");
require_once($libPath."FPDI-1.6.1/fpdi.php"); 

//var_dump($_POST);

$datos = array_values($_POST);

$nrosoli = $datos[0];
if($datos[1]=="on")
{
//	echo "SI Viene presupuesto"; echo "<br>";
	$presupue = $datos[2]; 
	$staauto = $datos[3];
}
else
{
//	echo "NO viene presupuesto"; echo "<br>";
	$presupue = 0;
	$staauto = $datos[1];
}

$fecauto = date("Y-m-d H:i:s");
$usuauto = $_SESSION['usuario'];

if($staauto==2)
{
	$presupue = 0;
	$estauto = "Rechazada";
	$recauto = $datos[2];
	$apeauto = "";
	$apefech = "";
	$presauto = "";
	$presmail = "";
	$presfech = "";	
	$montauto = "0.00";
}
else
{
	$estauto = "Aprobada";
	//$recauto = "";

	if($datos[1]=="on")
		$recauto = $datos[4];
	else
		$recauto = $datos[2];

	if($datos[1]=="on")
		$apeauto = $datos[5];
	else
		$apeauto = $datos[3];

	if($apeauto==1)
		$apefech = date("Y-m-d H:i:s");
	else
		$apefech = "";

	if($datos[1]=="on")
		$presauto = $datos[6];
	else
		$presauto = $datos[4];

	if($presauto==1)
	{
		if($datos[1]=="on")
		{
			$presmail = $datos[7];
			$presfech =  date("Y-m-d H:i:s");
			$montauto = $datos[8];
		}
		else
		{
			$presmail = $datos[5];
			$presfech =  date("Y-m-d H:i:s");
			$montauto = $datos[6];
		}
	}
	else
	{
		$presmail = "";
		$presfech = "";	
		if($datos[1]=="on")
			$montauto = $datos[7];
		else
			$montauto = $datos[5];
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
//echo "Monto Autorizado: "; echo $montauto; echo "<br>";
//echo "Aprobado 1: "; echo $presapr1; echo "<br>";
//echo "Aprobado 2: "; echo $presapr2; echo "<br>";
//echo "Aprobado 3: "; echo $presapr3; echo "<br>";
//echo "Aprobado 4: "; echo $presapr4; echo "<br>";
//echo "Aprobado 5: "; echo $presapr5; echo "<br>";

$fechamail=date("d/m/Y");
$horamail=date("H:i");

$sqlLeeSolicitud="SELECT * FROM autorizaciones where nrosolicitud = $nrosoli";
$resultLeeSolicitud=mysql_query($sqlLeeSolicitud,$db);
$rowLeeSolicitud=mysql_fetch_array($resultLeeSolicitud);

$cuilSolicitud = $rowLeeSolicitud['cuil'];

//echo "Apellido y Nombre: "; echo $rowLeeSolicitud['apellidoynombre']; echo "<br>";

$sqlLeeDeleg = "SELECT nombre FROM delegaciones where codidelega = $rowLeeSolicitud[codidelega]";
$resultLeeDeleg = mysql_query($sqlLeeDeleg,$db); 
$rowLeeDeleg = mysql_fetch_array($resultLeeDeleg);

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
		$sqlLeeMaterial = "SELECT descripcion FROM clasificamaterial where codigo = $rowLeeSolicitud[tipomaterial]";
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

//conexion y creacion de transaccion.
try {
	$hostlocal = $_SESSION['host'];
	$dblocal = $_SESSION['dbname'];
	//echo "$hostlocal"; echo "<br>";
	//echo "$dblocal"; echo "<br>";
	$dbl = new PDO("mysql:host=$hostlocal;dbname=$dblocal",$_SESSION['usuario'],$_SESSION['clave']);
	//echo 'Connected to database local<br/>';
	$dbl->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbl->beginTransaction();

	$hostremoto = "ospim.com.ar";
	$dbremota = "sistem22_intranet";
	//echo "$hostremoto"; echo "<br>";
	//echo "$dbremota"; echo "<br>";
	$dbr = new PDO("mysql:host=$hostremoto;dbname=$dbremota","sistem22_charly","bsdf5762");
	//echo 'Connected to database remota<br/>';
	$dbr->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbr->beginTransaction();

	set_time_limit(0);

	$mail=new PHPMailer();
	$bodymail="<body><br><br>Este es un mensaje de Aviso.<br><br>Su Solicitud de Autorizacion Nro: <strong>".$nrosoli."</strong>, ha sido <strong>".$estauto."</strong> por el Depto. de Autorizaciones de OSPIM el dia ".$fechamail." a las ".$horamail.".";
	if($staauto==2) {	
		$bodymail.="<br>Verifique la situacion de la solicitud a traves del modulo INTRANET DELEGACIONES.<br><br><br><br />Depto. de Autorizaciones<br />O.S.P.I.M.<br /></body>";
	} else {
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
		$pdf->Cell(69,5,'Solidaridad  y  Organización  al  Servicio   de   la  Familia',0,0,'L');
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(74);
		$pdf->Cell(40,5,date("j")." de ".date("F")." de ".date("Y").".-",0,1,'R');
//		$pdf->Cell(40,5,date("j")." de Julio de ".date("Y").".-",0,1,'R');
		$pdf->Ln(20);
		$pdf->SetFont('Arial','B',18);
		$pdf->Cell(10);
		$pdf->Cell(183,8,"Autorización Nro. ".$nrosoli,1,1,'C');
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
		$pdf->Cell(113,6,"Monto Autorizado: ".$montauto,1,0,'L');
		$pdf->Cell(70,6,"Delegacion: ".$rowLeeDeleg['nombre'],1,1,'L');

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
		if(strcmp($usuauto,"mlberges")==0) {
			$pdf->Image('../img/Firma Berges.png',160,190,18,50);
			$pdf->Image('../img/Sello Berges.png',150,220,35,13);
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
				$pdf->Image('../img/Sello Autorizado.png',87,130,50,30);
				$pdf->Image('../img/Sello OSPIM.png',21,190,45,45);
				if(strcmp($usuauto,"mlberges")==0) {
					$pdf->Image('../img/Firma Berges.png',160,190,18,50);
					$pdf->Image('../img/Sello Berges.png',150,220,35,13);
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
				if(strcmp($usuauto,"mlberges")==0) {
					$pdf->Image('../img/Firma Berges.png',160,190,18,50);
					$pdf->Image('../img/Sello Berges.png',150,220,35,13);
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
				if(strcmp($usuauto,"mlberges")==0) {
					$pdf->Image('../img/Firma Berges.png',160,190,18,50);
					$pdf->Image('../img/Sello Berges.png',150,220,35,13);
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
				$pdf->Image('../img/Sello Presupuesto.png',87,130,50,30);
				$pdf->Image('../img/Sello OSPIM.png',21,190,45,45);
				if(strcmp($usuauto,"mlberges")==0) {
					$pdf->Image('../img/Firma Berges.png',160,190,18,50);
					$pdf->Image('../img/Sello Berges.png',150,220,35,13);
				} else {
					$pdf->Image('../img/Firma Giraudo.png',160,190,18,50);
					$pdf->Image('../img/Sello Giraudo.png',150,220,35,13);
				}
			}
		}
		$nombrearchivo = "../tempautorizaciones/Autorizacion Nro ".$nrosoli.".pdf";
		$pdf->Output($nombrearchivo,'F');

		if(!empty($recauto)) {
			$bodymail.="<br>La aprobacion incluye una comunicacion de la que podra tomar conocimiento a traves del modulo INTRANET DELEGACIONES.";
		}

		$bodymail.="<br>Se envia adjunto documento PDF con los detalles de la Autorizacion.<br><br><br><br />Depto. de Autorizaciones<br />O.S.P.I.M.<br /></body>";

		$mail->Timeout=120;
		$mail->AddAttachment($nombrearchivo);  

		$fph = fopen($nombrearchivo,"r");
		$contenidodoc = fread($fph, filesize($nombrearchivo));
		fclose($fph);
//		echo $contenidodoc;
	}
	
	$sqlActualizaAuto="UPDATE autorizaciones SET aprobado1 = :aprobado1, aprobado2 = :aprobado2, aprobado3 = :aprobado3, aprobado4 = :aprobado4, aprobado5 = :aprobado5, statusautorizacion = :statusautorizacion, fechaautorizacion = :fechaautorizacion, usuarioautorizacion = :usuarioautorizacion, clasificacionape = :clasificacionape, fechaemailape = :fechaemailape, rechazoautorizacion = :rechazoautorizacion, fechaemaildelega = :fechaemaildelega, emailprestador = :emailprestador, fechaemailprestador = :fechaemailprestador, montoautorizacion = :montoautorizacion WHERE nrosolicitud = :nrosolicitud";
	//echo $sqlActualizaAuto; echo "<br>";
	$resultActualizaAuto = $dbl->prepare($sqlActualizaAuto);
	if($resultActualizaAuto->execute(array(':aprobado1' => $presapr1, ':aprobado2' => $presapr2, ':aprobado3' => $presapr3, ':aprobado4' => $presapr4, ':aprobado5' => $presapr5, ':statusautorizacion' => $staauto, ':fechaautorizacion' => $fecauto, ':usuarioautorizacion' => $usuauto, ':clasificacionape' => $apeauto, ':fechaemailape' => $apefech, ':rechazoautorizacion' => $recauto, ':fechaemaildelega' => $fecauto, ':emailprestador' => $presmail, ':fechaemailprestador' => $presfech, ':montoautorizacion' => $montauto, ':nrosolicitud' => $nrosoli)))
	{	
		$sqlActualizaProcesadas="UPDATE autorizacionprocesada SET statusautorizacion = :statusautorizacion, fechaautorizacion = :fechaautorizacion, rechazoautorizacion = :rechazoautorizacion, fechaemail = :fechaemail WHERE nrosolicitud = :nrosolicitud";
		//echo $sqlActualizaProcesadas; echo "<br>";
		$resultActualizaProcesadas = $dbr->prepare($sqlActualizaProcesadas);
		if($resultActualizaProcesadas->execute(array(':statusautorizacion' => $staauto, ':fechaautorizacion' => $fecauto, ':rechazoautorizacion' => $recauto, ':fechaemail' => $fecauto, ':nrosolicitud' => $nrosoli)))
		{
		}
		if ($staauto==1)  {
			$sqlAddDocumento="INSERT INTO autorizaciondocumento (nrosolicitud, documentofinal) VALUES (:nrosolicitud, :documentofinal)";
			$resultAddDocumento = $dbl->prepare($sqlAddDocumento);
			if($resultAddDocumento->execute(array(':nrosolicitud' => $nrosoli, ':documentofinal' => $contenidodoc)))
			{
			}
		}
	}

	$dbl->commit();
	$dbr->commit();

	$mail->IsSMTP();							// telling the class to use SMTP
	$mail->Host="smtp.ospim.com.ar"; 			// SMTP server
	$mail->SMTPAuth=true;						// enable SMTP authentication
	$mail->Host="smtp.ospim.com.ar";			// sets the SMTP server
	$mail->Port=25;								// set the SMTP port for the GMAIL server
	$mail->Username="autorizaciones@ospim.com.ar";	// SMTP account username
	$mail->Password="curt5716";					// SMTP account password
	$mail->SetFrom("autorizaciones@ospim.com.ar", "Autorizaciones OSPIM");
	$mail->AddReplyTo("autorizaciones@ospim.com.ar","Autorizaciones OSPIM");
	$mail->Subject="AVISO!!! Solicitud de Autorizacion Atendida";
	$mail->AltBody="Para ver este mensaje, por favor use un lector de correo compatible con HTML!"; // optional, comment out and test
	$mail->MsgHTML($bodymail);
	$address = "autorizaciones".$rowLeeSolicitud['codidelega']."@ospim.com.ar";
//	$nameto = "Autorizaciones ".$rowLeeSolicitud['codidelega']." - ".$rowLeeDeleg['nombre'];
	$nameto = "";
	$mail->AddAddress($address, $nameto);
//	$mail->AddBCC("jcbolognese@usimra.com.ar", "Autorizaciones OSPIM");
	$mail->Send();

	if($apeauto==1)
	{
//		TODO: Envia mail al departamento APE para comunicar que se trata de una autorizacion que incluye prestaciones APE
		$mail->ClearAddresses();
		$bodymail="<body><br><br>Este es un mensaje de Aviso.<br><br>La Solicitud de Autorizacion Nro: <strong>".$nrosoli."</strong>, ha sido <strong>".$estauto."</strong> por el Depto. de Autorizaciones de OSPIM el dia ".$fechamail." a las ".$horamail.".";
		$bodymail.="<br>Se envia adjunto documento PDF con los detalles de la Autorizacion.<br><br><br><br />Depto. de Autorizaciones<br />O.S.P.I.M.<br /></body>";
//		$mail->AddReplyTo("autorizaciones@ospim.com.ar","Autorizaciones OSPIM");
		$mail->Subject="AVISO: Autorizacion Aprobada incluye prestaciones SUR";
		$mail->MsgHTML($bodymail);
		$address = "expedientessur@ospim.com.ar";
//		$nameto = "APE OSPIM";
		$nameto = "";
		$mail->AddAddress($address, $nameto);
		$mail->Timeout=120;
		$mail->AddAttachment($nombrearchivo);
		$mail->Send();
	}

	if($presauto==1)
	{
//		TODO: Envia mail al prestador para avisarle que hay una prestacion autorizada
		$mail->ClearAddresses();
		$bodymail="<body><br><br>Este es un mensaje de Aviso.<br><br>Autorizacion de Prestacion Nro: <strong>".$nrosoli."</strong>, <strong>".$estauto."</strong> por el Depto. de Autorizaciones de OSPIM el dia ".$fechamail." a las ".$horamail.".";
		$bodymail.="<br>Comentarios/Observaciones de la Autorizacion: ".$recauto.".";
		$bodymail.="<br>Se envia adjunto documento PDF con los detalles de la Autorizacion.<br><br><br><br />Depto. de Autorizaciones<br />O.S.P.I.M. - Obra Social del Personal de la Industria Maderera<br /></body>";
		$mail->AddReplyTo("autorizaciones@ospim.com.ar","Autorizaciones OSPIM");
		$mail->Subject="AVISO: Autorizacion Aprobada";
		$mail->MsgHTML($bodymail);
		$address = $presmail;
//		$nameto = "Prestador OSPIM";
		$nameto = "";
		$mail->AddAddress($address, $nameto);
		$mail->Timeout=120;
		$mail->AddAttachment($nombrearchivo);
		$mail->Send();
	}

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
			$archivo = 	"Resumen Historia Clínia";
		}
		$pos = strpos($error, "avalsolicitud");
		if ($pos !== false) {
			$archivo = 	"Estudios";
		}
		$pos = strpos($error, "presupuesto");
		if ($pos !== false) {
			$archivo = 	"Presupuesto Aprobado";
		}
		$error = "<b>Descripción:</b> El archivo de ".$archivo. " de la solicitud nº ".$nrosoli." se genero de manera incorrecta.<br><br> 
				  Si rechaza la solicitud por favor coloque la descripción de este error en el motivo del rechazo.<br><br> 
				  <b>Información para sistemas:</b> ".$error;
	}
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}
?>