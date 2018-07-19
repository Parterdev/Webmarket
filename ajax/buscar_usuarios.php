<?php
    
    include('is_logged.php'); //Verificación de logueo
	/* Conexión a la Database */
	require_once ("../models/database.php");//Contiene las variables de configuracion para conectar a la base de datos
    require_once ("../models/connect.php");//Contiene funcion que conecta a la base de datos
    $action = (isset($_REQUEST['action']) && $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
    if(isset($_GET['id'])) {
        $id_usuario=intval($_GET['id']);
        $query=mysqli_query($con, "SELECT * FROM usuarios WHERE id_usuario='".$id_usuario."'");
        $rw_user=mysqli_fetch_array($query);
        $count=$rw_user['id_usuario'];
        if($id_usuario!=1) {
            if($delete1=mysqli_query($con,"DELETE FROM usuarios WHERE id_usuario='".$id_usuario."'")) {
            ?>
            <div class="alert alert-success alert-dismissible" role="alert">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  <strong>Aviso!</strong>Los datos han sido eliminados exitosamente.
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
    } else{
        ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
		    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		    <strong>Error!</strong>No se podrá eliminar el usuario administrador del sistema.
		</div>
        <?php
    }
}

    if($action == 'ajax'){
        //Función "mysqli_escape" que escapa caracteres especiales en una cadena para usarla en una declaración o sentencia SQL.
        //Función "strip_tags" la cual devuelve un string con las etiquetas HTML y PHP para ser eliminadas.
        $q=mysqli_real_escape_string($con,(strip_tags($_REQUEST['q'], ENT_QUOTES)));
        $aColumns=array('nombre', 'apellido'); //Columnas de busqueda
        $sTable="usuarios"; //Tabla categorías
        $sWhere="";

        if($_GET['q'] != "") {
            $sWhere="WHERE (";
            for ($i=0; $i<count($aColumns) ; $i++) {
            $sWhere .= $aColumns[$i]." LIKE '%".$q."%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }

        $sWhere.=" ORDER BY id_usuario DESC";
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
        $reload='./usuario.php';
        //Consulta principal para buscar datos
        $sql="SELECT * FROM $sTable $sWhere LIMIT $offset,$per_page";
        $query=mysqli_query($con,$sql);
        //Bucle a través de datos obtenidos
        if($numrows>0) {
            ?>
            <div class="table-responsive">
                <table class="table">
                    <tr class="info">
                        <th>Identificador</th>
					    <th>Nombres</th>
					    <th>Usuario</th>
                        <th>Correo electronico</th>
                        <th>Fecha agregado</th>
					    <th class="pull-right">Acciones</th>
                    </tr>
            <?php
            while($row=mysqli_fetch_array($query)) {
                $id_usuario=$row['id_usuario'];
				$fullname=$row['nombre']." ".$row["apellido"];
                $user_name=$row['user_name'];
                $user_email=$row['user_email'];
                $date_added=date('d/m/Y', strtotime($row['date_added']));
                ?>
                <input type="hidden" value="<?php echo $row['nombre'];?>" id="nombres<?php echo $id_usuario;?>">
				<input type="hidden" value="<?php echo $row['apellido'];?>" id="apellidos<?php echo $id_usuario;?>">
				<input type="hidden" value="<?php echo $user_name;?>" id="usuario<?php echo $id_usuario;?>">
				<input type="hidden" value="<?php echo $user_email;?>" id="email<?php echo $id_usuario;?>">
                <tr>
                    <td><?php echo $id_usuario; ?></td>
				    <td><?php echo $fullname; ?></td>
				    <td><?php echo $user_name;?></td>
                    <td><?php echo $user_email;?></td>
                    <td><?php echo $date_added;?></td>
                    <td><span class="pull-right">
						<a href="#" class='btn btn-warning' title='Editar usuario' onclick="obtener_datos('<?php echo $id_usuario;?>');" data-toggle="modal" data-target="#myModal2"><i class="glyphicon glyphicon-edit"></i></a> 
					    <a href="#" class='btn btn-info' title='Cambiar contraseña' onclick="get_user_id('<?php echo $id_usuario;?>');" data-toggle="modal" data-target="#myModal3"><i class="glyphicon glyphicon-cog"></i></a>
					    <a href="#" class='btn btn-danger' title='Borrar usuario' onclick="eliminar('<? echo $id_usuario; ?>')"><i class="glyphicon glyphicon-trash"></i> </a></span>
					</td>
                </tr>
                <?php
                }
                ?>
                <tr>
                    <td colspan=9><span class="pull-right">
                    <?php
                        echo paginate($reload, $page, $total_pages, $adjacents);
                    ?></span></td>
                </tr>
                </table>
            </div>
            <?php
        }
    }
?>