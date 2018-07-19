<?php

include('is_logged.php'); //Verificación del logueo de usuario para poder acceder a la ruta o URL.

//Conección a la base de datos
require_once ("../models/database.php"); //Variables para la conexión a la base
require_once ("../models/connect.php"); //Función que conecta a la base de datos

if (empty($_POST['nombre'])){
			$errors[] = "El campo nombres y apellidos se encuentra vacío";
		} elseif (empty($_POST['identificacion'])){
			$errors[] = "El campo número de identificación se encuentra vacío";
		}  elseif (empty($_POST['telefono'])) {
            $errors[] = "El campo teléfono se encuentra vacío";
        }  elseif (empty($_POST['direccion'])) {
            $errors[] = "El Campo dirección se encuentra vacío";
        }  elseif (strlen($_POST['email']) > 64) {
            $errors[] = "El correo electrónico no puede ser superior a 64 caracteres";
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "La dirección de correo electrónico ingresada no se encuentra en un formato de correo electrónico válida";
        } elseif($_POST['estado']=="") {
                $errors[] = "El campo estado se encuentra vacío";
        } elseif (
			!empty($_POST['nombre'])
			&& !empty($_POST['identificacion'])
			&& !empty($_POST['telefono'])
            && !empty($_POST['direccion'])
            && !empty($_POST['email'])
            && strlen($_POST['email']) <= 64
            && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)
            && $_POST['estado']!=""
        ) {
            
            ///Escape de código y eliminación de estructuras innecesarias
            //Función ""mysqli_escape que escapa caracteres especiales en una cadena para usarla en una declaración o sentencia SQL.
            $nombre = mysqli_real_escape_string($con,(strip_tags($_POST['nombre'], ENT_QUOTES)));
            $identificacion = mysqli_real_escape_string($con,(strip_tags($_POST['identificacion'], ENT_QUOTES)));
            $telefono = mysqli_real_escape_string($con,(strip_tags($_POST['telefono'], ENT_QUOTES)));
            $direccion = mysqli_real_escape_string($con,(strip_tags($_POST['direccion'], ENT_QUOTES)));
            $email = mysqli_real_escape_string($con,(strip_tags($_POST['email'], ENT_QUOTES)));
            $estado=intval($_POST['estado']);
            $date_added=date("Y-m-d H:i:s");
            
            //Inserción de los datos obtenidos por POST.
            $sql="INSERT INTO clientes (cedula_cliente, nombre_cliente, telefono_cliente, email_cliente, direccion_cliente, estado_cliente, dato_agregado) VALUES ('$identificacion','$nombre','$telefono','$email','$direccion','$estado','$date_added')";
            $query_new_insert = mysqli_query($con,$sql);
                if($query_new_insert) {
                    $messages[] = "El cliente ha sido registrado satisfactoriamente.";
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

             
