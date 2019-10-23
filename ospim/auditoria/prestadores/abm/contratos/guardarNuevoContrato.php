<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
include($_SERVER['DOCUMENT_ROOT']."/madera/lib/fechas.php");

var_dump($_POST);echo "<br>";

$codigopresta = $_GET['codigo'];
$fechaInicio = fechaParaGuardar($_POST['fechaInicio']);
if ($_POST['fechaFin'] != "") {
	$fechaFin = fechaParaGuardar($_POST['fechaFin']);
	$fechaFin = "'$fechaFin'";
} else {
	$fechaFin = "NULL";
}
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
	$contratoTercero = 0;
	if ($_POST['relacion'] != 0) {
		$contratoTercero = $_POST['contratoTercero'];
		$sqlNomencla = "SELECT DISTINCT nomenclador FROM practicas p, detcontratoprestador d WHERE d.idcontrato = $contratoTercero and d.idpractica = p.idpractica";
		$resNomencla = mysql_query($sqlNomencla,$db);
		while($rowNomencla = mysql_fetch_array($resNomencla)) {
			$sqlExistePrestaNomencla = "SELECT * FROM prestadornomenclador WHERE codigoprestador = $codigopresta AND codigonomenclador = ".$rowNomencla['nomenclador'];
			$resExistePrestaNomencla = mysql_query($sqlExistePrestaNomencla,$db);
			$numExistePrestaNomencla = mysql_num_rows($resExistePrestaNomencla);
			if ($numExistePrestaNomencla == 0) {
				$pagina = "nuevoContrato.php?codigo=$codigopresta&err=2";
				Header("Location: $pagina");
				exit(0);
			}
		}
	} 
	$sqlInsertCab = "INSERT INTO cabcontratoprestador VALUES(DEFAULT,$codigopresta,'$fechaInicio',$fechaFin,$contratoTercero,'$fecharegistro','$usuarioregistro','$fechamodificacion','$usuariomodificacion')";
	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();
	
		//echo $sqlInsertCab."<br>";
		$dbh->exec($sqlInsertCab);
		
		$dbh->commit();
		$pagina = "contratosPrestador.php?codigo=$codigopresta";
		Header("Location: $pagina"); 
	} catch (PDOException $e) {
		$error = "Cod. Error: ".$e->getCode()." - Linea: ".$e->getLine();
		$dbh->rollback();
		$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
		Header($redire);
		exit(0);
	}
}
?>
