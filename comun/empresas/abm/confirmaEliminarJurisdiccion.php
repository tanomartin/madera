<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSession.php");
	
	$cuit=$_GET['cuit'];
	$codidelega=$_GET['coddel'];

	$sqljuris = "select * from jurisdiccion where cuit = $cuit and codidelega = $codidelega";
	$resjuris = mysql_query($sqljuris,$db); 
	$rowjuris = mysql_fetch_array($resjuris);
	
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>
<title>.: Confirmacion Eliminacion Jurisdiccion :.</title>
</head>
<body bgcolor=<?php echo $bgcolor ?>>
<div align="center">
  <input type="reset" name="volver" value="Volver" onClick="location.href = 'empresa.php?origen=<?php echo $origen ?>&cuit=<?php echo $cuit ?>'"/>
  <p>
    <?php 
    	include($libPath."cabeceraEmpresaConsulta.php");
    	include($libPath."cabeceraEmpresa.php"); 
    ?>
  </p>
  <p><strong>Datos de la Jurisdicci&oacute;n a eliminar </strong></p>
  <table width="700" border="2" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:13px">
    <tr>
      <td><div align="right"><strong>Delegaci&oacute;n:</strong></div></td>
      <td colspan="2"><div align="left">
          <?php 
			$delega = $rowjuris['codidelega'];
			$sqldelegacion = "select * from delegaciones where codidelega = $delega";
			$resultdelegacion = mysql_query($sqldelegacion,$db); 
			$rowdelegacion = mysql_fetch_array($resultdelegacion); 
			echo $rowdelegacion['nombre']
		?>
          </div></td>
    </tr>
    <tr>
      <td><div align="right"><strong>Domicilio:</strong></div></td>
      <td><div align="left"><?php echo $rowjuris['domireal'];?></div></td>
    </tr>
    <tr>
      <td><div align="right"><strong>Localidad:</strong></div></td>
      <td><div align="left">
          <?php 
			$locali =  $rowjuris['codlocali'];
			$sqllocalidad = "select * from localidades where codlocali = $locali";
			$resultlocalidad = mysql_query($sqllocalidad,$db); 
			$rowlocalidad = mysql_fetch_array($resultlocalidad); 
			echo $rowlocalidad['nomlocali'];
		?>
          </div></td>
    </tr>
    <tr>
      <td><div align="right"><strong>Provincia</strong></div></td>
      <td><div align="left">
          <?php 	
			$provin = $rowjuris['codprovin'] ;
			$sqlprovi =  "select * from provincia where codprovin = $provin";
			$resultprovi = mysql_query($sqlprovi,$db); 
			$rowprovi = mysql_fetch_array($resultprovi);
			echo $rowprovi['descrip']; 
		?>
          </div></td>
    </tr>
    <tr>
      <td><div align="right"><strong>C&oacute;digo 
          Postal:</strong></div></td>
      <td><div align="left"> <?php echo $rowjuris['indpostal'].$rowjuris['numpostal'].$rowjuris['alfapostal'];?></div></td>
    </tr>
    <tr>
      <td><div align="right"><strong>Telefono:</strong></div></td>
      <td><div align="left">
          <?php 
		if ($rowjuris['telefono'] == 0){
			echo "-";
		} else {
			echo "(".$rowjuris['ddn'].") - ".$rowjuris['telefono'];
		}
		?>
          </div></td>
    </tr>
    <tr>
      <td><div align="right"><strong>Contacto Telefonico </strong></div></td>
      <td><div align="left">
          <?php 
		if ($rowjuris['contactel']!= "") {
			echo $rowjuris['contactel'];
		} else {
			echo "-";
		}
		?>
          </div></td>
    </tr>
    <tr>
      <td><div align="right"><strong>Email:</strong></div></td>
      <td><div align="left">
          <?php
		if ($rowjuris['email']!= "") {
			echo $rowjuris['email'];
		} else {
			echo "-";
		}
		 ?>
          </div></td>
    </tr>
    <tr>
      <td><div align="right"><strong>Disgregacion Dineraria:</strong></div></td>
      <td><div align="left"><?php echo $rowjuris['disgdinero']." %" ?></div></td>
    </tr>
  </table>
  <p>
    <input name="Input2" type="button" value="Confirmar Eliminacion - Reajustar Digregacion Dineraria" onClick="location.href='disgregaEliminaJurisdiccion.php?origen=<?php echo $origen ?>&amp;cuit=<?php echo $cuit ?>&coddel=<?php echo $codidelega ?> '"/>
  </p>
</div>
</body>
</html>
