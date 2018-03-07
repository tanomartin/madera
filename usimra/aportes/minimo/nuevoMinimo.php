<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php"); 
include($libPath."claves.php"); 

$maquina = $_SERVER ['SERVER_NAME'];
$hostaplicativo = $hostUsimra;
if(strcmp("localhost",$maquina)==0) {
	$hostaplicativo = "localhost";
}
$usuarioaplicativo = $usuarioUsimra;
$claveaplicativo = $claveUsimra;
$dbaplicativo =  mysql_connect($hostaplicativo, $usuarioaplicativo, $claveaplicativo);
if (!$dbaplicativo) {
	die('No pudo conectarse: ' . mysql_error());
}
$dbnameaplicativo = $baseUsimraNewAplicativo;
mysql_select_db($dbnameaplicativo);

$cuit = $_POST['cuit'];
$sqlEmpresa = "SELECT * FROM empresa WHERE nrcuit = '$cuit'";
$resEmpresa = mysql_query($sqlEmpresa, $dbaplicativo);
$canEmpresa = mysql_num_rows($resEmpresa);

if ($canEmpresa == 0) {
	$pagina = "moduloMinimo.php?err=1";
	Header("Location: $pagina");
	exit (0);
} else {
	$sqlEmpresaMinimo = "SELECT * FROM empresassinminimo WHERE nrcuit = '$cuit'";
	$resEmpresaMinimo = mysql_query($sqlEmpresaMinimo, $dbaplicativo);
	$canEmpresaMinimo = mysql_num_rows($resEmpresaMinimo);
	if ($canEmpresaMinimo != 0) {
		$pagina = "moduloMinimo.php?err=2";
		Header("Location: $pagina");
		exit (0);
	} else {
		$rowEmpresa = mysql_fetch_array($resEmpresa);
	}
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Nuevo Minimo DDJJ :.</title>
<link rel="stylesheet" href="/madera/lib/tablas.css"/>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>

<script language="javascript" type="text/javascript">

function cartelBlock() {
	$.blockUI({ message: "<h1>Autorizando Nueva Empresa. <br> Aguarde por favor</h1>" });
	return true;
}

</script>

</head>
<body bgcolor="#B2A274">
	<div align="center">	
		<p><input type="button" name="volver" value="Volver" onclick="location.href = 'moduloMinimo.php'" /></p> 
		<form id="minimo" name="minimo" method="post" action="guardarNuevoMinimo.php" onsubmit="cartelBlock()">
			<input type="text" value="<?php echo $rowEmpresa['nrcuit'] ?>" id="cuit" name="cuit" style="display: none"/>
			<h3>Habilitadar Empresa</h3>
	 		<div class="grilla">
	 			<table>
	 				<thead>
	 					<tr>
	 						<th>C.U.I.T.</th>
	 						<th>Razon Social</th>
	 					</tr>
	 				</thead>
					<tbody>
						<tr>
							<td><?php echo $rowEmpresa['nrcuit'] ?></td>
							<td><?php echo $rowEmpresa['nombre'] ?></td>
						</tr>
					</tbody>
		  		</table>
		  	</div>
		  	<p><input type="submit" name="Submit" value="Habilitar" /></p>
		</form>
	</div>
</body>
</html>