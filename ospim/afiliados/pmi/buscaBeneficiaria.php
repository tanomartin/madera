<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
//var_dump($_POST);
$ordenbusqueda = $_POST['seleccion'];
$valorbusqueda = $_POST['valor'];
$noexiste = 0;
set_time_limit(0);
$sqlTitular = "SELECT nroafiliado FROM titulares WHERE $ordenbusqueda = '$valorbusqueda' AND sexo = 'F'";
$resTitular = mysql_query($sqlTitular,$db);
if (mysql_num_rows($resTitular)==0) {
	$sqlFamiliar = "SELECT nroafiliado, nroorden FROM familiares WHERE $ordenbusqueda = '$valorbusqueda' AND sexo = 'F'";
	$resFamiliar = mysql_query($sqlFamiliar,$db);
	if (mysql_num_rows($resFamiliar)==0) {
		$noexiste = 1;
	}
	else {
		$rowFamiliar = mysql_fetch_array($resFamiliar);
		$nroafiliado = $rowFamiliar['nroafiliado'];
		$nroorden = $rowFamiliar['nroorden'];
		$tipafiliado = 'F';
	}
}
else {
	$rowTitular = mysql_fetch_array($resTitular);
	$nroafiliado = $rowTitular['nroafiliado'];
	$tipafiliado = 'T';
}


if(strcmp($ordenbusqueda, "nroafiliado")==0) {
	$errorbusqueda = 1;
	$pagina = "moduloPMI.php?nroAfi=$nroafiliado&tipAfi=A";
}

if(strcmp($ordenbusqueda, "nrodocumento")==0) {
	$errorbusqueda = 2;
	if(strcmp($tipafiliado, 'T')==0) {
		$pagina = "moduloPMI.php?nroAfi=$nroafiliado&tipAfi=$tipafiliado";
	} else {
		$pagina = "moduloPMI.php?nroAfi=$nroafiliado&nroOrd=$nroorden&tipAfi=$tipafiliado";
	}
}

if(strcmp($ordenbusqueda, "cuil")==0) {
	$errorbusqueda = 3;
	if(strcmp($tipafiliado, 'T')==0) {
		$pagina = "moduloPMI.php?nroAfi=$nroafiliado&tipAfi=$tipafiliado";
	} else {
		$pagina = "moduloPMI.php?nroAfi=$nroafiliado&nroOrd=$nroorden&tipAfi=$tipafiliado";
	}
}

if ($noexiste == 1)
	header("Location: moduloPMI.php?err=$errorbusqueda");
else
	header("Location: $pagina"); 
?>
