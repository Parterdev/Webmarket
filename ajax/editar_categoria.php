<?php

    include('is_logged.php'); //Verificación del logueo de usuario para poder acceder a la ruta o URL.
    //Validaciónes iniciales
    if(empty($_POST['mod_id'])) {
        $errors[] = "El campo (ID) se encuentra vacío";
    }else if(empty($_POST['mod_nombre'])) {
        $erros[] = "El campo nombre se encuentra vacío";
    } else if(!empty($_POST['mod_id']) && !empty($_POST['mod_nombre'])) {
        /** Conección a la base de datos **/
        require_once ("../models/database.php"); //Variables para la conexión a la base
        require_once ("../models/connect.php"); //Función que conecta a la base de datos
        //Función "mysqli_escape" que escapa caracteres especiales en una cadena para usarla en una declaración o sentencia SQL.
        $nombre=mysqli_real_escape_string($con,(strip_tags($_POST["mod_nombre"], ENT_QUOTES)));
        $descripcion=mysqli_real_escape_string($con,(strip_tags($_POST['mod_descripcion'], ENT_QUOTES)));
        $id_categoria=intval($_POST['mod_id']);
        $sql="UPDATE categorias SET nombre_categoria='".$nombre."', descripcion_categoria='".$descripcion."' WHERE id_categoria='".$id_categoria."'";
        $query_update=mysqli_query($con,$sql);
        if($query_update) {
            $messages[] = "La categoría ha sido actualizada exitosamente.";
        }else {
            $errors[] = "Ha ocurrido un error, por favor intenta nuevamente.".mysqli_error($con);
        }
    }else {
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