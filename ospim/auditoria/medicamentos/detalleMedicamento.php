<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");

$codigo = $_GET['codigo'];

$sqlMedicamento = "SELECT 
						m.*, 
						a.descripcion as accion, 
						ma.descripcion as marca, 
						t.descripcion as tamano, 
						p.descripcion as pami, 
						tv.descripcion as venta
					FROM 
						medicamentos m, 
						mediextra e,
						mediaccion a, 
						meditamano t,
						meditipoventa tv,
						medicodigopami p, 
						medimarca ma
					WHERE
						m.codigo = $codigo and
						m.codigo = e.codigo and
						e.codigoaccion = a.codigo and
						m.codigomarca = ma.codigo and
						m.codigotipoventa = tv.codigo and
						m.codigoPAMI = p.codigo and
						m.codigotamano = t.codigo";
$resMedicamento = mysql_query($sqlMedicamento,$db);
$rowMedicamento = mysql_fetch_assoc($resMedicamento);

$sqlHistorico = "SELECT * FROM medipreciohistorico WHERE codigomedicamento = $codigo order by fechadesde DESC";
$resHistorico = mysql_query($sqlHistorico,$db);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Medicamentos +Info :.</title>

<link rel="stylesheet" href="/madera/lib/tablas.css"/>

<style type="text/css" media="print">
.nover {display:none}
</style>

</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<h3>Detalle de Medicamento</h3> 
	<h3><font color="blue"><?php echo $rowMedicamento['nombre']." [codigo: ".$rowMedicamento['codigo']."]" ?></font></h3>
	<div class="grilla">
		<table>
			<tr>
	        	<td class="title">Presentacion</td>
	        	<td><?php echo $rowMedicamento['presentacion']?></td>
				<td class="title">Prestación Activa</td>
				<td><?php if ($rowMedicamento['baja'] == 1) { echo "NO"; } else { echo "SI"; } ?> </td>	
				<td class="title">Troquel</td>
	        	<td><?php echo $rowMedicamento['troquel']?></td>
	        </tr>     
	        <tr>       	
				<td class="title">Laboratorio</td>
				<td><?php echo $rowMedicamento['laboratorio']?></td>
				<td class="title">Prod. Controlado</td>
	        	<td><?php echo $rowMedicamento['marca']?></td>
	        	<td class="title">Tipo Venta</td>
				<td><?php echo $rowMedicamento['venta']?></td>
	        </tr>
	         <tr>
	        	<td class="title">IOMA Monto</td>
	        	<td><?php echo number_format($rowMedicamento['IOMAMonto'],2,',','.') ?></td>
				<td class="title">IOMA Normatizado</td>
				<td><?php if ($rowMedicamento['IOMANorma'] == 'S') { echo "SI"; } else { echo "NO"; } ?></td>
				<td class="title">IOMA Internación</td>
	        	<td><?php if ($rowMedicamento['IOMAInterna'] == 'S') { echo "SI"; } else { echo "NO"; } ?></td>
	        </tr>
	        <tr>
	         	<td class="title">importado</td>
				<td><?php if ($rowMedicamento['importado'] == 1) { echo "SI"; } else { echo "NO"; } ?> </td>	
				<td class="title">I.V.A. a Farmacia</td>
				<td><?php if ($rowMedicamento['iva'] == 1) { echo "SI"; } else { echo "NO"; } ?> </td>
				<td class="title">SIFAR</td>
	        	<td><?php if ($rowMedicamento['SIFAR'] == 'S') { echo "SI"; } else { echo "NO"; } ?> </td>
	        </tr>
	         <tr>
	         	<td class="title">Desc. PAMI</td>
				<td><?php echo  $rowMedicamento['pami']?> </td>	
				<td class="title">Cod. Lab.</td>
				<td><?php echo $rowMedicamento['codigoLab']?></td>
				<td class="title">Cod. Barra</td>
				<td><?php echo $rowMedicamento['codbarra']?></td>
	        </tr>
	         <tr>
	         	<td class="title">Unidades</td>
				<td><?php if ($rowMedicamento['unidades'] == 1) { echo "No divisible"; } else { echo "Divisible"; } ?> </td>
				<td class="title">Tamaño</td>
				<td><?php echo $rowMedicamento['tamano']?></td>
				<td class="title">Heladera</td>
				<td><?php if ($rowMedicamento['heladera'] == 1) { echo "SI"; } else { echo "NO"; } ?> </td>	
	        </tr>
		</table>
	</div>
	<h3>Información Extra</h3> 
	<div class="grilla">
		<table>
			<tr>
				<td class="title">Accion Farmacologica</td>
				<td><?php echo $rowMedicamento['accion']?></td>
			</tr>
		</table>
	</div>
	
	<h3>Información histórica de Precios</h3> 
	<div class="grilla">
		<table>
			<thead>
				<tr>
	        		<th>Fecha</th>
					<th>Precio</th>
	        	</tr>
        	</thead>
        	<tbody>
        		<?php while ($rowHistorico = mysql_fetch_assoc($resHistorico)) { ?>
        		<tr>
        			<td><?php echo invertirFecha($rowHistorico['fechadesde'])?></td>
        			<td><?php echo number_format($rowHistorico['precio'],2,',','.')  ?></td>
        		</tr>
        		<?php } ?>
        	</tbody>
		</table>
	</div>
	<p><input type="button" class="nover" name="imprimir" value="Imprimir" onclick="window.print();" /></p>
</div>
</body>
</html>