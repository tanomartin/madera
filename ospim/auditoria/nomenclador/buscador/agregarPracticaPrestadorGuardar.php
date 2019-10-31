<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];

$idpractica = $_GET['idpractica'];
$idPrestador = $_POST['codigoPresta'];
$idcontrato = $_POST['contrato'];
$categoria = $_POST['categoria'];
$selectTipo = $_POST['tipoCarga'];
$coseguro = $_POST['coseguro'];
if($coseguro == '') { $coseguro = 0; }

if ($selectTipo == 1) {
	$moduloConsultorio = $_POST["moduloConultorio"];
	if($moduloConsultorio == '') { $moduloConsultorio = 0; }
	$moduloUrgencia = $_POST["moduloUrgencia"];
	if($moduloUrgencia == '') { $moduloUrgencia = 0; }
	$sqlInsert = "INSERT INTO detcontratoprestador VALUES
					($idcontrato,$idpractica,$categoria,$moduloConsultorio,$moduloUrgencia,0.00,0.00,0.00,0.00,0.0,$coseguro,'$fecharegistro','$usuarioregistro')";
} else {
	$gHono = $_POST["gHono"];
	if($gHono == '') { $gHono = 0; }
	$gHonoEspe = $_POST["gHonoEspe"];
	if($gHonoEspe == '') { $gHonoEspe = 0; }
	$gHonoAyud = $_POST["gHonoAyud"];
	if($gHonoAyud == '') { $gHonoAyud = 0; }
	$gHonoAnes = $_POST["gHonoAnes"];
	if($gHonoAnes == '') { $gHonoAnes = 0; }
	$gGastos = $_POST["gGastos"];
	if($gGastos == '') { $gGastos = 0; }
	$sqlInsert = "INSERT INTO detcontratoprestador VALUES
						($idcontrato,$idpractica,$categoria,0.00,0.00,$gHono,$gHonoEspe,$gHonoAyud,$gHonoAnes,$gGastos,$coseguro,'$fecharegistro','$usuarioregistro')";
}

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	
	//print($sqlInsert."<br>");
	$dbh->exec($sqlInsert);

	$dbh->commit();
	$pagina = "detallePracticasPresta.php?idpractica=$idpractica";
	Header("Location: $pagina"); 
} catch (PDOException $e) {
	$dbh->rollback();
	$error = $e->getMessage();
	if (stripos($error,'Integrity constraint violation') !== FALSE ) {
		$pagina = "agregarPracticaPrestador.php?idpractica=$idpractica&error=1";
		Header("Location: $pagina"); 
	} else {
		echo $error;
	}
}

?>