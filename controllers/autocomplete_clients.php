<?php
if(isset($_GET['term'])) {
    include("../models/database.php");
    include("../models/connect.php");
    $return_arr=array();
    //Ejecución de la base de datos
    if($con) {
        $fetch=mysqli_query($con, "SELECT * FROM clientes WHERE nombre_cliente LIKE '%".mysqli_real_escape_string($con,($_GET['term'])) ."%' LIMIT 0, 50");
        //Recuperación y almacenamiento de los resultados de la consulta en una matriz.
        while($row=mysqli_fetch_array($fetch)) {
            $id_cliente=$row['id_cliente'];
            $row_array['value'] = $row['nombre_cliente'];
            $row_array['id_cliente'] = $id_cliente;
            $row_array['nombre_cliente'] = $row['nombre_cliente'];
            $row_array['telefono_cliente'] = $row['telefono_cliente'];
            $row_array['email_cliente'] = $row['email_cliente'];
            array_push($return_arr,$row_array);
        }
    }

    //Conexión a datos
    mysqli_close($con);

    //Lanzamiento de resultados en una matriz codificada Json.
    echo json_encode($return_arr);
}

?>