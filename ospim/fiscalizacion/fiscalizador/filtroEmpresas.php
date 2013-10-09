<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionOspim.php"); 
//Para que se vea el blockUI
print("PAGINA FILTRO EMPRESA<br>");
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
		$listadoEmpresas[$i] = $row;
		$i = $i + 1;
	}
	if (sizeof($listadoEmpresas) == 0) {
		header ("Location: menuFiscalizador.php?err=2");
	}
} else {
	$cuit = $datos[7];
	$origen = $datos[8];
	$solicitante = $datos[9];
	$motivo = $datos[10];
	$sqlEmpresas = "select cuit, iniobliosp, nombre from empresas where cuit = $cuit ";
	$resEmpresas = mysql_query($sqlEmpresas,$db);
	$cant = mysql_num_rows($resEmpresas);
	if ($cant != 0) {
		$row = mysql_fetch_assoc($resEmpresas);
		$listadoEmpresas[0] = $row;
			header ("Location: fiscalizadorPorCuit.php?cuit=$cuit&origen=$origen&soli=$solicitante&motivo=$motivo");
	} else {
		header ("Location: menuFiscalizador.php?err=1");
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
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Fiscalizador OSPIM :.</title>

<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
	$.blockUI({ message: "<h1>Filtrando Personal Promedio... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
	function formSubmit() {
		document.getElementById("filtroEmpresas").submit();
	}
</script>

<body onload="formSubmit();">
<form action="filtroPersonalPromedio.php" id="filtroEmpresas" method="POST"> 
   <input name="empresas" type="hidden" value="<?php echo $listadoSerializado ?>">
   <input name="filtros" type="hidden" value="<?php echo $filtrosSerializado ?>">
</form> 
</body>