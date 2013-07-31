<p align="center"><strong>Datos de Jurisdicciones </strong></p>
<?php 
	$cuit = $row['cuit'];
	$sqljuris = "select * from jurisdiccion where cuit = $cuit";
	$resjuris = mysql_query($sqljuris,$db); 
	$canjuris = mysql_num_rows($resjuris); 
	
	if ($canjuris != 0) {
		while ($rowjuris = mysql_fetch_array($resjuris)) { 
			$delega = $rowjuris['codidelega'];
			$sqldelegacion = "select * from delegaciones where codidelega = $delega";
			$resultdelegacion = mysql_query($sqldelegacion,$db); 
			$rowdelegacion = mysql_fetch_array($resultdelegacion); 
			
			$sqltitu = "select * from titulares where cuitempresa = $cuit and codidelega = $delega";
			$restitu = mysql_query($sqltitu,$db); 
			$cantitu = mysql_num_rows($restitu); 
?>
<div align="center">
    <table width="700" height="157" border="2">
      <tr bordercolor="#000000">
        <td width="200" bordercolor="#000000"><div align="right"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Delegaci&oacute;n:</font></strong></div></td>
        <td colspan="2" bordercolor="#000000"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> <?php echo $rowdelegacion['nombre']; ?>
        </font></div></td>
      </tr>
      <tr bordercolor="#000000">
        <td width="200" bordercolor="#000000"><div align="right"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Domicilio:</font></strong></div></td>
        <td colspan="2" bordercolor="#000000"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $rowjuris['domireal'];?></font></div></td>
      </tr>
      <tr bordercolor="#000000">
        <td width="200" bordercolor="#000000"><div align="right"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Localidad:</font></strong></div></td>
        <td colspan="2" bordercolor="#000000"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
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
        <td width="200" bordercolor="#000000"><div align="right"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Provincia</font></strong></div></td>
        <td colspan="2" bordercolor="#000000"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
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
        <td width="200" bordercolor="#000000"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>C&oacute;digo 
        Postal:</strong></font></div></td>
        <td colspan="2" bordercolor="#000000"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> <?php echo $rowjuris['indpostal'].$rowjuris['numpostal'].$rowjuris['alfapostal'];?></font></div></td>
      </tr>
      <tr bordercolor="#000000" >
        <td width="200" bordercolor="#000000"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Telefono:</strong></font></div></td>
        <td colspan="2" bordercolor="#000000"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
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
        <td width="200" bordercolor="#000000"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Contacto Telefonico </strong></font></div></td>
        <td colspan="2"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
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
        <td width="200"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Email:</strong></font></div></td>
        <td colspan="2"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
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
        <td width="200"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Disgregacion Dineraria:</strong></font></div></td>
        <td colspan="2"><div align="left"><?php echo $rowjuris['disgdinero']." %" ?></div></td>
      </tr>
      <tr bordercolor="#000000" >
        <td height="28" bordercolor="#000000">&nbsp;</td>
        <td width="221"><div align="center">
            <p>
              <input name="Input2" type="button" value="Modificar Datos" onclick="location.href='modificarJurisdiccion.php?origen=<?php echo $origen ?>&amp;cuit=<?php echo $cuit ?>&amp;coddel=<?php echo $delega ?> '"/>
            </p>
        </div></td>
        <td width="255"><p align="center">
            <?php if (($canjuris > 1) and ($cantitu == 0)) { ?>
            <input name="Input" type="button" value="Eliminar Jurisdiccion" onclick="location.href='confirmaEliminarJurisdiccion.php?origen=<?php echo $origen ?>&amp;cuit=<?php echo $cuit ?>&amp;coddel=<?php echo $delega ?> '"/>
            <?php } ?>
        </p></td>
      </tr>
  </table>
    <p>
	   <?php  } 
	  }
	  ?>
  </p>
</div>
