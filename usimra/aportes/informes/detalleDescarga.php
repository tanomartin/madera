<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionUsimra.php"); 

$idControl = $_GET['idControl'];
$sqlControl = "SELECT * FROM aporcontroldescarga WHERE id = $idControl";
$resControl = mysql_query($sqlControl,$db); 
$rowControl = mysql_fetch_assoc($resControl);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Resultados Descarga Aplicativo DDJJ :.</title>
</head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>

<body bgcolor="#B2A274">
<div align="center">
  <p><span class="Estilo2">Resumen de Descarga</span></p>
	   <table width="400" border="1">
		<tr>
		  <td><strong>Usuario</strong></td>
		  <td><?php echo $rowControl['usuariodescarga'] ?></td>
		</tr>
		<tr>
		  <td><strong>Fecha</strong></td>
		  <td><?php echo $rowControl['fechadescarga'] ?></td>
		</tr>
		<tr>
		  <td><strong>Cant. DDJJ</strong></td>
		  <td><?php echo $rowControl['cantidadddjj'] ?></td>
		</tr>
		<tr>
		  <td><strong>Cant. Activos</strong></td>
		  <td><?php echo $rowControl['cantidadactivos'] ?></td>
		</tr>
		<tr>
		  <td><strong>Cant. Inactivos</strong></td>
		  <td><?php echo $rowControl['cantidadinactivos'] ?></td>
		</tr>
		<tr>
		  <td><strong>Cant. Empresas</strong></td>
		  <td><?php echo $rowControl['cantidadempresas'] ?></td>
		</tr>
		<tr>
		  <td><strong>Cant. Titulares</strong></td>
		  <td><?php echo $rowControl['cantidadtitulares'] ?></td>
		</tr>
		<tr>
		  <td><strong>Cant. Familiares</strong></td>
		  <td><?php echo $rowControl['cantidadfamiliares'] ?></td>
		</tr>
		<tr>
		  <td><strong>Cant. Titulares de Baja</strong></td>
		  <td><?php echo $rowControl['cantidadtitularesbaja'] ?></td>
		</tr>
		<tr>
		  <td><strong>Cant. Familiares de Baja</strong></td>
		  <td><?php echo $rowControl['cantidadfamiliaresbaja'] ?></td>
		</tr>
  </table>
	   <p> <input type="button" name="imprimir" value="Imprimir" onclick="window.print();" align="center"/></p>
</div>
</body>