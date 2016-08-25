<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."claves.php"); 
if($_SERVER['SERVER_NAME'] != "poseidon") {
	header('location: /madera/ospim/moduloNoDisponible.php');
	exit(0);
} 
set_time_limit(0);

$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];

$sqlControl = "SELECT nrocontrol FROM aporcontroldescarga ORDER BY nrocontrol DESC LIMIT 1";
$resControl = mysql_query($sqlControl,$db);
$canControl = mysql_num_rows($resControl);
$nroControl = 0;
if ($canControl != 0) {
	$rowControl = mysql_fetch_assoc($resControl);
	$nroControl = $rowControl['nrocontrol'];
}

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	
	$stmt = $dbh->prepare("INSERT INTO aporcontroldescarga VALUE(?,?,?,?,?,?,?,?,?,?,?,?)");
	$stmt->execute(array('DEFAULT', $usuarioregistro,$fecharegistro,0,0,0,$nroControl,0,0,0,0,0));
	$idControl = $dbh->lastInsertId();
	$dbh->commit();
} catch(PDOException $e) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}	

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
		var direDesEmpresa = "Location://"+window.location.hostname+"/usimra/aportes/descarga/descargaEmpresas.php";
		document.forms.descargaEmpresa.action = direDesEmpresa;
		document.getElementById("descargaEmpresa").submit();
	}
</script>
</head>
<body bgcolor="#CCCCCC" onload="formSubmit();">
<form action="#" id="descargaEmpresa" method="post"> 
   <input name="nroControl" type="hidden" value="<?php echo $nroControl ?>" />
   <input name="idControl" type="hidden" value="<?php echo $idControl ?>" />
</form> 
</body>
</html>
