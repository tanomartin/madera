<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionOspim.php");
include($_SERVER['DOCUMENT_ROOT']."/lib/fechas.php"); 
$nroacu=$_GET['nroacu'];
$cuit=$_GET['cuit'];
$cantCuotas=$_GET['cantAgregar'];

$sqlMod = "select * from cuoacuerdosospim where cuit = $cuit and nroacuerdo = $nroacu and montopagada = 0 and boletaimpresa = 0";
$resMod = mysql_query($sqlMod,$db);
$canMod  = mysql_num_rows($resMod);

$sqlModFisca = "select * from cuoacuerdosospim where cuit = $cuit and nroacuerdo = $nroacu and tipocancelacion = 8 and boletaimpresa != 0";
$resModFisca = mysql_query($sqlModFisca,$db);
$canModFisca = mysql_num_rows($resModFisca);

$canMod = $canMod + $canModFisca;
$candCuotasTotal = $canMod + $cantCuotas;

$sqlUltima =  "select * from cuoacuerdosospim where cuit = $cuit and nroacuerdo = $nroacu order by nrocuota DESC";
$resUltima = mysql_query($sqlUltima,$db);
$rowUltima = mysql_fetch_array($resUltima);
$nroNuevaCuota = $rowUltima['nrocuota'] + 1;

$sqlMontoImpresas = "select * from cuoacuerdosospim where cuit = $cuit and nroacuerdo = $nroacu and montopagada = 0 and boletaimpresa != 0";
$resMontoImpresas = mysql_query($sqlMontoImpresas,$db);
while ($rowMontoImpresas=mysql_fetch_array($resMontoImpresas)) {
	$montoBoletasImpresas = $montoBoletasImpresas + $rowMontoImpresas['montocuota'];
}

$sqlMonto =  "select * from cabacuerdosospim where cuit = $cuit and nroacuerdo = $nroacu";
$resMonto = mysql_query($sqlMonto,$db);
$rowMonto = mysql_fetch_array($resMonto);
$montoapagar = $rowMonto['montoacuerdo'] - $rowMonto['montopagadas'] - $montoBoletasImpresas;

$sqlCuotas = "select * from cuoacuerdosospim where cuit = $cuit and nroacuerdo = $nroacu";
$resCuotas = mysql_query($sqlCuotas,$db);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>

<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">

jQuery(function($){
	for (i=0; i<=<?php echo $candCuotasTotal ?>; i++) {
		$("#fecha"+i).mask("99-99-9999");
		$("#fcheque"+i).mask("99-99-9999");
	}
});

function verInfoCheques(tipo, amostrar){
	var nroCheque = "ncheque"+amostrar;
	var banco = "bcheque"+amostrar;
	var fechaCheque = "fcheque"+amostrar;
	if (tipo == 1 || tipo == 3) {
		document.getElementById(nroCheque).style.visibility="visible";
		document.getElementById(banco).style.visibility="visible";
		document.getElementById(fechaCheque).style.visibility="visible";
	} else {
		document.getElementById(nroCheque).value = "";
		document.getElementById(banco).value = "";
		document.getElementById(fechaCheque).value = "";
		document.getElementById(nroCheque).style.visibility="hidden";
		document.getElementById(banco).style.visibility="hidden";
		document.getElementById(fechaCheque).style.visibility="hidden";
	}
}

function cartelCantidadCuotas(){
	var cantCuotasAgregar = prompt("Introduzca cantidad de cuotas","0");
	if (isNumberPositivo(cantCuotasAgregar)) {
		location.href = "modificarCuotas.php?cuit=<?php echo $cuit?>&nroacu=<?php echo $nroacu ?>&cantAgregar=" + cantCuotasAgregar 
	} else {
		alert("Debe ser un numero positivo");
		return false;
	}
}

function hayInfoCheque(id) {
	var NChe, FChe, BChe;
	NChe = document.getElementById("ncheque"+id).value;
	BChe = document.getElementById("bcheque"+id).value;
	FChe = document.getElementById("fcheque"+id).value;
	if (!esEnteroPositivo(NChe)) {
		alert("Error número de Cheque");
		document.getElementById("ncheque"+id).focus();
		return false;
	}
	if (BChe == "") {
		alert("Error en el Banco del Cheque");
		document.getElementById("bcheque"+id).focus();
		return false;
	}
	if (!esFechaValida(FChe)) {
		alert("La fecha no es valida");
		document.getElementById("fcheque"+id).focus();
		return false;
	}
	return true;
}

function desbloquear(){
	document.body.style.cursor = 'default';
	document.getElementById("nuevaCuota").disabled = false;
	document.getElementById("guardar").disabled = false;
}

function validoMontos() {
	var monto = 0;
	var cantCuotas = document.getElementById("cantCuotas").value;
	for (i=1; i<=cantCuotas; i++) {
		monto = monto + parseFloat(document.getElementById("monto"+i).value);
	}	
	monto = Math.round(monto*100)/100;
	if (monto < <?php echo $montoapagar ?>) {
		alert("La suma del monto de las cuotas en inferior al monto del acuerdo");
		document.getElementById("monto1").focus();
		return false;
	}
	return true;
}

function validarYGuardar(formulario) {
	var nombreMonto, nombreFecha, nombreTipo;
	var monto, fecha, tipoCance;
	var cantidadModif = <?php echo $canMod ?>;
	var id = cantidadModif+1;
	var nombre = "monto"+id;
	finfor = cantidadModif + <?php echo $cantCuotas ?>;
	
	document.getElementById("cantCuotas").value = finfor;
	document.getElementById("nuevaCuota").disabled = true;
	document.getElementById("guardar").disabled = true;
	document.body.style.cursor = 'wait';
	

	for (i=1; i<=finfor; i++) {
		nombreMonto = "monto"+i;
		monto = document.getElementById(nombreMonto).value;
		nombreFecha = "fecha"+i;
		fecha = document.getElementById(nombreFecha).value;
		nombreTipo = "tipo"+i;
		tipoCance = document.getElementById(nombreTipo).options[document.getElementById(nombreTipo).selectedIndex].value;
		if (!isNumberPositivo(monto)) {
			alert("Error en el Monto");
			document.getElementById(nombreMonto).focus();	
			desbloquear();
			return false;
		}
		if (!esFechaValida(fecha)){
			alert("La fecha no es valida");
			document.getElementById(nombreFecha).focus();
			desbloquear();
			return false;
		}
		if (tipoCance == -1) {
			alert("Error en el tipo de Cancelacion");
			document.getElementById(nombreTipo).focus();
			desbloquear();
			return false;
		} else {
			if (tipoCance == 1 || tipoCance == 3) {
				if(!hayInfoCheque(i)){
					desbloquear();
					return false;
				} 
			}
		}
	}
	if (validoMontos() == false) {
		desbloquear();
		return false
	} else {
		return true;
	}
}

function popUpcambio(confi) {
	document.body.style.cursor = 'default';
	if (confi == 1) {
		alert("CAMBIO GUARDADO SATISFACTORIAMENTE");
	}
}

</script>

<title>.: Carga Periodos y Cuotas :.</title>
</head>
<body bgcolor="#CCCCCC" >
<p  align="center">
<input type="reset" name="volver" value="Volver" onClick="location.href = 'formularioModif.php?cuit=<?php echo $cuit ?>&nroacu=<?php echo $nroacu?>'" align="center"/>
</p>
<p  align="center"><strong>Cuotas del Acuerdo </strong></p>
<form id="modifCuotas" name="modifCuotas" onSubmit="return validarYGuardar(this)" method="POST" action="actualizarCuotas.php?cuit=<?php echo $cuit?>&nroacu=<?php echo $nroacu?>&canMod=<?php echo $canMod ?>">
 <input name="cantCuotas" type="text" id="cantCuotas" size="4" readonly="true" style="visibility:hidden; position:absolute; z-index:1">
  <div align="center"></div>
  <div align="center">
    <table width="800" border="1">
      <tr>
        <td width="134"><div align="center">Cuota </div></td>
        <td width="107"><div align="center">Monto</div></td>
        <td width="116"><div align="center">Fecha</div></td>
        <td width="300"><div align="center">Cancelacion</div></td>
		<td width="200"><div align="center">Nro Cheque </div></td>
		<td width="212"><div align="center">Banco </div></td>
		<td width="212"><div align="center">Fecha Cheque </div></td>
  	 </tr>
  <p>
    <?php
	$contadorCuotas = 0;
	while ($rowCuotas=mysql_fetch_array($resCuotas)) {
		if (($rowCuotas['montopagada'] == 0 && $rowCuotas['boletaimpresa'] == 0 && $rowCuotas['fechapagada'] == '0000-00-00') || ($rowCuotas['tipocancelacion'] == 8 && $rowCuotas['boletaimpresa'] != 0)) {
			$contadorCuotas = $contadorCuotas + 1;	
			print ("<td width=134> <input  style='background-color:#CCCCCC' name='nroCuota".$contadorCuotas."' id='nroCuota".$contadorCuotas."' type='text' size='2' value='".$rowCuotas['nrocuota']."' readonly='raadonly'></td>");
			print ("<td width=107> <input name='monto".$contadorCuotas."' id='monto".$contadorCuotas."' type='text' size='10' value='".$rowCuotas['montocuota']."'></td>");
			print ("<td width=116> <input name='fecha".$contadorCuotas."' id='fecha".$contadorCuotas."' type='text' size='10' value='".invertirFecha($rowCuotas['fechacuota'])."'></td>");
			print ("<td width=212>"); ?>
		<select name=<?php print("tipo".$contadorCuotas);?> id=<?php print("tipo".$contadorCuotas);?> onChange="verInfoCheques(document.forms.modifCuotas.<?php echo("tipo".$contadorCuotas."[selectedIndex]");?>.value ,<?php echo $contadorCuotas ?>)">
		  <option value=-1>Seleccione un valor </option>
		  <?php
						$query="select * from tiposcancelaciones";
						$result=mysql_query($query,$db);
						while ($rowtipos=mysql_fetch_array($result)) { 
								if ($rowtipos['codigo'] == $rowCuotas['tipocancelacion']) { ?>
									<option value="<?php echo $rowtipos['codigo'] ?>" selected="selected"><?php echo $rowtipos['codigo'].' - '.$rowtipos['descripcion']  ?></option>
						  <?php } else {  ?>
									<option value="<?php echo $rowtipos['codigo'] ?>"><?php echo $rowtipos['codigo'].' - '.$rowtipos['descripcion']  ?></option>
						  <?php } ?>
					<?php } ?>
		</select>
		  <?php
			print("</td>");
			print ("<div align='center' id='infoCheques".$contadorCuotas."'>");
				print ("<td width=212> <input name=ncheque".$contadorCuotas." id=ncheque".$contadorCuotas." value='".$rowCuotas['chequenro']."' type='text' size='12' style='visibility: hidden'> </td>");
				print ("<td width=212> <input name=bcheque".$contadorCuotas." id=bcheque".$contadorCuotas." value='".$rowCuotas['chequebanco']."' type='text' size='12'  style='visibility: hidden'> </td>"); 
				print ("<td width=212> <input name=fcheque".$contadorCuotas." id=fcheque".$contadorCuotas." value='".invertirFecha($rowCuotas['chequefecha'])."'type='text' size='12' style='visibility: hidden'> </td>");
			print ("</div>");
			print ("</tr>");
			print ("<tr>");
			
			print ("<td width=134 align='center'><font face=Verdana size=1>Obs.</font></td>");
			print ("<td colspan='6'> <textarea name='obs".$contadorCuotas."' id='obs".$contadorCuotas."' cols='93' rows='2' >".$rowCuotas['observaciones']."</textarea> </td>");
			print ("</tr>"); ?>
		<script type="text/javascript">
				verInfoCheques(document.getElementById("<?php echo("tipo".$contadorCuotas)?>").value ,<?php echo $contadorCuotas ?>);
		</script>	
	  <?php 
			} 
		} 
		if ($cantCuotas != 0) {	
			for ( $i = 1 ; $i <= $cantCuotas ; $i ++) {
				$contadorCuotas = $contadorCuotas + 1;
			print ("<td width=134> <input  style='background-color:#CCCCCC' name='nroCuota".$contadorCuotas."' id='nroCuota".$contadorCuotas."' type='text' size='2' value='".$nroNuevaCuota."' readonly='raadonly'></td>");
				print ("<td width=107> <input name='monto".$contadorCuotas."' id='monto".$contadorCuotas."' type='text' size='10'></td>");
				print ("<td width=116> <input name='fecha".$contadorCuotas."' id='fecha".$contadorCuotas."' type='text' size='10'></td>");
				print ("<td width=212>");  ?>
				<select name=<?php print("tipo".$contadorCuotas);?> id=<?php print("tipo".$contadorCuotas); ?> onChange="verInfoCheques(document.forms.modifCuotas.<?php echo("tipo".$contadorCuotas."[selectedIndex]");?>.value ,<?php echo $contadorCuotas ?>)">
			  <option value=0>Seleccione un valor </option>
			  <?php
							$query="select * from tiposcancelaciones";
							$result=mysql_query($query,$db);
							while ($rowtipos=mysql_fetch_array($result)) { 
									if ($rowtipos['codigo'] == $rowCuotas['tipocancelacion']) { ?>
										<option value="<?php echo $rowtipos['codigo'] ?>" selected="selected"><?php echo $rowtipos['codigo'].' - '.$rowtipos['descripcion']  ?></option>
							  <?php } else {  ?>
										<option value="<?php echo $rowtipos['codigo'] ?>"><?php echo $rowtipos['codigo'].' - '.$rowtipos['descripcion']  ?></option>
							  <?php } ?>
						<?php } ?>
			</select>
				 <?php  print("</td>"); 
						print ("<td width=212> <input name=ncheque".$contadorCuotas." id=ncheque".$contadorCuotas." type='text' size='12' style='visibility: hidden'> </td>");
						print ("<td width=212> <input name=bcheque".$contadorCuotas." id=bcheque".$contadorCuotas." type='text' size='12'  style='visibility: hidden'> </td>"); 
						print ("<td width=212> <input name=fcheque".$contadorCuotas." id=fcheque".$contadorCuotas."  type='text' size='12' style='visibility: hidden'> </td>");
						print ("</tr>");
						print ("<tr>");
						
						print ("<td width=134 id='titobs".$contadorCuotas."' align='center'><font face=Verdana size=1>Obs.</font></td>");
						print ("<td colspan='6'> <textarea name='obs".$contadorCuotas."' id='obs".$contadorCuotas."' cols='93' rows='2' ></textarea> </td>");
						print ("</tr>"); 
						$nroNuevaCuota = $nroNuevaCuota+1;
						}
			}?>	
    </table>
  </div>
  </p>
  <div align="center">
    <table width="739" border="0">
      <tr>
        <td width="365">
          <div align="left">
            <input type="button" id="nuevaCuota" name="nuevaCuota" value="Agregar Cuotas" onClick="cartelCantidadCuotas()">
            </div>
        <div align="right"></div></td>
        <td width="364">
          <div align="right">
            <input type="submit" name="guardar" id="guardar" value="Guardar Cambios" sub />
          </div></td>
      </tr>
    </table>
  </div>
  <p>&nbsp;</p>
  <p align="center">
    <label></label>
  </p>
</form>
</body>
</html>

