<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php"); 
$cuentaRemesa=$_GET['ctaRemesa'];
$fechaCargada=$_GET['fecRemesa'];
$fechaRemesa=substr($fechaCargada, 6, 4).substr($fechaCargada, 3, 2).substr($fechaCargada, 0, 2);
$ultimaRemesa=$_GET['ultRemesa'];
$sistemaRemesa=$_GET['sisRemesa'];
$ultimoRemito=$_GET['ultRemito'];

$sqlLeeCuenta="SELECT * FROM cuentasusimra where codigocuenta = $cuentaRemesa";
$resultLeeCuenta=mysql_query($sqlLeeCuenta,$db);
$rowLeeCuenta=mysql_fetch_array($resultLeeCuenta);

$sqlLeeRemito="SELECT * FROM remitosremesasusimra WHERE codigocuenta = $cuentaRemesa and sistemaremesa = '$sistemaRemesa' and fecharemesa = $fechaRemesa and nroremesa = $ultimaRemesa and nroremito = $ultimoRemito";
$resultLeeRemito=mysql_query($sqlLeeRemito,$db);
$rowLeeRemito=mysql_fetch_array($resultLeeRemito);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Banco USIMRA :.</title>
</head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>
<body bgcolor="#B2A274">
<p align="center">
  <input type="reset" name="volver" value="Volver" onclick="location.href = 'listarRemitos.php?ctaRemesa=<?php echo $cuentaRemesa?>&amp;fecRemesa=<?php echo $fechaCargada?>&amp;ultRemesa=<?php echo $ultimaRemesa?>&amp;sisRemesa=<?php echo $sistemaRemesa?>'" align="left"/>
</p>
<p align="center"><strong>Consulta de Remito</strong></p>
<form id="consultaRemito" name="consultaRemito">
  <table width="500" border="1" align="center">
    <tr>
      <td colspan="2"><div align="right">Cuenta:</div></td>
      <td width="180"><div align="left"><?php echo $rowLeeCuenta['descripcioncuenta']?></div></td>
    </tr>
    <tr>
      <td width="150" rowspan="2"><div align="center"><strong>Datos de la Remesa</strong></div></td>
      <td width="170"><div align="right">Fecha:</div></td>
      <td width="180"><div align="left"><?php echo $fechaCargada?></div></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Nro.:</div></td>
      <td width="180"><div align="left"><?php echo $ultimaRemesa?></div></td>
    </tr>
    <tr>
      <td width="150" rowspan="8"><div align="center"><strong>Datos del Remito</strong></div></td>
      <td width="170"><div align="right">Nro:</div></td>
      <td width="180"><div align="left"><?php echo $ultimoRemito?></div></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Fecha:</div></td>
      <td width="180"><div align="left"><?php echo invertirFecha($rowLeeRemito['fecharemito'])?></div></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Bruto:</div></td>
      <td width="180"><div align="left"><?php echo $rowLeeRemito['importebruto']?></div></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Comision:</div></td>
      <td width="180"><div align="left"><?php echo $rowLeeRemito['importecomision']?></div></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Neto:</div></td>
      <td width="180"><div align="left"><?php echo $rowLeeRemito['importeneto']?></div></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Cantidad de Boletas: </div></td>
      <td width="180"><div align="left"><?php echo $rowLeeRemito['boletasremito']?></div></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Sucursal Banco: </div></td>
      <td width="180"><div align="left"><?php echo $rowLeeRemito['sucursalbanco']?></div></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Estado:</div></td>
      <td width="180"><div align="left"><?php if($rowLeeRemito['estadoconciliacion']==0) echo "No Conciliado"; else echo "Conciliado";?></div></td>
    </tr>
    <tr>
      <td width="150" rowspan="7"><div align="center"><strong>Datos de las Boletas</strong></div></td>
      <td width="170"><div align="right">Aportes:</div></td>
      <td width="180"><div align="left"><?php echo $rowLeeRemito['importeboletasaporte']?></div></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Recargos:</div></td>
      <td width="180"><div align="left"><?php echo $rowLeeRemito['importeboletasrecargo']?></div></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Varios:</div></td>
      <td width="180"><div align="left"><?php echo $rowLeeRemito['importeboletasvarios']?></div></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Total de Aportes:</div></td>
      <td width="180"><div align="left"><?php echo $rowLeeRemito['importeboletaspagos']?></div></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Acuerdos:</div></td>
      <td width="180"><div align="left"><?php echo $rowLeeRemito['importeboletascuotas']?></div></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Total Bruto:</div></td>
      <td width="180"><div align="left"><?php echo $rowLeeRemito['importeboletasbruto']?></div></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Cantidad de Boletas:</div></td>
      <td width="180"><div align="left"><?php echo $rowLeeRemito['cantidadboletas']?></div></td>
    </tr>
    <tr>
      <td width="150"><div align="center"><strong>Datos del Resumen</strong></div></td>
      <td width="170"><div align="right">Fecha de Acreditacion:</div></td>
      <td width="180"><div align="left"><?php echo invertirFecha($rowLeeRemito['fechaacreditacion'])?></div></td>
    </tr>
  </table>
  <p align="center">
    <input type="button" name="imprimir" value="Imprimir" onclick="window.print();" align="left"/>
  </p>
  </form>
</body>
</html>