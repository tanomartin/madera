<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php");

$codigo = $_GET['codigo'];
$idcontrato = $_GET['idcontrato'];
$sqlConsultaPresta = "SELECT * FROM prestadores WHERE codigoprestador = $codigo";
$resConsultaPresta = mysql_query($sqlConsultaPresta,$db);
$rowConsultaPresta = mysql_fetch_assoc($resConsultaPresta);

$sqlTiposContrato = "SELECT tn.id, tn.codigonomenclador, tp.descripcion, n.nombre
						FROM detcontratoprestador d, practicas p, tipopracticasnomenclador tn, tipopracticas tp, nomencladores n
						WHERE d.idcontrato = $idcontrato and d.idpractica = p.idpractica and p.tipopractica = tn.id and tn.idtipo = tp.id and tn.codigonomenclador = n.id
						GROUP BY p.tipopractica";
$resTiposContrato = mysql_query($sqlTiposContrato,$db);
$numTiposContrato = mysql_num_rows($resTiposContrato);
$arrayTipos = array();
if ($numTiposContrato > 0) {
	while ($rowTiposContrato = mysql_fetch_assoc($resTiposContrato)) {
		$arrayTipos[$rowTiposContrato['id']] = $rowTiposContrato;
	}
}

$sqlSubCapContrato = "SELECT s.*
						FROM detcontratoprestador d, practicas p, subcapitulosdepracticas s
						where d.idcontrato = $idcontrato and
						     d.idpractica = p.idpractica and
						     p.codigopractica like '%.%.%' and p.idpadre = s.id
						GROUP BY p.idpadre";
$resSubCapContrato = mysql_query($sqlSubCapContrato,$db);
$numSubCapContrato = mysql_num_rows($resSubCapContrato);
$arrayCapContrato = array();
$arraySubCapContrato = array();
if ($numSubCapContrato > 0 ) {
	while ($rowSubCapContrato = mysql_fetch_assoc($resSubCapContrato)) {
		$arraySubCapContrato[$rowSubCapContrato['id']] = $rowSubCapContrato['id'];
		$arrayCapContrato[$rowSubCapContrato['idcapitulo']] = $rowSubCapContrato['idcapitulo'];
	}
}
$listadoSubCapitulos = serialize($arraySubCapContrato);
$listadoSubCapitulos = urlencode($listadoSubCapitulos);


$sqlCapContarto = "SELECT c.*
					FROM detcontratoprestador d, practicas p, capitulosdepracticas c
					where d.idcontrato = $idcontrato and
					     d.idpractica = p.idpractica and
					     p.codigopractica like '%.%' and
					     p.codigopractica not like '%.%.%' and p.idpadre = c.id
					GROUP BY p.idpadre";
$resCapContrato = mysql_query($sqlCapContarto,$db);
$numCapContrato = mysql_num_rows($resCapContrato);
if ($numCapContrato > 0 ) {
	while ($rowCapContrato = mysql_fetch_assoc($resCapContrato)) {
		$arrayCapContrato[$rowCapContrato['id']] = $rowCapContrato['id'];
	}
}
$listadoCapitulos = serialize($arrayCapContrato);
$listadoCapitulos = urlencode($listadoCapitulos);

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modificar Contrato :.</title>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<link rel="stylesheet" href="/madera/lib/tablas.css"/>
<script type="text/javascript">

	$(function() {
		$("#practicas")
		.tablesorter({
			theme: 'blue', 
			widthFixed: true, 
			widgets: ["zebra", "filter"], 
			widgetOptions : { 
				filter_cssFilter   : '',
				filter_childRows   : false,
				filter_hideFilters : false,
				filter_ignoreCase  : true,
				filter_searchDelay : 300,
				filter_startsWith  : false,
				filter_hideFilters : false,
			}
		}),
		$.unblockUI(); 
	});

	function validar(formulario) {
		$.blockUI({ message: "<h1>Eliminando Practicas Seleccionadas</h1>" });
		return true;
	}
	
	jQuery(function($){	
		$("#tipo").change(function(){
			$("#capitulo").html("<option value='0'>Seleccione Capitulo</option>");
			$("#capitulo").prop("disabled",true);
			$("#subcapitulo").html("<option value='0'>Seleccione SubCapitulo</option>");
			$("#subcapitulo").prop("disabled",true);
			$("#eliminar").prop("disabled",true);
			$("#practicas").html("");
			var valor = $(this).val();
			var valores = valor.split("-");
			var tipo = valores[0];
			var nomenclador = valores[1];
			var personeria = $("#personeria").val();
			var contrato = $("#contrato").val();
			var idcapitulos = $("#idcapitulos").val();
			$.ajax({
				type: "POST",
				dataType: 'html',
				url: "getCapitulosContrato.php",
				data: {valor:tipo , idcapitulos:idcapitulos},
			}).done(function(respuesta){
				if (valor != 0) {
					if (respuesta != 0) {
						$("#capitulo").html(respuesta);
						$("#capitulo").prop("disabled",false);
					} else {
						$.ajax({
							type: "POST",
							dataType: 'html',
							url: "getPracticasContrato.php",
							data: {valor:-1, tipo:tipo, personeria: personeria, nomenclador:nomenclador, contrato:contrato, eleminar:1},
						}).done(function(respuesta){
							if (respuesta != 0) {	
								$("#practicas").html(respuesta);
								$("#eliminar").prop("disabled",false);
							} else {
								$("#practicas").html("NO EXISTEN PRACTICAS");
							}
						});
					}
				}
			});
		});
		
		$("#capitulo").change(function(){
			$("#subcapitulo").html("<option value='0'>Seleccione SubCapitulo</option>");
			$("#subcapitulo").prop("disabled",true);
			$("#practicas").html("");
			$("#eliminar").prop("disabled",true);
			var personeria = $("#personeria").val();
			var contrato = $("#contrato").val();
			var subcapitulos = $("#idsubcapitulos").val();
			var valor = $(this).val();
			valor = valor.split('-');
			tipo = $("#tipo").val();
			tipos = tipo.split('-');
			tipo = tipos[0];
			nomenclador = tipos[1];
			$.ajax({
				type: "POST",
				dataType: 'html',
				url: "getSubCapitulosContrato.php",
				data: {valor:valor[0], subcapitulos:subcapitulos},
			}).done(function(respuesta){
				console.log(respuesta);
				if (respuesta != 0) {
					$("#subcapitulo").html(respuesta);	
					$("#subcapitulo").prop("disabled",false);			
				}
				$.ajax({
					type: "POST",
					dataType: 'html',
					url: "getPracticasContrato.php",
					data: {valor:valor[1], tipo:tipo, nomenclador:nomenclador, personeria:personeria, contrato:contrato, padre:valor[0],eleminar:1},
				}).done(function(respuesta){
					if (respuesta != 0) {
						$("#practicas").html(respuesta);
						$("#eliminar").prop("disabled",false);
					}
				});
			});
		});
		
		$("#subcapitulo").change(function(){
			$("#practicas").html("");
			$("#eliminar").prop("disabled",true);
			var personeria = $("#personeria").val();
			var contrato = $("#contrato").val();
			tipo = $("#tipo").val();
			tipos = tipo.split('-');
			tipo = tipos[0];
			nomenclador = tipos[1];
			var valor = $(this).val();
			if (valor == 0) { 
				valor = $("#capitulo").val();
				valor = valor.split('-');
				$.ajax({
					type: "POST",
					dataType: 'html',
					url: "getPracticasContrato.php",
					data: {valor:valor[1], tipo:tipo, personeria:personeria, contrato:contrato, padre:valor[0], eleminar:1},
				}).done(function(respuesta){
					console.log(respuesta);
					if (respuesta != 0) {
						$("#practicas").html(respuesta);
						$("#eliminar").prop("disabled",false);
					} 
				});
			} else {
				valor = valor.split('-');
				$.ajax({
					type: "POST",
					dataType: 'html',
					url: "getPracticasContrato.php",
					data: {valor:valor[1], tipo:tipo, nomenclador:nomenclador, personeria:personeria, contrato:contrato, padre:valor[0], eleminar:1},
				}).done(function(respuesta){
					console.log(respuesta);
					if (respuesta != 0) {
						$("#practicas").html(respuesta);
						$("#eliminar").prop("disabled",false);
					}
				});
			}
		});
	});

</script>
</head>

<body bgcolor="#CCCCCC">
<script>
	$.blockUI({ message: "<h1>Cargando Contrato<br>Esto puede tardar unos segundos.<br> Aguarde por favor</h1>" } );
</script>
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = 'contratosPrestador.php?codigo=<?php echo $codigo ?>'" /></p>
  <h3>ABM de Contratos </h3>
  <table border="1">
    <tr>
      <td width="163"><div align="right"><strong>C&oacute;digo</strong></div></td>
      <td width="321"><div align="left"><strong><?php echo $rowConsultaPresta['codigoprestador']  ?></strong></div></td>
    </tr>
    <tr>
      <td><div align="right"><strong>Razón Social</strong></div></td>
      <td>
      	<div align="left"><?php echo $rowConsultaPresta['nombre'] ?></div>
      	<input type="hidden" id="personeria" value="<?php echo $rowConsultaPresta['personeria']?>" />
      </td>
    </tr>
  </table>
  <form name="editarContrato" id="editarContrato" onsubmit="return validar(this)" method="post" action="eliminarPracticasGuardar.php?codigo=<?php echo $codigo ?>&idcontrato=<?php echo $idcontrato ?>" >
    <h3>Prácticas dentro del contrato para eliminar - ID <?php echo $idcontrato ?> </h3>
	<input type="hidden" id="contrato" value="<?php echo $idcontrato ?>" />  
	<input type="hidden" id="idcapitulos" value="<?php echo $listadoCapitulos ?>" />  
	<input type="hidden" id="idsubcapitulos" value="<?php echo $listadoSubCapitulos ?>" />  
  <?php if(isset($_GET['error'])) {
     			if ($_GET['error'] == 0) { ?>
     				<div style='color:blue'><b>PRACTICA/S ELIMINADAS CORRECTAMENTE DEL CONTRATO</b></div>
     <?php 		}
    	   } ?>
      <p><select name="tipo" id="tipo">
          <option value="0">Seleccione Tipo de Practica</option>  
          <?php foreach ($arrayTipos as $idTipo => $tipos) { ?>
         	 <option value="<?php echo $idTipo."-".$tipos['codigonomenclador'] ?>"><?php echo $tipos['nombre']." - ".$tipos['descripcion'] ?></option>
          <?php } ?>
        </select></p>
     <p>
        <select name="capitulo" id="capitulo" disabled="disabled">
          <option value="0">Seleccione Capitulo</option>
        </select>
      </p>
	  <p>
        <select name="subcapitulo" id="subcapitulo" disabled="disabled">
          <option value="0">Seleccione SubCapitulo</option>
        </select>
      </p>
      <table style="text-align:center; font-size: 13px" id="practicas" class="tablesorter" >
		 <thead>
		 </thead>
		 <tbody>
		 </tbody>
  	 </table>
		<p><input type="submit" name="eliminar" id="eliminar" value="Eliminar Seleccionados" disabled="disabled"/></p>
    </form>
</div>
</body>
</html>