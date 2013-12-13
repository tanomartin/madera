<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionUsimra.php");

$cuit=$_GET['cuit'];
if ($cuit=="") {
	$cuit=$_POST['cuit'];
}

$sql = "select * from empresas where cuit = $cuit";
$result = mysql_query($sql,$db); 
$row = mysql_fetch_array($result); 

$sqllocalidad = "select * from localidades where codlocali = $row[codlocali]";
$resultlocalidad = mysql_query($sqllocalidad,$db); 
$rowlocalidad = mysql_fetch_array($resultlocalidad); 

$sqlprovi =  "select * from provincia where codprovin = $row[codprovin]";
$resultprovi = mysql_query($sqlprovi,$db); 
$rowprovi = mysql_fetch_array($resultprovi);

$sqlacu =  "select * from cabacuerdosusimra where cuit = $cuit order by nroacuerdo DESC";
$resulacu= mysql_query($sqlacu,$db); 
$cant = mysql_num_rows($resulacu); 
if ($cant == 0) {
	$nacuNuevo = 1;
} else {
	$rowacu = mysql_fetch_array($resulacu);
	$nacuNuevo = $rowacu['nroacuerdo'] + 1;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>
<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/lib/funcionControl.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
jQuery(function($){
	$("#fechaAcuerdo").mask("99-99-9999");
	for (i=0; i<= 120; i++) {
		$("#mes"+i).mask("99");
		$("#anio"+i).mask("9999");
	}
});

function mostrarPeriodos() {
	if (parseInt(document.forms.nuevoAcuerdo.mostrar.value) < 120) 
	{
		var n = parseInt(document.forms.nuevoAcuerdo.mostrar.value);
		document.forms.nuevoAcuerdo.mostrar.value = n;
		var o = 0;
		var m = 0;
		var a = 0;
		var s = 0;
		for (i=0; i<=12; i++){
			o = parseInt(document.forms.nuevoAcuerdo.mostrar.value) + i;
			m = "mes" + o;
			a = "anio" + o;
			s = "conDeuda" + o;
			document.getElementById(m).style.visibility="visible";
			document.getElementById(a).style.visibility="visible";
			document.getElementById(s).style.visibility="visible";
		}
		document.forms.nuevoAcuerdo.mostrar.value = n + 12;
	} else { 
		alert("No se pueden superar los 120 períodos");
	}
}

function habilitarCarga() {
	var control = parseInt(document.forms.nuevoAcuerdo.cantCuotas.value);
	if (control >= 0 && !isNaN(control)) {
		document.getElementById("guardar").disabled = false;
	} else {
		document.getElementById("guardar").disabled = true;
	}
}

function cargarPor(){
	<?php 
		$sqlPor = "select * from parametros where id = 1";
		$resPor= mysql_query($sqlPor,$db); 
		$rowPor = mysql_fetch_array($resPor);
	?>
	if (document.forms.nuevoAcuerdo.gasAdmi[1].checked) {
		document.forms.nuevoAcuerdo.porcentaje.value = "<?php echo $rowPor['valorgastoadmin']?>";
	} else {
		document.forms.nuevoAcuerdo.porcentaje.value ="";
	}
}

function validoMes(id) {
	var errorMes = "Error en la carga del mes";
	nombreMes = "mes" + id;
	valorMes = document.getElementById(nombreMes).value;
	if (valorMes < 0 || valorMes > 12) {
		alert(errorMes);
		document.getElementById(nombreMes).focus();
		return false;
	} 
}

function validoAnio(id){
	var errorAnio = "Error en la carga del año";
	nombreAnio = "anio" + id;
	valorAnio = document.getElementById(nombreAnio).value;
	if (valorAnio < 0) {
		alert(errorAnio);
		document.getElementById(nombreAnio).focus();
		return false;
	}
}

function validar(formulario) {
	if (!isNumberPositivo(formulario.nroacu.value)) {
		alert("Error en el numero de acuerdo");
		document.getElementById("nroacu").focus();
		return(false);
	}
	if (formulario.tipoAcuerdo.options[formulario.tipoAcuerdo.selectedIndex].value == 0) {
		alert("Error en el tipo de acuerdo");
		document.getElementById("tipoAcuerdo").focus();
		return(false);
	}
	if (!esFechaValida(formulario.fechaAcuerdo.value)) {
		alert("La fecha no es valida");
		document.getElementById("fechaAcuerdo").focus();
		return(false);
	} 
	if (!esEnteroPositivo(formulario.numeroActa.value)) {
			alert("Error Número de Acta");
			document.getElementById("numeroActa").focus();
			return(false);
	}
	if (!isNumberPositivo(formulario.monto.value)){
		alert("Error en el monto");
		document.getElementById("monto").focus();
		return(false);
	}
	
	var totalPeriodos = parseInt(formulario.mostrar.value) + 12;
	var errorMes = "Error en la carga del mes";
	var errorAnio = "Error en la carga del año";
	for (i=0; i<=totalPeriodos; i++) {
		nombreMes = "mes" + i;
		nombreAnio = "anio" + i;
		valorMes = document.getElementById(nombreMes).value;
		valorAnio = document.getElementById(nombreAnio).value;
		if (valorMes == 0 && valorAnio != 0) {
			alert(errorMes);
			document.getElementById(nombreMes).focus();
			return (false);
		}
		if (valorMes != 0 && valorAnio == 0 ) {
			alert(errorAnio);
			document.getElementById(nombreAnio).focus();
			return (false);
		}
		if (valorAnio < 1000 && valorMes!= 0) {
			alert(errorAnio);
			document.getElementById(nombreAnio).focus();
			return (false);
		}
	}
	
}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Carga de Acuerdos :.</title>
</head>
<body bgcolor="#B2A274" >
<form id="nuevoAcuerdo" name="nuevoAcuerdo" method="POST" action="cargarCuotas.php"  onSubmit="return validar(this)" style="visibility:visible" >
  <input name="nrcuit" type="text" id="nrcuit" size="4" readonly="true" style="visibility:hidden; position:absolute; z-index:1" value="<?php echo $cuit ?>">
   <div align="center">
   <input type="reset" name="volver" value="Volver" onClick="location.href = 'acuerdos.php?cuit=<?php echo $cuit?>'" align="center"/> 
  </div>
   <?php 	
		include($libPath."cabeceraEmpresa.php"); 
	?>
  <p align="center"><strong>M&oacute;dulo de Carga - Acuerdos Nuevos </strong></p>
   	<p align="center"><strong>ACUERDO NUMERO</strong>
      <input name="nroacu" type="text" id="nroacu" size="4" readonly="true" value="<?php echo $nacuNuevo ?>">
</p>
   	<div align="center">
   	  <table width="954" border="0">
        <tr>
          <td width="111" valign="bottom"><div align="left">Tipo de Acuerdo</div></td>
          <td width="240" valign="bottom">
		    
	        <div align="left">
		        <select name="tipoAcuerdo" size="1" id="tipoAcuerdo">
		          <option value=0 selected="selected">Seleccione un valor </option>
		          <?php 
					$query="select * from tiposdeacuerdos";
					$result=mysql_query($query,$db);
					while ($rowtipos=mysql_fetch_array($result)) { ?>
		          <option value="<?php echo $rowtipos['codigo'] ?>"><?php echo $rowtipos['descripcion']  ?></option>
		          <?php } ?>
	          </select>
            </div></td>
          <td width="106" valign="bottom"><div align="left">Fecha Acuerdo</div></td>
          <td width="144" valign="bottom">
            <div align="left">
              <input id="fechaAcuerdo" type="text" name="fechaAcuerdo"/>
            </div></td>
          <td width="158" valign="bottom"><div align="left">N&uacute;mero de Acta</div></td>
          <td colspan="2" valign="bottom">
            <div align="left">
              <input id="numeroActa" type="text" name="numeroActa"/>
            </div></td>
        </tr>
        <tr>
          <td valign="bottom"><div align="left">Gestor</div></td>
          <td valign="bottom">
            <div align="left">
              <select name="gestor" id="gestor" >
                <?php 
					$sqlGestor="select * from gestoresdeacuerdos";
					$resGestor=mysql_query($sqlGestor,$db);
					while ($rowGestor=mysql_fetch_array($resGestor)) { ?>
                  <option value="<?php echo $rowGestor['codigo'] ?>"><?php echo $rowGestor['apeynombre'] ?></option>
                <?php } ?>
              </select>
            </div></td>
          <td valign="bottom"><div align="left">Inpector</div></td>
          <td valign="bottom">
		    
	        <div align="left">
		        <select name="inpector" id="inspector" >
		          <option value=0>No Especificado </option>
	              <?php 
					$sqlInspec="select codigo, apeynombre from inspectores i, jurisdiccion j where j.cuit = $cuit and j.codidelega = i.codidelega";
					$resInspec=mysql_query($sqlInspec,$db);
					while ($rowInspec=mysql_fetch_array($resInspec)) { ?>
		           		<option value="<?php echo $rowInspec['codigo'] ?>"><?php echo $rowInspec['apeynombre'] ?></option>
	              <?php } ?>
	          </select>
            </div></td>
          <td valign="bottom"><div align="left">Requerimiento de Origen</div></td>
		  <td colspan="2" valign="bottom">
		    <div align="left">
		      <select name="requerimiento" id="requerimiento">
		        <option value=0>Seleccione un valor </option>
	            <?php 
				$sqlNroReq = "select * from reqfiscalizusimra where cuit = ".$cuit;
				$resNroReq = mysql_query($sqlNroReq,$db);
				while ($rowNroReq=mysql_fetch_array($resNroReq)) { ?>
		           <option value="<?php echo $rowNroReq['nrorequerimiento'] ?>"><?php echo $rowNroReq['nrorequerimiento'] ?></option>
	            <?php } ?>
	          </select>
            </div></td>
        </tr>
        <tr>
          <td valign="bottom"><label>
            <div align="left">Liquidacion Origen </div>
          </label></td>
          <td valign="bottom"><label>
          
            <div align="left">
              <input name="nombreArcReq" type="text" id="nombreArcReq" size="40" readonly="readonly" />
            </div></td>
          <td valign="bottom"><div align="left">Monto Acuerdo </div></td>
          <td valign="bottom">
            <div align="left">
              <input id="monto" type="text" name="monto"/>
            </div></td>
          <td valign="bottom"><div align="left">Gastos Administrativos </div></td>
          <td width="49" valign="bottom"><label>
          	<div align="left">
          	  <input name="gasAdmi" type="radio" value=0 checked onfocusout="cargarPor()"/>
          	NO<br />
          		<input name="gasAdmi" type="radio" value=1 onfocusout="cargarPor()"/>
          	SI            </div>
          </label></td>
          <td width="100" valign="bottom">
            <div align="left">
            <input name="porcentaje" type="text" id="porcentaje" size="5" readonly="readonly"/>
          %</div></td>
        </tr>
        <tr>
          <td height="87" valign="bottom"> <div align="left">Obervaciones </div></td>
          <td colspan="6" valign="bottom"><p align="left">
              <textarea name="observaciones" cols="110" rows="5" id="observaciones"></textarea>
          </p></td>
        </tr>
      </table>
   	</div>
  <div align="center">
    <p><b>Carga Períodos y Cuotas </b> </p>
    <p>Cantidad de Cuotas
      <input  name="cantCuotas" type="text" id="cantCuotas" size="4" onfocusout="habilitarCarga()"/>
    </p>
    <p>
      <input type="submit" name="guardar" id="guardar" value="Cargar Cuotas" disabled="disabled" sub />
    </p>
    <table width="446" border="0">
      <tr>
        <td width="440"><div align="center">
          <input name="masPeridos" type="button" id="masPeridos" value="Mas Periodos"  onclick="mostrarPeriodos()"/>
        </div></td>
      </tr>
    </table>
    <table width="531" height="32" border="0">
       
        <tr>
          <td width="134" height="11"> <div align="center">Mes</div></td>
          <td width="121"><div align="center">A&ntilde;o</div></td>
          <td width="262"><div align="center">Concepto de deuda </div></td>
        </tr>
       
	    <tr>
			<input  name="mostrar" type="text" id="mostrar" size="4" value="12" readonly="readonly" style="visibility:hidden"/>
            <?php
            for ($i = 0 ; $i <= 120; $i ++) {
				if ($i < 12) {
				print("<td height='11'><div align='center'><input name='mes".$i."' type='text' id='mes".$i."' size='2' onfocusout='validoMes(".$i.")'/></div></td>");
           		print("<td height='11'><div align='center'><input name='anio".$i."' type='text' id='anio".$i."' size='4' onfocusout='validoAnio(".$i.")' /></div></td>");
            	
				//TODO: hacerlo dinamico pegandole a la base..
				print("<td height='11'><div align='center'><select id='conDeuda".$i."' name='conDeuda".$i."'>
              			<option selected value='A'>Período no Pagado</option>
						<option value='B'>Pagado Fuera de Término</option>
						<option value='C'>Aporte y Contribución 3.1%</option>
						<option value='D'>Aporte 1.5%</option>
						<option value='E'>Contribución 1.6%</option>
						<option value='F'>No Remunerativo</option>
						<option value='G'>Contribución 0.6%</option>
						<option value='H'>Aporte y Contribución 2.5%</option>
            	   </select> </div></td>");
				 print("</tr>");
				 } else {
				 	print("<td height='11'><div align='center'><input name='mes".$i."' id='mes".$i."' type='text' size='2' style='visibility:hidden'  onfocusout='validoMes(".$i.")'/></div></td>");
					print("<td height='11'><div align='center'><input name='anio".$i."' id='anio".$i."' type='text'  size='4' style='visibility:hidden' onfocusout='validoAnio(".$i.")'/></div></td>");
					print("<td height='11'><div align='center'>
					<select id='conDeuda".$i."' name='conDeuda".$i."' style='visibility:hidden'>
						<option selected value='A'>Período no Pagado</option>
						<option value='B'>Pagado Fuera de Término</option>
						<option value='C'>Aporte y Contribución 3.1%</option>
						<option value='D'>Aporte 1.5%</option>
						<option value='E'>Contribución 1.6%</option>
						<option value='F'>No Remunerativo</option>
						<option value='G'>Contribución 0.6%</option>
						<option value='H'>Aporte y Contribución 2.5%</option>
            	    </select> </div></td>");
				 	 print("</tr>");
					//FIN TODO
				 }
			}
			?>
    </table>
  </div>
   	<p align="center">&nbsp;</p>
</form>
<div>

</body>
</html>
