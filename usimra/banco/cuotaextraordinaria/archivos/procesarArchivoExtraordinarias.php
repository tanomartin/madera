<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
$maquina = $_SERVER['SERVER_NAME'];

$hayErrores=0;
$hayPago=0;
$archivo_name=$_GET['nombreArc'];
$fechahoy=date("YmdHis",time());

if (!file_exists($archivo_name)) 
	$hayErrores=1;
else{
	$registros = file($archivo_name, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	for($i=0; $i < count($registros); $i++){
		$tipopago=substr($registros[$i], 73, 2);
		if(strcmp("20", $tipopago)==0) {
			$hayPago=1;
			$nromovdb=substr($registros[$i], 36, 6);
			$sucoridb=substr($registros[$i], 10, 4);
			$frecaudb=substr($registros[$i], 18, 8);
			$frendidb=substr($registros[$i], 26, 8);
			$sucbcrdb=substr($registros[$i], 14, 4);
			$codmovdb=substr($registros[$i], 34, 2);
			if(strcmp("50", $codmovdb)==0)
				$estmovdb=E;
			else
				$estmovdb=substr($registros[$i], 154, 1);
			$impoente=substr($registros[$i], 42, 13);
			$impodeci=substr($registros[$i], 55, 2);
			$impdepdb=$impoente.".".$impodeci;
			$monedadb=substr($registros[$i], 57, 1);
			$codbardb=substr($registros[$i], 58, 39);
			$cuibardb=substr($registros[$i], 62, 11);
			$ctrbardb=substr($registros[$i], 73, 14);
			$chebandb=substr($registros[$i], 138, 4);
			$chesucdb=substr($registros[$i], 142, 4);
			$chenrodb=substr($registros[$i], 146, 8);
			$fecregdb=$fechahoy;
			$usuregdb=$_SESSION['usuario'];

			$sqlBanco="INSERT INTO banextraordinariausimra VALUES('$nromovdb','$sucoridb','$frecaudb','$frendidb','$estmovdb','$sucbcrdb',	'$codmovdb','$impdepdb','$monedadb','$codbardb','$cuibardb','$ctrbardb','$chebandb','$chesucdb','$chenrodb','$fecregdb','$usuregdb','','','','')";
			$resultBanco= mysql_query($sqlBanco,$db); 
		}
	}

	$origen=$archivo_name;
	if(strcmp("localhost",$maquina)==0)
		$destino=$_SERVER['DOCUMENT_ROOT']."/madera/usimra/banco/ProcesadosBanco/".$archivo_name;
	else
		$destino="/home/sistemas/Documentos/Repositorio/UsimraxxBanco/Procesados/".substr($archivo_name,52,20);
		//$destino=$_SERVER['DOCUMENT_ROOT']."/ospim/acuerdos/Banco/ProcesadosBanco/".substr($archivo_name,52,20);
	rename($origen,$destino);
	
	$nroConvenio = 5866;	
	$fechaModif = date("Y-m-d H:i:s");
	$usuarModif = $_SESSION['usuario'];
	$dia = substr($archivo_name,-12,2);
	$mes = substr($archivo_name,-10,2);
	$ano = substr($archivo_name,-8,4);
	$sqlUpdateDia = "UPDATE diasbancousimra SET procesado = 1, fechamodificacion = '$fechaModif', usuariomodificacion = '$usuarModif' WHERE ano = $ano and mes = $mes and dia = $dia and nroconvenio = $nroConvenio";
	//print($sqlUpdateDia);
	$resUpdateDia = mysql_query($sqlUpdateDia,$db);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M�dulo Banco USIMRA :.</title>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>
</head>
<body bgcolor="#B2A274">
  <table width="762" border="1" align="center">
  <tr align="center" valign="top">
    <td height="23" colspan="2"><div align="center"><em><strong><font face="Arial, Helvetica, sans-serif">Ingreso de Registros de Archivo del Banco a la BASE DE DATOS</font></strong></em></div></td>
  </tr>
  <tr align="center" valign="top">
    <td height="23" colspan="2"><div align="left"></div></td>
    </tr>
  <tr align="center" valign="top">
    <td height="23" colspan="2"><div align="center"><?php
	if ($hayErrores == 1)
		print("Error en Archivo -- Error en la apertura del archivo. Comuniquese con el Depto. de Sistemas.<br/>\n");
	else{
		if(strcmp("localhost",$maquina)==0)
			$fechaarc=substr($archivo_name, 8, 2)."-".substr($archivo_name, 10, 2)."-".substr($archivo_name, 12, 4);
		else
			$fechaarc=substr($archivo_name, 60, 2)."-".substr($archivo_name, 62, 2)."-".substr($archivo_name, 64, 4);
		if ($hayPago == 1)
			print("Ingreso Exitoso -- Los registros del dia $fechaarc han ingresado correctamente a la Base de Datos.<br/>\n");
		else
			print("Sin Ingreso de Registros - El archivo del dia $fechaarc no contiene registros vinculados a Cuota Excepcional.<br/>\n");
	}
	?></div></td>
    </tr>
  <tr align="center" valign="top">
    <td height="23" colspan="2"><div align="left"></div></td>
    </tr>
  <tr align="center" valign="top">
    <td width="373" height="27"><div align="left">
		<input type="reset" name="volver" value="Volver" onclick="location.href = '../moduloExtraordinaria.php'" align="left" />
	</div></td>
    <td width="373"><div align="right">
        <input type="button" name="imprimir" value="Imprimir" onclick="window.print();"/>	
	</div></td>
  </tr>
</table>
</body>
</html>
