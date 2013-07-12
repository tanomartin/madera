<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSession.php");
	
	$cuit=$_GET['cuit'];
	$codidelega=$_GET['coddel'];
	
	//TODO: ANTES DE ESTO TENGO QUE VER SI TIENE BENEFICIARIOS, SI LO TIENE NO LOS DEJO ELIMINAR

	$sql = "select * from empresas where cuit = $cuit";
	$result = mysql_query($sql,$db); 
	$row = mysql_fetch_array($result); 

	$sqljuris = "select * from jurisdiccion where cuit = $cuit and codidelega = $codidelega";
	$resjuris = mysql_query($sqljuris,$db); 
	$rowjuris = mysql_fetch_array($resjuris);
	
	$sqllocalidad = "select * from localidades where codlocali = $row[codlocali]";
	$resultlocalidad = mysql_query($sqllocalidad,$db); 
	$rowlocalidad = mysql_fetch_array($resultlocalidad); 
	
	$sqlprovi =  "select * from provincia where codprovin = $row[codprovin]";
	$resultprovi = mysql_query($sqlprovi,$db); 
	$rowprovi = mysql_fetch_array($resultprovi);
	
	
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
 <input type="reset" name="volver" value="Volver" onClick="location.href = 'empresa.php?origen=<?php echo $origen ?>&cuit=<?php echo $cuit ?>'" align="center"/> 
   <p>
     <?php include($_SERVER['DOCUMENT_ROOT']."/lib/cabeceraEmpresa.php"); ?>
  </p>
   <p><strong>Datos de la Jurisdicci&oacute;n a eliminar </strong></p>
  <table width="700" height="261" border="2">
      <tr bordercolor="#000000">
        <td width="200" height="22" bordercolor="#000000"><div align="right"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Delegaci&oacute;n:</font></strong></div></td>
        <td width="500" colspan="2"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
            <?php 
			$delega = $rowjuris['codidelega'];
			$sqldelegacion = "select * from delegaciones where codidelega = $delega";
			$resultdelegacion = mysql_query($sqldelegacion,$db); 
			$rowdelegacion = mysql_fetch_array($resultdelegacion); 
			echo $rowdelegacion['nombre']
		?>
        </font></div></td>
      </tr>
      
      <tr bordercolor="#000000">
        <td width="200" height="22" bordercolor="#000000"><div align="right"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Domicilio:</font></strong></div></td>
        <td width="500"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $rowjuris['domireal'];?></font></div></td>
      </tr>
      <tr bordercolor="#000000">
        <td width="200" height="22" bordercolor="#000000"><div align="right"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Localidad:</font></strong></div></td>
        <td width="500"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
            <?php 
			$locali =  $rowjuris['codlocali'];
			$sqllocalidad = "select * from localidades where codlocali = $locali";
			$resultlocalidad = mysql_query($sqllocalidad,$db); 
			$rowlocalidad = mysql_fetch_array($resultlocalidad); 
			echo $rowlocalidad['nomlocali'];
		?>
        </font></div></td>
      </tr>
      <tr bordercolor="#000000">
        <td width="200" height="22" bordercolor="#000000"><div align="right"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Provincia</font></strong></div></td>
        <td width="500"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
            <?php 	
			$provin = $rowjuris['codprovin'] ;
			$sqlprovi =  "select * from provincia where codprovin = $provin";
			$resultprovi = mysql_query($sqlprovi,$db); 
			$rowprovi = mysql_fetch_array($resultprovi);
			echo $rowprovi['descrip']; 
		?>
        </font></div></td>
      </tr>
      <tr bordercolor="#000000" >
        <td width="200" height="22" bordercolor="#000000"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>C&oacute;digo 
          Postal:</strong></font></div></td>
        <td width="500"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> <?php echo $rowjuris['indpostal'].$rowjuris['numpostal'].$rowjuris['alfapostal'];?></font></div></td>
      </tr>
      <tr bordercolor="#000000" >
        <td width="200" height="22" bordercolor="#000000"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Telefono:</strong></font></div></td>
        <td width="500"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
            <?php 
		if ($rowjuris['telefono'] == 0){
			echo "-";
		} else {
			echo "(".$rowjuris['ddn'].") - ".$rowjuris['telefono'];
		}
		?>
        </font></div></td>
      </tr>
      <tr bordercolor="#000000" >
        <td width="200" height="22" bordercolor="#000000"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Contacto Telefonico </strong></font></div></td>
        <td width="500"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
            <?php 
		if ($rowjuris['contactel']!= "") {
			echo $rowjuris['contactel'];
		} else {
			echo "-";
		}
		?>
        </font></div></td>
      </tr>
      <tr bordercolor="#000000" >
        <td width="200" height="22"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Email:</strong></font></div></td>
        <td width="500"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
            <?php
		if ($rowjuris['email']!= "") {
			echo $rowjuris['email'];
		} else {
			echo "-";
		}
		 ?>
        </font></div></td>
      </tr>
      <tr bordercolor="#000000" >
        <td width="200" height="22"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Disgregacion Dineraria:</strong></font></div></td>
        <td width="500"><div align="left"><?php echo $rowjuris['disgdinero']." %" ?></div></td>
      </tr>
  </table>
  <p>
    <input name="Input2" type="button" value="Confirmar Eliminacion - Reajustar Digregacion Dineraria" onClick="location.href='disgregaEliminaJurisdiccion.php?origen=<?php echo $origen ?>&amp;cuit=<?php echo $cuit ?>&coddel=<?php echo $codidelega ?> '"/>
  </p>
</div>
</body>
</html>
