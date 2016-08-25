<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."claves.php"); 

var_dump($_POST);echo "<br>";

$maquina = $_SERVER['SERVER_NAME'];
if(strcmp("localhost",$maquina)==0)
	$hostaplicativo = "localhost";
else
	$hostaplicativo = $hostUsimra;

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	
	$dbhweb = new PDO("mysql:host=$hostaplicativo;dbname=$baseUsimraNewAplicativo",$usuarioUsimra,$claveUsimra);
	$dbhweb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbhweb->beginTransaction();
	
	$sqlPeriodo = "INSERT INTO periodosusimra VALUES(".$_POST['anio'].", ".$_POST['mes'].", '".$_POST['descripcion']."')";
	$sqlPeriodoApli = "INSERT INTO periodos VALUES(".$_POST['anio'].", ".$_POST['mes'].", '".$_POST['descripcion']."')";
	
	//echo $sqlPeriodo."<br>";
	$dbh->exec($sqlPeriodo);
	//echo $sqlPeriodoApli."<br>";
	$dbhweb->exec($sqlPeriodoApli);
	
	if ($_POST['mes'] > 12) {
		$sqlExtra = "INSERT INTO extraordinariosusimra VALUES(".$_POST['anio'].", ".$_POST['mes'].", ".$_POST['mesrelacion'].", ".$_POST['tipo'].", ".$_POST['valor'].", ".$_POST['ret060'].", ".$_POST['ret100'].", ".$_POST['ret150'].", '".$_POST['mensaje']."')";
		$sqlExtraApli = "INSERT INTO extraordinarios VALUES(".$_POST['anio'].", ".$_POST['mes'].", ".$_POST['mesrelacion'].", ".$_POST['tipo'].", ".$_POST['valor'].", ".$_POST['ret060'].", ".$_POST['ret100'].", ".$_POST['ret150'].", '".$_POST['mensaje']."')";
		//echo $sqlExtra."<br>";
		$dbh->exec($sqlExtra);
		//echo $sqlExtraApli."<br>";
		$dbhweb->exec($sqlExtraApli);
	}
	
	$dbh->commit();
	$dbhweb->commit();
	$pagina = "periodos.php";
	Header("Location: $pagina");
	
} catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$dbhweb->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}

?>