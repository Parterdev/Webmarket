<?php

//Condición para verificar la versión de PHP
if(version_compare(PHP_VERSION, '5.3.7', '<')) {
	exit("Lo sentimos, la versión de PHP verificado en su sistema no se ejecuta en una versión del mismo menor a la 5.3.7.");
}elseif(version_compare(PHP_VERSION, '5.5.0', '<')) {
	/** You have to include the password_api_compatibility_library.php, 
	 * this library adds the PHP 5.5 password hashing functions to older versions of PHP */
	require_once("../libraries/password_compability_library.php");
}

if (empty($_POST['nombre'])){
			$errors[] = "Nombres vacíos";
		} elseif (empty($_POST['apellido'])){
			$errors[] = "Apellidos vacíos";
		}  elseif (empty($_POST['user_name'])) {
            $errors[] = "Nombre de usuario vacío";
        } elseif (empty($_POST['user_password_new']) || empty($_POST['user_password_repeat'])) {
            $errors[] = "Contraseña vacía";
        } elseif ($_POST['user_password_new'] !== $_POST['user_password_repeat']) {
            $errors[] = "La contraseña y la repetición de la contraseña no son iguales";
        } elseif (strlen($_POST['user_password_new']) < 6) {
            $errors[] = "La contraseña debe tener como mínimo 6 caracteres";
        } elseif (strlen($_POST['user_name']) > 64 || strlen($_POST['user_name']) < 2) {
            $errors[] = "El Nombre de usuario no puede ser inferior a 2 o más de 64 caracteres";
        } elseif (!preg_match('/^[a-z\d]{2,64}$/i', $_POST['user_name'])) { //Expresión regular
            $errors[] = "El Nombre de usuario no encaja en el esquema de nombre: Sólo aZ y los números están permitidos , de 2 a 64 caracteres";
        } elseif (empty($_POST['user_email'])) {
            $errors[] = "El correo electrónico no puede estar vacío";
        } elseif (strlen($_POST['user_email']) > 64) {
            $errors[] = "El correo electrónico no puede ser superior a 64 caracteres";
        } elseif (!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "La dirección de correo electrónico ingresada no se encuentra en un formato de correo electrónico válida";
        } elseif (
			!empty($_POST['user_name'])
			&& !empty($_POST['nombre'])
			&& !empty($_POST['apellido'])
            && strlen($_POST['user_name']) <= 64
            && strlen($_POST['user_name']) >= 2
            && preg_match('/^[a-z\d]{2,64}$/i', $_POST['user_name'])
            && !empty($_POST['user_email'])
            && strlen($_POST['user_email']) <= 64
            && filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)
            && !empty($_POST['user_password_new'])
            && !empty($_POST['user_password_repeat'])
            && ($_POST['user_password_new'] === $_POST['user_password_repeat'])
        ) {
            //Conección a la base de datos
            require_once ("../models/database.php"); //Variables para la conexión a la base
            require_once ("../models/connect.php"); //Función que conecta a la base de datos
            
            ///Escape de código y eliminación de estructuras innecesarias
            //Función ""mysqli_escape que escapa caracteres especiales en una cadena para usarla en una declaración o sentencia SQL.
            $nombre = mysqli_real_escape_string($con,(strip_tags($_POST['nombre'], ENT_QUOTES)));
            $apellido = mysqli_real_escape_string($con,(strip_tags($_POST['apellido'], ENT_QUOTES)));
            $user_name = mysqli_real_escape_string($con,(strip_tags($_POST['user_name'], ENT_QUOTES)));
            $user_email = mysqli_real_escape_string($con,(strip_tags($_POST['user_email'], ENT_QUOTES)));
            $user_password = $_POST['user_password_new'];
            $date_added=date("Y-m-d H:i:s");
            
            /** Encriptación de la contraseña del usuario con la función "password_hash()" de PHP 5.5,
             * se obtiene como resultado una cadena de 60 caracteres **/
            $user_password_hash = password_hash($user_password, PASSWORD_DEFAULT);
            //Comprobación de usuario y email
            $sql="SELECT * FROM usuarios WHERE user_name='" .$user_name . "' OR user_email='" .$user_email ."';";
            $query_check_user_name = mysqli_query($con,$sql);
            $query_check_user=mysqli_num_rows($query_check_user_name);
            if($query_check_user ==1) {
                $errors[] = "Hemos detectado que el nombre del usuario ó correo electrónico ya se encuentra en uso";
            } else{
                //Escritura de los nuevos datos de usuario en la base
                $sql="INSERT INTO usuarios (nombre, apellido, user_name, user_password_hash, user_email, date_added) VALUES ('".$nombre."','".$apellido."','".$user_name."','".$user_password_hash."','".$user_email."','".$date_added."');";
                $query_new_user_insert = mysqli_query($con,$sql);
                //Comparación de resultado del registro del nuevo usuario
                if($query_new_user_insert) {
                    $messages[] = "La nueva cuenta ha sido creada exitosamente.";
                } else{
                    $errors[] = "El registro del usuario ha fallado, por favor vuelve a intentarlo.";
                }
            }
        } else {
            $errors[] = "Error desconocido.";
        }
        if(isset($errors)) {
            ?>
            <div class="alert alert-danger" role="alert">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>Error!</strong>
                    <?php
                        foreach($errors as $error) {
                            echo $error;
                        } 
                    ?>
            </div>
            <?php 
        }
        if(isset($messages)) {
            ?>
            <div class="alert alert-info" role="alert">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>Genial!</strong>
                    <?php
                        foreach($messages as $message) {
                            echo $message;
                        } 
                    ?>
            </div>
            <?php
        }
?>

             
