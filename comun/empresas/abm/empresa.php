<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSession.php");

if (isset($_GET['cuit'])) {
	$cuit=$_GET['cuit'];
} else {
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
		//TODO: ver los titulares de usimra
		$sqlTitulares = "select nroafiliado from titulares where cuitempresa = $cuit";
		$resTitulares = mysql_query($sqlTitulares,$db); 
		$canTitulares = mysql_num_rows($resTitulares); 
		if ($canTitulares > 0) { ?>
			if (confirm('Hay titulares activos para esta empresa.\nQuiere confirmar la baja')) {
				alert('Se dará de baja la empresa con sus titulares y sus respectivos familiares');
				location.href="confirmaBajaEmpresa.php?origen=<?php echo $origen ?>&cuit=<?php echo $cuit ?>";
				document.getElementById('bajaEmpresa').disabled = true;
			} else {
				alert('No se dará de baja la empresa ni sus titulares asociados');
			}
 <?php } else { ?>
 	   		location.href="confirmaBajaEmpresa.php?origen=<?php echo $origen ?>&cuit=<?php echo $cuit ?>";
 	   		document.getElementById('bajaEmpresa').disabled = true;
 <?php }   ?>
}

function rediSabanaCtaCte(origen, cuit) {
	$.blockUI({ message: "<h1>Generando Cuenta Corriente... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
	var dire = "";
	if (origen == "ospim") {
		dire = 'cuentas/cuentaCorrienteOspim.php?cuit='+cuit;
	}
	if (origen == "usimra") {
		dire = 'cuentas/cuentaCorrienteUsimra.php?cuit='+cuit;
	}
	location.href = dire;
}

function rediBeneficiarios(origen, cuit) {
	var dire = "";
	if (origen == "ospim") { 
		dire = "/madera/ospim/afiliados/informes/titularesPorEmpresa.php?cuit="+cuit;
	}
	if (origen == "usimra") { 
		dire = "/madera/usimra/empleados/informes/listadoTitularesPorEmpresa.php?cuit="+cuit;
	}
	a= window.open(dire,"","toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=10, left=10");
}

</script>
<title>.: Módulo Empresa :.</title>
</head>
<body bgcolor=<?php echo $bgcolor ?>>
<div align="center">
  <input type="reset" name="volver" value="Volver" onClick="location.href = 'moduloABM.php?origen=<?php echo $origen ?>'"/>
  <p>
    <?php 
    	if (isset($_GET['err'])) {
			$err = $_GET['err'];
			if ($err > 0) {
				$sqldelegacion = "select * from delegaciones where codidelega = $err";
				$resultdelegacion = mysql_query($sqldelegacion,$db);
				$rowdelegacion = mysql_fetch_array($resultdelegacion);
				echo("<div align='center' style='color:#FF0000'><b> ERROR JURISDICCION EXISTENTE </b></div>");
				echo("<div align='center' style='color:#FF0000'><b> NO SE PUEDE CARGAR LA JURISDICCION </b></div>");
				echo("<div align='center' style='color:#FF0000'><b>".$rowdelegacion['nombre']." </b></div>");
			}
    	}
    	if (isset($_GET['reactiva'])) {
			$reactiva = $_GET['reactiva'];
			if ($reactiva == 1) {
				echo("<h2 class='Estilo1'><div align='center' style='color:#006666'><b> EMPRESA REACTIVADA </b></div> </h2>");
			}
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
  <?php if (isset($_GET['bajaempre'])) { ?>  <font color="red"><b>No se puede dar de baja la empresa</b></font>  <?php } else { ?>
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
