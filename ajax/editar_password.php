<?php
include('is_logged.php'); //Verificación del inicio de sesión del usuario "Logueo".
//Condición para verificar la versión de PHP
if(version_compare(PHP_VERSION, '5.3.7', '<')) {
	exit("Lo sentimos, la versión de PHP verificado en su sistema no se ejecuta en una versión del mismo menor a la 5.3.7.");
}elseif(version_compare(PHP_VERSION, '5.5.0', '<')) {
	/** You have to include the password_api_compatibility_library.php, 
	 * this library adds the PHP 5.5 password hashing functions to older versions of PHP */
	require_once("../libraries/password_compability_library.php");
}

if(empty($_POST['user_id_mod'])) {
    $errors[] = "El campo (ID) se encuentra vacío";
} elseif(empty($_POST['user_password_new3']) || empty($_POST['user_password_repeat3'])) {
    $errors[] = "Campo contraseña vacío";
} elseif($_POST['user_password_new3'] !== $_POST['user_password_repeat3']) {
    $errors[] = "Las contraseñas ingresadas no son iguales"; 
} elseif(!empty($_POST['user_id_mod'])
&& !empty($_POST['user_password_new3']) && !empty($_POST['user_password_repeat3']) 
&& ($_POST['user_password_new3'] === $_POST['user_password_repeat3'])) {
    /* Conexión a la Database */
	require_once ("../models/database.php");//Contiene las variables de configuracion para conectar a la base de datos
    require_once ("../models/connect.php");//Contiene funcion que conecta a la base de datos

    $id_usuario=intval($_POST['user_id_mod']);
    $user_password=$_POST['user_password_new3'];

    /** Encriptación de la contraseña del usuario con la función "password_hash()" de PHP 5.5 **/
    $user_password_hash=password_hash($user_password, PASSWORD_DEFAULT);
    //Se obtiene como resultado una cadena de 60 caracteres **/
    $sql="UPDATE usuarios SET user_password_hash='$user_password_hash' WHERE id_usuario='$id_usuario';";
    $query=mysqli_query($con,$sql);

    //Datos comprobados de manera correcta
    if($query) {
        $messages[] = "La contraseña ha sido modificada exitosamente.";
    } else{
        $errors[] = "El registro de la contraseña ha fallado, por favor vuelve a intentarlo.";
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
   