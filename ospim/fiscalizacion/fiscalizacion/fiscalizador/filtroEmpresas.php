<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
//Para que se vea el blockUI
print("<br>");
//*************************
$datos = array_values($_POST);

$tipo = $datos[0];
if ($tipo == "delega") {
 	$delega = $datos[1];
	$codpos = $datos[2];
	$empleados = $datos[3];
	$empresas = $datos[4];
	$deuda = $datos[5];
	$solic = $datos[6];
	if ($codpos != "") {
		$sqlEmpresasJuris = "select e.cuit, e.iniobliosp, e.nombre, j.codidelega from jurisdiccion j, empresas e where j.codidelega = $delega and j.numpostal = $codpos and j.cuit = e.cuit ";
	} else {
		$sqlEmpresasJuris = "select e.cuit, e.iniobliosp, e.nombre, j.codidelega from jurisdiccion j, empresas e where j.codidelega = $delega and j.cuit = e.cuit ";
	}
	$resEmpresasJuris = mysql_query($sqlEmpresasJuris,$db);
	$i = 0;
	while($row = mysql_fetch_assoc($resEmpresasJuris)){
		$cuit = $row['cuit'];
		$sqlDelePrincipal = "select codidelega from jurisdiccion where cuit = $cuit order by disgdinero DESC limit 1";
		$resDelePrincipal = mysql_query($sqlDelePrincipal,$db);
		$rowDelePrincipal = mysql_fetch_assoc($resDelePrincipal);
		if ($rowDelePrincipal['codidelega'] == $delega) {
			$listadoEmpresas[$i] = $row;
			$i = $i + 1;
		}
	}
	if (sizeof($listadoEmpresas) == 0) {
		header ("Location: fiscalizador.php?err=2");
	}
} else {
	$cuit = $datos[7];
	$origen = $datos[8];
	$solicitante = $datos[9];
	$motivo = $datos[10];
	$sqlEmpresas = "select cuit from empresas where cuit = $cuit ";
	$resEmpresas = mysql_query($sqlEmpresas,$db);
	$cant = mysql_num_rows($resEmpresas);
	if ($cant != 0) {
			header ("Location: fiscalizadorPorCuit.php?cuit=$cuit&origen=$origen&soli=$solicitante&motivo=$motivo&tipo=activa");
	} else {
		$sqlEmpresas = "select cuit from empresasdebaja where cuit = $cuit ";
		$resEmpresas = mysql_query($sqlEmpresas,$db);
		$cant = mysql_num_rows($resEmpresas);
		if ($cant != 0) {
 			header ("Location: fiscalizador.php?err=6");
		} else {	
			header ("Location: fiscalizador.php?err=1");
		}
	}
}


$filtros['empleados'] = $empleados;
$filtros['empresas'] = $empresas;
$filtros['deuda'] = $deuda;
$filtros['solicitante'] = $solic;

$listadoSerializado = serialize($listadoEmpresas);
$listadoSerializado = urlencode($listadoSerializado);
$filtrosSerializado = serialize($filtros);
$filtrosSerializado = urlencode($filtrosSerializado);

//cambio la hora de secion por ahora para no perder la misma
$ahora = date("Y-n-j H:i:s"); 
$_SESSION["ultimoAcceso"] = $ahora; 
?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M�dulo Fiscalizador OSPIM :.</title>

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">
	$.blockUI({ message: "<h1>Filtrando Personal Promedio... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
	function formSubmit() {
		document.getElementById("filtroEmpresas").submit();
	}
</script>
</head>
<body onload="formSubmit();">
<form action="filtroPersonalPromedio.php" id="filtroEmpresas" method="post"> 
   <input name="empresas" type="hidden" value="<?php echo $listadoSerializado ?>"/>
   <input name="filtros" type="hidden" value="<?php echo $filtrosSerializado ?>"/>
</form> 
</body>
</html>