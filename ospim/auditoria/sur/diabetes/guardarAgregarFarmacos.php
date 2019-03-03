<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];
$metformina = 0;
$metforminapresentacion = NULL;
$metforminadosis = NULL;
$metforminainicio = 0;
$sulfonilureas = 0;
$sulfonilureasnombre = NULL;
$sulfonilureaspresentacion = NULL;
$sulfonilureasdosis = NULL;
$sulfonilureasinicio = 0;
$idpp4 = 0;
$idpp4nombre = NULL;
$idpp4presentacion = NULL;
$idpp4dosis = NULL;
$idpp4inicio = 0;
$insulinabasal = 0;
$insulinabasalcodigo = 0;
$insulinabasalpresentacion = NULL;
$insulinabasaldosis = NULL;
$insulinabasalinicio = 0;
$insulinacorreccion = 0;
$insulinacorreccioncodigo = 0;
$insulinacorreccionpresentacion = NULL;
$insulinacorrecciondosis = NULL;
$insulinacorreccioninicio = 0;
$otros1 = 0;
$otros1nombre = NULL;
$otros1presentacion = NULL;
$otros1dosis = NULL;
$otros1inicio = 0;
$otros2 = 0;
$otros2nombre = NULL;
$otros2presentacion = NULL;
$otros2dosis = NULL;
$otros2inicio = 0;

if(isset($_POST)) {
	//var_dump($_POST);
	if(isset($_POST['metformina'])) {
		if(strcmp($_POST['metformina'],"on")==0) {
			$metformina=1;
			$metforminapresentacion=$_POST['metforminapresentacion'];
			$metforminadosis=$_POST['metforminadosis'];
			$metforminainicio=$_POST['metforminainicio'];
		}
	}
	if(isset($_POST['sulfonilureas'])) {
		if(strcmp($_POST['sulfonilureas'],"on")==0) {	
			$sulfonilureas=1;
			$sulfonilureasnombre=$_POST['sulfonilureasnombre'];
			$sulfonilureaspresentacion=$_POST['sulfonilureaspresentacion'];
			$sulfonilureasdosis=$_POST['sulfonilureasdosis'];
			$sulfonilureasinicio=$_POST['sulfonilureasinicio'];
		}
	}	
	if(isset($_POST['idpp4'])) {
		if(strcmp($_POST['idpp4'],"on")==0) {	
			$idpp4=1;
			$idpp4nombre=$_POST['idpp4nombre'];
			$idpp4presentacion=$_POST['idpp4presentacion'];
			$idpp4dosis=$_POST['idpp4dosis'];
			$idpp4inicio=$_POST['idpp4inicio'];
		}
	}
	if(isset($_POST['insulinabasal'])) {
		if(strcmp($_POST['insulinabasal'],"on")==0) {	
			$insulinabasal=1;
			$insulinabasalcodigo=$_POST['insulinabasalcodigo'];
			$insulinabasalpresentacion=$_POST['insulinabasalpresentacion'];
			$insulinabasaldosis=$_POST['insulinabasaldosis'];
			$insulinabasalinicio=$_POST['insulinabasalinicio'];
		}
	}
	if(isset($_POST['insulinacorreccion'])) {
		if(strcmp($_POST['insulinacorreccion'],"on")==0) {	
			$insulinacorreccion=1;
			$insulinacorreccioncodigo=$_POST['insulinacorreccioncodigo'];
			$insulinacorreccionpresentacion=$_POST['insulinacorreccionpresentacion'];
			$insulinacorrecciondosis=$_POST['insulinacorrecciondosis'];
			$insulinacorreccioninicio=$_POST['insulinacorreccioninicio'];
		}
	}
	if(isset($_POST['otros1'])) {
		if(strcmp($_POST['otros1'],"on")==0) {	
			$otros1=1;
			$otros1nombre=$_POST['otros1nombre'];
			$otros1presentacion=$_POST['otros1presentacion'];
			$otros1dosis=$_POST['otros1dosis'];
			$otros1inicio=$_POST['otros1inicio'];
		}
	}
	if(isset($_POST['otros2'])) {
		if(strcmp($_POST['otros2'],"on")==0) {	
		}
			$otros2=1;
			$otros2nombre=$_POST['otros2nombre'];
			$otros2presentacion=$_POST['otros2presentacion'];
			$otros2dosis=$_POST['otros2dosis'];
			$otros2inicio=$_POST['otros2inicio'];
	}

	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();

		$sqlAgregaFarmacos = "INSERT INTO diabetesfarmacos (idDiagnostico,
metformina,
metforminapresentacion,
metforminadosis,
metforminainicio,
sulfonilureas,
sulfonilureasnombre,
sulfonilureaspresentacion,
sulfonilureasdosis,
sulfonilureasinicio,
idpp4,
idpp4nombre,
idpp4presentacion,
idpp4dosis,
idpp4inicio,
insulinabasal,
insulinabasalcodigo,
insulinabasalpresentacion,
insulinabasaldosis,
insulinabasalinicio,
insulinacorreccion,
insulinacorreccioncodigo,
insulinacorreccionpresentacion,
insulinacorrecciondosis,
insulinacorreccioninicio,
otros1,
otros1nombre,
otros1presentacion,
otros1dosis,
otros1inicio,
otros2,
otros2nombre,
otros2presentacion,
otros2dosis,
otros2inicio,
fecharegistro,
usuarioregistro,
fechamodificacion,
usuariomodificacion) VALUES(:idDiagnostico,
:metformina,
:metforminapresentacion,
:metforminadosis,
:metforminainicio,
:sulfonilureas,
:sulfonilureasnombre,
:sulfonilureaspresentacion,
:sulfonilureasdosis,
:sulfonilureasinicio,
:idpp4,
:idpp4nombre,
:idpp4presentacion,
:idpp4dosis,
:idpp4inicio,
:insulinabasal,
:insulinabasalcodigo,
:insulinabasalpresentacion,
:insulinabasaldosis,
:insulinabasalinicio,
:insulinacorreccion,
:insulinacorreccioncodigo,
:insulinacorreccionpresentacion,
:insulinacorrecciondosis,
:insulinacorreccioninicio,
:otros1,
:otros1nombre,
:otros1presentacion,
:otros1dosis,
:otros1inicio,
:otros2,
:otros2nombre,
:otros2presentacion,
:otros2dosis,
:otros2inicio,
:fecharegistro,
:usuarioregistro,
:fechamodificacion,
:usuariomodificacion)";
		$resAgregaFarmacos = $dbh->prepare($sqlAgregaFarmacos);
		if($resAgregaFarmacos->execute(array(':idDiagnostico' => $_POST['iddiagnostico'],
':metformina' => $metformina,
':metforminapresentacion' => $metforminapresentacion,
':metforminadosis' => $metforminadosis,
':metforminainicio' => $metforminainicio,
':sulfonilureas' => $sulfonilureas,
':sulfonilureasnombre' => $sulfonilureasnombre,
':sulfonilureaspresentacion' => $sulfonilureaspresentacion,
':sulfonilureasdosis' => $sulfonilureasdosis,
':sulfonilureasinicio' => $sulfonilureasinicio,
':idpp4' => $idpp4,
':idpp4nombre' => $idpp4nombre,
':idpp4presentacion' => $idpp4presentacion,
':idpp4dosis' => $idpp4dosis,
':idpp4inicio' => $idpp4inicio,
':insulinabasal' => $insulinabasal,
':insulinabasalcodigo' => $insulinabasalcodigo,
':insulinabasalpresentacion' => $insulinabasalpresentacion,
':insulinabasaldosis' => $insulinabasaldosis,
':insulinabasalinicio' => $insulinabasalinicio,
':insulinacorreccion' => $insulinacorreccion,
':insulinacorreccioncodigo' => $insulinacorreccioncodigo,
':insulinacorreccionpresentacion' => $insulinacorreccionpresentacion,
':insulinacorrecciondosis' => $insulinacorrecciondosis,
':insulinacorreccioninicio' => $insulinacorreccioninicio,
':otros1' => $otros1,
':otros1nombre' => $otros1nombre,
':otros1presentacion' => $otros1presentacion,
':otros1dosis' => $otros1dosis,
':otros1inicio' => $otros1inicio,
':otros2' => $otros2,
':otros2nombre' => $otros2nombre,
':otros2presentacion' => $otros2presentacion,
':otros2dosis' => $otros2dosis,
':otros2inicio' => $otros2inicio,
':fecharegistro' => $fecharegistro,
':usuarioregistro' => $usuarioregistro,
':fechamodificacion' => NULL,
':usuariomodificacion' => NULL)))
		$dbh->commit();
		$pagina ="listarDiagnosticos.php?nroAfi=$_POST[nroafiliado]&nroOrd=$_POST[nroorden]&estAfi=$_POST[estafiliado]";
		header("Location: $pagina");
	}
	catch (PDOException $e) {
		$error = $e->getMessage();
		$dbh->rollback();
		$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?&error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
		header($redire);
		exit(0);
	}
}
?>
