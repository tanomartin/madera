<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php");
$cuit = $_GET["cuit"];
$acuerdo = $_GET["acuerdo"];
$cuota = $_GET["cuota"];	
if (isset($_POST['fechapagada'])) {
	$datos = array_values($_POST);
	$fechapagada = $datos[0];
	$cuentaBoleta = $datos[1];
	$quees = $datos[2];
	
	if ($quees == "remesa") {
		$cuentaRemesa = $datos[3];
		$fechaRemesa = $datos[4];
		$nroremesa = $datos[5];
		if (isset($datos[6])) { $nroremito = $datos[6]; }
		if (isset($datos[7])) { $observ = $datos[7]; }
		$cuentaRemito = 0;
		$fechaRemito = "0000-00-00";
		$nroRemitoSuelto = 0;
		$fechaInvertida = fechaParaGuardar($fechaRemesa);
		$sqlRemesa="select * from remesasusimra where codigocuenta = $cuentaRemesa and sistemaremesa = 'M' and fecharemesa = '$fechaInvertida'";
		$resRemesa=mysql_query($sqlRemesa,$db);	
		if ($nroremesa!=0) {
			$sqlRem="select * from remitosremesasusimra where codigocuenta = $cuentaRemesa and sistemaremesa = 'M' and fecharemesa = '$fechaInvertida' and nroremesa = $nroremesa";
			$resRem=mysql_query($sqlRem,$db);
		}
	} 
	if ($quees == "remito") {
		$cuentaRemito = $datos[3];
		$fechaRemito = $datos[4];
		$nroRemitoSuelto = $datos[5];
		if (isset($datos[6])) { $observ = $datos[6]; }
		$cuentaRemesa = 0;
		$fechaRemesa = "0000-00-00";
		$nroremesa = 0;
		$nroremito = 0;
		$fechaInvertida = fechaParaGuardar($fechaRemito);
		$sqlRemitoSuelto = "select * from remitossueltosusimra where codigocuenta = $cuentaRemito and sistemaremito = 'M' and fecharemito = '$fechaInvertida'";
		$resRemitoSuelto=mysql_query($sqlRemitoSuelto,$db);
	}
} else {
	$fechaRemito = "0000-00-00";
	$fechaRemesa = "0000-00-00";
}

$sqlCab = "select * from cabacuerdosusimra where cuit = $cuit and nroacuerdo = $acuerdo";
$resCab = mysql_query($sqlCab,$db); 
$rowCab = mysql_fetch_array($resCab);

$sqlCuo = "select * from cuoacuerdosusimra where cuit = $cuit and nroacuerdo = $acuerdo and nrocuota = $cuota";
$resCuo = mysql_query($sqlCuo,$db); 
$rowCuo = mysql_fetch_array($resCuo);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>.: Confirmar Cancelacion :.</title>
</head>

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(function($){
	$("#fechapagada").mask("99-99-9999");
	$("#fecharemesa").mask("99-99-9999");
	$("#fecharemito").mask("99-99-9999");
});

function limpiarFechaRemesa(){
	document.forms.formularioSeleCuotas.fecharemesa.value = "";
	document.forms.formularioSeleCuotas.fecharemesa.disabled = true;
	document.forms.formularioSeleCuotas.botonRemesas.disabled = true;
	document.forms.formularioSeleCuotas.botonRemitoRemesa.disabled = true;
}

function limpiarFechaRemito(){
	document.forms.formularioSeleCuotas.fecharemito.value = "";
	document.forms.formularioSeleCuotas.fecharemito.disabled = true;
	document.forms.formularioSeleCuotas.botonRemitos.disabled = true;
}

function limpiarRemesas(){
	document.forms.formularioSeleCuotas.selectRemesa.length = 0;
	document.forms.formularioSeleCuotas.selectRemito.length = 0;
	document.forms.formularioSeleCuotas.selectRemesa.disabled = true;
	document.forms.formularioSeleCuotas.selectRemito.disabled = true;
	document.forms.formularioSeleCuotas.quees.value = "";
}

function limpiarRemitoSuelto(){
	document.forms.formularioSeleCuotas.selectRemitoSuelto.length = 0;
	document.forms.formularioSeleCuotas.selectRemitoSuelto.disabled = true;
	document.forms.formularioSeleCuotas.quees.value = "";
}

function LogicaCargaRemesa(Cuenta) {
	document.forms.formularioSeleCuotas.selectCuentaRemito.disabled = false;
	limpiarFechaRemesa();
	limpiarRemesas();
	if (Cuenta != 0) {
		document.forms.formularioSeleCuotas.selectCuentaRemito.disabled = true;
		document.forms.formularioSeleCuotas.fecharemesa.value = "";
		document.forms.formularioSeleCuotas.fecharemesa.disabled = false;
		document.forms.formularioSeleCuotas.quees.value = "remesa"
	}
}

function LogicaCargaRemito(Cuenta) {
	document.forms.formularioSeleCuotas.selectCuentaRemesa.disabled = false;
	limpiarFechaRemito();
	limpiarRemitoSuelto();
	if (Cuenta != 0) {
		document.forms.formularioSeleCuotas.selectCuentaRemesa.disabled = true;
		document.forms.formularioSeleCuotas.fecharemito.value = "";		
		document.forms.formularioSeleCuotas.fecharemito.disabled = false;
		document.forms.formularioSeleCuotas.quees.value = "remito";
	}
}

function validarFechaHabilitaBoton(fecha) {
	document.forms.formularioSeleCuotas.selectRemesa.length = 0;
	document.forms.formularioSeleCuotas.selectRemito.length = 0;
	if (!esFechaValida(fecha)){
		alert("La fecha no es valida");
		document.forms.formularioSeleCuotas.botonRemesas.disabled = true;
		document.forms.formularioSeleCuotas.selectRemito.disabled = true;
	} else {
		document.forms.formularioSeleCuotas.botonRemesas.disabled = false;
	}
}

function habilitarBotonRemito(remesa){
	document.forms.formularioSeleCuotas.selectRemito.length = 0;
	if(remesa!=0) {
		document.forms.formularioSeleCuotas.botonRemitoRemesa.disabled = false;
		document.forms.formularioSeleCuotas.selectRemito.disabled = false;
	} else {
		document.forms.formularioSeleCuotas.botonRemitoRemesa.disabled = true;
		document.forms.formularioSeleCuotas.selectRemito.disabled = true;
	}
}

function limpiarSelect(){
	document.forms.formularioSeleCuotas.selectRemesa.length = 0;
	document.forms.formularioSeleCuotas.selectRemito.length = 0;
	document.forms.formularioSeleCuotas.selectRemesa.disabled = true;
	document.forms.formularioSeleCuotas.selectRemito.disabled = true;
	document.forms.formularioSeleCuotas.botonRemitoRemesa.disabled = true;
}

function validarFechaHabilitaBotonRemitoSuelto(fecha) {
	document.forms.formularioSeleCuotas.selectRemitoSuelto.length = 0;
	if (!esFechaValida(fecha)){
		alert("La fecha no es valida");
		document.forms.formularioSeleCuotas.botonRemitos.disabled = true;
	} else {
		document.forms.formularioSeleCuotas.botonRemitos.disabled = false;
	}
}

function limpiarSelectRemitoSuelto(){
	document.forms.formularioSeleCuotas.selectRemitoSuelto.length = 0;
	document.forms.formularioSeleCuotas.selectRemitoSuelto.disabled = true;
}

function logicaHabilitacion() {
	if (document.forms.formularioSeleCuotas.selectCuentaRemesa.value != 0) {
		document.forms.formularioSeleCuotas.selectCuentaRemito.disabled = true;
		document.forms.formularioSeleCuotas.fecharemesa.disabled = false;
		if (document.forms.formularioSeleCuotas.fecharemesa.value != "") {
			document.forms.formularioSeleCuotas.selectRemesa.disabled = false;
			if (document.forms.formularioSeleCuotas.selectRemesa.value != 0) {
				document.forms.formularioSeleCuotas.selectRemito.disabled = false;
				document.forms.formularioSeleCuotas.botonRemitoRemesa.disabled = false;
			}
		}
	}
	if (document.forms.formularioSeleCuotas.selectCuentaRemito.value != 0) {
		document.forms.formularioSeleCuotas.selectCuentaRemesa.disabled = true;
		document.forms.formularioSeleCuotas.fecharemito.disabled = false;
		if (document.forms.formularioSeleCuotas.fecharemito.value != "") {
			document.forms.formularioSeleCuotas.selectRemitoSuelto.disabled = false;
		}
	}
}

function validar(formulario) {
	document.body.style.cursor = 'wait';
	var fecha = formulario.fechapagada.value;
	var cuentaBoleta = formulario.selectCuenta.value;
	var cuentaRemesa = formulario.selectCuentaRemesa.value;
	var cuentaRemito = formulario.selectCuentaRemito.value;
	var fechaRemesa = formulario.fecharemesa.value;
	var fechaRemito = formulario.fecharemito.value;
	var nroRemesa = formulario.selectRemesa.value;
	var nroRemito = formulario.selectRemito.value;
	var nroRemitoSuelto = formulario.selectRemitoSuelto.value;
	
	if (!esFechaValida(fecha)) {
		alert("La fecha no es valida");
		document.body.style.cursor = 'default';
		return false;
	}
	if (cuentaBoleta == 0) {
		alert("Debe elegir una cuenta de boleta");
		document.body.style.cursor = 'default';
		return false;
	}
	if (cuentaRemesa == 0 && cuentaRemito == 0) {
		alert("Debe elegir cuenta de remesa o de remito suelto");
		document.body.style.cursor = 'default';
		return false;
	}
	
	if (cuentaRemesa != 0) {
		if (!esFechaValida(fechaRemesa)) {
			alert("La fecha no es valida");
			return false;
		}
		if (nroRemesa == 0) {
			alert("Debe elegir un nro de remesa");
			document.body.style.cursor = 'default';
			return false;
		}
		if (nroRemito == 0) {
			alert("Debe elegir un nro de remito");
			document.body.style.cursor = 'default';
			return false;
		}
	}
	
	if (cuentaRemito != 0) {
		if (!esFechaValida(fechaRemito)) {
			alert("La fecha no es valida");
			document.body.style.cursor = 'default';
			return false;
		}
		if (nroRemitoSuelto == 0) {
		  	alert("Debe elegir un nro de remito suelto");
			document.body.style.cursor = 'default';
			return false;
		}
	}
	formulario.Submit.disabled=true;
	return true;
}
</script>

<body bgcolor="#B2A274" onLoad="logicaHabilitacion()">
<div align="center">
  	<input type="button" name="volver" value="Volver" onClick="location.href = 'selecCanCuotas.php?cuit=<?php echo $cuit ?>&acuerdo=<?php echo $acuerdo ?>'"/> 
	  <?php include($libPath."cabeceraEmpresaConsulta.php"); 
			include($libPath."cabeceraEmpresa.php"); ?>
	<form id="formularioSeleCuotas" name="formularioSeleCuotas" method="post" action="cancelarCuota.php?cuit=<?php echo $cuit ?>&acuerdo=<?php echo $acuerdo ?>&cuota=<?php echo $cuota ?>"  onSubmit="return validar(this)">
    	  <h3>Acuerdo N&uacute;mero <?php echo $acuerdo ?> Cuota <?php echo $cuota ?> </h3>
	 	  <table border="1" style="text-align: center; width: 800">
			<tr>
   				<th>Monto</th>
    			<th>Fecha Vto.</th>
    			<th>Tipo Cancelacion</th>
				<th>Nro Cheque</th>
				<th>Banco</th>
				<th>Fecha Cheque</th>
			</tr>
			<tr>
		<?php $sqltipocan = "select * from tiposcancelaciones where codigo = $rowCuo[tipocancelacion]";
			  $restipocan =  mysql_query( $sqltipocan,$db);
			  $rowtipocan = mysql_fetch_array($restipocan); ?>
			  <td><?php echo $rowCuo['montocuota'] ?></td>
			  <td><?php echo invertirFecha($rowCuo['fechacuota']) ?></td>
			  <td><?php echo $rowtipocan['descripcion'] ?></td>	
		<?php if ($rowCuo['chequenro'] == 0) { ?>
					<td>-</td>
					<td>-</td>
					<td>-</td>
		<?php } else { ?>
					<td><?php echo $rowCuo['chequenro'] ?></td>
					<td><?php echo $rowCuo['chequebanco'] ?></td>
					<td><?php echo invertirFecha($rowCuo['chequefecha']) ?></td>
		<?php } ?>
			</tr>
		  </table>
    	  <p>Fecha de Pago <input name="fechapagada" type="text" id="fechapagada" size="8" value="<?php echo $fechapagada ?>"></p>
    	  <p>Cuenta de la Boleta
          <select name="selectCuenta"  id="selectCuenta">
		          <option value=0 selected="selected">Seleccione una Cuenta </option>
		          <?php 
					$query="select * from cuentasusimra";
					$result=mysql_query($query,$db);
					while ($rowcuentas=mysql_fetch_array($result)) {
						if ($rowcuentas['codigocuenta'] == $cuentaBoleta ) { ?>
		                	<option value="<?php echo $rowcuentas['codigocuenta'] ?>" selected="selected"><?php echo $rowcuentas['descripcioncuenta']  ?></option>
						<?php } else { ?>
							 <option value="<?php echo $rowcuentas['codigocuenta'] ?>"><?php echo $rowcuentas['descripcioncuenta']  ?></option>			
						<?php } 
		            } ?>
         </select>
       	 <input type="text" name="quees" id="quees" value="<?php echo $quees ?>" style="visibility:hidden" size="1">
	     </p>
	     <table width="834" border="0">
	       <tr>
	         <td colspan="2"><div align="center"><b>REMESA </b></div></td>
	         <td colspan="2"><div align="center"><b>REMITO SUELTO </b></div></td>
	       </tr>
	       <tr>
	         <td width="142"><div align="right">Cuenta de la Remesa</div></td>
	         <td width="263">  
			 	<select name="selectCuentaRemesa" id="selectCuentaRemesa" onChange="LogicaCargaRemesa(document.forms.formularioSeleCuotas.selectCuentaRemesa[selectedIndex].value);">
			          <option value=0 selected="selected">Seleccione Cuenta de Remesa </option>
			          <?php 
						$query="select * from cuentasusimra";
						$result=mysql_query($query,$db);
						while ($rowcuentas=mysql_fetch_array($result)) { 
			         		if ($rowcuentas['codigocuenta'] == $cuentaRemesa ) { ?>
			                	<option value="<?php echo $rowcuentas['codigocuenta'] ?>" selected="selected"><?php echo $rowcuentas['descripcioncuenta']  ?></option>
							<?php } else { ?>
								 <option value="<?php echo $rowcuentas['codigocuenta'] ?>"><?php echo $rowcuentas['descripcioncuenta']  ?></option>			
							<?php } 
			            } ?>
	           </select>
		     </td>
	         <td width="143">
	         <div align="right">Cuenta Reminto Suelto</div></td>
	         <td width="268">	
			    <select name="selectCuentaRemito" id="selectCuentaRemito" onChange="LogicaCargaRemito(document.forms.formularioSeleCuotas.selectCuentaRemesa[selectedIndex].value);">
			          <option value=0 selected="selected">Seleccione Cuenta de Remito </option>
			          <?php 
						$query="select * from cuentasusimra";
						$result=mysql_query($query,$db);
						while ($rowcuentas=mysql_fetch_array($result)) { 
							if ($rowcuentas['codigocuenta'] == $cuentaRemito) {?>
			         			<option value="<?php echo $rowcuentas['codigocuenta'] ?>" selected="selected"><?php echo $rowcuentas['descripcioncuenta']  ?></option>
			          <?php } else { ?>
					  			<option value="<?php echo $rowcuentas['codigocuenta'] ?>"><?php echo $rowcuentas['descripcioncuenta']  ?></option>
					  <?php	}
					    }?>
	         </select></td>
	       </tr>
	       <tr>
	         <td>
	           <div align="right">Fecha de la Remesa</div></td>
	         <td>
	           <input name="fecharemesa" type="text" id="fecharemesa" size="8" disabled="disabled" value="<?php if ($fechaRemesa!="0000-00-00") echo $fechaRemesa ?>" onfocusout="validarFechaHabilitaBoton(this.value)" onFocus="limpiarSelect()">
	           <input name="botonRemesas" type="button" id="botonRemesas" value="Ver Remesas" disabled="disabled" onClick="this.form.action='confirmarCancelacion.php?cuota=<?php echo $cuota ?>&acuerdo=<?php echo $acuerdo ?>&cuit=<?php echo $cuit ?>';this.form.submit();">
	         </td>
	         <td>
	           <div align="right">Fecha Remito Suelto</div></td>
	         <td> 
	         	<input name="fecharemito" type="text" id="fecharemito" size="8" disabled="disabled" value="<?php if ($fechaRemito!="0000-00-00") echo $fechaRemito ?>" onfocusout="validarFechaHabilitaBotonRemitoSuelto(this.value)" onFocus="limpiarSelectRemitoSuelto()">
	         	<input name="botonRemitos" type="button" id="botonRemitos" value="Ver Remitos" disabled="disabled"  onClick="this.form.action='confirmarCancelacion.php?cuota=<?php echo $cuota ?>&acuerdo=<?php echo $acuerdo ?>&cuit=<?php echo $cuit ?>';this.form.submit();">
	         </td>
	       </tr>
	       <tr>
			 <td><div align="right">Nro Remesa</div></td>
	         <td><select name="selectRemesa" id="selectRemesa" disabled="disabled" onChange="habilitarBotonRemito(this.value)"> 
			 <?php while ($rowRemesa=mysql_fetch_array($resRemesa)) { 
			 		  if ($rowRemesa['nroremesa'] == $nroremesa ) { ?>
					    <option value="<?php echo $rowRemesa['nroremesa'] ?>" selected="selected"><?php echo $rowRemesa['nroremesa'] ?></option>
	  		    <?php } else { ?>
						<option value="<?php echo $rowRemesa['nroremesa'] ?>"><?php echo $rowRemesa['nroremesa'] ?></option>
					<?php }
					} ?>
			  </select>
			  <input name="botonRemitoRemesa" type="button" id="botonRemitoRemesa" value="Ver Remitos" disabled="disabled" onClick="this.form.action='confirmarCancelacion.php?cuota=<?php echo $cuota ?>&acuerdo=<?php echo $acuerdo ?>&cuit=<?php echo $cuit ?>';this.form.submit();"></td>
	         <td>
	           <div align="right">Nro Remito Suelto</div>
	         </td>
	         <td><select name="selectRemitoSuelto" id="selectRemitoSuelto" disabled="disabled"> 
			 <?php while ($rowRemitoSuelto=mysql_fetch_array($resRemitoSuelto)) { ?>
						<option value="<?php echo $rowRemitoSuelto['nroremito'] ?>"><?php echo $rowRemitoSuelto['nroremito'] ?></option>
			<?php }?>
			  </select>
			 </td>
	       </tr>
	       <tr>
	         <td>
	          <div align="right">Nro Remito</div>
	         </td>
	         <td><select name="selectRemito" id="selectRemito" disabled="disabled">
			  <?php while ($rowRem=mysql_fetch_array($resRem)) { ?>
						<option value="<?php echo $rowRem['nroremito'] ?>"><?php echo $rowRem['nroremito'] ?></option>
			 <?php }?>
	         </select>
	         </td>
	         <td colspan="2">&nbsp;</td>
	       </tr>
	    </table>
	     <p>>Observacion <textarea name="textarea" cols="50" rows="4"><?php echo $rowCuo['observaciones']?></textarea> </p>
	     <p><input type="submit" name="Submit" id="Submit" value="Cancelar Cuota"></p>
	</form>
</div>
</body>
</html>
