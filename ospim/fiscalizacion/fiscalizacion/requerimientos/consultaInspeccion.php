<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php"); 

$nroreq = $_GET['nroreq'];

$sqlInspe = "SELECT * from inspecfiscalizospim where nrorequerimiento = $nroreq";
$resInspe = mysql_query($sqlInspe,$db);
$rowInspe = mysql_fetch_assoc($resInspe);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado de Requerimientos :.</title>
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

<body bgcolor="#CCCCCC">
<div align="center">
  <p><span style="text-align:center">
  </span></p>
  	<p class="Estilo2">Datos de Requerimiento en Inspecci&oacute;n Nro. <?php echo $nroreq ?></p>
		<table width="579" border="1">
		  <tr>
			<td width="268"><div align="right">Inspector </div></td>
			<td width="295"><div align="left">
			<?php 
				$sqlInspector="select apeynombre from inspectores where codigo =".$rowInspe['inspectorasignado'];
				$resInspector=mysql_query($sqlInspector,$db);
				$rowInspector=mysql_fetch_array($resInspector);
				print($rowInspector['apeynombre']);
			?>
			</div></td>
		  </tr>
		  <tr>
			<td><div align="right">Fecha Asignaci&oacute;n </div></td>
			<td><div align="left"><?php echo  invertirFecha($rowInspe['fechaasignado']) ?>
			</div></td>
		  </tr>
		  <tr>
			<td><div align="right">D&iacute;as Efectivizaci&oacute;n</div></td>
			<td><div align="left"><?php echo  $rowInspe['diasefectivizacion'] ?>
			</div></td>
		  </tr>
		  <tr>
			<td><div align="right">Doc Adjuntos </div></td>
			<td><div align="left">
				<?php if ($rowInspe['adjuntadocumentos'] == 0) { 
					print("No");
				} else {  
					print("Si");
				} ?>
				</div></td>
		  </tr>
		  <tr>
			<td><div align="right">Detalle Doc Adjuntos </div></td>
			<td><div align="left"><?php echo  $rowInspe['detalledocumentos'] ?></div></td>
		  </tr>
		  <tr>
			<td><div align="right">Forma de envio Doc Adjuntos </div></td>
			<td><div align="left">
			  <?php if($rowInspe['formaenviodocumentos'] == 0 ) { 
						print("No especificado");
					} 
					if($rowInspe['formaenviodocumentos'] == 1 ) { 
						print("En mano");
					} 
					if($rowInspe['formaenviodocumentos'] == 2 ) { 
						print("Correo Postal");
					} 
					if($rowInspe['formaenviodocumentos'] == 3 ) { 
						print("Correo Electrónico");
					} 
					if($rowInspe['formaenviodocumentos'] == 4 ) { 
						print("FAX");
					} 
			?>
			</div></td>
		  </tr>
		  <tr>
		    <td><div align="right">Fecha recepci&oacute;n Doc Adjuntos </div></td>
		    <td><div align="left"><?php if ($rowInspe['fecharecibodocumentos'] != '') echo  invertirFecha($rowInspe['fecharecibodocumentos']) ?></div></td>
	      </tr>
		  <tr>
		    <td><div align="right">Inspecci&oacute;n Efectuada </div></td>
		    <td><div align="left">
		   <?php if ($rowInspe['inspeccionefectuada'] == 0) {
					print("No");
				} else {
					print("Si");
				} ?>
				</div></td>
	      </tr>
		  <tr>
		    <td height="21"><div align="right">Fecha Inspecci&oacute;n </div></td>
		    <td><div align="left"><?php if ($rowInspe['fechainspeccion'] != '') echo  invertirFecha($rowInspe['fechainspeccion']) ?></div></td>
	      </tr>
  </table>
	</p>
</div>
</body>
</html>