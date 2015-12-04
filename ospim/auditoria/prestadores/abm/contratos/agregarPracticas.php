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
			$codigo = $arrayKey[1];
			if ($value == 1) {
				$id = "moduloConultorio-".$codigo;
				$moduloConsultorio = $_POST[$id];
				$id = "moduloUrgencia-".$codigo;
				$moduloUrgencia = $_POST[$id];
				$codigo = str_replace("_",".",$codigo);
				$arrayInsert[$i] = "INSERT INTO detcontratoprestador VALUES
									($idcontrato,'$codigo',$nomenclador,$moduloConsultorio,$moduloUrgencia,'NULL','NULL','NULL','NULL','NULL','$fecharegistro','$usuarioregistro')";
				$i++;
			} else {
				$id = "gHono-".$codigo;
				$gHono = $_POST[$id];
				$id = "gHonoEspe-".$codigo;
				$gHonoEspe = $_POST[$id];
				$id = "gHonoAyud-".$codigo;
				$gHonoAyud = $_POST[$id];
				$id = "gHonoAnes-".$codigo;
				$gHonoAnes = $_POST[$id];
				$id = "gGastos-".$codigo;
				$gGastos = $_POST[$id];
				$codigo = str_replace("_",".",$codigo);
				$arrayInsert[$i] = "INSERT INTO detcontratoprestador VALUES
									($idcontrato,'$codigo',$nomenclador,'NULL','NULL',$gHono,$gHonoEspe,$gHonoAyud,$gHonoAnes,$gGastos,'$fecharegistro','$usuarioregistro')";
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