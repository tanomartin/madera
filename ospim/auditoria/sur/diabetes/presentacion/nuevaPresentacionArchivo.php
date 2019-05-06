<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$whereIn = "(";
$arrayCuiles = array();
foreach ($_POST as $datos) {
	$arrayDatos = explode("-",$datos);
	$whereIn .= $arrayDatos[0].",";
	$arrayCuiles[$arrayDatos[0]] = $arrayDatos[1];
}
$whereIn = substr($whereIn, 0, -1);
$whereIn .= ")";

$periodo = $_GET['periodo'];

$sqlListadoDiabetes = "SELECT d.id, d.nroafiliado, d.nroorden, d.tipodiabetes, d.fechaficha, b.edaddiagnostico,
							  
							  diabetescomorbilidad.dislipemia as dislipemia,
							  diabetescomorbilidad.obesidad as obesidad,
							  diabetescomorbilidad.tabaquismo as tabaquismo,
							  
							  diabetescomplicaciones.hipertrofiaventricular as hipertrofiaventricular,
							  diabetescomplicaciones.infartomiocardio as infartomiocardio,
							  diabetescomplicaciones.insuficienciacardiaca as insuficienciacardiaca,
							  diabetescomplicaciones.accidentecerebrovascular as accidentecerebrovascular,
							  diabetescomplicaciones.retinopatia as retinopatia,
							  diabetescomplicaciones.ceguera as ceguera,
							  diabetescomplicaciones.neuropatiaperiferica as neuropatiaperiferica,
							  diabetescomplicaciones.vasculopatiaperiferica as vasculopatiaperiferica,
							  diabetescomplicaciones.amputacion as amputacion,
							  diabetescomplicaciones.nefropatia as nefropatia,
							  diabetescomplicaciones.dialisis as dialisis,
							  diabetescomplicaciones.transplanterenal as transplanterenal,
							  
							  diabetesestudios.glucemiavalor as glucemiavalor,
							  diabetesestudios.glucemiafecha as glucemiafecha,
							  diabetesestudios.hba1cvalor as hba1cvalor,
							  diabetesestudios.hba1cfecha as hba1cfecha,
							  diabetesestudios.ldlcvalor as ldlcvalor,
							  diabetesestudios.ldlcfecha as ldlcfecha,
 							  diabetesestudios.trigliceridosvalor as trigliceridosvalor,
							  diabetesestudios.trigliceridosfecha as trigliceridosfecha,
 							  diabetesestudios.microalbuminuriavalor as microalbuminuriavalor,
 							  diabetesestudios.microalbuminuriafecha as microalbuminuriafecha,
							  diabetesestudios.tasistolicavalor as tasistolicavalor,
 							  diabetesestudios.tasistolicafecha as tasistolicafecha,
 							  diabetesestudios.tadiastolicavalor as tadiastolicavalor, 
 							  diabetesestudios.tadiastolicafecha as tadiastolicafecha,
 							  diabetesestudios.creatininasericavalor as creatininasericavalor, 
 							  diabetesestudios.creatininasericafecha as creatininasericafecha,
							  diabetesestudios.fondodeojo as fondodeojo,
							  diabetesestudios.fondodeojofecha as fondodeojofecha,
							  diabetesestudios.pesovalor as pesovalor,
							  diabetesestudios.pesofecha as pesofecha,
							  diabetesestudios.tallavalor as tallavalor, 
							  diabetesestudios.tallafecha as tallafecha,
							  diabetesestudios.cinturavalor as cinturavalor,
							  diabetesestudios.cinturafecha as cinturafecha, 
 							  
							  diabetestratamientos.automonitoreoglucemico as automonitoreoglucemico,
							  diabetestratamientos.actividadfisica as actividadfisica,
							  diabetestratamientos.cumpletratamiento as cumpletratamiento,
							  diabetestratamientos.farmacosantihipertensivos as farmacosantihipertensivos,
							  diabetestratamientos.farmacoshipolipemiantes as farmacoshipolipemiantes,
							  diabetestratamientos.acidoacetilsalicilico as acidoacetilsalicilico,
							  diabetestratamientos.hipoglucemiantesorales as hipoglucemiantesorales,
							  				  
							  d1.codigosss as insulinabasalcodigo,
							  d2.codigosss as insulinacorreccioncodigo
					   FROM diabetesbeneficiarios b, diabetesdiagnosticos d
					   LEFT JOIN diabetescomorbilidad on diabetescomorbilidad.idDiagnostico = d.id
					   LEFT JOIN diabetescomplicaciones on diabetescomplicaciones.idDiagnostico = d.id
					   LEFT JOIN diabetesestudios on diabetesestudios.idDiagnostico = d.id
					   LEFT JOIN diabetestratamientos on diabetestratamientos.idDiagnostico = d.id
					   LEFT JOIN diabetesfarmacos on diabetesfarmacos.idDiagnostico = d.id
					   LEFT JOIN diabetesinsulinas d1 on d1.id = diabetesfarmacos.insulinabasalcodigo
					   LEFT JOIN diabetesinsulinas d2 on d2.id = diabetesfarmacos.insulinacorreccioncodigo
					   WHERE d.id in $whereIn and d.nroafiliado = b.nroafiliado and d.nroorden = b.nroorden";

echo $sqlListadoDiabetes."<br><br>";
$resListadoDiabetes = mysql_query($sqlListadoDiabetes,$db);

$cantidadBene = sizeof($arrayCuiles);
$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];
$timestamp = date("YmdHis");
$nombreArchivo = "DIAB-$periodo-$timestamp.csv";

$maquina = $_SERVER['SERVER_NAME'];
if(strcmp("localhost",$maquina) == 0)
	$archivoImportacion="archivos/$nombreArchivo";
else
	$archivoImportacion="/home/sistemas/Documentos/Diabetes/$nombreArchivo";

$insertPresentacion = "INSERT INTO diabetespresentacion VALUES(DEFAULT, '$periodo', $cantidadBene,'$archivoImportacion', NULL,NULL,NULL,NULL,NULL,NULL,'$fecharegistro','$usuarioregistro',NULL,NULL)";
$file = fopen($archivoImportacion, "w");
if ($file !== false) {
	while ($rowListadoDiabetes = mysql_fetch_assoc($resListadoDiabetes)) {
		$fechaRegistro = date_format(date_create($rowListadoDiabetes['fechaficha']),"Ymd");
		$edadDiag = str_pad($rowListadoDiabetes['edaddiagnostico'],2,0,STR_PAD_LEFT);
		
		$hipertrofiaventricular = $rowListadoDiabetes['hipertrofiaventricular'];
		if ($hipertrofiaventricular == 1) {
			$hipertrofiaventricular = 2;
		}
		$hipertrofiaventricular = str_pad($hipertrofiaventricular,8,0,STR_PAD_LEFT);
		
		$infartomiocardio = $rowListadoDiabetes['infartomiocardio'];
		if ($infartomiocardio == 1) {
			$infartomiocardio = 2;
		}
		$infartomiocardio = str_pad($infartomiocardio,8,0,STR_PAD_LEFT);
		
		$insuficienciacardiaca = $rowListadoDiabetes['insuficienciacardiaca'];
		if ($insuficienciacardiaca == 1) {
			$insuficienciacardiaca = 2;
		}
		$insuficienciacardiaca = str_pad($insuficienciacardiaca,8,0,STR_PAD_LEFT);
		
		$accidentecerebrovascular = $rowListadoDiabetes['accidentecerebrovascular'];
		if ($accidentecerebrovascular == 1) {
			$accidentecerebrovascular = 2;
		}
		$accidentecerebrovascular = str_pad($accidentecerebrovascular,8,0,STR_PAD_LEFT);
		
		$retinopatia = $rowListadoDiabetes['retinopatia'];
		if ($retinopatia == 1) {
			$retinopatia = 2;
		}
		$retinopatia = str_pad($retinopatia,8,0,STR_PAD_LEFT);
		
		$ceguera = $rowListadoDiabetes['ceguera'];
		if ($ceguera == 1) {
			$ceguera = 2;
		}
		$ceguera = str_pad($ceguera,8,0,STR_PAD_LEFT);
		
		$neuropatiaperiferica = $rowListadoDiabetes['neuropatiaperiferica'];
		if ($neuropatiaperiferica == 1) {
			$neuropatiaperiferica = 2;
		}
		$neuropatiaperiferica = str_pad($neuropatiaperiferica,8,0,STR_PAD_LEFT);
		
		$vasculopatiaperiferica = $rowListadoDiabetes['vasculopatiaperiferica'];
		if ($vasculopatiaperiferica == 1) {
			$vasculopatiaperiferica = 2;
		}
		$vasculopatiaperiferica = str_pad($vasculopatiaperiferica,8,0,STR_PAD_LEFT);
		
		$amputacion = $rowListadoDiabetes['amputacion'];
		if ($amputacion == 1) {
			$amputacion = 2;
		}
		$amputacion = str_pad($amputacion,8,0,STR_PAD_LEFT);
		
		$nefropatia = $rowListadoDiabetes['nefropatia'];
		if ($nefropatia == 1) {
			$nefropatia = 2;
		}
		$nefropatia = str_pad($nefropatia,8,0,STR_PAD_LEFT);
		
		$dialisis = $rowListadoDiabetes['dialisis'];
		if ($dialisis == 1) {
			$dialisis = 2;
		}
		$dialisis = str_pad($dialisis,8,0,STR_PAD_LEFT);
		
		$transplanterenal = $rowListadoDiabetes['transplanterenal'];
		if ($transplanterenal == 1) {
			$transplanterenal = 2;
		}
		$transplanterenal = str_pad($transplanterenal,8,0,STR_PAD_LEFT);
		
		$glucemia = str_pad($rowListadoDiabetes['glucemiavalor'],4,0,STR_PAD_LEFT);
		$glucemiafecha = $rowListadoDiabetes['glucemiafecha'];
		if ($glucemiafecha == NULL) {
			$glucemiafecha = "00000000";
		} else {
			$glucemiafecha = date_format(date_create($glucemiafecha),"Ymd");
		}
		
		$hba1cvalor = str_pad($rowListadoDiabetes['hba1cvalor'],5,0,STR_PAD_LEFT);
		$hba1cfecha = $rowListadoDiabetes['hba1cfecha'];
		if ($hba1cfecha == NULL) {
			$hba1cfecha = "00000000";
		} else {
			$hba1cfecha = date_format(date_create($hba1cfecha),"Ymd");
		}
		
		$ldlcvalor = str_pad($rowListadoDiabetes['ldlcvalor'],4,0,STR_PAD_LEFT);
		$ldlcfecha = $rowListadoDiabetes['ldlcfecha'];
		if ($ldlcfecha == NULL) {
			$ldlcfecha = "00000000";
		} else {
			$ldlcfecha = date_format(date_create($ldlcfecha),"Ymd");
		}
		
		$trigliceridosvalor = str_pad($rowListadoDiabetes['trigliceridosvalor'],4,0,STR_PAD_LEFT);
		$trigliceridosfecha = $rowListadoDiabetes['trigliceridosfecha'];
		if ($trigliceridosfecha == NULL) {
			$trigliceridosfecha = "00000000";
		} else {
			$trigliceridosfecha = date_format(date_create($trigliceridosfecha),"Ymd");
		}
		
		$microalbuminuriavalor = $rowListadoDiabetes['microalbuminuriavalor'];
		$microalbuminuriafecha = $rowListadoDiabetes['microalbuminuriafecha'];
		if ($microalbuminuriafecha == NULL) {
			$microalbuminuriafecha = "00000000";
		} else {
			$microalbuminuriafecha = date_format(date_create($microalbuminuriafecha),"Ymd");
		}
		
		$tasistolicavalor = str_pad($rowListadoDiabetes['tasistolicavalor'],3,0,STR_PAD_LEFT);
		$tasistolicafecha = $rowListadoDiabetes['tasistolicafecha'];
		if ($tasistolicafecha == NULL) {
			$tasistolicafecha = "00000000";
		} else {
			$tasistolicafecha = date_format(date_create($tasistolicafecha),"Ymd");
		}
		
		$tadiastolicavalor = str_pad($rowListadoDiabetes['tadiastolicavalor'],3,0,STR_PAD_LEFT);
		$tadiastolicafecha = $rowListadoDiabetes['tadiastolicafecha'];
		if ($tadiastolicafecha == NULL) {
			$tadiastolicafecha = "00000000";
		} else {
			$tadiastolicafecha = date_format(date_create($tadiastolicafecha),"Ymd");
		}
		
		$creatininasericavalor = str_pad($rowListadoDiabetes['creatininasericavalor'],5,0,STR_PAD_LEFT);
		$creatininasericafecha = $rowListadoDiabetes['creatininasericafecha'];
		if ($creatininasericafecha == NULL) {
			$creatininasericafecha = "00000000";
		} else {
			$creatininasericafecha = date_format(date_create($creatininasericafecha),"Ymd");
		}
		
		$fondodeojo = $rowListadoDiabetes['fondodeojo'];
		$fondodeojofecha = $rowListadoDiabetes['fondodeojofecha'];
		if ($fondodeojofecha == NULL) {
			$fondodeojofecha = "00000000";
		} else {
			$fondodeojofecha = date_format(date_create($fondodeojofecha),"Ymd");
		}
		
		$pesovalor = str_pad($rowListadoDiabetes['pesovalor'],6,0,STR_PAD_LEFT);
		$pesofecha = $rowListadoDiabetes['pesofecha'];
		if ($pesofecha == NULL) {
			$pesofecha = "00000000";
		} else {
			$pesofecha = date_format(date_create($pesofecha),"Ymd");
		}
		$tallavalor = str_pad($rowListadoDiabetes['tallavalor'],3,0,STR_PAD_LEFT); 
		$tallafecha = $rowListadoDiabetes['tallafecha'];
		if ($tallafecha == NULL) {
			$tallafecha = "00000000";
		} else {
			$tallafecha = date_format(date_create($tallafecha),"Ymd");
		}
		
		$cinturavalor = str_pad($rowListadoDiabetes['cinturavalor'],3,0,STR_PAD_LEFT);
		$cinturafecha = $rowListadoDiabetes['cinturafecha'];
		if ($cinturafecha == NULL) {
			$cinturafecha = "00000000";
		} else {
			$cinturafecha = date_format(date_create($cinturafecha),"Ymd");
		}
		
		$insulinabasalcodigo = str_pad($rowListadoDiabetes['insulinabasalcodigo'],3,0,STR_PAD_LEFT);
		$insulinacorreccioncodigo = str_pad($rowListadoDiabetes['insulinacorreccioncodigo'],3,0,STR_PAD_LEFT);
	
		$linea = $arrayCuiles[$rowListadoDiabetes['id']]."|".$rowListadoDiabetes['tipodiabetes']."|".$fechaRegistro."|".$edadDiag."|".
				 $rowListadoDiabetes['dislipemia']."|".$rowListadoDiabetes['obesidad']."|".$rowListadoDiabetes['tabaquismo']."|".
				 $hipertrofiaventricular."|".$infartomiocardio."|".$insuficienciacardiaca."|".$accidentecerebrovascular."|".$retinopatia."|".
				 $ceguera."|".$neuropatiaperiferica."|".$vasculopatiaperiferica."|".$amputacion."|".$nefropatia."|".$dialisis."|".$transplanterenal."|".
				 $glucemia."|".$glucemiafecha."|".$hba1cvalor."|".$hba1cfecha."|".$ldlcvalor."|".$ldlcfecha."|".
				 $trigliceridosvalor."|".$trigliceridosfecha."|".$microalbuminuriavalor."|".$microalbuminuriafecha."|".$tasistolicavalor."|".
				 $tasistolicafecha."|".$tadiastolicavalor."|".$tadiastolicafecha."|".$creatininasericavalor."|".$creatininasericafecha."|".
				 $fondodeojo."|".$fondodeojofecha."|".$pesovalor."|".$pesofecha."|".$tallavalor."|".$tallafecha."|".$cinturavalor."|".$cinturafecha."|".
				 $rowListadoDiabetes['automonitoreoglucemico']."|".$rowListadoDiabetes['actividadfisica']."|".
				 $rowListadoDiabetes['cumpletratamiento']."|".$rowListadoDiabetes['farmacosantihipertensivos']."|".
				 $rowListadoDiabetes['farmacoshipolipemiantes']."|".$rowListadoDiabetes['acidoacetilsalicilico']."|".
				 $rowListadoDiabetes['hipoglucemiantesorales']."|".$insulinabasalcodigo."|".$insulinacorreccioncodigo;
		//echo $linea."<br>";
		fwrite($file, $linea . PHP_EOL);
	}
	fclose($file);
} else {
	$error = "Linea: No se pudo crear el archivo de migracion de diabetes";
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	Header($redire);
	exit(0);
}

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//echo $insertPresentacion."<br><br>";
	$dbh->exec($insertPresentacion);

	$dbh->commit();
	$redire = "moduloPresSSS.php";
	Header("Location: $redire");
} catch (PDOException $e) {
	if (file_exists($archivoImportacion)) {
		unlink($archivoImportacion);
	}
	$error = "Cod. Error: ".$e->getCode()." - Linea: ".$e->getLine();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	Header($redire);
	exit(0);
}

?>
