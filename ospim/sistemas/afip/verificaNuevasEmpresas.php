<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."fechas.php");
include($_SERVER['DOCUMENT_ROOT']."/lib/envioMailGeneral.php"); 
$nrodisco=$_GET['nroDis'];
$codigotipo = 0;
$codpertene = 3;
$obsospim = "Alta registrada por el procesamiento de archivos de AFIP.";
$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];
$fechamodificacion = $fecharegistro;
$usuariomodificacion = $usuarioregistro;
$mirroring = "N";
$codidelega = 3200;
$disgdinero = 100;

//conexion y creacion de transaccion.
try{
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	$sqlLeePadronDDJJ = "SELECT * FROM afippadrones WHERE nrodisco = '$nrodisco'";
	$resLeePadronDDJJ = $dbh->query($sqlLeePadronDDJJ);
	if($resLeePadronDDJJ) {
		$nuevasempresas = 0;
		foreach($resLeePadronDDJJ as $empresasddjj) {
			$sqlLeeEmpresa = "SELECT * FROM empresas WHERE cuit = '$empresasddjj[cuit]'";
			$resLeeEmpresa = mysql_query($sqlLeeEmpresa,$db);
			if(mysql_num_rows($resLeeEmpresa)==0) {
				$sqlLeeEmpresadebaja = "SELECT * FROM empresasdebaja WHERE cuit = '$empresasddjj[cuit]'";
				$resLeeEmpresadebaja = mysql_query($sqlLeeEmpresadebaja,$db);
				if(mysql_num_rows($resLeeEmpresadebaja)==0) {
					$codprovin = 99;
					$indpostal = 0;
					$codlocali = 0;
					$domilegal = $empresasddjj[calle]." ".$empresasddjj[numero]." ".$empresasddjj[piso]." ".$empresasddjj[depto];

					$sqlLeeProvincia = "SELECT * FROM provincia WHERE codafip = '$empresasddjj[provincia]'";
					$resLeeProvincia = mysql_query($sqlLeeProvincia,$db);
					if(mysql_num_rows($resLeeProvincia)!=0) {
						$rowLeeProvincia = mysql_fetch_array($resLeeProvincia);
						$codprovin = $rowLeeProvincia['codprovin'];
						$indpostal = $rowLeeProvincia['indpostal'];

						$sqlLeeLocalidad = "SELECT * FROM localidades WHERE codprovin = '$codprovin' AND numpostal = '$empresasddjj[codigopostal]'";
						$resLeeLocalidad = mysql_query($sqlLeeLocalidad,$db);
						if(mysql_num_rows($resLeeLocalidad)!=0) {
							while($rowLeeLocalidad = mysql_fetch_array($resLeeLocalidad)) {
								if(strcmp($empresasddjj[localidad],$rowLeeLocalidad['nomlocali'])==0) {
									$codlocali = $rowLeeLocalidad['codlocali'];
									break;
								} else {
									if(strncmp($empresasddjj[localidad],$rowLeeLocalidad['nomlocali'],10)==0) {
										$codlocali = $rowLeeLocalidad['codlocali'];
										break;
									}
								}
							}
						}
					}

					$sqlAddEmpresa = "INSERT INTO empresas (cuit,nombre,codprovin,indpostal,numpostal,codlocali,domilegal,codigotipo,codpertene,obsospim,fecharegistro,usuarioregistro,fechamodificacion,usuariomodificacion,mirroring) VALUES (:cuit,:nombre,:codprovin,:indpostal,:numpostal,:codlocali,:domilegal,:codigotipo,:codpertene,:obsospim,:fecharegistro,:usuarioregistro,:fechamodificacion,:usuariomodificacion,:mirroring)";
					$resAddEmpresa = $dbh->prepare($sqlAddEmpresa);
					if($resAddEmpresa->execute(array(':cuit' => $empresasddjj[cuit],':nombre' => $empresasddjj[nombre],':codprovin' => $codprovin,':indpostal' => $indpostal,':numpostal' => $empresasddjj[codigopostal],':codlocali' => $codlocali,':domilegal' => $domilegal,':codigotipo' => $codigotipo,':codpertene' => $codpertene,':obsospim' => $obsospim,':fecharegistro' => $fecharegistro,':usuarioregistro' => $usuarioregistro,':fechamodificacion' => $fechamodificacion,':usuariomodificacion' => $usuariomodificacion,':mirroring' => $mirroring))) {
						$sqlAddJurisdiccion = "INSERT INTO jurisdiccion (cuit,codidelega,codprovin,indpostal,numpostal,codlocali,domireal,disgdinero) VALUES (:cuit,:codidelega,:codprovin,:indpostal,:numpostal,:codlocali,:domireal,:disgdinero)";
						$resAddJurisdiccion = $dbh->prepare($sqlAddJurisdiccion);
						if($resAddJurisdiccion->execute(array(':cuit' => $empresasddjj[cuit],':codidelega' => $codidelega,':codprovin' => $codprovin,':indpostal' => $indpostal,':numpostal' => $empresasddjj[codigopostal],':codlocali' => $codlocali,':domireal' => $domilegal,':disgdinero' => $disgdinero))) {
							$nuevasempresas++;
							$empresasAltas[$nuevasempresas] = array('cuit' => $empresasddjj[cuit], 'nombre' => $empresasddjj[nombre]);
							//echo("Nueva Empresa ".$nuevasempresas.": Disco ".$empresasddjj[nrodisco]." Registro ".$empresasddjj[nroregistro]." CUIT ".$empresasddjj[cuit]." Razon Social ".$empresasddjj[nombre]); echo "<br>";
						}
					}
				}
			}
		}
	}

	$verificaempresasospim = 1;
	$sqlUpdatePadronDDJJ = "UPDATE padronesddjj SET verificaempresasospim = :verificaempresasospim, fechaverificaempresasospim = :fechaverificaempresasospim, altasempresasospim = :altasempresasospim WHERE nrodisco = :nrodisco";
	$resUpdatePadronDDJJ = $dbh->prepare($sqlUpdatePadronDDJJ);
	if($resUpdatePadronDDJJ->execute(array(':verificaempresasospim' => $verificaempresasospim,':fechaverificaempresasospim' => $fecharegistro,':altasempresasospim' => $nuevasempresas,':nrodisco' => $nrodisco)))
	{
	}

	$dbh->commit();

	//ENVIO DE MAIL AVISO A GOMEZ
	if($nuevasempresas>0) {
		//echo("Envia Email"); echo "<br>";
		$username = "sistemas@ospim.com.ar";
		$passw = "pepepascual";
		$fromRepli = "Sistemas O.S.P.I.M.";
		$subject = "Se han generado ALTAS automaticas de empresas en el sistema.";
		$bodymail="<body><br><br>Este es un mensaje de Aviso.<br><br>El procesamiento de archivos de AFIP ha generado el ALTA de <strong>".$nuevasempresas."</strong> nuevas empresas en el sistema segun el siguiente detalle:<br><br>";
		
		foreach($empresasAltas as $altas) {
			$bodymail.="CUIT: ".$altas[cuit]." - Razon Social: ".$altas[nombre]."<br>";
		}

		$bodymail.="<br><br>Las mismas han sido incorporadas en el ambito jurisdiccional de la Delegacion Auxiliar (3200), por favor verifique esta informacion para establecer el verdadero ambito jurisdiccional de las mismas.<br><br><br><br>Depto. de Sistemas<br>O.S.P.I.M.<br>";	
		$address = "jlgomez@usimra.com.ar";
		envioMail($username, $passw, $fromRepli, $subject, $bodymail, $address);
		//$address = "balbonetti@ospim.com.ar";
		//envioMail($username, $passw, $fromRepli, $subject, $bodymail, $address);
	}

	$pagina = "menuafip.php";
	Header("Location: $pagina");

}
catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}
?>