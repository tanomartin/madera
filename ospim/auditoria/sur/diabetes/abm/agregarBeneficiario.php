<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
$nroafiliado = NULL;
$nroorden = NULL;
$existediabetico = 0;
$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];
if(isset($_GET['nroAfi'])) {
	$nroafiliado=$_GET['nroAfi'];
	if(isset($_GET['nroOrd'])) {
		$nroorden=$_GET['nroOrd'];

		$sqlLeeDiabetico = "SELECT nroafiliado, nroorden FROM diabetesbeneficiarios WHERE nroafiliado = $nroafiliado AND nroorden = $nroorden";
		$resLeeDiabetico = mysql_query($sqlLeeDiabetico,$db);
		if (mysql_num_rows($resLeeDiabetico)!=0) {
			$existediabetico = 1;
		}
		
		if ($existediabetico == 1) {
			header("Location: moduloDiabetes.php?err=4");
		} else {
			try {
				$hostname = $_SESSION['host'];
				$dbname = $_SESSION['dbname'];
				$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
				$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$dbh->beginTransaction();
						
				$sqlAgregaBeneficiario = "INSERT INTO diabetesbeneficiarios (nroafiliado,nroorden,diagnosticos, fechadiagnostico, edaddiagnostico,fecharegistro,usuarioregistro,fechamodificacion,usuariomodificacion) VALUES (:nroafiliado,:nroorden,:diagnosticos,:fechadiagnostico, :edaddiagnostico, :fecharegistro,:usuarioregistro,:fechamodificacion,:usuariomodificacion)";
				$resAgregaBeneficiario = $dbh->prepare($sqlAgregaBeneficiario);
				if($resAgregaBeneficiario->execute(array(':nroafiliado' => $nroafiliado, ':nroorden' => $nroorden, ':diagnosticos' => 0, ':fechadiagnostico' => NULL, ':edaddiagnostico' => NULL, ':fecharegistro' => $fecharegistro, ':usuarioregistro' => $usuarioregistro, ':fechamodificacion' => NULL, ':usuariomodificacion' => NULL)))
				$dbh->commit();
				$pagina = "moduloDiabetes.php";
				header("Location: $pagina");
			} catch (PDOException $e) {
				$error = $e->getMessage();
				$dbh->rollback();
				$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?&error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
				header($redire);
				exit(0);
			}
		}
	}
}
?>