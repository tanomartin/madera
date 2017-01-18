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
		
		$dbhEmail->exec($sqlEmailCabecera);
		$lastId = $dbhEmail->lastInsertId();
		
		if ($attachment != null) {
			foreach ($attachment as $file) {
				$sqlEmailAdjunto = "INSERT INTO bandejasalidaadjuntos VALUES(DEFAULT, $lastId, '$file')";
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

function reenviarEmail($idEmail) {
	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbhEmail = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbhEmail->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbhEmail->beginTransaction();
		
		$sqlGetEnviado = "SELECT * FROM bandejaenviados WHERE id = $idEmail";
		$rowGetEnviado = $dbhEmail->query($sqlGetEnviado)->fetch();
		
		$moduloReenvio = "Reenvio - ".$rowGetEnviado['modulocreador'];
		$sqlEmailCabecera = "INSERT INTO bandejasalida VALUES(".$rowGetEnviado['id'].", '".$rowGetEnviado['from']."', '".$rowGetEnviado['subject']."', '".$rowGetEnviado['body']."', '".$rowGetEnviado['address']."', '$moduloReenvio', '".$rowGetEnviado['fecharegistro']."', '".$rowGetEnviado['usuarioregistro']."')";
		$dbhEmail->exec($sqlEmailCabecera);

		$sqlDeleteEnviados = "DELETE FROM bandejaenviados WHERE id = $idEmail";
		$dbhEmail->exec($sqlDeleteEnviados);
		
		$dbhEmail->commit();
		return $rowGetEnviado['id'];
		
	} catch (PDOException $e) {
		echo $e->getMessage();
		$dbhEmail->rollback();
		return -1;
	}
}

?>