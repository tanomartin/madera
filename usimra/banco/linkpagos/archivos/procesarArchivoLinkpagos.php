<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
$maquina = $_SERVER['SERVER_NAME'];
//var_dump($_GET);
$archivo_name=$_GET['nombreArc'];
$origen=$archivo_name;
if(strcmp("localhost",$maquina)==0) {
	$destino=$_SERVER['DOCUMENT_ROOT']."/madera/usimra/banco/ProcesadosBanco/".$archivo_name;
	$archivoFecha=substr($archivo_name, 4, 4)."-".substr($archivo_name, 8, 2)."-".substr($archivo_name, 10, 2);
}
else {
	$destino="/home/sistemas/Documentos/Repositorio/UsimraxxBanco/Procesados/".substr($archivo_name, 52, 16);
	$archivoFecha=substr($archivo_name, 56, 4)."-".substr($archivo_name, 60, 2)."-".substr($archivo_name, 62, 2);
}

$hayErrores=0;
$hayPago=0;
$totregi=0;
$idMovimiento=0;
$fechahoy=date("YmdHis",time());

if (!file_exists($archivo_name)) 
	$hayErrores=1;
else{
	$registros = file($archivo_name, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	$totregi = count($registros);
	if($totregi > 1) {
		$hayPago=1;
		for($i=1; $i < count($registros); $i++){
			$idMovimiento++;
			$concepto=substr($registros[$i], 2, 3);
			$cuit=substr($registros[$i], 9, 11);
			$deposito=substr($registros[$i], 32, 10).".".substr($registros[$i], 42, 2);
			$fechadeposito=substr($registros[$i], 48, 4)."-".substr($registros[$i], 52, 2)."-".substr($registros[$i], 54, 2);
			$referencia=substr($registros[$i], 60, 30);
			$fecregdb=$fechahoy;
			$usuregdb=$_SESSION['usuario'];
			//print("archivoFecha: ".$archivoFecha."<br>");
			//print("idMovimiento: ".$idMovimiento."<br>");
			//print("concepto: ".$concepto."<br>");
			//print("cuit: ".$cuit."<br>");
			//print("deposito: ".$deposito."<br>");
			//print("fechadeposito: ".$fechadeposito."<br>");
			//print("referencia: ".$referencia."<br>");
			//print("fecregdb: ".$fecregdb."<br>");
			//print("usuregdb: ".$usuregdb."<br>");
			$sqlInsertLink="INSERT INTO linkaportesusimra VALUES('$archivoFecha','$idMovimiento','$concepto','$cuit','$deposito','$fechadeposito',	'$referencia','$fecregdb','$usuregdb','','','','')";
			//print($sqlInsertLink);
			$resultInsertLink= mysql_query($sqlInsertLink,$db); 
		}
	}

	rename($origen,$destino);

	$nroConvenio = '0XO0';	
	$fechaModif = date("Y-m-d H:i:s");
	$usuarModif = $_SESSION['usuario'];
	$dia = substr($archivo_name,-6,2);
	$mes = substr($archivo_name,-8,2);
	$ano = substr($archivo_name,-12,4);
	$sqlUpdateDia = "UPDATE diasbancousimra SET procesado = 1, fechamodificacion = '$fechaModif', usuariomodificacion = '$usuarModif' WHERE ano = $ano and mes = $mes and dia = $dia and nroconvenio = '$nroConvenio'";
	//print($sqlUpdateDia);
	$resUpdateDia = mysql_query($sqlUpdateDia,$db);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Banco USIMRA :.</title>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>
</head>
<body bgcolor="#B2A274">
  <table width="762" border="1" align="center">
  <tr align="center" valign="top">
    <td height="23" colspan="2"><div align="center"><em><strong><font face="Arial, Helvetica, sans-serif">Ingreso de Registros del Archivo de Link Pagos a la BASE DE DATOS</font></strong></em></div></td>
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
			$fechaarc=substr($archivo_name, 10, 2)."-".substr($archivo_name, 8, 2)."-".substr($archivo_name, 4, 4);
		else
			$fechaarc=substr($archivo_name, 62, 2)."-".substr($archivo_name, 60, 2)."-".substr($archivo_name, 56, 4);
		if ($hayPago == 1)
			print("Ingreso Exitoso -- Los registros del dia $fechaarc han ingresado correctamente a la Base de Datos.<br/>\n");
		else
			print("Sin Ingreso de Registros - El archivo del dia $fechaarc no contiene registros vinculados a Link Pagos.<br/>\n");
	}
	?></div></td>
    </tr>
  <tr align="center" valign="top">
    <td height="23" colspan="2"><div align="left"></div></td>
    </tr>
  <tr align="center" valign="top">
    <td width="373" height="27"><div align="left">
		<input type="reset" name="volver" value="Volver" onclick="location.href = '../moduloAportes.php'" align="left" />
	</div></td>
    <td width="373"><div align="right">
        <input type="button" name="imprimir" value="Imprimir" onclick="window.print();"/>	
	</div></td>
  </tr>
</table>
</body>
</html>