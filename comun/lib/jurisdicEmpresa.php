<?php 
	$cuit = $row['cuit'];
	$sqljuris = "select * from jurisdiccion where cuit = $cuit";
	$resjuris = mysql_query($sqljuris,$db); 
	$canjuris = mysql_num_rows($resjuris); 
?>
 <p align="center"><strong>Datos de Jurisdicciones </strong></p>
  <div align="center">
    <p>
      <?php 
	if ($canjuris != 0) {
		while ($rowjuris = mysql_fetch_array($resjuris)) { ?>
    </p>
    <table width="53%" height="222" border="2">
      <tr bordercolor="#000000">
        <td width="34%" height="22" bordercolor="#000000"><div align="right"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Delegaci&oacute;n:</font></strong></div></td>
        <td bordercolor="#000000"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
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
        <td height="22" bordercolor="#000000"><div align="right"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Domicilio:</font></strong></div></td>
        <td bordercolor="#000000"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $rowjuris['domireal'];?></font></div></td>
      </tr>
      <tr bordercolor="#000000">
        <td height="22" bordercolor="#000000"><div align="right"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Localidad:</font></strong></div></td>
        <td bordercolor="#000000"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
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
        <td height="22" bordercolor="#000000"><div align="right"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Provincia</font></strong></div></td>
        <td bordercolor="#000000"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
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
        <td height="22" bordercolor="#000000"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>C&oacute;digo 
          Postal:</strong></font></div></td>
        <td bordercolor="#000000"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
		<?php echo $rowjuris['indpostal'].$rowjuris['numpostal'].$rowjuris['alfapostal'];?></font></div></td>
      </tr>
      <tr bordercolor="#000000" >
        <td height="22" bordercolor="#000000"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Telefono:</strong></font></div></td>
        <td bordercolor="#000000"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
		<?php 
		if ($rowjuris['telefono'] == 0){
			echo "-";
		} else {
			echo $rowjuris['telefono'];
		}
		?>
		</font></div></td>
      </tr>
      <tr bordercolor="#000000" >
        <td height="22" bordercolor="#000000"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Contacto Telefonico </strong></font></div></td>
        <td bordercolor="#000000"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
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
        <td height="22" bordercolor="#000000"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Email:</strong></font></div></td>
        <td bordercolor="#000000"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
		<?php
		if ($rowjuris['email']!= "") {
			echo$rowjuris['email'];
		} else {
			echo "-";
		}
		 ?></font></div></td>
      </tr>
      <tr bordercolor="#000000" >
        <td height="22" bordercolor="#000000"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Disgregacion Dineraria :</strong></font></div></td>
        <td bordercolor="#000000"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $rowjuris['disgdinero']." %" ?></font></div></td>
      </tr>
    </table>
	 <?php  } 
	  }
	  ?>
	
</div>
