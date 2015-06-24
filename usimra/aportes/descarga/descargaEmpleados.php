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

$sqlEmpleados = "select e.*, emp.rramaa as rramaa from empleados e, empresa emp where e.bajada = 0 and e.nrcuit = emp.nrcuit";
$resEmpleados = mysql_query($sqlEmpleados,$dbaplicativo); 
$canEmpleados = mysql_num_rows($resEmpleados); 
if ($canEmpleados > 0) {
	$n = 0;
	$u = 0;
	$empleadosInsert = 0;
	$listadoEmpleados = array();
	$sqlEjecuciones = array();
	$sqlUpdateBajadaEmpleados = array();
	
	while ($rowEmpleados = mysql_fetch_assoc($resEmpleados)) {
		$cuilInsert = $rowEmpleados['nrcuil'];
		$cuitInsert = $rowEmpleados['nrcuit'];
		$sqlEmpleadoInsert = "select nrcuil, nrcuit from empleadosusimra where nrcuil = $cuilInsert and nrcuit = $cuitInsert";
		//print($sqlEmpleadoInsert);
		$resEmpleadoInsert = mysql_query($sqlEmpleadoInsert,$db); 
		$canEmpleadoInsert = mysql_num_rows($resEmpleadoInsert); 
		if ($canEmpleadoInsert == 0) {

			$codProvinApli = $rowEmpleados['provin'];
			$sqlprovin = "select codprovin from provincia where codzeus = $codProvinApli";
			$resprovin = mysql_query($sqlprovin,$db); 
			$canprovin = mysql_num_rows($resprovin); 
			if ($codProvin == 1) {
				$rowprovin = mysql_fetch_assoc($resprovin);
				$codProvin = $rowprovin['codprovin'];
			} else {
				$codProvin = 0;
			}
			
			$sqlInsertTitu = "INSERT INTO empleadosusimra VALUE(
			'".$rowEmpleados['nrcuit']."','".$rowEmpleados['nrcuil']."','".$rowEmpleados['apelli']."','".$rowEmpleados['nombre']."','".$rowEmpleados['fecing']."',
			'".$rowEmpleados['tipdoc']."','".$rowEmpleados['nrodoc']."','".$rowEmpleados['ssexxo']."','".$rowEmpleados['fecnac']."','".$rowEmpleados['estciv']."',
			'".$rowEmpleados['direcc']."','".$rowEmpleados['locale']."','".$rowEmpleados['copole']."','".$codProvin."','".$rowEmpleados['nacion']."',
			'".$rowEmpleados['rramaa']."','".$rowEmpleados['catego']."','".$rowEmpleados['activo']."','1')";
			
			$sqlEmpleadoInsert = "select nrcuil, nrcuit from empleadosdebajausimra where nrcuil = $cuilInsert and nrcuit = $cuitInsert";
			$resEmpleadoInsert = mysql_query($sqlEmpleadoInsert,$db); 
			$canEmpleadoInsert = mysql_num_rows($resEmpleadoInsert); 
			if ($canEmpleadoInsert > 0) {
				$listadoEmpleados[$n] = array("estado" => 'B', "cuil" =>  $rowEmpleados['nrcuil'], "cuit" => $rowEmpleados['nrcuit'], "nombre" => $rowEmpleados['apelli'].", ".$rowEmpleados['nombre']);
				$sqlDeleteTitu = "DELETE from empleadosdebajausimra where nrcuil = $cuilInsert and nrcuit = $cuitInsert";
				$sqlEjecuciones[$n] = $sqlInsertTitu;
				$n++;
				$sqlEjecuciones[$n] = $sqlDeleteTitu;
				$empleadosInsert++;
			} else  {
				$listadoEmpleados[$n] = array("estado" => 'I', "cuil" =>  $rowEmpleados['nrcuil'], "cuit" => $rowEmpleados['nrcuit'], "nombre" => $rowEmpleados['apelli'].", ".$rowEmpleados['nombre']);
				$sqlEjecuciones[$n] = $sqlInsertTitu;
				$empleadosInsert++;
			}
		} else {
			$listadoEmpleados[$n] = array("estado" => 'M', "cuil" =>  $rowEmpleados['nrcuil'], "cuit" => $rowEmpleados['nrcuit'], "nombre" => $rowEmpleados['apelli'].", ".$rowEmpleados['nombre']);
			$codProvinApli = $rowEmpleados['provin'];
			$sqlprovin = "select codprovin from provincia where codzeus = $codProvinApli";
			$resprovin = mysql_query($sqlprovin,$db); 
			$canprovin = mysql_num_rows($resprovin); 
			if ($codProvin == 1) {
				$rowprovin = mysql_fetch_assoc($resprovin);
				$codProvin = $rowprovin['codprovin'];
			} else {
				$codProvin = 0;
			}
			$sqlUpdateTitu = "UPDATE empleadosusimra SET 
										apelli = '".$rowEmpleados['apelli']."',
										nombre = '".$rowEmpleados['nombre']."',
										fecing = '".$rowEmpleados['fecing']."',
										tipdoc = '".$rowEmpleados['tipdoc']."',
										nrodoc = '".$rowEmpleados['nrodoc']."',
										ssexxo = '".$rowEmpleados['ssexxo']."',
										fecnac = '".$rowEmpleados['fecnac']."',
										estciv = '".$rowEmpleados['estciv']."',
										direcc = '".$rowEmpleados['direcc']."',
										locale = '".$rowEmpleados['locale']."',
										copole = '".$rowEmpleados['copole']."',
										provin = '".$codProvin."',
										nacion = '".$rowEmpleados['nacion']."',
										rramaa = '".$rowEmpleados['rramaa']."',
										catego = '".$rowEmpleados['catego']."',
										activo = '".$rowEmpleados['activo']."'
								WHERE nrcuil = $cuilInsert and nrcuil = $cuitInsert";
			$sqlEjecuciones[$n] = $sqlUpdateTitu;
		}
		$sqlUpdateBajadaEmpleados[$u] = "UPDATE empleados SET bajada = 1 WHERE nrcuil = $cuilInsert and nrcuit = $cuitInsert";
		$u++;
		$n++;
	}
	
	$updateControl = "UPDATE aporcontroldescarga SET cantidadtitulares = $empleadosInsert WHERE id = '".$idControl."'";
	
	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();
		
		foreach($sqlEjecuciones as $sql) {
			//print($sql."<br>");
			$dbh->exec($sql);
		}
		
		$hostname = $hostaplicativo;
		$dbnameweb = $baseUsimraNewAplicativo;
		$dbhweb = new PDO("mysql:host=$hostname;dbname=$dbnameweb",$usuarioaplicativo,$claveaplicativo);
		$dbhweb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbhweb->beginTransaction();
		
		foreach($sqlUpdateBajadaEmpleados as $sqlUpdate) {
			//print($sqlUpdate."<br>");
			$dbhweb->exec($sqlUpdate);
		}
		
		//print($updateControl."<br>");
		$dbh->exec($updateControl);
		
		$dbh->commit();		
		$dbhweb->commit();	
		
	} catch(PDOException $e) {
		$error =  $e->getMessage();
		$dbh->rollback();
		$dbhweb->rollback();	
		$redire = "Location://".$_SERVER['SERVER_NAME']."/usimra/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
		header ($redire);
		exit(0);	
	}
}

$listadoSerializadoEmpleados = serialize($listadoEmpleados);
$listadoSerializadoEmpleados = urlencode($listadoSerializadoEmpleados);

/*print("ULTIMO: ".$utlimoNroControl."<br>");
print("CANTIDAD DE DJJJ: ".$totalDdjj."<br>");

$listadoEmpresas = unserialize(urldecode($_POST['empresas']));
echo("<pre>");
print_r($listadoEmpresas);
echo("</pre>");

echo("<pre>");
print_r($listadoEmpleados);
echo("</pre>");*/

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Descarga Aplicativo DDJJ :.</title>

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
	$.blockUI({ message: "<h1>Descargando Nuevos Familiares... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
	function formSubmit() {
		document.getElementById("descargaFamliares").submit();
	}
</script>

<body bgcolor="#B2A274" onload="formSubmit();">
<form action="descargaFamiliares.php" id="descargaFamliares" method="post"> 
   <input name="nroControl" type="hidden" value="<?php echo $_POST['nroControl'] ?>">
   <input name="empresas" type="hidden" value="<?php echo $_POST['empresas'] ?>">
   <input name="empleados" type="hidden" value="<?php echo $listadoSerializadoEmpleados ?>">
   <input name="idControl" type="hidden" value="<?php echo $idControl ?>">
</form> 
</body>