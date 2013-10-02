<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSession.php");

$cuit=$_GET['cuit'];
if ($cuit=="") {
	$cuit=$_POST['cuit'];
}

$sql = "select * from empresas where cuit = $cuit";
$result = mysql_query($sql,$db); 
$cant = mysql_num_rows($result); 
if ($cant == 0) {
	$sql = "select * from empresasdebaja where cuit = $cuit";
	$result = mysql_query($sql,$db); 
	$cant = mysql_num_rows($result); 
	if ($cant == 0) {
		header ("Location: nuevaEmpresa.php?origen=$origen&cuit=$cuit");
	} else {
		header ("Location: empresaBaja.php?origen=$origen&cuit=$cuit");
	}
}
$row = mysql_fetch_array($result); 

$sqllocalidad = "select * from localidades where codlocali = $row[codlocali]";
$resultlocalidad = mysql_query($sqllocalidad,$db); 
$rowlocalidad = mysql_fetch_array($resultlocalidad); 

$sqlprovi =  "select * from provincia where codprovin = $row[codprovin]";
$resultprovi = mysql_query($sqlprovi,$db); 
$rowprovi = mysql_fetch_array($resultprovi);

?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>

<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">

function validarBaja() {
	<?php 
		$sqlTitulares = "select * from titulares where cuitempresa = $cuit";
		$resTitulares = mysql_query($sqlTitulares,$db); 
		$canTitulares = mysql_num_rows($resTitulares); 
		if ($canTitulares > 0) {
			?>
			if (confirm('Hay titulares activos para esta empresa.\nQuiere confirmar la baja')) {
				alert('Se dará de baja la empresa con sus titulares y sus respectivos familiares');
				location.href="confirmaBajaEmpresa.php?origen=<?php echo $origen ?>&cuit=<?php echo $cuit ?>";
			} else {
				alert('No se dará de baja la empresa ni sus titulares asociados');
			}
 <?php } else { ?>
 	   		location.href="confirmaBajaEmpresa.php?origen=<?php echo $origen ?>&cuit=<?php echo $cuit ?>";
<?php }   ?>
}

function rediSabanaCtaCte(origen) {
	$.blockUI({ message: "<h1>Generando Cuenta Corriente... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
	if (origen == "ospim") {
		location.href='cuentas/cuentaCorrienteOspim.php?origen=<?php echo $origen ?>&cuit=<?php echo $cuit ?>';
	} else {
		location.href='cuentas/cuentaCorrienteUsimra.php?origen=<?php echo $origen ?>&cuit=<?php echo $cuit ?>';
	}
	
}

</script>

<title>.: Módulo Empresa :.</title>
</head>
<body bgcolor=<?php echo $bgcolor ?>>
<div align="center">
    <input type="reset" name="volver" value="Volver" onClick="location.href = 'moduloABM.php?origen=<?php echo $origen ?>'" align="center"/> 	
  <p>
    <?php 
		$err = $_GET['err'];
		$reactiva = $_GET['reactiva'];
		if ($err > 0) {
			$sqldelegacion = "select * from delegaciones where codidelega = $err";
			$resultdelegacion = mysql_query($sqldelegacion,$db); 
			$rowdelegacion = mysql_fetch_array($resultdelegacion); 
			print("<div align='center' style='color:#FF0000'><b> ERROR JURISDICCION EXISTENTE </b></div>");
			print("<div align='center' style='color:#FF0000'><b> NO SE PUEDE CARGAR LA JURISDICCION </b></div>");
			print("<div align='center' style='color:#FF0000'><b>".$rowdelegacion['nombre']." </b></div>");
		}
		if ($reactiva == 1) {
			print("<h2 class='Estilo1'><div align='center' style='color:#006666'><b> EMPRESA REACTIVADA </b></div> </h2>");
		}
		include($_SERVER['DOCUMENT_ROOT']."/lib/cabeceraEmpresa.php"); 
	?>
  </p>
  <table width="346" border="0">
    <tr>
      <td width="112"><div align="center">
        <input name="modifCabecera" type="button" value="Modificar Cabecera" onClick="location.href='modificarCabecera.php?origen=<?php echo $origen ?>&cuit=<?php echo $cuit ?> '">
      </div></td>
      <td width="123"><div align="center">
        <?php if ($origen == "ospim") { ?>
			<input name="ctacteOspim" type="button" value="Cuenta Corriente" onClick="rediSabanaCtaCte('ospim')">
		<?php } else {?>
			<input name="ctacteUsimra" type="button" value="Cuenta Corriente">
		<?php } ?>
      </div></td>
      <td width="97"><div align="center">
		<input name="beneficiarios" type="button" value="Beneficiarios">
      </div></td>
    </tr>
  </table>
  <p>
  	<?php
		$sqlCantAcuOspim = "select * from cabacuerdosospim where cuit = $cuit and estadoacuerdo = 1";
		$resCantAcuOspim = mysql_query($sqlCantAcuOspim,$db); 
		$CantAcuOspim = mysql_num_rows($resCantAcuOspim); 
		
		$sqlCantAcuUsimra = "select * from cabacuerdosusimra where cuit = $cuit and estadoacuerdo = 1";
		$resCantAcuUsimra = mysql_query($sqlCantAcuUsimra,$db); 
		$CantAcuUsimra = mysql_num_rows($resCantAcuUsimra); 
	
		$CantAcuerdos = $CantAcuOspim + $CantAcuUsimra;
		$CanDdjj = 0;
		if ($CantAcuerdos == 0) {
			//TOMO LOS LIMIETES DE MES Y ANIO
			$mesActual = date("n");
			$meslimite = date("n", (strtotime ("-6 month")));
			if ($mesActual < 8) {
				$anioLimite = date("Y") - 1;
			} else {
				$anioLimite = date("Y");
			}
			$sqlCantDdjj = "select * from cabddjjospim where cuit = $cuit and anoddjj >= $anioLimite and mesddjj >= $meslimite";
			$resCantDdjj = mysql_query($sqlCantDdjj,$db); 
			$CanDdjj = mysql_num_rows($resCantDdjj); 
			
			//TODO VER ddjj de USIMRA TAMBIEN
		}
		if ($CantAcuerdos == 0 and $CanDdjj == 0) { ?>
		    <input name="bajaEmpresa" type="button" id="bajaEmpresa" value="Bajar Empresa" onClick="validarBaja()">
  <?php } ?>
	
  </p>
  <p>
    <?php
		include($_SERVER['DOCUMENT_ROOT']."/comun/empresas/abm/jurisdicEmpresa.php");
	?>
  </p>
  <p>
    <input name="Input5" type="button" value="Disgregacion Dineraria" onClick='location.href="disgregaDinero.php?origen=<?php echo $origen ?>&cuit=<?php echo $cuit ?>"'>
    <input name="Input4" type="button" value="Agreagar Jurisdiccion" onclick='location.href="nuevaJurisdiccion.php?origen=<?php echo $origen ?>&cuit=<?php echo $cuit ?>"'>
  </p>
  <p>
    <input type="button" name="imprimir" value="Imprimir" onClick="window.print();" align="left" >
  </p>
</div>
</body>
</html>
