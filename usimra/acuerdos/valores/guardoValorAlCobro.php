<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php");
$fechadeposito  = date("Y-m-d H:i:s");
$usuariodeposito = $_SESSION['usuario'];

function desglosar ($dato) {
	$cont = 0;
	$a = "";
	$len_a = strlen($dato);
	$array = array();
	for ($cont = 0; $cont < $len_a; $cont++) {
		if (substr($dato, $cont, 1) != ",") {
			$a .= substr($dato, $cont, 1);
		}else{
			array_push ($array, $a);
			$a = "";
		}
		if ($cont == $len_a) {
			array_push ($array, $a);
		$a = "";
		}
	}
return $array;
}

function array_recibe($arrayDatos) { 
    $tmp = stripslashes($arrayDatos); 
    $tmp = urldecode($tmp); 
    $tmp = unserialize($tmp); 
    return $tmp; 
} 

$datos = array_values($_POST);
$info = array_recibe($datos[0]);
$nroChequeUsimra = $datos[1];
$fechaChequeUsimra = fechaParaGuardar($datos[2]);
$banco = "NACION";

//echo "NRO CHEQUE: ".$nroChequeUsimra; echo "<br>";
//echo "FECHA CHEQUE: ".$fechaChequeUsimra; echo "<br>";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
   	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	$i = 3;
	foreach ($info as $array) {
		$info = desglosar($array);
		$cuit = $info[0];
		$nroacu = $info[1];
		$nrocuo = $info[2];
		$idResumen = $datos[$i];
		$i = $i + 1;
		$fechaResumen = fechaParaGuardar($datos[$i]);
		$i = $i + 1;
		$sqlUpdateValores = "UPDATE valoresalcobrousimra set idresumenbancario = '$idResumen', fecharesumenbancario = '$fechaResumen', chequenrousimra = '$nroChequeUsimra', chequebancousimra = '$banco', chequefechausimra = '$fechaChequeUsimra', usuariodepositousimra = '$usuariodeposito', fechadepositousimra = '$fechadeposito' where cuit = $cuit and nroacuerdo = $nroacu and nrocuota = $nrocuo";
	
		//echo $sqlUpdateValores;  echo "<br>";
		$dbh->exec($sqlUpdateValores);
	}
	$dbh->commit();
	$pagina = "detalleValorAlCobro.php?nrocheque=$nroChequeUsimra&feccheque=$datos[2]&fecdep=$fechadeposito";
	Header("Location: $pagina"); 
} catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/usimra/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}
?>
