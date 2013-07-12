<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php"); ?>
<script type="text/javascript">document.body.style.cursor = 'wait';</script>		
<?php	
	$fechamodificacion = date("Y-m-d H:m:s");
	$usuariomodificacion = $_SESSION['usuario'];
	
	$nroacu=$_GET['nroacu'];
	$cuit=$_GET['cuit'];
	$cantCuotasModif=$_GET['canMod'];
	
	//echo $nroacu;//echo "<br>";
	//echo $cuit;//echo "<br>";
	//echo "CANTIDAD A MODIFICAR: ".$cantCuotasModif;//echo "<br>";
	
	$datos = array_values($_POST);
	$cantCuotasTotal = $datos[0];
	//echo $cantCuotasTotal;//echo "<br>";
	
	$finFor = $cantCuotasModif * 8;
	//echo $finFor;//echo "<br>";
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
		
		$sqlDeleteValorCobro = "DELETE from valoresalcobrousimra where cuit = $cuit and nroacuerdo = $nroacu and nrocuota = $nrocuota";
		//echo $sqlDeleteValorCobro; //echo "<br>";
		$dbh->exec($sqlDeleteValorCobro);	
		
		$sqlUpdateCuota="UPDATE cuoacuerdosusimra set montocuota = '$monto', fechacuota = '$fecha', tipocancelacion = '$tipoC', chequenro = '$chequen', chequebanco = '$chequeb', chequefecha = '$chequef', observaciones = '$observ', fechamodificacion = '$fechamodificacion', usuariomodificacion = '$usuariomodificacion' where cuit = $cuit and nroacuerdo = $nroacu and nrocuota = $nrocuota";
		//echo $sqlUpdateCuota; //echo "<br>";
		$dbh->exec($sqlUpdateCuota);	
		if ($tipoC == 3) {
			$sqlValCob = "INSERT INTO valoresalcobrousimra VALUES('$cuit','$nroacu','$nrocuota','$chequen','$chequeb','$chequef','','','','','','','')";
			//echo $sqlValCob; //echo "<br>";				
			$dbh->exec($sqlValCob);	
		}
	
	} 
	if ($cantCuotasTotal > $cantCuotasModif) {
		$cantidadInsert=$cantCuotasTotal-$cantCuotasModif;
		$inicioForInsert=$finFor+1;
		$finForInsert=($cantidadInsert)*8 + $inicioForInsert;
		//echo "NUEVAS: ".$cantidadInsert; //echo "<br>";
		//echo "INICIO INSERCION EN: ".$inicioForInsert; //echo "<br>";
		//echo "FIN INSERCION EN: ".$finForInsert; //echo "<br>";
		for ($i= $inicioForInsert; $i < $finForInsert; $i++){
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
			$sqlCuota="INSERT INTO cuoacuerdosusimra VALUES ('$cuit','$nroacu','$nrocuota','$monto','$fecha','$tipoC','$chequen','$chequeb','$chequef','$observ','','','','','','','','$fechamodificacion','$usuariomodificacion','$fechamodificacion','$usuariomodificacion')";
			//echo $sqlCuota; //echo "<br>";
			$dbh->exec($sqlCuota);	
			if ($tipoC == 3) {
				$sqlValCob = "INSERT INTO valoresalcobrousimra VALUES('$cuit','$nroacu','$nrocuota','$chequen','$chequeb','$chequef','','','','','','','')";
				//echo $sqlValCob; //echo "<br>";				
				$dbh->exec($sqlValCob);	
			}
		}				
	}
		
	//TODO: actualizo cabecera con total a pagar....
	$sqlCuotas = "select * from cuoacuerdosusimra where cuit = $cuit and nroacuerdo = $nroacu";
	$resCuotas = $dbh->query($sqlCuotas);
	$total=0;
	$cuotas=0;
	foreach ($resCuotas as $cuota) {
		//echo $cuota['montocuota']; //echo "<br>";
		$total = $total + $cuota['montocuota'];
		$cuotas = $cuotas + 1;
	}
	
	//echo "TOTAL: ".$total; //echo "<br>";
	$sqlUpdateMonto = "UPDATE cabacuerdosusimra SET cuotasapagar=$cuotas, montoapagar=$total WHERE cuit = $cuit AND nroacuerdo = $nroacu";	
	//echo $sqlUpdateMonto; //echo "<br>";
	$dbh->exec($sqlUpdateMonto);
	$dbh->commit();
	$pagina = "consultaAcuerdo.php?cuit=$cuit&nroacu=$nroacu";
	Header("Location: $pagina"); 
	
} catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}
?>