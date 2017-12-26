<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 

$codigo = $_GET['id'];
$sqlEscuela = "SELECT 
					p.*, l.nomlocali as localidad, r.descrip as provincia 
				FROM escuelas p, localidades l, provincia r 
				WHERE p.id = $codigo and p.codlocali = l.codlocali and p.codprovin = r.codprovin";
$resEscuela = mysql_query($sqlEscuela,$db);
$rowEscuela = mysql_fetch_assoc($resEscuela);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Escuela :.</title>

<style type="text/css" media="print">
.nover {display:none}
</style>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <h3>Ficha Escuela </h3>
	  <table style="width: 800px" border="1">
        <tr>
          <td><div align="right"><strong>C&oacute;digo</strong></div></td>
          <td colspan="5"><div align="left"><strong><?php echo $rowEscuela['id']  ?></strong></div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>Nombre</strong></div></td>
          <td colspan="5"><div align="left"><?php echo $rowEscuela['nombre'] ?></div></td>
        </tr>
         <tr>
          <td><div align="right"><strong>C.U.E.</strong></div></td>
          <td colspan="5"><div align="left"><?php echo $rowEscuela['cue'] ?></div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>Domicilio</strong></div></td>
          <td colspan="5"><div align="left"><?php echo $rowEscuela['domicilio'] ?></div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>Codigo Postal</strong></div></td>
          <td width="183"><div align="left"><?php echo $rowEscuela['indpostal']." ".$rowEscuela['numpostal']." ".$rowEscuela['alfapostal'] ?></div></td>
          <td width="160"><div align="left"><strong>Localidad</strong></div></td>
          <td width="140"><div align="left"><?php echo $rowEscuela['localidad'] ?></div></td>
          <td width="145"><div align="left"><strong>Provincia </strong></div></td>
          <td width="124"><div align="left"><?php echo $rowEscuela['provincia'] ?></div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>Telefono</strong></div></td>
          <td colspan="2"><div align="left"><?php echo $rowEscuela['telefono']; ?></div></td>
          <td><div align="left"><strong>Email</strong></div></td>
          <td colspan="2"><div align="left"><?php echo $rowEscuela['email'] ?></div></td>
        </tr>
  </table>
<p><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" /></p>
</div>
</body>
</html>
