<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php"); 
include($libPath."fechas.php");

$fecha = $_GET['fecha'];
$nroreq = $_GET['nroreq'];
$cuit = $_GET['cuit'];

$sqlDeta = "SELECT * from detfiscalizusimra d, periodosusimra p where nrorequerimiento = '$nroreq' and 
				 d.anofiscalizacion = p.anio and d.mesfiscalizacion = p.mes ";
$resDeta = mysql_query($sqlDeta,$db);

function obtenerMesRelacion($mes, $anio, $db) {
	$sqlExtra = "SELECT relacionmes FROM extraordinariosusimra WHERE anio = $anio and mes = $mes and tipo != 2";
	$resExtra = mysql_query($sqlExtra,$db);
	$rowExtra = mysql_fetch_assoc($resExtra);
	return $rowExtra['relacionmes'];
}

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
function abrirInfo(dire) {
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

<body bgcolor="#B2A274">
<div align="center">
  <p><span style="text-align:center">
    <input type="button" name="volver" value="Volver" onclick="location.href = 'listarRequerimientos.php?fecha=<?php echo $fecha ?>'" />
  </span></p>
  	<form name="editarReque" onsubmit="return validar(this)" method="post" action="eliminarPeriodos.php" >
		<input name="cuit" type="text" value="<?php echo $cuit?>" style="display:none"/>
		<input name="fecha" type="text" value="<?php echo $fecha?>" style="display:none"/>
		<input name="nroreq" type="text" value="<?php echo $nroreq?>" style="display:none"/>
		<p class="Estilo2">Edici&oacute;n de Periodos  del  Requerimiento Nro. <?php echo $nroreq ?></p>
		<table width="900" border="1" align="center" style="text-align: center;">
		  <tr style="font-size:12px">
		  	<th rowspan="2">Año</th>
		  	<th rowspan="2">Mes</th>
			<th rowspan="2">Status</th>
			<th colspan="2">DDJJ</th>
			<th rowspan="2">Deuda Nominal</th>
			<th rowspan="2">+Info</th>
			<th rowspan="2"><input type="checkbox" name="selecAll" id="selecAll" onchange="checkall(this)" /></th>
		  </tr>
		  <tr style="font-size:12px">
			<th>Remun.</th>
			<th>Cant. Personal </th>
		  </tr>
		  <?php while($rowDeta = mysql_fetch_array($resDeta)) { 
					$ano = $rowDeta['anofiscalizacion'];
					$mes = $rowDeta['mesfiscalizacion'];
					$id = $ano."-".$mes; 
					if ($rowDeta['statusfiscalizacion'] == 'S') {
						$status = "S/DDJJ";
					}
					if ($rowDeta['statusfiscalizacion'] == 'A') {
						$status = "Deuda";
					}
					if ($rowDeta['statusfiscalizacion'] == 'U') {
						$status = "Base USIMRA";
					}
					if ($rowDeta['statusfiscalizacion'] == 'F') {
						$status = "P.F.T.";
					}
					if ($rowDeta['statusfiscalizacion'] == 'M') {
						$status = "A.M.";
					}
					if ($rowDeta['statusfiscalizacion'] == 'O') {
						$status = "Base OSPIM";
					}
					?>
					<tr>
						<td><?php echo $ano ?></td>
						<td><?php echo $mes." - ".$rowDeta['descripcion']?></td>
						<td><?php echo $status ?></td>   
						<td><?php echo number_format($rowDeta['remundeclarada'],2,',','.'); ?></td> 
						<td><?php echo $rowDeta['cantidadpersonal'] ?></td> 
						<td><?php echo number_format($rowDeta['deudanominal'],2,',','.'); ?></td>        
						<?php
						
						if ($mes > 12) {
							$mes = obtenerMesRelacion($mes, $ano, $db);
						}
						
						if ($rowDeta['statusfiscalizacion'] == 'M' || $rowDeta['statusfiscalizacion'] == 'F') {
							$dire = "/madera/comun/empresas/abm/cuentas/detallePagosUsimra.php?cuit=".$cuit."&anio=".$ano."&mes=".$mes;?>
							<td><input type="button" value="VER PAGO" onclick="javascript:abrirInfo('<?php echo $dire ?>')"/></td>
			<?php		} else {
							if ($rowDeta['statusfiscalizacion'] == 'A') {
								$dire = "/madera/comun/empresas/abm/cuentas/detalleDDJJUsimra.php?cuit=".$cuit."&anio=".$ano."&mes=".$mes; ?>
								<td><input type="button" value="VER DDJJ" onclick="javascript:abrirInfo('<?php echo $dire ?>')" /></td>
			<?php			} else { 
								if ($rowDeta['statusfiscalizacion'] == 'O') {
									$dire = "/madera/comun/empresas/abm/cuentas/detalleDDJJ.php?cuit=".$cuit."&anio=".$ano."&mes=".$mes; ?>
									<td><input type="button" value="VER DDJJ OSPIM" onclick="javascript:abrirInfo('<?php echo $dire ?>')" /></td>
			<?php				} else { 
									if ($rowDeta['statusfiscalizacion'] == 'U') { ?>
										<td>VER Per.Ord.</td> 
						      <?php } else { ?>
										<td>-</td> 
							<?php	}
					 			}
							}
						} ?>
						<td><input type='checkbox' name='<?php echo $id ?>' id='periodos' value='<?php echo $id ?>' /></td> 
					</tr>
		<?php	  } ?>
		</table>
		<p><input type="submit" name="eliminar" id="eliminar" value="Eliminar Seleccionados" /></p>
	</form>
</div>
</body>
</html>