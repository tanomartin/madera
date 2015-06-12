<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");

$cuit=$_GET['cuit'];
if ($cuit=="") {
	$cuit=$_POST['cuit'];
}

$base = $_SESSION['dbname'];
$sqlBuscaNro = "SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = '$base' AND TABLE_NAME = 'cabjuiciosusimra'";
//echo $sqlBuscaNro; echo "<br>";
$resBuscaNro = mysql_query($sqlBuscaNro,$db);
$rowBuscaNro = mysql_fetch_array($resBuscaNro);

//VEO LOS ACUERDOS QUE PUEDEN SER ABSORIVIDOS
$today = date('Y-m-j');
$fechaVto = strtotime ('-6 month' , strtotime ($today)) ;
$fechaVto = date( 'Y-m-j' , $fechaVto );								
$sqlAcuerdos = "select c.nroacuerdo, t.descripcion, c.nroacta ,o.fechacuota from cabacuerdosusimra c, cuoacuerdosusimra o, tiposdeacuerdos t where c.cuit = $cuit and c.estadoacuerdo = 1 and c.cuit = o.cuit and c.nroacuerdo = o.nroacuerdo and o.montopagada = 0 and c.tipoacuerdo = t.codigo group by c.nroacuerdo order by c.nroacuerdo ASC, o.fechacuota DESC";
$resAcuerdos = mysql_query($sqlAcuerdos,$db); 
$i=0;
while($rowAcuerdos = mysql_fetch_assoc($resAcuerdos)) {
	$fechacuota = $rowAcuerdos['fechacuota'];
	if ($fechacuota < $fechaVto) { 
		$acuAbs[$i] = array('nroacu' => $rowAcuerdos['nroacuerdo'], 'tipo' => $rowAcuerdos['descripcion'], 'acta' => $rowAcuerdos['nroacta']);
		$i++;
	}
}

$sqlJuris = "select codidelega from jurisdiccion where cuit = $cuit";
$resJuris = mysql_query($sqlJuris,$db);
$sqlAsesor ="select * from asesoreslegales where codidelega in (";
while ($rowJuris = mysql_fetch_assoc($resJuris)) {
	$sqlAsesor = $sqlAsesor.$rowJuris['codidelega'].",";
}
$sqlAsesor = substr($sqlAsesor,0, -1);
$sqlAsesor = $sqlAsesor.")";
$resJuris = mysql_query($sqlJuris,$db);
$sqlInsp = "select * from inspectores where codidelega in (";
while ($rowJuris = mysql_fetch_assoc($resJuris)) {
	$sqlInsp = $sqlInsp.$rowJuris['codidelega'].",";
}
$sqlInsp = substr($sqlInsp,0, -1);
$sqlInsp = $sqlInsp.")";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

jQuery(function($){
	for (i=0; i<= 120; i++) {
		$("#mes"+i).mask("99");
		$("#anio"+i).mask("9999");
	}
	$("#fechaexp").mask("99-99-9999");
});

function cargarPeriodosAbsorvidos(acuerdo) {
	formatoPeriodoInicio();
	var n = 0;
<?php  	$sqlPeriodos = "select * from detacuerdosusimra where cuit = $cuit";
		$resPeriodos = mysql_query($sqlPeriodos,$db); 
		while ($rowPeriodos = mysql_fetch_array($resPeriodos)) { ?> 
			if(acuerdo == <?php echo $rowPeriodos['nroacuerdo'] ?>) {
				i = "id" + n;
				m = "mes" + n;
				a = "anio" + n;
				c = "concepto" + n;
				mes = <?php echo $rowPeriodos['mesacuerdo'] ?>;
				if (mes < 10) {
					mes = "0"+mes;
				}
				document.getElementById(i).value="<?php echo $rowPeriodos['idperiodo'] ?>";
				document.getElementById(m).value= mes;
				document.getElementById(a).value="<?php echo $rowPeriodos['anoacuerdo'] ?>";
				document.getElementById(c).value="<?php echo $rowPeriodos['conceptodeuda'] ?>";
				n++;
				mostrando = document.forms.nuevoJuicio.mostrar.value;
				if (n > mostrando && mostrando < 120) {
					mostrando = mostrando + 12;
					mostrarPeriodos();
				}
			}
<?php 	}?>
}

function limpiarAcuerdos() {
	formatoPeriodoInicio();
	var limite = <?php echo sizeof($acuAbs) ?>;
	if (limite == 1) {
		document.forms.nuevoJuicio.nroacu.checked = false
	} else {
		for (i=0; i < limite; i++) {
			document.forms.nuevoJuicio.nroacu[i].checked = false;
		}
	}
}

function mostrarAcuerdos() {
	limpiarAcuerdos();
	if (document.forms.nuevoJuicio.acuabs[0].checked) {
		document.getElementById("acuerdos").style.visibility="hidden";
	} else {
		document.getElementById("acuerdos").style.visibility="visible";
	}
}

function formatoPeriodoInicio() {
	for(i=0; i<12; i++) {
		id = "id" + i;
		m = "mes" + i;
		a = "anio" + i;
		con = "concepto" + i;
		document.getElementById(id).value="";
		document.getElementById(m).value="";
		document.getElementById(a).value="";
		document.getElementById(con).value="";
	}
	for (i=12; i<120; i++){
		id = "id" + i;
		m = "mes" + i;
		a = "anio" + i;
		con = "concepto" + i;
		document.getElementById(id).value="";
		document.getElementById(m).value="";
		document.getElementById(a).value="";
		document.getElementById(con).value="";
		document.getElementById(m).style.visibility="hidden";
		document.getElementById(a).style.visibility="hidden";
	}
	document.forms.nuevoJuicio.mostrar.value = 12;
}

function mostrarBotones() {
	if (document.forms.nuevoJuicio.tramite[0].checked) {
		document.getElementById("bguardar").style.visibility="visible";
		document.getElementById("btramite").style.visibility="hidden";
	} else {
		document.getElementById("bguardar").style.visibility="hidden";
		document.getElementById("btramite").style.visibility="visible";
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

function limpioid(id) {
	idper = "id" + id;
	idcon = "concepto" + id;
	document.getElementById(idper).value="";
	document.getElementById(idcon).value="";
	mesnombre = "mes" + id;
	anionombre = "anio" + id;
	mes = document.getElementById(mesnombre).value;
	anio = document.getElementById(anionombre).value;
	
	var n = parseInt(document.forms.nuevoJuicio.mostrar.value);
	for (i=0; i<n; i++){
		if (i != id) {
			mescom = "mes" + i;
			aniocom = "anio" + i;
			mescomp = document.getElementById(mescom).value;
			aniocom = document.getElementById(aniocom).value;
			if (mescomp != '' && aniocom != '') {
				if (anio == aniocom && mes == mescomp) {
					alert("Este periódo ya se encuentra en la lista");
					document.getElementById(mesnombre).value = "";
					document.getElementById(anionombre).value = "";;
					document.getElementById(mesnombre).focus();
				}
			}
		}	
	}
}

function mostrarPeriodos() {
	var n = parseInt(document.forms.nuevoJuicio.mostrar.value);
	if (n < 120) {
		var o = 0;
		var m = 0;
		var a = 0;
		for (i=0; i<=12; i++){
			o = parseInt(document.forms.nuevoJuicio.mostrar.value) + i;
			m = "mes" + o;
			a = "anio" + o;
			document.getElementById(m).style.visibility="visible";
			document.getElementById(a).style.visibility="visible";
		}
		document.forms.nuevoJuicio.mostrar.value = n + 12;
	} else { 
		alert("No se pueden superar los 120 períodos");
	}
}

function validar(formulario) {
	if(!esEnteroPositivo(formulario.nrocert.value) || formulario.nrocert.value == "" || formulario.nrocert.value == 0) {
		alert("Error en el Nro. de Certificado");
		return false;
	}
	if (formulario.status.value == 0) {
		alert("Debe elegir el estado del Juicio");
		return false;
	}
	if (!esFechaValida(formulario.fechaexp.value)) {
		alert("Fecha de Expedición invalida");
		return false;
	}
	if(!isNumberPositivo(formulario.deudaHistorica.value) || formulario.deudaHistorica.value == "") {
		alert("La dueda histórica debe ser un número postivo");
		return false;
	}
	if(!isNumberPositivo(formulario.intereses.value) || formulario.intereses.value == "") {
		alert("Los intereses deben ser un número postivo");
		return false;
	}
	if(!isNumberPositivo(formulario.deudaActual.value) || formulario.deudaActual.value == "") {
		alert("La dueda actual debe ser un número postivo");
		return false;
	}
	if (formulario.asesor.value == 0) {
		alert("Debe elegir un Asesor Legal");
		return false;
	}
	if (formulario.inspector.value == 0) {
		alert("Debe elegir un Inspector");
		return false;
	}
	
	if (formulario.ejecutor.value == "") {
		alert("Debe elegir un Ejecutor");
		return false;
	}
	
	var limite = <?php echo sizeof($acuAbs) ?>;		
	if (limite != 0) {
		if (formulario.acuabs[1].checked) {
			if (limite == 1) {
				if(!document.forms.nuevoJuicio.nroacu.checked) {
					alert("Debe elegir un acuerdo a absorber");
					return false;
				}
			} else {
				var algunCheck = false;
				for (i=0; i < limite; i++) {
					if(document.forms.nuevoJuicio.nroacu[i].checked) {
						algunCheck = true;
					}
				}
				if (!algunCheck) {
					alert("Debe elegir un acuerdo a absorber");
					return false;
				}
			}
		}
	} 
	$.blockUI({ message: "<h1>Preparando datos del juicio... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
	formulario.submit();
}

</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Nuevo Juicio :.</title>
</head>
<body bgcolor="#B2A274" >
<form id="nuevoJuicio" name="nuevoJuicio" method="POST" action="preparoDatosJuicio.php" >
  <div align="center">
    <input name="nrcuit" type="text" id="nrcuit" readonly="readonly" size="4" style="visibility:hidden; position:absolute; z-index:1" value="<?php echo $cuit ?>">
    <input type="reset" name="volver" value="Volver" onClick="location.href = 'juicios.php?cuit=<?php echo $cuit?>'"/>
    <?php 	
		include($libPath."cabeceraEmpresaConsulta.php"); 
		include($libPath."cabeceraEmpresa.php"); 
	?>
  </div>
  <p align="center"><strong>M&oacute;dulo de Carga - Nuevo Juicio </strong></p>
   	<p align="center"><strong>NRO ORDEN </strong>
      <input name="nroorden" type="text" id="nroorden" size="5" readonly="readonly" value="<?php echo $rowBuscaNro['AUTO_INCREMENT'] ?>" style="background-color:#CCCCCC; text-align:center">
</p>
   	<div align="center">
   	<table width="1000" border="0" style="text-align:left">
      <tr>
        <td width="85">Nro. Certificado</td>
        <td width="144"><input id="nrocert" type="text" name="nrocert"/></td>
        <td width="70">Status Deuda</td>
        <td width="156"><label>
          <select name="status" id="status">
            <option value="0" selected>Seleccione Status</option>
            <option value="1">EJECUCION</option>
            <option value="2">CONVOCATORIA</option>
            <option value="3">QUIEBRA</option>
          </select>
          </label>        </td>
        <td width="125">Fecha Expedición</td>
        <td width="241"><input id="fechaexp" type="text" name="fechaexp" size="12"/></td>
      </tr>
      <tr>
        <td>Deuda Histórica</td>
        <td><input id="deudaHistorica" type="text" name="deudaHistorica"/></td>
        <td>Intereses</td>
        <td><input name="intereses" type="text" id="intereses"/></td>
        <td>Deuda Actualizada</td>
        <td><input id="deudaActual" type="text" name="deudaActual"/></td>
      </tr>
      <tr>
        <td>Asesor Legal</td>
        <td><select name="asesor" id="asesor">
            <option value=0 selected>Seleccione Asesor</option>
            <?php 
					$resAsesor = mysql_query($sqlAsesor,$db);
					while ($rowAsesor=mysql_fetch_assoc($resAsesor)) { ?>
            <option value="<?php echo $rowAsesor['codigo'] ?>"><?php echo $rowAsesor['apeynombre'] ?></option>
            <?php } ?>
          </select>        </td>
        <td>Inspector</td>
        <td><select name="inspector" id="inspector">
            <option value=0 selected>Seleccione Inspector</option>
            <?php 			
				$resInspe = mysql_query($sqlInsp,$db);
				while ($rowInspe=mysql_fetch_assoc($resInspe)) { ?>
            <option value="<?php echo $rowInspe['codigo'] ?>"><?php echo $rowInspe['apeynombre'] ?></option>
			
            <?php }?>
          </select>      
		   </td>
		    <td>Ejecutor</td>
			<td><input id="ejecutor" type="text" name="ejecutor"/></td>
      </tr>
      
      <tr>
	  		 
     <?php 
	 if (sizeof($acuAbs) > 0) { ?>
        <td height="35" colspan="6"><div align="center"><strong>Acuerdos a Absorver </strong>[
            <label>
                <input name="acuabs" type="radio" value="0" checked="checked" onchange="mostrarAcuerdos()"/>
            NO - </label>
                <label>
                <input name="acuabs" type="radio" value="1" onchange="mostrarAcuerdos()"/>
                  SI</label>
          ]</div></td>
      </tr>
      <tr>
        <td colspan="6">
		<div align="center" id="acuerdos" style="visibility:hidden">
            <table>
              <?php 
			  foreach($acuAbs as $acuerdo) { ?>
              <tr>
                <td><input name="nroacu" type="radio" value="<?php echo $acuerdo['nroacu']?>" onclick="cargarPeriodosAbsorvidos(this.value)"/></td>
                <td align="left"><?php echo $acuerdo['nroacu']." - ".$acuerdo['tipo']. " - Acta: ".$acuerdo['acta']?></td>
                <td></td>
              </tr>
		<?php } ?>
            </table>
        </div>		</td>
  <?php } else { ?>
        <td width="149" colspan="6"><div align="center"><strong>Acuerdos a Absorver</strong> [
            <label>
                <input name="acuabs" type="radio" value="0" checked="checked"/>
            NO ]</label>
        </div></td>
      </tr>
      <tr>
        <td colspan="6"><div align="center"><b>No hay acuerdos posibles</b><input name="nroacu" type="radio" value="0" checked="checked" style="visibility:hidden"/> </div></td>
        <?php } ?>
      </tr>
    </table>
   	<table width="1001" border="0">
        <tr>
          <td height="43" colspan="4"><div align="center">
            <div align="center">
              <p><strong>PER&Iacute;ODOS DEL JUICIO</strong></p>
            </div>
            <input name="mostrar" type="text" id="mostrar" size="1" value="12" readonly="readonly" style="visibility:hidden"/>
            <input name="masPeridos" type="button" id="masPeridos" value="Mas Periodos"  onclick="mostrarPeriodos()"/>
          </div></td>
		  <td width="551" colspan="4"><div align="center">
		    <p><strong>TRAMITE JUDICIAL</strong> [
		        <input name="tramite" type="radio" value="0" checked="checked" onchange="mostrarBotones()"/>
		    NO -
  				<input name="tramite" type="radio" value="1" onchange="mostrarBotones()"/>
	      SI ]	      </p>
		    <p>
		      <input name="btramite" type="button" id="btramite" value="Cargar Tramite Judicial" style="visibility:hidden" onclick="validar(document.forms.nuevoJuicio)"/>
	        </p>
		  </div></td>
        </tr>
        <tr>
          <td width="138"></td>
          <td width="80" align="center">Mes</td>
          <td width="70" align="center">A&ntilde;o</td>
          <td width="140"></td>
		  <td colspan="4"><div align="center">
            <input name="bguardar" type="button" id="bguardar" value="Guardar Juicio" onclick="validar(document.forms.nuevoJuicio)"/>
		  </div></td>
        </tr>
        <?php
				for ($i = 0 ; $i < 120; $i ++) {
					if ($i < 12) {
					print("<tr>");
					print("<td><div align='center'><input name='id".$i."' type='text' id='id".$i."' size='2' style='visibility:hidden'/></td>");
					print("<td><div align='center'><input name='mes".$i."' type='text' id='mes".$i."' size='2' onfocusout='validoMes(".$i.")' onchange='limpioid(".$i.")'/></div></td>");
					print("<td><div align='center'><input name='anio".$i."' type='text' id='anio".$i."' size='4' onfocusout='validoAnio(".$i.")' onchange='limpioid(".$i.")'/></div></td>");
					 print("<td><div align='center'><input name='concepto".$i."' type='text' id='concepto".$i."' size='2' style='visibility:hidden'/></td>");
					 } else {
						print("<td><div align='center'><input name='id".$i."' type='text' id='id".$i."' size='2' style='visibility:hidden' /></td>");			 
						print("<td><div align='center'><input name='mes".$i."' id='mes".$i."' type='text' size='2' style='visibility:hidden' onfocusout='validoMes(".$i.")' onchange='limpioid(".$i.")'/></div></td>");
						print("<td><div align='center'><input name='anio".$i."' id='anio".$i."' type='text'  size='4' style='visibility:hidden' onfocusout='validoAnio(".$i.")' onchange='limpioid(".$i.")'/></div></td>");
						print("<td><div align='center'><input name='concepto".$i."' type='text' id='concepto".$i."' size='2' style='visibility:hidden'/></td>");		 
					 }			
					 print("</tr>");
				}
				?>
      </table>
  </div>
</form>
</body>
</html>
