<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
if(isset($_GET)) {
	$idcomprobante = $_GET['idComprobante'];
	$nroafiliado = $_GET['nroAfiliado'];
	$tipoafiliado = $_GET['tipoAfiliado'];
	$nroorden = $_GET['nroOrden'];
	$codidelega = $_GET['codiDelega'];
	$excepcionjurisdiccion = 0;
	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();

		$sqlConsultaFactura = "SELECT idPrestador FROM facturas WHERE id = $idcomprobante";
		$resConsultaFactura = mysql_query($sqlConsultaFactura,$db);
		$rowConsultaFactura = mysql_fetch_array($resConsultaFactura);

		$sqlConsultaJurisdiccion = "SELECT * FROM prestadorjurisdiccion WHERE codigoprestador = $rowConsultaFactura[idPrestador] AND codidelega = $codidelega";
		$resConsultaJurisdiccion = mysql_query($sqlConsultaJurisdiccion,$db);
		$existejurisdiccion = mysql_num_rows($resConsultaJurisdiccion);
		
		if($existejurisdiccion == 0) {
			$excepcionjurisdiccion = 1;
		}

		$sqlAddFacturasBeneficiarios = "INSERT INTO facturasbeneficiarios(id,idFactura,nroafiliado,tipoafiliado,nroorden,totalfacturado,totaldebito,totalcredito,excepcionjurisdiccion,exceptuado,consumoprestacional) VALUES(:id,:idFactura,:nroafiliado,:tipoafiliado,:nroorden,:totalfacturado,:totaldebito,:totalcredito,:excepcionjurisdiccion,:exceptuado,:consumoprestacional)";
		$resAddFacturasBeneficiarios = $dbh->prepare($sqlAddFacturasBeneficiarios);
		if($resAddFacturasBeneficiarios->execute(array(':id' => 'DEFAULT', ':idFactura' => $idcomprobante, ':nroafiliado' => $nroafiliado, ':tipoafiliado' => $tipoafiliado, ':nroorden' => $nroorden, ':totalfacturado' => 'DEFAULT', ':totaldebito' => 'DEFAULT', ':totalcredito' => 'DEFAULT', ':excepcionjurisdiccion' => $excepcionjurisdiccion, ':exceptuado' => 'DEFAULT', ':consumoprestacional' => 'DEFAULT')))
		$dbh->commit();
		echo json_encode(array('result'=> true));
	}
	catch (PDOException $e) {
		$dbh->rollback();
		echo json_encode(array('result'=> false));
	}
	return; 
}  
?>