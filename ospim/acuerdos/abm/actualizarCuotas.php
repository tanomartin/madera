<?php  include($_SERVER['DOCUMENT_ROOT']."/ospim/lib/controlSession.php");
	include($_SERVER['DOCUMENT_ROOT']."/ospim/lib/fechas.php"); 
	$fechamodificacion = date("Y-m-d H:m:s");
	$usuariomodificacion = $_SESSION['usuario'];
	
	$nroacu=$_GET['nroacu'];
	$cuit=$_GET['cuit'];
	$cantCuotasModif=$_GET['canMod'];
	
	echo $nroacu;echo "<br>";
	echo $cuit;echo "<br>";
	echo $cantCuotasModif;echo "<br>";
	
	$datos = array_values($_POST);
	$cantCuotasTotal = $datos[0];
	echo $cantCuotasTotal;echo "<br>";
	
	$finFor = $cantCuotasModif * 8;
	echo $finFor;echo "<br>";
try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	for ($i= 1; $i <= $finFor; $i++){
		$nrocuota = $datos[$i];
		$i++;
		$monto = $datos[$i];
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
		
		$sqlDeleteValorCobro = "DELETE from valoresalcobro where cuit = $cuit and nroacuerdo = $nroacu and nrocuota = $nrocuota";
		echo $sqlDeleteValorCobro; echo "<br>";
		$dbh->exec($sqlDeleteValorCobro);	
		
		$sqlUpdateCuota="UPDATE cuoacuerdosospim set montocuota = '$monto', fechacuota = '$fecha', tipocancelacion = '$tipoC', chequenro = '$chequen', chequebanco = '$chequeb', chequefecha = '$chequef', observaciones = '$observ', fechamodificacion = '$fechamodificacion', usuariomodificacion = '$usuariomodificacion' where cuit = $cuit and nroacuerdo = $nroacu and nrocuota = $nrocuota";
		echo $sqlUpdateCuota; echo "<br>";
		$dbh->exec($sqlUpdateCuota);	
		if ($tipoC == 3) {
			$sqlValCob = "INSERT INTO valoresalcobro VALUES('$cuit','$nroacu','$nrocuota','$chequen','$chequeb','$chequef','','','','','','','')";
			echo $sqlValCob; echo "<br>";				
			$dbh->exec($sqlValCob);	
		}
	
	} 
	if ($cantCuotasTotal > $cantCuotasModif) {
		$finFor++;
		$nrocuota = $datos[$finFor];
		$finFor++;
		$monto = $datos[$finFor];
		$finFor++;
		$fecha = fechaParaGuardar($datos[$finFor]);
		$finFor++;
		$tipoC = $datos[$finFor];
		$finFor++;
		$chequen = $datos[$finFor];
		$finFor++;
		$chequeb = $datos[$finFor];
		$finFor++;
		$chequef = fechaParaGuardar($datos[$finFor]); 
		$finFor++;
		$observ = $datos[$finFor];	
		$sqlCuota="INSERT INTO cuoacuerdosospim VALUES ('$cuit','$nroacu','$nrocuota','$monto','$fecha','$tipoC','$chequen','$chequeb','$chequef','$observ','','','','','','','','$fechamodificacion','$usuariomodificacion','$fechamodificacion','$usuariomodificacion')";
		echo $sqlCuota; echo "<br>";
		$dbh->exec($sqlCuota);	
		if ($tipoC == 3) {
			$sqlValCob = "INSERT INTO valoresalcobro VALUES('$cuit','$nroacu','$nrocuota','$chequen','$chequeb','$chequef','','','','','','','')";
			echo $sqlValCob; echo "<br>";				
			$dbh->exec($sqlValCob);	
		}				
	}
	$dbh->commit();
	$pagina = "modificarCuotas.php?cuit=$cuit&nroacu=$nroacu&cambio=1";
	Header("Location: $pagina"); 
} catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}

//TODO: actualizo cabecera con total a pgar....
$sqlCuotas = "select * from cuoacuerdosospim where cuit = $cuit and nroacuerdo = $nroacu";
$resCuotas = mysql_query($sqlCuotas,$db);
$total=0;
$cuotas=0;
while ($rowCuotas=mysql_fetch_array($resCuotas)) {
	$total = $total + $rowCuotas['montocuota'];
	$cuotas = $cuotas + 1;
}
try {
	$sqlUpdateMonto = "UPDATE cabacuerdosospim SET cuotasapagar=$cuotas, montoapagar=$total WHERE cuit = $cuit AND nroacuerdo = $nroacu";	
	echo $sqlUpdateMonto; echo "<br>";
	$dbh->exec($sqlUpdateMonto);
	$dbh->commit();
} catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}

?>