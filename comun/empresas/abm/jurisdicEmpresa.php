<p align="center"><strong>Datos de Jurisdicciones </strong></p>
<?php 
	$cuit = $row['cuit'];
	$sqljuris = "select j.*, d.nombre as delegacion, p.descrip as provincia, l.nomlocali as localidad
				from jurisdiccion j, delegaciones d, provincia p, localidades l 
				where j.cuit = $cuit and j.codidelega = d.codidelega and j.codprovin = p.codprovin and j.codlocali = l.codlocali";
	$resjuris = mysql_query($sqljuris,$db); 
	$canjuris = mysql_num_rows($resjuris); 
	if ($canjuris != 0) {
		while ($rowjuris = mysql_fetch_array($resjuris)) {
			$delega = $rowjuris['codidelega'];
			
			$sqltitu = "select * from titulares where cuitempresa = $cuit and codidelega = $delega";
			$restitu = mysql_query($sqltitu,$db); 
			$cantitu = mysql_num_rows($restitu); 
			
			$sqlRequeOspim = "select nrorequerimiento from reqfiscalizospim where cuit = $cuit and codidelega = $delega and procesoasignado != 1 and requerimientoanulado = 0";
			$resRequeOspim = mysql_query($sqlRequeOspim,$db); 
			$canRequeOspim = mysql_num_rows($resRequeOspim); 

			$sqlRequeUsimra = "select nrorequerimiento from reqfiscalizusimra where cuit = $cuit and codidelega = $delega and procesoasignado != 1 and requerimientoanulado = 0";
			$resRequeUsimra = mysql_query($sqlRequeUsimra,$db); 
			$canRequeUsimra = mysql_num_rows($resRequeUsimra); 
			$canTotalReq = $canRequeOspim + $canRequeUsimra;
?>
<div align="center">
    <table border="1" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:13px; width: 700">
      <tr>
        <td width="350"><div align="right"><strong>Delegaci&oacute;n:</strong></div></td>
        <td><div align="left"><?php echo $rowjuris['delegacion']; ?>
        </div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Domicilio:</strong></div></td>
        <td><div align="left"><?php echo $rowjuris['domireal'];?></div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Localidad:</strong></div></td>
        <td><div align="left"><?php echo $rowjuris['localidad'];?>
        </div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Provincia</strong></div></td>
        <td><div align="left"><?php echo $rowjuris['provincia']; ?>
        </div></td>
      </tr>
      <tr >
        <td><div align="right"><strong>C&oacute;digo Postal:</strong></div></td>
        <td><div align="left"><?php echo $rowjuris['indpostal'].$rowjuris['numpostal'].$rowjuris['alfapostal'];?></div></td>
      </tr>
      <tr >
        <td><div align="right"><strong>Telefono:</strong></div></td>
        <td><div align="left">
        <font size="2" face="Verdana, Arial, Helvetica, sans-serif">
            <?php 
		if ($rowjuris['telefono'] == 0){
			echo "-";
		} else {
			echo "(".$rowjuris['ddn'].") - ".$rowjuris['telefono'];
		}
		?>
		</font>
        </div></td>
      </tr>
      <tr >
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
      <tr >
        <td><div align="right"><strong>Email:</strong></div></td>
        <td><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
            <?php
		if ($rowjuris['email']!= "") {
			echo $rowjuris['email'];
		} else {
			echo "-";
		}
		 ?>
		 </font>
        </div></td>
      </tr>
      <tr >
        <td><div align="right"><strong>Disgregacion Dineraria:</strong></div></td>
        <td><div align="left"><?php echo $rowjuris['disgdinero']." %" ?></div></td>
      </tr>
      <tr >
        <td><p align="center"><input name="Input2" type="button" value="Modificar Datos" onclick="location.href='modificarJurisdiccion.php?origen=<?php echo $origen ?>&cuit=<?php echo $cuit ?>&coddel=<?php echo $delega ?>'"/></p></td>
        <td width="350" style="height: 60px; "><p align="center">
            <?php if (($canjuris > 1) and ($cantitu == 0) and ($canTotalReq == 0)) { ?>
           	 	<input name="Input" type="button" value="Eliminar Jurisdiccion" onclick="location.href='confirmaEliminarJurisdiccion.php?origen=<?php echo $origen ?>&cuit=<?php echo $cuit ?>&coddel=<?php echo $delega ?>'"/>
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
