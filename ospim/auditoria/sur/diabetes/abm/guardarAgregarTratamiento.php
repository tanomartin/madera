<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];
if(isset($_POST)) {
	//var_dump($_POST);
	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();

		$sqlAgregaTratamiento = "INSERT INTO diabetestratamientos (idDiagnostico,alimentacionsaludable,actividadfisica,educaciondiabetologica,cumpletratamiento,automonitoreoglucemico,farmacosantihipertensivos,farmacoshipolipemiantes,acidoacetilsalicilico,hipoglucemiantesorales,fecharegistro,usuarioregistro,fechamodificacion,usuariomodificacion) VALUES(:idDiagnostico,:alimentacionsaludable,:actividadfisica,:educaciondiabetologica,:cumpletratamiento,:automonitoreoglucemico,:farmacosantihipertensivos,:farmacoshipolipemiantes,:acidoacetilsalicilico,:hipoglucemiantesorales,:fecharegistro,:usuarioregistro,:fechamodificacion,:usuariomodificacion)";
		$resAgregaTratamiento = $dbh->prepare($sqlAgregaTratamiento);
		if($resAgregaTratamiento->execute(array(':idDiagnostico' => $_POST['iddiagnostico'],':alimentacionsaludable' => $_POST['alimentacionsaludable'],':actividadfisica' => $_POST['actividadfisica'],':educaciondiabetologica' => $_POST['educaciondiabetologica'],':cumpletratamiento' => $_POST['cumpletratamiento'],':automonitoreoglucemico' => $_POST['automonitoreoglucemico'],':farmacosantihipertensivos' => $_POST['farmacosantihipertensivos'],':farmacoshipolipemiantes' => $_POST['farmacoshipolipemiantes'],':acidoacetilsalicilico' => $_POST['acidoacetilsalicilico'],':hipoglucemiantesorales' => $_POST['hipoglucemiantesorales'],':fecharegistro' => $fecharegistro,':usuarioregistro' => $usuarioregistro,':fechamodificacion' => NULL,':usuariomodificacion' => NULL)))
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
