<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."funcionespracticas.php");
$codigo = $_GET['codigo'];
$idcontrato = $_GET['idcontrato'];
$sqlConsultaPresta = "SELECT codigoprestador, nombre, personeria FROM prestadores WHERE codigoprestador = $codigo";
$resConsultaPresta = mysql_query($sqlConsultaPresta,$db);
$rowConsultaPresta = mysql_fetch_assoc($resConsultaPresta);
?>

<!DOCTYPE>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Contrato Prestador :.</title>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
}
-->
</style>
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
  <p><span style="text-align:center">
   <input type="button" name="volver" value="Volver" onclick="location.href = 'consultaContratosPrestador.php?codigo=<?php echo $codigo ?>'" />
  </span></p>
  <p><strong>Contrato Prestador</strong></p>
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
   <p><strong>Pr&aacute;cticas dentro del contrato </strong></p>
		<?php 
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
  									tipopracticas t, 
  									tipocomplejidad tc,
  									nomencladores n,
  									practicascategorias pc
  								WHERE 
  									c.codigoprestador = $codigo and 
  									c.idcontrato = $idcontrato and 
  									c.idcontrato = p.idcontrato and 
  									p.idpractica = pr.idpractica and 
  									pr.nomenclador = n.id and
  									pr.tipopractica = t.id and 
  									pr.codigocomplejidad = tc.codigocomplejidad and
  									p.idcategoria = pc.id";
  		$resPracticas = mysql_query($sqlPracticas,$db);
		$numPracticas = mysql_num_rows($resPracticas);
		if ($numPracticas > 0) {
 		 ?>
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
			  <th class="filter-select" data-placeholder="Seleccione Complejidad">Complejidad</th>
			  <th>Modulo Consultorio / Valor General ($)</th>
			  <th>Modulo Urgencia ($)</th>
			  <th>G. Honorarios ($)</th>
			  <th>G. Honorarios Especialista ($)</th>
			  <th>G. Honorarios Ayudante ($)</th>
			  <th>G. Honorarios Anestesista ($)</th>
			  <th>G. Gastos ($)</th>
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
				</tr>
         <?php } ?>
          </tbody>
        </table>
        <p> 
        	<?php } else { 	print("<div style='color:#000099'><b> ESTE CONTRATO NO TIENE PRACTICAS CARGADAS </b></div>"); } ?>
		</p>
</div>
</body>
</html>