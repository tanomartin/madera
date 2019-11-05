<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."funcionespracticas.php");
$codigo = $_GET['codigo'];
$idcontrato = $_GET['idcontrato'];
$sqlConsultaPresta = "SELECT codigoprestador, nombre, personeria FROM prestadores WHERE codigoprestador = $codigo";
$resConsultaPresta = mysql_query($sqlConsultaPresta,$db);
$rowConsultaPresta = mysql_fetch_assoc($resConsultaPresta);

$sqlCabecera = "SELECT * FROM cabcontratoprestador WHERE idcontrato = $idcontrato";
$resCabecera = mysql_query($sqlCabecera,$db);
$rowCabecera = mysql_fetch_array($resCabecera);
$cartel = "";
if ($rowCabecera['idcontratotercero'] != 0) {
	$idcontrato = $rowCabecera['idcontratotercero'];
	$sqlPrestaTercer = "SELECT p.nombre, p.codigoprestador FROM prestadores p, cabcontratoprestador c WHERE c.idcontrato = $idcontrato and c.codigoprestador = p.codigoprestador";
	$resPrestaTercer = mysql_query($sqlPrestaTercer,$db);
	$rowPrestaTercer = mysql_fetch_assoc($resPrestaTercer);
	$cartel = "<h3 style='color: blue; border: 1px solid blue; width: 500'>Practicas de Contrato de Tercero <br>ID CONTRATO: $idcontrato <br> PRESTADOR: ".$rowPrestaTercer['nombre']." (".$rowPrestaTercer['codigoprestador'].")<h3>";
} 
$sqlPracticas = "SELECT pr.*,
p.*,
t.descripcion as tipo,
tc.descripcion as complejidad,
n.nombre as nombrenomenclador,
pc.descripcion as categoria
FROM
cabcontratoprestador c,
detcontratoprestador p,
practicas pr,
tipopracticasnomenclador tn,
tipopracticas t,
tipocomplejidad tc,
nomencladores n,
practicascategorias pc
WHERE
c.idcontrato = $idcontrato and
c.idcontrato = p.idcontrato and
p.idcategoria = pc.id and
p.idpractica = pr.idpractica and
pr.nomenclador = n.id and
pr.tipopractica = tn.id and
pr.codigocomplejidad = tc.codigocomplejidad and
n.id = tn.codigonomenclador and
tn.idtipo = t.id";
$resPracticas = mysql_query($sqlPracticas,$db);
$numPracticas = mysql_num_rows($resPracticas);
?>

<!DOCTYPE>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Contrato Prestador :.</title>
<style type="text/css" media="print">
.nover {display:none}
</style>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">

	$(function() {
		$("#practicaencontrato")
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
		
		})
	});
	
</script>

</head>
<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = 'consultaContratosPrestador.php?codigo=<?php echo $codigo ?>'" /></p>
  <h3>Contrato Prestador</h3>
	  <table style="width: 500" border="1">
        <tr>
          <td width="163"><div align="right"><strong>C&oacute;digo</strong></div></td>
          <td width="321"><div align="left"><strong><?php echo $rowConsultaPresta['codigoprestador']  ?></strong></div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>Nombre / Raz&oacute;n Social</strong></div></td>
          <td><div align="left">
              <div align="left"><?php echo $rowConsultaPresta['nombre'] ?></div>
          </div></td>
        </tr>
  	</table>
  	<h3>Prácticas del Contrato - Nº  <?php echo $idcontrato ?></h3>
    <?php echo $cartel ?>
	<?php  if ($numPracticas > 0) { ?>      
        <table style="text-align:center; width:1000px; font-size: 13px" id="practicaencontrato" class="tablesorter" >
          <thead>
            <tr>
              <th>C&oacute;digo</th>
              <?php if ($rowConsultaPresta['personeria'] == 3 || $rowConsultaPresta['personeria'] == 2) { ?><th class="filter-select" data-placeholder="Seleccione Categoria">Categoria</th> <?php } ?>
			  <th class="filter-select" data-placeholder="Seleccione Nomenclador">Nomenclador</th>
			  <th class="filter-select" data-placeholder="Seleccione Tipo">Tipo</th>
			  <th class="filter-select" data-placeholder="Seleccione Capitulo">Capitulo</th>
			  <th class="filter-select" data-placeholder="Seleccione Subcapitulo">Subcapitulo</th>
              <th>Descripciones</th>
			  <th class="filter-select" data-placeholder="Seleccione Complejidad">Clasificacion<br>Res. 650</th>
			  <th>Modulo Consultorio / Valor General ($)</th>
			  <th>Modulo Urgencia ($)</th>
			  <th>G. Honorarios ($)</th>
			  <th>G. Honorarios Especialista ($)</th>
			  <th>G. Honorarios Ayudante ($)</th>
			  <th>G. Honorarios Anestesista ($)</th>
			  <th>G. Gastos ($)</th>
			  <th>Coseguro ($)</th>
            </tr>
          </thead>
          <tbody>
            <?php
			while($rowPracticas = mysql_fetch_array($resPracticas)) {
				$descripPractica = descripcionPractica($rowPracticas['codigopractica'],$rowPracticas['tipopractica'],$db); ?>
				<tr>
				  <td><?php echo $rowPracticas['codigopractica'] ?></td>
				  <?php if ($rowConsultaPresta['personeria'] == 3 || $rowConsultaPresta['personeria'] == 2) { ?> <td> <?php echo $rowPracticas['categoria'] ?></td> <?php } ?>
				  <td><?php echo $rowPracticas['nombrenomenclador'] ?></td>
				  <td><?php echo $rowPracticas['tipo'] ?></td>
				  <td><?php echo $descripPractica['capitulo'] ?></td>
				  <td><?php echo $descripPractica['subcapitulo'] ?></td>
				  <td><?php echo $rowPracticas['descripcion'];?></td>
				  <td><?php echo $rowPracticas['complejidad'];?></td>
				  <td><?php echo $rowPracticas['moduloconsultorio'];?></td>
				  <td><?php echo $rowPracticas['modulourgencia'];?></td>
				  <td><?php echo $rowPracticas['galenohonorario'];?></td>
				  <td><?php echo $rowPracticas['galenohonorarioespecialista'];?></td>
				  <td><?php echo $rowPracticas['galenohonorarioayudante'];?></td>
				  <td><?php echo $rowPracticas['galenohonorarioanestesista'];?></td>
				  <td><?php echo $rowPracticas['galenogastos'];?></td>
				  <td><?php echo $rowPracticas['coseguro'];?></td>
				</tr>
         <?php } ?>
          </tbody>
        </table>
   <?php } else { ?>
        	<h3 style="color: blue">ESTE CONTRATO NO TIENE PRACTICAS CARGADAS</h3>
	<?php } ?>
</div>
</body>
</html>