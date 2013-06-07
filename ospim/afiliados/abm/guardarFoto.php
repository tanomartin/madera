<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/ospim/lib/";
include($libPath."controlSession.php");

$datos = array_values($_POST);
//echo $datos[0]; echo "<br>"; //nroafiliado (no guarda)
$nroafiliado = $datos[0];
//echo $datos[1]; echo "<br>"; //tipafiliado
$tipafiliado = $datos[1];
//echo $datos[2]; echo "<br>"; //ordafiliado
$ordafiliado = $datos[2];
$archivo = $_FILES["archivofoto"]["tmp_name"];
//echo $archivo; echo "<br>"; //archivo
$nomarchivo=$_FILES['archivofoto']['name'];
//echo $nomarchivo; echo "<br>"; //archivo
$tiparchivo=$_FILES['archivofoto']['type'];
//echo $tiparchivo; echo "<br>"; //archivo
$tamarchivo=$_FILES['archivofoto']['size'];
//echo $tamarchivo; echo "<br>"; //archivo

if ($archivo != "") {
	$fp = fopen($archivo, "rb");
	$conarchivo = fread($fp, $tamarchivo);
	//$conarchivo = addslashes($conarchivo);
	fclose($fp);

	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		//echo "$hostname"; echo "<br>";
		//echo "$dbname"; echo "<br>";
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		//echo 'Connected to database<br/>';
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();

		if($tipafiliado==1)
			$sqlActualizaFoto = "UPDATE titulares SET foto = :foto WHERE nroafiliado = :nroafiliado";
		else
			$sqlActualizaFoto = "UPDATE familiares SET foto = :foto WHERE nroafiliado = :nroafiliado and nroorden = :nroorden";

		$resActualizaFoto = $dbh->prepare($sqlActualizaFoto);

		if($tipafiliado==1)	{	
			$resActualizaFoto->execute(array(':nroafiliado' => $nroafiliado, ':foto' => $conarchivo));
		}
		else {
			$resActualizaFoto->execute(array(':nroafiliado' => $nroafiliado, ':nroorden' => $ordafiliado, ':foto' => $conarchivo));
		}

		$dbh->commit();

		if($tipafiliado==1)
			$pagina = "afiliado.php?nroAfi=$nroafiliado&estAfi=1";
		else
			$pagina = "fichaFamiliar.php?nroAfi=$nroafiliado&estAfi=1&estFam=1&nroOrd=$ordafiliado";

		Header("Location: $pagina"); 
	}
	catch (PDOException $e) {
		echo $e->getMessage();
		$dbh->rollback();
	}
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>

<title>.: Foto :.</title>
</head>
<body bgcolor="#CCCCCC" > 
</body>
</html>