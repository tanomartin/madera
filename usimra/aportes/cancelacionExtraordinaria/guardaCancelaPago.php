<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php");
if(isset($_POST) && !empty($_POST) && isset($_GET) && !empty($_GET))
{
	//var_dump($_GET);
	//var_dump($_POST);
	$fechacancelacion=date("Y-m-d H:i:s");
	$fechasubida=date("Y-m-d");
	$usuariocancelacion=$_SESSION['usuario'];
	$sistemacancelacion='M';
	$fechamodificacion="0000-00-00 00:00:00";
	$usuariomodificacion='';
	$estadoconciliacion=0;

	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();

		$ultimopago=0;
		$sqlBuscaUltimoPago="SELECT nropago FROM cuotaextraordinariausimra WHERE cuit = :cuit AND anopago = :anopago AND mespago = :mespago ORDER BY nropago DESC LIMIT 1";
		$resBuscaUltimoPago=$dbh->prepare($sqlBuscaUltimoPago);
		$resBuscaUltimoPago->execute(array(':cuit' => $_GET['cuit'], ':anopago' => $_POST['anopago'], ':mespago' => $_POST['mespago']));
		if($resBuscaUltimoPago) {
			foreach($resBuscaUltimoPago as $pagofinal) {
				$ultimopago=$pagofinal[nropago];
			}
		}
		$ultimopago=$ultimopago+1;
		$cuil="99999999999";
		if(!empty($_POST['codigobarra'])) {
			$codigobarra=$_POST['codigobarra'];
			$nrctrl=substr($_POST['codigobarra'],15,14);
		} else {
			$codigobarra='';
		}

		$sqlAgregaPago="INSERT INTO cuotaextraordinariausimra (cuit,mespago,anopago,nropago,fechapago,cantidadaportantes,totalaporte,montorecargo,montopagado,observaciones,sistemacancelacion,codigobarra,fechaacreditacion,fecharegistro,usuarioregistro,fechamodificacion,usuariomodificacion) VALUES (:cuit,:mespago,:anopago,:nropago,:fechapago,:cantidadaportantes,:totalaporte,:montorecargo,:montopagado,:observaciones,:sistemacancelacion,:codigobarra,:fechaacreditacion,:fecharegistro,:usuarioregistro,:fechamodificacion,:usuariomodificacion)";
		$resAgregaPago = $dbh->prepare($sqlAgregaPago);
		if($resAgregaPago->execute(array(':cuit' =>  $_GET['cuit'], ':mespago' => $_POST['mespago'], ':anopago' => $_POST['anopago'], ':nropago' => $ultimopago,':fechapago' => fechaParaGuardar($_POST['fechapago']), ':cantidadaportantes' => $_POST['cantidadaportantes'], ':totalaporte' => $_POST['totalaporte'], ':montorecargo' => $_POST['montorecargo'], ':montopagado' => $_POST['montopagado'], ':observaciones' => $_POST['observaciones'], ':sistemacancelacion' => $sistemacancelacion, ':codigobarra' => $codigobarra, ':fechaacreditacion' => $fechacancelacion, ':fecharegistro' => $fechacancelacion, ':usuarioregistro' => $usuariocancelacion, ':fechamodificacion' => $fechamodificacion, ':usuariomodificacion' => $usuariomodificacion))) {
			if(strcmp($_POST['ddjjvalidada'],"1")==0) {
				$sqlBuscaCabDDJJ="SELECT * FROM ddjjusimra WHERE nrcuit = :nrcuit AND nrcuil = :nrcuil AND nrctrl = :nrctrl";
				$resBuscaCabDDJJ=$dbh->prepare($sqlBuscaCabDDJJ);
				$resBuscaCabDDJJ->execute(array(':nrcuit' =>  $_GET['cuit'], ':nrcuil' => $cuil, ':nrctrl' => $nrctrl));
				if($resBuscaCabDDJJ) {
					foreach($resBuscaCabDDJJ as $cabddjj) {
						$sqlAgregaCabDDJJ="INSERT INTO cabddjjusimra (id,cuit,cuil,mesddjj,anoddjj,remuneraciones,apor060,apor100,apor150,totalaporte,recargo,cantidadpersonal,instrumentodepago,nrocontrol,observaciones,fechasubida) VALUES (:id,:cuit,:cuil,:mesddjj,:anoddjj,:remuneraciones,:apor060,:apor100,:apor150,:totalaporte,:recargo,:cantidadpersonal,:instrumentodepago,:nrocontrol,:observaciones,:fechasubida)";
						$resAgregaCabDDJJ = $dbh->prepare($sqlAgregaCabDDJJ);
						if($resAgregaCabDDJJ->execute(array(':id' => $cabddjj[id], ':cuit' => $cabddjj[nrcuit], ':cuil' => $cabddjj[nrcuil], ':mesddjj' => $cabddjj[permes], ':anoddjj' => $cabddjj[perano], ':remuneraciones' => $cabddjj[remune], ':apor060' => $cabddjj[apo060], ':apor100' => $cabddjj[apo100], ':apor150' => $cabddjj[apo150], ':totalaporte' => $cabddjj[totapo], ':recargo' => $cabddjj[recarg], ':cantidadpersonal' => $cabddjj[nfilas], ':instrumentodepago' => $cabddjj[instrumento], ':nrocontrol' => $cabddjj[nrctrl], ':observaciones' => $cabddjj[observ], ':fechasubida' => $fechasubida))) {
						}
					}
				}
				$sqlBuscaDetDDJJ="SELECT * FROM ddjjusimra WHERE nrcuit = :nrcuit AND nrcuil != :nrcuil AND nrctrl = :nrctrl";
				$resBuscaDetDDJJ=$dbh->prepare($sqlBuscaDetDDJJ);
				$resBuscaDetDDJJ->execute(array(':nrcuit' =>  $_GET['cuit'], ':nrcuil' => $cuil, ':nrctrl' => $nrctrl));
				if($resBuscaDetDDJJ) {
					foreach($resBuscaDetDDJJ as $detddjj) {
						$sqlAgregaDetDDJJ="INSERT INTO detddjjusimra (id,cuit,cuil,mesddjj,anoddjj,remuneraciones,apor060,apor100,apor150,nrocontrol,fechasubida) VALUES (:id,:cuit,:cuil,:mesddjj,:anoddjj,:remuneraciones,:apor060,:apor100,:apor150,:nrocontrol,:fechasubida)";
						$resAgregaDetDDJJ = $dbh->prepare($sqlAgregaDetDDJJ);
						if($resAgregaDetDDJJ->execute(array(':id' => $detddjj[id], ':cuit' => $detddjj[nrcuit], ':cuil' => $detddjj[nrcuil], ':mesddjj' => $detddjj[permes], ':anoddjj' => $detddjj[perano], ':remuneraciones' => $detddjj[remune], ':apor060' => $detddjj[apo060], ':apor100' => $detddjj[apo100], ':apor150' => $detddjj[apo150], ':nrocontrol' => $detddjj[nrctrl], ':fechasubida' => $fechasubida))) {
						}
					}
				}
				$sqlBorraDDJJ="DELETE FROM ddjjusimra WHERE nrcuit = :nrcuit AND nrctrl = :nrctrl";
				$resBorraDDJJ=$dbh->prepare($sqlBorraDDJJ);
				if($resBorraDDJJ->execute(array(':nrcuit' =>  $_GET['cuit'], ':nrctrl' => $nrctrl))) {
				}
			}
		}
		$cuit=$_GET['cuit'];
		$dbh->commit();
		$pagina = "listaPagos.php?cuit=$cuit";
		Header("Location: $pagina");
	}
	catch (PDOException $e) {
		$error =  $e->getMessage();
		$dbh->rollback();
		$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/usimra/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
		header ($redire);
		exit(0);
	}
}
?>