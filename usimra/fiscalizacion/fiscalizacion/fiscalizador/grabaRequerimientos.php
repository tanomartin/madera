<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionUsimra.php"); 
set_time_limit(0);
//Para que se vea el blockUI
print("<br>");
//*************************

/****************************************************************************************/

function calculoDeudaNr($remu, $personal, $mes, $anio, $db) {
	$sqlExtra = "SELECT anio, mes, tipo, valor, retiene060*0.06 + retiene100*0.1 + retiene150*0.15 as porcentaje FROM extraordinariosusimra 
					WHERE anio = $anio and mes = $mes and tipo != 2";
	$resExtra = mysql_query($sqlExtra,$db);
	$rowExtra = mysql_fetch_assoc($resExtra);
	$apagar = 0;
	if ($rowExtra['tipo'] == 0) {
		$apagar = $rowExtra['valor'] * $rowExtra['porcentaje'] * $personal;
	}
	if ($rowExtra['tipo'] == 1) {
		$apagar = $remu * $rowExtra['valor'] * $rowExtra['porcentaje'];
	}
	return $apagar;
}

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
			if ($estado != 'S') {
				$deudaNominal = 0;
				if ($estado == 'M') {
					$deudaNominal = (float)($deuda['deuda']);
				} else {
					if ($mes <= 12) {
						$deudaNominal = (float)($deuda['remu'] * $alicuota);
					} else {
						if ($estado == 'A') {
							$deudaNominal = calculoDeudaNr($deuda['remu'],$deuda['totper'],$mes, $anio, $db);
						} 
					}
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
	$cuit = $lista['cuit'];
	$sqlJuris = "SELECT codidelega from jurisdiccion where cuit = '$cuit' order by disgdinero DESC limit 1";
	$resJuris = mysql_query($sqlJuris,$db);
	$rowJuris = mysql_fetch_assoc($resJuris);
	$codidelega = $rowJuris['codidelega'];
	
	$sqlReqFis = "INSERT INTO reqfiscalizusimra VALUE(DEFAULT, '$fecreq', '$origen', '$solici', '$motivo', '$cuit', '$codidelega', '0', '$fecharegistro', '$usuarioregistro', '0000-00-00', '', '0','','0000-00-00','')";
	//print($sqlReqFis."<br>");
	$peridosDeuda = $lista['deuda'];	
	try {
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();
		$dbh->exec($sqlReqFis);
		$nroreq = $dbh->lastInsertId();
		foreach ($peridosDeuda as $key=>$deuda){
			$anofis = $deuda['anio'];
			if (strlen($key) == 6) {
				$mesfis = substr($key,4,2);
			} else {
				$mesfis = substr($key,4,1);
			}
			$stafis = $deuda['estado'];
			$remfis = $deuda['remu'];
			$canper = $deuda['totper'];
			$deunom = $deuda['deudaNominal'];
			$sqlDetFis = "INSERT INTO detfiscalizusimra VALUE('$nroreq', '$anofis', '$mesfis', '$stafis', '$remfis', '$canper', '$deunom')";
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