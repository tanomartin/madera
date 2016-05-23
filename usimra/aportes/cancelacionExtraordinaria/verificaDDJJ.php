<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
if(isset($_POST) && !empty($_POST) && isset($_POST['cuitbarra']) && isset($_POST['controlbarra'])) {
	$nrcuit=$_POST['cuitbarra'];
	$nrctrl=$_POST['controlbarra'];
	$respuesta = array("permes" => NULL, "mesnombre" => NULL, "perano" => NULL, "totapo" => NULL, "recarg" => NULL, "totpag" => NULL, "nfilas" => NULL, "observ" => NULL);
	$sqlBuscaDDJJ="SELECT d.permes, p.descripcion AS mesnombre, d.perano, d.totapo, d.recarg, d.nfilas, d.observ FROM ddjjusimra d, periodosusimra p WHERE d.nrcuit = '$nrcuit' AND d.nrcuil = '99999999999' AND d.nrctrl = '$nrctrl' AND d.perano = p.anio AND d.permes = p.mes";
	$resBuscaDDJJ=mysql_query($sqlBuscaDDJJ,$db);
	if(mysql_num_rows($resBuscaDDJJ)==1) {
		$rowBuscaDDJJ=mysql_fetch_array($resBuscaDDJJ);
		$respuesta = array("permes" => $rowBuscaDDJJ['permes'], "mesnombre" => $rowBuscaDDJJ['mesnombre'], "perano" => $rowBuscaDDJJ['perano'], "totapo" => ($rowBuscaDDJJ['totapo'], "recarg" => $rowBuscaDDJJ['recarg'], "totpag" => ($rowBuscaDDJJ['totapo']+$rowBuscaDDJJ['recarg']), "nfilas" => $rowBuscaDDJJ['nfilas'], "observ" => $rowBuscaDDJJ['observ']);
	}
	echo json_encode($respuesta);
}
?>