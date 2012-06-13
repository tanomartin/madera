<?php include($_SERVER['DOCUMENT_ROOT']."/ospim/lib/controlSession.php");
include($_SERVER['DOCUMENT_ROOT']."/ospim/lib/fechas.php"); 

$fecharegistro = date("Y-m-d H:m:s");
$usuarioregistro = $_SESSION['usuario'];

$fechamodificacion = $fecharegistro;
$usuariomodificacion = $usuarioregistro;

$cuit = $_GET['cuit'];
echo "CUIT: ".$cuit; echo "<br>";

$datos = array_values($_POST);
$acuReem = $datos[0];
echo "NRO ACUERDO A REEMPLAZAR: ".$acuReem; echo "<br>";

$nroNuevoAcuerdo = $datos[1];
echo "NRO NUEVO ACUERDO: ".$nroNuevoAcuerdo; echo "<br>";

$tipoAcu = $datos[2];
echo "TIPO ACUERDO: ".$tipoAcu; echo "<br>";

$fechaAcu = fechaParaGuardar($datos[3]);
echo "FECHA: ".$fechaAcu; echo "<br>";

$acta = $datos[4];
echo "ACTA: ".$acta; echo "<br>";

$gestor = $datos[5];
echo "GESTOR: ".$gestor; echo "<br>";

$inspector = $datos[6];
echo "INSPECTOR: ".$inspector; echo "<br>";

$requerimientoorigen = $datos[7];
$liquidacionorigen = $datos[8];
echo "REQUERI: ".$requerimientoorigen; echo "<br>";
echo "LIQUI: ".$liquidacionorigen; echo "<br>";

$montoacuerdo = $datos[9];
echo "MONTO: ".$montoacuerdo; echo "<br>";

$gastosAdmi = $datos[10];
echo "GASTOS ADMI: ".$gastosAdmi; echo "<br>";

$porcGastos = $datos[11];
echo "PORC GAST: ".$porcGastos; echo "<br>";

$observaciones = $datos[12];
echo "OBSER: ".$observaciones; echo "<br>";

echo "<br>";echo "<br>";

$estadoacuerdo = 1;
$cuotasapagar = 0;
$montoapagar = $montoacuerdo;
$cuotaspagadas = 0;
$montopagadas = 0;
$fechapagadas = "0000-00-00";
$saldoacuerdo = 0;

//datos de control de usuario...
$fecharegistro = date("Y-m-d H:m:s");
$usuarioregistro = $_SESSION['usuario'];
$fechamodificacion = $fecharegistro;
$usuariomodificacion = $usuarioregistro;

//Creo la sentencia SQL para cabecera.
$sqlCargaCabecera = "INSERT INTO cabacuerdosospim VALUES ('$cuit','$nroNuevoAcuerdo','$tipoAcu','$fechaAcu','$acta','$gestor','$porcGastos','$inspector','$requerimientoorigen','$liquidacionorigen','$montoacuerdo','$observaciones','$estadoacuerdo','$cuotasapagar',
'$montoapagar','$cuotaspagadas','$montopagadas','$fechapagadas','$saldoacuerdo','$fecharegistro','$usuarioregistro','$fechamodificacion','$usuariomodificacion')";

//conexion y craecion de transaccion.
try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	//CABECERA
	$dbh->exec($sqlCargaCabecera);
	echo $sqlCargaCabecera; echo("<br>");  echo("<br>");
	
	//PERIODOS
	$sqlUpdatePeriodos = "UPDATE detacuerdosospim set nroacuerdo = $nroNuevoAcuerdo where cuit = $cuit and nroacuerdo = $acuReem";
	echo $sqlUpdatePeriodos; echo("<br>");  echo("<br>");
	$dbh->exec($sqlUpdatePeriodos);
	
	//CUOTAS
	$sqlCuotas = "select * from cuoacuerdosospim where cuit = $cuit and nroacuerdo = $acuReem order by fechacuota ASC";
	$resCuotas = mysql_query($sqlCuotas,$db);
	$montoAcuViejo = 0;
	$cantCuotasPagasViejo = 0;
	$montoAcuNuevo = 0;
	$cantCuotasNuevo = 0;

	while ($rowCuotas = mysql_fetch_array($resCuotas)) { 
		//cuota cancelada...
		$nrocuota = $rowCuotas['nrocuota'];
		if ($rowCuotas['montopagada'] != 0 || $rowCuotas['fechapagada'] != '0000-00-00') {
			$montoAcuViejo = $montoAcuViejo + $rowCuotas['montocuota'];
			$cantCuotasPagasViejo = $cantCuotasPagasViejo + 1;
		} else { 
			//cuota no cancelada...
			$montoAcuNuevo = $montoAcuNuevo + $rowCuotas['montocuota'];
			$cantCuotasNuevo = $cantCuotasNuevo + 1;
			
			//anulacion de boleta impresa
			if ($rowCuotas['boletaimpresa'] != 0) {
				echo "HAY QUE ANULAR BOLETA DE LA CUOTA NUMERO $nrocuota"; echo("<br>");
				
				$sqlBol = "select * from boletasospim where cuit = $cuit and nroacuerdo = $acuReem and nrocuota = $nrocuota";
				$resBol = mysql_query($sqlBol,$db); 
				$rowBol = mysql_fetch_array($resBol); 
				$idBoleta = $rowBol['idboleta'];
				$cuit = $rowBol['cuit'];
				$nroacu = $rowBol['nroacuerdo'];
				$nrocuo = $rowBol['nrocuota'];
				$importe = $rowBol['importe'];
				$nrocontrol = $rowBol['nrocontrol'];
				$usuarioReg = $rowBol['usuarioregistro'];
				
				$sqlAnula = "INSERT INTO anuladasospim VALUES('$idBoleta','$cuit','$nroacu','$nrocuo','$importe','$nrocontrol','$usuarioReg','$fechamodificacion','$usuariomodificacion','0','Reemplazo de Acuerdo') ";
				echo $sqlAnula; echo "<br>";
				$dbh->exec($sqlAnula);
				
				$sqlDelete = "DELETE FROM boletasospim where idboleta = $idBoleta";
				echo $sqlDelete; echo "<br>";
				$dbh->exec($sqlDelete);
			}
			//updatevaloresalcobro
			if ($rowCuotas['tipocancelacion'] == 3) {
				echo "HAY QUE UPDETEAR VALORES AL COBRO DE LA CUOTA NUMERO $nrocuota"; echo("<br>");
				$sqlUpdateValores = "UPDATE valoresalcobro set nroacuerdo = '$nroNuevoAcuerdo', nrocuota = '$cantCuotasNuevo' where cuit = $cuit and nroacuerdo = $acuReem and nrocuota = $nrocuota";
				echo $sqlUpdateValores; echo "<br>";
				$dbh->exec($sqlUpdateValores);
			}
			
			$sqlUpdateCuota = "UPDATE cuoacuerdosospim set nroacuerdo = '$nroNuevoAcuerdo', nrocuota = '$cantCuotasNuevo', boletaimpresa = '0', fecharegistro = '$fecharegistro', usuarioregistro = '$usuarioregistro', fechamodificacion = '$fecharegistro', usuariomodificacion = '$usuarioregistro' where cuit = $cuit and nroacuerdo = $acuReem and nrocuota = $nrocuota";
			echo $sqlUpdateCuota; echo "<br>";
			$dbh->exec($sqlUpdateCuota);
		}
	}
	
	//echo "Monto VIEJO: ".$montoAcuViejo; echo "<br>";
	//echo "CUOTAS A PAG VIEJO: ".$cantCuotasPagasViejo; echo "<br>";
	//echo "Monto NUEVO: ".$montoAcuNuevo; echo "<br>";
	//echo "CUOTAS NUEVO: ".$cantCuotasNuevo; echo "<br>";
	
	 echo("<br>");
	//update cabecera viejo
	$sqlCabecera = "select * from cabacuerdosospim where cuit = $cuit and nroacuerdo = $acuReem";
	$resCabecera = mysql_query($sqlCabecera,$db); 
	$rowCebecera = mysql_fetch_array($resCabecera); 
	$saldoAcuerdo = $rowCebecera['montopagadas'] - $montoAcuViejo;
	if ($saldoAcuerdo > -0.01 && $saldoAcuerdo < 0.01) {
		$saldoAcuerdo = 0;
	}
	$observa = $rowCebecera['observaciones']." - Acuerdo reemplazado por el acuerdo numero $nroNuevoAcuerdo con acta numero $acta";
	$sqlUpdateCabeViejo = "UPDATE cabacuerdosospim set montoapagar = '$montoAcuViejo', saldoacuerdo = '$saldoAcuerdo', cuotasapagar = '$cantCuotasPagasViejo', observaciones = '$observa', estadoacuerdo = '0', fechamodificacion = '$fechamodificacion', usuariomodificacion = '$usuariomodificacion' where cuit = $cuit and nroacuerdo = $acuReem";
	echo $sqlUpdateCabeViejo;  echo("<br>");
	$dbh->exec($sqlUpdateCabeViejo);
	
	//update cabecera nuevo
	$sqlUpdateCabeNuevo = "UPDATE cabacuerdosospim set montoapagar = '$montoAcuNuevo', cuotasapagar = '$cantCuotasNuevo', saldoacuerdo = '$montoAcuNuevo' where cuit = $cuit and nroacuerdo = $nroNuevoAcuerdo";
	echo $sqlUpdateCabeNuevo;  echo("<br>");
	$dbh->exec($sqlUpdateCabeNuevo);
	
	$dbh->commit();
	
	//$pagina = "consultaAcuerdo.php?cuit=$cuit&nroacu=$nroNuevoAcuerdo";
	//Header("Location: $pagina"); 
	
}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}
?>
