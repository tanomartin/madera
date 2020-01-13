<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");

$arrayDatos = explode ("-",$_POST['generar']);
$cuit = $arrayDatos[0];
$idfactura = $arrayDatos[1];
$codigo = $arrayDatos[2];

$sqlPrestador = "SELECT * FROM prestadores WHERE codigoprestador = $codigo";
$resPrestador = mysql_query($sqlPrestador,$db);
$rowPrestador = mysql_fetch_array($resPrestador);

$envioEmail = true;
if ($rowPrestador['email1'] == NULL && $rowPrestador['email2'] == NULL) {
	$envioEmail = false;
}

$sqlFactura = "SELECT
					f.*,c.*,
					DATE_FORMAT(f.fechacomprobante,'%d/%m/%Y') as fechamostrar,
					DATE_FORMAT(f.fechavencimiento,'%d/%m/%Y') as fechavencimiento
				FROM facturas f, facturasconceptos c
			    WHERE f.id = $idfactura and f.id = c.idfactura";
$resFactura = mysql_query($sqlFactura,$db);
$factura = mysql_fetch_assoc($resFactura);
$total = $factura['restoapagar']; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Ordenes Pago :.</title>
<link rel="stylesheet" href="/madera/lib/tablas.css"/>
<link rel="stylesheet" href="/madera/lib/jquery-ui-1.9.2.custom/css/smoothness/jquery-ui-1.9.2.custom.css"/>
<script src="/madera/lib/jquery-ui-1.9.2.custom/js/jquery-1.8.3.js" type="text/javascript"></script>
<script src="/madera/lib/jquery-ui-1.9.2.custom/js/jquery-ui-1.9.2.custom.js" type="text/javascript"></script>
<script src="/madera/lib/jquery-ui-1.9.2.custom/js/ui.datepicker-es.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">

$(document).ready(function(){
	var $radios = $('input:radio[name=tipopago]');
 	$radios.filter('[value=T]').prop('checked', true);
	$.datepicker.setDefaults($.datepicker.regional['es']);
	$("#fecha").mask("99-99-9999");
	$("#fecha").datepicker({
		firstDay: 1,
		showButtonPanel: true,
		showOn: "button",
		buttonImage: "../img/calendar.png",
		buttonImageOnly: true,
		buttonText: "Seleccione la fecha",
		changeMonth: true,
		changeYear: true
    });
});


function calcularRetencion(valor, total) {
	if (valor == 0) {
		document.getElementById("rete").value = "0.00";
		document.getElementById("apagar").value = parseFloat(total).toFixed(2);
		document.getElementById("rete").disabled = true;
	} else {
		var rete = parseFloat(0).toFixed(2);
		document.getElementById("rete").disabled = false;
		var minRete = 67170;
		if (total > minRete) {
			rete = parseFloat(parseFloat(total) * 0.02).toFixed(2);
		}
		document.getElementById("rete").value = rete;
		document.getElementById("apagar").value = parseFloat(total - rete).toFixed(2);
	}
}

function calcularApagar(rete, total) {
	if (!isNumberPositivo(rete) || rete > total) {
		alert("La retencion debe ser un numero positivo y menor al total a pagar");
		document.getElementById("rete").value = "0.00";
		document.getElementById("apagar").value = parseFloat(total).toFixed(2);
	} else {
		document.getElementById("apagar").value = parseFloat(total - rete).toFixed(2);
	}
}

function habilitarEMail(valor) {
	if (valor == 0) {
		document.getElementById("email").disabled = true;
		document.getElementById("email").style.backgroundColor = "silver";
	} else {
		document.getElementById("email").disabled = false;
		document.getElementById("email").style.backgroundColor = "";
	}
}

function habilitarNro(valor) {
	document.getElementById("numero").value = "";
	document.getElementById("numero").disabled = false;
	if (valor == "E") {
		document.getElementById("numero").disabled = true;
	}
}

function validar(formulario) {
	if (formulario.tipopago.value != 'E') {
		if (formulario.numero.value == "" || !isNumber(formulario.numero.value)) {
			alert("El Nro de la forma de pago es obligatorio y numerico");
			return false
		}
	}
	if (formulario.fecha.value == "") {
		alert("La fecha del pago es obligatoria");
		return false
	}
	formulario.gurdarOrden.disabled = true;
	$.blockUI({ message: "<h1>Generando Orden de Pago<br>Aguarde por favor...</h1>" });
	return true;
}



</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="reset" name="volver" value="Volver" onclick="location.href = 'listadoFacturasNM.php'"/></p>
	<h3>Facturas Incluidas en la Orden de Pago</h3>
	<div style="border: solid; margin-bottom: 15px; width: 50%">
		<h4> Código: <font color='blue'><?php echo $rowPrestador['codigoprestador']?></font> - C.U.I.T.: <font color='blue'><?php echo $rowPrestador['cuit']?></font> 
		<br/> Razon Social: <font color='blue'><?php echo $rowPrestador['nombre'] ?></font></h4>
	</div>
	<form id="formorden" name="formorden" method="post" onsubmit="return validar(this)" action="guardarOrdenNM.php" >
		<input type="text" value="<?php echo $rowPrestador['codigoprestador'] ?>" id="codigo" name="codigo" style="display: none"/>
		<div class="grilla" style="width: 70%">
		   	<table>
				<thead>
					<tr>
						<th>Nro. Interno</th>
						<th>Nro. Factura</th>
						<th>Fecha</th>
						<th>Fecha Vto.</th>
						<th>Imp. Factura</th>
						<th>Debitos</th>
						<th>A Pagar</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php echo $idfactura;?></td>
						<td>
							<?php echo $factura['puntodeventa']."-".$factura['nrocomprobante'] ?>
							<input style="display: none" type="text" value="<?php echo $idfactura?>" id="id" name="id"/>
							
						</td>
						<td><?php echo $factura['fechamostrar'];?></td>
						<td><?php echo $factura['fechavencimiento'];?></td>
						<td><?php echo number_format($factura['importecomprobante'],2,',','.');?></td>
						<td>
							<?php echo number_format($factura['totaldebito'],2,',','.');?>
							<input style="display: none" type="text" id="debito" value="<?php echo $factura['totaldebito'] ?>" name="debito" /> 
						</td>
						<td><?php echo number_format($factura['restoapagar'],2,',','.');?></td>
					</tr>
				</tbody>
			</table>
		</div>
		
		<h3>Datos Orden de Pago</h3>
		
		<table border="0" width="60%">
			<tr>
				<td align="right"><b>Forma de pago</b></td>
				<td>
			<?php 	$sqlTipoFormaPago = "SELECT * FROM tipoformadepago ORDER BY id DESC";
					$resTipoFormaPago = mysql_query($sqlTipoFormaPago,$db);
					while($rowTipoFormaPago = mysql_fetch_array($resTipoFormaPago)) {  ?>
						<input type="radio" name="tipopago" value="<?php echo $rowTipoFormaPago['id']?>" onchange="habilitarNro(this.value)"/> <?php echo $rowTipoFormaPago['descripcion']?> <br/>
			<?php 	} ?>
		  		</td>
		  		<td><b>Nº</b></td>
		  		<td><input type="text" name="numero" id="numero"/></td>
		  		<td><b>Fecha</b></td>
		  		<td><input type="text" name="fecha" id="fecha" size="8"/></td>
	  		</tr>
	  		<tr>
	  			<td align="right"><b>Retención</b></td>
	  			<td>
					<input type="radio" name="retencion" value="0" checked="checked" onclick="calcularRetencion(this.value, <?php echo $total?>)"/> NO <br/>
		  			<input type="radio" name="retencion" value="1" onclick="calcularRetencion(this.value, <?php echo $total?>)"/> SI (2 %)
		  		</td>
		  		<td><b>Monto</b></td>
		  		<td><input size="12" type="text" name="rete" id="rete" disabled="disabled" value="0.00" onchange="calcularApagar(this.value, <?php echo $total?>)"/></td>
	  			<td><b>A pagar</b></td>
		  		<td><input size="12" type="text" name="apagar" id="apagar" style="background-color: silver;" readonly="readonly" value="<?php echo $total ?>" /></td>
	  		</tr>
		<?php if ($envioEmail) { ?>
			<tr>
				<td align="right"><b>Envio por mail</b></td>
				<td>
					<input type="radio" name="enviomail" value="0" checked="checked" onclick="habilitarEMail(this.value)"/> NO <br/>
		  		<!-- <input type="radio" name="enviomail" value="1" onclick="habilitarEMail(this.value)"/> SI   -->    
		  		</td>
		  		<td><b>Email</b></td>
		  		<td>
		  			<select id="email" name="email" disabled="disabled" style="background-color: silver">
		  			<?php if ($rowPrestador['email1'] != NULL) { ?>
		  					<option value="<?php echo $rowPrestador['email1']?>"><?php echo $rowPrestador['email1']?></option>
		  			<?php } 
		  				  if ($rowPrestador['email2'] != NULL) { ?>
		  					<option value="<?php echo $rowPrestador['email2']?>"><?php echo $rowPrestador['email2']?></option>
		  			<?php } ?>
		  			</select>
		  		</td>
			</tr>
		<?php }?>
  		</table>
		<p><input type="submit" name="gurdarOrden" value="Generar Orden de Pago" /></p>
	</form>
</div>
</body>
</html>