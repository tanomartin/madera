<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
include($_SERVER['DOCUMENT_ROOT']."/madera/lib/fechas.php");

$codigopresta = $_GET['codigo'];
$fechaInicio = fechaParaGuardar($_POST['fechaInicio']);
$fechaFin = fechaParaGuardar($_POST['fechaFin']);
$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];
$fechamodificacion = $fecharegistro;
$usuariomodificacion = $usuarioregistro;

$sqlCabContratoFin = "SELECT c.* FROM cabcontratoprestador c  WHERE c.codigoprestador = $codigopresta and c.fechafin >= '$fechaInicio'";
$resCabContratoFin = mysql_query($sqlCabContratoFin,$db);
$numCabContratoFin = mysql_num_rows($resCabContratoFin);
if ($numCabContratoFin > 0) {
	$pagina = "nuevoContrato.php?codigo=$codigopresta&err=1&fi=".$_POST['fechaInicio']."&ff=".$_POST['fechaFin'];
	Header("Location: $pagina"); 
	exit(0);
} else {
	$sqlInsertProf = "INSERT INTO cabcontratoprestador VALUES(DEFAULT,'$codigopresta','$fechaInicio','$fechaFin','$fecharegistro','$usuarioregistro','$fechamodificacion','$usuariomodificacion')";
	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();
	
		//print($sqlInsertProf."<br>");
		$dbh->exec($sqlInsertProf);
		
		$dbh->commit();
		$pagina = "contratosPrestador.php?codigo=$codigopresta";
		Header("Location: $pagina"); 
	} catch (PDOException $e) {
		echo $e->getMessage();
		$dbh->rollback();
	}
}
?>
