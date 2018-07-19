<?php
//Condición para verificar la versión de PHP
if(version_compare(PHP_VERSION, '5.3.7', '<')) {
	exit("Lo sentimos, la versión de PHP verificado en su sistema no se ejecuta en una versión del mismo menor a la 5.3.7.");
}elseif(version_compare(PHP_VERSION, '5.5.0', '<')) {
	/** You have to include the password_api_compatibility_library.php, 
	 * this library adds the PHP 5.5 password hashing functions to older versions of PHP */
	require_once("../libraries/password_compability_library.php");
}

//Validaciónes iniciales
if(empty($_POST['firstname2'])) {
    $errors[] = "El campo nombres se encuentra vacío";
}else if(empty($_POST['lastname2'])) {
    $errors[] = "El campo apellidos se encuentra vacío";
}else if(empty($_POST['user_name2'])) {
    $errors[] = "El campo nombre se encuentra vacío";
}else if(strlen($_POST['user_name2']) >64 || strlen($_POST['user_name2']) <2){
    $errors[] = "El nombre de usuario no puede ser inferior a 2 o más de 64 caracteres";
}else if(!preg_match('/^[a-z\d]{2,64}$/i', $_POST['user_name2'])) {
    $errors[] = "El nombre de usuario no es aceptado bajo el esquema de nombre: Solamente aZ y números permitidos, 2 a 64 caracteres";
}else if(empty($_POST['user_email2'])) {
    $errors[] = "El campo correo electrónico se encuentra vacio";
}else if(empty($_POST['user_email2']) >64) {
    $errors[] = "El correo electrónico no puede tener más de 64 caracteres";
}else if(!filter_var($_POST['user_email2'], FILTER_VALIDATE_EMAIL)) {
    $errors[] = "El correo electrónico no se encuentra en el formato adecuado";
}   else if (!empty($_POST['user_name2']) 
&& !empty($_POST['firstname2']) 
&& !empty($_POST['lastname2']) 
&& strlen($_POST['user_name2']) <=64 
&& strlen($_POST['user_name2']) >=2
&& preg_match('/^[a-z\d]{2,64}$/i', $_POST['user_name2']) 
&& !empty($_POST['user_email2']) 
&& strlen($_POST['user_email2']) <=64 
&& filter_var($_POST['user_email2'], FILTER_VALIDATE_EMAIL)){
        /** Conexión a la base de datos **/
        require_once ("../models/database.php"); //Variables para la conexión a la base
        require_once ("../models/connect.php"); //Función que conecta a la base de datos
        //Función "mysqli_escape" que escapa caracteres especiales en una cadena para usarla en una declaración o sentencia SQL.
        $nombre=mysqli_real_escape_string($con,(strip_tags($_POST["firstname2"], ENT_QUOTES)));
        $apellido=mysqli_real_escape_string($con,(strip_tags($_POST["lastname2"], ENT_QUOTES)));
        $user_name=mysqli_real_escape_string($con,(strip_tags($_POST["user_name2"], ENT_QUOTES)));
        $user_email=mysqli_real_escape_string($con,(strip_tags($_POST["user_email2"], ENT_QUOTES)));
        $id_usuario=intval($_POST['mod_id']);
        $sql="UPDATE usuarios SET nombre='".$nombre."', apellido='".$apellido."', user_name='".$user_name."', user_email='".$user_email."'WHERE id_usuario='".$id_usuario."';";
        $query_update = mysqli_query($con,$sql);
        if($query_update) {
            $messages[] = "La cuenta de usuario ha sido modificada exitosamente.";
        } else{
            $errors[] = "Ha ocurrido un error, por favor intenta nuevamente.".mysqli_error($con);
        }
    } else {
        $errors[] = "Error!";
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
        <div class="alert alert-success" role="alert">
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
