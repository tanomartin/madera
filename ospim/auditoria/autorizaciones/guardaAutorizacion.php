<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
require_once($libPath."PHPMailer_5.2.2/class.phpmailer.php");
require($libPath."fpdf.php");
require($libPath."FPDI-1.4.4/fpdi.php"); 

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
	$recauto = "";

	if($datos[1]=="on")
		$apeauto = $datos[4];
	else
		$apeauto = $datos[2];

	if($apeauto==1)
		$apefech = date("Y-m-d H:i:s");
	else
		$apefech = "";

	if($datos[1]=="on")
		$presauto = $datos[5];
	else
		$presauto = $datos[3];

	if($presauto==1)
	{
		if($datos[1]=="on")
		{
			$presmail = $datos[6];
			$presfech =  date("Y-m-d H:i:s");
			$montauto = $datos[7];
		}
		else
		{
			$presmail = $datos[4];
			$presfech =  date("Y-m-d H:i:s");
			$montauto = $datos[5];
		}
	}
	else
	{
		$presmail = "";
		$presfech = "";	
		if($datos[1]=="on")
			$montauto = $datos[6];
		else
			$montauto = $datos[4];
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

//echo "Apellido y Nombre: "; echo $rowLeeSolicitud['apellidoynombre']; echo "<br>";

$sqlLeeDeleg = "SELECT nombre FROM delegaciones where codidelega = $rowLeeSolicitud[codidelega]";
$resultLeeDeleg = mysql_query($sqlLeeDeleg,$db); 
$rowLeeDeleg = mysql_fetch_array($resultLeeDeleg);

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
	$dbremota = "uv0471_intranet";
	//echo "$hostremoto"; echo "<br>";
	//echo "$dbremota"; echo "<br>";
	$dbr = new PDO("mysql:host=$hostremoto;dbname=$dbremota","uv0471","bsdf5762");
	//echo 'Connected to database remota<br/>';
	$dbr->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbr->beginTransaction();
		
	$sqlActualizaAuto="UPDATE autorizaciones SET aprobado1 = :aprobado1, aprobado2 = :aprobado2, aprobado3 = :aprobado3, aprobado4 = :aprobado4, aprobado5 = :aprobado5, statusautorizacion = :statusautorizacion, fechaautorizacion = :fechaautorizacion, usuarioautorizacion = :usuarioautorizacion, clasificacionape = :clasificacionape, fechaemailape = :fechaemailape, rechazoautorizacion = :rechazoautorizacion, fechaemaildelega = :fechaemaildelega, emailprestador = :emailprestador, fechaemailprestador = :fechaemailprestador, montoautorizacion = :montoautorizacion WHERE nrosolicitud = :nrosolicitud";
	//echo $sqlActualizaAuto; echo "<br>";
	$resultActualizaAuto = $dbl->prepare($sqlActualizaAuto);
	if($resultActualizaAuto->execute(array(':aprobado1' => $presapr1, ':aprobado2' => $presapr2, ':aprobado3' => $presapr3, ':aprobado4' => $presapr4, ':aprobado5' => $presapr5, ':statusautorizacion' => $staauto, ':fechaautorizacion' => $fecauto, ':usuarioautorizacion' => $usuauto, ':clasificacionape' => $apeauto, ':fechaemailape' => $apefech, ':rechazoautorizacion' => $recauto, ':fechaemaildelega' => $fecauto, ':emailprestador' => $presmail, ':fechaemailprestador' => $presfech, ':montoautorizacion' => $montauto, ':nrosolicitud' => $nrosoli)))
	{
		$sqlActualizaProcesadas="UPDATE autorizacionprocesada SET statusautorizacion = :statusautorizacion, fechaautorizacion = :fechaautorizacion, rechazoautorizacion = :rechazoautorizacion WHERE nrosolicitud = :nrosolicitud";
		//echo $sqlActualizaProcesadas; echo "<br>";
		$resultActualizaProcesadas = $dbr->prepare($sqlActualizaProcesadas);
		if($resultActualizaProcesadas->execute(array(':statusautorizacion' => $staauto, ':fechaautorizacion' => $fecauto, ':rechazoautorizacion' => $recauto, ':nrosolicitud' => $nrosoli)))
		{
		}
	}

	$dbl->commit();
	$dbr->commit();

	set_time_limit(0);

	$mail=new PHPMailer();
	$bodymail="<body><br><br>Este es un mensaje de Aviso.<br><br>Su Solicitud de Autorizacion Nro: <strong>".$nrosoli."</strong>, ha sido <strong>".$estauto."</strong> por el Depto. de Autorizaciones de OSPIM el dia ".$fechamail." a las ".$horamail.".";
	if($staauto==2)
	{	
		$bodymail.="<br>Verifique la situacion de la solicitud a traves del modulo INTRANET DELEGACIONES.<br><br><br><br />Depto. de Autorizaciones<br />O.S.P.I.M.<br /></body>";
	}
	else
	{
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
		$pdf->Cell(30,6,"Nro: ".$rowLeeSolicitud['nroafiliado']."/".$rowLeeSolicitud['codiparentesco'],1,0,'L');
		$pdf->Cell(40,6,"C.U.I.L.: ".$rowLeeSolicitud['cuil'],1,1,'L');
		$pdf->Cell(10);
		$pdf->Cell(183,6,"Tipo: ".$tiposoli,1,1,'L');
		$pdf->Cell(10);
		$pdf->Cell(183,6,"Monto Autorizado: ".$montauto,1,1,'L');

		if($rowLeeSolicitud['pedidomedico']!=NULL) {
			$documentacion="Pedido Medico";
			$contenidoarchivo=$rowLeeSolicitud['pedidomedico'];
			$nombrepedido="../tempautorizaciones/pedidomedico".$nrosoli.".pdf"; 
			$fch=fopen($nombrepedido, "w");
			fwrite($fch, $contenidoarchivo); 
			fclose($fch);
		}

		if($rowLeeSolicitud['resumenhc']!=NULL) {
			$documentacion.=" / Resumen Historia Clinica";
			$contenidoarchivo=$rowLeeSolicitud['resumenhc'];
			$nombreresumen="../tempautorizaciones/resumenhc".$nrosoli.".pdf"; 
			$fch=fopen($nombreresumen, "w");
			fwrite($fch, $contenidoarchivo); 
			fclose($fch);
		}

		if($rowLeeSolicitud['avalsolicitud']!=NULL) {
			$documentacion.=" / Estudios";
			$contenidoarchivo=$rowLeeSolicitud['avalsolicitud'];
			$nombreaval="../tempautorizaciones/avalsolicitud".$nrosoli.".pdf"; 
			$fch=fopen($nombreaval, "w");
			fwrite($fch, $contenidoarchivo); 
			fclose($fch);
		}

		if($presupue!= 0) {
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
		$pdf->Image('../img/Firma Giraudo.png',160,190,18,50);
		$pdf->Image('../img/Sello Giraudo.png',150,220,35,13);
		$pdf->AddPage('P','Letter');
		$pdf->Ln(10);
		$pdf->SetFont('Arial','B',18);
		$pdf->Cell(10);
		$pdf->Cell(183,8,"Pedido Medico",1,1,'C');
		$pdf->setSourceFile($nombrepedido);
		$tplIdx = $pdf->importPage(1);
		$pdf->useTemplate($tplIdx, 10, 10, 200);
		$pdf->Image('../img/Sello Autorizado.png',87,130,50,30);
		$pdf->Image('../img/Sello OSPIM.png',21,190,45,45);
		$pdf->Image('../img/Firma Giraudo.png',160,190,18,50);
		$pdf->Image('../img/Sello Giraudo.png',150,220,35,13);
		$pdf->AddPage('P','Letter');
		$pdf->Ln(10);
		$pdf->SetFont('Arial','B',18);
		$pdf->Cell(10);
		$pdf->Cell(183,8,"Resumen Historia Clinica",1,1,'C');
		$pdf->setSourceFile($nombreresumen);
		$tplIdx = $pdf->importPage(1);
		$pdf->useTemplate($tplIdx, 10, 10, 200);
		$pdf->Image('../img/Sello Autorizado.png',87,130,50,30);
		$pdf->Image('../img/Sello OSPIM.png',21,190,45,45);
		$pdf->Image('../img/Firma Giraudo.png',160,190,18,50);
		$pdf->Image('../img/Sello Giraudo.png',150,220,35,13);
		$pdf->AddPage('P','Letter');
		$pdf->Ln(10);
		$pdf->SetFont('Arial','B',18);
		$pdf->Cell(10);
		$pdf->Cell(183,8,"Estudios",1,1,'C');
		$pdf->setSourceFile($nombreaval);
		$tplIdx = $pdf->importPage(1);
		$pdf->useTemplate($tplIdx, 10, 10, 200);
		$pdf->Image('../img/Sello Autorizado.png',87,130,50,30);
		$pdf->Image('../img/Sello OSPIM.png',21,190,45,45);
		$pdf->Image('../img/Firma Giraudo.png',160,190,18,50);
		$pdf->Image('../img/Sello Giraudo.png',150,220,35,13);
		$pdf->AddPage('P','Letter');
		$pdf->Ln(10);
		$pdf->SetFont('Arial','B',18);
		$pdf->Cell(10);
		$pdf->Cell(183,8,"Presupuesto Aprobado",1,1,'C');
		$pdf->setSourceFile($nombrepresupue);
		$tplIdx = $pdf->importPage(1);
		$pdf->useTemplate($tplIdx, 10, 10, 200);
		$pdf->Image('../img/Sello Autorizado.png',87,130,50,30);
		$pdf->Image('../img/Sello OSPIM.png',21,190,45,45);
		$pdf->Image('../img/Firma Giraudo.png',160,190,18,50);
		$pdf->Image('../img/Sello Giraudo.png',150,220,35,13);
		$nombrearchivo = "../tempautorizaciones/Autorizacion Nro ".$nrosoli.".pdf";
		$pdf->Output($nombrearchivo,'F');

		$bodymail.="<br>Se envia adjunto documento PDF con los detalles de la Autorizacion.<br><br><br><br />Depto. de Autorizaciones<br />O.S.P.I.M.<br /></body>";

		$mail->Timeout=120;
		$mail->AddAttachment($nombrearchivo);  

//		$fph = fopen($nombrearchivo,"rb");
//		$contenidodoc = fread($fph, filesize($nombrearchivo));
//		fclose($fph);

//		$sqlAddDocumento="INSERT INTO autorizaciondocumento (nrosolicitud, documentofinal) VALUES ('$nrosoli','$contenidodoc')";
//		$resultAddDocumento = $dbl->query($resultAddDocumento);
	}

	$mail->IsSMTP();							// telling the class to use SMTP
	$mail->Host="smtp.ospim.com.ar"; 			// SMTP server
	$mail->SMTPAuth=true;						// enable SMTP authentication
	$mail->Host="smtp.ospim.com.ar";			// sets the SMTP server
	$mail->Port=25;								// set the SMTP port for the GMAIL server
	$mail->Username="jcbolognese@ospim.com.ar";	// SMTP account username
	$mail->Password="256512";					// SMTP account password
	$mail->SetFrom("jcbolognese@ospim.com.ar", "Autorizaciones OSPIM");
	$mail->AddReplyTo("jcbolognese@ospim.com.ar","Cozzi OSPIM");
	$mail->Subject="AVISO!!! Solicitud de Autorizacion Atendida";
	$mail->AltBody="Para ver este mensaje, por favor use un lector de correo compatible con HTML!"; // optional, comment out and test
	$mail->MsgHTML($bodymail);
	$address = "jcbolognese@ospim.com.ar";
	$nameto = "Autorizaciones ".$rowLeeSolicitud['codidelega']." - ".$rowLeeDeleg['nombre'];
	$mail->AddAddress($address, $nameto);
	$mail->AddBCC("jcbolognese@usimra.com.ar", "Autorizaciones OSPIM");
	$mail->Send();

	if($apeauto==1)
	{
//		TODO: Envia mail al departamento APE para comunicar que se trata de una autorizacion que incluye prestaciones APE
//		$bodymail="<body><br><br>Este es un mensaje de Aviso.<br><br>Su Solicitud de Autorizacion Nro: <strong>".$nrosoli."</strong>, ha sido <strong>".$estauto."</strong> por el Depto. de Autorizaciones de OSPIM el dia ".$fechamail." a las ".$horamail.".";
//		$mail->Subject="AVISO!!! Autorizacion Aprobada incluye prestaciones APE";
//		$mail->MsgHTML($bodymail);
//		$address = "jcbolognese@ospim.com.ar";
//		$nameto = "APE OSPIM";
//		$mail->AddAddress($address, $nameto);
	}

	if($presauto==1)
	{
//		TODO: Envia mail al prestador para avisarle que hay una prestacion autorizada
//		$bodymail="<body><br><br>Este es un mensaje de Aviso.<br><br>Su Solicitud de Autorizacion Nro: <strong>".$nrosoli."</strong>, ha sido <strong>".$estauto."</strong> por el Depto. de Autorizaciones de OSPIM el dia ".$fechamail." a las ".$horamail.".";
//		$mail->Subject="AVISO!!! Autorizacion Aprobada incluye prestaciones APE";
//		$mail->MsgHTML($bodymail);
//		$address = $presmail;
//		$nameto = "Prestador OSPIM";
//		$mail->AddAddress($address, $nameto);
	}

	$pagina = "listarSolicitudes.php";
	Header("Location: $pagina");
}
catch (PDOException $e) {
	echo $e->getMessage();
	$dbl->rollback();
	$dbr->rollback();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Guarda Autorizacion :.</title></head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo1 {
	font-family: Arial, Helvetica, sans-serif;
	font-style: italic;
	font-weight: bold;
}
</style>
<body bgcolor="#CCCCCC">
</body>
</html>