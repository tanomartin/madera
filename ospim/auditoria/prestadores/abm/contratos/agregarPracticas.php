<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];

$idPrestador = $_GET['codigo'];
$idcontrato = $_GET['idcontrato'];
$i=0;
$arranca = 0;

$selectTipo = $_POST['tipo'];
$arrayTipo = explode("-",$selectTipo);
$nomenclador = $arrayTipo[1];
foreach($_POST as $key => $value) {
	$tipoCarga = strpos($key, "tipoCarga");
	if($tipoCarga !== FALSE) {
		if ($value != 0) {
			$arrayKey = explode("-",$key);
			$idpractica = $arrayKey[1];
			$id = "categoria-".$idpractica;
			$idCategoria = $_POST[$id];
			if ($value == 1) {
				$id = "moduloConultorio-".$idpractica;
				$moduloConsultorio = $_POST[$id];
				if($moduloConsultorio == '') { $moduloConsultorio = 0; }
				$id = "moduloUrgencia-".$idpractica;
				$moduloUrgencia = $_POST[$id];
				if($moduloUrgencia == '') { $moduloUrgencia = 0; }
				$arrayInsert[$i] = "INSERT INTO detcontratoprestador VALUES
									($idcontrato,$idpractica,$idCategoria,$moduloConsultorio,$moduloUrgencia,0,0,0,0,0,'$fecharegistro','$usuarioregistro')";
				$i++;
			} else {
				$id = "gHono-".$idpractica;
				$gHono = $_POST[$id];
				if($gHono == '') { $gHono = 0; }
				$id = "gHonoEspe-".$idpractica;
				$gHonoEspe = $_POST[$id];
				if($gHonoEspe == '') { $gHonoEspe = 0; }
				$id = "gHonoAyud-".$idpractica;
				$gHonoAyud = $_POST[$id];
				if($gHonoAyud == '') { $gHonoAyud = 0; }
				$id = "gHonoAnes-".$idpractica;
				$gHonoAnes = $_POST[$id];
				if($gHonoAnes == '') { $gHonoAnes = 0; }
				$id = "gGastos-".$idpractica;
				$gGastos = $_POST[$id];
				if($gGastos == '') { $gGastos = 0; }
				$arrayInsert[$i] = "INSERT INTO detcontratoprestador VALUES
									($idcontrato,$idpractica,$idCategoria,0,0,$gHono,$gHonoEspe,$gHonoAyud,$gHonoAnes,$gGastos,'$fecharegistro','$usuarioregistro')";
				$i++;	
			}
		}
	}
}

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	foreach ($arrayInsert as $sqlInsert) {
		//print($sqlInsert."<br>");
		$dbh->exec($sqlInsert);
	}
	$dbh->commit();
	$pagina = "modificarPracticasContrato.php?codigo=$idPrestador&idcontrato=$idcontrato";
	Header("Location: $pagina"); 
} catch (PDOException $e) {
	$dbh->rollback();
	$error = $e->getMessage();
	if (stripos($error,'Integrity constraint violation') !== FALSE ) {
		$pagina = "modificarPracticasContrato.php?codigo=$idPrestador&idcontrato=$idcontrato&error=1";
		Header("Location: $pagina"); 
	} else {
		echo $error;
	}
}

?>