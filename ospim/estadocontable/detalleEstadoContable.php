<?php
set_time_limit ( 0 );
ini_set ( 'memory_limit', '-1' );



$cuit = $_GET['cuit'];
$idControl = $_GET['id'];

//************************

try {
	
	$hostname = 'cronos';
	$dbname = 'madera';
	$usuario = 'sistemas';
	$pass = 'blam7326';
	$dbh = new PDO ( "mysql:host=$hostname;dbname=$dbname", $usuario, $pass );
	$dbh->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$dbh->beginTransaction ();
	
	
	$sqlControl = "SELECT * FROM estadocontablecontrol WHERE id = $idControl";
	$resControl = $dbh->query ( $sqlControl );
	$rowControl = $resControl->fetch ();
	
	$discoDesde = $rowControl ['discoinicio'];
	$discoHasta = $rowControl ['discofin'];
	$fechageneracion = $rowControl ['fechahasta'];
	$fechadesde = $rowControl ['fechadesde'];
	$anoDesde = substr ( $fechadesde, 0, 4 );
	$mesDesde = substr ( $fechadesde, 5, 2 );
	$anoHasta = substr ( $fechageneracion, 0, 4 );
	$mesHasta = substr ( $fechageneracion, 5, 2 );
	
	// OBTENEMOS LAS DDJJ
	// TODO rellenar todos los discos.
	$discoDesde = 100;
	$anolimite = 2009;
	$meslimite = 3;
	
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
				      pagos.fechaprocesoafip <= '" . $fechageneracion . "' AND
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
<title>.: Listado de Titulares por Empresa :.</title>

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
	<p><span class="Estilo2"> Empresa  "<?php echo $rowEmpresa['nombre'] ?>" - C.U.I.T.: <?php echo $cuit ?> </span></p>
	
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
					<th>Diferencia</th>
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
				$totalDife += $detalle['totpagos'] - $detalle['obligacion'];
				?>
					<tr align="center">
							<td width="50"><?php echo $anio;?></td>
							<td width="50"><?php echo $mes;?></td>
							<td width="190"><?php echo number_format($detalle['remuneracion'],2,',','')?></td>
							<td width="190"><?php echo number_format($detalle['obligacion'],2,',','') ?></td>
							<td width="190"><?php echo number_format($detalle['totpagos'],2,',','') ?></td>
							<td width="190"><?php echo number_format($detalle['totpagos'] - $detalle['obligacion'],2,',','') ?></td>
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
 <?php } else {
   		print("<p><span class='Estilo2'>No tiene empleados cargados en la nómina</span><p>");
    }?>
</div>
</body>
</html>

