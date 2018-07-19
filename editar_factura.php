<?php
	session_start();
	if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {
        header("location: login.php");
		exit;
        }

	/* Conexión a la Database*/
	require_once ("./models/database.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("./models/connect.php");//Contiene funcion que conecta a la base de datos
	
	$active_facturas="active";
	$title="Inventario | Micromercado @-----";
?>