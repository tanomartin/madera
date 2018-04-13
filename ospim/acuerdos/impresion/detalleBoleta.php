<?php  $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");

$nrocontrol = $_GET['nrocontrol'];
$estado = $_GET['estado'];
if ($estado == "Generada") { $sqlBoleta = "SELECT * from boletasospim b, empresas e where b.nrocontrol = $nrocontrol and b.cuit = e.cuit"  ; }
if ($estado == "Validada") { $sqlBoleta = "SELECT * from validasospim b, empresas e where b.nrocontrol = $nrocontrol and b.cuit = e.cuit"; }
if ($estado == "Anulada") { $sqlBoleta = "SELECT * from anuladasospim b, empresas e where b.nrocontrol = $nrocontrol and b.cuit = e.cuit"; }

$resBoleta = mysql_query($sqlBoleta,$db); 
$rowAMostrar = mysql_fetch_array($resBoleta);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Detalle Boleta :.</title>
</head>
<body bgcolor="#CCCCCC">
<div align="center">
    <p><b>DETALLE DE BOLETE CON NRO DE CONTROL <?php echo $nrocontrol ?></b></p>
    <table border="1" width="600" style="text-align: center">
    	<tr>
        	<td>C.U.I.T.</td>
        	<td><b><?php echo $rowAMostrar['cuit']; ?></b></td>
      	</tr>
      	<tr>
        	<td>Razón Social</td>
        	<td><b><?php echo $rowAMostrar['nombre']; ?></b></td>
      	</tr>
      	<tr>
        	<td>Nº Acuerdo</td>
        	<td><b><?php echo $rowAMostrar['nroacuerdo']; ?></b></td>
      	</tr>
      	<tr>
        	<td>Nº Cuota</td>
        	<td><b><?php echo $rowAMostrar['nrocuota']; ?></b></td>
      	</tr>
      	<tr>
       		<td>Importe</td>
        	<td><b><?php echo $rowAMostrar['importe']; ?></b></td>
      	</tr>
      	<tr>
        	<td>Identificacion Boleta</td>
        	<td><b><?php echo $rowAMostrar['nrocontrol']; ?></b></td>
      	</tr>
      	<tr>
       		<td>Usuario Emisión</td>
        	<td><b><?php echo $rowAMostrar['usuarioregistro']; ?></b></td>
      	</tr>
    </table>
<?php if ($estado == "Anulada") { ?>
		<p style='color:#000000'><b> INFORMACION DE ANULACION</b></p>
    	<table border="1" width="600" style="text-align: center">
      		<tr>
        		<td>Fecha Anulacion</td>
        		<td><b><?php echo $rowAMostrar['fechaanulacion']; ?></b></td>
      		</tr>
      		<tr>
        		<td>Docuemtancion en Mano</td>
       	 		<td>
       	 			<b>
	              <?php if($rowAMostrar['documentoenmano'] == 0) {
			   				echo "NO";
			   			} else {
			   				echo "SI";
			   			} ?>
            		</b>
            	</td>
      		</tr>
      		<tr>
        		<td>Motivo</td>
        		<td><b><?php if($rowAMostrar['motivoanulacion'] != "" ) {echo $rowAMostrar['motivoanulacion'];} else { echo "-"; } ?></b></td>
      		</tr>
    	</table>
<?php } ?>
    <p><input type="button" name="imprimir" value="Imprimir" onclick="window.print();" /></p>
</div>
</body>
</html>