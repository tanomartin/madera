<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php"); 
include($libPath."claves.php"); 
set_time_limit(0);
print("<br>");

$idControl = $_POST['idControl'];

$hostaplicativo = $hostUsimra;
//$hostaplicativo = "localhost";
$usuarioaplicativo = $usuarioUsimra;
$claveaplicativo = $claveUsimra;
$dbaplicativo =  mysql_connect($hostaplicativo, $usuarioaplicativo, $claveaplicativo);
if (!$dbaplicativo) {
    die('No pudo conectarse: ' . mysql_error());
}
$dbnameaplicativo = $baseUsimraNewAplicativo;
mysql_select_db($dbnameaplicativo);
$sqlEmpresas = "SELECT * FROM empresa WHERE bajada = 0";
$resEmpresas = mysql_query($sqlEmpresas,$dbaplicativo); 
$canEmpresas = mysql_num_rows($resEmpresas); 


if ($canEmpresas > 0) {
	$n = 0;
	$empresasInsert = 0;
	$listadoEmpresas = array();
	$sqlInsertEmpresas = array();
	$sqlUpdateBajadaEmpresa = "UPDATE empresa SET bajada = 1 WHERE nrcuit in (";
	
	while($rowEmpresas = mysql_fetch_assoc($resEmpresas)) {
		$cuitInsert = $rowEmpresas['nrcuit'];
		$nombre = $rowEmpresas['nombre'];
		$sqlEmpresaInsert = "select cuit from empresas where cuit = $cuitInsert";
		$resEmpresaInsert = mysql_query($sqlEmpresaInsert,$db); 
		$canEmpresaInsert = mysql_num_rows($resEmpresaInsert); 
		if ($canEmpresaInsert == 0) {
			$sqlEmpresaInsert = "select cuit from empresasdebaja where cuit = $cuitInsert";
			$resEmpresaInsert = mysql_query($sqlEmpresaInsert,$db); 
			$canEmpresaInsert = mysql_num_rows($resEmpresaInsert); 
			if ($canEmpresaInsert == 0) {
				$codProvinApli = $rowEmpresas['provin'];
				$sqlprovin = "select codprovin from provincia where codzeus = $codProvinApli";
				$resprovin = mysql_query($sqlprovin,$db); 
				$canprovin = mysql_num_rows($resprovin); 
				if ($codProvin == 1) {
					$rowprovin = mysql_fetch_assoc($resprovin);
					$codProvin = $rowprovin['codprovin'];
				} else {
					$codProvin = 0;
				}
				
				$sqlIndPos = "select indpostal from provincia where codprovin = $codProvin";
				$resIndPos = mysql_query($sqlIndPos,$db); 
				$canIndPos = mysql_num_rows($resIndPos); 
				if ($canIndPos == 1) {
					$rowIndPos = mysql_fetch_assoc($resIndPos);
					$indPostal = $rowIndPos['indpostal'];
				} else {
					$indPostal = 0;
				}
				
				$nomlocali = $rowEmpresas['locali'];
				$sqlLocali = "select codlocali from localidades where codprovin = $codProvin and nomlocali like '$nomlocali'";
				$resLocali = mysql_query($sqlLocali,$db); 
				$canLocali = mysql_num_rows($resLocali); 
				if ($canLocali == 1) {
					$rowLocali = mysql_fetch_assoc($resLocali);
					$locali = $rowLocali['codlocali'];
				} else {
					$locali = 0;
				}
				$descrip = "Alta por proceso actualizador de aplicativo DDJJ (id: $idControl)";
				$sqlInsertCabe = "INSERT INTO empresas VALUE('".$rowEmpresas['nrcuit']."','".addslashes($rowEmpresas['nombre'])."',".$rowEmpresas['provin'].",'$indPostal',".$rowEmpresas['copole'].",'','$locali','".addslashes($rowEmpresas['domile'])."','','".addslashes($rowEmpresas['telfon'])."','','','','',0,3,'".$rowEmpresas['activi']."','','$descrip','','".$rowEmpresas['fecini']."','".$rowEmpresas['emails']."','','$fecharegistro','$usuarioregistro','','',DEFAULT)";
				$sqlInsertJuris = "INSERT INTO jurisdiccion VALUE('".$rowEmpresas['nrcuit']."','3200',".$rowEmpresas['provin'].",'$indPostal',".$rowEmpresas['copole'].",'',$locali,'".addslashes($rowEmpresas['domile'])."','','".$rowEmpresas['telfon']."','','".$rowEmpresas['emails']."',100)";
	
				$sqlInsertEmpresas[$n] = array("empresa" => $sqlInsertCabe, "jurisdiccion" => $sqlInsertJuris);
				$listadoEmpresas[$n] = array('estado'=> 'I', 'cuit' => $cuitInsert, 'nombre' => $nombre);
				$empresasInsert++;
			} else {
				$listadoEmpresas[$n] = array('estado'=> 'B', 'cuit' => $cuitInsert, 'nombre' => $nombre);
			}
		} else {
			$listadoEmpresas[$n] = array('estado'=> 'E', 'cuit' => $cuitInsert, 'nombre' => $nombre);
		}
		
		$sqlUpdateBajadaEmpresa .= "'".$cuitInsert."',";
		$n++;
	}

	$sqlUpdateBajadaEmpresa = substr($sqlUpdateBajadaEmpresa,0,-1);
	$sqlUpdateBajadaEmpresa .= ")";

	$updateControl = "UPDATE aporcontroldescarga SET cantidadempresas = $empresasInsert WHERE id = '".$idControl."'";

	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();
			
		$hostname = $hostaplicativo;
		$dbnameweb = $baseUsimraNewAplicativo;
		$dbhweb = new PDO("mysql:host=$hostname;dbname=$dbnameweb",$usuarioaplicativo,$claveaplicativo);
		$dbhweb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbhweb->beginTransaction();
		
		foreach($sqlInsertEmpresas as $sqlEmpresa) {
			//print($sqlEmpresa['empresa']."<br>");
			$dbh->exec($sqlEmpresa['empresa']);
			//print($sqlEmpresa['jurisdiccion']."<br>");
			$dbh->exec($sqlEmpresa['jurisdiccion']);
		}
		
		//print($sqlUpdateBajadaEmpresa."<br>");
		$dbhweb->exec($sqlUpdateBajadaEmpresa);

		//print($updateControl."<br>");
		$dbh->exec($updateControl);

		$dbh->commit();		
		$dbhweb->commit();	
		
	} catch(PDOException $e) {
		$error =  $e->getMessage();
		$dbh->rollback();
		$dbhweb->rollback();
		$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/usimra/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
		header ($redire);
		exit(0);	
	}
}

$listadoSerializado = serialize($listadoEmpresas);
$listadoSerializado = urlencode($listadoSerializado);

/*print("<br>");
print("ULTIMO: ".$utlimoNroControl."<br>");
print("CANTIDAD DE DJJJ: ".$totalDdjj."<br>");
print("ID CONTROL: ".$idControl."<br>");*/
	
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Descarga Aplicativo DDJJ :.</title>

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
	$.blockUI({ message: "<h1>Descargando Nuevos Empleados... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
	function formSubmit() {
		document.getElementById("descargaEmpleados").submit();
	}
</script>
</head>
<body bgcolor="#B2A274" onload="formSubmit();">
<form action="descargaEmpleados.php" id="descargaEmpleados" method="post"> 
   <input name="nroControl" type="hidden" value="<?php echo $_POST['nroControl'] ?>" />
   <input name="empresas" type="hidden" value="<?php echo $listadoSerializado ?>" />
   <input name="idControl" type="hidden" value="<?php echo $idControl ?>" />
</form> 
</body>
</html>