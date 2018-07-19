<?php
include('is_logged.php'); //Verificación del logueo de usuario para poder acceder a la ruta o URL.

//Validaciónes iniciales
if(empty($_POST['codigo'])) {
    $errors[] = "El campo código se encuentra vacío";
}elseif(empty($_POST['nombre'])) {
    $errors[] = "El campo nombre se encuentra vació";
}else if($_POST['estado']=="") {
    $errors[] = "El campo estado se encuentra vacío";
}elseif(empty($_POST['stock'])) {
    $errors[] = "El campo stock se encuentra vació";
}elseif(empty($_POST['precio'])) {
    $errors[] = "El campo precio se encuentra vació";
} elseif(
    !empty($_POST['codigo']) && !empty($_POST['nombre']) && $_POST['estado']!="" && $_POST['stock']!="" && !empty($_POST['precio'])
){
    //Conección a la base de datos
    require_once ("../models/database.php"); //Variables para la conexión a la base
    require_once ("../models/connect.php"); //Función que conecta a la base de datos
    include("../controllers/funciones.php");

    //Escape de código y eliminación de estructuras innecesarias
    //Función ""mysqli_escape que escapa caracteres especiales en una cadena para usarla en una declaración o sentencia SQL.
    $codigo=mysqli_real_escape_string($con,(strip_tags($_POST["codigo"],ENT_QUOTES)));
    $nombre=mysqli_real_escape_string($con,(strip_tags($_POST["nombre"],ENT_QUOTES)));
    $estado=intval($_POST['estado']);
    $stock=intval($_POST['stock']);
    $id_categoria=intval($_POST['categoria']);
    $precio_venta=floatval($_POST['precio']);
    $dato_agregado=date("Y-m-d H:i:s");

    //Inserción de los datos obtenidos por POST.
    $sql="INSERT INTO productos (codigo_producto, nombre_producto, estado_producto, dato_agregado, precio_producto, stock, id_categoria) VALUES ('$codigo','$nombre','$estado','$dato_agregado','$precio_venta','$stock','$id_categoria')";
    $query_new_insert = mysqli_query($con,$sql);
        if($query_new_insert) {
            $messages[] = "El producto ha sido ingresado a su inventario satisfactoriamente.";
            $id_producto=get_row('productos','id_producto','codigo_producto', 'estado_producto', $estado, $codigo);
            $id_usuario=$_SESSION['id_usuario'];
            $nombre=$_SESSION['nombre'];
            $nota="$nombre ha agregado $stock producto(s) al inventario";
            echo guardar_historial($id_producto,$id_usuario,$dato_agregado,$nota,$codigo,$stock);
        }else{
            $errors[] = "Algo ha fallado durante el proceso.".mysqli_error($con);
        }
        }else{
            $errors[] = "Problema no localizado";
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
        if(isset($messages)){
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