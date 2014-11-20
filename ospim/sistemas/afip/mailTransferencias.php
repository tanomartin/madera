<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."fechas.php"); 

$hostname = '{pop3.ospim.com.ar:110/pop3/notls}INBOX';
$username = 'afiptransferencias@ospim.com.ar';
$password = 'purz4865';
$inbox = imap_open($hostname,$username,$password) or die('Ha fallado la conexión. - Error: '.imap_last_error());

$imap_obj = imap_check($inbox);
//var_dump($imap_obj);
$emails = imap_search($inbox,'ALL');
$total_emails = sizeof($emails);
//var_dump($emails);
$result = imap_fetch_overview($inbox,"1:{$imap_obj->Nmsgs}",0);
//var_dump($result);
date_default_timezone_set('America/Argentina/Buenos_Aires');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" href="/lib/jquery.tablesorter/themes/theme.blue.css" type="text/css" id="" media="print, projection, screen" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Transferencias AFIP :.</title>
<script type="text/javascript" src="/lib/jquery.js"></script>
<script type="text/javascript" src="/lib/jquery-ui.min.js"></script>
<script type="text/javascript" src="/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script type="text/javascript" src="/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script type="text/javascript" src="/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script type="text/javascript">
	$(function() {
		$("#listamsj")
		.tablesorter({theme: 'blue', widthFixed: true, widgets:['zebra'], headers:{3:{sorter:false}, 5:{sorter:false}}})
		.tablesorterPager({container: $("#paginador")}); 
	});

function consultaDetallesArchivo(fecmen, nromai) {
	param = "fechaMens="+fecmen+"&nroMail="+nromai;
	opciones = "top=50,left=50,width=1080,height=640,toolbar=no,menubar=no,status=no,dependent=yes,hotkeys=no,scrollbars=yes,resizable=yes"
	window.open("detallesTransferencias.php?"+param, "", opciones);
};
</script>
</head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>
<body bgcolor="#CCCCCC">
<div align="center">
<h1>Mensajes de Transferencias AFIP</h1>
</div>
<?php if ($total_emails != 0) {?>
	<div align="center">
		<h2>Se han encontrado <?php echo $total_emails;?> mensajes</h2>
	</div>
	<div align="center">
	<table id="listamsj" class="tablesorter">
		<thead>
			<tr>
				<th>Mensaje Nro</th>
				<th>Fecha</th>
				<th>De</th>
				<th>Asunto</th>
				<th>Estado</th>
				<th>Acci&oacute;n</th>
			</tr>
		</thead>
		<tbody>
	<?php foreach ($result as $overview) {
			$asunto = utf8_decode(imap_utf8($overview->subject));
			$nroMail = $overview->msgno;
			$fechaMail = "{$overview->date}";
			$fechaConv = date("d/m/Y H:i:s", strtotime($fechaMail));
			$fechaArch = date("Ymd", strtotime($fechaMail));
			$fechaMens = date("YmdHis", strtotime($fechaMail));
			$indicaMail = 0;
			$estadoMail = "Mensaje No Leido";

			$sqlBuscaMensaje = "SELECT * FROM afipmensajes WHERE nromensaje = '$nroMail' AND fechaemailafip = '$fechaMens' AND cuentaderecepcion = '$username'";
			$resBuscaMensaje = mysql_query($sqlBuscaMensaje,$db);
			$canBuscaMensaje = mysql_num_rows($resBuscaMensaje);
			if($canBuscaMensaje!=0) {
				$indicaMail = 1;
				$estadoMail = "Mensaje Leido";
			}?>
			<tr>
				<td><?php echo "{$nroMail}";?></td>
				<td><?php echo "{$fechaConv}";?></td>
				<td><?php echo "{$overview->from}";?></td>
				<td><?php echo "{$asunto}";?></td>
				<td><?php echo "{$estadoMail}";?></td>
		<?php if($indicaMail == 0) { ?>
				<td><input type="button" value="Procesar Archivo" onClick="window.location.href='archivosTransferencias.php?fechaArch=<?php echo $fechaArch;?>&fechaMens=<?php echo $fechaMens;?>&nroMail=<?php echo $nroMail;?>'"></td>
		<?php } else { ?>
				<td><input type="button" value="Archivo Procesado" onClick="javascript:consultaDetallesArchivo(<?php echo $fechaMens;?>,<?php echo $nroMail;?>)"/></td>
		<?php } ?>
			</tr>
	<?php }?>
		</tbody>
	</table>
	</div>
	<div id="paginador" class="pager">
	<form>
		<p>&nbsp;</p>
		<img src="img/first.png" width="16" height="16" class="first"/>
		  <img src="img/prev.png" width="16" height="16" class="prev"/>
		  <input type="text" class="pagedisplay" size="8" readonly="readonly" style="background:#CCCCCC; text-align:center"/>
		  <img src="img/next.png" width="16" height="16" class="next"/>
		  <img src="img/last.png" width="16" height="16" class="last"/>
			<select class="pagesize">
			  <option selected value="10">10 por pagina</option>
			  <option value="20">20 por pagina</option>
			  <option value="30">30 por pagina</option>
			  <option value="<?php echo $total_emails;?>">Todos</option>
			</select>
	  <table width="1229" border="0">
		<tr>
		  <td width="599">
			<div align="left">
			  <input type="reset" name="volver" value="Volver" onClick="location.href = 'menuAfip.php'" align="left"/>
			</div>
		  <td width="620">
			<div align="right">
			  <input type="button" name="imprimir" value="Imprimir" onClick="window.print();" align="right"/>
			</div>
		</tr>
	  </table>
	</form>
	</div>
<?php } else {?>
		<div align="center">
			<h2>No se han encontrado nuevos mensajes</h2>
		</div>
<?php }
imap_close($inbox);
?>
</body>
</html>