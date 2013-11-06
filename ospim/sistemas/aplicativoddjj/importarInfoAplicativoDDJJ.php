<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspimSistemas.php"); 

/****************************************************/

function ejectuarInsertEmpresa($sqlCabe, $sqlJuris) {
	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();
		$dbh->exec($sqlCabe);
		$dbh->exec($sqlJuris);
		$dbh->commit();
	} catch (PDOException $e) {
		echo $e->getMessage();
		$dbh->rollback();
	}
}

function ejectuarInsert($sql) {
	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();
		$dbh->exec($sql);
		$dbh->commit();
	} catch (PDOException $e) {
		echo $e->getMessage();
		$dbh->rollback();
	}
}

function ejecutarUpdate($sql) {
	try {
		$hostname = 'ospim.com.ar';
		$dbname = 'uv0472_newaplicativo';
		$usuarioaplicativo = 'uv0472';
		$claveaplicativo = 'trozo299tabea';
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$usuarioaplicativo,$claveaplicativo);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();
		$dbh->exec($sql);
		$dbh->commit();
	} catch (PDOException $e) {
		echo $e->getMessage();
		$dbh->rollback();
	}

}


/***************************************************/



$fecharegistro = date("Y-m-d H:m:s");
$usuarioregistro = $_SESSION['usuario'];
$hostaplicativo = 'ospim.com.ar';
$usuarioaplicativo = 'uv0472';
$claveaplicativo = 'trozo299tabea';
$dbaplicativo =  mysql_connect($hostaplicativo, $usuarioaplicativo, $claveaplicativo);
if (!$dbaplicativo) {
    die('No pudo conectarse: ' . mysql_error());
}
$dbnameaplicativo = 'uv0472_newaplicativo';
mysql_select_db($dbnameaplicativo);

// ***************** IMPORTO LAS EMPRESAS ******************** //
$sqlEmpresas = "select * from empresa where bajada = 0";
print("<br>".$sqlEmpresas."<br>");
$resEmpresas = mysql_query($sqlEmpresas,$dbaplicativo); 
$canEmpresas = mysql_num_rows($resEmpresas); 
if ($canEmpresas > 0) {
	while($rowEmpresas = mysql_fetch_assoc($resEmpresas)) {
		var_dump($rowEmpresas);
		$cuitInsert = $rowEmpresas['nrcuit'];
		$sqlEmpresaInsert = "select cuit from empresas where cuit = $cuitInsert";
		$resEmpresaInsert = mysql_query($sqlEmpresaInsert,$db); 
		$canEmpresaInsert = mysql_num_rows($resEmpresaInsert); 
		if ($canEmpresaInsert > 0) {
			print('EMPRESAS EXISTE<br>');
		} else {
			$sqlEmpresaInsert = "select cuit from empresasdebaja where cuit = $cuitInsert";
			$resEmpresaInsert = mysql_query($sqlEmpresaInsert,$db); 
			$canEmpresaInsert = mysql_num_rows($resEmpresaInsert); 
			if ($canEmpresaInsert > 0) {
				print('EMPRESAS EXISTE DE BAJA<br>');
			} else {
				$codProvin = $rowEmpresas['provin'];
				$sqlIndPos = "select indpostal from provincia where codprovin = $codProvin";
				$resIndPos = mysql_query($sqlIndPos,$db); 
				$rowIndPos = mysql_fetch_assoc($resIndPos);
				
				$nomlocali = $rowEmpresas['locali'];
				$sqlLocali = "select codlocali from localidades where codprovin = $codProvin and nomlocali like '$nomlocali'";
				$resLocali = mysql_query($sqlLocali,$db); 
				$canLocali = mysql_num_rows($resLocali); 
				if ($canLocali == 1) {
					$rowLocali = mysql_fetch_assoc($resLocali);
					$locali = $rowLocali['codlocali'];
				} else {
					$locali = 99;
				}
				
				$sqlInsertCabe = "INSERT INTO empresas VALUE('".$rowEmpresas['nrcuit']."','".$rowEmpresas['nombre']."',".$rowEmpresas['provin'].",'".$rowIndPos['indpostal']."',".$rowEmpresas['copole'].",'','$locali','".$rowEmpresas['domile']."','','".$rowEmpresas['telfon']."','','','','',0,".$rowEmpresas['rramaa'].",'".$rowEmpresas['activi']."','','Importada Por Sistemas','','".$rowEmpresas['fecini']."','".$rowEmpresas['emails']."','','$fecharegistro','$usuarioregistro','','',DEFAULT)";
				print($sqlInsertCabe."<br>");
	
				$sqlInsertJuris = "INSERT INTO jurisdiccion VALUE('".$rowEmpresas['nrcuit']."','3200',".$rowEmpresas['provin'].",'".$rowIndPos['indpostal']."',".$rowEmpresas['copole'].",'',$locali,'".$rowEmpresas['domile']."','','".$rowEmpresas['telfon']."','','".$rowEmpresas['emails']."',100)";
				print($sqlInsertJuris."<br>");
				ejectuarInsertEmpresa($sqlInsertCabe,$sqlInsertJuris);
			}
		}
		$sqlUpdateBajadaEmpresa = "UPDATE empresa SET bajada = 1 WHERE nrcuit = $cuitInsert";
		print($sqlUpdateBajadaEmpresa."<br>");
		ejecutarUpdate($sqlUpdateBajadaEmpresa);
	}
} else {
	print('NO HAY EMPRESAS A IMPORTAR<br>');
}

// *************************************************************** //


// ***************** IMPORTO LAS EMPLEADOS ******************** //
$sqlEmpleados = "select * from empleados where bajada = 0";
print("<br>".$sqlEmpleados."<br>");
$resEmpleados = mysql_query($sqlEmpleados,$dbaplicativo); 
$canEmpleados = mysql_num_rows($resEmpleados); 
if ($canEmpleados > 0) {
	while ($rowEmpleados = mysql_fetch_assoc($resEmpleados)) {
		var_dump($rowEmpleados);
		$cuilInsert = $rowEmpleados['nrcuil'];
		$sqlEmpleadoInsert = "select nrcuil from empleadosusimra where nrcuil = $cuilInsert";
		$resEmpleadoInsert = mysql_query($sqlEmpleadoInsert,$db); 
		$canEmpleadoInsert = mysql_num_rows($resEmpleadoInsert); 
		if ($canEmpleadoInsert > 0) {
			print('EMPLEADO EXISTE<br>');
		} else {
			$sqlEmpleadoInsert = "select nrcuil from empleadosdebajausimra where nrcuil = $cuilInsert";
			$resEmpleadoInsert = mysql_query($sqlEmpleadoInsert,$db); 
			$canEmpleadoInsert = mysql_num_rows($resEmpleadoInsert); 
			if ($canEmpleadoInsert > 0) {
				print('EMPLEADO EXISTE DE BAJA<br>');
			} else {
				$sqlInsertTitu = "INSERT INTO empleadosusimra VALUE(
				'".$rowEmpleados['nrcuit']."','".$rowEmpleados['nrcuil']."','".$rowEmpleados['apelli']."','".$rowEmpleados['nombre']."','".$rowEmpleados['fecing']."',
				'".$rowEmpleados['tipdoc']."','".$rowEmpleados['nrodoc']."','".$rowEmpleados['ssexxo']."','".$rowEmpleados['fecnac']."','".$rowEmpleados['estciv']."',
				'".$rowEmpleados['direcc']."','".$rowEmpleados['locale']."','".$rowEmpleados['copole']."','".$rowEmpleados['provin']."','".$rowEmpleados['nacion']."',
				'".$rowEmpleados['catego']."','".$rowEmpleados['activo']."','1')";
				print($sqlInsertTitu."<br>");
				ejectuarInsert($sqlInsertTitu);
			}
		}
		
		$sqlUpdateBajadaEmpleados = "UPDATE empleados SET bajada = 1 WHERE nrcuil = $cuilInsert";
		print($sqlUpdateBajadaEmpleados."<br>");
		ejecutarUpdate($sqlUpdateBajadaEmpleados);
	}
} else {
	print('NO HAY EMPLEADOS A IMPORTAR<br>');
}
// *************************************************************** //


// ***************** IMPORTO LAS FAMILIA ******************** //
$sqlFamiliar = "select * from familia where bajada = 0";
print("<br>".$sqlFamiliar."<br>");
$resFamiliar = mysql_query($sqlFamiliar,$dbaplicativo); 
$canFamiliar = mysql_num_rows($resFamiliar); 
if ($canFamiliar > 0) {
	while($rowFamiliar = mysql_fetch_assoc($resFamiliar)) {
		var_dump($rowFamiliar);
		$idFamiliaInsert = $rowFamiliar['id'];
		$sqlFamiliaInsert = "select nrcuil from familiausimra where id = $idFamiliaInsert";
		$resFamiliaInsert = mysql_query($sqlFamiliaInsert,$db); 
		$canFamiliaInsert = mysql_num_rows($resFamiliaInsert); 
		if ($canFamiliaInsert > 0) {
			print('FAMILIA EXISTE<br>');
		} else {
			$sqlFamiliaInsert = "select nrcuil from familiadebajausimra where id = $idFamiliaInsert";
			$resFamiliaInsert = mysql_query($sqlFamiliaInsert,$db); 
			$canFamiliaInsert = mysql_num_rows($resFamiliaInsert); 
			if ($canFamiliaInsert > 0) {
				print('FAMILIA EXISTE DE BAJA<br>');
			} else {
				$sqlInsertFami = "INSERT INTO familiausimra VALUE(
				'".$rowFamiliar['id']."','".$rowFamiliar['nrcuit']."','".$rowFamiliar['nrcuil']."','".$rowFamiliar['nombre']."','".$rowFamiliar['apelli']."',
				'".$rowFamiliar['codpar']."','".$rowFamiliar['ssexxo']."','".$rowFamiliar['fecnac']."','".$rowFamiliar['fecing']."','".$rowFamiliar['tipdoc']."',
				'".$rowFamiliar['nrodoc']."','".$rowFamiliar['benefi']."','1')";
				print($sqlInsertFami."<br>");
				ejectuarInsert($sqlInsertFami);
			}
		}
		
		$sqlUpdateBajadaFamilia = "UPDATE familia SET bajada = 1 WHERE id = $idFamiliaInsert";
		print($sqlUpdateBajadaFamilia."<br>");
		ejecutarUpdate($sqlUpdateBajadaFamilia);
	}
} else {
	print('NO HAY FAMILIARES A IMPORTAR<br>');
}
// *************************************************************** //


// ***************** IMPORTO LAS EMPLEADOS DE BAJA ******************** //
// TODO
// *************************************************************** //


// ***************** IMPORTO LAS FAMILIA DE BAJA ******************** //
// TODO
// *************************************************************** //

?>