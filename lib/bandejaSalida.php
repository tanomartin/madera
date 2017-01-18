<?php

function guardarEmail($username, $subject, $bodymail, $address, $modulo, $attachment) {
	$fecharegistro = date("Y-m-d H:i:s");
	$usuarioregistro = $_SESSION['usuario'];
	$sqlEmailCabecera = "INSERT INTO bandejasalida VALUES(DEFAULT, '$username', '$subject', '$bodymail', '$address', '$modulo', '$fecharegistro', '$usuarioregistro')";
	
	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbhEmail = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbhEmail->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbhEmail->beginTransaction();
		
		//echo ($sqlEmailCabecera."<br>");
		$dbhEmail->exec($sqlEmailCabecera);
		$lastId = $dbhEmail->lastInsertId();
		
		if ($attachment != null) {
			foreach ($attachment as $file) {
				$sqlEmailAdjunto = "INSERT INTO bandejasalidaadjuntos VALUES(DEFAULT, $lastId, '$file')";
				//echo ($sqlEmailAdjunto."<br>");
				$dbhEmail->exec($sqlEmailAdjunto);
			}
		}
			
		$dbhEmail->commit();
		return $lastId;
		
	} catch (PDOException $e) {
		echo $e->getMessage();
		$dbhEmail->rollback();
		return -1;
	}
	
}

?>