<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
//var_dump($_POST);
$ordenbusqueda = $_POST['seleccion'];
$valorbusqueda = $_POST['valor'];
$noexistebeneficiario = 0;
set_time_limit(0);

$sqlTitularActivo = "SELECT nroafiliado FROM titulares WHERE $ordenbusqueda = '$valorbusqueda'";
$resTitularActivo = mysql_query($sqlTitularActivo,$db);
if (mysql_num_rows($resTitularActivo)==0) {
	$sqlTitularInactivo = "SELECT nroafiliado FROM titularesdebaja WHERE $ordenbusqueda = '$valorbusqueda'";
	$resTitularInactivo = mysql_query($sqlTitularInactivo,$db);
	if (mysql_num_rows($resTitularInactivo)==0) {
		$sqlFamiliarActivo = "SELECT nroafiliado, nroorden FROM familiares WHERE $ordenbusqueda = '$valorbusqueda'";
		$resFamiliarActivo = mysql_query($sqlFamiliarActivo,$db);
		if (mysql_num_rows($resFamiliarActivo)==0) {
			$sqlFamiliarInactivo = "SELECT nroafiliado, nroorden FROM familiaresdebaja WHERE $ordenbusqueda = '$valorbusqueda'";
			$resFamiliarInactivo = mysql_query($sqlFamiliarInactivo,$db);
			if (mysql_num_rows($resFamiliarInactivo)==0) {
				$noexistebeneficiario = 1;
			} else {
				$rowFamiliarInactivo = mysql_fetch_array($resFamiliarInactivo);
				$nroafiliado = $rowFamiliarInactivo['nroafiliado'];
				$nroorden = $rowFamiliarInactivo['nroorden'];
				$tipafiliado = 'F';
			}
		}
		else {
			$rowFamiliarActivo = mysql_fetch_array($resFamiliarActivo);
			$nroafiliado = $rowFamiliarActivo['nroafiliado'];
			$nroorden = $rowFamiliarActivo['nroorden'];
			$tipafiliado = 'F';
		}
	}
	else {
		$rowTitularInactivo = mysql_fetch_array($resTitularInactivo);
		$nroafiliado = $rowTitularInactivo['nroafiliado'];
		$tipafiliado = 'T';
	}
}
else {
	$rowTitularActivo = mysql_fetch_array($resTitularActivo);
	$nroafiliado = $rowTitularActivo['nroafiliado'];
	$tipafiliado = 'T';
}


if(strcmp($ordenbusqueda, "nroafiliado")==0) {
	$errorbusqueda = 1;
	$pagina = "moduloOncologia.php?nroAfi=$nroafiliado&tipAfi=A";
}

if(strcmp($ordenbusqueda, "nrodocumento")==0) {
	$errorbusqueda = 2;
	if(strcmp($tipafiliado, 'T')==0) {
		$pagina = "moduloOncologia.php?nroAfi=$nroafiliado&tipAfi=$tipafiliado";
	} else {
		$pagina = "moduloOncologia.php?nroAfi=$nroafiliado&nroOrd=$nroorden&tipAfi=$tipafiliado";
	}
}

if(strcmp($ordenbusqueda, "cuil")==0) {
	$errorbusqueda = 3;
	if(strcmp($tipafiliado, 'T')==0) {
		$pagina = "moduloOncologia.php?nroAfi=$nroafiliado&tipAfi=$tipafiliado";
	} else {
		$pagina = "moduloOncologia.php?nroAfi=$nroafiliado&nroOrd=$nroorden&tipAfi=$tipafiliado";
	}
}

if ($noexistebeneficiario == 1)
	header("Location: moduloOncologia.php?err=$errorbusqueda");
else
	header("Location: $pagina"); 
?>
