<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
set_time_limit(0);
//Para que se vea el blockUI
print("<br>");
//*************************

/****************************************************************************************/

$listadoSerializado=$_POST['empresas'];
$listadoEmpresas = unserialize(urldecode($listadoSerializado));

$datosSerializado=$_POST['datosReq'];
$listadoDatosReq = unserialize(urldecode($datosSerializado));


$solicitante=$listadoDatosReq['solicitante'];
$motivo = $listadoDatosReq['motivo'];
$origen = $listadoDatosReq['origen'];
//print("DATOS FILSCALIZACION");
//var_dump($listadoDatosReq);

//print("DEUDA DE EMPRESAS FILSCALIZDAS<br><br>");
$empre = 0; 
$alicuota = 0.0765;
$listadoFinal = array();
for($i=0; $i < sizeof($listadoEmpresas); $i++) {
	$deudaFinal = array();
	$cuit = $listadoEmpresas[$i]['cuit'];
	$deudas = $listadoEmpresas[$i]['deudas'];
	foreach ($deudas as $deuda){
		$estado = $deuda['estado'];
		if ($estado != 'P') {
			$anio = $deuda['anio'];
			$mes = $deuda['mes'];
			$id = $anio.$mes;
			if ($estado != 'S') {
				$deudaNominal = (float)($deuda['remu'] * $alicuota);
				if ($estado == 'M' || $estado == 'F') {
					$deudaNominal = (float)($deudaNominal - $deuda['importe']);
				}
				$deuda['deudaNominal'] = (float)number_format($deudaNominal,2,'.','');
			} else {
				$deuda['remu'] = 0.00;
				$deuda['totper'] = 0;
				$deuda['deudaNominal'] = 0.00;
			}
			$deudaFinal[$id] = $deuda;
		}	
	}
	if (sizeof($deudaFinal) != 0) {
		$listadoFinal[$empre] = array('cuit' => $cuit, 'deuda' => $deudaFinal);
		$empre = $empre + 1;
	}
}

$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];
$fechamodificacion = $fecharegistro;
$usuariomodificacion = $usuarioregistro;
$fecreq = date("Y-m-d");
$origen = $listadoDatosReq['origen'];
$solici = $listadoDatosReq['solicitante'];
$motivo = $listadoDatosReq['motivo'];
$hostname = $_SESSION['host'];
$dbname = $_SESSION['dbname'];
foreach ($listadoFinal as $lista){
	$sqlBuscaNro = "SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = '$dbname' AND TABLE_NAME = 'reqfiscalizospim'";
	$resBuscaNro = mysql_query($sqlBuscaNro,$db);
	$rowBuscaNro = mysql_fetch_array($resBuscaNro);
	$nroreq= $rowBuscaNro['AUTO_INCREMENT'];
	
	$cuit = $lista['cuit'];
	$sqlJuris = "SELECT codidelega from jurisdiccion where cuit = '$cuit' order by disgdinero DESC limit 1";
	$resJuris = mysql_query($sqlJuris,$db);
	$rowJuris = mysql_fetch_assoc($resJuris);
	$codidelega = $rowJuris['codidelega'];
	
	$sqlReqFis = "INSERT INTO reqfiscalizospim VALUE('$nroreq', '$fecreq', '$origen', '$solici', '$motivo', '$cuit', '$codidelega', '0', '$fecharegistro', '$usuarioregistro', '0000-00-00', '', '0','','0000-00-00','')";
	//print($sqlReqFis."<br>");
	$peridosDeuda = $lista['deuda'];	
	try {
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();
		$dbh->exec($sqlReqFis);
		foreach ($peridosDeuda as $deuda){
			$anofis = $deuda['anio'];
			$mesfis = $deuda['mes'];
			$stafis = $deuda['estado'];
			$remfis = $deuda['remu'];
			$canper = $deuda['totper'];
			$deunom = $deuda['deudaNominal'];
			$sqlDetFis = "INSERT INTO detfiscalizospim VALUE('$nroreq', '$anofis', '$mesfis', '$stafis', '$remfis', '$canper', '$deunom')";
			//print($sqlDetFis."<br>");
			$dbh->exec($sqlDetFis);
		}
		$dbh->commit();
		$pagina = "../menuFiscalizaciones.php";
		
		//cambio la hora de secion por ahora para no perder la misma
		$ahora = date("Y-n-j H:i:s"); 
		$_SESSION["ultimoAcceso"] = $ahora;
		
		Header("Location: $pagina"); 
	}catch (PDOException $e) {
		echo $e->getMessage();
		$dbh->rollback();
	}
}

?>