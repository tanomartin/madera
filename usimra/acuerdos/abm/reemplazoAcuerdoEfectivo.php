<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php"); 

$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];

$fechamodificacion = $fecharegistro;
$usuariomodificacion = $usuarioregistro;

$cuit = $_GET['cuit'];
 //echo "CUIT: ".$cuit; //echo "<br>";

$datos = array_values($_POST);
$acuReem = $datos[0];
 //echo "NRO ACUERDO A REEMPLAZAR: ".$acuReem; //echo "<br>";

$nroNuevoAcuerdo = $datos[1];
 //echo "NRO NUEVO ACUERDO: ".$nroNuevoAcuerdo; //echo "<br>";

$tipoAcu = $datos[2];
 //echo "TIPO ACUERDO: ".$tipoAcu; //echo "<br>";

$fechaAcu = fechaParaGuardar($datos[3]);
 //echo "FECHA: ".$fechaAcu; //echo "<br>";

$acta = $datos[4];
 //echo "ACTA: ".$acta; //echo "<br>";

$gestor = $datos[5];
 //echo "GESTOR: ".$gestor; //echo "<br>";

$inspector = $datos[6];
 //echo "INSPECTOR: ".$inspector; //echo "<br>";

$requerimientoorigen = $datos[7];
$liquidacionorigen = $datos[8];
 //echo "REQUERI: ".$requerimientoorigen; //echo "<br>";
 //echo "LIQUI: ".$liquidacionorigen; //echo "<br>";

$montoacuerdo = $datos[9];
 //echo "MONTO: ".$montoacuerdo; //echo "<br>";

$gastosAdmi = $datos[10];
 //echo "GASTOS ADMI: ".$gastosAdmi; //echo "<br>";

$porcGastos = $datos[11];
 //echo "PORC GAST: ".$porcGastos; //echo "<br>";
if ($gastosAdmi == 0) {
 	$porcGastos = 0;
}
$observaciones = $datos[12];
 //echo "OBSER: ".$observaciones; //echo "<br>";

 //echo "<br>";//echo "<br>";

$estadoacuerdo = 1;
$cuotasapagar = 1;
$montoapagar = 0;
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
$sqlCargaCabecera = "INSERT INTO cabacuerdosusimra VALUES ('$cuit','$nroNuevoAcuerdo','$tipoAcu','$fechaAcu','$acta','$gestor','$porcGastos','$inspector','$requerimientoorigen','$liquidacionorigen','$montoacuerdo','$observaciones','$estadoacuerdo','$cuotasapagar',
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
	 //echo $sqlCargaCabecera; //echo("<br>");  //echo("<br>");
	
	//PERIODOS
	$sqlUpdatePeriodos = "UPDATE detacuerdosusimra set nroacuerdo = $nroNuevoAcuerdo where cuit = $cuit and nroacuerdo = $acuReem";
	 //echo $sqlUpdatePeriodos; //echo("<br>");  //echo("<br>");
	$dbh->exec($sqlUpdatePeriodos);
	
	//CUOTAS
	$sqlCuotas = "select * from cuoacuerdosusimra where cuit = $cuit and nroacuerdo = $acuReem order by fechacuota ASC";
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
			$sqlDeleteCuotas = "DELETE from cuoacuerdosusimra where cuit = $cuit and nroacuerdo = $acuReem and nrocuota = $nrocuota";
			 //echo $sqlDeleteCuotas; //echo("<br>");
			$dbh->exec($sqlDeleteCuotas);
			//anulacion de boleta impresa
			if ($rowCuotas['boletaimpresa'] != 0) {
				 //echo "HAY QUE ANULAR BOLETA DE LA CUOTA NUMERO $nrocuota"; //echo("<br>");
				
				$sqlBol = "select * from boletasusimra where cuit = $cuit and nroacuerdo = $acuReem and nrocuota = $nrocuota";
				$resBol = mysql_query($sqlBol,$db); 
				$rowBol = mysql_fetch_array($resBol); 
				$idBoleta = $rowBol['idboleta'];
				$cuit = $rowBol['cuit'];
				$nroacu = $rowBol['nroacuerdo'];
				$nrocuo = $rowBol['nrocuota'];
				$importe = $rowBol['importe'];
				$nrocontrol = $rowBol['nrocontrol'];
				$usuarioReg = $rowBol['usuarioregistro'];
				
				$sqlAnula = "INSERT INTO anuladasusimra VALUES('$idBoleta','$cuit','$nroacu','$nrocuo','$importe','$nrocontrol','$usuarioReg','$fechamodificacion','$usuariomodificacion','0','Reemplazo de Acuerdo') ";
				 //echo $sqlAnula; //echo "<br>";
				$dbh->exec($sqlAnula);
				
				$sqlDelete = "DELETE FROM boletasusimra where idboleta = $idBoleta";
				 //echo $sqlDelete; //echo "<br>";
				$dbh->exec($sqlDelete);
			}		
		}
	}
	
	 //echo "Monto VIEJO: ".$montoAcuViejo; //echo "<br>";
	 //echo "CUOTAS A PAG VIEJO: ".$cantCuotasPagasViejo; //echo "<br>";
	 //echo "Monto NUEVO: ".$montoAcuNuevo; //echo "<br>";
	
	$insertCuota = "INSERT INTO cuoacuerdosusimra VALUES ('$cuit','$nroNuevoAcuerdo','1','$montoAcuNuevo','$fecharegistro','8','','','0000-00-00','Deuda Pendiente del acuerdo reemplazado','0','0.0','0000-00-00','0000-00-00','','','0000-00-00','$fecharegistro','$usuarioregistro','$fechamodificacion','$usuariomodificacion')";
	 //echo $insertCuota; //echo "<br>";
	$dbh->exec($insertCuota);
	
	 //echo("<br>");
	//update cabecera viejo
	$sqlCabecera = "select * from cabacuerdosusimra where cuit = $cuit and nroacuerdo = $acuReem";
	$resCabecera = mysql_query($sqlCabecera,$db); 
	$rowCebecera = mysql_fetch_array($resCabecera); 
	$saldoAcuerdo = $rowCebecera['montopagadas'] - $montoAcuViejo;
	if ($saldoAcuerdo > -0.01 && $saldoAcuerdo < 0.01) {
		$saldoAcuerdo = 0;
	}
	$observa = $rowCebecera['observaciones']." - Acuerdo reemplazado por el acuerdo numero $nroNuevoAcuerdo con acta numero $acta";
	$sqlUpdateCabeViejo = "UPDATE cabacuerdosusimra set montoapagar = '$montoAcuViejo', saldoacuerdo = '$saldoAcuerdo', cuotasapagar = '$cantCuotasPagasViejo', observaciones = '$observa', estadoacuerdo = '0', fechamodificacion = '$fechamodificacion', usuariomodificacion = '$usuariomodificacion' where cuit = $cuit and nroacuerdo = $acuReem";
	 //echo $sqlUpdateCabeViejo;  //echo("<br>");
	$dbh->exec($sqlUpdateCabeViejo);
	
	//update cabecera nuevo
	$sqlUpdateCabeNuevo = "UPDATE cabacuerdosusimra set montoapagar = '$montoAcuNuevo', saldoacuerdo = '$montoAcuNuevo' where cuit = $cuit and nroacuerdo = $nroNuevoAcuerdo";
	 //echo $sqlUpdateCabeNuevo;  //echo("<br>");
	$dbh->exec($sqlUpdateCabeNuevo);
	
	$dbh->commit();
	
	$pagina = "acuerdos.php?cuit=$cuit";
	Header("Location: $pagina"); 
	
}catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/usimra/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}
?>
