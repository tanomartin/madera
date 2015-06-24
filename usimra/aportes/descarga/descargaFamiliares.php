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
		$sqlFamiliaInsert = "select nrcuil from familiausimra where id = $idFamiliaInsert";
		$resFamiliaInsert = mysql_query($sqlFamiliaInsert,$db); 
		$canFamiliaInsert = mysql_num_rows($resFamiliaInsert); 
		if ($canFamiliaInsert == 0) {
			$sqlInsertFami = "INSERT INTO familiausimra VALUE(
			'".$rowFamiliar['id']."','".$rowFamiliar['nrcuit']."','".$rowFamiliar['nrcuil']."','".$rowFamiliar['nombre']."','".$rowFamiliar['apelli']."',
			'".$rowFamiliar['codpar']."','".$rowFamiliar['ssexxo']."','".$rowFamiliar['fecnac']."','".$rowFamiliar['fecing']."','".$rowFamiliar['tipdoc']."',
			'".$rowFamiliar['nrodoc']."','".$rowFamiliar['benefi']."','1')";
			
			$sqlFamiliaInsert = "select nrcuil from familiadebajausimra where id = $idFamiliaInsert";
			$resFamiliaInsert = mysql_query($sqlFamiliaInsert,$db); 
			$canFamiliaInsert = mysql_num_rows($resFamiliaInsert); 
			if ($canFamiliaInsert > 0) {
				$listadoFamiliares[$n] = array("estado" => 'B', "cuil" =>  $rowFamiliar['nrcuil'], "cuit" =>  $rowFamiliar['nrcuit'], "parentesco" =>  $rowFamiliar['codpar'],"nombre" => $rowFamiliar['apelli'].", ".$rowFamiliar['nombre']);
				$sqlDeleteFami = "DELETE from familiadebajausimra where id = $idFamiliaInsert";
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
			$listadoFamiliares[$n] = array("estado" => 'M', "cuil" =>  $rowFamiliar['nrcuil'], "cuit" =>  $rowFamiliar['nrcuit'], "parentesco" =>  $rowFamiliar['codpar'], "nombre" => $rowFamiliar['apelli'].", ".$rowFamiliar['nombre']);
			$sqlUpdateFami = "UDPATE familiausimra SET 
									nombre = '".$rowFamiliar['nombre']."',
									apelli = '".$rowFamiliar['apelli']."',
									codpar = '".$rowFamiliar['codpar']."',
									ssexxo = '".$rowFamiliar['ssexxo']."',
									fecnac = '".$rowFamiliar['fecnac']."',
									fecing = '".$rowFamiliar['fecing']."',
									tipdoc = '".$rowFamiliar['tipdoc']."',
									nrodoc = '".$rowFamiliar['nrodoc']."',
									benefi = '".$rowFamiliar['benefi']."'
								where id = $idFamiliaInsert";
			$sqlEjecuciones[$n] = $sqlUpdateFami;	
		}
		$sqlUpdateBajadaFamilia[$u] = "UPDATE familia SET bajada = 1 WHERE id = $idFamiliaInsert";
		$u++;
		$n++;
	}
	
	$updateControl = "UPDATE aporcontroldescarga SET cantidadfamiliares = $familiaInsert WHERE id = '".$idControl."'";
	
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
		
		foreach($sqlUpdateBajadaFamilia as $sqlUpdate) {
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

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
	$.blockUI({ message: "<h1>Descargando Titulares de Baja... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
	function formSubmit() {
		document.getElementById("descargaEmpBaja").submit();
	}
</script>
</head>
<body bgcolor="#B2A274" onload="formSubmit();">
<form action="descargaEmpBaja.php" id="descargaEmpBaja" method="post"> 
   <input name="nroControl" type="hidden" value="<?php echo $_POST['nroControl'] ?>"/>
   <input name="empresas" type="hidden" value="<?php echo $_POST['empresas'] ?>"/>
   <input name="empleados" type="hidden" value="<?php echo $_POST['empleados'] ?>"/>
   <input name="familiares" type="hidden" value="<?php echo $listadoSerializadoFamiliares ?>"/>
   <input name="idControl" type="hidden" value="<?php echo $idControl ?>"/>
</form> 
</body>
</html>