<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
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
<title>.: Emision de Carnets :.</title>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css" type="text/css" id="" media="print, projection, screen" />
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery-ui.min.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
	var nombredelegacion;
	$('#listador').attr("disabled", true);
	$('#emitir').attr("disabled", true);
	$("#listado")
		.tablesorter({
			theme: 'blue',
			widthFixed: true, 
			widgets: ["zebra"],
			headers:{0:{sorter:false}, 1:{sorter:false}, 2:{sorter:false}, 3:{sorter:false}, 4:{sorter:false}, 5:{sorter:false}, 6:{sorter:false}}
		});

	$("#selectDelegacion").change(function(){
		var delegacion = $(this).val();
		$.ajax({
			type: "POST",
			dataType: "json",
			url: "nombreDelegacion.php",
			data: {delegacion:delegacion},
		}).done(function(respuesta){
			if(respuesta) {
				$('#listador').attr("disabled", false);
				$("#delegacion").val(delegacion);
				nombredelegacion = respuesta;
				$('#emitir').attr("disabled", true);
			}
		});
	});

	$("#listador").click(function(){
		var delegacion = $("#delegacion").val();
		if(delegacion=="") {
			alert("Debe seleccionar alguna Delegación");
			$("#selectDelegacion").focus();
		} else {
			$.blockUI({ message: "<h1>Buscando beneficiarios con status de emision.<br>Aguarde por favor...</h1>" });
			$.ajax({
				type: "POST",
				dataType: 'html',
				url: "titularesAEmitir.php",
				data: {delegacion:delegacion},
			}).done(function(respuesta){
				$.unblockUI();
				if(respuesta) {
					$("#txtNombreDelegacion").html(nombredelegacion);
					$("#listado").html(respuesta);
					$('#emitir').attr("disabled", false);
				} else {
					nombredelegacion = 'SIN RESULTADOS PARA LA DELEGACION '+nombredelegacion;
					$("#txtNombreDelegacion").html(nombredelegacion);
					$("#listado").html(respuesta);
					$('#emitir').attr("disabled", true);
				}
			});
		}
		$("#selectDelegacion option[value='']").prop('selected',true);
		$('#listador').attr("disabled", true);
	});
});

function validar(formulario) {
	var elementos = document.forms.carnetsPorDelegacion.elements;
	var longitud = document.forms.carnetsPorDelegacion.length;
	var elementocheckbox = 0;
	for(var i=0; i<longitud; i++) {
		if(elementos[i].name == "titularSeleccionado[]" && elementos[i].type == "checkbox" && elementos[i].checked == true) {
			elementocheckbox=i;
		}
	}
	if(elementocheckbox == 0) {
		alert("Debe seleccionar por lo menos un afiliado para poder emitir carnets");
		return false;
	}

	if(formulario.delegacion.value == "") {
		alert("Debe seleccionar alguna Delegación");
		return false;
	}
	$.blockUI({ message: "<h1>Emitiendo Carnets.<br>Aguarde por favor...</h1>" });
	return true;
};
</script>
</head>
<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="reset" name="volver" value="Volver" onClick="location.href = 'moduloImpresion.php'"/></p>
  	<form name="carnetsPorDelegacion" id="carnetsPorDelegacion" method="post" onSubmit="return validar(this)" action="emitirCarnets.php">
  	<h2>Carnets por Delegaci&oacute;n</h2>
	<table>
		<tr>
			<td width="96"><strong>Delegación</strong></td>
		  	<td width="377"><div align="left">
		    	<select name="selectDelegacion" id="selectDelegacion">
		    		<option title="Seleccione un valor" value="" selected="selected">Seleccione un Valor</option>
		      		<?php 
						$sqlSelectDelegacion="SELECT codidelega, nombre FROM delegaciones WHERE codidelega NOT IN(3500,4000,4001)";
						$resSelectDelegacion=mysql_query($sqlSelectDelegacion,$db);
						while($rowSelectDelegacion=mysql_fetch_array($resSelectDelegacion)) {
							echo "<option title ='$rowSelectDelegacion[nombre]' value='$rowSelectDelegacion[codidelega]'>".$rowSelectDelegacion['nombre']."</option>";
		    			}
					?>
	        	</select>
	      	    <input name="delegacion" type="text" id="delegacion" value="" size="6" readonly="readonly" style="visibility:hidden" />
		  	</div></td>
		</tr>
	</table>
	<p><input type="button" name="listador" id="listador" value="Listar"/></p>
	<div id="nombreDelegacion"><h3><span id="txtNombreDelegacion"></span></h3></div>
	<div id="tablaTitulares">
	<table class="tablesorter" id="listado" style="text-align:center">
		<thead>
			<tr>
				<th>Nro. de Afiliado</th>
				<th>Apellido y Nombre</th>
				<th>Regular [Azul]</th>
				<th>Solo OSPIM [Bordo]</th>
				<th>Opcion [Rojo]</th>
				<th>USIMRA [Verde]</th>
				<th>Emite</th>
			</tr>
	 	</thead>
		<tbody>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
		</tbody>
	</table> 
	</div>
	<p><input type="submit" name="emitir" id="emitir" value="Emitir Carnets"/></p>
	</form>
</div>
</body>
</html>
