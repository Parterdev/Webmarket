<?php
    
    include('is_logged.php'); //Verificación de logueo
	/* Conexión a la Database */
	require_once ("../models/database.php");//Contiene las variables de configuracion para conectar a la base de datos
    require_once ("../models/connect.php");//Contiene funcion que conecta a la base de datos

    $action = (isset($_REQUEST['action']) && $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
    if(isset($_GET['id'])) {
        $id_cliente=intval($_GET['id']);
        $query=mysqli_query($con, "SELECT * FROM facturas WHERE id_cliente='".$id_cliente."'");
        $count=mysqli_num_rows($query);
        if($count==0) {
            if ($delete1=mysqli_query($con, "DELETE FROM clientes WHERE id_cliente='".$id_cliente."'")) {
            ?>
            <div class="alert alert-success alert-dismissible" role="alert">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  <strong>Aviso!</strong>El cliente ha sido eliminado exitosamente.
			</div>
            <?php        
        }else {
            ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  <strong>Error!</strong>Ha ocurrido un error, por favor intenta nuevamente.
			</div>
            <?php
        }
    }else {
        ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  <strong>Error!</strong>No se ha podido eliminar el cliente ya que este, tiene facturas vinculadas.
			</div>
        <?php
    }
}

    if($action == 'ajax'){
        //Función "mysqli_escape" que escapa caracteres especiales en una cadena para usarla en una declaración o sentencia SQL.
        //Función "strip_tags" la cual devuelve un string con las etiquetas HTML y PHP para ser eliminadas.
        $q=mysqli_real_escape_string($con,(strip_tags($_REQUEST['q'], ENT_QUOTES)));
        $aColumns=array('nombre_cliente'); //Columnas de busqueda
        $sTable="clientes"; //Tabla productos
        $sWhere="";

        if($_GET['q'] != "") {
            $sWhere="WHERE (";
            for ($i=0; $i<count($aColumns) ; $i++) {
            $sWhere .= $aColumns[$i]." LIKE '%".$q."%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }
        $sWhere.="ORDER BY nombre_cliente";
        include 'pagination.php'; //Archivo de paginación
        //Variables de paginacion
        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 10; //Número de registros que se quieren mostrar
		$adjacents  = 4; //Espacio entre página después del número de adyacentes
        $offset = ($page - 1) * $per_page;
        //Conteo del número total de filas en la tabla
        $count_query=mysqli_query($con, "SELECT count(*) AS numrows FROM $sTable $sWhere");
        $row=mysqli_fetch_array($count_query); //Obtención de fila de resultados del array numérico.
        $numrows=$row['numrows'];
        $total_pages=ceil($numrows/$per_page);
        $reload='./producto.php';
        //Consulta principal para buscar datos
        $sql="SELECT * FROM $sTable $sWhere LIMIT $offset,$per_page";
        $query=mysqli_query($con,$sql);
        //Bucle a través de datos obtenidos
        if($numrows>0) {
            ?>
            <div class="table-responsive">
                <table class="table">
                <tr class="info">
                <th>Nombres y Apellidos</th>
                <th>Número de cédula</th>
                <th>Número telefónico</th>
                <th>Dirección</th>
                <th>Correo electrónico</th>
                <th>Estado</th>
                <th>Dato de registro</th>
                <th>Acciones</th>
                </tr>
                <?php
            while($row=mysqli_fetch_array($query)) {
                $id_cliente=$row['id_cliente'];
				$nombre_cliente=$row['nombre_cliente'];
                $cedula_cliente=$row['cedula_cliente'];
                $telefono_cliente=$row['telefono_cliente'];
                $direccion_cliente=$row['direccion_cliente'];
                $email_cliente=$row['email_cliente'];
                $estado_cliente=$row['estado_cliente'];
                if($estado_cliente==1){$estado_cliente="Activo";}
                else{$estado_cliente="Inactivo";}
                $dato_agregado=date('d/m/Y', strtotime($row['dato_agregado']));
                ?>
                <input time type="hidden" value="<?php echo $nombre_cliente;?>"id="nombre_cliente<?php echo $id_cliente;?>">
                <input time type="hidden" value="<?php echo $cedula_cliente;?>"id="cedula_cliente<?php echo $id_cliente;?>">
                <input time type="hidden" value="<?php echo $telefono_cliente;?>"id="telefono_cliente<?php echo $id_cliente;?>">
                <input time type="hidden" value="<?php echo $direccion_cliente;?>"id="direccion_cliente<?php echo $id_cliente;?>">
                <input time type="hidden" value="<?php echo $email_cliente;?>"id="email_cliente<?php echo $id_cliente;?>">
                <input time type="hidden" value="<?php echo $estado_cliente;?>"id="estado_cliente<?php echo $id_cliente;?>">
                <input time type="hidden" value="<?php echo $dato_agregado;?>"id="dato_agregado<?php echo $id_cliente;?>">
                <tr>
                    <td><?php echo $nombre_cliente;?></td>
                    <td><?php echo $cedula_cliente;?></td>
                    <td><?php echo $telefono_cliente;?></td>
                    <td><?php echo $direccion_cliente;?></td>
                    <td><?php echo $email_cliente;?></td>
                    <td><?php echo $estado_cliente;?></td>
                    <td><?php echo $dato_agregado;?></td>
                    <td><span class="pull-right">
                    <a href="#", class='btn btn-warning' title='Editar datos de cliente' onclick="obtener_datos('<?php echo $id_cliente;?>');" data-toggle="modal" data-target="#myModal2"><i class=" glyphicon glyphicon-edit"></i></a>
                    <a href="#", class='btn btn-danger' title='Borrar datos de cliente' onclick="eliminar('<?php echo $id_cliente;?>');" data-toggle="modal" data-target="#myModal2"><i class=" glyphicon glyphicon-trash"></i></a>
                    </span></td>
                </tr>
                <?php  
            }
            ?>
            <tr>
                <td colspan=7><span class="pull-right"><?php echo paginate($reload, $page, $total_pages, $adjacents);
                ?></span></td>
            </tr>
                </table>
			</div>
            <?php
        }
    }
?>