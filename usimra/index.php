<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>.: U.S.I.M.R.A :.</title>
</head>

<body bgcolor="#B2A274" link="#D5913A" vlink="#CF8B34" alink="#D18C35">
<form method="post" action="verificador.php">
  <div align="center">
    <h2>Ingreso Sistema U.S.I.M.R.A. </h2>
    <p><img src="img/logo.png" width="300" height="300" /> </p>
    <p><?php  
    	if (isset($_GET['error'])) {
			$error = $_GET['error'];
			if ($error == 1) {
				print("<div align='center' style='color:#FF0000'><b> USUARIO Y/O CONTRASEŅA INCORRECTOS </b></div>");
			}
			if ($error == 2) {
				print("<div align='center' style='color:#FF0000'><b> YA TIENE UNA SESION INICIADO CON ESTE USUARIO </b></div>");
			}
    	}
	?></p>
    <table style="text-align: right;">
      <tr>
        <td><b>Usuario: </b></td>
        <td><input name="user" type="text" id="user" style="text-align: center"/></td>
      </tr>
      <tr>
        <td><b>Contraseņa: </b></td>
        <td><input name="pass" type="password" id="pass" style="text-align: center"/></td>
      </tr>
      <tr>
        <td colspan="2" style="text-align: center">
            <input type="submit" value="Ingresar" style="margin-top: 15px"/>
        </td>
      </tr>
    </table>
  </div>
</form>
<div align="center">
	<h2>Cuantas veces te lavaste las manos hoy?</h2>
	<p><img src="img/lavadodemanosmin.jpg" width="584" height="540" /> </p>
</div>
</body>

</html>
