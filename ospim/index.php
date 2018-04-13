<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: O.S.P.I.M. :.</title>
</head>

<body bgcolor="#CCCCCC" link="#D5913A" vlink="#CF8B34" alink="#D18C35">
<form method="post" action="verificador.php">
  <div align="center">
    <h2>Ingreso Sistema O.S.P.I.M.</h2>
    <p><img src="img/logo.png" width="357" height="300" /> </p>
    <p><?php  
    	if (isset($_GET['error'])) {
			$error = $_GET['error'];
			if ($error == 1) {
				print("<div align='center' style='color:#FF0000'><b> USUARIO Y/O CONTRASEÑA INCORRECTOS </b></div>");
			}
			if ($error == 2) {
				print("<div align='center' style='color:#FF0000'><b> YA TIENE UNA SESION INICIADO CON ESTE USUARIO </b></div>");
			}
    	}
	?></p>
    <table style="text-align: right;">
      <tr>
        <td><b>Usuario: </b></td>
        <td><input name="user" type="text" id="user" style="text-align: center" /></td>
      </tr>
      <tr>
        <td><b>Contraseña: </b></td>
        <td><input name="pass" type="password" id="pass" style="text-align: center" /></td>
      </tr>
      <tr>
        <td colspan="2" style="text-align: center">
            <input type="submit" value="Ingresar" style="margin-top: 15px" />
        </td>
      </tr>
    </table>
  </div>
</form>

</body>

</html>
