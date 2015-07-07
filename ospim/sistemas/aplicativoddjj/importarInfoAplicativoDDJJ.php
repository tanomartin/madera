<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."claves.php"); 
set_time_limit(0);
/********************* FUNCIONES *******************************/
function ejectuarDoble($sql1, $sql2) {
	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();
		//print($sql1."<br>");
		$dbh->exec($sql1);
		//print($sql2."<br>");
		$dbh->exec($sql2);
		$dbh->commit();
		return 0;
	} catch (PDOException $e) {
		echo $e->getMessage();
		$dbh->rollback();
		return 1;
	}
}

function ejectuarSimple($sql) {
	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();
		//print($sql."<br>");
		$dbh->exec($sql);
		$dbh->commit();
		return 0;
	} catch (PDOException $e) {
		echo $e->getMessage();
		$dbh->rollback();
		return 1;
	}
}

function ejecutarUpdate($sql) {
	try {
		$hostname = $hostUsimra;
		$dbname = $baseUsimraNewAplicativo;
		$usuarioaplicativo = $usuarioUsimra;
		$claveaplicativo = $claveUsimra;
		$dbhInternet = new PDO("mysql:host=$hostname;dbname=$dbname",$usuarioaplicativo,$claveaplicativo);
		$dbhInternet->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbhInternet->beginTransaction();
		//print($sql."<br>");
		$dbhInternet->exec($sql);
		$dbhInternet->commit();
	} catch (PDOException $e) {
		echo $e->getMessage();
		$dbhInternet->rollback();
	}
}
/****************************************************************/

/*****************************************************/
$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];
$hostaplicativo = $hostUsimra;
$usuarioaplicativo = $usuarioUsimra;
$claveaplicativo = $claveUsimra;
$dbaplicativo =  mysql_connect($hostaplicativo, $usuarioaplicativo, $claveaplicativo);
if (!$dbaplicativo) {
    die('No pudo conectarse: ' . mysql_error());
}
$dbnameaplicativo = $baseUsimraNewAplicativo;
mysql_select_db($dbnameaplicativo);
$listadoIngresadas = array();
/*****************************************************/

// ***************** IMPORTO LAS EMPRESAS ******************** //
$sqlEmpresas = "select * from empresa where bajada = 0";
$resEmpresas = mysql_query($sqlEmpresas,$dbaplicativo); 
$canEmpresas = mysql_num_rows($resEmpresas); 
if ($canEmpresas > 0) {
	$n = 0;
	while($rowEmpresas = mysql_fetch_assoc($resEmpresas)) {
		$result  = 1;
		$cuitInsert = $rowEmpresas['nrcuit'];
		$nombre = $rowEmpresas['nombre'];
		$sqlEmpresaInsert = "select cuit from empresas where cuit = $cuitInsert";
		$resEmpresaInsert = mysql_query($sqlEmpresaInsert,$db); 
		$canEmpresaInsert = mysql_num_rows($resEmpresaInsert); 
		if ($canEmpresaInsert == 0) {
			$sqlEmpresaInsert = "select cuit from empresasdebaja where cuit = $cuitInsert";
			$resEmpresaInsert = mysql_query($sqlEmpresaInsert,$db); 
			$canEmpresaInsert = mysql_num_rows($resEmpresaInsert); 
			if ($canEmpresaInsert == 0) {
				$codProvinApli = $rowEmpresas['provin'];
				$sqlprovin = "select codprovin from provincia where codzeus = $codProvinApli";
				$resprovin = mysql_query($sqlprovin,$db); 
				$canprovin = mysql_num_rows($resprovin); 
				if ($codProvin == 1) {
					$rowprovin = mysql_fetch_assoc($resprovin);
					$codProvin = $rowprovin['codprovin'];
				} else {
					$codProvin = 0;
				}
				
				$sqlIndPos = "select indpostal from provincia where codprovin = $codProvin";
				$resIndPos = mysql_query($sqlIndPos,$db); 
				$canIndPos = mysql_num_rows($resIndPos); 
				if ($canIndPos == 1) {
					$rowIndPos = mysql_fetch_assoc($resIndPos);
					$indPostal = $rowIndPos['indpostal'];
				} else {
					$indPostal = 0;
				}
				
				$nomlocali = $rowEmpresas['locali'];
				$sqlLocali = "select codlocali from localidades where codprovin = $codProvin and nomlocali like '$nomlocali'";
				$resLocali = mysql_query($sqlLocali,$db); 
				$canLocali = mysql_num_rows($resLocali); 
				if ($canLocali == 1) {
					$rowLocali = mysql_fetch_assoc($resLocali);
					$locali = $rowLocali['codlocali'];
				} else {
					$locali = 0;
				}
				$sqlInsertCabe = "INSERT INTO empresas VALUE('".$rowEmpresas['nrcuit']."','".$rowEmpresas['nombre']."',".$rowEmpresas['provin'].",'$indPostal',".$rowEmpresas['copole'].",'','$locali','".$rowEmpresas['domile']."','','".$rowEmpresas['telfon']."','','','','',0,3,'".$rowEmpresas['activi']."','','Importada Por Sistemas','','".$rowEmpresas['fecini']."','".$rowEmpresas['emails']."','','$fecharegistro','$usuarioregistro','','',DEFAULT)";
				$sqlInsertJuris = "INSERT INTO jurisdiccion VALUE('".$rowEmpresas['nrcuit']."','3200',".$rowEmpresas['provin'].",'$indPostal',".$rowEmpresas['copole'].",'',$locali,'".$rowEmpresas['domile']."','','".$rowEmpresas['telfon']."','','".$rowEmpresas['emails']."',100)";
				$result = ejectuarDoble($sqlInsertCabe,$sqlInsertJuris);
				$listadoIngresadas[$n] = array('cuit' => $cuitInsert, 'nombre' => $nombre);
				$n++;
			} else {
				$result = 0;
			}
		} else {
			$result = 0;
		}
		if ($result == 0) { 
			$sqlUpdateBajadaEmpresa = "UPDATE empresa SET bajada = 1 WHERE nrcuit = $cuitInsert";
			ejecutarUpdate($sqlUpdateBajadaEmpresa);
		}
	}
}
// *************************************************************** //

// ***************** IMPORTO LOS EMPLEADOS ******************** //
$sqlEmpleados = "select * from empleados where bajada = 0";
$resEmpleados = mysql_query($sqlEmpleados,$dbaplicativo); 
$canEmpleados = mysql_num_rows($resEmpleados); 
if ($canEmpleados > 0) {
	while ($rowEmpleados = mysql_fetch_assoc($resEmpleados)) {
		$result  = 1;
		$cuilInsert = $rowEmpleados['nrcuil'];
		$sqlEmpleadoInsert = "select nrcuil from empleadosusimra where nrcuil = $cuilInsert";
		$resEmpleadoInsert = mysql_query($sqlEmpleadoInsert,$db); 
		$canEmpleadoInsert = mysql_num_rows($resEmpleadoInsert); 
		if ($canEmpleadoInsert == 0) {
			
			$sqlInsertTitu = "INSERT INTO empleadosusimra VALUE(
			'".$rowEmpleados['nrcuit']."','".$rowEmpleados['nrcuil']."','".$rowEmpleados['apelli']."','".$rowEmpleados['nombre']."','".$rowEmpleados['fecing']."',
			'".$rowEmpleados['tipdoc']."','".$rowEmpleados['nrodoc']."','".$rowEmpleados['ssexxo']."','".$rowEmpleados['fecnac']."','".$rowEmpleados['estciv']."',
			'".$rowEmpleados['direcc']."','".$rowEmpleados['locale']."','".$rowEmpleados['copole']."','".$rowEmpleados['provin']."','".$rowEmpleados['nacion']."',
			'".$rowEmpleados['catego']."','".$rowEmpleados['activo']."','1')";
			
			$sqlEmpleadoInsert = "select nrcuil from empleadosdebajausimra where nrcuil = $cuilInsert";
			$resEmpleadoInsert = mysql_query($sqlEmpleadoInsert,$db); 
			$canEmpleadoInsert = mysql_num_rows($resEmpleadoInsert); 
			if ($canEmpleadoInsert > 0) {
				$sqlDeleteTitu = "DELETE from empleadosdebajausimra where nrcuil = $cuilInsert";
				$result = ejectuarDoble($sqlInsertTitu, $sqlDeleteTitu);
			} else  {
				$result = ejectuarSimple($sqlInsertTitu);
			}
		} else {
			$result = 0;
		}
		if ($result == 0) {
			$sqlUpdateBajadaEmpleados = "UPDATE empleados SET bajada = 1 WHERE nrcuil = $cuilInsert";
			ejecutarUpdate($sqlUpdateBajadaEmpleados);
		}
	}
}
// *************************************************************** //

// ***************** IMPORTO LAS FAMILIA ******************** //
$sqlFamiliar = "select * from familia where bajada = 0";
$resFamiliar = mysql_query($sqlFamiliar,$dbaplicativo); 
$canFamiliar = mysql_num_rows($resFamiliar); 
if ($canFamiliar > 0) {
	while($rowFamiliar = mysql_fetch_assoc($resFamiliar)) {
		$result  = 1;
		$idFamiliaInsert = $rowFamiliar['id'];
		$sqlFamiliaInsert = "select nrcuil from familiausimra where id = $idFamiliaInsert";
		$resFamiliaInsert = mysql_query($sqlFamiliaInsert,$db); 
		$canFamiliaInsert = mysql_num_rows($resFamiliaInsert); 
		if ($canFamiliaInsert == 0) {
			$sqlInsertFami = "INSERT INTO familiausimra VALUE(
			'".$rowFamiliar['id']."','".$rowFamiliar['nrcuit']."','".$rowFamiliar['nrcuil']."','".$rowFamiliar['nombre']."','".$rowFamiliar['apelli']."',
			'".$rowFamiliar['codpar']."','".$rowFamiliar['ssexxo']."','".$rowFamiliar['fecnac']."','".$rowFamiliar['fecing']."','".$rowFamiliar['tipdoc']."',
			'".$rowFamiliar['nrodoc']."','".$rowFamiliar['benefi']."','1')";
			
			$sqlFamiliaInsert = "select nrcuil from familiadebajausimra where id = $idFamiliaInsert";
			$resFamiliaInsert = mysql_query($sqlFamiliaInsert,$db); 
			$canFamiliaInsert = mysql_num_rows($resFamiliaInsert); 
			if ($canFamiliaInsert > 0) {
				$sqlDeleteFami = "DELETE from familiadebajausimra where id = $idFamiliaInsert";
				$result = ejectuarDoble($sqlInsertFami, $sqlDeleteFami);	
			} else {
				$result = ejectuarSimple($sqlInsertFami);	
			}
		} else {
			$result = 0;
		}
		
		if ($result == 0) {
			$sqlUpdateBajadaFamilia = "UPDATE familia SET bajada = 1 WHERE id = $idFamiliaInsert";
			ejecutarUpdate($sqlUpdateBajadaFamilia);
		}
	}
}
// *************************************************************** //

// ***************** IMPORTO LOS EMPLEADOS DE BAJA ******************** //
$sqlEmpleadosdebaja = "select * from empleadosdebaja where bajada = 0";
$resEmpleadosdebaja = mysql_query($sqlEmpleadosdebaja,$dbaplicativo); 
$canEmpleadosdebaja = mysql_num_rows($resEmpleadosdebaja); 
if ($canEmpleadosdebaja > 0) {
	while($rowEmpleadodebaja = mysql_fetch_assoc($resEmpleadosdebaja)) {
		$result  = 1;
		$cuilInsert = $rowEmpleadodebaja['nrcuil'];
		$sqlEmpleadoInsertBaja = "select nrcuil from empleadosdebajausimra where nrcuil = $cuilInsert";
		$resEmpleadoInsertBaja = mysql_query($sqlEmpleadoInsertBaja,$db); 
		$canEmpleadoInsertBaja = mysql_num_rows($resEmpleadoInsertBaja); 
		if ($canEmpleadoInsertBaja == 0) {
			$sqlInsertTituBaja = "INSERT INTO empleadosdebajausimra VALUE(
			'".$rowEmpleadodebaja['nrcuit']."','".$rowEmpleadodebaja['nrcuil']."','".$rowEmpleadodebaja['apelli']."','".$rowEmpleadodebaja['nombre']."',
			'".$rowEmpleadodebaja['fecing']."','".$rowEmpleadodebaja['tipdoc']."','".$rowEmpleadodebaja['nrodoc']."','".$rowEmpleadodebaja['ssexxo']."',
			'".$rowEmpleadodebaja['fecnac']."','".$rowEmpleadodebaja['estciv']."','".$rowEmpleadodebaja['direcc']."','".$rowEmpleadodebaja['locale']."',
			'".$rowEmpleadodebaja['copole']."','".$rowEmpleadodebaja['provin']."','".$rowEmpleadodebaja['nacion']."','".$rowEmpleadodebaja['catego']."',
			'".$rowEmpleadodebaja['activo']."','1')";
			
			$sqlEmpleadoInsertBaja = "select nrcuil from empleadosusimra where nrcuil = $cuilInsert";
			$resEmpleadoInsertBaja = mysql_query($sqlEmpleadoInsertBaja,$db); 
			$canEmpleadoInsertBaja = mysql_num_rows($resEmpleadoInsertBaja); 
			if ($canEmpleadoInsertBaja > 0) {
				$sqlDeleteTituBaja = "DELETE from empleadosusimra where nrcuil = $cuilInsert";
				$result = ejectuarDoble($sqlInsertTituBaja, $sqlDeleteTituBaja);
			} else  {
				$result = ejectuarSimple($sqlInsertTituBaja);
			}
			
		} else {
			$result = 0;
		}
		
		if ($result == 0) {
			$sqlUpdateBajadaEmpleadosBaja = "UPDATE empleadosdebaja SET bajada = 1 WHERE nrcuil = $cuilInsert";
			ejecutarUpdate($sqlUpdateBajadaEmpleadosBaja);
		}
	}
}
// *************************************************************** //

// ***************** IMPORTO LAS FAMILIA DE BAJA ******************** //
$sqlFamiliarBaja = "select * from familiadebaja where bajada = 0";
$resFamiliarBaja = mysql_query($sqlFamiliarBaja,$dbaplicativo); 
$canFamiliarBaja = mysql_num_rows($resFamiliarBaja); 
if ($canFamiliarBaja > 0) {
	while($rowFamiliarBaja = mysql_fetch_assoc($resFamiliarBaja)) {
		$result = 1;
		$idFamiliaInsertBaja = $rowFamiliarBaja['id'];
		$sqlFamiliaInsertBaja = "select nrcuil from familiadebajausimra where id = $idFamiliaInsertBaja";
		$resFamiliaInsertBaja = mysql_query($sqlFamiliaInsertBaja,$db); 
		$canFamiliaInsertBaja = mysql_num_rows($resFamiliaInsertBaja); 
		if ($canFamiliaInsertBaja == 0) {
			$sqlInsertFamiBaja = "INSERT INTO familiadebajausimra VALUE(
			'".$rowFamiliarBaja['id']."','".$rowFamiliarBaja['nrcuit']."','".$rowFamiliarBaja['nrcuil']."','".$rowFamiliarBaja['nombre']."','".$rowFamiliarBaja['apelli']."',
			'".$rowFamiliarBaja['codpar']."','".$rowFamiliarBaja['ssexxo']."','".$rowFamiliarBaja['fecnac']."','".$rowFamiliarBaja['fecing']."','".$rowFamiliarBaja['tipdoc']."',
			'".$rowFamiliarBaja['nrodoc']."','".$rowFamiliarBaja['benefi']."','1')";
		
			$sqlFamiliaInsertBaja = "select nrcuil from familiausimra where id = $idFamiliaInsertBaja";
			$resFamiliaInsertBaja = mysql_query($sqlFamiliaInsertBaja,$db); 
			$canFamiliaInsertBaja = mysql_num_rows($resFamiliaInsertBaja); 
			if ($canFamiliaInsertBaja > 0) {
				$sqlDeleteFamiBaja = "DELETE from familiausimra where id = $idFamiliaInsertBaja";
				$result = ejectuarDoble($sqlInsertFamiBaja, $sqlDeleteFamiBaja);	
			} else {
				$result = ejectuarSimple($sqlInsertFamiBaja);	
			}
			
		} else {
			$result = 0;
		}
		if ($result == 0) {
			$sqlUpdateBajadaFamiliaBaja = "UPDATE familiadebaja SET bajada = 1 WHERE id = $idFamiliaInsertBaja";
			ejecutarUpdate($sqlUpdateBajadaFamiliaBaja);
		}
	}
}
// *************************************************************** //

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado de Empresas dasdas de alta :.</title>

<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><span style="text-align:center">
    <input type="button" name="volver" value="Volver" onclick="location.href = 'menuAplicativoddjj.php'" />
  </span></p>
  	<p class="Estilo2">Resultado del proceso de apertura automatica de empresas del Aplicativo del d&iacute;a <?php echo date("m/d/Y");?>  </p>
	<?php if  (sizeof($listadoIngresadas) > 0) { ?>
	  
	  <table width="800" border="1" align="center">
		<tr>
		  <th>C.U.I.T.</th>
			  <th>Raz&oacute;n Social </th>
			</tr>
	  <?php for ($i=0; $i < sizeof($listadoIngresadas); $i++) {
				print("<tr align='center'>");
				print("<td>".$listadoIngresadas[$i]['cuit']."</td>");
				print("<td>".$listadoIngresadas[$i]['nombre']."</td>");   
				print("</tr>");
	} ?>
	  </table>
	<?php } else {
		print("<div align='center' style='color:#FF0000'><b> NO SE CARGO NINGUNA EMPRESA </b></div>");
	} 
	?>
      <p>
        <input type="button" name="imprimir" value="Imprimir" onclick="window.print();" />
  </p>
</div>
</body>
</html>