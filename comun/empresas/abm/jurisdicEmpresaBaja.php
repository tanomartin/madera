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
    <table width="700" border="2" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:13px">
      <tr>
        <td width="350"><div align="right"><strong>Delegaci&oacute;n:</strong></div></td>
        <td><div align="left"><?php echo $rowjuris['delegacion'] ?></div></td>
      </tr>
      
      <tr>
        <td><div align="right"><strong>Domicilio:</strong></div></td>
        <td><div align="left"><?php echo $rowjuris['domireal'];?></div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Localidad:</strong></div></td>
        <td><div align="left"><?php echo $rowjuris['localidad'];?></div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Provincia</strong></div></td>
        <td><div align="left"><?php echo $rowjuris['provincia']; ?></div></td>
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
	   <?php  } 
	  }
	  ?>
  </p>
</div>
