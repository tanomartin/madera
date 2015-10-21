<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php"); 
include($libPath."claves.php"); 
set_time_limit(0);
print("<br>");

$hostaplicativo = $hostUsimra;
$hostaplicativo = "localhost";
$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];
$usuarioaplicativo = $usuarioUsimra;
$claveaplicativo = $claveUsimra;
$dbaplicativo =  mysql_connect($hostaplicativo, $usuarioaplicativo, $claveaplicativo);
if (!$dbaplicativo) {
    die('No pudo conectarse: ' . mysql_error());
}
$dbnameaplicativo = $baseUsimraNewAplicativo;
mysql_select_db($dbnameaplicativo);	

$sqlDdjjConDocu = "SELECT * FROM ddjjcondocu WHERE bajada = 0 ORDER BY nrctrl ASC";
$resDdjjConDocu = mysql_query($sqlDdjjConDocu,$dbaplicativo); 
$canDdjjConDocu = mysql_num_rows($resDdjjConDocu); 

$sqlControl = "SELECT nrocontrol FROM aporcontroldescarga ORDER BY nrocontrol DESC LIMIT 1";
$resControl = mysql_query($sqlControl,$db);
$canControl = mysql_num_rows($resControl);
if ($canControl != 0) {
	$rowControl = mysql_fetch_assoc($resControl);
	$nroControl = $rowControl['nrocontrol'];
} else {
	$nroControl = 0;
}

$totalDdjj = 0;
$cantDdjj = 0;
$cantInactivos = 0;
$ddjjAIngresar = array();
$inactivosAIngresar = array();
try { 
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	
	$dbhweb = new PDO("mysql:host=$hostaplicativo;dbname=$baseUsimraNewAplicativo",$usuarioaplicativo,$claveaplicativo);
	$dbhweb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbhweb->beginTransaction();
	
	$stmt = $dbh->prepare("INSERT INTO aporcontroldescarga VALUE(?,?,?,?,?,?,?,?,?,?,?,?)"); 
	$stmt->execute(array('DEFAULT', $usuarioregistro,$fecharegistro,0,0,0,0,0,0,0,0,0)); 
	$idControl = $dbh->lastInsertId(); 	
	
	if ($canDdjjConDocu > 0) {
		while($rowDdjjConDocu = mysql_fetch_assoc($resDdjjConDocu)) {
			$sqlInsertDdjj = "INSERT INTO ddjjusimra VALUE (".$rowDdjjConDocu['id'].",'".$rowDdjjConDocu['nrcuit']."','".$rowDdjjConDocu['nrcuil']."',".$rowDdjjConDocu['permes'].",".$rowDdjjConDocu['perano'].",".$rowDdjjConDocu['remune'].",".$rowDdjjConDocu['apo060'].",".$rowDdjjConDocu['apo100'].",".$rowDdjjConDocu['apo150'].",".$rowDdjjConDocu['totapo'].",".$rowDdjjConDocu['recarg'].",".$rowDdjjConDocu['nfilas'].",'".$rowDdjjConDocu['instrumento']."','".$rowDdjjConDocu['nrctrl']."','".$rowDdjjConDocu['observ']."','".$idControl."')";
			$ddjjAIngresar[$cantDdjj] = $sqlInsertDdjj;
			$utlimoNroControl = $rowDdjjConDocu['nrctrl'];
			$cantDdjj++;
			if ($rowDdjjConDocu['nrcuil'] == "99999999999") {
				$totalDdjj++;
				$wherein .= $rowDdjjConDocu['nrctrl'].",";
			}
		}
		$wherein = substr($wherein, 0, -1);
		
		foreach($ddjjAIngresar as $ddjjInsert) {
			//print($ddjjInsert."<br>");
			$dbh->exec($ddjjInsert);
		}
		
		$sqlUpdateDDJJBajada = "UPDATE ddjjcondocu SET bajada = 1 WHERE nrctrl in ($wherein)";
		$dbhweb->exec($sqlUpdateDDJJBajada);

		$sqlInactivos = "SELECT * FROM inactivos WHERE nrctrl in ($wherein)";
		$resInactivos = mysql_query($sqlInactivos,$dbaplicativo); 
		$canInactivos = mysql_num_rows($resInactivos); 
		if ($canInactivos > 0) {
			while($rowInactivos = mysql_fetch_assoc($resInactivos)) {
				$sqlInsertInactivos = "INSERT INTO ddjjinactivosusimra VALUE(".$rowInactivos['id'].",'".$rowInactivos['nrcuit']."','".$rowInactivos['nrcuil']."','".$rowInactivos['permes']."','".$rowInactivos['perano']."','".$rowInactivos['motivo']."','".$rowInactivos['nrctrl']."','".$idControl."')";
				$inactivosAIngresar[$cantInactivos] = $sqlInsertInactivos;
				$cantInactivos++;
			}
			foreach($inactivosAIngresar as $inactivos) {
				//print($inactivos."<br>");
				$dbh->exec($inactivos);
			}
		}	
	} else {
		$utlimoNroControl = $nroControl;
	}
	
	$cantActivos = $cantDdjj - $totalDdjj;
	$updateControl = "UPDATE aporcontroldescarga SET nrocontrol = $utlimoNroControl, cantidadddjj = $totalDdjj, cantidadactivos = $cantActivos, cantidadinactivos = $cantInactivos  WHERE id = ".$idControl;
	$dbh->exec($updateControl);
	
	$dbhweb->commit();
	$dbh->commit();		
	
} catch(PDOException $e) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$dbhweb->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/usimra/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}	

/*print("<br>");
print("ULTIMO: ".$utlimoNroControl."<br>");
print("TOTAL DE DJJJ: ".$totalDdjj."<br>");
print("CONTROL: ".$idControl."<br>");*/

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Descarga Aplicativo DDJJ :.</title>

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
	$.blockUI({ message: "<h1>Descargando Nuevas Empresas... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
	function formSubmit() {
		document.getElementById("descargaEmpresa").submit();
	}
</script>
</head>
<body bgcolor="#B2A274" onload="formSubmit();">
<form action="descargaEmpresas.php" id="descargaEmpresa" method="post"> 
   <input name="nroControl" type="hidden" value="<?php echo $nroControl ?>" />
   <input name="idControl" type="hidden" value="<?php echo $idControl ?>" />
</form> 
</body>
</html>
