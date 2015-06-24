<p align="center"><strong>Datos de la Empresa </strong></p>
<div align="center">
  <table border="1" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:14px; width: 700">
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
      <td colspan="2"><div align="center" style="font-size:16px;font-weight:bold; color:#FF0000">EMPRESA DE BAJA </div></td>
    </tr>
    <?php 	} ?>
  </table>
</div>
