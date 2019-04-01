<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
$nroafiliado = NULL;
$nroorden = NULL;
$estafiliado = NULL;
if(isset($_GET['nroAfi'])) {
	$nroafiliado=$_GET['nroAfi'];
	if(isset($_GET['nroOrd'])) {
		$nroorden=$_GET['nroOrd'];
		if(isset($_GET['estAfi'])) {
			$sqlDiabetes = "SELECT fechadiagnostico, edaddiagnostico FROM diabetesbeneficiarios WHERE nroafiliado = $nroafiliado and nroorden = $nroorden";
			$resDiabetes = mysql_query($sqlDiabetes,$db);
			$rowDiabetes = mysql_fetch_array($resDiabetes);
			
			$estafiliado=$_GET['estAfi'];
			if($nroorden == 0) {
				if(strcmp($estafiliado, 'A')==0) {
					$sqlLeeAfiliado = "SELECT nroafiliado, apellidoynombre, nrodocumento, cuil, fechanacimiento, YEAR(CURDATE())-YEAR(fechanacimiento)+IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(fechanacimiento,'%m-%d'), 0, -1) AS `edadactual` FROM titulares WHERE nroafiliado = $nroafiliado";
				}
				if(strcmp($estafiliado, 'I')==0) {
					$sqlLeeAfiliado = "SELECT nroafiliado, apellidoynombre, nrodocumento, cuil, fechabaja, fechanacimiento, YEAR(CURDATE())-YEAR(fechanacimiento)+IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(fechanacimiento,'%m-%d'), 0, -1) AS `edadactual` FROM titularesdebaja WHERE nroafiliado = $nroafiliado";
				}
			} else {
				if(strcmp($estafiliado, 'A')==0) {
					$sqlLeeAfiliado = "SELECT f.nroafiliado, f.nroorden, k.descrip, f.apellidoynombre, f.nrodocumento, f.cuil, f.fechanacimiento, YEAR(CURDATE())-YEAR(f.fechanacimiento)+IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(f.fechanacimiento,'%m-%d'), 0, -1) AS `edadactual` FROM familiares f, parentesco k WHERE f.nroafiliado = $nroafiliado AND f.nroorden = $nroorden AND f.tipoparentesco = k.codparent";
				}
				if(strcmp($estafiliado, 'I')==0) {
					$sqlLeeAfiliado = "SELECT f.nroafiliado, f.nroorden, k.descrip, f.apellidoynombre, f.nrodocumento, f.cuil, f.fechabaja, f.fechanacimiento, YEAR(CURDATE())-YEAR(f.fechanacimiento)+IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(f.fechanacimiento,'%m-%d'), 0, -1) AS `edadactual` FROM familiaresdebaja f, parentesco k WHERE f.nroafiliado = $nroafiliado AND f.nroorden = $nroorden AND f.tipoparentesco = k.codparent";
				}
			}
			$resLeeAfiliado = mysql_query($sqlLeeAfiliado,$db);
			$rowLeeAfiliado = mysql_fetch_array($resLeeAfiliado);

			if($nroorden == 0) {
				$tipoAfiliado = 'Titular';
			} else {
				$tipoAfiliado = 'Familiar '.$rowLeeAfiliado['descrip'];
			}

			if(strcmp($estafiliado, 'A')==0) {
				$estadoAfiliado = 'Activo';
			}
			if(strcmp($estafiliado, 'I')==0) {
				$estadoAfiliado = 'Inactivo desde '.invertirFecha($rowLeeAfiliado['fechabaja']);
			}
		}
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1" />
<title>.: Diabeticos :.</title>
<link rel="stylesheet" href="/madera/lib/style.css"/>
<link rel="stylesheet" href="/madera/lib/general.css" />
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css" type="text/css" id="" media="print, projection, screen" />
<link rel="stylesheet" href="/madera/lib/jquery-ui-1.9.2.custom/css/smoothness/jquery-ui-1.9.2.custom.css"/>
<script src="/madera/lib/jquery-ui-1.9.2.custom/js/jquery-1.8.3.js" type="text/javascript"></script>
<script src="/madera/lib/jquery-ui-1.9.2.custom/js/jquery-ui-1.9.2.custom.js" type="text/javascript"></script>
<script src="/madera/lib/jquery-ui-1.9.2.custom/js/ui.datepicker-es.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js" type="text/javascript"></script> 
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

$(document).ready(function(){
	$("#diagnosticos")
		.tablesorter({
			theme: 'blue',
			widthFixed: true, 
			widgets: ["zebra"],
			headers:{0:{sorter:false}, 7:{sorter:false}}
		})
		.tablesorterPager({
			container: $("#paginador")
		});
});

</script>
</head>
<body bgcolor="#CCCCCC">
	<div class="row" align="center" style="background-color: #CCCCCC;">
		<input class="style_boton4" type="button" name="volver" value="Volver" onclick="location.href = 'moduloDiabetes.php'" /> 
		<h2>Diagnosticos</h2>
		<?php include_once 'infoBeneficiario.php' ?>	
		<table id="diagnosticos" class="tablesorter" style="font-size:14px; text-align:center; width: 980px">
			<thead>
				<tr>
					<th colspan="8">Diagnosticos Existentes </th>
				</tr>
				<tr>
					<th>Fecha</th>
					<th>Diagnostico</th>
					<th>Comorbilidad</th>
					<th>Complicaciones</th>
					<th>Estudios</th>
					<th>Tratamiento</th>
					<th>Farmacos</th>
					<th>Insumos</th>
				</tr>
			</thead>
			<tbody>
		<?php
			$sqlListaDiagnosticos = "SELECT id, fechaficha FROM diabetesdiagnosticos WHERE nroafiliado = $nroafiliado AND nroorden = $nroorden ORDER BY fechaficha, id";
			$resListaDiagnosticos = mysql_query($sqlListaDiagnosticos,$db);
			while($rowListaDiagnosticos = mysql_fetch_array($resListaDiagnosticos)) { ?>
				<tr>
					<td><?php echo invertirFecha($rowListaDiagnosticos['fechaficha']) ?></td>
					<td><input class="nover" type="button" id="editadiagnostico" name="editadiagnostico" value="Editar" onclick="location.href = 'editarDiagnostico.php?idDiag=<?php echo $rowListaDiagnosticos['id']?>&nroAfi=<?php echo $nroafiliado?>&nroOrd=<?php echo $nroorden ?>&estAfi=<?php echo $estafiliado ?>'"/></td>
					<?php
						$sqlBuscaComorbilidad = "SELECT idDiagnostico FROM diabetescomorbilidad WHERE idDiagnostico = $rowListaDiagnosticos[id]";
						$resBuscaComorbilidad = mysql_query($sqlBuscaComorbilidad,$db);
					?>
					<td><?php if (mysql_num_rows($resBuscaComorbilidad)!=0) {?>
						<input class="nover" type="button" id="editacomorbilidad" name="editacomorbilidad" value="Editar" onclick="location.href = 'editarComorbilidad.php?idDiag=<?php echo $rowListaDiagnosticos['id']?>&nroAfi=<?php echo $nroafiliado?>&nroOrd=<?php echo $nroorden ?>&estAfi=<?php echo $estafiliado ?>'"/>
					<?php } else {?>
						<input class="nover" type="button" id="agregacomorbilidad" name="agregacomorbilidad" value="Agregar" onclick="location.href = 'agregarComorbilidad.php?idDiag=<?php echo $rowListaDiagnosticos['id']?>&nroAfi=<?php echo $nroafiliado?>&nroOrd=<?php echo $nroorden ?>&estAfi=<?php echo $estafiliado ?>'"/>
					<?php } ?>					</td>
					<?php
						$sqlBuscaComplicaciones = "SELECT idDiagnostico FROM diabetescomplicaciones WHERE idDiagnostico = $rowListaDiagnosticos[id]";
						$resBuscaComplicaciones = mysql_query($sqlBuscaComplicaciones,$db);
					?>
					<td><?php if (mysql_num_rows($resBuscaComplicaciones)!=0) {?>
						<input class="nover" type="button" id="editacomplicaciones" name="editacomplicaciones" value="Editar" onclick="location.href = 'editarComplicaciones.php?idDiag=<?php echo $rowListaDiagnosticos['id']?>&nroAfi=<?php echo $nroafiliado?>&nroOrd=<?php echo $nroorden ?>&estAfi=<?php echo $estafiliado ?>'"/>
					<?php } else {?>
						<input class="nover" type="button" id="agregacomplicaciones" name="agregacomplicaciones" value="Agregar" onclick="location.href = 'agregarComplicaciones.php?idDiag=<?php echo $rowListaDiagnosticos['id']?>&nroAfi=<?php echo $nroafiliado?>&nroOrd=<?php echo $nroorden ?>&estAfi=<?php echo $estafiliado ?>'"/>
					<?php } ?>					</td>
					<?php
						$sqlBuscaEstudios = "SELECT idDiagnostico FROM diabetesestudios WHERE idDiagnostico = $rowListaDiagnosticos[id]";
						$resBuscaEstudios = mysql_query($sqlBuscaEstudios,$db);
					?>
					<td><?php if (mysql_num_rows($resBuscaEstudios)!=0) {?>
						<input class="nover" type="button" id="editaestudios" name="editaestudios" value="Editar" onclick="location.href = 'editarEstudios.php?idDiag=<?php echo $rowListaDiagnosticos['id']?>&nroAfi=<?php echo $nroafiliado?>&nroOrd=<?php echo $nroorden ?>&estAfi=<?php echo $estafiliado ?>'"/>
					<?php } else {?>
						<input class="nover" type="button" id="agregaestudios" name="agregaestudios" value="Agregar" onclick="location.href = 'agregarEstudios.php?idDiag=<?php echo $rowListaDiagnosticos['id']?>&nroAfi=<?php echo $nroafiliado?>&nroOrd=<?php echo $nroorden ?>&estAfi=<?php echo $estafiliado ?>'"/>
					<?php } ?>					</td>
					<?php
						$sqlBuscaTratamiento = "SELECT idDiagnostico FROM diabetestratamientos WHERE idDiagnostico = $rowListaDiagnosticos[id]";
						$resBuscaTratamiento = mysql_query($sqlBuscaTratamiento,$db);
					?>
					<td><?php if (mysql_num_rows($resBuscaTratamiento)!=0) {?>
						<input class="nover" type="button" id="editatratamiento" name="editatratamiento" value="Editar" onclick="location.href = 'editarTratamiento.php?idDiag=<?php echo $rowListaDiagnosticos['id']?>&nroAfi=<?php echo $nroafiliado?>&nroOrd=<?php echo $nroorden ?>&estAfi=<?php echo $estafiliado ?>'"/>
					<?php } else {?>
						<input class="nover" type="button" id="agregatratamiento" name="agregatratamiento" value="Agregar" onclick="location.href = 'agregarTratamiento.php?idDiag=<?php echo $rowListaDiagnosticos['id']?>&nroAfi=<?php echo $nroafiliado?>&nroOrd=<?php echo $nroorden ?>&estAfi=<?php echo $estafiliado ?>'"/>
					<?php } ?>					</td>
					<?php
						$sqlBuscaFarmacos = "SELECT idDiagnostico FROM diabetesfarmacos WHERE idDiagnostico = $rowListaDiagnosticos[id]";
						$resBuscaFarmacos = mysql_query($sqlBuscaFarmacos,$db);
					?>
					<td><?php if (mysql_num_rows($resBuscaFarmacos)!=0) {?>
						<input class="nover" type="button" id="editafarmacos" name="editafarmacos" value="Editar" onclick="location.href = 'editarFarmacos.php?idDiag=<?php echo $rowListaDiagnosticos['id']?>&nroAfi=<?php echo $nroafiliado?>&nroOrd=<?php echo $nroorden ?>&estAfi=<?php echo $estafiliado ?>'"/>
					<?php } else {?>
						<input class="nover" type="button" id="agregafarmacos" name="agregafarmacos" value="Agregar" onclick="location.href = 'agregarFarmacos.php?idDiag=<?php echo $rowListaDiagnosticos['id']?>&nroAfi=<?php echo $nroafiliado?>&nroOrd=<?php echo $nroorden ?>&estAfi=<?php echo $estafiliado ?>'"/>
					<?php } ?>					</td>
					<?php
						$sqlBuscaInsumos = "SELECT idDiagnostico FROM diabetesinsumos WHERE idDiagnostico = $rowListaDiagnosticos[id]";
						$resBuscaInsumos = mysql_query($sqlBuscaInsumos,$db);
					?>
					<td><?php if (mysql_num_rows($resBuscaInsumos)!=0) {?>
						<input class="nover" type="button" id="editainsumos" name="editainsumos" value="Editar" onclick="location.href = 'editarInsumos.php?idDiag=<?php echo $rowListaDiagnosticos['id']?>&nroAfi=<?php echo $nroafiliado?>&nroOrd=<?php echo $nroorden ?>&estAfi=<?php echo $estafiliado ?>'"/>
					<?php } else {?>
						<input class="nover" type="button" id="agregainsumos" name="agregainsumos" value="Agregar" onclick="location.href = 'agregarInsumos.php?idDiag=<?php echo $rowListaDiagnosticos['id']?>&nroAfi=<?php echo $nroafiliado?>&nroOrd=<?php echo $nroorden ?>&estAfi=<?php echo $estafiliado ?>'"/>
					<?php } ?>					</td>
				</tr>
		<?php
		}
		?>
			</tbody>
		</table>
	</div>
	<div align="center">
		<p></p>
		<input class="nover" type="button" id="agregadiagnostico" name="agregadiagnostico" value="Agregar Nuevo Diagnostico" onclick="location.href = 'agregarDiagnostico.php?nroAfi=<?php echo $nroafiliado?>&nroOrd=<?php echo $nroorden ?>&estAfi=<?php echo $estafiliado ?>'"/>
	</div>
</body>
</html>