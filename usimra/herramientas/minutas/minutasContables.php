<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Minutas USIMRA :.</title>
<style>
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
jQuery(function($){
	$("#fecha").mask("99-99-9999");
});

function validar(formulario) {
	window.open("", "formpopup", "width=800,height=570");
	formulario.target = 'formpopup';
}

</script>

</head>

<body bgcolor="#B2A274">
	<div align="center">
		<p><span class="Estilo2">Minutas Contables</span></p>
	  	<form id="minutasContables" name="minutasContables" method="post" action="generaMinuta.php" onsubmit="validar(this)">
	  		<table>
	  			<tr>
	  				<td>Fecha</td>
	  				<td><input id="fecha" name="fecha" type="text" size="6" value="01-12-2012"/></td>
	  			</tr>
	  			<tr>
	  				<td>Asiento Nº</td>
	  				<td><input id="asiento" name="asiento" type="text" value="4548148"/></td>
	  			</tr>
	  			<tr>
	  				<td>Cuenta</td>
	  				<td><input id="cuenta" name="cuenta" type="text" value="13231"/></td>
	  			</tr>
	  			<tr>
	  				<td>Chece Nº</td>
	  				<td><input id="cheque" name="cheque" type="text" value="1131213"/></td>
	  			</tr>
	  			<tr>
	  				<td colspan="2">
	  					<input type="radio" name="tipo" value="deposito"/> Depósito
	  					<input type="radio" name="tipo" value="debito" checked="checked"/> Débito
	  					<input type="radio" name="tipo" value="credito"/> Crédito
	  				</td>
	  			</tr>
	  			<tr>
	  				<td>Importe</td>
	  				<td><input id="importe" name="importe" type="text" size="6" value="15.25"/></td>
	  			</tr>
	  			<tr>
	  				<td>Detalle</td>
	  				<td><textarea style="resize:none;" name="detalle" id="detalle" cols="65" rows="13">vajvaoispjepajwawvajvaoispjepajwawvajvaoispjepajwawvajvaoispjepa
	  				
	  				
	  				
	  				
	  				

vajvaoispjepajwawvajvaoispjepajwawvajvaoispjepajwawvajvaoispjepa


vajvaoispjepajwawvajvaoispjepajwawvajvaoispjepajwawvajvaoispjepa
vajvaoispjepajwawvajvaoispjepajwawvajvaoispjepajwawvajvaoispjepa
	  				</textarea>  </td>
	  			</tr>
	  			<tr>
	  				<td>Cuenta Debe</td>
	  				<td><textarea style="resize:none;" name="debe" id="debe" cols="65" rows="4">asfdaewrioe no se qpue noersdfsdafdsafddfsaasdffasfadsfdsafs

asfdaewrioe no se qpue noersdfsdafdsafddfsaasdffasfadsfdsafs
						</textarea> </td>
	  			</tr>
	  			<tr>
	  				<td>Cuenta Haber</td>
	  				<td>
	  					<textarea style="resize:none;" name="haber" id="haber" cols="65" rows="4">2431342143 afsdfdasf4321 1234312431234 rqwqreqr qw441323423s

2431342143 afsdfdasf4321 1234312431234 rqwqreqr qw4413234232
	  					</textarea>  
	  				</td>
	  			</tr>
	  		</table>
	  		<p><input type="submit" value="Vista Previa"/></p>
	  	</form>
	</div>
</body>
</html>
