<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
include($_SERVER['DOCUMENT_ROOT']."/madera/lib/fechas.php");

$codigopresta = $_GET['codigopresta'];

$nombre = addslashes($_POST['nombre']);

$cuit = $_POST['cuit'];
if ($cuit == '') {
	$cuit = "NULL";
} else {
	$cuit = "'$cuit'";
}

$domicilio = strtoupper(addslashes($_POST['domicilio']));

$indpostal = $_POST['indpostal'];
if ($indpostal == '') {
	$indpostal = "NULL";
} else {
	$indpostal = "'$indpostal'";
}

$codPos = $_POST['codPos'];
if ($codPos == '') {
	$codPos = "NULL";
} else {
	$codPos = "'$codPos'";
}

$alfapostal = $_POST['alfapostal'];
if ($alfapostal == '') {
	$alfapostal = "NULL";
} else {
	$alfapostal = "'$alfapostal'";
}

$localidad = $_POST['selectLocali'];
$codProvin = $_POST['codprovin'];

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

$email = $_POST['email'];
if ($email == "") {
	$email = "NULL";
} else {
	$email = "'$email'";
}

$circulo = $_POST['circulo'];
if ($circulo == 1) {
	$calidad = $_POST['calidad'];
	if ($calidad == 0) {
		$fechainiciocalidad = "NULL";
		$fechafincalidad = "NULL";
	} else {
		$fechadesde = fechaParaGuardar($_POST['fechadesde']);
		$fechainiciocalidad = "'$fechadesde'";
		if ($_POST['fechahasta'] == "") {
			$fechafincalidad = "NULL";
		} else {
			$fechahasta = fechaParaGuardar($_POST['fechahasta']);
			$fechafincalidad = "'$fechahasta'";
		}
	}
} else {
	$calidad = 0;
	$fechainiciocalidad = "NULL";
	$fechafincalidad = "NULL";
}

$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];

$sqlInsertProf = "INSERT INTO establecimientos VALUES(DEFAULT,'$codigopresta','$nombre',$cuit,'$domicilio','$localidad','$codProvin',$indpostal,$codPos,$alfapostal,$tel1,$ddn1,$tel2,$ddn2,$telfax,$ddnfax,$email,$circulo,$calidad,$fechainiciocalidad,$fechafincalidad,'$fecharegistro','$usuarioregistro',NULL,NULL)";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//print($sqlInsertProf."<br>");
	$dbh->exec($sqlInsertProf);
	$codigoNextPresta = $dbh->lastInsertId();
	
	$dbh->commit();
	$pagina = "establecimiento.php?codigo=$codigoNextPresta&codigopresta=$codigopresta";
	Header("Location: $pagina"); 
	
}catch (PDOException $e) {
	$error = "Cod. Error: ".$e->getCode()." - Linea: ".$e->getLine();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	Header($redire);
	exit(0);
}

?>
