<?php $libPath = $_SERVER ['DOCUMENT_ROOT'] . "/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php"); 
$cuit = $_GET['cuit'];
$nroacu = $_GET['nroacu'];

$sqlCabecera = "SELECT c.*,e.*,t.*, g.apeynombre as gestor, i.apeynombre as inspector
				FROM cabacuerdosospim c, estadosdeacuerdos e, tiposdeacuerdos t, gestoresdeacuerdos g, inspectores i
				WHERE 
					c.cuit = $cuit and 
					c.nroacuerdo = $nroacu and 
					c.estadoacuerdo = e.codigo and 
					c.tipoacuerdo = t.codigo and 
					c.gestoracuerdo = g.codigo and
					c.inspectorinterviene = i.codigo";
$resCabecera = mysql_query($sqlCabecera,$db);
$canCabecera = mysql_num_rows($resCabecera);
$rowCebecera = mysql_fetch_array($resCabecera); ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>.: Consulta Acuerdo :.</title>
</head>
<body bgcolor="#CCCCCC">
	<div align="center">
  		<input type="reset" name="volver" value="Volver" onClick="location.href = 'acuerdos.php?cuit=<?php echo $cuit ?>'" />
<?php 	include($libPath."cabeceraEmpresaConsulta.php"); 
	 	include($libPath."cabeceraEmpresa.php"); ?>
    	<p><b>O.S.P.I.M. - Acuerdo Cargado Nº <?php echo $rowCebecera['nroacuerdo'] ?></b></p>
    	<p>
    		<b>ESTADO "<?php echo $rowCebecera['descripcion']; ?>"</b>
		</p>
    	<p><b>Cabecera</b></p>
    	<table width="900" border="1" style="text-align: left">
	      	<tr>
		        <td><b>Tipo</b></td>
		        <td><?php echo $rowCebecera['descripcion'];?></td>
		        <td><b>Fecha</b></td>
		        <td><?php echo invertirFecha($rowCebecera['fechaacuerdo']) ?></td>
		        <td><b>Nº de Acta</b></td>
		        <td><?php echo $rowCebecera['nroacta'] ?></td>
	      	</tr>
      		<tr>
        		<td><b>Gestor</b></td>
        		<td><?php echo $rowCebecera['gestor']; ?></td>
				<td><b>Inspector</b></td>
        		<td><?php echo $rowCebecera['inspector'];?></td>
        		<td><b>Req. Origen</b></td>
        		<td><?php if ($rowCebecera['requerimientoorigen'] == 0) { echo "-"; } else { echo $rowCebecera['requerimientoorigen']; }  ?></td>
      		</tr>
      		<tr>
        		<td><b>Liq. Origen</b></td>
        		<td>
			<?php 	if ($rowCebecera['requerimientoorigen'] == 0) {
						echo "-";
					} else {
						echo $rowCebecera['liquidacionorigen'];
					} ?>
				</td>
        		<td><b>Monto</b></td>
        		<td><?php echo $rowCebecera['montoacuerdo'] ?></td>
        		<td><b>Gastos Admin.</b></td>
        		<td><?php echo $rowCebecera['porcengastoadmin']."%" ?></td>
      		</tr>
      		<tr>
        		<td><b>Obser.</b></td>
        		<td colspan="5"><?php echo $rowCebecera['observaciones'] ?></td>
      		</tr>
    	</table>
 		<p><input type="button" name="incobrable" value="Confirmar INCOBRABLE" onclick="window.location = 'incobrableAcuerdoGuardar.php?cuit=<?php echo $cuit ?>&nroacu=<?php echo $nroacu ?>'"/> </p>
 	</div>
</body>
</html>