<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");

if(isset($_GET['cuit'])) {
	$cuit = $_GET['cuit'];
} else {
	$cuit = $_POST['cuit'];
}

if(isset($_GET['id'])) {
	$idControl = $_GET['id'];
} else {
	$idControl = $_POST['id'];
}

try {
	
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$usuario = $_SESSION['usuario'];
	$pass = $_SESSION['clave'];
	$dbh = new PDO ( "mysql:host=$hostname;dbname=$dbname", $usuario, $pass );
	$dbh->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$dbh->beginTransaction ();
	
	$sqlEmpresa = "SELECT nombre FROM empresas WHERE cuit = $cuit";
	$resEmpresa = $dbh->query ( $sqlEmpresa );
	$canEmpresa = $resEmpresa->rowCount() ;
	if ($canEmpresa == 0) {
		header("Location: filtrosDetalleContable.php?err=1&cuit=$cuit");
		exit(0);
	} else {
		$rowEmpresa = $resEmpresa->fetch ();
	}
	
	
	$sqlControl = "SELECT * FROM estadocontablecontrol WHERE id = $idControl";
	$resControl = $dbh->query ( $sqlControl );
	$rowControl = $resControl->fetch ();
	
	$discoDesde = $rowControl ['discoinicio'];
	// TODO rellenar todos los discos.
	$discoDesde = 100;
	$discoHasta = $rowControl ['discofin'];
	$fechadesde = $rowControl ['fechadesde'];
	$fechahasta = $rowControl ['fechahasta'];
	$anoDesde = substr ( $fechadesde, 0, 4 );
	$mesDesde = substr ( $fechadesde, 5, 2 );
	$anoHasta = substr ( $fechahasta, 0, 4 );
	$mesHasta = substr ( $fechahasta, 5, 2 );
	
	// OBTENEMOS LAS DDJJ
	$anolimite = 2009;
	$meslimite = 3;
	
	//volver al archivo si corresponde
	$archivo_name = $rowControl ['patharchivo'];
	$arrayName = explode("/", $archivo_name);
	$archivo_name = array_pop($arrayName);
	$archivo_name = 'archivosHtm/'.$archivo_name;
	
	$detalleEstado = array ();
	if ($anoDesde < $anolimite or ($anolimite == $anoDesde and $meslimite < $mesDesde)) {
		$sqlDDJJPrimera = "SELECT
				  ddjj.anoddjj,
				  ddjj.mesddjj,
				  ddjj.secuenciapresentacion,
				  ROUND(SUM(ddjj.remundeclarada),2) AS totremune,
				  ROUND(SUM(IF(ddjj.remundeclarada < 1001, ddjj.remundeclarada * 0.081, ddjj.remundeclarada * 0.0765)),2) AS obligacion
				FROM
				      afipddjj ddjj
				WHERE
					  ddjj.cuit = " . $cuit . " AND
					  ddjj.nrodisco >= " . $discoDesde . " AND
				      ddjj.nrodisco <= " . $discoHasta . " AND
				      ((ddjj.anoddjj = " . $anoDesde . " AND ddjj.mesddjj > " . $mesDesde . ") OR 
				       (ddjj.anoddjj > " . $anoDesde . " AND ddjj.anoddjj < " . $anolimite . ") OR 
					   (ddjj.anoddjj = " . $anolimite . " AND ddjj.mesddjj < " . $meslimite . "))
				GROUP by ddjj.cuit, ddjj.anoddjj, ddjj.mesddjj, ddjj.secuenciapresentacion
				ORDER by ddjj.cuit, ddjj.anoddjj, ddjj.mesddjj, ddjj.secuenciapresentacion ASC";
		$resDDJJ = $dbh->prepare ( $sqlDDJJPrimera );
		$resDDJJ->execute ();
		
		while ( $rowDDJJ = $resDDJJ->fetch ( PDO::FETCH_LAZY ) ) {
			$indexPeriodo = $rowDDJJ ['anoddjj'] . $rowDDJJ ['mesddjj'];
			$detalleEstado [$indexPeriodo] = array (
					'remuneracion' => $rowDDJJ ['totremune'],
					'obligacion' => $rowDDJJ ['obligacion'],
					'nombre' => $rowDDJJ ['nombre'] 
			);
		}
		unset ( $resDDJJ );
		
		$sqlDDJJSegunda = "SELECT
				  ddjj.anoddjj,
				  ddjj.mesddjj,
				  ddjj.secuenciapresentacion,
				  ROUND(SUM(ddjj.remundeclarada),2) AS totremune,
				  ROUND(SUM(IF(ddjj.remundeclarada < 2401, ddjj.remundeclarada * 0.081, ddjj.remundeclarada * 0.0765)),2) AS obligacion
				FROM
				      afipddjj ddjj
				WHERE
					  ddjj.cuit = " . $cuit . " AND
				      ddjj.nrodisco >= " . $discoDesde . " AND
				      ddjj.nrodisco <= " . $discoHasta . " AND
				      ((ddjj.anoddjj > " . $anolimite . ") OR
	             	   (ddjj.anoddjj = " . $anolimite . " AND ddjj.mesddjj >= " . $meslimite . "))
				GROUP by ddjj.cuit, ddjj.anoddjj, ddjj.mesddjj, ddjj.secuenciapresentacion
				ORDER by ddjj.cuit, ddjj.anoddjj, ddjj.mesddjj, ddjj.secuenciapresentacion ASC";
		
		$resDDJJ = $dbh->prepare ( $sqlDDJJSegunda );
		$resDDJJ->execute ();
		while ( $rowDDJJ = $resDDJJ->fetch ( PDO::FETCH_LAZY ) ) {
			$indexPeriodo = $rowDDJJ ['anoddjj'] . $rowDDJJ ['mesddjj'];
			$detalleEstado [$indexPeriodo] = array (
					'remuneracion' => $rowDDJJ ['totremune'],
					'obligacion' => $rowDDJJ ['obligacion'] 
			);
		}
		unset ( $resDDJJ );
	} else {
		$sqlDDJJ = "SELECT
				  ddjj.anoddjj,
				  ddjj.mesddjj,
				  ddjj.secuenciapresentacion,
				  ROUND(SUM(ddjj.remundeclarada),2) AS totremune,
				  ROUND(SUM(IF(ddjj.remundeclarada < 2401, ddjj.remundeclarada * 0.081, ddjj.remundeclarada * 0.0765)),2) AS obligacion
				FROM
				      afipddjj ddjj
				WHERE
					  ddjj.cuit = " . $cuit . " AND
					  ddjj.nrodisco >= " . $discoDesde . " AND
				      ddjj.nrodisco <= " . $discoHasta . " AND
				      ((ddjj.anoddjj > " . $anoDesde . ") OR 
					   (ddjj.anoddjj = " . $anoDesde . " AND ddjj.mesddjj >= " . $mesDesde . "))
				GROUP by ddjj.cuit, ddjj.anoddjj, ddjj.mesddjj, ddjj.secuenciapresentacion
				ORDER by ddjj.cuit, ddjj.anoddjj, ddjj.mesddjj, ddjj.secuenciapresentacion ASC";
		
		$resDDJJ = $dbh->prepare ( $sqlDDJJ );
		$resDDJJ->execute ();
		
		while ( $rowDDJJ = $resDDJJ->fetch ( PDO::FETCH_LAZY ) ) {
			$indexPeriodo = $rowDDJJ ['anoddjj'] . $rowDDJJ ['mesddjj'];
			$detalleEstado [$indexPeriodo] = array (
					'remuneracion' => $rowDDJJ ['totremune'],
					'obligacion' => $rowDDJJ ['obligacion'] 
			);
		}
		unset ( $resDDJJ );
	}
	
	// OBTENEMOS LOS PAGOS
	$sqlPagos = "SELECT
				  pagos.cuit,
				  pagos.anopago,
				  pagos.mespago,
				  ROUND(SUM(IF(pagos.debitocredito = 'C', pagos.importe, pagos.importe * -1)),2) AS importepagos
				FROM
				      afiptransferencias pagos
				WHERE
					  pagos.cuit = " . $cuit . " AND
					  pagos.fechaprocesoafip >= '" . $fechadesde . "' AND
				      pagos.fechaprocesoafip <= '" . $fechahasta . "' AND
				      pagos.concepto != 'REM' AND
				      ((pagos.anopago = " . $anoDesde . " AND pagos.mespago > " . $mesDesde . ") OR (pagos.anopago > " . $anoDesde . "))
				GROUP by pagos.cuit, pagos.anopago, pagos.mespago
				ORDER by pagos.cuit, pagos.anopago ASC, pagos.mespago ASC";
	$resPagos = $dbh->prepare ( $sqlPagos );
	$resPagos->execute ();
	while ( $rowPagos = $resPagos->fetch ( PDO::FETCH_LAZY ) ) {
		$indexPeriodo = $rowPagos ['anopago'] . $rowPagos ['mespago'];
		if (array_key_exists ( $indexPeriodo, $detalleEstado )) {
			$detalleEstado [$indexPeriodo] += array (
					'totpagos' => $rowPagos ['importepagos'] 
			);
		} else {
			$detalleEstado [$indexPeriodo] = array (
					'totpagos' => $rowPagos ['importepagos'] 
			);
		}
	}
	unset ( $resPagos );
	
	
	// OBTENEMOS LOS ACUERDOS Y SE LOS SACO AL DETALLE
	$sqlAcuerdos = "SELECT
					 acuerdos.anoacuerdo,
					 acuerdos.mesacuerdo
					FROM
					      detacuerdosospim acuerdos
					WHERE
						acuerdos.cuit = " . $cuit . " AND
					   ((acuerdos.anoacuerdo > " . $anoDesde . ") OR (acuerdos.anoacuerdo = " . $anoDesde . " AND acuerdos.mesacuerdo >= " . $mesDesde . "))
					GROUP by acuerdos.cuit, acuerdos.anoacuerdo, acuerdos.mesacuerdo
				    ORDER by acuerdos.cuit, acuerdos.anoacuerdo, acuerdos.mesacuerdo";
	$resAcuerdos = $dbh->prepare ( $sqlAcuerdos );
	$resAcuerdos->execute ();
	while ( $rowAcuerdos = $resAcuerdos->fetch ( PDO::FETCH_LAZY ) ) {
		$indexPeriodo = $rowAcuerdos ['anoacuerdo'] . $rowAcuerdos ['mesacuerdo'];
		if (array_key_exists ( $indexPeriodo, $detalleEstado )) {
			unset($detalleEstado[$indexPeriodo]);
		}
	}
	
	// OBTENEMOS LOS JUICIOS Y SE LOS SACO AL DETALLE
	$sqlJuicios = "SELECT
					 det.anojuicio,
					 det.mesjuicio
					FROM
					      detjuiciosospim det, cabjuiciosospim cab
					WHERE
					   cab.cuit = ". $cuit ."  AND det.nroorden = cab.nroorden  AND
					   ((det.anojuicio > " . $anoDesde . ") OR (det.anojuicio = " . $anoDesde . " AND det.mesjuicio >= " . $mesDesde . "))				  
					GROUP by cab.cuit, det.anojuicio, det.mesjuicio
				    ORDER by cab.cuit, det.anojuicio, det.mesjuicio";
	
	$resJuicios = $dbh->prepare ( $sqlJuicios );
	$resJuicios->execute ();
	while ( $rowJuicios = $resJuicios->fetch ( PDO::FETCH_LAZY ) ) {
		$indexPeriodo = $rowJuicios ['anojuicio'] . $rowJuicios ['mesjuicio'];
		if (array_key_exists ( $indexPeriodo, $detalleEstado )) {
			unset($detalleEstado[$indexPeriodo]);
		}
	}
	
	$dbh->commit ();
} catch ( PDOException $e ) {
	echo $e->getMessage ();
	$dbh->rollback ();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Detalle Estado Contable :.</title>

<style>
A:link {
	text-decoration: none;
	color: #0033FF
}

A:visited {
	text-decoration: none
}

A:hover {
	text-decoration: none;
	color: #00FFFF
}

.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>
<style type="text/css" media="print">
.nover {
	display: none
}
</style>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<link rel="stylesheet"
	href="/madera/lib/jquery.tablesorter/themes/theme.blue.css" />
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script
	src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script
	src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script>
<script type="text/javascript">
$(function() {
	$("#listado")
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
	.tablesorterPager({container: $("#paginador")});
});

</script>
</head>

<body bgcolor="#CCCCCC">
	<div align="center">
	<?php if (isset($_POST['cuit'])) { ?><p><input class="nover" type="reset" name="volver" value="Volver" onclick="location.href = 'filtrosDetalleContable.php'" /></p> <?php } ?>
	<?php if (isset($_GET['cuit'])) { ?><p><input class="nover" type="reset" name="volver" value="Volver" onclick="location.href = '<?php echo $archivo_name ?>'" /></p> <?php } ?>
	<p><span class="Estilo2"> Empresa  "<?php echo $rowEmpresa['nombre'] ?>" - C.U.I.T.: <?php echo $cuit ?> </span></p>
	<p><span class="Estilo2"> Estado Contable  "<?php echo $rowControl['mes'] ?> - <?php echo  $rowControl['anio'] ?>" </span></p>
	
<?php if (sizeof($detalleEstado) > 0) { ?>

	<table class="tablesorter" id="listado"
		style="width: 900px; font-size: 16px">
			<thead>
				<tr>
					<th class="filter-select" data-placeholder="Seleccion Año">Año</th>
					<th class="filter-select" data-placeholder="Seleccion Mes">Mes</th>
					<th>Remuneración</th>
					<th>Obligación</th>
					<th>Pagos</th>
					<th>Debito/Credito</th>
				</tr>
			</thead>
			<tbody>
			<?php
			$totalRemune = 0;
			$totalObliga = 0;
			$totalPagos = 0;
			$totalDife = 0;
			foreach ( $detalleEstado as $periodo => $detalle ) {
				$anio = substr ( $periodo, 0, 4 );
				$mes = substr ( $periodo, 4, 2 );
				
				$totalRemune += $detalle['remuneracion'];
				$totalObliga += $detalle['obligacion'];
				$totalPagos += $detalle['totpagos'];
				$dife = $detalle['obligacion'] - $detalle['totpagos'];
				if ($dife < 50 && $dife > -50) {
					$dife = 0;
				}
				$totalDife += $dife;
				
				?>
					<tr align="center">
							<td width="50"><?php echo $anio;?></td>
							<td width="50"><?php echo $mes;?></td>
							<td width="190"><?php echo number_format($detalle['remuneracion'],2,',','')?></td>
							<td width="190"><?php echo number_format($detalle['obligacion'],2,',','') ?></td>
							<td width="190"><?php echo number_format($detalle['totpagos'],2,',','') ?></td>
							<td width="190"><?php echo number_format($dife,2,',','') ?></td>
			  </tr>
				<?php
			}
			?>
			</tbody>
	</table>
	<table bgcolor="#3399CC" style="width: 900px; font-size: 16px; border: 1px solid black" >
		<tbody>
			<tr align="center">
				<td colspan="2" width="100"><strong>TOTALES</strong></td>
				<td width="190"><strong><?php echo number_format($totalRemune,2,',','.') ?></strong></td>
				<td width="190"><strong><?php echo number_format($totalObliga,2,',','.') ?></strong></td>
				<td width="190"><strong><?php echo number_format($totalPagos,2,',','.') ?></strong></td>
				<td width="190"><strong><?php echo number_format($totalDife,2,',','.') ?></strong></td>
			</tr>
		</tbody>
	</table>
	<table width="245" border="0">
		<tr>
			<td width="239">
				<div id="paginador" class="pager">
					<form class="nover">
						<p align="center">
							<img src="../img/first.png" width="16" height="16" class="first" />
							<img src="../img/prev.png" width="16" height="16" class="prev" />
							<input name="text" type="text" class="pagedisplay" 
								style="background: #CCCCCC; text-align: center" size="8"
								readonly="readonly" /> 
								<img src="../img/next.png" width="16" height="16" class="next" /> 
								<img src="../img/last.png" width="16" height="16" class="last" /> 
								<select name="select" class="pagesize">
								<option selected="selected" value="10">10 por pagina</option>
								<option value="20">20 por pagina</option>
								<option value="30">30 por pagina</option>
								<option value="<?php echo sizeof($detalleEstado);?>">Todos</option>
							</select>
						</p>
					</form>
				</div>
			</td>
		</tr>
	</table>
	<p><input class="nover" type="button" name="imprimir" value="Imprimir"
				onclick="window.print();" align="right" /></p>
 <?php } else { ?>
   		<p><span class='Estilo2'><font color="red">No Existe Detalle de Estado Contable para esta Empresa</font></span></p>
 <?php  }?>
</div>
</body>
</html>

