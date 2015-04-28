<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionUsimra.php"); 
include($libPath."claves.php"); 
set_time_limit(0);
print("<br>");

$utlimoNroControl = $_POST['ultimocontrol'];
$totalDdjj = $_POST['totalDdjj'];
$listadoSerializadoEmpresa = $_POST['empresas'];
$listadoSerializadoEmpleados = $_POST['empleados'];
$idControl = $_POST['idControl'];

//$hostaplicativo = $hostUsimra;
$hostaplicativo = "localhost";
$usuarioaplicativo = $usuarioUsimra;
$claveaplicativo = $claveUsimra;
$dbaplicativo =  mysql_connect($hostaplicativo, $usuarioaplicativo, $claveaplicativo);
if (!$dbaplicativo) {
    die('No pudo conectarse: ' . mysql_error());
}
$dbnameaplicativo = $baseUsimraNewAplicativo;
mysql_select_db($dbnameaplicativo);

$sqlFamiliar = "select * from familia where bajada = 0";
$resFamiliar = mysql_query($sqlFamiliar,$dbaplicativo); 
$canFamiliar = mysql_num_rows($resFamiliar); 
if ($canFamiliar > 0) {
	$n = 0;
	$u = 0;
	$familiaInsert = 0;
	$listadoFamiliares = array();
	$sqlEjecuciones = array();
	$sqlUpdateBajadaFamilia = array();
	
	while($rowFamiliar = mysql_fetch_assoc($resFamiliar)) {
		$idFamiliaInsert = $rowFamiliar['id'];
		$cuilFamiliaInsert = $rowFamiliar['nrcuil'];
		$codparFamiliaInsert = $rowFamiliar['codpar'];
		
		$sqlFamiliaInsert = "select nrcuil from familiausimra where id = $idFamiliaInsert and nrcuil = $cuilFamiliaInsert and codpar = '$codparFamiliaInsert'";
		$resFamiliaInsert = mysql_query($sqlFamiliaInsert,$db); 
		$canFamiliaInsert = mysql_num_rows($resFamiliaInsert); 
		if ($canFamiliaInsert == 0) {
			$sqlInsertFami = "INSERT INTO familiausimra VALUE(
			'".$rowFamiliar['id']."','".$rowFamiliar['nrcuit']."','".$rowFamiliar['nrcuil']."','".$rowFamiliar['nombre']."','".$rowFamiliar['apelli']."',
			'".$rowFamiliar['codpar']."','".$rowFamiliar['ssexxo']."','".$rowFamiliar['fecnac']."','".$rowFamiliar['fecing']."','".$rowFamiliar['tipdoc']."',
			'".$rowFamiliar['nrodoc']."','".$rowFamiliar['benefi']."','1')";
			
			$sqlFamiliaInsert = "select nrcuil from familiadebajausimra where id = $idFamiliaInsert and nrcuil = $cuilFamiliaInsert and codpar = '$codparFamiliaInsert'";
			$resFamiliaInsert = mysql_query($sqlFamiliaInsert,$db); 
			$canFamiliaInsert = mysql_num_rows($resFamiliaInsert); 
			if ($canFamiliaInsert > 0) {
				$listadoFamiliares[$n] = array("estado" => 'B', "cuil" =>  $rowFamiliar['nrcuil'], "cuit" =>  $rowFamiliar['nrcuit'], "parentesco" =>  $rowFamiliar['codpar'],"nombre" => $rowFamiliar['apelli'].", ".$rowFamiliar['nombre']);
				$sqlDeleteFami = "DELETE from familiadebajausimra where id = $idFamiliaInsert and nrcuil = $cuilFamiliaInsert and codpar = $codparFamiliaInsert";
				$sqlEjecuciones[$n] = $sqlInsertFami;
				$n++;
				$sqlEjecuciones[$n] = $sqlDeleteFami;
				$familiaInsert++;
			} else {
				$listadoFamiliares[$n] = array("estado" => 'I', "cuil" =>  $rowFamiliar['nrcuil'], "cuit" =>  $rowFamiliar['nrcuit'], "parentesco" =>  $rowFamiliar['codpar'], "nombre" => $rowFamiliar['apelli'].", ".$rowFamiliar['nombre']);
				$sqlEjecuciones[$n] = $sqlInsertFami;
				$familiaInsert++;
			}
		} else {
			$listadoFamiliares[$n] = array("estado" => 'E', "cuil" =>  $rowFamiliar['nrcuil'], "cuit" =>  $rowFamiliar['nrcuit'], "parentesco" =>  $rowFamiliar['codpar'], "nombre" => $rowFamiliar['apelli'].", ".$rowFamiliar['nombre']);
		}
		$sqlUpdateBajadaFamilia[$u] = "UPDATE familia SET bajada = 1 WHERE id = $idFamiliaInsert and nrcuil = $cuilFamiliaInsert and codpar = '$codparFamiliaInsert'";
		$u++;
		$n++;
	}
	
	$updateControl = "UPDATE aporcontroldescarga SET cantfamiliares = $familiaInsert WHERE id = '".$idControl."'";
	
	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();
		
		foreach($sqlEjecuciones as $sql) {
			print($sql."<br>");
			$dbh->exec($sql);
		}
		
		$hostname = $hostaplicativo;
		$dbnameweb = $baseUsimraNewAplicativo;
		$dbhweb = new PDO("mysql:host=$hostname;dbname=$dbnameweb",$usuarioaplicativo,$claveaplicativo);
		$dbhweb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbhweb->beginTransaction();
		
		foreach($sqlUpdateBajadaFamilia as $sqlUpdate) {
			print($sqlUpdate."<br>");
			$dbhweb->exec($sqlUpdate);
		}
		
		print($updateControl."<br>");
		$dbh->exec($updateControl);
		
		$dbh->commit();		
		$dbhweb->commit();	
		
	} catch(PDOException $e) {
		echo $e->getMessage();
		$dbh->rollback();
		$dbhweb->rollback();	
	}
	
}

$listadoSerializadoFamiliares = serialize($listadoFamiliares);
$listadoSerializadoFamiliares = urlencode($listadoSerializadoFamiliares);

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

echo("<pre>");
print_r($listadoFamiliares);
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
	$.blockUI({ message: "<h1>Descargando Titulares de Baja... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
	function formSubmit() {
		document.getElementById("descargaEmpBaja").submit();
	}
</script>

<body bgcolor="#B2A274" onload="formSubmit();">
<form action="descargaEmpBaja.php" id="descargaEmpBaja" method="POST"> 
   <input name="ultimocontrol" type="hidden" value="<?php echo $utlimoNroControl ?>">
   <input name="totalDdjj" type="hidden" value="<?php echo $totalDdjj ?>">
   <input name="empresas" type="hidden" value="<?php echo $listadoSerializadoEmpresa ?>">
   <input name="empleados" type="hidden" value="<?php echo $listadoSerializadoEmpleados ?>">
   <input name="familiares" type="hidden" value="<?php echo $listadoSerializadoFamiliares ?>">
   <input name="idControl" type="hidden" value="<?php echo $idControl ?>">
</form> 
</body>