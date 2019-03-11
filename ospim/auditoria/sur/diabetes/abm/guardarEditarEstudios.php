<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
$fechamodificacion = date("Y-m-d H:i:s");
$usuariomodificacion = $_SESSION['usuario'];
if(isset($_POST)) {
	//var_dump($_POST);
	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();

		$sqlActualizaEstudios = "UPDATE diabetesestudios SET glucemiavalor = :glucemiavalor, glucemiafecha = :glucemiafecha, hba1cvalor = :hba1cvalor, hba1cfecha = :hba1cfecha, ldlcvalor = :ldlcvalor, ldlcfecha = :ldlcfecha, trigliceridosvalor = :trigliceridosvalor, trigliceridosfecha = :trigliceridosfecha, microalbuminuriavalor = :microalbuminuriavalor, microalbuminuriafecha = :microalbuminuriafecha, tasistolicavalor = :tasistolicavalor, tasistolicafecha = :tasistolicafecha, tadiastolicavalor = :tadiastolicavalor, tadiastolicafecha = :tadiastolicafecha, creatininasericavalor = :creatininasericavalor, creatininasericafecha = :creatininasericafecha, indicealbuminacreatinina = :indicealbuminacreatinina, fondodeojo = :fondodeojo, fondodeojofecha  = :fondodeojofecha, fondodeojotipo = :fondodeojotipo, pesovalor = :pesovalor, pesofecha = :pesofecha, tallavalor = :tallavalor, tallafecha = :tallafecha, imcvalor = :imcvalor, imcfecha = :imcfecha, cinturavalor = :cinturavalor, cinturafecha = :cinturafecha, examendepie = :examendepie, examendepiefecha = :examendepiefecha, examendepietipo = :examendepietipo, fechamodificacion = :fechamodificacion, usuariomodificacion  = :usuariomodificacion WHERE idDiagnostico = :idDiagnostico";
		$resActualizaEstudios = $dbh->prepare($sqlActualizaEstudios);
		if($resActualizaEstudios->execute(array(':idDiagnostico' => $_POST['iddiagnostico'],':glucemiavalor' => $_POST['glucemiavalor'],':glucemiafecha' => fechaParaGuardarNula($_POST['glucemiafecha']),':hba1cvalor' => $_POST['hba1cvalor'],':hba1cfecha' => fechaParaGuardarNula($_POST['hba1cfecha']),':ldlcvalor' => $_POST['ldlcvalor'],':ldlcfecha' => fechaParaGuardarNula($_POST['ldlcfecha']),':trigliceridosvalor' => $_POST['trigliceridosvalor'],':trigliceridosfecha' => fechaParaGuardarNula($_POST['trigliceridosfecha']),':microalbuminuriavalor' => $_POST['microalbuminuriavalor'],':microalbuminuriafecha' => fechaParaGuardarNula($_POST['microalbuminuriafecha']),':tasistolicavalor' => $_POST['tasistolicavalor'],':tasistolicafecha' => fechaParaGuardarNula($_POST['tasistolicafecha']),':tadiastolicavalor' => $_POST['tadiastolicavalor'],':tadiastolicafecha' => fechaParaGuardarNula($_POST['tadiastolicafecha']),':creatininasericavalor' => $_POST['creatininasericavalor'],':creatininasericafecha' => fechaParaGuardarNula($_POST['creatininasericafecha']),':indicealbuminacreatinina' => $_POST['indicealbuminacreatinina'],':fondodeojo' => $_POST['fondodeojo'],':fondodeojofecha' => fechaParaGuardarNula($_POST['fondodeojofecha']),':fondodeojotipo' => $_POST['fondodeojotipo'],':pesovalor' => $_POST['pesovalor'],':pesofecha' => fechaParaGuardarNula($_POST['pesofecha']),':tallavalor' => $_POST['tallavalor'],':tallafecha' => fechaParaGuardarNula($_POST['tallafecha']),':imcvalor' => $_POST['imcvalor'],':imcfecha' => fechaParaGuardarNula($_POST['imcfecha']),':cinturavalor' => $_POST['cinturavalor'],':cinturafecha' => fechaParaGuardarNula($_POST['cinturafecha']),':examendepie' => $_POST['examendepie'],':examendepiefecha' => fechaParaGuardarNula($_POST['examendepiefecha']),':examendepietipo' => $_POST['examendepietipo'],':fechamodificacion' => $fechamodificacion,':usuariomodificacion' => $usuariomodificacion)))
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
