<p align="center"><strong>Datos de Jurisdicciones </strong></p>
<?php 
	$cuit = $row['cuit'];
	$sqljuris = "select j.*, d.nombre as delegacion, p.descrip as provincia, l.nomlocali as localidad
				from jurisdiccion j, delegaciones d, provincia p, localidades l 
				where j.cuit = $cuit and j.codidelega = d.codidelega and j.codprovin = p.codprovin and j.codlocali = l.codlocali";
	$resjuris = mysql_query($sqljuris,$db); 
	$canjuris = mysql_num_rows($resjuris); 
?>
<div align="center">
    <p>
      <?php 
	if ($canjuris != 0) {
		while ($rowjuris = mysql_fetch_array($resjuris)) { ?>
    </p>
    <table width="700" height="157" border="2">
      <tr bordercolor="#000000">
        <td width="200" bordercolor="#000000"><div align="right"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Delegaci&oacute;n:</font></strong></div></td>
        <td width="482"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $rowjuris['delegacion'] ?></font></div></td>
      </tr>
      
      <tr bordercolor="#000000">
        <td height="22" bordercolor="#000000"><div align="right"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Domicilio:</font></strong></div></td>
        <td><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $rowjuris['domireal'];?></font></div></td>
      </tr>
      <tr bordercolor="#000000">
        <td height="22" bordercolor="#000000"><div align="right"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Localidad:</font></strong></div></td>
        <td><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $rowjuris['localidad'];?></font></div></td>
      </tr>
      <tr bordercolor="#000000">
        <td height="22" bordercolor="#000000"><div align="right"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Provincia</font></strong></div></td>
        <td><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $rowjuris['provincia']; ?></font></div></td>
      </tr>
      <tr bordercolor="#000000" >
        <td height="22" bordercolor="#000000"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>C&oacute;digo 
          Postal:</strong></font></div></td>
        <td><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> <?php echo $rowjuris['indpostal'].$rowjuris['numpostal'].$rowjuris['alfapostal'];?></font></div></td>
      </tr>
      <tr bordercolor="#000000" >
        <td height="22" bordercolor="#000000"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Telefono:</strong></font></div></td>
        <td><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
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
        <td height="22" bordercolor="#000000"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Contacto Telefonico </strong></font></div></td>
        <td><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
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
        <td height="22"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Email:</strong></font></div></td>
        <td><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
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
        <td height="22"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Disgregacion Dineraria:</strong></font></div></td>
        <td><div align="left"><?php echo $rowjuris['disgdinero']." %" ?></div></td>
      </tr>
  </table>
	 <p>
	   <?php  } 
	  }
	  ?>
  </p>
</div>
