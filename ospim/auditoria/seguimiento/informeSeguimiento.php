<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
$arrayResultado = array();

if (isset($_POST['seleccion'])) {
	$i = 0;
	$seleccion = $_POST['seleccion'];
	
	$arraySeguimiento = array();
	$arrayAfiliados = array();
	
	$sqlSeguimiento = "SELECT e.id, e.idseguimiento, e.estado,  s.nroafiliado, s.nroorden, p.descrip as parentesco, DATE_FORMAT(e.fecharegistro,'%d-%m-%Y %H:%i:%s') as fecharegistro 
						FROM seguimientoestado e, seguimiento s, parentesco p
						WHERE e.idseguimiento = s.id and s.nroorden = p.codparent order by e.fecharegistro, e.idseguimiento, e.id ASC";
	$resSeguimiento = mysql_query($sqlSeguimiento,$db);
	while ($rowSeguimiento = mysql_fetch_assoc($resSeguimiento)) {
		$arraySeguimiento[$rowSeguimiento['idseguimiento']] = $rowSeguimiento;
	}

	foreach($arraySeguimiento as $key => $segui) {		
		if ($segui['estado'] == $seleccion) {	
			$arrayResultado[$key] = $segui;
			$nroafiliado = $segui['nroafiliado'];
			if ($segui['nroorden'] == 0) {
				$selectTitular = "SELECT t.*, d.descrip as tipdoc, del.nombre as delegacion FROM titulares t, tipodocumento d, delegaciones del WHERE t.nroafiliado = $nroafiliado and t.tipodocumento = d.codtipdoc and t.codidelega = del.codidelega";
				$resTitular = mysql_query($selectTitular,$db);
				$numTitular = mysql_num_rows($resTitular);
				if ($numTitular > 0) {
					$arrayResultado[$key]['estadoafiliado'] = "ACTIVO";
					while ($rowTitular = mysql_fetch_assoc($resTitular)) {
						$arrayAfiliados[$key] = $rowTitular;
					}
				} else {
					$selectTitular = "SELECT t.*, d.descrip as tipdoc, del.nombre as delegacion FROM titularesdebaja t, tipodocumento d, delegaciones del WHERE t.nroafiliado = $nroafiliado and t.tipodocumento = d.codtipdoc and t.codidelega = del.codidelega";
					$resTitular = mysql_query($selectTitular,$db);
					$numTitular = mysql_num_rows($resTitular);
					if ($numTitular > 0) {
						$arrayResultado[$key]['estadoafiliado'] = "INACTIVO";
						while ($rowTitular = mysql_fetch_assoc($resTitular)) {
							$arrayAfiliados[$key] = $rowTitular;
						}
					}
				}
			} else {
				$nroorden = $segui['nroorden'];
				$selectFamiliar = "SELECT f.*, p.descrip as parentesco, d.descrip as tipdoc, del.nombre as delegacion FROM familiares f, parentesco p, tipodocumento d, titulares t, delegaciones del
				WHERE f.nroafiliado = $nroafiliado and f.nroorden = $nroorden and f.tipoparentesco = p.codparent and f.tipodocumento = d.codtipdoc and f.nroafiliado = t.nroafiliado and t.codidelega = del.codidelega";
				$resFamiliar = mysql_query($selectFamiliar,$db);
				$numFamiliar = mysql_num_rows($resFamiliar);
				if ($numFamiliar > 0) {
					$arrayResultado[$key]['estadoafiliado'] = "ACTIVO";
					while ($rowFamiliar = mysql_fetch_assoc($resFamiliar)) {
						$arrayAfiliados[$key] = $rowFamiliar;
					}
				} else {
					$selectFamiliar = "SELECT f.*, p.descrip as parentesco, d.descrip as tipdoc, del.nombre as delegacion FROM familiaresdebaja f, parentesco p, tipodocumento d, titularesdebaja t, delegaciones del
					WHERE f.nroafiliado = $nroafiliado and f.nroorden = $nroorden and f.tipoparentesco = p.codparent and f.tipodocumento = d.codtipdoc and f.nroafiliado = t.nroafiliado and t.codidelega = del.codidelega";
					$resFamiliar = mysql_query($selectFamiliar,$db);
					$numFamiliar = mysql_num_rows($resFamiliar);
					if ($numFamiliar > 0) {
						$arrayResultado[$key]['estadoafiliado'] = "INACTIVO";
						while ($rowFamiliar = mysql_fetch_assoc($resFamiliar)) {
							$arrayAfiliados[$key] = $rowFamiliar;
						}
					} else {
						$selectFamiliar = "SELECT f.*, p.descrip as parentesco, d.descrip as tipdoc, del.nombre as delegacion FROM familiaresdebaja f, parentesco p, tipodocumento d, titulares t, delegaciones del
						WHERE f.nroafiliado = $nroafiliado and f.nroorden = $nroorden and f.tipoparentesco = p.codparent and f.tipodocumento = d.codtipdoc and f.nroafiliado = t.nroafiliado and t.codidelega = del.codidelega";
						$resFamiliar = mysql_query($selectFamiliar,$db);
						$numFamiliar = mysql_num_rows($resFamiliar);
						if ($numFamiliar > 0) {
							$arrayResultado[$key]['estadoafiliado'] = "INACTIVO";
							while ($rowFamiliar = mysql_fetch_assoc($resFamiliar)) {
								$arrayAfiliados[$key] = $rowFamiliar;
							}
						}
					}
				}
			}
		}
	}
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Informe Seguimiento :.</title>

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery-ui.min.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>

<link rel="stylesheet" href="/madera/lib/tablas.css"/>

<script language="javascript" type="text/javascript">

function validar(formulario) {
	formulario.buscar.disabled = true;
	var elementos = document.forms.moduloABM.elements;
	var longitud = document.forms.moduloABM.length;
	var elementoradio = 0;
	for(var i=0; i<longitud; i++) {
		if(elementos[i].name == "seleccion" && elementos[i].type == "radio" && elementos[i].checked == true) {
			elementoradio=i;
		}
	}
	$.blockUI({ message: "<h1>Realizando informe. Aguarde por favor...</h1>" });
	return true;
}

function abrirSeguimiento(dire) {
	a= window.open(dire,"Seguimiento del Afiliado",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=1100, height=500, top=10, left=10");
}


</script>
<style type="text/css" media="print">
.nover {display:none}
</style>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<form id="moduloABM" name="moduloABM" method="post"  onsubmit="return validar(this)" action="informeSeguimiento.php">
		<p><input class="nover" type="button" name="volver" value="Volver" onclick="location.href = 'menuSeguimiento.php'" /></p>
		<h3>Listado de de Seguimiento segun Estado</h3> 
	    <p> <?php 
		    	if (isset($_POST['seleccion']) && sizeof($arrayResultado ) == 0) { ?>
					<b style='color:#FF0000'> LA BUSQUEDA POR EL ESTADO '<?php echo $seleccion ?>' NO GENERO RESULTADOS </b>
	      <?php } ?>
		</p>
		<table>
			<tr>
				<td><input name="seleccion" type="radio" value="EN GESTION"  checked="checked"/></td>
				<td><div align="left">EN GESTION</div></td>
			</tr>
			<tr>
				<td width="23"><input name="seleccion" type="radio" value="PENDIENTE"/></td>
				<td width="104"><div align="left">PENDIENTE</div></td>
			</tr>
			<tr>
				<td><input name="seleccion" type="radio" value="FINALIZADO" /></td>
				<td><div align="left">FINALIZADO</div></td>
			</tr>
		</table>
		<p><input class="nover" type="submit" name="buscar" value="Buscar" /></p>
	</form>
<?php if (sizeof($arrayResultado ) > 0) { ?>
		<p><b style='color:blue'> Resultados de la busqueda por estado '<?php echo $seleccion?>' </b></p>
		<div class="grilla">
			<table style="width: 1100px">
				<thead>
					<tr>
						<th>Id</th>
						<th width="80px">Fecha</th>
						<th>Nro Afiliado</th>
						<th>Tipo Afiliado</th>
						<th>Estado</th>
						<th>Nombre y Apellido</th>
						<th>Nro Documento</th>
						<th>C.U.I.L.</th>
						<th>Delegacion</th>	
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($arrayResultado as $key => $resultado) { ?>
					<tr>
						<td><?php echo $key?></td>
						<td><?php echo $resultado['fecharegistro']?></td>
						<td><?php echo $resultado['nroafiliado'] ?></td>
						<td><?php $tipo = "TITULAR";
								  if (($resultado['nroorden']) != 0) { 
										$tipo = "FAMILIAR - ".$resultado['parentesco']; 
								  } 
								  echo $tipo; ?>
						</td>	
						<?php 
							$color = "";
							if ($resultado['estadoafiliado'] == "INACTIVO") { $color = "red";}  ?>
						<td style="color: <?php echo $color ?>"><?php echo $resultado['estadoafiliado']?></td>
						<?php if (isset($arrayAfiliados[$key])) { ?>
								<td><?php echo $arrayAfiliados[$key]['apellidoynombre'] ?></td>	
								<td><?php echo $arrayAfiliados[$key]['tipdoc'].": ".$arrayAfiliados[$key]['nrodocumento'] ?></td>
								<td><?php echo $arrayAfiliados[$key]['cuil'] ?></td>	
								<td><?php echo $arrayAfiliados[$key]['delegacion'] ?></td>	
								<td>
									<input type="button" name="ver" id="ver" value="+INFO" onclick="javascript:abrirSeguimiento('seguimientoDetalle.php?id=<?php echo $resultado['idseguimiento'] ?>&nombre=<?php echo $arrayAfiliados[$key]['apellidoynombre'] ?>&delega=<?php echo $arrayAfiliados[$key]['delegacion'] ?>&volver=none')" />
									<?php if ($seleccion != "FINALIZADO" && $resultado['estadoafiliado'] == "ACTIVO") { ?><input type="button" name="ver" id="ver" value="Modificar" onclick="javascript:abrirSeguimiento('seguimientoModificar.php?id=<?php echo $resultado['idseguimiento'] ?>&nombre=<?php echo $arrayAfiliados[$key]['apellidoynombre'] ?>&delega=<?php echo $arrayAfiliados[$key]['delegacion']  ?>')" /> <?php } ?>	
								</td>
						<?php  } else { ?>
								<td colspan="5">NO SE ENCONTRO AFILIADO</td>
						<?php  } ?>																			
					</tr>
		    	<?php } ?>
				</tbody>
			</table>
		</div>
<?php } ?>
</div>
</body>
</html>