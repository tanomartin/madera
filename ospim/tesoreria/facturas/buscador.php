<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
$liquidador = $_SESSION['usuario']; 
$cartelFiltro = "";

if (isset($_POST['filtro'])) {
	$filtro = $_POST['filtro'];
	$whereBusqueda = "";
	if (isset($_POST['filtroPaga'])) {
		$filtroBusqueda = $_POST['filtroPaga'];
		$valor = $_POST['valor'];
		if ($filtroBusqueda == "codigo") {
			$cartelFiltro = "Codigo de Prestador '".$valor."'";
			$whereBusqueda = "f.idPrestador = ".$valor." AND ";
		}
		if ($filtroBusqueda == "cuit") {
			$cartelFiltro = "CUIT de Prestador '".$valor."'";
			$whereBusqueda = "p.cuit = ".$valor." AND ";
		}
		if ($filtroBusqueda == "id") {
			$cartelFiltro = "ID Factura '".$valor."'";
			$whereBusqueda = "f.id = ".$valor." AND ";
		}
		if ($filtroBusqueda == "nro") {
			$cartelFiltro = "Factura Nro. '".$valor."'";
			$whereBusqueda = "f.nrocomprobante = ".$valor." AND ";
		}
	}
	
	if ($filtro == "I") {
		$cartel = "LISTADO DE FACTURAS INGRESADAS";

		$sqlFacturas = "SELECT f.*, p.nombre, p.cuit, DATE_FORMAT(f.fechacomprobante,'%d-%m-%Y') as fechacomprobante,
			t.descripcion as tipocomprobante
			FROM facturas f, prestadores p, tipocomprobante t
			WHERE
		 	f.usuarioliquidacion is null AND
			f.idPrestador = p.codigoprestador AND "
			.$whereBusqueda.	
			"f.idTipocomprobante = t.id
			ORDER BY f.id DESC";
		$resFacturas = mysql_query($sqlFacturas,$db);
		$numFacturas = mysql_num_rows($resFacturas);
	}
	
	if ($filtro == "P") {
		$cartel = "LISTADO DE FACTURAS EN PROCESO";
		
		$sqlFacturas = "SELECT f.*, p.nombre, p.cuit, DATE_FORMAT(f.fechacomprobante,'%d-%m-%Y') as fechacomprobante,
			t.descripcion as tipocomprobante
			FROM facturas f, prestadores p, tipocomprobante t
			WHERE
			f.usuarioliquidacion is not null AND	
			(f.importecomprobante - f.totaldebito) != f.totalpagado AND
			f.idPrestador = p.codigoprestador AND "
			.$whereBusqueda.
			"f.idTipocomprobante = t.id
			ORDER BY f.id DESC";
		$resFacturas = mysql_query($sqlFacturas,$db);
		$numFacturas = mysql_num_rows($resFacturas);
		
		$sqlFacutrasInte = "SELECT DISTINCT f.id, p.nombre, p.cuit, DATE_FORMAT(f.fechacomprobante,'%d-%m-%Y') as fechacomprobante
			FROM facturas f, facturasprestaciones pf, facturasintegracion fi, prestadores p
			WHERE
			f.usuarioliquidacion is not null AND
			(f.importecomprobante - f.totaldebito) != f.totalpagado AND
			f.idPrestador = p.codigoprestador AND "
			.$whereBusqueda.
			"f.id = pf.idFactura AND
			pf.id = fi.idFacturaprestacion
			ORDER BY f.id DESC";
		$resFacturasInte = mysql_query($sqlFacutrasInte,$db);
		$numFacturasInte = mysql_num_rows($resFacturasInte);
		$arrayInte = array();
		if ($numFacturasInte > 0) {
			while ($rowFacturaInte = mysql_fetch_assoc($resFacturasInte)) {
				$arrayInte[$rowFacturaInte['id']] = $rowFacturaInte['id'];
			}
		}
	}
	
	if ($filtro == "F") {
		$cartel = "LISTADO DE FACTURAS PAGADAS";
			
		$sqlFacturas = "SELECT f.*, p.nombre, p.cuit, DATE_FORMAT(f.fechacomprobante,'%d-%m-%Y') as fechacomprobante,
						t.descripcion as tipocomprobante
						FROM facturas f, prestadores p, tipocomprobante t
						WHERE
						(f.importecomprobante - f.totaldebito) = f.totalpagado AND
						f.idPrestador = p.codigoprestador AND "
						.$whereBusqueda.
						"f.idTipocomprobante = t.id
						ORDER BY f.id DESC";
		$resFacturas = mysql_query($sqlFacturas,$db);
		$numFacturas = mysql_num_rows($resFacturas);
		
		$sqlFacutrasInte = "SELECT DISTINCT f.id, p.nombre, p.cuit, DATE_FORMAT(f.fechacomprobante,'%d-%m-%Y') as fechacomprobante
							FROM facturas f, facturasprestaciones pf, facturasintegracion fi, prestadores p
							WHERE
							f.usuarioliquidacion = '$liquidador' AND
							(f.importecomprobante - f.totaldebito) = f.totalpagado AND
							f.id = pf.idFactura AND
							f.idPrestador = p.codigoprestador AND "
							.$whereBusqueda.
							"pf.id = fi.idFacturaprestacion
							ORDER BY f.id DESC";
		$resFacturasInte = mysql_query($sqlFacutrasInte,$db);
		$numFacturasInte = mysql_num_rows($resFacturasInte);
		$arrayInte = array();
		if ($numFacturasInte > 0) {
			while ($rowFacturaInte = mysql_fetch_assoc($resFacturasInte)) {
				$arrayInte[$rowFacturaInte['id']] = $rowFacturaInte['id'];
			}
		}
	}
} ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado Factura Liquidaciones :.</title>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">

$(function() {
	$("#listaFacturasUsuario")
	.tablesorter({
		theme: 'blue', 
		widthFixed: true, 
		widgets: ["zebra", "filter"], 
		headers:{5:{sorter:false, filter: false},
				 6:{sorter:false, filter: false},
				 7:{sorter:false, filter: false},
				 8:{sorter:false, filter: false},
				 9:{sorter:false, filter: false},
				 10:{sorter:false},
				 11:{sorter:false},
				 12:{sorter:false, filter: false}},
		widgetOptions : { 
			filter_cssFilter   : '',
			filter_childRows   : false,
			filter_hideFilters : false,
			filter_ignoreCase  : true,
			filter_searchDelay : 300,
			filter_startsWith  : false,
			filter_hideFilters : false,
		}
	}).tablesorterPager({container: $("#paginador")}); 
});

function abrirPop(dire){	
	window.open(dire,'Planilla De Debito','width=800, height=500,resizable=yes');
}

function validar(formulario) {
	if (formulario.filtro.value == "F") {
		console.log(esEnteroPositivo(formulario.valor.value));
		if (formulario.valor.value == "") {
			alert("El Dato de Busqueda es obligatorio");
			return false;
		} else {
			if (!esEnteroPositivo(formulario.valor.value)) {
				alert("El Dato de Busqueda debe ser un numero entero positivo");
				return false;
			}
		}
	} else {
		if (formulario.filtroPaga.checked) {
			if (formulario.valor.value == "") {
				alert("El Dato de Busqueda es obligatorio");
				return false;
			}
		}
	}
	$.blockUI({ message: "<h1>Generando listado de Facturas... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = 'menuFacturas.php'" /></p>
	<h2>Buscador de Facturas</h2>
	
	<form method="post" action="buscador.php" onsubmit="return validar(this)">
		<h3 style="color: blue">Seleccione Estado de Factura</h3>
		<table>
			<tr><td><b>INGRESADA</b></td><td><input type="radio" value="I" id="i" name="filtro" checked="checked"></input></td></tr>
			<tr><td><b>EN PROCESO</b></td><td><input type="radio" value="P" id="p" name="filtro"></input></td></tr>
			<tr><td><b>PAGADAS</b></td><td><input type="radio" value="F" id="f" name="filtro"></input></td></tr>
		</table>
		
		<table id="filtrosPagadas">
			<tr>
				<td colspan="2" align="center"><h3 style="color: blue;">Filtro de Facturas</h3>(Obligatorio para Fac. Pagadas)</td>
			</tr>
			<tr>
				<td><b>COD. PRES.</b></td>
				<td><input type="radio" value="codigo" id="codigo" name="filtroPaga"/></td>	
			</tr>
			<tr>
				<td><b>C.U.I.T.</b></td>
				<td><input type="radio" value="cuit" id="cuit" name="filtroPaga"/></td>
			</tr>
			<tr>
				<td><b>ID. FACTURA</b></td>
				<td><input type="radio" value="id" id="id" name="filtroPaga" /></td>
			</tr>
			<tr>
				<td><b>NRO. FACTURA</b></td>
				<td><input type="radio" value="nro" id="nro" name="filtroPaga"/></td>
			</tr>
			<tr>
				<td colspan="2"><input type="text" id="valor" name="valor" size="22"/></td>
			</tr>
		</table>
		<p><input type="submit" value="LISTAR"/></p>
	</form>
<?php 	if (isset($_POST['filtro'])) { ?>
			<h3><?php echo $cartel ?></h3>
			<h3><?php echo $cartelFiltro ?></h3>
	<?php	if ($numFacturas) {  ?>
			<table style="text-align:center; width:95%" id="listaFacturasUsuario">
				<thead>
					<tr>
						<th>Id</th>
						<th>Cod. Pres.</th>
						<th>C.U.I.T.</th>
						<th width="25%">Nombre</th>
						<th>Comprobante</th>
						<th>Fecha</th>
						<th>$ Importe</th>
						<th>$ Debito</th>
						<th>$ Liquidado</th>
						<th>$ Pagado</th>
						<th class="filter-select" data-placeholder="-">Liquiedador</th>
						<th class="filter-select" data-placeholder="-">Estado</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php while ($rowFacturas = mysql_fetch_assoc($resFacturas)) { ?>
					<tr>
						<td><?php echo $rowFacturas['id'] ?></td>
						<td><?php echo $rowFacturas['idPrestador'] ?></td>
						<td><?php echo $rowFacturas['cuit'] ?></td>
						<td><?php echo $rowFacturas['nombre'] ?></td>
						<td><?php echo $rowFacturas['tipocomprobante']."<br>".$rowFacturas['puntodeventa']."-".$rowFacturas['nrocomprobante'] ?></td>
						<td><?php echo $rowFacturas['fechacomprobante'] ?></td>
						<td><?php echo number_format($rowFacturas['importecomprobante'],2,',','.'); ?></td>
						<td><?php echo number_format($rowFacturas['totaldebito'],2,',','.'); ?></td>
						<td><?php echo number_format($rowFacturas['importeliquidado'],2,',','.'); ?></td>
						<td><?php echo number_format($rowFacturas['totalpagado'],2,',','.'); ?></td>
						<td><?php if ($rowFacturas['usuarioliquidacion'] != NULL) { echo $rowFacturas['usuarioliquidacion']; } else { echo "-"; } ?></td>
						<?php $estado = "INGRESADA";
							  if ($rowFacturas['usuarioliquidacion'] != NULL) {
							  	  $estado = "AUDITORIA";
								  if ($rowFacturas['fechacierreliquidacion'] != "0000-00-00 00:00:00") { 
									if (isset($arrayInte[$rowFacturas['id']])) {
										$estado = "INTEGRACION";
										if ($rowFacturas['restoapagar'] == 0) {
											$estado = "INTEGRACION - PAGA";
										}
									} else {
									  	if ($rowFacturas['autorizacionpago'] == 1) {
											if ($rowFacturas['restoapagar'] != 0) {
												if ($rowFacturas['restoapagar'] == $rowFacturas['importeliquidado']) {
													$estado = "PARA PAGAR";
												} else {
													$estado = "PAGO PARCIAL";
												}
											} else {
												$estado = "PAGADA";
											}
										} else {
											$estado = "ENVIAR A PAGAR";
										}
									}
								  } 
								} ?>
						<td><b><?php echo $estado ?></b></td>
						<td>
							<input type="button" value="Liquidacion" onclick="abrirPop('../../auditoria/liquidaciones/consultaLiquidacion.php?id=<?php echo $rowFacturas['id'] ?>&estado=<?php echo $estado ?>');" /></br>
							<?php if ($rowFacturas['restoapagar'] != $rowFacturas['importeliquidado'] && $rowFacturas['totaldebito'] != 0) { ?>
								<input type="button" value="Plan. Debito" style="margin-top: 5px"  onclick="abrirPop('../../auditoria/liquidaciones/docuDebito.php?id=<?php echo $rowFacturas['id'] ?>&doc=PL');"  />
								<input type="button" value="Nota Debito" style="margin-top: 5px"  onclick="abrirPop('../../auditoria/liquidaciones/docuDebito.php?id=<?php echo $rowFacturas['id'] ?>&doc=DEB');"  />
							<?php } ?>
						</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
			<div id="paginador" class="pager">
				<form>
					<p>
						<img src="../img/first.png" width="16" height="16" class="first"/>
						<img src="../img/prev.png" width="16" height="16" class="prev"/>
						<input type="text" class="pagedisplay" size="8" readonly="readonly" style="background:#CCCCCC; text-align:center"/>
						<img src="../img/next.png" width="16" height="16" class="next"/>
						<img src="../img/last.png" width="16" height="16" class="last"/>
					</p>
					<p>
						<select class="pagesize">
							<option selected="selected" value="10">10 por pagina</option>
							<option value="20">20 por pagina</option>
							<option value="30">30 por pagina</option>
							<option value="50">50 por pagina</option>
							<option value="<?php echo $numFacturas?>">Todos</option>
						</select>
					</p>
				</form>
			</div>
	<?php } else { ?>
			<h3 style="color: blue">No existen facturas para este Liquidador</h3>
	<?php }
		}?>
</div>
</body>
</html>
