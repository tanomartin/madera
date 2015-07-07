<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."fechas.php");
$fechamensaje=$_GET['fechaMens'];
$nromensaje=$_GET['nroMail'];
$ctamensaje='afipnomina@ospim.com.ar';

$sqlBuscaMensaje = "SELECT * FROM afipmensajes WHERE nromensaje = '$nromensaje' AND fechaemailafip = '$fechamensaje' AND cuentaderecepcion = '$ctamensaje'";
$resBuscaMensaje = mysql_query($sqlBuscaMensaje,$db);
$canBuscaMensaje = mysql_num_rows($resBuscaMensaje);
if($canBuscaMensaje!=0) {
	$rowBuscaMensaje = mysql_fetch_array($resBuscaMensaje);
	$nrodisco = $rowBuscaMensaje['nrodisco'];

	if(strcmp($rowBuscaMensaje['tipoarchivo'],"NODJ")==0) {
		$tituloform = 'Resultados de Procesamiento Nomina de DDJJ - Archivo Nro: '.$nrodisco;
		$sqlBuscaArchivo = "SELECT * FROM nominasddjj WHERE nrodisco = '$nrodisco'";
		$resBuscaArchivo = mysql_query($sqlBuscaArchivo,$db);
		$canBuscaArchivo = mysql_num_rows($resBuscaArchivo);
		if($canBuscaArchivo!=0) {
			$rowBuscaArchivo = mysql_fetch_array($resBuscaArchivo);
		}
		else {
		}
	}
} else {
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>
<style type="text/css" media="print">
.nover {display:none}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Detalle Procesamiento Transferencias AFIP :.</title>
<link href="/madera/lib/tablas.css" rel="stylesheet"/>
</head>
<body bgcolor="#CCCCCC">
<div align="center">
	<h2><?php echo $tituloform;?></h2>
</div>
<div class="grilla">
<table width="1233" border="0">
<thead>
  <tr>
    <th colspan="2"><div align="center"><h3>AFIP</h3></div></th>
    <th colspan="2"><div align="center"><h3>OSPIM</h3></div></th>
  </tr>
</thead>
<tbody>
  <tr>
    <td width="425">Fecha del Mensaje :</td>
    <td width="261"><?php echo $rowBuscaArchivo['fechaemailafip'];?></td>
    <td width="425">Fecha de Procesamiento :</td>
    <td width="104"><?php echo $rowBuscaArchivo['fechaprocesoospim'];?></td>
  </tr>
  <tr>
    <td>Total de Registros Informados :</td>
    <td><?php echo $rowBuscaArchivo['registrosafip'];?></td>
    <td>Total de Registros Procesados :</td>
    <td><?php echo $rowBuscaArchivo['registrosprocesoospim'];?></td>
  </tr>
  
  <tr>
    <td colspan="2">&nbsp;</td>
    <td>Carpeta de Almacenamiento :</td>
    <td><?php echo $rowBuscaArchivo['carpetaarchivoospim'];?></td>
  </tr>
</tbody>
</table>
</div>
<p align="center">
	<input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" align="right"/>
</p>
</body>
</html>