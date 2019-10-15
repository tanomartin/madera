<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");

if (isset($_GET['cuit'])) {
	$cuit=$_GET['cuit'];
} else {
	$cuit=$_POST['cuit'];
}

include($libPath."cabeceraEmpresaConsulta.php");
$base = $_SESSION['dbname'];
$sqlBuscaNro = "SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = '$base' AND TABLE_NAME = 'cabjuiciosospim'";
//echo $sqlBuscaNro; echo "<br>";
$resBuscaNro = mysql_query($sqlBuscaNro,$db);
$rowBuscaNro = mysql_fetch_array($resBuscaNro);

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
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

jQuery(function($){
	for (var i=0; i<= 120; i++) {
		$("#mes"+i).mask("99");
		$("#anio"+i).mask("9999");
	}
	$("#fechaexp").mask("99-99-9999");

	$("#status").change(function(){
		var status = $(this).val();
		limpiarAcuerdos();
		$("#cartelAcuerdos").show()
		$("#datosAcuerdos").html("<input type='text' id='cantAcuerdos' name='cantAcuerdos' value='0' style='display: none'/>");
		$("#datosAcuerdos").hide();
		if (status != 0) {	
			$("#cartelAcuerdos").hide();
			$("#datosAcuerdos").show()
			$.ajax({
				type: "POST",
				dataType: "html",
				url: "buscarAcuerdos.php",
				data: {status:status, nrcuit: $(nrcuit).val()},
			}).done(function(respuesta){
				 $("#datosAcuerdos").html(respuesta);
			}); 
		}
	});
});

function cargarPeriodosAbsorvidos(acuerdo) {
	formatoPeriodoInicio();
	var n = 0;
	$.ajax({
		type: "POST",
		dataType: "json",
		url: "buscarPeriodos.php",
		data: {acuerdo:acuerdo, nrcuit: $(nrcuit).val()},
	}).done(function(respuesta) {
		$.each(respuesta, function (index, datos) {
			i = "id" + n;
			m = "mes" + n;
			a = "anio" + n;
			c = "concepto" + n;
			mes = datos['mesacuerdo'];
			if (mes < 10) {
				mes = "0"+mes;
			}
			document.getElementById(i).value= datos['idperiodo'];
			document.getElementById(m).value= mes;
			document.getElementById(a).value= datos['anoacuerdo'];
			document.getElementById(c).value= datos['conceptodeuda'];
			n++;
			mostrando = document.forms.nuevoJuicio.mostrar.value;
			if (n > mostrando && mostrando < 120) {
				mostrando = mostrando + 12;
				mostrarPeriodos();
			}
		});
	});
}
	
function limpiarAcuerdos() {
	formatoPeriodoInicio();
	var limite = document.forms.nuevoJuicio.cantAcuerdos.value;
	console.log(limite);
	if (limite == 1) {
		document.forms.nuevoJuicio.nroacu.checked = false;
	} else {
		for (var i=0; i < limite; i++) {
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
	for(var i=0; i<12; i++) {
		id = "id" + i;
		m = "mes" + i;
		a = "anio" + i;
		con = "concepto" + i;
		document.getElementById(id).value="";
		document.getElementById(m).value="";
		document.getElementById(a).value="";
		document.getElementById(con).value="";
	}
	for (var i=12; i<120; i++){
		id = "id" + i;
		m = "mes" + i;
		a = "anio" + i;
		con = "concepto" + i;
		f = "fila" + i;
		document.getElementById(f).style.display="none";
		document.getElementById(id).value="";
		document.getElementById(m).value="";
		document.getElementById(a).value="";
		document.getElementById(con).value="";
	}
	document.forms.nuevoJuicio.mostrar.value = 12;
}

function mostrarBotones() {
	if (document.forms.nuevoJuicio.tramite[0].checked) {
		document.getElementById("bguardar").style.display="block";
		document.getElementById("btramite").style.display="none";
	} else {
		document.getElementById("bguardar").style.display="none";
		document.getElementById("btramite").style.display="block";
	}
}

function validoMes(id) {
	nombreMes = "mes" + id;
	valorMes = document.getElementById(nombreMes).value;
	var errorMes = "Error en la carga del mes. Mes " + valorMes + " no es posible";
	if (valorMes < 0 || valorMes > 12) {
		alert(errorMes);
		document.getElementById(nombreMes).value = "";
		document.getElementById(nombreMes).focus();
		return false;
	} 
	return true;
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
	for (var i=0; i<n; i++){
		if (i != id) {
			mescom = "mes" + i;
			aniocom = "anio" + i;
			mescomp = document.getElementById(mescom).value;
			aniocom = document.getElementById(aniocom).value;
			if (mescomp != '' && aniocom != '') {
				if (anio == aniocom && mes == mescomp) {
					alert("Este periódo ya se encuentra en la lista");
					document.getElementById(mesnombre).value = "";
					document.getElementById(anionombre).value = "";
					document.getElementById(mesnombre).focus();
				}
			}
		}
	}
}

function mostrarPeriodos() {
	if (parseInt(document.forms.nuevoJuicio.mostrar.value) < 120) {	
		var n = parseInt(document.forms.nuevoJuicio.mostrar.value);
		var o = 0;
		var f = 0;
		for (var i=0; i<=12; i++){
			o = parseInt(document.forms.nuevoJuicio.mostrar.value) + i;
			if (o < 120) {
				f = "fila" + o;
				document.getElementById(f).style.display="table-row";
			}
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
	
	var limite = document.forms.nuevoJuicio.cantAcuerdos.value;
	if (limite != 0) {
		if (formulario.acuabs[1].checked) {
			if (limite == 1) {
				if(!document.forms.nuevoJuicio.nroacu.checked) {
					alert("Debe elegir un acuerdo a absorber");
					return false;
				}
			} else {
				var algunCheck = false;
				for (var i=0; i < limite; i++) {
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
<body bgcolor="#CCCCCC" >
<form id="nuevoJuicio" name="nuevoJuicio" method="post" action="preparoDatosJuicio.php" >
  <div align="center">
    <input name="nrcuit" type="text" id="nrcuit" readonly="readonly" size="4" style="visibility:hidden; position:absolute; z-index:1" value="<?php echo $cuit ?>"/>
    <p><input type="button" name="volver" value="Volver" onclick="location.href = 'juicios.php?cuit=<?php echo $cuit?>'"/></p>
    <?php include($libPath."cabeceraEmpresa.php"); ?>
  	<h3>Módulo de Carga - Nuevo Juicio </h3>
   	<p><b>NRO ORDEN </b><input name="nroorden" type="text" id="nroorden" size="5" readonly="readonly" value="<?php echo $rowBuscaNro['AUTO_INCREMENT'] ?>" style="background-color:#CCCCCC; text-align:center" /></p>

	<!-- CABECERA -->
   	<table width="1000" border="0" style="text-align: left">
      <tr>
        <td>Nro. Certificado</td>
        <td><input id="nrocert" type="text" name="nrocert"/></td>
        <td>Status Deuda</td>
        <td><label>
          <select name="status" id="status">
            <option value="0" selected="selected">Seleccione Status</option>
            <option value="1">EJECUCION</option>
            <option value="2">CONVOCATORIA</option>
            <option value="3">QUIEBRA</option>
          </select>
          </label>        
        </td>
        <td>Fecha Expedición</td>
        <td><input id="fechaexp" type="text" name="fechaexp" size="12"/></td>
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
            <option value='0' selected="selected">Seleccione Asesor</option>
            <?php 
					$resAsesor = mysql_query($sqlAsesor,$db);
					while ($rowAsesor=mysql_fetch_assoc($resAsesor)) { ?>
           				 <option value="<?php echo $rowAsesor['codigo'] ?>"><?php echo $rowAsesor['apeynombre'] ?></option>
            <?php } ?>
          </select>        </td>
        <td>Inspector</td>
        <td><select name="inspector" id="inspector">
            <option value='0' selected="selected">Seleccione Inspector</option>
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
    </table>
     
    <!-- ACUERDOS A ABSORVER --> 
    <h3>Acuerdos a Absorver </h3>
    <h4 id="cartelAcuerdos" style="color: blue">Seleccione Status de Deuda para ver los Acuerdos</h4>
   	<div id="datosAcuerdos" style="display: none">
   		 <input type="text" id="cantAcuerdos" name="cantAcuerdos" value="0" style="display: none"/>
   	</div>    
    
    <!-- PERIODOS -->
    <input name="mostrar" type="text" id="mostrar" size="1" value="12" readonly="readonly" style="display: none"/>
   	<table width="800" style="text-align: center; margin-top: 15px">
        <tr>
          <td width="50%">
              <p><b>PERÍODOS DEL JUICIO</b></p>
              <p><input name="masPeridos" type="button" id="masPeridos" value="Mas Periodos"  onclick="mostrarPeriodos()"/></p>
          </td>
		  <td>
		      <p><b>TRAMITE JUDICIAL</b> [<input name="tramite" type="radio" value="0" checked="checked" onchange="mostrarBotones()"/> NO - <input name="tramite" type="radio" value="1" onchange="mostrarBotones()"/> SI ]</p>
		  </td>
        </tr>
        <tr>
          <td width="80" align="center"><b>Mes | Año</b></td>
        </tr>
        <?php 	for ($i = 0 ; $i < 120; $i ++) {
					if ($i < 12) { ?>
						<tr id="fila<?php echo $i?>">
							<td>
								<input name='id<?php echo $i ?>' type='text' id='id<?php echo $i ?>' size='2' style='visibility:hidden'/>
								<input name='mes<?php echo $i ?>' type='text' id='mes<?php echo $i ?>' size='2' onfocusout='validoMes(<?php echo $i ?>)' onchange='limpioid(<?php echo $i ?>)'/>
								<input name='anio<?php echo $i ?>' type='text' id='anio<?php echo $i ?>' size='4' onchange='limpioid(<?php echo $i ?>)'/>
								<input name='concepto<?php echo $i ?>' type='text' id='concepto<?php echo $i ?>' size='2' style='visibility:hidden'/>
							</td>
						</tr>
		<?php		} else { ?>
						<tr id="fila<?php echo $i?>" style="display: none">
							<td>
								<input name='id<?php echo $i ?>' type='text' id='id<?php echo $i ?>' size='2' style='visibility:hidden' />		 
								<input name='mes<?php echo $i ?>' id='mes<?php echo $i ?>' type='text' size='2' onfocusout='validoMes(<?php echo $i ?>)' onchange='limpioid(<?php echo $i ?>)'/>
								<input name='anio<?php echo $i ?>' id='anio<?php echo $i ?>' type='text'  size='4' onchange='limpioid(<?php echo $i ?>)'/>
								<input name='concepto<?php echo $i ?>' type='text' id='concepto<?php echo $i ?>' size='2' style='visibility:hidden'/>
							</td>		 
						</tr>
		<?php		}	
        		} ?>
      </table>
    
    <!-- BOTONES DE GUARDADO -->
    <p>
      	<input name="bguardar" type="button" id="bguardar" value="Guardar Juicio" onclick="validar(document.forms.nuevoJuicio)"/>
  	  	<input name="btramite" type="button" id="btramite" value="Cargar Tramite Judicial" style="display: none" onclick="validar(document.forms.nuevoJuicio)"/>
  	</p>
</div>
</form>
</body>
</html>
