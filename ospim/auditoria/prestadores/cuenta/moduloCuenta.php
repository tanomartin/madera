<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");

$noExiste = 0;
if (isset($_POST['dato']) && isset($_POST['filtro'])) {
	$dato = $_POST['dato'];
	$filtro = $_POST['filtro'];
	$fecha = $_POST['fecha'];
	$resultado = array();
	if (isset($dato)) {
		if ($filtro == 0) { $sqlPrestador = "SELECT * FROM prestadores WHERE codigoprestador = $dato order by codigoprestador DESC"; }
		if ($filtro == 1) { $sqlPrestador = "SELECT * FROM prestadores WHERE cuit = $dato order by codigoprestador DESC"; }
		$resPrestador = mysql_query($sqlPrestador,$db); 
		$canPrestador = mysql_num_rows($resPrestador); 
		if ($canPrestador == 0) {
			$noExiste = 1;
		} else {
			$rowPrestador = mysql_fetch_array($resPrestador);
			$codigo = $rowPrestador['codigoprestador'];
			$cartel = "<b>'".$rowPrestador['nombre']." - ".$codigo."'</b></br>Detalle desde <b>'$fecha'</b>";		
			
			$fechaBuscar = fechaParaGuardar($fecha);
			
			$sqlFacturaSinLiqui = "SELECT sum(importecomprobante) as sumimporte
									FROM facturas
									WHERE idPrestador = $codigo and fechacomprobante < '$fechaBuscar' and
									totalcredito = 0 and totaldebito = 0";
			$resFacturaSinLiqui = mysql_query($sqlFacturaSinLiqui,$db);
			$rowFacturaSinLiqui  = mysql_fetch_array($resFacturaSinLiqui);
			
			$sqlFacturas = "SELECT sum(totalcredito) - sum(totaldebito) as sumdebe 
							FROM facturas 
							WHERE idPrestador = $codigo and fechacomprobante < '$fechaBuscar'";
			$resFacturas = mysql_query($sqlFacturas,$db);
			$rowFacturas = mysql_fetch_array($resFacturas);
			
			$sqlPagos = "SELECT sum(retencion) + sum(importe) as sumhaber 
							FROM ordencabecera 
							WHERE codigoprestador = $codigo and fechacancelacion is null and fechacomprobante < '$fechaBuscar'";
			$resPagos = mysql_query($sqlPagos,$db);
			$rowPagos = mysql_fetch_array($resPagos);
			
			$haber = 0;
			if ($resFacturaSinLiqui['sumimporte'] != null) {
				$haber =  $resFacturaSinLiqui['sumimporte'];
			}
			if ($rowFacturas['sumdebe'] != null) {
				$haber += $rowFacturas['sumdebe'];
			} 
			$debe = 0;
			if ($rowPagos['sumhaber'] != null) {
				$debe = $rowPagos['sumhaber'];
			}
			$saldo = $haber - $debe;
			
			$arrayDetalle = array();
			$index = 0;
			
			$sqlFacturasDet = "SELECT puntodeventa, nrocomprobante, fechacomprobante, importecomprobante, totaldebito, totalcredito, importeliquidado, totalpagado 
								FROM facturas 
								WHERE idPrestador = $codigo and fechacomprobante >= '$fechaBuscar'";
			$resFacturasDet = mysql_query($sqlFacturasDet,$db);
			while($rowFacturasDet = mysql_fetch_array($resFacturasDet)) {
				$index++;
				$descripcion = "Ingreso Factura ".$rowFacturasDet['puntodeventa']."-".$rowFacturasDet['nrocomprobante'];	
				$haber = $rowFacturasDet['totaldebito'];
				if ($rowFacturasDet['totaldebito'] == 0 and $rowFacturasDet['totalcredito'] == 0) {
					$haber = $rowFacturasDet['importecomprobante'];
				}
				if ($rowFacturasDet['totaldebito'] != 0) {
					$descripcion .= "<br> Debito Aud. Med. Factura ".$rowFacturasDet['puntodeventa']."-".$rowFacturasDet['nrocomprobante'];
				}
				$arrayDetalle[$rowFacturasDet['fechacomprobante'].$index] = array("descripcion" => $descripcion, "debe" => $rowFacturasDet['totalcredito'], "haber" => $haber);
			}
			
			$sqlPagosDet = "SELECT * FROM ordencabecera
							WHERE codigoprestador = $codigo and fechacancelacion is null and fechacomprobante >= '$fechaBuscar'";
			$resPagosDet = mysql_query($sqlPagosDet,$db);
			$arrayRete = array();
			while($rowPagosDet = mysql_fetch_array($resPagosDet)) {
				$index++;
				$descripcion = "Orden de Pago ".$rowPagosDet['nroordenpago']." ".$rowPagosDet['formapago']." ".$rowPagosDet['comprobantepago']; 
				$sqlDetalleOP = "SELECT * FROM ordendetalle o, facturas f 
								 WHERE o.nroordenpago = ".$rowPagosDet['nroordenpago']." and o.idfactura = f.id";
				$resDetalleOP= mysql_query($sqlDetalleOP,$db);
				$arrayDetOP = array();
				while ($rowDetalleOP = mysql_fetch_array($resDetalleOP)) {
					$arrayDetOP[$rowDetalleOP['idfactura']] = array("tipo" => $rowDetalleOP['tipocancelacion'], "importe" => $rowDetalleOP['importepago'], "factura" => $rowDetalleOP['puntodeventa']."-".$rowDetalleOP['nrocomprobante']);
				}				
				$arrayDetalle[$rowPagosDet['fechacomprobante'].$index] = array("descripcion" => $descripcion, "debe" => $rowPagosDet['importe'], "haber" => 0, "facturas" => $arrayDetOP);				
				if ($rowPagosDet['retencion'] != 0) {
					$arrayRete[$rowPagosDet['nroordenpago']] = array('fecha' => $rowPagosDet['fechacomprobante'], 'rete' =>  $rowPagosDet['retencion']);
				}
			}
			
			foreach ($arrayRete as $nroorden => $datos) {
				$index++;
				$descripcion = "Ret. Orden de Pago $nroorden";
				$arrayDetalle[$datos['fecha'].$index] = array("descripcion" => $descripcion, "debe" => $datos['rete'], "haber" => 0);
			}
		}
		ksort($arrayDetalle);
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<style type="text/css" media="print">
.nover {display:none}
</style>
<title>.: Módulo Prestadores :.</title>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">

	jQuery(function($){
		$("#fecha").mask("99-99-9999");
	});

	$(function() {
		$("#listaResultado")
		.tablesorter({
			theme: 'blue', 
			widthFixed: true, 
			widgets: ["zebra", "filter"], 
			widgetOptions : { 
				filter_cssFilter   : '',
				filter_childRows   : false,
				filter_hideFilters : false,
				filter_ignoreCase  : true,
				filter_searchDelay : 300,
				filter_startsWith  : false,
				filter_hideFilters : false,
			}
		
		})
	});

	function validar(formulario) {
		if(formulario.dato.value == "") {
			alert("Debe colocar un dato de busqueda");
			return false;
		}
		if (formulario.filtro[0].checked) {
			resultado = esEnteroPositivo(formulario.dato.value);
			if (!resultado) {
				alert("El Código de Prestador debe ser un numero entero positivo");
				return false;
			} 
		}
		if (formulario.filtro[1].checked) {
			if (!verificaCuilCuit(formulario.dato.value)) {
				alert("C.U.I.T. invalido");
				return false;
			}
		}
		if (!esFechaValida(formulario.fecha.value)) {
			alert("Debe colocar una fecha valida");
			return false;
		}
		$.blockUI({ message: "<h1>Generando Busqueda... <br>Esto puede tardar unos segundos.<br> Aguarde por favor</h1>" });
		return true;
	}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<form id="form1" name="form1" method="post" onsubmit="return validar(this)" action="moduloCuenta.php" class="nover">
		<p><input type="button" name="volver" value="Volver" onclick="location.href = '../menuPrestadores.php'" /></p>
		<h3>Cuenta Corriente </h3>
		  <?php if ($noExiste == 1) { ?>
					<div style='color:#FF0000'><b> NO EXISTE PRESTADOR CON ESTE FILTRO DE BUSQUEDA </b></div>
		  <?php }  ?>   
	    	<table width="200" border="0">
		      <tr>
		        <td rowspan="2"><div align="center"><strong>Buscar por </strong></div></td>
		        <td><div align="left"><input type="radio" name="filtro"  value="0" checked="checked" /> Código </div></td>
		      </tr>
		      <tr>
		        <td><div align="left"><input type="radio" name="filtro" value="1" /> C.U.I.T.</div></td>
		      </tr> 
			</table>
	   		<p><b>Dato</b> <input name="dato" type="text" id="dato" size="14" /></p>
	    	<p><b>Fecha Desde</b> <input name="fecha" type="text" id="fecha" size="9" /></p>
	    	<p><input type="submit" name="Buscar" value="Buscar" /></p>
	</form>
  <?php $totalDebe = 0;
   		$totalHaber = 0;
   		if ($noExiste == 0 and isset($dato)) { ?>
  			<p><?php echo $cartel ?></p>
  			<table style="text-align:center; width:1000px" id="listaResultado" class="tablesorter" >
				<thead>
					<tr>
						<th>Fecha</th>
						<th>Descripcion</th>
						<th>DEBE</th>
						<th>HABER</th>
						<th>SALDO</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php echo str_replace("-","/",$fecha); ?></td>
						<td><?php echo "Saldo Consolidado al $fecha"?></td>
						<td><?php if ($saldo < 0) { echo number_format($saldo,2,",","."); $totalDebe += $saldo; } ?></td>
						<td><?php if ($saldo > 0) { echo number_format($saldo,2,",","."); $totalHaber += $saldo; } ?></td>
						<td><?php echo number_format($saldo,2,",","."); ?></td>
					</tr>
			<?php foreach ($arrayDetalle as $fechas => $detalle) {  
					$saldo -= $detalle['debe']; 
					$saldo += $detalle['haber']; 
					$totalDebe += $detalle['debe'];
					$totalHaber += $detalle['haber']; ?>
					<tr>
						<td><?php echo invertirFecha(substr($fechas,0,10)); ?></td>
						<td><?php echo $detalle['descripcion']."<br>";
								  if (isset($detalle['facturas'])) {
								  	echo "---------------------------------------------------------------------<br>";
									foreach ($detalle['facturas'] as $facturas) {
										echo "Fac: ".$facturas['factura']." - Tipo.: ".$facturas['tipo']." - Imp: $".number_format($facturas['importe'],2,",",".")."<br>";
									}
									echo "----------------------------------------------------------------------";
								  } ?>
						</td>
						<td><?php echo number_format($detalle['debe'],2,",","."); ?></td>
						<td><?php echo number_format($detalle['haber'],2,",","."); ?></td>
						<td><?php echo number_format($saldo,2,",","."); ?></td>
					</tr>
			<?php } ?>
					<tr>
						<th colspan="2">TOTALES</th>
						<th><?php echo number_format($totalDebe,2,",","."); ?></th>
						<th><?php echo number_format($totalHaber,2,",","."); ?></th>
						<th><?php echo number_format($saldo,2,",","."); ?></th>
					</tr>
				</tbody>
		   </table>
		   <input type="button" name="imprimir" value="Imprimir" onclick="window.print();" class="nover"/>
  <?php } ?>
	</div>
</body>
</html>
