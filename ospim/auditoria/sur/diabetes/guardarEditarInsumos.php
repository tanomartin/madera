<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
$fechamodificacion = date("Y-m-d H:i:s");
$usuariomodificacion = $_SESSION['usuario'];
$tirasreactivas = 0;
$tirasreactivaspresentacion = NULL;
$tirasreactivasdosis = NULL;
$tirasreactivasinicio = 0;
$lancetas = 0;
$lancetaspresentacion = NULL;
$lancetasdosis = NULL;
$lancetasinicio = 0;
$agujas = 0;
$agujaspresentacion = NULL;
$agujasdosis = NULL;
$agujasinicio = 0;
$jeringas = 0;
$jeringaspresentacion = NULL;
$jeringasdosis = NULL;
$jeringasinicio = 0;

if(isset($_POST)) {
	//var_dump($_POST);
	if(isset($_POST['tirasreactivas'])) {
		if(strcmp($_POST['tirasreactivas'],"on")==0) {
			$tirasreactivas=1;
			$tirasreactivaspresentacion=$_POST['tirasreactivaspresentacion'];
			$tirasreactivasdosis=$_POST['tirasreactivasdosis'];
			$tirasreactivasinicio=$_POST['tirasreactivasinicio'];
		}
	}
	if(isset($_POST['lancetas'])) {
		if(strcmp($_POST['lancetas'],"on")==0) {	
			$lancetas=1;
			$lancetaspresentacion=$_POST['lancetaspresentacion'];
			$lancetasdosis=$_POST['lancetasdosis'];
			$lancetasinicio=$_POST['lancetasinicio'];
		}
	}	
	if(isset($_POST['agujas'])) {
		if(strcmp($_POST['agujas'],"on")==0) {	
			$agujas=1;
			$agujaspresentacion=$_POST['agujaspresentacion'];
			$agujasdosis=$_POST['agujasdosis'];
			$agujasinicio=$_POST['agujasinicio'];
		}
	}
	if(isset($_POST['jeringas'])) {
		if(strcmp($_POST['jeringas'],"on")==0) {	
			$jeringas=1;
			$jeringaspresentacion=$_POST['jeringaspresentacion'];
			$jeringasdosis=$_POST['jeringasdosis'];
			$jeringasinicio=$_POST['jeringasinicio'];
		}
	}
	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();

		$sqlActualizaInsumos = "UPDATE diabetesinsumos SET 
tirasreactivas = :tirasreactivas,
tirasreactivaspresentacion = :tirasreactivaspresentacion,
tirasreactivasdosis = :tirasreactivasdosis,
tirasreactivasinicio = :tirasreactivasinicio,
lancetas = :lancetas,
lancetaspresentacion = :lancetaspresentacion,
lancetasdosis = :lancetasdosis,
lancetasinicio = :lancetasinicio,
agujas = :agujas,
agujaspresentacion = :agujaspresentacion,
agujasdosis = :agujasdosis,
agujasinicio = :agujasinicio,
jeringas = :jeringas,
jeringaspresentacion = :jeringaspresentacion,
jeringasdosis = :jeringasdosis,
jeringasinicio = :jeringasinicio,
fechamodificacion = :fechamodificacion,
usuariomodificacion = :usuariomodificacion
WHERE idDiagnostico = :idDiagnostico";
		$resActualizaInsumos = $dbh->prepare($sqlActualizaInsumos);
		if($resActualizaInsumos->execute(array(':idDiagnostico' => $_POST['iddiagnostico'],
':tirasreactivas' => $tirasreactivas,
':tirasreactivaspresentacion' => $tirasreactivaspresentacion,
':tirasreactivasdosis' => $tirasreactivasdosis,
':tirasreactivasinicio' => $tirasreactivasinicio,
':lancetas' => $lancetas,
':lancetaspresentacion' => $lancetaspresentacion,
':lancetasdosis' => $lancetasdosis,
':lancetasinicio' => $lancetasinicio,
':agujas' => $agujas,
':agujaspresentacion' => $agujaspresentacion,
':agujasdosis' => $agujasdosis,
':agujasinicio' => $agujasinicio,
':jeringas' => $jeringas,
':jeringaspresentacion' => $jeringaspresentacion,
':jeringasdosis' => $jeringasdosis,
':jeringasinicio' => $jeringasinicio,
':fechamodificacion' => $fechamodificacion,
':usuariomodificacion' => $usuariomodificacion)))
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
