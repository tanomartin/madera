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

function controlarFechaVtoInsp(fecha, plazo, efectuada) {
	if (efectuada == 1) {
		alert("VAMOS A LIQUIDAR");
	} else {
		if (plazo == 0) {
			alert("No se puede liquidar. No hay plazo de efectivización para la inspeccion");
		} else {
			fechavto = fecha.replace("-", "/").replace("-", "/");	
			fechavto = new Date(fechavto);
			var plazo=parseInt(plazo);
			fechavto.setDate(fechavto.getDate() + plazo);
			var aniovto = fechavto.getFullYear();
			var mesvto = fechavto.getMonth()+1;
			var diavto = fechavto.getDate();
			fechavto = aniovto+"-"+mesvto+"-"+diavto;
			
			var today = new Date();
			var curr_date = today.getDate();
			var curr_month = today.getMonth() + 1; //Months are zero based
			var curr_year = today.getFullYear();
			today = curr_year + "-"  + curr_month + "-" + curr_date;

			if (today > fechavto) {
				if (confirm("La Inspección sigue vigente pero se ha vencido el plazo de la misma. ¿Desea liquidarla igualmente?")) {
					alert("VAMOS A LIQUIDAR");
				} 
			} else {
				alert("No se puede liquidar. Inspeccion en progreso y plazo de efectivización vigente.");
			}
		}
	}
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
					$sqlInsp = "SELECT * from inspecfiscalizospim where nrorequerimiento = ".$rowReque['nrorequerimiento'];
					$resInsp = mysql_query($sqlInsp,$db);
					$rowInsp = mysql_fetch_array($resInsp);
					print("<td><a href=javascript:controlarFechaVtoInsp('".$rowInsp['fechaasignado']."',".$rowInsp['diasefectivizacion'].",".$rowInsp['inspeccionefectuada'].")>Liquidar</a><br><a href='anulaRequerimiento.php?nroreq=".$rowReque['nrorequerimiento']."&fecha=".$fecha."'>Anular</a></td>");  
				}        
				print("</tr>");
			}
	  ?>
	</table>
	</p>
	
</div>
</body>
</html>