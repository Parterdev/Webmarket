<?php

//Función para el agregado de datos al inventario en el apatado de productos. 
function get_row($table,$row,$id,$equal) {
    global $con;
    $query=mysqli_query($con, "SELECT $row FROM $table WHERE $id='$equal'");
    $rw=mysqli_fetch_array($query);
    $value=$rw[$row];
    return $value;
}

//Función para guardar el historial de datos en base al agregado de productos al inventario.
function guardar_historial($id_producto,$id_usuario,$fecha,$nota,$reference,$quantity) {
    global $con;
    $sql="INSERT INTO historial (id_historial, id_producto, id_usuario, fecha, nota, referencia, cantidad)
    VALUES (NULL, '$id_producto','$id_usuario','$fecha','$nota','$reference','$quantity');";
    $query=mysqli_query($con,$sql);
}

//Función agregar stock a determinados productos del inventario.
function agregar_stock($id_producto,$quantity) {
    global $con;
    $update=mysqli_query($con,"UPDATE productos SET stock=stock+'$quantity' WHERE id_producto='$id_producto'");
    if($update) {
        return 1;
    } else{
        return 0;
    }
}

//Función eliminar stock a determinados productos del inventario.
function eliminar_stock($id_producto,$quantity) {
    global $con;
    $update=mysqli_query($con, "UPDATE productos SET stock=stock-'$quantity' WHERE id_producto='$id_producto'");
    if($update) {
        return 1;
    } else{
        return 0;
    }
}

?>