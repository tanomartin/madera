<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
<style>

A:link {text-decoration: none}
A:visited {text-decoration: none}
A:hover {text-decoration:underline; color:FCF63C}

.Estilo1 {
	font-size: 24px;
	font-weight: bold;
}
</style>

<title>.: O.S.P.I.M. :.</title>
</head>

<body bgcolor="#CCCCCC" link="#D5913A" vlink="#CF8B34" alink="#D18C35">
<form method="POST" action="verificador.php">
  <div align="center">
    <p class="Estilo1">Ingreso Sistema O.S.P.I.M.</p>
    <p><img src="img/logo.png" width="407" height="350"> </p>
    <p><?php  
		$error = $_GET['error'];
		if ($error == 1) {
			print("<div align='center' style='color:#FF0000'><b> USUARIO Y/O CONTRASEŅA INCORRECTOS </b></div>");
		}
		if ($error == 2) {
			print("<div align='center' style='color:#FF0000'><b> YA TIENE UNA SESION INICIADO CON ESTE USUARIO </b></div>");
		}
	?></p>
    <table border="0" width="26%">
      <tr>
        <td width="50%" align="right"><font face="Verdana" size="2"><b>Usuario:&nbsp;&nbsp;</b></font></td>
        <td width="50%"><p align="left">
          <input name="user" type="text" id="user" style="background-color: #FFFFFF" size="20">
        </td>
      </tr>
      <tr>
        <td width="50%" align="right"><font face="Verdana" size="2"><b>Contraseņa:&nbsp;&nbsp;</b></font></td>
        <td width="50%"><p align="left">
          <input name="pass" type="password" id="pass" style="background-color: #FFFFFF" size="20">
        </td>
      </tr>
      <tr>
        <td colspan="4"><div align="center">
            <input type="submit" value="Ingresar" name="B1">
        </div></td>
      </tr>
    </table>
  </div>
</form>

</body>

</html>
