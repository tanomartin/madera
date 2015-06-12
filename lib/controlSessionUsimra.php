<?php session_save_path($_SERVER['DOCUMENT_ROOT']."/madera/usimra/sessiones");
//si es necesario cambiar la config. del php.ini desde tu script 
ini_set("session.use_only_cookies","1"); 
ini_set("session.use_trans_sid","0"); 
//iniciamos la sesión 
session_start(); 
//session_set_cookie_params(0, "/", $HTTP_SERVER_VARS["HTTP_HOST"], 0); 
//cambiamos la duración a la cookie de la sesión 
//antes de hacer los cálculos, compruebo que el usuario está logueado 
//utilizamos el mismo script que antes 


$redire = "Location:../usimra/logout.php";
if ($_SESSION['aut'] != 1) { 
    //si no está logueado lo envío a la página de autentificación 
	//TODO que vaya a una pantalla de session caducada....
	header($redire); 
	exit(0);
} else { 
    //sino, calculamos el tiempo transcurrido 
    $fechaGuardada = $_SESSION["ultimoAcceso"]; 
    $ahora = date("Y-n-j H:i:s"); 
    $tiempo_transcurrido = (strtotime($ahora)-strtotime($fechaGuardada)); 
    //comparamos el tiempo transcurrido 
    if($tiempo_transcurrido >= 1200) { 
       //si pasaron 10 minutos o más 
	   //TODO que vaya a una pantalla de session caducada....	
   	   header($redire); //envío al usuario a la pag. de autenticación 
	   exit(0);
      //sino, actualizo la fecha de la sesión 
 	}else { 
    	$_SESSION["ultimoAcceso"] = $ahora; 
  	} 
} 
$db =  mysql_connect($_SESSION['host'],$_SESSION['usuario'], $_SESSION['clave']);
if (!$db) {
    die('No pudo conectarse: ' . mysql_error());
}
mysql_select_db($_SESSION['dbname']);
?>