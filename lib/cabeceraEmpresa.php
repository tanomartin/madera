 <p align="center"><strong>Datos de la Empresa </strong></p>
<div align="center">
  <table width="700" border="2" bordercolor="#000000" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:14px">
    <tr>
      <td><div align="right"><strong>CUIT:</strong></div></td>
      <td><div align="left"><?php echo $row['cuit'] ?></div></td>
    </tr>
    <tr>
      <td><div align="right"><strong>Raz&oacute;n Social:</strong></div></td>
      <td><div align="left"><?php echo $row['nombre'];?></div></td>
    </tr>
    <tr>
      <td><div align="right"><strong>Domicilio:</strong></div></td>
      <td><div align="left"><?php echo $row['domilegal'];?></div></td>
    </tr>
    <tr>
      <td><div align="right"><strong>Localidad:</strong></div></td>
      <td><div align="left"><?php echo $row['nomlocali'];?></div></td>
    </tr>
    <tr>
      <td><div align="right"><strong>Provincia</strong></div></td>
      <td><div align="left"><?php echo $row['nomprovin']; ?></div></td>
    </tr>
    <tr>
      <td><div align="right"><strong>C&oacute;digo Postal:</strong></div></td>
      <td><div align="left"><?php echo $row['numpostal'];?></div></td>
    </tr>
    <tr>
      <td><div align="right"><strong>Carpeta en Archivo</strong></div></td>
      <td><div align="left"> 
          <?php 
			if ($row['carpetaenarchivo'] != "") {
				echo $row['carpetaenarchivo'];
			} else { 
				echo '-' ;
			}
		?>
      </div></td>
      <?php if ($tipo == "baja") { ?>
    </tr>
    <tr>
      <td><div align="center"><strong><font color="#FF0000">EMPRESA DE BAJA </strong></div></td>
    </tr>
    <?php 	} ?>
  </table>
</div>
