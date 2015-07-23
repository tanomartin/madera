<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
set_time_limit(0);
//Para que se vea el blockUI
print("<br>");
//*************************

function calculoPersonalPromedio($cuit, $anoinicio, $mesinicio, $anofin, $mesfin, $db) {
	$sqlCantPersonalPromedio = "select avg(totalpersonal) from cabddjjospim where cuit = $cuit and ((anoddjj > $anoinicio and anoddjj <= $anofin) or (anoddjj = $anoinicio and mesddjj >= $mesinicio))";
	$resCantPersonalPromedio = mysql_query($sqlCantPersonalPromedio,$db);
	$rowCantPersonalPromedio = mysql_fetch_assoc($resCantPersonalPromedio);
	$resultado = $rowCantPersonalPromedio['avg(totalpersonal)'];
	return $resultado;
}

/****************************************************************************************/

$listadoSerializado=$_POST['empresas'];
$filtrosSerializado=$_POST['filtros'];

$listadoEmpresas = unserialize(urldecode($listadoSerializado));
$filtros = unserialize(urldecode($filtrosSerializado));
if ($filtros['empleados'] != '') {
	$n = 0;
	for ($i=0; $i < sizeof($listadoEmpresas); $i++) {
		$cuit = $listadoEmpresas[$i]['cuit'];
		$fechaInicio = $listadoEmpresas[$i]['iniobliosp'];
		include($_SERVER['DOCUMENT_ROOT']."/madera/lib/limitesTemporalesEmpresas.php");
		$empleadosPromedio =  calculoPersonalPromedio($cuit, $anoinicio, $mesinicio, $anofin, $mesfin, $db);
		if ($empleadosPromedio >= $filtros['empleados']) {
			$listadoEmpresasEmpleados[$n] = $listadoEmpresas[$i];
			$n = $n + 1;
		}
	}
	if (sizeof($listadoEmpresasEmpleados) == 0) {
		header ("Location: fiscalizador.php?err=3");
	} else {
		$listadoSerializado = serialize($listadoEmpresasEmpleados);
		$listadoSerializado = urlencode($listadoSerializado);
		$filtrosSerializado = serialize($filtros);
		$filtrosSerializado = urlencode($filtrosSerializado);
	}
} else {
	$listadoSerializado = serialize($listadoEmpresas);
	$listadoSerializado = urlencode($listadoSerializado);
	$filtrosSerializado = serialize($filtros);
	$filtrosSerializado = urlencode($filtrosSerializado);
}

//print("EMPRESAS<br>");
//var_dump($listadoEmpresas);
//print("EMPRESAS Filtradas<br>");
//var_dump($listadoEmpresasEmpleados);

//cambio la hora de secion por ahora para no perder la misma
$ahora = date("Y-n-j H:i:s"); 
$_SESSION["ultimoAcceso"] = $ahora;
?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Fiscalizador OSPIM :.</title>

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">
	$.blockUI({ message: "<h1>Filtrando Por Deuda Nominal y Cantidad de Empresas a Fiscalizar... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
	function formSubmit() {
		document.getElementById("filtroEmpresasEmpleados").submit();
	}
</script>
</head>
<body onload="formSubmit();">
<form action="fiscalizadorGlobal.php" id="filtroEmpresasEmpleados" method="post"> 
   <input name="empresas" type="hidden" value="<?php echo $listadoSerializado ?>"/>
   <input name="filtros" type="hidden" value="<?php echo $filtrosSerializado ?>"/>
</form> 
</body>
</html>
