<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php");

$porcentaje = ($_POST['porcentaje'] / 100) + 1;
$codigopresta = $_GET['codigo'];
$idcontrato = $_GET['idcontrato'];
$fechaInicio = fechaParaGuardar($_POST['fechaInicio']);
$fechaFin = fechaParaGuardar($_POST['fechaFin']);
$idcontrato = $_GET['idcontrato'];
$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];
$fechamodificacion = $fecharegistro;
$usuariomodificacion = $usuarioregistro;

$sqlCabContratoFin = "SELECT c.* FROM cabcontratoprestador c  WHERE c.codigoprestador = $codigopresta and c.fechafin >= '$fechaInicio'";
$resCabContratoFin = mysql_query($sqlCabContratoFin,$db);
$numCabContratoFin = mysql_num_rows($resCabContratoFin);
if ($numCabContratoFin > 0) {
	$pagina = "aumentoPorcentaje.php?idcontrato=$idcontrato&codigo=$codigopresta&err=1&fi=".$_POST['fechaInicio']."&ff=".$_POST['fechaFin'];
	Header("Location: $pagina");
	exit(0);
} else {
	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();
		
		$sqlInsertCabecera = "INSERT INTO cabcontratoprestador VALUES(DEFAULT,'$codigopresta','$fechaInicio','$fechaFin','$fecharegistro','$usuarioregistro','$fechamodificacion','$usuariomodificacion')";
		//echo $sqlInsertCabecera."<br>";
		$dbh->exec($sqlInsertCabecera);
		$idNuevoContrato = $dbh->lastInsertId();
		
		$sqlContrato = "SELECT * FROM detcontratoprestador WHERE idcontrato = $idcontrato";
		$resContrato = mysql_query($sqlContrato,$db);	
		while($rowContrato = mysql_fetch_array($resContrato)) { 
			$sqlInsertDetalle = "INSERT INTO detcontratoprestador VALUES($idNuevoContrato,
									".$rowContrato['idpractica'].",
									".$rowContrato['idcategoria'].",
									ROUND (".$rowContrato['moduloconsultorio']." * $porcentaje , 2),
									ROUND (".$rowContrato['modulourgencia']." * $porcentaje , 2),
									ROUND (".$rowContrato['galenohonorario']." * $porcentaje , 2),
									ROUND (".$rowContrato['galenohonorarioespecialista']." * $porcentaje , 2),
									ROUND (".$rowContrato['galenohonorarioayudante']." * $porcentaje , 2),
									ROUND (".$rowContrato['galenohonorarioanestesista']." * $porcentaje , 2),
									ROUND (".$rowContrato['galenogastos']." * $porcentaje , 2),
									'$fecharegistro',
									'$usuariomodificacion')";
			//echo $sqlInsertDetalle."<br>";
			$dbh->exec($sqlInsertDetalle);
		}
		
		$dbh->commit();
		$pagina = "contratosPrestador.php?codigo=$codigopresta";
		Header("Location: $pagina");
	} catch (PDOException $e) {
		echo $e->getMessage();
		$dbh->rollback();
	}
}

?>