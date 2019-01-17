<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
include($_SERVER['DOCUMENT_ROOT']."/madera/lib/fechas.php");
$codigo = $_GET['codigo'];
$sqlConsultaPresta = "SELECT p.*, l.nomlocali as localidad, r.descrip as provincia
						FROM prestadoresnm p
						LEFT JOIN localidades l on p.codlocali = l.codlocali
						LEFT JOIN provincia r on p.codprovin = r.codprovin
						WHERE p.codigo = $codigo";
$resConsultaPresta = mysql_query($sqlConsultaPresta,$db);
$rowConsultaPresta = mysql_fetch_assoc($resConsultaPresta);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Ficha Beneficiario Orden de pago :.</title>
<style type="text/css" media="print">
	.nover {display:none}
</style>
<link rel="stylesheet" href="/madera/lib/tablas.css"/>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<?php if (!isset($_GET['volver'])) { ?>
			<p><input type="button" name="volver" value="Volver" onclick="location.href = 'menuBeneficiario.php'" /></p>
	<?php } ?>
	<p><strong>Ficha Beneficiario</strong></p>
	<div class="grilla">
	  	<table>
		    <tr>
        		<td><b>Nombre</b></td>
        		<td colspan="3"><?php echo $rowConsultaPresta['nombre']?></td>
     	 	</tr>
     	 	<tr>
        		<td><b>Dirigido A</b></td>
        		<td colspan="3"><?php echo $rowConsultaPresta['dirigidoa']?></td>
     	 	</tr>
     		<tr>
        		<td><b>Domicilio</b></td>
        		<td colspan="3"><?php echo $rowConsultaPresta['domicilio']?></td>
      		</tr>
      		<tr>
        		<td><b>C.P.</b></td>
        		<td colspan="3">
        			<?php echo $rowConsultaPresta['indpostal']." ".$rowConsultaPresta['numpostal']." ".$rowConsultaPresta['alfapostal']?>
	   			</td>
	   		</tr>
	   		<tr>
        		<td><b>Localidad</b></td>
		        <td><?php echo $rowConsultaPresta['localidad']?></td>
        		<td><b>Provincia</b></td>
        		<td><?php echo $rowConsultaPresta['provincia']?> </td>
      		</tr>
      		<tr>
        		<td><b>Telefono</b></td>
        		<td><?php echo $rowConsultaPresta['telefono']?></td>
        		<td><b>Email </b></td>
        		<td><?php echo $rowConsultaPresta['email']?></td>
      		</tr>
    	</table>
    </div>
    <p><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" /></p>
</div>
</body>
</html>