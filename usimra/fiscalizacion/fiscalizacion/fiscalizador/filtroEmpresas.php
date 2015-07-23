<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
//Para que se vea el blockUI
print("<br>");
//*************************

$tipo = $_POST['tipo'];
if ($tipo == "delega") {
 	$delega = $_POST['selectDelegacion'];
	$codpos = $_POST['codpos'];
	$empresas = $_POST['empresas'];
	$deuda = $_POST['deuda'];
	$solic = $_POST['solicDele'];
	if ($codpos != "") {
		$sqlEmpresasJuris = "select e.cuit, e.iniobliosp, e.nombre, j.codidelega from jurisdiccion j, empresas e where j.codidelega = $delega and j.numpostal = $codpos and j.cuit = e.cuit ";
	} else {
		$sqlEmpresasJuris = "select e.cuit, e.iniobliosp, e.nombre, j.codidelega from jurisdiccion j, empresas e where j.codidelega = $delega and j.cuit = e.cuit ";
	}
	$resEmpresasJuris = mysql_query($sqlEmpresasJuris,$db);
	$i = 0;
	while($row = mysql_fetch_assoc($resEmpresasJuris)){
		$listadoEmpresas[$i] = $row;
		$i = $i + 1;
	}
	if (sizeof($listadoEmpresas) == 0) {
		header ("Location: fiscalizador.php?err=2");
		exit(0);
	}
} else {
	$cuit = $_POST['cuit'];
	$origen = $_POST['origenRequerimento'];
	$solicitante = $_POST['solicitante'];
	$motivo = $_POST['motivo'];
	$sqlEmpresas = "select cuit from empresas where cuit = $cuit ";
	$resEmpresas = mysql_query($sqlEmpresas,$db);
	$cant = mysql_num_rows($resEmpresas);
	if ($cant != 0) {
			header ("Location: fiscalizadorPorCuit.php?cuit=$cuit&origen=$origen&soli=$solicitante&motivo=$motivo&tipo=activa");
			exit(0);
	} else {
		$sqlEmpresas = "select cuit from empresasdebaja where cuit = $cuit ";
		$resEmpresas = mysql_query($sqlEmpresas,$db);
		$cant = mysql_num_rows($resEmpresas);
		if ($cant != 0) {
 			header ("Location: fiscalizador.php?err=6");
 			exit(0);
		} else {	
			header ("Location: fiscalizador.php?err=1");
			exit(0);
		}
	}
}

$filtros['empresas'] = $empresas;
$filtros['deuda'] = $deuda;
$filtros['solicitante'] = $solic;

$listadoSerializado = serialize($listadoEmpresas);
$listadoSerializado = urlencode($listadoSerializado);
$filtrosSerializado = serialize($filtros);
$filtrosSerializado = urlencode($filtrosSerializado);
?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Fiscalizador USIMRA :.</title>

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">
	$.blockUI({ message: "<h1>Filtrando Por Deuda Nominal y Cantidad de Empresas a Fiscalizar... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
	function formSubmit() {
		document.getElementById("filtroEmpresas").submit();
	}
</script>
</head>
<body bgcolor="#B2A274" onload="formSubmit();">
<form action="fiscalizadorGlobal.php" id="filtroEmpresas" method="post"> 
   <input name="empresas" type="hidden" value="<?php echo $listadoSerializado ?>"/>
   <input name="filtros" type="hidden" value="<?php echo $filtrosSerializado ?>"/>
</form> 
</body>
</html>