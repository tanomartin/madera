<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");

$cuit = $_POST['cuit'];
$nroorden = $_POST['nroorden'];
$listadoPeriodosAcuQuitadoSerializado = $_POST['insertPerAcuQuitado'];
$sqlUpdateAcuQuitado = $_POST['updateCabeceraAcuQuitado'];
$sqlDeletePeriodos = $_POST['deletePeriodos'];
$sqlUpdateAcuAbs = $_POST['updateCabeceraAcuAbs'];
$sqlCabecera = $_POST['updateCabeceraJui'];
$listadoPeriodosSerializado = $_POST['insertPeriodosJui'];
$listadoPeriodosAcuSerializado = $_POST['deletePeriodosAcuAbs'];

$sqlPeriodosQuitar = unserialize(urldecode($listadoPeriodosAcuQuitadoSerializado));
$sqlPeriodos = unserialize(urldecode($listadoPeriodosSerializado));
$sqlDelPer = unserialize(urldecode($listadoPeriodosAcuSerializado));


print($cuit."<br>");
print($nroorden."<br>");

/*print("<br>PRIMERO: DEVUELVO LOS PERIODOS AL ACUERDO<br>");
var_dump($sqlPeriodosQuitar);
print("<br>SEGUNDO: Desabsorvo el acuerdo si pide hacerlo<br>");
print($sqlUpdateAcuQuitado."<br>");
print("<br>TERCERO: Elimino todos los periodos del juicio<br>");
print($sqlDeletePeriodos."<br>");
print("<br>CUARTO: Updeteo el nuevo acuerdo a absorver si es que lo hay<br>");
print($sqlUpdateAcuAbs."<br>");
print("<br>QUINTO: Updeteo la cabecera del juicio dependiendo si hay o no acuerdos absorvidos.<br>");
print($sqlCabecera."<br>");
print("<br>SEXTO: Grabo los periodos del juicio y si hay de acuerdo absorivdo los elimino del acuerdo.<br>");
print("INSERT JUICIO: <br>");
var_dump($sqlPeriodos);
print("DELETE ACUERDO: <br>");
var_dump($sqlDelPer);*/

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	
	//print("<br>PRIMERO: DEVUELVO LOS PERIODOS AL ACUERDO<br>");
	if (!empty($sqlPeriodosQuitar)) {
		for($i=0; $i<sizeof($sqlPeriodosQuitar); $i++) {
			//print($sqlPeriodos[$i]."<br>");
			$dbh->exec($sqlPeriodosQuitar[$i]);
		}
	}
	//print("<br>SEGUNDO: Desabsorvo el acuerdo si pide hacerlo<br>");
	if (!empty($sqlUpdateAcuQuitado)) {
		//print($sqlUpdateAcuQuitado."<br>");
		$dbh->exec($sqlUpdateAcuQuitado);
	}
	
	//print("<br>TERCERO: Elimino todos los periodos del juicio<br>");
	if (!empty($sqlDeletePeriodos)) {
		//print($sqlDeletePeriodos."<br>");
		$dbh->exec($sqlDeletePeriodos);
	}
	
	//print("<br>CUARTO: Updeteo el nuevo acuerdo a absorver si es que lo hay<br>");
	if (!empty($sqlUpdateAcuAbs)) {
		//print($sqlUpdateAcuAbs."<br>");
		$dbh->exec($sqlUpdateAcuAbs);
	}
	
	//print("<br>QUINTO: Updeteo la cabecera del juicio dependiendo si hay o no acuerdos absorvidos.<br>");
	//print($sqlCabecera."<br>");
	$dbh->exec($sqlCabecera);
	
	//print("<br>SEXTO: Grabo los periodos del juicio y si hay de acuerdo absorivdo los elimino del acuerdo.<br>");
	if (!empty($sqlPeriodos)) {
		for($i=0; $i<sizeof($sqlPeriodos); $i++) {
			//print($sqlPeriodos[$i]."<br>");
			$dbh->exec($sqlPeriodos[$i]);
		}
	}
	if (!empty($sqlDelPer)) {
		for($i=0; $i<sizeof($sqlDelPer); $i++) {
			//print($sqlDelPer[$i]."<br>");
			$dbh->exec($sqlDelPer[$i]);
		}
	}
	
	$dbh->commit();
	$pagina = "consultaJuicio.php?cuit=$cuit&nroorden=$nroorden";
	Header("Location: $pagina"); 
	
}catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}


?>