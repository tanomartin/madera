<? session_save_path("sessiones");
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
.Estilo1 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
}
</STYLE>

<script language=Javascript>
function linkvolver(){	
	window.open(documento,'Historial');
}
</script>

<title>.: Sistmea de Acuerdos - Actualizacion :.</title>
</head>
<?
include("conexion.php");

$sql = "select * from usuarios where id = '$_SESSION[usuario]'";
$result = mysql_db_query("acuerdos",$sql,$db);
$row=mysql_fetch_array($result);

?>
<body bgcolor="#E4C192" link="#D5913A" vlink="#CF8B34" alink="#D18C35">
<p align="center"><img border="0" src="top.jpg" width="700" height="120"></p>
<p align="center"><strong><font color="#990000" size="1" face="Verdana, Arial, Helvetica, sans-serif">Bienvenid@</font><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
 <? echo $row['nombre']?></font></strong></p>

<form name="subida" id="subida" method="post" action="">
    <table width="689" border="1" align="center">
      <tr>
        <td width="679" height="28">
		 	<div align="center">
		 	  <p>Cuando la actualizaci&oacute;n termine aparecer&aacute; el link para volver al men&uacute; principal. </p>
		 	  <p>Esper por favor...</p>
			  <?php 
				//subida a la base de datos
				$sqlSubida="LOAD DATA LOCAL INFILE 'actualizacion/empracue.txt' REPLACE INTO TABLE empresas FIELDS TERMINATED BY '|' LINES TERMINATED BY '\\n'";
			  	mysql_query($sqlSubida,$db) or die(mysql_error());		
				$sqlSubida="LOAD DATA LOCAL INFILE 'actualizacion/cabeacue.txt' REPLACE INTO TABLE acuerdos FIELDS TERMINATED BY '|' LINES TERMINATED BY '\\n'";
			    mysql_query($sqlSubida,$db) or die(mysql_error());	
				$sqlSubida="LOAD DATA LOCAL INFILE 'actualizacion/cuotacue.txt' REPLACE INTO TABLE cuotas FIELDS TERMINATED BY '|' LINES TERMINATED BY '\\n'";
			  	mysql_query($sqlSubida,$db) or die(mysql_error());	
				
				//Logueo...
				$nombre="log".date("Ymd").".txt";
				$fichero = "actualizacion/log/".$nombre;
				if (file_exists($fichero)) {
            		$fp=fopen($fichero,"a");
				} else {
					$fp=fopen($fichero,"w+");
				}
				$lineaAgregar="Usuario: ".$_SESSION[usuario]." - HORA: ".date("G:i:s")." - Archivos: (Empresa: ".filesize("actualizacion/empracue.txt")." - Acuerdos: ".filesize("actualizacion/cabeacue.txt")." - Cuotas: ".filesize("actualizacion/cuotacue.txt").")\r\n";
				fwrite($fp,$lineaAgregar);
				fclose($fp);
				
				//paso los archivos a la carepta historial...			
				$destinoEmpresa="actualizacion/historial/empracue.txt";
			 	$destinoCabecera="actualizacion/historial/cabeacue.txt";
			 	$destinoCuotas="actualizacion/historial/cuotacue.txt";
				rename("actualizacion/empracue.txt",$destinoEmpresa);
				rename("actualizacion/cabeacue.txt",$destinoCabecera);
				rename("actualizacion/cuotacue.txt",$destinoCuotas);  
			  ?>
		 	  <p><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><a href="acuerdos.php">VOLVER</a></strong></font></p>
	 	  </div></td>
      </tr>
</table>

</form>
</body>
</html>
