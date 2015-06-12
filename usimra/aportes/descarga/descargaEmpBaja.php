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


$sqlEmpleadosdebaja = "select e.*, emp.rramaa as rramaa from empleadosdebaja e, empresa emp where e.bajada = 0 and e.nrcuit = emp.nrcuit";
$resEmpleadosdebaja = mysql_query($sqlEmpleadosdebaja,$dbaplicativo); 
$canEmpleadosdebaja = mysql_num_rows($resEmpleadosdebaja); 
if ($canEmpleadosdebaja > 0) {
	$n = 0;
	$u = 0;
	$empleadoBajaInsert = 0;
	$listadoEmpBaja = array();
	$sqlEjecuciones = array();
	$sqlUpdateBajadaEmpBaja = array();
	
	while($rowEmpleadodebaja = mysql_fetch_assoc($resEmpleadosdebaja)) {
		$id = $rowEmpleadodebaja['id'];
		$sqlEmpleadoInsertBaja = "select nrcuil from empleadosdebajausimra where id = $id";
		$resEmpleadoInsertBaja = mysql_query($sqlEmpleadoInsertBaja,$db); 
		$canEmpleadoInsertBaja = mysql_num_rows($resEmpleadoInsertBaja); 
		if ($canEmpleadoInsertBaja == 0) {
		
			$codProvinApli = $rowEmpleadodebaja['provin'];
			$sqlprovin = "select codprovin from provincia where codzeus = $codProvinApli";
			$resprovin = mysql_query($sqlprovin,$db); 
			$canprovin = mysql_num_rows($resprovin); 
			if ($codProvin == 1) {
				$rowprovin = mysql_fetch_assoc($resprovin);
				$codProvin = $rowprovin['codprovin'];
			} else {
				$codProvin = 0;
			}
			
			$sqlInsertTituBaja = "INSERT INTO empleadosdebajausimra VALUE(
			'".$rowEmpleadodebaja['id']."',
			'".$rowEmpleadodebaja['nrcuit']."','".$rowEmpleadodebaja['nrcuil']."','".$rowEmpleadodebaja['apelli']."','".$rowEmpleadodebaja['nombre']."',
			'".$rowEmpleadodebaja['fecing']."','".$rowEmpleadodebaja['tipdoc']."','".$rowEmpleadodebaja['nrodoc']."','".$rowEmpleadodebaja['ssexxo']."',
			'".$rowEmpleadodebaja['fecnac']."','".$rowEmpleadodebaja['estciv']."','".$rowEmpleadodebaja['direcc']."','".$rowEmpleadodebaja['locale']."',
			'".$rowEmpleadodebaja['copole']."','".$codProvin."',
			'".$rowEmpleadodebaja['nacion']."','".$rowEmpleadodebaja['rramaa']."','".$rowEmpleadodebaja['catego']."',
			'".$rowEmpleadodebaja['activo']."','1')";
			
			$cuilInsert = $rowEmpleadodebaja['nrcuil'];
			$cuitInsert = $rowEmpleadodebaja['nrcuit'];
			
			$sqlEmpleadoInsertBaja = "select nrcuil from empleadosusimra where nrcuil = $cuilInsert and nrcuit = $cuitInsert";
			$resEmpleadoInsertBaja = mysql_query($sqlEmpleadoInsertBaja,$db); 
			$canEmpleadoInsertBaja = mysql_num_rows($resEmpleadoInsertBaja); 
			
			if ($canEmpleadoInsertBaja > 0) {
				$listadoEmpBaja[$n] = array("estado" => 'A', "cuil" =>  $rowEmpleadodebaja['nrcuil'], "cuit" =>  $rowEmpleadodebaja['nrcuit'], "nombre" => $rowEmpleadodebaja['apelli'].", ".$rowEmpleadodebaja['nombre']);
				$sqlDeleteTituBaja = "DELETE from empleadosusimra where nrcuil = $cuilInsert and nrcuit = $cuitInsert";
				$sqlEjecuciones[$n] = $sqlDeleteTituBaja;
				$n++;
				$sqlEjecuciones[$n] = $sqlInsertTituBaja;
				$empleadoBajaInsert++;
			} else  {
				$listadoEmpBaja[$n] = array("estado" => 'I', "cuil" =>  $rowEmpleadodebaja['nrcuil'], "cuit" =>  $rowEmpleadodebaja['nrcuit'], "nombre" => $rowEmpleadodebaja['apelli'].", ".$rowEmpleadodebaja['nombre']);
				$sqlEjecuciones[$n] = $sqlInsertTituBaja;
				$empleadoBajaInsert++;
			}
		} else {
			$listadoEmpBaja[$n] = array("estado" => 'E', "cuil" =>  $rowEmpleadodebaja['nrcuil'], "cuit" =>  $rowEmpleadodebaja['nrcuit'], "nombre" => $rowEmpleadodebaja['apelli'].", ".$rowEmpleadodebaja['nombre']);
		}
		
		$sqlUpdateBajadaEmpBaja[$u] = "UPDATE empleadosdebaja SET bajada = 1 WHERE id = $id";
		$n++;
		$u++;
	}
	
	$updateControl = "UPDATE aporcontroldescarga SET cantidadtitularesbaja = $empleadoBajaInsert WHERE id = '".$idControl."'";
	
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
		
		foreach($sqlUpdateBajadaEmpBaja as $sqlUpdate) {
			//print($sqlUpdate."<br>");
			$dbhweb->exec($sqlUpdate);
		}
		
		//print($updateControl."<br>");
		$dbh->exec($updateControl);
		
		$dbh->commit();		
		$dbhweb->commit();	
		
	} catch(PDOException $e) {
		$error =  $e->getMessage();
		print($error);
		$dbh->rollback();
		$dbhweb->rollback();	
		$redire = "Location://".$_SERVER['SERVER_NAME']."/usimra/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
		header ($redire);
		exit(0);
	}
}


$listadoSerializadoEmpBaja = serialize($listadoEmpBaja);
$listadoSerializadoEmpBaja = urlencode($listadoSerializadoEmpBaja);

/*print("ULTIMO: ".$utlimoNroControl."<br>");
print("CANTIDAD DE DJJJ: ".$totalDdjj."<br>");
$listadoEmpresas = unserialize(urldecode($_POST['empresas']));
echo("<pre>");
print_r($listadoEmpresas);
echo("</pre>");
$listadoEmpleados = unserialize(urldecode($_POST['empleados']));
echo("<pre>");
print_r($listadoEmpleados);
echo("</pre>");
$listadoFamiliares = unserialize(urldecode($_POST['familiares']));
echo("<pre>");
print_r($listadoFamiliares);
echo("</pre>");

echo("<pre>");
print_r($listadoEmpBaja);
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
	$.blockUI({ message: "<h1>Descargando Familiares de Baja... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
	function formSubmit() {
		document.getElementById("descargaFamBaja").submit();
	}
</script>

<body bgcolor="#B2A274" onload="formSubmit();">
<form action="descargaFamBaja.php" id="descargaFamBaja" method="POST"> 
   <input name="nroControl" type="hidden" value="<?php echo $_POST['nroControl'] ?>">
   <input name="empresas" type="hidden" value="<?php echo $_POST['empresas'] ?>">
   <input name="empleados" type="hidden" value="<?php echo $_POST['empleados'] ?>">
   <input name="familiares" type="hidden" value="<?php echo $_POST['familiares'] ?>">
   <input name="empbaja" type="hidden" value="<?php echo $listadoSerializadoEmpBaja ?>">
   <input name="idControl" type="hidden" value="<?php echo $idControl ?>">
</form> 
</body>