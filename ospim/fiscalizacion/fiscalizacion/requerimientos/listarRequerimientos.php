<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php");
if (isset($_GET['fecha'])) {
	$fecha = $_GET['fecha'];
} else {
	$fecha = $_POST['fecha'];
}
$fechaBusqueda = fechaParaGuardar($fecha);

$sqlReque = "SELECT * from reqfiscalizospim where fecharequerimiento = '$fechaBusqueda' and procesoasignado != 1 and requerimientoanulado = 0";
$resReque = mysql_query($sqlReque,$db);
$canReque = mysql_num_rows($resReque);
if ($canReque == 0) {
	header ("Location: requerimientos.php?err=1");
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado de Requerimientos :.</title>
</head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>

<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">

jQuery(function($){
	$("#fecha").mask("99-99-9999");
});

function validar(formulario) {
	if (!esFechaValida(formulario.fecha.value)) {
		alert("Debe ingresar una fecha valida");
		formulario.fecha.focus();
		return false;
	}	
	return true
}

function controlarFechaVtoInsp(fecha, plazo) {
	alert(fecha);
	alert(plazo);
}
</script>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><span style="text-align:center">
    <input type="reset" name="volver" value="Volver" onclick="location.href = 'requerimientos.php'" align="center"/>
  </span></p>
  	<p class="Estilo2">Listado de  Requerimiento del d&iacute;a <?php echo $fecha ?>  </p>
	<p> <table width="1000" border="1" align="center">
	  <tr>
		<th>Número</th>
		<th>Origen</th>
		<th>Solicitante</th>
		<th>Motivo</th>
		<th>Cuit</th>
		<th>Detalle</th>
		<th>Acciones</th>
	  </tr>
	  <?php while($rowReque = mysql_fetch_array($resReque)) { 
				print("<tr>");
				print("<td>".$rowReque['nrorequerimiento']."</td>");
				if ($rowReque['origenrequerimiento'] == 1) {
					$origen = "Fiscalización";
				}
				if ($rowReque['origenrequerimiento'] == 2) {
					$origen = "Afiliaciones";
				}
				if ($rowReque['origenrequerimiento'] == 3) {
					$origen = "Prestación";
				}  
				print("<td>".$origen."</td>");   
				print("<td>".$rowReque['solicitarequerimiento']."</td>");   
				print("<td>".$rowReque['motivorequerimiento']."</td>"); 
				print("<td>".$rowReque['cuit']."</td>"); 
				print("<td><a href='detalleRequerimiento.php?nroreq=".$rowReque['nrorequerimiento']."&fecha=".$fecha."&cuit=".$rowReque['cuit']."'>Editar</a></td>");
				if ($rowReque['procesoasignado'] == 0) {		
					print("<td>Liquidar<br><a href='inspeccion.php?nroreq=".$rowReque['nrorequerimiento']."&fecha=".$fecha."&cuit=".$rowReque['cuit']."'>Inspección</a><br><a href='anulaRequerimiento.php?nroreq=".$rowReque['nrorequerimiento']."&fecha=".$fecha."'>Anular</a></td>");  
				} else {
					$sqlInsp = "SELECT * from requeinspeccionospim where nrorequerimiento = ".$rowReque['nrorequerimiento'];
					$resInsp = mysql_query($sqlInsp,$db);
					$rowInsp = mysql_fetch_array($resInsp);
					print("<td><a href=javascript:controlarFechaVtoInsp('".$rowInsp['fechaasignacion']."',".$rowInsp['plazoefectivizacion'].")>Liquidar</a><br><a href='anulaRequerimiento.php?nroreq=".$rowReque['nrorequerimiento']."&fecha=".$fecha."'>Anular</a></td>");  
				}        
				print("</tr>");
			}
	  ?>
	</table>
	</p>
	
</div>
</body>
</html>