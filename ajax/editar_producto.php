<?php
include('is_logged.php'); //Verificación del inicio de sesión del usuario "Logueo".
//Condición para verificar la versión de PHP

//Validaciónes iniciales
if(empty($_POST['mod_id'])) {
    $errors[] = "El campo (ID) se encuentra vacío";
}else if(empty($_POST['mod_codigo'])) {
    $errors[] = "El campo código se encuentra vacío";
}else if(empty($_POST['mod_nombre'])) {
    $errors[] = "El campo nombre se encuentra vacío";
}else if($_POST['mod_estado']=="") {
    $errors[] = "El campo estado se encuentra vacío";
}else if(empty($_POST['mod_categoria'])) {
    $errors[] = "El campo categoría se encuentra vacío";
}else if(empty($_POST['mod_precio'])) {
    $errors[] = "El campo precio se encuentra vacío";
}else if(!empty($_POST['mod_id']) && !empty($_POST['mod_nombre']) && $_POST['mod_estado']!="" && $_POST['mod_categoria']!="" && !empty($_POST['mod_precio'])) {
        /** Conección a la base de datos **/
        require_once ("../models/database.php"); //Variables para la conexión a la base
        require_once ("../models/connect.php"); //Función que conecta a la base de datos
        //Función "mysqli_escape" que escapa caracteres especiales en una cadena para usarla en una declaración o sentencia SQL.
        $codigo=mysqli_real_escape_string($con,(strip_tags($_POST["mod_codigo"], ENT_QUOTES)));
        $nombre=mysqli_real_escape_string($con,(strip_tags($_POST["mod_nombre"], ENT_QUOTES)));
        $categoria=intval($_POST['mod_categoria']);
        $estado=intval($_POST['mod_estado']);
        $stock=intval($_POST['mod_stock']);
        $precio_venta=intval($_POST['mod_precio']);
        $id_producto=$_POST['mod_id'];
        $sql="UPDATE productos SET codigo_producto='".$codigo."', nombre_producto='".$nombre."', id_categoria='".$categoria."', estado_producto='".$estado."', precio_producto='".$precio_venta."', stock='".$stock."' WHERE id_producto='".$id_producto."'";
        $query_update = mysqli_query($con,$sql);
        if($query_update) {
            $messages[] = "El producto ha sido actualizado exitosamente.";
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
