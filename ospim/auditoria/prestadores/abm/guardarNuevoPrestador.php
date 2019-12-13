<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
include($_SERVER['DOCUMENT_ROOT']."/madera/lib/fechas.php");

$nombre = addslashes($_POST['nombre']);
$domicilio = strtoupper(addslashes($_POST['domicilio']));
$indpostal = $_POST['indpostal'];
$codPos = $_POST['codPos'];

$alfapostal = $_POST['alfapostal'];
if ($alfapostal == "") {
	$alfapostal = "NULL";
} else {
	$alfapostal = "'$alfapostal'";
}

$localidad = $_POST['selectLocali'];
$codProvin = $_POST['codprovin'];

$idBarrio = 0;
if (isset($_POST['selectBarrio'])) {
	$idBarrio = $_POST['selectBarrio'];
}

$ddn1 = $_POST['ddn1'];
if ($ddn1 == "") {
	$ddn1 = "NULL";
} else {
	$ddn1 = "'$ddn1'";
}

$tel1 = $_POST['telefono1'];
if ($tel1 == "") {
	$tel1 = "NULL";
} else {
	$tel1 = "'$tel1'";
}

$ddn2 = $_POST['ddn2'];
if ($ddn2 == "") {
	$ddn2 = "NULL";
} else {
	$ddn2 = "'$ddn2'";
}

$tel2 = $_POST['telefono2'];
if ($tel2 == "") {
	$tel2 = "NULL";
} else {
	$tel2 = "'$tel2'";
}

$ddnfax = $_POST['ddnfax'];
if ($ddnfax == "") {
	$ddnfax = "NULL";
} else {
	$ddnfax = "'$ddnfax'";
}

$telfax = $_POST['telefonofax'];
if ($telfax == "") {
	$telfax = "NULL";
} else {
	$telfax = "'$telfax'";
}

$email1 = $_POST['email1'];
if ($email1 == "") {
	$email1 = "NULL";
} else {
	$email1 = "'$email1'";
}

$email2 = $_POST['email2'];
if ($email2 == "") {
	$email2 = "NULL";
} else {
	$email2 = "'$email2'";
}

$cuit = $_POST['cuit'];
$sitfiscal = $_POST['sitfiscal'];

$vtoexento = "NULL";
if (isset($_POST['vtoExento'])) {
	$vtoexento = fechaParaGuardar($_POST['vtoExento']);
	$vtoexento = "'$vtoexento'";
}

$personeria = $_POST['selectPersoneria'];

$tratamiento = "NULL";
if (isset($_POST['selectTratamiento'])) {
	$tratamiento = $_POST['selectTratamiento'];
}

$matriculaNac = "NULL";
if (isset($_POST['matriculaNac'])) {
	if ($_POST['matriculaNac'] != "") {
		$matriculaNac = $_POST['matriculaNac'];
		$matriculaNac = "'$matriculaNac'";
	}
}

$matriculaPro = "NULL";
if (isset($_POST['matriculaPro'])) {
	if ($_POST['matriculaPro'] != "") {
		$matriculaPro = $_POST['matriculaPro'];
		$matriculaPro = "'$matriculaPro'";
	}
}

$nroRegistroSSS = $_POST['nroSSS'];
if ($nroRegistroSSS == '') {
	$nroRegistroSSS = "NULL";
	$vtoRegistroSSS = "NULL";
} else {
	$nroRegistroSSS = "'$nroRegistroSSS'";
	$vtoRegistroSSS = fechaParaGuardar($_POST['vtoSSS']);
	$vtoRegistroSSS = "'$vtoRegistroSSS'";
}

$nroRegistroSNR = $_POST['nroSNR'];
if ($nroRegistroSNR == '') {
	$nroRegistroSNR = "NULL";
	$vtoRegistroSNR = "NULL";
} else {
	$nroRegistroSNR = "'$nroRegistroSNR'";
	$vtoRegistroSNR = fechaParaGuardar($_POST['vtoSNR']);
	$vtoRegistroSNR = "'$vtoRegistroSNR'";
}
$pertenencia = $_POST['pertenencia'];
$capitado = $_POST['capitado'];
$fijo = $_POST['fijo'];
$obs = strtoupper(addslashes($_POST['observacion']));
$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];
$fechamodificacion = $fecharegistro;
$usuariomodificacion = $usuarioregistro;

$sqlInsertPresta = "INSERT INTO prestadores VALUES(DEFAULT,'$nombre','$domicilio','$localidad','$idBarrio','$codProvin','$indpostal','$codPos',$alfapostal,$tel1,$ddn1,$tel2,$ddn2,$telfax,$ddnfax,$email1,$email2,'$cuit',$sitfiscal,$vtoexento,'$personeria',$tratamiento,$matriculaNac,$matriculaPro,$nroRegistroSSS,$vtoRegistroSSS,$nroRegistroSNR,$vtoRegistroSNR,$capitado,$fijo,'$obs','$fecharegistro','$usuarioregistro','$fechamodificacion','$usuariomodificacion')";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//print($sqlInsertPresta."<br>");
	$dbh->exec($sqlInsertPresta);
	$codigoNextPresta = $dbh->lastInsertId(); 
	
	$sqlInsertPrestaAux = "INSERT INTO prestadoresauxiliar VALUES('$codigoNextPresta',NULL,NULL,NULL,0,NULL,NULL,NULL)";
	
	//print($sqlInsertPrestaAux."<br>");
	$dbh->exec($sqlInsertPrestaAux);
	
	foreach($_POST as $key => $value) {
		if (strpos($key ,'servicio') !== false) {
			$servicio = $_POST[$key];
			$sqlInsertServicio = "INSERT INTO prestadorservicio VALUE($codigoNextPresta, $servicio)";
			print($sqlInsertServicio."<br>");
			$dbh->exec($sqlInsertServicio);
		}
	}
	
	foreach($_POST as $key => $value) {
		if (strpos($key ,'nomenclador') !== false) {
			$nomenclador = $_POST[$key];
			$sqlInsertNomenclador = "INSERT INTO prestadornomenclador VALUE($codigoNextPresta, $nomenclador)";
			//print($sqlInsertNomenclador."<br>");
			$dbh->exec($sqlInsertNomenclador);
		}
	}
	
	foreach($_POST as $key => $value) {
		if (strpos($key ,'delegacion') !== false) {
			$delegacion = $_POST[$key];
			$perte = 0;
			if ($pertenencia == $delegacion) {
				$perte = 1;
			}
			$sqlInsertJurisdiccion = "INSERT INTO prestadorjurisdiccion VALUE($codigoNextPresta, $delegacion,$perte)";
			//print($sqlInsertJurisdiccion."<br>");
			$dbh->exec($sqlInsertJurisdiccion);
		}
	}
	
	$dbh->commit();
	$pagina = "prestador.php?codigo=$codigoNextPresta";
	Header("Location: $pagina"); 
	
}catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	Header($redire);
	exit(0);
}

?>
