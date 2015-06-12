<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSession.php");

$cuit=$_GET['cuit'];
if ($cuit=="") {
	$cuit=$_POST['cuit'];
}

include($libPath."cabeceraEmpresaConsulta.php"); 
if ($tipo == "baja") {
	header ("Location: empresaBaja.php?origen=$origen&cuit=$cuit");
	exit(0);
}
if ($tipo == "noexiste") {
	header ("Location: nuevaEmpresa.php?origen=$origen&cuit=$cuit");
	exit(0);
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">

function validarBaja() {
	<?php 
		$sqlTitulares = "select nroafiliado from titulares where cuitempresa = $cuit";
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

function rediSabanaCtaCte(origen, cuit) {
	/*$.blockUI({ message: "<h1>Generando Cuenta Corriente... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
	var dire = "";
	if (origen == "ospim") {
		dire = 'cuentas/cuentaCorrienteOspim.php?cuit='+cuit;
	}
	if (origen == "usimra") {
		dire = 'cuentas/cuentaCorrienteUsimra.php?cuit='+cuit;
	}
	location.href = dire;*/
	if (origen == "ospim") {
		var dire = "";
		$.blockUI({ message: "<h1>Generando Cuenta Corriente... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
		dire = 'cuentas/cuentaCorrienteOspim.php?cuit='+cuit;
		location.href = dire;
	}
}

function rediBeneficiarios(origen, cuit) {
	/*var dire = "";
	if (origen == "ospim") { 
		dire = "/madera/ospim/afiliados/informes/titularesPorEmpresa.php?cuit="+cuit;
	}
	if (origen == "usimra") { 
		dire = "/madera/usimra/empleados/informes/listadoTitularesPorEmpresa.php?cuit="+cuit;
	}
	a= window.open(dire,"","toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=10, left=10");*/
	if (origen == "ospim") { 
		var dire = "";
		dire = "/madera/ospim/afiliados/informes/titularesPorEmpresa.php?cuit="+cuit;
		a= window.open(dire,"","toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=10, left=10");
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
		include($libPath."cabeceraEmpresa.php"); 
	?>
  </p>
  <table width="346" border="0">
    <tr>
      <td width="112"><div align="center">
          <input name="modifCabecera" type="button" value="Modificar Cabecera" onClick="location.href='modificarCabecera.php?origen=<?php echo $origen ?>&cuit=<?php echo $cuit ?> '"/>
        </div></td>
      <td width="123"><div align="center">
          <input name="ctacteOspim" type="button" value="Cuenta Corriente" onClick="rediSabanaCtaCte('<?php echo $origen ?>','<?php echo $cuit ?>')"/>
        </div></td>
      <td width="97"><div align="center">
          <input name="titulares" type="button" value="Nómina Empleados" onClick="rediBeneficiarios('<?php echo $origen ?>','<?php echo $cuit ?>')" />
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
		
		$sqlCabJuicios = "select * from cabjuiciosospim where cuit = $cuit";
		$resCabJuicios = mysql_query($sqlCabJuicios,$db); 
		$canCabJuicios = mysql_num_rows($resCabJuicios); 
	
		$controlAcuYJuicios = $CantAcuOspim + $CantAcuUsimra + $canCabJuicios;
		$CanDdjj = 0;
		if ($controlAcuYJuicios == 0) {
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
		if ($controlAcuYJuicios == 0 and $CanDdjj == 0) { ?>
    <input name="bajaEmpresa" type="button" id="bajaEmpresa" value="Bajar Empresa" onClick="validarBaja()"/>
    <?php } ?>
  </p>
  <p>
    <?php
		include("jurisdicEmpresa.php");
	?>
  </p>
  <p>
    <input name="Input5" type="button" value="Disgregacion Dineraria" onClick='location.href="disgregaDinero.php?origen=<?php echo $origen ?>&cuit=<?php echo $cuit ?>"'/>
    <input name="Input4" type="button" value="Agregar Jurisdiccion" onclick='location.href="nuevaJurisdiccion.php?origen=<?php echo $origen ?>&cuit=<?php echo $cuit ?>"'/>
  </p>
  <p>
    <input type="button" name="imprimir" value="Imprimir" onClick="window.print();"/>
  </p>
</div>
</body>
</html>
