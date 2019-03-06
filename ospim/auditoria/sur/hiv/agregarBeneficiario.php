<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
$nroafiliado = NULL;
$nroorden = NULL;
$existehiv = 0;
$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];
$fechamodificacion = NULL;
$usuariomodificacion = NULL;
if(isset($_GET['nroAfi'])) {
	$nroafiliado=$_GET['nroAfi'];
	if(isset($_GET['nroOrd'])) {
		$nroorden=$_GET['nroOrd'];

		$sqlLeeHiv = "SELECT nroafiliado, nroorden FROM hivbeneficiarios WHERE nroafiliado = $nroafiliado AND nroorden = $nroorden";
		$resLeeHiv = mysql_query($sqlLeeHiv,$db);
		if (mysql_num_rows($resLeeHiv)!=0) {
			$existehiv = 1;
		}
		
		if ($existehiv == 1) {
			header("Location: moduloHiv.php?err=4");
		} else {
			try {
				$hostname = $_SESSION['host'];
				$dbname = $_SESSION['dbname'];
				$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
				$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$dbh->beginTransaction();
						
				$sqlAgregaBeneficiario = "INSERT INTO hivbeneficiarios (nroafiliado,nroorden,fecharegistro,usuarioregistro,fechamodificacion,usuariomodificacion) VALUES (:nroafiliado,:nroorden,:fecharegistro,:usuarioregistro,:fechamodificacion,:usuariomodificacion)";
				$resAgregaBeneficiario = $dbh->prepare($sqlAgregaBeneficiario);
				if($resAgregaBeneficiario->execute(array(':nroafiliado' => $nroafiliado, ':nroorden' => $nroorden, ':fecharegistro' => $fecharegistro, ':usuarioregistro' => $usuarioregistro, ':fechamodificacion' => $fechamodificacion, ':usuariomodificacion' => $usuariomodificacion)))
				$dbh->commit();
				$pagina = "moduloHiv.php";
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