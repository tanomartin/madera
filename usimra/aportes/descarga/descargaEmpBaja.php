<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionUsimra.php"); 
include($libPath."claves.php"); 
set_time_limit(0);
print("<br>");

$nroControl = $_POST['nroControl'];
$utlimoNroControl = $_POST['ultimocontrol'];
$totalDdjj = $_POST['totalDdjj'];
$listadoSerializadoEmpresa = $_POST['empresas'];
$listadoSerializadoEmpleados = $_POST['empleados'];
$listadoSerializadoFamiliares = $_POST['familiares'];
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


$sqlEmpleadosdebaja = "select * from empleadosdebaja where bajada = 0";
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
		$cuitInsert = $rowEmpleadodebaja['nrcuit'];
		$cuilInsert = $rowEmpleadodebaja['nrcuil'];
		$sqlEmpleadoInsertBaja = "select nrcuil from empleadosdebajausimra where nrcuil = $cuilInsert and nrcuit = $cuitInsert";
		$resEmpleadoInsertBaja = mysql_query($sqlEmpleadoInsertBaja,$db); 
		$canEmpleadoInsertBaja = mysql_num_rows($resEmpleadoInsertBaja); 
		if ($canEmpleadoInsertBaja == 0) {
			$sqlInsertTituBaja = "INSERT INTO empleadosdebajausimra VALUE(
			'".$rowEmpleadodebaja['nrcuit']."','".$rowEmpleadodebaja['nrcuil']."','".$rowEmpleadodebaja['apelli']."','".$rowEmpleadodebaja['nombre']."',
			'".$rowEmpleadodebaja['fecing']."','".$rowEmpleadodebaja['tipdoc']."','".$rowEmpleadodebaja['nrodoc']."','".$rowEmpleadodebaja['ssexxo']."',
			'".$rowEmpleadodebaja['fecnac']."','".$rowEmpleadodebaja['estciv']."','".$rowEmpleadodebaja['direcc']."','".$rowEmpleadodebaja['locale']."',
			'".$rowEmpleadodebaja['copole']."','".$rowEmpleadodebaja['provin']."','".$rowEmpleadodebaja['nacion']."','".$rowEmpleadodebaja['catego']."',
			'".$rowEmpleadodebaja['activo']."','1')";
			
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
		
		$sqlUpdateBajadaEmpBaja[$u] = "UPDATE empleadosdebaja SET bajada = 1 WHERE nrcuil = $cuilInsert and nrcuit = $cuitInsert";
		$n++;
		$u++;
	}
	
	$updateControl = "UPDATE aporcontroldescarga SET canttitularesbaja = $empleadoBajaInsert WHERE id = '".$idControl."'";
	
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
		echo $e->getMessage();
		$dbh->rollback();
		$dbhweb->rollback();	
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

<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
	$.blockUI({ message: "<h1>Descargando Familiares de Baja... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
	function formSubmit() {
		document.getElementById("descargaFamBaja").submit();
	}
</script>

<body bgcolor="#B2A274" onload="formSubmit();">
<form action="descargaFamBaja.php" id="descargaFamBaja" method="POST"> 
   <input name="nroControl" type="hidden" value="<?php echo $nroControl ?>">
   <input name="ultimocontrol" type="hidden" value="<?php echo $utlimoNroControl ?>">
   <input name="totalDdjj" type="hidden" value="<?php echo $totalDdjj ?>">
   <input name="empresas" type="hidden" value="<?php echo $listadoSerializadoEmpresa ?>">
   <input name="empleados" type="hidden" value="<?php echo $listadoSerializadoEmpleados ?>">
   <input name="familiares" type="hidden" value="<?php echo $listadoSerializadoFamiliares ?>">
   <input name="empbaja" type="hidden" value="<?php echo $listadoSerializadoEmpBaja ?>">
   <input name="idControl" type="hidden" value="<?php echo $idControl ?>">
</form> 
</body>