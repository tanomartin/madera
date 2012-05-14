<?php session_save_path("sessiones");
session_start();
if($_SESSION['usuario'] == null or $_SESSION['aut'] > 1)
	header ("location:index.htm");
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style>
<!--
A:link {text-decoration: none}
A:visited {text-decoration: none}
A:hover {text-decoration:underline; color:FCF63C}
-->
</style>
<STYLE>
BODY {SCROLLBAR-FACE-COLOR: #E4C192; 
SCROLLBAR-HIGHLIGHT-COLOR: #CD8C34; 
SCROLLBAR-SHADOW-COLOR: #CD8C34; 
SCROLLBAR-3DLIGHT-COLOR: #CD8C34; 
SCROLLBAR-ARROW-COLOR: #CD8C34; 
SCROLLBAR-TRACK-COLOR: #CD8C34; 
SCROLLBAR-DARKSHADOW-COLOR: #CD8C34
}
</STYLE>


<title>.: Sistmea de Acuerdos - Reimprimir :.</title>
</head>
<?php
include("conexion.php");
$sql = "select * from usuarios where id = '$_SESSION[usuario]'";
$result = mysql_query($sql,$db);
$row=mysql_fetch_array($result);

$delcod=$_GET['delcod'];
$empcod=$_GET['empcod'];
$acuerdo=$_GET['acuerdo'];
$cuota=$_GET['cuota'];

$sqlEmpresa = "select * from empresas where delcod=".$delcod." and empcod=".$empcod;
$resultEmpresa = mysql_query($sqlEmpresa,$db);
$rowEmpresa=mysql_fetch_array($resultEmpresa);

?>
<body bgcolor="#E4C192" link="#D5913A" vlink="#CF8B34" alink="#D18C35">
<p align="center"><img border="0" src="top.jpg" width="700" height="120"></p>
<p align="center"><strong><font color="#990000" size="1" face="Verdana, Arial, Helvetica, sans-serif">Bienvenid@</font><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
  <?php echo $row['nombre']?></font></strong></p>

<form name="reimprimir" id="reimprimir" method="post" action="acuerdos2.php">
          <input name="cuit" type="hidden" value="<?php echo $rowEmpresa['nrcuit'];?>">
          <input name="delcod" type="hidden" value="<?php echo $delcod;?>">
          <input name="empcod" type="hidden" value="<?php echo $empcod;?>">
          <input name="acuerdo" type="hidden" value="<?php echo $acuerdo;?>">
          <input name="cuota" type="hidden" value="<?php echo $cuota;?>">
<table width="906" border="1" align="center">
      <tr>
        <td height="50"><p>DATOS DE LA EMPRESA </p>        </td>
      </tr>
      <tr bgcolor="#C08345">
        <td><div align="center">
          <p><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">CUIT:</font></strong><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> <?php echo $rowEmpresa['nrcuit'];?></font></strong></p>
          </div></td>
      </tr>
      <tr bgcolor="#C08345">
        <td><div align="center">
          <p><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Raz&oacute;n 
            Social: </font></strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $rowEmpresa['nombre'];?></font></p>
          </div></td>
      </tr>
      <tr bgcolor="#C08345">
        <td><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Delegaci&oacute;n:</strong> <?php echo $rowEmpresa['delcod'];?></font></div></td>
      </tr>
      <tr bgcolor="#C08345">
        <td><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Empresa: </strong> <?php echo $rowEmpresa['empcod'];?></font></div></td>
      </tr>
      <tr bgcolor="#C08345">
        <td><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Domicilio: </strong> <?php echo $rowEmpresa['domici'];?> </font></div></td>
      </tr>
      <tr bgcolor="#C08345">
        <td><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Localidad: </strong> <?php echo $rowEmpresa['locali'];?> </font></div></td>
      </tr>
      <tr bgcolor="#C08345">
        <td><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>C&oacute;digo Postal: </strong><?php echo $rowEmpresa['codpos'];?></font></div></td>
      </tr>
      <tr bgcolor="#C08345">
        <?php
$provincia = array ("PROVINCIA", "CAPITAL FEDERAL", "BUENOS AIRES", "MENDOZA", "NEUQUEN", "SALTA", "ENTRE RIOS", "MISIONES", "CHACO", "SANTA FE", "CORDOBA", "SAN JUAN", "RIO NEGRO", "CORRIENTES", "SANTA CRUZ", "CHUBUT", "FORMOSA", "LA PAMPA", "SANTIAGO DEL ESTERO", "JUJUY", "TUCUMAN", "TIERRA DEL FUEGO", "SAN LUIS", "LA RIOJA", "CATAMARCA");
$pro = $row["provin"];
?>
        <td><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Provincia:</strong> <?php echo $provincia [$pro]; ?></font></div></td>
      </tr>
      
      <tr>
        <td height="43"><p>DATOS DE LA CUOTA A HABILITAR PARA REIMPRESION </p>        </td>
      </tr>
      <tr bgcolor="#C08345">
        <td><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Acuerdo: </strong><?php echo $acuerdo;?></font></div></td>
      </tr>
      <tr bgcolor="#C08345">
        <td><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Cuota: </strong> <?php echo $cuota;?></font></div></td>
      </tr>
      <tr>
        <td>
		<p align="center">&nbsp;		  </p>
		<p align="center">
		  <input type="submit" name="habilitar" value="Habilitar Impresión" />
		  </p>
		<p align="center">
		<a href="historial.php?orden=nroctr"><font color="#CD8C34" face="Verdana" size="2"><b>Volver</b></font></a>		</p>		</td>
      </tr>
</table>
	  
</form>
</body>
</html>
