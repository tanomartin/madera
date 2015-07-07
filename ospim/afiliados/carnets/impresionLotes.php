<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
$lote=$_GET['nroLote'];
$usuariolote = $_SESSION['usuario'];
$lotecerrado = FALSE;

$sqlLeeLote="SELECT * FROM impresioncarnets WHERE lote = '$lote' AND usuarioemision = '$usuariolote'";
$resLeeLote=mysql_query($sqlLeeLote,$db);
$rowLeeLote=mysql_fetch_assoc($resLeeLote);
if($rowLeeLote['marcacierreimpresion'] == 1) {
	$lotecerrado = TRUE;
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo4 {
	font-size: 18px;
	font-weight: bold;
}
</style>
<style type="text/css" media="print">
.nover {display:none}
</style>
<title>.: Lote de Impresion :.</title>
<link href="/madera/lib/jquery-ui-1.11.1/jquery-ui.css" rel="stylesheet">
<link href="/madera/lib/tablas.css" rel="stylesheet">
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery-ui-1.11.1/jquery-ui.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
	var lote = $("#idlote").val();
	var usuario = $("#usuarioemision").val();
	//declaro variables global para indicar al dialogo modal que formulario debe buscar y que mensajes y titulos mostrar
	var tipoformulario = "";
	var titulodialogo = "";
	var listado = $("#marcalistado").val();
	var nota = $("#marcanota").val();

	if($("#marcacarnetsazul").length) {
		var carnetazul = $("#marcacarnetsazul").val();
	} else {
		var carnetazul = "1";
	}
	if($("#marcacarnetsbordo").length) {
		var carnetbordo = $("#marcacarnetsbordo").val();
	} else {
		var carnetbordo = "1";
	}
	if($("#marcacarnetsrojo").length) {
		var carnetrojo = $("#marcacarnetsrojo").val();
	} else {
		var carnetrojo = "1";
	}
	if($("#marcacarnetsverde").length) {
		var carnetverde = $("#marcacarnetsverde").val();
	} else {
		var carnetverde = "1";
	}
	if($("#marcacarnetsazul").val()=="0") {
		$("#imprimeazul").val("Imprimir");
		$('#imprimeazul').attr("disabled", false);
	} else {
		$("#imprimeazul").val("Ya Impreso");
		$('#imprimeazul').attr("disabled", true);
	}
	if($("#marcacarnetsbordo").val()=="0") {
		$("#imprimebordo").val("Imprimir");
		$('#imprimebordo').attr("disabled", false);
	} else {
		$("#imprimebordo").val("Ya Impreso");
		$('#imprimebordo').attr("disabled", true);
	}
	if($("#marcacarnetsrojo").val()=="0") {
		$("#imprimerojo").val("Imprimir");
		$('#imprimerojo').attr("disabled", false);
	} else {
		$("#imprimerojo").val("Ya Impreso");
		$('#imprimerojo').attr("disabled", true);
	}
	if($("#marcacarnetsverde").val()=="0") {
		$("#imprimeverde").val("Imprimir");
		$('#imprimeverde').attr("disabled", false);
	} else {
		$("#imprimeverde").val("Ya Impreso");
		$('#imprimeverde').attr("disabled", true);
	}
	if($("#marcalistado").val()=="0") {
		$("#imprimelistado").val("Imprimir");
		$('#imprimelistado').attr("disabled", false);
	} else {
		$("#imprimelistado").val("Ya Impreso");
		$('#imprimelistado').attr("disabled", true);
	}
	if($("#marcanota").val()=="0") {
		$("#imprimenota").val("Imprimir");
		$('#imprimenota').attr("disabled", false);
	} else {
		$("#imprimenota").val("Ya Impreso");
		$('#imprimenota').attr("disabled", true);
	}
	$('body').on('click','#imprimeazul',function() {
		tipoformulario = "A";
		titulodialogo = "Formulario Azul del Lote "+lote;
		var carnets = $("#totalcarnetsazul").val();
		if(carnets == 1) {
			var textocarnets = carnets+" carnet Regular que requiere ";
		}
		if(carnets > 1) {
			var textocarnets = carnets+" carnets Regulares que requieren ";
		}
		var hojas = $("#totalhojasazul").val();
		if(hojas == 1) {
			var textohojas = hojas+" hoja ";
		}
		if(hojas > 1) {
			var textohojas = hojas+" hojas ";
		}
		var textoformulario = "del formulario Azul. "
		$("#mensajeconfirma").empty();
		var mensaje = "Ud. va a imprimir "+textocarnets+textohojas+textoformulario+"Coloco las hojas del formulario en la impresora?";
		var contenidodialogo = "<span style='float:left; margin:0 7px 20px 0;'>"+mensaje+"</span>";
		$("#mensajeconfirma").html(contenidodialogo);
		$('#confirma').dialog('open');
	});
	$('body').on('click','#imprimebordo',function() {
		tipoformulario = "B";
		titulodialogo = "Formulario Bordo del Lote "+lote;
		var carnets = $("#totalcarnetsbordo").val();
		if(carnets == 1) {
			var textocarnets = carnets+" carnet Solo OSPIM que requiere ";
		}
		if(carnets > 1) {
			var textocarnets = carnets+" carnets Solo OSPIM que requieren ";
		}
		var hojas = $("#totalhojasbordo").val();
		if(hojas == 1) {
			var textohojas = hojas+" hoja ";
		}
		if(hojas > 1) {
			var textohojas = hojas+" hojas ";
		}
		var textoformulario = "del formulario Bordo. "
		$("#mensajeconfirma").empty();
		var mensaje = "Ud. va a imprimir "+textocarnets+textohojas+textoformulario+"Coloco las hojas del formulario en la impresora?";
		var contenidodialogo = "<span style='float:left; margin:0 7px 20px 0;'>"+mensaje+"</span>";
		$("#mensajeconfirma").html(contenidodialogo);
		$('#confirma').dialog('open');
	});
	$('body').on('click','#imprimerojo',function() {
		tipoformulario = "R";
		titulodialogo = "Formulario Rojo del Lote "+lote;
		var carnets = $("#totalcarnetsrojo").val();
		if(carnets == 1) {
			var textocarnets = carnets+" carnet de Opción que requiere ";
		}
		if(carnets > 1) {
			var textocarnets = carnets+" carnets de Opción que requieren ";
		}
		var hojas = $("#totalhojasrojo").val();
		if(hojas == 1) {
			var textohojas = hojas+" hoja ";
		}
		if(hojas > 1) {
			var textohojas = hojas+" hojas ";
		}
		var textoformulario = "del formulario Rojo. "
		$("#mensajeconfirma").empty();
		var mensaje = "Ud. va a imprimir "+textocarnets+textohojas+textoformulario+"Coloco las hojas del formulario en la impresora?";
		var contenidodialogo = "<span style='float:left; margin:0 7px 20px 0;'>"+mensaje+"</span>";
		$("#mensajeconfirma").html(contenidodialogo);
		$('#confirma').dialog('open');
	});
	$('body').on('click','#imprimeverde',function() {
		tipoformulario = "V";
		titulodialogo = "Formulario Verde del Lote "+lote;
		var carnets = $("#totalcarnetsverde").val();
		if(carnets == 1) {
			var textocarnets = carnets+" carnet USIMRA que requiere ";
		}
		if(carnets > 1) {
			var textocarnets = carnets+" carnets USIMRA que requieren ";
		}
		var hojas = $("#totalhojasverde").val();
		if(hojas == 1) {
			var textohojas = hojas+" hoja ";
		}
		if(hojas > 1) {
			var textohojas = hojas+" hojas ";
		}
		var textoformulario = "del formulario Verde. "
		$("#mensajeconfirma").empty();
		var mensaje = "Ud. va a imprimir "+textocarnets+textohojas+textoformulario+"Coloco las hojas del formulario en la impresora?";
		var contenidodialogo = "<span style='float:left; margin:0 7px 20px 0;'>"+mensaje+"</span>";
		$("#mensajeconfirma").html(contenidodialogo);
		$('#confirma').dialog('open');
	});
	$('body').on('click','#imprimelistado',function() {
		tipoformulario = "L";
		titulodialogo = "Listado del Lote "+lote;
		$("#mensajeconfirma").empty();
		var mensaje = "Ud. va a imprimir el Listado de Titulares correspondiente al Lote. Coloco hojas tamaño Carta en la impresora?";
		var contenidodialogo = "<span style='float:left; margin:0 7px 20px 0;'>"+mensaje+"</span>";
		$("#mensajeconfirma").html(contenidodialogo);
		$('#confirma').dialog('open');
	});
	$('body').on('click','#imprimenota',function() {
		tipoformulario = "N";
		titulodialogo = "Nota del Lote "+lote;
		$("#mensajeconfirma").empty();
		var mensaje = "Ud. va a imprimir la Nota de Entrega de Carnets correspondiente al Lote. Coloco hojas tamaño Carta en la impresora?";
		var contenidodialogo = "<span style='float:left; margin:0 7px 20px 0;'>"+mensaje+"</span>";
		$("#mensajeconfirma").html(contenidodialogo);
		$('#confirma').dialog('open');
	});
	$("#confirma" ).dialog({
		autoOpen: false,
		open: function(event, ui) { $(".ui-dialog-titlebar-close").hide(); },
		modal: true,
		height: "auto",
		resizable: false,
		closeOnEscape: false,
		buttons: {
			"Si": function() {
				$( this ).dialog( "close" );				
				$("#mostrarpdf").empty();
				var contenidopdf = "<object id='pdfObject' type='application/pdf' data='imprimeFormularios.php?lote="+lote+"&usuario="+usuario+"&formulario="+tipoformulario+"&azul="+carnetazul+"&bordo="+carnetbordo+"&rojo="+carnetrojo+"&verde="+carnetverde+"&listado="+listado+"&nota="+nota+"'width='100%' height='100%'></object>";
				//var contenidopdf = "<object id='pdfObject' type='application/pdf' data='imprimeFormularios.php?lote="+lote+"&usuario="+usuario+"&formulario="+tipoformulario+"'width='100%' height='100%'></object>";
				$("#mostrarpdf").html(contenidopdf);
				$('#mostrarpdf').dialog("option", "title", ""+titulodialogo);
				$('#mostrarpdf').dialog('open');
			},
			"No": function() {
				$( this ).dialog( "close" );
				if(tipoformulario == "A") {
					var textoformulario = "las hojas del formulario Azul";
				}
				if(tipoformulario == "B") {
					var textoformulario = "las hojas del formulario Bordo";
				}
				if(tipoformulario == "R") {
					var textoformulario = "las hojas del formulario Rojo";
				}
				if(tipoformulario == "V") {
					var textoformulario = "las hojas del formulario Verde";
				}
				if(tipoformulario == "L" || tipoformulario == "N" ) {
					var textoformulario = "hojas tamaño Carta";
				}
				$("#mensajecancela").empty();
				var mensaje = "Coloque por favor "+textoformulario+" y repita la operación.";
				var contenidodialogo = "<span style='float:left; margin:0 7px 20px 0;'>"+mensaje+"</span>";
				$("#mensajecancela").html(contenidodialogo);
				$('#cancela').dialog('open');
			}
		}
	});
	$("#cancela" ).dialog({
		autoOpen: false,
		open: function(event, ui) { $(".ui-dialog-titlebar-close").hide(); },
		modal: true,
		height: "auto",
		resizable: false,
		closeOnEscape: false,
		buttons: {
			"Ok": function() {
				$( this ).dialog( "close" );
			}
		}
	});
	$('#mostrarpdf').dialog({
		autoOpen: false,
		open: function(event, ui) { $(".ui-dialog-titlebar-close").hide(); },
		modal :true,
		position: { my: "center center", at: "center center", of: window },
		width:880,
		height:900,
		resizable: false,
		closeOnEscape: false,
		buttons: {
			"Cerrar": function() {
				$( this ).dialog( "close" );
				location.reload()
			}
		}
	});
});
</script>
</head>
<body bgcolor="#CCCCCC" >
<div align="center">
	<p><input type="reset" name="volver" value="Volver" onClick="location.href = 'listadoLotes.php'"/></p>
	<h2>Lote de Impresi&oacute;n <?php echo $lote ?></h2>
</div>
<?php 
if($lotecerrado) {
?>
<div align="center">
	<h3>El Lote de Impresion ya ha sido cerrado con fecha <?php echo invertirFecha(substr($rowLeeLote['fechaimpresion'],0,10));?> a las <?php echo substr($rowLeeLote['fechaimpresion'],11,5);?> Hs.</h3>
</div>
<?php 
} else {
?>
<div align="left">
	<h3>Composici&oacute;n del Lote</h3>
	<table>
		<tr>
			<td><h4>Identificador</h4></td>
			<td><h4>: <input type="text" name="idlote"  id="idlote" value="<?php echo $rowLeeLote['lote']?>" size="14" readonly="readonly" style="background-color:#CCCCCC"/></h4></td>
		</tr>
		<tr>
			<td><h4>Delegaci&oacute;n</h4></td>
			<td><h4>: <input type="text" name="codidelega"  id="codidelega" value="<?php echo $rowLeeLote['codidelega']?>" size="4" readonly="readonly" style="background-color:#CCCCCC"/></h4></td>
		</tr>
		<tr>
			<td><h4>Usuario Emisor</h4></td>
			<td><h4>: <input type="text" name="usuarioemision"  id="usuarioemision" value="<?php echo $rowLeeLote['usuarioemision']?>" size="50" readonly="readonly" style="background-color:#CCCCCC"/></h4></td>
		</tr>
		<tr>
			<td><h4>Fecha de Emisi&oacute;n</h4></td>
			<td><h4>: <input type="text" name="fechaemision"  id="fechaemision" value="<?php echo invertirFecha(substr($rowLeeLote['fechaemision'],0,10));?>" size="10" readonly="readonly" style="background-color:#CCCCCC"/></h4></td>
		</tr>
		<tr>
			<td><h4>Total de Titulares</h4></td>
			<td><h4>: <input type="text" name="totaltitulares"  id="totaltitulares" value="<?php echo $rowLeeLote['totaltitulares']?>" size="4" readonly="readonly" style="background-color:#CCCCCC"/></h4></td>
		</tr>
	</table>
</div>
<p></p>
<div class="grilla">
	<table>
		<thead>
		<tr>
			<th colspan="4" scope="col">Formularios</th>
		</tr>
		<tr>
		  	<th>Tipo</th>
			<th>Total de Carnets</th>
			<th>Hojas Necesarias</th>
			<th>Acci&oacute;n</th>
		</tr>
	 	</thead>
		<tbody>
<?php 
if($rowLeeLote['totalcarnetsazul'] != 0) {
?>
		<tr>
			<td>Regular [Azul]<input type="text" name="marcacarnetsazul"  id="marcacarnetsazul" value="<?php echo $rowLeeLote['marcaimpresionazul']?>" size="1" readonly="readonly" style="visibility:hidden"/></td>
			<td><input type="text" name="totalcarnetsazul"  id="totalcarnetsazul" value="<?php echo $rowLeeLote['totalcarnetsazul']?>" size="4" readonly="readonly" style="background-color:#CCCCCC; text-align:center"/></td>
			<td><input type="text" name="totalhojasazul"  id="totalhojasazul" value="<?php echo $rowLeeLote['totalhojasazul']?>" size="4" readonly="readonly" style="background-color:#CCCCCC; text-align:center"/></td>
			<td><input type="button" name="imprimeazul" id="imprimeazul" value="Imprimir"></td>
		</tr>
		
<?php 
}
if($rowLeeLote['totalcarnetsbordo'] != 0) {
?>
		<tr>
			<td>Solo OSPIM [Bordo]<input type="text" name="marcacarnetsbordo"  id="marcacarnetsbordo" value="<?php echo $rowLeeLote['marcaimpresionbordo']?>" size="1" readonly="readonly" style="visibility:hidden"/></td>
			<td><input type="text" name="totalcarnetsbordo"  id="totalcarnetsbordo" value="<?php echo $rowLeeLote['totalcarnetsbordo']?>" size="4" readonly="readonly" style="background-color:#CCCCCC; text-align:center"/></td>
			<td><input type="text" name="totalhojasbordo"  id="totalhojasbordo" value="<?php echo $rowLeeLote['totalhojasbordo']?>" size="4" readonly="readonly" style="background-color:#CCCCCC; text-align:center"/></td>
			<td><input type="button" name="imprimebordo" id="imprimebordo" value="Imprimir"></td>
		</tr>
<?php 
}
if($rowLeeLote['totalcarnetsrojo'] != 0) {
?>
		<tr>
			<td>Opci&oacute;n [Rojo]<input type="text" name="marcacarnetsrojo"  id="marcacarnetsrojo" value="<?php echo $rowLeeLote['marcaimpresionrojo']?>" size="1" readonly="readonly" style="visibility:hidden"/></td>
			<td><input type="text" name="totalcarnetsrojo"  id="totalcarnetsrojo" value="<?php echo $rowLeeLote['totalcarnetsrojo']?>" size="4" readonly="readonly" style="background-color:#CCCCCC; text-align:center"/></td>
			<td><input type="text" name="totalhojasrojo"  id="totalhojasrojo" value="<?php echo $rowLeeLote['totalhojasrojo']?>" size="4" readonly="readonly" style="background-color:#CCCCCC; text-align:center"/></td>
			<td><input type="button" name="imprimerojo" id="imprimerojo" value="Imprimir"></td>
		</tr>
<?php 
}
if($rowLeeLote['totalcarnetsverde'] != 0) {
?>
		<tr>
			<td>USIMRA [Verde]<input type="text" name="marcacarnetsverde"  id="marcacarnetsverde" value="<?php echo $rowLeeLote['marcaimpresionverde']?>" size="1" readonly="readonly" style="visibility:hidden"/></td>
			<td><input type="text" name="totalcarnetsverde"  id="totalcarnetsverde" value="<?php echo $rowLeeLote['totalcarnetsverde']?>" size="4" readonly="readonly" style="background-color:#CCCCCC; text-align:center"/></td>
			<td><input type="text" name="totalhojasverde"  id="totalhojasverde" value="<?php echo $rowLeeLote['totalhojasverde']?>" size="4" readonly="readonly" style="background-color:#CCCCCC; text-align:center"/></td>
			<td><input type="button" name="imprimeverde" id="imprimeverde" value="Imprimir"></td>
		</tr>
<?php 
}
?>
		</tbody>
	</table>
</div>
<p></p>
<div class="grilla">
	<table>
		<thead>
		<tr>
			<th colspan="2" scope="col">Documentos</th>
		</tr>
		<tr>
			<th>Tipo</th>
			<th>Acción</th>
		</tr>
	 	</thead>
		<tbody >
		<tr>
			<td>Listado de Titulares<input type="text" name="marcalistado"  id="marcalistado" value="<?php echo $rowLeeLote['marcaimpresionlistado']?>" size="1" readonly="readonly" style="visibility:hidden"/></td>
			<td><input type="button" name="imprimelistado" id="imprimelistado" value="Imprimir"></td>
		</tr>
		<tr>
			<td>Nota de Entrega de Carnets<input type="text" name="marcanota"  id="marcanota" value="<?php echo $rowLeeLote['marcaimpresionnota']?>" size="1" readonly="readonly" style="visibility:hidden"/></td>
			<td><input type="button" name="imprimenota" id="imprimenota" value="Imprimir"></td>
		</tr>
		</tbody>
	</table>
</div>
<div id="confirma" title="Confirmaci&oacute;n de Impresi&oacute;n">
  <p id="mensajeconfirma"></p>
</div>
<div id="cancela" title="Aviso">
  <p id="mensajecancela"></p>
</div>
<div id="mostrarpdf">
</div>
<?php
} ?>
</body>
</html>