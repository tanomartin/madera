<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$nroafiliado = $_GET['nroafil'];
$nroorden = $_GET['nroorden'];
if ($nroorden == 0) {
	$sqlBeneficiario = "SELECT apellidoynombre, '' as parentesco FROM titulares WHERE nroafiliado = $nroafiliado";
	$tipoBeneficiario = "TITULAR";
} else {
	$sqlBeneficiario = "SELECT f.apellidoynombre, p.descrip as parentesco FROM familiares f, parentesco p WHERE f.nroafiliado = $nroafiliado and f.nroorden = $nroorden and f.tipoparentesco = p.codparent";
	$tipoBeneficiario = "FAMILIAR";
}
$resBeneficiario = mysql_query($sqlBeneficiario,$db);
$rowBeneficiario = mysql_fetch_assoc($resBeneficiario);

$sqlPiezas = "SELECT * FROM piezadental";
$resPiezas = mysql_query($sqlPiezas,$db);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Nueva Entrada Odontograma :.</title>

<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<link rel="stylesheet" href="/madera/lib/jquery-ui-1.9.2.custom/css/smoothness/jquery-ui-1.9.2.custom.css"/>
<script src="/madera/lib/jquery-ui-1.9.2.custom/js/jquery-1.8.3.js"></script>
<script src="/madera/lib/jquery-ui-1.9.2.custom/js/jquery-ui-1.9.2.custom.js"></script>
<script src="/madera/lib/inputmask/dist/jquery.inputmask.bundle.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">

$(document).ready(function(){
	$.datepicker.setDefaults($.datepicker.regional['es']);
	$("#fechaprestacion").inputmask("date");
	$("#fechaprestacion").datepicker({
		firstDay: 1,
		showButtonPanel: true,
		showOn: "button",
		buttonImage: "../img/calendar.png",
		buttonImageOnly: true,
		buttonText: "Seleccione la fecha",
		changeMonth: true,
		changeYear: true
    });

	$("#prestador").click(function() {
		$("#prestador").val('');
		$("#codigoprestador").val('');
		$("#buscaprestacion").val('');
		$("#idPractica").val('');
	});
    
	$("#prestador").autocomplete({
		source: function(request, response) {
			$.ajax({
				url: "listaPrestadores.php",
				dataType: "json",
				data: {getPrestador:request.term},
				success: function(data) {
					response(data);
				}
			});
		},
	    minLength: 4,
		select: function(event, ui) {
			$("#codigoprestador").val(ui.item.codigoprestador);
		}  
	});


	$("#buscaprestacion").autocomplete({
		source: function(request, response) {
			var idprestador = $("#codigoprestador").val();
			$.ajax({
				url: "buscaPrestacion.php",
				dataType: "json",
				data: {getPrestacion:request.term, idPrestador:idprestador},
				success: function(data) {
					response(data);
				}
			});
		},
        minLength: 3,
		select: function(event, ui) {
			$("#idPractica").val(ui.item.idpractica);
		}  
	});

	$("#pieza").change(function() {
		var valor = $("#pieza").val();
		$("#caras").html("<option value='0'>Seleccione Cara</option>");
		if (valor != 0) {
			var res = valor.split("-");
			var pos = "";
			if (res[1].indexOf("superior") > -1) {
				pos = "superior";
			} else {
				pos = "inferior";
			}
			$.ajax({
				type: "POST",
				dataType: 'html',
				url: "getCaras.php",
				data: {pos:pos},
			}).done(function(respuesta) {
				$("#caras").html(respuesta);	
			});
		}
	});
});

function validar(formulario) {
	if (formulario.fechaprestacion.value == "") {
		alert("La fecha de la prestacion es obligatoria");
		return false;
	}
	if (formulario.codigoprestador.value == "") {
		alert("Debe ingresar el prestador que realizo la practica");
		return false;
	}
	if (formulario.idPractica.value == "") {
		alert("Debe ingresar el prestador que realizo la practica");
		return false;
	}
	if (formulario.pieza.value == 0) {
		alert("Debe seleccionar la Pieza Dental");
		return false;
	}
	formulario.Submit.disabled = true;
	return true;
}

</script>

</head>

<body bgcolor="#CCCCCC">
	<div align="center">
	  	<p><input type="button" name="volver" value="Volver" onclick="location.href='odontograma.php?tipo=A&nroafil=<?php echo $nroafiliado ?>&nroorden=<?php echo $nroorden ?>'" /></p>
	  	<h3>Odontograma Nueva Entrada</h3>
	  	<table width="500" border="1" style="margin-bottom: 15px">
	    	<tr>
	      		<td width="163"><div align="right"><strong>Nro Afiliado </strong></div></td>
	     		<td width="321"><div align="left"><strong><?php echo $nroafiliado ?></strong></div></td>
	    	</tr>
	    	<tr>
	      		<td><div align="right"><strong>Apellido y Nombre </strong></div></td>
	      		<td><div align="left"><?php echo $rowBeneficiario['apellidoynombre'] ?></div></td>
	    	</tr>
	    	<tr>
	      		<td><div align="right"><strong>Tipo de Beneficiario </strong></div></td>
	      		<td><div align="left"><?php echo $tipoBeneficiario." - ".$rowBeneficiario['parentesco'] ?></div></td>
	   	 	</tr>
	  </table>
	  <form name="nuevoOdonto" id="nuevoOdonto" method="post" onsubmit="return validar(this)" action="odontogramaNuevoGuardar.php">
	  	<input type="text" name="nroafil" id="nroafil" value="<?php echo $nroafiliado ?>" style="display: none"/>
	  	<input type="text" name="nroorden" id="nroorden" value="<?php echo $nroorden ?>" style="display: none"/>
		<p>
			<strong>Fecha Prestacion</strong>
			<input name="fechaprestacion" type="text" id="fechaprestacion" size="8"/>
		</p>
		<p>
			<strong>Prestador</strong> 
			<textarea name="prestador" rows="2" cols="80" id="prestador" placeholder="Ingrese un minimo de 4 caracteres para que se inicie la busqueda"></textarea>
			<input name="codigoprestador" type="text" id="codigoprestador" style="display: none"/>
		</p>
	 	<p>
			<strong>Prestacion</strong>
			<textarea name="buscaprestacion" rows="2" cols="80" id="buscaprestacion" placeholder="Ingrese un minimo de 3 caracteres para que se inicie la busqueda"></textarea>
			<input name="idPractica" type="text" id="idPractica" style="display: none"/>
		</p>
		<p>
			<strong>Pieza Dental</strong>
			<select id="pieza" name="pieza">
				<option value="0" label="">Seleccione Pieza</option>
				<?php while ($rowpiezas = mysql_fetch_assoc($resPiezas)) { ?>
						<option value="<?php echo $rowpiezas['codigo']."-".$rowpiezas['posicion'] ?>" label="<?php echo $rowpiezas['codigo']." - ".$rowpiezas['descripcion']." - ".$rowpiezas['tipo']." - ".$rowpiezas['posicion'] ?>"></option>
				<?php } ?>
			</select>
		</p>
		<p>
			<strong>Cara de la Pieza</strong>
			<select id="caras" name="caras">
				<option value="0">Seleccione Cara</option>
			</select>
		</p>
	  <p><input type="submit" name="Submit" id="Submit" value="Guardar" /></p>
	  </form>
	</div>
</body>
</html>
