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
$listadoSerializadoEmpBaja = $_POST['empbaja'];

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

$sqlFamiliarBaja = "select * from familiadebaja where bajada = 0";
$resFamiliarBaja = mysql_query($sqlFamiliarBaja,$dbaplicativo); 
$canFamiliarBaja = mysql_num_rows($resFamiliarBaja); 
if ($canFamiliarBaja > 0) {
	$n = 0;
	$u = 0;
	$familiaBajaInsert = 0;
	$listadoFamBaja = array();
	$sqlEjecuciones = array();
	$sqlUpdateBajadaFamBaja = array();
	
	while($rowFamiliarBaja = mysql_fetch_assoc($resFamiliarBaja)) {
		$idFamiliaInsertBaja = $rowFamiliarBaja['id'];
		$cuilFamiliaInsertBaja = $rowFamiliarBaja['nrcuil'];
		$codparFamiliaInsertBaja = $rowFamiliarBaja['codpar'];
		
	    $sqlFamiliaInsertBaja = "select nrcuil from familiadebajausimra where id = $idFamiliaInsertBaja and nrcuil = $cuilFamiliaInsertBaja and codpar = '$codparFamiliaInsertBaja'";
		$resFamiliaInsertBaja = mysql_query($sqlFamiliaInsertBaja,$db); 
		$canFamiliaInsertBaja = mysql_num_rows($resFamiliaInsertBaja); 
		if ($canFamiliaInsertBaja == 0) {
			$sqlInsertFamiBaja = "INSERT INTO familiadebajausimra VALUE(
			'".$rowFamiliarBaja['id']."','".$rowFamiliarBaja['nrcuit']."','".$rowFamiliarBaja['nrcuil']."','".$rowFamiliarBaja['nombre']."','".$rowFamiliarBaja['apelli']."',
			'".$rowFamiliarBaja['codpar']."','".$rowFamiliarBaja['ssexxo']."','".$rowFamiliarBaja['fecnac']."','".$rowFamiliarBaja['fecing']."','".$rowFamiliarBaja['tipdoc']."',
			'".$rowFamiliarBaja['nrodoc']."','".$rowFamiliarBaja['benefi']."','1')";
		
			$sqlFamiliaInsertBaja = "select nrcuil from familiausimra where id = $idFamiliaInsertBaja and nrcuil = $cuilFamiliaInsertBaja and codpar = '$codparFamiliaInsertBaja'";
			$resFamiliaInsertBaja = mysql_query($sqlFamiliaInsertBaja,$db); 
			$canFamiliaInsertBaja = mysql_num_rows($resFamiliaInsertBaja); 
			if ($canFamiliaInsertBaja > 0) {
				$listadoFamBaja[$n] = array("estado" => 'A', "cuil" =>  $rowFamiliarBaja['nrcuil'], "cuit" =>  $rowFamiliarBaja['nrcuit'], "parentesco" =>  $rowFamiliarBaja['codpar'], "nombre" => $rowFamiliarBaja['apelli'].", ".$rowFamiliarBaja['nombre']);
				$sqlDeleteFamiBaja = "DELETE from familiausimra where id = $idFamiliaInsertBaja and nrcuil = $cuilFamiliaInsertBaja and codpar = '$codparFamiliaInsertBaja'";
				$sqlEjecuciones[$n] = $sqlDeleteFamiBaja;
				$n++;
				$sqlEjecuciones[$n] = $sqlInsertFamiBaja;
				$familiaBajaInsert++;	
			} else {
				$listadoFamBaja[$n] = array("estado" => 'I', "cuil" =>  $rowFamiliarBaja['nrcuil'], "cuit" =>  $rowFamiliarBaja['nrcuit'], "parentesco" =>  $rowFamiliarBaja['codpar'], "nombre" => $rowFamiliarBaja['apelli'].", ".$rowFamiliarBaja['nombre']);
				$sqlEjecuciones[$n] = $sqlInsertFamiBaja;
				$familiaBajaInsert++;
			}
		} else {
			$listadoFamBaja[$n] = array("estado" => 'E', "cuil" =>  $rowFamiliarBaja['nrcuil'], "cuit" =>  $rowFamiliarBaja['nrcuit'], "parentesco" =>  $rowFamiliarBaja['codpar'], "nombre" => $rowFamiliarBaja['apelli'].", ".$rowFamiliarBaja['nombre']);
		}
		
		$sqlUpdateBajadaFamBaja[$u] = "UPDATE familiadebaja SET bajada = 1 WHERE id = $idFamiliaInsertBaja and nrcuil = $cuilFamiliaInsertBaja and codpar = '$codparFamiliaInsertBaja'";
		$n++;
		$u++;
	}
	
	$updateControl = "UPDATE aporcontroldescarga SET cantfamiliaresbaja = $familiaBajaInsert WHERE id = '".$idControl."'";
	
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
		
		foreach($sqlUpdateBajadaFamBaja as $sqlUpdate) {
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

$listadoSerializadoFamBaja = serialize($listadoFamBaja);
$listadoSerializadoFamBaja = urlencode($listadoSerializadoFamBaja);

/*
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
$listadoEmpBaja = unserialize(urldecode($_POST['empbaja']));
echo("<pre>");
print_r($listadoEmpBaja);
echo("</pre>");
echo("<pre>");
print_r($listadoFamBaja);
echo("</pre>");
*/


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Descarga Aplicativo DDJJ :.</title>

<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
	$.blockUI({ message: "<h1>Generando Informe de Descasrga... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
	function formSubmit() {
		document.getElementById("resultados").submit();
	}
</script>

<body bgcolor="#B2A274" onload="formSubmit();">
<form action="informeResultadoDescarga.php" id="resultados" method="POST"> 
   <input name="nroControl" type="hidden" value="<?php echo $nroControl ?>">
   <input name="ultimocontrol" type="hidden" value="<?php echo $utlimoNroControl ?>">
   <input name="totalDdjj" type="hidden" value="<?php echo $totalDdjj ?>">
   <input name="empresas" type="hidden" value="<?php echo $listadoSerializadoEmpresa ?>">
   <input name="empleados" type="hidden" value="<?php echo $listadoSerializadoEmpleados ?>">
   <input name="familiares" type="hidden" value="<?php echo $listadoSerializadoFamiliares ?>">
   <input name="empbaja" type="hidden" value="<?php echo $listadoSerializadoEmpBaja ?>">
   <input name="fambaja" type="hidden" value="<?php echo $listadoSerializadoFamBaja ?>">
   <input name="idControl" type="hidden" value="<?php echo $idControl ?>">
</form> 
</body>