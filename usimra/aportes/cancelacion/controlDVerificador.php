<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
if(isset($_POST) && !empty($_POST) && isset($_POST['convenio']) && isset($_POST['cuitjvs']) && isset($_POST['idboljvs']))
{
	$respuesta = NULL;
	$ncuasifinal = $_POST['convenio'].$_POST['cuitjvs'].$_POST['idboljvs'];
	$npart3total = 0;
	$npart1total = 0;
	for ($i=0; $i < 29; $i++) {
		$npor3 = substr($ncuasifinal,$i,1);
		$npor33 = $npor3 * 3;
		$npart3total = $npart3total + $npor33;
		$i = $i + 1;
		$npor1 = substr($ncuasifinal,$i,1);
		$npart1total = $npart1total + $npor1;
	}
	$npartot = $npart1total + $npart3total;
	$largonpar = strlen($npartot);
	$ndigito = $largonpar -1;
	$nverifi01 = substr($npartot,$ndigito,1);
	if ($nverifi01 == 0) {
		$respuesta = 0;
	} else {
		$respuesta = 10 - $nverifi01;
	}
	echo json_encode($respuesta);
}
?>