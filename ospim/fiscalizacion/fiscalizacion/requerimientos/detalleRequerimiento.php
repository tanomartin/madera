<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php");

$fecha = $_GET['fecha'];
$nroreq = $_GET['nroreq'];
$cuit = $_GET['cuit'];

$sqlDeta = "SELECT * from detfiscalizospim where nrorequerimiento = '$nroreq'";
$resDeta = mysql_query($sqlDeta,$db);


$sqlEmpresa = "SELECT * from empresas where cuit = '$cuit'";
$resEmpresa  = mysql_query($sqlEmpresa,$db);
$rowEmpresa  = mysql_fetch_array($resEmpresa)
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Detalle de Requerimientos :.</title>

<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript">
function abrirDDJJPagos(dire) {
	a= window.open(dire,"InfoPeriodoCuentaCorrienteEmpresa",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=10, left=10");
}
function abrirMasInfo(dire) {
	a= window.open(dire,"InfoPeriodoCuentaCorrienteEmpresa",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=10, left=10");
}

function checkall(seleccion) {
	if (seleccion.checked) {
    	 for (var i=0;i<document.editarReque.elements.length;i++) 
     		 if(document.editarReque.elements[i].type == "checkbox")	
				 document.editarReque.elements[i].checked=1;  
	} else {
		 for (var i=0;i<document.editarReque.elements.length;i++) 
     		 if(document.editarReque.elements[i].type == "checkbox")	
				 document.editarReque.elements[i].checked=0;  
	}
} 

function validar(formulario) {
	var grupo = formulario.periodos;
	var total = grupo.length;
	if (total == null) {
		alert("No se pueden eliminar todos los periodos.\nPara elminarlos todos debe anular el requerimiento");
		return false;
	}
	var checkeados = 0; 
	for (var i = 0; i < total; i++) {
		if (grupo[i].checked) {
			checkeados++;
		}
	}
	if (checkeados == total) {
		alert("No se pueden eliminar todos los periodos.\nPara elminarlos todos debe anular el requerimiento");
		return false;
	}
	if (checkeados == 0) {
		alert("Debe seleccionar el o los periodos que desea elminar");
		return false;
	}
	$.blockUI({ message: "<h1>Eliminando Periodos Seleccionados</h1>" });
	return true;
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><span style="text-align:center">
    <input type="button" name="volver" value="Volver" onclick="location.href = 'listarRequerimientos.php?fecha=<?php echo $fecha ?>'" />
  </span></p>
  	<form name="editarReque" onsubmit="return validar(this)" method="post" action="eliminarPeriodos.php" >
		<input name="cuit" type="text" value="<?php echo $cuit?>" style="display:none"/>
		<input name="fecha" type="text" value="<?php echo $fecha?>" style="display:none"/>
		<input name="nroreq" type="text" value="<?php echo $nroreq?>" style="display:none"/>
		<p class="Estilo2">Edici&oacute;n de Periodos  del  Requerimiento Nro. <?php echo $nroreq ?></p>
		<p class="Estilo2"><?php echo $cuit." - ".$rowEmpresa['nombre'] ?></p>
		<table width="600" border="1" align="center">
		  <tr style="font-size:12px">
			<th rowspan="2" width="65">Per�odo</th>
			<th rowspan="2">Status</th>
			<th colspan="2">DDJJ</th>
			<th rowspan="2">Deuda Nominal</th>
			<th rowspan="2"></th>
			<th rowspan="2"></th>
			<th rowspan="2"><input type="checkbox" name="selecAll" id="selecAll" onchange="checkall(this)" /></th>
		  </tr>
		  <tr style="font-size:12px">
		 	 <th>Remun.</th>
			<th>Cant. Personal </th>
		  </tr>
		  <?php while($rowDeta = mysql_fetch_array($resDeta)) { 
					print("<tr>");
					$ano = $rowDeta['anofiscalizacion'];
					$mes = $rowDeta['mesfiscalizacion'];
					$id = $ano."-".$mes;
					print("<td width='65'>".$rowDeta['mesfiscalizacion']."-".$ano."</td>");
					if ($rowDeta['statusfiscalizacion'] == 'S') {
						$status = "S/DDJJ";
					}
					if ($rowDeta['statusfiscalizacion'] == 'A') {
						$status = "Deuda";
					}
					if ($rowDeta['statusfiscalizacion'] == 'F') {
						$status = "P.F.T.";
					} 
					if ($rowDeta['statusfiscalizacion'] == 'M') {
						$status = "Ap.Menor.";
					}  
					print("<td>".$status."</td>");   
					print("<td>".$rowDeta['remundeclarada']."</td>");   
					print("<td>".$rowDeta['cantidadpersonal']."</td>"); 
					print("<td>".$rowDeta['deudanominal']."</td>");   
					$dire = "infoRequerimiento.php?cuit=".$cuit."&anio=".$ano."&mes=".$mes;     
					print ("<td><a href=javascript:abrirMasInfo('".$dire."')>+ Info</a></td>");
					if ($rowDeta['statusfiscalizacion'] == 'M' || $rowDeta['statusfiscalizacion'] == 'F') {
						$dire = "/madera/comun/empresas/abm/cuentas/detallePagos.php?cuit=".$cuit."&anio=".$ano."&mes=".$mes;
						print ("<td><a href=javascript:abrirDDJJPagos('".$dire."')>Pago</a></td>");
					} else {
						if ($rowDeta['statusfiscalizacion'] == 'A') {
							$dire = "/madera/comun/empresas/abm/cuentas/detalleDDJJ.php?cuit=".$cuit."&anio=".$ano."&mes=".$mes;
							print ("<td><a href=javascript:abrirDDJJPagos('".$dire."')>DDJJ</a></td>");
						} else {
							print("<td>-</td>"); 
						}
					}
					print("<td><input type='checkbox' name='".$id."' id='periodos' value='".$id."'></td>"); 
					print("</tr>");
				}
		  ?>
		</table>
		<p><input type="submit" name="eliminar" id="eliminar" value="Eliminar Seleccionados" /></p>
	</form>
</div>
</body>
</html>