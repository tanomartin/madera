<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php");
$cuit = $_GET["cuit"];
$acuerdo = $_GET["acuerdo"];
$cuota = $_GET["cuota"];	

$datos = array_values($_POST);
if (sizeof($datos) != 0) {
	$fechapagada = $datos[0];
	$cuentaBoleta = $datos[1];
	$quees = $datos[2];
	if ($quees == "remesa") {
		$cuentaRemesa = $datos[3];
		$fechaRemesa = $datos[4];
		$nroremesa = $datos[5];
		$nroremito = $datos[6];
		$observ = $datos[7];
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
		$observ = $datos[6];
		$cuentaRemesa = 0;
		$fechaRemesa = "0000-00-00";
		$nroremesa = 0;
		$nroremito = 0;
		$fechaInvertida = fechaParaGuardar($fechaRemito);
		$sqlRemitoSuelto = "select * from remitossueltosusimra where codigocuenta = $cuentaRemito and sistemaremito = 'M' and fecharemito = '$fechaInvertida'";
		$resRemitoSuelto=mysql_query($sqlRemitoSuelto,$db);
	}
} else {
	$sqlConcilia = "select * from conciliacuotasusimra where cuit = $cuit and nroacuerdo = $acuerdo and nrocuota = $cuota";
	$resConcilia = mysql_query($sqlConcilia,$db); 
	$rowConcilia = mysql_fetch_array($resConcilia);
	$cuentaBoleta = $rowConcilia['cuentaboleta'];
	$cuentaRemesa=$rowConcilia['cuentaremesa'];
	$fechaRemesa =$rowConcilia['fecharemesa'];
	$nroremesa=$rowConcilia['nroremesa'];
	$nroremito=$rowConcilia['nroremitoremesa'];
	$cuentaRemito=$rowConcilia['cuentaremitosuelto'];
	$fechaRemito=$rowConcilia['fecharemitosuelto'];
	$nroRemitoSuelto=$rowConcilia['nroremitosuelto'];
	if ($rowConcilia['cuentaremesa'] != 0) {
		$quees="remesa";
		$sqlRemesa="select * from remesasusimra where codigocuenta = $cuentaRemesa and sistemaremesa = 'M' and fecharemesa = '$fechaRemesa'";
		$resRemesa=mysql_query($sqlRemesa,$db);	
		if ($nroremesa!=0) {
			$sqlRem="select * from remitosremesasusimra where codigocuenta = $cuentaRemesa and sistemaremesa = 'M' and fecharemesa = '$fechaRemesa' and nroremesa = $nroremesa";
			$resRem=mysql_query($sqlRem,$db);
		}
	} else {
		$quees="remito";
		$sqlRemitoSuelto = "select * from remitossueltosusimra where codigocuenta = $cuentaRemito and sistemaremito = 'M' and fecharemito = '$fechaRemito'";
		$resRemitoSuelto=mysql_query($sqlRemitoSuelto,$db);
	}
	$fechaRemesa = invertirFecha($fechaRemesa);
	$fechaRemito = invertirFecha($fechaRemito);
}

$sql = "select * from empresas where cuit = $cuit";
$result = mysql_query( $sql,$db); 
$row=mysql_fetch_array($result); 

$sqllocalidad = "select * from localidades where codlocali = $row[codlocali]";
$resultlocalidad = mysql_query( $sqllocalidad,$db); 
$rowlocalidad = mysql_fetch_array($resultlocalidad); 

$sqlprovi =  "select * from provincia where codprovin = $row[codprovin]";
$resultprovi = mysql_query( $sqlprovi,$db); 
$rowprovi = mysql_fetch_array($resultprovi);

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
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none;color:#0033FF}
A:hover {text-decoration: none;color:#33CCFF }
</style>

<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(function($){
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
	var cuentaBoleta = formulario.selectCuenta.value;
	var cuentaRemesa = formulario.selectCuentaRemesa.value;
	var cuentaRemito = formulario.selectCuentaRemito.value;
	var fechaRemesa = formulario.fecharemesa.value;
	var fechaRemito = formulario.fecharemito.value;
	var nroRemesa = formulario.selectRemesa.value;
	var nroRemito = formulario.selectRemito.value;
	var nroRemitoSuelto = formulario.selectRemitoSuelto.value;
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
	return true;
}
</script>

<body bgcolor="#B2A274" onLoad="logicaHabilitacion()">
<div align="center">
  <p><strong><a href="selecCanCuotas.php?cuit=<?php echo $cuit ?>&acuerdo=<?php echo $acuerdo ?>"><font face="Verdana" size="2"><b>VOLVER</b></font></a></strong></p>
	 <?php 	
		include($libPath."cabeceraEmpresa.php"); 
	?>
<form id="formularioSeleCuotas" name="formularioSeleCuotas" method="post" action="modificarDatosConciliacion.php?cuit=<?php echo $cuit ?>&acuerdo=<?php echo $acuerdo ?>&cuota=<?php echo $cuota ?>"  onSubmit="return validar(this)">
  <div align="center">
    <p><strong>Acuerdo N&uacute;mero </strong> <?php echo $acuerdo ?> <strong>Cuota</strong> <?php echo $cuota ?> </p>
	 <table border="1" width="935" bordercolorlight="#000000" bordercolordark="#000000" bordercolor="#000000" cellpadding="2" cellspacing="0">
				<tr>
   					<td width="168"><div align="center"><strong><font size="1" face="Verdana">Monto</font></strong></div></td>
    				<td width="168"><div align="center"><strong><font size="1" face="Verdana">Fecha Vto.</font></strong></div></td>
    				<td width="168"><div align="center"><strong><font size="1" face="Verdana">Tipo Cancelacion</font></strong></div></td>
					<td width="168"><div align="center"><strong><font size="1" face="Verdana">Nro Cheque</font></strong></div></td>
					<td width="168"><div align="center"><strong><font size="1" face="Verdana">Banco</font></strong></div></td>
					<td width="168"><div align="center"><strong><font size="1" face="Verdana">Fecha Cheque</font></strong></div></td>
				</tr>
				<?php
				print ("<td width=168><div align=center><font face=Verdana size=1>".$rowCuo['montocuota']."</font></div></td>");
				print ("<td width=168><div align=center><font face=Verdana size=1>".invertirFecha($rowCuo['fechacuota'])."</font></div></td>");
				
				$sqltipocan = "select * from tiposcancelaciones where codigo = $rowCuo[tipocancelacion]";
				$restipocan =  mysql_query( $sqltipocan,$db);
				$rowtipocan = mysql_fetch_array($restipocan);
				
				print ("<td width=168><div align=center><font face=Verdana size=1>".$rowtipocan['descripcion']."</font></div></td>");
				
				if ($rowCuo['chequenro'] == 0) {
					print ("<td width=168><div align=center><font face=Verdana size=1>-</font></div></td>");
					print ("<td width=168><div align=center><font face=Verdana size=1>-</font></div></td>");
					print ("<td width=168><div align=center><font face=Verdana size=1>-</font></div></td>");
				} else {
					print ("<td width=168><div align=center><font face=Verdana size=1>".$rowCuo['chequenro']."</font></div></td>");
					print ("<td width=168><div align=center><font face=Verdana size=1>".$rowCuo['chequebanco']."</font></div></td>");
					print ("<td width=168><div align=center><font face=Verdana size=1>".invertirFecha($rowCuo['chequefecha'])."</font></div></td>");
				}
				print ("</tr>"); 
				?>
	</table>
     <p>Fecha de Pago <input name="fechapagada" readonly="readonly" value="<?php echo invertirFecha($rowCuo['fechacancelacion']); ?>" type="text" id="fechapagada" size="8" style="background-color:#CCCCCC">
     </p>
     <p>Cuenta de la Boleta
       <label>
        <select name="selectCuenta"  id="selectCuenta">
		          <option value=0>Seleccione una Cuenta </option>
		          <?php 
					$query="select * from cuentasusimra";
					$result=mysql_query($query,$db);
					while ($rowcuentas=mysql_fetch_array($result)) { 
						if ($rowcuentas['codigocuenta'] == $cuentaBoleta){?>
		         			<option value="<?php echo $rowcuentas['codigocuenta'] ?>" selected="selected"><?php echo $rowcuentas['descripcioncuenta']?></option>	 
		          <?php } else { ?>
				   			<option value="<?php echo $rowcuentas['codigocuenta'] ?>"><?php echo $rowcuentas['descripcioncuenta']  ?></option>
				   <?php } ?>
			 <?php } ?>
       </select>
       </label>
       <label>
       <input type="text" name="quees" id="quees" value="<?php echo $quees ?>" style="visibility:hidden" size="1">
       </label>
</p>
     <label></label>
     <table width="834" border="0">
       <tr>
         <td colspan="2"><div align="center"><strong>REMESA </strong></div></td>
         <td colspan="2"><div align="center"><strong>REMITO SUELTO </strong></div></td>
       </tr>
       <tr>
         <td width="142"><div align="right">Cuenta de la Remesa
           
         </div></td>
         <td width="263">  
		 	<select name="selectCuentaRemesa" id="selectCuentaRemesa" onChange="LogicaCargaRemesa(document.forms.formularioSeleCuotas.selectCuentaRemesa[selectedIndex].value);">
		          <option value=0 selected="selected">Seleccione Cuenta de Remesa </option>
		          <?php 
					$query="select * from cuentasusimra";
					$result=mysql_query($query,$db);
					while ($rowcuentas=mysql_fetch_array($result)) { 
						if ($rowcuentas['codigocuenta'] == $cuentaRemesa) {	?>
							<option value="<?php echo $rowcuentas['codigocuenta'] ?>" selected="selected"><?php echo $rowcuentas['descripcioncuenta']  ?></option>	
		          <?php } else { ?>
				 			<option value="<?php echo $rowcuentas['codigocuenta'] ?>"><?php echo $rowcuentas['descripcioncuenta']  ?></option>	
				  <?php } ?>
			<?php } ?>
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
						if ($rowcuentas['codigocuenta'] == $cuentaRemito) {	?>
		         			 <option value="<?php echo $rowcuentas['codigocuenta'] ?>" selected="selected"><?php echo $rowcuentas['descripcioncuenta']  ?></option>
		          <?php } else { ?>
				    		 <option value="<?php echo $rowcuentas['codigocuenta'] ?>"><?php echo $rowcuentas['descripcioncuenta']  ?></option>
				<?php } ?>
			<?php } ?>
         </select></td>
       </tr>
       <tr>
         <td>
           <div align="right">Fecha de la Remesa</div></td>
         <td><label>
          <input name="fecharemesa" type="text" id="fecharemesa" size="8" disabled="disabled" value="<?php if ($fechaRemesa!="0000-00-00" && $fechaRemesa!="00/00/0000") echo $fechaRemesa ?>" onFocusOut="validarFechaHabilitaBoton(this.value)" onFocus="limpiarSelect()">
           <input name="botonRemesas" type="button" id="botonRemesas" value="Ver Remesas" disabled="disabled" onClick="this.form.action='datosConciliacion.php?cuota=<?php echo $cuota ?>&acuerdo=<?php echo $acuerdo ?>&cuit=<?php echo $cuit ?>';this.form.submit();">
           </label></td>
         <td>
           <div align="right">Fecha Remito Suelto</div></td>
         <td> <input name="fecharemito" type="text" id="fecharemito" size="8" disabled="disabled" value="<?php if ($fechaRemito!="0000-00-00" && $fechaRemito!="00/00/0000") echo $fechaRemito ?>" onFocusOut="validarFechaHabilitaBotonRemitoSuelto(this.value)" onFocus="limpiarSelectRemitoSuelto()">
         <input name="botonRemitos" type="button" id="botonRemitos" value="Ver Remitos" disabled="disabled"  onClick="this.form.action='datosConciliacion.php?cuota=<?php echo $cuota ?>&acuerdo=<?php echo $acuerdo ?>&cuit=<?php echo $cuit ?>';this.form.submit();"></td>
       </tr>
       <tr>
         <td>
          <div align="right">Nro Remesa</div></td>
         <td><select name="selectRemesa" id="selectRemesa" disabled="disabled" onChange="habilitarBotonRemito(this.value)"> 
		 <?php while ($rowRemesa=mysql_fetch_array($resRemesa)) { 
		 		  if ($rowRemesa['nroremesa'] == $nroremesa ) { ?>
				    <option value="<?php echo $rowRemesa['nroremesa'] ?>" selected="selected"><?php echo $rowRemesa['nroremesa'] ?></option>
  		    <?php } else { ?>
					<option value="<?php echo $rowRemesa['nroremesa'] ?>"><?php echo $rowRemesa['nroremesa'] ?></option>
				<?php }
				} ?>
		  </select><input name="botonRemitoRemesa" type="button" id="botonRemitoRemesa" value="Ver Remitos" disabled="disabled" onClick="this.form.action='datosConciliacion.php?cuota=<?php echo $cuota ?>&acuerdo=<?php echo $acuerdo ?>&cuit=<?php echo $cuit ?>';this.form.submit();"></td>
         <td>
           <div align="right">Nro Remito Suelto</div></td>
         <td><select name="selectRemitoSuelto" id="selectRemitoSuelto" disabled="disabled"> 
		 <?php while ($rowRemitoSuelto=mysql_fetch_array($resRemitoSuelto)) { 
		 			if ($rowRemitoSuelto['nroremito'] == $nroRemitoSuelto) {?>
						<option value="<?php echo $rowRemitoSuelto['nroremito'] ?>" selected="selected"><?php echo $rowRemitoSuelto['nroremito'] ?></option>
					<?php } else { ?>
						<option value="<?php echo $rowRemitoSuelto['nroremito'] ?>"><?php echo $rowRemitoSuelto['nroremito'] ?></option>
					<?php }
					}?>
		  </select></td>
       </tr>
       <tr>
         <td>
          <div align="right">Nro Remito</div></td>
         <td><select name="selectRemito" id="selectRemito" disabled="disabled">
		  <?php while ($rowRem=mysql_fetch_array($resRem)) { 
		  			echo $rowRem['nroremito'];
		  			if ($rowRem['nroremito'] == $nroremito) { ?>
						<option value="<?php echo $rowRem['nroremito'] ?>" selected="selected"><?php echo $rowRem['nroremito'] ?></option>
			  <?php } else { ?>
			  			<option value="<?php echo $rowRem['nroremito'] ?>"><?php echo $rowRem['nroremito'] ?></option>
				<?php }
				} ?>
         </select></td>
         <td colspan="2">&nbsp;</td>
       </tr>
    </table>
     <p>
       <label></label>
       <label></label><label>Observacion
	   <textarea name="textarea" style="background:#CCCCCC" cols="50" rows="4" readonly="readonly"><?php echo  $rowCuo['observaciones'] ?> </textarea>
       </label>
     </p>
     <p>
       <label>
       <input type="submit" name="Submit" value="Modificar Datos Conciliacion">
       </label>
     </p>
  </div>
</form>
<p align="center">&nbsp;</p>
</body>
</html>