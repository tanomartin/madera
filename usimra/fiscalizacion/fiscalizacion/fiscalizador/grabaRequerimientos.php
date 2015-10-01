<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionUsimra.php"); 
set_time_limit(0);
//Para que se vea el blockUI
print("<br>");
//*************************

/****************************************************************************************/
$listadoSerializado=$_POST['empresas'];
$listadoEmpresas = unserialize(urldecode($listadoSerializado));
unset($listadoSerializado);

$datosSerializado=$_POST['datosReq'];
$listadoDatosReq = unserialize(urldecode($datosSerializado));
unset($datosSerializado);

//print("DATOS FILSCALIZACION");
//var_dump($listadoDatosReq);

//print("DEUDA DE EMPRESAS FILSCALIZDAS<br><br>");
$empre = 0; 
$alicuota = 0.031;
$listadoFinal = array();
for($i=0; $i < sizeof($listadoEmpresas); $i++) {
	$deudaFinal = array();
	$cuit = $listadoEmpresas[$i]['cuit'];
	$deudas = $listadoEmpresas[$i]['deudas'];
	foreach ($deudas as $key=>$deuda){
		$estado = $deuda['estado'];
		if ($estado != 'P') {
			if (strlen($key) == 6) {
				$mes = substr($key,4,2);
			} else {
				$mes = substr($key,4,1);
			}
			$anio = $deuda['anio'];
			$id = $anio.$mes;
			if ($estado == 'S') {
				$deuda['remu'] = 0.00;
				$deuda['totper'] = 0;
				$deuda['deuda'] = 0.00;
			}
			$deudaFinal[$id] = $deuda;
		}	
	}
	if (sizeof($deudaFinal) != 0) {
		$listadoFinal[$empre] = array('cuit' => $cuit, 'listadodeuda' => $deudaFinal);
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
	$cuit = $lista['cuit'];
	$sqlJuris = "SELECT codidelega from jurisdiccion where cuit = '$cuit' order by disgdinero DESC limit 1";
	$resJuris = mysql_query($sqlJuris,$db);
	$rowJuris = mysql_fetch_assoc($resJuris);
	$codidelega = $rowJuris['codidelega'];
	
	$sqlReqFis = "INSERT INTO reqfiscalizusimra VALUE(DEFAULT, '$fecreq', '$origen', '$solici', '$motivo', '$cuit', '$codidelega', '0', '$fecharegistro', '$usuarioregistro', '0000-00-00', '', '0','','0000-00-00','')";
	$peridosDeuda = $lista['listadodeuda'];	
	try {
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();
		$dbh->exec($sqlReqFis);
		$nroreq = $dbh->lastInsertId();
		foreach ($peridosDeuda as $key=>$deudaperiodo){
			$anofis = $deudaperiodo['anio'];
			if (strlen($key) == 6) {
				$mesfis = substr($key,4,2);
			} else {
				$mesfis = substr($key,4,1);
			}
			$stafis = $deudaperiodo['estado'];
			$remfis = $deudaperiodo['remu'];
			$canper = $deudaperiodo['totper'];
			$deunom = $deudaperiodo['deuda'];
			$sqlDetFis = "INSERT INTO detfiscalizusimra VALUE('$nroreq', '$anofis', '$mesfis', '$stafis', '$remfis', '$canper', '$deunom')";
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