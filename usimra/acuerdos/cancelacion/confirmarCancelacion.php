<?php include($_SERVER['DOCUMENT_ROOT']."/usimra/lib/controlSession.php"); 
include($_SERVER['DOCUMENT_ROOT']."/usimra/lib/fechas.php"); 
$cuit = $_GET["cuit"];
$acuerdo = $_GET["acuerdo"];
$cuota = $_GET["cuota"];	

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

<script src="../../lib/jquery.js" type="text/javascript"></script>
<script src="../../lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="../../lib/funcionControl.js" type="text/javascript"></script>
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
}

function limpiarRemitoSuelto(){
	document.forms.formularioSeleCuotas.selectRemitoSuelto.length = 0;
	document.forms.formularioSeleCuotas.selectRemesa.disabled = true;
	document.forms.formularioSeleCuotas.selectRemito.disabled = true;
}

function limpiarRemitos(){
	document.forms.formularioSeleCuotas.selectRemito.length = 0;
	document.forms.formularioSeleCuotas.selectRemitoSuelto.disabled = true;
}

function LogicaCargaRemesa(Cuenta) {
		if (Cuenta == 0) {
			document.forms.formularioSeleCuotas.selectCuentaRemito.disabled = false;
			limpiarFechaRemesa();
			limpiarRemesas();
		} else {
			document.forms.formularioSeleCuotas.selectCuentaRemito.disabled = true;
			document.forms.formularioSeleCuotas.fecharemesa.disabled = false;
			document.forms.formularioSeleCuotas.botonRemesas.disabled = false;
		}
}

function LogicaCargaRemito(Cuenta) {
		if (Cuenta == 0) {
			document.forms.formularioSeleCuotas.selectCuentaRemesa.disabled = false;
			limpiarFechaRemito();
			limpiarRemitoSuelto();
		} else {
			document.forms.formularioSeleCuotas.selectCuentaRemesa.disabled = true;
			document.forms.formularioSeleCuotas.fecharemito.disabled = false;
			document.forms.formularioSeleCuotas.botonRemitos.disabled = false;
		}
}

function cargarRemesas(){
	limpiarRemesas();
	var cuenta = document.forms.formularioSeleCuotas.selectCuentaRemesa.value;
	var fecha = document.forms.formularioSeleCuotas.fecharemesa.value;
	var o;
	if (fecha == "") {
		alert("Debe cargar fecha de remesa");
	} else {
		fecha = invertirFecha(fecha);
		o = document.createElement("OPTION");
		o.text = 'Seleccione Remesa';
		o.value = 0;
		document.forms.formularioSeleCuotas.selectRemesa.options.add(o);
		<?php 
		$sqlRemesa="select * from remesasusimra";
		$resRemesa=mysql_query($sqlRemesa,$db);
		while ($rowRemesa=mysql_fetch_array($resRemesa)) { ?>
			if (cuenta == <?php echo $rowRemesa['codigocuenta'] ?> && fecha == "<?php echo $rowRemesa['fecharemesa'] ?>" ) {
				o = document.createElement("OPTION");
				o.text = '<?php echo $rowRemesa["nroremesa"]; ?>';
				o.value = <?php echo $rowRemesa["nroremesa"]; ?>;
				document.forms.formularioSeleCuotas.selectRemesa.options.add(o);
			}
  <?php } ?>
	}
	document.forms.formularioSeleCuotas.selectRemesa.disabled = false;
}

function cargarRemitosSueltos(){
	limpiarRemitoSuelto();
	var cuenta = document.forms.formularioSeleCuotas.selectCuentaRemito.value;
	var fecha = document.forms.formularioSeleCuotas.fecharemito.value;
	var o;
	if (fecha == "") {
		alert("Debe cargar fecha de remito");
	} else {
		fecha = invertirFecha(fecha);
		o = document.createElement("OPTION");
		o.text = 'Seleccione Remito';
		o.value = 0;
		document.forms.formularioSeleCuotas.selectRemitoSuelto.options.add(o);
		<?php 
		$sqlRemesa="select * from remitossueltosusimra";
		$resRemesa=mysql_query($sqlRemesa,$db);
		while ($rowRemesa=mysql_fetch_array($resRemesa)) { ?>
			if (cuenta == <?php echo $rowRemesa['codigocuenta'] ?> && fecha == "<?php echo $rowRemesa['fecharemito'] ?>" ) {
				o = document.createElement("OPTION");
				o.text = '<?php echo $rowRemesa["nroremito"]; ?>';
				o.value = <?php echo $rowRemesa["nroremito"]; ?>;
				document.forms.formularioSeleCuotas.selectRemitoSuelto.options.add(o);
			}
  <?php } ?>
	}
	document.forms.formularioSeleCuotas.selectRemitoSuelto.disabled = false;
}

function cargaRemitos(){
	//carga de remitos...
	limpiarRemitos();
	var cuenta = document.forms.formularioSeleCuotas.selectCuentaRemesa.value;
	var fecha = document.forms.formularioSeleCuotas.fecharemesa.value;
	var remesa = document.forms.formularioSeleCuotas.selectRemesa.value;
	var o;
	fecha = invertirFecha(fecha);
	o = document.createElement("OPTION");
	o.text = 'Seleccione Remesa';
	o.value = 0;
	document.forms.formularioSeleCuotas.selectRemito.options.add(o);
	<?php 
	//TODO: no se puede tomar la tabla de remitosremesasusimra porque es muy grande...
	 ?>
	document.forms.formularioSeleCuotas.selectRemito.disabled = false;
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

<body bgcolor="#B2A274">
<div align="center">
  <p><strong><a href="selecCanCuotas.php?cuit=<?php echo $cuit ?>&acuerdo=<?php echo $acuerdo ?>"><font face="Verdana" size="2"><b>VOLVER</b></font></a></strong></p>
	 <?php 	
		include($_SERVER['DOCUMENT_ROOT']."/usimra/lib/cabeceraEmpresa.php"); 
	?>
<form id="formularioSeleCuotas" name="formularioSeleCuotas" method="post" action="cancelarCuota.php?cuit=<?php echo $cuit ?>&acuerdo=<?php echo $acuerdo ?>&cuota=<?php echo $cuota ?>"  onSubmit="return validar(this)">
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
	
     <p>Fecha de Pago 
       <label>
       <input name="fechapagada" type="text" id="fechapagada" size="8">
       </label>
     </p>
     <p>Cuenta de la Boleta
       <label>
	   <?php 
	   		
	   ?>
        <select name="selectCuenta"  id="selectCuenta">
		          <option value=0 selected="selected">Seleccione una Cuenta </option>
		          <?php 
					$query="select * from cuentasusimra";
					$result=mysql_query($query,$db);
					while ($rowcuentas=mysql_fetch_array($result)) { ?>
		          <option value="<?php echo $rowcuentas['codigocuenta'] ?>"><?php echo $rowcuentas['descripcioncuenta']  ?></option>
		          <?php } ?>
       </select>
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
					while ($rowcuentas=mysql_fetch_array($result)) { ?>
		          <option value="<?php echo $rowcuentas['codigocuenta'] ?>"><?php echo $rowcuentas['descripcioncuenta']  ?></option>
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
					while ($rowcuentas=mysql_fetch_array($result)) { ?>
		          <option value="<?php echo $rowcuentas['codigocuenta'] ?>"><?php echo $rowcuentas['descripcioncuenta']  ?></option>
		          <?php } ?>
         </select></td>
       </tr>
       <tr>
         <td>
           <div align="right">Fecha de la Remesa</div></td>
         <td><label>
           <input name="fecharemesa" type="text" id="fecharemesa" size="8" disabled="disabled">
           <input name="botonRemesas" type="button" id="botonRemesas" value="Ver Remesas" disabled="disabled" onClick="cargarRemesas()">
           </label></td>
         <td>
           <div align="right">Fecha Remito Suelto</div></td>
         <td> <input name="fecharemito" type="text" id="fecharemito" size="8" disabled="disabled">
         <input name="botonRemitos" type="button" id="botonRemitos" value="Ver Remitos" disabled="disabled"  onClick="cargarRemitosSueltos()"></td>
       </tr>
       <tr>
         <td>
          <div align="right">Nro Remesa</div></td>
         <td><select name="selectRemesa" id="selectRemesa" disabled="disabled" onChange="cargaRemitos()">
         </select></td>
         <td>
           <div align="right">Nro Remito Suelto</div></td>
         <td><select name="selectRemitoSuelto" id="selectRemitoSuelto" disabled="disabled">
         </select></td>
       </tr>
       <tr>
         <td>
          <div align="right">Nro Remito</div></td>
         <td><select name="selectRemito" id="selectRemito" disabled="disabled">
         </select></td>
         <td colspan="2">&nbsp;</td>
       </tr>
    </table>
     <p>
       <label></label>
       <label></label><label>Observacion
	   <textarea name="textarea" cols="50" rows="4"></textarea>
       </label>
     </p>
     <p>
       <label>
       <input type="submit" name="Submit" value="Cancelar Cuota">
       </label>
     </p>
  </div>
</form>
<p align="center">&nbsp;</p>
</body>
</html>
