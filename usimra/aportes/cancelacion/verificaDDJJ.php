<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
if(isset($_POST) && !empty($_POST) && isset($_POST['cuitbarra']) && isset($_POST['controlbarra'])) {
	$nrcuit=$_POST['cuitbarra'];
	$nrctrl=$_POST['controlbarra'];
	$respuesta = array("permes" => NULL, "mesnombre" => NULL, "perano" => NULL, "remune" => NULL, "apo060" => NULL, "apo100" => NULL, "apo150" => NULL, "recarg" => NULL, "totapo" => NULL, "nfilas" => NULL, "observ" => NULL);
	$sqlBuscaDDJJ="SELECT d.permes, p.descripcion AS mesnombre, d.perano, d.remune, d.apo060, d.apo100, d.apo150, d.recarg, d.totapo, d.nfilas, d.observ FROM ddjjusimra d, periodosusimra p WHERE d.nrcuit = '$nrcuit' AND d.nrcuil = '99999999999' AND d.nrctrl = '$nrctrl' AND d.perano = p.anio AND d.permes = p.mes";
	$resBuscaDDJJ=mysql_query($sqlBuscaDDJJ,$db);
	if(mysql_num_rows($resBuscaDDJJ)==1) {
		$rowBuscaDDJJ=mysql_fetch_array($resBuscaDDJJ);
		$respuesta = array("permes" => $rowBuscaDDJJ['permes'], "mesnombre" => $rowBuscaDDJJ['mesnombre'], "perano" => $rowBuscaDDJJ['perano'], "remune" => $rowBuscaDDJJ['remune'], "apo060" => $rowBuscaDDJJ['apo060'], "apo100" => $rowBuscaDDJJ['apo100'], "apo150" => $rowBuscaDDJJ['apo150'], "recarg" => $rowBuscaDDJJ['recarg'], "totapo" => ($rowBuscaDDJJ['totapo']+$rowBuscaDDJJ['recarg']), "nfilas" => $rowBuscaDDJJ['nfilas'], "observ" => $rowBuscaDDJJ['observ']);
	}
	echo json_encode($respuesta);
}
?>