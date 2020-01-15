<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");

if (isset($_POST['dato']) || isset($_GET['codigo'])) {
	$dato = "";
	$filtro = "";
	if (isset($_GET['codigo'])) {
		$dato = $_GET['codigo'];
		$filtro = 0;
	} else {
		$dato = $_POST['dato'];
		$filtro = $_POST['filtro'];
	}

	if ($filtro == 0) {
		$sqlPrestador = "SELECT * FROM prestadores WHERE codigoprestador = $dato and personeria != 5 order by codigoprestador DESC";
	} else {
		$sqlPrestador = "SELECT * FROM prestadores where cuit = $dato and personeria != 5 order by codigoprestador DESC";
	}

	$resPrestador = mysql_query($sqlPrestador,$db);
	$canPrestador = mysql_num_rows($resPrestador);
	if ($canPrestador == 1) {
		$rowPrestador = mysql_fetch_array($resPrestador);
		$codigo = $rowPrestador['codigoprestador'];
		$sqlOrdenesCabecera = "SELECT * FROM ordencabecera WHERE codigoprestador = $codigo ORDER BY nroordenpago DESC";
		$resOrdenesCabecera = mysql_query($sqlOrdenesCabecera,$db);
		$canOrdenesCabecera = mysql_num_rows($resOrdenesCabecera);
	} else {
		$redire = "nuevaOrdenPago.php?error=1";
		header("Location: $redire");
		exit(0);
	}
}

$sqlPrestador = "SELECT * FROM prestadores WHERE codigoprestador = $codigo order by codigoprestador DESC";
$resPrestador = mysql_query($sqlPrestador,$db);
$rowPrestador = mysql_fetch_array($resPrestador);

$sqlFacPendientesInte = "SELECT
							f.*,
							DATE_FORMAT(f.fechacomprobante,'%d-%m-%Y') as fechamostrar,
							DATE_FORMAT(f.fechavencimiento,'%d-%m-%Y') as fechavencimiento
						 FROM facturasprestaciones p, facturasintegracion i, facturas f
						 LEFT JOIN facturasprestaciones ON facturasprestaciones.idfactura = f.id and facturasprestaciones.totaldebito > 0
						 LEFT JOIN facturascarenciasbeneficiarios ON facturascarenciasbeneficiarios.idfactura = f.id and facturascarenciasbeneficiarios.totaldebito > 0
						 WHERE
							f.idPrestador = ".$rowPrestador['codigoprestador']." and
							(f.fechacierreliquidacion is not null and f.fechacierreliquidacion != '0000-00-00 00:00:00') and
							f.restoapagar > 0 and 
							f.autorizacionpago = 1 and
							f.id = p.idFactura and 
							p.id = i.idFacturaPrestacion
						 GROUP BY f.id
						 ORDER BY f.fechacomprobante ASC";
$resFacPendientesInte = mysql_query($sqlFacPendientesInte,$db);
$canFacPendientesInte = mysql_num_rows($resFacPendientesInte);
$arrayInte = array();
if ($canFacPendientesInte > 0) {
	while($rowFacPendientesInte = mysql_fetch_array($resFacPendientesInte)) {
		$arrayInte[$rowFacPendientesInte['id']] = $rowFacPendientesInte;
	}
}

$sqlFacPendientes = "SELECT
						f.*,
						DATE_FORMAT(f.fechacomprobante,'%d-%m-%Y') as fechamostrar,
						DATE_FORMAT(f.fechavencimiento,'%d-%m-%Y') as fechavencimiento
					 FROM facturas f
					 LEFT JOIN facturasprestaciones ON facturasprestaciones.idfactura = f.id and facturasprestaciones.totaldebito > 0
					 LEFT JOIN facturascarenciasbeneficiarios ON facturascarenciasbeneficiarios.idfactura = f.id and facturascarenciasbeneficiarios.totaldebito > 0 
					 WHERE
						f.idPrestador = ".$rowPrestador['codigoprestador']." and
						(f.fechacierreliquidacion is not null and f.fechacierreliquidacion != '0000-00-00 00:00:00')  and
						f.restoapagar > 0 and 
						f.autorizacionpago = 1
					 GROUP BY f.id
					 ORDER BY f.fechacomprobante ASC";
$resFacPendientes = mysql_query($sqlFacPendientes,$db);
$canFacPendientes = mysql_num_rows($resFacPendientes);
$arrayNoInte = array();
if ($canFacPendientes > 0) {
	while($rowFacPendientes = mysql_fetch_array($resFacPendientes)) {
		if (!array_key_exists ($rowFacPendientes['id'] , $arrayInte )) {
			$arrayNoInte[$rowFacPendientes['id']] = $rowFacPendientes;
		}
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Ordenes Pago :.</title>
<link rel="stylesheet" href="/madera/lib/tablas.css"/>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">

function habilitarMonto(id, totalApagar, seleccion, tipo) {
	var nombreinput = "apagar"+id;	
	var apagar = document.getElementById(nombreinput);
	var valorAnterior = apagar.value;

	if (tipo == 'N') {
		var total = document.getElementById("totalNoInte");
	} else {
		var total = document.getElementById("totalInte");
	}

	if (valorAnterior != 0.00 && valorAnterior != '') {
		totalValor = parseFloat(total.value) - parseFloat(valorAnterior);
		total.value = totalValor.toFixed(2);
	}

	apagar.style.readOnly = true;
	apagar.value = "0.00";
	apagar.style.backgroundColor = "silver";

	if (seleccion == "P") {
		apagar.style.readOnly = false;
		apagar.style.backgroundColor = "";
		apagar.value = "";
		apagar.focus();
	}
	if (seleccion == "T") {
		apagar.value = totalApagar;	
		totalValor = parseFloat(total.value) + parseFloat(totalApagar);
		total.value = totalValor.toFixed(2);
	}
}

function validarValor(valor, totalApagar, id) {
	var nombreinput = "apagar"+id;
	var apagar = document.getElementById(nombreinput);
	valor = parseFloat(valor).toFixed(2);
	apagar.value = valor;
	
	var ok = 0;
	if (isNumber(valor)) {
		if (valor > totalApagar || valor <= 0) {
			alert("El valor de pago parcial no puede ser mayor al Resto a Pagar y debe ser positivo");
		} else {
			var total = document.getElementById("totalNoInte");
			total.value = parseFloat(total.value) + parseFloat(valor);
			ok = 1;
		}
	} else {
		alert("El valor debe ser un numero y no puede quedar vacio");
	}
	if (ok == 0) {
		apagar.value = "0.00";
		var nombreSelect = "tipopago"+id;
		var selectTipo = document.getElementById(nombreSelect);
		selectTipo.value = "0";
		selectTipo.focus();
	}		
	apagar.style.readOnly = true;
	apagar.style.backgroundColor = "silver";
}

function validarCantFacturas(formulario) {
	var cantidadFactura = 0;
	for (var i=0;i<formulario.elements.length;i++) {
		 if (formulario.elements[i].name.indexOf("tipopago") !== -1 ) {
			 if (formulario.elements[i].value != 0) {
				 cantidadFactura++;
			 }
		 }
	}
	if (cantidadFactura > 25) {
		alert("No se pueden cargar mas de 25 facturas en una Orden de Pago");
		return false;
	}
	return true;
}

function validar(formulario) {
	var name = formulario.name;
	if (name == "formNointe") {
		var totalNoInte = document.getElementById("totalNoInte");
		if (totalNoInte.value == 0) {
			alert("Debe elegir por lo menos una factura para pagar del grupo NO integración")
			return false;
		}
	} else {
		var totalInte = document.getElementById("totalInte");
		if (totalInte.value == 0) {
			alert("Debe elegir por lo menos una factura para pagar del grupo Integración")
			return false;
		}
	}
	if (validarCantFacturas(formulario)) {
		$.blockUI({ message: "<h1>Agrupando Informacion para la Carga de la Orden de Pago<br>Aguarde por favor...</h1>" });
		return true;
	}
	return false;
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="reset" name="volver" value="Volver" onclick="location.href = 'nuevaOrdenPago.php'" /></p>
	<h3>Facturas Pendientes de Pago</h3>
	<div style="border: solid; margin-bottom: 15px; width: 50%">
		<h4> Código: <font color='blue'><?php echo $rowPrestador['codigoprestador']?></font> - C.U.I.T.: <font color='blue'><?php echo $rowPrestador['cuit']?></font> 
		<br/> Razon Social: <font color='blue'><?php echo $rowPrestador['nombre'] ?></font></h4>
	</div>
	<h3>FACTURAS</h3>
<?php if (sizeof($arrayNoInte) > 0) { ?>
		<form id="formNointe" name="formNointe" method="post" onsubmit="return validar(this)" action="cargarOrdenPago.php?tipo=N">	
			<input type="text" style="display: none" value="<?php echo $rowPrestador['codigoprestador']?>" id="codigo" name="codigo"/>
		   	<div class="grilla">
		   		<table>
					<thead>
						<tr>
							<th>Nro. Interno</th>
							<th>Nro. Factura</th>
							<th>Fecha</th>
							<th>Fecha Vto.</th>
							<th>Imp. Factura</th>
							<th>Debitos</th>
							<th>Pagos Ant.</th>
							<th>A Pagar</th>
							<th>Tipo Pago</th>
							<th>Monto a Pagar</th>
						</tr>
					</thead>
					<tbody>
				<?php 	$totalImpFactura = 0;	
						$totalDebitos = 0;
						$totalPagosAnteriores = 0;
						$totalAPagar = 0;
						foreach ($arrayNoInte as $facturaNoInte) { 
							$totalImpFactura += $facturaNoInte['importecomprobante'];
							$totalDebitos += $facturaNoInte['totaldebito'];
							$totalPagosAnteriores += $facturaNoInte['totalpagado'];
							$totalAPagar += $facturaNoInte['restoapagar']; ?>
							<tr>
								<td><?php echo $facturaNoInte['id'];?></td>
								<td><?php echo $facturaNoInte['puntodeventa']."-".$facturaNoInte['nrocomprobante'] ?></td>
								<td><?php echo $facturaNoInte['fechamostrar'];?></td>
								<td><?php echo $facturaNoInte['fechavencimiento'];?></td>
								<td><?php echo number_format($facturaNoInte['importecomprobante'],2,',','.');?></td>
								<td><?php echo number_format($facturaNoInte['totaldebito'],2,',','.');?></td>
								<td><?php echo number_format($facturaNoInte['totalpagado'],2,',','.');?></td>
								<td><?php echo number_format($facturaNoInte['restoapagar'],2,',','.');?></td>
								<td>
									<select id="tipopago<?php echo $facturaNoInte['id'] ?>" name="tipopago<?php echo $facturaNoInte['id'] ?>" onchange="habilitarMonto('<?php echo $facturaNoInte['id']?>','<?php echo $facturaNoInte['restoapagar']?>',this[selectedIndex].value, 'N')">
										<option value="0">No pagar</option>
										<option value="P">Parcial</option>
									<?php if ($facturaNoInte['totalpagado'] == 0) { ?>
										  		<option value="T">Total</option>
								    <?php } ?>
									  </select>
								</td>
								<td>
									<input type="text" id="apagar<?php echo $facturaNoInte['id'] ?>" name="apagar<?php echo $facturaNoInte['id'] ?>" onchange="validarValor(this.value, <?php echo $facturaNoInte['restoapagar']?>,'<?php echo $facturaNoInte['id'] ?>')" size="14" value="0.00" style="background-color: silver; text-align: center"/>
								</td>
							</tr>
				<?php } ?>
						</tbody>
						<thead>
							<tr>
								<th colspan="4">TOTAL</th>
								<th><?php echo number_format($totalImpFactura,2,',','.');?></th>
								<th><?php echo number_format($totalDebitos,2,',','.');?></th>
								<th><?php echo number_format($totalPagosAnteriores,2,',','.');?></th>
								<th><?php echo number_format($totalAPagar,2,',','.');?></th>
								<th></th>
								<th><input type="text" id="totalNoInte" name="totalNoInte" size="14" value="0" style="background-color: silver; text-align: center"/></th>
							</tr>
						</thead>
		  		</table>
		  	</div> 
	 		<p><input type="submit" name="cargarOrden" value="Cargar Orden de Pago" /></p>
	 	</form>
<?php } else { ?>
		<h3 style="color: blue">No existen facturas pendientes de pago</h3>
<?php } ?>
	  <h3>FACTURAS INTEGRACIÓN</h3>
<?php if (sizeof($arrayInte) > 0) { ?>
		<form id="formInte" name="formInte" method="post" onsubmit="return validar(this)" action="cargarOrdenPago.php?tipo=I">	
			<input type="text" style="display: none" value="<?php echo $rowPrestador['codigoprestador']?>" id="codigo" name="codigo"/>
			<div class="grilla">
		   		<table>
					<thead>
						<tr>
							<th>Nro. Interno</th>
							<th>Nro. Factura</th>
							<th>Fecha</th>
							<th>Fecha Vto.</th>
							<th>Imp. Factura</th>
							<th>Debitos</th>
							<th>Pagos Ant.</th>
							<th>A Pagar</th>
							<th>Tipo Pago</th>
							<th>Monto a Pagar</th>
						</tr>
					</thead>
					<tbody>
				<?php 	$totalImpFacturaInte = 0;	
						$totalDebitosInte = 0;
						$totalPagosAnterioresInte = 0;
						$totalAPagarInte = 0;
						foreach ($arrayInte as $facturaInte) {
							$totalImpFacturaInte += $facturaInte['importecomprobante'];
							$totalDebitosInte += $facturaInte['totaldebito'];
							$totalPagosAnterioresInte += $facturaInte['totalpagado'];
							$totalAPagarInte += $facturaInte['restoapagar']; ?>
							<tr>
								<td><?php echo $facturaInte['id'];?></td>
								<td><?php echo $facturaInte['puntodeventa']."-".$facturaInte['nrocomprobante'] ?></td>
								<td><?php echo $facturaInte['fechamostrar'];?></td>
								<td><?php echo $facturaInte['fechavencimiento'];?></td>
								<td><?php echo number_format($facturaInte['importecomprobante'],2,',','.');?></td>
								<td><?php echo number_format($facturaInte['totaldebito'],2,',','.');?></td>
								<td><?php echo number_format($facturaInte['totalpagado'],2,',','.');?></td>
								<td><?php echo number_format($facturaInte['restoapagar'],2,',','.');?></td>
								<td>
									<select id="tipopago<?php echo $facturaInte['id'] ?>" name="tipopago<?php echo $facturaInte['id'] ?>" onchange="habilitarMonto('<?php echo $facturaInte['id']?>','<?php echo $facturaInte['restoapagar']?>',this[selectedIndex].value, 'I')">
										<option value="0">No pagar</option>
								<?php if ($facturaInte['totalpagado'] == 0) { ?>
									  		<option value="T">Total</option>
							   <?php	}?>
									  </select>
								</td>
								<td><input type="text" id="apagar<?php echo $facturaInte['id'] ?>" name="apagar<?php echo $facturaInte['id'] ?>" size="14" value="0.00" style="background-color: silver; text-align: center"/></td>
							</tr>
				<?php } ?>
					</tbody>
					<thead>
							<tr>
								<th colspan="4">TOTAL</th>
								<th><?php echo number_format($totalImpFacturaInte,2,',','.');?></th>
								<th><?php echo number_format($totalDebitosInte,2,',','.');?></th>
								<th><?php echo number_format($totalPagosAnterioresInte,2,',','.');?></th>
								<th><?php echo number_format($totalAPagarInte,2,',','.');?></th>
								<th></th>
								<th><input type="text" id="totalInte" name="totalInte" size="14" value="0" style="background-color: silver; text-align: center"/></th>
							</tr>
						</thead>
		  		</table>
		  	</div> 
		  	<p><input type="submit" name="cargarOrden" value="Cargar Orden de Pago" /></p>
		</form>		
<?php } else { ?>
		<h3 style="color: blue">No existen facturas pendientes de pago</h3>
<?php } ?>
</div>
</body>
</html>
