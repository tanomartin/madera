<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$id = $_GET['id'];
$nombre = $_GET['nombreEfe'];
$idEfector = $_GET['idEfector'];
$tipopresta = $_GET['tipopresta'];
if ($tipopresta != 6) {
	$sqlPretaciones = "SELECT p.*,f.*, DATE_FORMAT(f.fechapractica,'%d/%m/%Y') as fechapractica, p.codigopractica ";
	if ($tipopresta == 3) {
		$sqlPretaciones .= ",profesionales.nombre as efector ";
	}
	if ($tipopresta == 4) {
		$sqlPretaciones .= ",establecimientos.nombre as efector ";
	}
	$sqlPretaciones .=	"FROM practicas p, facturasprestaciones f
							    LEFT JOIN facturasintegracion ON facturasintegracion.idFacturaprestacion = f.id
								LEFT JOIN practicas ON practicas.idpractica = facturasintegracion.tipoescuela
								LEFT JOIN escuelas ON escuelas.id = facturasintegracion.idEscuela ";
	if ($tipopresta == 3) {
		$sqlPretaciones .= "LEFT JOIN profesionales ON profesionales.codigoprofesional = f.efectorpractica";
	}
	if ($tipopresta == 4) {
		$sqlPretaciones .= "LEFT JOIN establecimientos ON establecimientos.codigo = f.efectorpractica";
	}
	$sqlPretaciones .= " WHERE f.idFactura = $id and f.efectorpractica = $idEfector and f.idpractica = p.idpractica";
} else {
	$sqlPretaciones = "SELECT CONCAT(m.codigo,' ',m.nombre) as codigopractica, f.*, 
							  DATE_FORMAT(f.fechapractica,'%d/%m/%Y') as fechapractica,
							  establecimientos.nombre as efector
						FROM medicamentos m, facturasprestaciones f
						LEFT JOIN establecimientos ON establecimientos.codigo = f.efectorpractica
						WHERE f.idFactura = $id and f.efectorpractica = $idEfector and f.idpractica = m.codigo";
}
$resPretaciones = mysql_query($sqlPretaciones,$db);
$arrayPresta = array();
$whereIn = "(";
while ($rowPretaciones = mysql_fetch_assoc($resPretaciones)) {
	$arrayPresta[$rowPretaciones['idFacturabeneficiario']][$rowPretaciones['id']] = $rowPretaciones;
	$whereIn .= "'".$rowPretaciones['idFacturabeneficiario']."',";
}
$whereIn = substr($whereIn, 0, -1);
$whereIn .= ")";

$sqlBeneficiarios = "SELECT f.*,
							IF(titulares.apellidoynombre is NULL,titularesdebaja.apellidoynombre,titulares.apellidoynombre) as nombretitu,
							IF(familiares.apellidoynombre is NULL,familiaresdebaja.apellidoynombre,familiares.apellidoynombre) as nombrefami
						FROM facturasbeneficiarios f
						LEFT JOIN titulares ON f.tipoafiliado = 0 and titulares.nroafiliado = f.nroafiliado
						LEFT JOIN titularesdebaja ON f.tipoafiliado = 0 and titularesdebaja.nroafiliado = f.nroafiliado
						LEFT JOIN familiares ON f.tipoafiliado != 0 and familiares.nroafiliado = f.nroafiliado and familiares.nroorden = f.nroorden
						LEFT JOIN familiaresdebaja ON f.tipoafiliado != 0 and familiaresdebaja.nroafiliado = f.nroafiliado and familiaresdebaja.nroorden = f.nroorden
						WHERE id in $whereIn";
$resBeneficiarios = mysql_query($sqlBeneficiarios,$db);
$arrayBene = array();
while ($rowBeneficiarios = mysql_fetch_assoc($resBeneficiarios)) {
	$arrayBene[$rowBeneficiarios['id']] = $rowBeneficiarios;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Consulta Factura Liquidaciones Detalle Efector :.</title>
<link rel="stylesheet" href="/madera/lib/tablas.css"/>
<style type="text/css" media="print">
.nover {display:none}
</style>

</head>
<body bgcolor="#CCCCCC">
<div align="center">
	<h3>Detalle de Liquidacion por Efector</h3>
	<h3>Efector "<?php echo $nombre ?>"</h3>
	<div class="grilla" style="width: 90%; margin-top: 10px">			
		<table>
			<tr>
				<td class="title">Beneficiario</td>
				<td class="title">Practica</td>
				<td class="title">Fecha</td>
				<td class="title">Facturado</td>
				<td class="title">Debito</td>
				<td class="title">Credito</td>
			</tr>
			<?php $totFacEfe = 0;
				  $totDebEfe = 0; 
				  $totCreEfe = 0;
				  foreach ($arrayBene as $id => $bene) {
					$nombreBene = $bene['nombretitu'];
				 	if ($bene['tipoafiliado'] != 0) { $nombreBene = $bene['nombrefami']; } ?>
				  <?php foreach ($arrayPresta[$id] as $pretacion) { 
				  		$totFacEfe += $pretacion['totalfacturado'];
				  		$totDebEfe += $pretacion['totaldebito']; 
				  		$totCreEfe += $pretacion['totalcredito'];?>
						<tr>
							<td><?php echo $nombreBene." - ".$bene['nroafiliado']."/". $bene['nroorden'] ?></td>
							<td><?php echo $pretacion['codigopractica']; ?></td>
							<td><?php echo $pretacion['fechapractica']; ?></td>
							<td><?php echo $pretacion['totalfacturado']; ?></td>
							<td><?php echo $pretacion['totaldebito']; ?></td>
							<td><?php echo $pretacion['totalcredito']; ?></td>
						</tr>
			<?php	 	}
			 		} ?>
			<tr>
			  	<td colspan="3" class="title">TOTAL</td>
			  	<td class="title"><?php echo number_format($totFacEfe,2,",","."); ?></td>
			  	<td class="title"><?php echo number_format($totDebEfe,2,",","."); ?></td>
			  	<td class="title"><?php echo number_format($totCreEfe,2,",","."); ?></td>
			</tr>
		</table>
	</div>
	<p><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" /> </p>
</div>
</body>
</html>