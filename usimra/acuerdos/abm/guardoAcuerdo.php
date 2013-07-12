<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php");
$fecharegistro = date("Y-m-d H:m:s");
$usuarioregistro = $_SESSION['usuario'];
$fechamodificacion = $fecharegistro;
$usuariomodificacion = $usuarioregistro;

$cuit = $_GET['cuit'];
$nroacu = $_GET['nroacu'];
//echo "CUIT: ".$cuit; echo "<br>";
//echo "ACUERDO: ".$nroacu; echo "<br>"; echo "<br>";

$datos = array_values($_POST);
$sqlAltaCabe = $datos[0];
//echo "CARGO CABECERA"; echo "<br>";
//echo $sqlAltaCabe;echo "<br>";echo "<br>";

//conexion y craecion de transaccion.
try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	//echo "$hostname"; echo "<br>";
	//echo "$dbname"; echo "<br>";
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	//echo 'Connected to database<br/>';
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//CABECERA
	$dbh->exec($sqlAltaCabe);

	//PERIODOS
	$cantPer = $datos[1];
	//echo "CARGO ".$cantPer." PERIODOS <br>";
	if ($cantPer != 0) {
		for ($i = 2; $i<$cantPer+2; $i++) {
			$sqlAltaPeri = $datos[$i];
			//echo $sqlAltaPeri;echo "<br>";
			$dbh->exec($sqlAltaPeri);
		}
	} //else {
		//echo "NO HAY PERIODOS CARGADOS";echo "<br>";
	//}
	
	$indice = $cantPer + 2;
	$cantCuotas = $datos[$indice];
	$iniFor = $indice + 1;
	$finFor = $iniFor + $cantCuotas * 7;
	//echo "<br>";
	//echo "CARGO ".$cantCuotas." CUOTAS --> INICIO FOR: ".$iniFor." Y TERMINA EN ".$finFor; echo "<br>";
	$nroCuo = 1;
	$totalMonto = 0;
	for ($i= $iniFor; $i < $finFor; $i++){
		$monto = $datos[$i];
		$totalMonto = $totalMonto + $monto;
		$i++;
		$fecha = fechaParaGuardar($datos[$i]);
		$i++;
		$tipoC = $datos[$i];
		$i++;
		$chequen = $datos[$i];
		$i++;
		$chequeb = $datos[$i];
		$i++;
		$chequef = fechaParaGuardar($datos[$i]); 
		$i++;
		$observ = $datos[$i];
		
		$sqlCuota="INSERT INTO cuoacuerdosusimra VALUES ('$cuit','$nroacu','$nroCuo','$monto','$fecha','$tipoC','$chequen','$chequeb','$chequef','$observ','','','','','','','','$fecharegistro','$usuarioregistro','$fechamodificacion','$usuariomodificacion')";
		//echo $sqlCuota; echo "<br>";
		$dbh->exec($sqlCuota);	
		if ($tipoC == 3) {
			$sqlValCob = "INSERT INTO valoresalcobrousimra VALUES('$cuit','$nroacu','$nroCuo','$chequen','$chequeb','$chequef','','','','','','','')";
			//echo $sqlValCob; echo "<br>";
			$dbh->exec($sqlValCob);	
		}
		$nroCuo++;
	}
	
	//update del monto a pagar (suma de todas las cuotas)
	$sqlUpdateMonto = "UPDATE cabacuerdosusimra SET montoapagar=$totalMonto WHERE cuit = $cuit AND nroacuerdo = $nroacu";	
	//echo $sqlUpdateMonto; echo "<br>";
	$dbh->exec($sqlUpdateMonto);
	$dbh->commit();
	
	$pagina = "consultaAcuerdo.php?cuit=$cuit&nroacu=$nroacu";
	Header("Location: $pagina"); 
	
}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}
?>
