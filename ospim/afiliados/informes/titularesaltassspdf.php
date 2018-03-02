<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
require($libPath."fpdf.php");
$maquina = $_SERVER['SERVER_NAME'];

if(strcmp("localhost",$maquina)==0)
	$archivo_path="informes/";
else
	$archivo_path="/home/sistemas/Documentos/Repositorio/Afiliaciones/";

$arrayDelegacion = explode("-",$_POST['delegacion']);
$delegacion = $arrayDelegacion[0];
$nomdelega = $arrayDelegacion[1];
$fecha = date("d-m-Y His");
$nombreArchivo = "FICHA ALTA SSS DELEGACION $delegacion AL $fecha.pdf";

function printHeader($pdf) {
	$pdf->Image('../img/Logo Membrete OSPIM.jpg',7,7,25,20,'JPG');
	$pdf->SetFont('Courier','B',30);
	$pdf->SetXY(35, 7);
	$pdf->Cell(35,10,"OSPIM",0,0);
	$pdf->SetFont('Courier','B',10);
	$pdf->SetXY(35, 16);
	$pdf->Cell(55,5,"Obra Social del Personal",0,0);
	$pdf->SetXY(35, 20);
	$pdf->Cell(55,5,"de la Industria Maderera",0,0);
	$pdf->SetFont('Courier','B',7);
	$pdf->SetXY(7, 27);
	$pdf->Cell(80,7,"Solidaridad y Organizacion al Servicio de la Familia",0,0);
	
	$pdf->SetFont('Courier','B',13);
	$pdf->SetXY(135, 7);
	$pdf->Cell(75,10,"Alta Titulares desde la SSS",0,0,"R");
		
	$pdf->SetFont('Courier','B',8);
	$pdf->SetXY(135, 15);
	$pdf->Cell(75,10,"Pedido de Actualización de datos",0,0,"R");
	$pdf->SetXY(135, 20);
	$pdf->Cell(75,10,"e Información de Familiares",0,0,"R");
		
	$pdf->SetFont('Courier','B',7);
	$pdf->SetXY(107, 27);
	$pdf->Cell(80,7,"INOS 11.100-1|Rojas 254-C1405ABB-Capital Federal-Tel: 4431-4089/4791",0,0);
		
	$pdf->Line(7, 35, 210, 35);
}

function printFooterAndDiv($pdf, $pagNumber) {
	$pdf->Line(7, 109, 210, 109);
	$pdf->Line(7, 179, 210, 179);
	$pdf->Line(7, 250, 210, 250);
	$pdf->SetFont('Courier','B',10);
	$pdf->SetXY(7, 253);
	$pdf->Cell(205,5,$pagNumber,0,0,"C");
}

$sqlTitularesSSS = "SELECT 
						t.*, DATE_FORMAT(t.fechanacimiento,'%d/%I/%Y') as fechanacimiento, 
						DATE_FORMAT(t.fechaempresa,'%d/%I/%Y') as fechaempresa,
						p.descrip as provincia, l.nomlocali as localidad, e.descrip as estadocivil, 
						empresas.nombre as empresa, empresas.domilegal as domiempresa,
						empresas.numpostal as postalempresa,
						empresas.ddn1 as ddn1, empresas.telefono1 as telefono1, 
						empresas.ddn2 as ddn2, empresas.telefono2 as telefono2,
						DATE_FORMAT(empresas.iniobliosp,'%d/%I/%Y') as fechainicio,
						provincia.descrip as proviempresa, localidades.nomlocali as localiempresa
				    FROM  
				    	provincia p, localidades l, estadocivil e , titulares t
				    LEFT JOIN empresas ON t.cuitempresa = empresas.cuit
				    LEFT JOIN provincia ON empresas.codprovin = provincia.codprovin
				    LEFT JOIN localidades ON empresas.codlocali = localidades.codlocali
				    WHERE
						t.codidelega = $delegacion and
						t.cantidadcarnet = 1 and
						t.fechacarnet is null and
						t.codprovin = p.codprovin and
						t.codlocali = l.codlocali and 
						t.estadocivil = e.codestciv";
$resTitularesSSS = mysql_query($sqlTitularesSSS,$db);
$canTitularesSSS = mysql_num_rows($resTitularesSSS);
echo $canTitularesSSS."<br>";
if ($canTitularesSSS != 0) {
	$pdf = new FPDF('P','mm','Letter');
	$pdf->SetMargins(3, 3);
	$pdf->AddPage();
	$contador = 1;
	$pagNumber = 1;
	$salto = 0;
	while ($rowTitularesSSS = mysql_fetch_array($resTitularesSSS)) {
		if ($contador > 3) {
			$pdf->AddPage();
			$pagNumber++;
			$contador = 1;
			$salto = 0;
		}
		if ($contador == 1) {
			printHeader($pdf);
			printFooterAndDiv($pdf, $pagNumber);			
		}
		
		$pdf->SetFont('Courier','B',15);
		$pdf->SetXY(7, 40+$salto);
		$pdf->Cell(80,7,"DATOS DEL TITULAR",0,0);
			
		$pdf->SetFont('Courier','B',10);
		$pdf->SetXY(7, 46+$salto);
		$data = "CREDENCIAL Nº: ".$rowTitularesSSS['nroafiliado'];
		$pdf->Cell(80,7,$data,0,0);
		
		$pdf->SetXY(7, 50+$salto);
		$data = "Apellido y nombre: ".$rowTitularesSSS['apellidoynombre'];
		$pdf->Cell(80,7,$data,0,0);
		
		$pdf->SetXY(144, 50+$salto);
		$data = "Fecha de Nacimiento: ".$rowTitularesSSS['fechanacimiento'];
		$pdf->Cell(80,7,$data,0,0);
		
		$pdf->SetXY(7, 54+$salto);
		$data = "Estado Civil: ".$rowTitularesSSS['estadocivil'];
		$pdf->Cell(80,7,$data,0,0);

		$pdf->SetXY(144, 54+$salto);
		$data = "Tipo de Doc: ".$rowTitularesSSS['tipodocumento']." Nº: ".$rowTitularesSSS['nrodocumento'];
		$pdf->Cell(80,7,$data,0,0);
		
		$pdf->SetXY(7, 58+$salto);
		$data = "Domicilio: ".$rowTitularesSSS['domicilio'];
		$pdf->Cell(80,7,$data,0,0);
		
		$pdf->SetXY(7, 62+$salto);
		$data = "C.P: ".$rowTitularesSSS['numpostal']." - LOC: ".$rowTitularesSSS['localidad']." - PROV: ".$rowTitularesSSS['provincia'];
		$pdf->Cell(80,7,$data,0,0);
		
		$pdf->SetXY(7, 66+$salto);
		$data = "E-mail: ".$rowTitularesSSS['email'];
		$pdf->Cell(80,7,$data,0,0);
		
		$pdf->SetXY(144, 66+$salto);
		$data = "Telefono: (".$rowTitularesSSS['ddn'].") ".$rowTitularesSSS['telefono'];
		$pdf->Cell(80,7,$data,0,0);
		
		$pdf->SetXY(7, 70+$salto);
		$data = "Fecha Ingreso Empresa que trabaja (Según DDJJ): ".$rowTitularesSSS['fechaempresa'];
		$pdf->Cell(80,7,$data,0,0);
		
		$pdf->SetXY(144, 70+$salto);
		$data = "C.U.I.L.: ".$rowTitularesSSS['cuil'];;
		$pdf->Cell(80,7,$data,0,0);
		
		$pdf->SetFont('Courier','B',15);
		$pdf->SetXY(7, 79+$salto);
		$pdf->Cell(80,7,"DATOS DE LA EMPRESA DONDE TRABAJA",0,0);
		
		$pdf->SetFont('Courier','B',10);
		
		$pdf->SetXY(7, 85+$salto);
		$data = "Razon Social: ".$rowTitularesSSS['empresa'];
		$pdf->Cell(80,7,$data,0,0);
		
		$pdf->SetXY(144, 85+$salto);
		$data = "C.U.I.T.: ".$rowTitularesSSS['cuitempresa'];
		$pdf->Cell(80,7,$data,0,0);
		
		$pdf->SetXY(7, 89+$salto);
		$data = "Domicilio: ".$rowTitularesSSS['domiempresa'];
		$pdf->Cell(80,7,$data,0,0);
		
		$pdf->SetXY(7, 93+$salto);
		$data = "C.P.: ".$rowTitularesSSS['postalempresa']. " - LOC: ".$rowTitularesSSS['localiempresa']." - PROV: ".$rowTitularesSSS['proviempresa'];
		$pdf->Cell(80,7,$data,0,0);
		
		$pdf->SetXY(7, 97+$salto);
		$data = "Telefono 1: (".$rowTitularesSSS['ddn1'].") ".$rowTitularesSSS['telefono1'];
		$pdf->Cell(80,7,$data,0,0);
		
		$pdf->SetXY(144, 97+$salto);
		$data = "Telefono 2: (".$rowTitularesSSS['ddn2'].") ".$rowTitularesSSS['telefono2'];
		$pdf->Cell(80,7,$data,0,0);
		
		$pdf->SetXY(7, 101+$salto);
		$fechainicio = $rowTitularesSSS['fechainicio'];
		if ($rowTitularesSSS['fechainicio'] == "00/12/0000") {
			$fechainicio = "Sin datos";
		}
		$data = "Inicio Actividad de la Empresa: ".$fechainicio;
		$pdf->Cell(80,7,$data,0,0);
		
		$salto += 70;	
		$contador++;
	}
	
	$nombrearchivoA = $archivo_path.$nombreArchivo;
	$pdf->Output($nombrearchivoA,'F');
	
	$pagina = "titularesaltasss.php?error=0&delega=$nomdelega";
	Header("Location: $pagina");
} else {
	$pagina = "titularesaltasss.php?error=2&delega=$nomdelega";
	Header("Location: $pagina");
}