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
if(empty($_POST['mod_nombre'])) {
    $errors[] = "El campo nombres se encuentra vacío";
}else if(empty($_POST['mod_identificacion'])) {
    $errors[] = "El campo identificación se encuentra vacío";
}else if(empty($_POST['mod_telefono'])) {
    $errors[] = "El campo teléfono se encuentra vacío";
}else if(empty($_POST['mod_email'])) {
    $errors[] = "El campo correo electrónico se encuentra vacio";
}else if(empty($_POST['mod_email']) >64) {
    $errors[] = "El correo electrónico no puede tener más de 64 caracteres";
}else if(!filter_var($_POST['mod_email'], FILTER_VALIDATE_EMAIL)) {
    $errors[] = "El correo electrónico no se encuentra en el formato adecuado";
}else if(empty($_POST['mod_direccion'])) {
    $errors[] = "El campo direccion se encuentra vacio";
}else if($_POST['mod_estado']=="") {
    $errors[] = "El campo estado se encuentra vacío";
}   else if (!empty($_POST['user_name2']) 
&& !empty($_POST['mod_nombre']) 
&& !empty($_POST['mod_identificacion']) 
&& !empty($_POST['mod_telefono']) 
&& !empty($_POST['mod_email']) 
&& strlen($_POST['mod_email']) <=64
&& filter_var($_POST['mod_email'], FILTER_VALIDATE_EMAIL)
&& !empty($_POST['mod_direccion']) 
&& $_POST['mod_estado']!="" ){
        /** Conexión a la base de datos **/
        require_once ("../models/database.php"); //Variables para la conexión a la base
        require_once ("../models/connect.php"); //Función que conecta a la base de datos
        //Función "mysqli_escape" que escapa caracteres especiales en una cadena para usarla en una declaración o sentencia SQL.
        $identificacion=mysqli_real_escape_string($con,(strip_tags($_POST["mod_identificacion"], ENT_QUOTES)));
        $nombre=mysqli_real_escape_string($con,(strip_tags($_POST["mod_nombre"], ENT_QUOTES)));
        $telefono=mysqli_real_escape_string($con,(strip_tags($_POST["mod_telefono"], ENT_QUOTES)));
        $email=mysqli_real_escape_string($con,(strip_tags($_POST["mod_email"], ENT_QUOTES)));
        $direccion=mysqli_real_escape_string($con,(strip_tags($_POST["user_direccion"], ENT_QUOTES)));
        $estado=intval($_POST['mod_estado']);
        $id_cliente=intval($_POST['mod_id']);
        $sql="UPDATE clientes SET cedula_cliente='".$identificacion."', nombre_cliente='".$nombre."', telefono_cliente='".$telefono."',
        email_cliente='".$email."', direccion_cliente='".$direccion."', estado_cliente='".$estado."', WHERE id_cliente='".$id_cliente."'";
        $query_update = mysqli_query($con,$sql);
        if($query_update) {
            $messages[] = "Los datos del cliente han sido modificados exitosamente.";
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
