<?php
    
    include('is_logged.php'); //Verificación de logueo
	/* Conexión a la Database */
	require_once ("../models/database.php");//Contiene las variables de configuracion para conectar a la base de datos
    require_once ("../models/connect.php");//Contiene funcion que conecta a la base de datos
    $action = (isset($_REQUEST['action']) && $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
    if(isset($_GET['id'])) {
        $id_categoria=intval($_GET['id']);
        $query=mysqli_query($con, "SELECT * FROM productos WHERE id_categoria='".$id_categoria."'");
        $count=mysqli_num_rows($query);
        if($count==0) {
            if($delete1=mysqli_query($con,"DELETE FROM categorias WHERE id_categoria='".$id_categoria."'")) {
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
		    <strong>Error!</strong>No se podrá eliminar esta categoría ya que existen productos vinculados a la misma.
		</div>
        <?php
    }
}

    if($action == 'ajax'){
        //Función "mysqli_escape" que escapa caracteres especiales en una cadena para usarla en una declaración o sentencia SQL.
        //Función "strip_tags" la cual devuelve un string con las etiquetas HTML y PHP para ser eliminadas.
        $q=mysqli_real_escape_string($con,(strip_tags($_REQUEST['q'], ENT_QUOTES)));
        $aColumns=array('nombre_categoria'); //Columnas de busqueda
        $sTable="categorias"; //Tabla categorías
        $sWhere="";

        if($_GET['q'] != "") {
            $sWhere="WHERE (";
            for ($i=0; $i<count($aColumns) ; $i++) {
            $sWhere .= $aColumns[$i]." LIKE '%".$q."%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }

        $sWhere.=" ORDER BY nombre_categoria DESC";
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
        $reload='./clientes.php';
        //Consulta principal para buscar datos
        $sql="SELECT * FROM $sTable $sWhere LIMIT $offset,$per_page";
        $query=mysqli_query($con,$sql);
        //Bucle a través de datos obtenidos
        if($numrows>0) {
            ?>
            <div class="table-responsive">
                <table class="table">
                    <tr class="">
                        <th>Nombre</th>
					    <th>Descripción</th>
					    <th>Agregado</th>
					    <th class='text-right'>Acciones</th>
                    </tr>
            <?php
            while($row=mysqli_fetch_array($query)) {
                $id_categoria=$row['id_categoria'];
				$nombre_categoria=$row['nombre_categoria'];
				$descripcion_categoria=$row['descripcion_categoria'];
                $date_added=date('d/m/Y', strtotime($row['dato_agregado']));
                ?>
                <tr>
                    <td><?php echo $nombre_categoria; ?></td>
				    <td ><?php echo $descripcion_categoria; ?></td>
				    <td><?php echo $date_added;?></td>
                    <td class='text-right'>
						<a href="#" class='btn btn-warning' title='Editar categoría' data-nombre='<?php echo $nombre_categoria;?>' data-descripcion='<?php echo $descripcion_categoria?>' data-id='<?php echo $id_categoria;?>' data-toggle="modal" data-target="#myModal2"><i class="glyphicon glyphicon-edit"></i></a> 
						<a href="#" class='btn btn-danger' title='Borrar categoría' onclick="eliminar('<?php echo $id_categoria; ?>')"><i class="glyphicon glyphicon-trash"></i> </a>
					</td>
                </tr>
                <?php
                }
                ?>
                <tr>
                    <td colspan=4><span class="pull-right">
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