<?php session_save_path("sessiones");
session_start();
if($_SESSION['usuario'] == null)
	header ("location:index.htm");

$nrcuit = $_GET['nrcuit'];
$empcod = $_GET['empcod'];
$delcod = $_GET['delcod'];

if ($nrcuit == "") {
	$datos = array_values($_POST);
	$nrcuit = $datos [0];
	$delcod = $datos [1];
	$empcod = $datos [2];

	$acuerdo = $datos [3];
	$cuota = $datos [4];
}

include("conexion.php");
if ($acuerdo <> "" and $cuota <> "") {
	$sqlBorrar= "delete from boletas where delcod=".$delcod." and empcod=".$empcod." and nroacu=".$acuerdo." and nrocuo=".$cuota;
	$resultBorrar = mysql_query( $sqlBorrar,$db);
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style>
<!--
A:link {text-decoration: none}
A:visited {text-decoration: none}
A:hover {text-decoration:underline; color:FCF63C}
-->
</style>
<STYLE>
BODY {SCROLLBAR-FACE-COLOR: #E4C192; 
SCROLLBAR-HIGHLIGHT-COLOR: #CD8C34; 
SCROLLBAR-SHADOW-COLOR: #CD8C34; 
SCROLLBAR-3DLIGHT-COLOR: #CD8C34; 
SCROLLBAR-ARROW-COLOR: #CD8C34; 
SCROLLBAR-TRACK-COLOR: #CD8C34; 
SCROLLBAR-DARKSHADOW-COLOR: #CD8C34
}
.Estilo1 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
}
.Estilo3 {font-size: 10px}
</STYLE>
<title>.: Sistema de Acuerdos - Boletas a Depositar :.</title>
</head>
<body bgcolor="#E4C192" link="#D5913A" vlink="#CF8B34" alink="#D18C35">
<p align="center"><img border="0" src="top.jpg" width="700" height="120"></p>
<p align="center">&nbsp;</p>


<script language=Javascript>

function EstadoChequeBanco() {
	 if (document.forms.form1.tipoPago.value ==  2) {
	       document.forms.form1.nrocheque.value = "";
		   document.forms.form1.nrocheque.readOnly = true;
		   document.getElementById('nrocheque').style.background='#CCCCCC'; 
		   document.forms.form1.banco.value = "";
		   document.forms.form1.banco.readOnly = true;
		   document.getElementById('banco').style.background='#CCCCCC'; 
	 } else {
	 	   document.forms.form1.nrocheque.value = "";
		   document.forms.form1.nrocheque.readOnly = false;
		   document.getElementById('nrocheque').style.background='#FFFFFF'; 
		   document.forms.form1.banco.value = "";
		   document.forms.form1.banco.readOnly = false;
	 	   document.getElementById('banco').style.background='#FFFFFF';
	 }
}

function BloquearLibre() {
		document.forms.form1.acuerdosLibre.value = "";
		document.forms.form1.cuotasLibre.value = "";
		document.forms.form1.importe.value = "";
		document.forms.form1.acuerdosLibre.disabled=true;
		document.forms.form1.cuotasLibre.disabled=true;
		document.forms.form1.importe.readOnly=true;
}

function DesBloquerLiebre() {
		document.forms.form1.acuerdosLibre.value = "";
		document.forms.form1.cuotasLibre.value = "";
		document.forms.form1.importe.value = "";
		document.forms.form1.acuerdosLibre.disabled=false;
		document.forms.form1.cuotasLibre.disabled=false;
		
		//reseteo las listas.
		document.forms.form1.importe.readOnly=false;
		document.forms.form1.acuerdos.disabled = false;
		o = document.createElement("OPTION");
		o.text = '-';
		o.value = -1;
		document.forms.form1.cuotas.options.add (o);
}

function ComponerLista(Acu) {
		BloquearLibre();
		document.forms.form1.importe.value = "";
		document.forms.form1.acuerdos.disabled = true;
		document.forms.form1.cuotas.length = 0;
		if (Acu == -1 ) {
			DesBloquerLiebre();
		} else {
			CargarCuotas(Acu);
		}
		document.forms.form1.cuotas.disabled = false;
}

function CargarCuotas(Acu) {
	var o
	document.forms.form1.cuotas.disabled=true;
	o = document.createElement("OPTION");
	o.text = '-';
	o.value = -1;
	document.forms.form1.cuotas.options.add (o);
	<?php	
		$sql3 = "select * from cuotas where delcod = '$delcod' and empcod = '$empcod'";
		$result3 = mysql_query( $sql3,$db);
		while ($row3 = mysql_fetch_array($result3)) {
	?>
			if (Acu == <?php echo $row3["nroacu"]; ?>) {
				
				<?php 
					$sqlImpresa = "select * from boletas where delcod = '$delcod' and empcod = '$empcod' and nroacu =".$row3["nroacu"]." and nrocuo =".$row3["nrocuo"];	
					$resultImpresa = mysql_query( $sqlImpresa,$db);
					$cant = mysql_num_rows($resultImpresa);
					if ($cant == 0) {
				?>
						o = document.createElement("OPTION");
						o.text = '<?php echo $row3["nrocuo"]; ?>';
						o.value = <?php echo $row3["nrocuo"]; ?>;
						document.forms.form1.cuotas.options.add(o);
				<?php
				}
				?>
			} 
	<?php
		}
	?> 
	document.forms.form1.cuotas.disabled=false;
	document.forms.form1.acuerdos.disabled=false;
}
	
function CargarImporte(Cuota) {
	document.forms.form1.importe.value = "";
	var indice = document.forms.form1.acuerdos.selectedIndex;
	var Acuerdo = document.forms.form1.acuerdos.options[indice].value;
	<?php 	
			$sqlImporte = "select * from cuotas where delcod = '$delcod' and empcod = '$empcod'";
			$resultImporte = mysql_query( $sqlImporte,$db);
			while ($rowImporte = mysql_fetch_array($resultImporte)) { 
	?>		
				if (Cuota == <?php echo $rowImporte["nrocuo"]; ?> && Acuerdo == <?php echo $rowImporte["nroacu"]; ?> ) {
					document.forms.form1.importe.value = <?php echo $rowImporte["moncuo"]; ?>;
				}
	<?php
		}
	?> 	
}

function limpiarCheque() {
	document.forms.form1.nrocheque.value = "";
}

function limpiarBanco() {
	document.forms.form1.banco.value = "";
}
	
</script>

<?php //SQL para acuerdos...
$sql2 = "select * from acuerdos where delcod = '$delcod' and empcod = '$empcod'";
$result2 = mysql_query( $sql2,$db);
$cant2 = mysql_num_rows($result2);


//Ejecucion de la sentencia SQL para los datos de la emprersa...
$sql = "select * from empresas where nrcuit = '$nrcuit' and delcod = '$delcod' and empcod = '$empcod'";
$result = mysql_query( $sql,$db);
$cant = mysql_num_rows($result);
if ($cant > 0 and $delcod <> "" and $empcod <> "" ) {
$row = mysql_fetch_array($result);

?>
<table width="100%" border="0">
  <tr bgcolor="#C08345"> 
    <td width="16%"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">CUIT:</font></strong></td>
    <td colspan="3"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $row['nrcuit'];?></font></td>
  </tr>
  <tr bgcolor="#C08345"> 
    <td><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Raz&oacute;n 
      Social:</font></strong></td>
    <td colspan="3"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $row['nombre'];?></font></td>
  </tr>
  <tr bgcolor="#C08345"> 
    <td><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Delegaci&oacute;n:</font></strong></td>
    <td width="32%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $row['delcod'];?></font></td>
    <td width="23%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Empresa:</strong></font></td>
    <td width="29%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $row['empcod'];?></font></td>
  </tr>
  <tr bgcolor="#C08345"> 
    <td><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Domicilio:</font></strong></td>
    <td colspan="3"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $row['domici'];?></font></td>
  </tr>
  <tr bgcolor="#C08345"> 
    <td><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Localidad:</font></strong></td>
    <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $row['locali'];?></font></td>
    <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>C&oacute;digo 
      Postal:</strong></font></td>
    <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $row['codpos'];?></font></td>
  </tr>
  <tr bgcolor="#C08345"> 
<?php $provincia = array ("PROVINCIA", "CAPITAL FEDERAL", "BUENOS AIRES", "MENDOZA", "NEUQUEN", "SALTA", "ENTRE RIOS", "MISIONES", "CHACO", "SANTA FE", "CORDOBA", "SAN JUAN", "RIO NEGRO", "CORRIENTES", "SANTA CRUZ", "CHUBUT", "FORMOSA", "LA PAMPA", "SANTIAGO DEL ESTERO", "JUJUY", "TUCUMAN", "TIERRA DEL FUEGO", "SAN LUIS", "LA RIOJA", "CATAMARCA");
$pro = $row["provin"];
?>
    <td><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Provincia</font></strong></td>
    <td colspan="3"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $provincia [$pro]; ?></font></td>
  </tr>
</table>
<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Datos a 
  Completar:</strong></font></p>
<form name="form1" method="post" action="acuboleta.php">
  <div align="center"> 
    <table width="100%" border="0">
      <tr bgcolor="#C08345"> 
        <td width="16%" height="27"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Nro. 
          de Acta:</font></strong></td>
        <td colspan="3"><input name="acta" type="text" style="width: 148px;"></td>
      </tr>
      <tr bgcolor="#C08345"> 
        <td><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Nro. 
          de Acuerdo:</font></strong></td>
        <td colspan="3"><?php 
				echo '<select name="acuerdos" style="width: 50px;" onChange="ComponerLista(document.forms.form1.acuerdos[selectedIndex].value);">';
				echo '<option value= "-1"> - </option>';
				if ($cant2 > 0) {
					
					while ($row2=mysql_fetch_array($result2)) { 
       					echo '<option  value= "'.$row2["nroacu"].'">'.$row2["nroacu"].'</option>';
					} 
				} 
				echo '</select>';
			?>
          <input type="text" name="acuerdosLibre" style="width: 93px;">        </tr>
      <tr bgcolor="#C08345"> 
        <td><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Nro. 
          de Cuota:</font></strong></td>
        <td colspan="3">
	  <?php
					echo '<select name="cuotas" id="cuotas" style="width: 50px;" onchange="CargarImporte(document.forms.form1.cuotas[selectedIndex].value);">';
					echo '<option value= "-1"> - </option>';
					echo '</select>';
			?>              
      <input type="text" name="cuotasLibre" style="width: 93px;">      </tr>
      <tr bgcolor="#C08345"> 
        <td><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Importe:</font></strong></td>
        <td colspan="3">
		<?php
		   echo '<input name="importe" type="text" style="background-color:#CCCCCC">'
		?>		</td>
      </tr>
      <tr bgcolor="#C08345"> 
        <td height="24"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Tipo 
          de pago:</strong></font></td>
        <td width="11%"><p> 
            <select name="tipoPago" onChange="EstadoChequeBanco()">
              <option value="1" selected>Cheque</option>
              <option value="2">Efectivo</option>
            </select>
        </p></td>
        <td width="21%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>N&uacute;mero Cheque:</strong></font>
          <input name="nrocheque" id="nrocheque" type="text" onFocus="limpiarCheque();">
        </span></td>
        <td width="52%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong> Banco:</strong></font>
          <input name="banco" id="banco" type="text" onFocus="limpiarBanco();"></td>
      </tr>
    </table>
    <p><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
      <input name="hiddenField" type="hidden" value="<?php echo $nrcuit;?>">
      <input name="hiddenField2" type="hidden" value="<?php echo $usuario;?>">
      <input name="hiddenField22" type="hidden" value="<?php echo $delcod;?>">
      <input name="hiddenField23" type="hidden" value="<?php echo $empcod;?>">
    </font></strong></p>
    <p><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
      <input type="submit" name="Submit" value="Enviar">
      </font></strong> </p>
  </div>
  
</form>


<p align="center"><a href="acuerdosFisca.php"><font color="#CD8C34" face="Verdana" size="2"><b>Volver</b></font></a> </p>
<?php } else {
?>
<p align="center"><a href="acuerdosFisca.php"><font color="#CD8C34" face="Verdana" size="2"><b> EMPRESA NO ENCONTRADA - Volver</b></font></a> 
<?php }
?>
</body>
</html>
