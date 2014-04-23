<?php
function ConectarFTP(){
	//Permite conectarse al Servidor FTP
	$id_ftp=ftp_connect(SERVER,PORT); //Obtiene un manejador del Servidor FTP
	ftp_login($id_ftp,USER,PASSWORD); //Se loguea al Servidor FTP
	ftp_pasv($id_ftp,MODO); //Establece el modo de conexión
	return $id_ftp; //Devuelve el manejador a la función
}

function SubirArchivo($archivo_local, $archivo_remoto, $path_remoto){
	//Sube archivo de la maquina Cliente al Servidor (Comando PUT)
	$id_ftp=ConectarFTP(); //Obtiene un manejador y se conecta al Servidor FTP 
	ftp_chdir($id_ftp, $path_remoto);
	$resultado = ftp_put($id_ftp, $archivo_remoto, $archivo_local, FTP_ASCII);
	//Sube un archivo al Servidor FTP en modo Binario
	ftp_quit($id_ftp); //Cierra la conexion FTP
	return($resultado);
}

function ObtenerRuta(){
	//Obriene ruta del directorio del Servidor FTP (Comando PWD)
	$id_ftp=ConectarFTP(); //Obtiene un manejador y se conecta al Servidor FTP 
	$Directorio=ftp_pwd($id_ftp); //Devuelve ruta actual p.e. "/home/willy"
	ftp_quit($id_ftp); //Cierra la conexion FTP
	return $Directorio; //Devuelve la ruta a la función
}
?>