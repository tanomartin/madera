<?php

function guardarEmail($username, $subject, $bodymail, $address, $modulo, $attachment) {
	$fecharegistro = date("Y-m-d H:i:s");
	$usuarioregistro = $_SESSION['usuario'];
	$sqlEmailCabecera = "INSERT INTO bandejasalida VALUES(DEFAULT, '$username', '$subject', '$bodymail', '$address', '$modulo', 0, null, '$fecharegistro', '$usuarioregistro')";
	
	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();
		
		//echo ($sqlEmailCabecera."<br>");
		$dbh->exec($sqlEmailCabecera);
		$lastId = $dbh->lastInsertId();
		
		if ($attachment != null) {
			foreach ($attachment as $file) {
				$sqlEmailAdjunto = "INSERT INTO bandejasalidaadjuntos VALUES(DEFAULT, $lastId, '$file')";
				//echo ($sqlEmailAdjunto."<br>");
				$dbh->exec($sqlEmailAdjunto);
			}
		}
			
		$dbh->commit();
		return $lastId;
		
	} catch (PDOException $e) {
		echo $e->getMessage();
		$dbh->rollback();
		return -1;
	}
	
}

?>