<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
$codigo = $_GET['codigo'];
$sqlConsultaPresta = "SELECT p.*, l.nomlocali as localidad, r.descrip as provincia
						FROM prestadores p
						LEFT JOIN localidades l on p.codlocali = l.codlocali
						LEFT JOIN provincia r on p.codprovin = r.codprovin
						WHERE p.codigoprestador = $codigo and personeria = 5";
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
			<p><input class="nover" type="button" name="volver" value="Volver" onclick="location.href = 'menuPrestadores.php'" /></p>
	<?php } ?>
	<p><strong>Ficha Prestador No Médico</strong></p>
	<div class="grilla">
	  	<table>
		    <tr>
        		<td class="title">Nombre</td>
        		<td colspan="5"><?php echo $rowConsultaPresta['nombre']?></td>
     	 	</tr>
     	 	<tr>
        		<td class="title">C.U.I.T.</td>
        		<td colspan="5"><?php echo $rowConsultaPresta['cuit']?></td>
     	 	</tr>
     		<tr>
        		<td class="title">Domicilio</td>
        		<td colspan="5"><?php echo $rowConsultaPresta['domicilio']?></td>
      		</tr>
      		<tr>
        		<td class="title">C.P.</td>
        		<td>
        			<?php echo $rowConsultaPresta['indpostal']." ".$rowConsultaPresta['numpostal']." ".$rowConsultaPresta['alfapostal']?>
	   			</td>
	   			<td class="title">Localidad</td>
		        <td><?php echo $rowConsultaPresta['localidad']?></td>
        		<td class="title">Provincia</td>
        		<td><?php echo $rowConsultaPresta['provincia']?> </td>
	   		</tr>
	   		<tr>
        		
      		</tr>
      		<tr>
        		<td class="title">Telefono</td>
        		<td><?php echo $rowConsultaPresta['telefono1']?></td>
        		<td class="title">Telefono 2</td>
        		<td><?php echo $rowConsultaPresta['telefono2']?></td>
        		<td class="title">Tel. Fax</td>
        		<td><?php echo $rowConsultaPresta['telefonofax']?></td>
        	</tr>
        	<tr>
        		<td class="title">Email</td>
        		<td colspan="5"><?php echo $rowConsultaPresta['email1']?></td>
      		</tr>
      		<tr>
        		<td class="title">Email Sec.</td>
        		<td colspan="5"><?php echo $rowConsultaPresta['email2']?></td>
      		</tr>
    	</table>
    </div>
    <p><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" /></p>
</div>
</body>
</html>