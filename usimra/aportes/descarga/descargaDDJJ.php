<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionUsimra.php"); 
include($libPath."claves.php"); 
set_time_limit(0);
print("<br>");

//$hostaplicativo = $hostUsimra;
$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];
$hostaplicativo = "localhost";
$usuarioaplicativo = $usuarioUsimra;
$claveaplicativo = $claveUsimra;
$dbaplicativo =  mysql_connect($hostaplicativo, $usuarioaplicativo, $claveaplicativo);
if (!$dbaplicativo) {
    die('No pudo conectarse: ' . mysql_error());
}
$dbnameaplicativo = $baseUsimraNewAplicativo;
mysql_select_db($dbnameaplicativo);

$sqlControl = "SELECT nrocontrol FROM aporcontroldescarga ORDER BY nrocontrol DESC LIMIT 1";
$resControl = mysql_query($sqlControl,$db); 
$canControl = mysql_num_rows($resControl); 
if ($canControl != 0) {
	$rowControl = mysql_fetch_assoc($resControl);
	$nroControl = $rowControl['nrocontrol'];
} else {
	$nroControl = 0;
}	

$sqlDdjjConDocu = "SELECT * FROM ddjjcondocu WHERE nrctrl > $nroControl ORDER BY nrctrl ASC";
$resDdjjConDocu = mysql_query($sqlDdjjConDocu,$dbaplicativo); 
$canDdjjConDocu = mysql_num_rows($resDdjjConDocu); 
$totalDdjj = 0;
if ($canDdjjConDocu > 0) {
	$cantDdjj = 0;
	$ddjjAIngresar = array();
	while($rowDdjjConDocu = mysql_fetch_assoc($resDdjjConDocu)) {
		$sqlInsertDdjj = "INSERT INTO ddjjusimra VALUE (".$rowDdjjConDocu['id'].",'".$rowDdjjConDocu['nrcuit']."','".$rowDdjjConDocu['nrcuil']."',".$rowDdjjConDocu['permes'].",".$rowDdjjConDocu['perano'].",".$rowDdjjConDocu['remune'].",".$rowDdjjConDocu['apo060'].",".$rowDdjjConDocu['apo100'].",".$rowDdjjConDocu['apo150'].",".$rowDdjjConDocu['totapo'].",".$rowDdjjConDocu['recarg'].",".$rowDdjjConDocu['nfilas'].",'".$rowDdjjConDocu['instrumento']."','".$rowDdjjConDocu['nrctrl']."','".$rowDdjjConDocu['observ']."')";
		$ddjjAIngresar[$cantDdjj] = $sqlInsertDdjj;
		$utlimoNroControl = $rowDdjjConDocu['nrctrl'];
		$cantDdjj++;
		if ($rowDdjjConDocu['nrcuil'] == "99999999999") {
			$totalDdjj++;
		}
	}
	
	try { 
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();
		
		foreach($ddjjAIngresar as $ddjjInsert) {
			print($ddjjInsert."<br>");
			$dbh->exec($ddjjInsert);
		}
		
		$stmt = $dbh->prepare("INSERT INTO aporcontroldescarga VALUE(?,?,?,?,?,?,?,?,?,?)"); 
		$stmt->execute(array('DEFAULT', $usuarioregistro,$fecharegistro,$totalDdjj,$utlimoNroControl,0,0,0,0,0)); 
		$idControl = $dbh->lastInsertId(); 	
		$dbh->commit();	
		
		
	} catch(PDOException $e) {
		echo $e->getMessage();
		$dbh->rollback();
	}	
} else {
	$utlimoNroControl = $nroControl;
	try { 
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
	    $stmt = $dbh->prepare("INSERT INTO aporcontroldescarga VALUE(?,?,?,?,?,?,?,?,?,?)"); 
		$dbh->beginTransaction();
		$stmt->execute(array('DEFAULT', $usuarioregistro,$fecharegistro,$totalDdjj,$utlimoNroControl,0,0,0,0,0)); 
		$idControl = $dbh->lastInsertId(); 	
		$dbh->commit();	

	} catch(PDOException $e) {
		echo $e->getMessage();
		$dbh->rollback();
	}
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

<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
	$.blockUI({ message: "<h1>Descargando Nuevas Empresas... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
	function formSubmit() {
		document.getElementById("descargaEmpresa").submit();
	}
</script>

<body bgcolor="#B2A274" onload="formSubmit();">
<form action="descargaEmpresas.php" id="descargaEmpresa" method="POST"> 
   <input name="ultimocontrol" type="hidden" value="<?php echo $utlimoNroControl ?>">
   <input name="totalDdjj" type="hidden" value="<?php echo $totalDdjj ?>">
   <input name="idControl" type="hidden" value="<?php echo $idControl ?>">
</form> 
</body>