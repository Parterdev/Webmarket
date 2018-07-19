<?php
    
    include('is_logged.php'); //Verificación de logueo
	/* Conexión a la Database */
	require_once ("../models/database.php");//Contiene las variables de configuracion para conectar a la base de datos
    require_once ("../models/connect.php");//Contiene funcion que conecta a la base de datos
    $action = (isset($_REQUEST['action']) && $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
    if(isset($_GET['id'])) {
        $numero_factura=intval($_GET['id']);
        $del1="DELETE FROM facturas WHERE numero_factura='".$numero_factura."'";
        $del2="DELETE FROM detalle_factura WHERE numero_factura='".$numero_factura."'";
        if($delete1=mysqli_query($con,$dele1) and $delete2=mysqli_query($con,$del2)){
            ?>
            <div class="alert alert-danger alert-dismissible fade in col-sm-3 animated bounceInDown" role="alert" style="position:fixed; top:70px; right:10px; z-index:10;"> 
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="text-center">Los datos han sido eliminados satisfactoriamente.</h4>
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
    }

    if($action == 'ajax'){
        //Función "mysqli_escape" que escapa caracteres especiales en una cadena para usarla en una declaración o sentencia SQL.
        //Función "strip_tags" la cual devuelve un string con las etiquetas HTML y PHP para ser eliminadas.
        $q=mysqli_real_escape_string($con,(strip_tags($_REQUEST['q'], ENT_QUOTES)));
        $sTable="facturas, clientes, usuarios"; //Tabla categorías
        $sWhere="";
        $sWhere.="WHERE facturas.id_cliente=clientes.id_cliente AND facturas.id_vendedor=usuarios.id_usuario";
        if($_GET['q'] != "") {
            $sWhere.="AND (clientes.nombre_cliente LIKE '%$q%' OR facturas.numero_factura LIKE '%$q%')";
        }

        $sWhere.=" ORDER BY facturas.id_factura DESC";
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
        $reload='./facturas.php';
        //Consulta principal para buscar datos
        $sql="SELECT * FROM $sTable $sWhere LIMIT $offset,$per_page";
        $query=mysqli_query($con,$sql);
        //Bucle a través de datos obtenidos
        if($numrows>0) {
            echo mysqli_error($con);
            ?>
            <div class="table-responsive">
                <table class="table">
                    <tr class="bg-primary">
                        <th>Número de factura</th>
					    <th>Fecha</th>
					    <th>Cliente</th>
                        <th>Nombre del vendedor</th>
					    <th>Estado</th>
                        <th class='text-right'>Total</th>
					    <th class='text-right'>Acciones</th>
                    </tr>
            <?php
            while($row=mysqli_fetch_array($query)) {
                $id_factura=$row['id_factura'];
                $numero_factura=$row['numero_factura'];
                $fecha=date('d/m/Y', strtotime($row['fecha_factura']));
                $cedula_cliente=$row['cedula_cliente'];
                $nombre_cliente=$row['nombre_cliente'];
                $telefono_cliente=$row['telefono_cliente'];
                $email_cliente=$row['email_cliente'];
                $nombre_vendedor=$row['nombre']." ".$row['apellido'];
                $estado_factura=$row['estado_factura'];
                $descripcion_categoria=$row['descripcion_categoria'];
                if($estado_factura==1){$text_estado="Factura pagada";$label_class='label-success';}
                else{$text_estado="Factura pendiente";$label_class='label-warning';}
                $total_venta=$row['total_venta'];
                ?>
                <tr>
                    <td><?php echo $numero_factura; ?></td>
                    <td><?php echo $fech; ?></td>
                    <td><a href="#" data-toggle="tooltip" data-placement="top" title="<i class='glyphicon glyphicon-phone'></i><?php echo $telefono_cliente;?><br><i class='glyphicon glyphicon-envelope'></i><?php echo $email_cliente;?>"><?php echo $nombre_cliente;?></a></td>
				    <td ><?php echo $nombre_vendedor; ?></td>
				    <td><span class="label <?php echo $label_class;?>"><?php echo $text_estado;?></span></td>
                    <td class='text-right'><?php echo_number_format ($total_venta,2); ?></td>
                    <td class='text-right'>
						<a href="editar_factura.php?id_factura=<?php echo $id_factura;?>" class='btn btn-warning' title='Editar factura'><i class="glyphicon glyphicon-edit"></i></a> 
                        <a href="#" class='btn btn-success' title='Imprimir' onclick="imprimir('<?php echo $id_factura; ?>')"><i class="glyphicon glyphicon-download"></i> </a>
						<a href="#" class='btn btn-danger' title='Borrar factura' onclick="eliminar('<?php echo $numero_factura; ?>')"><i class="glyphicon glyphicon-trash"></i> </a>
					</td>
                </tr>
                <?php
                }
                ?>
                <tr>
                    <td colspan=7><span class="pull-right">
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