 <p align="center"><strong>Datos de la Empresa </strong></p>
  <div align="center">
    <table width="53%" height="157" border="2">
      <tr bordercolor="#000000" bgcolor="#B2A274">
        <td width="49%" bordercolor="#000000"><div align="right"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">CUIT:</font></strong></div></td>
        <td width="51%" bordercolor="#000000"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $row['cuit'] ?></font></div></td>
      </tr>
      <tr bordercolor="#000000" bgcolor="#B2A274">
        <td bordercolor="#000000"><div align="right"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Raz&oacute;n 
          Social:</font></strong></div></td>
        <td bordercolor="#000000"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $row['nombre'];?></font></div></td>
      </tr>
      
      <tr bordercolor="#000000" bgcolor="#B2A274">
        <td bordercolor="#000000"><div align="right"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Domicilio:</font></strong></div></td>
        <td bordercolor="#000000"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $row['domilegal'];?></font></div></td>
      </tr>
      <tr bordercolor="#000000" bgcolor="#B2A274">
        <td height="24" bordercolor="#000000"><div align="right"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Localidad:</font></strong></div></td>
        <td bordercolor="#000000"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $rowlocalidad['nomlocali'];?></font></div></td>
      </tr>
      <tr bordercolor="#000000" bgcolor="#B2A274">
        <td bordercolor="#000000"><div align="right"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Provincia</font></strong></div></td>
        <td bordercolor="#000000"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $rowprovi['descrip']; ?></font></div></td>
      </tr>
      <tr bordercolor="#000000" bgcolor="#B2A274">
        <td bordercolor="#000000"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>C&oacute;digo 
          Postal:</strong></font></div></td>
        <td bordercolor="#000000"><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $row['numpostal'];?></font></div></td>
      </tr>
    </table>
  </div>
