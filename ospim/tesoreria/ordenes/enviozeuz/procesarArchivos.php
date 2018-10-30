<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

function listar_archivos_txt($ruta){ 
   $arrayArchivos = array();
   $i = 0;
   if (is_dir($ruta)) { 
      if ($dh = opendir($ruta)) { 
         while (($file = readdir($dh)) !== false) { 
         	$pos = strpos($file, ".txt");
         	if ($pos !== false) {
         		$arrayArchivos[$i] = $ruta.$file;
         		$i++;
           	}
         } 
      	 closedir($dh); 
       } 
    } 
    return ($arrayArchivos);
}

$maquina = $_SERVER['SERVER_NAME'];
if(strcmp("localhost",$maquina)==0) {
	$carpetaOrden = "../OrdenesPagoPDF/";
	$carpetaDatos = "../OrdenesPagoPDF/datos/";
} else { 
	$carpetaOrden = "/home/sistemas/Documentos/Repositorio/OrdenesPagoPDF/";
	$carpetaDatos = "/home/sistemas/Documentos/Repositorio/OrdenesPagoPDF/datos/";
}

$arrayOK = array();
$arrayNOK = array();
$i = 0;
foreach (listar_archivos_txt($carpetaDatos) as $pathArchivo) {
	$fp = fopen($pathArchivo, "r");
	while (!feof($fp)){
		$linea = fgets($fp);
		if (strlen($linea) != 0) {
			$arrayDatos = explode("|",$linea);
			$codigo = trim($arrayDatos[0]);
			$email = trim($arrayDatos[1]);
			$nroorden = trim($arrayDatos[2]);
			$nombrePDF = "OP".$nroorden."O.pdf";
			if (!file_exists ($carpetaOrden.$nombrePDF)) {
				$arrayNOK[$i] = array("codigo" => $codigo, "email" => $email, "nroorden" => $nroorden, "error" => "No existe documento PDF de la Orden de Pago");
			} else {
				$sqlPrestador = "SELECT email1,email2 FROM prestadores WHERE codigoprestador = $codigo";
				$resPrestador = mysql_query($sqlPrestador,$db);
				$canPrestador = mysql_num_rows($resPrestador);
				if ($canPrestador == 0) {
					$arrayNOK[$i] = array("codigo" => $codigo, "email" => $email, "nroorden" => $nroorden, "error" => "No existe el prestador con ese codigo");
				} else {
					$rowPrestador = mysql_fetch_array($resPrestador);
					if ($email != $rowPrestador['email1'] && $email != $rowPrestador['email2']) {
						$arrayNOK[$i] = array("codigo" => $codigo, "email" => $email, "nroorden" => $nroorden, "error" => "No concuerda el correo informado en el archivo con los correos cargados");
					} else {
						$arrayOK[$i] = array("codigo" => $codigo, "email" => $email, "nroorden" => $nroorden, "datos" => $linea);
					}
				}
			}
			$i++;
		}
	}
	fclose($fp);
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<style type="text/css" media="print">
.nover {display:none}
</style>
<title>.: Módulo Ordenes Pago Envio Correo :.</title>
<link rel="stylesheet" href="/madera/lib/tablas.css"/>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">

function validar(formulario) {
	formulario.Submit.disabled = true;
	$.blockUI({ message: "<h1>Generando Correso a Enviar... <br>Esto puede tardar unos segundos.<br> Aguarde por favor</h1>" });
	return true;
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" class="nover" name="volver" value="Volver" onclick="location.href = 'moduloEnvio.php'" /></p>	
	<h3>Resultados del proceso de envio de Ordenes de Pago </br> Fecha de Proceso "<?php echo date("d-m-Y");  ?>"</h3>
<?php if (sizeof($arrayNOK) == 0 && sizeof($arrayOK) == 0) { ?>
		<h3 style="color: blue;">No existen registros para procesar</h3>
<?php } else { ?>
	  <form id="procesoArchivo" name="procesoArchivo" method="post" onsubmit="return validar(this)" action="generarCorreos.php">
<?php	if (sizeof($arrayNOK) > 0) {   ?>
		<h3 style="color: red">Lineas del archivo con ERRORES </br> No se enviaran por correo</h3>
	 	<div class="grilla">
		 	<table>
		 		<thead>
			 		<tr>
			 			<th>Codigo Prestador</th>
			 			<th>Email</th>
			 			<th>Nro Orden</th>
			 			<th>ERROR</th> 						
			 		</tr>
		 		</thead>
		 		<tbody>
		 <?php foreach ($arrayNOK as $lineasError) { ?>
		 		  	<tr>
		 		  		<td><?php echo $lineasError['codigo'] ?></td>
		 		  		<td><?php echo $lineasError['email'] ?></td>
		 		  		<td><?php echo $lineasError['nroorden'] ?> </td>
		 		  		<td><?php echo $lineasError['error'] ?> </td>
		 		  	</tr>
		 <?php } ?>
		 		</tbody>
		 	</table>
	 	</div>
<?php } 
	  if (sizeof($arrayOK) > 0) {  ?>
		<h3 style="color: blue">Correos a Enviar</h3>
	 	<div class="grilla">
		 	<table>
		 		<thead>
			 		<tr>
			 			<th>Codigo Prestador</th>
			 			<th>Email</th>
			 			<th>Nro Orden</th>				
			 		</tr>
		 		</thead>
		 		<tbody>
		 <?php foreach ($arrayOK as $key=>$lineas) { ?>
		 		  	<tr>
		 		  		<td>
		 		  			<?php echo preg_replace('/^0+/', '', $lineas['codigo']); ?>
		 		  			<input style="display: none" type="text" id="datos<?php echo $key ?>" name="datos<?php echo $key ?>" value="<?php echo $lineas['datos']?>" />
		 		  		</td>
		 		  		<td>
		 		  			<?php echo $lineas['email'] ?>
		 		  		</td>
		 		  		<td>
		 		  			<?php echo preg_replace('/^0+/', '', $lineas['nroorden']); ?> 
		 		  		</td>
		 		  	</tr>
		 <?php } ?>
		 		</tbody>
		 	</table>
	 	</div>
<?php } ?>   
		<p><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();"/></p>
<?php if (sizeof($arrayOK) > 0) {  ?>		
		<p><input type="submit" id="Submit" name="Procesar" value="Procesar" /></p>
<?php } ?>
	</form>
<?php } ?> 
</div>
</body>
</html>

