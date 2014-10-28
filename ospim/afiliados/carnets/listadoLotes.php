<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
$usuariolote = $_SESSION['usuario'];
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo4 {
	font-size: 18px;
	font-weight: bold;
}
</style>
<style type="text/css" media="print">
.nover {display:none}
</style>
<title>.: Lotes de Impresion :.</title>
<link rel="stylesheet" href="/lib/jquery.tablesorter/themes/theme.blue.css" type="text/css" id="" media="print, projection, screen" />
<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/jquery-ui.min.js" type="text/javascript"></script>
<script src="/lib/jquery.blockUI.js" type="text/javascript"></script>
<script src="/lib/jquery.tablesorter/jquery.tablesorter.js" type="text/javascript"></script>
<script src="/lib/jquery.tablesorter/jquery.tablesorter.widgets.js" type="text/javascript"></script>
<script type="text/javascript" src="/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script language="javascript" type="text/javascript">
$(document).ready(function(){
	$("#listado")
		.tablesorter({
			theme: 'blue',
			widthFixed: true, 
			widgets: ["zebra"],
			headers:{1:{sorter:false}, 3:{sorter:false}, 4:{sorter:false}, 5:{sorter:false}, 7:{sorter:false}, 9:{sorter:false}, 10:{sorter:false}, 11:{sorter:false}, 12:{sorter:false}, 13:{sorter:false}, 14:{sorter:false}}
		})
		.tablesorterPager({container: $("#paginador")});
});
</script>
</head>
<body bgcolor="#CCCCCC" >
<div align="center">
	<p><input class="nover" type="reset" name="volver" value="Volver" onClick="location.href = 'moduloImpresion.php'"/></p>
	<h2>Lotes de Impresi&oacute;n</h2>
</div>
<div align="center" id="tablaLotes">
	<table class="tablesorter" id="listado" style="text-align:center">
		<thead>
			<tr>
				<th rowspan="2" scope="col">ID de Lote</th>
				<th colspan="2" scope="col">Generado</th>
				<th rowspan="2" scope="col">Delegacion</th>
				<th colspan="5" scope="col">Datos de Carnets</th>
				<th rowspan="2" scope="col">Listado</th>
				<th rowspan="2" scope="col">Nota</th>
				<th rowspan="2" scope="col">Status</th>
				<th rowspan="2" scope="col" class="nover">Accion</th>
			</tr>
			<tr>
				<th>Fecha</th>
				<th>Hora</th>
				<th>Titulares</th>
				<th>Regulares [Azul]</th>
				<th>Solo OSPIM [Bordo]</th>
				<th>Opcion [Rojo]</th>
				<th>USIMRA [Verde]</th>
			</tr>
	 	</thead>
		<tbody>
<?php 
		$sqlLeeLotes="SELECT * FROM impresioncarnets WHERE usuarioemision = '$usuariolote' ORDER BY lote DESC";
		$resLeeLotes=mysql_query($sqlLeeLotes,$db);
		$canLotes=mysql_num_rows($resLeeLotes);	
		while($rowLeeLotes=mysql_fetch_assoc($resLeeLotes)) {
			if($rowLeeLotes['marcacierreimpresion']==0) {
				$statuslote="Abierto";
			} else {
				$statuslote="Cerrado";
			}

			$carnetazul=$rowLeeLotes['totalcarnetsazul'];
			if($rowLeeLotes['totalcarnetsazul']!=0) {
				if($rowLeeLotes['marcaimpresionazul']==0) {
					$carnetazul.=" Sin Imprimir";
				} else {
					if($rowLeeLotes['totalcarnetsazul']==1) {
						$carnetazul.=" Impreso";
					} else {
						$carnetazul.=" Impresos";
					}
				}
			}

			$carnetbordo=$rowLeeLotes['totalcarnetsbordo'];
			if($rowLeeLotes['totalcarnetsbordo']!=0) {
				if($rowLeeLotes['marcaimpresionbordo']==0) {
					$carnetbordo.=" Sin Imprimir";
				} else {
					if($rowLeeLotes['totalcarnetsbordo']==1) {
						$carnetbordo.=" Impreso";
					} else {
						$carnetbordo.=" Impresos";
					}
				}
			}

			$carnetrojo=$rowLeeLotes['totalcarnetsrojo'];
			if($rowLeeLotes['totalcarnetsrojo']!=0) {
				if($rowLeeLotes['marcaimpresionrojo']==0) {
					$carnetrojo.=" Sin Imprimir";
				} else {
					if($rowLeeLotes['totalcarnetsrojo']==1) {
						$carnetrojo.=" Impreso";
					} else {
						$carnetrojo.=" Impresos";
					}
				}
			}

			$carnetverde=$rowLeeLotes['totalcarnetsverde'];
			if($rowLeeLotes['totalcarnetsverde']!=0) {
				if($rowLeeLotes['marcaimpresionverde']==0) {
					$carnetverde.=" Sin Imprimir";
				} else {
					if($rowLeeLotes['totalcarnetsverde']==1) {
						$carnetverde.=" Impreso";
					} else {
						$carnetverde.=" Impresos";
					}
				}
			}
			if($rowLeeLotes['marcaimpresionlistado']==0) {
				$listado="Sin Imprimir";
			} else {
				$listado="Impreso";
			}
			if($rowLeeLotes['marcaimpresionnota']==0) {
				$nota="Sin Imprimir";
			} else {
				$nota="Impresa";
			}
?>
			<tr>
				<td><?php echo $rowLeeLotes['lote'];?></td>
				<td><?php echo invertirFecha(substr($rowLeeLotes['fechaemision'],0,10));?></td>
				<td><?php echo substr($rowLeeLotes['fechaemision'],11,5);?></td>
				<td><?php echo $rowLeeLotes['codidelega'];?></td>
				<td><?php echo $rowLeeLotes['totaltitulares'];?></td>
				<td><?php echo $carnetazul;?></td>
				<td><?php echo $carnetbordo;?></td>
				<td><?php echo $carnetrojo;?></td>
				<td><?php echo $carnetverde;?></td>
				<td><?php echo $listado;?></td>
				<td><?php echo $nota;?></td>
				<td><?php echo $statuslote;?></td>
				<td class="nover"><input type="button" name="iralote" id="iralote" value="Ir a Lote" onClick="location.href = 'impresionLotes.php?nroLote=<?php echo $rowLeeLotes['lote']?>'"/></td>
			</tr>
<?php 
		}
?>
		</tbody>
	</table> 
</div>
<table class="nover" align="center" width="245" border="0">
	<tr>
		<td width="239">
			<div id="paginador" class="pager">
				<form>
					<p align="center">
					<img src="../img/first.png" width="16" height="16" class="first"/> <img src="../img/prev.png" width="16" height="16" class="prev"/>
					<input name="text" type="text" class="pagedisplay" style="background:#CCCCCC; text-align:center" size="8" readonly="readonly"/>
					<img src="../img/next.png" width="16" height="16" class="next"/> <img src="../img/last.png" width="16" height="16" class="last"/>
					<select name="select" class="pagesize">
						<option selected="selected" value="10">10 por pagina</option>
						<option value="20">20 por pagina</option>
						<option value="30">30 por pagina</option>
						<option value="60">60 por pagina</option>
						<option value="<?php echo $canLotes;?>">Todos</option>
						</select>
					</p>
					<p align="center"><input class="nover" type="button" name="imprimir" value="Imprimir" onClick="window.print();"/></p>
				</form>	
			</div>
		</td>
	</tr>
</table>
</body>
</html>