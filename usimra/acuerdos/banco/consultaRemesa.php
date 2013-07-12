<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php"); 
$cuentaRemesa=$_GET['ctaRemesa'];
$fechaCargada=$_GET['fecRemesa'];
$fechaRemesa=substr($fechaCargada, 6, 4).substr($fechaCargada, 3, 2).substr($fechaCargada, 0, 2);
$ultimaRemesa=$_GET['ultRemesa'];
$sistemaRemesa=$_GET['sisRemesa'];

$sqlLeeCuenta="SELECT * FROM cuentasusimra where codigocuenta = $cuentaRemesa";
$resultLeeCuenta=mysql_query($sqlLeeCuenta,$db);
$rowLeeCuenta=mysql_fetch_array($resultLeeCuenta);

$sqlLeeRemesa="SELECT * FROM remesasusimra where codigocuenta = $cuentaRemesa and sistemaremesa = '$sistemaRemesa' and fecharemesa = $fechaRemesa and nroremesa = $ultimaRemesa";
$resultLeeRemesa=mysql_query($sqlLeeRemesa,$db);
$rowLeeRemesa=mysql_fetch_array($resultLeeRemesa);

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
<p align="center"><strong>Consulta de Remesa</strong></p>
<form id="consultaRemesa" name="consultaRemesa">
  <table width="500" border="1" align="center">
    <tr>
      <td colspan="2"><div align="right">Cuenta:</div></td>
      <td width="180"><div align="left"><?php echo $rowLeeCuenta['descripcioncuenta']?></div></td>
    </tr>
    <tr>
      <td width="150" rowspan="7"><div align="center"><strong>Datos de la Remesa</strong></div></td>
      <td width="170"><div align="right">Fecha:</div></td>
      <td width="180"><div align="left"><?php echo $fechaCargada?></div></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Nro.:</div></td>
      <td width="180"><div align="left"><?php echo $ultimaRemesa?></div></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Bruto:</div></td>
      <td width="180"><div align="left"><?php echo $rowLeeRemesa['importebruto']?></div></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Comision:</div></td>
      <td width="180"><div align="left"><?php echo $rowLeeRemesa['importecomision']?></div></td>
    </tr>
    <tr>
      <td width="170"><div align="right">FAIMA:</div></td>
      <td width="180"><div align="left"><?php echo $rowLeeRemesa['importefaima']?></div></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Neto:</div></td>
      <td width="180"><div align="left"><?php echo $rowLeeRemesa['importeneto']?></div></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Estado:</div></td>
      <td width="180"><div align="left"><?php if($rowLeeRemesa['estadoconciliacion']==0) echo "No Conciliado"; else echo "Conciliado";?></div></td>
    </tr>
    <tr>
      <td width="150" rowspan="3"><div align="center"><strong>Datos de los Remitos</strong></div></td>
      <td width="170"><div align="right">Bruto:</div></td>
      <td width="180"><div align="left"><?php echo $rowLeeRemesa['importebrutoremitos']?></div></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Comision:</div></td>
      <td width="180"><div align="left"><?php echo $rowLeeRemesa['importecomisionesremitos']?></div></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Neto:</div></td>
      <td width="180"><div align="left"><?php echo $rowLeeRemesa['importenetoremitos']?></div></td>
    </tr>
    <tr>
      <td width="150" rowspan="7"><div align="center"><strong>Datos de las Boletas</strong></div></td>
      <td width="170"><div align="right">Aportes:</div></td>
      <td width="180"><div align="left"><?php echo $rowLeeRemesa['importeboletasaporte']?></div></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Recargos:</div></td>
      <td width="180"><div align="left"><?php echo $rowLeeRemesa['importeboletasrecargo']?></div></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Varios:</div></td>
      <td width="180"><div align="left"><?php echo $rowLeeRemesa['importeboletasvarios']?></div></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Total de Aportes:</div></td>
      <td width="180"><div align="left"><?php echo $rowLeeRemesa['importeboletaspagos']?></div></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Acuerdos:</div></td>
      <td width="180"><div align="left"><?php echo $rowLeeRemesa['importeboletascuotas']?></div></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Total Bruto:</div></td>
      <td width="180"><div align="left"><?php echo $rowLeeRemesa['importeboletasbruto']?></div></td>
    </tr>
    <tr>
      <td width="170"><div align="right">Cantidad de Boletas:</div></td>
      <td width="180"><div align="left"><?php echo $rowLeeRemesa['cantidadboletas']?></div></td>
    </tr>
    <tr>
      <td width="150"><div align="center"><strong>Datos del Resumen</strong></div></td>
      <td width="170"><div align="right">Fecha de Acreditacion:</div></td>
      <td width="180"><div align="left"><?php echo invertirFecha($rowLeeRemesa['fechaacreditacion'])?></div></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <table width="500" border="0" align="center">
    <tr>
      <td width="250"><div align="left">
        <input type="reset" name="volver" value="Volver" onClick="location.href = 'listarRemesas.php?ctaRemesa=<?php echo $cuentaRemesa?>&fecRemesa=<?php echo $fechaCargada?>'" align="left"/>
      </div></td>
      <td width="250"><div align="right">
        <input type="button" name="imprimir" value="Imprimir" onClick="window.print();" align="left"/>
      </div></td>
    </tr>
  </table>
</form>
</body>
</html>