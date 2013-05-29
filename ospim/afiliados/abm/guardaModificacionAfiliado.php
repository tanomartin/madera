<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/ospim/lib/";
include($libPath."controlSession.php");
include($libPath."fechas.php");

$fechamodificacion = date("Y-m-d H:m:s");
$usuariomodificacion = $_SESSION['usuario'];

$datos = array_values($_POST);

echo $datos[0]; echo "<br>"; //nroafiliado (no guarda)
echo $datos[1]; echo "<br>"; //apellidoynombre
echo $datos[2]; echo "<br>"; //tipodocumento
echo $datos[3]; echo "<br>"; //nrodocumento
echo $datos[4]; echo "<br>"; //fechanacimiento
echo $datos[5]; echo "<br>"; //nacionalidad
echo $datos[6]; echo "<br>"; //sexo
echo $datos[7]; echo "<br>"; //estadocivil
echo $datos[8]; echo "<br>"; //domicilio
echo $datos[9]; echo "<br>"; //indpostal
echo $datos[10]; echo "<br>"; //numpostal
echo $datos[11]; echo "<br>"; //alfapostal
echo $datos[12]; echo "<br>"; //codlocali
echo $datos[13]; echo "<br>"; //codprovin
echo $datos[14]; echo "<br>"; //ddn
echo $datos[15]; echo "<br>"; //telefono
echo $datos[16]; echo "<br>"; //email
echo $datos[17]; echo "<br>"; //fechaobrasocial
echo $datos[18]; echo "<br>"; //tipoafiliado
echo $datos[19]; echo "<br>"; //solicitudopcion
echo $datos[20]; echo "<br>"; //situaciontitularidad
echo $datos[21]; echo "<br>"; //discapacidad
echo $datos[22]; echo "<br>"; //certificadodiscapacidad
echo $datos[23]; echo "<br>"; //cuil
echo $datos[24]; echo "<br>"; //cuitempresa
echo $datos[25]; echo "<br>"; //nombreempresa (no guarda)
echo $datos[26]; echo "<br>"; //fechaempresa
echo $datos[27]; echo "<br>"; //codidelega
echo $datos[28]; echo "<br>"; //categoria
echo $datos[29]; echo "<br>"; //emitecarnet
echo $datos[30]; echo "<br>"; //cantidadcarnet
echo $datos[31]; echo "<br>"; //fechacarnet
echo $datos[32]; echo "<br>"; //tipocarnet
echo $datos[33]; echo "<br>"; //vencimientocarnet (no guarda)

//try {
//	$hostname = $_SESSION['host'];
//	$dbname = $_SESSION['dbname'];
	//echo "$hostname"; echo "<br>";
	//echo "$dbname"; echo "<br>";
//	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	//echo 'Connected to database<br/>';
//	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//	$dbh->beginTransaction();

	$sqlActualizaTitular = "UPDATE titulares SET  WHERE nroafiliado = :nroafiliado";
	$resActualizaTitular = $dbh->prepare($sqlActualizaTitular);
	if($resActualizaTitular->execute(array()))


//	$dbh->commit();
//	$pagina = "afiliado.php?nroAfi=$nroafiliado&estAfi=1";
//	Header("Location: $pagina"); 
//}
//catch (PDOException $e) {
//	echo $e->getMessage();
//	$dbh->rollback();
//}
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>

<title>.: ABM Afiliados :.</title>
</head>
<body bgcolor="#CCCCCC" > 
</body>
</html>
